<?php

namespace App\Http\Middleware;

use App\Engine\GraphApi\ManagerGraph;
use App\Models\BacOrganization;
use App\Models\BacUser;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SSOUserAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->microsoft365_session_guid) {
            return redirect()->route("login");
        }
        if(!auth()->user()->bac_role && auth()->user()->organization->registration_step == 5){
            return redirect()->route("login")->with('error', __("variables.messages.login_user_without_role"));
        }

        if(auth()->user()->status == "inactive"){
            return redirect()->route("login")->with('error', __("variables.messages.login_inactive_user"));
        }
        if(auth()->user()->organization->status != "active"){
            return redirect()->route("login")->with('error', __("variables.messages.login_inactive_organization"));
        }
        if(!auth()->user()->organization->is_admin_consent && auth()->user()->organization->registration_step != 0){
            return redirect("admin-consent-callback");
        }
        // $microsoft365_session_data = json_decode(session("microsoft365_session_data"));
        // if($microsoft365_session_data->expires_in < Carbon::now()){
        //     return redirect()->route("login")->with('error', __("variables.messages.login_microsoft_session_expired"));
        // }
        // $managerGraph = new ManagerGraph();
        // $data = $managerGraph->getUserInfo(auth()->user()->microsoft365_user_guid,"");
        // $user = BacUser::find(auth()->user()->id);
        // if ($user->role_id) {
        //     $user->last_login_date = Carbon::now();
        //     $user->save();
        // }
        return $next($request);
    }
}
