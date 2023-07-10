<?php

// Get Veeam Settings
// represents a group of Veeam constants

namespace App\Engine\Veeam;

use App\Models\VeeamServer;
use Illuminate\Support\Facades\Log;

class EnumVeeam
{
    public static function URL($userId = '')
    {
        try {
            if (!$userId) {
                $userId = auth()->id();
            }
            return VeeamServer::getVeeamSettings($userId)->veeam_url;
        } catch (\Exception $ex) {
            Log::log("error", "Get Veeam Server Url ".$ex);
            return -1;
        }
    }

    public static function TOKEN($userId = '')
    {
        try {
            if (!$userId) {
                $userId = auth()->id();
            }
            return optional(VeeamServer::getVeeamSettingsWithToken($userId))->access_token;
        } catch (\Exception $ex) {
            Log::log("error", "Get Veeam Server Token ".$ex);
            return -1;
        }
    }

    public static function USERNAME($userId='')
    {
        try {
            if (!$userId) {
                $userId = auth()->id();
            }
            return VeeamServer::getVeeamSettings($userId)->veeam_username;
        } catch (\Exception $ex) {
            return -1;
        }

    }

    public static function PASSWORD($userId='')
    {
        try {
            if (!$userId) {
                $userId = auth()->id();
            }
            return VeeamServer::getVeeamSettings($userId)->veeam_password;
        } catch (\Exception $ex) {
            return -1;
        }

    }
}
