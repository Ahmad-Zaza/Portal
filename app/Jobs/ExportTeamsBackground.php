<?php

namespace App\Jobs;

use App\Engine\Azure\ManagerAzure;
use App\Engine\Base\Manager;
use App\Engine\Veeam\ManagerVeeam;
use App\Models\RestoreHistoryDetails;
use App\Models\RestoreHistory;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Psr7\Utils;
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
class ExportTeamsBackground implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $tries = 1;
    public $timeout = 60 * 60 * 12;
    public $sessionData;
    public $storageData;
    public $managerVeeam;
    public $managerAzure;
    public $type;
    public $userId;
    public $historyId;
    public $session;
    public $historyStatus;

    public function __construct($userId, $historyId, $type, $storageData, $sessionData)
    {
        $this->sessionData  = $sessionData;
        $this->storageData  = $storageData;
        $this->type         = $type;
        $this->historyId    = $historyId;
        $this->userId       = $userId;
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
        $this->managerVeeam = new ManagerVeeam();
        $this->managerAzure = new ManagerAzure();
        if ($this->type == 'channel-posts') {
            $this->exportChannelsPosts();
        } else if ($this->type == 'channel-files') {
            $this->exportChannelsFiles();
        } else if ($this->type == 'file') {
            $this->exportFiles();
        } else if ($this->type == 'post') {
            $this->exportPosts();
        }
    }
    //-------------------------------------------------------------------------//
    public function exportChannelsPosts()
    {
        $history = BacRestoreHistory::where('id', $this->historyId)->first();
        if ($this->checkProcessCanceled($this->historyId)) {
            return;
        }
        try {
            $sessionId                   = $this->createRestoreSession($this->sessionData['orgId'], $this->sessionData['time'], $this->sessionData['showDeleted'], $this->sessionData['showVersions']);
            $history->restore_session_guid = $sessionId;
            $history->save();
            //-----------------
            $this->historyStatus = "Success";
            //-----------------
            $details = BacRestoreHistoryDetail::where('restore_history_id', $history->id)->get();
            $itemIds = [];
            $itemParentIds = [];
            $callbacks = [];
            foreach ($details as $item) {
                //-----------------
                $savingFunction = $this->generateStreamingCallback($item, "Exporting Channel Posts");
                //-----------------
                array_push($itemIds, $item->item_id);
                array_push($itemParentIds, $item->item_parent_id);
                array_push($callbacks, $savingFunction);
                //-----------------
            }
            try {
                $this->managerVeeam->exportChannelsPosts($sessionId, $itemParentIds, $itemIds, $callbacks);
            } catch (Exception $e) {
                $this->historyStatus = "Failed";
                Log::log("error", "Exception While Exporting Channel Posts " . $e->getMessage());
            }
            //-----------------
            $history->status             = $this->historyStatus;
            $history->completion_time    = Carbon::now();
            $history->expiration_time    = Carbon::now()->add(config('parameters.EXPORTED_FILES_EXPIRATION_DAYS'), 'days');
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
        } catch (Exception $exception) {
            $history->status             = 'Failed';
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            BacRestoreHistoryDetail::where('restore_history_id', $this->historyId)
                ->where('status', 'In Progress')
                ->update([
                    'status' => 'Failed',
                ]);
            Log::log('error', 'Exception While Exporting Channel Posts ' . json_encode($exception->getMessage()));
        }
    }
    //-------------------------------------------------------------------------//
    public function exportPosts()
    {
        $history = BacRestoreHistory::where('id', $this->historyId)->first();
        if ($this->checkProcessCanceled($this->historyId)) {
            return;
        }
        try {
            $sessionId                   = $this->createRestoreSession($this->sessionData['orgId'], $this->sessionData['time'], $this->sessionData['showDeleted'], $this->sessionData['showVersions']);
            $history->restore_session_guid = $sessionId;
            $history->save();
            //-----------------
            $this->historyStatus = "Success";
            //-----------------
            $details = BacRestoreHistoryDetail::where('restore_history_id', $history->id)->get();
            $teamIds = [];
            $channelIds = [];
            $callbacks = [];
            $postsArr = [];
            foreach ($details as $item) {
                //-----------------
                $savingFunction = $this->generateStreamingCallback($item, "Exporting Posts");
                //-----------------
                array_push($callbacks, $savingFunction);
                //-----------------
                $tempArr = json_decode($item->item_id);
                $posts = [];
                foreach ($tempArr as $it) {
                    array_push($posts, ['id' => intval($it->id)]);
                }
                array_push($postsArr, json_encode($posts));
                //-----------------
                $tempArr = explode("|", $item->item_parent_id);
                array_push($channelIds, $tempArr[1]);
                array_push($teamIds, $tempArr[0]);
                //-----------------
            }
            try {
                $this->managerVeeam->exportPosts($sessionId, $teamIds, $channelIds, $postsArr, $callbacks);
            } catch (Exception $e) {
                $this->historyStatus = "Failed";
                Log::log("error", "Exception While Exporting Posts " . $e->getMessage());
            }
            //-----------------
            $history->status             = $this->historyStatus;
            $history->completion_time    = Carbon::now();
            $history->expiration_time    = Carbon::now()->add(config('parameters.EXPORTED_FILES_EXPIRATION_DAYS'), 'days');
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
        } catch (Exception $e) {
            $history->status             = 'Failed';
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            BacRestoreHistoryDetail::where('restore_history_id', $this->historyId)
                ->where('status', 'In Progress')
                ->update([
                    'status' => 'Failed',
                ]);
            Log::log('error', 'Exception While Exporting Posts ' .$e);
        }
    }
    //-------------------------------------------------------------------------//
    public function exportChannelsFiles()
    {
        $history = BacRestoreHistory::where('id', $this->historyId)->first();
        if ($this->checkProcessCanceled($this->historyId)) {
            return;
        }
        try {
            $sessionId = $this->createRestoreSession($this->sessionData['orgId'], $this->sessionData['time'], $this->sessionData['showDeleted'], $this->sessionData['showVersions']);
            $history->restore_session_guid = $sessionId;
            $history->save();
            //-----------------
            $this->historyStatus = "Success";
            //-----------------
            $details = BacRestoreHistoryDetail::where('restore_history_id', $history->id)->get();
            $itemIds = [];
            $itemParentIds = [];
            $callbacks = [];
            foreach ($details as $item) {
                //-----------------
                $savingFunction = $this->generateStreamingCallback($item, "Exporting Channel Files");
                //-----------------
                array_push($itemIds, $item->item_id);
                array_push($itemParentIds, $item->item_parent_id);
                array_push($callbacks, $savingFunction);
                //-----------------
            }
            try {
                $this->managerVeeam->exportChannelsFiles($sessionId, $itemParentIds, $itemIds, $callbacks);
            } catch (Exception $e) {
                $this->historyStatus = "Failed";
                Log::log("error", "Exception While Exporting Channel Files " . $e->getMessage());
            }
            //-----------------
            $history->status             = $this->historyStatus;
            $history->completion_time    = Carbon::now();
            $history->expiration_time    = Carbon::now()->add(config('parameters.EXPORTED_FILES_EXPIRATION_DAYS'), 'days');
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
        } catch (Exception $exception) {
            $history->status             = 'Failed';
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            BacRestoreHistoryDetail::where('restore_history_id', $this->historyId)
                ->where('status', 'In Progress')
                ->update([
                    'status' => 'Failed',
                ]);
            Log::log('error', 'Exception While Exporting Channel Files ' . json_encode($exception->getMessage()));
        }
    }
    //-------------------------------------------------------------------------//
    public function exportFiles()
    {
        $history = BacRestoreHistory::where('id', $this->historyId)->first();
        if ($this->checkProcessCanceled($this->historyId)) {
            return;
        }
        try {
            $sessionId                   = $this->createRestoreSession($this->sessionData['orgId'], $this->sessionData['time'], $this->sessionData['showDeleted'], $this->sessionData['showVersions']);
            $history->restore_session_guid = $sessionId;
            $history->save();
            //-----------------
            $this->historyStatus = "Success";
            //-----------------
            $details = BacRestoreHistoryDetail::where('restore_history_id', $history->id)->get();
            $itemIds = [];
            $itemParentIds = [];
            $callbacks = [];
            foreach ($details as $item) {
                //-----------------
                $savingFunction = $this->generateStreamingCallback($item, "Exporting Files");
                //-----------------
                array_push($itemIds, $item->item_id);
                array_push($itemParentIds, $item->item_parent_id);
                array_push($callbacks, $savingFunction);
                //-----------------
            }
            try {
                $this->managerVeeam->exportTeamsFiles($sessionId, $itemParentIds, $itemIds, $callbacks);
            } catch (Exception $e) {
                $this->historyStatus = "Failed";
                Log::log("error", "Exception While Exporting Files " . $e->getMessage());
            }
            //-----------------
            $history->status             = $this->historyStatus;
            $history->completion_time    = Carbon::now();
            $history->expiration_time    = Carbon::now()->add(config('parameters.EXPORTED_FILES_EXPIRATION_DAYS'), 'days');
            $history->restore_session_guid = $sessionId;
            $history->save();
            $this->stopRestoreSession($sessionId);
        } catch (Exception $exception) {
            $history->status             = 'Failed';
            $history->completion_time    = Carbon::now();
            $history->restore_session_guid = $sessionId;
            $history->save();
            BacRestoreHistoryDetail::where('restore_history_id', $this->historyId)
                ->where('status', 'In Progress')
                ->update([
                    'status' => 'Failed',
                ]);
            Log::log('error', 'Exception While Exporting Files ' . json_encode($exception->getMessage()));
        }
    }
    //-------------------------------------------------------------------------//
    private function createRestoreSession($organizationId, $time, $showDeleted, $showVersions)
    {
        try {
            return $this->managerVeeam->createRestoreSession($organizationId, $time, "vet", $showDeleted, $showVersions)['data']->id;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Creating Restore Session  ' . $ex->getMessage());
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
            Log::log('error', 'Exception While Stopping Restore Session  ' . $ex->getMessage());
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
        Log::log('error', 'Exception Job Failed ' .  $e);
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
    private function generateStreamingCallback($item, $type = "")
    {
        $before = Carbon::now();
        //-----------------
        if ($this->checkProcessCanceled($this->historyId)) {
            return;
        }
        //-----------------
        $blockListString = '<?xml version="1.0" encoding="utf-8"?><BlockList>';
        $count = 0;
        $blobSize = 0;
        //----- Set Blob Name
        $tempBlobName = preg_replace('/\W/',"", $item->item_name);
        $blockSize    = config('parameters.UPLOAD_BLOB_BLOCK_MEGA_SIZE') * 1024 * 1024;
        $blobName = $tempBlobName . '_' . date("Ymd_His");
        $contentLength = 0;
        $stepSize = 0;
        $stepContent = "";
        //--------------------
        $isError = false;
        $errorMsg = false;
        $savingFunction = function ($ch, $str) use (&$count, &$isError, &$errorMsg, &$blobSize, &$blockListString, &$contentLength, &$stepContent, &$stepSize, &$blobName, &$item, $before, $blockSize, $type) {
            $chunkSize = strlen($str);
            $count++;
            if ($count == 12 && $isError) {
                Log::log('error', "Error $type $str");
                $errorMsg = optional(json_decode($str))->message;
                $this->historyStatus = "Failed";
                $item->status = 'Failed';
                $item->error_response = $errorMsg;
                $after           = Carbon::now();
                $item->duration  = $after->diffAsCarbonInterval($before);
                $item->save();
                return -1;
            }
            if ($count < 13) {
                if (strpos($str, "content-length") !== false)
                    $contentLength = substr($str, (strlen("content-length: ") - 1));
                if (strpos($str, "content-type:") !== false)
                    if (strpos($str, "content-type: application/json") !== false)
                        $isError = true;
                if (strpos($str, "content-disposition") !== false) {
                    $blobName .= $this->getExtension($str);
                }
                return $chunkSize;
            }
            $stepContent .= $str;
            $stepSize += $chunkSize;
            $blobSize += $chunkSize;
            if ($stepSize < $contentLength && $blobSize < $contentLength && $stepSize < $blockSize) {
                return $chunkSize;
            }
            //----- Set Block Id
            $blockCount = str_pad($count - 6, 20, "0", STR_PAD_LEFT);
            $blockId    = base64_encode("$blockCount");
            //--------------------
            $blockListString .= "<Latest>$blockId</Latest>";
            //--------------------
            // Put Block
            $blockContent = $stepContent;
            //--------------------
            if ($this->checkProcessCanceled($this->historyId)) {
                return 0;
            }
            //--------------------
            $this->managerAzure->uploadContainerBlobBlock([
                "storageAccount" => $this->storageData['storageAccount'],
                "accountKey"     => $this->storageData['accountKey'],
                "containerName"  => $this->storageData['containerName'],
                "blobName"       => $blobName,
                "dataString"     => $blockContent,
                "blockId"        => $blockId,
            ]);
            $stepContent = "";
            $stepSize = 0;
            //--------------------
            if ($blobSize == $contentLength) {
                $blockListString .= '</BlockList>';
                //-----------------
                $this->managerAzure->putBlobBlockList([
                    'storageAccount' => $this->storageData['storageAccount'],
                    'accountKey'     => $this->storageData['accountKey'],
                    'containerName'  => $this->storageData['containerName'],
                    'blobName'       => $blobName,
                    'blocksListXML'  => $blockListString,
                ]);
                //-----------------
                if ($isError) {
                    $this->historyStatus = "Failed";
                    $item->status = 'Failed';
                    $item->error_response = $errorMsg;
                } else {
                    $item->status    = 'Success';
                    $item->exported_file_name = $blobName;
                    $item->exported_file_size = $blobSize;
                }
                $after           = Carbon::now();
                $item->duration  = $after->diffAsCarbonInterval($before);
                $item->save();
            }
            //--------------------
            return $chunkSize;
        };
        return $savingFunction;
    }
    //-------------------------------------------------------------------------//
    private function getExtension($fileName)
    {
        $temp = explode(".", $fileName);
        $ext = end($temp);
        $ext = str_replace("\r\n", "", $ext);
        $ext = preg_replace('/\W/',"", $ext);
        return "." . $ext;
    }
    //-------------------------------------------------------------------------//
}
