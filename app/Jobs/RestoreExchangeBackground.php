<?php

namespace App\Jobs;

use App\Engine\Veeam\ManagerVeeam;
use App\Models\BacRestoreHistory;
use App\Models\BacRestoreHistoryDetail;
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

class RestoreExchangeBackground implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 60 * 60 * 12;

    public $userId;
    public $historyId;
    public $sessionData;
    public $exchangeData;
    public $functionName;
    public $_managerVeeam;
    public $session;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $historyId, $functionName, $sessionData, $exchangeData)
    {
        $this->userId = $userId;
        $this->historyId = $historyId;
        $this->sessionData = $sessionData;
        $this->exchangeData = $exchangeData;
        $this->functionName = $functionName;
        $this->session = session()->all();
    }

    public function middleware()
    {
        return [(new WithoutOverlapping($this->historyId))];
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
    public function restoreMailboxOriginal()
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
                    "error_response" => "Session Not Valid",
                ]);
            }
            $sessionInfo = $this->_managerVeeam->getRestoreSession($sessionId)['data'];
            if ($sessionInfo->state != "Working") {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid",
                ]);
            }
            //--------------------------------------------//
            $before = Carbon::now();
            $data = $this->_managerVeeam->restoreMailboxToOriginal($sessionId, $this->exchangeData['mailboxes'], $this->exchangeData['deviceCode'], (array) json_decode($history->options), $this->historyId);
            if ($this->checkProcessCanceled($this->historyId)) {
                return;
            }
            //--------------------------------------------//
            $result = $data['data']->details;
            $historyStatus = 'Success';
            foreach ($result as $item) {
                $details = BacRestoreHistoryDetail::where('item_id', $item->mailbox->id)->where('restore_history_id', $history->id)->first();
                $details->status = $item->status;
                $details->returned_response = json_encode($item);
                //---------------------------------------//
                // Exceptions not caught by Veeam api
                $itemDetails = $item->details;
                if (isset($itemDetails->exceptions)) {
                    $historyStatus = "Failed";
                    $details->status = "Failed";
                    $details->error_response = implode(",", $itemDetails->exceptions);
                } else if (isset($itemDetails->warnings) && !empty($itemDetails->warnings)) {
                    $historyStatus = 'Warning';
                    $details->status = 'Warning';
                    $details->error_response = implode(",", $itemDetails->warnings);
                }
                //---------------------------------------//
                $after = Carbon::now();
                $details->duration = $after->diffAsCarbonInterval($before);
                $details->save();
            }
            //--------------------------------------------//
            $history->status = $historyStatus;
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
        } catch (Exception $e) {
            $history->status = 'Failed';
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            Log::log('error', 'Exception While Restoring Mailbox To Original ' . $e);
            $this->fail($e);
            // throw new \Exception("Error While Restoring Mailbox To Original");
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreMailboxAnother()
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
                    "error_response" => "Session Not Valid",
                ]);
            }
            $sessionInfo = $this->_managerVeeam->getRestoreSession($sessionId)['data'];
            if ($sessionInfo->state != "Working") {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid",
                ]);
            }
            //--------------------------------------------//
            $jobStatus = 'Success';
            //--------------------------------------------//
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
                    $data = $this->_managerVeeam->restoreMailboxToAnother($sessionId, $item->item_id, $this->exchangeData['userCode'], $optionsArr['toMailBox'], $optionsArr['toFolder'], $optionsArr, $item->id);
                    $result = $data['data'];
                    $item->returned_response = json_encode($result);
                    $item->status = $data['status'];
                    // Exceptions not caught by Veeam api
                    $itemDetails = optional($result);
                    if (isset($itemDetails->exceptions)) {
                        $jobStatus = "Failed";
                        $item->status = "Failed";
                        $item->error_response = implode(",", $itemDetails->exceptions);
                    } else if (isset($itemDetails->warnings) && count($itemDetails->warnings)) {
                        $jobStatus = 'Warning';
                        $item->status = 'Warning';
                        $item->error_response = implode(",", $itemDetails->warnings);
                    }
                    $after = Carbon::now();
                    $item->duration = $after->diffAsCarbonInterval($before);
                    $item->save();
                } catch (Exception $e) {
                    $item->status = "Failed";
                    $item->save();
                    $jobStatus = "Failed";
                    Log::log('error', 'Exception While Restoring Mailbox To Another ' . $e);
                }
            }
            //--------------------------------------------//
            $history->status = $jobStatus;
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
        } catch (Exception $e) {
            $history->status = 'Failed';
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            Log::log('error', 'Exception While Restoring Mailbox To Another ' . $e);
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreFolderOriginal()
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
                    "error_response" => "Session Not Valid",
                ]);
            }
            $sessionInfo = $this->_managerVeeam->getRestoreSession($sessionId)['data'];
            if ($sessionInfo->state != "Working") {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid",
                ]);
            }
            //--------------------------------------------//
            $jobStatus = 'Success';
            //--------------------------------------------//
            $details = BacRestoreHistoryDetail::where('restore_history_id', $history->id)->get();
            //--------------------------------------------//
            foreach ($details as $folder) {
                if ($this->checkProcessCanceled($this->historyId)) {
                    return;
                }
                try {
                    $before = Carbon::now();
                    $data = $this->_managerVeeam->restoreMailboxFolderToOriginal($sessionId, $folder->item_parent_id, $folder->item_id, $this->exchangeData['userCode'], $folder->id);
                    $folder->status = $data["status"];
                    $folder->returned_response = json_encode($data["data"]);
                    // Exceptions not caught by Veeam api
                    $itemDetails = optional($data["data"]);
                    if (isset($itemDetails->exceptions)) {
                        $jobStatus = 'Failed';
                        $folder->status = 'Failed';
                        $folder->error_response = implode(",", $itemDetails->exceptions);
                    } else if (isset($itemDetails->warnings) && count($itemDetails->warnings)) {
                        $jobStatus = 'Warning';
                        $folder->status = 'Warning';
                        $folder->error_response = implode(",", $itemDetails->warnings);
                    }
                    $after = Carbon::now();
                    $folder->duration = $after->diffAsCarbonInterval($before);
                    $folder->save();
                } catch (Exception $e) {
                    $jobStatus = 'Failed';
                    Log::log('error', 'Exception While Restoring Folder To Original ' . $e);
                }
            }
            //--------------------------------------------//
            $history->status = $jobStatus;
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
        } catch (Exception $e) {
            $history->status = 'Failed';
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            Log::log('error', 'Exception While Restoring Folder To Original ' . $e);
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreFolderAnother()
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
                    "error_response" => "Session Not Valid",
                ]);
            }
            $sessionInfo = $this->_managerVeeam->getRestoreSession($sessionId)['data'];
            if ($sessionInfo->state != "Working") {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid",
                ]);
            }
            //--------------------------------------------//
            $jobStatus = 'Success';
            $optionsArr = (array) json_decode($history->options);
            //--------------------------------------------//
            $details = BacRestoreHistoryDetail::where('restore_history_id', $history->id)->get();
            //--------------------------------------------//
            foreach ($details as $folder) {
                if ($this->checkProcessCanceled($this->historyId)) {
                    return;
                }
                $before = Carbon::now();
                try {
                    $data = $this->_managerVeeam->restoreMailboxFolderToAnother($sessionId, $folder->item_parent_id, $folder->item_id, $this->exchangeData['userCode'], $optionsArr['toMailBox'], $optionsArr['toFolder'], $optionsArr, $folder->id);
                    if (optional($data["data"])->exceptions) {
                        $folder->status = "Failed";
                        $jobStatus = 'Failed';
                        $folder->error_response = implode(",", $data["data"]->exceptions);
                        $folder->returned_response = json_encode($data["data"]);
                        $after = Carbon::now();
                        $folder->duration = $after->diffAsCarbonInterval($before);
                        $folder->save();
                    } else {
                        $folder->status = $data["status"];
                        $folder->returned_response = json_encode($data["data"]);
                        $after = Carbon::now();
                        $folder->duration = $after->diffAsCarbonInterval($before);
                        $folder->save();
                    }
                } catch (Exception $e) {
                    $folder->status = $data["status"];
                    $folder->error_response = json_encode($data["data"]);
                    $after = Carbon::now();
                    $folder->duration = $after->diffAsCarbonInterval($before);
                    $folder->save();
                    $jobStatus = 'Failed';
                    Log::log('error', 'Exception While Restoring Folder To Original ' . $e);
                }
            }
            //--------------------------------------------//
            $history->status = $jobStatus;
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
        } catch (Exception $e) {
            $history->status = 'Failed';
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            Log::log('error', 'Exception While Restoring Folder To Another ' . $e);
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreMailboxItemsOriginal()
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
                    "error_response" => "Session Not Valid",
                ]);
            }
            $sessionInfo = $this->_managerVeeam->getRestoreSession($sessionId)['data'];
            if ($sessionInfo->state != "Working") {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid",
                ]);
            }
            //--------------------------------------------//
            $detailsArr = BacRestoreHistoryDetail::where("restore_history_id", $this->historyId)->get();
            $jobStatus = 'Success';
            foreach ($detailsArr as $details) {
                //--------------------------------------------//
                $before = Carbon::now();
                $data = $this->_managerVeeam->restoreMailboxItemToOriginal($sessionId, $details->item_parent_id, $details->item_id, $this->exchangeData['userCode'], $details->id);
                if ($this->checkProcessCanceled($this->historyId)) {
                    return;
                }
                //--------------------------------------------//
                $details->status = is_numeric($data["status"]) && ($data["status"] >= 200 && $data["status"] <= 299) ? "Success" : $data["status"];
                $details->returned_response = json_encode($data["data"]);
                // Exceptions not caught by Veeam api
                $itemDetails = optional($data["data"]);
                if (isset($itemDetails->exceptions)) {
                    $jobStatus = 'Failed';
                    $details->status = 'Failed';
                    $details->error_response = implode(",", $itemDetails->exceptions);
                } else if (isset($itemDetails->warnings) && count($itemDetails->warnings)) {
                    $jobStatus = 'Warning';
                    $details->status = 'Warning';
                    $details->error_response = implode(",", $itemDetails->warnings);
                }
                $after = Carbon::now();
                $details->duration = $after->diffAsCarbonInterval($before);
                $details->save();
            }
            //--------------------------------------------//
            $history->status = $jobStatus;
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
        } catch (Exception $e) {
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->status = 'Failed';
            $history->save();
            $this->stopRestoreSession($sessionId);
            Log::log('error', 'Exception While Restoring Mailbox Items To Original ' . $e);
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreMailboxItemsAnother()
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
                    "error_response" => "Session Not Valid",
                ]);
            }
            $sessionInfo = $this->_managerVeeam->getRestoreSession($sessionId)['data'];
            if ($sessionInfo->state != "Working") {
                $history->status = "Failed";
                $history->save();
                BacRestoreHistoryDetail::where("restore_history_id", $history->id)->update([
                    "status" => "Failed",
                    "error_response" => "Session Not Valid",
                ]);
            }
            //--------------------------------------------//
            $detailsArr = BacRestoreHistoryDetail::where("restore_history_id", $this->historyId)->get();
            $jobStatus = 'Success';
            foreach ($detailsArr as $details) {
                $before = Carbon::now();
                $optionsArr = (array) json_decode($history->options);
                //--------------------------------------------//
                $data = $this->_managerVeeam->restoreMailboxItemToAnother($sessionId, $details->item_parent_id, $details->item_id, $this->exchangeData['userCode'], $optionsArr['toMailBox'], $optionsArr['toFolder'], $optionsArr, $details->id);
                if ($this->checkProcessCanceled($this->historyId)) {
                    return;
                }
                //--------------------------------------------//
                $details->status = is_numeric($data["status"]) && ($data["status"] >= 200 && $data["status"] <= 299) ? "Success" : $data["status"];
                $details->returned_response = json_encode($data["data"]);
                // Exceptions not caught by Veeam api
                $itemDetails = optional($data["data"]);
                if (isset($itemDetails->exceptions)) {
                    $jobStatus = 'Failed';
                    $details->status = 'Failed';
                    $details->error_response = implode(",", $itemDetails->exceptions);
                } else if (isset($itemDetails->warnings) && count($itemDetails->warnings)) {
                    $jobStatus = 'Warning';
                    $details->status = 'Warning';
                    $details->error_response = implode(",", $itemDetails->warnings);
                }
                $after = Carbon::now();
                $details->duration = $after->diffAsCarbonInterval($before);
                $details->save();
            }
            //--------------------------------------------//
            $history->status = $jobStatus;
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
        } catch (Exception $e) {
            $history->status = 'Failed';
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            Log::log('error', 'Exception While Restoring Mailbox Items To Another Location ' . $e);
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
            if ($session->state != "Stopped") {
                return $this->_managerVeeam->stopRestoreSession($sessionId);
            }
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Stopping Restore Sessions ** ' . $ex);
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
        Log::log('error', 'Exception Job Failed ' . $e);
        $history = BacRestoreHistory::where('id', $this->historyId)->first();
        $this->_managerVeeam = new ManagerVeeam();
        //--------------------------------------//
        if ($history->restore_session_guid) {
            $this->stopRestoreSession($history->restore_session_guid);
        }
        //--------------------------------------//
        $history->status = 'Failed';
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
