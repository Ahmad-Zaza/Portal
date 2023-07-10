<?php

namespace App\Jobs;

use App\Engine\Azure\ManagerAzure;
use App\Engine\Base\Manager;
use App\Engine\Veeam\ManagerVeeam;
use App\Models\BacEDiscoveryJob;
use App\Models\BacOrganization;
use App\Models\EDiscoveryJob;
use App\Models\Organization;
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

class EDiscoveryTeamsBackground implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 1;
    public $timeout = 60 * 60 * 12;
    public $userId;
    public $ediscoveryJobId;
    public $searchQueryArr;
    public $teamChannels;
    public $managerVeeam;
    public $managerAzure;
    public $azureData;
    public $session;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $ediscoveryJobId, $searchQueryArr = [], $azureData)
    {
        $this->teamChannels = [];
        $this->userId = $userId;
        $this->ediscoveryJobId = $ediscoveryJobId;
        $this->searchQueryArr = $searchQueryArr;
        $this->azureData = $azureData;
        $this->session = session()->all();
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
            //-----------
            foreach ($searchDataArr as $value) {
                $filesResult = $tabsResult = [];
                if ($value->channelId == "-1") {
                    if (count($this->searchQueryArr) > 0) {
                        $result = $this->managerVeeam->searchTeamItems($sessionId, $value->teamId, 0, $limit, implode(' ', $this->searchQueryArr), $value->type)['data']->results;
                    }
                } else {
                    if (count($this->searchQueryArr)) {
                        $result = $this->managerVeeam->searchTeamChannelItems($sessionId, $value->teamId, $value->channelId, 0, $limit, implode(' ', $this->searchQueryArr), $value->type)['data']->results;
                        $filesResult = [];
                        $tabsResult = [];
                    } else {
                        $result = $this->managerVeeam->getChannelPosts($sessionId, $value->teamId, $value->channelId, 0, $limit)['data']->results;
                        $filesResult = $this->managerVeeam->getChannelFiles($sessionId, $value->teamId, $value->channelId, "", 0, $limit)['data']->results;
                        $tabsResult = $this->managerVeeam->getChannelTabs($sessionId, $value->teamId, $value->channelId)['data']->results;
                    }
                }
                $total += count($result);
                $total += count($filesResult);
                $total += count($tabsResult);
                $count = 0;
                if (count($result) > 0 || count($filesResult) > 0 || count($tabsResult) > 0) {
                    //----- Insert Files Data to Azure Table
                    $stepsArrays = array_chunk($filesResult, 100);
                    foreach ($stepsArrays as $key => $stepArr) {
                        foreach ($stepArr as $item) {
                            $count++;
                            $item->PartitionKey = $value->teamName . ($value->channelName ? "_" . $value->channelName : "");
                            $item->RowKey = "$count";
                            $item->team = $value->teamId;
                            $item->teamTitle = $value->teamName;
                            $item->channel = $value->channelId;
                            $item->channel = $value->channelId;
                            $item->channelTitle = $value->channelName;
                            if ($value->channelId == "-1") {
                                $tempArr = explode("/", $item->_links->channel->href);
                                $item->channel = end($tempArr);
                                $item->channelTitle = $this->getChannelName($sessionId, $value->teamId, $item->channel);
                            }
                            $item->type = $value->type;
                            $item->_links = null;
                            $item->attachments = null;
                            $item->_actions = null;
                        }
                        $tableRows = $stepArr;
                        if ($tableRows)
                            $this->managerAzure->tableInsertDataBatch($this->azureData['accountName'], $this->azureData['accountKey'], $eJobData->backup_job_kind . $ediscoveryJob->id, $tableRows);
                    }
                    //-----------------------------//
                    //----- Insert Tabs Data to Azure Table
                    $stepsArrays = array_chunk($tabsResult, 100);
                    foreach ($stepsArrays as $key => $stepArr) {
                        foreach ($stepArr as $item) {
                            $count++;
                            $item->PartitionKey = $value->teamName . ($value->channelName ? "_" . $value->channelName : "") . " - tabs";
                            $item->RowKey = "$count";
                            $item->team = $value->teamId;
                            $item->teamTitle = $value->teamName;
                            $item->channel = $value->channelId;
                            $item->channel = $value->channelId;
                            $item->channelTitle = $value->channelName;
                            if ($value->channelId == "-1") {
                                $tempArr = explode("/", $item->_links->channel->href);
                                $item->channel = end($tempArr);
                                $item->channelTitle = $this->getChannelName($sessionId, $value->teamId, $item->channel);
                            }
                            $item->type = $value->type;
                            $item->_links = null;
                            $item->attachments = null;
                            $item->_actions = null;
                        }
                        $tableRows = $stepArr;
                        if ($tableRows)
                            $this->managerAzure->tableInsertDataBatch($this->azureData['accountName'], $this->azureData['accountKey'], $eJobData->backup_job_kind . $ediscoveryJob->id, $tableRows);
                    }
                    //-----------------------------//
                    //----- Insert Data to Azure Table
                    $stepsArrays = array_chunk($result, 100);
                    foreach ($stepsArrays as $key => $stepArr) {
                        foreach ($stepArr as $item) {
                            $count++;
                            $item->PartitionKey = $value->teamName . ($value->channelName ? "_" . $value->channelName : "");
                            $item->RowKey = "$count";
                            $item->team = $value->teamId;
                            $item->teamTitle = $value->teamName;
                            $item->channel = $value->channelId;
                            $item->channelTitle = $value->channelName;
                            if ($value->channelId == "-1") {
                                $tempArr = explode("/", $item->_links->channel->href);
                                $item->channel = end($tempArr);
                                $item->channelTitle = $this->getChannelName($sessionId, $value->teamId, $item->channel);
                            }
                            $item->type = $value->type;
                            $item->_links = null;
                            $item->attachments = null;
                            $item->_actions = null;
                        }
                        $tableRows = $stepArr;
                        if ($tableRows)
                            $this->managerAzure->tableInsertDataBatch($this->azureData['accountName'], $this->azureData['accountKey'], $eJobData->backup_job_kind . $ediscoveryJob->id, $tableRows);
                    }
                    //-----------------------------------//
                    //----- Get Next Result
                    $offset = 0;
                    while (count($result) >= $limit) {
                        $offset += count($result);
                        if ($value->channelId == "-1") {
                            if (count($this->searchQueryArr))
                                $result = $this->managerVeeam->searchTeamItems($sessionId, $value->teamId, $offset, $limit, implode(' ', $this->searchQueryArr), $value->type)['data']->results;
                        } else {
                            if (count($this->searchQueryArr))
                                $result = $this->managerVeeam->searchTeamChannelItems($sessionId, $value->teamId, $value->channelId, $offset, $limit, implode(' ', $this->searchQueryArr), $value->type)['data']->results;
                            else
                                $result = $this->managerVeeam->getChannelPosts($sessionId, $value->teamId, $value->channelId, $offset, $limit)['data']->results;
                        }
                        $total += count($result);
                        //----- Insert Data to Azure Table
                        $stepsArrays = array_chunk($result, 100);
                        foreach ($stepsArrays as $key => $stepArr) {
                            foreach ($stepArr as $item) {
                                $count++;
                                $item->PartitionKey = $value->teamName . ($value->channelName ? "_" . $value->channelName : "");
                                $item->RowKey = "$count";
                                $item->team = $value->teamId;
                                $item->teamTitle = $value->teamName;
                                $$item->channel = $value->channelId;
                                $item->channelTitle = $value->channelName;
                                if ($value->channelId == "-1") {
                                    $tempArr = explode("/", $item->_links->channel->href);
                                    $item->channel = end($tempArr);
                                    $item->channelTitle = $this->getChannelName($sessionId, $value->teamId, $item->channel);
                                }
                                $item->type = $value->type;
                                $item->_links = null;
                                $item->attachments = null;
                                $item->_actions = null;
                            }
                            //-----------------------------//
                            $tableRows = $stepArr;
                            if ($tableRows)
                                $this->managerAzure->tableInsertDataBatch($this->azureData['accountName'], $this->azureData['accountKey'], $eJobData->backup_job_kind . $ediscoveryJob->id, $tableRows);
                            //-----------------------------------//
                        }
                    }
                    //-----------------------------------//
                    //----- Get Next Files Result
                    $offset = 0;
                    while (count($filesResult) >= $limit) {
                        $offset += count($filesResult);
                        if (count($this->searchQueryArr))
                            $filesResult = $this->managerVeeam->searchTeamChannelItems($sessionId, $value->teamId, $value->channelId, $offset, $limit, implode(' ', $this->searchQueryArr), $value->type)['data']->results;
                        else
                            $filesResult = $this->managerVeeam->getChannelFiles($sessionId, $value->teamId, $value->channelId, "", $offset, $limit)['data']->results;
                        $total += count($filesResult);
                        //----- Insert Data to Azure Table
                        $stepsArrays = array_chunk($filesResult, 100);
                        foreach ($stepsArrays as $key => $stepArr) {
                            foreach ($stepArr as $item) {
                                $count++;
                                $item->PartitionKey = $value->teamName . ($value->channelName ? "_" . $value->channelName : "");
                                $item->RowKey = "$count";
                                $item->team = $value->teamId;
                                $item->teamTitle = $value->teamName;
                                $item->channel = $value->channelId;
                                $item->channelTitle = $value->channelName;
                                if ($value->channelId == "-1") {
                                    $tempArr = explode("/", $item->_links->channel->href);
                                    $item->channel = end($tempArr);
                                    $item->channelTitle = $this->getChannelName($sessionId, $value->teamId, $item->channel);
                                }
                                $item->type = $value->type;
                                $item->_links = null;
                                $item->attachments = null;
                                $item->_actions = null;
                            }
                            //-----------------------------//
                            $tableRows = $stepArr;
                            if ($tableRows)
                                $this->managerAzure->tableInsertDataBatch($this->azureData['accountName'], $this->azureData['accountKey'], $eJobData->backup_job_kind . $ediscoveryJob->id, $tableRows);
                            //-----------------------------------//
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
            Log::log('error', 'Exception While Searching Team Items ' .  $e);
        }
    }
    //-------------------------------------------------------------------------//
    private function createRestoreSession($organizationId, $time, $showDeleted, $showVersions)
    {
        try {
            return $this->managerVeeam->createRestoreSession($organizationId, $time, "vet", $showDeleted, $showVersions)['data']->id;
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
    private function getChannelName($sessionId, $teamId, $channelId)
    {
        if (optional(optional($this->teamChannels)[$teamId])[$channelId]) {
            return $this->teamChannels[$teamId][$channelId];
        } else {
            $this->teamChannels[$teamId] = $this->managerVeeam->getTeamChannels($sessionId, $teamId)['data']->results;
            $this->teamChannels[$teamId] = array_column($this->teamChannels[$teamId], 'displayName', 'id');
            return $this->teamChannels[$teamId][$channelId];
        }
    }
    //-------------------------------------------------------------------------//
}
