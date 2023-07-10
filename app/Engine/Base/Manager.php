<?php

//  general functions in system

namespace App\Engine\Base;

use App\Engine\Veeam\ManagerVeeam;
use App\Models\BacUser;
use App\Models\VeeamServer;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class Manager
{

    public static function generateVeeamToken()
    {
        $user = auth()->user();
        if (!$user) {
            $user = BacUser::find(session("user_id"));
        }
        if (!$user) {
            throw new Exception("Error While Trying to login on Veeam Server - No User");
        }
        if (!session("veeam_server_url") || !session("veeam_access_token") || session("veeam_access_token_expire") < Carbon::now()) {
            $server = VeeamServer::where("id", $user->organization->server_id)->first();
            $managerVeeam = new ManagerVeeam();
            $data = $managerVeeam->login($server);
            session()->put("veeam_server_url", $server->url);
            session()->put("veeam_access_token", $data->access_token);
            session()->put("veeam_access_token_expire", Carbon::now()->addSeconds($data->expires_in - 120));
        }
    }

    public static function getVeeamServerToken()
    {
        if (!session("veeam_server_url") || !session("veeam_access_token") || session("veeam_access_token_expire") < Carbon::now()) {
            Manager::generateVeeamToken();
        }
        return session("veeam_access_token");
    }

    public static function getVeeamServerUrl()
    {
        if (!session("veeam_server_url") || !session("veeam_access_token") || session("veeam_access_token_expire") < Carbon::now()) {
            Manager::generateVeeamToken();
        }
        return session("veeam_server_url");
    }

    public static function getUserTimezone()
    {
        $url = "http://worldtimeapi.org/api/ip";
        $caller = new CallerApi();
        $response = $caller->get($url, []);
        $status = $response['status'];
        if ($status == 200 || $status == 201 || $status == 202 || $status == 204) {
            return $response['data']->timezone;
        } else {
            Log::log('error', 'Exception While Getting User Timezone ' . json_encode($response));
            return '';
        }
    }

    public static function getTimezoneList()
    {
        $url = "http://worldtimeapi.org/api/timezones";
        $caller = new CallerApi();
        $response = $caller->get($url, []);
        $status = $response['status'];
        if ($status == 200 || $status == 201 || $status == 202 || $status == 204) {
            return $response['data'];
        } else {
            Log::log('error', 'Exception While Getting User Timezone ' . json_encode($response));
            return [];
        }
    }
}
