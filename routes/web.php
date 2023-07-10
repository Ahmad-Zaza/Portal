<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

//--- Testing ----------------------------------------------------------------
Route::post('execps', 'HomeController@execPs');
Route::get('exectest', 'HomeController@execPs');
Route::get('/route/clear', function () {
    // Artisan::call('permission:cache-reset');
    Artisan::call('optimize');
});
//--------------------------------------------------------------------------//

//--- Login
// Route::get('/', 'Auth\AuthController@loginPage')->middleware('RegisterStorage','auth');
Route::get('login', 'Auth\AuthController@loginPage')->name('login');
Route::post('logout', 'Auth\AuthController@logout')->name('logout');
Route::get('logout', 'Auth\AuthController@logout');
Route::get('sso', 'Auth\AuthController@loginGraph');
Route::get('graph-callback', 'Auth\AuthController@callbackGraph');
Route::get('admin-consent-callback', 'Auth\AuthController@callbackAdminConsent');
Route::get('marketplace', 'Auth\AuthController@marketplacePage')->name('marketplace');
Route::get('configure-subscription', 'Auth\AuthController@configureSubscription')->name('configure-subscription');

//-------------------------------------------------------------------------//

//--- Register Steps
Route::get('step0', 'Auth\StepController@step0')->name('step0')->middleware(['RegisterStorage', 'auth']);
Route::post('step0', 'Auth\StepController@saveStep0')->middleware(['auth']);
Route::get('step1', 'Auth\StepController@step1')->name('step1')->middleware(['RegisterStorage', 'auth']);
Route::post('step1', 'Auth\StepController@saveStep1')->middleware(['auth']);
Route::get('step2', 'Auth\StepController@step2')->name("step2")->middleware(['RegisterStorage', 'auth']);
Route::post('step2/newTenant', 'Auth\StepController@newTenant')->middleware(['auth']);
Route::post('step2/checkTenant', 'Auth\StepController@checkTenant')->middleware(['auth']);
Route::get('step2/checkDomain/{domain}', 'Auth\StepController@checkDomain');
Route::post('step3/generateDeviceCode', 'Auth\StepController@generateDeviceCode')->middleware(['auth']);
Route::get('step3', 'Auth\StepController@step3')->name("step3")->middleware(['RegisterStorage', 'auth']);
//-------------------------------------------------------------------------//

Route::post('veeam/auth', 'Auth\StepController@veeamAuth')->name("saveStep3")->middleware('auth');
Route::get('finish', 'Auth\StepController@finishRegistration')->middleware('auth');
Route::get('getProgress', 'Auth\StepController@getProgressVal')->middleware('auth');
//--- Login & Complete Register
Route::middleware(['RegisterStorage', 'auth'])->group(function () {
    Route::get('home', 'HomeController@index')->name('home');
    Route::get('/', function () {
        return redirect()->route("home");
    });
    Route::get('step4', 'Auth\StepController@step4')->name("step4");
    Route::get('last_step', 'Auth\StepController@lastStep');
});
//-------------------------------------------------------------------------//

//--- Repositories
Route::middleware(['RegisterStorage', 'auth'])->group(function () {
    Route::get('main', 'Repositories\BackupRepoController@main')->middleware("permission:view_repository");
    Route::get('repositories', 'Repositories\BackupRepoController@getRepositories')->middleware("permission:view_repository");
    Route::get('getRepositoriesContent', 'Repositories\BackupRepoController@getRepositoriesContent')->middleware("permission:view_repository");
    Route::get('createRepository', 'Repositories\BackupRepoController@createRepository')->middleware("permission:add_repository");
    Route::get('updateRepository', 'Repositories\BackupRepoController@updateRepository')->middleware("permission:edit_repository");
    Route::get('deleteRepository', 'Repositories\BackupRepoController@deleteRepository')->middleware("permission:delete_repository");
});
//-------------------------------------------------------------------------//

//--- Backup
Route::middleware(['auth'])->group(function () {
    Route::get('backup/{type}', 'Backup\BackupJobController@main')->middleware("permission:_view_backup");
    Route::get('getBackupJobs/{type}', 'Backup\BackupJobController@getJobs')->middleware("permission:_view_backup");
    Route::get('backup/{type}/add', 'Backup\BackupJobController@addBackupGet')->middleware("permission:_add_backup");
    Route::post('addBackup/{type}', 'Backup\BackupJobController@addBackupPost')->middleware("permission:_add_backup");
    Route::get('backup/{type}/edit/{id}', 'Backup\BackupJobController@editBackupGet')->middleware("permission:_edit_backup");
    Route::post('editBackup/{type}', 'Backup\BackupJobController@editBackupPost')->middleware("permission:_edit_backup");
    Route::get('manageBackupJob/{type}', 'Backup\BackupJobController@manageBackup')->middleware("permission:_actions_backup");
    Route::get('deleteBackupJob/{type}', 'Backup\BackupJobController@deleteBackup')->middleware("permission:_actions_backup");
    Route::get('getBackupJobSession/{type}/{id}', 'Backup\BackupJobController@getJobSessionLogItems')->middleware("permission:_view_backup");
    Route::get('backup/{type}/session/{id}', 'Backup\BackupJobController@jobSessionPage');
    Route::get('getOrganizationUsers', 'Backup\BackupJobController@getOrganizationUsers');
    Route::get('getOrganizationGroups', 'Backup\BackupJobController@getOrganizationGroups');
    Route::get('getOrganizationTeams', 'Backup\BackupJobController@getOrganizationTeams');
    Route::get('getOrganizationSites', 'Backup\BackupJobController@getOrganizationSites');
});
//-------------------------------------------------------------------------//

Route::middleware(['auth'])->group(function () {
    //----- Restore
    Route::get('restore/{type}', 'Restore\RestoreController@main')->middleware(['permission:_create_restore_session']);
    Route::post('generateExchangeDeviceCode', 'Restore\RestoreExchangeController@generateDeviceCode')->middleware(['permission:exchange_create_restore_session']);
    Route::post('generateOnedriveDeviceCode', 'Restore\RestoreOnedriveController@generateDeviceCode')->middleware(['permission:onedrive_create_restore_session']);
    Route::post('generateSharepointDeviceCode', 'Restore\RestoreSharepointController@generateDeviceCode')->middleware(['permission:sharepoint_create_restore_session']);
    Route::post('generateTeamsDeviceCode', 'Restore\RestoreTeamsController@generateDeviceCode')->middleware(['permission:teams_create_restore_session']);
    Route::get('getRestoreTime/{type}/{id}', 'Restore\RestoreController@getRestoreTimes')->middleware(['permission:_create_restore_session']);
    //---------------------------------------//

    //----- Restore Session
    Route::post('createExchangeSession', 'Restore\RestoreExchangeController@createSession')->middleware('permission:exchange_create_restore_session');
    Route::post('createTeamsSession', 'Restore\RestoreTeamsController@createSession')->middleware('permission:teams_create_restore_session');
    Route::post('createSharepointSession', 'Restore\RestoreSharepointController@createSession')->middleware('permission:sharepoint_create_restore_session');
    Route::post('createOnedriveSession', 'Restore\RestoreOnedriveController@createSession')->middleware('permission:onedrive_create_restore_session');
    //---------------------------------------//
});
//---- Restore Teams
Route::middleware(['checkTeamsRestoreSession', 'auth'])->group(function () {
    Route::get('getFilteredTeams', 'Restore\RestoreTeamsController@getFilteredTeams');
    Route::get('getTeamChannels/{siteId}', 'Restore\RestoreTeamsController@getTeamChannels');
    Route::post('getTeamChannelContent', 'Restore\RestoreTeamsController@getTeamChannelContent');
    Route::post('getChannelPostReplies', 'Restore\RestoreTeamsController@getChannelPostReplies');

    Route::post('restoreTeam', 'Restore\RestoreTeamsController@restoreTeam')->middleware("permission:teams_restore_actions");
    Route::post('restoreChannels', 'Restore\RestoreTeamsController@restoreChannels')->middleware("permission:teams_restore_actions");
    Route::post('restoreChannelsPosts', 'Restore\RestoreTeamsController@restoreChannelsPosts')->middleware("permission:teams_restore_actions");
    Route::post('exportChannelsPosts', 'Restore\RestoreTeamsController@exportChannelsPosts')->middleware("permission:teams_export_actions");
    Route::post('restoreChannelsFiles', 'Restore\RestoreTeamsController@restoreChannelsFiles')->middleware("permission:teams_restore_actions");
    Route::post('exportChannelsFiles', 'Restore\RestoreTeamsController@exportChannelsFiles')->middleware("permission:teams_export_actions");
    Route::post('restoreChannelsTabs', 'Restore\RestoreTeamsController@restoreChannelsTabs')->middleware("permission:teams_restore_actions");
    Route::post('restoreTeamsPosts', 'Restore\RestoreTeamsController@restorePosts')->middleware("permission:teams_restore_actions");
    Route::post('restoreTeamsFiles', 'Restore\RestoreTeamsController@restoreFiles')->middleware("permission:teams_restore_actions");
    Route::post('exportTeamsPosts', 'Restore\RestoreTeamsController@exportPosts')->middleware("permission:teams_export_actions");
    Route::post('exportTeamsFiles', 'Restore\RestoreTeamsController@exportFiles')->middleware("permission:teams_export_actions");
    Route::post('restoreTeamsTabs', 'Restore\RestoreTeamsController@restoreTabs')->middleware("permission:teams_restore_actions");
    Route::post('downloadTeamsFile', 'Restore\RestoreTeamsController@downloadFile')->middleware("permission:teams_view_item_details");
    Route::post('downloadTeamsPost', 'Restore\RestoreTeamsController@downloadPost')->middleware("permission:teams_view_item_details");
    Route::post('viewTeamsPost', 'Restore\RestoreTeamsController@viewPost')->middleware("permission:teams_view_item_details");
    //--------------------------------------------------//
});

//---- Restore Sharepoint
Route::middleware(['checkSharepointRestoreSession', 'auth'])->group(function () {
    Route::get('getSiteContent/{siteId}', 'Restore\RestoreSharepointController@getSiteContent');
    Route::get('getFilteredSites', 'Restore\RestoreSharepointController@getFilteredSites');
    Route::post('getSiteContentItems', 'Restore\RestoreSharepointController@getSiteContentItems');

    Route::post('restoreSite', 'Restore\RestoreSharepointController@restoreSite')->middleware("permission:sharepoint_restore_actions");
    Route::post('restoreSiteContent', 'Restore\RestoreSharepointController@restoreSiteContent')->middleware("permission:sharepoint_restore_actions");
    Route::post('exportSiteLibraries', 'Restore\RestoreSharepointController@exportSiteLibraries')->middleware("permission:sharepoint_export_actions");
    Route::post('restoreSiteDocuments', 'Restore\RestoreSharepointController@restoreSiteDocuments')->middleware("permission:sharepoint_restore_actions");
    Route::post('restoreSiteItems', 'Restore\RestoreSharepointController@restoreSiteItems')->middleware("permission:sharepoint_restore_actions");
    Route::post('exportSiteDocuments', 'Restore\RestoreSharepointController@exportSiteDocuments')->middleware("permission:sharepoint_export_actions");
    Route::post('exportSiteItems', 'Restore\RestoreSharepointController@exportSiteItems')->middleware("permission:sharepoint_export_actions");
    Route::post('downloadSiteDocument', 'Restore\RestoreSharepointController@downloadSiteDocument')->middleware("permission:sharepoint_view_item_details");
    Route::post('downloadSiteItem', 'Restore\RestoreSharepointController@downloadSiteItem')->middleware("permission:sharepoint_view_item_details");
    Route::post('restoreSiteFolders', 'Restore\RestoreSharepointController@restoreSiteFolders')->middleware("permission:sharepoint_restore_actions");
    Route::post('exportSiteFolders', 'Restore\RestoreSharepointController@exportSiteFolders')->middleware("permission:sharepoint_export_actions");
    //--------------------------------------------------//
});

//---- Restore Onedrive
Route::middleware(['checkOnedriveRestoreSession', 'auth'])->group(function () {
    Route::get('getFilteredItems', 'Restore\RestoreOnedriveController@getFilteredItems');
    Route::get('getOnedriveFolders/{id}', 'Restore\RestoreOnedriveController@getOnedriveFolders');
    Route::post('getOnedriveFolderItems', 'Restore\RestoreOnedriveController@getOnedriveFolderItems');
    Route::get('getOnedriveUsers', 'Restore\RestoreOnedriveController@getOnedriveUsers');
    Route::post('getUserOnedrives', 'Restore\RestoreOnedriveController@getUserOnedrives');

    Route::post('restoreOneDrive', 'Restore\RestoreOnedriveController@restoreOneDriveOriginal')->middleware("permission:onedrive_restore_actions");
    Route::post('copyOneDrive', 'Restore\RestoreOnedriveController@restoreCopyOneDrive')->middleware("permission:onedrive_restore_actions");
    Route::post('exportOnedrive', 'Restore\RestoreOnedriveController@exportOneDrive')->middleware("permission:onedrive_export_actions");

    Route::post('restoreOnedriveFolder', 'Restore\RestoreOnedriveController@restoreOnedriveFolder')->middleware("permission:onedrive_restore_actions");
    Route::post('exportOnedriveFolders', 'Restore\RestoreOnedriveController@exportOnedriveFolders')->middleware("permission:onedrive_export_actions");

    Route::post('restoreOnedriveDocs', 'Restore\RestoreOnedriveController@restoreOnedriveDocs')->middleware("permission:onedrive_restore_actions");
    Route::post('exportOnedriveDocuments', 'Restore\RestoreOnedriveController@exportOnedriveDocuments')->middleware("permission:onedrive_export_actions");

    Route::post('downloadOnedriveDocument', 'Restore\RestoreOnedriveController@downloadOnedriveDocument')->middleware("permission:onedrive_view_item_details");
    //--------------------------------------------------//

});

//---- Restore Exchange
Route::middleware(['checkExchangeRestoreSession', 'auth'])->group(function () {
    Route::get('getExchangeUsers', 'Restore\RestoreExchangeController@getOrganizationUsers');

    Route::post('restoreMailBoxOriginal', 'Restore\RestoreExchangeController@restoreMailBoxOriginal')->middleware("permission:exchange_restore_actions");
    Route::post('restoreMailBoxAnother', 'Restore\RestoreExchangeController@restoreMailBoxAnother')->middleware("permission:exchange_restore_actions");
    Route::post('restoreFolder', 'Restore\RestoreExchangeController@restoreFolder')->middleware("permission:exchange_restore_actions");
    Route::post('restoreItem', 'Restore\RestoreExchangeController@restoreItem')->middleware("permission:exchange_restore_actions");

    Route::get('getFilteredMailboxes', 'Restore\RestoreExchangeController@getFilteredMailboxes');
    Route::get('getMailBoxFolders/{id}', 'Restore\RestoreExchangeController@getMailBoxFolders');
    Route::post('getMailBoxFolderItems', 'Restore\RestoreExchangeController@getMailBoxFolderItems');

    Route::post('exportMailBoxToPst', 'Restore\RestoreExchangeController@exportMailBoxToPst')->middleware("permission:exchange_export_actions");
    Route::post('exportMailBoxFolderToPst', 'Restore\RestoreExchangeController@exportMailBoxFolderToPst')->middleware("permission:exchange_export_actions");
    Route::post('exportMailBoxFolderItemsToPst', 'Restore\RestoreExchangeController@exportMailBoxFolderItemsToPst')->middleware("permission:exchange_export_actions");
    Route::post('exportMailBoxFolderItemsToZip', 'Restore\RestoreExchangeController@exportMailBoxFolderItemsToZip')->middleware("permission:exchange_export_actions");
    Route::post('downloadMultiItems', 'Restore\RestoreExchangeController@downloadMultiItems')->middleware("permission:exchange_view_item_details");
    Route::post('downloadSingleItem', 'Restore\RestoreExchangeController@downloadSingleItem')->middleware("permission:exchange_view_item_details");

    Route::get('downloadItem/{file}', 'Restore\RestoreController@downloadItem')->name('downloadItem')->middleware("permission:exchange_view_item_details");
    //--------------------------------------------------//
});

//---- Restore History
Route::middleware(['auth'])->group(function () {
    Route::get('restore-history/{type}', 'Restore\RestoreHistoryController@main')->middleware("permission:_view_history");

    Route::get('getHistoryContent/{type}', 'Restore\RestoreHistoryController@getHistoryContent')->middleware("permission:_view_history");
    Route::get('getHistoryDetails/{type}/{id}', 'Restore\RestoreHistoryController@getHistoryDetails')->middleware("permission:_view_history_details");
    Route::post('cancelRestore', 'Restore\RestoreHistoryController@cancelRestore');
    Route::post('forceExpire/{type}', 'Restore\RestoreHistoryController@forceExpire')->middleware("permission:_force_expire");
    Route::get('downloadExportedFile/{type}/{id}', 'Restore\RestoreHistoryController@downloadExportedFile')->middleware("permission:_download_exported_files");
    Route::get('restore/{type}/session/{id}', 'Restore\RestoreHistoryController@restoreSessionPage')->middleware("permission:_view_history_details");
    Route::get('getRestoreDetails/{type}/{id}', 'Restore\RestoreHistoryController@getRestoreDetails');
    Route::get('getRestoreSessionInfo/{type}/{historyId}', 'Restore\RestoreHistoryController@getRestoreSessionInfo')->middleware("permission:_view_history_details");
});
//--------------------------------------------------//

//---- User Profile
Route::middleware(['auth'])->group(function () {
    Route::get('/bactopus-settings', 'Auth\BactopusSettingsController@index')->name('bactopus-settings');
    Route::put('/bactopus-settings/update/{id}', 'Auth\BactopusSettingsController@update')->name('bactopus-settings.update');
    Route::get('/license-management', 'Auth\BactopusSettingsController@license_management')->name('license-management');
    Route::get('/o365-authentication', 'Auth\BactopusSettingsController@o_authentication')->name('o365-authentication');
    Route::get('/backup-account', 'Auth\BactopusSettingsController@backup_account')->name('backup-account');

    Route::post('authUserVeeam', 'Auth\BactopusSettingsController@authUserVeeam');
    Route::get('getBackupApplications', 'Auth\BactopusSettingsController@getBackupApplications');
    Route::post('saveBackupApplication', 'Auth\BactopusSettingsController@saveBackupApplication');
    Route::post('activateBackupApplication', 'Auth\BactopusSettingsController@activateBackupApplication');
    Route::get('getUserVerificationCodes', 'Auth\BactopusSettingsController@getUserVerificationCodes');
    Route::post('saveUserVerificationCode', 'Auth\BactopusSettingsController@saveUserVerificationCode');

    Route::get('notification', 'Auth\BactopusSettingsController@notification')->name('notification');
    Route::post('saveUserNotifications', 'Auth\BactopusSettingsController@saveUserNotifications');
    Route::get('getTimezoneList', 'Auth\BactopusSettingsController@getTimezoneList')->middleware('auth');
});
//-----------------------------------------------------//

//---- E-discovery
Route::middleware(['auth'])->group(function () {
    Route::get('e-discovery/{type}', 'EDiscovery\EDiscoveryController@main')->middleware("permission:_view_ediscovery_jobs");
    Route::get('e-discoveryJobs/{type}', 'EDiscovery\EDiscoveryController@getEDiscoveryJobs')->middleware("permission:_view_ediscovery_jobs");
    Route::get('e-discovery/{type}/add', 'EDiscovery\EDiscoveryController@createEDiscoveryJobPage')->middleware("permission:_add_ediscovery_job");
    Route::get('e-discovery/{type}/edit/{restoreSessionId?}', 'EDiscovery\EDiscoveryController@editEDiscoveryJobPage')->middleware("permission:_edit_ediscovery_job");
    Route::get('e-discovery/{type}/result/{restoreSessionId}', 'EDiscovery\EDiscoveryController@resultEDiscoveryJobPage')->middleware("permission:_view_ediscovery_job_results");
    Route::get('getConditionValue/{type}', 'EDiscovery\EDiscoveryController@getConditionValue');
    Route::get('getEdiscoveryJobResult/{type}/{id}', 'EDiscovery\EDiscoveryController@getEdiscoveryJobResult')->middleware("permission:_view_ediscovery_job_results");
    Route::post('saveEDiscoveryJob', 'EDiscovery\EDiscoveryController@saveEDiscoveryJob');
    Route::post('manageEdiscoveryJob', 'EDiscovery\EDiscoveryController@manageEdiscoveryJob');
    Route::post('deleteEdiscoveryJob/{type}', 'EDiscovery\EDiscoveryController@deleteEdiscoveryJob')->middleware("permission:_delete_ediscovery_job");
    Route::post('runEdiscoveryJob/{type}', 'EDiscovery\EDiscoveryController@reRunEdiscoveryJob')->middleware("permission:_rerun_ediscovery_job");
    Route::post('copyEdiscoveryJob/{type}', 'EDiscovery\EDiscoveryController@copyEdiscoveryJob')->middleware("permission:_edit_ediscovery_job");
    Route::post('expireEdiscoveryJob/{type}', 'EDiscovery\EDiscoveryController@forceExpireEdiscoveryJob')->middleware("permission:_force_expire_ediscovery_job");

    Route::post('setEDiscoveryData/{type}', 'EDiscovery\EDiscoveryController@setEDiscoveryData')->middleware("permission:_add_ediscovery_job");
});
//-----------------------------------------------------//

//---- Users Management
Route::middleware(['auth'])->group(function () {
    Route::get('unauthorized/{code}', 'HomeController@unauthorized')->name("unauthorized");

    Route::get('users-roles', 'Auth\UsersManagementController@main');
    Route::get('getUsers', 'Auth\UsersManagementController@getUsers')->middleware("permission:users_view");
    Route::post('saveUser', 'Auth\UsersManagementController@saveUser');
    Route::get('getRoles', 'Auth\UsersManagementController@getRoles')->middleware("permission:roles_view");
    Route::get('role/add', 'Auth\UsersManagementController@rolePage')->middleware("permission:roles_add");
    Route::get('role/edit/{id}', 'Auth\UsersManagementController@rolePage')->middleware("permission:roles_edit");
    Route::post('saveRole', 'Auth\UsersManagementController@saveRole');
    Route::post('actionUser', 'Auth\UsersManagementController@actionUser')->middleware("permission:users_edit");
});
//-----------------------------------------------------//

