<?php

namespace App\Http\Controllers\Auth;

use App\Engine\Azure\ManagerAzure;
use App\Engine\GraphApi\ManagerGraph;
use App\Engine\MarketPlace\ManagerMarketPlace;
use App\Engine\PartnerCenterApi\ManagerPartnerCenter;
use App\Engine\Veeam\ManagerVeeam;
use App\Http\Controllers\Controller;
use App\Models\BacCountry;
use App\Models\BacOrganization;
use App\Models\BacUser;
use App\Models\BacVerificationCode;
use App\Models\Organization;
use App\Models\User;
use App\Models\VeeamServer;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StepController extends Controller
{

    public $_managerPartnerCenter;
    public $_managerVeeam;
    private $_managerAzure;
    private $_managerGraph;

    public function __construct()
    {
        $this->_managerPartnerCenter = new ManagerPartnerCenter();
        $this->_managerMarketPlace = new ManagerMarketPlace();
        $this->_managerVeeam = new ManagerVeeam();
        $this->_managerAzure = new ManagerAzure();
        $this->_managerGraph = new ManagerGraph();
        $this->middleware('auth')->except(['step0']);
    }

    public function step0()
    {
        $user = auth()->user();
        return response()->view('auth.step0', compact('user'));
    }

    public function saveStep0()
    {
        $user = auth()->user();
        $organization = BacOrganization::where("id", $user->organization_id)->first();
        return redirect()->away($this->_managerGraph->getAdminConsent($organization->microsoft365_tenant_guid));
    }

    public function step1()
    {
        $user = auth()->user();
        return response()->view('auth.step1', compact('user'));
    }

    public function saveStep1(Request $request)
    {
        $user = auth()->user();
        //----------------------------------------------------//
        $user = BacUser::where("id", $user->id)->first();
        if ($request->Phone) {
            $user->phone = $request->Phone;
        }

        if ($request->email) {
            $user->email = $request->email;
        }

        if ($request->last_name) {
            $user->last_name = $request->last_name;
        }

        $user->save();
        //----------------------------------------------------//
        $organization = BacOrganization::where("id", $user->organization_id)->first();
        if ($request->input('subscription_type') == "1") {
            $code = BacVerificationCode::with("period")->where([
                "code" => $request->input('code'),
                "is_active" => 0,
                "organization_id" => null,
            ])->first();
            if (!$code) {
                return redirect()->back()->withErrors(["code" => "Wrong Verification Code"]);
            }
            $period = $code->period->code;
            $code->organization_id = $organization->id;
            $code->activation_date = date('Y-m-d H:i:s');
            $code->expiration_date = date('Y-m-d H:i:s', strtotime($period));
            $code->is_active = 1;
            $code->save();

            $organization->total_license_allowed = $code->license_count;
            $organization->license_expiry_date = $code->expiration_date;
            $organization->is_free_trial = 0;
        } else {
            $organization->total_license_allowed = config('parameters.TRIAL_LICENSE_COUNT');
            $organization->license_expiry_date = Carbon::now()->add(config('parameters.TRIAL_EXPIRY_DAYS'), 'days');
            $organization->is_free_trial = 1;
        }
        //----------------------------------------------------//
        $organization->registration_step = 2;
        $organization->save();
        //----------------------------------------------------//
        return redirect()->route("step2");
    }

    public function step2()
    {
        $user = auth()->user();
        $countries = BacCountry::all();
        $arr = array('user' => $user, "countries" => $countries);
        return response()->view("auth.step2", $arr);
    }

    public function newTenant(Request $request)
    {
        $user = auth()->user();
        $organization = BacOrganization::where("id", $user->organization->id)->first();
        //-----------------------------------------------//
        if ($request->get('Country') == -1) {
            $request["Country"] = "SA";
            $request["tenantType"] = "newTenant";
        }
        //-----------------------------------------------//
        Validator::make($request->all(), [
            'Domain' => ['required', 'string', 'max:255'],
            'City' => ['required', 'string', 'max:255'],
            'State' => ['required', 'string', 'max:255'],
            'PostalCode' => ['required', 'numeric'],
            'Country' => ['required'],
        ])->validate();
        //-----------------------------------------------//
        $data = $this->_managerPartnerCenter->createCustomer($request->all(), $user);
        //-----------------------------------------------------------//
        $organization->azure_tenant_guid = $data["data"]->companyProfile->tenantId;
        $organization->azure_tenant_name = $data["data"]->companyProfile->domain;
        if (optional($data["data"])->customDomains) {
            foreach (optional($data["data"])->customDomains as $domain) {
                if (substr_count($domain, ".", 0) == 2 && substr_count($domain, ".onmicrosoft.com", 0) > 0) {
                    $organization->azure_tenant_name = $domain;
                    continue;
                }
            }
        }
        $organization->registration_step = 3;
        $organization->save();
        //-----------------------------------------------------------//
        if ($data["status"] == 400) {
            Log::log('error', 'Exception while creating tenant  ' . json_encode($data["data"]));
            $error = optional($data["data"])->description;
            if (optional($data["data"])->details) {
                $error .= implode(' ', optional($data["data"])->details);
            }
            if (optional($data["data"])->errorName) {
                $error .= " (" . optional($data["data"])->errorName;
                if (optional($data["data"])->parameters) {
                    $error .= " - " . optional($data["data"])->parameters->property_name;
                }

                $error .= ")";
            }
            return redirect()->back()->with('errors_api', $error);
        }
        if ($data["status"] == 409) {
            return redirect()->back()->with('errors_api', $data["data"]->description);
        }
        return redirect()->route("step3");
    }

    public function checkTenant(Request $request)
    {
        $user = auth()->user();
        $organization = BacOrganization::where("id", $user->organization->id)->first();
        //-----------
        Validator::make($request->all(), [
            'tenantId' => ['required', 'string', 'max:40'],
        ]);
        $tenantId = $request->input("tenantId");
        //-----------
        try {
            $data = $this->_managerPartnerCenter->getCustomersById($tenantId);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(["tenantId" => "Wrong Tenant Id"]);
        }
        $organization->azure_tenant_guid = $data["data"]->companyProfile->tenantId;
        $organization->azure_tenant_name = $data["data"]->companyProfile->domain;
        if (optional($data["data"])->customDomains) {
            foreach (optional($data["data"])->customDomains as $domain) {
                if (substr_count($domain, ".", 0) == 2 && substr_count($domain, ".onmicrosoft.com", 0) > 0) {
                    $organization->azure_tenant_name = $domain;
                    continue;
                }
            }
        }
        $organization->registration_step = 3;
        $organization->save();
        //-----------
        if ($data["status"] == 400 || $data["status"] == 404) {
            return redirect()->back()->with('errors_api_check', $data["data"]->description);
        }
        return redirect()->route("step3");
    }

    public function checkDomain($domain)
    {
        $data = $this->_managerPartnerCenter->checkDomain($domain);
        return response()->json($data, 200);
    }

    public function step3()
    {
        return response()->view('auth.step3');
    }

    public function veeamAuth(Request $request) //save step 3
    {
        set_time_limit(60 * 4);
        $user = auth()->user();
        $organization = $user->organization;
        $serverInfo = VeeamServer::getLessUsedServer($organization->microsoft365_tenant_guid);
        try {
            $data = [];
            try {
                if (!$organization->veeam_organization_guid) {
                    $data = $this->_managerVeeam->createOrganizationModernApp($serverInfo, $request->deviceCode, $request->application_name);
                    //----- Check Organization Name with Microsoft Guid
                    if ($data["data"]->name != $organization->microsoft365_tenant_name) {
                        $this->_managerVeeam->deleteOrganization($serverInfo, $data["data"]->id);
                        Log::log('error', "Organization Name is different from microsoft tenant guid");
                        return redirect()->back()
                            ->with('errors_api_check', __("variables.errors.veeam_auth_organization_name_error"));
                    }
                    //-----------------------
                    $organization->veeam_organization_name = $data["data"]->name;
                    $organization->veeam_organization_guid = $data["data"]->id;
                    $organization->veeam_aad_authentication_guid = $data["data"]->exchangeOnlineSettings->applicationId;
                    $organization->server_id = $serverInfo->id;
                    $organization->save();
                }
            } catch (\Exception $ex) {
                Log::log('error', 'Exception while creating organization  ' . $ex->getMessage());
                return redirect()->back()->with('errors_api_check', $ex->getMessage());
            }
            //--- Check Customer Agreement
            $agr = $this->_managerPartnerCenter->checkCustomerAgreement($user->organization->azure_tenant_guid)['data'];
            //------------------------------//
            if ($agr->totalCount == 0) {
                $agr = $this->_managerPartnerCenter->confirmCustomersAgreement($user);
            }
            //-----------------------
            if (!$organization->azure_subscription_guid) {
                $cartData = $this->_managerPartnerCenter->addCartToCustomer($user->organization->azure_tenant_guid);
                $cartCheck = $this->_managerPartnerCenter->cartCheckOut($user->organization->azure_tenant_guid, $cartData['data']->id);
                //-----------------------
                $organization->azure_subscription_guid = $cartCheck["data"]->orders[0]->lineItems[0]->subscriptionId;
            }
            $organization->registration_step = 4;
            $organization->save();
            //------------------------------------//
            session()->put('registerTime', time() + 1000);
            session()->put('resourceCreated', 0);
            //----------------------
            return redirect()->route('step4');
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while creating organization ' . $ex->getMessage());
            return redirect()->back()->with('errors_api_check', "Error Occurred, Please contact system administrator");
        }
    }

    public function step4()
    {
        if (!session("registerTime")) {
            session()->put('registerTime', time() + 1000);
            session()->put('resourceCreated', 0);
        }
        return response()->view('auth.step4');
    }

    public function finishRegistration()
    {
        return response()->view('auth.finish');
    }

    public function lastStep()
    {
        return response()->view('auth.last_step');
    }

    public function getProgressVal()
    {
        if (!session('registerTime')) {
            return;
        }
        try {
            $res = 100;
            $user = auth()->user();
            $organization = BacOrganization::where("id", $user->organization_id)->first();
            if (session('registerTime')) {
                $percent = (time() - session('registerTime') + 1000) / 5;
                if ($percent < 100) {
                    $res = $percent;
                } else {
                    session()->forget('registerTime');
                }
            }
            if ($res >= 70 && session('resourceCreated') == 0) {
                try {
                    //---- Get Subscription azureEntitlements (Azure Plan) Type
                    $azureEntitlements = $this->_managerPartnerCenter->getSubscriptionAzureEntitlements($user->organization->azure_tenant_guid, $user->organization->azure_subscription_guid)['data'];
                    if ($azureEntitlements->totalCount > 0) {
                        $organization->azure_subscription_guid = $azureEntitlements->items[0]->id;
                        $organization->save();
                        //------------
                        $this->_managerAzure->renameSubscription($azureEntitlements->items[0]->id);
                        //------------
                    }
                } catch (\Exception $ex) {
                    Log::log('error', 'Exception While Getting Subscription azureEntitlements  ' . $ex->getMessage());
                }
                //--------------------------------------------------------------------------------//
                try {
                    $this->_managerAzure->registerAzureResourceProvider($organization->azure_subscription_guid);
                } catch (\Exception $ex) {
                    Log::log('error', 'Exception While Registering Azure Resource Provider  ' . $ex->getMessage());
                }
                //--------------------------------------------------------------------------------//
                if (!$organization->azure_resource_group) {
                    $location = VeeamServer::where('id', $organization->server_id)->first()->region;
                    $this->_managerAzure->createAzureResourceGroup($organization->azure_subscription_guid, "BACTOPUS_RG", $location);
                    $organization->azure_resource_group = "BACTOPUS_RG";
                }
                $organization->save();
                //--------------------------------------------------------------------------------//
                //---- Activate Subscription
                try {
                    if ($organization->marketplace_subscription_guid) {
                        $subscriptionData = $this->_managerMarketPlace->getSubscription($organization->marketplace_subscription_guid)["data"];
                        if ($subscriptionData->saasSubscriptionStatus == "PendingFulfillmentStart")
                            $this->_managerMarketPlace->activateSubscription($organization->marketplace_subscription_guid, $subscriptionData->planId, $subscriptionData->quantity);
                    }
                } catch (Exception $e) {
                    Log::log("error", "error sass subscription $e");
                }
                //--------------------------------------------------------------------------------//
                session()->put('resourceCreated', 1);
            }
            if ($res == 100 && $organization->azure_resource_group) {
                $organization->registration_step = 5;
                $organization->save();
            }

            return $res;
        } catch (\Exception $ex) {
            $organization->registration_step = 4;
            $organization->save();
            session()->put('resourceCreated', 0);
            Log::log('error', 'Exception while finalizing configuration ' . $ex->getMessage());
        }
    }

    public function generateDeviceCode(Request $request)
    {
        //-------------------------------------------//
        $organization = auth()->user()->organization;
        //-------------------------------------------//
        try {
            $serverInfo = VeeamServer::getLessUsedServer($organization->microsoft365_tenant_guid);
            $deviceCode = $this->_managerVeeam->generateDeviceCode($serverInfo)["data"];
            //-------------------------------------------//
            return response()->json([
                "userCode" => $deviceCode->userCode,
            ]);
        } catch (Exception $e) {
            Log::log("error", "Error while generating device code " . $e->getMessage());
            return response()->json(["message" => __("variables.errors.generating_device_code")], 500);
        }
    }
}
