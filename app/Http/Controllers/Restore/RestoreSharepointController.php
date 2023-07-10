<?php

namespace App\Http\Controllers\Restore;

use App\Engine\Azure\ManagerAzure;
use App\Engine\Veeam\ManagerVeeam;
use App\Jobs\ExportSharepointBackground;
use App\Jobs\RestoreSharepointBackground;
use App\Models\BacRestoreHistory;
use App\Models\BacRestoreHistoryDetail;
use App\Models\BacTempBlobFile;
use App\Models\Organization;
use App\Models\RestoreTempBlobFile;
use App\Models\VeeamBackupJob;
use App\Models\ViewOrganizationBackupJob;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RestoreSharepointController extends BaseController
{
    private $_managerVeeam;
    //TEST COMMIT
    public function __construct()
    {
        $this->_managerVeeam = new ManagerVeeam();
        $this->_managerAzure = new ManagerAzure();
    }

    //------------------------------------------------//
    //create restore session
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
            if (session('restoreSharepointSessionId')) {
                $session = $this->_managerVeeam->getRestoreSession(session('restoreSharepointSessionId'))['data'];
                if ($session->state != "Stopped") {
                    $this->_managerVeeam->stopRestoreSession(session('restoreSharepointSessionId'));
                }

                session()->forget('restoreSharepointSessionId');
            }
        } catch (\Exception $ex) {
        }

        try {
            if ($request->jobs == 'all') {
                $restoreSession = $this->createRestoreSession($request->time, $request->showDeleted, $request->showVersions);
            } else {
                $job = VeeamBackupJob::where("id", $request->jobs)->first();
                $restoreSession = $this->_managerVeeam->createJobRestoreSession($job->guid, $request->time, 'vesp', $request->showDeleted, $request->showVersions);
            }

            //--------------------------------------//
            $items = $this->_managerVeeam->getSharepointSites($restoreSession->id)['data']->results;
            session()->put('restoreSharepointSessionId', $restoreSession->id);
            //------------------------------------------------//
            //-- Sort Sites
            $all = $this->filterItems($items, 'asc');
            $data = $this->setSiteSubSites($all);
            //------------------------------------------------//
            return ['data' => $data];
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Creating Restore Sessions  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.create_restore_session')], 500);
        }
    }
    //------------------------------------------------//
    private function setSiteSubSites($arr)
    {
        //-- Sort Sites with subSites
        $hasSubsites = [];
        $childrenSites = [];
        $parentSites = [];
        //-------------------------------//
        foreach ($arr as $key => $item) {
            $item->parentSiteId = -1;
            if (optional($item->_links)->parent) {
                $parent = explode('/', $item->_links->parent->href);
                //------
                if (!array_key_exists(end($parent), $parentSites)) {
                    $parentSites[end($parent)] = [];
                }
                //------
                array_push($parentSites[end($parent)], $arr[$key]);
                array_push($hasSubsites, end($parent));
                array_push($childrenSites, $item->id);
                //------
                $item->parentSiteId = end($parent);
            }
        }
        //-------------------------------//
        //---- Add Attribute To Check if Has SubSite
        foreach ($arr as $key => $item) {
            $item->hasSubsites = false;
            if (in_array($item->id, $hasSubsites)) {
                $item->hasSubsites = true;
                $item->children = $parentSites[$item->id];
            }
        }
        //-------------------------------//
        //---- Add Attribute Children of Every Site
        foreach ($arr as $key => $item) {
            if (in_array($item->id, $childrenSites)) {
                unset($arr[$key]);
            }
        }
        //-------------------------------//
        return array_values($arr);
    }
    //------------------------------------------------//
    //filter sites
    private function filterItems($arr, $sorting = 'asc', $letters = '')
    {
        foreach ($arr as $key => $item) {
            if (!$item->name) {
                $item->name = "- My Organization";
            }
            if (strpos($item->url, "/personal/") !== false) {
                unset($arr[$key]);
            }
        }
        $arr = array_values($arr);
        if ($letters) {
            $letters = explode(',', $letters);
            foreach ($arr as $key => $item) {
                if (!$item->name) {
                    $item->name = "My Organization";
                }
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
    //get filtered items
    public function getFilteredSites(Request $request)
    {
        $sort = $request->sortBoxType;
        $letters = $request->letters;
        try {
            $this->checkRestoreSession();
            $items = $this->_managerVeeam->getSharepointSites(session('restoreSharepointSessionId'))['data']->results;
            return $this->filterItems($items, ($sort == 'AZ' ? 'asc' : 'desc'), $letters);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Filtered Sharepoint Site  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.getSites')], 500);
        }
    }
    //------------------------------------------------//
    //get site content
    public function getSiteContent($siteId)
    {
        if ($siteId == "" || $siteId == null) {
            return response()->json(['message' => trans('variables.errors.sharepoint.sites_required')], 500);
        }
        try {
            $this->checkRestoreSession();
            $lists = $this->_managerVeeam->getSiteLists(session('restoreSharepointSessionId'), $siteId)['data']->results;
            $libraries = $this->_managerVeeam->getSiteLibraries(session('restoreSharepointSessionId'), $siteId)['data']->results;
            $data = [];
            foreach ($lists as $item) {
                $item->siteId = $siteId;
                $item->type = 'list';
                array_push($data, $item);
            }
            foreach ($libraries as $item) {
                $item->siteId = $siteId;
                $item->type = 'library';
                array_push($data, $item);
            }
            return $data;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Site Content  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.contents_required')], 500);
        }
    }
    //------------------------------------------------//
    //Get Site Content Items
    public function getSiteContentItems(Request $request)
    {
        if ($request->input("siteId") == "-1") {
            return [];
        }

        $type = $request->input('type');
        if ($type == "list") {
            return $this->getSiteListItems($request);
        }
        return $this->getSiteLibraryItems($request);
    }
    //------------------------------------------------//
    //Get Site List Items
    public function getSiteListItems(Request $request)
    {
        //-------------------------------------//
        $siteId = $request->input('siteId');
        $listId = $request->input('contentId');
        $folderId = $request->input('folderId');
        $siteTitle = $request->input('siteTitle');
        $contentTitle = $request->input('contentTitle');
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
                    $foldersArr = $this->_managerVeeam->getSiteFolders(session('restoreSharepointSessionId'), $siteId, $listId);
                }
                $data = $this->_managerVeeam->getSiteItems(session('restoreSharepointSessionId'), $siteId, $listId, $offset, $limit);
            } else {
                if ($offset == 0) {
                    $foldersArr = $this->_managerVeeam->getSiteFolders(session('restoreSharepointSessionId'), $siteId, $listId, $folderId);
                }
                $data = $this->_managerVeeam->getSiteItems(session('restoreSharepointSessionId'), $siteId, $folderId, $offset, $limit);
            }
            $items = $data['data'];
            foreach ($items->results as $item) {
                $item->siteId = $siteId;
                $item->siteTitle = $siteTitle;
                $item->contentId = $listId;
                $item->contentTitle = $contentTitle;
                $item->folderId = $folderId;
                $item->folderTitle = $folderTitle;
                $item->isFolder = false;
                $item->type = $request->input('type');
                array_push($res, $item);
            }
            if ($offset == 0) {
                $folders = $foldersArr['data'];
                foreach ($folders->results as $item) {
                    $item->isFolder = true;
                    $item->siteId = $siteId;
                    $item->siteTitle = $siteTitle;
                    $item->contentId = $listId;
                    $item->contentTitle = $contentTitle;
                    $item->folderId = $folderId;
                    $item->folderTitle = $folderTitle;
                    $item->parentFolderId = -1;
                    $item->type = $request->input('type');
                    if (optional($item->_links)->parent) {
                        $tempArr = explode('/', $item->_links->parent->href);
                        $item->parentFolderId = end($tempArr);
                    }
                    array_push($res, $item);
                }
            }
            return $res;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Site List Items  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.get_list_items')], 500);
        }
    }
    //------------------------------------------------//
    //Get Site Library Items
    public function getSiteLibraryItems(Request $request)
    {
        //-------------------------------------//
        $siteId = $request->input('siteId');
        $libraryId = $request->input('contentId');
        $folderId = $request->input('folderId');
        $siteTitle = $request->input('siteTitle');
        $libraryTitle = $request->input('contentTitle');
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
                    $foldersArr = $this->_managerVeeam->getSiteFolders(session('restoreSharepointSessionId'), $siteId, $libraryId);
                }
                $data = $this->_managerVeeam->getSiteDocuments(session('restoreSharepointSessionId'), $siteId, $libraryId, $offset, $limit);
            } else {
                if ($offset == 0) {
                    $foldersArr = $this->_managerVeeam->getSiteFolders(session('restoreSharepointSessionId'), $siteId, $folderId);
                }
                $data = $this->_managerVeeam->getSiteDocuments(session('restoreSharepointSessionId'), $siteId, $folderId, $offset, $limit);
            }
            $items = $data['data'];
            foreach ($items->results as $item) {
                $item->siteId = $siteId;
                $item->siteTitle = $siteTitle;
                $item->contentId = $libraryId;
                $item->contentTitle = $libraryTitle;
                $item->folderId = $folderId;
                $item->folderTitle = $folderTitle;
                $item->type = $request->input('type');
                $item->isFolder = false;
                array_push($res, $item);
            }
            if ($offset == 0) {
                $folders = $foldersArr['data'];
                foreach ($folders->results as $item) {
                    $item->isFolder = true;
                    $item->siteId = $siteId;
                    $item->siteTitle = $siteTitle;
                    $item->contentId = $libraryId;
                    $item->contentTitle = $libraryTitle;
                    $item->folderId = $folderId;
                    $item->folderTitle = $folderTitle;
                    $item->type = $request->input('type');
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
            Log::log('error', 'Exception While Getting Site Library Items  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.get_library_items')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Site
    public function restoreSite(Request $request)
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
        $sites = $request->input('sites');
        if ($sites == "" || $sites == null || $sites == "[]") {
            return response()->json(['message' => trans('variables.errors.sharepoint.sites_required')], 500);
        }

        if ($request['changedItems'] == 'false' && $request['deletedItems'] == 'false') {
            return response()->json(['message' => trans('variables.errors.restore_required_options')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['siteType'] = 'original';
        $options['restoreListViews'] = $requestData['restoreListViews'];
        $options['changedItems'] = $requestData['changedItems'];
        $options['deletedItems'] = $requestData['deletedItems'];
        $options['restoreSubsites'] = $requestData['restoreSubsites'];
        $options['restoreMasterPages'] = $requestData['restoreMasterPages'];
        $options['restorePermissions'] = $requestData['restorePermissions'];
        $options['documentVersion'] = $requestData['documentVersion'];
        $options['sendSharedLinksNotification'] = $requestData['sendSharedLinksNotification'];
        $options['documentLastVersionAction'] = $requestData['documentLastVersionAction'];
        try {
            $sitesArr = json_decode($sites);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'site';
            $history->items_count = count($sitesArr);
            $history->name = $requestData["restoreJobName"];
            $history->sub_type = 'Restore Site';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            foreach ($sitesArr as $item) {
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
            $siteData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreSharepointBackground(auth()->id(), $history->id, 'restoreSite', $sessionData, $siteData));
            //--------------------------------------//
            session()->forget("backgroundSharepointRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.sharepoint.restore_site')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Sites' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.restore_site')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Site Content
    public function restoreSiteContent(Request $request)
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
        if ($request['changedItems'] == 'false' && $request['deletedItems'] == 'false') {
            return response()->json(['message' => trans('variables.errors.restore_required_options')], 500);
        }
        //-------------------------------------//
        $type = $request->contentType;
        if ($type == "list") {
            return $this->restoreSiteLists($request);
        }

        return $this->restoreSiteLibraries($request);
    }
    //------------------------------------------------//
    //Restore Site Content
    public function restoreSiteLibraries(Request $request)
    {
        //-------------------------------------//
        $content = $request->input('content');
        $type = $request->contentType;
        if ($content == "" || $content == null || $content == "[]") {
            return response()->json(['message' => trans('variables.errors.sharepoint.libraries_required')], 500);
        }
        Log::log("Error", "the type of content list is " . $type);
        //-------------------------------------//
        $requestData = $request->all();

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['list'] = optional($requestData)['list'];
        $options['listType'] = $requestData['listType'];
        $options['restoreListViews'] = $requestData['restoreListViews'];
        $options['changedItems'] = $requestData['changedItems'];
        $options['deletedItems'] = $requestData['deletedItems'];
        $options['restorePermissions'] = $requestData['restorePermissions'];
        $options['documentVersion'] = $requestData['documentVersion'];
        $options['sendSharedLinksNotification'] = $requestData['sendSharedLinksNotification'];
        $options['documentLastVersionAction'] = $requestData['documentLastVersionAction'];
        try {
            $contentsArr = json_decode($content);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'library';
            $history->items_count = count($contentsArr);
            $history->name = $requestData["restoreJobName"];
            $history->sub_type = 'Restore Libraries';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            foreach ($contentsArr as $item) {
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->content;
                $details->item_parent_id = $item->siteId;
                $details->item_parent_name = $item->siteTitle;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $organization = auth()->user()->organization;
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $siteData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreSharepointBackground(auth()->id(), $history->id, 'restoreSiteLibraries', $sessionData, $siteData));
            //--------------------------------------//
            session()->forget("backgroundSharepointRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.sharepoint.restore_site_libraries')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Site Libraries ' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.restore_site_libraries')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Site Content
    public function restoreSiteLists(Request $request)
    {
        //-------------------------------------//
        $content = $request->input('content');
        if ($content == "" || $content == null || $content == "[]") {
            return response()->json(['message' => trans('variables.errors.sharepoint.lists_required')], 500);
        }

        //-------------------------------------//
        $requestData = $request->all();

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['list'] = optional($requestData)['list'];
        $options['listType'] = $requestData['listType'];
        $options['restoreListViews'] = $requestData['restoreListViews'];
        $options['changedItems'] = $requestData['changedItems'];
        $options['deletedItems'] = $requestData['deletedItems'];
        $options['restorePermissions'] = $requestData['restorePermissions'];
        $options['documentVersion'] = $requestData['documentVersion'];
        $options['sendSharedLinksNotification'] = $requestData['sendSharedLinksNotification'];
        $options['documentLastVersionAction'] = $requestData['documentLastVersionAction'];
        try {
            $contentsArr = json_decode($content);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'list';
            $history->items_count = count($contentsArr);
            $history->name = $requestData["restoreJobName"];
            $history->sub_type = 'Restore Lists';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            foreach ($contentsArr as $item) {
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->content;
                $details->item_parent_id = $item->siteId;
                $details->item_parent_name = $item->siteTitle;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $organization = auth()->user()->organization;
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $siteData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreSharepointBackground(auth()->id(), $history->id, 'restoreSiteLists', $sessionData, $siteData));
            //--------------------------------------//
            session()->forget("backgroundSharepointRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.sharepoint.restore_site_lists')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Site Lists ' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.restore_site_lists')], 500);
        }
    }
    //------------------------------------------------//
    //Export Site Libraries
    public function exportSiteLibraries(Request $request)
    {
        //-------------------------------------//
        $libraries = $request->input('libraries');
        if ($libraries == "" || $libraries == null || $libraries == "[]") {
            return response()->json(['message' => trans('variables.errors.sharepoint.libraries_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//
        try {
            $librariesArr = json_decode($libraries);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->type = 'library';
            $history->items_count = count($librariesArr);
            $history->name = $requestData["restoreJobName"];
            $history->sub_type = 'Export Libraries';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->save();
            //--------------------------------------//
            foreach ($librariesArr as $item) {
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->content;
                $details->item_parent_id = $item->siteId;
                $details->item_parent_name = $item->siteTitle;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $organization = auth()->user()->organization;
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            //--------------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $requestData['jobId'])->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
            ];
            //--------------------------------------//
            dispatch(new ExportSharepointBackground(auth()->id(), $history->id, 'library', $storageData, $sessionData));
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.sharepoint.export_libraries')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Exporting Site Libraries ' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.export_libraries')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Site Documents
    public function restoreSiteDocuments(Request $request)
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
            return response()->json(['message' => trans('variables.errors.sharepoint.documents_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//
        $docsArr = json_decode($docs);
        //-------------------------------------//
        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['list'] = optional($requestData)['list'];
        $options['listType'] = $requestData['listType'];
        $options['restorePermissions'] = $requestData['restorePermissions'];
        $options['documentVersion'] = $requestData['documentVersion'];
        $options['sendSharedLinksNotification'] = $requestData['sendSharedLinksNotification'];
        $options['documentLastVersionAction'] = $requestData['documentLastVersionAction'];
        try {
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'document';
            $history->sub_type = 'Restore Documents';
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
                $count += count($item->items);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->items);
                $details->item_parent_id = $item->siteId;
                $details->item_name = $item->siteTitle . '-' . $item->contentTitle;
                $details->item_parent_name = $item->siteTitle . '-' . $item->contentTitle;
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
            $sharepointData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreSharepointBackground(auth()->id(), $history->id, 'restoreSiteDocument', $sessionData, $sharepointData));
            //--------------------------------------//
            session()->forget("backgroundSharepointRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.sharepoint.restore_site_documents')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Site Documents' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.restore_site_documents')], 500);
        }
    }
    //Restore Site Items
    public function restoreSiteItems(Request $request)
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
        $items = $request->input('items');
        if ($items == "" || $items == null || $items == "[]") {
            return response()->json(['message' => trans('variables.errors.sharepoint.items_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//
        $itemsArr = json_decode($items);
        //-------------------------------------//
        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['list'] = optional($requestData)['list'];
        $options['listType'] = $requestData['listType'];
        $options['restorePermissions'] = $requestData['restorePermissions'];
        $options['documentVersion'] = $requestData['documentVersion'];
        $options['sendSharedLinksNotification'] = $requestData['sendSharedLinksNotification'];
        $options['documentLastVersionAction'] = $requestData['documentLastVersionAction'];
        try {
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'item';
            $history->sub_type = 'Restore Items';
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
            foreach ($itemsArr as $item) {
                $count += count($item->items);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->items);
                $details->item_parent_id = $item->siteId;
                $details->item_parent_name = $item->siteTitle . '-' . $item->contentTitle;
                $details->item_name = $item->siteTitle . '-' . $item->contentTitle;
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
            $sharepointData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreSharepointBackground(auth()->id(), $history->id, 'restoreSiteItem', $sessionData, $sharepointData));
            //--------------------------------------//
            session()->forget("backgroundSharepointRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.sharepoint.restore_site_items')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Site Documents' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.restore_site_items')], 500);
        }
    }
    //------------------------------------------------//
    //Export Site Documents
    public function exportSiteDocuments(Request $request)
    {
        //-------------------------------------//
        $docs = $request->input('docs');
        if ($docs == "" || $docs == null || $docs == "[]") {
            return response()->json(['message' => trans('variables.errors.sharepoint.documents_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//
        try {
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
                $count += count($item->items);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->items);
                $details->item_parent_id = $item->siteId;
                $details->item_parent_name = $item->siteTitle . '-' . $item->contentTitle;
                $details->item_name = $item->siteTitle . '-' . $item->contentTitle;
                $details->status = 'In Progress';
                $details->save();
            }
            $history->items_count = $count;
            $history->name = $requestData["restoreJobName"];
            $history->save();
            //--------------------------------------//
            $organization = auth()->user()->organization;
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            //--------------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $requestData['jobId'])->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);

            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
            ];
            //--------------------------------------//
            dispatch(new ExportSharepointBackground(auth()->id(), $history->id, 'document', $storageData, $sessionData));
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.sharepoint.export_site_documents')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Exporting Site Documents ' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.export_site_documents')], 500);
        }
    }
    //------------------------------------------------//
    //Export Site Items Attachments
    public function exportSiteItems(Request $request)
    {
        //-------------------------------------//
        $items = $request->input('docs');
        if ($items == "" || $items == null || $items == "[]") {
            return response()->json(['message' => trans('variables.errors.sharepoint.items_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//
        try {
            $itemsArr = json_decode($items);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->type = 'item-attachments';
            $history->items_count = count($itemsArr);
            $history->name = $requestData['restoreJobName'];
            $history->sub_type = 'Export Items Attachments';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->save();
            //--------------------------------------//
            foreach ($itemsArr as $item) {
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->name;
                $details->item_parent_id = $item->siteId;
                $details->item_parent_name = $item->siteTitle . '-' . $item->contentTitle;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $organization = auth()->user()->organization;
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            //--------------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $requestData['jobId'])->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);

            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
            ];
            //--------------------------------------//
            dispatch(new ExportSharepointBackground(auth()->id(), $history->id, 'item', $storageData, $sessionData));
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.sharepoint.export_site_items')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Exporting Site Items ' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.export_site_items')], 500);
        }
    }
    //------------------------------------------------//
    //Download Site Document
    public function downloadSiteDocument(Request $request)
    {
        set_time_limit(300);
        $requestData = $request->all();
        $siteId = $request->input('siteId');
        $documentId = $request->input('documentId');
        $fileSize = $request->input('fileSize');
        $contentType = $request->input('contentType');
        if ($siteId == "" || $siteId == null || $documentId == '' || $documentId == '') {
            return response()->json(['message' => trans('variables.errors.sharepoint.documents_required')], 500);
        }
        try {
            $this->checkRestoreSession();
            //--------------------------------//
            $fileSize = str_replace(" MB", '', $fileSize);
            $downloadLimit = config('parameters.DIRECT_DOWNLOAD_MEGA_LIMIT');
            if ($fileSize > $downloadLimit) {
                //--------------------------------//
                $docsArr[0] = (object) [
                    "siteId" => $siteId,
                    "siteTitle" => $request->input('siteTitle'),
                    "contentTitle" => $request->input('libraryTitle'),
                    "items" => [
                        [
                            "id" => $documentId,
                            "name" => $request->input('name'),
                            "siteId" => $siteId,
                            "siteTitle" => $request->input('siteTitle'),
                            "contentTitle" => $request->input('libraryTitle'),
                        ],
                    ],
                ];
                $docs = json_encode($docsArr);
                //--------------------------------//
                $docsArr = json_decode($docs);
                $history = new BacRestoreHistory();
                $history->organization_id = auth()->user()->organization->id;
                $history->status = 'In Progress';
                $history->type = 'document';
                $history->items_count = count($docsArr);
                $history->name = $history->items_count . '_SharepointDocuments_' . date("Ymd_His");
                $history->sub_type = 'Export Documents';
                $history->backup_job_id = $requestData['jobId'];
                $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
                $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
                $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
                $history->request_time = Carbon::now();
                $history->save();
                //--------------------------------------//
                foreach ($docsArr as $item) {
                    $details = new BacRestoreHistoryDetail();
                    $details->restore_history_id = $history->id;
                    $details->item_id = json_encode($item->items);
                    $details->item_parent_id = $item->siteId;
                    $details->item_parent_name = $item->siteTitle . '-' . $item->contentTitle;
                    $details->item_name = $item->siteTitle . '-' . $item->contentTitle;
                    $details->status = 'In Progress';
                    $details->save();
                }
                //--------------------------------------//
                $organization = auth()->user()->organization;
                $sessionData = [
                    'time' => $requestData['jobTime'],
                    'showDeleted' => $requestData['showDeleted'],
                    'showVersions' => $requestData['showVersions'],
                    'orgId' => $organization->veeam_organization_guid,
                ];
                //--------------------------------------//
                $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $requestData['jobId'])->first();
                $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);

                $storageData = [
                    'storageAccount' => $backupJobData->storage_account_name,
                    'accountKey' => $storageAccountKey,
                    'containerName' => $backupJobData->restore_container,
                ];
                //--------------------------------------//
                dispatch(new ExportSharepointBackground(auth()->id(), $history->id, 'document', $storageData, $sessionData));
                //--------------------------------//
                return response()->json(['message' => trans('variables.success.sharepoint.download_document')], 200);
            }
            //--------------------------------//
            try {
                $data = $this->_managerVeeam->downloadSiteDocument(session('restoreSharepointSessionId'), $siteId, $documentId);
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
                $ext = end($tempArr);
                $ext = str_replace('"', '', $ext);
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
            } catch (Exception $e) {
                Log::log('error', 'Exception While Download Site Document' . $e->getMessage());
            }
            return response()->json(['file' => $url]);
            //--------------------------------//
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Downloading Site Document ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.download_document')], 500);
        }
    }
    //------------------------------------------------//
    //Download Site Item Attachments
    public function downloadSiteItem(Request $request)
    {
        set_time_limit(300);
        $requestData = $request->all();
        $siteId = $request->input('siteId');
        $documentId = $request->input('documentId');
        $contentType = $request->input('contentType');
        if ($siteId == "" || $siteId == null || $documentId == '' || $documentId == '') {
            return response()->json(['message' => trans('variables.errors.sharepoint.documents_required')], 500);
        }
        try {
            $this->checkRestoreSession();
            //--------------------------------//
            $attachments = $this->_managerVeeam->getSiteItemsAttachments(session('restoreSharepointSessionId'), $siteId, $documentId)['data']->results;
            if ($attachments == []) {
                return response()->json(['message' => trans('variables.errors.sharepoint.item_no_attachments')], 500);
            }
            //--- Calculate Size
            $total = 0;
            foreach ($attachments as $attach) {
                $total += $attach->sizeBytes;
            }
            //-------------------------//
            $fileSize = round($total / 1024 / 1024);
            $downloadLimit = config('parameters.DIRECT_DOWNLOAD_MEGA_LIMIT');
            if ($fileSize > $downloadLimit) {
                //--------------------------------//
                $history = new BacRestoreHistory();
                $history->organization_id = auth()->user()->organization->id;
                $history->status = 'In Progress';
                $history->type = 'item-attachments';
                $history->items_count = 1;
                $history->name = $history->items_count . '_SharepointItemsAttachments_' . date("Ymd_His");
                $history->sub_type = 'Export Items Attachments';
                $history->backup_job_id = $requestData['jobId'];
                $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
                $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
                $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
                $history->request_time = Carbon::now();
                $history->save();
                //--------------------------------------//
                $attachments = [];
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $documentId;
                $details->item_name = $request->name;
                $details->item_parent_id = $request->siteId;
                $details->item_parent_name = $request->siteTitle . '-' . $request->contentTitle;
                $details->status = 'In Progress';
                $details->save();
                $history->options = json_encode([$details->id => $attachments]);
                $history->save();
                //--------------------------------------//
                $organization = auth()->user()->organization;
                $sessionData = [
                    'time' => $requestData['jobTime'],
                    'showDeleted' => $requestData['showDeleted'],
                    'showVersions' => $requestData['showVersions'],
                    'orgId' => $organization->veeam_organization_guid,
                ];
                //--------------------------------------//
                $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $requestData['jobId'])->first();
                $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
                $storageData = [
                    'storageAccount' => $backupJobData->storage_account_name,
                    'accountKey' => $storageAccountKey,
                    'containerName' => $backupJobData->restore_container,
                ];
                //--------------------------------------//
                dispatch(new ExportSharepointBackground(auth()->id(), $history->id, 'item', $storageData, $sessionData));
                //--------------------------------//
                return response()->json(['message' => trans('variables.success.sharepoint.download_document')], 200);
            }
            //--------------------------------//
            try {
                $data = $this->_managerVeeam->exportSiteItemAttachments(session('restoreSharepointSessionId'), $siteId, $documentId, json_encode($attachments));
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
                $ext = end($tempArr);
                $ext = str_replace('"', '', $ext);
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
            } catch (Exception $e) {
                Log::log('error', 'Exception While Download Site Item Attachments' . $e->getMessage());
            }
            return response()->json(['file' => $url]);
            //--------------------------------//
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Downloading Site Item Attachments ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.download_item_attachments')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Site Folders
    public function restoreSiteFolders(Request $request)
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
            return response()->json(['message' => trans('variables.errors.sharepoint.folders_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        $foldersArr = json_decode($folders);
        //-------------------------------------//

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['list'] = optional($requestData)['list'];
        $options['listType'] = $requestData['listType'];
        $options['restorePermissions'] = $requestData['restorePermissions'];
        $options['documentVersion'] = $requestData['documentVersion'];
        $options['sendSharedLinksNotification'] = $requestData['sendSharedLinksNotification'];
        $options['documentLastVersionAction'] = $requestData['documentLastVersionAction'];
        try {
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'folder';
            $history->sub_type = 'Restore Folders';
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
            foreach ($foldersArr as $item) {
                $count += count($item->items);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->items);
                $details->item_parent_id = $item->siteId;
                $details->item_parent_name = $item->siteTitle . '-' . $item->contentTitle;
                $details->item_name = $item->siteTitle . '-' . $item->contentTitle;
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
            $sharepointData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreSharepointBackground(auth()->id(), $history->id, 'restoreSiteFolder', $sessionData, $sharepointData));
            //--------------------------------------//
            session()->forget("backgroundSharepointRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.sharepoint.restore_site_folders')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Site Folders' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.restore_site_folders')], 500);
        }
    }
    //------------------------------------------------//
    //Export Site Folders
    public function exportSiteFolders(Request $request)
    {
        //-------------------------------------//
        $folders = $request->input('folders');
        if ($folders == "" || $folders == null || $folders == "[]") {
            return response()->json(['message' => trans('variables.errors.sharepoint.folders_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//
        try {
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
            $count = 0;
            foreach ($foldersArr as $item) {
                $count += count($item->items);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->items);
                $details->item_parent_id = $item->siteId;
                $details->item_parent_name = $item->siteTitle . '-' . $item->contentTitle;
                $details->item_name = $item->siteTitle . '-' . $item->contentTitle;
                $details->status = 'In Progress';
                $details->save();
            }
            $history->items_count = $count;
            $history->name = $requestData["restoreJobName"];
            $history->save();
            //--------------------------------------//
            $organization = auth()->user()->organization;
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            //--------------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $requestData['jobId'])->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
            ];
            //--------------------------------------//
            dispatch(new ExportSharepointBackground(auth()->id(), $history->id, 'folder', $storageData, $sessionData));
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.sharepoint.export_folders')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Exporting Site Folders ' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.export_folders')], 500);
        }
    }
    //------------------------------------------------//
    //Check Restore Session
    private function checkRestoreSession()
    {
        if (session('restoreSharepointSessionId')) {
            $sessionInfo = $this->_managerVeeam->getRestoreSession(session('restoreSharepointSessionId'))['data'];
            if ($sessionInfo->state != "Working") {
                session()->forget('restoreSharepointSessionId');
                throw new \Exception("Session Is Expired");
            }
        } else {
            throw new \Exception("Session Is Expired");
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
            return $this->_managerVeeam->createRestoreSession($organization->veeam_organization_guid, $time, "vesp", $showDeleted, $showVersions)['data'];
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Creating Restore Session  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.sharepoint.create_restore_session')], 500);
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
            $oldRestoreSessionId = session()->get("backgroundSharepointRestoreSessionGuid");
            if ($oldRestoreSessionId) {
                $oldRestoreSession = $this->_managerVeeam->getRestoreSession($oldRestoreSessionId)["data"];
                if ($oldRestoreSession->state == "Working")
                    $this->_managerVeeam->stopRestoreSession($oldRestoreSessionId);
                session()->forget("backgroundSharepointRestoreSessionGuid");
            }
            //-------------------------------------------//
            if ($request->jobType == "all") {
                $restoreSession = $this->_managerVeeam->createRestoreSession($organization->veeam_organization_guid, $request->jobTime, "vesp", $request->showDeleted, $request->showVersions)["data"];
            } else {
                $backupJobData = VeeamBackupJob::where("id", $request->jobId)->first();
                $restoreSession = $this->_managerVeeam->createJobRestoreSession($backupJobData->guid, $request->jobTime, "vesp", $request->showDeleted, $request->showVersions);
            }
            //-------------------------------------------//
            session()->put("backgroundSharepointRestoreSessionGuid", $restoreSession->id);
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
}
