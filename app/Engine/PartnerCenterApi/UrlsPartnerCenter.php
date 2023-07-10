<?php

// created by fesal
// 07/04/2020

namespace App\Engine\PartnerCenterApi;

use App\Engine\Base\Manager;

class UrlsPartnerCenter
{

    //get url login partner center api
    //tenant_id is the tenant id for microSoft account
    public function loginUrl($tenant_id)
    {
        return "https://login.microsoftonline.com/" . $tenant_id . "/oauth2/token";
    }

    //get url customers partner center
    public function getCustomersUrl()
    {
        return config('parameters.PARTNER_CENTER_URL') . "customers";
    }

    //get url customers By id partner center
    public function getCustomerByTenantIdUrl($id)
    {
        return config('parameters.PARTNER_CENTER_URL') . "customers/" . $id;
    }

    //get url all customers partner center
    public function getAllCustomerUrl()
    {
        return config('parameters.PARTNER_CENTER_URL') . "customers/";
    }

    public function createCustomerUrl()
    {
        return config('parameters.PARTNER_CENTER_URL') . "customers";
    }

    public function checkDomainUrl($domain)
    {
        return config('parameters.PARTNER_CENTER_URL') . "domains/" . $domain;
    }

    public function countryValidationRules($code)
    {
        return config('parameters.PARTNER_CENTER_URL') . "countryvalidationrules/" . $code;
    }
    public function addCustomerToCart($customerId)
    {
        return config('parameters.PARTNER_CENTER_URL') . "customers/" . $customerId . "/carts";
    }
    public function confirmCustomerAgreement($customerId)
    {
        return config('parameters.PARTNER_CENTER_URL') . "customers/" . $customerId . "/agreements";
    }

    public function getCustomerAgreement($customerId)
    {
        return config('parameters.PARTNER_CENTER_URL') . "customers/" . $customerId . "/agreements?agreementType=MicrosoftCustomerAgreement";
    }

    public function getSubscription($customerId, $subscriptionId)
    {
        return config('parameters.PARTNER_CENTER_URL') . "customers/" . $customerId . "/subscriptions/" . $subscriptionId;
    }
    public function addCartToCustomerUrl($customerId)
    {
        return config('parameters.PARTNER_CENTER_URL') . "customers/" . $customerId . "/carts";
    }

    public function cartCheckOutUrl($customerId, $cartId)
    {
        return config('parameters.PARTNER_CENTER_URL') . "customers/" . $customerId . "/carts/" . $cartId . "/checkout";
    }

    public function getSubscriptionAzureEntitlementsUrl($customerId, $subscriptionId)
    {
        return config('parameters.PARTNER_CENTER_URL') . "customers/$customerId/subscriptions/$subscriptionId/azureEntitlements";
    }

    public function getCustomerSuspendSubscriptionUrl($customerId, $subscriptionId)
    {
        return config('parameters.PARTNER_CENTER_URL') . "customers/" . $customerId . "/subscriptions/" . $subscriptionId;
    }

    public function getProductsListUrl()
    {
        return config('parameters.PARTNER_CENTER_URL') ."/products?country=SA&targetView=MicrosoftAzure";
    }
    public function getProductSKUsListUrl($productId)
    {
        return config('parameters.PARTNER_CENTER_URL') ."/products/$productId/skus?country=SA";
    }
    public function getProductSKUAvailabilitiesListUrl($productId,$skuId)
    {
        return config('parameters.PARTNER_CENTER_URL') ."/products/$productId/skus/$skuId/availabilities?country=SA";
    }
}
