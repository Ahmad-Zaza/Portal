<?php

namespace App\Http\Controllers\Restore;

use App\Engine\Azure\ManagerAzure;
use App\Engine\Veeam\ManagerVeeam;
use App\Jobs\ExportOnedriveBackground;
use App\Jobs\RestoreOnedriveBackground;
use App\Models\BacRestoreHistory;
use App\Models\BacRestoreHistoryDetail;
use App\Models\BacTempBlobFile;
use App\Models\Organization;
use App\Models\VeeamBackupJob;
use App\Models\ViewOrganizationBackupJob;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RestoreOnedriveController extends BaseController
{
    private $_managerVeeam;

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

        try {
            //--------------------------------------//
            if ($this->checkRestorePeriod()) {
                return response()->json(['message' => trans('variables.errors.restore_expiry_date')], 500);
            }
            //--------------------------------------//
            if (session('restoreOnedriveSessionId')) {
                $session = $this->_managerVeeam->getRestoreSession(session('restoreOnedriveSessionId'))['data'];
                if ($session->state != "Stopped") {
                    $this->_managerVeeam->stopRestoreSession(session('restoreOnedriveSessionId'));
                }

                session()->forget('restoreOnedriveSessionId');
            }
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Creating Restore Sessions ' . $ex->getMessage());
        }

        try {
            if ($request->jobs == 'all') {
                $restoreSession = $this->createRestoreSession($request->time, $request->showDeleted, $request->showVersions);
            } else {
                $job = VeeamBackupJob::where("id", $request->jobs)->first();
                $restoreSession = $this->_managerVeeam->createJobRestoreSession($job->guid, $request->time, 'veod', $request->showDeleted, $request->showVersions);
            }

            //--------------------------------------//
            $items = $this->_managerVeeam->getOnedriveItems($restoreSession->id)['data']->results;
            session()->put('restoreOnedriveSessionId', $restoreSession->id);
            $data = $this->filterItems($items, 'asc');
            return ['data' => $data];
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Creating Restore Sessions ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.create_restore_session')], 500);
        }
    }
    //------------------------------------------------//
    //Filter Onedrives
    private function filterItems($arr, $sorting = 'asc', $letters = '')
    {
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
            $oldRestoreSessionId = session()->get("backgroundOnedriveRestoreSessionGuid");
            if ($oldRestoreSessionId) {
                $oldRestoreSession = $this->_managerVeeam->getRestoreSession($oldRestoreSessionId)["data"];
                if ($oldRestoreSession->state == "Working")
                    $this->_managerVeeam->stopRestoreSession($oldRestoreSessionId);
                session()->forget("backgroundOnedriveRestoreSessionGuid");
            }
            //-------------------------------------------//
            if ($request->jobType == "all") {
                $restoreSession = $this->_managerVeeam->createRestoreSession($organization->veeam_organization_guid, $request->jobTime, "veod", $request->showDeleted, $request->showVersions)["data"];
            } else {
                $backupJobData = VeeamBackupJob::where("id", $request->jobId)->first();
                $restoreSession = $this->_managerVeeam->createJobRestoreSession($backupJobData->guid, $request->jobTime, "veod", $request->showDeleted, $request->showVersions);
            }
            //-------------------------------------------//
            session()->put("backgroundOnedriveRestoreSessionGuid", $restoreSession->id);
            $deviceCode = $this->_managerVeeam->generateRestoreSessionDeviceCode($restoreSession->id, $organization->veeam_aad_authentication_guid)["data"];
            //-------------------------------------------//
            return response()->json([
                "userCode" => $deviceCode->userCode,
                "restoreSessionGuid" => $restoreSession->id
            ]);
        } catch (Exception $e) {
            Log::log("error", "Error while generating restore session device code " . $e->getMessage());
            return response()->json(["message" => __("variables.errors.generating_device_code")], 500);
        }
    }
    //------------------------------------------------//
    //Get Filtered Onedrives
    public function getFilteredItems(Request $request)
    {
        $sort = $request->sortBoxType;
        $letters = $request->letters;
        try {
            $this->checkRestoreSession();
            $items = $this->_managerVeeam->getOnedriveItems(session('restoreOnedriveSessionId'))['data']->results;
            return $this->filterItems($items, ($sort == 'AZ' ? 'asc' : 'desc'), $letters);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Filtered Onedrive Items  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.onedrive.get_onedrives')], 500);
        }
    }
    //------------------------------------------------//
    public function getOnedriveUsers()
    {
        try {
            $res = [];
            $user = auth()->user();
            $user_id = $user->id;
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
    //------------------------------------------------//
    //Get Onedrive Folders
    public function getOnedriveFolders($onedriveId)
    {
        if ($onedriveId == "" || $onedriveId == null) {
            return response()->json(['message' => trans('variables.errors.onedrive.onedrive_required')], 500);
        }
        try {
            $this->checkRestoreSession();
            $onedriveFolders = $this->_managerVeeam->getOnedriveFolders(session('restoreOnedriveSessionId'), $onedriveId, true)['data'];
            $folders = $onedriveFolders->results;
            $hasFolders = [];
            $childrenFolders = [];
            $parentFolders = [];
            //-------------------------------//
            foreach ($folders as $key => $item) {
                $item->onedriveId = $onedriveId;
                $item->parentFolderId = -1;
                if (optional($item->_links)->parent) {
                    $parent = explode('/', $item->_links->parent->href);
                    $item->parentId = end($parent);
                    //------
                    if (!array_key_exists(end($parent), $parentFolders)) {
                        $parentFolders[end($parent)] = [];
                    }
                    //------
                    array_push($parentFolders[end($parent)], $folders[$key]);
                    array_push($hasFolders, end($parent));
                    array_push($childrenFolders, $item->id);
                    //------
                    $item->parentFolderId = end($parent);
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
            $indexedArr = [];
            foreach ($folders as $item) {
                $indexedArr[$item->id] = $item;
            }
            //-------------------------------//
            foreach ($folders as $item) {
                $item->path = implode('/', $this->getFolderPath($item, $indexedArr));
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
            Log::log('error', 'Exception While Getting Onedrive Folders  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.onedrive.get_folders')], 500);
        }
    }
    //------------------------------------------------//
    private function getFolderPath($folder, $folders)
    {
        $temp = [];
        if ($folder->parentFolderId != -1) {
            array_push($temp, implode('/', $this->getFolderPath($folders[$folder->parentFolderId], $folders)));
        }
        array_push($temp, $folder->id);
        return $temp;
    }
    //------------------------------------------------//
    //Get Onedrive Folder Items
    public function getOnedriveFolderItems(Request $request)
    {
        $onedriveId = $request->input('onedriveId');
        $folderId = $request->input('folderId');
        //-------------------------------------//
        if ($onedriveId == "-1") {
            return [];
        }
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'onedriveId' => 'required',
            'onedriveTitle' => 'required',
            'offset' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        //-------------------------------------//
        $onedriveTitle = $request->input('onedriveTitle');
        $folderTitle = $request->input('folderTitle');
        $offset = $request->input('offset');
        //--------------------//
        $limit = config('parameters.EXPLORING_RESTORE_ITEMS_LIMIT_COUNT');
        //--------------------//

        $res = array();
        try {
            $this->checkRestoreSession();
            if ($folderId == -1) {
                if ($offset == 0) {
                    $foldersArr = $this->_managerVeeam->getOnedriveFolders(session('restoreOnedriveSessionId'), $onedriveId);
                }
                $data = $this->_managerVeeam->getOnedriveDocuments(session('restoreOnedriveSessionId'), $onedriveId, $offset, $limit);
            } else {
                if ($offset == 0) {
                    $foldersArr = $this->_managerVeeam->getOnedriveFolderSubFolders(session('restoreOnedriveSessionId'), $onedriveId, $folderId);
                }
                $data = $this->_managerVeeam->getOnedriveFolderDocuments(session('restoreOnedriveSessionId'), $onedriveId, $folderId, $offset, $limit);
            }
            $items = $data['data'];
            foreach ($items->results as $item) {
                $item->onedriveId = $onedriveId;
                $item->onedriveTitle = $onedriveTitle;
                $item->folderId = $folderId;
                $item->folderTitle = $folderTitle;
                $item->isFolder = false;
                array_push($res, $item);
            }
            if ($offset == 0) {
                $folders = $foldersArr['data'];
                foreach ($folders->results as $item) {
                    $item->isFolder = true;
                    $item->onedriveId = $onedriveId;
                    $item->onedriveTitle = $onedriveTitle;
                    $item->folderId = $item->id;
                    $item->folderTitle = $item->name;
                    $item->parentFolderId = -1;
                    if (optional($item->_links)->parent) {
                        $tempArr = explode('/', $item->_links->parent->href);
                        $item->parentFolderId = end($tempArr);
                    }
                    array_push($res, $item);
                }
            }
            return $res;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Onedrive Folder Items  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.onedrive.get_items')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Onedrive To Original Location
    public function restoreOneDriveOriginal(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        //-------------------------------------//
        $organization = auth()->user()->organization;
        //-------------------------------------//
        if(!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $onedrives = $request->input('onedrives');
        if ($onedrives == "" || $onedrives == null || $onedrives == "[]") {
            return response()->json(['message' => trans('variables.errors.onedrive.onedrive_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $onedrives = $requestData['onedrives'];
        $options['action'] = $requestData['restoreAction'];
        $options['version'] = $requestData['documentVersion'];
        $options['skipUnresolved'] = $requestData['skipUnresolved'];
        try {
            $onedrivesArr = json_decode($onedrives);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'onedrive';
            $history->sub_type = 'Restore Onedrive to Original Location (' . $options['action'] . ')';
            $history->items_count = count($onedrivesArr);
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
            foreach ($onedrivesArr as $item) {
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->name;
                $details->item_parent_name = $item->url;
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
            $onedriveData = [
                'userCode' => $userCode,
                'onedrives' => $onedrives,
            ];
            //--------------------------------------//
            dispatch(new RestoreOnedriveBackground(auth()->id(), $history->id, 'restoreOnedrive', $sessionData, $onedriveData));
            //--------------------------------------//
            session()->forget("backgroundOnedriveRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.onedrive.restore_onedrive_original')], 200);
        } catch (\Exception $e) {
            Log::log('error', 'Exception While Restoring Onedrives' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.onedrive.restore_onedrive_original')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Copy Onedrive To Original Location
    public function restoreCopyOneDrive(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        $organization = auth()->user()->organization;
        //-------------------------------------//
        if(!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//
        $onedrives = $requestData['onedrives'];
        if ($onedrives == "" || $onedrives == null || $onedrives == "[]") {
            return response()->json(['message' => trans('variables.errors.onedrive.onedrive_required')], 500);
        }

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $onedrives = $requestData['onedrives'];
        $toOnedrive = $requestData['onedrive'];
        $toFolder = $requestData['folder'];
        $options['action'] = $requestData['restoreAction'];
        $options['version'] = $requestData['documentVersion'];
        $options['toOnedrive'] = $toOnedrive;
        $options['toFolder'] = $toFolder;
        $options['changedItems'] = $requestData['changedItems'];
        $options['deletedItems'] = $requestData['deletedItems'];
        $options['restorePermissions'] = $requestData['restorePermissions'];
        $options['sendSharedLinksNotification'] = $requestData['sendSharedLinksNotification'];
        try {
            $onedrivesArr = json_decode($onedrives);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'onedrive';
            $history->items_count = count($onedrivesArr);
            $history->name = $requestData["restoreJobName"];
            $history->sub_type = 'Copy Onedrive to Another Location';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            foreach ($onedrivesArr as $item) {
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->name;
                $details->item_parent_name = $item->url;
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
            $onedriveData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreOnedriveBackground(auth()->id(), $history->id, 'copyOnedrive', $sessionData, $onedriveData));
            //--------------------------------------//
            session()->forget("backgroundOnedriveRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.onedrive.restore_onedrive_copy')], 200);
        } catch (\Exception $e) {
            Log::log('error', 'Exception While Restoring Onedrives' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.onedrive.restore_onedrive_copy')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Onedrive Folder
    public function restoreOnedriveFolder(Request $request)
    {
        if ($request->restoreType == "original") {
            return $this->restoreOnedriveFolderOriginal($request);
        }
        return $this->copyOnedriveFolder($request);
    }
    //------------------------------------------------//
    //Restore Onedrive Folder To Original Location
    public function restoreOnedriveFolderOriginal(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        $organization = auth()->user()->organization;
        //-------------------------------------//
        if(!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $folders = $request->input('folders');
        if ($folders == "" || $folders == null || $folders == "[]") {
            return response()->json(['message' => trans('variables.errors.onedrive.folders_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['action'] = $requestData['restoreAction'];
        $options['version'] = $requestData['documentVersion'];
        try {
            $foldersArr = json_decode($folders);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'folder';
            $history->sub_type = 'Restore Folders to Original Location (' . $options['action'] . ')';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            $itemsCount = 0;
            foreach ($foldersArr as $item) {
                $itemsCount += count($item->folders);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->folders);
                $details->item_parent_id = $item->id;
                $details->item_parent_name = $item->name;
                $details->item_name = $item->name;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $history->items_count = $itemsCount;
            $history->name = $requestData["restoreJobName"];
            $history->save();
            //--------------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $onedriveData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreOnedriveBackground(auth()->id(), $history->id, 'restoreOnedriveFolderOriginal', $sessionData, $onedriveData));
            //--------------------------------------//
            session()->forget("backgroundOnedriveRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.onedrive.restore_folder_original')], 200);
        } catch (\Exception $e) {
            Log::log('error', 'Exception While Restoring Onedrives' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.onedrive.restore_folder_original')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Onedrive Folder To Original Location
    public function copyOnedriveFolder(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        $organization = auth()->user()->organization;
        //-------------------------------------//
        if(!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $folders = $request->input('folders');
        if ($folders == "" || $folders == null || $folders == "[]") {
            return response()->json(['message' => trans('variables.errors.onedrive.folders_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['toOnedrive'] = $requestData['onedrive'];
        $options['toFolder'] = $requestData['folder'];
        $options['action'] = $requestData['restoreAction'];
        $options['version'] = $requestData['documentVersion'];
        $options['changedItems'] = $requestData['changedItems'];
        $options['deletedItems'] = $requestData['deletedItems'];
        $options['restorePermissions'] = $requestData['restorePermissions'];
        $options['restoreVersionAction'] = $requestData['restoreVersionAction'];
        $options['sendSharedLinksNotification'] = $requestData['sendSharedLinksNotification'];
        try {
            $foldersArr = json_decode($folders);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'folder';
            $history->sub_type = 'Copy Folders to Another Location';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            $itemsCount = 0;
            foreach ($foldersArr as $item) {
                $itemsCount += count($item->folders);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->folders);
                $details->item_parent_id = $item->id;
                $details->item_parent_name = $item->name;
                $details->item_name = $item->name;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $history->items_count = $itemsCount;
            $history->name = $requestData["restoreJobName"];
            $history->save();
            //--------------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $onedriveData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreOnedriveBackground(auth()->id(), $history->id, 'copyOnedriveFolder', $sessionData, $onedriveData));
            //--------------------------------------//
            session()->forget("backgroundOnedriveRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.onedrive.restore_folder_copy')], 200);
        } catch (\Exception $e) {
            Log::log('error', 'Exception While Restoring Onedrives' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.onedrive.restore_folder_copy')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Onedrive Documents
    public function restoreOnedriveDocs(Request $request)
    {
        if ($request->restoreType == "original") {
            return $this->restoreOnedriveDocsOriginal($request);
        }
        return $this->copyOnedriveDocs($request);
    }
    //------------------------------------------------//
    //Restore Onedrive Folder To Original Location
    public function restoreOnedriveDocsOriginal(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        $organization = auth()->user()->organization;
        //-------------------------------------//
        if(!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $docs = $request->input('docs');
        if ($docs == "" || $docs == null || $docs == "[]") {
            return response()->json(['message' => trans('variables.errors.onedrive.documents_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['action'] = $requestData['restoreAction'] ?? $request["restoreVersionAction"];
        $options['version'] = $requestData['documentVersion'];
        try {
            $docsArr = json_decode($docs);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'document';
            $history->sub_type = 'Restore Documents to Original Location (' . $options['action'] . ')';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            $count = 0;
            foreach ($docsArr as $item) {
                $count += count($item->docs);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->docs);
                $details->item_parent_id = $item->onedriveId;
                $details->item_parent_name = $item->onedriveTitle . '-' . $item->folderTitle;
                $details->item_name = $item->onedriveTitle;
                if ($item->onedriveTitle == $item->folderTitle) {
                    $details->item_parent_name = $item->onedriveTitle;
                    $details->item_name = $item->onedriveTitle;
                }
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
            $onedriveData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreOnedriveBackground(auth()->id(), $history->id, 'restoreOnedriveDocumentOriginal', $sessionData, $onedriveData));
            //--------------------------------------//
            session()->forget("backgroundOnedriveRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.onedrive.restore_document_original')], 200);
        } catch (\Exception $e) {
            Log::log('error', 'Exception While Restoring Onedrives' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.onedrive.restore_document_original')], 500);
        }
    }
    //------------------------------------------------//
    //Copy Onedrive Folder To
    public function copyOnedriveDocs(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        $organization = auth()->user()->organization;
        //-------------------------------------//
        if(!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $docs = $request->input('docs');
        if ($docs == "" || $docs == null || $docs == "[]") {
            return response()->json(['message' => trans('variables.errors.onedrive.documents_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['toOnedrive'] = $requestData['onedrive'];
        $options['toFolder'] = $requestData['folder'];
        $options['action'] = $requestData['restoreAction'];
        $options['version'] = $requestData['documentVersion'];
        $options['restoreVersionAction'] = $requestData['restoreVersionAction'];
        try {
            $docsArr = json_decode($docs);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->restore_session_guid = $restoreSessionGuid;
            $history->type = 'document';
            $history->sub_type = 'Copy Documents to Another Location';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            $count = 0;
            foreach ($docsArr as $item) {
                $count += count($item->docs);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->docs);
                $details->item_parent_id = $item->onedriveId;
                $details->item_parent_name = $item->onedriveTitle . '-' . $item->folderTitle;
                $details->item_name = $item->onedriveTitle . '-' . $item->folderTitle;
                if ($item->onedriveTitle == $item->folderTitle) {
                    $details->item_parent_name = $item->onedriveTitle;
                    $details->item_name = $item->onedriveTitle;
                }
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
            $onedriveData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreOnedriveBackground(auth()->id(), $history->id, 'copyOnedriveDocument', $sessionData, $onedriveData));
            //--------------------------------------//
            session()->forget("backgroundOnedriveRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.onedrive.restore_document_copy')], 200);
        } catch (\Exception $e) {
            Log::log('error', 'Exception While Restoring Onedrives' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.onedrive.restore_document_copy')], 500);
        }
    }
    //------------------------------------------------//
    //Export Onedrive
    public function exportOnedrive(Request $request)
    {
        $onedrives = $request->input('onedrives');
        if ($onedrives == "" || $onedrives == "[]" || $onedrives == null) {
            return response()->json(['message' => trans('variables.errors.onedrive.onedrive_required')], 500);
        }
        //----------------------------------//
        $requestData = $request->all();
        //----------------------------------//
        $onedrives = $requestData['onedrives'];
        $jobId = $requestData['jobId'];
        try {
            //--------------------------------//
            $onedrivesArr = json_decode($onedrives);
            //--------------------------------//
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->type = 'onedrive';
            $history->items_count = count($onedrivesArr);
            $history->name = $requestData['restoreJobName'];
            $history->sub_type = 'Export Onedrives';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->save();
            //--------------------------------//
            foreach ($onedrivesArr as $item) {
                //-----------------
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->name;
                $details->item_parent_name = $item->url;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $jobId)->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
            $organization = auth()->user()->organization;
            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
            ];
            //--------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            //--------------------------------//
            dispatch(new ExportOnedriveBackground(auth()->id(), $history->id, 'item', $storageData, $sessionData));
            //--------------------------------//
            return response()->json(['message' => trans('variables.success.onedrive.export_onedrives')], 200);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Exporting Onedrives ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.onedrive.export_onedrives')], 500);
        }
    }
    //------------------------------------------------//
    //Export Onedrive Folders
    public function exportOnedriveFolders(Request $request)
    {
        $folders = $request->input('folders');
        if ($folders == "" || $folders == "[]" || $folders == null) {
            return response()->json(['message' => trans('variables.errors.onedrive.folders_required')], 500);
        }
        //--------------------------------//
        $requestData = $request->all();
        $jobId = $requestData['jobId'];
        //--------------------------------//
        try {
            //--------------------------------//
            $foldersArr = json_decode($folders);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->type = 'folder';
            $history->sub_type = 'Export Folders';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->save();
            //--------------------------------------//
            $itemsCount = 0;
            foreach ($foldersArr as $item) {
                $itemsCount += count($item->folders);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->folders);
                $details->item_parent_id = $item->id;
                $details->item_parent_name = $item->name;
                // $details->item_name        = optional($item)->folder;
                $details->item_name = $item->name;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $history->items_count = $itemsCount;
            $history->name = $requestData["restoreJobName"];
            $history->save();
            //--------------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $jobId)->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
            $organization = auth()->user()->organization;
            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
            ];
            //--------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            //--------------------------------//
            dispatch(new ExportOnedriveBackground(auth()->id(), $history->id, 'folder', $storageData, $sessionData));
            //--------------------------------//
            return response()->json(['message' => trans('variables.success.onedrive.export_folders')], 200);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Exporting Onedrive Folders ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.onedrive.export_folders')], 500);
        }
    }
    //------------------------------------------------//
    //Export Onedrive Documents
    public function exportOnedriveDocuments(Request $request)
    {
        $docs = $request->input('docs');
        if ($docs == "" || $docs == "[]" || $docs == null) {
            return response()->json(['message' => trans('variables.errors.onedrive.documents_required')], 500);
        }
        //-----------------------------//
        $requestData = $request->all();
        $jobId = $requestData['jobId'];
        //-----------------------------//
        try {
            //--------------------------------//
            $docsArr = json_decode($docs);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->type = 'document';
            $history->sub_type = 'Export Documents';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->save();
            //--------------------------------------//
            $count = 0;
            foreach ($docsArr as $item) {
                $count += count($item->docs);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->docs);
                $details->item_parent_id = $item->onedriveId;
                $details->item_parent_name = $item->onedriveTitle . '-' . $item->folderTitle;
                $details->item_name = $item->onedriveTitle . '-' . $item->folderTitle;
                if ($item->onedriveTitle == $item->folderTitle) {
                    $details->item_parent_name = $item->onedriveTitle;
                    $details->item_name = $item->onedriveTitle;
                }
                $details->status = 'In Progress';
                $details->save();
            }
            $history->items_count = $count;
            $history->name = $requestData["restoreJobName"];
            $history->save();
            //--------------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $jobId)->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
            ];
            $organization = auth()->user()->organization;
            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
            ];
            //--------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            //--------------------------------//
            dispatch(new ExportOnedriveBackground(auth()->id(), $history->id, 'document', $storageData, $sessionData));
            //--------------------------------//
            return response()->json(['message' => trans('variables.success.onedrive.export_documents')], 200);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Exporting Onedrive Documents ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.onedrive.export_documents')], 500);
        }
    }
    //------------------------------------------------//
    //Download Onedrive Document
    public function downloadOnedriveDocument(Request $request)
    {
        set_time_limit(300);
        $requestData = $request->all();
        $onedriveId = $request->input('onedriveId');
        $documentId = $request->input('documentId');
        $fileSize = $request->input('fileSize');
        if ($onedriveId == "" || $onedriveId == null || $documentId == '' || $documentId == '') {
            return response()->json(['message' => trans('variables.errors.onedrive.documents_required')], 500);
        }
        try {
            $this->checkRestoreSession();
            //--------------------------------//
            $fileSize = str_replace(" MB", '', $fileSize);
            $downloadLimit = config('parameters.DIRECT_DOWNLOAD_MEGA_LIMIT');
            if ($fileSize > $downloadLimit) {
                //--------------------------------//
                $docsArr[0] = (object) [
                    "onedriveId" => $onedriveId,
                    "onedriveTitle" => $request->input('onedriveTitle'),
                    "folderTitle" => $request->input('folderTitle'),
                    "items" => [[
                        "id" => $documentId,
                        "name" => $documentId,
                        "onedriveId" => $onedriveId,
                        "onedriveTitle" => $request->input('onedriveTitle'),
                        "folderTitle" => $request->input('folderTitle'),
                    ]],
                ];
                $docs = json_encode($docsArr);
                //--------------------------------//
                $history = new BacRestoreHistory();
                $history->organization_id = auth()->user()->organization->id;
                $history->status = 'In Progress';
                $history->type = 'document';
                $history->sub_type = 'Export Document';
                $history->backup_job_id = $requestData['jobId'];
                $history->restore_point_time = $requestData['jobTime'];
                $history->restore_point_type = $requestData['jobType'];
                $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
                $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
                $history->request_time = Carbon::now();
                $history->items_count = 1;
                $history->name = $history->items_count . '_OnedriveDocuments_' . date("Ymd_His");
                $history->save();
                //--------------------------------------//
                foreach ($docsArr as $item) {
                    $details = new BacRestoreHistoryDetail();
                    $details->restore_history_id = $history->id;
                    $details->item_id = json_encode($item->items);
                    $details->item_parent_id = $item->onedriveId;
                    $details->item_parent_name = $item->onedriveTitle . '-' . $item->folderTitle;
                    $details->item_name = $item->onedriveTitle . '-' . $item->folderTitle;
                    if ($item->onedriveTitle == $item->folderTitle) {
                        $details->item_parent_name = $item->onedriveTitle;
                        $details->item_name = $item->onedriveTitle;
                    }
                    $details->status = 'In Progress';
                    $details->save();
                }
                //--------------------------------------//
                $organization = auth()->user()->organization;
                $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $requestData['jobId'])->first();
                $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
                $storageData = [
                    'storageAccount' => $backupJobData->storage_account_name,
                    'accountKey' => $storageAccountKey,
                    'containerName' => $backupJobData->restore_container,
                ];
                //--------------------------------//
                $sessionData = [
                    'time' => $requestData['jobTime'],
                    'showDeleted' => $requestData['showDeleted'],
                    'showVersions' => $requestData['showVersions'],
                    'orgId' => $organization->veeam_organization_guid,
                ];
                //--------------------------------//
                dispatch(new ExportOnedriveBackground(auth()->id(), $history->id, 'document', $storageData, $sessionData));
                //--------------------------------//
                return response()->json(['message' => trans('variables.success.onedrive.download_document')], 200);
            }
            //--------------------------------//
            try {
                $data = $this->_managerVeeam->downloadOnedriveDocument(session('restoreOnedriveSessionId'), $onedriveId, $documentId);
                //-----------------------------------------------------//
                $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $request->jobId)->first();
                $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
                //-----------------------------------------------------//
                $body = (object) $data['body'];
                $body = Utils::streamFor($body);

                //------------------------------------------------//
                $filenameArr = explode('filename=', $data['headers']['Content-Disposition'][0]);
                $filename = end($filenameArr);
                $tempArr = explode('.', $filename);
                $ext = str_replace('"', '', end($tempArr));
                //------------------------------------------------//
                $blobName = 'temp_' . date('Ymd_His') . '.' . $ext;
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
                $url = $this->_managerAzure->getBlobUrl($accountName, $containerName, $blobName, $endDate, $account_key);
                //-----------------------------------------------------//
            } catch (\Exception $e) {
                Log::log('error', 'Exception While Download Onedrive Document' . $e->getMessage());
            }
            return response()->json(['file' => $url]);
            //--------------------------------//
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Downloading Onedrive Document ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.onedrive.download_document')], 500);
        }
    }
    //------------------------------------------------//
    //Download Organization User Onedrives
    public function getUserOnedrives(Request $request)
    {
        try {
            $user = auth()->user();
            $user_id = $user->id;
            $organization = $user->organization;
            $data = $this->_managerVeeam->getOrganizationUserOnedrives($organization->veeam_organization_guid, $request->userId)['data']->results;
            return $data;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting users ' . $ex->getMessage());
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
    //------------------------------------------------//
    //Check Restore Session
    private function checkRestoreSession()
    {
        if (session('restoreOnedriveSessionId')) {
            try {
                $sessionInfo = $this->_managerVeeam->getRestoreSession(session('restoreOnedriveSessionId'))['data'];
                if ($sessionInfo->state != "Working") {
                    session()->forget('restoreOnedriveSessionId');
                    return response()->json(['message' => trans('variables.errors.restore_session_expired')], 500);
                }
                return true;
            } catch (\Exception $e) {
                session()->forget('restoreOnedriveSessionId');
                return response()->json(['message' => trans('variables.errors.restore_session_expired')], 500);
            }
        }
        return response()->json(['message' => trans('variables.errors.restore_session_expired')], 500);
    }
    //------------------------------------------------//
    //Create Restore Session
    private function createRestoreSession($time, $showDeleted, $showVersions)
    {
        try {
            $user = auth()->user();
            $user_id = $user->id;
            $organization = $user->organization;
            return $this->_managerVeeam->createRestoreSession($organization->veeam_organization_guid, $time, "veod", $showDeleted, $showVersions)['data'];
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
