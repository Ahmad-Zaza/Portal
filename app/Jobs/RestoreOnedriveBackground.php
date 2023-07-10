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

class RestoreOnedriveBackground implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 60 * 60 * 12;
    public $userId;
    public $historyId;
    public $sessionData;
    public $onedriveData;
    public $functionName;
    public $_managerVeeam;
    public $session;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $historyId, $functionName, $sessionData, $onedriveData)
    {
        $this->userId = $userId;
        $this->historyId = $historyId;
        $this->sessionData = $sessionData;
        $this->onedriveData = $onedriveData;
        $this->functionName = $functionName;
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
    public function restoreOnedrive()
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
            $before = Carbon::now();
            $optionsArr = (array) json_decode($history->options);
            //--------------------------------------------//
            $data = $this->_managerVeeam->restoreOnedriveOriginal($sessionId, $this->onedriveData['onedrives'], $this->onedriveData['userCode'], $optionsArr, $this->historyId);
            if ($this->checkProcessCanceled($this->historyId)) {
                return;
            }
            //--------------------------------------------//
            $jobStatus = 'Success';
            $result = $data['data']->details;
            foreach ($result as $item) {
                $details = BacRestoreHistoryDetail::where('item_id', $item->oneDrive->id)->where('restore_history_id', $history->id)->first();
                $details->status = $item->status;
                // Exceptions not caught by Veeam api
                $itemDetails = optional($item->details);
                if (isset($itemDetails->exceptions)) {
                    $details->status = "Failed";
                    $jobStatus = "Failed";
                    $details->error_response = implode(",", $itemDetails->exceptions);
                } else if (isset($itemDetails->warnings) && count($itemDetails->warnings)) {
                    $details->status = 'Warning';
                    $jobStatus = 'Warning';
                    $details->error_response = implode(",", $itemDetails->warnings);
                }
                $details->returned_response = json_encode($item->details);
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
            Log::log('error', 'Exception While Restoring Onedrive To Original ' . $e->getMessage());
            throw new \Exception("Error While Restoring Onedrive To Original");
        }
    }
    //-------------------------------------------------------------------------//
    public function copyOnedrive()
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
            $jobStatus = 'Success';
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
                    $data = $this->_managerVeeam->restoreOnedriveCopy($sessionId, $item->item_id, $this->onedriveData['userCode'], $optionsArr['toOnedrive'], $optionsArr['toFolder'], $optionsArr, $item->id);
                    $result = $data['data'];
                    $item->status = ($data['status'] == '200' ? 'Success' : $data['status']);
                    // Exceptions not caught by Veeam api
                    $itemDetails = optional($data['data']);
                    if (isset($itemDetails->exceptions)) {
                        $item->status = 'Failed';
                        $jobStatus = 'Failed';
                        $item->error_response = implode(",", $itemDetails->exceptions);
                    } else if (isset($itemDetails->warnings)) {
                        $item->status = 'Warning';
                        $jobStatus = 'Warning';
                        $item->error_response = implode(",", $itemDetails->warnings);
                    }
                    $item->returned_response = json_encode($result);
                    $after = Carbon::now();
                    $item->duration = $after->diffAsCarbonInterval($before);
                    $item->save();
                } catch (Exception $e) {
                    $jobStatus = "Failed";
                    Log::log('error', 'Exception While Copying Onedrive To Another ' . $e->getMessage());
                }
            }
            //--------------------------------------------//
            $history->status = $jobStatus;
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            //--------------------------------------------//
        } catch (Exception $e) {
            $history->status = 'Failed';
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
            Log::log('error', 'Exception While Copying Onedrive ' . $e->getMessage());
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreOnedriveFolderOriginal()
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
            $jobStatus = 'Success';
            //--------------------------------------------//
            $items = BacRestoreHistoryDetail::where('restore_history_id', $history->id)->get();
            foreach ($items as $detail) {
                $before = Carbon::now();
                if ($this->checkProcessCanceled($this->historyId)) {
                    return;
                }
                try {
                    $data = $this->_managerVeeam->restoreOnedriveFolderOriginal($sessionId, $detail->item_id, $detail->item_parent_id, $this->onedriveData['userCode'], (array) json_decode($history->options), $detail->id);
                } catch (Exception $e) {
                    $jobStatus = "Failed";
                    continue;
                }
                //--------------------------------------------//
                $result = $data['data'];
                $detail->status = ($data["status"] == 200 ? 'Success' : $data["status"]);
                // Exceptions not caught by Veeam api
                $itemDetails = optional($data['data']);
                if (isset($itemDetails->exceptions)) {
                    $detail->status = 'Failed';
                    $jobStatus = 'Failed';
                    $detail->error_response = implode(",", $itemDetails->exceptions);
                } else if (isset($itemDetails->warnings)) {
                    $detail->status = 'Warning';
                    $jobStatus = 'Warning';
                    $detail->error_response = implode(",", $itemDetails->warnings);
                }
                $detail->returned_response = json_encode($result);
                $after = Carbon::now();
                $detail->duration = $after->diffAsCarbonInterval($before);
                $detail->save();
                //--------------------------------------------//
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
            Log::log('error', 'Exception While Restoring Onedrive Folders To Original ' . $e->getMessage());
            throw new \Exception("Error While Restoring Onedrive Folders To Original");
        }
    }
    //-------------------------------------------------------------------------//
    public function copyOnedriveFolder()
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
            //--------------------------------------------//
            $items = BacRestoreHistoryDetail::where('restore_history_id', $history->id)->get();
            foreach ($items as $detail) {
                $before = Carbon::now();
                try {
                    $data = $this->_managerVeeam->copyOnedriveFolder($sessionId, $detail->item_id, $detail->item_parent_id, $this->onedriveData['userCode'], (array) json_decode($history->options), $detail->id);
                } catch (Exception $e) {
                    $jobStatus = "Failed";
                    continue;
                }
                if ($this->checkProcessCanceled($this->historyId)) {
                    return;
                }
                //--------------------------------------------//
                $detail->status = ($data["status"] == 200 ? 'Success' : $data["status"]);
                $result = $data['data'];
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
                $detail->returned_response = json_encode($result);
                $after = Carbon::now();
                $detail->duration = $after->diffAsCarbonInterval($before);
                $detail->save();
                //--------------------------------------------//
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
            Log::log('error', 'Exception While Copy Onedrive To Original ' . $e->getMessage());
            throw new \Exception("Error While Copy Onedrive To Original");
        }
    }
    //-------------------------------------------------------------------------//
    public function restoreOnedriveDocumentOriginal()
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
            $details = BacRestoreHistoryDetail::where("restore_history_id", $this->historyId)->get();
            foreach ($details as $detail) {
                $before = Carbon::now();
                try {
                    $data = $this->_managerVeeam->restoreOnedriveDocumentOriginal($sessionId, $detail->item_id, $detail->item_parent_id, $this->onedriveData['userCode'], (array) json_decode($history->options), $detail->id);
                } catch (\Exception $e) {
                    $jobStatus = "Failed";
                    Log::log('error', 'Exception While Restoring Onedrive Documents To Original ' . $e->getMessage());
                    continue;
                }
                if ($this->checkProcessCanceled($this->historyId)) {
                    return;
                }
                //--------------------------------------------//
                $detail->status = ($data["status"] == 200 ? 'Success' : $data["status"]);
                $result = $data['data'];
                // Exceptions not caught by Veeam api
                $itemDetails = optional($data['data']);
                if (isset($itemDetails->exceptions)) {
                    $detail->status = 'Failed';
                    $jobStatus = 'Failed';
                    $detail->error_response = implode(",", $itemDetails->exceptions);
                } else if (isset($itemDetails->warnings)) {
                    $detail->status = 'Warning';
                    $jobStatus = 'Warning';
                    $detail->error_response = implode(",", $itemDetails->warnings);
                }
                $detail->returned_response = json_encode($result);
                $after = Carbon::now();
                $detail->duration = $after->diffAsCarbonInterval($before);
                $detail->save();
            }
            //--------------------------------------------//
            $history->status = $jobStatus;
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
        } catch (Exception $e) {
            //-----------------------------------//
            $history->status = 'Failed';
            $history->completion_time = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            //-----------------------------------//
            $this->stopRestoreSession($sessionId);
            Log::log('error', 'Exception While Restoring Onedrive Documnets To Original ' . $e->getMessage());
            throw new \Exception("Error While Restoring Onedrive Documnets To Original");
        }
    }
    //-------------------------------------------------------------------------//
    public function copyOnedriveDocument()
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
            $details = BacRestoreHistoryDetail::where("restore_history_id", $this->historyId)->get();
            $jobStatus = "Success";
            foreach ($details as $detail) {
                $before = Carbon::now();
                try {
                    $data = $this->_managerVeeam->copyOnedriveDocument($sessionId, $detail->item_id, $detail->item_parent_id, $this->onedriveData['userCode'], (array) json_decode($history->options), $detail->id);
                } catch (Exception $e) {
                    $jobStatus = "Failed";
                    Log::log('error', 'Exception While Restoring Onedrive Documents To Another ' . $e->getMessage());
                    continue;
                }
                if ($this->checkProcessCanceled($this->historyId)) {
                    return;
                }
                //--------------------------------------------//
                $detail->status = ($data["status"] == 200 ? 'Success' : $data["status"]);
                $result = $data['data'];
                // Exceptions not caught by Veeam api
                $itemDetails = optional($data['data']);
                if (isset($itemDetails->exceptions)) {
                    $detail->status = 'Failed';
                    $jobStatus = 'Failed';
                    $detail->error_response = implode(",", $itemDetails->exceptions);
                } else if (isset($itemDetails->warnings)) {
                    $detail->status = 'Warning';
                    $jobStatus = 'Warning';
                    $detail->error_response = implode(",", $itemDetails->warnings);
                }
                $detail->returned_response = json_encode($result);
                $after = Carbon::now();
                $detail->duration = $after->diffAsCarbonInterval($before);
                $detail->save();
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
            Log::log('error', 'Exception While Restoring Onedrive Documents To Another ' . $e->getMessage());
            throw new \Exception("Error While Restoring Onedrive Documents To Another");
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
