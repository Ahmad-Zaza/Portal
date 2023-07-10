<?php

// created by fesal
// 07/04/2020

namespace App\Engine\GraphApi;

use App\Engine\Base\Manager;

class UrlsGraph
{
    public $graphUrl;

    public function __construct(){
        $this->graphUrl = config('parameters.GRAPH_API_URL');
    }
    //get url login partner center api
    public function getAuthorizationCodeUrl($state){
        $clientId = config('parameters.GRAPH_CLIENT_ID');
        $responseType = config('parameters.GRAPH_RESPONSE_TYPE');
        $responseMode = config('parameters.GRAPH_RESPONSE_MODE');
        $scope = config('parameters.GRAPH_SCOPE');
        $redirectUrl = config('parameters.GRAPH_REDIRECT_URL');

        return "https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=$clientId&response_type=$responseType&redirect_uri=$redirectUrl&response_mode=$responseMode&scope=$scope&state=$state";
    }

    public function getAdminConsentUrl($tenantId){
        $clientId = config('parameters.GRAPH_CLIENT_ID');
        $scope = config('parameters.GRAPH_ADMIN_SCOPE');
        $redirectUrl = config('parameters.GRAPH_ADMIN_REDIRECT_URL');
        //TODO
        $url = "https://login.microsoftonline.com/$tenantId/v2.0/adminconsent?client_id=$clientId&scope=$scope&redirect_uri=$redirectUrl&state=12345";
        return $url;
    }

    //get url login partner center api
    public static function getLogoutUrl(){
        $logoutUrl = config('parameters.GRAPH_LOGOUT_URL');
        return "https://login.microsoftonline.com/common/oauth2/v2.0/logout?post_logout_redirect_uri=$logoutUrl";
    }

    public function getAccessTokenUrl(){
        return "https://login.microsoftonline.com/common/oauth2/v2.0/token";
    }

    public function getUserInfoUrl($userGuid){
        return $this->graphUrl."/users/$userGuid";
    }
    public function getTenantInfoUrl(){
        return $this->graphUrl."/organization";
    }

    public function refreshAccessTokenUrl(){
        return "https://login.microsoftonline.com/common/oauth2/v2.0/token";
    }
}
