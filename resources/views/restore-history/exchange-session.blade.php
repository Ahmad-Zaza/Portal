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
                    @if (optional($data['restoreOptions'])->toFolder && $data['history']['kind'] == 'Exchange')
                        <div class="col-lg-12 sess-info">
                            <div class="col-lg-6 nopadding sess-title">
                                Restore To Folder:
                            </div>
                            <div class="sess-info-details col-lg-6 nopadding tooltipSpan"
                                title="{{ optional($data['restoreOptions'])->toFolder }}">
                                {{ optional($data['restoreOptions'])->toFolder }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @if ($process == 'Restore' && $data['restoreOptions'] && $data['history']['kind'] == 'Exchange')
                <div class="col">
                    <div class="col-lg-11" style="padding-left: 0px;">
                        <h5 class="txt-blue">Restore Options</h5>
                    </div>
                    <div class="col-lg-12 newJobRow">
                        <div class="rowBorderRight"></div>
                        <div class="rowBorderBottom"></div>
                        <div class="rowBorderleft"></div>
                        <div class="rowBorderUp"></div>
                        @if (optional($data['restoreOptions'])->skipUnresolved)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-10 nopadding sess-title">
                                    Skip Unresolved Items:
                                </div>
                                <div class="sess-info-details col-lg-2 nopadding">
                                    {{ optional($data['restoreOptions'])->skipUnresolved }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->changedItems)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-10 nopadding sess-title">
                                    Restore Changed Items:
                                </div>
                                <div class="sess-info-details col-lg-2 nopadding">
                                    {{ optional($data['restoreOptions'])->changedItems }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->deletedItems)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-10 nopadding sess-title">
                                    Restore Deleted Items:
                                </div>
                                <div class="sess-info-details col-lg-2 nopadding">
                                    {{ optional($data['restoreOptions'])->deletedItems }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->markRestoredAsunread)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-10 nopadding sess-title">
                                    Mark Restored As Unread:
                                </div>
                                <div class="sess-info-details col-lg-2 nopadding">
                                    {{ optional($data['restoreOptions'])->markRestoredAsunread }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            @if ($process == 'Restore' &&
                $data['restoreOptions'] &&
                optional($data['restoreOptions'])->excludeDeletedItems &&
                $data['history']['kind'] == 'Exchange')
                <div class="col">
                    <div class="col-lg-11" style="padding-left: 0px;">
                        <h5 class="txt-blue">Restore Options</h5>
                    </div>
                    <div class="col-lg-12 newJobRow">
                        <div class="rowBorderRight"></div>
                        <div class="rowBorderBottom"></div>
                        <div class="rowBorderleft"></div>
                        <div class="rowBorderUp"></div>
                        @if (optional($data['restoreOptions'])->excludeDrafts)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-10 nopadding sess-title">
                                    Exclude Drafts:
                                </div>
                                <div class="sess-info-details col-lg-2 nopadding">
                                    {{ optional($data['restoreOptions'])->excludeDrafts }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->excludeInplaceHolditems)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-10 nopadding sess-title">
                                    Exclude In-Place Hold Items:
                                </div>
                                <div class="sess-info-details col-lg-2 nopadding">
                                    {{ optional($data['restoreOptions'])->excludeInplaceHolditems }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->excludeDeletedItems)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-10 nopadding sess-title">
                                    Exclude Deleted Items:
                                </div>
                                <div class="sess-info-details col-lg-2 nopadding">
                                    {{ optional($data['restoreOptions'])->excludeDeletedItems }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->excludeLitigationHoldItems)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-10 nopadding sess-title">
                                    Exclude Litigation Hold Items:
                                </div>
                                <div class="sess-info-details col-lg-2 nopadding">
                                    {{ optional($data['restoreOptions'])->excludeLitigationHoldItems }}
                                </div>
                            </div>
                        @endif
                        @if (optional($data['restoreOptions'])->daysNumber)
                            <div class="col-lg-12 sess-info">
                                <div class="col-lg-10 nopadding sess-title">
                                    Finish the Restore of Recent Items Before
                                    Restoring the Remaining Items.
                                </div>
                                <div class="sess-info-details col-lg-2 nopadding">
                                    {{ optional($data['restoreOptions'])->daysNumber }}
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
            $showButton = ($isRestore === 0 && $status != 'In Progress' && $role->hasPermissionTo('exchange_export_again')) || (!$isRestore && in_array($status, ['Canceled', 'Expired', 'Failed']) && $role->hasPermissionTo('exchange_restore_again'));
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
        <div class="modal-dialog modal-lg modal-width modal-folder-width" style="width: 600px">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-108 modal-folder-margin" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p modal-title">Restore Selected Folders</h4>
                        </div>
                    </div>
                    <form id="restoreFolderForm" class="mb-0" onsubmit="restoreFolder(event)">
                        <div class="custom-left-col modal-folder-left">
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
                                    <h5 class="txt-blue mt-0">Mailboxes Folders</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-200p">
                                        <div class="allWidth">
                                            <table id="foldersResultsTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <td>
                                                            <label style="top: 45%;left: 10px;"
                                                                class="checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </td>
                                                        <td>Folder</td>
                                                        <td>Mailbox</td>
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
                        <div class="custom-right-col modal-folder-right">
                            <div class="row hide foldersAnotherOptions_cont targetMailbox">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Target MailBox</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div>
                                            <div class="mb-10">Specify Mailbox to Restore to:</div>
                                            <div class="mb-10 allWidth">
                                                <input type="email"
                                                    class="form-control form_input custom-form-control font-size required"
                                                    name="mailbox" placeholder="MailBox" name="mailbox" required
                                                    autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row hide foldersAnotherOptions_cont targetMailbox">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Target MailBox Folder</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div>
                                            <div class="authCont custom-authCont pt-3 pb-3">
                                                <label class="mr-2 m-0 nowrap">Folder to Restore To:</label>
                                                <div class="radioDiv">
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="folderType" class="folderType"
                                                                value="original" checked>Original
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="folderType" class="folderType"
                                                                value="custom" checked>Another Folder
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-10 pl-2 allWidth">
                                                <input type="text" class="form-control form_input required font-size"
                                                    id="folder" value="" placeholder="Folder" name="folder"
                                                    required autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row hide foldersAnotherOptions_cont">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Options</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">

                                        <div class="row pl-3 mb-1">Specify Restore Options:</div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Restore the following items:</label>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="relative allWidth mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="changedItems" type="checkbox"
                                                            class="form-check-input" />
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
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Deleted Items</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="ml-10">Flag Restored Items as Unread:</label>
                                            <div class="allWidth relative ml-15">
                                                <label style="padding-top: 5px;left: 0px;"
                                                    class="checkbox-container">&nbsp;
                                                    <input name="markRestoredAsunread" type="checkbox"
                                                        class="form-check-input" />
                                                    <span
                                                        style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                        class="check-mark"></span>
                                                </label>
                                                <span style="margin-left: 25px;">Mark Restored As Unread</span>
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
    <div id="restoreMailboxOriginal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg mt-5v" style="width: 1000px!important">
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
                            <h4 class="per-req ml-2p">Restore Selected Mailboxs
                            </h4>
                        </div>
                    </div>
                    <form id="restoreMailboxForm" class="mb-0" onsubmit="restoreMailboxOriginal(event)">
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
                                    <h5 class="txt-blue mt-0">Mailboxes E-Mail</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-175p">
                                        <div class="allWidth mailboxesresultsTable">
                                            <table id="mailboxOriginalTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <td>
                                                            <label style="top: 45%;left: 10px;"
                                                                class="checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </td>
                                                        <td>Mailbox</td>
                                                        <td>E-Mail</td>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> mailboxes selected <span
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
                                    <div class="col-lg-12 customBorder pb-3 pt-3 h-392p">

                                        <div class="row pl-3 mb-10">Specify Restore Options:</div>
                                        <div class="row">
                                            <div class="pl-3">
                                                <label>Exclude the Following Items:</label>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="relative  mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="excludeDrafts" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Drafts</span>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="allWidth relative  mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="excludeDeletedItems" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Deleted Items</span>
                                                </div>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="allWidth relative  mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="excludeInplaceHolditems" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">In-Place Hold Items</span>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="allWidth relative  mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="excludeLitigationHoldItems" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Litigation Hold Items</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="pr-0 pl-3 mt-3">
                                                <label>Restore the following items:</label>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="relative allWidth mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="changedItems" type="checkbox"
                                                            class="form-check-input" />
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
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Deleted Items</span>
                                                </div>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="relative mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="skipUnresolved" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Skip Unresolved Items</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <label class="mt-3 ml-10">Flag Restored Items as Unread:</label>
                                            <div class="allWidth relative mb-3 ml-15">
                                                <label style="padding-top: 5px;left: 0px;"
                                                    class="checkbox-container">&nbsp;
                                                    <input name="markRestoredAsunread" type="checkbox"
                                                        class="form-check-input" />
                                                    <span
                                                        style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                        class="check-mark"></span>
                                                </label>
                                                <span style="margin-left: 25px;">Mark Restored As Unread</span>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-12 pl-3 pr-3">
                                                <div class="allWidth inline-flex">
                                                    <div class="allWidth relative ml-5p">
                                                        <label style="padding-top: 5px;left: 0px;top:8px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="RecentItemRestorePeriod" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <p class="mb-0"
                                                            style="margin-left: 25px;font-size:13px;color:white">
                                                            Finish the Restore of Recent Items Before
                                                            Restoring the Remaining Items.
                                                        </p>
                                                        <div class="flex">
                                                            <span style="margin-left: 25px;">Restore items for the
                                                                last</span>&nbsp;
                                                            <input name="daysNumber" type="number" placeholder="Days"
                                                                min='0'
                                                                class="minInput custom-minInput ml-1 mr-1 days-color daysNumber" />
                                                            &nbsp;<span>first.</span>
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
    <div id="exportMailboxModal" class="modal modal-center" role="dialog">
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
                            <h4 class="per-req ml-2p">Export Selected Mailboxs
                            </h4>
                        </div>
                    </div>
                    <form id="exportMailboxForm" class="mb-0" onsubmit="exportMailbox(event)">
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
                                <h5 class="txt-blue mt-0">Mailboxes E-Mail</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder">
                                    <div class="allWidth mailboxesresultsTable">
                                        <table id="exportMailboxTable"
                                            class="stripe table table-striped table-dark display nowrap allWidth">
                                            <thead class="table-th">
                                                <tr>
                                                    <td>
                                                        <label style="top: 45%;left: 10px;"
                                                            class="checkbox-container checkbox-search">
                                                            <input type="checkbox" checked class="form-check-input">
                                                            <span class="tree-checkBox check-mark"></span>
                                                        </label>
                                                    </td>
                                                    <td>Mailbox</td>
                                                    <td>E-Mail</td>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">
                                                        <span class="boxesCount"></span> mailboxes selected <span
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
                            <div class="input-form-70 mb-1">
                                <h5 class="txt-blue mt-0">Export Options</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder pb-3 pt-3">
                                    <div>
                                        <div class="row">
                                            <div class="col-md-12 pr-4 pl-4">
                                                <div class="relative allWidth">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="enablePstSizeLimit" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-12px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 21px;">Limit PST size to:</span>
                                                    <input type="number" name="sizeLimit"
                                                        class="minInput mr-1 ml-1 custom-minInput days-color"
                                                        placeholder="GB">
                                                    <span> (additional PST files will be created as needed)</span>
                                                </div>
                                            </div>
                                        </div>
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
    <div id="exportFolderModal" class="modal modal-center" role="dialog">
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
                            <h4 class="per-req ml-2p">Export Selected Folders</h4>
                        </div>
                    </div>
                    <form id="exportFolderForm" class="mb-0" onsubmit="exportFolder(event)">
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
                        <div class="row">
                            <div class="input-form-70 mb-1">
                                <h5 class="txt-blue mt-0">Mailboxes Folders</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder">
                                    <div class="allWidth">
                                        <table id="exportFolderTable"
                                            class="stripe table table-striped table-dark display nowrap allWidth">
                                            <thead class="table-th">
                                                <tr>
                                                    <td>
                                                        <label style="top: 45%;left: 10px;"
                                                            class="checkbox-container checkbox-search">
                                                            <input type="checkbox" checked class="form-check-input">
                                                            <span class="tree-checkBox check-mark"></span>
                                                        </label>
                                                    </td>
                                                    <td>Folder</td>
                                                    <td>Mailbox</td>
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
                            <div class="input-form-70 mb-1">
                                <h5 class="txt-blue mt-0">Export Options</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder pb-3 pt-3">
                                    <div>
                                        <div class="row">
                                            <div class="col-md-12 pr-4 pl-4">
                                                <div class="relative allWidth">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="enablePstSizeLimit" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-12px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 21px;">Limit PST size to:</span>
                                                    <input type="number" name="sizeLimit"
                                                        class="minInput mr-1 ml-1 custom-minInput days-color"
                                                        placeholder="GB">
                                                    <span> (additional PST files will be created as needed)</span>
                                                </div>
                                            </div>
                                        </div>
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
    <div id="exportItemModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Export Selected Items</h4>
                        </div>
                    </div>
                    <form id="exportItemForm" class="mb-0" onsubmit="exportFolderItem(event)">
                        <input type="hidden" class="restoreType" name="restoreType" />
                        <input type="hidden" class="items" name="items" />
                        <input type="hidden" class="mailboxId" name="mailboxId" />
                        <input type="hidden" class="mailboxName" name="mailboxName" />
                        <input type="hidden" class="folderTitle" name="folderTitle" />
                        <input type="hidden" class="parentName" name="parentName" />
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
                                <h5 class="txt-blue mt-0">Mailbox Items</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder pb-3">
                                    <table id="exportItemTable"
                                        class="stripe table table-striped table-dark display nowrap allWidth">
                                        <thead class="table-th">
                                            <tr>
                                                <td>
                                                    <label style="top: 45%;left: 10px;"
                                                        class="checkbox-container checkbox-search">
                                                        <input type="checkbox" checked class="form-check-input">
                                                        <span class="tree-checkBox check-mark"></span>
                                                    </label>
                                                </td>
                                                <td>Folder</td>
                                                <td>Name</td>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3">
                                                    <span class="boxesCount"></span> items selected <span
                                                        class="unresolvedCount"></span>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
    <div id="restoreMailboxAnother" class="modal modal-center" role="dialog">
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
                            <h4 class="per-req ml-2p">Restore Selected Mailboxs To Another Location
                            </h4>
                        </div>
                    </div>
                    <form id="restoreMailboxAnotherForm" class="mb-0" onsubmit="restoreMailboxAnother(event)">
                        <div class="custom-left-col">
                            <input type="hidden" class="mailBoxes" name="mailBoxes" />
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
                                <div class="input-form-70 mb-1 ">
                                    <h5 class="txt-blue mt-0">Mailboxes E-Mail</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-180p">
                                        <div class="allWidth mailboxesresultsTable">
                                            <table id="mailboxAnotherTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth mailboxesresultsTable">
                                                <thead class="table-th">
                                                    <tr>
                                                        <td>
                                                            <label style="top: 45%;left: 10px;"
                                                                class="checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </td>
                                                        <td>Mailbox</td>
                                                        <td>E-Mail</td>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> mailboxes selected <span
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
                                    <h5 class="txt-blue mt-0">Target MailBox</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div>
                                            <div class="mb-10">Specify Mailbox to Restore to:</div>
                                            <div class="mb-10 allWidth">
                                                {{-- <select name="mailbox" required style="width:100%"
                                                        class="required js-data-example-ajax users">
                                                    <option value="">Select User</option>
                                                </select> --}}
                                                <input type="email"
                                                    class="form-control form_input custom-form-control font-size required"
                                                    name="mailbox" placeholder="MailBox" name="mailbox" required
                                                    autocomplete="off" />
                                            </div>
                                            <div class="allWidth">
                                                <input type="text"
                                                    class="form-control form_input required custom-form-control font-size"
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

                                        <div class="row pl-3 mb-10">Specify Restore Options:</div>
                                        <div class="row">
                                            <div class="pl-3">
                                                <label>Exclude the Following Items:</label>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="relative  mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="excludeDrafts" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Drafts</span>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="allWidth relative  mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="excludeInplaceHolditems" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">In-Place Hold Items</span>
                                                </div>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="allWidth relative  mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="excludeDeletedItems" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Deleted Items</span>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="allWidth relative  mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="excludeLitigationHoldItems" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Litigation Hold Items</span>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="pr-0 pl-3 ml-15">
                                                    <label>Restore the following items:</label>
                                                </div>
                                                <div class="w-100"></div>
                                                <div class="col">
                                                    <div class="relative allWidth mb-2 ml-15">
                                                        <label style="padding-top: 5px;left: 0px;"
                                                            class="checkbox-container">&nbsp;
                                                            <input name="changedItems" type="checkbox"
                                                                class="form-check-input" />
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
                                                                class="form-check-input" />
                                                            <span
                                                                style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        <span style="margin-left: 25px;">Deleted Items</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <label class="mt-3 ml-25">Flag Restored Items as Unread:</label>
                                                <div class="allWidth relative ml-30">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="markRestoredAsunread" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Mark Restored As Unread</span>
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
    <div id="restoreItem" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg modal-width modal-item-width" style="width: 600px">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-100 modal-item-margin" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p modal-title">Restore Selected Items</h4>
                        </div>
                    </div>
                    <form id="restoreItemForm" class="mb-0" onsubmit="restoreItem(event)">
                        <div class="custom-left-col modal-item-left">
                            <input type="hidden" class="restoreType" name="restoreType" />
                            <input type="hidden" class="mailboxId" name="mailboxId" />
                            <input type="hidden" class="folderTitle" name="folderTitle" />
                            <input type="hidden" class="parentName" name="parentName" />
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
                                    <h5 class="txt-blue mt-0">Mailbox Items</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder h-211p">
                                        <table id="mailboxItemsTable"
                                            class="stripe table table-striped table-dark display nowrap allWidth">
                                            <thead class="table-th">
                                                <tr>
                                                    <td>
                                                        <label style="top: 45%;left: 10px;"
                                                            class="checkbox-container checkbox-search">
                                                            <input type="checkbox" checked class="form-check-input">
                                                            <span class="tree-checkBox check-mark"></span>
                                                        </label>
                                                    </td>
                                                    <td>Folder</td>
                                                    <td>Name</td>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">
                                                        <span class="boxesCount"></span> items selected <span
                                                            class="unresolvedCount"></span>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom-right-col modal-item-right">
                            <div class="row hide itemsAnotherOptions_cont targetMailbox">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Target MailBox</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div>
                                            <div class="mb-10">Specify Mailbox to Restore to:</div>
                                            <div class="mb-10 allWidth">
                                                {{-- <select name="mailbox" required style="width:100%"
                                                        class="required js-data-example-ajax users">
                                                    <option value="">Select User</option>
                                                </select> --}}
                                                <input type="email"
                                                    class="form-control form_input custom-form-control font-size required"
                                                    name="mailbox" placeholder="MailBox" name="mailbox" required
                                                    autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row hide itemsAnotherOptions_cont targetMailbox">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Target MailBox Folder</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div>
                                            <div class="authCont custom-authCont pt-3 pb-3">
                                                <label class="mr-2 m-0 nowrap">Folder to Restore To:</label>
                                                <div class="radioDiv">
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="folderType" class="folderType"
                                                                value="original" checked>Original
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="folderType" class="folderType"
                                                                value="custom" checked>Another Folder
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-10 pl-2 allWidth">
                                                <input type="text" class="form-control form_input required font-size"
                                                    id="folder" value="" placeholder="Folder"
                                                    name="folder" required autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row hide itemsAnotherOptions_cont">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Options</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">

                                        <div class="row pl-3 mb-10">Specify Restore Options:</div>
                                        <div class="row">
                                            <div class="pr-0 pl-3">
                                                <label>Restore the following items:</label>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="relative allWidth mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="changedItems" type="checkbox"
                                                            class="form-check-input" />
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
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Deleted Items</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="mt-3 ml-25">Flag Restored Items as Unread:</label>
                                                <div class="allWidth relative ml-30">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="markRestoredAsunread" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Mark Restored As Unread</span>
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
                    <div class="row" id="Status-Section">
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
        var jobType;
        var jobTime;
        var showDeleted;
        var showVersions;
        var jobId;
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
            $('form [name="enablePstSizeLimit"]').change(function() {
                if ($(this).prop('checked') == true) {
                    $(this).closest('form').find('[name="sizeLimit"]').attr('required', 'required');
                } else {
                    $(this).closest('form').find('[name="sizeLimit"]').removeAttr('required');
                }
            }).change();
            //---------------------------------------//
            let detailsTable = $('#detailsTable').DataTable({
                'ajax': {
                    "type": "GET",
                    "url": "{{ url('getRestoreDetails', [$data['kind'], $data['historyId']]) }}",
                    "dataSrc": function(json) {
                        @if ($data['history']['status'] == 'In Progress')
                            CheckItemstable();
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
                    "dataType": "json"
                },
                'columns': [{
                        "class": "left-col",
                        "data": null,
                        render: function(data) {
                            if ("{{ $data['history']->type }}" == "item") {
                                let resultsArr = JSON.parse(data.item_id);
                                let str = '<ul class="p-0 m-0">';
                                resultsArr.forEach((e) => {
                                    str +=
                                        '<li style="max-width:250px" class="li-session-details ellipsis" title="' +
                                        e.name + '">' +
                                        e.name + '</li>';
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
                            if (data.error_response)
                                return '<div class="ellipsisContent" title="' + data
                                    .error_response + '">' + data.error_response + '<div>';
                            if (data.result && data.result != "null") {
                                return getExchangeResult(data.result);
                            } else if (isRestore == 0 && data.status == 'Success' &&
                                "{{ $data['history']['status'] }}" != "Expired") {
                                @if ($role->hasPermissionTo('exchange_download_exported_files'))
                                    return '<div><img data-id="' + data.id +
                                        '" class="tableIcone hand downloadExportedFile" style="width: 13px; margin-right:0;" src="/svg/download\.svg " title="Download"></div>';
                                @endif
                            }
                            return data.error_response;
                        }
                    }, {
                        "data": null,
                        "class": "text-center",
                        render: function(data) {
                            var temp = "{{ $data['history']['sub_type'] }}";
                            var isRestore = (temp.match(/Restore/g) || []).length;
                            if (data.status == 'Failed') {
                                if (isRestore === 0) {
                                    @if ($role->hasPermissionTo('exchange_export_again'))
                                        return '<div><img data-id="' + data.id +
                                            '" class="tableIcone hand restoreFailedAgain" style="width: 13px; margin-right:0;" src="/svg/restore\.svg " title="Retry"></div>';
                                    @endif
                                } else {
                                    @if ($role->hasPermissionTo('exchange_restore_again'))
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
                        if (data.json[0].exported_file_size > 0) {
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
            $('.restoreAgain').unbind('click').click(function() {
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
            $('#exportFolderTable').DataTable({
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
                                '" class="form-check-input mailboxCheck" data-mailbox-id="' + data
                                .mailboxId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "mailboxName"
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
            $('#exportMailboxTable').DataTable({
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
                        "data": "email",
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
            //---------------------------------------//
            $('#exportItemTable').DataTable({
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
                                '" class="form-check-input mailboxCheck" mailboxId="' + data
                                .mailboxId + '" mailboxTitle="' + data.mailboxTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "parentName",
                        "width": "40%"
                    },
                    {
                        "data": "name",
                        "width": "60%"
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
            $('#mailboxesTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('hasHTML') && $(this).children().length == 0)
                            $(this).attr('title', $(this).html());
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "render": function(data) {
                            return '<input type="hidden" value="' + data.id + ' name="mailboxId">';
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
                "scrollY": "200px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $('#foldersTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('hasHTML') && $(this).children().length == 0)
                            $(this).attr('title', $(this).html());
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "render": function(data) {
                            return '<input type="hidden" value="' + data.id + ' name="folderId">';
                        }
                    },
                    {
                        "data": "folder",
                        "width": "50%"
                    },
                    {
                        "data": "mailbox",
                        "width": "50%"
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
                            if (data.resolved)
                                return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                    '<input type="checkbox" checked value="' + data.id +
                                    '" class="form-check-input mailboxCheck" data-mailbox-id="' +
                                    data
                                    .mailboxId + '">' +
                                    '<span class="tree-checkBox check-mark"></span>' +
                                    '</label>';
                            return '';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "mailboxName"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "130px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $('#mailboxOriginalTable').DataTable({
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
                            if (data.resolved)
                                return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                    '<input type="checkbox" checked value="' + data.id +
                                    '" class="form-check-input mailboxCheck">' +
                                    '<span class="tree-checkBox check-mark"></span>' +
                                    '</label>';
                            return '';
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
                "scrollY": "102px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $('#mailboxAnotherTable').DataTable({
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
                        "data": "email",
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

            $('#mailboxItemsTable').DataTable({
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
                                '" class="form-check-input mailboxCheck" mailboxId="' + data
                                .mailboxId + '" mailboxTitle="' + data.mailboxTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "parentName",
                        "width": "40%"
                    },
                    {
                        "data": "name",
                        "width": "60%"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "140px",
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
                        'disabled').val('');
                    $('.itemsAnotherOptions_cont.targetMailbox input[name="folder"]').attr('disabled',
                        'disabled').val('');
                } else {
                    $('.foldersAnotherOptions_cont.targetMailbox input[name="folder"]').removeAttr(
                        'disabled');
                    $('.itemsAnotherOptions_cont.targetMailbox input[name="folder"]').removeAttr(
                        'disabled');
                }
            });
            $('input[name="folderType"]').change();
            //---------------------------------------//
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
        function getExchangeResult(result) {
            let resultArr = JSON.parse(result);
            let str = '<div class="col-lg-12 sess-info text-left" style="max-width:250px">';
            str += '<span class="col-lg-6 sess-title">Warning:</span>';
            str += '<span class="col-lg-6 sess-title">' + (resultArr.warnings ? resultArr.warnings.length : 0) + '</span>';
            str += '<span class="col-lg-6 sess-title">Failed Items:</span>';
            str += '<span class="col-lg-6 sess-title">' + resultArr.failedItemsCount + '</span>';
            str += '<span class="col-lg-6 sess-title">Merged Items:</span>';
            str += '<span class="col-lg-6 sess-title">' + resultArr.mergedItemsCount + '</span>';
            str += '<span class="col-lg-6 sess-title">Created Items:</span>';
            str += '<span class="col-lg-6 sess-title">' + resultArr.createdItemsCount + '</span>';
            str += '<span class="col-lg-6 sess-title">Skipped Items:</span>';
            str += '<span class="col-lg-6 sess-title">' + resultArr.skippedItemsCount + '</span>';
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
                    //--------------------------//
                    $('.jobId').val(data.backup_job_id);
                    $('.jobType').val(data.restore_point_type);
                    $('.jobTime').val(data.restore_point_time);
                    $('.showDeleted').val(data.is_restore_point_show_deleted == 1);
                    $('.showVersions').val(data.is_restore_point_show_version == 1);
                    if (type == "mailbox") {
                        options = JSON.parse(data.options);
                        let mailBoxes = data.details;
                        let itemsCount = mailBoxes.length;
                        tableData = [];
                        mailBoxes.forEach((e) => {
                            //-------------------------------------//
                            let resolved = true;
                            let unresolvedCount = 0;
                            if (!options.toMailBox) {
                                if (checkResolvedMail(e.item_parent_name)) {
                                    unresolvedCount++;
                                    itemsCount--;
                                    resolved = false;
                                }
                            }
                            //-------------------------------------//
                            if (state == 'all' || e.id == detailsId)
                                tableData.push({
                                    id: e.item_id,
                                    name: e.item_name,
                                    resolved: resolved,
                                    email: e.item_parent_name
                                });
                        });
                        let mailboxCount = tableData.length;
                        //-------------------------------------------//
                        if (options.toMailBox) {
                            $('#mailboxAnotherTable_wrapper').find('.boxesCount').html(
                                mailboxCount);
                            $('#mailboxAnotherTable').DataTable().clear().draw();
                            $('#mailboxAnotherTable').DataTable().rows.add(tableData); // Add new data
                            $('#mailboxAnotherTable').DataTable().columns.adjust()
                                .draw(); // Redraw the DataTable
                            //------------------------------------------------//
                            checkTableCount('mailboxAnotherTable');
                            adjustTable();
                            $("#mailboxAnotherTable").DataTable().draw();
                            //------------------------------------------------//
                        } else {
                            $('#mailboxOriginalTable_wrapper').find('.boxesCount').html(
                                mailboxCount);
                            $('#mailboxOriginalTable').DataTable().clear().draw();
                            $('#mailboxOriginalTable').DataTable().rows.add(tableData); // Add new data
                            $('#mailboxOriginalTable').DataTable().columns.adjust()
                                .draw(); // Redraw the DataTable
                            //------------------------------------------------//
                            checkTableCount('mailboxOriginalTable');
                            adjustTable();
                            $("#mailboxOriginalTable").DataTable().draw();
                            //------------------------------------------------//
                        }
                        //------------------------------------------------//
                        if (options.toMailBox) {
                            $.each(options, function(key, value) {
                                if (key == "toMailBox") {
                                    $('#restoreMailboxAnotherForm [name="mailbox"]').val(
                                        value);
                                } else if (key == "toFolder") {
                                    $('#restoreMailboxAnotherForm input[name="folder"]').val(value);
                                } else if (value == 'true')
                                    $('#restoreMailboxAnotherForm input[name="' + key + '"]').prop(
                                        'checked', 'checked');
                                else
                                    $('#restoreMailboxAnotherForm input[name="' + key + '"]')
                                    .removeProp('checked');
                            });
                            $("#exportMailboxForm .mailboxesresultsTable table").DataTable().clear().draw();
                            $("#restoreMailboxOriginal .mailboxesresultsTable table").DataTable().clear()
                                .draw();
                            $('#restoreMailboxAnother').find(".refreshDeviceCode").click();
                            $('#restoreMailboxAnother').modal('show');
                        } else {
                            $.each(options, function(key, value) {
                                if (key == "daysNumber") {
                                    $('#restoreMailboxForm input[name="daysNumber"]').val(value);
                                } else if (value == 'true')
                                    $('#restoreMailboxForm input[name="' + key + '"]').prop(
                                        'checked', 'checked');
                                else
                                    $('#restoreMailboxForm input[name="' + key + '"]')
                                    .removeProp('checked');
                            });
                            $("#exportMailboxForm .mailboxesresultsTable table").DataTable().clear().draw();
                            $("#restoreMailboxAnother .mailboxesresultsTable table").DataTable().clear().draw();
                            $('#restoreMailboxOriginal').modal('show');
                        }
                        $('#restoreMailboxForm').find(".refreshDeviceCode").click();
                    } else if (type == "folder") {
                        //-------------------------------------------------//
                        tableData = [];
                        options = JSON.parse(data.options);
                        let folders = data.details;
                        folders.forEach((e) => {
                            let resolved = true;
                            let unresolvedCount = 0;
                            if (state == 'all' || e.id == detailsId)
                                tableData.push({
                                    "id": e.item_id,
                                    "name": e.item_name,
                                    "mailboxId": e.item_parent_id,
                                    "mailboxName": e.item_parent_name,
                                    resolved: resolved
                                });
                        });
                        let foldersCount = tableData.length;
                        $('#foldersResultsTable_wrapper').find('.boxesCount').html(foldersCount);
                        $('#foldersResultsTable').DataTable().clear().draw();
                        $('#foldersResultsTable').DataTable().rows.add(tableData); // Add new data
                        $('#foldersResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('foldersResultsTable');
                        adjustTable();
                        $("#foldersResultsTable").DataTable().draw();
                        //-------------------------------------------------//
                        if (options) {
                            $.each(options, function(key, value) {
                                if (key == "toMailBox") {
                                    $('#restoreFolderForm [name="mailbox"]').val(value);
                                } else if (key == "toFolder") {
                                    if (!value) {
                                        $('#restoreFolderForm input[name="folder"]').val('');
                                        $('#restoreFolderForm .folderType[value="original"]').prop(
                                            'checked', true);
                                        $('#restoreFolderForm .targetMailbox input[name="folder"]')
                                            .attr('disabled', 'disabled');
                                    } else {
                                        $('#restoreFolderForm .folderType[value="custom"]').prop(
                                            'checked', true);
                                        $('#restoreFolderForm input[name="folder"]').val(value);
                                        $('#restoreFolderForm .targetMailbox input[name="folder"]')
                                            .removeAttr('disabled');
                                    }
                                } else if (value == 'true')
                                    $('#restoreFolderForm input[name="' + key + '"]').prop(
                                        'checked', 'checked');
                                else if (key == "folderType") {
                                    $('#restoreFolderForm [name="folderType"][value="' + value + '"]')
                                        .prop("checked", "checked");
                                    if (value == "original") {
                                        $('#restoreFolderForm [name="folder"]').val("").attr('disabled',
                                            'disabled');
                                    } else {
                                        $('#restoreFolderForm [name="folder"]').removeAttr('disabled');
                                    }
                                } else {
                                    $('#restoreFolderForm input[name="' + key + '"]')
                                        .removeProp('checked');
                                }
                            });
                            $('.modal-folder-width').addClass('modal-width');
                            $('.modal-folder-left').addClass('custom-left-col');
                            $('.modal-folder-right').addClass('custom-right-col');
                            $('.modal-folder-margin').addClass('ml-108');
                            $('.modal-h-275p').addClass('h-275p');
                            $('.foldersAnotherOptions_cont').removeClass('hide');
                            $('.foldersAnotherOptions_cont .required').attr('required',
                                'required');
                            $('#restoreFolder').find('.modal-title').html(
                            'Restore Folders to Another Location');
                            $('#restoreFolder').find('.restoreType').val('another');
                        } else {
                            $.each(options, function(key, value) {
                                if (value == 'true')
                                    $('#restoreFolderForm input[name="' + key + '"]').prop(
                                        'checked', 'checked');
                                else
                                    $('#restoreFolderForm input[name="' + key + '"]')
                                    .removeProp('checked');
                            });
                            $('.modal-folder-width').removeClass('modal-width');
                            $('.modal-folder-left').removeClass('custom-left-col');
                            $('.modal-folder-right').removeClass('custom-right-col');
                            $('.modal-folder-margin').removeClass('ml-108');
                            $('.modal-h-275p').removeClass('h-275p');
                            $('.foldersAnotherOptions_cont').addClass('hide');
                            $('.foldersAnotherOptions_cont .required').removeAttr('required');
                            $('#restoreFolder').find('.modal-title').html(
                                'Restore Folders to Original Location');
                            $('#restoreFolder').find('.restoreType').val('original');
                        }
                        //-------------------------------------------------//
                        $('#restoreFolder').find(".refreshDeviceCode").click();
                        $('#restoreFolder').modal('show');
                    } else if (type == "item") {
                        let options = JSON.parse(data.options);
                        //--------------------//
                        let detailsArr = data.details;
                        detailsArr.forEach(function(e) {
                            if (state == 'all' || e.status == "Failed") {
                                let tempArr = JSON.parse(e.item_id);
                                tempArr.forEach(function(e1) {
                                    tableData.push({
                                        "id": e1.id,
                                        "parentName": e1.parentName,
                                        "name": e1.name,
                                        "mailboxId": e.item_parent_id,
                                        "mailboxTitle": e.item_parent_name,
                                    });
                                });
                            }
                        });
                        let itemsCount = tableData.length;
                        //--------------------//
                        $('#mailboxItemsTable_wrapper').find('.boxesCount').html(itemsCount);
                        $('#mailboxItemsTable').DataTable().clear().draw();
                        $('#mailboxItemsTable').DataTable().rows.add(tableData); // Add new data
                        $('#mailboxItemsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('mailboxItemsTable');
                        adjustTable();
                        $("#mailboxItemsTable").DataTable().draw();
                        //--------------------//
                        $('#restoreItem').find('.restoreItemsWarning').addClass('hide');
                        $('#restoreItem').find('[type="submit"]').removeAttr('disabled');
                        //--------------------//
                        if (options) {
                            $.each(options, function(key, value) {
                                if (key == "toMailBox") {
                                    $('#restoreItemForm [name="mailbox"]').val(value);
                                } else if (key == "toFolder") {
                                    if (!value) {
                                        $('#restoreItemForm input[name="folder"]').val('');
                                        $('#restoreItemForm .folderType[value="original"]').prop(
                                            'checked', true);
                                        $('#restoreItemForm .targetMailbox input[name="folder"]').attr(
                                            'disabled', 'disabled');
                                    } else {
                                        $('#restoreItemForm .folderType[value="custom"]').prop(
                                            'checked', true);
                                        $('#restoreItemForm .targetMailbox input[name="folder"]')
                                            .removeAttr('disabled');
                                        $('#restoreItemForm input[name="folder"]').val(value);
                                    }
                                } else if (value == 'true')
                                    $('#restoreItemForm input[name="' + key + '"]').prop(
                                        'checked', 'checked');
                                else if (key == "folderType") {
                                    $('#restoreItemForm [name="folderType"][value="' + value + '"]')
                                        .prop("checked", "checked");
                                    if (value == "original") {
                                        $('#restoreItemForm [name="folder"]').val("").attr('disabled',
                                            'disabled');
                                    } else {
                                        $('#restoreItemForm [name="folder"]').removeAttr('disabled');
                                    }
                                } else {
                                    $('#restoreItemForm input[name="' + key + '"]')
                                        .removeProp('checked');
                                }
                            });
                            $('.modal-item-width').addClass('modal-width');
                            $('.modal-item-left').addClass('custom-left-col');
                            $('.modal-item-right').addClass('custom-right-col');
                            $('.modal-item-margin').addClass('ml-100');
                            $('.itemsAnotherOptions_cont').removeClass('hide');
                            $('.itemsAnotherOptions_cont .required').attr('required', 'required');
                            $('#restoreItem').find('.modal-title').html('Restore Items to Another Location');
                            $('#restoreItem').find('.restoreType').val('another');
                        } else {
                            $('.modal-item-width').removeClass('modal-width');
                            $('.modal-item-left').removeClass('custom-left-col');
                            $('.modal-item-right').removeClass('custom-right-col');
                            $('.modal-item-margin').removeClass('ml-100');
                            $('.itemsAnotherOptions_cont').addClass('hide');
                            $('.itemsAnotherOptions_cont .required').removeAttr('required');
                            $('#restoreItem').find('.modal-title').html('Restore Items to Original Location');
                            $('#restoreItem').find('.restoreType').val('original');
                            //--------------------//
                        }
                        $('#restoreItem').find(".refreshDeviceCode").click();
                        $('#restoreItem').modal('show');
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
                    //------------------------------//
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    //------------------------------//
                    $('.jobId').val(data.backup_job_id);
                    $('.jobType').val(data.restore_point_type);
                    $('.jobTime').val(data.restore_point_time);
                    $('.showDeleted').val(data.is_restore_point_show_deleted == 1);
                    $('.showVersions').val(data.is_restore_point_show_version == 1);
                    if (type == "mailbox") {
                        options = JSON.parse(data.options);
                        let mailBoxes = data.details;
                        tableData = [];
                        mailBoxes.forEach((e) => {
                            if (state == 'all' || e.id == detailsId)
                                tableData.push({
                                    id: e.item_id,
                                    name: e.item_name,
                                    email: e.item_parent_name
                                });
                        });
                        let mailboxCount = tableData.length;
                        $('#exportMailboxTable').find('.boxesCount').html(
                            mailboxCount);
                        $('#exportMailboxTable').DataTable().clear().draw();
                        $('#exportMailboxTable').DataTable().rows.add(tableData); // Add new data
                        $('#exportMailboxTable').DataTable().columns.adjust().draw(); // Redraw the DataTable
                        //------------------------------------------------//
                        checkTableCount('exportMailboxTable');
                        adjustTable();
                        $("#exportMailboxTable").DataTable().draw();
                        $("#restoreMailboxAnother .mailboxesresultsTable table").DataTable().clear().draw();
                        $("#restoreMailboxOriginal .mailboxesresultsTable table").DataTable().clear().draw();
                        //------------------------------------------------//
                        if (options.enablePstSizeLimit == "true") {
                            $('#exportMailboxForm').find('[name="enablePstSizeLimit"]').attr('checked',
                                "checked");
                            $('#exportMailboxForm').find('[name="sizeLimit"]').attr("required", "required");
                        } else {
                            $('#exportMailboxForm').find('[name="enablePstSizeLimit"]').removeAttr('checked');
                            $('#exportMailboxForm').find('[name="sizeLimit"]').removeAttr("required");
                        }
                        if (options.sizeLimit) {
                            $('#exportMailboxForm').find('[name="sizeLimit"]').val(options.sizeLimit);
                        }
                        //------------------------------------------------//
                        $('#exportMailboxModal').modal('show');
                        //------------------------------------------------//
                    } else if (type == "folder") {
                        //-------------------------------------------------//
                        tableData = [];
                        options = JSON.parse(data.options);
                        let folders = data.details;
                        folders.forEach((e) => {
                            if (state == 'all' || e.id == detailsId)
                                tableData.push({
                                    "id": e.item_id,
                                    "name": e.item_name,
                                    "mailboxId": e.item_parent_id,
                                    "mailboxName": e.item_parent_name
                                });
                        });
                        let foldersCount = tableData.length;
                        $('#exportFolderTable_wrapper').find('.boxesCount').html(foldersCount);
                        $('#exportFolderTable').DataTable().clear().draw();
                        $('#exportFolderTable').DataTable().rows.add(tableData); // Add new data
                        $('#exportFolderTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('exportFolderTable');
                        adjustTable();
                        $("#exportFolderTable").DataTable().draw();
                        //-------------------------------------------------//
                        if (options.enablePstSizeLimit == "true") {
                            $('#exportFolderForm').find('[name="enablePstSizeLimit"]').attr('checked',
                                "checked");
                            $('#exportFolderForm').find('[name="sizeLimit"]').attr("required", "required");
                        } else {
                            $('#exportFolderForm').find('[name="enablePstSizeLimit"]').removeAttr('checked');
                            $('#exportFolderForm').find('[name="sizeLimit"]').removeAttr("required");
                        }
                        if (options.sizeLimit) {
                            $('#exportFolderForm').find('[name="sizeLimit"]').val(options.sizeLimit);
                        }
                        //-------------------------------------------------//
                        $('#exportFolderModal').modal('show');
                    } else if (type == "item") {
                        tableData = [];
                        options = JSON.parse(data.options);
                        let itemsCount = tableData.length;
                        //--------------------//
                        let detailsArr = data.details;
                        detailsArr.forEach(function(e) {
                            if (state == 'all' || e.status == "Failed") {
                                let tempArr = JSON.parse(e.item_id);
                                itemsCount += tempArr.length;
                                tempArr.forEach(function(e1) {
                                    tableData.push({
                                        "id": e1.id,
                                        "parentName": e1.parentName,
                                        "name": e1.name,
                                        "mailboxId": e.item_parent_id,
                                        "mailboxTitle": e.item_parent_name,
                                    });
                                });
                            }
                        });
                        //--------------------//
                        $('#exportItemTable_wrapper').find('.boxesCount').html(itemsCount);
                        $('#exportItemTable').DataTable().clear().draw();
                        $('#exportItemTable').DataTable().rows.add(tableData); // Add new data
                        $('#exportItemTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

                        checkTableCount('exportItemTable');
                        adjustTable();
                        $("#exportItemTable").DataTable().draw();
                        //--------------------//
                        $('#exportItemModal').modal('show');
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
        function expireRestoreModal(id) {
            $('#confirmationModal').find('.historyId').val(id);
            $('#confirmationModal').find('.confirmTitle').html('Force Expire Job');
            $('#confirmationModal').find('.confirmButton').attr('onClick', 'forceExpire()');
            $('#confirmationModal').modal('show');
        }
        //-------------------------------------------------------------//
        function cancelRestoreModal(id) {
            $('#confirmationModal').find('.historyId').val(id);
            $('#confirmationModal').find('.confirmTitle').html('Cancel Restore Job');
            $('#confirmationModal').find('.confirmButton').attr('onClick', 'cancelRestore()');
            $('#confirmationModal').modal('show');
        }
        //-------------------------------------------------------------//
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
        //-------------------------------------------------------------//
        function onTableResultChange(tableName) {
            let folders = $('#' + tableName + '_wrapper').find('tbody .form-check-input:checked');
            let foldersCount = folders.length;
            let unresolvedCount = 0;
            $('#' + tableName + '_wrapper').find('.boxesCount').html(foldersCount);
        }
        //-------------------------------------------------------------//
        function cancelRestore() {
            let id = $('#confirmationModal').find('.historyId').val();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('cancelRestore') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    $('#confirmationModal').modal('hide');
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
        }
        //-------------------------------------------------------------//
        function forceExpire() {
            let id = $('#confirmationModal').find('.historyId').val();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('forceExpire') }}/exchange",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    $('#confirmationModal').modal('hide');
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
        }
        //-------------------------------------------------------------//
        function downloadExportedFiles(id) {
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "GET",
                url: "{{ url('downloadExportedFile') }}/exchange/" + id,
                data: {},
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    //---------------------------------//
                    $('#detailsTable').DataTable().ajax.reload();
                    CheckItemstable(true);
                    //---------------------------------//
                    window.open(data, '_blank');
                    //---------------------------------//
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
        function showSuccessMessage(message) {
            $(".success-oper .success-msg").html(message);
            $(".success-oper").css("display", "block");
            setTimeout(function() {
                $(".success-oper").css("display", "none");
                window.location.href = "{{ url('restore-history', $data['kind']) }}";
            }, 2000);
        }
        //-------------------------------------------------------------//
        function restoreMailboxAnother() {
            event.preventDefault();
            //----------------------------//
            if (!$('#restoreMailboxAnotherForm').find("[name='changedItems']").prop("checked") && !$(
                    '#restoreMailboxAnotherForm').find("[name='deletedItems']").prop("checked")) {
                $(".danger-oper .danger-msg").html(
                    "{{ __('variables.errors.restore_required_options') }}");
                $(".danger-oper").css("display", "block");
                setTimeout(function() {
                    $(".danger-oper").css("display", "none");
                }, 3000);
                return false;
            }
            //----------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');

            var data = $('#restoreMailboxAnotherForm').serialize();
            let mailboxes = $('#restoreMailboxAnotherForm .mailboxCheck:checked');
            let mailboxesArr = [];
            mailboxes.each(function() {
                let tr = $(this).closest('tr');
                mailboxesArr.push({
                    "id": $(this).val(),
                    name: tr.find('td:nth-child(2)').html(),
                    email: tr.find('td:nth-child(3)').html(),
                });
            });

            $.ajax({
                type: "POST",
                url: "{{ url('restoreMailBoxAnother') }}",
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "&mailboxes=" + JSON.stringify(mailboxesArr),
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000)
                    $('#restoreMailboxAnother').modal('hide');
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
        //---------------------------------------------------//
        function restoreMailboxOriginal() {
            event.preventDefault()
            var data = $('#restoreMailboxForm').serialize();
            let mailboxes = $('#restoreMailboxForm .mailboxCheck:checked');
            let mailboxesArr = [];
            mailboxes.each(function() {
                let tr = $(this).closest('tr');
                mailboxesArr.push({
                    id: $(this).val(),
                    name: tr.find('td:nth-child(2)').html(),
                    email: tr.find('td:nth-child(3)').html(),
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreMailBoxOriginal') }}",
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "&mailboxes=" + JSON.stringify(mailboxesArr),
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000)
                    $('#restoreMailboxOriginal').modal('hide');
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
        function restoreFolder() {
            event.preventDefault();
            //--------------------------------//
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
            //--------------------------------//
            var data = $('#restoreFolderForm').serialize();
            let folders = $('#restoreFolderForm .mailboxCheck:checked');
            let foldersArr = [];
            folders.each(function() {
                let tr = $(this).closest('tr');
                foldersArr.push({
                    id: $(this).val(),
                    folder: tr.find('td:nth-child(2)').html(),
                    mailboxId: $(this).attr('data-mailbox-id'),
                    mailboxName: tr.find('td:nth-child(3)').html()
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreFolder') }}",
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "&folders=" + JSON.stringify(foldersArr),
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000)
                    $('#restoreFolder').modal('hide');
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
        function restoreItem() {
            event.preventDefault();
            //----------------------------------------------//
            if ($('#restoreItemForm').find(".restoreType").val() == "another") {
                if (!$('#restoreItemForm').find("[name='changedItems']").prop("checked") && !$('#restoreItemForm').find(
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
            //----------------------------------------------//
            var data = $('#restoreItemForm').serialize();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            //--------------------------------------------//
            let mailboxes = $('#restoreItemForm .mailboxCheck:checked');
            let mailboxArr = [];
            mailboxes.each(function() {
                if (mailboxArr.filter(e => e.mailboxId === $(this).attr("mailboxId")).length == 0) {
                    let mailboxItems = $('#restoreItemForm .mailboxCheck:checked[mailboxId="' + $(this).attr(
                        "mailboxId") + '"]');
                    let mailboxItemsArr = [];
                    mailboxItems.each(function() {
                        let tr = $(this).closest('tr');
                        mailboxItemsArr.push({
                            "id": $(this).val(),
                            "parentName": $(this).attr("mailboxTitle"),
                            "name": tr.find('td:nth-child(3)').html(),
                            "folderTitle": $(this).attr("mailboxTitle"),
                        })
                    });
                    mailboxArr.push({
                        "mailboxId": $(this).attr("mailboxId"),
                        "mailboxTitle": $(this).attr("mailboxTitle"),
                        "items": mailboxItemsArr
                    });
                }
            });
            //--------------------------------------------//
            $.ajax({
                type: "POST",
                url: "{{ url('restoreItem') }}",
                data: data + '&items=' + JSON.stringify(mailboxArr) +
                    "&_token={{ csrf_token() }}&folderTitle=" + $("#restoreItemForm").find("#folder").val(),
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000)
                    $('#restoreItem').modal('hide');
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
        function exportMailbox() {
            //------------------------------------------------//
            event.preventDefault()
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            //------------------------------------------------//
            var data = $('#exportMailboxForm').serialize();
            let mailboxes = $('#exportMailboxForm .mailboxCheck:checked');
            let mailboxesArr = [];
            mailboxes.each(function() {
                let tr = $(this).closest('tr');
                mailboxesArr.push({
                    "id": $(this).val(),
                    name: tr.find('td:nth-child(2)').html(),
                    email: tr.find('td:nth-child(3)').html(),
                });
            });
            //------------------------------------------------//
            $.ajax({
                type: "POST",
                url: "{{ url('exportMailBoxToPst') }}",
                data: data + '&' +
                    "_token={{ csrf_token() }}" +
                    "&enablePstSizeLimit=" + $('#exportMailboxForm [name="enablePstSizeLimit"]')[0].checked +
                    "&sizeLimit=" + $('#exportMailboxForm [name="sizeLimit"]').val() +
                    "&mailBoxes=" + JSON.stringify(mailboxesArr),
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000)
                    $('#exportMailboxModal').modal('hide');
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
            //------------------------------------------------//
        }
        //----------------------------------------------------//
        function exportFolder() {
            event.preventDefault();
            //--------------------------------------------//
            let data = $('#exportFolderForm').serialize();
            let folders = $('#exportFolderForm .mailboxCheck:checked');
            let foldersArr = [];
            folders.each(function() {
                let tr = $(this).closest('tr');
                foldersArr.push({
                    "id": $(this).val(),
                    "mailboxId": $(this).attr('data-mailbox-id'),
                    "name": tr.find('td:nth-child(2)').html(),
                    "mailboxName": tr.find('td:nth-child(3)').html(),
                });
            });
            //--------------------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportMailBoxFolderToPst') }}",
                data: data +
                    "&_token={{ csrf_token() }}&" +
                    "enablePstSizeLimit=" + $("#exportFolderForm [name='enablePstSizeLimit']")[0].checked +
                    "&sizeLimit=" + $("#exportFolderForm [name='sizeLimit']").val() +
                    "&folders=" + JSON.stringify(foldersArr),
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(res.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000)
                    $('#exportFolderModal').modal('hide');
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
                    showErrorMessage(errMessage);
                }
            });
        }
        //-------------------------------------------------------------//
        function exportFolderItem() {
            event.preventDefault();
            //--------------------------------------------------//
            let items = $('#exportItemForm .mailboxCheck:checked');
            let data = $('#exportItemForm').serialize();
            //----------------------------------------------//
            let mailboxes = $('#exportItemForm .mailboxCheck:checked');
            let mailboxArr = [];
            mailboxes.each(function() {
                if (mailboxArr.filter(e => e.mailboxId === $(this).attr("mailboxId")).length == 0) {
                    let mailboxItems = $('#exportItemForm .mailboxCheck:checked[mailboxId="' + $(this).attr(
                        "mailboxId") + '"]');
                    let mailboxItemsArr = [];
                    mailboxItems.each(function() {
                        let tr = $(this).closest('tr');
                        mailboxItemsArr.push({
                            "id": $(this).val(),
                            "parentName": $(this).attr("mailboxTitle"),
                            "name": tr.find('td:nth-child(3)').html(),
                            "folderTitle": $(this).attr("mailboxTitle"),
                        })
                    });
                    mailboxArr.push({
                        "mailboxId": $(this).attr("mailboxId"),
                        "mailboxTitle": $(this).attr("mailboxTitle"),
                        "items": mailboxItemsArr
                    });
                }
            });
            //--------------------------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportMailBoxFolderItemsToPst') }}",
                data: data +
                    "&_token={{ csrf_token() }}" +
                    "&items=" + JSON.stringify(mailboxArr),
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(res.message);
                    setTimeout(function() {
                        window.location = "{{ url('restore-history', $data['kind']) }}";
                    }, 4000)
                    $('#exportItemModal').modal('hide');
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
                    showErrorMessage(errMessage);
                }
            });
        }
        //----------------------------------------------------//
        function checkResolvedMail(mail) {
            return false;
        }
        //----------------------------------------------------//
    </script>
@endsection
