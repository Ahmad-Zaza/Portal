<?php

namespace App\Http\Controllers\Restore;

use App\Engine\Veeam\ManagerVeeam;
use App\Models\VeeamBackupJob;
use App\Models\ViewOrganizationBackupJob;
use DateTime;
use DateTimeZone;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class RestoreController extends BaseController
{
    public $_managerVeeam;
    public function __construct()
    {
        $this->_managerVeeam = new ManagerVeeam();
    }
    //main interface request
    public function main($kind)
    {
        try {
            $user = auth()->user();
            $organization = $user->organization;
            $jobs = ViewOrganizationBackupJob::where("backup_job_kind", $kind)->where("organization_id", $organization->id)->get();
            $data = [
                'repo_kind' => $kind,
                'jobs' => $jobs,
                "itemsWarningLimit" => config('parameters.EXPLORING_RESTORE_ITEMS_WARNING_COUNT'),
                "itemsStoppingLimit" => config('parameters.EXPLORING_RESTORE_ITEMS_STOPPING_COUNT'),
                "itemsLimit" => config('parameters.EXPLORING_RESTORE_ITEMS_LIMIT_COUNT'),
            ];
            if (!view()->exists("restore.$kind")) {
                return abort(404);
            }
            return response()->view("restore.$kind", ["data" => $data]);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Restore Page  ' . $ex->getMessage());
            return response()->view("errors.500", [], 500);
        }
    }
    //------------------------------------------------//
    //get job Backup times
    public function getRestoreTimes($kind, $jobId)
    {
        $timesSessions = array();
        $user = auth()->user();
        try {
            if ($jobId == "" || $jobId == null) {
                return [];
            }
            $timesSessions = [];
            if ($jobId == "all") {
                //-------------------------//
                $organization = $user->organization;
                $backupJobs = ViewOrganizationBackupJob::where("backup_job_kind", $kind)->where("organization_id", $organization->id)->get();
                //-------------------------//
                if (count($backupJobs) == 0) {
                    return [];
                }
                //-------------------------//
                $timesSessions = [];
                foreach ($backupJobs as $backupJob) {
                    $allSessions = $this->_managerVeeam->getVeeamJobSessions($backupJob->backup_job_guid)['data'];
                    if ($allSessions != null && !empty($allSessions->results)) {
                        foreach ($allSessions->results as $session) {
                            //-------------------
                            $tempDate = new DateTime($session->endTime, new DateTimeZone($user->timezone ?? config('app.timezone')));
                            $tempDate->setTimeZone(new DateTimeZone('UTC'));
                            $session->endTime = $tempDate->format('Y-m-d\TH:i:s\.u\0\Z');
                            //-------------------
                            if (($session->status == "Success" || $session->status == "Warning") && $session->progress > 0) {
                                array_push($timesSessions, ['date' => $session->endTime, 'id' => $backupJob->backup_job_id]);
                            }
                        }
                    }
                }
                //-------------------------//
                return $timesSessions;
            } else {
                $veeamBackupJob = VeeamBackupJob::where('id', $jobId)->first();
                $allSessions = $this->_managerVeeam->getVeeamJobSessions($veeamBackupJob->guid)['data'];
                if ($allSessions != null && !empty($allSessions->results)) {
                    foreach ($allSessions->results as $session) {
                        //-------------------
                        $tempDate = new DateTime($session->endTime, new DateTimeZone($user->timezone ?? config('app.timezone')));
                        $tempDate->setTimeZone(new DateTimeZone('UTC'));
                        $session->endTime = $tempDate->format('Y-m-d\TH:i:s\.u\0\Z');
                        //-------------------
                        if (($session->status == "Success" || $session->status == "Warning") && $session->progress > 0) {
                            array_push($timesSessions, ['date' => $session->endTime, 'id' => $veeamBackupJob->id]);
                        }

                    }
                    return $timesSessions;
                }
                return [];
            }
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Backup Job Sessions  ' . $ex->getMessage());
            return response()->json(['message' => "Error While Getting Backup Job Time"], 500);
        }
    }
    //------------------------------------------------//
    //get all organization Users
    public function getOrganizationUsers()
    {
        try {
            $res = [];
            $user = auth()->user();
            $organization = $user->organization;
            $users = $this->_managerVeeam->getOrganizationUsers($organization->veeam_organization_guid)['data']->results;
            $groups = $this->_managerVeeam->getOrganizationGroups($organization->veeam_organization_guid)['data']->results;
            foreach ($users as $user) {
                if (optional($user)->email) {
                    $item = $user;
                    $item->name = $user->email;
                    $item->displayName = $user->name;
                    array_push($res, $item);
                } else {
                    array_push($res, $user);
                }
            }
            foreach ($groups as $group) {
                if (optional($group)->name) {
                    array_push($res, $group);
                }
            }
            usort($res, function ($a, $b) {
                return strcmp(strtolower($a->displayName), strtolower($b->displayName));
            });
            return $res;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception while getting users & groups' . $ex->getMessage());
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
    //------------------------------------------------//
}
