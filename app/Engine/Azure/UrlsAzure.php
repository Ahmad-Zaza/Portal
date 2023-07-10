<?php

namespace App\Engine\Azure;

use App\Engine\Base\Manager;

class UrlsAzure
{

    public function loginUrl($tenantId)
    {
        return "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token";
    }

    public function createAzureResourceGroupUrl($subscriptionId, $resourceGroupName)
    {
        return config('parameters.AZURE_URL') . "$subscriptionId/resourceGroups/$resourceGroupName?api-version=2020-06-01";
    }

    public function createAzureContainerUrl($azureStorageAccountName, $azureContainerName)
    {
        return "https://$azureStorageAccountName.blob.core.windows.net/$azureContainerName?restype=container";
    }

    public function createAzureStorageAccountUrl($subscriptionId, $resourceGroupName, $storageAccountName)
    {
        return config('parameters.AZURE_URL') . "$subscriptionId/resourceGroups/$resourceGroupName/providers/Microsoft.Storage/storageAccounts/$storageAccountName?api-version=2019-06-01";
    }

    public function getAzureStorageAccountUrl($subscriptionId, $resourceGroup,$storageAccountName)
    {
        return config('parameters.AZURE_URL') . "$subscriptionId/resourceGroups/$resourceGroup/providers/Microsoft.Storage/storageAccounts/$storageAccountName?api-version=2019-06-01";
    }

    public function getAllAzureStorageAccountUrl($subscriptionId, $resourceGroup)
    {
        return config('parameters.AZURE_URL') . "$subscriptionId/resourceGroups/$resourceGroup/providers/Microsoft.Storage/storageAccounts?api-version=2019-06-01";
    }

    public function getAzureStorageAccountSharedKeysUrl($subscriptionId, $resourceGroupName, $storageAccountName)
    {
        return config('parameters.AZURE_URL') . "$subscriptionId/resourceGroups/$resourceGroupName/providers/Microsoft.Storage/storageAccounts/$storageAccountName/listKeys?api-version=2019-06-01";
    }

    public function deleteAzureStorageAccountUrl($subscriptionId, $resourceGroupName, $storageAccountName)
    {
        return config('parameters.AZURE_URL') . "$subscriptionId/resourceGroups/$resourceGroupName/providers/Microsoft.Storage/storageAccounts/$storageAccountName?api-version=2019-06-01";
    }
    //------------------------------------------------------------//
    public function getAzureResourceProviderUrl($subscriptionId)
    {
        return config('parameters.AZURE_URL') . "$subscriptionId/providers/microsoft.storage?api-version=2020-06-01";
    }
    //------------------------------------------------------------//
    public function registerAzureResourceProviderUrl($subscriptionId)
    {
        return config('parameters.AZURE_URL') . "$subscriptionId/providers/microsoft.storage/register?api-version=2020-06-01";
    }
    //------------------------------------------------------------//
    public function getAzureContainerUrl($subscriptionId, $resourceGroupName, $storageAccountName, $containerName)
    {
        return config('parameters.AZURE_URL') . "$subscriptionId/resourceGroups/$resourceGroupName/providers/Microsoft.Storage/storageAccounts/$storageAccountName/blobServices/default/containers/$containerName?api-version=2019-06-01";
    }

    public function renameSubscriptionUrl($subscriptionId)
    {
        return config('parameters.AZURE_URL') . "$subscriptionId/providers/Microsoft.Subscription/rename?api-version=2020-09-01";
    }

    public function createTableUrl($subscriptionId,$accountName,$resourceGroup,$tableName)
    {
        return config('parameters.AZURE_URL') .
            "$subscriptionId/resourceGroups/$resourceGroup/providers/Microsoft.Storage/storageAccounts/$accountName/tableServices/default/tables/$tableName?api-version=2021-04-01";
    }

    public function getTableUrl($subscriptionId,$accountName,$resourceGroup,$tableName)
    {
        return config('parameters.AZURE_URL') .
            "$subscriptionId/resourceGroups/$resourceGroup/providers/Microsoft.Storage/storageAccounts/$accountName/tableServices/default/tables/$tableName?api-version=2021-04-01";
    }

    public function tableSelectDataUrl($accountName,$tableName,$limit,$filter='')
    {
        return "https://$accountName.table.core.windows.net/$tableName()?\$top=$limit".($filter?"&\$filter=PartitionKey%20eq%20'$filter'":"");
    }

    public function tableSelectPageDataUrl($accountName,$tableName,$limit,$nextPartitionKey,$nextRowKey,$filter='')
    {
        return "https://$accountName.table.core.windows.net/$tableName?\$top=$limit&NextPartitionKey=$nextPartitionKey&NextRowKey=$nextRowKey".($filter?"&\$filter=PartitionKey%20eq%20'$filter'":"");
    }

    public function tableInsertDataUrl($accountName,$tableName)
    {
        return "https://$accountName.table.core.windows.net/\$batch";
    }

    public function tableDeleteDataUrl($accountName,$tableName,$partition,$row)
    {
        return "https://$accountName.table.core.windows.net/$tableName(PartitionKey='$partition',RowKey='$row')";
    }
}
