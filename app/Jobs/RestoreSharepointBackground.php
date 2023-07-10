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

class RestoreSharepointBackground implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 60 * 60 * 12;
    public $userId;
    public $historyId;
    public $sessionData;
    public $sharepointData;
    public $functionName;
    public $_managerVeeam;
    public $session;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $historyId, $functionName, $sessionData, $sharepointData)
    {
        $this->userId        = $userId;
        $this->historyId     = $historyId;
        $this->sessionData   = $sessionData;
        $this->sharepointData = $sharepointData;
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
    public function restoreSite()
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
                    $data = $this->_managerVeeam->restoreSite($sessionId, $item->item_id, $this->sharepointData['userCode'], $optionsArr, $item->id);
                    $result         = $data['data'];
                    $item->status   = ($data['status'] == '200' ? 'Success' : $data['status']);
                    // Exceptions not caught by Veeam api
                    $itemDetails = optional($data['data']);
                    if (isset($itemDetails->exceptions)) {
                        $item->status = "Failed";
                        $jobStatus = "Failed";
                        $item->error_response = implode(",", $itemDetails->exceptions);
                    } else if (isset($itemDetails->warnings) && count($itemDetails->warnings)) {
                        $item->status = 'Warning';
                        $jobStatus = 'Warning';
                        $item->error_response = implode(",", $itemDetails->warnings);
                    } else if (isset($itemDetails->restoreIssues) && count($itemDetails->restoreIssues)) {
                        if (isset($itemDetails->restoreIssues[0]->error) && count($itemDetails->restoreIssues[0]->error)) {
                            $item->status = 'Failed';
                            $item->error_response = implode(",", $itemDetails->restoreIssues[0]->error);
                        }
                    }
                    $item->returned_response   = json_encode($result);
                    $after          = Carbon::now();
                    $item->duration = $after->diffAsCarbonInterval($before);
                    $item->save();
                } catch (Exception $e) {
                    $jobStatus = "Failed";
                    Log::log('error', 'Exception While Restoring Site ' . $e->getMessage());
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
            Log::log('error', 'Exception While Restoring Site ' . $e->getMessage());
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreSiteLibraries()
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
                    $optionsArr['toList'] = ($optionsArr['list'] ? $optionsArr['list'] : $item->item_name);
                    $data = $this->_managerVeeam->restoreSiteLibraries($sessionId, $item->item_parent_id, $item->item_id, $this->sharepointData['userCode'], $optionsArr, $item->id);
                    $result         = $data['data'];
                    $item->status   = ($data['status'] == '200' ? 'Success' : $data['status']);
                    // Exceptions not caught by Veeam api
                    $itemDetails = optional($data['data']);
                    if (isset($itemDetails->exceptions)) {
                        $item->status = "Failed";
                        $jobStatus = "Failed";
                        $item->error_response = implode(",", $itemDetails->exceptions);
                    } else if (isset($itemDetails->warnings) && count($itemDetails->warnings)) {
                        $item->status = 'Warning';
                        $jobStatus = 'Warning';
                        $item->error_response = implode(",", $itemDetails->warnings);
                    }
                    $item->returned_response   = json_encode($result);
                    $after          = Carbon::now();
                    $item->duration = $after->diffAsCarbonInterval($before);
                    $item->save();
                } catch (Exception $e) {
                    $jobStatus = "Failed";
                    Log::log('error', 'Exception While Restoring Site Libraries ' . $e->getMessage());
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
            Log::log('error', 'Exception While Restorying Site Libraries ' . $e->getMessage());
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreSiteLists()
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
                    $optionsArr['toList'] = ($optionsArr['list'] ? $optionsArr['list'] : $item->item_name);
                    $data           = $this->_managerVeeam->restoreSiteLists($sessionId, $item->item_parent_id, $item->item_id, $this->sharepointData['userCode'], $optionsArr, $history->id);
                    $result         = $data['data'];
                    $item->status   = ($data['status'] == '200' ? 'Success' : $data['status']);
                    // Exceptions not caught by Veeam api
                    $itemDetails = optional($data['data']);
                    if (isset($itemDetails->exceptions)) {
                        $item->status = "Failed";
                        $jobStatus = "Failed";
                        $item->error_response = implode(",", $itemDetails->exceptions);
                    } else if (isset($itemDetails->warnings) && count($itemDetails->warnings)) {
                        $item->status = 'Warning';
                        $jobStatus = 'Warning';
                        $item->error_response = implode(",", $itemDetails->warnings);
                    }
                    $item->returned_response   = json_encode($result);
                    $after          = Carbon::now();
                    $item->duration = $after->diffAsCarbonInterval($before);
                    $item->save();
                } catch (Exception $e) {
                    $jobStatus = "Failed";
                    Log::log('error', 'Exception While Restoring Site Lists ' . $e->getMessage());
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
            Log::log('error', 'Exception While Restoring Site Lists ' . $e->getMessage());
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreSiteDocument()
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
            $detailsArr = BacRestoreHistoryDetail::where("restore_history_id", $this->historyId)->get();
            $jobStatus = "Success";
            foreach ($detailsArr as $detail) {
                $before = Carbon::now();
                try {
                    $options = (array) json_decode($history->options);
                    $tempItem = json_decode($detail->item_id);
                    $toList = ($options['list'] ? $options['list'] : $tempItem[0]->contentTitle);
                    $data   = $this->_managerVeeam->restoreSiteDocuments($sessionId, $detail->item_parent_id, $detail->item_id, $this->sharepointData['userCode'], $options, $toList, $detail->id);
                } catch (Exception $e) {
                    $jobStatus = "Failed";
                    Log::log('error', 'Exception While Restoring Site Documents ' . $e->getMessage());
                }
                //--------------------------------------------//
                $result           = $data['data'];
                $detail->status   = ($data["status"] == 200 ? 'Success' : $data["status"]);
                // Exceptions not caught by Veeam api
                $itemDetails = optional($data['data']);
                if (isset($itemDetails->exceptions)) {
                    $detail->status = "Failed";
                    $jobStatus = "Failed";
                    $detail->error_response = implode(",", $itemDetails->exceptions);
                } else if (isset($itemDetails->warnings) && count($itemDetails->warnings)) {
                    $detail->status = 'Warning';
                    $jobStatus = 'Warning';
                    $detail->error_response = implode(",", $itemDetails->warnings);
                }
                $detail->returned_response   = json_encode($result);
                $after            = Carbon::now();
                $detail->duration = $after->diffAsCarbonInterval($before);
                $detail->save();
            }
            //--------------------------------------------//
            $history->status             = $jobStatus;
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
            Log::log('error', 'Exception While Restoring Site Documents ' . $e->getMessage());
            throw new \Exception("Error While Restoring Site Documents");
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreSiteItem()
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
            $jobStatus = "Success";
            $detailsArr = BacRestoreHistoryDetail::where("restore_history_id", $this->historyId)->get();
            foreach ($detailsArr as $detail) {
                $before = Carbon::now();
                $options = (array) json_decode($history->options);
                $tempItem = json_decode($detail->item_id);
                $toList = ($options['list'] ? $options['list'] : $tempItem[0]->contentTitle);
                try {
                    $data   = $this->_managerVeeam->restoreSiteItems($sessionId, $detail->item_parent_id, $detail->item_id, $this->sharepointData['userCode'], $options, $toList, $detail->id);
                } catch (Exception $e) {
                    $jobStatus = "Failed";
                    Log::log('error', 'Exception While Restoring Site Items ' . $e->getMessage());
                    continue;
                }
                //--------------------------------------------//
                $result           = $data['data'];
                $detail->status   = ($data["status"] == 200 ? 'Success' : $data["status"]);
                // Exceptions not caught by Veeam api
                $itemDetails = optional($data['data']);
                if (isset($itemDetails->exceptions)) {
                    $detail->status = "Failed";
                    $jobStatus = "Failed";
                    $detail->error_response = implode(",", $itemDetails->exceptions);
                } else if (isset($itemDetails->warnings) && count($itemDetails->warnings)) {
                    $detail->status = 'Warning';
                    $jobStatus = 'Warning';
                    $detail->error_response = implode(",", $itemDetails->warnings);
                }
                $detail->returned_response   = json_encode($result);
                $after            = Carbon::now();
                $detail->duration = $after->diffAsCarbonInterval($before);
                $detail->save();
            }
            //--------------------------------------------//
            $history->status             = $jobStatus;
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
            Log::log('error', 'Exception While Restoring Site Items ' . $e->getMessage());
            throw new \Exception("Error While Restoring Site Items");
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreSiteFolder()
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
            $jobStatus = "Success";
            $detailsArr = BacRestoreHistoryDetail::where("restore_history_id", $this->historyId)->get();
            foreach ($detailsArr as $detail) {
                $before = Carbon::now();
                if ($this->checkProcessCanceled($this->historyId)) {
                    return;
                }
                try {
                    $options = (array) json_decode($history->options);
                    $tempItem = json_decode($detail->item_id);
                    $toList = ($options['list'] ? $options['list'] : $tempItem[0]->contentTitle);
                    $data   = $this->_managerVeeam->restoreSiteFolders($sessionId, $detail->item_parent_id, $detail->item_id, $this->sharepointData['userCode'], $options, $toList, $detail->id);
                } catch (Exception $e) {
                    $jobStatus = "Failed";
                    Log::log('error', 'Exception While Restoring Site Folders ' . $e->getMessage());
                    continue;
                }
                //--------------------------------------------//
                $result           = $data['data'];
                $detail->status   = ($data["status"] == 200 ? 'Success' : $data["status"]);
                // Exceptions not caught by Veeam api
                $itemDetails = optional($data['data']);
                if (isset($itemDetails->exceptions)) {
                    $detail->status = "Failed";
                    $jobStatus = "Failed";
                    $detail->error_response = implode(",", $itemDetails->exceptions);
                } else if (isset($itemDetails->warnings) && count($itemDetails->warnings)) {
                    $detail->status = 'Warning';
                    $jobStatus = 'Warning';
                    $detail->error_response = implode(",", $itemDetails->warnings);
                }
                $detail->returned_response   = json_encode($result);
                $after            = Carbon::now();
                $detail->duration = $after->diffAsCarbonInterval($before);
                $detail->save();
            }
            //--------------------------------------------//
            $history->status             = $jobStatus;
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
            Log::log('error', 'Exception While Restoring Site Folders ' . $e->getMessage());
            throw new \Exception("Error While Restoring Site Folders");
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
