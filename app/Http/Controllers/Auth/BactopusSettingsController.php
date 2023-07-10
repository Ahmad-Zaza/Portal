<?php

namespace App\Http\Controllers\Auth;

use App\Engine\Base\Manager;
use App\Engine\Veeam\ManagerVeeam;
use App\Http\Controllers\Controller;
use App\Models\BacNotifications;
use App\Models\BacOrganizationNotifications;
use App\Models\BacTimezone;
use App\Models\BacUser;
use App\Models\BacVerificationCode;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BactopusSettingsController extends Controller
{
    private $_managerVeeam;

    //-------------------------------------------------------//
    public function __construct()
    {
        $this->_managerVeeam = new ManagerVeeam();
    }
    //-------------------------------------------------------//
    public function index()
    {
        //license management
        $user = auth()->user();
        $organization = $user->organization;
        $arr['license_allowed'] = $organization->total_license_allowed;
        $arr['used_license'] = $organization->veeam_licensed_users + $user->organization->veeam_trial_users;
        //notification
        $data = BacNotifications::with([
            'organizationNotifications' => function ($query) use ($organization) {
                $query->where('organization_id', '=', $organization->id);
            },
        ])->get();
        //groups
        try {
            $arr['securityGroups'] = [];
            //------------------------------//
            $securityGroups = $this->_managerVeeam->getOrganizationGroups($organization->veeam_organization_guid)['data']->results;
            usort($securityGroups, function ($a, $b) {
                return strcmp(strtolower($a->displayName), strtolower($b->displayName));
            });
            $securityGroups = array_values($securityGroups);
            //------------------------------//
            foreach ($securityGroups as $group) {
                if ($group->type == 'Security') {
                    array_push($arr['securityGroups'], $group);
                }
            }
            //------------------------------//
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Security Groups ' . $ex->getMessage());
            $arr['securityGroups'] = [];
        }
        //timezones
        $timezones = BacTimezone::all();
        return response()->view('bactopus-settings.main', compact('arr', 'data', 'timezones'));
    }
    //-------------------------------------------------------//
    public function update(Request $request, $id)
    {
        $user = BacUser::findOrFail(auth()->user()->id);
        $user->timezone = $request->timezone;
        $user->save();
        return redirect()->route('bactopus-settings');
    }
    //-------------------------------------------------------//
    public function getUserVerificationCodes()
    {
        $user = auth()->user();
        $data = BacVerificationCode::where('organization_id', $user->organization->id)->get();
        return $data;
    }
    //-------------------------------------------------------//
    public function saveUserVerificationCode(Request $request)
    {
        $user = auth()->user();
        $organization = $user->organization;
        //---------------------------------------//
        //--- Check if Code Exist
        $code = BacVerificationCode::with("period")->where([
            'code' => $request->code,
            'is_active' => 0,
            'organization_id' => null,
        ])->first();

        if (!$code) {
            return response()->json(['message' => trans('variables.errors.wrong_verification_code')], 500);
        }

        //---------------------------------------//
        $code->activation_date = Carbon::now()->format('Y-m-d H:i:s');
        $code->expiration_date = date('Y-m-d H:i:s', strtotime($code->period->code));
        $code->organization_id = $organization->id;
        $code->is_active = 1;
        $code->save();
        //---------------------------------------//
        //-- Calculate New Expiry Date
        $licenseSum = 0;
        $codes = BacVerificationCode::where('organization_id', $organization->id)
            ->where('is_active', '1')
            ->where('expiration_date', '>', Carbon::now())
            ->get();
        $minExpiry = $codes[0]->expiration_date;
        foreach ($codes as $code) {
            $licenseSum += $code->license_count;
            if ($minExpiry > $code->expiration_date) {
                $minExpiry = $code->expiration_date;
            }
        }
        if ($licenseSum) {
            $organization->total_license_allowed = $licenseSum;
            $organization->license_expiry_date = $minExpiry;
            $organization->is_free_trial = 0;
            $organization->save();
        }
        //--- If New License Count Less Than Current
        if ($this->checkBackedUsers($user)) {
            $jobs = $this->_managerVeeam->getOrganizationBackUpJobs($organization->veeam_organization_guid)['data'];
            foreach ($jobs as $job) {
                if ($job->isEnabled) {
                    $this->_managerVeeam->manageVeeamJobs($job->id, 'disable');
                }
            }
        }
        //--------------------------------------------//
        return response()->json(['message' => []], 200);
    }
    //-------------------------------------------------------//
    private function checkBackedUsers($user)
    {
        $allUsersIds = [];
        //----------------------------------------//
        $organization = auth()->user()->organization;
        $jobs = $this->_managerVeeam->getOrganizationBackUpJobs($organization->veeam_organization_guid)['data'];
        $backedUsersCount = 0;
        $allUsers = false;
        foreach ($jobs as $job) {
            if ($job->isEnabled == false) {
                continue;
            }
            $jobItems = $this->_managerVeeam->getVeeamJobSelectedItems($job->id)['data'];
            foreach ($jobItems as $item) {
                if (strtolower($item->type) == "user" && $item->user->type == "User" && !strstr($item->user->name, 'DiscoverySearchMailbox')) {
                    if (!in_array($item->user->id, $allUsersIds)) {
                        array_push($allUsersIds, $item->user->id);
                        $backedUsersCount++;
                    }
                } else if (strtolower($item->type) == "partialorganization") {
                    if ($item->mailbox == true || $item->archiveMailbox || $item->oneDrive) {
                        $allUsers = true;
                        try {
                            $data = $this->_managerVeeam->getOrganizationUsers($organization->veeam_organization_guid)['data']->results;
                            $backedUsersCount = 0;
                            foreach ($data as $i) {
                                if (strtolower($i->type) == "user" && strstr($i->name, 'DiscoverySearchMailbox') === false) {
                                    $backedUsersCount++;
                                }
                            }
                        } catch (Exception $e) {
                            Log::log("error", "While Getting Organization Users " . $e->getMessage());
                        }
                        break;
                    }
                }
            }
            if ($allUsers) {
                break;
            }
        }
        //----------------------------------------//
        return ($user->organization->total_license_allowed < $backedUsersCount);
    }
    //-------------------------------------------------------//
    public function authUserVeeam(Request $request)
    {
        $user = auth()->user();
        $organizationId = $user->organization->veeam_organization_guid;
        try {
            $data = $this->_managerVeeam->setOrganizationModernApp($request, $organizationId);
            if ($data["status"] == 500) {
                return response()->json(["message" => $data["data"]->message], 500);
            } else {
                return response()->json(["message" => trans('variables.success.user_veeam_auth')], 200);
            }
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while creating organization    ' . $ex->getMessage());
            return response()->json(["message" => $ex], 500);
        }
    }
    //-------------------------------------------------------//
    public function getBackupApplications(Request $request)
    {
        $user = auth()->user();
        $organizationId = $user->organization->veeam_organization_guid;
        try {
            $data = [];
            $azureApplications = $this->_managerVeeam->getAzureApplications($organizationId)['data']->results;
            $backupApplications = $this->_managerVeeam->getBackupApplications($organizationId)['data']->results;
            foreach ($azureApplications as $item) {
                $item->is_active = false;
                foreach ($backupApplications as $item1) {
                    if ($item->applicationId == $item1->applicationId) {
                        $item->is_active = true;
                    }
                }
                if($item->displayName == "Bactopus Authentication Application - Performance"){
                    $data[] = $item;
                }
            }
            return $data;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Backup Applications ' . $ex->getMessage());
            return [];
        }
    }
    //-------------------------------------------------------//
    public function saveBackupApplication(Request $request)
    {
        //-------------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required',
            'applicationsCount' => 'required',
        ]);
        //-------------------------------------------//
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 500);
        }
        //-------------------------------------------//
        set_time_limit(60 * 4);
        $user = auth()->user();
        try {
            $organizationId = $user->organization->veeam_organization_guid;
            $azureApplications = $this->_managerVeeam->addAzureApplications($organizationId, $request->backupApplicationName, $request->deviceCode, $request->applicationsCount)["data"]->results;
            $azureApplicationsIds = array_column($azureApplications,"applicationId");
            $applications = [];
            foreach ($azureApplications as $application) {
                $temp["application"] = [
                    "applicationId" => $application->applicationId,
                    "displayName" => $application->displayName,
                ];
                $temp["applicationCertificate"] = config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE");
                $temp["applicationCertificatePassword"] = config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE_PASSWORD");
                $applications[] = $temp;
            }
            //--- Set All already activated applications
            $backupApplications = $this->_managerVeeam->getBackupApplications($organizationId)["data"]->results;
            foreach($backupApplications as $application){
                if(!in_array($application->applicationId,$azureApplications)){
                    $temp["application"] = [
                        "applicationId" => $application->applicationId,
                        "displayName" => $application->displayName,
                    ];
                    $temp["applicationCertificate"] = config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE");
                    $temp["applicationCertificatePassword"] = config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE_PASSWORD");
                    $applications[] = $temp;
                }
            }
            //------------------------//
            $this->_managerVeeam->addBackupApplications($organizationId, $applications);
            return response()->json(["message" => __("variables.success.save_backup_account")], 200);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Adding Backup Account ' . $ex);
            return response()->json(['message' => __("variables.errors.save_backup_account")], 500);
        }
    }
    //-------------------------------------------------------//
    public function activateBackupApplication(Request $request)
    {
        set_time_limit(60 * 4);
        //-------------------------------------------//
        $validator = Validator::make($request->all(), [
            'applications' => 'required',
            'deviceCode' => 'required',
            'type' => 'required',
        ]);
        //-------------------------------------------//
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 500);
        }
        //-------------------------------------------//
        $selectedApplications = $request->applications;
        $selectedApplicationsIds = array_column($selectedApplications,"applicationId");
        //-------------------------------------------//
        $user = auth()->user();
        try {
            $organizationId = $user->organization->veeam_organization_guid;
            $applications = [];
            if($request->type == 'A'){
                foreach ($selectedApplications as $application) {
                    $temp["application"] = [
                        "applicationId" => $application["applicationId"],
                        "displayName" => $application["applicationName"],
                    ];
                    $temp["applicationCertificate"] = config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE");
                    $temp["applicationCertificatePassword"] = config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE_PASSWORD");
                    $applications[] = $temp;
                }
                //--- Set All already activated applications
                $azureApplications = $this->_managerVeeam->getAzureApplications($organizationId)["data"]->results;
                $azureApplicationsIds = array_column($azureApplications,"applicationId");
                $backupApplications = $this->_managerVeeam->getBackupApplications($organizationId)["data"]->results;
                foreach($backupApplications as $application){
                    if(!in_array($application->applicationId,$selectedApplicationsIds) && in_array($application->applicationId,$azureApplicationsIds)){
                        $temp["application"] = [
                            "applicationId" => $application->applicationId,
                            "displayName" => $application->displayName,
                        ];
                        $temp["applicationCertificate"] = config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE");
                        $temp["applicationCertificatePassword"] = config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE_PASSWORD");
                        $applications[] = $temp;
                    }
                }
                //------------------------//
                $this->_managerVeeam->addBackupApplications($organizationId, $applications);
            } else if($request->type == "D"){
                $backupApplications = $this->_managerVeeam->getBackupApplications($organizationId)["data"]->results;
                foreach($backupApplications as $application){
                    if(!in_array($application->applicationId,$selectedApplicationsIds)){
                        $temp["application"] = [
                            "applicationId" => $application->applicationId,
                            "displayName" => $application->displayName,
                        ];
                        $temp["applicationCertificate"] = config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE");
                        $temp["applicationCertificatePassword"] = config("parameters.VEEAM_AAD_AUTHENTICATION_CERTIFICATE_PASSWORD");
                        $applications[] = $temp;
                    }
                }
                $this->_managerVeeam->addBackupApplications($organizationId, $applications);
            }
            return response()->json(["message" => __("variables.success.save_backup_account")], 200);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Activating Backup Account ' . $ex->getMessage());
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
    //-------------------------------------------------------//
    public function getTimezoneList()
    {
        try {
            $data = Manager::getTimezoneList();
            return response()->json($data, 200);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Timezone List ' . $ex->getMessage());
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
    //-------------------------------------------------------//
    public function saveUserNotifications(Request $request)
    {
        $user = auth()->user();
        $organization = $user->organization;
        $dataArr = json_decode($request->dataArr);
        $organization->notifications_users = $request->sendToEmail;
        $organization->save();

        foreach ($dataArr as $item) {
            $not = BacOrganizationNotifications::where([
                'organization_id' => $organization->id,
                'notification_id' => $item->id,
            ])->first();
            if (!$not) {
                $not = new BacOrganizationNotifications();
            }
            $not->organization_id = $user->organization->id;
            $not->notification_id = $item->id;
            $not->is_teams = $item->teams;
            $not->is_email = $item->email;
            $not->save();
        }
        return response()->json([], 200);
    }
    //-------------------------------------------------------//
}
