<?php

namespace App\Engine\Veeam;

use App\Engine\Base\Manager;

class UrlsVeeam
{

    public function loginUrl($serverUrl)
    {
        return $serverUrl . "/Token";
    }

    public function getGenerateDeviceCodeUrl($veeamUrl)
    {
        return $veeamUrl . "/DeviceCode";
    }

    public function createOrganizationUrl($veeamUrl)
    {
        return $veeamUrl . "/Organizations";
    }

    public function deleteOrganizationUrl($veeamUrl, $organizationId)
    {
        return $veeamUrl . "/Organizations/$organizationId";
    }

    public function setOrganizationUrl($organizationId)
    {
        return Manager::getVeeamServerUrl() . "/Organizations/$organizationId";
    }

    public function createAccountUrl()
    {
        return Manager::getVeeamServerUrl() . "/Accounts/";
    }

    public function deleteAccountUrl($cloudCredentialAccountId)
    {
        return Manager::getVeeamServerUrl() . "/Accounts/$cloudCredentialAccountId";
    }

    public function createFolderUrl($azureContainerName, $cloudCredentialAccountId)
    {
        return Manager::getVeeamServerUrl() . "/AzureResources/containers/$azureContainerName/folders?accountId=$cloudCredentialAccountId&RegionType=Global";
    }

    public function getRepositoryUrl($repositoryId)
    {
        return Manager::getVeeamServerUrl() . "/BackupRepositories/$repositoryId";
    }

    public function createRepositoryUrl()
    {
        return Manager::getVeeamServerUrl() . "/BackupRepositories";
    }

    public function updateRepositoryUrl($repositoryId)
    {
        return Manager::getVeeamServerUrl() . "/BackupRepositories/$repositoryId";
    }

    public function deleteRepositoryUrl($repositoryId)
    {
        return Manager::getVeeamServerUrl() . "/BackupRepositories/$repositoryId";
    }
    public function createObjectStorageUrl()
    {
        return Manager::getVeeamServerUrl() . "/ObjectStorageRepositories/";
    }

    public function getObjectStorageUrl($objectStorageId = '')
    {
        return Manager::getVeeamServerUrl() . "/ObjectStorageRepositories/$objectStorageId";
    }

    public function updateObjectStorageUrl($objectStorageId)
    {
        return Manager::getVeeamServerUrl() . "/ObjectStorageRepositories/$objectStorageId";
    }

    public function deleteObjectStorageUrl($objectStorageId)
    {
        return Manager::getVeeamServerUrl() . "/ObjectStorageRepositories/$objectStorageId";
    }

    public function createPasswordUrl()
    {
        return Manager::getVeeamServerUrl() . "/EncryptionKeys";
    }

    public function deletePasswordUrl($encryptionKey)
    {
        return Manager::getVeeamServerUrl() . "/EncryptionKeys/$encryptionKey";
    }

    public function getOrganizationJobsUrl($org_id, $jobId = '')
    {
        if ($jobId) {
            return Manager::getVeeamServerUrl() . "/Jobs/$jobId?limit=10000";
        }
        return Manager::getVeeamServerUrl() . "/Organizations/$org_id/Jobs/?limit=10000";
    }

    public function getOrganizationUsersUrl($org_id, $limit = 10000, $offset = 0, $setId = null)
    {
        if (!$offset) {
            $offset = 0;
        }
        if ($setId) {
            return Manager::getVeeamServerUrl() . "/Organizations/$org_id/Users?offset=$offset&limit=$limit&setId=$setId";
        }

        return Manager::getVeeamServerUrl() . "/Organizations/$org_id/Users?offset=$offset&limit=$limit";
    }

    public function getOrganizationUserOnedrivesUrl($org_id, $orgUserId)
    {
        return Manager::getVeeamServerUrl() . "/Organizations/$org_id/Users/$orgUserId/Onedrives?offset=0&limit=10000";
    }

    public function getOrganizationGroupsUrl($org_id, $limit = 10000, $offset = 0, $setId = null)
    {
        if (!$offset) {
            $offset = 0;
        }
        return Manager::getVeeamServerUrl() . "/Organizations/$org_id/Groups?offset=$offset&limit=$limit&setId=$setId";
    }

    public function getOrganizationGroupMembersUrl($org_id, $groupId)
    {
        return Manager::getVeeamServerUrl() . "/Organizations/$org_id/Groups/$groupId/Members?offset=0&limit=10000";
    }

    public function getBackupAccountUrl($org_id)
    {
        return Manager::getVeeamServerUrl() . "/Organizations/$org_id/backupaccounts";
    }

    public function getOrganizationTeamsUrl($org_id)
    {
        return Manager::getVeeamServerUrl() . "/Organizations/$org_id/Teams?offset=0&limit=10000";
    }
    public function getOrganizationSitesUrl($org_id, $limit, $offset)
    {
        $serverUrl = Manager::getVeeamServerUrl();
        return $serverUrl . "/Organizations/$org_id/Sites?offset=$offset&limit=$limit";
    }

    public function manageJobsUrl($job_id)
    {
        return Manager::getVeeamServerUrl() . "/Jobs/$job_id/Action";
    }

    public function getJobsUrl($job_id = '')
    {
        return Manager::getVeeamServerUrl() . "/Jobs/$job_id";
    }
    public function deleteJobsUrl($job_id)
    {
        return Manager::getVeeamServerUrl() . "/Jobs/$job_id";
    }
    public function editJobsUrl($job_id)
    {
        return Manager::getVeeamServerUrl() . "/Jobs/$job_id";
    }
    public function getJobSelectedItemsUrl($job_id)
    {
        return Manager::getVeeamServerUrl() . "/Jobs/$job_id/SelectedItems";
    }
    public function createJobsUrl($org_id)
    {
        return Manager::getVeeamServerUrl() . "/Organizations/$org_id/jobs";
    }
    public function addJobItemsUrl($jobId)
    {
        return Manager::getVeeamServerUrl() . "/jobs/$jobId/SelectedItems";
    }

    public function getJobSessionsUrl($job_id, $limit)
    {
        return Manager::getVeeamServerUrl() . "/Jobs/$job_id/JobSessions?offset=0&limit=$limit";
    }

    public function getAllJobSessionsUrl()
    {
        return Manager::getVeeamServerUrl() . "/JobSessions?offset=0&limit=10000";
    }

    // Edited by Ctelecoms on 29th June 2021
    public function getJobSessionLogItemsUrl($session_id)
    {
        return Manager::getVeeamServerUrl() . "/JobSessions/$session_id/LogItems?offset=0&limit=10000";
    }

    public function createRestoreSessionUrl($org_id)
    {
        return Manager::getVeeamServerUrl() . "/Organizations/$org_id/Action";
    }

    public function createJobRestoreSessionUrl($jobId)
    {
        return Manager::getVeeamServerUrl() . "/jobs/$jobId/Action";
    }
    public function stopRestoreSessionUrl($session_id)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Action";
    }

    public function getRestoreSessionUrl($session_id)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id";
    }

    public function getRestoreSessionDeviceCodeUrl($session_id)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/organization/restoreDeviceCode";
    }

    public function getRestoreSessionEventsUrl($session_id)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Events?limit=10000";
    }

    public function getExchangeMailBoxesUrl($session_id)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Mailboxes?offset=0&limit=10000&sortAsc=name";
    }

    public function getMailBoxFoldersUrl($session_id, $mailbox_id)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Mailboxes/$mailbox_id/Folders?limit=10000&offset=0";
    }

    public function getMailBoxFolderUrl($session_id, $mailbox_id, $folderId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Mailboxes/$mailbox_id/Folders/$folderId";
    }

    public function getMailBoxFoldersChildrenUrl($session_id, $mailbox_id, $folderId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Mailboxes/$mailbox_id/Folders?parentId=$folderId";
    }

    public function getMailBoxFolderItemsUrl($session_id, $mailbox_id, $folder_id)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Mailboxes/$mailbox_id/Folders" / $folder_id . "/action?offset=0&limit=10000";
    }

    public function getMailBoxItemsUrl($session_id, $mailbox_id, $folderId, $offset = 0, $limit = 1)
    {
        if ($folderId) {
            return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Mailboxes/$mailbox_id/Items?folderId=$folderId&offset=$offset&limit=$limit";
        }

        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Mailboxes/$mailbox_id/Items?offset=$offset&limit=$limit";
    }

    public function getRestoreMailboxToOriginalUrl($session_id)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Mailboxes/Action";
    }

    public function getRestoreMailboxUrl($session_id, $mailbox_id)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Mailboxes/$mailbox_id/Action";
    }

    public function getRestoreMailboxFolderUrl($session_id, $mailbox_id, $folderId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Mailboxes/$mailbox_id/Folders/$folderId/Action";
    }

    public function getRestoreMailboxFolderItemsUrl($session_id, $mailbox_id)
    {
        if (is_array($mailbox_id))
            $mailbox_id = $mailbox_id[0];
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Mailboxes/$mailbox_id/Items/Action";
    }

    public function getRestoreMailboxFolderItemUrl($session_id, $mailbox_id, $itemId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Mailboxes/$mailbox_id/Items/$itemId/Action";
    }

    public function getOnedriveItemsUrl($session_id)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/OneDrives?offset=0&limit=10000";
    }

    public function getOnedriveFoldersUrl($session_id, $onedriveId, $withParent = false)
    {
        if (!$withParent) {
            return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/OneDrives/$onedriveId/Folders?parentId=null&offset=0&limit=10000";
        }
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/OneDrives/$onedriveId/Folders?offset=0&limit=10000";
    }

    public function getOnedriveFolderSubFoldersUrl($session_id, $onedriveId, $folderId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/OneDrives/$onedriveId/Folders?parentId=$folderId&offset=0&limit=10000";
    }

    public function getOnedriveFolderDocumentsUrl($session_id, $onedriveId, $folderId, $offset = 0, $limit = 1)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/onedrives/$onedriveId/documents?parentId=$folderId&offset=$offset&limit=$limit";
    }

    public function getOnedriveDocumentsUrl($session_id, $onedriveId, $offset = 0, $limit = 1)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/onedrives/$onedriveId/documents?parentId=null&offset=$offset&limit=$limit";
    }

    public function getOnedriveBulkRestoreURL($session_id)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/onedrives/Action";
    }

    public function getOnedriveRestoreURL($session_id, $onedriveId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/onedrives/$onedriveId/Action";
    }

    public function getOnedriveFoldersBulkRestoreURL($session_id, $onedriveId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/onedrives/$onedriveId/Folders/Action";
    }

    public function getOnedriveFoldersRestoreURL($session_id, $onedriveId, $folderId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/onedrives/$onedriveId/Folders/$folderId/Action";
    }

    public function getOnedriveDocumentsBulkRestoreURL($session_id, $onedriveId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/onedrives/$onedriveId/Documents/Action";
    }

    public function getOnedriveDocumentsRestoreURL($session_id, $onedriveId, $documentId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/onedrives/$onedriveId/Documents/$documentId/Action";
    }

    public function getSharepointSitesUrl($session_id)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites?limit=10000";
    }

    public function getSiteListsUrl($session_id, $siteId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/Lists?offset=0&limit=10000";
    }

    public function getSiteLibrariesUrl($session_id, $siteId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/Libraries?offset=0&limit=10000";
    }

    public function getSiteFoldersUrl($session_id, $siteId, $parentId)
    {
        if ($parentId) {
            return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/Folders?parentId=$parentId&offset=0&limit=10000";
        }

        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/Folders?offset=0&limit=10000";
    }

    public function getSiteDocumentsUrl($session_id, $siteId, $parentId, $offset, $limit)
    {
        if ($parentId) {
            return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/Documents?parentId=$parentId&offset=$offset&limit=$limit";
        }

        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/Documents?offset=$offset&limit=$limit";
    }

    public function getSiteItemsUrl($session_id, $siteId, $parentId = '', $offset, $limit)
    {
        if ($parentId) {
            return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/Items?parentId=$parentId&offset=$offset&limit=$limit";
        }

        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/Items?offset=$offset&limit=$limit";
    }

    public function getSiteRestoreURL($session_id, $siteId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/sites/$siteId/Action";
    }

    public function getSiteLibrariesRestoreURL($session_id, $siteId, $libraryId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/sites/$siteId/Libraries/$libraryId/Action";
    }

    public function getSiteListsRestoreURL($session_id, $siteId, $listId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/sites/$siteId/Lists/$listId/Action";
    }

    public function getSiteFoldersRestoreUrl($session_id, $siteId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/Folders/Action";
    }

    public function getSiteDocumentsRestoreUrl($session_id, $siteId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/Documents/Action";
    }

    public function getSiteItemsAttachmentsUrl($session_id, $siteId, $itemId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/Items/$itemId/Attachments?limit=10000";
    }

    public function getSiteItemsAttachmentsRestoreUrl($session_id, $siteId, $itemId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/Items/$itemId/Attachments/Action";
    }

    public function getSiteItemsRestoreUrl($session_id, $siteId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/items/Action";
    }

    public function getSiteDocumentRestoreUrl($session_id, $siteId, $documentId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/Documents/$documentId/Action";
    }

    public function getTeamsUrl($session_id)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Teams?limit=10000";
    }

    public function getTeamChannelsUrl($session_id, $teamId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Teams/$teamId/channels?offset=0&limit=10000";
    }

    public function getTeamChannelUrl($session_id, $teamId, $channelId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Teams/$teamId/channels/$channelId";
    }

    public function getChannelPostsUrl($session_id, $teamId, $channelId, $offset, $limit)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Teams/$teamId/Posts?parentId=-1&channelId=$channelId&offset=$offset&limit=$limit";
    }

    public function getChannelPostRepliesUrl($session_id, $teamId, $channelId, $postId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Teams/$teamId/Posts?parentId=$postId&channelId=$channelId&offset=0&limit=10000";
    }

    public function getChannelFilesUrl($session_id, $teamId, $channelId, $folderId, $offset, $limit)
    {
        if ($folderId == -1) {
            return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Teams/$teamId/Files?channelId=$channelId&offset=$offset&limit=$limit";
        }

        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Teams/$teamId/Files?channelId=$channelId&parentId=$folderId&offset=$offset&limit=$limit";
    }

    public function getChannelTabsUrl($session_id, $teamId, $channelId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Teams/$teamId/channels/$channelId/tabs?offset=0&limit=10000";
    }

    public function getTeamRestoreURL($session_id)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/teams/Action";
    }

    public function getChannelsRestoreURL($session_id, $teamId, $channelId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/teams/$teamId/channels/$channelId/Action";
    }

    public function getTeamsPostsRestoreURL($session_id, $teamId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/teams/$teamId/posts/Action";
    }

    public function getTeamsPostRestoreURL($session_id, $teamId, $postId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/teams/$teamId/posts/$postId/Action";
    }

    public function getTeamsFilesRestoreURL($session_id, $teamId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/teams/$teamId/files/Action";
    }

    public function getTeamsFileRestoreURL($session_id, $teamId, $fileId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/teams/$teamId/files/$fileId/Action";
    }

    public function getTeamsTabsRestoreURL($session_id, $teamId, $channelId)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/teams/$teamId/channels/$channelId/tabs/Action";
    }

    public function getLicensedUsersUrl($organizationId)
    {
        return Manager::getVeeamServerUrl() . "/Organizations/$organizationId/LicensingInformation";
    }

    public function searchMailBoxItemsUrl($session_id, $mailbox_id, $offset = 0, $limit = 1)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Mailboxes/$mailbox_id/action?offset=$offset&limit=$limit";
    }

    public function searchMailBoxFolderItemsUrl($session_id, $mailbox_id, $folderId, $offset = 0, $limit = 1)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Mailboxes/$mailbox_id/folders/$folderId/action?offset=$offset&limit=$limit";
    }

    public function searchOnedriveItemsUrl($session_id, $onedriveId, $offset = 0, $limit = 1)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/OneDrives/$onedriveId/action?offset=$offset&limit=$limit";
    }

    public function searchOnedriveFolderItemsUrl($session_id, $onedriveId, $folderId, $offset = 0, $limit = 1)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/OneDrives/$onedriveId/folders/$folderId/action?offset=$offset&limit=$limit";
    }

    public function searchTeamItemsUrl($session_id, $teamId, $offset = 0, $limit = 1)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Teams/$teamId/action?offset=$offset&limit=$limit";
    }

    public function searchTeamChannelItemsUrl($session_id, $teamId, $channelId, $offset = 0, $limit = 1)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Teams/$teamId/channels/$channelId/action?offset=$offset&limit=$limit";
    }

    public function searchSiteItemsUrl($session_id, $siteId, $offset = 0, $limit = 1)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/action?offset=$offset&limit=$limit";
    }

    public function searchSiteListItemsUrl($session_id, $siteId, $listId, $offset = 0, $limit = 1)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/lists/$listId/action?offset=$offset&limit=$limit";
    }

    public function searchSiteLibraryItemsUrl($session_id, $siteId, $libraryId, $offset = 0, $limit = 1)
    {
        return Manager::getVeeamServerUrl() . "/RestoreSessions/$session_id/Organization/Sites/$siteId/libraries/$libraryId/action?offset=$offset&limit=$limit";
    }

    public function getAzureApplicationsUrl($organizationId)
    {
        return Manager::getVeeamServerUrl() . "/Organizations/$organizationId/Applications?offset=0&limit=10000";
    }

    public function getBackupApplicationsUrl($organizationId)
    {
        return Manager::getVeeamServerUrl() . "/Organizations/$organizationId/BackupApplications?offset=0&limit=10000";
    }
}
