<?php

namespace App\Http\Controllers\Restore;

use App\Engine\Azure\ManagerAzure;
use App\Engine\Veeam\ManagerVeeam;
use App\Http\Controllers\Controller;
use App\Models\BacRestoreHistory;
use App\Models\BacRestoreHistoryDetail;
use App\Models\ViewOrganizationBackupJob;
use App\Models\ViewOrganizationRestoreHistory;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RestoreHistoryController extends Controller
{

    public $_managerAzure;
    public $_managerVeeam;
    public function __construct()
    {
        $this->_managerAzure = new ManagerAzure();
        $this->_managerVeeam = new ManagerVeeam();
    }
    //-------------------------------------------------------//
    public function main($kind)
    {
        //-----------------------------------------------------//
        $data = array('repo_kind' => $kind);
        if ($kind == "onedrive") {
            $data['users'] = $this->getOrganizationOnedriveUsers();
        }
        if (!view()->exists("restore-history.$kind")) {
            return abort(404);
        }
        return response()->view("restore-history.$kind", ["data" => $data]);
    }
    //-------------------------------------------------------//
    public function getOrganizationOnedriveUsers()
    {
        try {
            $res = [];
            $user = auth()->user();
            $organization = $user->organization;
            $users = $this->_managerVeeam->getOrganizationUsers($organization->veeam_organization_guid)['data']->results;
            foreach ($users as $user) {
                if (strpos($user->name, 'DiscoverySearchMailbox') === false && $user->type == 'User') {
                    array_push($res, $user);
                }
            }
            usort($res, function ($a, $b) {
                return strcmp(strtolower($a->displayName), strtolower($b->displayName));
            });
            return $res;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting users ' . $ex->getMessage());
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
    //-------------------------------------------------------//
    public function getOnedriveUsers()
    {
        try {
            $res = [];
            $user = auth()->user();
            $organization = $user->organization;
            $users = $this->_managerVeeam->getOrganizationUsers($organization->veeam_organization_guid)['data']->results;
            foreach ($users as $user) {
                if (strpos($user->name, 'DiscoverySearchMailbox') === false && $user->type == 'User') {
                    array_push($res, $user);
                }
            }
            usort($res, function ($a, $b) {
                return strcmp(strtolower($a->displayName), strtolower($b->displayName));
            });
            return $res;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting users ' . $ex->getMessage());
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
    //-------------------------------------------------------//
    public function getHistoryContent($kind)
    {
        $organization = auth()->user()->organization;
        $data = ViewOrganizationRestoreHistory::where("organization_id", $organization->id)->where("backup_job_kind", $kind)->get();
        return $data;
    }
    //-------------------------------------------------------//
    //Get Restore History Details
    public function getHistoryDetails($type, $id)
    {
        $history = BacRestoreHistory::with("details")->where('id', $id)->first();
        return $history;
    }
    //-------------------------------------------------------//
    //Cancel Restore
    public function cancelRestore(Request $request)
    {
        $history = BacRestoreHistory::where('id', $request->id)->first();
        if ($history->restoreSessionId) {
            $session = $this->_managerVeeam->getRestoreSession($history->restoreSessionId)['data'];
            if ($session->state != "Stopped") {
                $this->_managerVeeam->stopRestoreSession($history->restoreSessionId);
            }
        }
        $history->status = "Canceled";
        $history->save();
        return response()->json([], 200);
    }
    //-------------------------------------------------------//
    public function downloadExportedFile($kind, $historyDetailsId)
    {
        $details = BacRestoreHistoryDetail::where('id', $historyDetailsId)->with("restore_history")->first();
        //-------------------------------------//
        $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $details->restore_history->backup_job_id)->first();
        $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
        //-------------------------------------//
        try {
            //-------------------------------------//
            $blobDownloadLinkExpire = config('parameters.MINUTES_BEFORE_BLOB_LINK_EXPIRE');
            $endDate = gmdate('Y-m-d\TH:i:s\.u\0\Z', strtotime("+ $blobDownloadLinkExpire minutes"));
            //-------------------------------------//
            $accountName = $backupJobData->storage_account_name;
            $containerName = $backupJobData->restore_container;
            $account_key = $storageAccountKey;
            $blobName = $details->exported_file_name;
            //-------------------------------------//
            $details->last_download_date = Carbon::now();
            $details->save();
            //-------------------------------------//
            return $this->_managerAzure->getBlobUrl($accountName, $containerName, $blobName, $endDate, $account_key);
            //-------------------------------------//
        } catch (Exception $e) {
            Log::log('error', 'Exception While Downloading Blob ' . $e->getMessage());
            throw new \Exception('Error Downloading Blob');
        }
    }
    //-------------------------------------------------------//
    //Force Expire
    public function forceExpire(Request $request)
    {
        $history = BacRestoreHistory::where('id', $request->id)->first();
        //--------------------
        if (substr_count($history->sub_type, 'Export')) {
            foreach ($history->details as $item) {
                //-------------------//
                $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $history->backup_job_id)->first();
                $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
                $params = [
                    "storageAccount" => $backupJobData->storage_account_name,
                    "accountKey" => $storageAccountKey,
                    "containerName" => $backupJobData->restore_container,
                    "blobName" => $item->exported_file_name,
                ];
                //-------------------//
                if ($this->_managerAzure->getBlobDetails($backupJobData->storage_account_name, $backupJobData->restore_container, $item->exported_file_name, $storageAccountKey)) {
                    $this->_managerAzure->deleteBlob($params);
                }
            }
        }
        //--------------------
        $history->expiration_time = Carbon::now();
        $history->status = "Expired";
        $history->save();
        return response()->json([], 200);
    }
    //-------------------------------------------------------//
    //restore sessions page
    public function restoreSessionPage($kind, $restoreSessionId)
    {
        set_time_limit(0);
        $history = BacRestoreHistory::where('restore_session_guid', $restoreSessionId)->first();
        if (!$history) {
            return abort(404);
        }

        try {
            $user = auth()->user();
            //--------------------------------------------------//
            $data['history'] = $history;
            $data['historyId'] = $history->id;
            $data['kind'] = $kind;
            $data['repo_kind'] = $kind;
            //--------------------------------------------------//
            $sessionInfo = $this->_managerVeeam->getRestoreSession($history->restore_session_guid);
            //---------------------------//
            $tempDate = new DateTime($sessionInfo["data"]->creationTime);
            $tempDate->setTimeZone(new DateTimeZone($user->timezone));
            $data['sessionInfo']['startDate'] = $tempDate->format('Y-m-d H:i');
            //---------------------------//
            $tempDate = new DateTime($sessionInfo["data"]->endTime);
            $tempDate->setTimeZone(new DateTimeZone($user->timezone));
            $data['sessionInfo']['endDate'] = $tempDate->format('Y-m-d H:i');
            //---------------------------//
            $data['sessionInfo']['status'] = $sessionInfo["data"]->result;
            $data['sessionInfo']['details'] = optional($sessionInfo["data"])->details;
            //--------------------------------------------------//
            if ($kind == 'exchange') {
                if ($history->type == "mailbox") {
                    $data['item_type'] = "Mailbox";
                    $data['item_parent'] = "E-Mail";
                } else if ($history->type == "folder") {
                    $data['item_type'] = "Folder";
                    $data['item_parent'] = "Mailbox";
                } else {
                    $data['item_type'] = "Item";
                    $data['item_parent'] = "Mailbox";
                }
            } else if ($kind == 'onedrive') {
                if ($history->type == "onedrive") {
                    $data['item_type'] = "Onedrive";
                    $data['item_parent'] = "Url";
                } else if ($history->type == "folder") {
                    $data['item_type'] = "Folder";
                    $data['item_parent'] = "Onedrive";
                } else {
                    $data['item_type'] = "Item";
                    $data['item_parent'] = "Onedrive";
                }
            } else if ($kind == 'sharepoint') {
                if ($history->type == "site") {
                    $data['item_type'] = "Site";
                    $data['item_parent'] = "Url";
                } else if ($history->type == "folder") {
                    $data['item_type'] = "Folder";
                    $data['item_parent'] = "Site";
                } else {
                    $data['item_type'] = "Item";
                    $data['item_parent'] = "Site";
                }
            } else if ($kind == 'teams') {
                if ($history->type == "team") {
                    $data['item_type'] = "Team";
                    $data['item_parent'] = "Group Email";
                } else if ($history->type == "channel" || $history->type == "channel-posts" || $history->type == "channel-files") {
                    $data['item_type'] = "Channel";
                    $data['item_parent'] = "Team";
                } else {
                    $data['item_type'] = "Item";
                    $data['item_parent'] = "Team";
                }
            }
            //--------------------------------------------------//
            $tempDate = new DateTime($history->restore_point_time);
            $tempDate->setTimeZone(new DateTimeZone($user->timezone));
            $data['jobInfo']['jobTime'] = $tempDate->format('Y-m-d H:i');
            $data['jobInfo']['showDeleted'] = $history->is_restore_point_show_deleted ? "True" : "False";
            $data['jobInfo']['showVersion'] = $history->is_restore_point_show_version ? "True" : "False";
            //--------------------------------------------------//
            $options = json_decode($history->options);
            $data['restoreOptions'] = $options;
            //--------------------------------------------------//
            if ($kind == "onedrive") {
                $data['users'] = $this->getOrganizationOnedriveUsers();
            }
            //--------------------------------------------------//
            if (!view()->exists("restore-history.$kind-session")) {
                return abort(404);
            }
            return response()->view("restore-history.$kind-session", ["data" => $data]);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Restore Session Page  ' . $ex->getMessage());
            return response()->view("errors.500", [], 500);
        }
    }
    //-------------------------------------------------------//
    //Get Restore History Details
    public function getRestoreDetails($type, $id)
    {
        $data = BacRestoreHistoryDetail::where('restore_history_id', $id)->get();
        return $data;
    }
    //-------------------------------------------------------//
    public function getRestoreSessionInfo($type, $historyId)
    {
        //------------------------------------//
        $history = BacRestoreHistory::where('id', $historyId)->first();
        //------------------------------------//
        try {
            $data = $this->_managerVeeam->getRestoreSessionEvents($history->restore_session_guid)["data"]->results;
            return $data;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Restore Session Page  ' . $ex->getMessage());
            return response()->view("errors.500", [], 500);
        }
    }
    //-------------------------------------------------------//
    private function getStorageAccountSharedAccessKey($storageAccountName)
    {
        $organization = auth()->user()->organization;
        $storageKeys = $this->_managerAzure->getStorageAccountSharedAccessKeys($storageAccountName, $organization->azure_subscription_guid, $organization->azure_resource_group);
        $key1 = ($storageKeys->keys)[0]->value;
        return $key1;
    }
    //-------------------------------------------------------//
}
