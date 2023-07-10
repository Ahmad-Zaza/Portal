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
        $isRestore = $isRestore !== 0 ? strpos($data['history']['sub_type'], 'Copy') : $isRestore;
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
        <!-- Create new repository button -->
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
            @if (optional($data['restoreOptions'])->restorePermissions || optional($data['restoreOptions'])->changedItems)
                <div class="col">
                    <div class="col-lg-11" style="padding-left: 0px;">
                        <h5 class="txt-blue">Restore Options</h5>
                    </div>
                    <div class="col-lg-12 newJobRow">
                        <div class="rowBorderRight"></div>
                        <div class="rowBorderBottom"></div>
                        <div class="rowBorderleft"></div>
                        <div class="rowBorderUp"></div>
                        @if (optional($data['restoreOptions'])->changedItems)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore Changed Items:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->changedItems }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->deletedItems)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore Deleted Items:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->deletedItems }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->sendSharedLinksNotification)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Send Shared Links Notification:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->sendSharedLinksNotification }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->restorePermissions)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore Permissions:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->restorePermissions }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
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
                        @if (optional($data['restoreOptions'])->action)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Documents Action:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->action }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->version)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Documents Version:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->version }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->restoreVersionAction)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Last Documents Version:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->restoreVersionAction }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->skipUnresolved)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Skip Unresolved Items:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->skipUnresolved }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->toOnedrive)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore to Onedrive:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    @php $to = json_decode($data['restoreOptions']->toOnedrive) @endphp
                                    <span title="{{ $to->url }}">{{ $to->name }}</span>
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->toFolder)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore to Folder:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->toFolder }}
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
            $isRestore = $isRestore !== 0 ? strpos($data['history']['sub_type'], 'Copy') : $isRestore;
            $showButton = ($isRestore === 0 && $status != 'In Progress' && $role->hasPermissionTo('onedrive_export_again')) || (!$isRestore && in_array($status, ['Canceled', 'Expired', 'Failed']) && $role->hasPermissionTo('onedrive_restore_again'));
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
    <div id="restoreFolder" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg modal-mt-10 mt-10v" style="width: 1000px!important">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-100" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="modal-title per-req ml-2p">Restore Selected Folders</h4>
                        </div>
                    </div>
                    <form id="restoreFolderForm" class="mb-0" onsubmit="restoreFolder(event)">
                        <div class="custom-left-col">
                            <input type="hidden" class="restoreAction" name="restoreAction" value="" />
                            <input type="hidden" class="restoreType" name="restoreType" />
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
                                    <div class="col-lg-12 customBorder pt-1 pb-1">
                                        <div class="flex pt-2 pb-2">
                                            <label class="mr-4 m-0 nowrap">Documents Version:</label>
                                            <div class="radioDiv">
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentVersion"
                                                            class="documentVersion" id="documentVersion" value="Last"
                                                            checked="">Last
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentVersion"
                                                            class="documentVersion" id="documentVersion"
                                                            value="All">All
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="hide restoreAnother_cont">
                                            <div class="flex pb-2">
                                                <label class="mr-4 m-0 nowrap">Documents Last Version Action:</label>
                                            </div>
                                            <div class="radioDiv pb-10">
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="restoreVersionAction"
                                                            class="restoreVersionAction" value="overwrite"
                                                            checked="">Overwrite
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="restoreVersionAction"
                                                            class="restoreVersionAction" value="merge">Merge
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="hide restoreAnother_cont">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="relative allWidth mb-2">
                                                        <label style="padding-top: 5px;left: 0px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="restorePermissions" type="checkbox"
                                                                class="form-check-input">
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <span style="margin-left: 25px;">Restore Permissions</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="relative allWidth mb-2">
                                                        <label style="padding-top: 5px;left: 0px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="sendSharedLinksNotification" type="checkbox"
                                                                class="form-check-input">
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <span style="margin-left: 25px;">Send Shared Links
                                                            Notifications</span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="pr-0 pl-3">
                                                        <div class="relative allWidth mt-2 ml-20p">
                                                            <label>Restore the following items:</label>
                                                        </div>
                                                    </div>
                                                    <div class="w-100"></div>
                                                    <div class="col">
                                                        <div class="relative allWidth mb-2 ml-16">
                                                            <label style="padding-top: 5px;left: 0px;"
                                                                class="checkbox-container">&nbsp;
                                                                <input name="changedItems" type="checkbox"
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
                                                                <input name="deletedItems" type="checkbox"
                                                                    class="form-check-input">
                                                                <span
                                                                    style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                    class="check-mark"></span>
                                                            </label>
                                                            <span style="margin-left: 25px;">Deleted Items</span>
                                                        </div>
                                                    </div>
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
                                    <h5 class="txt-blue mt-0">Onedrive Folders</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder modal-h-190p h-253p">
                                        <div class="allWidth">
                                            <table id="foldersResultsTable"
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
                                                        <th>Folder</th>
                                                        <th>Onedrive</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> folders selected
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row hide restoreAnother_cont">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Target Onedrive</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div>
                                            <div class="mb-10">Specify Onedrive to Restore to:</div>
                                            <div class="mb-10 allWidth">
                                                <select style="width:100%;" required
                                                    class="js-data-example-ajax users required">
                                                    <option value="">Select User</option>
                                                </select>
                                            </div>
                                            <div class="mb-10 allWidth relative">
                                                <div class="onedrive-spinner hide"></div>
                                                <select
                                                    class="allWidth form-control form_input user_onedrive required custom-form-control"
                                                    required id="onedrive" name="onedrive">
                                                </select>
                                            </div>
                                            <div class="allWidth">
                                                <input type="text" required
                                                    class="required form-control form_input custom-form-control"
                                                    id="folder" placeholder="Folder" name="folder" required
                                                    autocomplete="off" />
                                            </div>
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
    <div id="restoreOneDriveModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg mt-10v" style="width: 1000px!important">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-100" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="modal-title per-req ml-2p">Restore Selected OneDrives
                            </h4>
                        </div>
                    </div>
                    <form id="restoreOnedriveForm" class="mb-0" onsubmit="restoreOneDrive(event)">
                        <div class="custom-left-col">
                            <input type="hidden" class="showVersions" name="showVersions" />
                            <input type="hidden" class="showDeleted" name="showDeleted" />
                            <input type="hidden" class="jobTime" name="jobTime" />
                            <input type="hidden" class="jobId" name="jobId" />
                            <input type="hidden" class="jobType" name="jobType" />
                            <input type="hidden" name="restoreAction" class="restoreAction" />
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
                                    <div class="col-lg-12 customBorder pb-3">
                                        <div class="flex pt-3 pb-3">
                                            <label class="mr-4 m-0 nowrap">Documents Version:</label>
                                            <div class="radioDiv">
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentVersion"
                                                            class="documentVersion" id="documentVersion" value="Last"
                                                            checked="">Last
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentVersion"
                                                            class="documentVersion" id="documentVersion"
                                                            value="All">All
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="relative allWidth mb-2">
                                                        <label style="padding-top: 5px;left: 0px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="skipUnresolved" type="checkbox"
                                                                class="form-check-input">
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <span style="margin-left: 25px;">Skip Unresolved</span>
                                                    </div>
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
                                    <h5 class="txt-blue mt-0">Onedrives</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-290p">
                                        <div class="allWidth onedrivesResultsTable">
                                            <table id="oneDriveOriginalTable"
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
                                                        <th>OneDrive</th>
                                                        <th>Url</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> onedrives selected <span
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
    <div id="restoreOneDriveCopyModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg" style="width: 1000px!important">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-100" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="modal-title per-req ml-2p">Copy Selected Onedrives
                            </h4>
                        </div>
                    </div>
                    <form id="restoreOneDriveCopyForm" class="mb-0" onsubmit="restoreOneDriveCopy(event)">
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
                                    <h5 class="txt-blue mt-0">Onedrives</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-250p">
                                        <div class="allWidth onedrivesResultsTable">
                                            <table id="oneDriveCopyTable"
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
                                                        <th>OneDrive</th>
                                                        <th>Url</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> onedrives selected <span
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
                                    <h5 class="txt-blue mt-0">Target Onedrive</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div>
                                            <div class="mb-10">Specify Onedrive to Restore to:</div>
                                            <div class="mb-10 allWidth">
                                                <select required style="width:100%" class="js-data-example-ajax users">
                                                    <option value="">Select User</option>
                                                </select>
                                            </div>
                                            <div class="mb-10 allWidth relative">
                                                <div class="onedrive-spinner hide"></div>
                                                <select style="width:100%"
                                                    class="form-control form_input user_onedrive custom-form-control"
                                                    required id="onedrive" name="onedrive">
                                                </select>
                                            </div>
                                            <div class="allWidth">
                                                <input type="text" required
                                                    class="form-control form_input custom-form-control font-size"
                                                    id="folder" placeholder="Folder" name="folder" required
                                                    autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Options</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div class="flex pb-2">
                                            <label class="mr-4 m-0 nowrap">Documents Version:</label>
                                            <div class="radioDiv">
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentVersion"
                                                            class="documentVersion" id="documentVersion" value="Last"
                                                            checked="">Last
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentVersion"
                                                            class="documentVersion" id="documentVersion"
                                                            value="All">All
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex pb-2">
                                            <label class="mr-4 m-0 nowrap">Documents Last Version Action:</label>
                                        </div>
                                        <div class="radioDiv pb-10">
                                            <div class="radio m-0">
                                                <label>
                                                    <input type="radio" name="restoreAction" class="restoreAction"
                                                        value="overwrite" checked="">Overwrite
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="radio m-0">
                                                <label>
                                                    <input type="radio" name="restoreAction" class="restoreAction"
                                                        value="merge">Merge
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="relative allWidth mb-2">
                                                        <label style="padding-top: 5px;left: 0px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="restorePermissions" type="checkbox"
                                                                class="form-check-input">
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <span style="margin-left: 25px;">Restore Permissions</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="relative allWidth mb-2">
                                                        <label style="padding-top: 5px;left: 0px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="sendSharedLinksNotification" type="checkbox"
                                                                class="form-check-input">
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <span style="margin-left: 25px;">Send Shared Links
                                                            Notifications</span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="pr-0 pl-3">
                                                        <div class="relative allWidth mt-2 ml-20p">
                                                            <label>Restore the following items:</label>
                                                        </div>
                                                    </div>
                                                    <div class="w-100"></div>
                                                    <div class="col">
                                                        <div class="relative allWidth ml-16">
                                                            <label style="padding-top: 5px;left: 0px;"
                                                                class="checkbox-container">&nbsp;
                                                                <input name="changedItems" type="checkbox"
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
                                                                <input name="deletedItems" type="checkbox"
                                                                    class="form-check-input">
                                                                <span
                                                                    style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                    class="check-mark"></span>
                                                            </label>
                                                            <span style="margin-left: 25px;">Deleted Items</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
    <div id="exportOnedriveModal" class="modal modal-center" role="dialog">
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
                            <h4 class="modal-title per-req ml-2p">Export Onedrives To .Zip
                            </h4>
                        </div>
                    </div>
                    <form id="exportOnedriveForm" class="mb-0" onsubmit="exportOnedrive(event)">
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
                                <h5 class="txt-blue mt-0">Onedrives</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder">
                                    <div class="allWidth onedrivesResultsTable">
                                        <table id="exportOneDriveTable"
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
                                                    <th>OneDrive</th>
                                                    <th>Url</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">
                                                        <span class="boxesCount"></span> onedrives selected <span
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
    <div id="exportOnedriveFoldersModal" class="modal modal-center" role="dialog">
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
                            <h4 class="modal-title per-req">Export Onedrive Folders To .Zip
                            </h4>
                        </div>
                    </div>
                    <form id="exportOnedriveFoldersForm" class="mb-0" onsubmit="exportOnedriveFolders(event)">
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
                                <h5 class="txt-blue mt-0">Onedrive Folders</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder">
                                    <div class="allWidth">
                                        <table id="exportFoldersResultsTable"
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
                                                    <th>Folder</th>
                                                    <th>Onedrive</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">
                                                        <span class="boxesCount"></span> folders selected
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
    <div id="exportOnedriveDocumentsModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg" >
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
                            <h4 class="modal-title per-req">Export Onedrive Documents To .Zip
                            </h4>
                        </div>
                    </div>
                    <form id="exportOnedriveDocumentsForm" class="mb-0" onsubmit="exportOnedriveDocuments(event)">
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
                                <h5 class="txt-blue mt-0">Onedrive Documents</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder">
                                    <div class="allWidth">
                                        <table id="exportDocsResultsTable"
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
                                                    <th>Folder</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">
                                                        <span class="boxesCount"></span> documents selected
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
    <div id="restoreItem" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg mt-10v" style="width: 1000px!important">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-100" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="modal-title per-req ml-2p">Restore Selected Documents</h4>
                        </div>
                    </div>
                    <form id="restoreDocumentForm" class="mb-0" onsubmit="restoreItem(event)">
                        <div class="custom-left-col">
                            <input type="hidden" class="restoreType" name="restoreType" />
                            <input type="hidden" class="restoreAction" name="restoreAction" />
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
                                    <div class="col-lg-12 customBorder pb-1 pt-1">
                                        <div class="flex pt-2 pb-2">
                                            <label class="mr-4 m-0 nowrap">Documents Version:</label>
                                            <div class="radioDiv">
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentVersion"
                                                            class="documentVersion" id="documentVersion" value="Last"
                                                            checked="">Last
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentVersion"
                                                            class="documentVersion" id="documentVersion"
                                                            value="All">All
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="hide restoreAnother_cont">
                                            <div class="flex pb-2">
                                                <label class="mr-4 m-0 nowrap">Documents Last Version Action:</label>
                                            </div>
                                            <div class="radioDiv">
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="restoreVersionAction"
                                                            class="restoreVersionAction" value="overwrite"
                                                            checked="">Overwrite
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="restoreVersionAction"
                                                            class="restoreVersionAction" value="merge">Merge
                                                        <span class="checkmark"></span>
                                                    </label>
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
                                    <h5 class="txt-blue mt-0">Onedrive Documents</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-253p">
                                        <div class="allWidth">
                                            <table id="docsResultsTable"
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
                                                        <th>Folder</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> documents selected
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row hide restoreAnother_cont">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Target Onedrive</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div>
                                            <div class="mb-10">Specify Onedrive to Restore to:</div>
                                            <div class="mb-10 allWidth">
                                                <select style="width:100%;" required
                                                    class="js-data-example-ajax users required">
                                                    <option value="">Select User</option>
                                                </select>
                                            </div>
                                            <div class="mb-10 allWidth relative">
                                                <div class="onedrive-spinner hide"></div>
                                                <select class="allWidth form-control form_input user_onedrive required"
                                                    required id="onedrive" name="onedrive">
                                                </select>
                                            </div>
                                            <div class="allWidth">
                                                <input type="text" required class="required form-control form_input"
                                                    id="folder" placeholder="Folder" name="folder" required
                                                    autocomplete="off" />
                                            </div>
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
    <div id="restoreItemCopy" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg modal-width mt-10v">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-100 mb-15">
                        <div class="input-form-70">
                            <h4 class="modal-title per-req ml-2p">Restore Selected Documents</h4>
                        </div>
                    </div>
                    <form id="restoreDocumentCopyForm" class="mb-0" onsubmit="restoreItemCopy(event)">
                        <input type="hidden" class="restoreType" name="restoreType" />
                        <input type="hidden" class="restoreAction" name="restoreAction" />
                        <input type="hidden" class="showVersions" name="showVersions" />
                        <input type="hidden" class="showDeleted" name="showDeleted" />
                        <input type="hidden" class="jobTime" name="jobTime" />
                        <input type="hidden" class="jobId" name="jobId" />
                        <input type="hidden" class="jobType" name="jobType" />
                        <div class="custom-left-col">
                            <input type="hidden" class="restoreType" name="restoreType" />
                            <input type="hidden" class="restoreAction" name="restoreAction" />
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
                                    <h5 class="txt-blue mt-0">Onedrive Documents</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-150p">
                                        <div class="allWidth">
                                            <table id="docsCopyResultsTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <th>
                                                            <label
                                                                class="checkbox-top-left checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>File</th>
                                                        <th>Folder</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> documents selected
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
                                    <div class="col-lg-12 customBorder h-150p pb-3 pt-3">
                                        <div class="flex pb-2">
                                            <label class="mr-4 m-0 nowrap">Documents Version:</label>
                                            <div class="radioDiv">
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentVersion"
                                                            class="documentVersion" id="documentVersion"
                                                            value="Last" checked="">Last
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentVersion"
                                                            class="documentVersion" id="documentVersion"
                                                            value="All">All
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="hide restoreAnother_cont">
                                            <div class="flex pb-2 ">
                                                <label class="mr-4 m-0 nowrap">Documents Last Version Action:</label>
                                            </div>
                                            <div class="radioDiv">
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="restoreVersionAction"
                                                            class="restoreVersionAction" value="overwrite"
                                                            checked="">Overwrite
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="restoreVersionAction"
                                                            class="restoreVersionAction" value="merge">Merge
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row hide restoreAnother_cont">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Target Onedrive</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div>
                                            <div class="mb-10">Specify Onedrive to Restore to:</div>
                                            <div class="mb-10 allWidth">
                                                <select style="width:100%;" required
                                                    class="js-data-example-ajax users required">
                                                    <option value="">Select User</option>
                                                </select>
                                            </div>
                                            <div class="mb-10 allWidth relative">
                                                <div class="onedrive-spinner hide"></div>
                                                <select
                                                    class="allWidth form-control form_input user_onedrive required custom-form-control font-size"
                                                    required id="onedrive" name="onedrive">
                                                </select>
                                            </div>
                                            <div class="allWidth">
                                                <input type="text" required
                                                    class="required form-control form_input custom-form-control font-size"
                                                    id="folder" placeholder="Folder" name="folder" required
                                                    autocomplete="off" />
                                            </div>
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
                            <h4 class="modal-title per-req ml-2p">Search
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
                            <h4 class="modal-title per-req">Search
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
                    "dataType": "json",
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
            let users = {!! json_encode($data['users']) !!};
            $('.users').html("");
            $('.user_onedrive').html("");
            if (users.length > 0) {
                let result = [];
                //--------------------//
                result.push({
                    id: '',
                    text: 'Select User',
                });
                users.forEach((e) => {
                    result.push({
                        id: e.id,
                        name: e.displayName,
                        text: e.displayName,
                    });
                });
                //--------------------//
                $(".users").select2({
                    data: result,
                });
                //--------------------//
                $('.users').change(function() {
                    $('.onedrive-spinner').removeClass('hide');
                    if ($(this).val())
                        getUserOnedrives($(this).val());
                });
                //--------------------//
            }
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
                            if ("{{ $data['history']->type }}" == "document") {
                                let resultsArr = JSON.parse(data.item_id);
                                let str = '<ul class="p-0 m-0">';
                                resultsArr.forEach((e) => {
                                    str += '<li>' + e.name + '</li>';
                                });
                                str += '</ul>';
                                return str;
                            } else if ("{{ $data['history']->type }}" == 'folder') {
                                let resultsArr = JSON.parse(data.item_id);
                                let str = '<ul class="p-0 m-0">';
                                resultsArr.forEach((e) => {
                                    str += '<li>' + e.folder + '</li>';
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
                            statusClass = (status == 'Stop') ? 'text-danger' :
                                statusClass;
                            statusClass = (status == 'Success') ? 'text-success' :
                                statusClass;
                            statusClass = (status == 'Failed') ? 'text-danger' :
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
                            var isRestore = (temp.match(/Restore/g) || temp.match(/Copy/g) || [])
                                .length;
                            if (data.result) {
                                return getOnedriveResult(data.result);
                            } else if (isRestore == 0 && data.status == 'Success' &&
                                "{{ $data['history']['status'] }}" != "Expired") {
                                @if ($role->hasPermissionTo('onedrive_download_exported_files'))
                                    return '<div><img data-id="' + data.id +
                                        '" class="tableIcone hand downloadExportedFile" style="width: 13px; margin-right:0;" src="/svg/download\.svg " title="Download"></div>';
                                @endif
                            }
                            if (data.error_response)
                                return '<div class="ellipsisContent" title="' + data
                                    .error_response + '">' + data.error_response + '<div>';
                        }
                    }, {
                        "data": null,
                        "class": "text-center",
                        render: function(data) {
                            var temp = "{{ $data['history']['sub_type'] }}";
                            var isRestore = (temp.match(/Restore/g) || temp.match(/Copy/g) || [])
                                .length;
                            if (data.status == 'Failed') {
                                if (isRestore === 0) {
                                    @if ($role->hasPermissionTo('onedrive_export_again'))
                                        return '<div><img data-id="' + data.id +
                                            '" class="tableIcone hand restoreFailedAgain" style="width: 13px; margin-right:0;" src="/svg/restore\.svg " title="Retry"></div>';
                                    @endif
                                } else {
                                    @if ($role->hasPermissionTo('onedrive_restore_again'))
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
                        let isRestore = (subType.match(/Restore/g) || subType.match(/Copy/g) ||
                            []).length;
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
                let isRestore = (subType.match(/Restore/g) || subType.match(/Copy/g) || []).length;
                if (isRestore > 0)
                    restoreAgainModal('all', "{{ $data['history']['id'] }}",
                        "{{ $data['history']['type'] }}", subType);
                else
                    exportAgainModal('all', "{{ $data['history']['id'] }}",
                        "{{ $data['history']['type'] }}", subType);
                //----------------------------------------//
            });
            //---------------------------------------//
            $('#oneDriveOriginalTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
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
                                '" class="form-check-input mailboxCheck" onedriveId="' + data
                                .onedriveId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name",
                        "width": "30%"
                    },
                    {
                        "data": "url",
                        "width": "70%"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "220px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $('#oneDriveCopyTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
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
                        "data": "url",
                        "width": "70%"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "170px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $('#exportOneDriveTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
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
                                '" class="form-check-input mailboxCheck" onedriveId="' + data
                                .onedriveId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name",
                        "width": "30%"
                    },
                    {
                        "data": "url",
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

            $('#foldersResultsTable').DataTable({
                "data": [],
                "order": [
                    [2, 'asc']
                ],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" onedriveId="' + data
                                .onedriveId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "onedriveName"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": function() {
                    let parent = $('#foldersResultsTable').closest(".col-lg-12.customBorder");
                    if (parent.hasClass("h-190p"))
                        return "120px";
                    return "180px";
                },
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $('#docsResultsTable').DataTable({
                "data": [],
                "order": [
                    [2, 'asc']
                ],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" onedriveId="' + data
                                .onedriveId + '">' +
                                '<input class="onedriveId" value="' + data.onedriveId + '">' +
                                '<input class="onedriveTitle" value="' + data.onedriveTitle + '">' +
                                '<input class="folderTitle" value="' + data.folderTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "folderTitle"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "183px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            $('#docsCopyResultsTable').DataTable({
                "data": [],
                "order": [
                    [2, 'asc']
                ],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" onedriveId="' + data
                                .onedriveId + '">' +
                                '<input class="onedriveId" value="' + data.onedriveId + '">' +
                                '<input class="onedriveTitle" value="' + data.onedriveTitle + '">' +
                                '<input class="folderTitle" value="' + data.folderTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "folderTitle"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "80px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            $('#exportDocsResultsTable').DataTable({
                "data": [],
                "order": [
                    [2, 'asc']
                ],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" onedriveId="' + data
                                .onedriveId + '">' +
                                '<input class="onedriveId" value="' + data.onedriveId + '">' +
                                '<input class="onedriveTitle" value="' + data.onedriveTitle + '">' +
                                '<input class="folderTitle" value="' + data.folderTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "folderTitle"
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

            $('#exportFoldersResultsTable').DataTable({
                "data": [],
                "order": [
                    [2, 'asc']
                ],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" onedriveId="' + data
                                .onedriveId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "onedriveName"
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
        var reset_count = 0;
        $.fn.dataTable.ext.search.push(

            function(settings, data, dataIndex) {
                res = true;
                reset_count++;
                let DurationFrom = $("#durationFrom").val();
                let DurationTo = $("#durationTo").val();
                if (DurationTo == "00:00:00")
                    DurationTo = "24:00:00";
                let conditionsArray = [];
                console.log("duration from => ", DurationFrom, " ,duration to => ", DurationTo, " ,data[1] => ", data[
                    1], " ,data[2] => ", data[2]);
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

        function getUserOnedrives(value) {
            $('.user_onedrive').html("");
            $.ajax({
                type: "POST",
                url: "{{ url('getUserOnedrives') }}",
                data: "_token={{ csrf_token() }}&" +
                    "userId=" + value,
                success: function(data) {
                    if (data.length > 0) {
                        $('.user_onedrive').html("");
                        data.forEach((e) => {
                            $('.user_onedrive').append(new Option(e.url, e.id));
                        });
                    }
                    $('.onedrive-spinner').addClass('hide');
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
                    $('.onedrive-spinner').addClass('hide');
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
        //---------------------------------------//
        function getOnedriveResult(result) {
            let resultArr = JSON.parse(result);
            let str = '<div class="col-lg-12 sess-info text-left" style="max-width:400px">';
            str += '<span class="col-lg-6 sess-title">Restore Issues:</span>';
            str += '<span class="col-lg-6 sess-title">' + (resultArr.restoreIssues ? resultArr.restoreIssues.length : 0) +
                '</span>';
            str += '<span class="col-lg-6 sess-title">Total Items:</span>';
            str += '<span class="col-lg-6 sess-title">' + resultArr.totalItemsCount + '</span>';
            str += '<span class="col-lg-6 sess-title">Failed Items:</span>';
            str += '<span class="col-lg-6 sess-title">' + resultArr.failedItemsCount + '</span>';
            str += '<span class="col-lg-6 sess-title">Restored Items:</span>';
            str += '<span class="col-lg-6 sess-title">' + resultArr.restoredItemsCount + '</span>';
            str += '<span class="col-lg-6 sess-title">Failed Restrictions:</span>';
            str += '<span class="col-lg-6 sess-title">' + resultArr.failedRestrictionsCount + '</span>';
            str += '<span class="col-lg-6 sess-title">Skipped Items By Error:</span>';
            str += '<span class="col-lg-6 sess-title">' + resultArr.skippedItemsByErrorCount + '</span>';
            str += '<span class="col-lg-6 sess-title">Skipped Items By No Changes:</span>';
            str += '<span class="col-lg-6 sess-title">' + resultArr.skippedItemsByNoChangesCount + '</span>';
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
        //---------------------------------------//
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

        function ConvertToDatePicker() {
            $("#durationFrom").addClass("html-duration-picker");
            $("#durationTo").addClass("html-duration-picker")
        }

        function downloadExportedFiles(id) {
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "GET",
                url: "{{ url('downloadExportedFile') }}/onedrive/" + id,
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
        //----------------------------------------------------//
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


        //---- Onedrives & Folders & Items Functions
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

        //----------------------------------------------------//
        //---- Modal Functions
        //----------------------------------------------------//
        function restoreAgainModal(state = 'all', id, type, subType) {
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
                    //--------------------------//
                    $('.jobId').val(data.backup_job_id);
                    $('.jobType').val(data.restore_point_type);
                    $('.jobTime').val(data.restore_point_time);
                    $('.showDeleted').val(data.is_restore_point_show_deleted == 1);
                    $('.showVersions').val(data.is_restore_point_show_version == 1);
                    //--------------------------------------------------// OndeDrive
                    if (type == "onedrive") {
                        options = JSON.parse(data.options);
                        let onedrives = data.details;
                        tableData = [];
                        let unresolvedCount = 0;
                        onedrives.forEach((e) => {
                            //-------------------------------------//
                            if (state == 'all' || e.status == "Failed") {
                                tableData.push({
                                    id: e.item_id,
                                    name: e.item_name,
                                    url: e.item_parent_name
                                });
                            }
                            //-------------------------------------//
                        });
                        let drivesCount = tableData.length;
                        //-------------------------------------//
                        if (options.toOnedrive) {
                            //----------------------------------//
                            $.each(options, function(key, value) {
                                if (key == "action") {
                                    $('#restoreOneDriveCopyForm [name="restoreAction"][value="' +
                                        value + '"]').prop("checked", "checked");
                                } else if (key == "version") {
                                    $('#restoreOneDriveCopyForm [name="documentVersion"][value="' +
                                        value + '"]').prop("checked", "checked");
                                } else if (key == "toFolder") {
                                    $('#restoreOneDriveCopyForm input[name="folder"]').val(value);
                                } else if (key == "toOnedrive") {
                                    let to = JSON.parse(value);
                                    let userId = $('#restoreOneDriveCopyForm option:contains("' + to
                                        .name + '")').val();
                                    $('#restoreOneDriveCopyForm .users').select2().val(userId);
                                    $('#restoreOneDriveCopyForm input[name="onedrive"]').append(
                                        new Option(to.url, to.id));
                                } else if (value == 'true')
                                    $('#restoreOneDriveCopyForm input[name="' + key + '"]').prop(
                                        'checked', 'checked');
                                else
                                    $('#restoreOneDriveCopyForm input[name="' + key + '"]')
                                    .removeProp('checked');
                            });
                            //----------------------------------//
                            $('#oneDriveCopyTable_wrapper').find('.boxesCount').html(drivesCount);
                            $('#oneDriveCopyTable').DataTable().clear().draw();
                            $('#oneDriveCopyTable').DataTable().rows.add(tableData); // Add new data
                            $('#oneDriveCopyTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                            checkTableCount('oneDriveCopyTable');
                            adjustTable();
                            $("#oneDriveCopyTable").DataTable().draw();
                            $('#restoreOneDriveCopyModal').find('.refreshDeviceCode').click();
                            $('#restoreOneDriveCopyModal').modal('show');
                        } else {
                            //----------------------------------//
                            $.each(options, function(key, value) {
                                if (key == "action") {
                                    $('#restoreOnedriveForm input[name="restoreAction"]').val(value);
                                } else if (key == "version") {
                                    $('#restoreOnedriveForm [name="documentVersion"][value="' + value +
                                        '"]').prop("checked", "checked")
                                } else if (value == 'true')
                                    $('#restoreOnedriveForm input[name="' + key + '"]').prop(
                                        'checked', 'checked');
                                else
                                    $('#restoreOnedriveForm input[name="' + key + '"]')
                                    .removeProp('checked');
                            });
                            //----------------------------------//
                            $('#oneDriveOriginalTable_wrapper').find('.boxesCount').html(drivesCount);
                            $('#oneDriveOriginalTable').DataTable().clear().draw();
                            $('#oneDriveOriginalTable').DataTable().rows.add(tableData); // Add new data
                            $('#oneDriveOriginalTable').DataTable().columns.adjust()
                                .draw(); // Redraw the DataTable

                            checkTableCount('oneDriveOriginalTable');
                            adjustTable();
                            $("#oneDriveOriginalTable").DataTable().draw();
                            if ($('#restoreOnedriveForm input[name="restoreAction"]').val() == 'overwrite')
                                $('#restoreOneDriveModal').find('.modal-title').text(
                                    'Restore Selected OneDrives To Original Location (Overwrite)');
                            else
                                $('#restoreOneDriveModal').find('.modal-title').text(
                                    'Restore Selected OneDrives To Original Location (Keep)');
                            $('#restoreOneDriveModal').find('.refreshDeviceCode').click();
                            $('#restoreOneDriveModal').modal('show');
                        }
                    } else if (type == "folder") {
                        //-------------------------------------------------//
                        tableData = [];
                        options = JSON.parse(data.options);
                        let onedriveFolders = data.details;
                        let unresolvedCount = 0;
                        onedriveFolders.forEach((e) => {
                            if (state == 'all' || e.status == "Failed") {
                                let foldersArr = JSON.parse(e.item_id);
                                foldersArr.forEach((l) => {
                                    tableData.push({
                                        "id": l.id,
                                        "name": l.folder,
                                        "onedriveId": e.item_parent_id,
                                        "onedriveName": e.item_parent_name,
                                    });
                                });
                            }
                        });
                        let foldersCount = tableData.length;
                        $('#foldersResultsTable_wrapper').find('.boxesCount').html(foldersCount);
                        $('#foldersResultsTable').DataTable().clear().draw();
                        $('#foldersResultsTable').DataTable().rows.add(tableData); // Add new data
                        $('#foldersResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('foldersResultsTable');
                        adjustTable();
                        $("#foldersResultsTable").DataTable().draw();
                        $('#restoreFolder').modal('show');
                        //-------------------------------------------------//
                        if (options.toOnedrive) {
                            $.each(options, function(key, value) {
                                if (key == "version") {
                                    $('#restoreFolderForm [name="documentVersion"][value="' + value +
                                        '"]').prop("checked", "checked");
                                } else if (key == "restoreVersionAction") {
                                    $('#restoreFolderForm [name="restoreVersionAction"][value="' +
                                        value + '"]').prop("checked", "checked");
                                } else if (key == "toFolder") {
                                    $('#restoreFolderForm input[name="folder"]').val(value);
                                } else if (key == "toOnedrive") {
                                    let to = JSON.parse(value);
                                    let userId = $('#restoreFolderForm option:contains("' + to.name +
                                        '")').val();
                                    $('#restoreFolderForm .users').select2().val(userId);
                                    $('#restoreFolderForm input[name="onedrive"]').append(new Option(to
                                        .url, to.id));
                                } else if (value == 'true')
                                    $('#restoreFolderForm input[name="' + key + '"]').prop(
                                        'checked', 'checked');
                                else
                                    $('#restoreFolderForm input[name="' + key + '"]')
                                    .removeProp('checked');
                            });
                            $('#restoreFolderForm').find('.restoreType').val('another');
                            //--------------------//
                            $('#restoreFolder').find('.modal-title').text(
                                'Copy Selected Folders to Another Location');
                            $('#restoreFolderForm .restoreAnother_cont').removeClass('hide');
                            $('.modal-mt-10').removeClass('mt-10v');
                            $('.modal-h-190p').removeClass('h-253p');
                            $('.modal-h-190p').addClass('h-190p');
                            $('#restoreFolderForm .restoreAnother_cont .required').attr('required', 'required');
                        } else {
                            $.each(options, function(key, value) {
                                if (key == "version") {
                                    $('#restoreFolderForm [name="documentVersion"][value="' + value +
                                        '"]').prop("checked", "checked");
                                } else if (key == "action") {
                                    $('#restoreFolderForm [name="restoreAction"]').val(value);
                                } else if (value == 'true')
                                    $('#restoreFolderForm input[name="' + key + '"]').prop(
                                        'checked', 'checked');
                                else
                                    $('#restoreFolderForm input[name="' + key + '"]')
                                    .removeProp('checked');
                            });
                            $('#restoreFolderForm').find('.restoreType').val('original');
                            //--------------------//
                            $('#restoreFolderForm .restoreAnother_cont').addClass('hide');
                            $('.modal-mt-10').addClass('mt-10v');
                            $('.modal-h-190p').addClass('h-253p');
                            $('.modal-h-190p').removeClass('h-190p');
                            $('#restoreFolderForm .restoreAnother_cont .required').removeAttr('required');
                            //--------------------//
                            if ($('#restoreFolderForm input[name="restoreAction"]').val() == 'overwrite')
                                $('#restoreFolder').find('.modal-title').text(
                                    'Restore Selected Folders to Original Location (Overwrite)');
                            else
                                $('#restoreFolder').find('.modal-title').text(
                                    'Restore Selected Folders to Original Location (Keep)');
                        }
                        //-------------------------------------------------//
                        $('#restoreFolder').find('.refreshDeviceCode').click();
                        $('#restoreFolder').modal('show');
                    } else if (type == "document") {
                        options = JSON.parse(data.options);
                        //--------------------//
                        let items = data.details;
                        tableData = [];
                        items.forEach(function(e) {
                            let ondriveDocs = JSON.parse(e.item_id);
                            ondriveDocs.forEach(function(e1) {
                                if (state == 'all' || e.status == "Failed") {
                                    folderTitle = e1.folderTitle;
                                    onedriveTitle = e1.item_parent_name;
                                    tableData.push({
                                        "id": e1.id,
                                        "name": e1.name,
                                        "onedriveId": e.item_parent_id,
                                        "onedriveTitle": e1.onedriveTitle,
                                        "folderTitle": e1.folderTitle,
                                    });
                                }
                            });
                        });
                        //--------------------//
                        let itemsCount = tableData.length;
                        //--------------------//
                        if (options.toOnedrive) {
                            //--------------------//
                            $('#restoreDocumentCopyForm').find('.restoreAction').val('');
                            $('#restoreDocumentCopyForm').find('.restoreType').val('another');
                            //--------------------//
                            $('#restoreDocumentCopyForm .restoreAnother_cont').removeClass('hide');
                            $('#restoreDocumentCopyForm .restoreAnother_cont .required').attr('required',
                                'required');
                            //--------------------//
                            $.each(options, function(key, value) {
                                if (key == "version") {
                                    $('#restoreDocumentCopyForm [name="documentVersion"][value="' +
                                        value +
                                        '"]').prop("checked", "checked");
                                } else if (key == "restoreVersionAction") {
                                    $('#restoreDocumentCopyForm [name="restoreVersionAction"][value="' +
                                        value + '"]').prop("checked", "checked");
                                } else if (key == "toFolder") {
                                    $('#restoreDocumentCopyForm input[name="folder"]').val(value);
                                } else if (key == "toOnedrive") {
                                    let to = JSON.parse(value);
                                    let userId = $('#restoreDocumentCopyForm option:contains("' + to
                                        .name +
                                        '")').val();
                                    $('#restoreDocumentCopyForm .users').select2().val(userId);
                                    $('#restoreDocumentCopyForm input[name="onedrive"]').append(
                                        new Option(
                                            to.url, to.id));
                                } else if (value == 'true')
                                    $('#restoreDocumentCopyForm input[name="' + key + '"]').prop(
                                        'checked', 'checked');
                                else
                                    $('#restoreDocumentCopyForm input[name="' + key + '"]')
                                    .removeProp('checked');
                            });
                            //--------------------//
                            $('#restoreItemCopy').find('.modal-title').text(
                                'Copy Selected Documents To Another Location');
                            $('#docsCopyResultsTable_wrapper').find('.boxesCount').html(tableData.length);
                            $('#docsCopyResultsTable').DataTable().clear().draw();
                            $('#docsCopyResultsTable').DataTable().rows.add(tableData); // Add new data
                            $('#docsCopyResultsTable').DataTable().columns.adjust()
                                .draw(); // Redraw the DataTable

                            checkTableCount('docsCopyResultsTable');
                            adjustTable();
                            $("#docsCopyResultsTable").DataTable().draw();
                            //--------------------//
                            $('#restoreItemCopy').find('.refreshDeviceCode').click();
                            $('#restoreItemCopy').modal('show');
                            //--------------------//
                        } else {
                            //--------------------//
                            $.each(options, function(key, value) {
                                if (key == "action") {
                                    $('#restoreDocumentForm input[name="restoreAction"]').val(value);
                                } else if (key == "version") {
                                    $('#restoreDocumentForm [name="documentVersion"][value="' + value +
                                        '"]').prop("checked", "checked");
                                } else if (key == "restoreVersionAction") {
                                    $('#restoreDocumentForm [name="restoreVersionAction"][value="' +
                                        value + '"]').prop("checked", "checked");
                                } else if (value == 'true')
                                    $('#restoreDocumentForm input[name="' + key + '"]').prop(
                                        'checked', 'checked');
                                else
                                    $('#restoreDocumentForm input[name="' + key + '"]')
                                    .removeProp('checked');
                            });
                            //--------------------//
                            $('#restoreDocumentForm').find('.restoreType').val('original');
                            //--------------------//
                            $('#restoreDocumentForm .restoreAnother_cont').addClass('hide');
                            $('#restoreDocumentForm .restoreAnother_cont .required').removeAttr('required');
                            //--------------------//
                            $('#docsResultsTable_wrapper').find('.boxesCount').html(tableData.length);
                            $('#docsResultsTable').DataTable().clear().draw();
                            $('#docsResultsTable').DataTable().rows.add(tableData); // Add new data
                            $('#docsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                            checkTableCount('docsResultsTable');
                            adjustTable();
                            $("#docsResultsTable").DataTable().draw();
                            //--------------------//
                            if ($('#restoreDocumentForm input[name="restoreAction"]').val() ==
                                'overwrite') {
                                $('#restoreItem').find('.modal-title').text(
                                    'Restore Selected Documents To Original Location (Overwrite)');
                            } else {
                                $('#restoreItem').find('.modal-title').text(
                                    'Restore Selected Document To Original Location (Keep)');
                            }
                            $('#restoreItem').find('.refreshDeviceCode').click();
                            $('#restoreItem').modal('show');
                            //--------------------//
                        }
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
        function exportAgainModal(state = 'all', id, type, subType) {
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
                    //------------------------------//
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    //------------------------------//
                    $('.jobId').val(data.backup_job_id);
                    $('.jobType').val(data.restore_point_type);
                    $('.jobTime').val(data.restore_point_time);
                    $('.showDeleted').val(data.is_restore_point_show_deleted == 1);
                    $('.showVersions').val(data.is_restore_point_show_version == 1);
                    if (type == "onedrive") {
                        options = JSON.parse(data.options);
                        let onedrives = data.details;
                        tableData = [];
                        let drivesCount = onedrives.length;
                        onedrives.forEach(function(e) {
                            tableData.push({
                                id: e.item_id,
                                name: e.item_name,
                                url: e.item_parent_name
                            });
                        });
                        $('#exportOneDriveTable_wrapper').find('.boxesCount').html(drivesCount);
                        $('#exportOneDriveTable').DataTable().clear().draw();
                        $('#exportOneDriveTable').DataTable().rows.add(tableData); // Add new data
                        $('#exportOneDriveTable').DataTable().columns.adjust().draw(); // Redraw the DataTable
                        checkTableCount('exportOneDriveTable');
                        adjustTable();
                        $("#exportOneDriveTable").DataTable().draw();
                        $('#exportOnedriveModal').modal('show');
                        //------------------------------------------------//
                    } else if (type == "folder") {
                        //-------------------------------------------------//
                        tableData = [];
                        options = JSON.parse(data.options);
                        let onedriveFolders = data.details;
                        let unresolvedCount = 0;
                        onedriveFolders.forEach((e) => {
                            if (state == 'all' || e.status == "Failed") {
                                let foldersArr = JSON.parse(e.item_id);
                                foldersArr.forEach((l) => {
                                    tableData.push({
                                        "id": l.id,
                                        "name": l.folder,
                                        "onedriveId": e.item_parent_id,
                                        "onedriveName": e.item_parent_name,
                                    });
                                });
                            }
                        });
                        let foldersCount = tableData.length;
                        $('#exportFoldersResultsTable_wrapper').find('.boxesCount').html(foldersCount);
                        $('#exportFoldersResultsTable').DataTable().clear().draw();
                        $('#exportFoldersResultsTable').DataTable().rows.add(tableData); // Add new data
                        $('#exportFoldersResultsTable').DataTable().columns.adjust()
                            .draw(); // Redraw the DataTable

                        checkTableCount('exportFoldersResultsTable');
                        adjustTable();
                        $("#exportFoldersResultsTable").DataTable().draw();
                        $('#exportOnedriveFoldersModal').modal('show');
                    } else if (type == "document") {
                        let items = data.details;
                        tableData = [];
                        items.forEach(function(e) {
                            let ondriveDocs = JSON.parse(e.item_id);
                            ondriveDocs.forEach(function(e1) {
                                if (state == 'all' || e.status == "Failed") {
                                    folderTitle = e1.folderTitle;
                                    onedriveTitle = e1.item_parent_name;
                                    tableData.push({
                                        "id": e1.id,
                                        "name": e1.name,
                                        "onedriveId": e.item_parent_id,
                                        "onedriveTitle": e1.onedriveTitle,
                                        "folderTitle": e1.folderTitle,
                                    });
                                }
                            });
                        });
                        let itemsCount = tableData.length;
                        //--------------------//
                        parentName = onedriveTitle + '-' + folderTitle;
                        //--------------------//
                        $('#exportDocsResultsTable_wrapper').find('.boxesCount').html(tableData.length);
                        $('#exportDocsResultsTable').DataTable().clear().draw();
                        $('#exportDocsResultsTable').DataTable().rows.add(tableData); // Add new data
                        $('#exportDocsResultsTable').DataTable().columns.adjust()
                            .draw(); // Redraw the DataTable

                        checkTableCount('exportDocsResultsTable');
                        adjustTable();
                        $("#exportDocsResultsTable").DataTable().draw();
                        //--------------------//
                        $('#exportOnedriveDocumentsModal').modal('show');
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

        //---- Ajax Functions
        //----------------------------------------------------//
        function restoreOneDrive() {
            event.preventDefault()
            var data = $('#restoreOnedriveForm').serialize();
            data += "&skipUnresolved=" + $("#restoreOnedriveForm [name='skipUnresolved']")[0].checked;
            let onedrives = $('#restoreOnedriveForm .mailboxCheck:checked');
            let onedrivesArr = [];
            onedrives.each(function() {
                let tr = $(this).closest('tr');
                onedrivesArr.push({
                    id: $(this).val().trim(),
                    name: tr.find('td:nth-child(2)').html(),
                    url: tr.find('td:nth-child(3)').html(),
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreOneDrive') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" + data +
                    "&onedrives=" + JSON.stringify(onedrivesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000)
                    $('#restoreOneDriveModal').modal('hide');
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
        function restoreOneDriveCopy() {
            event.preventDefault();
            var data = $('#restoreOneDriveCopyForm').serialize();
            //--------------------------------------------//
            if (!$('#restoreOneDriveCopyForm').find("[name='changedItems']").prop("checked") && !$(
                    '#restoreOneDriveCopyForm').find("[name='deletedItems']").prop("checked")) {
                $(".danger-oper .danger-msg").html(
                    "{{ __('variables.errors.restore_required_options') }}");
                $(".danger-oper").css("display", "block");
                setTimeout(function() {
                    $(".danger-oper").css("display", "none");
                }, 3000);
                return false;
            }
            //--------------------------------------------//
            data += "&changedItems=" + $("#restoreOneDriveCopyForm [name='changedItems']")[0].checked;
            data += "&deletedItems=" + $("#restoreOneDriveCopyForm [name='deletedItems']")[0].checked;
            data += "&restorePermissions=" + $("#restoreOneDriveCopyForm [name='restorePermissions']")[0].checked;
            data += "&sendSharedLinksNotification=" + $("#restoreOneDriveCopyForm [name='sendSharedLinksNotification']")[0]
                .checked;

            let toOnedrive = {
                'id': $('#restoreOneDriveCopyForm #onedrive').val(),
                'name': $('#restoreOneDriveCopyForm .users option:selected').html(),
                'url': $('#restoreOneDriveCopyForm #onedrive option:first').html()
            };
            data += "&onedrive=" + JSON.stringify(toOnedrive);
            let onedrives = $('#restoreOneDriveCopyForm .mailboxCheck:checked');
            let onedrivesArr = [];
            onedrives.each(function() {
                let tr = $(this).closest('tr');
                onedrivesArr.push({
                    id: $(this).val().trim(),
                    name: tr.find('td:nth-child(2)').html(),
                    url: tr.find('td:nth-child(3)').html(),
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('copyOneDrive') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}" +
                    "&onedrives=" + JSON.stringify(onedrivesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000)
                    $('#restoreOneDriveCopyModal').modal('hide');
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
        function exportOnedrive() {
            event.preventDefault()
            let data = $('#exportOnedriveForm').serialize();
            let onedrives = $('#exportOnedriveForm .mailboxCheck:checked');
            let onedrivesArr = [];
            onedrives.each(function() {
                let tr = $(this).closest('tr');
                onedrivesArr.push({
                    id: $(this).val().trim(),
                    name: tr.find('td:nth-child(2)').html(),
                    url: tr.find('td:nth-child(3)').html(),
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportOnedrive') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: "_token={{ csrf_token() }}&" + data +
                    "&onedrives=" + JSON.stringify(onedrivesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000)
                    $('#exportOnedriveModal').modal('hide');
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
        function exportOnedriveFolders() {
            event.preventDefault()
            let folders = $('#exportOnedriveFoldersForm .mailboxCheck:checked');
            let data = $('#exportOnedriveFoldersForm').serialize();
            let onedrivesFolders = [];
            let index;
            //-----------------------------------//
            folders.each(function() {
                let tr = $(this).closest('tr');
                let folderParentId = $(this).attr('onedriveId');
                index = onedrivesFolders.findIndex(x => x.id === folderParentId);
                if (index == -1) {
                    onedrivesFolders.push({
                        "id": folderParentId,
                        "name": tr.find('td:nth-child(3)').html()
                    });
                    index = onedrivesFolders.findIndex(x => x.id === folderParentId);
                    onedrivesFolders[index].folders = [];
                }
                onedrivesFolders[index].folders.push({
                    id: $(this).val(),
                    folder: tr.find('td:nth-child(2)').html()
                });
            });
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportOnedriveFolders') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: "_token={{ csrf_token() }}&" + data +
                    "&folders=" + JSON.stringify(onedrivesFolders),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000)
                    $('#exportOnedriveFoldersModal').modal('hide');
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
                    $(".danger-oper").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");

                    }, 8000);
                }
            });
            return false;
        }
        //---------------------------------------------------//
        function exportOnedriveDocuments() {
            event.preventDefault()
            let docs = $('#exportOnedriveDocumentsForm .mailboxCheck:checked');
            let data = $('#exportOnedriveDocumentsForm').serialize();
            let docsArr = [];
            let index;
            //-----------------------------------//
            docs.each(function() {
                let tr = $(this).closest('tr');
                let folderParentId = $(this).attr('onedriveId');
                docsArr.push({
                    id: $(this).val(),
                    name: tr.find('td:nth-child(2)').html(),
                    onedriveId: tr.find('.onedriveId').val(),
                    onedriveTitle: tr.find('.onedriveTitle').val(),
                    folderTitle: tr.find('.folderTitle').val(),
                });
            });
            //-----------------------------------//
            let onedriveDocs = [];
            onedriveDocs.push({
                "onedriveId": docsArr[0].onedriveId,
                "onedriveTitle": docsArr[0].onedriveTitle,
                "folderTitle": docsArr[0].folderTitle,
                "docs": docsArr
            });
            //-----------------------------------//
            data += "&docs=" + JSON.stringify(onedriveDocs);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportOnedriveDocuments') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data +
                    "&_token={{ csrf_token() }}",
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000)
                    $('#exportOnedriveDocumentsModal').modal('hide');
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
        function restoreFolder() {
            event.preventDefault();
            //-----------------------------------//
            if ($('#restoreFolderForm').find(".restoreType").val() == "another") {
                if (!$('#restoreFolderForm').find("[name='changedItems']").prop("checked") && !$('#restoreFolderForm').find(
                        "[name='deletedItems']").prop("checked")) {
                    $(".danger-oper .danger-msg").html(
                        "{{ __('variables.errors.restore_required_options') }}");
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");
                    }, 3000);
                    return false;
                }
            }
            //-----------------------------------//
            let folders = $('#restoreFolderForm .mailboxCheck:checked');
            let onedrivesFolders = [];
            let index;
            //-----------------------------------//
            folders.each(function() {
                let tr = $(this).closest('tr');
                let folderParentId = $(this).attr('onedriveId');
                index = onedrivesFolders.findIndex(x => x.id === folderParentId);
                if (index == -1) {
                    onedrivesFolders.push({
                        "id": folderParentId,
                        "name": tr.find('td:nth-child(3)').html()
                    });
                    index = onedrivesFolders.findIndex(x => x.id === folderParentId);
                    onedrivesFolders[index].folders = [];
                }
                onedrivesFolders[index].folders.push({
                    id: $(this).val(),
                    folder: tr.find('td:nth-child(2)').html()
                });
            });
            //-----------------------------------//
            var data = $('#restoreFolderForm').serialize();
            data += "&changedItems=" + $("#restoreFolderForm [name='changedItems']")[0].checked;
            data += "&deletedItems=" + $("#restoreFolderForm [name='deletedItems']")[0].checked;
            data += "&restorePermissions=" + $("#restoreFolderForm [name='restorePermissions']")[0].checked;
            data += "&sendSharedLinksNotification=" + $("#restoreFolderForm [name='sendSharedLinksNotification']")[0]
                .checked;
            let toOnedrive = {
                'id': $('#restoreFolderForm #onedrive').val(),
                'name': $('#restoreFolderForm .users option:selected').html(),
                'url': $('#restoreFolderForm #onedrive option:first').html()
            };
            data += "&onedrive=" + JSON.stringify(toOnedrive);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreOnedriveFolder') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "&folders=" + JSON.stringify(onedrivesFolders),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000)
                    $('#restoreFolder').modal('hide');
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
        function restoreItem() {
            event.preventDefault()
            let docs = $('#restoreDocumentForm .mailboxCheck:checked');
            let docsArr = [];
            docs.each(function() {
                if (docsArr.filter(e => e.onedriveId === $(this).attr("onedriveId")).length == 0) {
                    let onedriveItems = $('#restoreDocumentForm .mailboxCheck:checked[onedriveId="' + $(this).attr(
                        "onedriveId") + '"]');
                    let onedriveItemsArr = [];
                    onedriveItems.each(function() {
                        let tr = $(this).closest('tr');
                        onedriveItemsArr.push({
                            "id": $(this).val(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "folderTitle": tr.find('.folderTitle').val(),
                            "onedriveTitle": tr.find('.onedriveTitle').val(),
                        })
                    });
                    let tr = $(this).closest('tr');
                    docsArr.push({
                        "onedriveId": tr.find('.onedriveId').val(),
                        "onedriveTitle": tr.find('.onedriveTitle').val(),
                        "folderTitle": tr.find('.folderTitle').val(),
                        "docs": onedriveItemsArr
                    });
                }
            });
            //-----------------------------------//
            var data = $('#restoreDocumentForm').serialize();
            //data += "&changedItems="+$("#restoreDocumentForm [name='changedItems']")[0].checked;
            //data += "&deletedItems="+$("#restoreDocumentForm [name='deletedItems']")[0].checked;
            //data += "&restorePermissions="+$("#restoreDocumentForm [name='restorePermissions']")[0].checked;
            //data += "&sendSharedLinksNotification="+$("#restoreDocumentForm [name='sendSharedLinksNotification']")[0].checked;
            let toOnedrive = {
                'id': $('#restoreDocumentForm #onedrive').val(),
                'name': $('#restoreDocumentForm .users option:selected').html(),
                'url': $('#restoreDocumentForm #onedrive option:first').html()
            };
            data += "&onedrive=" + JSON.stringify(toOnedrive);
            data += "&docs=" + JSON.stringify(docsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreOnedriveDocs') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + "&_token={{ csrf_token() }}",
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000)
                    $('#restoreItem').modal('hide');
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
        function restoreItemCopy() {
            event.preventDefault();
            //-----------------------------------//
            let docs = $('#restoreDocumentCopyForm .mailboxCheck:checked');
            let docsArr = [];
            docs.each(function() {
                if (docsArr.filter(e => e.onedriveId === $(this).attr("onedriveId")).length == 0) {
                    let onedriveItems = $('#restoreDocumentCopyForm .mailboxCheck:checked[onedriveId="' + $(this)
                        .attr("onedriveId") + '"]');
                    let onedriveItemsArr = [];
                    onedriveItems.each(function() {
                        let tr = $(this).closest('tr');
                        onedriveItemsArr.push({
                            "id": $(this).val(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "folderTitle": tr.find('.folderTitle').val(),
                            "onedriveTitle": tr.find('.onedriveTitle').val(),
                        })
                    });
                    let tr = $(this).closest('tr');
                    docsArr.push({
                        "onedriveId": tr.find('.onedriveId').val(),
                        "onedriveTitle": tr.find('.onedriveTitle').val(),
                        "folderTitle": tr.find('.folderTitle').val(),
                        "docs": onedriveItemsArr
                    });
                }
            });
            //-----------------------------------//
            var data = $('#restoreDocumentCopyForm').serialize();
            let toOnedrive = {
                'id': $('#restoreDocumentCopyForm #onedrive').val(),
                'name': $('#restoreDocumentCopyForm .users option:selected').html(),
                'url': $('#restoreDocumentCopyForm #onedrive option:first').html()
            };
            data += "&onedrive=" + JSON.stringify(toOnedrive);
            data += "&docs=" + JSON.stringify(docsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreOnedriveDocs') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}",
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000);
                    $('#restoreItemCopy').modal('hide');
                },
                statusCode: {
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
