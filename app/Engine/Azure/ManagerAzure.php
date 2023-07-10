<?php

namespace App\Engine\Azure;

use App\Engine\Base\CallerApi;
use App\Models\BacOrganization;
use App\Models\BacUser;
use Exception;
use Illuminate\Support\Facades\Log;

class ManagerAzure
{
    private $_urlManager;
    private $_clientId;
    private $_secret;
    private $_grant_type;
    private $_resource;
    private $_caller;
    private $_refresh_token;

    public function __construct()
    {
        $this->_urlManager = new UrlsAzure();

        $this->_clientId = config("parameters.AZURE_CLIENT_ID");
        $this->_secret = config("parameters.AZURE_SECRET");
        $this->_grant_type = config("parameters.AZURE_GRANT_TYPE");
        $this->_resource = config("parameters.AZURE_RESOURCE");
        $this->_refresh_token = config("parameters.AZURE_REFRESH_TOKEN");
        $this->_caller = new CallerApi();
    }

    public function login($userId = '')
    {
        if (!$userId) {
            $user = auth()->user();
        } else {
            $user = BacUser::where('id', $userId)->first();
        }

        if ($user) {
            $organization = $user->organization;
        } else if (session("organization_id")) {
            $organization = BacOrganization::where("id", session("organization_id"))->first();
        }
        $url = $this->_urlManager->loginUrl($organization->azure_tenant_guid);
        $params = [
            'client_id' => $this->_clientId,
            'client_secret' => $this->_secret,
            'grant_type' => $this->_grant_type,
            // 'resource' => $this->_resource,
            'refresh_token' => $this->_refresh_token,
        ];
        //call request
        $data = $this->_caller->post($url, $params, []);
        // if ($data["data"]->refresh_token) {
        // Parameters::where('key', 'AZURE_REFRESH_TOKEN')
        //     ->update(['value' => $data["data"]->refresh_token]);
        // }
        return $data["data"]->access_token;
    }

    //create azure resource group and store it in DB
    public function createAzureResourceGroup($subscriptionId, $azureResourceGroupName, $azureResourceGroupLocation)
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->createAzureResourceGroupUrl($subscriptionId, $azureResourceGroupName);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $body = '
        {
            "location": "' . $azureResourceGroupLocation . '"
        }
        ';
        $data = $this->_caller->put($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while creating Azure ResourceGroup   ' . json_encode($data));
            throw new \Exception('Error Creating Resource Group');
        }
    }

    //create azure storage account and store it in DB
    public function createAzureStorageAccount($params)
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->createAzureStorageAccountUrl($params["azureSubscriptionId"], $params["resourceGroupName"], $params["storageAccountName"]);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $body = '
          {
            "sku": {
                  "name": "Standard_LRS",
                  "tier": "Standard"
              },
            "kind": "StorageV2",
            "location": "' . $params["storageLocation"] . '",
            "properties": {
                "enableHttpsTrafficOnly": true,
                "allowBlobPublicAccess": true,
                "accessTier": "Cool",
                "minimumTlsVersion": "TLS1_2"
              }
          }
          ';
        $data = $this->_caller->put($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while creating Azure StorageAccount   ' . json_encode($data));
            throw new \Exception('Error Creating Storage');
        }
    }

    public function getAllAzureStorageAccount($subscriptionId, $resourceGroupName)
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->getAllAzureStorageAccountUrl($subscriptionId, $resourceGroupName);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Getting Azure Storage Account   ' . json_encode($data));
            throw new \Exception('Error Getting Storage Account');
        }
    }

    public function getAzureStorageAccount($subscriptionId, $resourceGroup, $storageAccountName)
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->getAzureStorageAccountUrl($subscriptionId, $resourceGroup, $storageAccountName);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Getting Azure Storage Account   ' . json_encode($data));
            throw new \Exception('Error Getting Storage Account');
        }
    }

    //get azure storage account's shared keys
    public function getStorageAccountSharedAccessKeys($azureStorageAccountName, $azureSubscriptionId, $azureResourceGroupName,$userId="")
    {
        $token = "Bearer " . $this->login($userId);
        $url = $this->_urlManager->getAzureStorageAccountSharedKeysUrl($azureSubscriptionId, $azureResourceGroupName, $azureStorageAccountName);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->post($url, [], $header);
        $res = [];
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            $res = $data["data"];
            return $res;
        } else {
            Log::log('error', 'Exception while getting StorageAccount Shared Access Keys   ' . json_encode($data));
            throw new \Exception('Error Getting Storage Info');
        }
    }

    //get azure storage account's shared keys
    public function getAzureContainer($azureStorageAccountName, $azureResourceGroupName, $containerName)
    {
        $user = auth()->user();
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->getAzureContainerUrl($user->organization->azure_subscription_guid, $azureResourceGroupName, $azureStorageAccountName, $containerName);
        $header = [
            'Authorization' => $token,
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        }
    }

    //delete azure storage account
    public function deleteAzureStorageAccount($azureStorageAccountName, $azureResourceGroupName)
    {
        $user = auth()->user();
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->deleteAzureStorageAccountUrl($user->organization->azure_subscription_guid, $azureResourceGroupName, $azureStorageAccountName);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        $data = $this->_caller->delete($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while deleting Azure StorageAccount   ' . json_encode($data));
            throw new \Exception('Error Deleting Storage');
        }
    }

    //create azure container and store it in DB
    public function createContainer($storageKey, $storageAccountName, $containerName)
    {
        try {
            $apiVersion = "2009-09-19";
            $dt = gmdate('D, d M Y H:i:s T', time());
            $canonicalizeHeaders = "x-ms-date:" . $dt . "\nx-ms-version:" . $apiVersion;
            $canonicalizeResource = "/" . $storageAccountName . "/" . $containerName . "\nrestype:container";
            $stringToSign = "PUT\n\n\n0\n\n\n\n\n\n\n\n\n" . $canonicalizeHeaders . "\n" . $canonicalizeResource;

            $auth = $this->signString($stringToSign, $storageKey, $storageAccountName);
            return $this->createAzureContainer($storageAccountName, $containerName, $auth, $dt, $apiVersion);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while creating Azure Container, with error: ' . $ex->getMessage());
        }
    }

    //create azure container and store it in DB
    public function getContainer($storageKey, $storageAccountName, $containerName, $localStorageAccountId)
    {
        try {
            $apiVersion = "2009-09-19";
            $dt = gmdate('D, d M Y H:i:s T', time());
            $canonicalizeHeaders = "x-ms-date:" . $dt . "\nx-ms-version:" . $apiVersion;
            $canonicalizeResource = "/" . $storageAccountName . "/" . $containerName . "\nrestype:container";
            $stringToSign = "PUT\n\n\n0\n\n\n\n\n\n\n\n\n" . $canonicalizeHeaders . "\n" . $canonicalizeResource;

            $auth = $this->signString($stringToSign, $storageKey, $storageAccountName);
            return $this->createAzureContainer($storageAccountName, $containerName, $localStorageAccountId, $auth, $dt, $apiVersion);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while creating Azure Container, with error: ' . $ex->getMessage());
        }
    }

    public function signString($stringToSign, $storageKey, $storageAccountName)
    {
        $unicodeKey = base64_decode($storageKey);
        $hash = strtoupper(hash_hmac("sha256", "$stringToSign", $unicodeKey));
        $hashBytes = str_split($hash, 2);
        $hexBytes = array();
        for ($i = 0; $i < count($hashBytes); $i++) {
            array_push($hexBytes, hexdec($hashBytes[$i]));
        }
        $packed = pack("c*", ...$hexBytes);
        $encoded = base64_encode($packed);
        return "SharedKey " . $storageAccountName . ":" . $encoded;
    }
    //call azure container api
    public function createAzureContainer($azureStorageAccountName, $azureContainerName, $auth, $date, $apiVersion)
    {
        $url = $this->_urlManager->createAzureContainerUrl($azureStorageAccountName, $azureContainerName);
        $header = [
            "Authorization" => $auth,
            "x-ms-date" => $date,
            "x-ms-version" => $apiVersion,
            "Content-Length" => 0,
        ];
        $data = $this->_caller->put($url, '', $header);
        $res = -1;
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while creating Azure Container   ' . json_encode($data));
            throw new \Exception('Error Creating Container');
        }
    }

    //register Microsoft.Storage  ResourceProvider
    public function registerAzureResourceProvider($subscription_id)
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->registerAzureResourceProviderUrl($subscription_id);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->post($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while registering Azure Resource Provider   ' . json_encode($data));
            throw new \Exception('Error Registering Microsoft.Storage');
        }
    }
    //get Microsoft.Storage  ResourceProvider
    public function getAzureResourceProvider($subscription_id)
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->getAzureResourceProviderUrl($subscription_id);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url,[],$header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Azure Resource Provider   ' . json_encode($data));
            throw new \Exception('Error Getting Microsoft.Storage');
        }
    }

    //upload azure blob
    public function putBlobBlockList($params = [])
    {
        //---------------------------
        $storageAccount = $params['storageAccount'];
        $account_key = $params['accountKey'];
        $containerName = $params['containerName'];
        $blobName = $params['blobName'];
        $blocksListXML = $params['blocksListXML'];
        //---------------------------
        $date = gmdate('D, d M Y H:i:s \G\M\T');
        $version = "2019-12-12";
        $fileLen = strlen($blocksListXML);

        $headerResource = "x-ms-date:$date\nx-ms-version:$version";
        $urlResource = "/$storageAccount/$containerName/$blobName\ncomp:blocklist";

        $arraySign = array();
        $arraySign[] = 'PUT'; /*HTTP Verb*/
        $arraySign[] = ''; /*Content-Encoding*/
        $arraySign[] = ''; /*Content-Language*/
        $arraySign[] = $fileLen; /*Content-Length (include value when zero)*/
        $arraySign[] = ''; /*Content-MD5*/
        $arraySign[] = 'text/plain; charset=UTF-8'; /*Content-Type*/
        $arraySign[] = ''; /*Date*/
        $arraySign[] = ''; /*If-Modified-Since */
        $arraySign[] = ''; /*If-Match*/
        $arraySign[] = ''; /*If-None-Match*/
        $arraySign[] = ''; /*If-Unmodified-Since*/
        $arraySign[] = ''; /*Range*/
        $arraySign[] = $headerResource; /*CanonicalizeHeaders*/
        $arraySign[] = $urlResource; /*CanonicalizeResource*/

        //--------------------//
        $str2sign = implode("\n", $arraySign);
        $authHeader = $this->signString($str2sign, $account_key, $storageAccount);
        //--------------------//
        try {
            $url = "https://$storageAccount.blob.core.windows.net/$containerName/$blobName?comp=blocklist";

            $headers = [
                'Authorization' => $authHeader,
                'x-ms-date' => $date,
                'x-ms-version' => $version,
                'Content-Length' => $fileLen,
                'Content-Type' => 'text/plain; charset=UTF-8',
            ];
            $data = $this->_caller->put($url, $blocksListXML, $headers);
            if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
                return $data;
            } else {
                Log::log('error', 'Exception while Put Block List ' . json_encode($data));
                throw new \Exception('Error Put Block List');
            }
        } catch (Exception $e) {
            Log::log('error', 'Exception while Put Block List ' . $e->getMessage());
            throw new \Exception('Error Put Block List');
        }
    }

    //upload azure blob
    public function uploadContainerBlobBlock($params = [])
    {
        $storageAccount = $params['storageAccount'];
        $containerName = $params['containerName'];
        $blobName = $params['blobName'];
        $account_key = $params['accountKey'];
        $dataString = $params['dataString'];
        $blockId = $params['blockId'];
        //-- Set String To Signature
        $date = gmdate('D, d M Y H:i:s \G\M\T');
        $version = "2019-12-12";
        $fileLen = strlen($dataString);
        $headerResource = "x-ms-date:$date\nx-ms-version:$version";
        $urlResource = "/$storageAccount/$containerName/$blobName\nblockid:$blockId\ncomp:block";
        $arraySign = array();
        $arraySign[] = 'PUT'; /*HTTP Verb*/
        $arraySign[] = ''; /*Content-Encoding*/
        $arraySign[] = ''; /*Content-Language*/
        $arraySign[] = $fileLen; /*Content-Length (include value when zero)*/
        $arraySign[] = ''; /*Content-MD5*/
        $arraySign[] = ''; /*Content-Type*/
        $arraySign[] = ''; /*Date*/
        $arraySign[] = ''; /*If-Modified-Since */
        $arraySign[] = ''; /*If-Match*/
        $arraySign[] = ''; /*If-None-Match*/
        $arraySign[] = ''; /*If-Unmodified-Since*/
        $arraySign[] = ''; /*Range*/
        $arraySign[] = $headerResource; /*CanonicalizeHeaders*/
        $arraySign[] = $urlResource; /*CanonicalizeResource*/
        $str2sign = implode("\n", $arraySign);
        //----------------------------------------------------//

        $authHeader = $this->signString($str2sign, $account_key, $storageAccount);
        try {
            $url = "https://$storageAccount.blob.core.windows.net/$containerName/$blobName?comp=block&blockid=$blockId";
            $headers = [
                'Authorization' => $authHeader,
                'x-ms-date' => $date,
                'x-ms-version' => $version,
                'Content-Length' => $fileLen,
            ];
            $data = $this->_caller->put($url, $dataString, $headers);
            if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
                return $data;
            } else {
                $data["url"] = $url;
                Log::log('error', 'Exception while Uploading Blob Block ' . json_encode($data));
                throw new \Exception('Error Uploading Blob Block');
            }
        } catch (Exception $e) {
            Log::log('error', 'Exception while Uploading Blob Block   ' . $e->getMessage());
            throw new \Exception('Error Uploading Blob Block');
        }
    }
    //-------------------------------------------------------//
    //delete blob
    public function deleteBlob($params = [])
    {
        $storageAccount = $params['storageAccount'];
        $containerName = $params['containerName'];
        $blobName = $params['blobName'];
        $account_key = $params['accountKey'];
        //-- Set String To Signature
        $date = gmdate('D, d M Y H:i:s \G\M\T');
        $version = "2019-12-12";
        $headerResource = "x-ms-date:$date\nx-ms-version:$version";
        $urlResource = "/$storageAccount/$containerName/$blobName";
        $arraySign = array();
        $arraySign[] = 'DELETE'; /*HTTP Verb*/
        $arraySign[] = ''; /*Content-Encoding*/
        $arraySign[] = ''; /*Content-Language*/
        $arraySign[] = ''; /*Content-Length (include value when zero)*/
        $arraySign[] = ''; /*Content-MD5*/
        $arraySign[] = ''; /*Content-Type*/
        $arraySign[] = ''; /*Date*/
        $arraySign[] = ''; /*If-Modified-Since */
        $arraySign[] = ''; /*If-Match*/
        $arraySign[] = ''; /*If-None-Match*/
        $arraySign[] = ''; /*If-Unmodified-Since*/
        $arraySign[] = ''; /*Range*/
        $arraySign[] = $headerResource; /*CanonicalizeHeaders*/
        $arraySign[] = $urlResource; /*CanonicalizeResource*/
        $str2sign = implode("\n", $arraySign);
        //----------------------------------------------------//
        $authHeader = $this->signString($str2sign, $account_key, $storageAccount);
        try {
            $url = "https://$storageAccount.blob.core.windows.net/$containerName/$blobName";
            $headers = [
                'Authorization' => $authHeader,
                'x-ms-date' => $date,
                'x-ms-version' => $version,
            ];

            $data = $this->_caller->delete($url, [], $headers);
            if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
                return $data;
            } else {
                Log::log('error', 'Exception while Deleting Blob ' . json_encode($data));
                throw new \Exception('Error Deleting Blob');
            }
        } catch (Exception $e) {
            Log::log('error', 'Exception while Deleting Blob ' . $e->getMessage());
            throw new \Exception('Error Deleting Blob');
        }
    }
    //-------------------------------------------------------//
    // Get SAS Signature
    public function getSASForBlob($accountName, $container, $blob, $expiry, $key)
    {
        $permissions = "r";
        /* Create the signature */
        $_arraySign = array();
        $_arraySign[] = $permissions;
        $_arraySign[] = '';
        $_arraySign[] = $expiry;
        $_arraySign[] = '/' . $accountName . '/' . $container . '/' . $blob;
        $_arraySign[] = '';
        $_arraySign[] = "2014-02-14"; //the API version is now required
        $_arraySign[] = '';
        $_arraySign[] = '';
        $_arraySign[] = '';
        $_arraySign[] = '';
        $_arraySign[] = '';

        $_str2sign = implode("\n", $_arraySign);
        return base64_encode(
            hash_hmac('sha256', urldecode(utf8_encode($_str2sign)), base64_decode($key), true)
        );
    }
    //-------------------------------------------------------//
    // Generate Blob
    public function getBlobUrl($accountName, $container, $blob, $expiry, $key)
    {
        $resourceType = "b";
        $permissions = "r";
        $_signature = $this->getSASForBlob($accountName, $container, $blob, $expiry, $key);
        /* Create the signed query part */
        $_parts = array();
        $_parts[] = (!empty($expiry)) ? 'se=' . urlencode($expiry) : '';
        $_parts[] = 'sr=' . $resourceType;
        $_parts[] = (!empty($permissions)) ? 'sp=' . $permissions : '';
        $_parts[] = 'sig=' . urlencode($_signature);
        $_parts[] = 'sv=2014-02-14';

        /* Create the signed blob URL */
        $_url = 'https://'
        . $accountName . '.blob.core.windows.net/'
        . $container . '/'
        . $blob . '?'
        . implode('&', $_parts);

        return $_url;
    }
    //-------------------------------------------------------//
    // Generate Blob
    public function getBlobDetails($accountName, $container, $blob, $key)
    {
        $expiry = gmdate('Y-m-d\TH:i:s\.u\0\Z', strtotime("+ 1 minutes"));
        $url = $this->getBlobUrl($accountName, $container, $blob, $expiry, $key);
        $data = $this->_caller->getStream($url, []);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return true;
        }
        return false;
    }
    //-------------------------------------------------------//
    //create azure resource group and store it in DB
    public function renameSubscription($subscriptionId)
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->renameSubscriptionUrl($subscriptionId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $body = '
        {
            "subscriptionName": "Microsoft Azure For Cloud O365 Backup"
        }
        ';
        $data = $this->_caller->post_($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while creating Azure ResourceGroup ' . json_encode($data));
            throw new \Exception('Error Creating Resource Group');
        }
    }
    //-------------------------------------------------------//
    public function createTable($subscriptionId, $accountName, $resourceGroup, $tableName)
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->createTableUrl($subscriptionId, $accountName, $resourceGroup, $tableName);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->put($url, '', $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', "Exception while creating Azure Table $url " . json_encode($data));
            throw new \Exception('Error Creating Table');
        }
    }
    //-------------------------------------------------------//
    public function getTable($subscriptionId, $accountName, $resourceGroup, $tableName, $userId = "")
    {
        $token = "Bearer " . $this->login($userId);
        $url = $this->_urlManager->createTableUrl($subscriptionId, $accountName, $resourceGroup, $tableName);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->get($url, '', $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Azure Table ' . json_encode($data));
            throw new \Exception('Error Getting Table');
        }
    }
    //-------------------------------------------------------//
    public function deleteTable($subscriptionId, $accountName, $resourceGroup, $tableName, $userId = "")
    {
        $token = "Bearer " . $this->login($userId);
        $url = $this->_urlManager->createTableUrl($subscriptionId, $accountName, $resourceGroup, $tableName);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $data = $this->_caller->delete($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while deleting Azure Table ' . json_encode($data));
            throw new \Exception('Error Getting Table');
        }
    }
    //-------------------------------------------------------//
    public function tableSelectData($accountName, $accountKey, $tableName, $limit, $filter = '')
    {
        $date = gmdate('D, d M Y H:i:s T', time());
        //---------------------------------------//
        $StringToSign = "GET\n" .
        '' . "\n" . //Content-MD5
        '' . "\n" . //Content-Type
        $date . "\n" .
            '/' . $accountName . '/' . $tableName . '()';
        $sig = base64_encode(
            hash_hmac('sha256', urldecode($StringToSign), base64_decode($accountKey), true)
        );
        $auth = "SharedKey $accountName:$sig";
        //---------------------------------------//
        $filter = str_replace("/", "_", $filter);
        $url = $this->_urlManager->tableSelectDataUrl($accountName, $tableName, $limit, urlencode($filter));
        $header = [
            'Authorization' => $auth,
            'Accept' => "application/json;odata=nometadata",
            'x-ms-date' => $date,
            'x-ms-version' => "2014-02-14",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Selecting Data from Table ' . json_encode($data));
            throw new \Exception('Error Selecting Data from Table');
        }
    }
    //-------------------------------------------------------//
    public function tableSelectPageData($accountName, $accountKey, $tableName, $limit, $nextPartition, $nextRow, $filter = '')
    {
        $date = gmdate('D, d M Y H:i:s T', time());
        //---------------------------------------//
        $StringToSign = "GET\n" .
        '' . "\n" . //Content-MD5
        '' . "\n" . //Content-Type
        $date . "\n" .
            '/' . $accountName . '/' . $tableName;
        $sig = base64_encode(
            hash_hmac('sha256', urldecode($StringToSign), base64_decode($accountKey), true)
        );
        $auth = "SharedKey $accountName:$sig";
        //---------------------------------------//
        $filter = str_replace("/", "_", $filter);
        $url = $this->_urlManager->tableSelectPageDataUrl($accountName, $tableName, $limit, $nextPartition, $nextRow, $filter);
        $header = [
            'Authorization' => $auth,
            'Accept' => "application/json;odata=nometadata",
            'x-ms-date' => $date,
            'x-ms-version' => "2014-02-14",
        ];
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Selecting Page Data from Table url=' . $url . '  ' . json_encode($data));
            throw new \Exception('Error Selecting Page Data from Table');
        }
    }
    //-------------------------------------------------------//
    public function tableInsertData($accountName, $accountKey, $tableName, $body)
    {
        $date = gmdate('D, d M Y H:i:s T', time());
        //---------------------------------------//
        $StringToSign = "POST\n" .
        '' . "\n" . //Content-MD5
        'application/json' . "\n" . //Content-Type
        $date . "\n" .
            '/' . $accountName . '/' . $tableName;
        $sig = base64_encode(
            hash_hmac('sha256', urldecode($StringToSign), base64_decode($accountKey), true)
        );
        $auth = "SharedKey $accountName:$sig";
        //---------------------------------------//
        $url = $this->_urlManager->tableInsertDataUrl($accountName, $tableName);
        $header = [
            'Authorization' => $auth,
            'Accept' => "application/json;odata=nometadata",
            'Content-Type' => "application/json",
            'x-ms-date' => $date,
            'x-ms-version' => "2014-02-14",
        ];
        $data = $this->_caller->post_($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Inserting Data from Table ' . json_encode($data));
            throw new \Exception('Error Inserting Data from Table');
        }
    }
    //-------------------------------------------------------//
    public function tableInsertDataBatchTest($accountName, $accountKey, $tableName, $items)
    {
        $date = gmdate('D, d M Y H:i:s T', time());
        //---------------------------------------//
        $StringToSign = "POST\n" .
        '' . "\n" . //Content-MD5
        'multipart/mixed; boundary=batch_e4071276-6bca-459b-af6b-c18e5eb42106' . "\n" . //Content-Type
        $date . "\n" .
            '/' . $accountName . '/$batch';
        $sig = base64_encode(
            hash_hmac('sha256', urldecode($StringToSign), base64_decode($accountKey), true)
        );
        $auth = "SharedKey $accountName:$sig";
        //---------------------------------------//
        $url = $this->_urlManager->tableInsertDataUrl($accountName, $tableName);
        $header = [
            'Authorization' => $auth,
            'x-ms-date' => $date,
            'x-ms-version' => "2017-07-29",
            'Accept' => "application/json; odata=nometadata",
            'DataServiceVersion' => "3.0",
            'MaxDataServiceVersion' => "3.0;NetFx",
            'Content-Type' => "multipart/mixed; boundary=batch_e4071276-6bca-459b-af6b-c18e5eb42106",
            'Accept-Charset' => "UTF-8",
            'Connection' => "Keep-Alive",
        ];
        $body = '--batch_e4071276-6bca-459b-af6b-c18e5eb42106' . "\n";
        $body .= 'Content-Type: multipart/mixed; boundary=changeset_52c5f249-48de-4ae5-89e6-b288a26099f1' . "\n";
        $body .= "\n";
        $body .= '--changeset_52c5f249-48de-4ae5-89e6-b288a26099f1' . "\n";
        $body .= 'Content-Type: application/http' . "\n";
        $body .= 'Content-Transfer-Encoding: binary' . "\n";
        $body .= "\n";
        $body .= 'POST https://co365lamayouzn05.table.core.windows.net/testTable HTTP/1.1' . "\n";
        $body .= 'Accept: application/json;odata=nometadata' . "\n";
        $body .= 'Content-Type: application/json' . "\n";
        $body .= 'Prefer: return-no-content' . "\n";
        $body .= 'DataServiceVersion: 3.0;' . "\n";
        $body .= "\n";
        $body .= '{"PartitionKey":"Channel_26", "RowKey":"1", "Rating":9, "Text":".NET..."}' . "\n";
        $body .= '--changeset_52c5f249-48de-4ae5-89e6-b288a26099f1--' . "\n";
        $body .= '--batch_e4071276-6bca-459b-af6b-c18e5eb42106--' . "\n";
        $data = $this->_caller->post_($url, $body, $header, false);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Inserting Data from Table ' . json_encode($data));
            throw new \Exception('Error Inserting Data from Table');
        }
    }
    //-------------------------------------------------------//
    public function tableInsertDataBatch($accountName, $accountKey, $tableName, $items)
    {
        $date = gmdate('D, d M Y H:i:s T', time());
        //---------------------------------------//
        $StringToSign = "POST\n" .
        '' . "\n" . //Content-MD5
        'multipart/mixed; boundary=batch_e4071276-6bca-459b-af6b-c18e5eb42106' . "\n" . //Content-Type
        $date . "\n" .
            '/' . $accountName . '/$batch';
        $sig = base64_encode(
            hash_hmac('sha256', urldecode($StringToSign), base64_decode($accountKey), true)
        );
        $auth = "SharedKey $accountName:$sig";
        //---------------------------------------//
        $url = $this->_urlManager->tableInsertDataUrl($accountName, $tableName);
        $header = [
            'Authorization' => $auth,
            'x-ms-date' => $date,
            'x-ms-version' => "2017-07-29",
            'Accept' => "application/json; odata=nometadata",
            'DataServiceVersion' => "3.0",
            'MaxDataServiceVersion' => "3.0;NetFx",
            'Content-Type' => "multipart/mixed; boundary=batch_e4071276-6bca-459b-af6b-c18e5eb42106",
            'Accept-Charset' => "UTF-8",
            'Connection' => "Keep-Alive",
        ];
        $body = '--batch_e4071276-6bca-459b-af6b-c18e5eb42106' . "\n";
        $body .= 'Content-Type: multipart/mixed; boundary=changeset_52c5f249-48de-4ae5-89e6-b288a26099f1' . "\n";
        $body .= "\n";
        foreach ($items as $value) {
            $body .= '--changeset_52c5f249-48de-4ae5-89e6-b288a26099f1' . "\n";
            $body .= 'Content-Type: application/http' . "\n";
            $body .= 'Content-Transfer-Encoding: binary' . "\n";
            $body .= "\n";
            $body .= 'POST https://' . $accountName . '.table.core.windows.net/' . $tableName . ' HTTP/1.1' . "\n";
            $body .= 'Accept: application/json;odata=nometadata' . "\n";
            $body .= 'Content-Type: application/json' . "\n";
            $body .= 'Prefer: return-no-content' . "\n";
            $body .= 'DataServiceVersion: 3.0;' . "\n";
            $body .= "\n";
            $body .= json_encode($value) . "\n";
        }
        $body .= '--changeset_52c5f249-48de-4ae5-89e6-b288a26099f1--' . "\n";
        $body .= '--batch_e4071276-6bca-459b-af6b-c18e5eb42106--' . "\n";
        $data = $this->_caller->post_($url, $body, $header, false);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            // $data["body"] = $body;
            return $data;
        } else {
            Log::log('error', 'Exception While Inserting Data from Table ' . json_encode($data));
            throw new \Exception('Error Inserting Data from Table');
        }
    }
    //-------------------------------------------------------//
    public function tableDeleteData($accountName, $accountKey, $tableName, $partition, $row)
    {
        $date = gmdate('D, d M Y H:i:s T', time());
        //---------------------------------------//
        $StringToSign = "DELETE\n" .
        '' . "\n" . //Content-MD5
        '' . "\n" . //Content-Type
        $date . "\n" .
            '/' . $accountName . '/' . $tableName . "(PartitionKey='$partition',RowKey='$row')";
        $sig = base64_encode(
            hash_hmac('sha256', urldecode($StringToSign), base64_decode($accountKey), true)
        );
        $auth = "SharedKey $accountName:$sig";
        //---------------------------------------//
        $url = $this->_urlManager->tableDeleteDataUrl($accountName, $tableName, $partition, $row);
        $header = [
            'Authorization' => $auth,
            'Accept' => "application/json;odata=nometadata",
            'x-ms-date' => $date,
            'x-ms-version' => "2014-02-14",
            'If-Match' => "*",
        ];
        $data = $this->_caller->delete($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception While Inserting Data from Table ' . json_encode($data));
            throw new \Exception('Error Inserting Data from Table');
        }
    }
    //-------------------------------------------------------//
}
