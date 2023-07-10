<?php

namespace App\Engine\Veeam;

use App\Engine\Base\CallerApi;
use App\Engine\Base\Manager;
use App\Engine\Veeam\UrlsVeeam;
use App\Models\BacRestoreHistoryDetail;
use Exception;
use Illuminate\Support\Facades\Log;

class ManagerVeeam
{
    public $_urlManager;
    private $_caller;

    public function __construct()
    {
        $this->_urlManager = new UrlsVeeam();
        $this->_caller = new CallerApi();
    }
    //-------------------------------------------------------//
    public function login($server)
    {
        //get url request login in veeam api
        $url = $this->_urlManager->loginUrl($server->url);
        $params = [
            'grant_type' => "password",
            'username' => $server->username,
            'password' => $server->password,
        ];
        //call request
        $data = $this->_caller->post($url, $params, []);
        return $data["data"];
    }
    //-------------------------------------------------------//
    public function getServerToken($userId = '')
    {
        if (!$userId) {
            $userId = auth()->user()->id;
        }
    }
    //-------------------------------------------------------//
    public function lessUserServerLogin($serverInfo)
    {
        //get url request login in partner center api
        $url = $serverInfo->url . "/Token";
        $params = [
            'grant_type' => "password",
            'username' => $serverInfo->username,
            'password' => $serverInfo->password,
        ];
        //call request
        $data = $this->_caller->post($url, $params, []);
        return $data["data"]->access_token;
    }
    //-------------------------------------------------------//
    public function generateDeviceCode($serverInfo)
    {
        $token = "Bearer " . $this->lessUserServerLogin($serverInfo);
        $url = $this->_urlManager->getGenerateDeviceCodeUrl($serverInfo->url);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "targetRegion" : "Worldwide"
            }
        ';
        $data = $this->_caller->post_($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {

            return $data;
        } else {
            Log::log('error', 'Exception while getting device code  ' . json_encode($data));
            throw new \Exception('Error getting device code');
        }
    }
    //-------------------------------------------------------//
    public function createOrganizationModernApp($serverInfo, $userCode, $applicationName)
    {
        $token = "Bearer " . $this->lessUserServerLogin($serverInfo);
        $url = $this->_urlManager->createOrganizationUrl($serverInfo->url);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $body = '
        {
            "isTeamsOnline": true,
            "isExchangeOnline": true,
            "exchangeOnlineSettings": {
                "useApplicationOnlyAuth": true,
                "useMfa": true,
                "applicationCertificate": "' . config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE") . '",
                "applicationCertificatePassword": "' . config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE_PASSWORD") . '"
            },
            "isSharePointOnline": true,
            "sharePointOnlineSettings": {
                "sharePointSaveAllWebParts": true,
                "useApplicationOnlyAuth": true,
                "useMfa": true,
                "applicationCertificate": "' . config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE") . '",
                "applicationCertificatePassword": "' . config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE_PASSWORD") . '"
            },
            "userCode": "' . $userCode . '",
            "type": "Office365",
            "newApplicationName": "Bactopus Authentication Application - Main",
         }
        ';

        $data = $this->_caller->post_($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while creating Organization   ' . json_encode($data));
            if (optional(optional($data)['data'])->message) {
                throw new \Exception($data['data']->message);
            } else {
                throw new \Exception('Error Creating Organization');
            }
        }
    }
    //-------------------------------------------------------//
    public function deleteOrganization($serverInfo, $organizationId)
    {
        $token = "Bearer " . $this->login($serverInfo)->access_token;
        $url = $this->_urlManager->deleteOrganizationUrl($serverInfo->url, $organizationId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->delete($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while deleting veeam organization   ' . json_encode($data));
            throw new \Exception('Error Deleting Veeam Organization');
        }
    }
    //-------------------------------------------------------//
    public function setOrganizationModernApp($request, $organizationId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->setOrganizationUrl($organizationId);

        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $body = '
        {
            "type": "Office365",
            "region": "Worldwide",
            "isExchangeOnline": true,
            "isSharePointOnline": true,
            "isTeamsOnline": true,
            "ExchangeOnlineSettings": {
                "account": "' . $request["usernameBasic"] . '",
                "Password":"' . $request["apppasswordBasic"] . '",
                "grantAdminAccess": "true",
                "useMfa": false,
                "useCustomVeeamAADApplication": false
              },
            "sharePointOnlineSettings": {
              "account": "' . $request["usernameBasic"] . '",
              "Password":"' . $request["apppasswordBasic"] . '",
              "grantAdminAccess": "true",
              "useMfa": false,
              "useCustomVeeamAADApplication": false
            }
         }
        ';
        $data = $this->_caller->put($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Editing basic Organization ' . json_encode($data));
            if (optional(optional($data)['data'])->message) {
                throw new \Exception($data['data']->message);
            } else {
                throw new \Exception('Error Editing Organization');
            }
        }
    }
    //-------------------------------------------------------//
    //create veeam account and store it in DB
    public function createCloudCredentialAccount($azureTenantName, $azureStorageAccountName, $azureStorageAccountKey)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->createAccountUrl();
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $body = '
        {
            "accountType"   :   "azureBlobAccount"  ,
            "username"      :   "' . $azureStorageAccountName . '"  ,
            "password"      :   "' . $azureStorageAccountKey . '"  ,
            "description"   :   "' . $azureTenantName . '"
        }
        ';
        $data = $this->_caller->post_($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while creating veeam cloud credentials account   ' . json_encode($data));
            throw new \Exception('Error Creating Veeam Account');
        }
    }
    //delete veeam account
    public function deleteCloudCredentialAccount($cloudCredentialAccountId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->deleteAccountUrl($cloudCredentialAccountId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->delete($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while deleting veeam cloud credentials account   ' . json_encode($data));
            throw new \Exception('Error Deleting Veeam Account');
        }
    }

    //create veeam folder and store it in DB
    public function createVeeamFolder($veeamFolderName, $azureContainerName, $cloudCredentialAccountId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->createFolderUrl($azureContainerName, $cloudCredentialAccountId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $body = '
         {
             "name":"' . $veeamFolderName . '"
         }
         ';
        $data = $this->_caller->post_($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while creating veeam folder   ' . json_encode($data));
            throw new \Exception('Error Creating Veeam Folder');
        }
    }

    //create veeam repository and store it in DB
    public function createVeeamRepository($params)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->createRepositoryUrl();
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $body = '
         {
            "name": "' . $params["repositoryName"] . '",
            "proxyId": "' . $params["proxyId"] . '",
            "retentionPeriodType": "Yearly",
            "yearlyRetentionPeriod": "keep",
            "retentionFrequencyType": "Daily",
            "dailyTime": "08:00:00",
            "description": "' . $params["displayName"] . '",
            "retentionType": "ItemLevel",
            "dailyType": "Weekends",
            "attachUsedRepository": "true",
            "ObjectStorageId": "' . $params["objectStorageId"] . '",
            "ObjectStorageCachePath": "' . $params["cachePath"] . '",
            "ObjectStorageEncryptionEnabled": true,
            "EncryptionKeyId": "' . $params["encryptionKey"] . '"
        }
        ';

        $data = $this->_caller->post_($url, $body, $header);

        $res = -1;
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while creating veeam Repository   ' . json_encode($data));
            throw new \Exception('Error Creating Veeam Repository');
        }
    }

    //update veeam repository and store it in DB
    public function updateVeeamRepository($veeamRepositoryName, $veeamRepositoryDisplayName, $veeamRepositoryId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->updateRepositoryUrl($veeamRepositoryId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $body = '
        {
            "name": "' . $veeamRepositoryName . '",
            "description": "' . $veeamRepositoryDisplayName . '",
            "retentionPeriodType": "Yearly",
            "yearlyRetentionPeriod": "keep",
            "retentionFrequencyType": "Daily",
            "dailyTime": "08:00:00",
            "retentionType": "ItemLevel",
            "dailyType": "Weekends",
            "attachUsedRepository": "true",
            "ObjectStorageEncryptionEnabled": true
        }
         ';
        $data = $this->_caller->put($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Updating veeam Repository   ' . json_encode($data));
            throw new \Exception('Error Updating Veeam Repository');
        }
    }

    //delete veeam repository
    public function deleteVeeamRepository($veeamRepositoryId)
    {

        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->deleteRepositoryUrl($veeamRepositoryId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->delete($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while deleting veeam Repository   ' . json_encode($data));
            throw new \Exception('Error Deleting Veeam Repository');
        }
    }

    //get veeam repository (pass empty string to get all repositories)
    public function getVeeamRepository($veeamRepositoryId = '')
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getRepositoryUrl($veeamRepositoryId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting veeam Repositories   ' . json_encode($data));
            throw new \Exception('Error Getting Veeam Repositories');
        }
    }

    //create veeam Object Storage and store it in DB
    public function createVeeamObjectStorage($veeamObjectStorageName, $folderName, $azureContainerName, $cloudCredentialAccountId)
    {

        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->createObjectStorageUrl();
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $body = '
            {
                "Name": "' . $veeamObjectStorageName . '",
                "Description": "' . $folderName . '",
                "Type": "AzureBlob",
                "accountId": "' . $cloudCredentialAccountId . '",
                "azureContainer": {
                    "name": "' . $azureContainerName . '",
                    "regionType": "Global"
                },
                "AzureFolder": "' . $folderName . '",
                "SizeLimitEnabled": "false"
            }
        ';
        $data = $this->_caller->post_($url, $body, $header);
        $res = [];
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while creating veeam Object Storage   ' . json_encode($data));
            throw new \Exception($data['data']->message);
        }
    }

    //get veeam Object Storage
    public function getVeeamObjectStorage($veeamObjectStorageId = '')
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getObjectStorageUrl($veeamObjectStorageId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting veeam Object Storage   ' . json_encode($data));
            throw new \Exception('Error Getting Veeam Object Storage');
        }
    }

    //update veeam Object Storage and store it in DB
    public function updateVeeamObjectStorage($veeamObjectStorageId)
    {

        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->updateObjectStorageUrl($veeamObjectStorageId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "Description": "updated"
           }
         ';
        $data = $this->_caller->put($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Updating veeam Object Storage   ' . json_encode($data));
            throw new \Exception('Error Updating Veeam Object Storage');
        }
    }

    //delete veeam Object Storage
    public function deleteVeeamObjectStorage($veeamObjectStorageId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->deleteObjectStorageUrl($veeamObjectStorageId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->delete($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while deleting veeam Object Storage   ' . json_encode($data));
            throw new \Exception('Error Deleting Veeam Object Storage');
        }
    }
    //create veeam Encryption password
    public function createVeeamPassword($password, $description)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->createPasswordUrl();
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $body = '
        {
            "password" : "' . $password . '",
            "description" : "' . $description . '"
        }
        ';
        $data = $this->_caller->post_($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data['data'];
        } else {
            Log::log('error', 'Exception while creating Veeam Encryption Password   ' . json_encode($data));
            throw new \Exception('Error Creating Veeam Encryption Password');
        }
    }
    //delete veeam password
    public function deleteVeeamPassword($encryptionKey)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->deletePasswordUrl($encryptionKey);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->delete($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while deleting password   ' . json_encode($data));
            throw new \Exception('Error Deleting Password');
        }
    }
    //get veeam Organization Jobs
    public function getOrganizationBackUpJobs($organization_guid, $jobId = '')
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOrganizationJobsUrl($organization_guid, $jobId);

        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {

            return $data;
        } else {
            Log::log('error', 'Exception while getting backup jobs   ' . json_encode($data));
            throw new \Exception('Error getting backup jobs ');
        }
    }
    //get veeam Organization Users
    public function getOrganizationUsers($organization_guid, $limit = 10000, $offset = 0, $setId = null)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOrganizationUsersUrl($organization_guid, $limit, $offset, $setId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Organization Users   ' . json_encode($data));
            throw new \Exception('Error getting Organization Users');
        }
    }
    //get veeam Organization User Onedrives
    public function getOrganizationUserOnedrives($organization_guid, $organizationUserId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOrganizationUserOnedrivesUrl($organization_guid, $organizationUserId);
        $url = $this->_urlManager->getOrganizationUserOnedrivesUrl($organization_guid, $organizationUserId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Organization User Onedrives ' . json_encode($data));
            throw new \Exception('Error getting Organization User Onedrives');
        }
    }
    //get veeam Organization Groups
    public function getOrganizationGroups($organization_guid, $limit = 1000, $offset = 0, $setId = null)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOrganizationGroupsUrl($organization_guid, $limit, $offset, $setId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Organization Groups   ' . json_encode($data));
            throw new \Exception('Error getting Organization Groups ');
        }
    }
    //get veeam Organization Group Members
    public function getOrganizationGroupMembers($organization_guid, $groupId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOrganizationGroupMembersUrl($organization_guid, $groupId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Organization Group Members ' . json_encode($data));
            throw new \Exception('Error getting Organization Group Members');
        }
    }

    //get veeam Organization Teams
    public function getOrganizationTeams($organization_guid, $limit = 10000, $offset = 0)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOrganizationTeamsUrl($organization_guid);

        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Organization Teams   ' . json_encode($data));
            throw new \Exception('Error getting Organization Teams ');
        }
    }
    //get veeam Organization Sites
    public function getOrganizationSites($organization_guid, $limit = 50, $offset = 0)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOrganizationSitesUrl($organization_guid, $limit, $offset);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Organization Sites   ' . json_encode($data));
            throw new \Exception('Error getting Organization Sites ');
        }
    }
    //get veeam backup job
    public function getVeeamJob($backupJobGuid)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getJobsUrl($backupJobGuid);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting  backup job   ' . json_encode($data));
            throw new \Exception('Error getting veeam backup job');
        }
    }
    //get veeam backup job
    public function getVeeamJobSelectedItems($backupJobGuid)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getJobSelectedItemsUrl($backupJobGuid);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->get($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting  backup job\'s selected items  ' . json_encode($data));
            throw new \Exception('Error getting backup job\'s selected items ');
        }
    }
    //manage veeam backup job (enable, disable, start, stop)
    public function manageVeeamJobs($backupJobGuid, $action)
    {

        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->manageJobsUrl($backupJobGuid);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $body = '
         {
             "' . $action . '"   :   ""
         }
         ';
        $data = $this->_caller->post_($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {

            return $data;
        } else {
            Log::log('error', 'Exception while managing  backup job   ' . json_encode($data));
            throw new \Exception($data['data']->message);
        }
    }
    //create Backup job
    public function createVeeamBackupJob($organization_guid, $backupData)
    {

        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->createJobsUrl($organization_guid);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $body = json_encode($backupData);
        $data = $this->_caller->post_($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data["data"];
        } else {
            Log::log('error', 'Exception while creating  backup job   ' . json_encode($data));
            throw new \Exception($data['data']->message);
        }
    }

    //Add Items to Backup job
    public function addVeeamBackupJobItems($jobId, $items)
    {

        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->addJobItemsUrl($jobId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $body = json_encode($items);
        $data = $this->_caller->post_($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while creating  backup job   ' . json_encode($data));
            throw new \Exception($data['data']->message);
        }
    }

    //edit Backup job
    public function editVeeamBackupJob($jobId, $backupData)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->editJobsUrl($jobId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $body = json_encode($backupData);

        $data = $this->_caller->put($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while editing backup job ' . json_encode($data));
            throw new \Exception($data['data']->message);
        }
    }
    //delete backup Job
    public function deleteVeeamJob($jobId)
    {

        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->deleteJobsUrl($jobId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->delete($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {

            return $data;
        } else {
            Log::log('error', 'Exception while deleting veeam backup job   ' . json_encode($data));
            throw new \Exception('Error Deleting Veeam Backup Job');
        }
    }

    //get veeam backup job sessions
    public function getVeeamJobSessions($backupJobGuid, $limit = 10000)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getJobSessionsUrl($backupJobGuid, $limit);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {

            return $data;
        } else {
            Log::log('error', 'Exception while getting  backup job\'s sessions  ' . json_encode($data));
            throw new \Exception('Error getting backup job\'s sessions ');
        }
    }
    //-----------------------------------------------------------//
    //Get Veeam Backup All Job Sessions
    public function getVeeamAllJobSessions()
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getAllJobSessionsUrl();
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {

            Log::log('error', 'Exception while getting  backup job\'s sessions  ' . json_encode($data));
            throw new \Exception('Error getting backup job\'s sessions ');
        }
    }
    //-----------------------------------------------------------//
    //get information about job session
    public function getVeeamJobSessionLogItems($sessionId)
    {

        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getJobSessionLogItemsUrl($sessionId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->get($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {

            return $data;
        } else {
            Log::log('error', 'Exception while getting  backup job\'s session log Items  ' . json_encode($data));
            throw new \Exception('Error getting backup job\'s session log Items ');
        }
    }
    //-----------------------------------------------------------//
    //get Restore Session by id
    public function getRestoreSession($sessionId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getRestoreSessionUrl($sessionId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->get($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {

            return $data;
        } else {
            Log::log('error', 'Exception while getting restore session  ' . json_encode($data));
            throw new \Exception('Error getting restore session ');
        }
    }
    //-----------------------------------------------------------//
    public function generateRestoreSessionDeviceCode($sessionId, $applicationId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getRestoreSessionDeviceCodeUrl($sessionId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "targetApplicationId" : "' . $applicationId . '"
            }
        ';
        $data = $this->_caller->post_($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {

            return $data;
        } else {
            Log::log('error', 'Exception while getting restore session device code  ' . json_encode($data));
            throw new \Exception('Error getting restore session device code');
        }
    }
    //-----------------------------------------------------------//
    //get Restore Session by id
    public function getRestoreSessionEvents($sessionId)
    {

        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getRestoreSessionEventsUrl($sessionId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {

            return $data;
        } else {
            Log::log('error', 'Exception while getting restore session  ' . json_encode($data));
            throw new \Exception('Error getting restore session ');
        }
    }
    //-----------------------------------------------------------//
    //create Restore Session
    public function createRestoreSession($organization_guid, $datetime, $type, $showDeleted, $showVersions)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->createRestoreSessionUrl($organization_guid);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $body = '
        {
            "explore": {
             "datetime": "' . $datetime . '",
             "type": "' . $type . '",
             "showAllVersions": ' . ($showVersions ? "true" : "false") . ',
             "showDeleted": ' . ($showDeleted ? "true" : "false") . '
            }
           }
        ';
        $data = $this->_caller->post_($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while creating restore session   ' . json_encode($data));
            throw new \Exception($data['data']->message);
        }
    }
    //-----------------------------------------------------------//
    //create Restore Session For Job
    public function createJobRestoreSession($jobId, $datetime, $type, $showDeleted, $showVersions)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->createJobRestoreSessionUrl($jobId);

        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $body = '
        {
            "explore": {
             "datetime": "' . $datetime . '",
             "type": "' . $type . '",
             "ShowAllVersions": ' . ($showVersions ? "true" : "false") . ',
             "ShowDeleted": ' . ($showDeleted ? "true" : "false") . '
            }
           }
        ';
        $data = $this->_caller->post_($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data['data'];
        } else {
            Log::log('error', 'Exception while creating  restore session   ' . json_encode($data));
            throw new \Exception($data['data']->message);
        }
    }
    //-----------------------------------------------------------//
    //stop Restore Session
    public function stopRestoreSession($sessionId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->stopRestoreSessionUrl($sessionId);

        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $body = '{"stop": null}';

        $data = $this->_caller->post_($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while stopping  restore session   ' . json_encode($data));
            throw new \Exception($data['data']->message);
        }
    }
    //-----------------------------------------------------------//
    //Get Restore Exchange Mailboxes
    public function getExchangeMailBoxes($sessionId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getExchangeMailBoxesUrl($sessionId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Getting MailBoxes  ' . json_encode($data));
            throw new \Exception('Error Getting MailBoxes');
        }
    }
    //-----------------------------------------------------------//
    //Get Exchange Mailbox Folders
    public function getMailBoxFolders($sessionId, $mailboxId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getMailBoxFoldersUrl($sessionId, $mailboxId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Getting MailBox Folders  ' . json_encode($data));
            throw new \Exception('Error Getting MailBox Folders');
        }
    }
    //-----------------------------------------------------------//
    //Get Exchange Mailbox Folder
    public function getMailBoxFolder($sessionId, $mailboxId, $folderId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getMailBoxFolderUrl($sessionId, $mailboxId, $folderId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Getting MailBox Folders  ' . json_encode($data));
            throw new \Exception('Error Getting MailBox Folders');
        }
    }
    //-----------------------------------------------------------//
    //Get Exchange Mailbox Items
    public function getMailBoxItems($sessionId, $mailboxId, $folderId = '', $offset, $limit = 1)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getMailBoxItemsUrl($sessionId, $mailboxId, $folderId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Getting MailBox Items ' . json_encode($data));
            throw new \Exception('Error Getting MailBox Items');
        }
    }
    //-----------------------------------------------------------//
    //Export Mailbox
    public function exportMailBoxToPST($sessionId, $mailboxIds, $options, $callbacks)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $body = '
        {
           "exportToPst": {
               "enablePstSizeLimit": ' . $options->enablePstSizeLimit . ',
               ' . (optional($options)->sizeLimit ? ('"pstSizeLimitBytes": ' . (($options->sizeLimit ?? 1) * 1024 * 1024 * 1024)) : '') . '
           }
          }
       ';
        $handlersArr = [];
        $index = 0;
        foreach ($mailboxIds as $mailboxId) {
            array_push($handlersArr, [
                "url" => $this->_urlManager->getRestoreMailboxUrl($sessionId, $mailboxId),
                "callback" => $callbacks[$index],
                "body" => $body,
            ]);
            $index++;
        }
        $header = [
            "Authorization: $token",
            "Accept: application/octet-stream",
            "Content-Type: application/json",
            "Connection: keep-alive",
        ];

        $response = $this->_caller->postStreamMulti($handlersArr, $header);
        return $response;
    }
    //-----------------------------------------------------------//
    //Export Mailbox Folder
    public function exportMailBoxFolderToPST($sessionId, $mailboxIds, $folderIds, $options, $callbacks)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $body = '
         {
            "exportToPst": {
                "enablePstSizeLimit": ' . $options->enablePstSizeLimit . ',
                ' . (optional($options)->sizeLimit ? ('"pstSizeLimitBytes": ' . (($options->sizeLimit ?? 1) * 1024 * 1024 * 1024)) : '') . '
            }
           }
        ';
        $handlersArr = [];
        $index = 0;
        foreach ($mailboxIds as $mailboxId) {
            array_push($handlersArr, [
                "url" => $this->_urlManager->getRestoreMailboxFolderUrl($sessionId, $mailboxId, $folderIds[$index]),
                "callback" => $callbacks[$index],
                "body" => $body,
            ]);
            $index++;
        }
        $header = [
            "Authorization: $token",
            "Accept: application/octet-stream",
            "Content-Type: application/json",
            "Connection: keep-alive",
        ];
        $response = $this->_caller->postStreamMulti($handlersArr, $header);
        return $response;
    }
    //-----------------------------------------------------------//
    //Export Mailbox Items to PST
    public function exportMailBoxFolderItemsToPST($sessionId, $mailboxIds, $items, $callbacks)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $handlersArr = [];
        $index = 0;
        foreach ($mailboxIds as $mailboxId) {
            array_push($handlersArr, [
                "url" => $this->_urlManager->getRestoreMailboxFolderItemsUrl($sessionId, $mailboxId),
                "callback" => $callbacks[$index],
                "body" => '
                        {
                            "exportToPst": {
                                "items": ' . $items[$index] . ',
                                "enablePstSizeLimit": true,
                                "pstSizeLimitBytes": 1073741824
                            }
                        }
                    ',
            ]);
            $index++;
        }
        $header = [
            "Authorization: $token",
            "Accept: application/octet-stream",
            "Content-Type: application/json",
            "Connection: keep-alive",
        ];

        $response = $this->_caller->postStreamMulti($handlersArr, $header);
        return $response;
    }
    //-----------------------------------------------------------//
    //Export Mailbox Items to MSG
    public function exportMailBoxFolderItemsToMSG($sessionId, $mailboxId, $items, $callbacks = null)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getRestoreMailboxFolderItemsUrl($sessionId, $mailboxId);
        $header = [
            "Authorization: $token",
            "Accept: application/octet-stream",
            "Content-Type: application/json",
            "Connection: keep-alive",
        ];

        if ($callbacks) {
            $handlersArr = [];
            $index = 0;
            foreach($mailboxId as $singleMailboxId){
                array_push($handlersArr, [
                    "url" => $this->_urlManager->getRestoreMailboxFolderItemsUrl($sessionId, $singleMailboxId),
                    "callback" => $callbacks[$index],
                    "body" => '
                        {
                            "savetoMsg": {
                            "items": ' . $items[$index] . '
                            }
                        }
                        ',
                ]);
                $index++;
            }
            $response = $this->_caller->postStreamMulti($handlersArr, $header);
            return $response;
        } else {
            $header = [
                "Authorization" => "$token",
                "Accept" => "application/octet-stream",
                "Content-Type" => "application/json",
            ];
            $body = '
                {
                   "savetoMsg": {
                     "items": ' . $items . '
                   }
                  }
                ';
            $response = $this->_caller->postStreamDirect($url, $body, $header);
            $status = $response['status'];
            if ($status == 200 || $status == 201 || $status == 202 || $status == 204) {
                $headers = $response['headers'];
                $body = $response['body'];
                return ['headers' => $headers, "body" => (object) $response['body']];
            } else {
                Log::log('error', 'Exception while Exporting MailBox Items ' . json_encode($response));
                throw new \Exception('Error Exporting MailBox Item');
            }
        }

    }
    //-----------------------------------------------------------//
    //Export Mailbox Item to MSG
    public function exportMailBoxFolderItemToMSG($sessionId, $mailboxId, $itemId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getRestoreMailboxFolderItemUrl($sessionId, $mailboxId, $itemId);
        $header = [
            "Authorization" => "$token",
            "Accept" => "application/octet-stream",
            "Content-Type" => "application/json",
        ];
        $body = '
         {
            "savetoMsg": null
           }
         ';
        $response = $this->_caller->postStreamDirect($url, $body, $header);

        $status = $response['status'];
        if ($status == 200 || $status == 201 || $status == 202 || $status == 204) {
            $headers = $response['headers'];
            return ['headers' => $headers, "body" => (object) $response['body']];
        } else {
            Log::log('error', 'Exception While Exporting MailBox Items ' . json_encode($response));
            throw new \Exception('Error Exporting MailBox');
        }
    }
    //-----------------------------------------------------------//
    //Restore Mailbox to Original
    public function restoreMailboxToOriginal($sessionId, $mailboxes, $userCode, $options = [], $historyId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getRestoreMailboxToOriginalUrl($sessionId);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
         {
            "restoreTo": {
              "userCode":"' . $userCode . '",
              "mailBoxes": ' . $mailboxes . ',
              "casServer": "outlook.office365.com",
              ' . ($options['daysNumber'] ? '"recentItemRestorePeriod":' . $options['daysNumber'] . ',' : '') . '
              "skipUnresolved": ' . $options['skipUnresolved'] . ',
              "changedItems": ' . $options['changedItems'] . ',
              "deletedItems": ' . $options['deletedItems'] . ',
              "markrestoredAsunread": ' . $options['markRestoredAsunread'] . ',
              "excludeDrafts": ' . $options['excludeDrafts'] . ',
              "excludeDeleteditems": ' . $options['excludeDeletedItems'] . ',
              "excludeLitigationholditems": ' . $options['excludeLitigationHoldItems'] . ',
              "excludeInplaceholditems": ' . $options['excludeInplaceHolditems'] . '
            }
           }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore MailBox To Original ' . json_encode($data));
            if ($data["status"] == 500 && optional($data["data"])->message) {
                if (str_contains($data['data']->message, "The communication object,")) {
                    $data['data']->message = "This user does not have permission";
                }
                BacRestoreHistoryDetail::where('restore_history_id', $historyId)
                    ->update([
                        'status' => 'Failed',
                        'error_response' => $data["data"]->message,
                        'returned_response' => json_encode($data["data"]),
                    ]);
            }
            throw new \Exception('Error Restore MailBox To Original');
        }
    }
    //-----------------------------------------------------------//
    //Restore Mailbox to Another
    public function restoreMailboxToAnother($sessionId, $mailboxId, $userCode, $mailbox, $folder, $options, $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getRestoreMailboxUrl($sessionId, $mailboxId);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        //TODO folder parameter is not exist anymore
        $body = '
         {
            "restoreTo": {
              "casServer": "outlook.office365.com",
              "mailbox": "' . $mailbox . '",
              "folder": "' . addslashes($folder) . '",
              "userCode": "' . $userCode . '",
              "changedItems": "' . $options['changedItems'] . '",
              "deletedItems": "' . $options['deletedItems'] . '",
              "markrestoredAsunread": "' . $options['markRestoredAsunread'] . '",
              "excludeDrafts": "' . $options['excludeDrafts'] . '",
              "excludeDeletedItems": "' . $options['excludeDeletedItems'] . '",
              "excludeInplaceHolditems": "' . $options['excludeInplaceHolditems'] . '",
              "excludeLitigationHoldItems": "' . $options['excludeLitigationHoldItems'] . '"
            }
           }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore MailBox To Another ' . json_encode($data));
            if ($data["status"] == 500 && optional($data["data"])->message) {
                $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
                $restoreHistoryDetails->error_response = $data["data"]->message;
                $restoreHistoryDetails->status = 'Failed';
                $restoreHistoryDetails->returned_response = json_encode($data["data"]);
                $restoreHistoryDetails->save();
            }
            throw new \Exception('Error Restore MailBox To Another');
        }
    }
    //-----------------------------------------------------------//
    //Restore Mailbox Folder to Original
    public function restoreMailboxFolderToOriginal($sessionId, $mailboxId, $folderId, $userCode, $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getRestoreMailboxFolderUrl($sessionId, trim($mailboxId), $folderId);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
         {
            "restoretoOriginallocation": {
              "userCode": "' . $userCode . '"
            }
           }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore MailBox Folders To Original ' . json_encode($data));
            if ($data["status"] == 500 && optional($data["data"])->message) {
                $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
                $restoreHistoryDetails->error_response = $data["data"]->message;
                $restoreHistoryDetails->status = 'Failed';
                $restoreHistoryDetails->returned_response = json_encode($data["data"]);
                $restoreHistoryDetails->save();
            }
            throw new \Exception('Error Restore MailBox Folders To Original');
        }
    }
    //-----------------------------------------------------------//
    //Restore Mailbox to Another
    public function restoreMailboxFolderToAnother($sessionId, $mailboxId, $folder, $userCode, $toMailbox, $toFolder, $options = [], $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getRestoreMailboxFolderUrl($sessionId, trim($mailboxId), $folder);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
        {
            "restoreTo":
            {
                "casServer": "outlook.office365.com",
                "mailbox": "' . $toMailbox . '",
                "folder": "' . addslashes($toFolder) . '",
                "userCode": "' . $userCode . '",
                "changedItems": "' . $options['changedItems'] . '",
                "deletedItems": "' . $options['deletedItems'] . '",
                "markRestoredAsunread": "' . $options['markRestoredAsunread'] . '"
            }
           }';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore MailBox Folders To Another Server ' . json_encode($data));
            if ($data["status"] == 500 && optional($data["data"])->message) {
                $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
                $restoreHistoryDetails->error_response = $data["data"]->message;
                $restoreHistoryDetails->status = 'Failed';
                $restoreHistoryDetails->returned_response = json_encode($data["data"]);
                $restoreHistoryDetails->save();
            }
            throw new \Exception('Error Restore MailBox Folders To Another');
        }
    }
    //-----------------------------------------------------------//
    //Restore Mailbox Item to Original
    public function restoreMailboxItemToOriginal($sessionId, $mailboxId, $items, $userCode, $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getRestoreMailboxFolderItemsUrl($sessionId, $mailboxId);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
         {
            "restoretoOriginallocation": {
              "userCode": "' . $userCode . '",
              "items": ' . $items . '
            }
           }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore MailBox Items To Original ' . json_encode($data));
            if ($data["status"] == 500 && optional($data["data"])->message) {
                $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
                $restoreHistoryDetails->error_response = $data["data"]->message;
                $restoreHistoryDetails->status = 'Failed';
                $restoreHistoryDetails->returned_response = json_encode($data["data"]);
                $restoreHistoryDetails->save();
            }
            throw new \Exception('Error Restore MailBox Items To Original');
        }
    }
    //-----------------------------------------------------------//
    //Restore Mailbox Item to Another
    public function restoreMailboxItemToAnother($sessionId, $mailboxId, $items, $userCode, $toMailbox, $toFolder, $options, $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getRestoreMailboxFolderItemsUrl($sessionId, $mailboxId);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
        {
            "restoreTo":
            {
                "userCode": "' . $userCode . '",
                "mailbox": "' . $toMailbox . '",
                "folder": "' . addslashes($toFolder) . '",
                "casServer": "outlook.office365.com",
                "items": ' . $items . ',
                "changedItems": "' . $options['changedItems'] . '",
                "deletedItems": "' . $options['deletedItems'] . '",
                "markrestoredAsunread": "' . $options['markRestoredAsunread'] . '"
            }
           }';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore MailBox Items To Another Server ' . json_encode($data));
            if ($data["status"] == 500 && optional($data["data"])->message) {
                $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
                $restoreHistoryDetails->error_response = $data["data"]->message;
                $restoreHistoryDetails->status = 'Failed';
                $restoreHistoryDetails->returned_response = json_encode($data["data"]);
                $restoreHistoryDetails->save();
            }
            throw new \Exception('Error Restore MailBox Items To Another');
        }
    }
    //-----------------------------------------------------------//
    //Get Onedrives
    public function getOnedriveItems($sessionId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOnedriveItemsUrl($sessionId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Getting Onedrives  ' . json_encode($data));
            throw new \Exception('Error Getting Onedrives');
        }
    }
    //-----------------------------------------------------------//
    //Get Onedrive Folders
    public function getOnedriveFolders($sessionId, $onedriveId, $withParent = false)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOnedriveFoldersUrl($sessionId, $onedriveId, $withParent);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Onedrive Folders  ' . json_encode($data));
            throw new \Exception('Error getting Onedrive Folders');
        }
    }
    //-----------------------------------------------------------//
    //Get Onedrive Folder Sub Folders
    public function getOnedriveFolderSubFolders($sessionId, $onedriveId, $folderId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOnedriveFolderSubFoldersUrl($sessionId, $onedriveId, $folderId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Onedrive Folder Subfolder  ' . json_encode($data));
            throw new \Exception('Error getting Onedrive Folder Subfolder');
        }
    }
    //-----------------------------------------------------------//
    //get Onedrive Folder Documents
    public function getOnedriveFolderDocuments($sessionId, $onedriveId, $folderId, $offset, $limit = 1)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOnedriveFolderDocumentsUrl($sessionId, $onedriveId, $folderId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->get($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Getting Onedrive Folder Documents  ' . json_encode($data));
            throw new \Exception('Error Getting Onedrive Documents');
        }
    }
    //-----------------------------------------------------------//
    //Get Onedrive Documents
    public function getOnedriveDocuments($sessionId, $onedriveId, $offset, $limit = 1)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOnedriveDocumentsUrl($sessionId, $onedriveId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Getting Onedrive Folder Documents  ' . json_encode($data));
            throw new \Exception('Error Getting Onedrive Documents');
        }
    }
    //-----------------------------------------------------------//
    //Restore Onedrive to Original
    public function restoreOnedriveOriginal($sessionId, $onedrives, $userCode, $options = [], $historyId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOnedriveBulkRestoreURL($sessionId);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
            'Connection' => "keep-alive",
        ];
        $body = '
        {
            "restoreToOriginalLocation": {
                "OneDrives": ' . $onedrives . ',
                "userCode": "' . $userCode . '",
                "SkipUnresolved": "' . $options["skipUnresolved"] . '",
                "DocumentVersion": "' . $options["version"] . '",
                "DocumentAction": "' . $options["action"] . '"
            }
        }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Onedrive to Original ' . json_encode($data));
            if ($data["status"] == 500 && optional($data["data"])->message) {
                BacRestoreHistoryDetail::where('restore_history_id', $historyId)
                    ->update([
                        'status' => 'Failed',
                        'error_response' => $data["data"]->message,
                        'returned_response' => json_encode($data["data"]),
                    ]);
            }
            throw new \Exception('Error Restore Onedrive to Original');
        }
    }
    //-----------------------------------------------------------//
    //Copy Onedrive to
    public function restoreOnedriveCopy($sessionId, $onedriveId, $userCode, $toOnedrive, $toFolder, $options = [], $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOnedriveRestoreURL($sessionId, $onedriveId);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
        {
            "copyTo": {
                "userCode": "' . $userCode . '",
                "DocumentVersion": "' . $options['version'] . '",
                "documentLastVersionAction": "' . $options['action'] . '",
                "folder": "' . addslashes($toFolder) . '",
                "OneDrive": ' . $toOnedrive . ',
                "changedItems": ' . $options['changedItems'] . ',
                "deletedItems": ' . $options['deletedItems'] . ',
                "restorePermissions": ' . $options['restorePermissions'] . ',
                "SendSharedLinksNotification": ' . $options['sendSharedLinksNotification'] . ',
            }
        }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Copying Onedrive' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }
            $restoreHistoryDetails->returned_response = json_encode($data["data"]);
            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Copying Onedrive');
        }
    }
    //-----------------------------------------------------------//
    //Restore Onedrive Folder to Original
    public function restoreOnedriveFolderOriginal($sessionId, $folders, $onedriveId, $userCode, $options = [], $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOnedriveFoldersBulkRestoreURL($sessionId, trim($onedriveId));
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
        {
            "restoreToOriginalLocation": {
                "folders": ' . $folders . ',
                "userCode": "' . $userCode . '",
                "documentVersion": "' . $options["version"] . '",
                "documentAction": "' . $options["action"] . '"
            }
        }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Onedrive Folders ' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }
            $restoreHistoryDetails->returned_response = json_encode($data["data"]);
            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Restore Onedrive');
        }
    }
    //-----------------------------------------------------------//
    //Copy Onedrive Folder
    public function copyOnedriveFolder($sessionId, $folders, $onedriveId, $userCode, $options = [], $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOnedriveFoldersBulkRestoreURL($sessionId, trim($onedriveId));
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
        {
            "copyTo": {
                "folders": ' . $folders . ',
                "userCode": "' . $userCode . '",
                "oneDrive": ' . $options["toOnedrive"] . ',
                "folder": "' . addslashes($options["toFolder"]) . '",
                "changedItems": "' . $options["changedItems"] . '",
                "deletedItems": "' . $options["deletedItems"] . '",
                "restorePermissions": "' . $options["restorePermissions"] . '",
                "sendSharedLinksNotification": "' . $options["sendSharedLinksNotification"] . '",
                "documentVersion": "' . $options["version"] . '",
                "DocumentLastVersionAction": "' . $options["restoreVersionAction"] . '"
            }
        }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Copying Onedrive Folders ' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }

            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Copying Onedrive');
        }
    }
    //-----------------------------------------------------------//
    //Restore Onedrive Document
    public function restoreOnedriveDocumentOriginal($sessionId, $docs, $onedriveId, $userCode, $options = [], $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOnedriveDocumentsBulkRestoreURL($sessionId, trim($onedriveId));
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
        {
            "restoreToOriginalLocation": {
                "documents": ' . $docs . ',
                "userCode": "' . $userCode . '",
                "documentVersion": "' . ($options["version"] ? $options["version"] : $options["documentVersion"]) . '",
                "documentAction": "' . $options["action"] . '"
            }
        }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Onedrive Documents ' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }
            $restoreHistoryDetails->returned_response = json_encode($data["data"]);
            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Restore Onedrive Documents');
        }
    }
    //-----------------------------------------------------------//
    //Copy Onedrive Document
    public function copyOnedriveDocument($sessionId, $docs, $onedriveId, $userCode, $options = [], $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOnedriveDocumentsBulkRestoreURL($sessionId, trim($onedriveId));
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
        {
            "copyTo": {
                "documents": ' . $docs . ',
                "userCode": "' . $userCode . '",
                "oneDrive": ' . $options["toOnedrive"] . ',
                "folder": "' . addslashes($options["toFolder"]) . '",
                "documentVersion": "' . $options["version"] . '",
                "DocumentLastVersionAction": "' . $options["restoreVersionAction"] . '"
            }
        }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Copying Onedrive Documents ' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }

            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Copying Onedrive Documents');
        }
    }
    //-----------------------------------------------------------//
    //Export Onedrive
    public function exportOnedrive($sessionId, $onedriveIds, $callbacks)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $header = [
            "Authorization: $token",
            "Accept: application/octet-stream",
            "Content-Type: application/json",
            "Connection: keep-alive",
        ];
        $body = '
         {
            "save": {
              "asZip" : true
            }
           }
        ';
        $handlersArr = [];
        $index = 0;
        foreach ($onedriveIds as $onedriveId) {
            array_push($handlersArr, [
                "url" => $this->_urlManager->getOnedriveRestoreURL($sessionId, $onedriveId),
                "callback" => $callbacks[$index],
                "body" => $body,
            ]);
            $index++;
        }
        $response = $this->_caller->postStreamMulti($handlersArr, $header);
        return $response;
    }
    //-----------------------------------------------------------//
    //Export Onedrive Folder
    public function exportOnedriveFolder($sessionId, $folders, $onedriveIds, $callbacks)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $header = [
            "Authorization: $token",
            "Accept: application/octet-stream",
            "Content-Type: application/json",
            "Connection: keep-alive",
        ];
        $handlersArr = [];
        $index = 0;
        foreach ($onedriveIds as $onedriveId) {
            array_push($handlersArr, [
                "url" => $this->_urlManager->getOnedriveFoldersBulkRestoreURL($sessionId, trim($onedriveId)),
                "callback" => $callbacks[$index],
                "body" => '
                {
                   "save": {
                       "folders": ' . $folders[$index] . ',
                       "asZip" : true
                   }
                  }
               ',
            ]);
            $index++;
        }
        $response = $this->_caller->postStreamMulti($handlersArr, $header);
        return $response;
    }
    //-----------------------------------------------------------//
    //Export Onedrive Document
    public function exportOnedriveDocument($sessionId, $docs, $onedriveIds, $callbacks)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $header = [
            "Authorization: $token",
            "Accept: application/octet-stream",
            "Content-Type: application/json",
            "Connection: keep-alive",
        ];
        $handlersArr = [];
        $index = 0;
        foreach ($onedriveIds as $onedriveId) {
            array_push($handlersArr, [
                "url" => $this->_urlManager->getOnedriveDocumentsBulkRestoreURL($sessionId, trim($onedriveId)),
                "callback" => $callbacks[$index],
                "body" => '
                {
                    "save": {
                        "documents": ' . $docs[$index] . ',
                        "asZip" : true
                    }
                   }
               ',
            ]);
            $index++;
        }
        $response = $this->_caller->postStreamMulti($handlersArr, $header);
        return $response;
    }
    //-----------------------------------------------------------//
    //Export Onedrive Document
    public function downloadOnedriveDocument($sessionId, $onedriveId, $documentId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getOnedriveDocumentsRestoreURL($sessionId, trim($onedriveId), $documentId);
        $header = [
            "Authorization" => "$token",
            "Accept" => "application/octet-stream",
            "Content-Type" => "application/json",
        ];
        $body = '
         {
            "save": {
                "asZip" : false
            }
           }
        ';
        $response = $this->_caller->postStreamDirect($url, $body, $header);
        $status = $response['status'];
        if ($status == 200 || $status == 201 || $status == 202 || $status == 204) {
            $headers = $response['headers'];
            return ['headers' => $headers, "body" => (object) $response['body']];
        } else {
            Log::log('error', 'Exception While Downloading Onedrive Documents ' . json_encode($response));
            throw new \Exception('Error Downloading Onedrive Documents');
        }
    }
    //-----------------------------------------------------------//
    //Get Sharepoint Sites
    public function getSharepointSites($sessionId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getSharepointSitesUrl($sessionId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Getting Sharepoint Sites  ' . json_encode($data));
            throw new \Exception('Error Getting Sharepoint Sites');
        }
    }
    //-----------------------------------------------------------//
    //Get Site Lists
    public function getSiteLists($sessionId, $siteId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getSiteListsUrl($sessionId, trim($siteId));
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Getting Site Lists  ' . json_encode($data));
            throw new \Exception('Error getting Site Lists');
        }
    }
    //-----------------------------------------------------------//
    //Get Site Libraries
    public function getSiteLibraries($sessionId, $siteId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getSiteLibrariesUrl($sessionId, $siteId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Getting Site Libraries  ' . json_encode($data));
            throw new \Exception('Error Getting Site Libraries');
        }
    }
    //-----------------------------------------------------------//
    //Get Site Folders
    public function getSiteFolders($sessionId, $siteId, $parentId = '')
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getSiteFoldersUrl($sessionId, $siteId, $parentId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Site Folders  ' . json_encode($data));
            throw new \Exception('Error getting Site Folders');
        }
    }
    //-----------------------------------------------------------//
    //Get Site Documents
    public function getSiteDocuments($sessionId, $siteId, $parentId = '', $offset, $limit)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getSiteDocumentsUrl($sessionId, $siteId, $parentId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Getting Site Documents  ' . json_encode($data));
            throw new \Exception('Error getting Site Documents');
        }
    }
    //-----------------------------------------------------------//
    //Get Site Items
    public function getSiteItems($sessionId, $siteId, $parentId = '', $offset, $limit)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getSiteItemsUrl($sessionId, $siteId, $parentId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Site Items  ' . json_encode($data));
            throw new \Exception('Error getting Site Items');
        }
    }
    //-----------------------------------------------------------//
    //Get Site Items
    public function getSiteItemsAttachments($sessionId, $siteId, $itemId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getSiteItemsAttachmentsUrl($sessionId, $siteId, $itemId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Site Item Attachments  ' . json_encode($data));
            throw new \Exception('Error getting Site Item Attachments');
        }
    }
    //-----------------------------------------------------------//
    //Restore Site
    public function restoreSite($sessionId, $siteId, $userCode, $options = [], $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getSiteRestoreURL($sessionId, $siteId);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "restoreTo": {
                    "userCode": "' . $userCode . '",
                    "restorePermissions": ' . $options['restorePermissions'] . ',
                    "documentVersion": "' . $options['documentVersion'] . '",
                    "documentLastVersionAction": "' . $options['documentLastVersionAction'] . '",
                    "restoreListViews": ' . $options['restoreListViews'] . ',
                    "changedItems": ' . $options['changedItems'] . ',
                    "deletedItems": ' . $options['deletedItems'] . ',
                    "restoreSubsites": ' . $options['restoreSubsites'] . ',
                    "restoreMasterPages": ' . $options['restoreMasterPages'] . ',
                    "sendSharedLinksNotification": ' . $options['sendSharedLinksNotification'] . '
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Site' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }
            $restoreHistoryDetails->returned_response = json_encode($data["data"]);
            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Restore Site');
        }
    }
    //-----------------------------------------------------------//
    //Restore Site Libraries
    public function restoreSiteLibraries($sessionId, $siteId, $libraryId, $userCode, $options = [], $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getSiteLibrariesRestoreURL($sessionId, trim($siteId), $libraryId);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "restoreTo": {
                    "userCode": "' . $userCode . '",
                    "list": "' . $options['toList'] . '",
                    "restorePermissions": ' . $options['restorePermissions'] . ',
                    "documentVersion": "' . $options['documentVersion'] . '",
                    "documentLastVersionAction": "' . $options['documentLastVersionAction'] . '",
                    "restoreListViews": ' . $options['restoreListViews'] . ',
                    "changedItems": ' . $options['changedItems'] . ',
                    "deletedItems": ' . $options['deletedItems'] . ',
                    "sendSharedLinksNotification": ' . $options['sendSharedLinksNotification'] . '
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Site Libraries' . json_encode($data));
            //-----------------------------------//
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }
            $restoreHistoryDetails->returned_response = json_encode($data["data"]);
            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            //-----------------------------------//
            throw new \Exception('Error Restore Site Libraries');
        }
    }
    //-----------------------------------------------------------//
    //Restore Site Libraries
    public function restoreSiteLists($sessionId, $siteId, $listId, $userCode, $options = [], $historyId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getSiteListsRestoreURL($sessionId, trim($siteId), $listId);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "restoreTo": {
                    "userCode": "' . $userCode . '",
                    "list": "' . $options['toList'] . '",
                    "restorePermissions": ' . $options['restorePermissions'] . ',
                    "documentVersion": "' . $options['documentVersion'] . '",
                    "documentLastVersionAction": "' . $options['documentLastVersionAction'] . '",
                    "restoreListViews": ' . $options['restoreListViews'] . ',
                    "changedItems": ' . $options['changedItems'] . ',
                    "deletedItems": ' . $options['deletedItems'] . ',
                    "sendSharedLinksNotification": ' . $options['sendSharedLinksNotification'] . '
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Site Lists' . json_encode($data));
            if ($data["status"] == 500 && optional($data["data"])->message) {
                BacRestoreHistoryDetail::where('restore_history_id', $historyId)
                    ->update([
                        'status' => 'Failed',
                        'error_response' => $data["data"]->message,
                        'returned_response' => json_encode($data["data"]),
                    ]);
            }
            throw new \Exception('Error Restore Site Lists');
        }
    }
    //-----------------------------------------------------------//
    //Export Site Libraries
    public function exportSiteLibraries($sessionId, $siteIds, $libraryIds, $callbacks)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $header = [
            "Authorization: $token",
            "Accept: application/octet-stream",
            "Content-Type: application/json",
            "Connection: keep-alive",
        ];
        $handlersArr = [];
        $index = 0;
        foreach ($siteIds as $siteId) {
            array_push($handlersArr, [
                "url" => $this->_urlManager->getSiteLibrariesRestoreURL($sessionId, trim($siteId), $libraryIds[$index]),
                "callback" => $callbacks[$index],
                "body" => '
                {
                    "save": {
                      "asZip" : true
                    }
                   }
               ',
            ]);
            $index++;
        }
        $response = $this->_caller->postStreamMulti($handlersArr, $header);
        return $response;
    }
    //-----------------------------------------------------------//
    public function restoreSiteDocuments($sessionId, $siteId, $docs, $userCode, $options = [], $toList = '', $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getSiteDocumentsRestoreUrl($sessionId, trim($siteId));
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "restoreTo": {
                    "userCode": "' . $userCode . '",
                    "documents": ' . $docs . ',
                    "list": "' . $toList . '",
                    "restorePermissions": ' . $options['restorePermissions'] . ',
                    "documentVersion": "' . $options['documentVersion'] . '",
                    "documentLastVersionAction": "' . $options['documentLastVersionAction'] . '",
                    "sendSharedLinksNotification": ' . $options['sendSharedLinksNotification'] . '
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Site Documents ' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional(optional($data)["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }
            $restoreHistoryDetails->returned_response = json_encode($data["data"]);
            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Restore Site Documents');
        }
    }
    //-----------------------------------------------------------//
    public function restoreSiteItems($sessionId, $siteId, $items, $userCode, $options = [], $toList = '', $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getSiteItemsRestoreUrl($sessionId, trim($siteId));
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "restoreTo": {
                    "userCode": "' . $userCode . '",
                    "items": ' . $items . ',
                    "list": "' . $toList . '",
                    "restorePermissions": ' . $options['restorePermissions'] . ',
                    "documentVersion": "' . $options['documentVersion'] . '",
                    "documentLastVersionAction": "' . $options['documentLastVersionAction'] . '",
                    "sendSharedLinksNotification": ' . $options['sendSharedLinksNotification'] . '
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Site Items ' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }
            $restoreHistoryDetails->returned_response = json_encode($data["data"]);
            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Restore Site Items');
        }
    }
    //-----------------------------------------------------------//
    public function restoreSiteFolders($sessionId, $siteId, $folders, $userCode, $options = [], $toList = '', $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getSiteFoldersRestoreUrl($sessionId, trim($siteId));
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "restoreTo": {
                    "userCode": "' . $userCode . '",
                    "folders": ' . $folders . ',
                    "list": "' . $toList . '",
                    "restorePermissions": ' . $options['restorePermissions'] . ',
                    "documentVersion": "' . $options['documentVersion'] . '",
                    "documentLastVersionAction": "' . $options['documentLastVersionAction'] . '",
                    "sendSharedLinksNotification": ' . $options['sendSharedLinksNotification'] . '
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Site Folders ' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }
            $restoreHistoryDetails->returned_response = json_encode($data["data"]);
            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Restore Site Folders');
        }
    }
    //-----------------------------------------------------------//
    //Export Site Documents
    public function exportSiteDocuments($sessionId, $siteIds, $docs, $callbacks)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $handlersArr = [];
        $index = 0;
        foreach ($siteIds as $siteId) {
            array_push($handlersArr, [
                "url" => $this->_urlManager->getSiteDocumentsRestoreUrl($sessionId, trim($siteId)),
                "callback" => $callbacks[$index],
                "body" => '
                {
                    "save": {
                        "documents": ' . $docs[$index] . ',
                        "asZip" : true
                    }
                   }
                    ',
            ]);
            $index++;
        }
        $header = [
            "Authorization: $token",
            "Accept: application/octet-stream",
            "Content-Type: application/json",
            "Connection: keep-alive",
        ];

        $response = $this->_caller->postStreamMulti($handlersArr, $header);
        return $response;
    }
    //-----------------------------------------------------------//
    //Export Site Items Attachments
    public function exportSiteItems($sessionId, $siteIds, $itemIds, $attachmentsArr, $callbacks = null)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $handlersArr = [];
        $index = 0;
        foreach ($siteIds as $siteId) {
            array_push($handlersArr, [
                "url" => $this->_urlManager->getSiteItemsAttachmentsRestoreUrl($sessionId, trim($siteId), $itemIds[$index]),
                "callback" => $callbacks[$index],
                "body" => '
                {
                    "save": {
                        "Attachments": ' . json_encode($attachmentsArr[$index]) . '
                    }
                }
                ',
            ]);
            $index++;
        }
        $header = [
            "Authorization: $token",
            "Accept: application/octet-stream",
            "Content-Type: application/json",
            "Connection: keep-alive",
        ];
        return $this->_caller->postStreamMulti($handlersArr, $header);
    }
    //-----------------------------------------------------------//
    //Export Site Items Attachments
    public function exportSiteItemAttachments($sessionId, $siteId, $itemId, $attachmentsArr)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getSiteItemsAttachmentsRestoreUrl($sessionId, trim($siteId), $itemId);
        $body = '
        {
            "save": {
                "Attachments": ' . json_encode($attachmentsArr) . '
            }
        }
        ';
        $header = [
            "Authorization" => "$token",
            "Accept" => "application/octet-stream",
            "Content-Type" => "application/json",
        ];
        $response = $this->_caller->postStreamDirect($url, $body, $header);
        $status = $response['status'];
        if ($status == 200 || $status == 201 || $status == 202 || $status == 204) {
            $headers = $response['headers'];
            return ['headers' => $headers, 'body' => (object) $response["body"]];
        } else {
            Log::log('error', 'Exception While Downloading Site Item Attachments ' . json_encode($response));
            throw new \Exception('Error Downloading Site Item Attachments');
        }
    }
    //-----------------------------------------------------------//
    //Download Site Documents
    public function downloadSiteDocument($sessionId, $siteId, $documentId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getSiteDocumentRestoreUrl($sessionId, trim($siteId), $documentId);
        $header = [
            "Authorization" => "$token",
            "Accept" => "application/octet-stream",
            "Content-Type" => "application/json",
        ];
        $body = '
         {
            "save": {
                "asZip" : false
            }
           }
        ';
        $response = $this->_caller->postStreamDirect($url, $body, $header);
        $status = $response['status'];
        if ($status == 200 || $status == 201 || $status == 202 || $status == 204) {
            $headers = $response['headers'];
            return ['headers' => $headers, 'body' => (object) $response["body"]];
        } else {
            Log::log('error', 'Exception While Downloading Site Document ' . json_encode($response));
            throw new \Exception('Error Downloading Site Document');
        }
    }
    //-----------------------------------------------------------//
    //Export Site Documents
    public function exportSiteFolders($sessionId, $siteIds, $foldersArr, $callbacks)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $handlersArr = [];
        $index = 0;
        foreach ($siteIds as $siteId) {
            array_push($handlersArr, [
                "url" => $this->_urlManager->getSiteFoldersRestoreUrl($sessionId, trim($siteId)),
                "callback" => $callbacks[$index],
                "body" => '
                {
                    "save": {
                        "folders": ' . $foldersArr[$index] . ',
                        "asZip" : true
                    }
                   }
                ',
            ]);
            $index++;
        }
        $header = [
            "Authorization: $token",
            "Accept: application/octet-stream",
            "Content-Type: application/json",
            "Connection: keep-alive",
        ];
        $response = $this->_caller->postStreamMulti($handlersArr, $header);
        return $response;
    }
    //-----------------------------------------------------------//
    //Get Teams
    public function getTeams($sessionId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getTeamsUrl($sessionId);
        $header = [
            'Authorization' => $token,
            'Accept' => "*/*",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Getting Teams  ' . json_encode($data));
            throw new \Exception('Error Getting Teams');
        }
    }
    //-----------------------------------------------------------//
    //Get Team Channels
    public function getTeamChannels($sessionId, $teamId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getTeamChannelsUrl($sessionId, trim($teamId));
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Getting Team Channels  ' . json_encode($data));
            throw new \Exception('Error getting Team Channels');
        }
    }
    //-----------------------------------------------------------//
    //Get Team Channels
    public function getTeamChannelName($sessionId, $teamId, $channelId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getTeamChannelUrl($sessionId, trim($teamId), $channelId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Getting Team Channels  ' . json_encode($data));
            throw new \Exception('Error getting Team Channels');
        }
    }
    //-----------------------------------------------------------//
    //Get Channel Posts
    public function getChannelPosts($sessionId, $teamId, $channelId, $offset, $limit)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getChannelPostsUrl($sessionId, $teamId, $channelId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Channel Posts  ' . json_encode($data));
            throw new \Exception('Error getting Channel Posts');
        }
    }
    //-----------------------------------------------------------//
    //Get Channel Posts
    public function getChannelPostReplies($sessionId, $teamId, $channelId, $postId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getChannelPostRepliesUrl($sessionId, $teamId, $channelId, $postId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Channel Post Replies ' . json_encode($data));
            throw new \Exception('Error getting Channel Post Replies');
        }
    }
    //-----------------------------------------------------------//
    //Get Channel Files
    public function getChannelFiles($sessionId, $teamId, $channelId, $folderId, $offset, $limit)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getChannelFilesUrl($sessionId, $teamId, $channelId, $folderId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Channel Files  ' . json_encode($data));
            throw new \Exception('Error getting Channel Files');
        }
    }
    //-----------------------------------------------------------//
    //Get Channel Tabs
    public function getChannelTabs($sessionId, $teamId, $channelId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getChannelTabsUrl($sessionId, $teamId, $channelId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Channel Tabs  ' . json_encode($data));
            throw new \Exception('Error getting Channel Tabs');
        }
    }
    //-----------------------------------------------------------//
    //Restore Team
    public function restoreTeam($sessionId, $teams, $userCode, $options = [], $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getTeamRestoreURL($sessionId);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "bulkRestore": {
                    "userCode": "' . $userCode . '",
                    "teams": ' . $teams . ',
                    "restoreChangedItems": ' . $options['restoreChangedItems'] . ',
                    "restoreMissingItems": ' . $options['restoreMissingItems'] . ',
                    "restoreMembers": ' . $options['restoreMembers'] . ',
                    "restoreSettings": ' . $options['restoreSettings'] . '
                }
            }
            ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Team' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }
            $restoreHistoryDetails->returned_response = json_encode($data["data"]);
            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Restore Team');
        }
    }
    //-----------------------------------------------------------//
    //Restore Channels
    public function restoreChannels($sessionId, $teamId, $channelId, $userCode, $options = [], $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getChannelsRestoreURL($sessionId, trim($teamId), $channelId);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "restore": {
                    "userCode": "' . $userCode . '",
                    "restoreMissingItems": ' . $options['restoreMissingItems'] . ',
                    "restoreChangedItems": ' . $options['restoreChangedItems'] . '
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Channels' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }
            $restoreHistoryDetails->returned_response = json_encode($data["data"]);
            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Restore Channels');
        }
    }
    //-----------------------------------------------------------//
    //Restore Channels Posts
    public function restoreChannelsPosts($sessionId, $teamId, $channelId, $userCode, $options = [], $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getTeamsPostsRestoreURL($sessionId, trim($teamId));
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "restore": {
                    "userCode": "' . $userCode . '",
                    "channelId": "' . $channelId . '",
                    ' . (optional($options)['from'] ? '"from": "' . optional($options)['from'] . '",' : '') . '
                    ' . (optional($options)['to'] ? '"to": "' . optional($options)['to'] . '"' : '') . '
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Channels Posts' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }
            $restoreHistoryDetails->returned_response = json_encode($data["data"]);
            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Restore Channels Posts');
        }
    }
    //-----------------------------------------------------------//
    //Restore Channels Files
    public function restoreChannelsFiles($sessionId, $teamId, $channelId, $userCode, $options = [], $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getTeamsFilesRestoreURL($sessionId, trim($teamId));
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "restore": {
                    "userCode": "' . $userCode . '",
                    "channelId": "' . $channelId . '",
                    ' . (optional($options)['from'] ? '"from": "' . optional($options)['from'] . '",' : '') . '
                    ' . (optional($options)['to'] ? '"to": "' . optional($options)['to'] . '",' : '') . '
                    "restoreChangedItems": "' . $options['restoreChangedItems'] . '",
                    "restoreMissingItems": "' . $options['restoreMissingItems'] . '",
                    "fileVersion": "' . $options['fileVersion'] . '",
                    "fileLastVersionAction": "' . $options['fileLastVersionAction'] . '",
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Channels Files' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }
            $restoreHistoryDetails->returned_response = json_encode($data["data"]);
            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Restore Channels Files');
        }
    }
    //-----------------------------------------------------------//
    //Restore Channels Files
    public function restoreChannelsTabs($sessionId, $teamId, $channelId, $userCode, $options = [], $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getTeamsTabsRestoreURL($sessionId, trim($teamId), $channelId);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "restore": {
                    "userCode": "' . $userCode . '",
                    "restoreChangedTabs": "' . $options['restoreChangedItems'] . '",
                    "restoreMissingTabs": "' . $options['restoreMissingItems'] . '"
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Channels Tabs' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }
            $restoreHistoryDetails->returned_response = json_encode($data["data"]);
            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Restore Channels Tabs');
        }
    }
    //-----------------------------------------------------------//
    //Export Channels Posts
    public function exportChannelsPosts($sessionId, $teamIds, $channelIds, $callbacks)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $handlersArr = [];
        $index = 0;
        foreach ($teamIds as $teamId) {
            array_push($handlersArr, [
                "url" => $this->_urlManager->getTeamsPostsRestoreURL($sessionId, trim($teamId)),
                "callback" => $callbacks[$index],
                "body" => '
                {
                    "save": {
                      "asZip" : true,
                      "channelId": "' . $channelIds[$index] . '"
                    }
                   }
                    ',
            ]);
            $index++;
        }
        $header = [
            "Authorization: $token",
            "Accept: application/octet-stream",
            "Content-Type: application/json",
            "Connection: keep-alive",
        ];

        $response = $this->_caller->postStreamMulti($handlersArr, $header);
        return $response;
    }
    //-----------------------------------------------------------//
    //Export Channels Posts
    public function exportPosts($sessionId, $teamIds, $channelIds, $postsArr, $callbacks)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $handlersArr = [];
        $index = 0;
        foreach ($teamIds as $teamId) {
            array_push($handlersArr, [
                "url" => $this->_urlManager->getTeamsPostsRestoreURL($sessionId, trim($teamId)),
                "callback" => $callbacks[$index],
                "body" => '
                {
                    "export": {
                      "channelId": "' . $channelIds[$index] . '",
                      "posts": ' . $postsArr[$index] . '
                    }
                   }
                    ',
            ]);
            $index++;
        }
        $header = [
            "Authorization: $token",
            "Accept: application/octet-stream",
            "Content-Type: application/json",
            "Connection: keep-alive",
        ];

        $response = $this->_caller->postStreamMulti($handlersArr, $header);
        return $response;
    }
    //-----------------------------------------------------------//
    //Export Channels Files
    public function exportChannelsFiles($sessionId, $teamIds, $channelIds, $callbacks)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $handlersArr = [];
        $index = 0;
        foreach ($teamIds as $teamId) {
            array_push($handlersArr, [
                "url" => $this->_urlManager->getTeamsFilesRestoreURL($sessionId, trim($teamId)),
                "callback" => $callbacks[$index],
                "body" => '
                {
                    "save": {
                      "asZip" : true,
                      "channelId": "' . $channelIds[$index] . '"
                    }
                   }
                    ',
            ]);
            $index++;
        }
        $header = [
            "Authorization: $token",
            "Accept: application/octet-stream",
            "Content-Type: application/json",
            "Connection: keep-alive",
        ];

        $response = $this->_caller->postStreamMulti($handlersArr, $header);
        return $response;
    }
    //-----------------------------------------------------------//
    //Export Files
    public function exportTeamsFiles($sessionId, $teamIds, $filesArr, $callbacks)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $handlersArr = [];
        $index = 0;
        foreach ($teamIds as $teamId) {
            array_push($handlersArr, [
                "url" => $this->_urlManager->getTeamsFilesRestoreURL($sessionId, trim($teamId)),
                "callback" => $callbacks[$index],
                "body" => '
                {
                    "save": {
                      "asZip" : true,
                      "files": ' . $filesArr[$index] . '
                    }
                   }
                    ',
            ]);
            $index++;
        }
        $header = [
            "Authorization: $token",
            "Accept: application/octet-stream",
            "Content-Type: application/json",
            "Connection: keep-alive",
        ];

        $response = $this->_caller->postStreamMulti($handlersArr, $header);
        return $response;
    }
    //-----------------------------------------------------------//
    //Restore Teams Files
    public function restoreTeamsFiles($sessionId, $teamId, $files, $userCode, $options = [], $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getTeamsFilesRestoreURL($sessionId, trim($teamId));
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "restore": {
                    "userCode": "' . $userCode . '",
                    "files": ' . $files . ',
                    "restoreChangedItems": ' . $options['restoreChangedItems'] . ',
                    "restoreMissingItems": ' . $options['restoreMissingItems'] . ',
                    "fileVersion": "' . $options['fileVersion'] . '",
                    "fileLastVersionAction": "' . $options['fileLastVersionAction'] . '"
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Files' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }
            $restoreHistoryDetails->returned_response = json_encode($data["data"]);
            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Restore Files');
        }
    }
    //-----------------------------------------------------------//
    //Restore Teams Tabs
    public function restoreTeamsTabs($sessionId, $teamId, $channelId, $tabs, $userCode, $options = [], $detailsId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getTeamsTabsRestoreURL($sessionId, trim($teamId), $channelId);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "restore": {
                    "userCode": "' . $userCode . '",
                    "tabs": ' . $tabs . ',
                    "restoreChangedTabs": ' . $options['restoreChangedItems'] . ',
                    "restoreMissingTabs": ' . $options['restoreMissingItems'] . '
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Restore Tabs' . json_encode($data));
            $restoreHistoryDetails = BacRestoreHistoryDetail::where('id', $detailsId)->first();
            if (optional($data["data"])->message) {
                $restoreHistoryDetails->error_response = $data["data"]->message;
            }
            $restoreHistoryDetails->returned_response = json_encode($data["data"]);
            $restoreHistoryDetails->status = 'Failed';
            $restoreHistoryDetails->save();
            throw new \Exception('Error Restore Tabs');
        }
    }
    //-----------------------------------------------------------//
    //Download Teams File
    public function downloadTeamsFile($sessionId, $teamId, $fileId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getTeamsFileRestoreURL($sessionId, trim($teamId), $fileId);
        $header = [
            "Authorization" => "$token",
            "Accept" => "application/octet-stream",
            "Content-Type" => "application/json",
        ];
        $body = '
         {
            "save": {
                "asZip" : false
            }
           }
        ';
        $response = $this->_caller->postStreamDirect($url, $body, $header);
        $status = $response['status'];
        if ($status == 200 || $status == 201 || $status == 202 || $status == 204) {
            $headers = $response['headers'];
            return ['headers' => $headers, 'body' => (object) $response["body"]];
        } else {
            Log::log('error', 'Exception While Downloading Teams File ' . json_encode($response));
            throw new \Exception('Error Downloading Teams File');
        }
    }
    //-----------------------------------------------------------//
    //Download Teams Post
    public function downloadTeamsPost($sessionId, $teamId, $postId)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->getTeamsPostRestoreURL($sessionId, trim($teamId), $postId);
        $header = [
            "Authorization" => "$token",
            "Accept" => "application/octet-stream",
            "Content-Type" => "application/json",
        ];
        $body = '
         {
            "export": {}
           }
        ';
        $response = $this->_caller->postStreamDirect($url, $body, $header);
        $status = $response['status'];
        if ($status == 200 || $status == 201 || $status == 202 || $status == 204) {
            $headers = $response['headers'];
            return ['headers' => $headers, 'body' => (object) $response["body"]];
        } else {
            Log::log('error', 'Exception While Downloading Teams Post ' . json_encode($response));
            throw new \Exception('Error Downloading Teams Post');
        }
    }
    //-----------------------------------------------------------//
    //Get Organization License Info
    public function getLicensedUsers($organizationId)
    {
        $url = $this->_urlManager->getLicensedUsersUrl($organizationId);
        $header = [
            'Authorization' => "Bearer " . Manager::getVeeamServerToken(),
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Veeam Licensed Users ' . json_encode($data));
            throw new \Exception('Error Getting Veeam Licensed Users');
        }
    }
    //-----------------------------------------------------------//
    public function searchMailBoxItems($sessionId, $mailboxId, $offset, $limit = 1, $query)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->searchMailBoxItemsUrl($sessionId, $mailboxId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "search": {
                    "query": "' . addslashes($query) . '",
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Searching MailBox Items ' . $body . '   ' . json_encode($data));
            throw new \Exception('Error Searching MailBox Items');
        }
    }
    //-----------------------------------------------------------//
    public function searchMailBoxFolderItems($sessionId, $mailboxId, $folderId, $offset, $limit = 1, $query)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->searchMailBoxFolderItemsUrl($sessionId, $mailboxId, $folderId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "search": {
                    "query": "' . addslashes($query) . '",
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Searching MailBox Folder Items ' . json_encode($data));
            throw new \Exception('Error Searching MailBox Folder Items');
        }
    }
    //-----------------------------------------------------------//
    public function searchOnedriveItems($sessionId, $onedriveId, $offset, $limit = 1, $query)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->searchOnedriveItemsUrl($sessionId, $onedriveId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "search": {
                    "query": "' . addslashes($query) . '",
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Searching Onedrive Items ' . $body . '   ' . json_encode($data));
            throw new \Exception('Error Searching Onedrive Items');
        }
    }
    //-----------------------------------------------------------//
    public function searchOnedriveFolderItems($sessionId, $onedriveId, $folderId, $offset, $limit = 1, $query)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->searchOnedriveFolderItemsUrl($sessionId, $onedriveId, $folderId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "search": {
                    "query": "' . addslashes($query) . '",
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Searching Onedrive Items ' . $body . '   ' . json_encode($data));
            throw new \Exception('Error Searching Onedrive Items');
        }
    }
    //-----------------------------------------------------------//
    public function searchTeamItems($sessionId, $teamId, $offset, $limit = 1, $query, $type)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->searchTeamItemsUrl($sessionId, $teamId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "search": {
                    "type": "' . $type . '",
                    "query": "' . addslashes($query) . '",
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            $data["body"] = $body;
            return $data;
        } else {
            Log::log('error', 'Exception While Searching Team Items ' . $body . '   ' . json_encode($data));
            throw new \Exception('Error Searching Team Items');
        }
    }
    //-----------------------------------------------------------//
    public function searchTeamChannelItems($sessionId, $temaId, $channelId, $offset, $limit = 1, $query, $type)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->searchTeamChannelItemsUrl($sessionId, $temaId, $channelId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "search": {
                    "type": "' . $type . '",
                    "query": "' . addslashes($query) . '",
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Searching Team Channel ' . $body . '   ' . json_encode($data));
            throw new \Exception('Error Searching Team Channel');
        }
    }
    //-----------------------------------------------------------//
    public function searchSiteItems($sessionId, $siteId, $offset, $limit = 1, $query)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->searchSiteItemsUrl($sessionId, $siteId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "search": {
                    "query": "' . addslashes($query) . '",
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            $data["body"] = $body;
            return $data;
        } else {
            Log::log('error', 'Exception While Searching Site Items ' . $body . '   ' . json_encode($data));
            throw new \Exception('Error Searching Site Items');
        }
    }
    //-----------------------------------------------------------//
    public function searchSiteListItems($sessionId, $siteId, $listId, $offset, $limit = 1, $query)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->searchSiteListItemsUrl($sessionId, $siteId, $listId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "search": {
                    "query": "' . addslashes($query) . '",
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            $data["url"] = $url;
            Log::log('error', 'Exception While Searching Site List ' . json_encode($data));
            throw new \Exception('Error Searching Site List');
        }
    }
    //-----------------------------------------------------------//
    public function searchSiteLibraryItems($sessionId, $siteId, $libraryId, $offset, $limit = 1, $query)
    {
        $token = "Bearer " . Manager::getVeeamServerToken();
        $url = $this->_urlManager->searchSiteLibraryItemsUrl($sessionId, $siteId, $libraryId, $offset, $limit);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "search": {
                    "query": "' . addslashes($query) . '",
                }
            }
         ';
        $data = $this->_caller->postAlive($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Searching Site Library ' . $body . '   ' . json_encode($data));
            throw new \Exception('Error Searching Site Library');
        }
    }
    //-----------------------------------------------------------//
    public function getAzureApplications($organizationId)
    {
        $url = $this->_urlManager->getAzureApplicationsUrl($organizationId);
        $header = [
            'Authorization' => "Bearer " . Manager::getVeeamServerToken(),
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Azure Applications ' . json_encode($data));
            throw new \Exception('Error Getting Azure Applications');
        }
    }
    //-----------------------------------------------------------//
    public function addAzureApplications($organizationId, $applicationName, $userCode, $count)
    {
        $url = $this->_urlManager->getAzureApplicationsUrl($organizationId);
        $header = [
            'Authorization' => "Bearer " . Manager::getVeeamServerToken(),
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "displayName":"Bactopus Authentication Application - Performance",
                "userCode":"' . $userCode . '",
                "applicationCertificate":"' . config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE") . '",
                "applicationCertificatePassword":"' . config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE_PASSWORD") . '",
                "count":"' . $count . '"
            }
        ';

        $data = $this->_caller->post_($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202) {
            return $data;
        } else {
            Log::log('error', 'Exception while adding Azure Applications ' . json_encode($data));
            if (optional(optional($data)["data"])->message) {
                throw new \Exception(optional(optional($data)["data"])->message);
            }
            throw new \Exception('Error Adding Azure Applications');
        }
    }
    //-----------------------------------------------------------//
    public function getBackupApplications($organizationId)
    {
        $url = $this->_urlManager->getBackupApplicationsUrl($organizationId);
        $header = [
            'Authorization' => "Bearer " . Manager::getVeeamServerToken(),
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Backup Applications ' . json_encode($data));
            throw new \Exception('Error Getting Backup Applications');
        }
    }
    //-----------------------------------------------------------//
    public function addBackupApplications($organizationId, $applicationsArr)
    {
        $url = $this->_urlManager->getBackupApplicationsUrl($organizationId);
        $header = [
            'Authorization' => "Bearer " . Manager::getVeeamServerToken(),
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $body = json_encode($applicationsArr);
        $data = $this->_caller->put($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202) {
            return $data;
        } else {
            Log::log('error', 'Exception while adding Backup Applications ' . json_encode($data));
            if (optional(optional($data)["data"])->message) {
                throw new \Exception(optional(optional($data)["data"])->message);
            }
            throw new \Exception('Error Adding Backup Applications');
        }
    }
    //-----------------------------------------------------------//
}
