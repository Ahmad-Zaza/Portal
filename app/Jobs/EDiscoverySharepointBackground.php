<?php

namespace App\Jobs;

use App\Engine\Azure\ManagerAzure;
use App\Engine\Base\Manager;
use App\Engine\Veeam\ManagerVeeam;
use App\Models\AzureResourceGroup;
use App\Models\BacEDiscoveryJob;
use App\Models\BacOrganization;
use App\Models\EDiscoveryJob;
use App\Models\Organization;
use App\Models\User;
use App\Models\VeeamBackupJob;
use App\Models\ViewOrganizationEdiscoveryJob;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class EDiscoverySharepointBackground implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 60 * 60 * 12;


    public $userId;
    public $ediscoveryJobId;
    public $searchQueryArr;
    public $managerVeeam;
    public $managerAzure;
    public $azureData;
    public $siteLists;
    public $siteLibraries;
    public $session;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $ediscoveryJobId, $searchQueryArr = [], $azureData)
    {
        $this->userId = $userId;
        $this->ediscoveryJobId = $ediscoveryJobId;
        $this->searchQueryArr = $searchQueryArr;
        $this->azureData = $azureData;
        $this->siteLists = [];
        $this->session = session()->all();
        $this->siteLibraries = [];
    }

    public function middleware()
    {
        return [new WithoutOverlapping($this->ediscoveryJobId)];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        session([
            "user_id" => $this->userId,
            "veeam_access_token" => $this->session["veeam_access_token"],
            "veeam_access_token_expire" => $this->session["veeam_access_token_expire"],
            "veeam_server_url" => $this->session["veeam_server_url"],
        ]);
        $this->managerVeeam = new ManagerVeeam();
        $this->managerAzure = new ManagerAzure();
        $eJobData = ViewOrganizationEdiscoveryJob::where('id', $this->ediscoveryJobId)->first();
        $ediscoveryJob = BacEdiscoveryJob::where('id', $this->ediscoveryJobId)->first();

        $searchDataArr = json_decode($ediscoveryJob->search_data);
        $limit = config('parameters.INSERTING_EDISCOVERY_ITEMS_LIMIT_COUNT');
        try {
            $before = Carbon::now();
            $organization = BacOrganization::where('id', $ediscoveryJob->organization_id)->first();
            try {
                $sessionId = $this->createRestoreSession($organization->veeam_organization_guid, $ediscoveryJob->restore_point_time, $ediscoveryJob->is_restore_point_show_deleted, $ediscoveryJob->is_restore_point_show_version);
                $ediscoveryJob->restore_session_guid = $sessionId;
                $ediscoveryJob->save();
            } catch (Exception $ex) {
                Log::log('error', 'Exception While Creating Restore Session ' . json_encode($ex));
            }
            //-----------
            $total = 0;
            foreach ($searchDataArr as $value) {
                $foldersResult = [];
                $docsResult = [];
                $result = [];
                if ($value->listId == "-1") {
                    if (count($this->searchQueryArr) > 0) {
                        $result = $this->managerVeeam->searchSiteItems($sessionId, $value->siteId, 0, $limit, implode(' ', $this->searchQueryArr))['data']->results;
                    } else {
                        $result = $this->managerVeeam->getSiteItems($sessionId, $value->siteId, '', 0, $limit)['data']->results;
                        $foldersResult = $this->managerVeeam->getSiteFolders($sessionId, $value->siteId, '')['data']->results;
                        $docsResult = $this->managerVeeam->getSiteDocuments($sessionId, $value->siteId, '', 0, $limit)['data']->results;
                    }
                } else {
                    if (count($this->searchQueryArr)) {
                        if ($value->type == "list")
                            $result = $this->managerVeeam->searchSiteListItems($sessionId, $value->siteId, $value->listId, 0, $limit, implode(' ', $this->searchQueryArr))['data']->results;
                        else if ($value->type == "library")
                            $result = $this->managerVeeam->searchSiteLibraryItems($sessionId, $value->siteId, $value->listId, 0, $limit, implode(' ', $this->searchQueryArr))['data']->results;
                    } else {
                        if ($value->type == "list")
                            $result = $this->managerVeeam->getSiteItems($sessionId, $value->siteId, $value->listId, 0, $limit)['data']->results;
                        else if ($value->type == "library") {
                            $result = $this->managerVeeam->getSiteDocuments($sessionId, $value->siteId, $value->listId, 0, $limit)['data']->results;
                            $foldersResult = $this->managerVeeam->getSiteFolders($sessionId, $value->siteId, $value->listId)['data']->results;
                        }
                    }
                }
                $total += count($result);
                $total += count($foldersResult);
                $total += count($docsResult);
                $count = 0;
                if (count($result) > 0 || count($foldersResult) > 0 || count($docsResult) > 0) {
                    //----- Insert Library Folders Data to Azure Table
                    $stepsArrays = array_chunk($foldersResult, 100);
                    foreach ($stepsArrays as $key => $stepArr) {
                        foreach ($stepArr as $item) {
                            $count++;
                            $item->PartitionKey = $value->siteTitle . ($value->listName ? "_" . $value->listName : "");
                            $item->RowKey = "$count";
                            $item->siteId = $value->siteId;
                            $item->type = $value->type;
                            $item->listId = $value->listId;
                            $item->listTitle = $value->listName;
                            $item->siteTitle = $value->siteTitle;
                            if (!$value->listName) {
                                if (optional($item->_links)->list)
                                    $tempArr = explode("/", $item->_links->list->href);
                                else
                                    $tempArr = explode("/", $item->_links->library->href);
                                $item->listId = end($tempArr);
                                $item->listTitle = $this->getListName($sessionId, $value->siteId, $item->listId);
                            }
                            $item->itemType = "folder";
                            $item->isFolder = true;
                            $item->_links = null;
                            $item->_actions = null;
                        }
                        $tableRows = $stepArr;
                        if (count($tableRows) > 0)
                            $this->managerAzure->tableInsertDataBatch($this->azureData['accountName'], $this->azureData['accountKey'], $eJobData->backup_job_kind . $ediscoveryJob->id, $tableRows);
                        //-----------------------------//
                    }
                    //----- Insert Library Documents Data to Azure Table
                    $stepsArrays = array_chunk($docsResult, 100);
                    foreach ($stepsArrays as $key => $stepArr) {
                        foreach ($stepArr as $item) {
                            $count++;
                            $item->PartitionKey = $value->siteTitle . ($value->listName ? "_" . $value->listName : "");
                            $item->RowKey = "$count";
                            $item->siteId = $value->siteId;
                            $item->type = $value->type;
                            $item->listId = $value->listId;
                            $item->listTitle = $value->listName;
                            $item->siteTitle = $value->siteTitle;
                            if (!$value->listName) {
                                if (optional($item->_links)->list)
                                    $tempArr = explode("/", $item->_links->list->href);
                                else
                                    $tempArr = explode("/", $item->_links->library->href);
                                $item->listId = end($tempArr);
                                $item->listTitle = $this->getListName($sessionId, $value->siteId, $item->listId);
                            }
                            $item->isFolder = false;
                            $item->itemType = "document";
                            $item->_links = null;
                            $item->_actions = null;
                        }
                        $tableRows = $stepArr;
                        if (count($tableRows) > 0)
                            $this->managerAzure->tableInsertDataBatch($this->azureData['accountName'], $this->azureData['accountKey'], $eJobData->backup_job_kind . $ediscoveryJob->id, $tableRows);
                        //-----------------------------//
                    }
                    //----- Insert Data to Azure Table
                    $stepsArrays = array_chunk($result, 100);
                    foreach ($stepsArrays as $key => $stepArr) {
                        foreach ($stepArr as $item) {
                            $count++;
                            $item->PartitionKey = $value->siteTitle . ($value->listName ? "_" . $value->listName : "");
                            $item->RowKey = "$count";
                            $item->siteId = $value->siteId;
                            $item->type = $value->type;
                            $item->listId = $value->listId;
                            $item->listTitle = $value->listName;
                            $item->siteTitle = $value->siteTitle;
                            if (!$value->listName) {
                                if (optional($item->_links)->list)
                                    $tempArr = explode("/", $item->_links->list->href);
                                else
                                    $tempArr = explode("/", $item->_links->library->href);
                                $item->listId = end($tempArr);
                                $item->listTitle = $this->getListName($sessionId, $value->siteId, $item->listId);
                            }
                            //---- check item type
                            if (strpos($item->name, ".000")) {
                                $item->itemType = "item";
                            } else if (strpos($item->name, ".")) {
                                $item->itemType = "document";
                            } else {
                                $item->itemType = "folder";
                            }
                            //------------------------------//
                            $item->isFolder = false;
                            $item->_links = null;
                            $item->_actions = null;
                        }
                        $tableRows = $stepArr;
                        if (count($tableRows) > 0)
                            $this->managerAzure->tableInsertDataBatch($this->azureData['accountName'], $this->azureData['accountKey'], $eJobData->backup_job_kind . $ediscoveryJob->id, $tableRows);
                        //-----------------------------------//
                    }
                    //----- Get Next Result
                    $offset = 0;
                    while (count($result) >= $limit) {
                        $offset += count($result);
                        if ($value->listId == "-1") {
                            if (count($this->searchQueryArr) > 0) {
                                $result = $this->managerVeeam->searchSiteItems($sessionId, $value->siteId, $offset, $limit, implode(' ', $this->searchQueryArr))['data']->results;
                            } else {
                                $result = $this->managerVeeam->getSiteItems($sessionId, $value->siteId, '', $offset, $limit)['data']->results;
                            }
                        } else {
                            if (count($this->searchQueryArr)) {
                                if ($value->type == "list")
                                    $result = $this->managerVeeam->searchSiteListItems($sessionId, $value->siteId, $value->listId, $offset, $limit, implode(' ', $this->searchQueryArr))['data']->results;
                                else if ($value->type == "library")
                                    $result = $this->managerVeeam->searchSiteLibraryItems($sessionId, $value->siteId, $value->listId, $offset, $limit, implode(' ', $this->searchQueryArr))['data']->results;
                            } else {
                                if ($value->type == "list")
                                    $result = $this->managerVeeam->getSiteItems($sessionId, $value->siteId, $value->listId, $offset, $limit)['data']->results;
                                else if ($value->type == "library")
                                    $result = $this->managerVeeam->getSiteDocuments($sessionId, $value->siteId, $value->listId, $offset, $limit)['data']->results;
                            }
                        }
                        $total += count($result);
                        $count = 0;
                        //----- Insert Data to Azure Table
                        $stepsArrays = array_chunk($result, 100);
                        foreach ($stepsArrays as $key => $stepArr) {
                            foreach ($stepArr as $item) {
                                $count++;
                                $item->PartitionKey = $value->siteTitle . ($value->listName ? "_" . $value->listName : "");
                                $item->RowKey = "$count";
                                $item->siteId = $value->siteId;
                                $item->type = $value->type;
                                $item->listId = $value->listId;
                                $item->listTitle = $value->listName;
                                $item->siteTitle = $value->siteTitle;
                                if (!$value->listName) {
                                    if (optional($item->_links)->list)
                                        $tempArr = explode("/", $item->_links->list->href);
                                    else
                                        $tempArr = explode("/", $item->_links->library->href);
                                    $item->listId = end($tempArr);
                                    $item->listTitle = $this->getListName($sessionId, $value->siteId, $item->listId);
                                }
                                if (strpos($item->name, ".000")) {
                                    $item->itemType = "item";
                                } else if (strpos($item->name, ".")) {
                                    $item->itemType = "document";
                                } else {
                                    $item->itemType = "folder";
                                }
                                $item->isFolder = false;
                                $item->_links = null;
                                $item->_actions = null;
                            }
                            //-----------------------------//
                            $tableRows = $stepArr;
                            if (count($tableRows) > 0)
                                $this->managerAzure->tableInsertDataBatch($this->azureData['accountName'], $this->azureData['accountKey'], $eJobData->backup_job_kind . $ediscoveryJob->id, $tableRows);
                            //-----------------------------//
                        }
                    }
                    //-----------------------------------//
                    //----- Get Next Docs Result
                    $offset = 0;
                    while (count($docsResult) >= $limit) {
                        $offset += count($docsResult);
                        if ($value->listId == "-1") {
                            if (count($this->searchQueryArr) == 0) {
                                $docsResult = $this->managerVeeam->getSiteDocuments($sessionId, $value->siteId, '', 0, $limit)['data']->results;
                            }
                        } else {
                            if (count($this->searchQueryArr) == 0) {
                                if ($value->type == "library") {
                                    $docsResult = $this->managerVeeam->getSiteDocuments($sessionId, $value->siteId, $value->listId, 0, $limit)['data']->results;
                                }
                            }
                        }
                        $total += count($docsResult);
                        $count = 0;
                        //----- Insert Data to Azure Table
                        $stepsArrays = array_chunk($docsResult, 100);
                        foreach ($stepsArrays as $key => $stepArr) {
                            foreach ($stepArr as $item) {
                                $count++;
                                $item->PartitionKey = $value->siteTitle . ($value->listName ? "_" . $value->listName : "");
                                $item->RowKey = "$count";
                                $item->siteId = $value->siteId;
                                $item->type = $value->type;
                                $item->listId = $value->listId;
                                $item->listTitle = $value->listName;
                                $item->siteTitle = $value->siteTitle;
                                if (!$value->listName) {
                                    if (optional($item->_links)->list)
                                        $tempArr = explode("/", $item->_links->list->href);
                                    else
                                        $tempArr = explode("/", $item->_links->library->href);
                                    $item->listId = end($tempArr);
                                    $item->listTitle = $this->getListName($sessionId, $value->siteId, $item->listId);
                                }
                                $item->itemType = "document";
                                $item->isFolder = false;
                                $item->_links = null;
                                $item->_actions = null;
                            }
                            //-----------------------------//
                            $tableRows = $stepArr;
                            if (count($tableRows) > 0)
                                $this->managerAzure->tableInsertDataBatch($this->azureData['accountName'], $this->azureData['accountKey'], $eJobData->backup_job_kind . $ediscoveryJob->id, $tableRows);
                            //-----------------------------//
                        }
                    }
                    //-----------------------------------//
                }
                $value->count = $count;
            }
            //-------------------------------------//
            $ediscoveryJob->status             = 'Success';
            $ediscoveryJob->completion_time    = Carbon::now();
            $ediscoveryJob->expiration_time    = Carbon::now()->add(config('parameters.EXPORTED_FILES_EXPIRATION_DAYS'), 'days');
            $after                             = Carbon::now();
            $ediscoveryJob->duration           = $after->diffAsCarbonInterval($before);
            $ediscoveryJob->restore_session_guid = $sessionId;
            $ediscoveryJob->total_items = $total;
            $ediscoveryJob->search_data = json_encode($searchDataArr);
            $ediscoveryJob->save();
            $this->stopRestoreSession($sessionId);
            //-------------------------------------//
        } catch (Exception $e) {
            $ediscoveryJob->status             = 'Failed';
            $ediscoveryJob->completion_time    = Carbon::now();
            $ediscoveryJob->restore_session_guid = $sessionId;
            $ediscoveryJob->save();
            Log::log('error', 'Exception While Searching Sharepoint Items ' .  $e);
        }
    }
    //-------------------------------------------------------------------------//
    private function createRestoreSession($organizationId, $time, $showDeleted, $showVersions)
    {
        try {
            return $this->managerVeeam->createRestoreSession($organizationId, $time, "vesp", $showDeleted, $showVersions)['data']->id;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Creating Restore Sessions  ' . $ex->getMessage());
        }
    }
    //-------------------------------------------------------------------------//
    private function stopRestoreSession($sessionId)
    {
        try {
            if (!$this->managerVeeam) {
                $this->managerVeeam = new ManagerVeeam();
            }
            $session = $this->managerVeeam->getRestoreSession($sessionId)['data'];
            if ($session->state != "Stopped")
                return $this->managerVeeam->stopRestoreSession($sessionId);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Stopping Restore Sessions  ' . $ex->getMessage());
        }
    }
    //-------------------------------------------------------------------------//
    private function convertCondition($kind, $condition, $field, $value, $secValue = '')
    {
        //--------------------------------------//
        $fieldsArr = config("app.search_criteria.$kind.fields");
        $key = array_search($field, array_column($fieldsArr, 'name'));
        $fieldArr = $fieldsArr[$key];
        //--------------------------------------//
        $conditionsArr = config("app.search_criteria.conditions");
        $conditionArr = array_values(array_filter(array_map(function ($item) use ($condition, $fieldArr) {
            if ($item['name'] == $condition && $item['type'] == $fieldArr['type'])
                return $item;
        }, $conditionsArr)))[0];
        if ($condition == "Between") {
            $temp = explode(' - ', $value);
            $value = $temp[0];
            $secValue = $temp[1];
        }
        //--------------------------------------//
        $code = $conditionArr['code'];
        $code = str_replace('__FIELD__', $fieldArr['code'], $code);
        $code = str_replace('__VALUE__', $value, $code);
        $code = str_replace('__SEC_VALUE__', $secValue, $code);
        return $code;
    }
    //-------------------------------------------------------------------------//
    private function getListName($sessionId, $siteId, $listId)
    {
        if (optional(optional($this->siteLists)[$siteId])[$listId]) {
            return $this->siteLists[$siteId][$listId];
        } else {
            $this->siteLists[$siteId] = $this->managerVeeam->getSiteLists($sessionId, $siteId)['data']->results;
            $this->siteLists[$siteId] = array_column($this->siteLists[$siteId], 'name', 'id');
            if (!optional(optional($this->siteLists)[$siteId])[$listId]) {
                $this->siteLibraries[$siteId] = $this->managerVeeam->getSiteLibraries($sessionId, $siteId)['data']->results;
                $this->siteLibraries[$siteId] = array_column($this->siteLibraries[$siteId], 'name', 'id');
                return $this->siteLibraries[$siteId][$listId];
            }
            return $this->siteLists[$siteId][$listId];
        }
    }
    //-------------------------------------------------------------------------//
    public function failed(Throwable $e)
    {
        Log::log('error', 'Exception Job Failed ' .  $e);
        $ediscoveryJob = BacEDiscoveryJob::where('id', $this->ediscoveryJobId)->first();
        //--------------------------------------//
        if (optional($ediscoveryJob)->restore_session_guid)
            $this->stopRestoreSession($ediscoveryJob->restore_session_guid);
        //--------------------------------------//
        if ($ediscoveryJob) {
            $ediscoveryJob->status = 'Failed';
            $ediscoveryJob->completion_time = Carbon::now();
            $ediscoveryJob->save();
        }
    }
    //-------------------------------------------------------------------------//
}
