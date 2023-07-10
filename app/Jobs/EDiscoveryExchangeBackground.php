<?php

namespace App\Jobs;

use App\Engine\Azure\ManagerAzure;
use App\Engine\Veeam\ManagerVeeam;
use App\Models\BacEDiscoveryJob;
use App\Models\BacOrganization;
use App\Models\ViewOrganizationEdiscoveryJob;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class EDiscoveryExchangeBackground implements ShouldQueue
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
    public $mailboxFolders = [];
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
            $sessionId = $this->createRestoreSession($organization->veeam_organization_guid, $ediscoveryJob->restore_point_time, $ediscoveryJob->is_restore_point_show_deleted, $ediscoveryJob->is_restore_point_show_version);
            $ediscoveryJob->restore_session_guid = $sessionId;
            $ediscoveryJob->save();
            //-----------
            $total = 0;
            foreach ($searchDataArr as $value) {
                if ($value->folderId == "-1") {
                    if (count($this->searchQueryArr)) {
                        $result = $this->managerVeeam->searchMailboxItems($sessionId, $value->mailboxId, 0, $limit, implode(' ', $this->searchQueryArr))['data']->results;
                    } else {
                        $result = $this->managerVeeam->getMailBoxItems($sessionId, $value->mailboxId, '', 0, $limit)['data']->results;
                    }
                } else {
                    if (count($this->searchQueryArr)) {
                        $result = $this->managerVeeam->searchMailboxFolderItems($sessionId, $value->mailboxId, $value->folderId, 0, $limit, implode(' ', $this->searchQueryArr))['data']->results;
                    } else {
                        $result = $this->managerVeeam->getMailBoxItems($sessionId, $value->mailboxId, $value->folderId, 0, $limit)['data']->results;
                    }

                }
                $total += count($result);
                $count = 0;
                if (count($result) > 0) {
                    //-- Set Step
                    $stepsArrays = array_chunk($result, 100);
                    foreach ($stepsArrays as $key => $stepArr) {
                        //----- Insert Data to Azure Table
                        foreach ($stepArr as $item) {
                            $count++;
                            $item->PartitionKey = $value->mailboxName . ($value->folderName ? "_" . str_replace("/", "_", $value->folderName) : "");
                            $item->RowKey = "$count";
                            $parentTemp = explode("/", $item->_links->parent->href);
                            $item->folder = end($parentTemp);
                            $mailboxTemp = explode("/", $item->_links->mailbox->href);
                            $item->mailbox = end($mailboxTemp);
                            $item->mailboxTitle = $value->mailboxName;
                            $item->folderTitle = $value->folderName;
                            if (!$value->folderName) {
                                $item->folderTitle = $this->getFolderName($sessionId, end($mailboxTemp), end($parentTemp));
                            }

                            $item->_links = null;
                            $item->_actions = null;
                            $item->attachments = null;
                        }
                        //-----------------------------//
                        $tableRows = $stepArr;
                        $this->managerAzure->tableInsertDataBatch($this->azureData['accountName'], $this->azureData['accountKey'], $eJobData->backup_job_kind . $ediscoveryJob->id, $tableRows);
                        //-----------------------------------//
                    }
                    //-----------------------------------//
                    //----- Get Next Result
                    $offset = 0;
                    while (count($result) >= $limit) {
                        $offset += count($result);
                        if ($value->folderId == "-1") {
                            if (count($this->searchQueryArr)) {
                                $result = $this->managerVeeam->searchMailboxItems($sessionId, $value->mailboxId, $offset, $limit, implode(' ', $this->searchQueryArr))['data']->results;
                            } else {
                                $result = $this->managerVeeam->getMailBoxItems($sessionId, $value->mailboxId, '', $offset, $limit)['data']->results;
                            }
                        } else {
                            if (count($this->searchQueryArr)) {
                                $result = $this->managerVeeam->searchMailboxFolderItems($sessionId, $value->mailboxId, $value->folderId, $offset, $limit, implode(' ', $this->searchQueryArr))['data']->results;
                            } else {
                                $result = $this->managerVeeam->getMailBoxItems($sessionId, $value->mailboxId, $value->folderId, $offset, $limit)['data']->results;
                            }

                        }
                        $total += count($result);
                        //---------------------------------//
                        //-- Set Step
                        $stepsArrays = array_chunk($result, 100);
                        foreach ($stepsArrays as $key => $stepArr) {
                            //----- Insert Data to Azure Table
                            foreach ($stepArr as $item) {
                                $count++;
                                $item->PartitionKey = $value->mailboxName . ($value->folderName ? "_" . str_replace("/", "_", $value->folderName) : "");
                                $item->RowKey = "$count";
                                $parentTemp = explode("/", $item->_links->parent->href);
                                $item->folder = end($parentTemp);
                                $mailboxTemp = explode("/", $item->_links->mailbox->href);
                                $item->mailbox = end($mailboxTemp);
                                $item->mailboxTitle = $value->mailboxName;
                                $item->folderTitle = $value->folderName;
                                if (!$value->folderName) {
                                    $item->folderTitle = $this->getFolderName($sessionId, end($mailboxTemp), end($parentTemp));
                                }

                                $item->_links = null;
                                $item->_actions = null;
                                $item->attachments = null;
                            }
                            //-----------------------------//
                            $tableRows = $stepArr;
                            $this->managerAzure->tableInsertDataBatch($this->azureData['accountName'], $this->azureData['accountKey'], $eJobData->backup_job_kind . $ediscoveryJob->id, $tableRows);
                            //-----------------------------------//
                        }
                    }
                    //-----------------------------------//
                }
                $value->count = $count;
            }
            //-------------------------------------//
            $ediscoveryJob->status = 'Success';
            $ediscoveryJob->completion_time = Carbon::now();
            $ediscoveryJob->expiration_time = Carbon::now()->add(config('parameters.EXPORTED_FILES_EXPIRATION_DAYS'), 'days');
            $after = Carbon::now();
            $ediscoveryJob->duration = $after->diffAsCarbonInterval($before);
            $ediscoveryJob->restore_session_guid = $sessionId;
            $ediscoveryJob->total_items = $total;
            $ediscoveryJob->search_data = json_encode($searchDataArr);
            $ediscoveryJob->save();
            $this->stopRestoreSession($sessionId);
            //-------------------------------------//
        } catch (Exception $e) {
            Log::log('error', 'Exception While Searching Exchange Items ' . $e->getMessage());
            $ediscoveryJob->status = 'Failed';
            $ediscoveryJob->completion_time = Carbon::now();
            $ediscoveryJob->restore_session_guid = $sessionId;
            $ediscoveryJob->save();
        }
    }
    //-------------------------------------------------------------------------//
    private function createRestoreSession($organizationId, $time, $showDeleted, $showVersions)
    {
        try {
            return $this->managerVeeam->createRestoreSession($organizationId, $time, "vex", $showDeleted, $showVersions)['data']->id;
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
            if ($session->state != "Stopped") {
                return $this->managerVeeam->stopRestoreSession($sessionId);
            }

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
            if ($item['name'] == $condition && $item['type'] == $fieldArr['type']) {
                return $item;
            }

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
    private function getFolderName($sessionId, $mailboxId, $folderId)
    {
        if (optional(optional($this->mailboxFolders)[$mailboxId])[$folderId]) {
            return $this->mailboxFolders[$mailboxId][$folderId];
        } else {
            $this->mailboxFolders[$mailboxId] = $this->managerVeeam->getMailBoxFolders($sessionId, $mailboxId)['data']->results;
            $this->mailboxFolders[$mailboxId] = array_column($this->mailboxFolders[$mailboxId], 'name', 'id');
            return $this->mailboxFolders[$mailboxId][$folderId];
        }
    }
    //-------------------------------------------------------------------------//
    public function failed(Throwable $e)
    {
        Log::log('error', 'Exception Job Failed ' . $e->getMessage());
        $ediscoveryJob = BacEDiscoveryJob::where('id', $this->ediscoveryJobId)->first();
        //--------------------------------------//
        if (optional($ediscoveryJob)->restore_session_guid) {
            $this->stopRestoreSession($ediscoveryJob->restore_session_guid);
        }
        //--------------------------------------//
        if ($ediscoveryJob) {
            $ediscoveryJob->status = 'Failed';
            $ediscoveryJob->completion_time = Carbon::now();
            $ediscoveryJob->save();
        }
    }
    //-------------------------------------------------------------------------//
}
