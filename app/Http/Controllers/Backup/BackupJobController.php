<?php

namespace App\Http\Controllers\Backup;

use App\Engine\Veeam\ManagerVeeam;
use App\Models\BacEDiscoveryJob;
use App\Models\BacRestoreHistory;
use App\Models\BacRestoreHistoryDetail;
use App\Models\VeeamBackupJob;
use App\Models\VeeamBackupRepository;
use App\Models\ViewOrganizationBackupJob;
use App\Models\ViewOrganizationRepository;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class BackupJobController extends BaseController
{
    private $_managerVeeam;
    //------------------------------------------//
    public function __construct()
    {
        $this->_managerVeeam = new ManagerVeeam();
    }
    //------------------------------------------//
    //main interface request
    public function main($id)
    {
        try {
            $repo_kind = ($id == "exchange") ? "Exchange" : (($id == "onedrive") ? "OneDrive" : (($id == "sharepoint") ? "SharePoint" : "Teams"));
            $arr = array('typeId' => $id, 'repo_kind' => $repo_kind);
            return response()->view('backUpJobs.main', ["arr" => $arr]);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting backup jobs page  ' . $ex->getMessage());
            return response()->view("errors.500", [], 500);
        }
    }
    //------------------------------------------//
    //get backup jobs
    public function getJobs($id)
    {
        $userJobs = array();
        try {
            $repo_kind = ($id == "exchange") ? "Exchange" : (($id == "onedrive") ? "OneDrive" : (($id == "sharepoint") ? "SharePoint" : "Teams"));
            $user = auth()->user();
            $organization = $user->organization;
            $allJobs = $this->_managerVeeam->getOrganizationBackUpJobs($organization->veeam_organization_guid)['data'];
            $results = ViewOrganizationBackupJob::where("organization_id", $organization->id)->where("backup_job_kind", $repo_kind)->get();
            foreach ($results as $localJob) {
                foreach ($allJobs as $veeamJob) {
                    if ($veeamJob->id == $localJob->backup_job_guid) {
                        $veeamJob->repoName = $localJob->repository_display_name;
                        //---------
                        if (optional($veeamJob)->lastRun) {
                            $date = $veeamJob->lastRun;
                            $tempDate = new DateTime($date);
                            $tempDate->setTimeZone(new DateTimeZone('UTC'));
                            $veeamJob->lastRun = $tempDate->format('Y-m-d\TH:i:s\.u\0\Z');
                        }
                        //---------
                        if (optional($veeamJob)->nextRun) {
                            $date = $veeamJob->nextRun;
                            $tempDate = new DateTime($date);
                            $tempDate->setTimeZone(new DateTimeZone('UTC'));
                            $veeamJob->nextRun = $tempDate->format('Y-m-d\TH:i:s\.u\0\Z');
                        }
                        //---------
                        array_push($userJobs, $veeamJob);
                    }
                }
            }
            return $userJobs;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting backup jobs  ' . $ex->getMessage());
            return [];
        }
    }
    //------------------------------------------//
    //new Backup Job page
    public function addBackupGet($kind)
    {
        if (!in_array($kind, ["exchange", "onedrive", "sharepoint", "teams"])) {
            return abort(404);
        }
        $repos = $this->getUserRepositories($kind == "teams" | $kind == "sharepoint" ? "sharepoint-teams" : $kind);
        $arr = array('repos' => $repos, 'typeId' => $kind);
        return response()->view("backUpJobs.new-$kind-backup", ["arr" => $arr]);
    }
    //------------------------------------------//
    //create new backup job
    public function addBackupPost($type, Request $request)
    {
        try {
            $user = auth()->user();
            //-----------------------------------------//
            $repository = VeeamBackupRepository::select('id')->where('guid', $request->backupData["RepositoryId"])->first();
            $organization = $user->organization;
            $backupData = $request->backupData;
            //-----------------------------------------//
            if ($request->kind == 'exchange' || $request->kind == 'onedrive') {
                $this->updateUserLicenseInfo();
            }
            //-----------------------------------------//
            if (optional(optional($backupData)["schedulePolicy"])["dailyTime"]) {
                $tempDate = new DateTime($backupData["schedulePolicy"]["dailyTime"]);
                $tempDate->setTimeZone(new DateTimeZone('UTC'));
                $backupData["schedulePolicy"]["dailyTime"] = $tempDate->format('H:i');
            }
            $data = $this->_managerVeeam->createVeeamBackupJob($organization->veeam_organization_guid, $backupData, $repository->id);
            $backupJob = new VeeamBackupJob();
            $backupJob->backup_repository_id = $repository->id;
            $backupJob->guid = $data->id;
            $backupJob->name = $data->name;
            $backupJob->kind = strtolower($type);
            $backupJob->size = 0;
            $backupJob->description = $data->description;
            $backupJob->save();
            //----------------------------------//
            if ($user->organization->license_alert_type == 'LICENSE_EXCEEDED' || $user->organization->license_alert_type == 'EXPIRY_DATE') {
                $this->_managerVeeam->manageVeeamJobs($backupJob->id, 'disable');
            }
            //----------------------------------//
            return $backupJob;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while creating veeam backup job   ,  ' . $ex->getMessage());
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
    //------------------------------------------//
    //edit Backup Job page
    public function editBackupGet($kind, $backupGuid)
    {
        $user = auth()->user();
        $backupJob = VeeamBackupJob::where("guid", $backupGuid)->first();
        if (!$backupJob || !in_array($kind, ["exchange", "onedrive", "sharepoint", "teams"])) {
            return abort(404);
        }
        try {
            $job = $this->_managerVeeam->getVeeamJob($backupGuid)['data'];
            $repos = $this->getUserRepositories($kind == "teams" || $kind == "sharepoint" ? "sharepoint-teams" : $kind);
            //-------------------------//
            $selectedItems = $this->_managerVeeam->getVeeamJobSelectedItems($backupGuid)['data'];
            $selectedItemsArr = [];
            if ($kind == "exchange" && count($selectedItems) > 0 && $selectedItems[0]->type != "PartialOrganization") {
                foreach ($selectedItems as $item) {
                    $selectedItemsArr[] = [
                        "id" => optional($item)->user ? optional($item)->user->id : optional($item)->group->id,
                        "name" => optional($item)->user ? optional($item)->user->displayName : optional($item)->group->displayName,
                        "email" => optional($item)->user ? optional($item)->user->name : optional($item)->group->name,
                        "item_type" => optional($item)->user ? "User" : "Group",
                        "usermail" => optional($item)->user ? optional($item)->mailbox : optional($item)->memberMailbox,
                        "userarchived" => optional($item)->user ? optional($item)->archiveMailbox : optional($item)->memberArchiveMailbox,
                        "groupmail" => optional($item)->user ? false : optional($item)->mailbox,
                        "type" => optional($item)->user ? optional($item)->user->type : optional($item)->group->type,
                    ];
                }
            }

            $backupRepo = explode('/', $job->_links->backupRepository->href);
            //-------------------------//
            if (optional(optional($job)->schedulePolicy)->dailyTime) {
                $tempDate = new DateTime($job->schedulePolicy->dailyTime, new DateTimeZone('UTC'));
                $tempDate->setTimeZone(new DateTimeZone($user->timezone ?? config('app.timezone')));
                $job->schedulePolicy->dailyTime = $tempDate->format('H:i');
            }
            //-------------------------//
            $arr = [
                'repos' => $repos,
                'typeId' => $kind,
                'job' => $job,
                'selectedItems' => $selectedItems,
                'backupRepo' => end($backupRepo),
                'selectedItemsArr' => $selectedItemsArr,
            ];
            return response()->view("backUpJobs.edit-$kind-backup", ["arr" => $arr]);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while editing veeam backup job   ,  ' . $ex->getMessage());
            return response()->view("errors.500", [], 500);
        }
    }
    //------------------------------------------//
    //edit  backup job
    public function editBackupPost($type, Request $request)
    {
        $user = auth()->user();
        try {
            //-----------------------------------------//
            $repository = VeeamBackupRepository::select('id')->where('guid', $request->backupData["RepositoryId"])->first();
            //-----------------------------------------//
            if ($request->kind == 'exchange' || $request->kind == 'onedrive') {
                $this->updateUserLicenseInfo();
            }
            //-----------------------------------------//
            $backupData = $request->backupData;
            if (optional(optional($backupData)["schedulePolicy"])["dailyTime"]) {
                $tempDate = new DateTime($backupData["schedulePolicy"]["dailyTime"]);
                $tempDate->setTimeZone(new DateTimeZone('UTC'));
                $backupData["schedulePolicy"]["dailyTime"] = $tempDate->format('H:i');
            }
            //-------------
            $res = $this->_managerVeeam->editVeeamBackupJob($request->jobId, $backupData);
            VeeamBackupJob::where('guid', $request->jobId)->update(
                [
                    'name' => $backupData["name"],
                    'description' => $backupData["description"],
                    'backup_repository_id' => $repository->id,
                ]
            );
            //-----------------------------------//
            if ($user->organization->license_alert_type == 'LICENSE_EXCEEDED' || $user->organization->license_alert_type == 'EXPIRY_DATE') {
                try {
                    $this->_managerVeeam->manageVeeamJobs($request->jobId, 'disable');
                } catch (\Exception $ex) {
                    Log::log('error', 'Exception while disabling backup job ' . $ex->getMessage());
                }
            }
            //-----------------------------------//
            return $res;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while editing veeam backup job  ,  ' . $ex->getMessage());
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
    //------------------------------------------//
    //manage veeam backup job (enable, disable, start, stop)
    public function manageBackup($type, Request $request)
    {
        $user = auth()->user();
        try {
            if ($request->action == 'enable') {
                if ($user->organization->license_alert_type == 'LICENSE_EXCEEDED' || $user->organization->license_alert_type == 'EXPIRY_DATE') {
                    return response()->json(['message' => trans('variables.errors.user_license_count_limit')], 500);
                }
            }
            return $this->_managerVeeam->manageVeeamJobs($request->jobId, $request->action);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while managing veeam backup job   , ' . $ex->getMessage());
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
    //------------------------------------------//
    //delete veeam backup job
    public function deleteBackup($type, Request $request)
    {
        try {
            $backupJob = VeeamBackupJob::where('guid', $request->jobId)->first();
            $eDiscoveryJob = BacEDiscoveryJob::where('backup_job_id', $backupJob->id)->get();
            if (count($eDiscoveryJob) > 0) {
                return response()->json(['message' => str_replace("#var#", $eDiscoveryJob[0]->name, __("variables.alerts.BACKUP_JOB_DELETE_ERROR"))], 500);
            }
            $BacRestoreHistory = BacRestoreHistory::where("backup_job_id", $backupJob->id)->pluck("id")->toArray();
            BacRestoreHistoryDetail::whereIn("restore_history_id", $BacRestoreHistory)->delete();
            BacRestoreHistory::where("backup_job_id", $backupJob->id)->delete();
            VeeamBackupJob::where('guid', $request->jobId)->delete();
            $this->_managerVeeam->deleteVeeamJob($request->jobId);
            return 200;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while deleting veeam backup job   ,  ' . $ex->getMessage());
            return response()->json(['message' => __("variables.alerts.backup_job_used_in_e_discovery")], 500);
        }
    }
    //------------------------------------------//
    //get user's backup repositories
    public function getUserRepositories($repo_kind)
    {
        $specRepositories = array();
        try {
            $allRepositories = $this->_managerVeeam->getVeeamRepository()['data'];
        } catch (Exception $e) {
            Log::log('error', 'Exception while getting repositories from veeam server ' . $e->getMessage());
            return [];
        }

        try {
            $user = auth()->user();
            $results = ViewOrganizationRepository::where("organization_id", $user->organization->id)->where("repository_kind", $repo_kind)->get();
            foreach ($results as $localJob) {
                foreach ($allRepositories as $cloudRepo) {
                    if ($cloudRepo->id == $localJob->repository_guid) {
                        $cloudRepo->name = $localJob->repository_display_name;
                        array_push($specRepositories, $cloudRepo);
                    }
                }
            }
            return $specRepositories;
        } catch (Exception $e) {
            Log::log('error', 'Exception while getting repositories ' . $e->getMessage());
            return [];
        }
    }
    //------------------------------------------//
    //job sessions page
    public function jobSessionPage($type, $backupCloudJobId)
    {
        $job = VeeamBackupJob::where("guid", $backupCloudJobId)->first();
        if (!$job) {
            return abort(404);
        }

        try {
            $backupJob = $this->_managerVeeam->getVeeamJob($backupCloudJobId);

            $jobStatus = $backupJob["data"]->lastStatus;

            $arr = array('typeId' => $type, 'repo_kind' => $type, "jobId" => $backupCloudJobId, "jobStatus" => $jobStatus);
            return response()->view('backUpJobs.session', ["arr" => $arr]);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting job sessions page  ' . $ex->getMessage());
            return response()->view("errors.500", [], 500);
        }
    }
    //------------------------------------------//
    //get all organization users
    public function getOrganizationUsers(Request $request)
    {
        try {
            $res = [];
            $user = auth()->user();
            $user_id = $user->id;
            $organization = $user->organization;
            $data = $this->_managerVeeam->getOrganizationUsers($organization->veeam_organization_guid, 10000, $request->offset, optional($request)->setId)['data'];
            $users = $data->results;
            $usersLinks = $data->_links;
            $setId = optional($data)->setId;
            $tempUsers = [];
            $users = array_merge($users, $tempUsers);
            foreach ($users as $user) {
                if (strpos($user->name, 'DiscoverySearchMailbox') === false) {
                    array_push($res, $user);
                }
            }
            if ($request->withLinks) {
                $links = [];
                foreach ($usersLinks as $key => $link) {
                    $tempArr = [];
                    $url_components = parse_url($link->href);
                    parse_str($url_components['query'], $tempArr);
                    $links[$key] = $tempArr["offset"];
                }
                return ["data" => $res, "links" => $links, "setId" => $setId];
            }
            return $res;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting users ' . $ex->getMessage());
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
    //------------------------------------------//
    //get all organization groups
    public function getOrganizationGroups(Request $request)
    {
        try {
            $user = auth()->user();
            $organization = $user->organization;
            $data = $this->_managerVeeam->getOrganizationGroups($organization->veeam_organization_guid, 10000, $request->offset, optional($request)->setId)['data'];
            $groups = $data->results;
            $groupsLinks = $data->_links;
            $setId = optional($data)->setId;

            if ($request->withLinks) {
                $links = [];
                foreach ($groupsLinks as $key => $link) {
                    $tempArr = [];
                    $url_components = parse_url($link->href);
                    parse_str($url_components['query'], $tempArr);
                    $links[$key] = $tempArr["offset"];
                }
                return ["data" => $groups, "links" => $links, "setId" => $setId];
            }
            return $groups;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting groups ' . $ex->getMessage());
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
    //------------------------------------------//
    //get all organization Sites
    public function getOrganizationSites(Request $request)
    {
        set_time_limit(0);
        $res = [];
        try {
            $user = auth()->user();
            $user_id = $user->id;
            $organization = $user->organization;
            $sites = $this->_managerVeeam->getOrganizationSites($organization->veeam_organization_guid,50, $request->offset ? $request->offset : 0)['data']->results;

            foreach ($sites as $site) {
                if ($site->isPersonal == false) {
                    array_push($res, $site);
                }
            }
            return $res;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting sites ' . $ex->getMessage());
            return [];
        }
    }
    //------------------------------------------//
    //get all organization Teams
    public function getOrganizationTeams()
    {
        try {
            $user = auth()->user();
            $organization = $user->organization;
            $groups = $this->_managerVeeam->getOrganizationTeams($organization->veeam_organization_guid)['data']->results;
            return $groups;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting teams ' . $ex->getMessage());
            return [];
        }
    }
    //------------------------------------------//
    //get none backed organization groups
    public function getNoneBackedOrgaGroups()
    {
        try {
            $res = [];
            $user = auth()->user();
            $organization = $user->organization;
            $groups = $this->_managerVeeam->getOrganizationGroups($organization->veeam_organization_guid)['data']->results;
            foreach ($groups as $group) {
                if ($group->isBackedup == false) {
                    array_push($res, $group);
                }
            }
            return $res;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting groups ' . $ex->getMessage());
            return [];
        }
    }
    //------------------------------------------//
    //get job session Log Items
    public function getJobSessionLogItems($type, $backupJobGuid)
    {
        $jobSessions = array();
        $total = 0;
        try {
            if ($backupJobGuid == "" || $backupJobGuid == null) {
                return ["session" => null, "logItems" => []];
            }
            $jobStatus = $this->_managerVeeam->getVeeamJob($backupJobGuid)["data"]->lastStatus;
            $allSessions = $this->_managerVeeam->getVeeamJobSessions($backupJobGuid)['data'];
            if ($allSessions != null && !empty($allSessions->results)) {
                $session = $allSessions->results[0];
                $sessionDuration = strtotime($session->endTime) - strtotime($session->creationTime);
                if ($jobStatus == "Running") {
                    $sessionDuration = strtotime(now()) - strtotime($session->creationTime);
                }

                $session->duration = $sessionDuration == 0 ? "" : gmdate("H:i:s", $sessionDuration);
                $logItems = $this->_managerVeeam->getVeeamJobSessionLogItems($session->id)['data']->results;

                foreach ($logItems as $itm) {
                    $tmp = strtotime($itm->endTime) - strtotime($itm->creationTime);
                    $itm->duration = $tmp == 0 ? "" : $tmp;
                }

                if (!empty($logItems) && count($logItems) > 3) {

                    preg_match_all('!\d+!', $logItems[3]->title, $total);
                }
                if (!empty($total) && !empty($total[0])) {
                    $session->totalObj = $total[0][0];
                } else {
                    $session->totalObj = 0;
                }
                return ["session" => $session, "logItems" => $logItems];
            } else {
                return ["session" => null, "logItems" => []];
            }
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting backup job session log items  ' . $ex->getMessage());
            return ["session" => null, "logItems" => []];
        }
    }
    //------------------------------------------//
    private function updateUserLicenseInfo()
    {
        $user = auth()->user();
        $organization = $user->organization;
        $licensedUsers = $this->_managerVeeam->getLicensedUsers($organization->veeam_organization_guid)['data'];
        $organization->veeam_licensed_users = $licensedUsers->licensedUsers;
        $organization->veeam_trial_users = $licensedUsers->newUsers;
        $organization->save();
    }
    //------------------------------------------//
    private function getBackupJobSelectedItems($organizationGuid,$backupJobGuid,$backupJobKind){
        $data = $this->_managerVeeam->getVeeamJobSelectedItems($backupJobGuid)["data"];
        $selectedItems = [];
        if($backupJobKind == "exchange" || $backupJobKind == "onedrive"){
            foreach($data as $item){
                if($item->type == "User"){
                    $temp = [];
                    $temp["type"] = "User";
                    $temp["id"] = $item->user->id;
                    $temp["name"] = $item->user->displayName;
                    $temp["second_name"] = $item->user->name;
                    if($backupJobKind == "exchange"){
                        $temp["mailbox"] = $item->mailbox;
                        $temp["archive"] = $item->archiveMailbox;
                    }
                    if($backupJobKind == "onedrive"){
                        $temp["onedrive"] = $item->oneDrive;
                        $temp["site"] = $item->site;
                    }
                    $selectedItems[] = $temp;
                } else if($item->type == "Group"){
                    $temp = [];
                    $temp["type"] = "Group";
                    $temp["id"] = $item->group->id;
                    $temp["name"] = $item->group->displayName;
                    $temp["second_name"] = $item->group->name;
                    $temp["type"] = $item->group->type;
                    if($backupJobKind == "exchange"){
                        $temp["mailbox"] = $item->memberMailbox;
                        $temp["archive"] = $item->memberArchiveMailbox;
                    }
                    if($backupJobKind == "onedrive"){
                        $temp["user_onedrive"] = $item->memberOnedrive;
                        $temp["user_site"] = $item->memberSite;
                        $temp["group_site"] = $item->groupSite;
                    }
                    $selectedItems[] = $temp;
                } else if($item->type == "PartialOrganization"){
                    //------------------------------------------//
                    $usersArr = $groupsArr = [];
                    $users = $this->_managerVeeam->getOrganizationUsers($organizationGuid,10000,0)["data"]->results;
                    $usersArr = array_merge($usersArr,$users);
                    $offset = 10000;
                    while(count($users) == 10000){
                        $users = $this->_managerVeeam->getOrganizationUsers($organizationGuid,10000,$offset)["data"]->results;
                        $usersArr = array_merge($usersArr,$users);
                        $offset += 10000;
                    }
                    //------------------------------------------//
                    $groups = $this->_managerVeeam->getOrganizationGroups($organizationGuid,10000,0)["data"]->results;
                    $groupsArr = array_merge($groupsArr,$groups);
                    $offset = 10000;
                    while(count($groups) == 10000){
                        $groups = $this->_managerVeeam->getOrganizationGroups($organizationGuid,10000,$offset)["data"]->results;
                        $groupsArr = array_merge($groupsArr,$groups);
                        $offset += 10000;
                    }
                    //------------------------------------------//
                    foreach($usersArr as $user){
                        $temp = [];
                        $temp["type"] = "User";
                        $temp["id"] = $user->id;
                        $temp["name"] = $user->displayName;
                        $temp["second_name"] = $user->name;
                        if($backupJobKind == "exchange"){
                            $temp["mailbox"] = $item->mailbox;
                            $temp["archive"] = $item->archiveMailbox;
                        }
                        if($backupJobKind == "onedrive"){
                            $temp["onedrive"] = $item->oneDrive;
                            $temp["site"] = $item->site;
                        }
                        $selectedItems[] = $temp;
                    }
                    //------------------------------------------//
                    foreach($groupsArr as $group){
                        $temp = [];
                        $temp["type"] = "Group";
                        $temp["id"] = $group->id;
                        $temp["name"] = $group->displayName;
                        $temp["type"] = $group->type;
                        $temp["second_name"] = $group->name;
                        if($backupJobKind == "exchange"){
                            $temp["mailbox"] = $item->mailbox;
                            $temp["archive"] = $item->archiveMailbox;
                        }
                        if($backupJobKind == "onedrive"){
                            $temp["user_onedrive"] = $item->oneDrive;
                            $temp["user_site"] = $item->site;
                            $temp["group_site"] = $item->site;
                        }
                        $selectedItems[] = $temp;
                    }
                    //------------------------------------------//
                }
            }
        } else if($backupJobKind == "sharepoint"){
            foreach($data as $item){
                if($item->type == "Site"){
                    $temp = [];
                    $temp["type"] = "Site";
                    $temp["id"] = $item->site->id;
                    $temp["name"] = $item->site->name;
                    $temp["second_name"] = $item->site->url;
                    $temp["site"] = true;
                    $selectedItems[] = $temp;
                } else if($item->type == "PartialOrganization"){
                    //------------------------------------------//
                    $sitesArr = [];
                    $sites = $this->_managerVeeam->getOrganizationSites($organizationGuid,10000,0)["data"]->results;
                    $sitesArr = array_merge($sitesArr,$sites);
                    $offset = 10000;
                    while(count($sites) == 10000){
                        $sites = $this->_managerVeeam->getOrganizationSites($organizationGuid,10000,$offset)["data"]->results;
                        $sitesArr = array_merge($sitesArr,$sites);
                        $offset += 10000;
                    }
                    //------------------------------------------//
                    foreach($sitesArr as $site){
                        $temp = [];
                        $temp["type"] = "Site";
                        $temp["id"] = $site->id;
                        $temp["name"] = $site->name;
                        $temp["second_name"] = $site->url;
                        $temp["site"] = true;
                        $selectedItems[] = $temp;
                    }
                    //------------------------------------------//
                }
            }
        } else if($backupJobKind == "sharepoint"){
            foreach($data as $item){
                if($item->type == "Team"){
                    $temp = [];
                    $temp["type"] = "Team";
                    $temp["id"] = $item->team->id;
                    $temp["name"] = $item->team->displayName;
                    $temp["second_name"] = $item->team->mail;
                    $temp["team"] = true;
                    $selectedItems[] = $temp;
                } else if($item->type == "PartialOrganization"){
                    //------------------------------------------//
                    $teamsArr = [];
                    $teams = $this->_managerVeeam->getOrganizationTeams($organizationGuid,10000,0)["data"]->results;
                    $teamsArr = array_merge($teamsArr,$teams);
                    $offset = 10000;
                    while(count($teams) == 10000){
                        $teams = $this->_managerVeeam->getOrganizationTeams($organizationGuid,10000,$offset)["data"]->results;
                        $teamsArr = array_merge($teamsArr,$teams);
                        $offset += 10000;
                    }
                    //------------------------------------------//
                    foreach($teamsArr as $team){
                        $temp = [];
                        $temp["type"] = "Team";
                        $temp["id"] = $team->id;
                        $temp["name"] = $team->displayName;
                        $temp["second_name"] = $team->mail;
                        $temp["team"] = true;
                        $selectedItems[] = $temp;
                    }
                    //------------------------------------------//
                }
            }
        }
        return $selectedItems;
    }
    //------------------------------------------//
}
