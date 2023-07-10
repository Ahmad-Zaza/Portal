<?php

namespace App\Engine\MarketPlace;


class UrlsMarketPlace
{

    public function loginUrl($tenantId){
        return "https://login.microsoftonline.com/$tenantId/oauth2/token";
    }

    public function getSubscriptionUrl($subscriptionId){
        return "https://marketplaceapi.microsoft.com/api/saas/subscriptions/$subscriptionId?api-version=2018-08-31";
    }

    public function getResolveSubscriptionUrl(){
        return "https://marketplaceapi.microsoft.com/api/saas/subscriptions/resolve?api-version=2018-08-31";
    }

    public function getActivateSubscriptionUrl($subscriptionId){
        return "https://marketplaceapi.microsoft.com/api/saas/subscriptions/$subscriptionId/activate?api-version=2018-08-31";
    }
}
