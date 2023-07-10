<?php

namespace App\Http\Controllers\Restore;

use App\Engine\Azure\ManagerAzure;
use App\Engine\Veeam\ManagerVeeam;
use App\Jobs\ExportExchangeBackground;
use App\Jobs\RestoreExchangeBackground;
use App\Models\BacEDiscoveryJob;
use App\Models\BacRestoreHistory;
use App\Models\BacRestoreHistoryDetail;
use App\Models\BacTempBlobFile;
use App\Models\VeeamBackupJob;
use App\Models\ViewOrganizationBackupJob;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RestoreExchangeController extends Controller
{
    private $_managerVeeam;
    private $_managerAzure;

    public function __construct()
    {
        $this->_managerVeeam = new ManagerVeeam();
        $this->_managerAzure = new ManagerAzure();
    }

    //------------------------------------------------//
    //Create Restore Session
    public function createSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'time' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }

        //--------------------------------------//
        try {
            //--------------------------------------//
            if ($this->checkRestorePeriod()) {
                return response()->json(['message' => trans('variables.errors.restore_expiry_date')], 500);
            }
            //--------------------------------------//
            if (session('restoreExchangeSessionId')) {
                $session = $this->_managerVeeam->getRestoreSession(session('restoreExchangeSessionId'))['data'];
                if ($session->state != "Stopped") {
                    $this->_managerVeeam->stopRestoreSession(session('restoreExchangeSessionId'));
                }
                session()->forget('restoreExchangeSessionId');
            }
            //--------------------------------------//
        } catch (\Exception $ex) {
        }
        //--------------------------------------//
        try {
            if ($request->jobs == 'all') {
                $restoreSession = $this->createRestoreSession($request->time, $request->showDeleted, $request->showVersions);
            } else {
                $job = VeeamBackupJob::where("id", $request->jobs)->first();
                $restoreSession = $this->_managerVeeam->createJobRestoreSession($job->guid, $request->time, 'vex', $request->showDeleted, $request->showVersions);
            }
            //--------------------------------------//
            $mailboxes = $this->_managerVeeam->getExchangeMailBoxes($restoreSession->id)['data']->results;
            session()->put('restoreExchangeSessionId', $restoreSession->id);
            $data = $this->filterMailboxes($mailboxes, 'asc');
            return ['data' => $data];
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Creating Restore Sessions  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.create_restore_session')], 500);
        }
        //--------------------------------------//
    }
    //------------------------------------------------//
    public function generateDeviceCode(Request $request)
    {
        //-------------------------------------------//
        $validator = Validator::make($request->all(), [
            'jobTime' => 'required|string',
            'showDeleted' => 'required',
            'showVersions' => 'required',
            'jobType' => 'required',
        ]);
        //-------------------------------------------//
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 500);
        }
        //-------------------------------------------//
        $organization = auth()->user()->organization;
        //-------------------------------------------//
        try {
            $oldRestoreSessionId = session()->get("backgroundExchangeRestoreSessionGuid");
            if ($oldRestoreSessionId) {
                $oldRestoreSession = $this->_managerVeeam->getRestoreSession($oldRestoreSessionId)["data"];
                if ($oldRestoreSession->state == "Working")
                    $this->_managerVeeam->stopRestoreSession($oldRestoreSessionId);
                session()->forget("backgroundExchangeRestoreSessionGuid");
            }
            //-------------------------------------------//
            if ($request->jobType == "all") {
                $restoreSession = $this->_managerVeeam->createRestoreSession($organization->veeam_organization_guid, $request->jobTime, "vex", $request->showDeleted, $request->showVersions)["data"];
            } else {
                $backupJobData = VeeamBackupJob::where("id", $request->jobId)->first();
                $restoreSession = $this->_managerVeeam->createJobRestoreSession($backupJobData->guid, $request->jobTime, "vex", $request->showDeleted, $request->showVersions);
            }
            //-------------------------------------------//
            session()->put("backgroundExchangeRestoreSessionGuid", $restoreSession->id);
            $deviceCode = $this->_managerVeeam->generateRestoreSessionDeviceCode($restoreSession->id, $organization->veeam_aad_authentication_guid)["data"];
            //-------------------------------------------//
            return response()->json([
                "userCode" => $deviceCode->userCode,
                "restoreSessionGuid" => $restoreSession->id
            ]);
        } catch (Exception $e) {
            Log::log("error", "Error while generating restore session device code " . $e);
            return response()->json(["message" => __("variables.errors.generating_device_code")], 500);
        }
    }
    //------------------------------------------------//
    //Get Filtered Mailboxes
    public function getFilteredMailboxes(Request $request)
    {
        $sort = $request->sortBoxType;
        $filterBoxType = $request->filterBoxType;
        $letters = $request->letters;
        try {
            $this->checkRestoreSession();
            $mailboxes = $this->_managerVeeam->getExchangeMailBoxes(session('restoreExchangeSessionId'))['data']->results;
            return $this->filterMailboxes($mailboxes, ($sort == 'AZ' ? 'asc' : 'desc'), ($filterBoxType != 'all' ? $filterBoxType : ''), $letters);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Filtered Mailboxes  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.exchange.get_mailboxes')], 500);
        }
    }
    //------------------------------------------------//
    //Filter Mailboxes
    private function filterMailboxes($arr, $sorting = 'asc', $allMails = '', $letters = '')
    {
        foreach ($arr as $item) {
            if ($item->isArchive) {
                $temp = explode(' - ', $item->name);
                if (optional($temp)[1]) {
                    $item->name = $temp[1] . ' - ' . $temp[0];
                }
            }
        }
        if ($allMails == 'users') {
            foreach ($arr as $key => $item) {
                if ($item->isArchive) {
                    unset($arr[$key]);
                }
            }
        }
        $arr = array_values($arr);
        if ($allMails == 'archive') {
            foreach ($arr as $key => $item) {
                if (!$item->isArchive) {
                    unset($arr[$key]);
                }
            }
        }
        $arr = array_values($arr);
        if ($letters) {
            $letters = explode(',', $letters);
            foreach ($arr as $key => $item) {
                if (!in_array(str_split($item->name)[0], $letters)) {
                    unset($arr[$key]);
                }
            }
        }
        $arr = array_values($arr);
        if ($sorting == 'asc') {
            usort($arr, function ($a, $b) {
                return strcmp(strtolower($a->name), strtolower($b->name));
            });
        } else {
            usort($arr, function ($a, $b) {
                return -1 * strcmp(strtolower($a->name), strtolower($b->name));
            });
        }
        $arr = array_values($arr);
        return $arr;
    }
    //------------------------------------------------//
    //Get Mailbox Folders
    public function getMailBoxFolders($mailBoxId)
    {
        if ($mailBoxId == "" || $mailBoxId == null) {

            return response()->json(['message' => trans('variables.errors.exchange.mailbox_required')], 500);
        }
        try {
            $this->checkRestoreSession();
            $mailFolders = $this->_managerVeeam->getMailBoxFolders(session('restoreExchangeSessionId'), $mailBoxId)['data'];
            $folders = $mailFolders->results;
            $hasFolders = [];
            $childrenFolders = [];
            $parentFolders = [];
            //-------------------------------//
            foreach ($folders as $key => $item) {
                $item->mailboxId = $mailBoxId;
                if (optional($item->_links)->parent) {
                    $parent = explode('/', $item->_links->parent->href);
                    //------
                    $item->parentId = end($parent);
                    //------
                    if (!array_key_exists(end($parent), $parentFolders)) {
                        $parentFolders[end($parent)] = [];
                    }
                    //------
                    array_push($parentFolders[end($parent)], $folders[$key]);
                    array_push($hasFolders, end($parent));
                    array_push($childrenFolders, $item->id);
                }
            }
            //-------------------------------//
            //---- Add Attribute To Check if Has Folders
            foreach ($folders as $key => $item) {
                $item->hasFolders = false;
                if (in_array($item->id, $hasFolders)) {
                    $item->hasFolders = true;
                    $item->children = $parentFolders[$item->id];
                }
            }
            //-------------------------------//
            //---- Add Attribute Children of Every Folder
            foreach ($folders as $key => $item) {
                if (in_array($item->id, $childrenFolders)) {
                    unset($folders[$key]);
                }
            }
            //-------------------------------//
            return array_values($folders);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Mailbox Folders  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.exchange.get_folders')], 500);
        }
    }
    //------------------------------------------------//
    //Get Mailbox Folder Items
    public function getMailBoxFolderItems(Request $request)
    {
        $mailBoxId = $request->input('mailboxId');
        $folderId = $request->input('folderId');
        //-------------------------------------//
        if ($mailBoxId == "-1" || $folderId == "-1") {
            return [];
        }
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'mailboxId' => 'required',
            'mailboxTitle' => 'required',
            'folderId' => 'required',
            'folderTitle' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        //-------------------------------------//
        $mailboxTitle = $request->input('mailboxTitle');
        $folderTitle = $request->input('folderTitle');
        $offset = $request->input('offset');
        //--------------------//
        $limit = config('parameters.EXPLORING_RESTORE_ITEMS_LIMIT_COUNT');
        // $limit = 10000;
        //--------------------//
        $res = array();
        try {
            $this->checkRestoreSession();
            $data = $this->_managerVeeam->getMailBoxItems(session('restoreExchangeSessionId'), $mailBoxId, $folderId, $offset, $limit);
            $mailItems = $data['data'];
            foreach ($mailItems->results as $item) {
                $item->mailboxId = $mailBoxId;
                $item->mailboxTitle = $mailboxTitle;
                $item->folderId = $folderId;
                $item->folderTitle = $folderTitle;
                $item->limit = $mailItems->limit;
                $item->offset = $mailItems->offset;
                array_push($res, $item);
            }
            return $res;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Mailbox Folder Items  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.exchange.get_items')], 500);
        }
    }
    //------------------------------------------------//
    //Export Mailboxes
    public function exportMailBoxToPst(Request $request)
    {
        $mailBoxes = $request->input('mailBoxes');
        if ($mailBoxes == "" || $mailBoxes == "[]" || $mailBoxes == null) {
            return response()->json(['message' => trans('variables.errors.exchange.mailbox_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->All();
        $user = auth()->user();
        $organization = $user->organization;
        //-------------------------------------//
        $jobId = $requestData['jobId'];
        try {
            //--------------------------------//
            $mailBoxArr = json_decode($mailBoxes);
            $options['enablePstSizeLimit'] = $requestData['enablePstSizeLimit'];
            $options['sizeLimit'] = $requestData['sizeLimit'];
            //--------------------------------//
            $history = new BacRestoreHistory();
            $history->organization_id = $organization->id;
            $history->backup_job_id = $requestData['jobId'];
            $history->items_count = count($mailBoxArr);
            $history->name = $requestData["restoreJobName"];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->type = 'mailbox';
            $history->sub_type = 'Export Mailboxes';
            $history->options = json_encode($options);
            $history->status = 'In Progress';
            $history->request_time = Carbon::now();
            $history->save();
            //--------------------------------//
            foreach ($mailBoxArr as $item) {
                //-----------------
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->name;
                $details->item_parent_name = $item->email;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $jobId)->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
            ];
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            //--------------------------------//
            dispatch(new ExportExchangeBackground(auth()->id(), $history->id, 'box', $storageData, $sessionData));
            //--------------------------------//
            return response()->json(['message' => trans('variables.success.exchange.export_mailbox')], 200);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Exporting Mailboxes ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.exchange.export_mailbox')], 500);
        }
    }
    //------------------------------------------------//
    //Export Mailbox Folders
    public function exportMailBoxFolderToPst(Request $request)
    {
        $folders = $request->input('folders');
        if ($folders == "" || $folders == '[]' || $folders == null) {
            return response()->json(['message' => trans('variables.errors.exchange.mailbox_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->All();
        $user = auth()->user();
        $organization = $user->organization;
        //-------------------------------------//
        $jobId = $requestData['jobId'];
        //-------------------------------------//
        try {
            //--------------------------------//
            $foldersArr = json_decode($folders);
            $options['enablePstSizeLimit'] = $requestData['enablePstSizeLimit'];
            $options['sizeLimit'] = $requestData['sizeLimit'];
            //--------------------------------//
            $history = new BacRestoreHistory();
            $history->organization_id = $organization->id;
            $history->backup_job_id = $requestData['jobId'];
            $history->items_count = count($foldersArr);
            $history->name = $requestData["restoreJobName"];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->type = 'folder';
            $history->sub_type = 'Export Folders';
            $history->status = 'In Progress';
            $history->options = json_encode($options);
            $history->request_time = Carbon::now();
            $history->save();
            //--------------------------------//
            foreach ($foldersArr as $folder) {
                //-----------------
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $folder->id;
                $details->item_name = $folder->name;
                $details->item_parent_id = $folder->mailboxId;
                $details->item_parent_name = $folder->mailboxName;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $jobId)->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
            ];
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            //--------------------------------//
            dispatch(new ExportExchangeBackground(auth()->id(), $history->id, 'folder', $storageData, $sessionData));
            //--------------------------------//
            return response()->json(['message' => trans('variables.success.exchange.export_mailbox_folder')], 200);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Exporting Mailbox Folders ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.exchange.export_mailbox_folder')], 500);
        }
    }
    //------------------------------------------------//
    //Export Mailbox Folders Items
    public function exportMailBoxFolderItemsToPst(Request $request, $isZip = false)
    {
        $items = $request->input('items');
        if ($items == null || $items == '' || $items == '[]') {
            return response()->json(['message' => trans('variables.errors.exchange.items_required')], 500);
        }
        //----------------------------------------------//
        $requestData = $request->All();
        $user = auth()->user();
        $organization = $user->organization;
        //----------------------------------------------//
        $jobId = $requestData['jobId'];
        try {
            $itemsArr = json_decode($items);
            $history = new BacRestoreHistory();
            $history->backup_job_id = $requestData['jobId'];
            $history->organization_id = $organization->id;
            $history->status = 'In Progress';
            $history->type = 'item';
            $history->sub_type = 'Export Mailbox Items';
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];

            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->save();
            //--------------------------------//
            $count = 0;
            foreach ($itemsArr as $item) {
                $count += count($item->items);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->items);
                $details->item_name = $item->mailboxTitle . '-items';
                $details->item_parent_id = $item->mailboxId;
                $details->item_parent_name = $item->mailboxTitle;
                $details->status = 'In Progress';
                $details->save();
            }
            $history->items_count = $count;
            $history->name = $requestData["restoreJobName"];
            $history->save();
            //--------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $jobId)->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
                'isZip' => $isZip,
            ];
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            //--------------------------------//
            dispatch(new ExportExchangeBackground(auth()->id(), $history->id, 'item', $storageData, $sessionData));
            //--------------------------------//
            return response()->json(['message' => trans('variables.success.exchange.export_mailbox_item')], 200);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while exporting mailbox items ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.exchange.export_mailbox_item')], 500);
        }
    }
    //------------------------------------------------//
    //Export Mailbox Folders Items
    public function exportMailBoxFolderItemsToZip(Request $request)
    {
        $items = $request->input('items');
        if ($items == null || $items == '' || $items == '[]') {
            return response()->json(['message' => trans('variables.errors.exchange.items_required')], 500);
        }
        //----------------------------------------------//
        $requestData = $request->All();
        $user = auth()->user();
        $organization = $user->organization;
        //----------------------------------------------//
        $jobId = $requestData['jobId'];
        try {
            $itemsArr = json_decode($items);
            $history = new BacRestoreHistory();
            $history->organization_id = $organization->id;
            $history->backup_job_id = $requestData['jobId'];
            $history->status = 'In Progress';
            $history->type = 'item';
            $history->sub_type = 'Export Mailbox Items';
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->save();
            //--------------------------------//
            $count = 0;
            foreach ($itemsArr as $item) {
                $count += count($item->items);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->items);
                $details->item_name = $item->mailboxTitle . '-items';
                $details->item_parent_id = $item->mailboxId;
                $details->item_parent_name = $item->mailboxTitle;
                $details->status = 'In Progress';
                $details->save();
            }
            $history->items_count = $count;
            $history->name = $history->items_count . '_MailboxFoldersItems_' . date("Ymd_His");
            $history->save();
            //--------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $jobId)->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
                'isZip' => true,
            ];
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            //--------------------------------//
            dispatch(new ExportExchangeBackground(auth()->id(), $history->id, 'item', $storageData, $sessionData));
            //--------------------------------//
            return response()->json(['message' => trans('variables.success.exchange.export_mailbox_item')], 200);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while exporting mailbox items ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.exchange.export_mailbox_item')], 500);
        }
    }
    //------------------------------------------------//
    //Download Mailbox Folders Items
    public function downloadMultiItems(Request $request)
    {
        $mailboxId = $request->input('mailboxId');
        $items = $request->input('items');
        if ($mailboxId == "" || $mailboxId == null || $items == '' || $items == '') {
            return response()->json(['message' => trans('variables.errors.exchange.items_required')], 500);
        }
        try {
            $this->checkRestoreSession();
            //--------------------------------//
            $itemsArr = json_decode($items);
            $downloadCountLimit = (int) config('parameters.DIRECT_DOWNLOAD_ITEMS_COUNT_LIMIT');
            //--------------------------------//
            if ($downloadCountLimit >= count($itemsArr)) {
                $url = $this->downloadMultiItemsDirectly($request->all());
                return response()->json(['file' => $url]);
            } else {
                $items = [
                    [
                        "mailboxId" => $mailboxId,
                        "mailboxTitle" => $request->mailboxTitle,
                        "folderTitle" => $request->folderTitle,
                        "items" => json_decode($request->items),
                    ],
                ];
                $request->merge(['items' => json_encode($items)]);
                return $this->exportMailBoxFolderItemsToZip($request);
            }
            //--------------------------------//
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Downloading Mailbox Items 1' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.exchange.download_mailbox_item')], 500);
        }
    }
    //------------------------------------------------//
    private function downloadMultiItemsDirectly($requestData = [])
    {
        set_time_limit(300);
        $mailboxId = $requestData['mailboxId'];
        $items = $requestData['items'];
        try {
            $data = $this->_managerVeeam->exportMailBoxFolderItemsToMSG(session('restoreExchangeSessionId'), $mailboxId, $items);
            //-----------------------------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $requestData['jobId'])->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
            //-----------------------------------------------------//
            $body = (object) $data['body'];
            $blobName = 'temp_' . date('Ymd_His') . '.zip';
            $blockListString = '<?xml version="1.0" encoding="utf-8"?><BlockList>';
            $count = 0;
            $blobSize = 0;
            //--------------------
            while (!$body->eof()) {
                $count++;
                //----- Set Block Id
                $blockCount = str_pad($count, 20, "0", STR_PAD_LEFT);
                $blockId = base64_encode("$blockCount");
                //--------------------
                $blockListString .= "<Latest>$blockId</Latest>";
                //--------------------
                // Put Block
                $blockSize = config('parameters.UPLOAD_BLOB_BLOCK_MEGA_SIZE');
                $blockContent = $body->read($blockSize * 1024 * 1024);
                $blobSize += strlen($blockContent);
                $this->_managerAzure->uploadContainerBlobBlock([
                    "storageAccount" => $backupJobData->storage_account_name,
                    "accountKey" => $storageAccountKey,
                    "containerName" => $backupJobData->restore_container,
                    "blobName" => $blobName,
                    "dataString" => $blockContent,
                    "blockId" => $blockId,
                ]);
            }
            $blockListString .= '</BlockList>';
            //-----------------
            $this->_managerAzure->putBlobBlockList([
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
                'blobName' => $blobName,
                'blocksListXML' => $blockListString,
            ]);
            //-----------------------------------------------------//
            $blobDownloadLinkExpire = config('parameters.MINUTES_BEFORE_BLOB_LINK_EXPIRE');
            $endDate = gmdate('Y-m-d\TH:i:s\.u\0\Z', strtotime("+ $blobDownloadLinkExpire minutes"));
            //-------------------------------------//
            $accountName = $backupJobData->storage_account_name;
            $containerName = $backupJobData->restore_container;
            $account_key = $storageAccountKey;
            //-------------------------------------//
            $deleteFile = new BacTempBlobFile();
            $deleteFile->file_name = $blobName;
            $deleteFile->expiration_time = Carbon::now()->addMinute(config('parameters.TEMP_BLOB_FILE_EXPIRATION_MINUTES'));
            $deleteFile->storage_account_id = $backupJobData->storage_account_id;
            $deleteFile->save();
            //-------------------------------------//
            return $this->_managerAzure->getBlobUrl($accountName, $containerName, $blobName, $endDate, $account_key);
            //-----------------------------------------------------//
        } catch (Exception $e) {
            Log::log('error', 'Exception While Download Mailbox Items' . $e);
        }
    }
    //------------------------------------------------//
    //Download Mailbox Item
    public function downloadSingleItem(Request $request)
    {
        set_time_limit(0);
        $mailboxId = $request->input('mailboxId');
        $itemId = $request->input('itemId');
        if ($mailboxId == "" || $mailboxId == null || $itemId == '' || $itemId == '') {
            return response()->json(['message' => trans('variables.success.exchange.items_required')], 500);
        }
        try {
            $this->checkRestoreSession();
            //--------------------------------//
            try {
                $data = $this->_managerVeeam->exportMailBoxFolderItemToMSG(session('restoreExchangeSessionId'), $mailboxId, $itemId);
                //-----------------------------------------------------//
                $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $request->jobId)->first();
                $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
                //-----------------------------------------------------//
                $body = (object) $data['body'];
                $blobName = 'temp_' . date('Ymd_His') . '.msg';
                $blockListString = '<?xml version="1.0" encoding="utf-8"?><BlockList>';
                $count = 0;
                $blobSize = 0;
                //--------------------
                while (!$body->eof()) {
                    $count++;
                    //----- Set Block Id
                    $blockCount = str_pad($count, 20, "0", STR_PAD_LEFT);
                    $blockId = base64_encode("$blockCount");
                    //--------------------
                    $blockListString .= "<Latest>$blockId</Latest>";
                    //--------------------
                    // Put Block
                    $blockSize = config('parameters.UPLOAD_BLOB_BLOCK_MEGA_SIZE');
                    $blockContent = $body->read($blockSize * 1024 * 1024);
                    $blobSize += strlen($blockContent);
                    $this->_managerAzure->uploadContainerBlobBlock([
                        "storageAccount" => $backupJobData->storage_account_name,
                        "accountKey" => $storageAccountKey,
                        "containerName" => $backupJobData->restore_container,
                        "blobName" => $blobName,
                        "dataString" => $blockContent,
                        "blockId" => $blockId,
                    ]);
                }
                $blockListString .= '</BlockList>';
                //-----------------
                $this->_managerAzure->putBlobBlockList([
                    "storageAccount" => $backupJobData->storage_account_name,
                    "accountKey" => $storageAccountKey,
                    "containerName" => $backupJobData->restore_container,
                    'blobName' => $blobName,
                    'blocksListXML' => $blockListString,
                ]);
                //-----------------------------------------------------//
                $blobDownloadLinkExpire = config('parameters.MINUTES_BEFORE_BLOB_LINK_EXPIRE');
                $endDate = gmdate('Y-m-d\TH:i:s\.u\0\Z', strtotime("+ $blobDownloadLinkExpire minutes"));
                //-------------------------------------//
                $accountName = $backupJobData->storage_account_name;
                $containerName = $backupJobData->restore_container;
                $account_key = $storageAccountKey;
                //-------------------------------------//
                $deleteFile = new BacTempBlobFile();
                $deleteFile->file_name = $blobName;
                $deleteFile->expiration_time = Carbon::now()->addMinute(config('parameters.TEMP_BLOB_FILE_EXPIRATION_MINUTES'));
                $deleteFile->storage_account_id = $backupJobData->storage_account_id;
                $deleteFile->save();
                //-------------------------------------//
                $url = $this->_managerAzure->getBlobUrl($accountName, $containerName, $blobName, $endDate, $account_key);
                //-----------------------------------------------------//
            } catch (Exception $e) {
                Log::log('error', 'Exception While Download Mailbox Items' . $e);
            }
            //--------------------------------//
            return response()->json(['file' => $url]);
            //--------------------------------//
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Downloading Mailbox Items ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.exchange.download_mailbox_items')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Mailbox To Original Location
    public function restoreMailBoxOriginal(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'restoreSessionGuid' => 'required',
            'deviceCode' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        //-------------------------------------//
        $mailboxes = $request->input('mailboxes');
        if ($mailboxes == "" || $mailboxes == null || $mailboxes == "[]") {
            return response()->json(['message' => trans('variables.errors.exchange.mailbox_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->All();
        $user = auth()->user();
        $organization = $user->organization;
        //-------------------------------------//
        if(!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $mailboxes = $requestData['mailboxes'];
        try {
            //---- Set Restore Options
            $options = [];
            $options['skipUnresolved'] = optional($requestData)['skipUnresolved'] ? 'true' : 'false';
            $options['changedItems'] = optional($requestData)['changedItems'] ? 'true' : 'false';
            $options['deletedItems'] = optional($requestData)['deletedItems'] ? 'true' : 'false';
            $options['markRestoredAsunread'] = optional($requestData)['markRestoredAsunread'] ? 'true' : 'false';
            $options['excludeDrafts'] = optional($requestData)['excludeDrafts'] ? 'true' : 'false';
            $options['excludeDeletedItems'] = optional($requestData)['excludeDeletedItems'] ? 'true' : 'false';
            $options['excludeLitigationHoldItems'] = optional($requestData)['excludeLitigationHoldItems'] ? 'true' : 'false';
            $options['excludeInplaceHolditems'] = optional($requestData)['excludeInplaceHolditems'] ? 'true' : 'false';
            $options['RecentItemRestorePeriod'] = optional($requestData)['RecentItemRestorePeriod'] ? 'true' : 'false';
            $options['daysNumber'] = (optional($requestData)['daysNumber'] && optional($requestData)['RecentItemRestorePeriod']) ? $requestData['daysNumber'] : '';
            //--------------------------------------//
            $mailboxesArr = json_decode($mailboxes);
            $history = new BacRestoreHistory();
            $history->organization_id = $organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->backup_job_id = $requestData['jobId'];
            $history->items_count = count($mailboxesArr);
            $history->name = $requestData["restoreJobName"];
            $history->type = 'mailbox';
            $history->sub_type = 'Restore Mailboxes';
            $history->status = 'In Progress';
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            foreach ($mailboxesArr as $item) {
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->name;
                $details->item_parent_name = $item->email;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $exchangeData = [
                'deviceCode' => $userCode,
                'mailboxes' => $mailboxes,
            ];
            //--------------------------------------//
            dispatch(new RestoreExchangeBackground(auth()->id(), $history->id, 'restoreMailboxOriginal', [], $exchangeData));
            //--------------------------------------//
            session()->forget("backgroundExchangeRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.exchange.restore_mailbox_original')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Mailboxes' . $e);
            return response()->json(['message' => trans('variables.errors.exchange.restore_mailbox_original')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Mailbox To Another Location
    public function restoreMailBoxAnother(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required',
            'mailbox' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        //-------------------------------------//
        $mailboxes = $request->input('mailboxes');
        if ($mailboxes == "" || $mailboxes == null || $mailboxes == "[]") {
            return response()->json(['message' => trans('variables.errors.exchange.mailbox_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->All();
        $user = auth()->user();
        $organization = $user->organization;
        //-------------------------------------//
        if(!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $mailboxes = $requestData['mailboxes'];
        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $toFolder = $requestData['folder'];
        $toMailBox = $requestData['mailbox'];
        $mailboxesArr = json_decode($mailboxes);
        try {
            //---- Set Restore Options
            $options['changedItems'] = optional($requestData)['changedItems'] ? "true" : 'false';
            $options['deletedItems'] = optional($requestData)['deletedItems'] ? "true" : 'false';
            $options['markRestoredAsunread'] = optional($requestData)['markRestoredAsunread'] ? "true" : 'false';
            $options['excludeDrafts'] = optional($requestData)['excludeDrafts'] ? "true" : 'false';
            $options['excludeDeletedItems'] = optional($requestData)['excludeDeletedItems'] ? "true" : 'false';
            $options['excludeLitigationHoldItems'] = optional($requestData)['excludeLitigationHoldItems'] ? "true" : 'false';
            $options['excludeInplaceHolditems'] = optional($requestData)['excludeInplaceHolditems'] ? "true" : 'false';
            $options['toMailBox'] = $toMailBox;
            $options['toFolder'] = $toFolder;
            //-------------------------------//
            $history = new BacRestoreHistory();
            $history->organization_id = $organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'mailbox';
            $history->sub_type = 'Restore Mailboxes to Another Location';
            $history->items_count = count($mailboxesArr);
            $history->name = $requestData["restoreJobName"];
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];

            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            foreach ($mailboxesArr as $item) {
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->name;
                $details->item_parent_name = $item->email;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $exchangeData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreExchangeBackground(auth()->id(), $history->id, 'restoreMailboxAnother', $sessionData, $exchangeData));
            //--------------------------------------//
            session()->forget("backgroundExchangeRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.exchange.restore_mailbox_another')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Mailboxes' . $e);
            return response()->json(['message' => trans('variables.errors.exchange.restore_mailbox_another')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Mailbox Folder
    public function restoreFolder(Request $request)
    {
        if ($request->input('restoreType') == 'another') {
            return $this->restoreFolderAnother($request);
        }
        return $this->restoreFolderOriginal($request);
    }
    //------------------------------------------------//
    //Restore Mailbox Folder To Original Location
    public function restoreFolderOriginal(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        //-------------------------------------//
        $folders = $request->input('folders');
        if ($folders == "" || $folders == null || $folders == '[]') {
            return response()->json(['message' => trans('variables.errors.exchange.folders_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->All();
        $user = auth()->user();
        $organization = $user->organization;
        //-------------------------------------//
        if(!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $folders = $requestData['folders'];
        try {
            $foldersArr = json_decode($folders);
            //--------------------------------------//
            $history = new BacRestoreHistory();
            $history->organization_id = $organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'folder';
            $history->sub_type = 'Restore Folders to Original Location';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->items_count = count($foldersArr);
            $history->name = $requestData["restoreJobName"];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->save();
            //--------------------------------------//
            foreach ($foldersArr as $folder) {
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_parent_id = $folder->mailboxId;
                $details->item_id = $folder->id;
                $details->item_name = $folder->folder;
                $details->item_parent_name = $folder->mailboxName;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $exchangeData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreExchangeBackground(auth()->id(), $history->id, 'restoreFolderOriginal', $sessionData, $exchangeData));
            //--------------------------------------//
            session()->forget("backgroundExchangeRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.exchange.restore_folder_original')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Mailbox Folders To Original' . $e);
            return response()->json(['message' => trans('variables.errors.exchange.restore_folder_original')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Mailbox Folder To Another Location
    public function restoreFolderAnother(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'mailbox' => 'required|email',
            'deviceCode' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        //-------------------------------------//
        $folders = $request->input('folders');
        if ($folders == "" || $folders == null || $folders == '[]') {
            return response()->json(['message' => trans('variables.errors.exchange.folders_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->All();
        $user = auth()->user();
        $organization = $user->organization;
        //-------------------------------------//
        if(!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $toFolder = optional($requestData)['folder'];
        $toMailBox = $requestData['mailbox'];
        $folderType = $requestData['folderType'];
        if ($folders == "" || $folders == null) {
            return response()->json(['message' => trans('variables.errors.exchange.folders_required')], 500);
        }
        try {
            //----- Set Restore Options
            $options = [];
            $options['changedItems'] = optional($requestData)['changedItems'] ? 'true' : 'false';
            $options['deletedItems'] = optional($requestData)['deletedItems'] ? 'true' : 'false';
            $options['markRestoredAsunread'] = optional($requestData)['markRestoredAsunread'] ? 'true' : 'false';
            //-----------------------------//
            $foldersArr = json_decode($folders);
            //--------------------------------------//
            $history = new BacRestoreHistory();
            $history->organization_id = $organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'folder';
            $history->items_count = count($foldersArr);
            $history->name = $requestData["restoreJobName"];
            $history->sub_type = 'Restore Folders to Another Location';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];

            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $options['toMailBox'] = $toMailBox;
            $options['folderType'] = $folderType;
            $options['toFolder'] = $toFolder;
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            foreach ($foldersArr as $folder) {
                if ($folderType == 'custom') {
                    $folderType = $folder->folder;
                }
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_parent_id = $folder->mailboxId;
                $details->item_id = $folder->id;
                $details->item_name = $folder->folder;
                $details->item_parent_name = $folder->mailboxName;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $exchangeData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreExchangeBackground(auth()->id(), $history->id, 'restoreFolderAnother', $sessionData, $exchangeData));
            //--------------------------------------//
            session()->forget("backgroundExchangeRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.exchange.restore_folder_another')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Mailbox Folders To Original' . $e);
            return response()->json(['message' => trans('variables.errors.exchange.restore_folder_another')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Mailbox Items
    public function restoreItem(Request $request)
    {
        if ($request->input('restoreType') == 'another') {
            return $this->restoreItemAnother($request);
        }
        return $this->restoreItemOriginal($request);
    }
    //------------------------------------------------//
    //Restore Mailbox Items To Original Location
    public function restoreItemOriginal(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        //-------------------------------------//
        $items = $request->input('items');
        $itemsArr = json_decode($items);
        if ($items == "" || $items == null || $items == '[]') {
            return response()->json(['message' => trans('variables.errors.exchange.items_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->All();
        $user = auth()->user();
        $organization = $user->organization;
        //-------------------------------------//
        if(!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];

        try {
            $history = new BacRestoreHistory();
            $history->organization_id = $organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'item';
            $history->sub_type = 'Restore Mailbox Items to Original Location';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->save();
            //---------------------------------//
            $count = 0;
            foreach ($itemsArr as $item) {
                $count += count($item->items);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_parent_id = $item->mailboxId;
                $details->item_parent_name = $item->mailboxTitle;
                $details->item_name = $item->mailboxTitle;
                $details->item_id = json_encode($item->items);
                $details->status = 'In Progress';
                $details->save();
            }
            $history->items_count = $count;
            $history->name = $requestData["restoreJobName"];
            $history->save();
            //--------------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $exchangeData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreExchangeBackground(auth()->id(), $history->id, 'restoreMailboxItemsOriginal', $sessionData, $exchangeData));
            //--------------------------------------//
            session()->forget("backgroundExchangeRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.exchange.restore_item_original')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Mailbox Folders To Original' . $e);
            return response()->json(['message' => trans('variables.errors.exchange.restore_item_original')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Mailbox Items To Another Location
    public function restoreItemAnother(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'mailbox' => 'required|email',
            'deviceCode' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        //-------------------------------------//
        $items = $request->input('items');
        $itemsArr = json_decode($items);
        if ($items == "" || $items == null || $items == "[]") {
            return response()->json(['message' => trans('variables.errors.exchange.restore_folder_original')], 500);
        }
        //-------------------------------------//
        $requestData = $request->All();
        $user = auth()->user();
        $organization = $user->organization;
        //-------------------------------------//
        if(!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];

        $folderTitle = $requestData['folderTitle'];
        $toMailBox = $requestData['mailbox'];
        $toFolder = optional($requestData)['folder'];
        $folderType = $requestData['folderType'];
        if ($items == "" || $items == null || $items == []) {
            return response()->json(['message' => trans('variables.errors.exchange.items_required')], 500);
        }
        try {
            //----- Set Restore Options
            $options = [];
            $options['changedItems'] = optional($requestData)['changedItems'] ? 'true' : 'false';
            $options['deletedItems'] = optional($requestData)['deletedItems'] ? 'true' : 'false';
            $options['markRestoredAsunread'] = optional($requestData)['markRestoredAsunread'] ? 'true' : 'false';
            //-----------------------------//
            if ($folderType == 'original') {
                $toFolder = $folderTitle;
            }
            //---------------------------------//
            $itemsArr = json_decode($items);
            //---------------------------------//
            $history = new BacRestoreHistory();
            $history->organization_id = $organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'item';
            $history->sub_type = 'Restore Mailbox Items to Another Location';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $options['toMailBox'] = $toMailBox;
            $options['folderType'] = $folderType;
            $options['toFolder'] = $toFolder;
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            $count = 0;
            foreach ($itemsArr as $item) {
                $count += count($item->items);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_parent_id = $item->mailboxId;
                $details->item_parent_name = $item->mailboxTitle;
                $details->item_name = $item->mailboxTitle;
                $details->item_id = json_encode($item->items);
                $details->status = 'In Progress';
                $details->save();
            }
            $history->items_count = $count;
            $history->name = $requestData["restoreJobName"];
            $history->save();
            //--------------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $exchangeData = [
                'userCode' => $userCode,

            ];
            //--------------------------------------//
            dispatch(new RestoreExchangeBackground(auth()->id(), $history->id, 'restoreMailboxItemsAnother', $sessionData, $exchangeData));
            //--------------------------------------//
            session()->forget("backgroundExchangeRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.exchange.restore_item_another')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Mailbox Items To Another ' . $e);
            return response()->json(['message' => trans('variables.errors.exchange.restore_item_another')], 500);
        }
    }
    //------------------------------------------------//
    //Send Mailbox Items
    public function sendItem(Request $request)
    {
        $items = $request->input('items');
        if ($items == "" || $items == null) {
            return response()->json(['message' => trans('variables.errors.exchange.items_required')], 500);
        }
        return true;
    }
    //------------------------------------------------//
    public function getOrganizationUsers()
    {
        try {
            $res = [];
            $user = auth()->user();
            $organization = $user->organization;
            $users = $this->_managerVeeam->getOrganizationUsers($organization->veeam_organization_guid)['data']->results;
            $groups = $this->_managerVeeam->getOrganizationGroups($organization->veeam_organization_guid)['data']->results;
            foreach ($users as $user) {
                if (optional($user)->email) {
                    $item = $user;
                    $item->name = $user->email;
                    $item->displayName = $user->name;
                    array_push($res, $item);
                } else {
                    array_push($res, $user);
                }
            }
            foreach ($groups as $group) {
                if (optional($group)->name) {
                    array_push($res, $group);
                }
            }
            usort($res, function ($a, $b) {
                return strcmp(strtolower($a->displayName), strtolower($b->displayName));
            });
            return $res;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting users & groups' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.get_organization_users')], 500);
        }
    }
    //------------------------------------------------//
    //Check Restore Session
    private function checkRestoreSession()
    {
        if (request()->hasHeader("fromHistory")) {
            if (session('restoreExchangeSessionId')) {
                $sessionInfo = $this->_managerVeeam->getRestoreSession(session('restoreExchangeSessionId'))['data'];
                if ($sessionInfo->state == "Working") {
                    return true;
                }
            }
            session()->forget('restoreExchangeSessionId');
            if (request()->hasHeader("ediscoveryId")) {
                $ediscoveryJob = BacEDiscoveryJob::with(["backup_job"])->where('id', request()->header("ediscoveryId"))->first();
                if (!$ediscoveryJob->restore_point_type == "all") {
                    $restoreSession = $this->createRestoreSession($ediscoveryJob->restore_point_time, $ediscoveryJob->is_restore_point_show_deleted, $ediscoveryJob->is_restore_point_show_version);
                } else {
                    $restoreSession = $this->_managerVeeam->createJobRestoreSession($ediscoveryJob->backup_job->guid, $ediscoveryJob->restore_point_time, 'vex', $ediscoveryJob->is_restore_point_show_deleted, $ediscoveryJob->is_restore_point_show_version);
                }
                session()->put('restoreExchangeSessionId', $restoreSession->id);
            } else {
                throw new \Exception("Session Is Expired", 402);
            }
        } else {
            if (session('restoreExchangeSessionId')) {
                $sessionInfo = $this->_managerVeeam->getRestoreSession(session('restoreExchangeSessionId'))['data'];
                if ($sessionInfo->state != "Working") {
                    session()->forget('restoreExchangeSessionId');
                    throw new \Exception("Session Is Expired", 402);
                }
            } else {
                throw new \Exception("Session Is Expired", 402);
            }
        }
    }
    //------------------------------------------------//
    //Create Restore Session
    private function createRestoreSession($time, $showDeleted, $showVersions)
    {
        try {
            $user = auth()->user();
            $user_id = $user->id;
            $organization = $user->organization;
            //-----------------------------------------------//
            return $this->_managerVeeam->createRestoreSession($organization->veeam_organization_guid, $time, "vex", $showDeleted, $showVersions)['data'];
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Creating Restore Session  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.create_restore_session')], 500);
        }
    }
    //------------------------------------------------//
    private function checkRestorePeriod()
    {
        $user = auth()->user();
        $restoreDays = config('parameters.ALLOWED_RESTORE_DAYS_AFTER_LICENSE_EXPIRED');
        $expiryDate = Carbon::createFromFormat('Y-m-d', $user->organization->license_expiry_date)->add($restoreDays, 'days');
        return ($expiryDate < Carbon::now());
    }
    //------------------------------------------------//
    private function getStorageAccountSharedAccessKey($storageAccountName)
    {
        $organization = auth()->user()->organization;
        $storageKeys = $this->_managerAzure->getStorageAccountSharedAccessKeys($storageAccountName, $organization->azure_subscription_guid, $organization->azure_resource_group);
        $key1 = ($storageKeys->keys)[0]->value;
        return $key1;
    }
    //------------------------------------------------//
}
