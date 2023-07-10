<?php

namespace App\Http\Controllers\EDiscovery;

use App\Engine\Azure\ManagerAzure;
use App\Engine\Veeam\ManagerVeeam;
use App\Jobs\EDiscoveryExchangeBackground;
use App\Jobs\EDiscoveryOnedriveBackground;
use App\Jobs\EDiscoverySharepointBackground;
use App\Jobs\EDiscoveryTeamsBackground;
use App\Models\BacEDiscoveryJob;
use App\Models\Organization;
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

class EDiscoveryController extends BaseController
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
    //main interface request
    public function main($kind)
    {
        try {
            $arr = array('kind' => $kind);
            return response()->view('ediscovery.main', ["arr" => $arr]);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting E-discovery page ' . $ex->getMessage());
            return response()->view("errors.500", [], 500);
        }
    }
    //------------------------------------------//
    //get jobs
    public function getEDiscoveryJobs($kind)
    {
        $user = auth()->user();
        try {
            $data = ViewOrganizationEdiscoveryJob::where('backup_job_kind', $kind)->where('organization_id', $user->organization->id)->get();
            return $data;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting backup jobs  ' . $ex->getMessage());
            return [];
        }
    }
    //------------------------------------------//
    public function createEDiscoveryJobPage($kind)
    {
        session()->forget("e-discovery_$kind\_data");
        $organization = auth()->user()->organization;
        $jobs = ViewOrganizationBackupJob::where("backup_job_kind", $kind)->where("organization_id", $organization->id)->get();

        $arr = [
            "kind" => $kind,
            "jobs" => $jobs,
            "job" => '',
            "categories" => config("app.search_criteria.$kind.categories"),
            "conditions" => config("app.search_criteria.conditions"),
        ];
        $fields = config("app.search_criteria.$kind.fields");
        $arr['categoriesFields']["All"] = $this->sortArray($fields);
        foreach ($arr['categories'] as $category) {
            $arr['categoriesFields'][$category] = array_values(array_filter(array_map(function ($value) use ($category) {
                if (strpos(",{$value['category']},", ",$category,") !== false) {
                    return $value;
                }
            }, $fields)));
        }
        if (!view()->exists("ediscovery.$kind")) {
            return abort(404);
        }

        return response()->view("ediscovery.$kind", compact('arr'));
    }
    //------------------------------------------//
    public function resultEDiscoveryJobPage($kind, $restoreSessionId)
    {
        //----------------------------------------//
        $user = auth()->user();
        //----------------------------------------//
        $data = [
            "kind" => $kind,
            "repo_kind" => $kind,
        ];
        $data['job'] = BacEDiscoveryJob::where('restore_session_guid', $restoreSessionId)->first();
        if (!$data['job']) {
            return response()->view("errors.404", [], 404);
        }
        $data['backup_job'] = VeeamBackupJob::where('id', $data['job']->backup_job_id)->first();
        $tempDate = new DateTime($data['job']->restore_point_time);
        $tempDate->setTimeZone(new DateTimeZone($user->timezone));
        $data['job']->restore_point_time = $tempDate->format('Y-m-d H:i');
        $data['search_data'] = json_decode($data['job']->search_data);
        //----------------------------------------//
        if (!view()->exists("ediscovery.$kind-result")) {
            return abort(404);
        }

        return response()->view("ediscovery.$kind-result", compact('data'));
    }
    //------------------------------------------//
    public function getConditionValue($kind, Request $request)
    {
        $type = $request->type;
        //----------------------------------------//
        $conditionsArr = config("app.search_criteria.conditions");
        $condition = $request->condition;
        $key = array_search($condition, array_column($conditionsArr, 'name'));
        $static = $conditionsArr[$key]['static'];
        //----------------------------------------//
        $values = '';
        if ($type == "list") {
            $values = $this->getFieldValues($kind, $request->field);
        }

        return view("ediscovery.condition-value", compact('type', 'condition', 'values', 'static'))->render();
    }
    //------------------------------------------//
    public function editEDiscoveryJobPage($kind, $restoreSessionId, Request $request)
    {
        if ($kind == "exchange") {
            return app(EDiscoveryExchangeController::class)->editEDiscoveryJobPage("exchange", $restoreSessionId, $request);
        } else if ($kind == "onedrive") {
            return app(EDiscoveryOnedriveController::class)->editEDiscoveryJobPage("onedrive", $restoreSessionId, $request);
        } else if ($kind == "teams") {
            return app(EDiscoveryTeamsController::class)->editEDiscoveryJobPage("teams", $restoreSessionId, $request);
        } else if ($kind == "sharepoint") {
            return app(EDiscoverySharepointController::class)->editEDiscoveryJobPage("sharepoint", $restoreSessionId, $request);
        }

    }
    //------------------------------------------//
    public function setEDiscoveryData($kind, Request $request)
    {
        $requestData = json_decode($request->data);
        $organization = auth()->user()->organization;
        session()->put("e-discovery_{$kind}_data", $request->data);
        $tempKind = ucfirst($kind);
        if (session("restore{$tempKind}SessionId")) {
            $session = $this->_managerVeeam->getRestoreSession(session("restore{$tempKind}SessionId"))['data'];
            if ($session->state != "Stopped") {
                $this->_managerVeeam->stopRestoreSession(session("restore{$tempKind}SessionId"));
            }

            session()->forget("restore{$tempKind}SessionId");
        }
        $sessionType = ($kind == "exchange") ? "vex" : (($kind == "onedrive") ? "veod" : (($kind == "sharepoint") ? "vesp" : "vet"));
        if ($requestData->restorePointType == 'all') {
            $restoreSession = $this->_managerVeeam->createRestoreSession($organization->veeam_organization_guid, $requestData->jobTime, $sessionType, $requestData->showDeleted, $requestData->showVersions)['data'];
        } else {
            $backupJob = VeeamBackupJob::where("id", $requestData->backupJobId)->first();
            $restoreSession = $this->_managerVeeam->createJobRestoreSession($backupJob->guid, $requestData->jobTime, $sessionType, $requestData->showDeleted, $requestData->showVersions);
        }
        session()->put("restore{$tempKind}SessionId", $restoreSession->id);
        return $restoreSession->id;
    }
    //------------------------------------------//
    public function moveToEdiscovery($kind, Request $request)
    {
        if ($kind == "exchange") {
            return app(EDiscoveryExchangeController::class)->moveToEdiscovery($request);
        } else if ($kind == "onedrive") {
            return app(EDiscoveryOnedriveController::class)->moveToEdiscovery($request);
        } else if ($kind == "teams") {
            return app(EDiscoveryTeamsController::class)->moveToEdiscovery($request);
        } else if ($kind == "sharepoint") {
            return app(EDiscoverySharepointController::class)->moveToEdiscovery($request);
        }

    }
    //------------------------------------------//
    public function getEdiscoveryJobResult($kind, $id, Request $request)
    {
        if ($kind == "exchange") {
            return app(EDiscoveryExchangeController::class)->getEdiscoveryJobResult("exchange", $id, $request);
        } else if ($kind == "onedrive") {
            return app(EDiscoveryOnedriveController::class)->getEdiscoveryJobResult("onedrive", $id, $request);
        } else if ($kind == "teams") {
            return app(EDiscoveryTeamsController::class)->getEdiscoveryJobResult("teams", $id, $request);
        } else if ($kind == "sharepoint") {
            return app(EDiscoverySharepointController::class)->getEdiscoveryJobResult("sharepoint", $id, $request);
        }

    }
    //------------------------------------------//
    public function saveEDiscoveryJob(Request $request)
    {
        if ($request->kind == "exchange") {
            return app(EDiscoveryExchangeController::class)->saveEDiscoveryJob($request);
        } else if ($request->kind == "onedrive") {
            return app(EDiscoveryOnedriveController::class)->saveEDiscoveryJob($request);
        } else if ($request->kind == "teams") {
            return app(EDiscoveryTeamsController::class)->saveEDiscoveryJob($request);
        } else if ($request->kind == "sharepoint") {
            return app(EDiscoverySharepointController::class)->saveEDiscoveryJob($request);
        }

    }
    //------------------------------------------//
    public function copyEdiscoveryJob($type, Request $request)
    {
        $jobId = $request->jobId;
        $status = $request->status;
        $user = auth()->user();
        $ediscoveryJob = BacEDiscoveryJob::where('id', $jobId)->first();
        //------------------------------------------//
        $organization = $user->organization;
        $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $ediscoveryJob->backup_job_id)->first();
        $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
        //------------------------------------------//
        $newJob = $ediscoveryJob->replicate();
        $newJob->status = "stopped";
        $newJob->request_time = Carbon::now();
        $newJob->duration = null;
        $newJob->total_items = null;
        $newJob->completion_time = null;
        $newJob->expiration_time = null;
        $newJob->save();
        try {
            $result = $this->_managerAzure->createTable($organization->azure_subscription_guid, $backupJobData->storage_account_name, $organization->azure_resource_group, $backupJobData->backup_job_kind . $newJob->id);
        } catch (Exception $ex) {
            Log::log('error', 'Exception While Copying Ediscovery Job ' . $ex->getMessage());
        }
        return response()->json([], 200);
    }
    //------------------------------------------//
    public function deleteEdiscoveryJob($type, Request $request)
    {
        $jobId = $request->jobId;
        $status = $request->status;
        $user = auth()->user();
        $ediscoveryJob = BacEDiscoveryJob::where('id', $jobId)->first();
        //------------------------------------------//
        $organization = $user->organization;
        $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $ediscoveryJob->backup_job_id)->first();
        $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
        //------------------------------------------//
        try {
            if ($ediscoveryJob->status != "Expired") {
                if ($this->_managerAzure->getTable($organization->azure_subscription_guid, $backupJobData->storage_account_name, $organization->azure_resource_group, $backupJobData->backup_job_kind . $ediscoveryJob->id)) {
                    $this->_managerAzure->deleteTable($organization->azure_subscription_guid, $backupJobData->storage_account_name, $organization->azure_resource_group, $backupJobData->backup_job_kind . $ediscoveryJob->id);
                }
            }

            $ediscoveryJob->delete();
        } catch (Exception $ex) {
            Log::log('error', 'Exception While Cancelling Ediscovery Job ' . $ex->getMessage());
        }
        return response()->json([], 200);
    }
    //------------------------------------------//
    public function forceExpireEdiscoveryJob($type, Request $request)
    {
        $jobId = $request->jobId;
        $status = $request->status;
        $user = auth()->user();
        $ediscoveryJob = BacEDiscoveryJob::where('id', $jobId)->first();
        //------------------------------------------//
        $organization = $user->organization;
        $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $ediscoveryJob->backup_job_id)->first();
        $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
        //------------------------------------------//
        try {
            if ($ediscoveryJob->status != "Expired") {
                if ($this->_managerAzure->getTable($organization->azure_subscription_guid, $backupJobData->storage_account_name, $organization->azure_resource_group, $backupJobData->backup_job_kind . $ediscoveryJob->id)) {
                    $this->_managerAzure->deleteTable($organization->azure_subscription_guid, $backupJobData->storage_account_name, $organization->azure_resource_group, $backupJobData->backup_job_kind . $ediscoveryJob->id);
                }
            }

        } catch (Exception $ex) {
            Log::log('error', 'Exception While Expiring Ediscovery Job ' . $ex->getMessage());
        }
        $ediscoveryJob->status = "Expired";
        $ediscoveryJob->save();
        return response()->json([], 200);
    }
    //------------------------------------------//
    public function reRunEdiscoveryJob($type, Request $request)
    {
        $jobId = $request->jobId;
        $status = $request->status;
        $user = auth()->user();
        $ediscoveryJob = BacEDiscoveryJob::where('id', $jobId)->first();
        //------------------------------------------//
        $organization = $user->organization;
        $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $ediscoveryJob->backup_job_id)->first();
        $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
        //------------------------------------------//
        try {
            //--------------------------------//
            if ($ediscoveryJob->status != "Expired") {
                if ($this->_managerAzure->getTable($organization->azure_subscription_guid, $backupJobData->storage_account_name, $organization->azure_resource_group, $backupJobData->backup_job_kind . $ediscoveryJob->id)) {
                    $this->_managerAzure->deleteTable($organization->azure_subscription_guid, $backupJobData->storage_account_name, $organization->azure_resource_group, $backupJobData->backup_job_kind . $ediscoveryJob->id);
                }
            }
            //--------------------------------//
        } catch (Exception $ex) {
            Log::log('error', 'Exception While Getting Table on Running Ediscovery Job ' . $ex->getMessage());
            // return response()->json(['message' => $ex->getMessage()], 500);
        }
        //----------------------------------------//
        $newJob = $ediscoveryJob->replicate();
        $newJob->status = "Running";
        $newJob->request_time = Carbon::now();
        $newJob->duration = null;
        $newJob->total_items = null;
        $newJob->completion_time = null;
        $newJob->expiration_time = null;
        $newJob->save();
        try {
            $result = $this->_managerAzure->createTable($organization->azure_subscription_guid, $backupJobData->storage_account_name, $organization->azure_resource_group, $backupJobData->backup_job_kind . $newJob->id);
        } catch (Exception $ex) {
            Log::log('error', 'Exception While Running Ediscovery Job ' . $ex->getMessage());
        }
        //----------------------------------------//
        $ediscoveryJob->delete();
        //----------------------------------------//
        $azureData = [
            "accountName" => $backupJobData->storage_account_name,
            "accountKey" => $storageAccountKey,
        ];
        //----------------------------------------//
        $searchQueryArr = [];
        $searchArr = json_decode($newJob->search_criteria);
        foreach ($searchArr as $item) {
            array_push($searchQueryArr, $this->convertCondition($backupJobData->backup_job_kind, $item->condition, $item->field, $item->value));
        }
        //----------------------------------------//
        if ($backupJobData->backup_job_kind == "exchange") {
            dispatch(new EDiscoveryExchangeBackground(auth()->user()->id, $newJob->id, $searchQueryArr, $azureData));
        } else if ($backupJobData->backup_job_kind == "onedrive") {
            dispatch(new EDiscoveryOnedriveBackground(auth()->user()->id, $newJob->id, $searchQueryArr, $azureData));
        } else if ($backupJobData->backup_job_kind == "teams") {
            dispatch(new EDiscoveryTeamsBackground(auth()->user()->id, $newJob->id, $searchQueryArr, $azureData));
        } else if ($backupJobData->backup_job_kind == "sharepoint") {
            dispatch(new EDiscoverySharepointBackground(auth()->user()->id, $newJob->id, $searchQueryArr, $azureData));
        }
        //----------------------------------------//
        return response()->json([], 200);
    }
    //------------------------------------------//
    private function getFieldValues($kind, $field)
    {
        $fields = config("app.search_criteria.$kind.fields");
        $fields = array_map(function ($value) use ($field) {
            return $value['code'] == $field ? $value : '';
        }, $fields);
        return explode(",", array_values(array_filter($fields))[0]["values"]);
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
    private function sortArray($arr)
    {
        usort($arr, function ($a, $b) {
            return strcmp(strtolower($a["name"]), strtolower($b["name"]));
        });
        $arr = array_values($arr);
        return $arr;
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
