<?php

namespace App\Engine\PartnerCenterApi;

use App\Engine\Base\CallerApi;
use App\Engine\PartnerCenterApi\UrlsPartnerCenter;
use Illuminate\Support\Facades\Log;

class ManagerPartnerCenter
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
        $this->_urlManager = new UrlsPartnerCenter();
        $this->_client_id = config('parameters.PARTNER_CENTER_CLIENT_ID');
        $this->_tenant_id = config('parameters.PARTNER_CENTER_TENANT_ID');
        $this->_secret = config('parameters.PARTNER_CENTER_SECRET');
        $this->_grant_type = config('parameters.PARTNER_CENTER_GRANT_TYPE');
        $this->_resource = config('parameters.PARTNER_CENTER_RESOURCE');
        $this->_refresh_token = config('parameters.PARTNER_CENTER_REFRESH_TOKEN');
        $this->_caller = new CallerApi();
    }

    public function login()
    {

        //get url request login in partner center api
        $url = $this->_urlManager->loginUrl($this->_tenant_id);
        $params = [
            'client_id' => $this->_client_id,
            'client_secret' => $this->_secret,
            'grant_type' => $this->_grant_type,
            'resource' => $this->_resource,
            'refresh_token' => $this->_refresh_token,
        ];
        //call request
        $data = $this->_caller->post($url, $params, []);
        // if ($data["data"]->refresh_token) {
        //     Parameters::where('key', 'PARTNER_CENTER_REFRESH_TOKEN')
        //         ->update(['value' => $data["data"]->refresh_token]);
        // }
        return $data["data"]->access_token;
    }

    public function getCustomers()
    {
        //get url customers in partner center api

        $token = "Bearer " . $this->login();

        $url = $this->_urlManager->getCustomersUrl();
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        //call request
        return $this->_caller->get($url, [], $header);
    }

    //Confirm Customer's Agreement
    public function confirmCustomersAgreement($user)
    {
        //get url customers in partner center api
        $dt = gmdate('D, d M Y H:i:s T', time());
        $dt = gmdate('Y-m-d\TH:i:s\.u\0\Z', time());
        $token = "Bearer " . $this->login();

        $url = $this->_urlManager->confirmCustomerAgreement($user->organization->azure_tenant_guid);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $body = '
        {
            "primaryContact": {
                "firstName": "' . $user->first_name . '",
                "lastName": "' . ($user->last_name?:$user->first_name) . '",
                "email": "' . $user->email . '",
                "phoneNumber": "' . $user->phone . '"
            },
            "templateId": "117a77b0-9360-443b-8795-c6dedc750cf9",
            "dateAgreed": "' . $dt . '",
            "type": "MicrosoftCustomerAgreement"
        }
        ';

        //call request
        $data = $this->_caller->post_($url, $body, $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202) {

            return $data;
        } else {
            Log::log('error', 'Exception while confirming agreement   ' . json_encode($data));
            throw new \Exception('Error confirming agreement');
        }
    }

    //Confirm Customer's Agreement
    public function checkCustomerAgreement($customerId)
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->getCustomerAgreement($customerId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        //call request
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202) {
            return $data;
        } else {
            Log::log('error', 'Exception while confirming  Agreement   ' . json_encode($data));
            throw new \Exception('Error confirming  Agreement');
        }
    }

    //Confirm Customer's Agreement
    public function getCustomersSubscription($customerId)
    {

        $token = "Bearer " . $this->login();
        $user = auth()->user();
        $url = $this->_urlManager->getSubscription($customerId, $user->organization->azure_subscription_guid);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        //call request
        $data = $this->_caller->get($url, [], $header);
        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202) {

            return $data;
        } else {
            Log::log('error', 'Customer\'s Subscription not found   ' . json_encode($data));
            return $data;
        }
    }

    public function checkDomain($domain)
    {
        //get url customers in partner center api

        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->checkDomainUrl($domain);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        //call request
        return $this->_caller->head($url, [], $header);
    }

    public function getCustomersById($id)
    {
        //get url customers by tenant id in partner center api
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->getCustomerByTenantIdUrl($id);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        //call request
        $data = $this->_caller->get($url, [], $header);
        if (in_array($data["status"], [200, 201, 202, 204])) {
            return $data;
        } else {
            Log::log('error', 'Exception while creating customer in partner center  ' . json_encode($data));
            throw new \Exception('Error creating customer');
        }
        return $data;
    }

    private function getDomain($domains)
    {
        foreach ($domains as $domain) {
            if (substr_count($domain, ".", 0) == 2 && substr_count($domain, ".onmicrosoft.com", 0) > 0) {
                return $domain;
            }
        }
    }

    public function getAllCustomers()
    {
        //get url customers by tenant id in partner center api

        $token = "Bearer " . $this->login();

        $user = auth()->user();

        $url = $this->_urlManager->getAllCustomerUrl();
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];

        //call request
        $data = $this->_caller->get($url, [], $header);

        return $data;
    }

    public function createCustomer($request, $user)
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->createCustomerUrl();
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $domain = $request["Domain"] . '.onmicrosoft.com';
        $body = '
        {
            "CompanyProfile": {
                "Domain": "' . $domain . '",
                "organizationRegistrationNumber" : "' . $request["vat_num"] . '"
            },
            "BillingProfile": {
                "Culture": "EN-US",
                "Email": "' . $user->email . '",
                "Language": "en",
                "CompanyName": "' . $user->organization->company_name . '",
                "DefaultAddress": {
                    "FirstName": "' . $user->first_name . '",
                    "LastName": "' . ($user->last_name?:$user->first_name) . '",
                    "AddressLine1": "' . $user->phone . '",
                    "City": "' . $request["City"] . '",
                    "State": "' . $request["State"] . '",
                    "PostalCode": "' . $request["PostalCode"] . '",
                    "Country": "' . $request["Country"] . '",
                    "PhoneNumber": "' . $user->phone . '"
                }
            }
        }
        ';
        $data = $this->_caller->post_($url, $body, $header);

        if (in_array($data["status"], [200, 201, 202, 204])) {
            return $data;
        } else {
            Log::log('error', 'Exception while creating customer in partner center  ' . json_encode($data));
            throw new \Exception('Error creating customer');
        }
        return $data;
    }

    public function addCartToCustomer($customerId)
    {
        $catalogItemId = $this->generateCatalogItemId();
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->addCartToCustomerUrl($customerId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $body = '
        {
            "lineItems" : [
                {
                    "catalogItemId": "' . $catalogItemId . '",
                    "FriendlyName": "Microsoft Azure For Cloud O365 Backup",
                    "quantity" : 1,
                    "billingCycle" : "monthly"
                }
            ]
        }
        ';
        $data = $this->_caller->post_($url, $body, $header);

        if (($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202) && !optional(optional(optional($data["data"])->lineItems)[0])->error ) {
            return $data;
        } else {
            Log::log('error', 'Exception while creating Cart   ' . json_encode($data));
            throw new \Exception('Error Creating Organization');
        }
    }

    public function cartCheckOut($customerId, $cartId)
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->cartCheckOutUrl($customerId, $cartId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $body = '';
        $data = $this->_caller->post_($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202) {
            return $data;
        } else {
            Log::log('error', 'Exception while creating Cart   ' . json_encode($data));
            throw new \Exception('Error Creating Organization');
        }
    }

    public function getSubscriptionAzureEntitlements($customerId, $subscriptionId)
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->getSubscriptionAzureEntitlementsUrl($customerId, $subscriptionId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $body = '';
        $data = $this->_caller->get($url, [], $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202) {
            return $data;
        } else {
            Log::log('error', 'Exception while getting Subscription Azure Entitlements ' . json_encode($data));
            throw new \Exception('Error Getting Subscription Azure Entitlements');
        }
    }

    public function suspendCustomerSubscription($customerId, $subscriptionId)
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->getCustomerSuspendSubscriptionUrl($customerId, $subscriptionId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        $body = '
            {
                "Id": "' . $subscriptionId . '",
                "Status": "suspended"
            }
        ';
        $data = $this->_caller->patch($url, $body, $header);

        if ($data["status"] == 200 || $data["status"] == 201 || $data["status"] == 202) {

            return $data;
        } else {
            Log::log('error', 'Exception while Suspending Subscription   ' . json_encode($data));
            throw new \Exception('Error Suspending Subscription');
        }
    }

    public function getProductsList()
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->getProductsListUrl();
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        //call request
        $response = $this->_caller->get($url, [], $header);
        $status = $response['status'];
        if ($status == 200 || $status == 201 || $status == 202 || $status == 204) {
            return $response["data"];
        } else {
            Log::log('error', 'Exception While Getting Products List ' . json_encode($response));
            throw new \Exception('Error Getting Products List');
        }
    }
    public function getProductSKUsList($productId)
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->getProductSKUsListUrl($productId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        //call request
        $response = $this->_caller->get($url, [], $header);
        $status = $response['status'];
        if ($status == 200 || $status == 201 || $status == 202 || $status == 204) {
            return $response["data"];
        } else {
            Log::log('error', 'Exception While Getting Products SKUs List ' . json_encode($response));
            throw new \Exception('Error Getting Products SKUs List');
        }
    }

    public function getProductSKUAvailabilitiesList($productId, $skuId)
    {
        $token = "Bearer " . $this->login();
        $url = $this->_urlManager->getProductSKUAvailabilitiesListUrl($productId, $skuId);
        $header = [
            'Authorization' => $token,
            'Accept' => "application/json",
            'Content-Type' => "application/json",
        ];
        //call request
        $response = $this->_caller->get($url, [], $header);
        $status = $response['status'];
        if ($status == 200 || $status == 201 || $status == 202 || $status == 204) {
            return $response["data"];
        } else {
            Log::log('error', 'Exception While Getting Products SKUs Availabilities List ' . json_encode($response));
            throw new \Exception('Error Getting Products SKUs Availabilities List');
        }
    }

    public function generateCatalogItemId()
    {
        $products = $this->getProductsList()->items;
        $productId = array_values(array_filter($products, function ($item) {
            return ($item->title == "Azure plan");
        }))[0]->id;
        //-----------------//
        $skuId = $this->getProductSKUsList($productId)->items[0]->id;
        //-----------------//
        $catalogItemId = $this->getProductSKUAvailabilitiesList($productId, $skuId)->items[0]->catalogItemId;
        //-----------------//
        return $catalogItemId;
    }
}
