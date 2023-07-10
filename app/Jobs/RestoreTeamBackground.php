<?php

namespace App\Jobs;

use App\Engine\Veeam\ManagerVeeam;
use App\Models\RestoreHistoryDetails;
use App\Models\RestoreHistory;
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
use App\Models\BacRestoreHistory;
use App\Models\BacRestoreHistoryDetail;

class RestoreTeamBackground implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 60 * 60 * 12;
    public $userId;
    public $historyId;
    public $sessionData;
    public $teamData;
    public $functionName;
    public $_managerVeeam;
    public $session;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $historyId, $functionName, $sessionData, $teamData)
    {
        $this->userId        = $userId;
        $this->historyId     = $historyId;
        $this->sessionData   = $sessionData;
        $this->teamData = $teamData;
        $this->functionName  = $functionName;
        $this->session = session()->all();
    }

    public function middleware()
    {
        return [new WithoutOverlapping($this->historyId)];
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
        $this->_managerVeeam = new ManagerVeeam();
        call_user_func([$this, $this->functionName]);
    }
    //-------------------------------------------------------------------------//
    public function restoreTeam()
    {
        $history = BacRestoreHistory::where('id', $this->historyId)->first();
        if ($this->checkProcessCanceled($this->historyId)) {
            return;
        }
        try {
            //--------------------------------------------//
            $sessionId = $history->restore_session_guid;
            if (!$sessionId) {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid"
                ]);
            }
            $sessionInfo = $this->_managerVeeam->getRestoreSession($sessionId)['data'];
            if ($sessionInfo->state != "Working") {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid"
                ]);
            }
            //--------------------------------------------//
            $detail = BacRestoreHistoryDetail::where('restore_history_id', $history->id)->first();
            $before = Carbon::now();
            $data   = $this->_managerVeeam->restoreTeam($sessionId, $detail->item_id, $this->teamData['userCode'], (array) json_decode($history->options), $detail->id);
            if ($this->checkProcessCanceled($this->historyId)) {
                return;
            }
            //--------------------------------------------//
            $result           = $data['data'];
            $detail->status   = ($data["status"] == 200 ? 'Success' : $data["status"]);

            // Exceptions&Warnings not caught by Veeam api
            $dataDetails = optional($data['data']);
            if (isset($dataDetails->exceptions)) {
                $detail->status = 'Failed';
                $detail->error_response = implode(",", $dataDetails->exceptions);
            } else if (isset($dataDetails->warnings)) {
                $detail->status = 'Warning';
                $detail->error_response = implode(",", $dataDetails->warnings);
            } else if (isset($dataDetails->restoreIssues) && count($dataDetails->restoreIssues)) {
                if (isset($dataDetails->restoreIssues[0]->warnings) && count($dataDetails->restoreIssues[0]->warnings)) {
                    $detail->status = 'Warning';
                    $detail->error_response = implode(",", $dataDetails->restoreIssues[0]->warnings);
                }
            }
            $detail->returned_response   = json_encode($result);
            $after            = Carbon::now();
            $detail->duration = $after->diffAsCarbonInterval($before);
            $detail->save();
            //--------------------------------------------//
            $history->status             = $detail->status;
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
        } catch (Exception $e) {
            $history->status             = 'Failed';
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            Log::log('error', 'Exception While Restoring Teams ' . $e->getMessage());
            throw new \Exception("Error While Restoring Teams");
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreChannels()
    {
        $history = BacRestoreHistory::where('id', $this->historyId)->first();
        try {
            //--------------------------------------------//
            $sessionId = $history->restore_session_guid;
            if (!$sessionId) {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid"
                ]);
            }
            $sessionInfo = $this->_managerVeeam->getRestoreSession($sessionId)['data'];
            if ($sessionInfo->state != "Working") {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid"
                ]);
            }
            //--------------------------------------------//
            $jobStatus  = 'Success';
            $optionsArr = (array) json_decode($history->options);
            //--------------------------------------------//
            $details = BacRestoreHistoryDetail::where('restore_history_id', $history->id)->get();
            //--------------------------------------------//
            foreach ($details as $item) {
                if ($this->checkProcessCanceled($this->historyId)) {
                    return;
                }
                $before = Carbon::now();
                try {
                    $data           = $this->_managerVeeam->restoreChannels($sessionId, $item->item_parent_id, $item->item_id, $this->teamData['userCode'], $optionsArr, $item->id);
                    $result         = $data['data'];
                    $item->status   = ($data['status'] == '200' ? 'Success' : $data['status']);
                    // Exceptions&Warnings not caught by Veeam api
                    $itemDetails = optional($data['data']);
                    if (isset($itemDetails->exceptions)) {
                        $jobStatus = 'Failed';
                        $item->status = 'Failed';
                        $item->error_response = implode(",", $itemDetails->exceptions);
                    } else if (isset($itemDetails->warnings)) {
                        $jobStatus = 'Warning';
                        $item->status = 'Warning';
                        $item->error_response = implode(",", $itemDetails->warnings);
                    } else if (isset($itemDetails->restoreIssues) && count($itemDetails->restoreIssues)) {
                        if (isset($itemDetails->restoreIssues[0]->error)) {
                            $jobStatus = 'Failed';
                            $item->status = 'Failed';
                            $item->error_response = $itemDetails->restoreIssues[0]->error;
                        } else if (isset($itemDetails->restoreIssues[0]->warnings) && count($itemDetails->restoreIssues[0]->warnings)) {
                            $jobStatus = 'Warning';
                            $item->status = 'Warning';
                            $item->error_response = implode(",", $itemDetails->restoreIssues[0]->warnings);
                        }
                    }
                    $item->returned_response   = json_encode($result);
                    $after          = Carbon::now();
                    $item->duration = $after->diffAsCarbonInterval($before);
                    $item->save();
                } catch (Exception $e) {
                    $jobStatus = "Failed";
                    Log::log('error', 'Exception While Restoring Channels ' . $e->getMessage());
                }
            }
            //--------------------------------------------//
            $history->status             = $jobStatus;
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            //--------------------------------------------//
        } catch (Exception $e) {
            $history->status             = 'Failed';
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            Log::log('error', 'Exception While Restorying Channels ' . $e->getMessage());
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreChannelsPosts()
    {
        $history = BacRestoreHistory::where('id', $this->historyId)->first();
        try {
            //--------------------------------------------//
            $sessionId = $history->restore_session_guid;
            if (!$sessionId) {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid"
                ]);
            }
            $sessionInfo = $this->_managerVeeam->getRestoreSession($sessionId)['data'];
            if ($sessionInfo->state != "Working") {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid"
                ]);
            }
            //--------------------------------------------//
            $jobStatus  = 'Success';
            $optionsArr = (array) json_decode($history->options);
            //--------------------------------------------//
            $details = BacRestoreHistoryDetail::where('restore_history_id', $history->id)->get();
            //--------------------------------------------//
            foreach ($details as $item) {
                if ($this->checkProcessCanceled($this->historyId)) {
                    return;
                }
                $before = Carbon::now();
                try {
                    $data           = $this->_managerVeeam->restoreChannelsPosts($sessionId, $item->item_parent_id, $item->item_id, $this->teamData['userCode'], $optionsArr, $item->id);
                    $result         = $data['data'];
                    $item->status   = ($data['status'] == '200' ? 'Success' : $data['status']);
                    // Exceptions&Warnings not caught by Veeam api
                    $itemDetails = optional($data['data']);
                    if (isset($itemDetails->exceptions)) {
                        $jobStatus = 'Failed';
                        $item->status = 'Failed';
                        $item->error_response = implode(",", $itemDetails->exceptions);
                    } else if (isset($itemDetails->warnings)) {
                        $jobStatus = 'Warning';
                        $item->status = 'Warning !!';
                        $item->error_response = implode(",", $itemDetails->warnings);
                    } else if (isset($itemDetails->restoreIssues) && count($itemDetails->restoreIssues)) {
                        if (isset($itemDetails->restoreIssues[0]->error)) {
                            $jobStatus = 'Failed';
                            $item->status = 'Failed';
                            $item->error_response = $itemDetails->restoreIssues[0]->error;
                        } else if (isset($itemDetails->restoreIssues[0]->warnings) && count($itemDetails->restoreIssues[0]->warnings)) {
                            $jobStatus = 'Warning';
                            $item->status = 'Warning';
                            $item->error_response = implode(",", $itemDetails->restoreIssues[0]->warnings);
                        }
                    }
                    $item->returned_response   = json_encode($result);
                    $after          = Carbon::now();
                    $item->duration = $after->diffAsCarbonInterval($before);
                    $item->save();
                } catch (Exception $e) {
                    $jobStatus = "Failed";
                    Log::log('error', 'Exception While Restoring Channels Posts ' . $e->getMessage());
                }
            }
            //--------------------------------------------//
            $history->status             = $jobStatus;
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            //--------------------------------------------//
        } catch (Exception $e) {
            $history->status             = 'Failed';
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            Log::log('error', 'Exception While Restorying Channels Posts ' . $e->getMessage());
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreChannelsFiles()
    {
        $history = BacRestoreHistory::where('id', $this->historyId)->first();
        try {
            //--------------------------------------------//
            $sessionId = $history->restore_session_guid;
            if (!$sessionId) {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid"
                ]);
            }
            $sessionInfo = $this->_managerVeeam->getRestoreSession($sessionId)['data'];
            if ($sessionInfo->state != "Working") {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid"
                ]);
            }
            //--------------------------------------------//
            $jobStatus  = 'Success';
            $optionsArr = (array) json_decode($history->options);
            //--------------------------------------------//
            $details = BacRestoreHistoryDetail::where('restore_history_id', $history->id)->get();
            //--------------------------------------------//
            foreach ($details as $item) {
                if ($this->checkProcessCanceled($this->historyId)) {
                    return;
                }
                $before = Carbon::now();
                try {
                    $data           = $this->_managerVeeam->restoreChannelsFiles($sessionId, $item->item_parent_id, $item->item_id, $this->teamData['userCode'], $optionsArr, $item->id);
                    $result         = $data['data'];
                    $item->status   = ($data['status'] == '200' ? 'Success' : $data['status']);
                    // Exceptions&Warnings not caught by Veeam api
                    $itemDetails = optional($data['data']);
                    if (isset($itemDetails->exceptions)) {
                        $jobStatus = 'Failed';
                        $item->status = 'Failed';
                        $item->error_response = implode(",", $itemDetails->exceptions);
                    } else if (isset($itemDetails->warnings)) {
                        $jobStatus = 'Warning';
                        $item->status = 'Warning';
                        $item->error_response = implode(",", $itemDetails->warnings);
                    } else if (isset($itemDetails->restoreIssues) && count($itemDetails->restoreIssues)) {
                        if (isset($itemDetails->restoreIssues[0]->error)) {
                            $jobStatus = 'Failed';
                            $item->status = 'Failed';
                            $item->error_response = $itemDetails->restoreIssues[0]->error;
                        } else if (isset($itemDetails->restoreIssues[0]->warnings) && count($itemDetails->restoreIssues[0]->warnings)) {
                            $jobStatus = 'Warning';
                            $item->status = 'Warning';
                            $item->error_response = implode(",", $itemDetails->restoreIssues[0]->warnings);
                        }
                    }
                    $item->returned_response   = json_encode($result);
                    $after          = Carbon::now();
                    $item->duration = $after->diffAsCarbonInterval($before);
                    $item->save();
                } catch (Exception $e) {
                    $jobStatus = "Failed";
                    Log::log('error', 'Exception While Restoring Channels Files ' . $e->getMessage());
                }
            }
            //--------------------------------------------//
            $history->status             = $jobStatus;
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            //--------------------------------------------//
        } catch (Exception $e) {
            $history->status             = 'Failed';
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            Log::log('error', 'Exception While Restorying Channels Files ' . $e->getMessage());
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreChannelsTabs()
    {
        $history = BacRestoreHistory::where('id', $this->historyId)->first();
        try {
            //--------------------------------------------//
            $sessionId = $history->restore_session_guid;
            if (!$sessionId) {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid"
                ]);
            }
            $sessionInfo = $this->_managerVeeam->getRestoreSession($sessionId)['data'];
            if ($sessionInfo->state != "Working") {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid"
                ]);
            }
            //--------------------------------------------//
            $jobStatus  = 'Success';
            $optionsArr = (array) json_decode($history->options);
            //--------------------------------------------//
            $details = BacRestoreHistoryDetail::where('restore_history_id', $history->id)->get();
            //--------------------------------------------//
            foreach ($details as $item) {
                if ($this->checkProcessCanceled($this->historyId)) {
                    return;
                }
                $before = Carbon::now();
                try {
                    $data           = $this->_managerVeeam->restoreChannelsTabs($sessionId, $item->item_parent_id, $item->item_id, $this->teamData['userCode'], $optionsArr, $item->id);
                    $result         = $data['data'];
                    $item->status   = ($data['status'] == '200' ? 'Success' : $data['status']);
                    // Exceptions&Warnings not caught by Veeam api
                    $itemDetails = optional($data['data']);
                    if (isset($itemDetails->exceptions)) {
                        $jobStatus = 'Failed';
                        $item->status = 'Failed';
                        $item->error_response = implode(",", $itemDetails->exceptions);
                    } else if (isset($itemDetails->warnings)) {
                        $jobStatus = 'Warning';
                        $item->status = 'Warning';
                        $item->error_response = implode(",", $itemDetails->warnings);
                    } else if (isset($itemDetails->restoreIssues) && count($itemDetails->restoreIssues)) {
                        if (isset($itemDetails->restoreIssues[0]->error)) {
                            $jobStatus = 'Failed';
                            $item->status = 'Failed';
                            $item->error_response = $itemDetails->restoreIssues[0]->error;
                        } else if (isset($itemDetails->restoreIssues[0]->warnings) && count($itemDetails->restoreIssues[0]->warnings)) {
                            $jobStatus = 'Warning';
                            $item->status = 'Warning';
                            $item->error_response = implode(",", $itemDetails->restoreIssues[0]->warnings);
                        }
                    }
                    $item->returned_response   = json_encode($result);
                    $after          = Carbon::now();
                    $item->duration = $after->diffAsCarbonInterval($before);
                    $item->save();
                } catch (Exception $e) {
                    $jobStatus = "Failed";
                    Log::log('error', 'Exception While Restoring Channels Tabs ' . $e->getMessage());
                }
            }
            //--------------------------------------------//
            $history->status             = $jobStatus;
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            //--------------------------------------------//
        } catch (Exception $e) {
            $history->status             = 'Failed';
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            Log::log('error', 'Exception While Restorying Channels Tabs ' . $e->getMessage());
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreFiles()
    {
        $history = BacRestoreHistory::where('id', $this->historyId)->first();
        try {
            //--------------------------------------------//
            $sessionId = $history->restore_session_guid;
            if (!$sessionId) {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid"
                ]);
            }
            $sessionInfo = $this->_managerVeeam->getRestoreSession($sessionId)['data'];
            if ($sessionInfo->state != "Working") {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid"
                ]);
            }
            //--------------------------------------------//
            $jobStatus  = 'Success';
            $optionsArr = (array) json_decode($history->options);
            //--------------------------------------------//
            $detailsArr = BacRestoreHistoryDetail::where("restore_history_id", $this->historyId)->get();
            foreach ($detailsArr as $details) {
                //--------------------------------------------//
                if ($this->checkProcessCanceled($this->historyId)) {
                    return;
                }
                $before = Carbon::now();
                try {
                    $data           = $this->_managerVeeam->restoreTeamsFiles($sessionId, $details->item_parent_id, $details->item_id, $this->teamData['userCode'], $optionsArr, $details->id);
                    $result         = $data['data'];
                    $details->status   = ($data['status'] == '200' ? 'Success' : $data['status']);
                    // Exceptions&Warnings not caught by Veeam api
                    $itemDetails = optional($data['data']);
                    if (isset($itemDetails->exceptions)) {
                        $jobStatus = 'Failed';
                        $details->status = 'Failed';
                        $details->error_response = implode(",", $itemDetails->exceptions);
                    } else if (isset($itemDetails->warnings)) {
                        $jobStatus = 'Warning';
                        $details->status = 'Warning';
                        $details->error_response = implode(",", $itemDetails->warnings);
                    } else if (isset($itemDetails->restoreIssues) && count($itemDetails->restoreIssues)) {
                        if (isset($itemDetails->restoreIssues[0]->error)) {
                            $jobStatus = 'Failed';
                            $details->status = 'Failed';
                            $details->error_response = $itemDetails->restoreIssues[0]->error;
                        } else if (isset($itemDetails->restoreIssues[0]->warnings) && count($itemDetails->restoreIssues[0]->warnings)) {
                            $jobStatus = 'Warning';
                            $details->status = 'Warning';
                            $details->error_response = implode(",", $itemDetails->restoreIssues[0]->warnings);
                        }
                    }
                    $details->returned_response   = json_encode($result);
                    $after          = Carbon::now();
                    $details->duration = $after->diffAsCarbonInterval($before);
                    $details->save();
                } catch (Exception $e) {
                    $jobStatus = "Failed";
                    Log::log('error', 'Exception While Restoring Teams Files ' . $e->getMessage());
                }
            }
            //--------------------------------------------//
            $history->status             = $jobStatus;
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            //--------------------------------------------//
        } catch (Exception $e) {
            $history->status             = 'Failed';
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            Log::log('error', 'Exception While Restorying Teams Files ' . $e->getMessage());
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreTabs()
    {
        $history = BacRestoreHistory::where('id', $this->historyId)->first();
        try {
            //--------------------------------------------//
            $sessionId = $history->restore_session_guid;
            if (!$sessionId) {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid"
                ]);
            }
            $sessionInfo = $this->_managerVeeam->getRestoreSession($sessionId)['data'];
            if ($sessionInfo->state != "Working") {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid"
                ]);
            }
            //--------------------------------------------//
            $jobStatus  = 'Success';
            $optionsArr = (array) json_decode($history->options);
            //--------------------------------------------//
            $details = BacRestoreHistoryDetail::where('restore_history_id', $history->id)->first();
            $tabsArr = json_decode($details->item_id);
            //--------------------------------------------//
            if ($this->checkProcessCanceled($this->historyId)) {
                return;
            }
            $before = Carbon::now();
            try {
                $data           = $this->_managerVeeam->restoreTeamsTabs($sessionId, $details->item_parent_id, $tabsArr[0]->channelId, $details->item_id, $this->teamData['userCode'], $optionsArr, $details->id);
                $result         = $data['data'];
                $details->status   = ($data['status'] == '200' ? 'Success' : $data['status']);
                // Exceptions&Warnings not caught by Veeam api
                $itemDetails = optional($data['data']);
                if (isset($itemDetails->exceptions)) {
                    $jobStatus = 'Failed';
                    $details->status = 'Failed';
                    $details->error_response = implode(",", $itemDetails->exceptions);
                } else if (isset($itemDetails->warnings)) {
                    $jobStatus = 'Warning';
                    $details->status = 'Warning';
                    $details->error_response = implode(",", $itemDetails->warnings);
                } else if (isset($itemDetails->restoreIssues) && count($itemDetails->restoreIssues)) {
                    if (isset($itemDetails->restoreIssues[0]->error)) {
                        $jobStatus = 'Failed';
                        $details->status = 'Failed';
                        $details->error_response = $itemDetails->restoreIssues[0]->error;
                    } else if (isset($itemDetails->restoreIssues[0]->warnings) && count($itemDetails->restoreIssues[0]->warnings)) {
                        $jobStatus = 'Warning';
                        $details->status = 'Warning';
                        $details->error_response = implode(",", $itemDetails->restoreIssues[0]->warnings);
                    }
                    $details->returned_response   = json_encode($result);
                    $after          = Carbon::now();
                    $details->duration = $after->diffAsCarbonInterval($before);
                    $details->save();
                }
            } catch (Exception $e) {
                $jobStatus = "Failed";
                Log::log('error', 'Exception While Restoring Teams Tabs ' . $e->getMessage());
            }
            //--------------------------------------------//
            $history->status             = $jobStatus;
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            //--------------------------------------------//
        } catch (Exception $e) {
            $history->status             = 'Failed';
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            Log::log('error', 'Exception While Restorying Teams Tabs ' . $e->getMessage());
        }
    }
    //-------------------------------------------------------------------------//
    private function stopRestoreSession($sessionId)
    {
        try {
            if (!$this->_managerVeeam) {
                $this->_managerVeeam = new ManagerVeeam();
            }
            $session = $this->_managerVeeam->getRestoreSession($sessionId)['data'];
            if ($session->state != "Stopped")
                return $this->_managerVeeam->stopRestoreSession($sessionId);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Stopping Restore Sessions  ' . $ex->getMessage());
        }
    }
    //-------------------------------------------------------------------------//
    private function checkProcessCanceled($historyId)
    {
        return BacRestoreHistory::where('id', $historyId)->where('status', 'canceled')->count();
    }
    //-------------------------------------------------------------------------//
    public function failed(Throwable $e)
    {
        Log::log('error', 'Exception Job Failed ' . $e->getMessage());
        $history = BacRestoreHistory::where('id', $this->historyId)->first();
        //--------------------------------------//
        if ($history->restore_session_guid) {
            $this->stopRestoreSession($history->restore_session_guid);
        }
        //--------------------------------------//
        $history->status          = 'Failed';
        $history->completion_time = Carbon::now();
        $history->save();
        BacRestoreHistoryDetail::where('restore_history_id', $this->historyId)
            ->where('status', 'In Progress')
            ->update([
                'status' => 'Failed',
            ]);
    }
    //-------------------------------------------------------------------------//
}
