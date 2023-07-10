<?php
// Called By Web job
// Delete Expired Files in Azure
namespace App\Console\Commands;

use App\Engine\Azure\ManagerAzure;
use App\Models\AzureStorageAccount;
use App\Models\BacEDiscoveryJob;
use App\Models\BacOrganization;
use App\Models\BacRestoreHistory;
use App\Models\BacTempBlobFile;
use App\Models\ViewOrganizationBackupJob;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blob:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Expired Files in Azure Blob';

    protected $timeout = 0;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //---------------------------//
        $_managerAzure = new ManagerAzure();
        //---------------------------//
        $now = Carbon::now();
        $data = BacRestoreHistory::where('status', '<>', 'Expired')->whereDate('expiration_time', '<', $now->toDateTimeString())->get();
        foreach ($data as $history) {
            //--------------------
            if (substr_count($history->sub_type, 'Export')) {
                foreach ($history->details as $item) {
                    //-------------------//
                    $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $history->backup_job_id)->first();
                    $organization = BacOrganization::where("id", $backupJobData->organization_id)->first();
                    session()->put("organization_id", $organization->id);
                    $storageKeys = $_managerAzure->getStorageAccountSharedAccessKeys($backupJobData->storage_account_name, $organization->azure_subscription_guid, $organization->azure_resource_group);
                    $storageAccountKey = ($storageKeys->keys)[0]->value;
                    $params = [
                        "storageAccount" => $backupJobData->storage_account_name,
                        "accountKey" => $storageAccountKey,
                        "containerName" => $backupJobData->restore_container,
                        "blobName" => $item->exported_file_name,
                    ];
                    //-------------------//
                    try {
                        $blobDetails = $_managerAzure->getBlobDetails($backupJobData->storage_account_name, $backupJobData->restore_container, $item->exported_file_name, $storageAccountKey);
                        if ($blobDetails) {
                            $_managerAzure->deleteBlob($params);
                        }

                    } catch (Exception $e) {
                        Log::log('error', 'Exception while Deleting Blob');
                    }
                }
            }
            //--------------------
            $history->status = "Expired";
            $history->save();
        }
        //---------------------------//
        $deleteFiles = BacTempBlobFile::whereDate('expiration_time', '<', $now->toDateTimeString())->get();
        foreach ($deleteFiles as $file) {
            //--------------------
            $storageAccount = AzureStorageAccount::where("id", $file->storage_account_id)->first();
            $organization = BacOrganization::where("id", $storageAccount->organization_id)->first();
            session()->put("organization_id",  $organization->id);
            $storageKeys = $_managerAzure->getStorageAccountSharedAccessKeys($storageAccount->name, $organization->azure_subscription_guid, $organization->azure_resource_group);
            $storageAccountKey = ($storageKeys->keys)[0]->value;

            $params = [
                "storageAccount" => $storageAccount->name,
                "accountKey" => $storageAccountKey,
                "containerName" => $storageAccount->restore_container,
                "blobName" => $file->file_name,
            ];
            try {
                //-------------------//
                $blobDetails = $_managerAzure->getBlobDetails($storageAccount->name, $storageAccount->restore_container, $file->file_name, $storageAccountKey);
                if ($blobDetails) {
                    $_managerAzure->deleteBlob($params);
                }

                //--------------------
                $file->delete();
            } catch (Exception $e) {
                Log::log('error', 'Exception while Deleting Blob ' . json_encode($data));
            }
        }
        //---------------------------//
        $data = BacEDiscoveryJob::where('status', '<>', 'Expired')->whereDate('expiration_time', '<', $now->toDateTimeString())->get();
        foreach ($data as $job) {
            //--------------------
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $job->backup_job_id)->first();
            $organization = BacOrganization::where("id", $backupJobData->organization_id)->first();
            session()->put("organization_id", $organization->id);
            $storageKeys = $_managerAzure->getStorageAccountSharedAccessKeys($backupJobData->storage_account_name, $organization->azure_subscription_guid, $organization->azure_resource_group);
            $storageAccountKey = ($storageKeys->keys)[0]->value;
            try {
                //--------------------------------//
                if ($_managerAzure->getTable($organization->azure_subscription_guid, $backupJobData->storage_account_name, $organization->azure_resource_group, $backupJobData->backup_job_kind.$job->id)) {
                    $_managerAzure->deleteTable($organization->azure_subscription_guid, $backupJobData->storage_account_name, $organization->azure_resource_group, $backupJobData->backup_job_kind . $job->id);
                }
                //--------------------------------//
            } catch (Exception $ex) {
                Log::log('error', 'Exception While Deleting Expired table ' . $ex->getMessage());
            }
            //--------------------
            $job->status = "Expired";
            $job->save();
        }
        //---------------------------//
    }
    //---------------------------//
}
