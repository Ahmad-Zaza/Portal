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
            @if (optional($data['restoreOptions'])->restorePermissions)
                <div class="col">
                    <div class="col-lg-11" style="padding-left: 0px;">
                        <h5 class="txt-blue">Restore Options</h5>
                    </div>
                    <div class="col-lg-12 newJobRow">
                        <div class="rowBorderRight"></div>
                        <div class="rowBorderBottom"></div>
                        <div class="rowBorderleft"></div>
                        <div class="rowBorderUp"></div>
                        @if (optional($data['restoreOptions'])->alias)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Target Site:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding tooltipSpan"
                                    title="{{ optional($data['restoreOptions'])->alias }}">
                                    {{ optional($data['restoreOptions'])->alias }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->list)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    List:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding tooltipSpan"
                                    title="{{ optional($data['restoreOptions'])->list }}">
                                    {{ optional($data['restoreOptions'])->list }}
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
                        <div class="col-lg-12 sess-info">
                            <div class="col-lg-8 nopadding sess-title">
                                Document Last Version Action:
                            </div>
                            <div class="sess-info-details col-lg-4 nopadding">
                                {{ optional($data['restoreOptions'])->documentLastVersionAction }}
                            </div>
                        </div>
                        <div class="col-lg-12 sess-info">
                            <div class="col-lg-8 nopadding sess-title">
                                Documents Version:
                            </div>
                            <div class="sess-info-details col-lg-4 nopadding">
                                {{ optional($data['restoreOptions'])->documentVersion }}
                            </div>
                        </div>
                        @if (optional($data['restoreOptions'])->restoreSubsites)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore Subsites:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->restoreSubsites }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->restoreListViews)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore List Views:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->restoreListViews }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->restoreMasterPages)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-8 nopadding sess-title">
                                    Restore Master Pages:
                                </div>
                                <div class="sess-info-details col-lg-4 nopadding">
                                    {{ optional($data['restoreOptions'])->restoreMasterPages }}
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
            $showButton = ($isRestore === 0 && $status != 'In Progress' && $role->hasPermissionTo('sharepoint_export_again')) || (!$isRestore && in_array($status, ['Canceled', 'Expired', 'Failed']) && $role->hasPermissionTo('sharepoint_restore_again'));
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
    <div id="exportSiteItemsModal" class="modal modal-center" role="dialog">
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
                            <h4 class="per-req ml-2p">Export Site Items Attachments To .Zip
                            </h4>
                        </div>
                    </div>
                    <form id="exportSiteItemsForm" class="mb-0" onsubmit="exportSiteItems(event)">
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
                                <h5 class="txt-blue mt-0">Site Items</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder">
                                    <div class="allWidth">
                                        <table id="exportItemsResultsTable"
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
                                                    <th>Item</th>
                                                    <th>List</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">
                                                        <span class="boxesCount"></span> items selected
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
    <div id="restoreSiteModal" class="modal modal-center" role="dialog">
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
                            <h4 class="per-req ml-2p">Restore Selected Sites To Original Location
                            </h4>
                        </div>
                    </div>
                    <form id="restoreSiteForm" class="mb-0" onsubmit="restoreSite(event)">
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
                                    <h5 class="txt-blue mt-0">Selected Sites</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-107p">
                                        <div class="allWidth sitesResultsTable">
                                            <table id="sitesTable"
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
                                                        <th>Site</th>
                                                        <th>Url</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> sites selected <span
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
                                    <div class="col-lg-12 customBorder h-320p pt-3 pb-3">
                                        <div class="flex">
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
                                        <div class="flex pt-3 pb-3">
                                            <label class="mr-4 m-0 nowrap">Documents Last Version Action:</label>
                                        </div>
                                        <div class="radioDiv">
                                            <div class="radio m-0 pb-10">
                                                <label>
                                                    <input type="radio" name="documentLastVersionAction"
                                                        class="documentLastVersionAction" value="Overwrite"
                                                        checked="">Overwrite
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="radio m-0">
                                                <label>
                                                    <input type="radio" name="documentLastVersionAction"
                                                        class="documentLastVersionAction" value="Merge">Merge
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
                                            </div>
                                            <div class="row">
                                                <div class="pr-0 pl-4 mt-2">
                                                    <label>Restore the following items:</label>
                                                </div>
                                                <div class="w-100"></div>
                                                <div class="col">
                                                    <div class="relative allWidth mb-2">
                                                        <label style="padding-top: 5px;left: 0px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="restoreListViews" type="checkbox"
                                                                class="form-check-input">
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <span style="margin-left: 25px;">List Views</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="relative allWidth mb-2">
                                                        <label style="padding-top: 5px;left: 0px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="restoreSubsites" type="checkbox"
                                                                class="form-check-input">
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <span style="margin-left: 25px;">Subsites</span>
                                                    </div>
                                                </div>
                                                <div class="w-100"></div>
                                                <div class="col">
                                                    <div class="relative allWidth mb-2">
                                                        <label style="padding-top: 5px;left: 0px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="restoreMasterPages" type="checkbox"
                                                                class="form-check-input">
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <span style="margin-left: 25px;">Master Pages</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="relative allWidth mb-2">
                                                        <label style="padding-top: 5px;left: 0px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="restorePermissions" type="checkbox"
                                                                class="form-check-input">
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <span style="margin-left: 25px;">Permissions</span>
                                                    </div>
                                                </div>
                                                <div class="w-100"></div>
                                                <div class="col">
                                                    <div class="relative allWidth">
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
    <div id="restoreContentModal" class="modal modal-center" role="dialog">
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
                            <h4 class="per-req ml-2p">Restore Selected <span class="contentType"></span>
                            </h4>
                        </div>
                    </div>
                    <form id="restoreContentForm" class="mb-0" onsubmit="restoreContent(event)">
                        <div class="custom-left-col">
                            <input type="hidden" name="contentType">
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
                                    <h5 class="txt-blue mt-0">Selected <span class="contentType"></span></h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-142p">
                                        <div class="allWidth">
                                            <table id="contentsTable"
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
                                                        <th><span class="contentTypeSingle"></span></th>
                                                        <th>Site</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> <span
                                                                class="contentType"></span> selected <span
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
                            <div class="row restoreAnother_cont">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Target <span class="contentTypeSingle"></span></h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3">
                                        <div class="authCont custom-authCont pt-3 pb-3">
                                            <label class="mr-4 m-0 nowrap">Site to Restore to:</label>
                                        </div>
                                        <div class="radioDiv pb-6">
                                            <div class="radio m-0">
                                                <label>
                                                    <input type="radio" name="listType" class="listType"
                                                        value="original" checked>Original <span
                                                        class="contentTypeSingle"></span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="radio m-0">
                                                <label>
                                                    <input type="radio" name="listType" class="listType"
                                                        value="custom" checked>Following <span
                                                        class="contentTypeSingle"></span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="allWidth">
                                            <input type="text" required
                                                class="required form-control form_input custom-form-control font-size"
                                                id="list" placeholder="List" name="list" required
                                                autocomplete="off" />
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
                                        <div class="flex">
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
                                        <div class="flex pt-3 pb-3">
                                            <label class="mr-4 m-0 nowrap">Documents Last Version Action:</label>
                                        </div>
                                        <div class="radioDiv pb-10">
                                            <div class="radio m-0">
                                                <label>
                                                    <input type="radio" name="documentLastVersionAction"
                                                        class="documentLastVersionAction" value="Overwrite"
                                                        checked="">Overwrite
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="radio m-0">
                                                <label>
                                                    <input type="radio" name="documentLastVersionAction"
                                                        class="documentLastVersionAction" value="Merge">Merge
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
                                            </div>
                                            <div class="row">
                                                <div class="pr-0 pl-4 mt-2">
                                                    <label>Restore the following items:</label>
                                                </div>
                                                <div class="w-100"></div>
                                                <div class="col">
                                                    <div class="relative allWidth mb-2">
                                                        <label style="padding-top: 5px;left: 0px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="restoreListViews" type="checkbox"
                                                                class="form-check-input">
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <span style="margin-left: 25px;">List Views</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="relative allWidth mb-2">
                                                        <label style="padding-top: 5px;left: 0px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="restorePermissions" type="checkbox"
                                                                class="form-check-input">
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <span style="margin-left: 25px;">Permissions</span>
                                                    </div>
                                                </div>
                                                <div class="w-100"></div>
                                                <div class="col">
                                                    <div class="relative allWidth">
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
    <div id="exportLibrariesModal" class="modal modal-center" role="dialog">
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
                            <h4 class="per-req ml-2p">Export Libraries to .Zip
                            </h4>
                        </div>
                    </div>
                    <form id="exportLibrariesForm" class="mb-0" onsubmit="exportLibraries(event)">
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
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder">
                                    <div class="allWidth">
                                        <table id="contentsTableResult"
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
                                                    <th>Library</th>
                                                    <th>Site</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">
                                                        <span class="boxesCount"></span> Libraries selected <span
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
    <div id="exportSiteDocumentsModal" class="modal modal-center" role="dialog">
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
                            <h4 class="per-req ml-2p">Export Site Documents To .Zip
                            </h4>
                        </div>
                    </div>
                    <form id="exportSiteDocumentsForm" class="mb-0" onsubmit="exportSiteDocuments(event)">
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
                                <h5 class="txt-blue mt-0">Site Documents</h5>
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
                                                    <th>Library</th>
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
    <div id="restoreSiteDocumentsModal" class="modal modal-center" role="dialog">
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
                            <h4 class="per-req ml-2p">Restore Selected Documents</h4>
                        </div>
                    </div>
                    <form id="restoreSiteDocumentsForm" class="mb-0" onsubmit="restoreSiteDocuments(event)">
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
                                    <h5 class="txt-blue mt-0">Site Documents</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-127p">
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
                                                        <th>Library</th>
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
                            <div class="row restoreAnother_cont">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Target Library</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3">
                                        <div class="allWidth">
                                            <div class="authCont custom-authCont pt-3 pb-3">
                                                <label class="mr-4 m-0 nowrap">Library to Restore To:</label>
                                                <div class="radioDiv">
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="listType" class="listType"
                                                                value="original" checked>Original
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="listType" class="listType"
                                                                value="custom" checked>Following
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="text" required
                                                class="required form-control form_input custom-form-control font-size"
                                                id="list" placeholder="Library" name="list" required
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Options</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-215p pb-3 pt-3">
                                        <div class="flex">
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
                                        <div class="flex pt-3 pb-3">
                                            <label class="mr-4 m-0 nowrap">Documents Last Version Action:</label>
                                        </div>
                                        <div class="radioDiv pb-10">
                                            <div class="radio m-0">
                                                <label>
                                                    <input type="radio" name="documentLastVersionAction"
                                                        class="documentLastVersionAction" value="Overwrite"
                                                        checked="">Overwrite
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="radio m-0">
                                                <label>
                                                    <input type="radio" name="documentLastVersionAction"
                                                        class="documentLastVersionAction" value="Merge">Merge
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
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="relative allWidth">
                                                        <label style="padding-top: 5px;left: 0px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="restorePermissions" type="checkbox"
                                                                class="form-check-input">
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <span style="margin-left: 25px;">Permissions</span>
                                                    </div>
                                                </div>
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
    <div id="restoreSiteItemsModal" class="modal modal-center" role="dialog">
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
                            <h4 class="per-req ml-2p">Restore Selected Items</h4>
                        </div>
                    </div>
                    <form id="restoreSiteItemsForm" class="mb-0" onsubmit="restoreSiteItems(event)">
                        <input type="hidden" class="showVersions" name="showVersions" />
                        <input type="hidden" class="showDeleted" name="showDeleted" />
                        <input type="hidden" class="jobTime" name="jobTime" />
                        <input type="hidden" class="jobId" name="jobId" />
                        <input type="hidden" class="jobType" name="jobType" />
                        <div class="custom-left-col">
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
                                    <h5 class="txt-blue mt-0">Site Items</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-127p">
                                        <div class="allWidth">
                                            <table id="itemsResultsTable"
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
                                                        <th>Item</th>
                                                        <th>List</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> items selected
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
                            <div class="row restoreAnother_cont">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Target Items</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3">
                                        <div class="allWidth">
                                            <div class="authCont custom-authCont pt-3 pb-3">
                                                <label class="mr-4 m-0 nowrap">List to Restore To:</label>
                                                <div class="radioDiv">
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="listType" class="listType"
                                                                value="original" checked>Original
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="listType" class="listType"
                                                                value="custom" checked>Following
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="text" required
                                                class="required form-control form_input custom-form-control font-size"
                                                id="list" placeholder="List" name="list" required
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Options</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-215p pb-3 pt-3">
                                        <div class="flex">
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
                                        <div class="flex pt-3 pb-3">
                                            <label class="mr-4 m-0 nowrap">Documents Last Version Action:</label>
                                        </div>
                                        <div class="radioDiv pb-10">
                                            <div class="radio m-0">
                                                <label>
                                                    <input type="radio" name="documentLastVersionAction"
                                                        class="documentLastVersionAction" value="Overwrite"
                                                        checked="">Overwrite
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="radio m-0">
                                                <label>
                                                    <input type="radio" name="documentLastVersionAction"
                                                        class="documentLastVersionAction" value="Merge">Merge
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
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="relative allWidth">
                                                        <label style="padding-top: 5px;left: 0px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="restorePermissions" type="checkbox"
                                                                class="form-check-input">
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <span style="margin-left: 25px;">Permissions</span>
                                                    </div>
                                                </div>
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
    <div id="restoreFoldersModal" class="modal modal-center" role="dialog">
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
                            <h4 class="per-req ml-2p">Restore Selected Folders</h4>
                        </div>
                    </div>
                    <form id="restoreFoldersForm" class="mb-0" onsubmit="restoreFolders(event)">
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
                                    <h5 class="txt-blue mt-0">Selected Folders</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-127p">
                                        <div class="allWidth">
                                            <table id="foldersResultTable"
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
                                                        <th>Site</th>
                                                        <th>Content</th>
                                                        <th>Folder</th>
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
                        </div>
                        <div class="custom-right-col">
                            <div class="row restoreAnother_cont">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Target List</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3">
                                        <div class="allWidth">
                                            <div class="authCont custom-authCont pt-3 pb-3">
                                                <label class="mr-4 m-0 nowrap">List to Restore To:</label>
                                                <div class="radioDiv">
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="listType" class="listType"
                                                                value="original" checked>Original
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="listType" class="listType"
                                                                value="custom" checked>Following
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="text" required
                                                class="required form-control form_input custom-form-control font-size"
                                                id="list" placeholder="Target" name="list" required
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Options</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-215p pb-3 pt-3">
                                        <div class="flex">
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
                                        <div class="flex pt-3 pb-3">
                                            <label class="mr-4 m-0 nowrap">Documents Last Version Action:</label>
                                        </div>
                                        <div class="radioDiv pb-10">
                                            <div class="radio m-0">
                                                <label>
                                                    <input type="radio" name="documentLastVersionAction"
                                                        class="documentLastVersionAction" value="Overwrite"
                                                        checked="">Overwrite
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="radio m-0">
                                                <label>
                                                    <input type="radio" name="documentLastVersionAction"
                                                        class="documentLastVersionAction" value="Merge">Merge
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
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="relative allWidth">
                                                        <label style="padding-top: 5px;left: 0px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="restorePermissions" type="checkbox"
                                                                class="form-check-input">
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <span style="margin-left: 25px;">Permissions</span>
                                                    </div>
                                                </div>
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
    <div id="exportFoldersModal" class="modal modal-center" role="dialog">
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
                            <h4 class="per-req ml-2p">Export Folders To .Zip
                            </h4>
                        </div>
                    </div>
                    <form id="exportFoldersForm" class="mb-0" onsubmit="exportFolders(event)">
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
                                <h5 class="txt-blue mt-0">Selected Folders</h5>
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
                                                    <th>Site</th>
                                                    <th>Content</th>
                                                    <th>Folders</th>
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
        let loadingSites = false;
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
                            if ("{{ $data['history']->type }}" == "document" ||
                                "{{ $data['history']->type }}" == "folder" ||
                                "{{ $data['history']->type }}" ==
                                "item") {
                                let resultsArr = JSON.parse(data.item_id);
                                let str = '<ul class="p-0 m-0">';
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
                            } else if (data.exported_file_size && isRestore == 0 && data.status ==
                                'Success' &&
                                "{{ $data['history']['status'] }}" != "Expired") {
                                @if ($role->hasPermissionTo('sharepoint_download_exported_files'))
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
                                    @if ($role->hasPermissionTo('sharepoint_export_again'))
                                        return '<div><img data-id="' + data.id +
                                            '" class="tableIcone hand restoreFailedAgain" style="width: 13px; margin-right:0;" src="/svg/restore\.svg " title="Retry"></div>';
                                    @endif
                                } else {
                                    @if ($role->hasPermissionTo('sharepoint_restore_again'))
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
            $('#exportItemsResultsTable').DataTable({
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
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '" data-contentId="' + data.contentId + '">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "contentTitle"
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
            //---------------------------------------//
            $('#sitesTable').DataTable({
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
                "scrollY": "38px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $('#contentsTable').DataTable({
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
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "siteTitle"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "73px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $('#contentsTableResult').DataTable({
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
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "siteTitle"
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

            $('#foldersResultTable').DataTable({
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
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '" data-contentId="' + data.contentId + '">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "siteTitle"
                    },
                    {
                        "data": "contentTitle"
                    },
                    {
                        "data": "name"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "58px",
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
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '" data-contentId="' + data.contentId + '">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "siteTitle"
                    },
                    {
                        "data": "contentTitle"
                    },
                    {
                        "data": "name"
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
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '" data-contentId="' + data.contentId + '">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "contentTitle"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "58px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $('#itemsResultsTable').DataTable({
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
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '" data-contentId="' + data.contentId + '">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "contentTitle"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "58px",
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
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '" data-contentId="' + data.contentId + '">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "contentTitle"
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

        function ConvertToDatePicker() {
            $("#durationFrom").addClass("html-duration-picker");
            $("#durationTo").addClass("html-duration-picker")
        }

        function downloadExportedFiles(id) {
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "GET",
                url: "{{ url('downloadExportedFile') }}/sharepoint/" + id,
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
        function exportedFilesModal(id, details, jobName) {
            $('#exportedFilesModal .modal-header').html("Exported Files Of " + jobName);
            details = JSON.parse(details);
            let data = [];
            details.forEach((e) => {
                data.push({
                    id: e.id,
                    name: e.item_name,
                    fileName: e.exported_file_size
                });
            });
            $('#exportedFilesTable').DataTable().clear().draw();
            $('#exportedFilesTable').DataTable().rows.add(data); // Add new data
            $('#exportedFilesTable').DataTable().columns.adjust().draw();
            $('#exportedFilesModal').modal('show');
        }
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
                    if (type == "site") {
                        options = JSON.parse(data.options);
                        let sites = data.details;
                        tableData = [];
                        let unresolvedCount = 0;
                        sites.forEach((e) => {
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
                        getSites();
                        //----------------------------------//
                        $.each(options, function(key, value) {
                            if (key == "alias") {
                                $('#restoreSiteForm input[name="alias"]').val(value);
                                if (!value) {
                                    $('#restoreSiteForm input[name="alias"]').val('');
                                    $('#restoreSiteForm .siteType[value="original"]').prop(
                                        'checked', true);
                                    $('#restoreSiteForm input[name="alias"]').attr('disabled',
                                        'disabled');
                                } else {
                                    $('#restoreSiteForm .siteType[value="custom"]').prop(
                                        'checked', true);
                                    $('#restoreSiteForm input[name="alias"]').val(value);
                                    $('#restoreSiteForm input[name="alias"]').removeAttr('disabled');
                                }
                            } else if (key == "documentLastVersionAction") {
                                $('#restoreSiteForm [name="documentLastVersionAction"][value="' +
                                    value + '"]').prop("checked", "checked")
                            } else if (key == "documentVersion") {
                                $('#restoreSiteForm [name="documentVersion"][value="' + value + '"]')
                                    .prop("checked", "checked")
                            } else if (value == 'true')
                                $('#restoreSiteForm input[name="' + key + '"]').prop(
                                    'checked', 'checked');
                            else
                                $('#restoreSiteForm input[name="' + key + '"]')
                                .removeProp('checked');
                        });
                        //----------------------------------//
                        $('#sitesTable_wrapper').find('.boxesCount').html(drivesCount);
                        $('#sitesTable').DataTable().clear().draw();
                        $('#sitesTable').DataTable().rows.add(tableData); // Add new data
                        $('#sitesTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('sitesTable');
                        adjustTable();
                        $("#sitesTable").DataTable().draw();
                        $('#restoreSiteModal').find('.refreshDeviceCode').click();
                        $('#restoreSiteModal').modal('show');
                        //--------------------------------------//
                    } else if (type == "list" || type == "library") {
                        //-------------------------------------------------//
                        $('#restoreContentModal').find('input[name="contentType"]').val(type);
                        $('#restoreContentModal').find('.contentType').html((type == "list" ? "Lists" :
                            "Libraries"));
                        $('#restoreContentModal').find('.contentTypeSingle').html(type);
                        //-------------------------------------------------//
                        tableData = [];
                        options = JSON.parse(data.options);
                        let siteContents = data.details;
                        let unresolvedCount = 0;
                        siteContents.forEach((e) => {
                            if (state == 'all' || e.status == "Failed") {
                                tableData.push({
                                    "id": e.item_id,
                                    "name": e.item_name,
                                    "siteId": e.item_parent_id,
                                    "siteTitle": e.item_parent_name
                                });
                            }
                        });
                        let foldersCount = tableData.length;
                        $('#contentsTable_wrapper').find('.boxesCount').html(foldersCount);
                        $('#contentsTable').DataTable().clear().draw();
                        $('#contentsTable').DataTable().rows.add(tableData); // Add new data
                        $('#contentsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('contentsTable');
                        adjustTable();
                        $("#contentsTable").DataTable().draw();
                        //-------------------------------------------------//
                        $.each(options, function(key, value) {
                            if (key == "list") {
                                $('#restoreContentModal [name="' + key + '"]').val(value);
                                if (!value) {
                                    $('#restoreContentModal input[name="list"]').val('');
                                    $('#restoreContentModal .listType[value="original"]').prop(
                                        'checked', true);
                                    $('#restoreContentModal input[name="list"]').attr('disabled',
                                        'disabled');
                                } else {
                                    $('#restoreContentModal .listType[value="custom"]').prop('checked',
                                        true);
                                    $('#restoreContentModal input[name="list"]').val(value);
                                    $('#restoreContentModal input[name="list"]').removeAttr('disabled');
                                }
                            } else if (key == "documentLastVersionAction") {
                                $('#restoreContentModal [name="documentLastVersionAction"][value="' +
                                    value + '"]').prop("checked", "checked");
                            } else if (key == "documentVersion") {
                                $('#restoreContentModal [name="documentVersion"][value="' + value +
                                    '"]').prop("checked", "checked");
                            } else if (value == 'true')
                                $('#restoreContentModal input[name="' + key + '"]').prop(
                                    'checked', 'checked');
                            else if (key != 'listType')
                                $('#restoreContentModal input[name="' + key + '"]')
                                .removeProp('checked');
                        });
                        //-------------------------------------------------//
                        $('#restoreContentModal').find('.refreshDeviceCode').click();
                        $('#restoreContentModal').modal('show');

                    } else if (type == "folder") {
                        tableData = [];
                        options = JSON.parse(data.options);
                        let items = data.details;
                        items.forEach(function(e) {
                            let siteDocs = JSON.parse(e.item_id);
                            siteDocs.forEach(function(e1) {
                                if (state == 'all' || e.status == "Failed") {
                                    tableData.push({
                                        "id": e1.id,
                                        "name": e1.name,
                                        "siteId": e.item_parent_id,
                                        "siteTitle": e1.siteTitle,
                                        "contentTitle": e1.contentTitle,
                                        "contentId": e1.contentId,
                                    });
                                }
                            });
                        });
                        //--------------------//
                        $('#foldersResultTable_wrapper').find('.boxesCount').html(tableData.length);
                        $('#foldersResultTable').DataTable().clear().draw();
                        $('#foldersResultTable').DataTable().rows.add(tableData); // Add new data
                        $('#foldersResultTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('foldersResultTable');
                        adjustTable();
                        $("#foldersResultTable").DataTable().draw();
                        //--------------------//
                        $('#restoreFoldersModal').modal('show');
                        //--------------------//
                        $.each(options, function(key, value) {
                            if (key == "list") {
                                $('#restoreFoldersForm [name="' + key + '"]').val(value);
                                if (!value) {
                                    $('#restoreFoldersForm input[name="list"]').val('');
                                    $('#restoreFoldersForm .listType[value="original"]').prop('checked',
                                        true);
                                    $('#restoreFoldersForm input[name="list"]').attr('disabled',
                                        'disabled');
                                } else {
                                    $('#restoreFoldersForm .listType[value="custom"]').prop('checked',
                                        true);
                                    $('#restoreFoldersForm input[name="list"]').val(value);
                                    $('#restoreFoldersForm input[name="list"]').removeAttr('disabled');
                                }
                            } else if (key == "documentLastVersionAction") {
                                $('#restoreFoldersForm [name="documentLastVersionAction"][value="' +
                                    value + '"]').prop("checked", "checked");
                            } else if (key == "documentVersion") {
                                $('#restoreFoldersForm [name="documentVersion"][value="' + value + '"]')
                                    .prop("checked", "checked");
                            } else if (value == 'true')
                                $('#restoreFoldersForm input[name="' + key + '"]').prop(
                                    'checked', 'checked');
                            else if (key != 'listType')
                                $('#restoreFoldersForm input[name="' + key + '"]')
                                .removeProp('checked');
                        });
                        //--------------------//
                        $('#restoreFoldersModal').find('.refreshDeviceCode').click();
                        $('#restoreFoldersModal').modal('show');
                    } else if (type == "document") {
                        tableData = [];
                        options = JSON.parse(data.options);
                        let items = data.details;
                        items.forEach(function(e) {
                            let siteDocs = JSON.parse(e.item_id);
                            siteDocs.forEach(function(e1) {
                                if (state == 'all' || e.status == "Failed") {
                                    tableData.push({
                                        "id": e1.id,
                                        "name": e1.name,
                                        "siteId": e.item_parent_id,
                                        "siteTitle": e1.siteTitle,
                                        "contentTitle": e1.contentTitle,
                                        "contentId": e1.contentId,
                                    });
                                }
                            });
                        });
                        //--------------------//
                        $('#docsResultsTable_wrapper').find('.boxesCount').html(tableData.length);
                        $('#docsResultsTable').DataTable().clear().draw();
                        $('#docsResultsTable').DataTable().rows.add(tableData); // Add new data
                        $('#docsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('docsResultsTable');
                        adjustTable();
                        $("#docsResultsTable").DataTable().draw();
                        //--------------------//
                        $('#restoreItem').modal('show');
                        //--------------------//
                        $.each(options, function(key, value) {
                            if (key == "documentVersion") {
                                $('#restoreSiteDocumentsForm [name="documentVersion"][value="' + value +
                                    '"]').prop("checked", "checked");
                            } else if (key == "documentLastVersionAction") {
                                $('#restoreSiteDocumentsForm [name="documentLastVersionAction"][value="' +
                                    value + '"]').prop("checked", "checked");
                            } else if (key == "list") {
                                if (!value) {
                                    $('#restoreSiteDocumentsForm input[name="list"]').val('');
                                    $('#restoreSiteDocumentsForm .listType[value="original"]').prop(
                                        'checked', true);
                                    $('#restoreSiteDocumentsForm input[name="list"]').attr('disabled',
                                        'disabled');
                                } else {
                                    $('#restoreSiteDocumentsForm .listType[value="custom"]').prop(
                                        'checked', true);
                                    $('#restoreSiteDocumentsForm input[name="list"]').val(value);
                                    $('#restoreSiteDocumentsForm input[name="list"]').removeAttr(
                                        'disabled');
                                }
                            } else if (value == 'true')
                                $('#restoreSiteDocumentsForm input[name="' + key + '"]').prop(
                                    'checked', 'checked');
                            else if (key != 'listType')
                                $('#restoreSiteDocumentsForm input[name="' + key + '"]')
                                .removeProp('checked');
                        });
                        //--------------------//
                        $('#restoreSiteDocumentsModal').find('.refreshDeviceCode').click();
                        $('#restoreSiteDocumentsModal').modal('show');
                    } else if (type == "item") {
                        tableData = [];
                        options = JSON.parse(data.options);
                        let items = data.details;
                        items.forEach(function(e) {
                            let siteDocs = JSON.parse(e.item_id);
                            siteDocs.forEach(function(e1) {
                                if (state == 'all' || e.status == "Failed") {
                                    tableData.push({
                                        "id": e1.id,
                                        "name": e1.name,
                                        "siteId": e.item_parent_id,
                                        "siteTitle": e1.siteTitle,
                                        "contentTitle": e1.contentTitle,
                                        "contentId": e1.contentId,
                                    });
                                }
                            });
                        });
                        //--------------------//
                        $('#itemsResultsTable_wrapper').find('.boxesCount').html(tableData.length);
                        $('#itemsResultsTable').DataTable().clear().draw();
                        $('#itemsResultsTable').DataTable().rows.add(tableData); // Add new data
                        $('#itemsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('itemsResultsTable');
                        adjustTable();
                        $("#itemsResultsTable").DataTable().draw();
                        //--------------------//
                        $.each(options, function(key, value) {
                            if (key == "documentVersion") {
                                $('#restoreSiteItemsForm [name="documentVersion"][value="' + value +
                                    '"]').prop("checked", "checked");
                            } else if (key == "documentLastVersionAction") {
                                $('#restoreSiteItemsForm [name="documentLastVersionAction"][value="' +
                                    value + '"]').prop("checked", "checked");
                            } else if (key == "list") {
                                if (!value) {
                                    $('#restoreSiteItemsForm input[name="list"]').val('');
                                    $('#restoreSiteItemsForm .listType[value="original"]').prop(
                                        'checked', true);
                                    $('#restoreSiteItemsForm input[name="list"]').attr('disabled',
                                        'disabled');
                                } else {
                                    $('#restoreSiteItemsForm .listType[value="custom"]').prop('checked',
                                        true);
                                    $('#restoreSiteItemsForm input[name="list"]').val(value);
                                    $('#restoreSiteItemsForm input[name="list"]').removeAttr(
                                        'disabled');
                                }
                            } else if (value == 'true')
                                $('#restoreSiteItemsForm input[name="' + key + '"]').prop(
                                    'checked', 'checked');
                            else if (key != 'listType')
                                $('#restoreSiteItemsForm input[name="' + key + '"]')
                                .removeProp('checked');
                        });
                        //--------------------//
                        $('#restoreSiteItemsModal').find('.refreshDeviceCode').click();
                        $('#restoreSiteItemsModal').modal('show');
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
                    if (type == "library") {
                        options = JSON.parse(data.options);
                        tableData = [];
                        let libraries = data.details;
                        let librariesCount = libraries.length;
                        libraries.forEach(function(e) {
                            tableData.push({
                                "id": e.item_id,
                                "name": e.item_name,
                                "siteId": e.item_parent_id,
                                "siteTitle": e.item_parent_name
                            });
                        });
                        $('#contentsTableResult_wrapper').find('.boxesCount').html(librariesCount);
                        $('#contentsTableResult').DataTable().clear().draw();
                        $('#contentsTableResult').DataTable().rows.add(tableData); // Add new data
                        $('#contentsTableResult').DataTable().columns.adjust().draw(); // Redraw the DataTable
                        checkTableCount('contentsTableResult');
                        adjustTable();
                        $("#contentsTableResult").DataTable().draw();
                        $('#exportLibrariesModal').modal('show');
                        //------------------------------------------------//
                    } else if (type == "item-attachments") {
                        options = JSON.parse(data.options);
                        let libraries = data.details;
                        tableData = [];
                        let librariesCount = libraries.length;
                        libraries.forEach(function(e) {
                            let tempArr = e.item_parent_name.split('-');
                            tableData.push({
                                "id": e.item_id,
                                "name": e.item_name,
                                "siteId": e.item_parent_id,
                                "siteTitle": tempArr[0],
                                "contentTitle": tempArr[1],
                            });
                        });
                        $('#exportItemsResultsTable_wrapper').find('.boxesCount').html(librariesCount);
                        $('#exportItemsResultsTable').DataTable().clear().draw();
                        $('#exportItemsResultsTable').DataTable().rows.add(tableData); // Add new data
                        $('#exportItemsResultsTable').DataTable().columns.adjust()
                            .draw(); // Redraw the DataTable
                        checkTableCount('exportItemsResultsTable');
                        adjustTable();
                        $("#exportItemsResultsTable").DataTable().draw();
                        $('#exportSiteItemsModal').modal('show');
                        //------------------------------------------------//
                    } else if (type == "folder") {
                        options = JSON.parse(data.options);
                        tableData = [];
                        let items = data.details;
                        items.forEach(function(e) {
                            let siteDocs = JSON.parse(e.item_id);
                            siteDocs.forEach(function(e1) {
                                if (state == 'all' || e.status == "Failed") {
                                    tableData.push({
                                        "id": e1.id,
                                        "name": e1.name,
                                        "siteId": e.item_parent_id,
                                        "siteTitle": e1.siteTitle,
                                        "contentTitle": e1.contentTitle,
                                        "contentId": e1.contentId,
                                    });
                                }
                            });
                        });
                        //--------------------//
                        $('#exportFoldersResultsTable_wrapper').find('.boxesCount').html(tableData.length);
                        $('#exportFoldersResultsTable').DataTable().clear().draw();
                        $('#exportFoldersResultsTable').DataTable().rows.add(tableData); // Add new data
                        $('#exportFoldersResultsTable').DataTable().columns.adjust()
                            .draw(); // Redraw the DataTable

                        checkTableCount('exportFoldersResultsTable');
                        adjustTable();
                        $("#exportFoldersResultsTable").DataTable().draw();
                        //--------------------//
                        $('#exportFoldersModal').modal('show');
                        //--------------------//
                    } else if (type == "document") {
                        tableData = [];
                        options = JSON.parse(data.options);
                        let items = data.details;
                        items.forEach(function(e) {
                            let siteDocs = JSON.parse(e.item_id);
                            siteDocs.forEach(function(e1) {
                                if (state == 'all' || e.status == "Failed") {
                                    tableData.push({
                                        "id": e1.id,
                                        "name": e1.name,
                                        "siteId": e.item_parent_id,
                                        "siteTitle": e1.siteTitle,
                                        "contentTitle": e1.contentTitle,
                                        "contentId": e1.contentId,
                                    });
                                }
                            });
                        });
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
                        $('#exportSiteDocumentsModal').modal('show');
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
        function getSites() {
            if (!loadingSites) {
                $('#restoreSiteModal').find('.onedrive-spinner').removeClass('hide');
                loadingSites = true;
                $.ajax({
                    type: "GET",
                    url: "{{ url('getOrganizationSites') }}",
                    data: {},
                    success: function(data) {
                        //---------------------------------//
                        $('#restoreSiteModal').find('.onedrive-spinner').addClass('hide');
                        //---------------------------------//
                        let sites = data;
                        $('.select_sites').html("");
                        if (sites.length > 0) {
                            let result = [];
                            //--------------------//
                            result.push({
                                id: '',
                                text: 'Select User',
                            });
                            sites.forEach((e) => {
                                result.push({
                                    id: e.url,
                                    name: e.name,
                                    text: e.name,
                                });
                            });
                            //--------------------//
                            $(".select_sites").removeClass('form-control form_input');
                            //--------------------//
                            $(".select_sites").select2({
                                data: result,
                            });
                            //--------------------//
                        }
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
                        $('#restoreSiteModal').find('.onedrive-spinner').addClass('hide');
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
            }
        }
        //---------------------------------------------------//

        //---- Ajax Functions
        //----------------------------------------------------//
        function restoreSite() {
            event.preventDefault();

            if ($("#restoreSiteForm [name='changedItems']")[0].checked || $(
                    "#restoreSiteForm [name='deletedItems']")[0].checked) {
                var data = $('#restoreSiteForm').serialize();
                data += "&restoreListViews=" + $("#restoreSiteForm [name='restoreListViews']")[0].checked;
                data += "&restoreSubsites=" + $("#restoreSiteForm [name='restoreSubsites']")[0].checked;
                data += "&restoreMasterPages=" + $("#restoreSiteForm [name='restoreMasterPages']")[0].checked;
                data += "&restorePermissions=" + $("#restoreSiteForm [name='restorePermissions']")[0].checked;
                data += "&changedItems=" + $("#restoreSiteForm [name='changedItems']")[0].checked;
                data += "&deletedItems=" + $("#restoreSiteForm [name='deletedItems']")[0].checked;
                data += "&sendSharedLinksNotification=" + $("#restoreSiteForm [name='sendSharedLinksNotification']")[0]
                    .checked;
                let sites = $('#restoreSiteForm .mailboxCheck:checked');
                let sitesArr = [];
                sites.each(function() {
                    let tr = $(this).closest('tr');
                    sitesArr.push({
                        id: $(this).val().trim(),
                        name: tr.find('td:nth-child(2)').html(),
                        url: tr.find('td:nth-child(3)').html(),
                    });
                });
                $(".spinner_parent").css("display", "block");
                $('body').addClass('removeScroll');
                $.ajax({
                    type: "POST",
                    url: "{{ url('restoreSite') }}",
                    beforeSend: function(request) {
                        request.setRequestHeader("fromHistory", true);
                    },
                    data: data + '&' +
                        "_token={{ csrf_token() }}" +
                        "&sites=" + JSON.stringify(sitesArr),
                    success: function(data) {
                        $(".spinner_parent").css("display", "none");
                        $('body').removeClass('removeScroll');
                        showSuccessMessage(data.message);
                        setTimeout(function() {
                            window.location = "{{ url('restore-history', $data['kind']) }}";
                        }, 4000);
                        $('#restoreSiteModal').modal('hide');
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
            } else {
                let errMessage = "   ERROR   ";
                $(".danger-oper .danger-msg").html(
                    "{{ __('variables.errors.restore_required_options') }}");
                $(".danger-oper").css("display", "block");
                setTimeout(function() {
                    $(".danger-oper").css("display", "none");
                }, 8000);
            }
            return false;
        }
        //---------------------------------------------------//
        function exportLibraries() {
            event.preventDefault()

            let libraries = $('#exportLibrariesForm .mailboxCheck:checked');
            let librariesArr = [];
            libraries.each(function() {
                let tr = $(this).closest('tr');
                let contentParentId = $(this).attr('data-siteId');
                librariesArr.push({
                    id: $(this).val(),
                    content: tr.find('td:nth-child(2)').html(),
                    siteId: contentParentId,
                    siteTitle: tr.find('td:nth-child(3)').html(),
                });
            });
            let data = $('#exportLibrariesForm').serialize();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportSiteLibraries') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: "_token={{ csrf_token() }}&" + data +
                    "&libraries=" + JSON.stringify(librariesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000);
                    $('#exportLibrariesModal').modal('hide');
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
        function exportSiteDocuments() {
            event.preventDefault()
            let docs = $('#exportSiteDocumentsForm .mailboxCheck:checked');
            let docsArr = [];
            //-----------------------------------//
            docs.each(function() {
                let tr = $(this).closest('tr');
                if (docsArr.filter(e => e.siteId === $(this).attr("data-siteId") && e.contentId === $(this).attr(
                        "data-contentId")).length == 0) {
                    let siteDocs = $('#exportSiteDocumentsForm .mailboxCheck:checked[data-siteId="' + $(this).attr(
                        "data-siteId") + '"][data-contentId="' + $(this).attr("data-contentId") + '"]');
                    let siteDocsArr = [];
                    siteDocs.each(function() {
                        tr = $(this).closest('tr');
                        siteDocsArr.push({
                            "id": $(this).val(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "siteTitle": tr.find('.siteTitle').val(),
                            "contentTitle": tr.find('.contentTitle').val(),
                            "contentId": $(this).attr("data-contentId"),
                            "siteId": $(this).attr("data-siteId"),
                        })
                    });
                    docsArr.push({
                        "siteId": $(this).attr("data-siteId"),
                        "siteTitle": tr.find('.siteTitle').val(),
                        "contentTitle": tr.find('.contentTitle').val(),
                        "contentId": $(this).attr("data-contentId"),
                        "items": siteDocsArr
                    });
                }
            });
            //-----------------------------------//
            let data = $('#exportSiteDocumentsForm').serialize();
            data += "&docs=" + JSON.stringify(docsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportSiteDocuments') }}",
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
                    }, 4000);
                    $('#exportSiteDocumentsModal').modal('hide');
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
        function restoreContent() {
            event.preventDefault()
            if ($("#restoreContentForm [name='changedItems']")[0].checked || $(
                    "#restoreContentForm [name='deletedItems']")[0].checked) {
                let contents = $('#restoreContentForm .mailboxCheck:checked');
                let contentsArr = [];
                let index;
                //-----------------------------------//
                contents.each(function() {
                    let tr = $(this).closest('tr');
                    let contentParentId = $(this).attr('data-siteId');
                    contentsArr.push({
                        id: $(this).val(),
                        content: tr.find('td:nth-child(2)').html(),
                        siteId: contentParentId,
                        siteTitle: tr.find('td:nth-child(3)').html(),
                    });
                });
                //-----------------------------------//
                var data = $('#restoreContentForm').serialize();
                data += "&changedItems=" + $("#restoreContentForm [name='changedItems']")[0].checked;
                data += "&deletedItems=" + $("#restoreContentForm [name='deletedItems']")[0].checked;
                data += "&restorePermissions=" + $("#restoreContentForm [name='restorePermissions']")[0].checked;
                data += "&sendSharedLinksNotification=" + $("#restoreContentForm [name='sendSharedLinksNotification']")[0]
                    .checked;
                data += "&restoreListViews=" + $("#restoreContentForm [name='restoreListViews']")[0].checked;
                //-----------------------------------//
                $(".spinner_parent").css("display", "block");
                $('body').addClass('removeScroll');
                $.ajax({
                    type: "POST",
                    url: "{{ url('restoreSiteContent') }}",
                    beforeSend: function(request) {
                        request.setRequestHeader("fromHistory", true);
                    },
                    data: data + '&' +
                        "_token={{ csrf_token() }}" +
                        "&content=" + JSON.stringify(contentsArr),
                    success: function(data) {
                        $(".spinner_parent").css("display", "none");
                        $('body').removeClass('removeScroll');
                        showSuccessMessage(data.message);
                        setTimeout(function() {
                            window.location = "{{ url('restore-history', $data['kind']) }}";
                        }, 4000);
                        $('#restoreContentModal').modal('hide');
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
            } else {
                let errMessage = "   ERROR   ";
                $(".danger-oper .danger-msg").html(
                    "{{ __('variables.errors.restore_required_options') }}");
                $(".danger-oper").css("display", "block");
                setTimeout(function() {
                    $(".danger-oper").css("display", "none");
                }, 8000);
            }
            return false;
        }
        //---------------------------------------------------//
        function restoreSiteDocuments() {
            event.preventDefault()
            let docs = $('#restoreSiteDocumentsForm .mailboxCheck:checked');
            let docsArr = [];
            //-----------------------------------//
            docs.each(function() {
                let tr = $(this).closest('tr');
                if (docsArr.filter(e => e.siteId === $(this).attr("data-siteId") && e.contentId === $(this).attr(
                        "data-contentId")).length == 0) {
                    let siteDocs = $('#restoreSiteDocumentsForm .mailboxCheck:checked[data-siteId="' + $(this).attr(
                        "data-siteId") + '"][data-contentId="' + $(this).attr("data-contentId") + '"]');
                    let siteDocsArr = [];
                    siteDocs.each(function() {
                        tr = $(this).closest('tr');
                        siteDocsArr.push({
                            "id": $(this).val(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "siteTitle": tr.find('.siteTitle').val(),
                            "contentTitle": tr.find('.contentTitle').val(),
                            "contentId": $(this).attr("data-contentId"),
                            "siteId": $(this).attr("data-siteId"),
                        })
                    });
                    docsArr.push({
                        "siteId": $(this).attr("data-siteId"),
                        "siteTitle": tr.find('.siteTitle').val(),
                        "contentTitle": tr.find('.contentTitle').val(),
                        "contentId": $(this).attr("data-contentId"),
                        "items": siteDocsArr
                    });
                }
            });
            //-----------------------------------//
            var data = $('#restoreSiteDocumentsForm').serialize();
            data += "&restorePermissions=" + $("#restoreSiteDocumentsForm [name='restorePermissions']")[0].checked;
            data += "&sendSharedLinksNotification=" + $("#restoreSiteDocumentsForm [name='sendSharedLinksNotification']")[0]
                .checked;
            data += "&docs=" + JSON.stringify(docsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreSiteDocuments') }}",
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
                    $('#restoreSiteDocumentsModal').modal('hide');
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
        function restoreFolders() {
            event.preventDefault()
            let folders = $('#restoreFoldersForm .mailboxCheck:checked');
            let foldersArr = [];
            //-----------------------------------//
            folders.each(function() {
                let tr = $(this).closest('tr');
                if (foldersArr.filter(e => e.siteId === $(this).attr("data-siteId") && e.contentId === $(this).attr(
                        "data-contentId")).length == 0) {
                    let siteFolders = $('#restoreFoldersForm .mailboxCheck:checked[data-siteId="' + $(this).attr(
                        "data-siteId") + '"][data-contentId="' + $(this).attr("data-contentId") + '"]');
                    let siteFoldersArr = [];
                    siteFolders.each(function() {
                        tr = $(this).closest('tr');
                        siteFoldersArr.push({
                            "id": $(this).val(),
                            "name": tr.find('td:nth-child(4)').html(),
                            "siteTitle": tr.find('.siteTitle').val(),
                            "contentTitle": tr.find('.contentTitle').val(),
                            "contentId": $(this).attr("data-contentId"),
                            "siteId": $(this).attr("data-siteId"),
                        })
                    });
                    foldersArr.push({
                        "siteId": $(this).attr("data-siteId"),
                        "siteTitle": tr.find('.siteTitle').val(),
                        "contentTitle": tr.find('.contentTitle').val(),
                        "contentId": $(this).attr("data-contentId"),
                        "items": siteFoldersArr
                    });
                }
            });
            //-----------------------------------//
            var data = $('#restoreFoldersForm').serialize();
            data += "&restorePermissions=" + $("#restoreFoldersForm [name='restorePermissions']")[0].checked;
            data += "&sendSharedLinksNotification=" + $("#restoreFoldersForm [name='sendSharedLinksNotification']")[0]
                .checked;
            data += "&folders=" + JSON.stringify(foldersArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreSiteFolders') }}",
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
                    $('#restoreFoldersModal').modal('hide');
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
        function exportFolders() {
            event.preventDefault()
            let folders = $('#exportFoldersForm .mailboxCheck:checked');
            let foldersArr = [];
            //-----------------------------------//
            folders.each(function() {
                let tr = $(this).closest('tr');
                if (foldersArr.filter(e => e.siteId === $(this).attr("data-siteId") && e.contentId === $(this).attr(
                        "data-contentId")).length == 0) {
                    let siteFolders = $('#exportFoldersForm .mailboxCheck:checked[data-siteId="' + $(this).attr(
                        "data-siteId") + '"][data-contentId="' + $(this).attr("data-contentId") + '"]');
                    let siteFoldersArr = [];
                    siteFolders.each(function() {
                        tr = $(this).closest('tr');
                        siteFoldersArr.push({
                            "id": $(this).val(),
                            "name": tr.find('td:nth-child(4)').html(),
                            "siteTitle": tr.find('.siteTitle').val(),
                            "contentTitle": tr.find('.contentTitle').val(),
                            "contentId": $(this).attr("data-contentId"),
                            "siteId": $(this).attr("data-siteId"),
                        })
                    });
                    foldersArr.push({
                        "siteId": $(this).attr("data-siteId"),
                        "siteTitle": tr.find('.siteTitle').val(),
                        "contentTitle": tr.find('.contentTitle').val(),
                        "contentId": $(this).attr("data-contentId"),
                        "items": siteFoldersArr
                    });
                }
            });
            //-----------------------------------//
            var data = $('#exportFoldersForm').serialize();
            data += "&folders=" + JSON.stringify(foldersArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportSiteFolders') }}",
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
                    $('#exportFoldersModal').modal('hide');
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
        function restoreSiteItems() {
            event.preventDefault()
            let items = $('#restoreSiteItemsForm .mailboxCheck:checked');
            let itemsArr = [];
            //-----------------------------------//
            items.each(function() {
                let tr = $(this).closest('tr');
                if (itemsArr.filter(e => e.siteId === $(this).attr("data-siteId") && e.contentId === $(this).attr(
                        "data-contentId")).length == 0) {
                    let siteItems = $('#restoreSiteItemsForm .mailboxCheck:checked[data-siteId="' + $(this).attr(
                        "data-siteId") + '"][data-contentId="' + $(this).attr("data-contentId") + '"]');
                    let siteItemsArr = [];
                    siteItems.each(function() {
                        tr = $(this).closest('tr');
                        siteItemsArr.push({
                            "id": $(this).val(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "siteTitle": tr.find('.siteTitle').val(),
                            "contentTitle": tr.find('.contentTitle').val(),
                            "contentId": $(this).attr("data-contentId"),
                            "siteId": $(this).attr("data-siteId"),
                        })
                    });
                    itemsArr.push({
                        "siteId": $(this).attr("data-siteId"),
                        "siteTitle": tr.find('.siteTitle').val(),
                        "contentTitle": tr.find('.contentTitle').val(),
                        "contentId": $(this).attr("data-contentId"),
                        "items": siteItemsArr
                    });
                }
            });
            //-----------------------------------//
            var data = $('#restoreSiteItemsForm').serialize();
            data += "&restorePermissions=" + $("#restoreSiteItemsForm [name='restorePermissions']")[0].checked;
            data += "&sendSharedLinksNotification=" + $("#restoreSiteItemsForm [name='sendSharedLinksNotification']")[0]
                .checked;
            data += "&items=" + JSON.stringify(itemsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreSiteItems') }}",
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
                    $('#restoreSiteItemsModal').modal('hide');
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
        function exportSiteItems() {
            event.preventDefault()
            let docs = $('#exportSiteItemsForm .mailboxCheck:checked');
            let docsArr = [];
            //-----------------------------------//
            docs.each(function() {
                let tr = $(this).closest('tr');
                let folderParentId = $(this).attr('data-siteId');
                docsArr.push({
                    id: $(this).val(),
                    name: tr.find('td:nth-child(2)').html(),
                    siteId: tr.find('.siteId').val(),
                    siteTitle: tr.find('.siteTitle').val(),
                    contentTitle: tr.find('.contentTitle').val(),
                    folderName: tr.find('.folderTitle').val(),
                });
            });
            //-----------------------------------//
            data = $('#exportSiteItemsForm').serialize();
            data += "&docs=" + JSON.stringify(docsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportSiteItems') }}",
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
                    }, 4000);
                    $('#exportSiteItemsModal').modal('hide');
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
