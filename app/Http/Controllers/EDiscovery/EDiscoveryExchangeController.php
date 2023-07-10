<?php

namespace App\Http\Controllers\EDiscovery;

use App\Engine\Azure\ManagerAzure;
use App\Engine\Veeam\ManagerVeeam;
use App\Jobs\EDiscoveryExchangeBackground;
use App\Models\BacEDiscoveryJob;
use App\Models\VeeamBackupJob;
use App\Models\ViewOrganizationBackupJob;
use App\Models\ViewOrganizationEdiscoveryJob;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EDiscoveryExchangeController extends BaseController
{
    private $_managerVeeam;
    private $_managerAzure;
    //------------------------------------------//
    public function __construct()
    {
        $this->_managerVeeam = new ManagerVeeam();
        $this->_managerAzure = new ManagerAzure();
    }
    //------------------------------------------//
    public function editEDiscoveryJobPage($kind, $restoreSessionId, Request $request)
    {
        if ($request->type == "move") {
            return $this->moveToEdiscovery();
        }

        session()->forget("e-discovery_{$kind}_data");
        $repo_kind = "Exchange";
        $jobs = ViewOrganizationBackupJob::where("backup_job_kind", $kind)->where("organization_id", auth()->user()->organization->id)->get();
        $arr = [
            "kind" => $kind,
            "jobs" => $jobs,
            "categories" => config("app.search_criteria.$kind.categories"),
            "conditions" => config("app.search_criteria.conditions"),
        ];
        //---------------------------------------------//
        $fields = config("app.search_criteria.$kind.fields");
        $arr['categoriesFields']["All"] = $fields;
        foreach ($arr['categories'] as $category) {
            $arr['categoriesFields'][$category] = array_values(array_filter(array_map(function ($value) use ($category) {
                if (strpos(",{$value['category']},", ",$category,") !== false) {
                    return $value;
                }
            }, $fields)));
        }
        //---------------------------------------------//
        $ediscoveryJob = ViewOrganizationEdiscoveryJob::where('restore_session_guid', $restoreSessionId)->first();
        if (!$ediscoveryJob) {
            $ediscoveryJob = ViewOrganizationEdiscoveryJob::where('id', $restoreSessionId)->first();
        }
        if (!$ediscoveryJob) {
            return abort(404);
        }

        $selectedItems = json_decode($ediscoveryJob->search_data);
        $selectedMailboxes = array_combine(array_column($selectedItems, "mailboxId"), $selectedItems);
        $selectedFolders = array_combine(array_column($selectedItems, "folderId"), $selectedItems);
        $arr['job'] = $ediscoveryJob;
        //---------------------------------------------//
        //----- Get Selected Job Points
        if ($ediscoveryJob->restore_point_type == "all") {
            $arr['jobPoints'] = $this->getRestoreTimes($ediscoveryJob->backup_job_kind, "all");
        } else {
            $arr['jobPoints'] = $this->getRestoreTimes($ediscoveryJob->backup_job_kind, $ediscoveryJob->backup_job_id);
        }

        //---------------------------------------------//
        //----- Create Session
        try {
            if (session('restoreExchangeSessionId')) {
                $session = $this->_managerVeeam->getRestoreSession(session('restoreExchangeSessionId'))['data'];
                if ($session->state != "Stopped") {
                    $this->_managerVeeam->stopRestoreSession(session('restoreExchangeSessionId'));
                }

                session()->forget('restoreExchangeSessionId');
            }
            if ($ediscoveryJob->restore_point_type == 'all') {
                $organization = auth()->user()->organization;
                $restoreSession = $this->_managerVeeam->createRestoreSession($organization->veeam_organization_guid, $ediscoveryJob->restore_point_time, "vex", $ediscoveryJob->is_restore_point_show_deleted, $ediscoveryJob->is_restore_point_show_version)['data'];
            } else {
                $backupJob = VeeamBackupJob::where("id", $ediscoveryJob->backup_job_id)->first();
                $restoreSession = $this->_managerVeeam->createJobRestoreSession($backupJob->guid, $ediscoveryJob->restore_point_time, 'vex', $ediscoveryJob->is_restore_point_show_deleted, $ediscoveryJob->is_restore_point_show_version);
            }
            //--------------------------------------//
            //----- Get Mailboxes
            $mailboxes = $this->_managerVeeam->getExchangeMailBoxes($restoreSession->id)['data']->results;
            session()->put('restoreExchangeSessionId', $restoreSession->id);
            $mailboxes = $this->filterMailboxes($mailboxes, 'asc');
            //--------------------------------------//
            //----- Get Mailboxes Folders
            foreach ($mailboxes as $item) {
                $item->selected = false;
                $item->folders = '';
                if (optional($selectedMailboxes)[$item->id]) {
                    if ($selectedMailboxes[$item->id]->folderId == "-1") {
                        $item->selected = true;
                    } else {
                        $item->folders = $this->getMailboxFolders($item->id, $selectedFolders);
                    }

                }
            }
            //---------------------------------------------//
            $arr['mailboxes'] = $mailboxes;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Creating Restore Sessions  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.create_restore_session')], 500);
        }
        //---------------------------------------------//
        if (!view()->exists("ediscovery.$kind")) {
            return abort(404);
        }

        return response()->view("ediscovery.$kind", compact('arr'));
    }
    //------------------------------------------//
    public function moveToEdiscovery()
    {
        $kind = "exchange";
        $organization = auth()->user()->organization;
        $jobs = ViewOrganizationBackupJob::where("backup_job_kind", $kind)->where("organization_id", $organization->id)->get();
        $arr = [
            "kind" => $kind,
            "jobs" => $jobs,
            "categories" => config("app.search_criteria.$kind.categories"),
            "conditions" => config("app.search_criteria.conditions"),
        ];
        $requestData = json_decode(session("e-discovery_exchange_data"));
        $arr['job'] = (object) null;
        $arr['job']->backup_job_id = $requestData->backupJobId;
        $arr['job']->restore_point_type = $requestData->restorePointType;
        $arr['job']->restore_point_time = $requestData->jobTime;
        $arr['job']->is_restore_point_show_deleted = $requestData->showDeleted;
        $arr['job']->is_restore_point_show_version = $requestData->showVersions;
        //---------------------------------------------//
        $fields = config("app.search_criteria.$kind.fields");
        $arr['pageType'] = "move";
        $arr['categoriesFields']["All"] = $fields;
        foreach ($arr['categories'] as $category) {
            $arr['categoriesFields'][$category] = array_values(array_filter(array_map(function ($value) use ($category) {
                if (strpos(",{$value['category']},", ",$category,") !== false) {
                    return $value;
                }
            }, $fields)));
        }
        //---------------------------------------------//
        $selectedMailboxes = json_decode($requestData->selectedMailboxes);
        $selectedFolders = json_decode($requestData->selectedFolders);
        //---------------------------------------------//
        //----- Get Selected Job Points
        if ($requestData->restorePointType == "all") {
            $arr['jobPoints'] = $this->getRestoreTimes($kind, "all");
        } else {
            $arr['jobPoints'] = $this->getRestoreTimes($kind, $requestData->backupJobId);
        }

        //---------------------------------------------//
        //----- Create Session
        try {
            if (!session('restoreExchangeSessionId')) {
                if ($requestData->restorePointType == 'all') {
                    $restoreSession = $this->_managerVeeam->createRestoreSession($organization->veeam_organization_guid, $requestData->jobTime, "vex", $requestData->showDeleted, $requestData->showVersions)['data'];
                } else {
                    $backupJob = VeeamBackupJob::where("id", $requestData->backupJobId)->first();
                    $restoreSession = $this->_managerVeeam->createJobRestoreSession($backupJob->guid, $requestData->jobTime, 'vex', $requestData->showDeleted, $requestData->showVersions);
                }
            }
            $restoreSession = $this->_managerVeeam->getRestoreSession(session('restoreExchangeSessionId'))['data'];
            //--------------------------------------//
            //----- Get Mailboxes
            $mailboxes = $this->_managerVeeam->getExchangeMailBoxes($restoreSession->id)['data']->results;
            session()->put('restoreExchangeSessionId', $restoreSession->id);
            $mailboxes = $this->filterMailboxes($mailboxes, 'asc');
            //--------------------------------------//
            $selectedMailboxes = array_combine(array_column($selectedMailboxes, "mailboxId"), $selectedMailboxes);
            $selectedFolders = array_combine(array_column($selectedFolders, "folderId"), $selectedFolders);
            $tempSelectedFolders = array_combine(array_column($selectedFolders, "mailboxId"), $selectedFolders);
            //----- Get Mailboxes Folders
            foreach ($mailboxes as $item) {
                $item->selected = false;
                $item->folders = '';
                if (optional($selectedMailboxes)[$item->id]) {
                    $item->selected = true;
                }
                $item->selectedParent = false;
                if (optional($tempSelectedFolders)[$item->id]) {
                    $item->folders = $this->getMailboxFolders($item->id, $selectedFolders);
                    if (optional($selectedMailboxes)[$item->id]) {
                        $item->selectedParent = true;
                    }

                }
            }
            //---------------------------------------------//
            $arr['mailboxes'] = $mailboxes;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Creating Restore Sessions  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.create_restore_session')], 500);
        }
        //---------------------------------------------//
        if (!view()->exists("ediscovery.$kind")) {
            return abort(404);
        }

        return response()->view("ediscovery.$kind", compact('arr'));
    }
    //------------------------------------------//
    public function getEdiscoveryJobResult($kind, $id, Request $request)
    {
        //-----------------------------------------------------//
        $validator = Validator::make($request->all(), [
            'filter' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["data"=>[]]);
        }
        //-----------------------------------------------------//
        $limit = config('parameters.EXPLORING_EDISCOVERY_ITEMS_LIMIT_COUNT');
        //-----------------------------------------------------//
        $ediscoveryJob = BacEDiscoveryJob::where('id', $id)->first();
        //-----------------------------------------------------//
        $totalCount = $ediscoveryJob->total_items;
        if ($request->filter) {
            $filter = $request->filter;
            $tempArr = array_values(array_filter(array_map(function ($value) use ($filter) {
                return $value->mailboxName . ($value->folderName ? "_" . $value->folderName : "") == $filter ? $value : '';
            }, json_decode($ediscoveryJob['search_data']))))[0];
            $totalCount = $tempArr->count;
        }
        if ($totalCount < $limit) {
            $limit = $totalCount;
        }

        //-----------------------------------------------------//
        $user = auth()->user();
        //-----------------------------------------------------//
        $organization = $user->organization;
        $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $ediscoveryJob->backup_job_id)->first();
        $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
        //-----------------------------------------------------//
        if ($request->nextPartition) {
            $result = $this->_managerAzure->tableSelectPageData($backupJobData->storage_account_name, $storageAccountKey, $kind . $id, $limit, $request->nextPartition, $request->nextRow, $request->filter);
        } else {
            $result = $this->_managerAzure->tableSelectData($backupJobData->storage_account_name, $storageAccountKey, $kind . $id, $limit, $request->filter);
        }

        $nextPartition = optional(optional($result['header'])['x-ms-continuation-NextPartitionKey'])[0];
        $nextRow = optional(optional($result['header'])['x-ms-continuation-NextRowKey'])[0];
        $data = $result['data']->value;
        //-----------------------------------------------------//
        return ['data' => $data, 'nextPartition' => $nextPartition, 'nextRow' => $nextRow, "totalCount" => $totalCount, "pageItemsCount" => $limit];
    }
    //------------------------------------------//
    public function saveEDiscoveryJob(Request $request)
    {
        //-------------------------------//
        if ($request->ediscoveryJobId) {
            $eJob = BacEDiscoveryJob::where('id', $request->ediscoveryJobId)->first()->delete();
        }

        //-------------------------------//
        $eJob = new BacEDiscoveryJob();
        $eJob->name = $request->jobName;
        $user = auth()->user();
        $eJob->organization_id = $user->organization->id;
        $eJob->status = "Running";
        $eJob->backup_job_id = $request->jobId;
        $eJob->restore_point_type = ($request->jobs == "all" ? "all" : "single");
        $eJob->restore_point_time = $request->backupTime;
        $eJob->is_restore_point_show_deleted = ($request->showDeleted == "true" ? 1 : 0);
        $eJob->is_restore_point_show_version = ($request->showVersions == "true" ? 1 : 0);
        $eJob->request_time = Carbon::now();
        $eJob->completion_time = null;
        $eJob->expiration_time = null;
        //---------------------------------//
        $searchData = [];
        foreach ($request->mailboxId as $key => $item) {
            array_push($searchData, [
                "mailboxId" => $item,
                "folderId" => $request->folderId[$key],
                "mailboxName" => $request->mailboxName[$key],
                "folderName" => $request->folderName[$key],
                "email" => $request->email[$key],
            ]);
        }
        $eJob->search_data = json_encode($searchData);
        //---------------------------------//
        $searchCriteria = [];
        $searchQueryArr = [];
        if ($request->category) {
            foreach ($request->category as $key => $item) {
                array_push($searchCriteria, [
                    "category" => $item,
                    "field" => $request->field[$key],
                    "condition" => $request->condition[$key],
                    "value" => $request->value[$key],
                ]);
                array_push($searchQueryArr, $this->convertCondition($request->kind, $request->condition[$key], $request->field[$key], $request->value[$key]));
            }
        }
        $eJob->search_criteria = json_encode($searchCriteria);
        //---------------------------------//
        $eJob->save();
        //---------------------------------//
        //---- Create Table
        $organization = $user->organization;
        $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $eJob->backup_job_id)->first();
        $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
        try {
            $this->_managerAzure->createTable($organization->azure_subscription_guid, $backupJobData->storage_account_name, $organization->azure_resource_group, $request->kind . $eJob->id);
            if ($request->ediscoveryJobId && $eJob->status != "Expired") {
                $this->_managerAzure->deleteTable($organization->azure_subscription_guid, $backupJobData->storage_account_name, $organization->azure_resource_group, $request->kind . $request->ediscoveryJobId);
            }

        } catch (Exception $ex) {
            Log::log('error', 'Exception While Creating Table ' . $ex->getMessage());
        }
        //---------------------------------//
        $azureData = [
            "accountName" => $backupJobData->storage_account_name,
            "accountKey" => $storageAccountKey,
        ];
        //---------------------------------//
        dispatch(new EDiscoveryExchangeBackground(auth()->user()->id, $eJob->id, $searchQueryArr, $azureData));
        //---------------------------------//
        return response()->json(['message' => __("variables.success.exchange")], 200);
    }
    //------------------------------------------//
    private function getRestoreTimes($kind, $jobId)
    {
        $timesSessions = array();
        $user = auth()->user();
        try {
            if ($jobId == "" || $jobId == null) {
                return [];
            }
            $timesSessions = [];
            if ($jobId == "all") {
                //-------------------------//
                $organization = $user->organization;
                $backupJobs = ViewOrganizationBackupJob::where("backup_job_kind", $kind)->where("organization_id", $organization->id)->get();
                //-------------------------//
                if (count($backupJobs) == 0) {
                    return [];
                }
                //-------------------------//
                $timesSessions = [];
                foreach ($backupJobs as $backupJob) {
                    $allSessions = $this->_managerVeeam->getVeeamJobSessions($backupJob->backup_job_guid)['data'];
                    if ($allSessions != null && !empty($allSessions->results)) {
                        foreach ($allSessions->results as $session) {
                            //-------------------
                            $tempDate = new DateTime($session->endTime, new DateTimeZone($user->timezone ?? config('app.timezone')));
                            $tempDate->setTimeZone(new DateTimeZone('UTC'));
                            $session->endTime = $tempDate->format('Y-m-d\TH:i:s\.u\0\Z');
                            //-------------------
                            if (($session->status == "Success" || $session->status == "Warning") && $session->progress > 0) {
                                array_push($timesSessions, ['date' => $session->endTime, 'id' => $backupJob->backup_job_id]);
                            }
                        }
                    }
                }
                //-------------------------//
                return $timesSessions;
            } else {
                $veeamBackupJob = VeeamBackupJob::where('id', $jobId)->first();
                $allSessions = $this->_managerVeeam->getVeeamJobSessions($veeamBackupJob->guid)['data'];
                if ($allSessions != null && !empty($allSessions->results)) {
                    foreach ($allSessions->results as $session) {
                        //-------------------
                        $tempDate = new DateTime($session->endTime, new DateTimeZone($user->timezone ?? config('app.timezone')));
                        $tempDate->setTimeZone(new DateTimeZone('UTC'));
                        $session->endTime = $tempDate->format('Y-m-d\TH:i:s\.u\0\Z');
                        //-------------------
                        if ($session->status == "Success" || $session->status == "Warning") {
                            array_push($timesSessions, ['date' => $session->endTime, 'id' => $veeamBackupJob->id]);
                        }

                    }
                    return $timesSessions;
                }
                return [];
            }
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Backup Job Sessions  ' . $ex->getMessage());
            return response()->json(['message' => "Error While Getting Backup Job Time"], 500);
        }
    }
    //------------------------------------------//
    private function getMailboxFolders($mailBoxId, $selectedFolders)
    {
        $mailFolders = $this->_managerVeeam->getMailBoxFolders(session('restoreExchangeSessionId'), $mailBoxId)['data'];
        $folders = $mailFolders->results;
        $hasFolders = [];
        $childrenFolders = [];
        $parentFolders = [];
        //-------------------------------//
        foreach ($folders as $key => $item) {
            $item->selected = false;
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
            if (optional($selectedFolders)[$item->id]) {
                $item->selected = true;
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
    }
    //------------------------------------------//
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
    //------------------------------------------//
    private function convertCondition($kind, $condition, $field, $value, $secValue = '')
    {
        //--------------------------------------//
        $fieldsArr = config("app.search_criteria.$kind.fields");
        $key = array_search($field, array_column($fieldsArr, 'name'));
        $fieldArr = $fieldsArr[$key];
        //--------------------------------------//
        $conditionsArr = config("app.search_criteria.conditions");
        $conditionArr = array_values(array_filter(array_map(function ($item) use ($condition, $fieldArr) {
            if ($item['name'] == $condition && $item['type'] == $fieldArr['type']) {
                return $item;
            }

        }, $conditionsArr)))[0];
        if ($condition == "Between") {
            $temp = explode(' - ', $value);
            $value = $temp[0];
            $tempDate = new DateTime($value);
            $tempDate->setTimeZone(new DateTimeZone('UTC'));
            $value = $tempDate->format('Y-m-d\TH:i:s\Z');
            $secValue = $temp[1];
            $tempDate = new DateTime($secValue);
            $tempDate->setTimeZone(new DateTimeZone('UTC'));
            $secValue = $tempDate->format('Y-m-d\TH:i:s\Z');
        } else if ($fieldArr['type'] == "date") {
            $tempDate = new DateTime($value);
            $tempDate->setTimeZone(new DateTimeZone('UTC'));
            $value = $tempDate->format('Y-m-d\TH:i:s\Z');
        }
        //--------------------------------------//
        $code = $conditionArr['code'];
        $code = str_replace('__FIELD__', $fieldArr['code'], $code);
        $code = str_replace('__VALUE__', $value, $code);
        $code = str_replace('__SEC_VALUE__', $secValue, $code);
        return $code;
    }
    //------------------------------------------//
    private function getStorageAccountSharedAccessKey($storageAccountName)
    {
        $organization = auth()->user()->organization;
        $storageKeys = $this->_managerAzure->getStorageAccountSharedAccessKeys($storageAccountName, $organization->azure_subscription_guid, $organization->azure_resource_group);
        $key1 = ($storageKeys->keys)[0]->value;
        return $key1;
    }
    //------------------------------------------//
}
