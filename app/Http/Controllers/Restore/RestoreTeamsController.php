<?php

namespace App\Http\Controllers\Restore;

use App\Engine\Azure\ManagerAzure;
use App\Engine\Veeam\ManagerVeeam;
use App\Jobs\ExportTeamsBackground;
use App\Jobs\RestoreTeamBackground;
use App\Models\BacRestoreHistory;
use App\Models\BacRestoreHistoryDetail;
use App\Models\BacTempBlobFile;
use App\Models\Organization;
use App\Models\RestoreTempBlobFile;
use App\Models\VeeamBackupJob;
use App\Models\ViewOrganizationBackupJob;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RestoreTeamsController extends BaseController
{
    private $_managerVeeam;
    public function __construct()
    {
        $this->_managerVeeam = new ManagerVeeam();
        $this->_managerAzure = new ManagerAzure();
    }

    //------------------------------------------------//
    //create restore session
    public function createSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'time' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }

        try {
            //--------------------------------------//
            if ($this->checkRestorePeriod()) {
                return response()->json(['message' => trans('variables.errors.restore_expiry_date')], 500);
            }
            //--------------------------------------//
            if (session('restoreTeamsSessionId')) {
                $session = $this->_managerVeeam->getRestoreSession(session('restoreTeamsSessionId'))['data'];
                if ($session->state != "Stopped") {
                    $this->_managerVeeam->stopRestoreSession(session('restoreTeamsSessionId'));
                }

                session()->forget('restoreTeamsSessionId');
            }
        } catch (\Exception $ex) {
        }

        try {
            if ($request->jobs == 'all') {
                $restoreSession = $this->createRestoreSession($request->time, $request->showDeleted, $request->showVersions);
            } else {
                $job = VeeamBackupJob::where("id", $request->jobs)->first();
                $restoreSession = $this->_managerVeeam->createJobRestoreSession($job->guid, $request->time, 'vet', $request->showDeleted, $request->showVersions);
            }

            //--------------------------------------//
            $items = $this->_managerVeeam->getTeams($restoreSession->id)['data']->results;
            session()->put('restoreTeamsSessionId', $restoreSession->id);
            //------------------------------------------------//
            //-- Sort Teams
            $all = $this->filterItems($items, 'asc');
            //------------------------------------------------//
            return ['data' => $all];
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Creating Restore Sessions  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.create_restore_session')], 500);
        }
    }
    //------------------------------------------------//
    //filter teams
    private function filterItems($arr, $sorting = 'asc', $privacy = '', $letters = '')
    {
        foreach ($arr as $key => $item) {
            if ($privacy == "public") {
                if ($item->privacy == "Private") {
                    unset($arr[$key]);
                }
            } else if ($privacy == "private") {
                if ($item->privacy == "Public") {
                    unset($arr[$key]);
                }
            }
        }
        $arr = array_values($arr);
        if ($letters) {
            $letters = explode(',', $letters);
            foreach ($arr as $key => $item) {
                if (!$item->displayName) {
                    $item->name = "My Organization";
                }

                if (!in_array(str_split($item->displayName)[0], $letters)) {
                    unset($arr[$key]);
                }
            }
        }
        $arr = array_values($arr);
        if ($sorting == 'asc') {
            usort($arr, function ($a, $b) {
                return strcmp(strtolower($a->displayName), strtolower($b->displayName));
            });
        } else {
            usort($arr, function ($a, $b) {
                return -1 * strcmp(strtolower($a->displayName), strtolower($b->displayName));
            });
        }
        $arr = array_values($arr);
        return $arr;
    }
    //------------------------------------------------//
    //get filtered items
    public function getFilteredTeams(Request $request)
    {
        $sort = $request->sortBoxType;
        $privacyType = $request->privacyType;
        $letters = $request->letters;
        try {
            $sessionCheck = $this->checkRestoreSession();
            if ($sessionCheck) {
                return $sessionCheck;
            }

            $items = $this->_managerVeeam->getTeams(session("restoreTeamsSessionId"))['data']->results;
            return $this->filterItems($items, ($sort == 'AZ' ? 'asc' : 'desc'), $privacyType, $letters);
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Filtered Teams ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.get_teams')], 500);
        }
    }
    //------------------------------------------------//
    //get team channels
    public function getTeamChannels($teamId)
    {
        if ($teamId == "" || $teamId == null) {
            return response()->json(['message' => trans('variables.errors.teams.teams_required')], 500);
        }
        try {
            $sessionCheck = $this->checkRestoreSession();
            if ($sessionCheck) {
                return $sessionCheck;
            }

            $arr = $this->_managerVeeam->getTeamChannels(session('restoreTeamsSessionId'), $teamId)['data']->results;
            $data = [];
            foreach ($arr as $item) {
                $item->teamId = $teamId;
                array_push($data, $item);
            }
            return $data;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Team Content  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.get_teams_channels')], 500);
        }
    }
    //------------------------------------------------//
    //Get Channel Content
    public function getTeamChannelContent(Request $request)
    {
        if ($request->input("teamId") == "-1") {
            return [];
        }

        $type = $request->input('type');
        if ($type == "post") {
            return $this->getChannelPosts($request);
        } else if ($type == "file") {
            return $this->getChannelFiles($request);
        } else if ($type == "tab") {
            return $this->getChannelTabs($request);
        }
    }
    //------------------------------------------------//
    //Get Channel Posts
    public function getChannelPosts(Request $request)
    {
        //-------------------------------------//
        $teamId = $request->input('teamId');
        $channelId = $request->input('channelId');
        $teamTitle = $request->input('teamTitle');
        $channelTitle = $request->input('channelTitle');
        $offset = $request->input('offset');
        //--------------------//
        $limit = config('parameters.EXPLORING_RESTORE_ITEMS_LIMIT_COUNT');
        //--------------------//
        $res = array();
        try {
            $sessionCheck = $this->checkRestoreSession();
            if ($sessionCheck) {
                return $sessionCheck;
            }

            $data = $this->_managerVeeam->getChannelPosts(session('restoreTeamsSessionId'), $teamId, $channelId, $offset, $limit);
            $items = $data['data']->results;
            //-------------------------------------------------//
            foreach ($items as $key => $item) {
                $item->teamId = $teamId;
                $item->teamTitle = $teamTitle;
                $item->channelId = $channelId;
                $item->channelTitle = $channelTitle;
                $item->type = $request->input('type');
            }
            //-------------------------------//
            return array_values($items);
            //-------------------------------------------------//
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Team Channel Posts ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.get_teams_channels_posts')], 500);
        }
    }
    //------------------------------------------------//
    public function getChannelPostReplies(Request $request)
    {
        //-------------------------------------//
        $postId = $request->input('postId');
        $teamId = $request->input('teamId');
        $channelId = $request->input('channelId');
        $teamTitle = $request->input('teamTitle');
        $channelTitle = $request->input('channelTitle');
        //--------------------//
        try {
            $sessionCheck = $this->checkRestoreSession();
            if ($sessionCheck) {
                return $sessionCheck;
            }

            $data = $this->_managerVeeam->getChannelPostReplies(session('restoreTeamsSessionId'), $teamId, $channelId, $postId);
            $items = $data['data']->results;
            //-------------------------------------------------//
            foreach ($items as $item) {
                $item->teamId = $teamId;
                $item->teamTitle = $teamTitle;
                $item->channelId = $channelId;
                $item->channelTitle = $channelTitle;
                $item->type = 'post';
                $item->parentPostId = $postId;
            }
            //-------------------------------//
            return array_values($items);
            //-------------------------------------------------//
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Team Channel Post Replies ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.get_teams_channels_posts'), 'postId' => $postId], 500);
        }
    }
    //------------------------------------------------//
    //Get Channel Files
    public function getChannelFiles(Request $request)
    {
        //-------------------------------------//
        $teamId = $request->input('teamId');
        $channelId = $request->input('channelId');
        $folderId = $request->input('folderId');
        $teamTitle = $request->input('teamTitle');
        $channelTitle = $request->input('channelTitle');
        $offset = $request->input('offset');
        //--------------------//
        $limit = config('parameters.EXPLORING_RESTORE_ITEMS_LIMIT_COUNT');
        //--------------------//
        $res = array();
        try {
            $sessionCheck = $this->checkRestoreSession();
            if ($sessionCheck) {
                return $sessionCheck;
            }

            $data = $this->_managerVeeam->getChannelFiles(session('restoreTeamsSessionId'), $teamId, $channelId, $folderId, $offset, $limit);
            $items = $data['data'];
            foreach ($items->results as $item) {
                $item->teamId = $teamId;
                $item->teamTitle = $teamTitle;
                $item->channelId = $channelId;
                $item->channelTitle = $channelTitle;
                $item->isFolder = ($item->type == "File" ? false : true);
                $item->type = $request->input('type');

                array_push($res, $item);
            }
            return $res;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Team Channel Files ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.get_teams_channels_posts')], 500);
        }
    }
    //------------------------------------------------//
    //Get Channel Tabs
    public function getChannelTabs(Request $request)
    {
        //-------------------------------------//
        $teamId = $request->input('teamId');
        $channelId = $request->input('channelId');
        $teamTitle = $request->input('teamTitle');
        $channelTitle = $request->input('channelTitle');
        $offset = $request->input('offset');
        //--------------------//
        $limit = config('parameters.EXPLORING_RESTORE_ITEMS_LIMIT_COUNT');
        //--------------------//
        $res = array();
        try {
            $sessionCheck = $this->checkRestoreSession();
            if ($sessionCheck) {
                return $sessionCheck;
            }

            $data = $this->_managerVeeam->getChannelTabs(session('restoreTeamsSessionId'), $teamId, $channelId, $offset, $limit);
            $items = $data['data'];
            foreach ($items->results as $item) {
                $item->teamId = $teamId;
                $item->teamTitle = $teamTitle;
                $item->channelId = $channelId;
                $item->channelTitle = $channelTitle;
                $item->tabType = $item->type;
                $item->type = $request->input('type');
                array_push($res, $item);
            }
            return $res;
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Getting Team Channel Tabs ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.get_teams_channels_tabs')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Teams
    public function restoreTeam(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        if ($request['restoreChangedItems'] == 'false' && $request['restoreMissingItems'] == 'false') {
            return response()->json(['message' => trans('variables.errors.restore_required_options')], 500);
        }
        $organization = auth()->user()->organization;
        //-------------------------------------//
        if (!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $teams = $request->input('teams');
        if ($teams == "" || $teams == null || $teams == "[]") {
            return response()->json(['message' => trans('variables.errors.teams.teams_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//
        $teamsArr = json_decode($teams);
        //-------------------------------------//

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['restoreChangedItems'] = $requestData['restoreChangedItems'];
        $options['restoreMissingItems'] = $requestData['restoreMissingItems'];
        $options['restoreMembers'] = $requestData['restoreMembers'];
        $options['restoreSettings'] = $requestData['restoreSettings'];
        try {
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->restore_session_guid = $restoreSessionGuid;
            $history->type = 'team';
            $history->sub_type = 'Restore Teams';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->items_count = count($teamsArr);
            $history->name = $requestData["restoreJobName"];
            $history->save();
            //--------------------------------------//
            $details = new BacRestoreHistoryDetail();
            $details->restore_history_id = $history->id;
            $details->item_id = json_encode($teamsArr);
            $details->status = 'In Progress';
            $details->save();
            //--------------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $teamData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreTeamBackground(auth()->id(), $history->id, 'restoreTeam', $sessionData, $teamData));
            //--------------------------------------//
            session()->forget("backgroundTeamsRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.teams.restore_teams')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Teams' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.restore_teams')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Channels
    public function restoreChannels(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        $organization = auth()->user()->organization;
        //-------------------------------------//
        if (!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $channels = $request->input('channels');
        if ($channels == "" || $channels == null || $channels == "[]") {
            return response()->json(['message' => trans('variables.errors.teams.channels_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['restoreMissingItems'] = $requestData['restoreMissingItems'];
        $options['restoreChangedItems'] = $requestData['restoreChangedItems'];
        try {
            $channelsArr = json_decode($channels);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->restore_session_guid = $restoreSessionGuid;
            $history->type = 'channel';
            $history->items_count = count($channelsArr);
            $history->name = $requestData["restoreJobName"];
            $history->sub_type = 'Restore Channels';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            foreach ($channelsArr as $item) {
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->name;
                $details->item_parent_id = $item->teamId;
                $details->item_parent_name = $item->teamName;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $teamData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreTeamBackground(auth()->id(), $history->id, 'restoreChannels', $sessionData, $teamData));
            //--------------------------------------//
            session()->forget("backgroundTeamsRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.teams.restore_channels')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Channels' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.restore_channels')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Channels Posts
    public function restoreChannelsPosts(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        $organization = auth()->user()->organization;
        //-------------------------------------//
        if (!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $channels = $request->input('channels');
        if ($channels == "" || $channels == null || $channels == "[]") {
            return response()->json(['message' => trans('variables.errors.teams.channels_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['pointType'] = $requestData['pointType'];
        if ($options['pointType'] == 'custom') {
            $options['from'] = gmdate('Y-m-d\TH:i:s\.u\0\Z', strtotime($requestData['restoreFrom']));
            $options['to'] = gmdate('Y-m-d\TH:i:s\.u\0\Z', strtotime($requestData['restoreTo']));
        }
        try {
            $channelsArr = json_decode($channels);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->restore_session_guid = $restoreSessionGuid;
            $history->type = 'channel-posts';
            $history->items_count = count($channelsArr);
            $history->name = $requestData["restoreJobName"];
            $history->sub_type = 'Restore Channels Posts';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            foreach ($channelsArr as $item) {
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->name;
                $details->item_parent_id = $item->teamId;
                $details->item_parent_name = $item->teamName;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $teamData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreTeamBackground(auth()->id(), $history->id, 'restoreChannelsPosts', $sessionData, $teamData));
            //--------------------------------------//
            session()->forget("backgroundTeamsRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.teams.restore_channels_posts')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Channels Posts ' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.restore_channels_posts')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Channels Posts
    public function restoreChannelsFiles(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }

        if ($request["restoreChangedItems"] == 'false' && $request["restoreMissingItems"] == 'false') {
            return response()->json(['message' => trans('variables.errors.restore_required_options')], 500);
        }
        $organization = auth()->user()->organization;
        //-------------------------------------//
        if (!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $channels = $request->input('channels');
        if ($channels == "" || $channels == null || $channels == "[]") {
            return response()->json(['message' => trans('variables.errors.teams.channels_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['restoreMissingItems'] = $requestData['restoreMissingItems'];
        $options['restoreChangedItems'] = $requestData['restoreChangedItems'];
        $options['fileVersion'] = $requestData['fileVersion'];
        $options['fileLastVersionAction'] = $requestData['fileLastVersionAction'];
        try {
            $channelsArr = json_decode($channels);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->restore_session_guid = $restoreSessionGuid;
            $history->type = 'channel-files';
            $history->items_count = count($channelsArr);
            $history->name = $requestData["restoreJobName"];
            $history->sub_type = 'Restore Channels Files';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            foreach ($channelsArr as $item) {
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->name;
                $details->item_parent_id = $item->teamId;
                $details->item_parent_name = $item->teamName;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $teamData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreTeamBackground(auth()->id(), $history->id, 'restoreChannelsFiles', $sessionData, $teamData));
            //--------------------------------------//
            session()->forget("backgroundTeamsRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.teams.restore_channels_files')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Channels Files ' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.restore_channels_files')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Channels Posts
    public function restoreChannelsTabs(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        $organization = auth()->user()->organization;
        //-------------------------------------//
        if (!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $channels = $request->input('channels');
        if ($channels == "" || $channels == null || $channels == "[]") {
            return response()->json(['message' => trans('variables.errors.teams.channels_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['restoreMissingItems'] = $requestData['restoreMissingItems'];
        $options['restoreChangedItems'] = $requestData['restoreChangedItems'];
        try {
            $channelsArr = json_decode($channels);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->restore_session_guid = $restoreSessionGuid;
            $history->type = 'channel-tabs';
            $history->items_count = count($channelsArr);
            $history->name = $requestData["restoreJobName"];
            $history->sub_type = 'Restore Channels Tabs';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            foreach ($channelsArr as $item) {
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->name;
                $details->item_parent_id = $item->teamId;
                $details->item_parent_name = $item->teamName;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $teamData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreTeamBackground(auth()->id(), $history->id, 'restoreChannelsTabs', $sessionData, $teamData));
            //--------------------------------------//
            session()->forget("backgroundTeamsRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.teams.restore_channels_tabs')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Channels Tabs ' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.restore_channels_tabs')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Files
    public function restoreFiles(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        $organization = auth()->user()->organization;
        //-------------------------------------//
        if (!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $files = $request->input('files');
        if ($files == "" || $files == null || $files == "[]") {
            return response()->json(['message' => trans('variables.errors.teams.files_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['restoreMissingItems'] = $requestData['restoreMissingItems'];
        $options['restoreChangedItems'] = $requestData['restoreChangedItems'];
        $options['fileVersion'] = $requestData['fileVersion'];
        $options['fileLastVersionAction'] = $requestData['fileLastVersionAction'];
        try {
            $filesArr = json_decode($files);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->restore_session_guid = $restoreSessionGuid;
            $history->type = 'file';
            $history->sub_type = 'Restore Files';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            $count = 0;
            foreach ($filesArr as $item) {
                $count += count($item->items);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->items);
                $details->item_parent_id = $item->teamId;
                $details->item_parent_name = $item->teamName . ' - ' . $item->channelName;
                $details->item_name = $item->teamName . ' - ' . $item->channelName;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $history->items_count = $count;
            $history->name = $requestData["restoreJobName"];
            $history->save();
            //--------------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $teamData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreTeamBackground(auth()->id(), $history->id, 'restoreFiles', $sessionData, $teamData));
            //--------------------------------------//
            session()->forget("backgroundTeamsRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.teams.restore_files')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Files ' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.restore_files')], 500);
        }
    }
    //------------------------------------------------//
    //Restore Tabs
    public function restoreTabs(Request $request)
    {
        //-------------------------------------//
        $validator = Validator::make($request->all(), [
            'deviceCode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        $organization = auth()->user()->organization;
        //-------------------------------------//
        if (!$organization->veeam_aad_authentication_guid)
            return response()->json(['message' => trans('variables.errors.organization_not_veeam_aad_authentication_guid')], 500);
        //-------------------------------------//
        $tabs = $request->input('tabs');
        if ($tabs == "" || $tabs == null || $tabs == "[]") {
            return response()->json(['message' => trans('variables.errors.teams.tabs_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();

        $userCode = $requestData['deviceCode'];
        $restoreSessionGuid = $requestData['restoreSessionGuid'];
        $options['restoreMissingItems'] = $requestData['restoreMissingItems'];
        $options['restoreChangedItems'] = $requestData['restoreChangedItems'];
        try {
            $tabsArr = json_decode($tabs);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->restore_session_guid = $restoreSessionGuid;
            $history->status = 'In Progress';
            $history->type = 'tab';
            $history->items_count = count($tabsArr);
            $history->name = $requestData["restoreJobName"];
            $history->sub_type = 'Restore Tabs';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->options = json_encode($options);
            $history->save();
            //--------------------------------------//
            $details = new BacRestoreHistoryDetail();
            $details->restore_history_id = $history->id;
            $details->item_id = $tabs;
            $details->item_parent_id = $tabsArr[0]->teamId;
            $details->item_parent_name = $tabsArr[0]->teamName . ' - ' . $tabsArr[0]->channelName;
            $details->item_name = $tabsArr[0]->teamName . ' - ' . $tabsArr[0]->channelName;
            $details->status = 'In Progress';
            $details->save();
            //--------------------------------------//
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            $teamData = [
                'userCode' => $userCode,
            ];
            //--------------------------------------//
            dispatch(new RestoreTeamBackground(auth()->id(), $history->id, 'restoreTabs', $sessionData, $teamData));
            //--------------------------------------//
            session()->forget("backgroundTeamsRestoreSessionGuid");
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.teams.restore_tabs')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Restoring Tabs ' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.restore_tabs')], 500);
        }
    }
    //------------------------------------------------//
    //Export Channel Posts
    public function exportChannelsPosts(Request $request)
    {
        //-------------------------------------//
        $channels = $request->input('channels');
        if ($channels == "" || $channels == null || $channels == "[]") {
            return response()->json(['message' => trans('variables.errors.teams.channels_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//
        try {
            $channelsArr = json_decode($channels);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->type = 'channel-posts';
            $history->items_count = count($channelsArr);
            $history->name = $requestData["restoreJobName"];
            $history->sub_type = 'Export Channels Posts';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->save();
            //--------------------------------------//
            foreach ($channelsArr as $item) {
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->name;
                $details->item_parent_id = $item->teamId;
                $details->item_parent_name = $item->teamName;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $organization = auth()->user()->organization;
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            //--------------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $requestData['jobId'])->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
            ];
            //--------------------------------------//
            dispatch(new ExportTeamsBackground(auth()->id(), $history->id, 'channel-posts', $storageData, $sessionData));
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.teams.export_channel_posts')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Exporting Channel Posts ' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.export_channel_posts')], 500);
        }
    }
    //------------------------------------------------//
    //Export Channel Posts
    public function exportPosts(Request $request)
    {
        //-------------------------------------//
        $posts = $request->input('posts');
        if ($posts == "" || $posts == null || $posts == "[]") {
            return response()->json(['message' => trans('variables.errors.teams.posts_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//
        try {
            $postsArr = json_decode($posts);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->type = 'post';
            $history->sub_type = 'Export Posts';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->save();
            //--------------------------------------//
            $count = 0;
            foreach ($postsArr as $item) {
                $count += count($item->items);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->items);
                $details->item_parent_id = $item->teamId . "|" . $item->channelId;
                $details->item_parent_name = $item->teamName . '-' . $item->channelName;
                $details->item_name = $item->teamName . '-' . $item->channelName;
                $details->status = 'In Progress';
                $details->save();
            }
            $history->items_count = $count;
            $history->name = $requestData["restoreJobName"];
            $history->save();
            //--------------------------------------//
            $organization = auth()->user()->organization;
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            //--------------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $requestData['jobId'])->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
            ];
            //--------------------------------------//
            dispatch(new ExportTeamsBackground(auth()->id(), $history->id, 'post', $storageData, $sessionData));
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.teams.export_posts')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Exporting Posts ' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.export_posts')], 500);
        }
    }
    //------------------------------------------------//
    //Export Channel Files
    public function exportChannelsFiles(Request $request)
    {
        //-------------------------------------//
        $channels = $request->input('channels');
        if ($channels == "" || $channels == null || $channels == "[]") {
            return response()->json(['message' => trans('variables.errors.teams.channels_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//
        try {
            $channelsArr = json_decode($channels);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->type = 'channel-files';
            $history->items_count = count($channelsArr);
            $history->name = $requestData["restoreJobName"];
            $history->sub_type = 'Export Channels Files';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->save();
            //--------------------------------------//
            foreach ($channelsArr as $item) {
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = $item->id;
                $details->item_name = $item->name;
                $details->item_parent_id = $item->teamId;
                $details->item_parent_name = $item->teamName;
                $details->status = 'In Progress';
                $details->save();
            }
            //--------------------------------------//
            $organization = auth()->user()->organization;
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            //--------------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $requestData['jobId'])->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
            ];
            //--------------------------------------//
            dispatch(new ExportTeamsBackground(auth()->id(), $history->id, 'channel-files', $storageData, $sessionData));
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.teams.export_channels_files')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Exporting Channel Files ' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.export_channels_files')], 500);
        }
    }
    //------------------------------------------------//
    //Export Channel Files
    public function exportFiles(Request $request)
    {
        //-------------------------------------//
        $files = $request->input('files');
        if ($files == "" || $files == null || $files == "[]") {
            return response()->json(['message' => trans('variables.errors.teams.files_required')], 500);
        }
        //-------------------------------------//
        $requestData = $request->all();
        //-------------------------------------//
        try {
            $filesArr = json_decode($files);
            $history = new BacRestoreHistory();
            $history->organization_id = auth()->user()->organization->id;
            $history->status = 'In Progress';
            $history->type = 'file';
            $history->sub_type = 'Export Files';
            $history->backup_job_id = $requestData['jobId'];
            $history->restore_point_time = $requestData['jobTime'];
            $history->restore_point_type = $requestData['jobType'];
            $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
            $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
            $history->request_time = Carbon::now();
            $history->save();
            //--------------------------------------//
            $count = 0;
            foreach ($filesArr as $item) {
                $count += count($item->items);
                $details = new BacRestoreHistoryDetail();
                $details->restore_history_id = $history->id;
                $details->item_id = json_encode($item->items);
                $details->item_parent_id = $item->teamId;
                $details->item_parent_name = $item->teamName . '-' . $item->channelName;
                $details->item_name = $item->teamName . '-' . $item->channelName;
                $details->status = 'In Progress';
                $details->save();
            }
            $history->items_count = $count;
            $history->name = $requestData["restoreJobName"];
            $history->save();
            //--------------------------------------//
            $organization = auth()->user()->organization;
            $sessionData = [
                'time' => $requestData['jobTime'],
                'showDeleted' => $requestData['showDeleted'],
                'showVersions' => $requestData['showVersions'],
                'orgId' => $organization->veeam_organization_guid,
            ];
            //--------------------------------------//
            $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $requestData['jobId'])->first();
            $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
            $storageData = [
                'storageAccount' => $backupJobData->storage_account_name,
                'accountKey' => $storageAccountKey,
                'containerName' => $backupJobData->restore_container,
            ];
            //--------------------------------------//
            dispatch(new ExportTeamsBackground(auth()->id(), $history->id, 'file', $storageData, $sessionData));
            //--------------------------------------//
            return response()->json(['message' => trans('variables.success.teams.export_files')], 200);
        } catch (Exception $e) {
            Log::log('error', 'Exception While Exporting Files ' . $e->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.export_files')], 500);
        }
    }
    //------------------------------------------------//
    //Download File
    public function downloadFile(Request $request)
    {
        set_time_limit(300);
        $requestData = $request->all();
        $teamId = $request->input('teamId');
        $channelId = $request->input('channelId');
        $fileId = $request->input('fileId');
        $fileSize = $request->input('fileSize');
        if ($teamId == "" || $teamId == null || $fileId == '' || $fileId == '') {
            return response()->json(['message' => trans('variables.errors.teams.files_required')], 500);
        }
        try {
            $this->checkRestoreSession();
            //--------------------------------//
            $fileSize = str_replace(" MB", '', $fileSize);
            $downloadLimit = config('parameters.DIRECT_DOWNLOAD_MEGA_LIMIT');
            if ($fileSize > $downloadLimit) {
                //--------------------------------//
                $filesArr[0] = (object) [
                    "teamId" => $teamId,
                    "channelId" => $request->input('channelId'),
                    "teamName" => $request->input('teamName'),
                    "channelName" => $request->input('channelName'),
                    "items" => [[
                        "id" => $fileId,
                        "name" => $request->input('name'),
                        "teamId" => $teamId,
                        "channelId" => $request->input('channelId'),
                        "teamName" => $request->input('teamName'),
                        "channelName" => $request->input('channelName'),
                    ]],
                ];
                $files = json_encode($filesArr);
                //--------------------------------//
                $filesArr = json_decode($files);
                $history = new BacRestoreHistory();
                $history->organization_id = auth()->user()->organization->id;
                $history->status = 'In Progress';
                $history->type = 'file';
                $history->items_count = 1;
                $history->name = '1_TeamsFile_' . date("Ymd_His");
                $history->sub_type = 'Export Files';
                $history->backup_job_id = $requestData['jobId'];
                $history->restore_point_time = $requestData['jobTime'];
                $history->restore_point_type = $requestData['jobType'];
                $history->is_restore_point_show_deleted = ($requestData['showDeleted'] == "true" ? 1 : 0);
                $history->is_restore_point_show_version = ($requestData['showVersions'] == "true" ? 1 : 0);
                $history->request_time = Carbon::now();
                $history->save();
                //--------------------------------------//
                foreach ($filesArr as $item) {
                    $details = new BacRestoreHistoryDetail();
                    $details->restore_history_id = $history->id;
                    $details->item_id = json_encode($item->items);
                    $details->item_parent_id = $item->teamId;
                    $details->item_parent_name = $item->teamName . '-' . $item->channelName;
                    $details->item_name = $item->teamName . '-' . $item->channelName;
                    $details->status = 'In Progress';
                    $details->save();
                }
                //--------------------------------------//
                $organization = auth()->user()->organization;
                $sessionData = [
                    'time' => $requestData['jobTime'],
                    'showDeleted' => $requestData['showDeleted'],
                    'showVersions' => $requestData['showVersions'],
                    'orgId' => $organization->veeam_organization_guid,
                ];
                //--------------------------------------//
                $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $requestData['jobId'])->first();
                $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
                $storageData = [
                    'storageAccount' => $backupJobData->storage_account_name,
                    'accountKey' => $storageAccountKey,
                    'containerName' => $backupJobData->restore_container,
                ];
                //--------------------------------------//
                dispatch(new ExportTeamsBackground(auth()->id(), $history->id, 'file', $storageData, $sessionData));
                //--------------------------------//
                return response()->json(['message' => trans('variables.success.teams.download_file')], 200);
            }
            //--------------------------------//
            try {
                $data = $this->_managerVeeam->downloadTeamsFile(session('restoreTeamsSessionId'), $teamId, $fileId);
                //-----------------------------------------------------//
                $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $request->jobId)->first();
                $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);
                //-----------------------------------------------------//
                $body = (object) $data['body'];
                $body = Utils::streamFor($body);

                //------------------------------------------------//
                $filenameArr = explode('filename=', $data['headers']['Content-Disposition'][0]);
                $filename = end($filenameArr);
                $tempArr = explode('.', $filename);
                $ext = end($tempArr);
                $ext = str_replace('"', '', $ext);
                //------------------------------------------------//
                $blobName = 'temp_' . date('Ymd_His') . '.' . $ext;
                $blockListString = '<?xml version="1.0" encoding="utf-8"?><BlockList>';
                $count = 0;
                $blobSize = 0;
                //--------------------
                while (!$body->eof()) {
                    $count++;
                    //----- Set Block Id
                    $blockCount = str_pad($count, 20, "0", STR_PAD_LEFT);
                    $blockId = base64_encode("$blockCount");
                    //--------------------
                    $blockListString .= "<Latest>$blockId</Latest>";
                    //--------------------
                    // Put Block
                    $blockSize = config('parameters.UPLOAD_BLOB_BLOCK_MEGA_SIZE');
                    $blockContent = $body->read($blockSize * 1024 * 1024);
                    $blobSize += strlen($blockContent);
                    $this->_managerAzure->uploadContainerBlobBlock([
                        "storageAccount" => $backupJobData->storage_account_name,
                        "accountKey" => $storageAccountKey,
                        "containerName" => $backupJobData->restore_container,
                        "blobName" => $blobName,
                        "dataString" => $blockContent,
                        "blockId" => $blockId,
                    ]);
                }
                $blockListString .= '</BlockList>';
                //-----------------
                $this->_managerAzure->putBlobBlockList([
                    'storageAccount' => $backupJobData->storage_account_name,
                    'accountKey' => $storageAccountKey,
                    'containerName' => $backupJobData->restore_container,
                    'blobName' => $blobName,
                    'blocksListXML' => $blockListString,
                ]);
                //-----------------------------------------------------//
                $blobDownloadLinkExpire = config('parameters.MINUTES_BEFORE_BLOB_LINK_EXPIRE');
                $endDate = gmdate('Y-m-d\TH:i:s\.u\0\Z', strtotime("+ $blobDownloadLinkExpire minutes"));
                //-------------------------------------//
                $accountName = $backupJobData->storage_account_name;
                $containerName = $backupJobData->restore_container;
                $account_key = $storageAccountKey;
                //-------------------------------------//
                $deleteFile = new BacTempBlobFile();
                $deleteFile->file_name = $blobName;
                $deleteFile->expiration_time = Carbon::now()->addMinute(config('parameters.TEMP_BLOB_FILE_EXPIRATION_MINUTES'));
                $deleteFile->storage_account_id = $backupJobData->storage_account_id;
                $deleteFile->save();
                //-------------------------------------//
                $url = $this->_managerAzure->getBlobUrl($accountName, $containerName, $blobName, $endDate, $account_key);
                //-----------------------------------------------------//
            } catch (Exception $e) {
                Log::log('error', 'Exception While Download Teams File' . $e->getMessage());
            }
            return response()->json(['file' => $url]);
            //--------------------------------//
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Downloading Teams File ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.download_file')], 500);
        }
    }
    //------------------------------------------------//
    //Download Post
    public function downloadPost(Request $request)
    {
        set_time_limit(300);
        $requestData = $request->all();
        $teamId = $request->input('teamId');
        $postId = $request->input('postId');
        $fileSize = $request->input('fileSize');
        if ($teamId == "" || $teamId == null || $postId == '' || $postId == '') {
            return response()->json(['message' => trans('variables.errors.teams.posts_required')], 500);
        }
        try {
            $this->checkRestoreSession();
            //--------------------------------//
            $fileSize = str_replace(" MB", '', $fileSize);
            //--------------------------------//
            try {
                $data = $this->_managerVeeam->downloadTeamsPost(session('restoreTeamsSessionId'), $teamId, $postId);
                //-----------------------------------------------------//
                $backupJobData = ViewOrganizationBackupJob::where("backup_job_id", $request->jobId)->first();
                $storageAccountKey = $this->getStorageAccountSharedAccessKey($backupJobData->storage_account_name);

                //-----------------------------------------------------//
                $body = (object) $data['body'];
                $body = Utils::streamFor($body);

                //------------------------------------------------//
                $filenameArr = explode('filename=', $data['headers']['Content-Disposition'][0]);
                $filename = end($filenameArr);
                $tempArr = explode('.', $filename);
                $ext = end($tempArr);
                $ext = str_replace('"', '', $ext);
                //------------------------------------------------//
                $blobName = 'temp_' . date('Ymd_His') . '.' . $ext;
                $blockListString = '<?xml version="1.0" encoding="utf-8"?><BlockList>';
                $count = 0;
                $blobSize = 0;
                //--------------------
                while (!$body->eof()) {
                    $count++;
                    //----- Set Block Id
                    $blockCount = str_pad($count, 20, "0", STR_PAD_LEFT);
                    $blockId = base64_encode("$blockCount");
                    //--------------------
                    $blockListString .= "<Latest>$blockId</Latest>";
                    //--------------------
                    // Put Block
                    $blockSize = config('parameters.UPLOAD_BLOB_BLOCK_MEGA_SIZE');
                    $blockContent = $body->read($blockSize * 1024 * 1024);
                    $blobSize += strlen($blockContent);
                    $this->_managerAzure->uploadContainerBlobBlock([
                        "storageAccount" => $backupJobData->storage_account_name,
                        "accountKey" => $storageAccountKey,
                        "containerName" => $backupJobData->restore_container,
                        "blobName" => $blobName,
                        "dataString" => $blockContent,
                        "blockId" => $blockId,
                    ]);
                }
                $blockListString .= '</BlockList>';
                //-----------------
                $this->_managerAzure->putBlobBlockList([
                    'storageAccount' => $backupJobData->storage_account_name,
                    'accountKey' => $storageAccountKey,
                    'containerName' => $backupJobData->restore_container,
                    'blobName' => $blobName,
                    'blocksListXML' => $blockListString,
                ]);
                //-----------------------------------------------------//
                $blobDownloadLinkExpire = config('parameters.MINUTES_BEFORE_BLOB_LINK_EXPIRE');
                $endDate = gmdate('Y-m-d\TH:i:s\.u\0\Z', strtotime("+ $blobDownloadLinkExpire minutes"));
                //-------------------------------------//
                $accountName = $backupJobData->storage_account_name;
                $containerName = $backupJobData->restore_container;
                $account_key = $storageAccountKey;
                //-------------------------------------//
                $deleteFile = new BacTempBlobFile();
                $deleteFile->file_name = $blobName;
                $deleteFile->expiration_time = Carbon::now()->addMinute(config('parameters.TEMP_BLOB_FILE_EXPIRATION_MINUTES'));
                $deleteFile->storage_account_id = $backupJobData->storage_account_id;
                $deleteFile->save();
                //-------------------------------------//
                $url = $this->_managerAzure->getBlobUrl($accountName, $containerName, $blobName, $endDate, $account_key);
                //-----------------------------------------------------//
                return response()->json(['file' => $url]);
            } catch (Exception $e) {
                Log::log('error', 'Exception While Download Teams Post' . $e->getMessage());
                return response()->json(['message' => trans('variables.errors.teams.download_post')], 500);
            }
            //--------------------------------//
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Downloading Teams Post ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.download_post')], 500);
        }
    }
    //------------------------------------------------//
    //Download Post
    public function viewPost(Request $request)
    {
        set_time_limit(300);
        $requestData = $request->all();
        $teamId = $request->input('teamId');
        $postId = $request->input('postId');
        if ($teamId == "" || $teamId == null || $postId == '' || $postId == '') {
            return response()->json(['message' => trans('variables.errors.teams.posts_required')], 500);
        }
        try {
            //--------------------------------//
            $this->checkRestoreSession();
            //--------------------------------//
            try {
                $data = $this->_managerVeeam->downloadTeamsPost(session('restoreTeamsSessionId'), $teamId, $postId);
                //-----------------------------------------------------//
                $arr['html'] = $data['body']->getContents();
                $arr['html'] = str_replace('<html>', '', $arr['html']);
                $arr['html'] = str_replace('</html>', '', $arr['html']);
                $arr['html'] = str_replace('<!DOCTYPE html>', '', $arr['html']);
                //-----------------------------------------------------//
                return response()->json($arr);
            } catch (Exception $e) {
                Log::log('error', 'Exception While Viewing Teams Post' . $e->getMessage());
                return response()->json(['message' => trans('variables.errors.teams.view_post')], 500);
            }
            //--------------------------------//
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Viewing Teams Post ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.teams.view_post')], 500);
        }
    }
    //------------------------------------------------//
    //Check Restore Session
    private function checkRestoreSession()
    {
        if (session('restoreTeamsSessionId')) {
            $sessionInfo = $this->_managerVeeam->getRestoreSession(session('restoreTeamsSessionId'))['data'];
            if ($sessionInfo->state != "Working") {
                session()->forget('restoreTeamsSessionId');
                return response()->json(['message' => trans('variables.errors.restore_session_expired')], 500);
            }
        } else {
            return response()->json(['message' => trans('variables.errors.restore_session_expired')], 500);
        }
    }
    //------------------------------------------------//
    //Create Restore Session
    private function createRestoreSession($time, $showDeleted, $showVersions)
    {
        try {
            $user = auth()->user();
            $user_id = $user->id;
            $organization = $user->organization;
            return $this->_managerVeeam->createRestoreSession($organization->veeam_organization_guid, $time, "vet", $showDeleted, $showVersions)['data'];
        } catch (\Exception $ex) {
            Log::log('error', 'Exception While Creating Restore Session  ' . $ex->getMessage());
            return response()->json(['message' => trans('variables.errors.create_restore_session')], 500);
        }
    }
    //------------------------------------------------//
    private function checkRestorePeriod()
    {
        $user = auth()->user();
        $restoreDays = config('parameters.ALLOWED_RESTORE_DAYS_AFTER_LICENSE_EXPIRED');
        $expiryDate = Carbon::createFromFormat('Y-m-d', $user->organization->license_expiry_date)->add($restoreDays, 'days');
        return ($expiryDate < Carbon::now());
    }
    //------------------------------------------------//
    private function getStorageAccountSharedAccessKey($storageAccountName)
    {
        $organization = auth()->user()->organization;
        $storageKeys = $this->_managerAzure->getStorageAccountSharedAccessKeys($storageAccountName, $organization->azure_subscription_guid, $organization->azure_resource_group);
        $key1 = ($storageKeys->keys)[0]->value;
        return $key1;
    }
    //------------------------------------------------//
    public function generateDeviceCode(Request $request)
    {
        //-------------------------------------------//
        $validator = Validator::make($request->all(), [
            'jobTime' => 'required|string',
            'showDeleted' => 'required',
            'showVersions' => 'required',
            'jobType' => 'required',
        ]);
        //-------------------------------------------//
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 500);
        }
        //-------------------------------------------//
        $organization = auth()->user()->organization;
        //-------------------------------------------//
        try {
            $oldRestoreSessionId = session()->get("backgroundTeamsRestoreSessionGuid");
            if ($oldRestoreSessionId) {
                $oldRestoreSession = $this->_managerVeeam->getRestoreSession($oldRestoreSessionId)["data"];
                if ($oldRestoreSession->state == "Working")
                    $this->_managerVeeam->stopRestoreSession($oldRestoreSessionId);
                session()->forget("backgroundTeamsRestoreSessionGuid");
            }
            //-------------------------------------------//
            if ($request->jobType == "all") {
                $restoreSession = $this->_managerVeeam->createRestoreSession($organization->veeam_organization_guid, $request->jobTime, "vet", $request->showDeleted, $request->showVersions)["data"];
            } else {
                $backupJobData = VeeamBackupJob::where("id", $request->jobId)->first();
                $restoreSession = $this->_managerVeeam->createJobRestoreSession($backupJobData->guid, $request->jobTime, "vet", $request->showDeleted, $request->showVersions);
            }
            //-------------------------------------------//
            session()->put("backgroundTeamsRestoreSessionGuid", $restoreSession->id);
            $deviceCode = $this->_managerVeeam->generateRestoreSessionDeviceCode($restoreSession->id, $organization->veeam_aad_authentication_guid)["data"];
            //-------------------------------------------//
            return response()->json([
                "userCode" => $deviceCode->userCode,
                "restoreSessionGuid" => $restoreSession->id
            ]);
        } catch (Exception $e) {
            Log::log("error", "Error while generating restore session device code " . $e->getMessage());
            return response()->json(["message" => __("variables.errors.generating_device_code")], 500);
        }
    }
    //------------------------------------------------//
}
