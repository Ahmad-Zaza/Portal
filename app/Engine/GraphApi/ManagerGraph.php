<?php

namespace App\Engine\GraphApi;

use App\Engine\Base\CallerApi;
use Illuminate\Support\Facades\Log;

class ManagerGraph
{
    private $_urlManager;
    private $_client_id;
    private $_tenant_id;
    private $_secret;
    private $_grant_type;
    private $_resource;
    private $_refresh_token;
    private $_caller;

    public function __construct()
    {
        $this->_urlManager = new UrlsGraph();
        $this->_caller = new CallerApi();
    }

    public function getAuthorizationCodeUrl($state)
    {
        return $this->_urlManager->getAuthorizationCodeUrl($state);
    }

    public function getAccessToken($code, $state)
    {
        $url = $this->_urlManager->getAccessTokenUrl();
        //---------------------------------------------//
        $clientId = config('parameters.GRAPH_CLIENT_ID');
        $secret = config('parameters.GRAPH_SECRET');
        $scope = config('parameters.GRAPH_SCOPE');
        $redirectUrl = config('parameters.GRAPH_REDIRECT_URL');
        //---------------------------------------------//
        $params = [
            "client_id" => $clientId,
            "scope" => $scope,
            "code" => $code,
            "state" => $state,
            "redirect_uri" => $redirectUrl,
            "grant_type" => "authorization_code",
            "client_secret" => $secret,
        ];
        $data = $this->_caller->post($url, $params, []);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Getting Graph Access Token   ' . json_encode($data));
            throw new \Exception('Error Getting Graph Access Token');
        }
    }

    public function getAccessTokenUsingClientCredentials($microsoft365TenantId)
    {
        $url = "https://login.microsoftonline.com/$microsoft365TenantId/oauth2/v2.0/token";
        //---------------------------------------------//
        $clientId = config('parameters.GRAPH_CLIENT_ID');
        $secret = config('parameters.GRAPH_SECRET');
        $scope = config('parameters.GRAPH_ADMIN_SCOPE');
        //---------------------------------------------//
        $params = [
            "client_id" => $clientId,
            "scope" => "https://graph.microsoft.com/.default",
            "grant_type" => "client_credentials",
            "client_secret" => $secret,
        ];
        $data = $this->_caller->post($url, $params, []);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Getting Graph Access Token   ' . json_encode($data));
            throw new \Exception('Error Getting Graph Access Token');
        }
    }

    public function getAdminConsent($tenantId)
    {
        $url = $this->_urlManager->getAdminConsentUrl($tenantId);
        //---------------------------------------------//
        return $url;
    }

    public function getUserInfo($userGuid, $token)
    {
        $url = $this->_urlManager->getUserInfoUrl($userGuid);
        //---------------------------------------------//
        $headers = [
            "Authorization" => "Bearer $token",
            "Content-Type" => "",
        ];
        $data = $this->_caller->get($url, [], $headers);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Getting Graph User Info ' . json_encode($data));
            throw new \Exception('Error Getting Graph User Info');
        }
    }

    public function getTenantInfo($microsoft365TenantId,$token="")
    {
        if(!$token)
            $token = $this->getAccessTokenUsingClientCredentials($microsoft365TenantId)["data"]->access_token;
        $url = $this->_urlManager->getTenantInfoUrl();
        //---------------------------------------------//
        $headers = [
            "Authorization" => "Bearer $token",
            "Content-Type" => "",
        ];
        $data = $this->_caller->get($url, [], $headers);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Getting Graph Tenant Info ' . json_encode($data));
            throw new \Exception('Error Getting Graph Tenant Info');
        }
    }

    public static function officeLogout()
    {
        return UrlsGraph::getLogoutUrl();
    }

    public function refreshAccessToken($refresh_token)
    {
        $url = $this->_urlManager->refreshAccessTokenUrl();
        //---------------------------------------------//
        $clientId = config('parameters.GRAPH_CLIENT_ID');
        $secret = config('parameters.GRAPH_SECRET');
        $scope = config('parameters.GRAPH_SCOPE');
        //---------------------------------------------//
        $params = [
            "client_id" => $clientId,
            "scope" => $scope,
            "refresh_token" => $refresh_token,
            "grant_type" => "refresh_token",
            "client_secret" => $secret,
        ];
        $data = $this->_caller->post($url, $params, []);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while Getting Graph Access Token   ' . json_encode($data));
            throw new \Exception('Error Getting Graph Access Token');
        }
    }

    public function getRandomState($length = 32)
    {
        // Converting bytes to hex will always double length. Hence, we can reduce
        // the amount of bytes by half to produce the correct length.
        return bin2hex(random_bytes($length / 2));
    }
}
