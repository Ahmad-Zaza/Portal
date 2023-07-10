<?php

namespace App\Engine\MarketPlace;

use App\Engine\Base\CallerApi;
use Exception;
use Illuminate\Support\Facades\Log;

class ManagerMarketPlace
{
    public $_urlManager;
    private $_caller;

    public function __construct()
    {
        $this->_urlManager = new UrlsMarketPlace();
        $this->_caller = new CallerApi();
    }
    //-----------------------------------------------------------//
    public function login()
    {
        $url = $this->_urlManager->loginUrl(config("parameters.MARKET_PLACE_TENANT_ID"));
        $params = [
            'grant_type' => config("parameters.MARKET_PLACE_GRANT_TYPE"),
            'client_id' => config("parameters.MARKET_PLACE_CLIENT_ID"),
            'client_secret' => config("parameters.MARKET_PLACE_SECRET"),
            'resource' => config("parameters.MARKET_PLACE_RESOURCE"),
        ];
        //call request
        try {
            $data = $this->_caller->post($url, $params, []);
        } catch (Exception $e) {
            Log::log("error", "Error Logging into Market Place Sass " . $e->getMessage());
            throw new Exception("Error Logging into Market Place Sass");
        }
        return $data["data"]->access_token;
    }
    //-----------------------------------------------------------//
    public function getSubscription($subscriptionId)
    {
        $token = "Bearer " . $this->login();

        $url = $this->_urlManager->getSubscriptionUrl($subscriptionId);
        $header = [
            'Content-Type' => "application/json",
            'Authorization' => $token,
        ];
        $body = '';
        $data = $this->_caller->get($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting sass subscription ' . json_encode($data));
            throw new \Exception('Error Getting Sass Subscription');
        }
    }
    //-----------------------------------------------------------//
    public function resolveSubscription($marketplace_token)
    {
        $token = "Bearer " . $this->login();

        $url = $this->_urlManager->getResolveSubscriptionUrl();
        $header = [
            'Content-Type' => "application/json",
            'Authorization' => $token,
            'x-ms-marketplace-token' => $marketplace_token,
        ];
        $body = '';
        $data = $this->_caller->post_($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while resolving sass subscription ' . json_encode($data));
            throw new \Exception('Error Resolving Sass Subscription');
        }
    }
    //-----------------------------------------------------------//
    public function activateSubscription($customerSubscriptionId,$planId,$quantity)
    {
        $token = "Bearer " . $this->login();

        $url = $this->_urlManager->getActivateSubscriptionUrl($customerSubscriptionId);
        $header = [
            'Content-Type' => "application/json",
            'Authorization' => $token,
        ];
        $body = '
        {
            "planId": "'.$planId.'",
            "quantity": "'.$quantity.'"
          }
        ';
        $data = $this->_caller->post_($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202 || $data["status"] == 204) {
            return $data;
        } else {
            Log::log('error', 'Exception while activating  sass subscription ' . json_encode($data));
            throw new \Exception('Error Activating Sass Subscription');
        }
    }
    //-----------------------------------------------------------//
}
