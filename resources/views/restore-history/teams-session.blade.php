@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/main.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/repositories.css') }}" />
<link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/generalElement.css') }}">
<link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/restore.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/restore-customize.css') }}" />

<script src="/js/html-duration-picker.min.js"></script>
@section('topnav')
    <style>
        .controlsDivStyle {
            display: none !important;
        }

        #durationFrom,
        #durationTo {
            text-align: left;
        }

        i {
            padding: 0px 3px;
        }

        .sess-info {
            margin-bottom: 6px;
            padding-left: 0px;
            padding-right: 0px;
            font-size: 12px;
        }

        .sess-title {
            font-weight: 600;
        }

        .newJobRow {
            padding-top: 20px;
            height: 100%;
            margin-bottom: -35px;
        }

        .sess-info-details {
            color: #c1c0c0;
        }

        .job-session-details {
            display: flex;
            position: relative;
        }

        .job-session-details>.col-lg-3 {
            display: block;
            position: relative;
            flex: 1;
        }

        .dataTables_scrollBody {
            height: auto !important;
        }
    </style>
    @php
        $status = $data['history']['status'];
        $isRestore = strpos($data['history']['sub_type'], 'Restore');
        $process = $isRestore === 0 ? 'Restore' : 'Export';
    @endphp
    <div class="col-sm-10 navbarLayout">
        <!-- Upper navbar -->
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm upperNavBar"> -->
        <ul class="ulNavbar">
            @php $parent = strtolower($data['kind']) @endphp
            <li class="liNavbar"><a class="parent-link" data-parent="{{ $parent }}"
                    href="{{ url('restore-history', $data['kind']) }}"> Restore History <img class="nav-arrow"
                        src="/svg/arrow-right.svg"> {{ getDataType($data['kind']) }}</a></li>
            <li class="liNavbar"><a class="active"
                    href="{{ url('restore', [$data['kind'], 'session', $data['history']['restore_session_guid']]) }}">Restore
                    Session</a></li>
            <!-- Authentication Links -->
            @include('layouts.authentication-links')
        </ul>
    </div>
@endsection
@section('content')
    <div id="mainContent">
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <!-- Create new repository button  -->
        <div class="row job-session-details" style="margin-bottom: 75px !important;">
            <div class="col">
                <div class="col-lg-11" style="padding-left: 0px;">
                    <h5 class="txt-blue">Session Data</h5>
                </div>

                <div class="col-lg-12 newJobRow">
                    <div class="rowBorderRight"></div>
                    <div class="rowBorderBottom"></div>
                    <div class="rowBorderleft"></div>
                    <div class="rowBorderUp"></div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-6 nopadding sess-title nopadding">
                            Session Status:
                        </div>
                        <div class="sess-info-details col-lg-6 nopadding">
                            {{ $data['sessionInfo']['status'] }}
                        </div>
                    </div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-6 nopadding sess-title">
                            Start Time:
                        </div>
                        <div class="sess-info-details col-lg-6 nopadding">
                            {{ $data['sessionInfo']['startDate'] }}
                        </div>
                    </div>
                    @if ($data['history']['status'] != 'In Progress')
                        <div class="col-lg-12 sess-info">
                            <div class="col-lg-6 nopadding sess-title">
                                End Time:
                            </div>
                            <div class="sess-info-details col-lg-6 nopadding">
                                {{ $data['sessionInfo']['endDate'] }}
                            </div>
                        </div>
                        <div class="col-lg-12 sess-info">
                            <div class="col-lg-6 nopadding sess-title">
                                Details:
                            </div>
                            <div class="sess-info-details col-lg-6 nopadding">
                                {{ $data['sessionInfo']['details'] }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col">
                <div class="col-lg-11" style=" padding-left: 0px;">
                    <h5 class="txt-blue">Job Info</h5>
                </div>
                <div class="col-lg-12 newJobRow">
                    <div class="rowBorderRight"></div>
                    <div class="rowBorderBottom"></div>
                    <div class="rowBorderleft"></div>
                    <div class="rowBorderUp"></div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-6 nopadding sess-title">
                            Job Name:
                        </div>
                        <div title="{{ $data['history']['name'] }}"
                            class="sess-info-details col-lg-6 nopadding tooltipSpan">
                            {{ $data['history']['name'] }}
                        </div>
                    </div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-6 nopadding sess-title">
                            Job Type:
                        </div>
                        <div class="sess-info-details col-lg-6 nopadding">
                            {{ $data['history']['sub_type'] }}
                        </div>
                    </div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-6 nopadding sess-title">
                            Point Time:
                        </div>
                        <div class="sess-info-details col-lg-6 nopadding">
                            {{ $data['jobInfo']['jobTime'] }}
                        </div>
                    </div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-6 nopadding sess-title">
                            With Deleted:
                        </div>
                        <div class="sess-info-details col-lg-6 nopadding">
                            {{ $data['jobInfo']['showDeleted'] }}
                        </div>
                    </div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-6 nopadding sess-title">
                            With Versions:
                        </div>
                        <div class="sess-info-details col-lg-6 nopadding">
                            {{ $data['jobInfo']['showVersion'] }}
                        </div>
                    </div>
                    @if (optional($data['restoreOptions'])->toMailBox)
                        <div class="col-lg-12 sess-info">
                            <div class="col-lg-6 nopadding sess-title">
                                Restore To Mailbox:
                            </div>
                            <div class="sess-info-details col-lg-6 nopadding tooltipSpan"
                                title="{{ optional($data['restoreOptions'])->toMailBox }}">
                                {{ optional($data['restoreOptions'])->toMailBox }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @if ($data['restoreOptions'])
                <div class="col">
                    <div class="col-lg-11" style="padding-left: 0px;">
                        <h5 class="txt-blue">Restore Options</h5>
                    </div>
                    <div class="col-lg-12 newJobRow">
                        <div class="rowBorderRight"></div>
                        <div class="rowBorderBottom"></div>
                        <div class="rowBorderleft"></div>
                        <div class="rowBorderUp"></div>
                        @if (optional($data['restoreOptions'])->fileLastVersionAction)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    File Last Version Action:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->fileLastVersionAction }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->fileVersion)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore File Version:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->fileVersion }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->restoreChangedItems)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore Changed Items:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->restoreChangedItems }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->restoreMissingItems)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore Missing Items:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->restoreMissingItems }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->restoreMembers)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore Members:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->restoreMembers }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->restoreSettings)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore Settings:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->restoreSettings }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->pointType)
                            @php
                                $type = $data['history']['type'];
                                $itemType = $type == 'channel-posts' ? 'Posts' : ($type == 'channel-files' ? 'Files' : 'Tabs');
                            @endphp
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore All {{ $itemType }}:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->pointType == 'custom' ? 'No' : 'Yes' }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->from)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore From:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding formatDate">
                                    {{ optional($data['restoreOptions'])->from }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->to)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore To:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding formatDate">
                                    {{ optional($data['restoreOptions'])->to }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <!-- All repositories table -->
        <div class="row">
            <div class="col-lg-12">
                <h5 class="txt-blue">Items Details</h5>
            </div>
        </div>
        <div class="jobsTable" style="margin-left:0px; margin-top: -38px;">
            <table id="detailsTable" class="stripe table table-striped table-dark" style="width:100%">
                <thead class="table-th">
                    <th class="left-col">{{ $data['item_type'] }}</th>
                    <th>{{ $data['item_parent'] }}</th>
                    <th>Status</th>
                    <th>{{ $process }} Duration</th>
                    <th>File Size</th>
                    <th>Last Download Date</th>
                    <th>Result</th>
                    <th></th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-lg-12">
                <h5 class="txt-blue">Session Details</h5>
            </div>
        </div>
        <div class="jobsTable" style="margin-left:0px; margin-top: -38px;">
            <table id="sessionsTable" class="stripe table table-striped table-dark" style="width:100%">
                <thead class="table-th">
                    <th class="col-sm-8 left-col"> Action </th>
                    <th class="col-sm-2 left-col">Duration</th>
                    <th class="col-sm-2 left-col">Status</th>
                </thead>
                <tbody id="session-content">

                </tbody>
            </table>
        </div>
        @php
            $status = $data['history']['status'];
            $isRestore = strpos($data['history']['sub_type'], 'Restore');
            $showButton = ($isRestore === 0 && $status != 'In Progress' && $role->hasPermissionTo('teams_export_again')) || (!$isRestore && in_array($status, ['Canceled', 'Expired', 'Failed']) && $role->hasPermissionTo('teams_restore_again'));
        @endphp
        @if ($showButton)
            <div class="row actions-row mr-0" style="margin-bottom: 25px;margin-top:25px;">
                <div class="col-lg-8"></div>
                <div class="col-lg-4 nopadding">
                    <a href="{{ url('restore-history', $data['kind']) }}"
                        class="cancel-button btn_primary_state right-float mr-0">
                        Cancel</a>
                    <button class="btn_primary_state right-float restoreAgain">
                        {{ $isRestore === false ? 'Export' : 'Restore' }} Again</button>
                </div>
            </div>
        @endif
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="confirmationModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog" style="margin:20vh auto;">

            <!-- Modal content-->
            <div class="modal-content ">
                <div id="modalBody_id" class="modalContent">
                    <div class="alert swal-modal-confirmation custom-confirmation" role="alert">
                        <div class="swal-icon swal-icon--warning" style="background-color: #FA9351!important">
                            <span class="swal-icon--warning__body">
                                <span class="swal-icon--warning__dot"></span>
                            </span>
                        </div>

                        <div class="swal-title text-center confirmTitle">Cancel Restore</div>
                        <div class="row">
                            <div id="deleteTxt" class="modal-body basic-color text-center mt-22">
                                <input type="hidden" class="historyId">
                                Are You Sure ?
                            </div>
                        </div>

                        <div class="row mt-10">
                            <div class="input-form-70 inline-flex">
                                <button type="button" class="btn_primary_state allWidth confirmButton mr-25"
                                    onClick="cancelRestore();">Yes</button>
                                <button type="button" class="btn_cancel_primary_state allWidth"
                                    data-dismiss="modal">No</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="restoreTeamsModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg modal-width mt-10v">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-100 mb-15">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Restore Selected Teams
                            </h4>
                        </div>
                    </div>
                    <form id="restoreTeamsForm" class="mb-0" onsubmit="restoreTeams(event)">
                        <div class="custom-left-col">
                            <input type="hidden" class="showVersions" name="showVersions" />
                            <input type="hidden" class="showDeleted" name="showDeleted" />
                            <input type="hidden" class="jobTime" name="jobTime" />
                            <input type="hidden" class="jobId" name="jobId" />
                            <input type="hidden" class="jobType" name="jobType" />
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Job Name</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div class="mb-0 allWidth flex relative">
                                            <input type="text"
                                                class="form-control form_input custom-form-control font-size"
                                                placeholder="Job Name" name="restoreJobName" required
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('partials.device-code-authentication')
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Options</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div class="row">
                                            <div class="pl-0 pr-3 ml-8">
                                                <label>Restore the following items:</label>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="relative allWidth mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="restoreChangedItems" type="checkbox"
                                                            class="form-check-input">
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Changed Items</span>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="relative allWidth mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="restoreMissingItems" type="checkbox"
                                                            class="form-check-input">
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Missing Items</span>
                                                </div>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="relative allWidth">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="restoreMembers" type="checkbox"
                                                            class="form-check-input">
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Members</span>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="relative allWidth">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="restoreSettings" type="checkbox"
                                                            class="form-check-input">
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Settings</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom-right-col">
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Selected Teams</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-300p">
                                        <div class="allWidth">
                                            <table id="teamsTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <th>
                                                            <label style="top: 45%;left: 10px;"
                                                                class="checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>Team</th>
                                                        <th>Email</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> teams selected <span
                                                                class="unresolvedCount"></span>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-form-70 inline-flex">
                                    <button type="submit" class="btn_primary_state allWidth mr-25">Restore</button>
                                    <button type="button" class="btn_cancel_primary_state  allWidth"
                                        data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="restoreChannelsModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg modal-width mt-10v">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-100 mb-15">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Restore Selected Channels
                            </h4>
                        </div>
                    </div>
                    <form id="restoreChannelsForm" class="mb-0" onsubmit="restoreChannels(event)">
                        <div class="custom-left-col">
                            <input type="hidden" class="showVersions" name="showVersions" />
                            <input type="hidden" class="showDeleted" name="showDeleted" />
                            <input type="hidden" class="jobTime" name="jobTime" />
                            <input type="hidden" class="jobId" name="jobId" />
                            <input type="hidden" class="jobType" name="jobType" />
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Job Name</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div class="mb-0 allWidth flex relative">
                                            <input type="text"
                                                class="form-control form_input custom-form-control font-size"
                                                placeholder="Job Name" name="restoreJobName" required
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('partials.device-code-authentication')
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Options</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div class="row">
                                            <div class="pr-0 pl-3">
                                                <label>Restore the following items:</label>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="relative allWidth">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="restoreChangedItems" type="checkbox"
                                                            class="form-check-input">
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Changed Items</span>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="relative allWidth">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="restoreMissingItems" type="checkbox"
                                                            class="form-check-input">
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Missing Items</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom-right-col">
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Selected Channels</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-280p">
                                        <div class="allWidth">
                                            <table id="channelsTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <th>
                                                            <label style="top: 45%;left: 10px;"
                                                                class="checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>Channel</th>
                                                        <th>Team</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> channels selected <span
                                                                class="unresolvedCount"></span>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="input-form-70 inline-flex">
                                    <button type="submit" class="btn_primary_state allWidth mr-25">Restore</button>
                                    <button type="button" class="btn_cancel_primary_state  allWidth"
                                        data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="restoreChannelsPostsModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg modal-width mt-10v">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-100 mb-15">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Restore Selected Channels Posts
                            </h4>
                        </div>
                    </div>
                    <form id="restoreChannelsPostsForm" class="mb-0" onsubmit="restoreChannelsPosts(event)">
                        <div class="custom-left-col">
                            <input type="hidden" class="showVersions" name="showVersions" />
                            <input type="hidden" class="showDeleted" name="showDeleted" />
                            <input type="hidden" class="jobTime" name="jobTime" />
                            <input type="hidden" class="jobId" name="jobId" />
                            <input type="hidden" class="jobType" name="jobType" />
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Job Name</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div class="mb-0 allWidth flex relative">
                                            <input type="text"
                                                class="form-control form_input custom-form-control font-size"
                                                placeholder="Job Name" name="restoreJobName" required
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('partials.device-code-authentication')
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Options</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div class="mb-10 allWidth">
                                            <div>
                                                <div class="radio m-0 mb-2">
                                                    <label>
                                                        <input type="radio" name="pointType" class="pointType"
                                                            value="all" checked>Restore All Posts.
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="pointType" class="pointType" checked
                                                            value="custom">Restore Posts for The Specified Time Period:
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="allWidth flex restorePointRow">
                                            <input type="text"
                                                class="form_input form-control mr-25 date custom-form-control font-size"
                                                required name="restoreFrom" placeholder="From" />
                                            <input type="text"
                                                class="form_input form-control date custom-form-control font-size" required
                                                name="restoreTo" placeholder="To" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom-right-col">
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Selected Channels</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-290p">
                                        <div class="allWidth">
                                            <table id="channelsPostsTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <th>
                                                            <label style="top: 45%;left: 10px;"
                                                                class="checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>Channel</th>
                                                        <th>Team</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> channels selected <span
                                                                class="unresolvedCount"></span>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-btn">
                                <div class="input-form-70 inline-flex">
                                    <button type="submit" class="btn_primary_state allWidth mr-25">Restore</button>
                                    <button type="button" class="btn_cancel_primary_state  allWidth"
                                        data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="restoreChannelsFilesModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg modal-width mt-10v">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-100 mb-15">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Restore Selected Channels Files
                            </h4>
                        </div>
                    </div>
                    <form id="restoreChannelsFilesForm" class="mb-0" onsubmit="restoreChannelsFiles(event)">
                        <div class="custom-left-col">
                            <input type="hidden" class="showVersions" name="showVersions" />
                            <input type="hidden" class="showDeleted" name="showDeleted" />
                            <input type="hidden" class="jobTime" name="jobTime" />
                            <input type="hidden" class="jobId" name="jobId" />
                            <input type="hidden" class="jobType" name="jobType" />
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Job Name</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div class="mb-0 allWidth flex relative">
                                            <input type="text"
                                                class="form-control form_input custom-form-control font-size"
                                                placeholder="Job Name" name="restoreJobName" required
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('partials.device-code-authentication')
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Selected Channels</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-117p">
                                        <div class="allWidth">
                                            <table id="channelsFilesTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <th>
                                                            <label style="top: 45%;left: 10px;"
                                                                class="checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>Channel</th>
                                                        <th>Team</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> channels selected <span
                                                                class="unresolvedCount"></span>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom-right-col">
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Options</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-330p pb-3 pt-3">
                                        <div class="flex">
                                            <label class="mr-4 m-0 nowrap">Files Version:</label>
                                            <div class="radioDiv">
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="fileVersion" class="fileVersion"
                                                            value="Last" checked="">Last
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="fileVersion" class="fileVersion"
                                                            value="All">All
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex pt-3 pb-3">
                                            <label class="mr-4 m-0 nowrap">File Last Version Action:</label>
                                            <div class="radioDiv">
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="fileLastVersionAction"
                                                            class="fileLastVersionAction" value="Overwrite"
                                                            checked="">Overwrite
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="fileLastVersionAction"
                                                            class="fileLastVersionAction" value="Merge">Merge
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-10">
                                            <div class="pr-0 pl-3">
                                                <label>Restore the following items:</label>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="relative allWidth mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="restoreChangedItems" type="checkbox"
                                                            class="form-check-input">
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Changed Items</span>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="relative allWidth mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="restoreMissingItems" type="checkbox"
                                                            class="form-check-input">
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Missing Items</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-fbtn">
                                <div class="input-form-70 inline-flex">
                                    <button type="submit" class="btn_primary_state allWidth mr-25">Restore</button>
                                    <button type="button" class="btn_cancel_primary_state  allWidth"
                                        data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="restoreChannelsTabsModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg modal-width mt-10v">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-100 mb-15">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Restore Selected Channels Tabs
                            </h4>
                        </div>
                    </div>
                    <form id="restoreChannelsTabsForm" class="mb-0" onsubmit="restoreChannelsTabs(event)">
                        <div class="custom-left-col">
                            <input type="hidden" class="showVersions" name="showVersions" />
                            <input type="hidden" class="showDeleted" name="showDeleted" />
                            <input type="hidden" class="jobTime" name="jobTime" />
                            <input type="hidden" class="jobId" name="jobId" />
                            <input type="hidden" class="jobType" name="jobType" />
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Job Name</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div class="mb-0 allWidth flex relative">
                                            <input type="text"
                                                class="form-control form_input custom-form-control font-size"
                                                placeholder="Job Name" name="restoreJobName" required
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('partials.device-code-authentication')
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Options</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div class="row">
                                            <div class="pr-0 pl-3">
                                                <label>Restore the following items:</label>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="relative allWidth">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="restoreChangedItems" type="checkbox"
                                                            class="form-check-input">
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Changed Items</span>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="relative allWidth">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="restoreMissingItems" type="checkbox"
                                                            class="form-check-input">
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Missing Items</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom-right-col">
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Selected Channels</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-280p">
                                        <div class="allWidth">
                                            <table id="channelsTabsTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <th>
                                                            <label style="top: 45%;left: 10px;"
                                                                class="checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>Channel</th>
                                                        <th>Team</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> channels selected <span
                                                                class="unresolvedCount"></span>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="input-form-70 inline-flex">
                                    <button type="submit" class="btn_primary_state allWidth mr-25">Restore</button>
                                    <button type="button" class="btn_cancel_primary_state  allWidth"
                                        data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="exportChannelsPostsModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg ">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Export Selected Channels Posts
                            </h4>
                        </div>
                    </div>
                    <form id="exportChannelsPostsForm" class="mb-0" onsubmit="exportChannelsPosts(event)">
                        <input type="hidden" class="showVersions" name="showVersions" />
                        <input type="hidden" class="showDeleted" name="showDeleted" />
                        <input type="hidden" class="jobTime" name="jobTime" />
                        <input type="hidden" class="jobId" name="jobId" />
                        <input type="hidden" class="jobType" name="jobType" />
                        <div class="row">
                            <div class="input-form-70 mb-1">
                                <h5 class="txt-blue mt-0">Restore Job Name</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder pb-3 pt-3">
                                    <div class="mb-0 allWidth flex relative">
                                        <input type="text"
                                            class="form-control form_input custom-form-control font-size"
                                            placeholder="Job Name" name="restoreJobName" required autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div cl ass="row">
                            <div class="input-form-70 mb-1">
                                <h5 class="txt-blue mt-0">Selected Channels</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder">
                                    <div class="allWidth">
                                        <table id="exportChannelsPostsTable"
                                            class="stripe table table-striped table-dark display nowrap allWidth">
                                            <thead class="table-th">
                                                <tr>
                                                    <th>
                                                        <label style="top: 45%;left: 10px;"
                                                            class="checkbox-container checkbox-search">
                                                            <input type="checkbox" checked class="form-check-input">
                                                            <span class="tree-checkBox check-mark"></span>
                                                        </label>
                                                    </th>
                                                    <th>Channel</th>
                                                    <th>Team</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">
                                                        <span class="boxesCount"></span> channels selected <span
                                                            class="unresolvedCount"></span>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 inline-flex">
                                <button type="submit" class="btn_primary_state allWidth mr-25">Export</button>
                                <button type="button" class="btn_cancel_primary_state  allWidth"
                                    data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="exportChannelsFilesModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg ">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Export Selected Channels Files
                            </h4>
                        </div>
                    </div>
                    <form id="exportChannelsFilesForm" class="mb-0" onsubmit="exportChannelsFiles(event)">
                        <input type="hidden" class="showVersions" name="showVersions" />
                        <input type="hidden" class="showDeleted" name="showDeleted" />
                        <input type="hidden" class="jobTime" name="jobTime" />
                        <input type="hidden" class="jobId" name="jobId" />
                        <input type="hidden" class="jobType" name="jobType" />
                        <div class="row">
                            <div class="input-form-70 mb-1">
                                <h5 class="txt-blue mt-0">Restore Job Name</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder pb-3 pt-3">
                                    <div class="mb-0 allWidth flex relative">
                                        <input type="text"
                                            class="form-control form_input custom-form-control font-size"
                                            placeholder="Job Name" name="restoreJobName" required autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 mb-1">
                                <h5 class="txt-blue mt-0">Selected Channels</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder">
                                    <div class="allWidth">
                                        <table id="exportChannelsFilesTable"
                                            class="stripe table table-striped table-dark display nowrap allWidth">
                                            <thead class="table-th">
                                                <tr>
                                                    <th>
                                                        <label style="top: 45%;left: 10px;"
                                                            class="checkbox-container checkbox-search">
                                                            <input type="checkbox" checked class="form-check-input">
                                                            <span class="tree-checkBox check-mark"></span>
                                                        </label>
                                                    </th>
                                                    <th>Channel</th>
                                                    <th>Team</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">
                                                        <span class="boxesCount"></span> channels selected <span
                                                            class="unresolvedCount"></span>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 inline-flex">
                                <button type="submit" class="btn_primary_state allWidth mr-25">Export</button>
                                <button type="button" class="btn_cancel_primary_state  allWidth"
                                    data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="restoreFilesModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg modal-width mt-10v">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-100 mb-15">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Restore Selected Files
                            </h4>
                        </div>
                    </div>
                    <form id="restoreFilesForm" class="mb-0" onsubmit="restoreFiles(event)">
                        <div class="custom-left-col">
                            <input type="hidden" class="showVersions" name="showVersions" />
                            <input type="hidden" class="showDeleted" name="showDeleted" />
                            <input type="hidden" class="jobTime" name="jobTime" />
                            <input type="hidden" class="jobId" name="jobId" />
                            <input type="hidden" class="jobType" name="jobType" />
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Job Name</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div class="mb-0 allWidth flex relative">
                                            <input type="text"
                                                class="form-control form_input custom-form-control font-size"
                                                placeholder="Job Name" name="restoreJobName" required
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('partials.device-code-authentication')
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Options</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div class="flex">
                                            <label class="mr-4 m-0 nowrap">Files Version:</label>
                                            <div class="radioDiv">
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="fileVersion" class="fileVersion"
                                                            value="Last" checked="">Last
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="fileVersion" class="fileVersion"
                                                            value="All">All
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex pt-3 pb-3">
                                            <label class="mr-4 m-0 nowrap">File Last Version Action:</label>
                                            <div class="radioDiv">
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="fileLastVersionAction"
                                                            class="fileLastVersionAction" value="Overwrite"
                                                            checked="">Overwrite
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="fileLastVersionAction"
                                                            class="fileLastVersionAction" value="Merge">Merge
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="pr-0 pl-4">
                                                <label>Restore the following items:</label>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="relative allWidth">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="restoreChangedItems" type="checkbox"
                                                            class="form-check-input">
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Changed Items</span>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="relative allWidth">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="restoreMissingItems" type="checkbox"
                                                            class="form-check-input">
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Missing Items</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom-right-col">
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Selected Files</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-280p">
                                        <div class="allWidth">
                                            <table id="filesTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <th>
                                                            <label style="top: 45%;left: 10px;"
                                                                class="checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>File</th>
                                                        <th>Channel</th>
                                                        <th>Team</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> files selected <span
                                                                class="unresolvedCount"></span>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-form-70 inline-flex">
                                    <button type="submit" class="btn_primary_state allWidth mr-25">Restore</button>
                                    <button type="button" class="btn_cancel_primary_state  allWidth"
                                        data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="exportFilesModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Export Selected Files
                            </h4>
                        </div>
                    </div>
                    <form id="exportFilesForm" class="mb-0" onsubmit="exportFiles(event)">
                        <input type="hidden" class="showVersions" name="showVersions" />
                        <input type="hidden" class="showDeleted" name="showDeleted" />
                        <input type="hidden" class="jobTime" name="jobTime" />
                        <input type="hidden" class="jobId" name="jobId" />
                        <input type="hidden" class="jobType" name="jobType" />
                        <div class="row">
                            <div class="input-form-70 mb-1">
                                <h5 class="txt-blue mt-0">Restore Job Name</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder pb-3 pt-3">
                                    <div class="mb-0 allWidth flex relative">
                                        <input type="text"
                                            class="form-control form_input custom-form-control font-size"
                                            placeholder="Job Name" name="restoreJobName" required
                                            autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 mb-1">
                                <h5 class="txt-blue mt-0">Selected Files</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder">
                                    <div class="allWidth">
                                        <table id="exportFilesTable"
                                            class="stripe table table-striped table-dark display nowrap allWidth">
                                            <thead class="table-th">
                                                <tr>
                                                    <th>
                                                        <label style="top: 45%;left: 10px;"
                                                            class="checkbox-container checkbox-search">
                                                            <input type="checkbox" checked class="form-check-input">
                                                            <span class="tree-checkBox check-mark"></span>
                                                        </label>
                                                    </th>
                                                    <th>File</th>
                                                    <th>Channel</th>
                                                    <th>Team</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">
                                                        <span class="boxesCount"></span> files selected <span
                                                            class="unresolvedCount"></span>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 inline-flex">
                                <button type="submit" class="btn_primary_state allWidth mr-25">Export</button>
                                <button type="button" class="btn_cancel_primary_state  allWidth"
                                    data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="exportPostsModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Export Selected Posts
                            </h4>
                        </div>
                    </div>
                    <form id="exportPostsForm" class="mb-0" onsubmit="exportPosts(event)">
                        <input type="hidden" class="showVersions" name="showVersions" />
                        <input type="hidden" class="showDeleted" name="showDeleted" />
                        <input type="hidden" class="jobTime" name="jobTime" />
                        <input type="hidden" class="jobId" name="jobId" />
                        <input type="hidden" class="jobType" name="jobType" />
                        <div class="row">
                            <div class="input-form-70 mb-1">
                                <h5 class="txt-blue mt-0">Restore Job Name</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder pb-3 pt-3">
                                    <div class="mb-0 allWidth flex relative">
                                        <input type="text"
                                            class="form-control form_input custom-form-control font-size"
                                            placeholder="Job Name" name="restoreJobName" required
                                            autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 mb-1">
                                <h5 class="txt-blue mt-0">Selected Posts</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder">
                                    <div class="allWidth">
                                        <table id="exportPostsTable"
                                            class="stripe table table-striped table-dark display nowrap allWidth">
                                            <thead class="table-th">
                                                <tr>
                                                    <th>
                                                        <label style="top: 45%;left: 10px;"
                                                            class="checkbox-container checkbox-search">
                                                            <input type="checkbox" checked class="form-check-input">
                                                            <span class="tree-checkBox check-mark"></span>
                                                        </label>
                                                    </th>
                                                    <th>Post</th>
                                                    <th>Channel</th>
                                                    <th>Team</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">
                                                        <span class="boxesCount"></span> posts selected <span
                                                            class="unresolvedCount"></span>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 inline-flex">
                                <button type="submit" class="btn_primary_state allWidth mr-25">Export</button>
                                <button type="button" class="btn_cancel_primary_state  allWidth"
                                    data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="restoreTabsModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg modal-width mt-10v">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-100 mb-15">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Restore Selected Tabs
                            </h4>
                        </div>
                    </div>
                    <form id="restoreTabsForm" class="mb-0" onsubmit="restoreTabs(event)">
                        <div class="custom-left-col">
                            <input type="hidden" class="showVersions" name="showVersions" />
                            <input type="hidden" class="showDeleted" name="showDeleted" />
                            <input type="hidden" class="jobTime" name="jobTime" />
                            <input type="hidden" class="jobId" name="jobId" />
                            <input type="hidden" class="jobType" name="jobType" />
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Job Name</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div class="mb-0 allWidth flex relative">
                                            <input type="text"
                                                class="form-control form_input custom-form-control font-size"
                                                placeholder="Job Name" name="restoreJobName" required
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('partials.device-code-authentication')
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Options</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div class="row">
                                            <div class="pr-0 pl-3">
                                                <label>Restore the following items:</label>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="relative allWidth">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="restoreChangedItems" type="checkbox"
                                                            class="form-check-input">
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Changed Items</span>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="relative allWidth">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="restoreMissingItems" type="checkbox"
                                                            class="form-check-input">
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Missing Items</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom-right-col">
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Selected Tabs</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-215p">
                                        <div class="allWidth">
                                            <table id="tabsTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <th>
                                                            <label style="top: 45%;left: 10px;"
                                                                class="checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>Tab</th>
                                                        <th>Channel</th>
                                                        <th>Team</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> tabs selected <span
                                                                class="unresolvedCount"></span>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-form-70 inline-flex">
                                    <button type="submit" class="btn_primary_state allWidth mr-25">Restore</button>
                                    <button type="button" class="btn_cancel_primary_state  allWidth"
                                        data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="firstSearchModal" class="modal modal-center" role="dialog">

        <div class="modal-dialog modal-lg" style="width: 500px; margin:20vh auto;">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>

            <!-- Modal content-->
            <div class="modal-content ">

                <div id="seaerch_modal_id" class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Search
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70 mb-1">Status:</div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="firstSuccess" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Success</span>
                            </div>
                            <div class="halfWidth" style="position: relative;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="firstStop" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Stop</span>
                            </div>

                        </div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="firstWarning" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Warning</span>
                            </div>
                            <div class="halfWidth" style="position: relative;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="firstError" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Error</span>
                            </div>
                        </div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="firstRunning" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Running</span>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70" style="display: inline-flex;">
                            <button type="button" onclick="applySearch()"
                                class="btn_primary_state  halfWidth mr-25">Apply</button>
                            <button type="button" class="btn_cancel_primary_state  halfWidth"
                                onclick="resetSearch()">Reset</button>
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>

        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="secondSearchModal" class="modal modal-center" role="dialog">

        <div class="modal-dialog modal-lg" style="width: 500px; margin:20vh auto;">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>

            <!-- Modal content-->
            <div class="modal-content ">

                <div id="seaerch_modal_id" class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="per-req">Search
                            </h4>
                        </div>
                    </div>

                    <div class="row" id="Duration-Section">
                        <div class="input-form-70 mb-1">Duration:</div>

                        <div class="input-form-70" style="display: inline-flex;">
                            <div class="mr-25" style="position: relative">
                                <input class="form_input form-control html-duration-picker" id="durationFrom"
                                    placeholder="From" />
                            </div>

                            <div style="position: relative">
                                <input class="form_input form-control html-duration-picker" id="durationTo"
                                    placeholder="To" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70 mb-1">Status:</div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="secondSuccess" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Success</span>
                            </div>
                            <div class="halfWidth" style="position: relative;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="secondStop" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Stop</span>
                            </div>

                        </div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="secondWarning" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Warning</span>
                            </div>
                            <div class="halfWidth" style="position: relative;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="secondError" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Error</span>
                            </div>
                        </div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="secondRunning" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Running</span>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70" style="display: inline-flex;">
                            <button type="button" onclick="secondApplySearch()"
                                class="btn_primary_state  halfWidth mr-25">Apply</button>
                            <button type="button" class="btn_cancel_primary_state  halfWidth"
                                onclick="secondResetSearch()">Reset</button>
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>

        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <script>
        let durationData = null;
        let CurrentFilterFor = null;
        $(document).ready(function() {
            var parent = $('.parent-link').attr('data-parent');
            $('.submenu-restore-history.submenu a[data-route="' + parent + '"]').addClass('active');
            var row = $('a.sub-menu-link.active').closest('.row');
            row.find('.left-nav-list').addClass('active').removeClass('collapsed');
            $('.submenu-restore-history').addClass('in');

            $('#sessionsTable').DataTable({
                'ajax': {
                    "type": "GET",
                    "url": "{{ url('getRestoreSessionInfo', [$data['kind'], $data['history']['id']]) }}",
                    "dataSrc": function(json) {
                        @if ($data['history']['status'] == 'In Progress')
                            CheckSessionsTable();
                        @endif
                        return json
                    },
                    "data": {},
                    "statusCode": {
                        401: function() {
                            window.location.href = "{{ url('/') }}";
                        },
                        402: function() {
                            let errMessage = "   ERROR   ";
                            $(".danger-oper .danger-msg").html(
                                "{{ __('variables.errors.restore_session_expired') }}");
                            $(".danger-oper").css("display", "block");
                            setTimeout(function() {
                                $(".danger-oper").css("display", "none");
                                window.location.reload();
                            }, 3000);
                        }
                    },
                    "dataType": "json",
                },
                'columns': [{
                        "class": "col-lg-8",
                        "data": null,
                        render: function(data, type, full, meta) {
                            var status = data.status;
                            var res = data.message ?? data.title;
                            if (status == "Success") {
                                res =
                                    '<div class="col-lg-1" style="margin-right: -5%;padding-top: 2px;padding-left: 0px;"><i  class="fa fa-check-circle"  style="font-size:14px;color:green"></i> </div><div style="padding-left: 0px;" class="col-lg-11">' +
                                    res + ' </div>';

                            } else if (status == "Stop") {
                                res =
                                    '<div class="col-lg-1" style="margin-right: -5%;padding-top: 2px;padding-left: 0px;"><i  class="fa fa-stop"  style="font-size:14px;color:red"></i> </div><div style="padding-left: 0px;" class="col-lg-11">' +
                                    res + ' </div>';

                            } else if (status == "Warning") {
                                res =
                                    '<div class="col-lg-1" style="margin-right: -5%;padding-top: 2px;padding-left: 0px;"><i  class="fas fa-exclamation-triangle"  style="font-size:14px;color:#efff00"></i> </div><div style="padding-left: 0px;" class="col-lg-11">' +
                                    res + ' </div>';

                            } else if (status == "Error") {
                                res =
                                    '<div class="col-lg-1" style="margin-right: -5%;padding-top: 2px;padding-left: 0px;"><i  class="fa fa-ban"  style="font-size:14px;color:red"></i> </div><div style="padding-left: 0px;" class="col-lg-11">' +
                                    res + ' </div>';

                            } else if (status == "Running") {
                                res =
                                    '<div class="col-lg-1" style="margin-right: -5%;padding-top: 2px;padding-left: 0px;"><i  class="fa fa-hourglass-start"  style="font-size:14px;color:green"></i> </div><div style="padding-left: 0px;" class="col-lg-11">' +
                                    res + ' </div>';

                            }
                            return res;
                        }
                    },
                    {
                        "data": "duration",
                        "class": "col-lg-2",
                        render: function(data, type, full, meta) {
                            return data ? toHHMMSS(data) : "";
                        }
                    },
                    {
                        "data": null,
                        "class": "col-lg-2",
                        render: function(data, type, full, meta) {
                            var status = data.status;
                            var statusClass = 'text-orange1';
                            statusClass = (status == 'Stop') ? 'text-danger' :
                                statusClass;
                            statusClass = (status == 'Success') ? 'text-success' :
                                statusClass;
                            statusClass = (status == 'Error') ? 'text-danger' :
                                statusClass;
                            statusClass = (status == 'Warning') ? 'text-warning' :
                                statusClass;
                            statusClass = (status == 'Running') ? 'text-primary' :
                                statusClass;
                            return '<span class="' + statusClass + '">' + status + '</span>';
                        }
                    }
                ],
                dom: 'Bfrtip',
                "fnDrawCallback": function() {
                    var icon =
                        '<div class="search-container"><img class="search-icon session-search-icon" src="/svg/search.svg"></div>';
                    if ($("#sessionsTable_filter label").find('.search-icon').length == 0)
                        $('#sessionsTable_filter label').append(icon);
                    $('#sessionsTable_filter input').addClass('form_input form-control');
                },
                buttons: [

                    {
                        extend: 'csvHtml5',
                        text: '<img src="/svg/excel.svg" style="width:15px;height:30px;">',
                        titleAttr: 'Export to csv'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<img src="/svg/pdf.svg" style="width:15px;height:30px;">',
                        customize: function(doc) {
                            customizePdf(doc)
                        },
                        titleAttr: 'Export to pdf',
                        // exportOptions: {
                        //    // columns: 'th:not(:first-child,:last-child)',
                        //     format: {
                        //         body: function(data, column, row) {
                        //             data=data+"";
                        //             //if it is html, return the text of the html instead of html
                        //             if (data.includes("<i")) {
                        //                 return $(data)[1].innerText;
                        //             }
                        //             else   if (/<\/?[^>]*>/.test(data)) {
                        //                 return $(data).text();
                        //             }
                        //             else {
                        //                 return data;
                        //             }
                        //         }
                        //     }
                        // }
                    },
                    {
                        text: '<img src="/svg/filter.svg" style="width:15px;height:30px;">',
                        titleAttr: 'Advanced Search',
                        action: function(e, dt, node, config) {
                            $('#secondSearchModal').modal('show');
                        }
                    }
                ],
                "processing": false,
                "scrollY": "300px",
                "bInfo": false,
                "paging": false,
                "autoWidth": false,
                "bSort": false,
                language: {
                    "sEmptyTable": "No available session info",
                    search: "",
                    searchPlaceholder: "Search...",
                    loadingRecords: '&nbsp;',
                    // processing: '<div style="margin-top: 1%; width: 6rem; height: 6rem;" class="spinner-border"></div>'
                },
                'columnDefs': [{
                    'targets': [0, 1], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            $('#sessionsTable').DataTable().buttons().container()
                .prependTo('#sessionsTable_filter');

            //---------------------------------------//
            $('#teamsTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" >' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name",
                        "width": "30%"
                    },
                    {
                        "data": "email",
                        "width": "70%"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "230px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //--------------------------------------------------//
            $('#channelsTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="hidden" value="' + data.teamId +
                                '" class="form-check-input teamId">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name",
                        "width": "30%"
                    },
                    {
                        "data": "teamName",
                        "width": "70%"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "210px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //--------------------------------------------------//
            $('#channelsPostsTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="hidden" value="' + data.teamId +
                                '" class="form-check-input teamId">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-teamId="' + data
                                .teamId + '" data-channelId="' + data.channelId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            return data.name;
                        },
                        "width": "30%"
                    },
                    {
                        "data": "teamName",
                        "width": "70%"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "230px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //--------------------------------------------------//
            $('#exportChannelsPostsTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="hidden" value="' + data.teamId +
                                '" class="form-check-input teamId">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-teamId="' + data
                                .teamId + '" data-channelId="' + data.channelId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            return data.name;
                        },
                        "width": "30%"
                    },
                    {
                        "data": "teamName",
                        "width": "70%"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "200px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //--------------------------------------------------//
            $('#channelsFilesTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="hidden" value="' + data.teamId +
                                '" class="form-check-input teamId">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            return data.name;
                        },
                        "width": "30%"
                    },
                    {
                        "data": "teamName",
                        "width": "70%"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "45px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //--------------------------------------------------//
            $('#channelsTabsTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="hidden" value="' + data.teamId +
                                '" class="form-check-input teamId">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            return data.name;
                        },
                        "width": "30%"
                    },
                    {
                        "data": "teamName",
                        "width": "70%"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "145px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //--------------------------------------------------//
            $('#exportChannelsFilesTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="hidden" value="' + data.teamId +
                                '" class="form-check-input teamId">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            return data.name;
                        },
                        "width": "30%"
                    },
                    {
                        "data": "teamName",
                        "width": "70%"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "200px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //--------------------------------------------------//
            $('#filesTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="hidden" value="' + data.teamId +
                                '" class="form-check-input teamId">' +
                                '<input type="hidden" value="' + data.channelId +
                                '" class="form-check-input channelId">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-teamId="' + data
                                .teamId + '" data-channelId="' + data.channelId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            return data.name;
                        },
                    },
                    {
                        "data": "channelName",
                    },
                    {
                        "data": "teamName",
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "210px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //--------------------------------------------------//
            $('#exportFilesTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="hidden" value="' + data.teamId +
                                '" class="form-check-input teamId">' +
                                '<input type="hidden" value="' + data.channelId +
                                '" class="form-check-input channelId">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-teamId="' + data
                                .teamId + '" data-channelId="' + data.channelId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            return data.name;
                        },
                    },
                    {
                        "data": "channelName",
                    },
                    {
                        "data": "teamName",
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "200px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //--------------------------------------------------//
            $('#exportPostsTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="hidden" value="' + data.teamId +
                                '" class="form-check-input teamId">' +
                                '<input type="hidden" value="' + data.channelId +
                                '" class="form-check-input channelId">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-teamId="' + data
                                .teamId + '" data-channelId="' + data.channelId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            return data.name;
                        },
                    },
                    {
                        "data": "channelName",
                    },
                    {
                        "data": "teamName",
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "200px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //--------------------------------------------------//
            $('#tabsTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="hidden" value="' + data.teamId +
                                '" class="form-check-input teamId">' +
                                '<input type="hidden" value="' + data.channelId +
                                '" class="form-check-input channelId">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-teamId="' + data
                                .teamId + '" data-channelId="' + data.channelId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            return data.name;
                        },
                    },
                    {
                        "data": "channelName",
                    },
                    {
                        "data": "teamName",
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "145px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //---------------------------------------//
            let detailsTable = $('#detailsTable').DataTable({
                'ajax': {
                    "type": "GET",
                    "url": "{{ url('getRestoreDetails', [$data['kind'], $data['historyId']]) }}",
                    "dataSrc": function(json) {
                        CheckItemstable();
                        return json
                    },
                    "data": {},
                    "statusCode": {
                        401: function() {
                            window.location.href = "{{ url('/') }}";
                        },
                        402: function() {
                            let errMessage = "   ERROR   ";
                            $(".danger-oper .danger-msg").html(
                                "{{ __('variables.errors.restore_session_expired') }}");
                            $(".danger-oper").css("display", "block");
                            setTimeout(function() {
                                $(".danger-oper").css("display", "none");
                                window.location.reload();
                            }, 3000);
                        }
                    },
                    "dataType": "json"
                },
                'columns': [{
                        "class": "left-col",
                        "data": null,
                        render: function(data) {
                            if ("{{ $data['history']->type }}" == "team") {
                                let resultsArr = JSON.parse(data.item_id);
                                let str = '<ul class="p-0 m-0">';
                                resultsArr.forEach((e) => {
                                    str += '<li>' + e.name + ' (' + e.email + ')</li>';
                                });
                                str += '</ul>';
                                return str;
                            } else if ("{{ $data['history']->type }}" == "post" ||
                                "{{ $data['history']->type }}" == "file" ||
                                "{{ $data['history']->type }}" ==
                                "tab") {
                                let resultsArr = JSON.parse(data.item_id);
                                let str = '<ul class="test p-0 m-0">';
                                resultsArr.forEach((e) => {
                                    str += '<li>' + e.name + '</li>';
                                });
                                str += '</ul>';
                                return str;
                            } else {
                                return data.item_name;
                            }
                        }
                    },
                    {
                        "data": "item_parent_name",
                        "class": "ellipsisContent",
                    },
                    {
                        "data": null,
                        "class": "",
                        render: function(data, type, full, meta) {
                            var status = data.status;
                            if (status == 200) {
                                status = 'Success';
                            }
                            var statusClass = 'text-orange1';
                            statusClass = (status == 'Failed') ? 'text-danger' :
                                statusClass;
                            statusClass = (status == 'Success') ? 'text-success' :
                                statusClass;
                            statusClass = (status == 'Error') ? 'text-danger' :
                                statusClass;
                            statusClass = (status == 'Warning') ? 'text-warning' :
                                statusClass;
                            statusClass = (status == 'Running') ? 'text-primary' :
                                statusClass;
                            return '<span class="' + statusClass + '">' + status + '</span>';
                        }
                    },
                    {
                        "data": "duration",
                        "class": "",
                    },
                    {
                        data: null,
                        "class": "",
                        render: function(data) {
                            if (data.exported_file_size)
                                return (data.exported_file_size / 1024 / 1024).toFixed(2) + ' MB';
                        }
                    },
                    {
                        "data": "last_download_date",
                        "class": "",
                    },
                    {
                        "data": null,
                        "class": "text-center",
                        render: function(data) {
                            var temp = "{{ $data['history']['sub_type'] }}";
                            var isRestore = (temp.match(/Restore/g) || []).length;
                            if (data.result) {
                                return getResult(data.result);
                            } else if (isRestore == 0 && data.status == 'Success' &&
                                "{{ $data['history']['status'] }}" != "Expired") {
                                @if ($role->hasPermissionTo('teams_download_exported_files'))
                                    return '<div><img data-id="' + data.id +
                                        '" class="tableIcone hand downloadExportedFile" style="width: 13px; margin-right:0;" src="/svg/download\.svg " title="Download"></div>';
                                @endif
                            }
                            if (data.error_response)
                                return '<div class="ellipsisContent" title="' + data
                                    .error_response + '">' + data.error_response + '<div>';
                        }
                    },
                    {
                        "data": null,
                        "class": "text-center",
                        render: function(data) {
                            var temp = "{{ $data['history']['sub_type'] }}";
                            var isRestore = (temp.match(/Restore/g) || []).length;
                            if (data.status == 'Failed') {
                                if (isRestore === 0) {
                                    @if ($role->hasPermissionTo('teams_export_again'))
                                        return '<div><img data-id="' + data.id +
                                            '" class="tableIcone hand restoreFailedAgain" style="width: 13px; margin-right:0;" src="/svg/restore\.svg " title="Retry"></div>';
                                    @endif
                                } else {
                                    @if ($role->hasPermissionTo('teams_restore_again'))
                                        return '<div><img data-id="' + data.id +
                                            '" class="tableIcone hand restoreFailedAgain" style="width: 13px; margin-right:0;" src="/svg/restore\.svg " title="Retry"></div>';
                                    @endif
                                }
                            }
                            return '';
                        }
                    },
                ],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if ($(this).hasClass('jobName'))
                            $(this).attr('title', $(this).find('a').html());
                        else if (!$(this).hasClass('hasHTML') && $(this).children().length == 0)
                            $(this).attr('title', $(this).html());
                    });
                },
                dom: 'Bfrtip',
                "fnDrawCallback": function(data) {
                    if (data.json) {
                        if (data.json[0].exported_file_size) {
                            // detailsTable.column(3).visible(true);
                            detailsTable.column(4).visible(true);
                            detailsTable.column(5).visible(true);
                        }
                        if (data.json[0].type == "team") {
                            detailsTable.column(1).visible(false);
                        }
                    }
                    var icon =
                        '<div class="search-container"><img class="search-icon session-search-icon" src="/svg/search.svg"></div>';
                    if ($("#detailsTable_filter label").find('.search-icon').length == 0)
                        $('#detailsTable_filter label').append(icon);
                    $('#detailsTable_filter input').addClass('form_input form-control');
                    //-----------------------------------------//
                    $('.retryItem').click(function() {});
                    //-----------------------------------------//
                    $('.downloadExportedFile').click(function() {
                        downloadExportedFiles($(this).attr('data-id'));
                    });
                    //-----------------------------------------//
                    $('.restoreFailedAgain').click(function() {
                        let subType = "{{ $data['history']['sub_type'] }}";
                        let isRestore = (subType.match(/Restore/g) || []).length;
                        if (isRestore > 0)
                            restoreAgainModal('failed', "{{ $data['history']['id'] }}",
                                "{{ $data['history']['type'] }}", subType, $(this).attr(
                                    'data-id'));
                        else
                            exportAgainModal('failed', "{{ $data['history']['id'] }}",
                                "{{ $data['history']['type'] }}", subType, $(this).attr(
                                    'data-id'));
                    });
                    //-----------------------------------------//
                },
                buttons: [

                    {
                        extend: 'csvHtml5',
                        text: '<img src="/svg/excel.svg" style="width:15px;height:30px;">',
                        titleAttr: 'Export to csv'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<img src="/svg/pdf.svg" style="width:15px;height:30px;">',
                        customize: function(doc) {
                            customizePdf(doc)
                        },
                        titleAttr: 'Export to pdf',
                    },
                    {
                        text: '<img src="/svg/filter.svg" style="width:15px;height:30px;">',
                        titleAttr: 'Advanced Search',
                        action: function(e, dt, node, config) {
                            $('#firstSearchModal').modal('show');
                        }
                    }
                ],
                "processing": false,
                "scrollY": "300px",
                "bInfo": false,
                "paging": false,
                // "searching": false,
                "autoWidth": false,
                "bSort": false,
                language: {
                    "sEmptyTable": "No available session info",
                    search: "",
                    searchPlaceholder: "Search...",
                    loadingRecords: '&nbsp;',
                    // processing: '<div style="margin-top: 1%; width: 6rem; height: 6rem;" class="spinner-border"></div>'
                },
                'columnDefs': [{
                    'targets': [4, 5], // column index (start from 0)
                    'visible': false, // set orderable false for selected columns
                }]
            });
            $('#detailsTable').DataTable().buttons().container()
                .prependTo('#detailsTable_filter');
            //---------------------------------------//
            $('.restoreAgain').click(function() {
                let subType = "{{ $data['history']['sub_type'] }}";
                let isRestore = (subType.match(/Restore/g) || []).length;
                if (isRestore > 0)
                    restoreAgainModal('all', "{{ $data['history']['id'] }}",
                        "{{ $data['history']['type'] }}", subType);
                else
                    exportAgainModal('all', "{{ $data['history']['id'] }}",
                        "{{ $data['history']['type'] }}", subType);
                //----------------------------------------//
            });
            //---------------------------------------//
            //---------------------------------------//
            $('input[name="siteType"]').change(function() {
                let form = $(this).closest('form');
                if ($(this).val() == 'original') {
                    form.find('input[name="alias"]').attr('disabled', 'disabled').val('');
                } else {
                    form.find('input[name="alias"]').removeAttr('disabled');
                }
            });
            $('input[name="siteType"]').change();
            //---------------------------------------//
            $('input[name="listType"]').change(function() {
                let form = $(this).closest('form');
                if ($(this).val() == 'original') {
                    form.find('input[name="list"]').attr('disabled', 'disabled').val('');
                } else {
                    form.find('input[name="list"]').removeAttr('disabled');
                }
            });
            $('input[name="listType"]').change();
            //---------------------------------------//
            $('input[name="folderType"]').change(function() {
                if ($(this).val() == 'original') {
                    $('.foldersAnotherOptions_cont.targetMailbox input[name="folder"]').attr('disabled',
                        'disabled');
                    $('.itemsAnotherOptions_cont.targetMailbox input[name="folder"]').attr('disabled',
                        'disabled');
                } else {
                    $('.foldersAnotherOptions_cont.targetMailbox input[name="folder"]').removeAttr(
                        'disabled');
                    $('.itemsAnotherOptions_cont.targetMailbox input[name="folder"]').removeAttr(
                        'disabled');
                }
            });
            $('input[name="folderType"]').change();
        });
        //---------------------------------------//
        $('.formatDate').each(function() {
            let val = $(this).html().trim();
            $(this).html(formatDate(val));
        });
        //---------------------------------------//
        $("input.date").datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function() {
                $('#itemsTable').DataTable().draw()
            }
        });
        //--------------------------------------------------//
        $('input[name="pointType"]').change(function() {
            let form = $(this).closest('form');
            if ($(this).val() == 'all') {
                form.find('.restorePointRow').addClass('hide');
                form.find('.restorePointRow input').removeAttr('required').val('');
            } else {
                form.find('.restorePointRow').removeClass('hide');
                form.find('.restorePointRow input').attr('required', 'required');
            }
        });
        $('input[name="pointType"]').change();
        //---------------------------------------//

        //---------------------------------------//
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                res = true;
                let conditionsArray = [];

                if (settings.nTable.id !== 'detailsTable') {
                    return true;
                }

                if ($("#firstSuccess")[0].checked === true) {
                    conditionsArray.push("success");
                }
                if ($("#firstStop")[0].checked === true) {
                    conditionsArray.push("stop");
                }
                if ($("#firstWarning")[0].checked === true) {
                    conditionsArray.push("warning");
                }
                if ($("#firstError")[0].checked === true) {
                    conditionsArray.push("error");
                }
                if ($("#firstRunning")[0].checked === true) {
                    conditionsArray.push("running");
                }
                if (conditionsArray.length > 0 && (!data[2] || conditionsArray.indexOf(data[2].toLowerCase()) === -1)) {
                    res = false;
                }
                return res;
            }
        );

        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                res = true;
                let DurationFrom = $("#durationFrom").val()
                let DurationTo = $("#durationTo").val();
                if (DurationTo == "00:00:00")
                    DurationTo = "24:00:00";
                let conditionsArray = [];

                if (settings.nTable.id !== 'sessionsTable') {
                    return true;
                }

                if (!data[2] || Date.parse("01/01/2011 " + DurationFrom) > Date.parse("01/01/2011 " + data[1])) {
                    res = false;
                }

                if (!data[2] || Date.parse("01/01/2011 " + DurationTo) < Date.parse("01/01/2011 " + data[1])) {
                    res = false
                }

                if ($("#secondSuccess")[0].checked === true) {
                    conditionsArray.push("success");
                }
                if ($("#secondStop")[0].checked === true) {
                    conditionsArray.push("stop");
                }
                if ($("#secondWarning")[0].checked === true) {
                    conditionsArray.push("warning");
                }
                if ($("#secondError")[0].checked === true) {
                    conditionsArray.push("error");
                }
                if ($("#secondRunning")[0].checked === true) {
                    conditionsArray.push("running");
                }
                if (conditionsArray.length > 0 && (!data[2] || conditionsArray.indexOf(data[2].toLowerCase()) === -1)) {
                    res = false;
                }
                return res;
            }
        );
        //---------------------------------------//
        function getResult(result) {
            let resultArr = JSON.parse(result);
            let temp, finalValue;
            let str = '<div class="col-lg-12 sess-info text-left" style="max-width:300px">';
            $.each(resultArr, function(key, value) {
                finalValue = value;
                temp = key;
                temp = (temp.substr(0, 1)).toUpperCase() + temp.substr(1);
                temp = temp.replace(/([A-Z])/g, " $1");
                temp = temp.trim();
                str += '<span class="col-lg-10 sess-title">' + temp + ':</span>';
                if ($.isArray(value)) {
                    finalValue = value.length;
                }
                str += '<span class="col-lg-2 sess-title">' + finalValue + '</span>';
            });
            str += '</div>';
            return str;
        }
        //---------------------------------------//
        function resetSearch() {
            $("#firstSuccess").attr('checked', false);
            $("#firstStop").attr('checked', false);
            $("#firstError").attr('checked', false);
            $("#firstWarning").attr('checked', false);
            $("#firstRunning").attr('checked', false);
            $('#detailsTable').DataTable().draw();
        }

        function applySearch() {
            $('#detailsTable').DataTable().draw();
        }

        function secondResetSearch() {
            $("#durationFrom").val("00:00:00");
            $("#durationTo").val("00:00:00");
            $("#secondSuccess").attr('checked', false);
            $("#secondStop").attr('checked', false);
            $("#secondError").attr('checked', false);
            $("#secondWarning").attr('checked', false);
            $("#secondRunning").attr('checked', false);
            $('#sessionsTable').DataTable().draw();
        }

        function secondApplySearch() {
            $('#sessionsTable').DataTable().draw();
        }
        //---------------------------------------//
        function toHHMMSS(secs) {
            if (secs.length > 7) {
                let duration = secs.substring(0, 8);
                return duration == "00:00:00" ? "" : duration;
            }
        }

        function toMin(timeString) {

            let timeArr = timeString.split(":");
            return parseInt(timeArr[0]) * 60 + parseInt(timeArr[1]);
        }

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();


            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return day + "/" + month + "/" + year;

        }

        function formatDateWithoutTime(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;
            return year + "-" + month + "-" + day;
        }

        function ConvertToDatePicker() {
            $("#durationFrom").addClass("html-duration-picker");
            $("#durationTo").addClass("html-duration-picker")
        }

        function downloadExportedFiles(id) {
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "GET",
                url: "{{ url('downloadExportedFile') }}/teams/" + id,
                data: {},
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    //---------------------------------//
                    CheckItemstable(true);
                    //---------------------------------//
                    window.open(data, '_blank');
                    //---------------------------------//
                    $('#detailsTable').DataTable().ajax.reload();
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html(
                            "{{ __('variables.errors.restore_session_expired') }}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(error) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');

                    let errMessage = "   ERROR   ";
                    if (error.responseText) {
                        let err = JSON.parse(error.responseText);
                        if (err.message)
                            errMessage = "ERROR  : " + err.message;
                    }
                    $(".danger-oper .danger-msg").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");

                    }, 8000);
                }
            });
        }

        //---- Modal Functions
        function confirmDelete(deletedRepId, deletedRepName) {
            document.getElementById('deletedrep').value = deletedRepId;
            $("#deleteTxt").html("Delete Storage " + deletedRepName + " ?");

        }
        //----------------------------------------------------//
        function showErrorMessage(message) {
            $(".danger-oper .danger-msg").html(message);
            $(".danger-oper").css("display", "block");
            setTimeout(function() {
                $(".danger-oper").css("display", "none");

            }, 8000);
        }
        //-------------------------------------------------------------//
        function showSuccessMessage(message) {
            $(".success-oper .success-msg").html(message);
            $(".success-oper").css("display", "block");
            setTimeout(function() {
                $(".success-oper").css("display", "none");
                window.location.href = "{{ url('restore-history', $data['kind']) }}";
            }, 2000);
        }
        //-------------------------------------------------------------//
        function CheckSessionsTable() {
            if ("{{ $data['history']['status'] }}" == 'In Progress')
                setTimeout(() => {
                    $('#sessionsTable').DataTable().ajax.reload();
                }, 15000);
        }
        //---------------------------------------------------//
        function CheckItemstable(force = false) {
            if ("{{ $data['history']['status'] }}" == 'In Progress' || force)
                setTimeout(() => {
                    $('#detailsTable').DataTable().ajax.reload();
                }, 15000);
        }
        //----------------------------------------------------//



        //---- Modal Functions
        //----------------------------------------------------//
        function restoreAgainModal(state = 'all', id, type, subType, detailsId = '') {
            $("[name='restoreJobName']").val("");
            let options = [];
            let tableData = [];
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "GET",
                url: "{{ url('getHistoryDetails', $data['kind']) }}/" + id,
                data: {},
                success: function(data) {
                    jobType = data.restore_point_type;
                    jobTime = data.restore_point_time;
                    showDeleted = data.is_restore_point_show_deleted == 1;
                    showVersions = data.is_restore_point_show_version == 1;
                    jobId = data.backup_job_id;
                    $('.jobId').val(data.backup_job_id);
                    $('.jobType').val(data.restore_point_type);
                    $('.jobTime').val(data.restore_point_time);
                    $('.showDeleted').val(data.is_restore_point_show_deleted == 1);
                    $('.showVersions').val(data.is_restore_point_show_version == 1);
                    if (type == "team") {
                        options = JSON.parse(data.options);
                        let teams = JSON.parse(data.details[0]["item_id"]);
                        tableData = [];
                        options = JSON.parse(data.options);
                        let folderTitle, parentName;
                        teams.forEach((e) => {
                            if (state == 'all' || data.details[0]["status"] == "Failed") {
                                tableData.push({
                                    "id": e.id,
                                    "name": e.name,
                                    "email": e.email,
                                });
                            }
                        });
                        let teamsCount = tableData.length;
                        //----------------------------------//
                        $('#teamsTable_wrapper').find('.boxesCount').html(teamsCount);
                        $('#teamsTable').DataTable().clear().draw();
                        $('#teamsTable').DataTable().rows.add(tableData); // Add new data
                        $('#teamsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('teamsTable');
                        adjustTable();
                        $("#teamsTable").DataTable().draw();
                        $('#restoreTeamsModal').find('.refreshDeviceCode').click();
                        $('#restoreTeamsModal').modal('show');
                        //----------------------------------//
                        $.each(options, function(key, value) {
                            if (value == 'true')
                                $('#restoreTeamsForm input[name="' + key + '"]').prop(
                                    'checked', 'checked');
                            else
                                $('#restoreTeamsForm input[name="' + key + '"]')
                                .removeProp('checked');
                        });
                        //--------------------------------------//
                    } else if (type == "channel") {
                        //-------------------------------------------------//
                        tableData = [];
                        options = JSON.parse(data.options);
                        let channels = data.details;
                        let unresolvedCount = 0;
                        channels.forEach((e) => {
                            if (state == 'all' || e.status == "Failed") {
                                tableData.push({
                                    "id": e.item_id,
                                    "name": e.item_name,
                                    "teamId": e.item_parent_id,
                                    "teamName": e.item_parent_name
                                });
                            }
                        });
                        let channelsCount = tableData.length;
                        $('#channelsTable_wrapper').find('.boxesCount').html(channelsCount);
                        $('#channelsTable').DataTable().clear().draw();
                        $('#channelsTable').DataTable().rows.add(tableData); // Add new data
                        $('#channelsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('channelsTable');
                        adjustTable();
                        $("#channelsTable").DataTable().draw();
                        //-------------------------------------------------//
                        $.each(options, function(key, value) {
                            if (value == 'true')
                                $('#restoreChannelsForm input[name="' + key + '"]').prop(
                                    'checked', 'checked');
                            else
                                $('#restoreChannelsForm input[name="' + key + '"]')
                                .removeProp('checked');
                        });
                        //-------------------------------------------------//
                        $('#restoreChannelsModal').find('.refreshDeviceCode').click();
                        $('#restoreChannelsModal').modal('show');

                    } else if (type == "channel-posts") {
                        //-------------------------------------------------//
                        tableData = [];
                        options = JSON.parse(data.options);
                        let channels = data.details;
                        let unresolvedCount = 0;
                        channels.forEach((e) => {
                            if (state == 'all' || e.status == "Failed") {
                                tableData.push({
                                    "id": e.item_id,
                                    "name": e.item_name,
                                    "teamId": e.item_parent_id,
                                    "teamName": e.item_parent_name
                                });
                            }
                        });
                        let channelsCount = tableData.length;
                        $('#channelsPostsTable_wrapper').find('.boxesCount').html(channelsCount);
                        $('#channelsPostsTable').DataTable().clear().draw();
                        $('#channelsPostsTable').DataTable().rows.add(tableData); // Add new data
                        $('#channelsPostsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('channelsPostsTable');
                        adjustTable();
                        $("#channelsPostsTable").DataTable().draw();
                        //-------------------------------------------------//
                        $.each(options, function(key, value) {
                            if (key == "pointType") {
                                if (value == 'all') {
                                    $('#restoreChannelsPostsForm').find('.restorePointRow').addClass(
                                        'hide');
                                    $('.mt-btn').removeClass('mt-32');
                                    $('#restoreChannelsPostsForm').find('.restorePointRow input')
                                        .removeAttr('required');
                                } else {
                                    $('#restoreChannelsPostsForm').find('.restorePointRow').removeClass(
                                        'hide');
                                    $('.mt-btn').addClass('mt-32');
                                    $('#restoreChannelsPostsForm').find('.restorePointRow input').attr(
                                        'required', 'required');
                                }
                                $('#restoreChannelsPostsForm [name="pointType"][value="' + value + '"]')
                                    .prop("checked", "checked");
                            } else if (key == "from")
                                $('#restoreChannelsPostsForm [name="restoreFrom"]').val(
                                    formatDateWithoutTime(value));
                            else if (key == "to")
                                $('#restoreChannelsPostsForm [name="restoreTo"]').val(
                                    formatDateWithoutTime(value));
                            else if (value == 'true')
                                $('#restoreChannelsPostsForm input[name="' + key + '"]').prop('checked',
                                    'checked');
                            else
                                $('#restoreChannelsPostsForm input[name="' + key + '"]').removeProp(
                                    'checked');
                        });
                        //-------------------------------------------------//
                        $('#restoreChannelsPostsModal').find('.refreshDeviceCode').click();
                        $('#restoreChannelsPostsModal').modal('show');

                    } else if (type == "channel-files") {
                        //-------------------------------------------------//
                        tableData = [];
                        options = JSON.parse(data.options);
                        let channels = data.details;
                        let unresolvedCount = 0;
                        channels.forEach((e) => {
                            if (state == 'all' || e.status == "Failed") {
                                tableData.push({
                                    "id": e.item_id,
                                    "name": e.item_name,
                                    "teamId": e.item_parent_id,
                                    "teamName": e.item_parent_name
                                });
                            }
                        });
                        let channelsCount = tableData.length;
                        $('#channelsFilesTable_wrapper').find('.boxesCount').html(channelsCount);
                        $('#channelsFilesTable').DataTable().clear().draw();
                        $('#channelsFilesTable').DataTable().rows.add(tableData); // Add new data
                        $('#channelsFilesTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('channelsFilesTable');
                        adjustTable();
                        $("#channelsFilesTable").DataTable().draw();
                        //-------------------------------------------------//
                        $.each(options, function(key, value) {
                            if (key == "pointType") {
                                if (value == 'all') {
                                    $('#restoreChannelsFilesForm').find('.restorePointRow').addClass(
                                        'hide');
                                    $('#restoreChannelsFilesForm').find('.restorePointRow input')
                                        .removeAttr('required');
                                } else {
                                    $('#restoreChannelsFilesForm').find('.restorePointRow').removeClass(
                                        'hide');
                                    $('#restoreChannelsFilesForm').find('.restorePointRow input').attr(
                                        'required', 'required');
                                }
                                $('#restoreChannelsFilesForm [name="pointType"][value="' + value + '"]')
                                    .prop("checked", "checked");
                            } else if (key == "fileVersion")
                                $('#restoreChannelsFilesForm [name="fileVersion"][value="' + value +
                                    '"]').prop("checked", "checked");
                            else if (key == "fileLastVersionAction")
                                $('#restoreChannelsFilesForm [name="fileLastVersionAction"][value="' +
                                    value + '"]').prop("checked", "checked");
                            else if (key == "from")
                                $('#restoreChannelsFilesForm [name="restoreFrom"]').val(
                                    formatDateWithoutTime(value));
                            else if (key == "to")
                                $('#restoreChannelsFilesForm [name="restoreTo"]').val(
                                    formatDateWithoutTime(value));
                            else if (value == 'true')
                                $('#restoreChannelsFilesForm input[name="' + key + '"]').prop('checked',
                                    'checked');
                            else
                                $('#restoreChannelsFilesForm input[name="' + key + '"]').removeProp(
                                    'checked');
                        });
                        //-------------------------------------------------//
                        $('#restoreChannelsFilesModal').find('.refreshDeviceCode').click();
                        $('#restoreChannelsFilesModal').modal('show');

                    } else if (type == "channel-tabs") {
                        //-------------------------------------------------//
                        tableData = [];
                        options = JSON.parse(data.options);
                        let channels = data.details;
                        let unresolvedCount = 0;
                        channels.forEach((e) => {
                            if (state == 'all' || e.status == "Failed") {
                                tableData.push({
                                    "id": e.item_id,
                                    "name": e.item_name,
                                    "teamId": e.item_parent_id,
                                    "teamName": e.item_parent_name
                                });
                            }
                        });
                        let channelsCount = tableData.length;
                        $('#channelsTabsTable_wrapper').find('.boxesCount').html(channelsCount);
                        $('#channelsTabsTable').DataTable().clear().draw();
                        $('#channelsTabsTable').DataTable().rows.add(tableData); // Add new data
                        $('#channelsTabsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('channelsTabsTable');
                        adjustTable();
                        $("#channelsTabsTable").DataTable().draw();
                        //-------------------------------------------------//
                        $.each(options, function(key, value) {
                            if (value == 'true')
                                $('#restoreChannelsTabsForm input[name="' + key + '"]').prop('checked',
                                    'checked');
                            else
                                $('#restoreChannelsTabsForm input[name="' + key + '"]').removeProp(
                                    'checked');
                        });
                        //-------------------------------------------------//
                        $('#restoreChannelsTabsModal').find('.refreshDeviceCode').click();
                        $('#restoreChannelsTabsModal').modal('show');

                    } else if (type == "file") {
                        let items = data.details;
                        tableData = [];
                        options = JSON.parse(data.options);
                        items.forEach((e) => {
                            let postsArr = JSON.parse(e.item_id);
                            if (!detailsId || detailsId == e.id)
                                postsArr.forEach((e1) => {
                                    if (state == 'all' || e.status == "Failed") {
                                        tableData.push({
                                            "id": e1.id,
                                            "name": e1.name,
                                            "teamName": e1.teamName,
                                            "channelName": e1.channelName,
                                            "teamId": e1.teamId,
                                            "channelId": e1.channelId,
                                        });
                                    }
                                })
                        });
                        //--------------------//
                        $('#filesTable_wrapper').find('.boxesCount').html(tableData.length);
                        $('#filesTable').DataTable().clear().draw();
                        $('#filesTable').DataTable().rows.add(tableData); // Add new data
                        $('#filesTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('filesTable');
                        adjustTable();
                        $("#filesTable").DataTable().draw();
                        //--------------------//
                        $('#restoreFilesModal').modal('show');
                        //--------------------//
                        $.each(options, function(key, value) {
                            if (key == "fileVersion") {
                                $('#restoreFilesForm [name="fileVersion"][value="' + value + '"]').prop(
                                    "checked", "checked");
                            } else if (key == "fileLastVersionAction") {
                                $('#restoreFilesForm [name="fileLastVersionAction"][value="' + value +
                                    '"]').prop("checked", "checked");
                            } else if (value == 'true')
                                $('#restoreFilesForm input[name="' + key + '"]').prop(
                                    'checked', 'checked');
                            else
                                $('#restoreFilesForm input[name="' + key + '"]')
                                .removeProp('checked');
                        });
                        //--------------------//
                        $('#restoreFilesModal').modal('show');
                    } else if (type == "tab") {
                        let items = JSON.parse(data.details[0]["item_id"]);
                        tableData = [];
                        options = JSON.parse(data.options);
                        let folderTitle, parentName;
                        items.forEach((e) => {
                            if (state == 'all' || data.details[0]["status"] == "Failed") {
                                tableData.push({
                                    "id": e.id,
                                    "name": e.name,
                                    "teamId": e.teamId,
                                    "teamName": e.teamName,
                                    "channelId": e.channelId,
                                    "channelName": e.channelName
                                });
                            }
                        });
                        let itemsCount = tableData.length;
                        //--------------------//
                        $('#tabsTable_wrapper').find('.boxesCount').html(tableData.length);
                        $('#tabsTable').DataTable().clear().draw();
                        $('#tabsTable').DataTable().rows.add(tableData); // Add new data
                        $('#tabsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('tabsTable');
                        adjustTable();
                        $("#tabsTable").DataTable().draw();
                        //--------------------//
                        $('#restoreTabsModal').find('.refreshDeviceCode').click();
                        $('#restoreTabsModal').modal('show');
                        //--------------------//
                        $.each(options, function(key, value) {
                            if (value == 'true')
                                $('#restoreTabsForm input[name="' + key + '"]').prop(
                                    'checked', 'checked');
                            else
                                $('#restoreTabsForm input[name="' + key + '"]')
                                .removeProp('checked');
                        });
                        //--------------------//
                        $('#restoreTabsModal').find('.refreshDeviceCode').click();
                        $('#restoreTabsModal').modal('show');
                    }
                    //-------------------------------------//
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    //-------------------------------------//
                    $('select').trigger('change');
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html(
                            "{{ __('variables.errors.restore_session_expired') }}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(error) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');

                    let errMessage = "   ERROR   ";
                    if (error.responseText) {
                        let err = JSON.parse(error.responseText);
                        if (err.message)
                            errMessage = "ERROR  : " + err.message;
                    }
                    $(".danger-oper .danger-msg").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");

                    }, 8000);
                }
            });
        }
        //-------------------------------------------------------------//
        function exportAgainModal(state = 'all', id, type, subType, detailsId = '') {
            $("[name='restoreJobName']").val("");
            let options = [];
            let tableData = [];
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "GET",
                url: "{{ url('getHistoryDetails', $data['kind']) }}/" + id,
                data: {},
                success: function(data) {
                    jobType = data.restore_point_type;
                    jobTime = data.restore_point_time;
                    showDeleted = data.is_restore_point_show_deleted == 1;
                    showVersions = data.is_restore_point_show_version == 1;
                    jobId = data.backup_job_id;
                    //------------------------------//
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    //------------------------------//
                    $('.jobId').val(data.backup_job_id);
                    $('.jobType').val(data.restore_point_type);
                    $('.jobTime').val(data.restore_point_time);
                    $('.showDeleted').val(data.is_restore_point_show_deleted == 1);
                    $('.showVersions').val(data.is_restore_point_show_version == 1);
                    if (type == "channel-posts") {
                        //-------------------------------------------------//
                        tableData = [];
                        options = JSON.parse(data.options);
                        let channels = data.details;
                        let unresolvedCount = 0;
                        channels.forEach((e) => {
                            if (state == 'all' || e.status == "Failed") {
                                tableData.push({
                                    "id": e.item_id,
                                    "name": e.item_name,
                                    "teamId": e.item_parent_id,
                                    "teamName": e.item_parent_name
                                });
                            }
                        });
                        let channelsCount = tableData.length;
                        $('#exportChannelsPostsTable_wrapper').find('.boxesCount').html(channelsCount);
                        $('#exportChannelsPostsTable').DataTable().clear().draw();
                        $('#exportChannelsPostsTable').DataTable().rows.add(tableData); // Add new data
                        $('#exportChannelsPostsTable').DataTable().columns.adjust()
                            .draw(); // Redraw the DataTable

                        checkTableCount('exportChannelsPostsTable');
                        adjustTable();
                        $("#exportChannelsPostsTable").DataTable().draw();
                        //-------------------------------------------------//
                        $('#exportChannelsPostsModal').modal('show');

                    } else if (type == "channel-files") {
                        //-------------------------------------------------//
                        tableData = [];
                        options = JSON.parse(data.options);
                        let channels = data.details;
                        let unresolvedCount = 0;
                        channels.forEach((e) => {
                            if (state == 'all' || e.status == "Failed") {
                                tableData.push({
                                    "id": e.item_id,
                                    "name": e.item_name,
                                    "teamId": e.item_parent_id,
                                    "teamName": e.item_parent_name
                                });
                            }
                        });
                        let channelsCount = tableData.length;
                        $('#exportChannelsFilesTable_wrapper').find('.boxesCount').html(channelsCount);
                        $('#exportChannelsFilesTable').DataTable().clear().draw();
                        $('#exportChannelsFilesTable').DataTable().rows.add(tableData); // Add new data
                        $('#exportChannelsFilesTable').DataTable().columns.adjust()
                            .draw(); // Redraw the DataTable

                        checkTableCount('exportChannelsFilesTable');
                        adjustTable();
                        $("#exportChannelsFilesTable").DataTable().draw();
                        //-------------------------------------------------//
                        $('#exportChannelsFilesModal').modal('show');

                    } else if (type == "file") {
                        let items = data.details;
                        tableData = [];
                        options = JSON.parse(data.options);
                        items.forEach((e) => {
                            let filesArr = JSON.parse(e.item_id);
                            if (!detailsId || detailsId == e.id)
                                filesArr.forEach((e1) => {
                                    if (state == 'all' || e.status == "Failed") {
                                        tableData.push({
                                            "id": e1.id,
                                            "name": e1.name,
                                            "teamName": e1.teamName,
                                            "channelName": e1.channelName,
                                            "teamId": e1.teamId,
                                            "channelId": e1.channelId,
                                        });
                                    }
                                })
                        });
                        //--------------------//
                        $('#exportFilesTable_wrapper').find('.boxesCount').html(tableData.length);
                        $('#exportFilesTable').DataTable().clear().draw();
                        $('#exportFilesTable').DataTable().rows.add(tableData); // Add new data
                        $('#exportFilesTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('exportFilesTable');
                        adjustTable();
                        $("#exportFilesTable").DataTable().draw();
                        //--------------------//
                        $('#exportFilesModal').modal('show');
                    } else if (type == "post") {
                        //--------------------//
                        let items = data.details;
                        tableData = [];
                        options = JSON.parse(data.options);
                        items.forEach((e) => {
                            let postsArr = JSON.parse(e.item_id);
                            if (!detailsId || detailsId == e.id)
                                postsArr.forEach((e1) => {
                                    if (state == 'all' || e.status == "Failed") {
                                        tableData.push({
                                            "id": e1.id,
                                            "name": e1.name,
                                            "teamName": e1.teamName,
                                            "channelName": e1.channelName,
                                            "teamId": e1.teamId,
                                            "channelId": e1.channelId,
                                        });
                                    }
                                })
                        });
                        //--------------------//
                        $('#exportPostsTable_wrapper').find('.boxesCount').html(tableData.length);
                        $('#exportPostsTable').DataTable().clear().draw();
                        $('#exportPostsTable').DataTable().rows.add(tableData); // Add new data
                        $('#exportPostsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('exportPostsTable');
                        adjustTable();
                        $("#exportPostsTable").DataTable().draw();
                        //--------------------//
                        $('#exportPostsModal').modal('show');
                    }
                    $('select').trigger('change');
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html(
                            "{{ __('variables.errors.restore_session_expired') }}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(error) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');

                    let errMessage = "   ERROR   ";
                    if (error.responseText) {
                        let err = JSON.parse(error.responseText);
                        if (err.message)
                            errMessage = "ERROR  : " + err.message;
                    }
                    $(".danger-oper .danger-msg").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");

                    }, 8000);
                }
            });
        }
        //----------------------------------------------------//


        //---- sites & Folders & Items Functions
        function onTableResultChange(tableName) {
            let folders = $('#' + tableName + '_wrapper').find('tbody .form-check-input:checked');
            let foldersCount = folders.length;
            let unresolvedCount = 0;
            $('#' + tableName + '_wrapper').find('.boxesCount').html(foldersCount);
        }
        //---------------------------------------------------//
        function checkTableCount(tableName) {
            $('#' + tableName + '_wrapper').find('thead .form-check-input').click(function() {
                if ($(this).prop('checked'))
                    $('#' + tableName + '_wrapper').find('tbody .form-check-input').each(function() {
                        $(this).prop('checked', true);
                    });
                else
                    $('#' + tableName + '_wrapper').find('tbody .form-check-input').each(function() {
                        $(this).prop('checked', false);
                    });
                onTableResultChange(tableName);
            });

            $('#' + tableName + '_wrapper').find('tbody .form-check-input').change(function() {
                onTableResultChange(tableName);
            });
            adjustTable();
            $("#" + tableName).DataTable().draw();
        }
        //---------------------------------------------------//

        //---- Ajax Functions
        //----------------------------------------------------//
        function restoreTeams() {
            event.preventDefault()
            var data = $('#restoreTeamsForm').serialize();
            data += "&restoreSettings=" + $("#restoreTeamsForm [name='restoreSettings']")[0].checked;
            data += "&restoreMembers=" + $("#restoreTeamsForm [name='restoreMembers']")[0].checked;
            data += "&restoreMissingItems=" + $("#restoreTeamsForm [name='restoreMissingItems']")[0].checked;
            data += "&restoreChangedItems=" + $("#restoreTeamsForm [name='restoreChangedItems']")[0].checked;
            let teams = $('#restoreTeamsForm .mailboxCheck:checked');
            let teamsArr = [];
            teams.each(function() {
                let tr = $(this).closest('tr');
                teamsArr.push({
                    id: $(this).val().trim(),
                    name: tr.find('td:nth-child(2)').html(),
                    email: tr.find('td:nth-child(3)').html(),
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreTeam') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "teams=" + JSON.stringify(teamsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000);
                    $('#restoreTeamsModal').modal('hide');
                    $('#historyTable').DataTable().ajax.reload();
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html(
                            "{{ __('variables.errors.restore_session_expired') }}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(error) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    let errMessage = "   ERROR   ";
                    if (error.responseText) {
                        let err = JSON.parse(error.responseText);
                        if (err.message)
                            errMessage = "ERROR  : " + err.message;
                    }
                    $(".danger-oper .danger-msg").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");

                    }, 8000);
                }
            });
            return false;
        }
        //---------------------------------------------------//
        function restoreChannels() {
            event.preventDefault()
            var data = $('#restoreChannelsForm').serialize();
            data += "&restoreMissingItems=" + $("#restoreChannelsForm [name='restoreMissingItems']")[0].checked;
            data += "&restoreChangedItems=" + $("#restoreChannelsForm [name='restoreChangedItems']")[0].checked;
            let channels = $('#restoreChannelsForm .mailboxCheck:checked');
            let channelsArr = [];
            channels.each(function() {
                let tr = $(this).closest('tr');
                channelsArr.push({
                    id: $(this).val().trim(),
                    teamId: tr.find('.teamId').val(),
                    name: tr.find('td:nth-child(2)').html(),
                    teamName: tr.find('td:nth-child(3)').html(),
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreChannels') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "channels=" + JSON.stringify(channelsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000);
                    $('#restoreChannelsModal').modal('hide');
                    $('#historyTable').DataTable().ajax.reload();
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html(
                            "{{ __('variables.errors.restore_session_expired') }}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(error) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    let errMessage = "   ERROR   ";
                    if (error.responseText) {
                        let err = JSON.parse(error.responseText);
                        if (err.message)
                            errMessage = "ERROR  : " + err.message;
                    }
                    $(".danger-oper .danger-msg").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");

                    }, 8000);
                }
            });
            return false;
        }
        //---------------------------------------------------//
        function restoreChannelsPosts() {
            event.preventDefault()
            var data = $('#restoreChannelsPostsForm').serialize();
            let channels = $('#restoreChannelsPostsForm .mailboxCheck:checked');
            let channelsArr = [];
            channels.each(function() {
                let tr = $(this).closest('tr');
                channelsArr.push({
                    id: $(this).val().trim(),
                    teamId: tr.find('.teamId').val(),
                    name: tr.find('td:nth-child(2)').html(),
                    teamName: tr.find('td:nth-child(3)').html(),
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreChannelsPosts') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "channels=" + JSON.stringify(channelsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000);
                    $('#restoreChannelsPostsModal').modal('hide');
                    $('#historyTable').DataTable().ajax.reload();
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html(
                            "{{ __('variables.errors.restore_session_expired') }}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(error) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    let errMessage = "   ERROR   ";
                    if (error.responseText) {
                        let err = JSON.parse(error.responseText);
                        if (err.message)
                            errMessage = "ERROR  : " + err.message;
                    }
                    $(".danger-oper .danger-msg").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");

                    }, 8000);
                }
            });
            return false;
        }
        //---------------------------------------------------//
        function restoreChannelsFiles() {
            event.preventDefault()
            var data = $('#restoreChannelsFilesForm').serialize();
            //----------------------------//
            if (!$('#restoreChannelsFilesForm').find("[name='restoreChangedItems']").prop("checked") && !$(
                    '#restoreChannelsFilesForm').find("[name='restoreMissingItems']").prop("checked")) {
                $(".danger-oper .danger-msg").html(
                    "{{ __('variables.errors.restore_required_options') }}");
                $(".danger-oper").css("display", "block");
                setTimeout(function() {
                    $(".danger-oper").css("display", "none");
                }, 3000);
                return false;
            }
            //----------------------------//
            data += "&restoreMissingItems=" + $("#restoreChannelsFilesForm [name='restoreMissingItems']")[0].checked;
            data += "&restoreChangedItems=" + $("#restoreChannelsFilesForm [name='restoreChangedItems']")[0].checked;
            let channels = $('#restoreChannelsFilesForm .mailboxCheck:checked');
            let channelsArr = [];
            channels.each(function() {
                let tr = $(this).closest('tr');
                channelsArr.push({
                    id: $(this).val().trim(),
                    teamId: tr.find('.teamId').val(),
                    name: tr.find('td:nth-child(2)').html(),
                    teamName: tr.find('td:nth-child(3)').html(),
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreChannelsFiles') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "channels=" + JSON.stringify(channelsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000);
                    $('#restoreChannelsFilesModal').modal('hide');
                    $('#historyTable').DataTable().ajax.reload();
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html(
                            "{{ __('variables.errors.restore_session_expired') }}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(error) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    let errMessage = "   ERROR   ";
                    if (error.responseText) {
                        let err = JSON.parse(error.responseText);
                        if (err.message)
                            errMessage = "ERROR  : " + err.message;
                    }
                    $(".danger-oper .danger-msg").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");

                    }, 8000);
                }
            });
            return false;
        }
        //---------------------------------------------------//
        function restoreChannelsTabs() {
            event.preventDefault()
            var data = $('#restoreChannelsTabsForm').serialize();
            data += "&restoreMissingItems=" + $("#restoreChannelsTabsForm [name='restoreMissingItems']")[0].checked;
            data += "&restoreChangedItems=" + $("#restoreChannelsTabsForm [name='restoreChangedItems']")[0].checked;
            let channels = $('#restoreChannelsTabsForm .mailboxCheck:checked');
            let channelsArr = [];
            channels.each(function() {
                let tr = $(this).closest('tr');
                channelsArr.push({
                    id: $(this).val().trim(),
                    teamId: tr.find('.teamId').val(),
                    name: tr.find('td:nth-child(2)').html(),
                    teamName: tr.find('td:nth-child(3)').html(),
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreChannelsTabs') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "channels=" + JSON.stringify(channelsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000);
                    $('#restoreChannelsTabsModal').modal('hide');
                    $('#historyTable').DataTable().ajax.reload();
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html(
                            "{{ __('variables.errors.restore_session_expired') }}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(error) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    let errMessage = "   ERROR   ";
                    if (error.responseText) {
                        let err = JSON.parse(error.responseText);
                        if (err.message)
                            errMessage = "ERROR  : " + err.message;
                    }
                    $(".danger-oper .danger-msg").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");

                    }, 8000);
                }
            });
            return false;
        }
        //---------------------------------------------------//
        function exportChannelsFiles() {
            event.preventDefault()
            let data = $('#exportChannelsFilesForm').serialize();
            let channels = $('#exportChannelsFilesForm .mailboxCheck:checked');
            let channelsArr = [];
            channels.each(function() {
                let tr = $(this).closest('tr');
                channelsArr.push({
                    id: $(this).val().trim(),
                    name: tr.find('td:nth-child(2)').html(),
                    teamId: tr.find('.teamId').val(),
                    teamName: tr.find('td:nth-child(3)').html(),
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportChannelsFiles') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + "&_token={{ csrf_token() }}&" +
                    "channels=" + JSON.stringify(channelsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000);
                    $('#exportChannelsFilesModal').modal('hide');
                    $('#historyTable').DataTable().ajax.reload();
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html(
                            "{{ __('variables.errors.restore_session_expired') }}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(error) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    let errMessage = "   ERROR   ";
                    if (error.responseText) {
                        let err = JSON.parse(error.responseText);
                        if (err.message)
                            errMessage = "ERROR  : " + err.message;
                    }
                    $(".danger-oper .danger-msg").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");

                    }, 8000);
                }
            });
            return false;
        }
        //---------------------------------------------------//
        function exportChannelsPosts() {
            event.preventDefault()
            let channels = $('#exportChannelsPostsForm .mailboxCheck:checked');
            let data = $('#exportChannelsPostsForm').serialize();
            let channelsArr = [];
            channels.each(function() {
                let tr = $(this).closest('tr');
                channelsArr.push({
                    id: $(this).val().trim(),
                    name: tr.find('td:nth-child(2)').html(),
                    teamId: tr.find('.teamId').val(),
                    teamName: tr.find('td:nth-child(3)').html(),
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportChannelsPosts') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "channels=" + JSON.stringify(channelsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000);
                    $('#exportChannelsPostsModal').modal('hide');
                    $('#historyTable').DataTable().ajax.reload();
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html(
                            "{{ __('variables.errors.restore_session_expired') }}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(error) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    let errMessage = "   ERROR   ";
                    if (error.responseText) {
                        let err = JSON.parse(error.responseText);
                        if (err.message)
                            errMessage = "ERROR  : " + err.message;
                    }
                    $(".danger-oper .danger-msg").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");

                    }, 8000);
                }
            });
            return false;
        }
        //---------------------------------------------------//
        function restoreFiles() {
            event.preventDefault()
            let data = $('#restoreFilesForm').serialize();
            data += "&restoreMissingItems=" + $("#restoreFilesForm [name='restoreMissingItems']")[0].checked;
            data += "&restoreChangedItems=" + $("#restoreFilesForm [name='restoreChangedItems']")[0].checked;
            let files = $('#restoreFilesForm .mailboxCheck:checked');
            let filesArr = [];
            //-----------------------------------------//
            files.each(function() {
                let tr = $(this).closest('tr');
                if (filesArr.filter(e => e.teamId === $(this).attr("data-teamId") && e.channelId === $(this).attr(
                        "data-channelId")).length == 0) {
                    let teamFiles = $('#restoreFilesForm .mailboxCheck:checked[data-teamId="' + $(this).attr(
                        "data-teamId") + '"][data-channelId="' + $(this).attr("data-channelId") + '"]');
                    let teamFilesArr = [];
                    teamFiles.each(function() {
                        tr = $(this).closest('tr');
                        teamFilesArr.push({
                            "id": $(this).val(),
                            "teamName": tr.find('td:nth-child(4)').html(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "channelName": tr.find('td:nth-child(3)').html(),
                            "channelId": $(this).attr("data-channelid"),
                            "teamId": $(this).attr("data-teamId"),
                        })
                    });
                    filesArr.push({
                        "teamId": $(this).attr("data-teamId"),
                        "teamName": tr.find('td:nth-child(4)').html(),
                        "channelName": tr.find('td:nth-child(3)').html(),
                        "channelId": $(this).attr("data-channelid"),
                        "items": teamFilesArr
                    });
                }
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreTeamsFiles') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "files=" + JSON.stringify(filesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000);
                    $('#restoreFilesModal').modal('hide');
                    $('#historyTable').DataTable().ajax.reload();
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html(
                            "{{ __('variables.errors.restore_session_expired') }}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(error) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    let errMessage = "   ERROR   ";
                    if (error.responseText) {
                        let err = JSON.parse(error.responseText);
                        if (err.message)
                            errMessage = "ERROR  : " + err.message;
                    }
                    $(".danger-oper .danger-msg").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");

                    }, 8000);
                }
            });
            return false;
        }
        //---------------------------------------------------//
        function exportFiles() {
            event.preventDefault()
            let data = $('#exportFilesForm').serialize();
            let files = $('#exportFilesForm .mailboxCheck:checked');
            let filesArr = [];
            //-----------------------------------------//
            files.each(function() {
                let tr = $(this).closest('tr');
                if (filesArr.filter(e => e.teamId === $(this).attr("data-teamId") && e.channelId === $(this).attr(
                        "data-channelId")).length == 0) {
                    let teamFiles = $('#exportFilesForm .mailboxCheck:checked[data-teamId="' + $(this).attr(
                        "data-teamId") + '"][data-channelId="' + $(this).attr("data-channelId") + '"]');
                    let teamFilesArr = [];
                    teamFiles.each(function() {
                        tr = $(this).closest('tr');
                        teamFilesArr.push({
                            "id": $(this).val(),
                            "teamName": tr.find('td:nth-child(4)').html(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "channelName": tr.find('td:nth-child(3)').html(),
                            "channelId": $(this).attr("data-channelid"),
                            "teamId": $(this).attr("data-teamId"),
                        })
                    });
                    filesArr.push({
                        "teamId": $(this).attr("data-teamId"),
                        "teamName": tr.find('td:nth-child(4)').html(),
                        "channelName": tr.find('td:nth-child(3)').html(),
                        "channelId": $(this).attr("data-channelid"),
                        "items": teamFilesArr
                    });
                }
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportTeamsFiles') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "files=" + JSON.stringify(filesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000);
                    $('#exportFilesModal').modal('hide');
                    $('#historyTable').DataTable().ajax.reload();
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html(
                            "{{ __('variables.errors.restore_session_expired') }}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(error) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    let errMessage = "   ERROR   ";
                    if (error.responseText) {
                        let err = JSON.parse(error.responseText);
                        if (err.message)
                            errMessage = "ERROR  : " + err.message;
                    }
                    $(".danger-oper .danger-msg").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");

                    }, 8000);
                }
            });
            return false;
        }
        //---------------------------------------------------//
        function exportPosts() {
            event.preventDefault()
            let data = $('#exportPostsForm').serialize();
            let posts = $('#exportPostsForm .mailboxCheck:checked');
            let postsArr = [];
            //-----------------------------------------//
            let teamsArr = [];
            posts.each(function() {
                let tr = $(this).closest('tr');
                if (teamsArr.filter(e => e.teamId === $(this).attr("data-teamId")).length == 0) {
                    let teamPosts = $('#exportPostsForm .mailboxCheck:checked[data-teamId="' + $(this).attr(
                        "data-teamId") + '"]');
                    let teamPostsArr = [];
                    teamPosts.each(function() {
                        teamPostsArr.push({
                            "id": $(this).val(),
                            "teamName": tr.find('td:nth-child(4)').html(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "channelName": tr.find('td:nth-child(3)').html(),
                        })
                    });
                    postsArr.push({
                        "teamId": $(this).attr("data-teamId"),
                        "teamName": tr.find('td:nth-child(4)').html(),
                        "channelName": tr.find('td:nth-child(3)').html(),
                        "channelId": $(this).attr("data-channelid"),
                        "items": teamPostsArr
                    });
                }
            });
            //--------------------------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportTeamsPosts') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "posts=" + JSON.stringify(postsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000);
                    $('#exportPostsModal').modal('hide');
                    $('#historyTable').DataTable().ajax.reload();
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html(
                            "{{ __('variables.errors.restore_session_expired') }}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(error) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    let errMessage = "   ERROR   ";
                    if (error.responseText) {
                        let err = JSON.parse(error.responseText);
                        if (err.message)
                            errMessage = "ERROR  : " + err.message;
                    }
                    $(".danger-oper .danger-msg").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");

                    }, 8000);
                }
            });
            return false;
        }
        //---------------------------------------------------//
        function restoreTabs() {
            event.preventDefault()
            let tabs = $('#restoreTabsForm .mailboxCheck:checked');
            let data = $('#restoreTabsForm').serialize();
            data += "&restoreMissingItems=" + $("#restoreTabsForm [name='restoreMissingItems']")[0].checked;
            data += "&restoreChangedItems=" + $("#restoreTabsForm [name='restoreChangedItems']")[0].checked;
            let tabsArr = [];
            tabs.each(function() {
                let tr = $(this).closest('tr');
                tabsArr.push({
                    id: $(this).val().trim(),
                    teamId: tr.find('.teamId').val(),
                    channelId: tr.find('.channelId').val(),
                    name: tr.find('td:nth-child(2)').html(),
                    channelName: tr.find('td:nth-child(3)').html(),
                    teamName: tr.find('td:nth-child(4)').html(),
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreTeamsTabs') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "tabs=" + JSON.stringify(tabsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000);
                    $('#restoreTabsModal').modal('hide');
                    $('#historyTable').DataTable().ajax.reload();
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html(
                            "{{ __('variables.errors.restore_session_expired') }}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(error) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    let errMessage = "   ERROR   ";
                    if (error.responseText) {
                        let err = JSON.parse(error.responseText);
                        if (err.message)
                            errMessage = "ERROR  : " + err.message;
                    }
                    $(".danger-oper .danger-msg").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");

                    }, 8000);
                }
            });
            return false;
        }
        //----------------------------------------------------//
    </script>
@endsection
