<?php
// managing veeam backup repositories
namespace App\Http\Controllers\Repositories;

use App\Engine\Azure\ManagerAzure;
use App\Engine\Base\CallerApi;
use App\Engine\Veeam\ManagerVeeam;
use App\Models\AzureStorageAccount;
use App\Models\BacOrganization;
use App\Models\VeeamBackupRepository;
use App\Models\VeeamCloudCredentialsAccount;
use App\Models\VeeamObjectStorage;
use App\Models\VeeamProxy;
use App\Models\VeeamServer;
use App\Models\ViewOrganizationRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class BackupRepoController extends BaseController
{
    private $_managerVeeam;
    private $_managerAzure;

    //------------------------------------------------------------
    public function __construct()
    {
        $this->_caller = new CallerApi();
        $this->_managerAzure = new ManagerAzure();
        $this->_managerVeeam = new ManagerVeeam();
    }
    //------------------------------------------------------------
    //main interface request
    public function main()
    {
        $user = auth()->user();
        $arr = array('user' => $user);
        return response()->view("home", ["arr" => $arr]);
    }
    //------------------------------------------------------------
    //get  repositories view
    public function getRepositories()
    {
        return response()->view("repositories.main");
    }
    //------------------------------------------------------------
    //get user's repositories
    public function getRepositoriesContent()
    {
        $speRepositories = array();
        try {
            $allRepositories = $this->_managerVeeam->getVeeamRepository()['data'];
            $allStorages = $this->_managerVeeam->getVeeamObjectStorage()['data'];
            $results = ViewOrganizationRepository::where("organization_id", auth()->user()->organization->id)->get();
            foreach ($results as $orgRepo) {
                foreach ($allRepositories as $cloudRepo) {
                    if ($cloudRepo->id == $orgRepo->repository_guid) {
                        $cloudRepo->repo_kind = $orgRepo->repository_kind;
                        $cloudRepo->name = $orgRepo->repository_display_name;
                        array_push($speRepositories, $cloudRepo);
                    }
                }
            }
            foreach ($speRepositories as $spec) {
                foreach ($allStorages as $storage) {
                    if ($storage->id == $spec->objectStorageId) {
                        if (optional($storage)->sizeLimitGB) {
                            $spec->capacityBytes = $storage->sizeLimitGB;
                            $spec->freeSpaceBytes = $storage->freeSpaceBytes;
                        }
                        if (optional($storage)->usedSpaceBytes) {
                            $spec->usedSpaceBytes = $storage->usedSpaceBytes;
                        }
                    }
                }
            }
            return $speRepositories;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting  repositories,  ' . $ex->getMessage());
            return [];
        }
    }
    //------------------------------------------------------------
    //update repository
    public function updateRepository(Request $request)
    {
        try {
            $user = auth()->user();
            //-------------------------
            $repoCheck = ViewOrganizationRepository::where('repository_guid', '!=', $request->repositoryId)
                ->where('organization_id', $user->organization->id)
                ->where('repository_display_name', $request->repositoryName)
                ->get();
            if (count($repoCheck) > 0) {
                return response()->json(['message' => "Repository Name Already exists"], 500);
            }
            //-------------------------
            $repo = VeeamBackupRepository::where('guid', $request->repositoryId)->first();
            //-------------------------
            $this->_managerVeeam->updateVeeamRepository($repo->name, $request->repositoryName, $request->repositoryId);
            //-------------------------
            $repo->display_name = $request->repositoryName;
            $repo->save();
            //-------------------------
            return 200;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while executing update repository  ' . $ex->getMessage());
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
    //------------------------------------------------------------
    //create new repository
    public function createRepository(Request $request)
    {
        set_time_limit(0);
        try {
            $user = auth()->user();
            $repositoryName = $request->repositoryName;
            $repositoryKind = strtolower($request->repositoryKind);
            //-------------------------
            //get organization id
            $organization = $user->organization;
            $repoCheck = ViewOrganizationRepository::where("organization_id", $organization->id)->where("repository_display_name", $request->repositoryName)->select("*")->get();
            if (count($repoCheck) > 0) {
                return response()->json(['message' => "Repository Name Already exists"], 500);
            }
            //-------------------------
            //------ Create Azure Storage Account
            try {
                $allStorageAccounts = (array) $this->_managerAzure->getAllAzureStorageAccount($organization->azure_subscription_guid, $organization->azure_resource_group)['data']->value;
                //--------------------------------------------//
                //------ Check if number exist
                $number = count($allStorageAccounts) + 1;
                $number = $this->checkStorageAccountName($number, $organization);
                if ($number < 10) {
                    $number = '0' . $number;
                }
                //----------------------------------------------------//
                //storage kind and location
                $storageLocation = VeeamServer::where('id', $organization->server_id)->first()->region;
                $organizationName = $this->removeDomainFromName($organization->azure_tenant_name);
                $organizationName = strtolower($organizationName);
                $storageAccountName = 'co365' . substr($organizationName, 0, 16) . 'n' . $number;
                //-------------------------
                $containerName = strtolower($request->repositoryKind);
                $azureStorageAccountData = [
                    "storageAccountName" => $storageAccountName,
                    "azureSubscriptionId" => $organization->azure_subscription_guid,
                    "resourceGroupName" => $organization->azure_resource_group,
                    "storageLocation" => $storageLocation,
                ];
                $this->_managerAzure->createAzureStorageAccount($azureStorageAccountData);
                //-------------------------------------------------------------------//
                $azureStorageAccount = new AzureStorageAccount();
                $azureStorageAccount->organization_id = $organization->id;
                $azureStorageAccount->name = $storageAccountName;
                $azureStorageAccount->backup_container = $containerName;
                $azureStorageAccount->restore_container = $containerName . '-restore';
                $azureStorageAccount->save();
                //-------------------------------------------------------------------//
            } catch (Exception $e) {
                Log::log('error', 'Exception while creating azure storage account: ' . $e->getMessage());
                return response()->json(['message' => "Error while creating repository, please try again later"], 500);
            }
            //make a delay until azure server change creating operation to 'success'
            sleep(20);
            //get storage account access keys
            $storageKeys = $this->_managerAzure->getStorageAccountSharedAccessKeys($storageAccountName, $organization->azure_subscription_guid, $organization->azure_resource_group);
            $key1 = ($storageKeys->keys)[0]->value;
            $key2 = ($storageKeys->keys)[1]->value;
            //-------------------------
            //------ Create Azure Container
            try {
                $this->_managerAzure->createContainer($key1, $storageAccountName, $containerName);
                $this->_managerAzure->createContainer($key1, $storageAccountName, $containerName . '-restore');
            } catch (\Exception $ex) {
                Log::log('error', 'Exception while creating container  ' . $ex->getMessage());
                return response()->json(['message' => "Error while creating repository, please try again later"], 500);
            }
            //------ Create Veeam Cloud Credentials Account
            try {
                //----------------
                $data = $this->_managerVeeam->createCloudCredentialAccount($organization->azure_tenant_name, $storageAccountName, $key1);
                //----------------
                $cloudCredentialAccount = new VeeamCloudCredentialsAccount();
                $cloudCredentialAccount->storage_account_id = $azureStorageAccount->id;
                $cloudCredentialAccount->guid = $data["data"]->id;
                $cloudCredentialAccount->name = $data["data"]->userName;
                $cloudCredentialAccount->type = $data["data"]->accountType;
                $cloudCredentialAccount->save();
            } catch (\Exception $ex) {
                Log::log('error', 'Exception while creating Veeam Account  ' . $ex->getMessage());
                return response()->json(['message' => "Error while creating repository, please try again later"], 500);
            }
            //------ Create Veeam Folder
            try {
                $this->_managerVeeam->createVeeamFolder($repositoryName, $containerName, $cloudCredentialAccount->guid);
            } catch (\Exception $ex) {
                Log::log('error', 'Exception while creating Veeam Folder  ' . $ex->getMessage());
                //------ Delete Veeam Account
                $this->_managerVeeam->deleteCloudCredentialAccount($cloudCredentialAccount->guid);
                VeeamCloudCredentialsAccount::where("id", $cloudCredentialAccount->id)->delete();
                //---------------------------------//
                return response()->json(['message' => "Error while creating repository, please try again later"], 500);
            }
            //------ Create Veeam Object Storage
            try {
                $data = $this->_managerVeeam->createVeeamObjectStorage($storageAccountName, $repositoryName, $containerName, $cloudCredentialAccount->guid);
                //---------------------------------------//
                $objectStorage = new VeeamObjectStorage();
                $objectStorage->guid = $data["data"]->id;
                $objectStorage->name = $data["data"]->name;
                $objectStorage->cloud_credentials_account_id = $cloudCredentialAccount->id;
                $objectStorage->save();
                //---------------------------------------//
            } catch (\Exception $ex) {
                Log::log('error', 'Exception while creating Veeam Object Storage  ' . $ex->getMessage());
                //------ Delete Veeam Account
                $this->_managerVeeam->deleteCloudCredentialAccount($cloudCredentialAccount->guid);
                VeeamCloudCredentialsAccount::where("id", $cloudCredentialAccount->id)->delete();
                //----------------------------//
                Log::log('error', 'Exception while creating Veeam Object Storage');
                return response()->json(['message' => "Error while creating repository, please try again later"], 500);
            }
            //------ Create Veeam Password
            try {
                $encryptionKey = $this->_managerVeeam->createVeeamPassword($request->encryptionKey, "$storageAccountName - " . $organization->azure_tenant_name);
            } catch (\Exception $ex) {
                Log::log('error', 'Exception while creating Veeam Password ' . $ex->getMessage());
                //------ Delete Veeam Account
                $this->_managerVeeam->deleteCloudCredentialAccount($cloudCredentialAccount->guid);
                VeeamCloudCredentialsAccount::where("id", $cloudCredentialAccount->id)->delete();
                //----------------------------//
                //------ Delete Veeam Object Storage
                $this->_managerVeeam->deleteVeeamObjectStorage($objectStorage->guid);
                VeeamObjectStorage::where("id", $objectStorage->id)->delete();
                //----------------------------//
                return response()->json(['message' => "Error while creating repository, please try again later"], 500);
            }
            //------ Create Veeam Repository
            try {
                $proxy = VeeamProxy::getLessUsedProxy($organization->server_id);
                $cachePath = $proxy->cache_path . "\\\\" . $organization->veeam_organization_name . '\\\\' . getDataType($repositoryKind) . '\\\\' . $repositoryName;
                $repositoryData = [
                    "displayName" => $repositoryName,
                    "proxyId" => $proxy->guid,
                    "repositoryName" => $storageAccountName,
                    "objectStorageId" => $objectStorage->guid,
                    "encryptionKey" => $encryptionKey->id,
                    "cachePath" => $cachePath,
                ];
                $data = $this->_managerVeeam->createVeeamRepository($repositoryData);
                $cachePath = $proxy->cache_path . "\\" . $organization->veeam_organization_name . '\\' . getDataType($repositoryKind) . '\\' . $repositoryName;

                $repository = new VeeamBackupRepository();
                $repository->object_storage_id = $objectStorage->id;
                $repository->proxy_id = $proxy->id;
                $repository->guid = $data["data"]->id;
                $repository->name = $storageAccountName;
                $repository->display_name = $repositoryName;
                $repository->encryption_password_guid = $encryptionKey->id;
                $repository->encryption_password_hint = $encryptionKey->description;
                $repository->kind = $repositoryKind;
                $repository->cache_path = $cachePath;
                $repository->save();
            } catch (\Exception $ex) {
                Log::log('error', 'Exception while creating Repository  ' . $ex->getMessage());
                //------ Delete Veeam Password
                $this->_managerVeeam->deleteVeeamPassword($encryptionKey->id);
                //------ Delete Veeam Account
                $this->_managerVeeam->deleteCloudCredentialAccount($cloudCredentialAccount->guid);
                VeeamCloudCredentialsAccount::where("id", $cloudCredentialAccount->id)->delete();
                VeeamObjectStorage::where("id", $objectStorage->id)->delete();
                //------ Delete Veeam Object Storage
                $this->_managerVeeam->deleteVeeamObjectStorage($objectStorage->guid);
                //----------------------------//
                return response()->json(['message' => "Error while creating repository, please try again later"], 500);
            }
            return 200;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while creating Repository  ' . $ex->getMessage());
            return response()->json(['message' => "Error while creating repository, please try again later"], 500);
        }
    }
    //------------------------------------------------------------
    private function checkStorageAccountName($number, BacOrganization $organization)
    {
        $azureTenantName = strtolower($this->removeDomainFromName($organization->azure_tenant_name));
        $storageAccountName = 'co365' . substr($azureTenantName, 0, 16) . 'n' . $number;
        if ($number < 10) {
            $storageAccountName = 'co365' . substr($azureTenantName, 0, 16) . 'n0' . $number;
        }
        try {
            $this->_managerAzure->getAzureStorageAccount($organization->azure_subscription_guid, $organization->azure_resource_group, $storageAccountName)['data'];
            $result = true;
        } catch (Exception $e) {
            $result = false;
        }
        if ($result) {
            return $this->checkStorageAccountName($number + 1, $organization);
        } else {
            return $number;
        }

    }
    //------------------------------------------------------------
    private function removeDomainFromName($name)
    {
        return explode(".", $name)[0];
    }
    //------------------------------------------------------------
}
