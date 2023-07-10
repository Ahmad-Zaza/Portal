<?php

namespace App\Http\Controllers\Auth;

use App\Engine\GraphApi\ManagerGraph;
use App\Engine\MarketPlace\ManagerMarketPlace;
use App\Http\Controllers\Controller;
use App\Models\BacOrganization;
use App\Models\BacUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $_managerGraph;

    public function __construct()
    {
        $this->_managerGraph = new ManagerGraph();
        $this->_managerMarketPlace = new ManagerMarketPlace();
    }
    public function loginPage()
    {
        Auth::logout();
        return response()->view('auth.login');
    }

    public function marketplacePage(Request $request)
    {
        session(["marketplace_token" => $request->token]);
        return response()->view("auth.marketplace");
    }

    public function configureSubscription()
    {
        $state = $this->_managerGraph->getRandomState();
        $graphAuthenticationUrl = $this->_managerGraph->getAuthorizationCodeUrl($state);
        session(['graphState' => $state]);
        return redirect()->away($graphAuthenticationUrl);
    }
    /**
     * logout process.
     *
     * @return void
     */
    public function logout(Request $request)
    {
        Log::log("info", "Request Data " . json_encode($request->all()));
        if ($request->sid) {
            BacUser::where("microsoft365_session_guid", $request->sid)->update([
                "microsoft365_session_guid" => null,
            ]);
            return;
        }
        auth()->guard()->logout();
        Session::flush();
        return redirect()->away(ManagerGraph::officeLogout());
    }

    public function loginGraph()
    {
        $state = $this->_managerGraph->getRandomState();
        $graphAuthenticationUrl = $this->_managerGraph->getAuthorizationCodeUrl($state);
        session(['graphState' => $state]);
        return redirect()->away($graphAuthenticationUrl);
    }

    public function callbackGraph(Request $request)
    {
        $subscriptionInfo = null;
        if (session("marketplace_token")) {
            $subscriptionInfo = $this->_managerMarketPlace->resolveSubscription(session("marketplace_token"))["data"];
        }
        if (config("app.login_as_user_id")) {
            Auth::login(BacUser::find(config("app.login_as_user_id")));
            return redirect("home");
        }
        // Validate state
        try {
            $expectedState = session('graphState');
            $request->session()->forget('graphState');
            $providedState = $request->query('state');
            //TODO
            // if (!isset($expectedState)) {
            //     return redirect('/');
            // }
            // if (!isset($providedState) || $expectedState != $providedState) {
            //     Log::log('error', 'The provided auth state did not match the expected value ');
            //     return redirect('/')
            //         ->with('error', "Something went wrong");
            // }
            if ($request->error) {
                Log::log('error', 'Error From SSO ' . $request->error_description);
                return redirect('/login')
                    ->with('error', $request->error_description);
            }
            // Authorization code should be in the "code" query param
            $authCode = $request->query('code');
            $sessionState = $request->query('session_state');
            //Request an access token
            $data = $this->_managerGraph->getAccessToken($authCode, $providedState);
            $data["data"]->expires_in = Carbon::now()->addSeconds($data["data"]->expires_in - 120)->format("Y-m-d H:i:s");
            session()->put('microsoft365_session_data', json_encode($data["data"]));
            return $this->checkGraphToken($data["data"], $sessionState, $subscriptionInfo);
        } catch (Exception $e) {
            Log::log('error', 'Getting Graph Access Token ' . $e->getMessage());
            return redirect('/login')
                ->with('error', 'Error requesting access token');
        }
    }

    public function checkGraphToken($data, $sessionState, $subscriptionInfo)
    {
        try {
            $tokenData = $this->decodeJWTToken($data->access_token);
            //-----------------------------------//
            $appId = $tokenData->appid;
            $officeTenantId = $tokenData->tid;
            $userAuthId = $tokenData->oid;
            $userInfo = $this->_managerGraph->getUserInfo($userAuthId, $data->access_token)["data"];
            $email = $userInfo->mail;
            $upn = $userInfo->userPrincipalName;
            if ($appId != config("parameters.GRAPH_CLIENT_ID")) {
                return redirect('/login')
                    ->with('error', "Something went wrong");
            }
            //-----------------------------------//
            $organization = BacOrganization::where("microsoft365_tenant_guid", $officeTenantId)->first();
            //-----------------------------------//
            $companyName = "";
            $tenantName = "";
            if (optional($organization)->is_admin_consent) {
                $tenantInfo = $this->_managerGraph->getTenantInfo($organization->microsoft365_tenant_guid, $data->access_token)["data"]->value[0];
                session()->put('tenantInfoCity', $tenantInfo->city);
                session()->put('tenantInfoState', $tenantInfo->state);
                foreach ($tenantInfo->verifiedDomains as $domain) {
                    if ($domain->isInitial) {
                        $tenantName = $domain->name;
                        continue;
                    }
                }
                $companyName = $tenantInfo->displayName;
            }
            //-----------------------------------//
            $phone = preg_replace("/[^0-9]/", "", $userInfo->mobilePhone);
            if (optional($organization)->id) {
                $user = BacUser::whereRaw("lower(upn) = ?", [strtolower($upn)])->where("organization_id", optional($organization)->id)->first();
            } else if ($upn) {
                $user = BacUser::whereRaw("lower(upn) = ?", [strtolower($upn)])->first();
            } else if ($email) {
                $user = BacUser::whereRaw("lower(email) = ?", [strtolower($email)])->first();
            }
            if (!$user && !$organization) {
                $organizationData = [
                    'microsoft365_tenant_guid' => $officeTenantId,
                    'microsoft365_tenant_name' => $tenantName,
                    'company_name' => $companyName,
                    'registration_step' => 0,
                    'status' => "active",
                    'is_admin_consent' => 0,
                    'marketplace_users_count' => optional(optional($subscriptionInfo)->subscription)->quantity,
                ];
                if (optional($subscriptionInfo)->id) {
                    $organizationData["marketplace_subscription_guid"] = optional($subscriptionInfo)->id;
                }
                $organization = BacOrganization::create($organizationData);
                $user = BacUser::create([
                    "microsoft365_user_guid" => $userAuthId,
                    "microsoft365_session_guid" => $sessionState,
                    "organization_id" => $organization->id,
                    "first_name" => $tokenData->given_name,
                    "last_name" => optional($tokenData)->family_name,
                    "email" => $email,
                    "upn" => $upn,
                    "phone" => $phone,
                    "status" => "active",
                    "is_super_admin" => 1,
                    "role_id" => 1,
                    "registration_date" => Carbon::now(),
                    "timezone" => config("app.timezone"),
                ]);
                Auth::guard()->login($user);
                return redirect()->route("step0");
            } else if (!$user) {
                $user = BacUser::create([
                    "microsoft365_user_guid" => $userAuthId,
                    "microsoft365_session_guid" => $sessionState,
                    "organization_id" => $organization->id,
                    "first_name" => $tokenData->given_name,
                    "last_name" => optional($tokenData)->family_name,
                    "email" => $email,
                    "upn" => $upn,
                    "status" => "inactive",
                    "registration_date" => Carbon::now(),
                    "last_login_date" => Carbon::now(),
                    "is_super_admin" => 0,
                    "phone" => $phone,
                    "timezone" => config("app.timezone"),
                ]);
                Auth::guard()->login($user);
                return redirect()->route("home");
            } else if (!$user->last_login_date) {
                $user->registration_date = Carbon::now();
                $user->last_login_date = Carbon::now();
                $user->save();
            }
            $userData = [
                "microsoft365_user_guid" => $userAuthId,
                "first_name" => $tokenData->given_name,
                "upn" => $upn,
                "last_login_date" => Carbon::now(),
                "timezone" => config("app.timezone"),
                "microsoft365_session_guid" => $sessionState,
            ];
            if ($email) {
                $userData["email"] = $email;
            }
            if ($phone) {
                $userData["phone"] = $phone;
            }
            if (optional($tokenData)->family_name) {
                $userData["last_name"] = optional($tokenData)->family_name;
            }
            BacUser::where("id", $user->id)->update($userData);

            $organization->update([
                'marketplace_users_count' => optional(optional($subscriptionInfo)->subscription)->quantity,
            ]);
            if (optional($subscriptionInfo)->id) {
                $organization->update([
                    'marketplace_subscription_guid' => optional($subscriptionInfo)->id,
                ]);
            }
            Auth::guard()->login($user);
            return redirect()->route("home");
        } catch (Exception $e) {
            Log::log("error", "Error Logging after graph $e");
            return redirect()->route("home");
        }
    }

    public function callbackAdminConsent(Request $request)
    {
        //----------------
        if ($request->error) {
            return redirect('/step0')
                ->with('error', $request->error_description);
        }
        //----------------
        $user = Auth::user();
        $organization = $user->organization;
        //----------------
        if (session("microsoft365_session_data")) {
            $microsoft365_session_data = json_decode(session("microsoft365_session_data"));
            $tenantInfo = $this->_managerGraph->getTenantInfo($organization->microsoft365_tenant_guid, $microsoft365_session_data->access_token)["data"]->value[0];
        } else {
            $tenantInfo = $this->_managerGraph->getTenantInfo($organization->microsoft365_tenant_guid)["data"]->value[0];
        }
        session()->put('tenantInfoCity', $tenantInfo->city);
        session()->put('tenantInfoState', $tenantInfo->state);
        $tenantName = "";
        foreach ($tenantInfo->verifiedDomains as $domain) {
            if ($domain->isInitial) {
                $tenantName = $domain->name;
                continue;
            }
        }
        $companyName = $tenantInfo->displayName;
        //----------------
        $organization->is_admin_consent = 1;
        $organization->company_name = $companyName;
        $organization->microsoft365_tenant_name = $tenantName;
        $organization->save();
        //----------------
        Auth::guard()->login($user);
        if ($organization->registration_step == 0) {
            $organization->registration_step = 1;
            $organization->save();
            return redirect()->route("step1");
        }
        if ($organization->registration_step == 5) {
            return redirect()->route("home");
        }
        return redirect()->route("step" . $organization->registration_step);
    }

    private function decodeJWTToken($token)
    {
        $tokenParts = explode(".", $token);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

        return $jwtPayload;
    }
}
