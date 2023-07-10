@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/main.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/repositories.css') }}" />
<link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/generalElement.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/restore.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/restore-customize.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

@section('topnav')
    <style>
        i {
            padding: 0px 3px;
        }

        .tableDiv .dataTables_wrapper {
            margin-top: -30px;
        }

        .restore-items-toAnother-customBorder {
            height: 162px;
        }
    </style>
    <div class="col-sm-10 navbarLayout">
        <!-- Upper navbar -->
        <ul class="ulNavbar">
            <li>
                <div class="col-sm-2 custom-col-sm-2">
                </div>
            </li>

            <li class="liNavbar"><a class="active" href="{{ url('restore', $data['repo_kind']) }}">Restore
                    <img class="nav-arrow" src="/svg/arrow-right-active.svg">
                    {{ getDataType($data['repo_kind']) }}</a></li>
            <!-- Authenticat ion Links -->
            @include('layouts.authentication-links')
        </ul>
    </div>
@endsection
@section('content')
    <script src="{{ url('/js/timepicker/mdtimepicker.js') }}"></script>
    <link href="{{ url('/css/timepicker/mdtimepicker.css') }}" rel="stylesheet" type="text/css">

    <div id="mainContent" class="m-0">
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <div id="jobsModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg w-600" >
                <div class="divBorderRight"></div>
                <div class="divBorderBottom"></div>
                <div class="divBorderleft"></div>
                <div class="divBorderUp"></div>

                <!-- Modal content-->

                <div class="modal-content ">

                    <div class="modalContent">
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        <div class="row mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req">Specify Point in Time
                                </h4>
                            </div>
                        </div>
                        <form class="mb-0" onsubmit="createSession(event)">
                            <div class="row">
                                <div class="input-form-70 mb-1">Backup Job:</div>
                                <div class="input-form-70 inline-flex">
                                    <select class="form_input form-control w-47 font-size" name="jobs" id="jobs"
                                        required>
                                        <option value="" disabled selected>Select Job</option>
                                        <option value="all">All</option>
                                        @if (!empty($data['jobs']))
                                            @foreach ($data['jobs'] as $job)
                                                <option value="{{ $job->backup_job_id }}">{{ $job->backup_job_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="input-form-70 mb-1">Point in Time:</div>
                                <div class="input-form-70 inline-flex">
                                    <input placeholder="Backup Date" type="text" disabled required
                                        class="backupDate form_input form-control mr-25 font-size" />
                                    <select class="form_input form-control backupTime font-size" disabled required
                                        name="backupTime">
                                        <option value="" disabled selected>Select Time</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <label class="checkbox-padding-left checkbox-container">&nbsp;
                                        <input id="showDeleted" type="checkbox" class="form-check-input">
                                        <span class="checkbox-span-class check-mark-white check-mark"></span>
                                    </label>
                                    <span class="ml-25">Show Items That Have Been Deleted By
                                        User</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-form-70">
                                    <label class="checkbox-padding-left checkbox-container">&nbsp;
                                        <input id="showVersions" type="checkbox" class="form-check-input">
                                        <span class="checkbox-span-class check-mark-white check-mark"></span>
                                    </label>
                                    <span class="ml-25">Show All Versions Of Items That Have
                                        Been Modified By User</span>
                                </div>
                            </div>

                            <div class="row mt-10">
                                <div class="input-form-70 inline-flex">
                                    <button id="activeapply" type="submit"
                                        class="btn_primary_state allWidth mr-25">Apply</button>
                                    <button id="activeclose" type="button"
                                        class="btn_cancel_primary_state allWidth activeclose"
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
        <div id="confirmationModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content ">
                    <div id="modalBody_id" class="modalContent">
                        <div class="alert swal-modal-confirmation custom-confirmation" role="alert">
                            <div class="swal-icon swal-icon--warning" style="background-color: #FA9351!important">
                                <span class="swal-icon--warning__body">
                                    <span class="swal-icon--warning__dot"></span>
                                </span>
                            </div>
                            <input type="hidden" class="jobId">
                            <input type="hidden" class="status">
                            <div class="swal-title text-center confirmTitle">Moving this restore session to E-Discovery
                            </div>
                            <div class="row">
                                <div class="modal-body basic-color text-center mt-22">
                                    Are You Sure ?
                                </div>
                            </div>

                            <div class="row mt-10">
                                <div class="input-form-70 inline-flex">
                                    <button type="button" class="btn_primary_state allWidth confirmButton mr-25"
                                        onClick="moveToEDiscovery();">Yes</button>
                                    <button type="button" class="btn_cancel_primary_state allWidth"
                                        data-dismiss="modal">No</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        <div class="row ml-108 modal-folder-margin" style="margin-bottom:15px">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p modal-title">Restore Folders to Original Location</h4>
                            </div>
                        </div>
                        <form id="restoreFolderForm" class="mb-0" onsubmit="restoreFolder(event)">
                            <div class="custom-left-col modal-folder-left">
                                <input type="hidden" class="restoreType" name="restoreType" />
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
                                                            <th>
                                                                <label
                                                                    class="checkbox-top-left checkbox-container checkbox-search">
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
                                                                    <span
                                                                        class="tree-checkBox check-mark-white check-mark"></span>
                                                                </label>
                                                            </th>
                                                            <th>Folder</th>
                                                            <th>Mailbox</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3">
                                                                <span class="boxesCount"></span> folders selected
                                                                <span class="unresolvedCount"></span>
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
                                                        class="custom-form-control form-control form_input font-size required"
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
                                                                <input type="radio" name="folderType"
                                                                    class="folderType" value="original" checked>Original
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                        <div class="radio m-0">
                                                            <label>
                                                                <input type="radio" name="folderType"
                                                                    class="folderType" value="custom" checked>Another
                                                                Folder
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-10 allWidth">
                                                    <input type="text"
                                                        class="form-control form_input required custom-form-control font-size"
                                                        id="folder" value="" placeholder="Folder"
                                                        name="folder" required autocomplete="off" />
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
                                                <div class="pr-0 pl-3">
                                                    <label>Restore the following items:</label>
                                                </div>
                                                <div class="w-100"></div>

                                                <div class="col">
                                                    <div class="relative allWidth mb-2">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="changedItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">Changed Items</span>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="relative allWidth mb-2">
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="deletedItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">Deleted Items</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label class="ml-10">Flag Restored Items as Unread:</label>
                                                <div class="allWidth relative ml-15">
                                                    <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                        <input name="markRestoredAsunread" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            class="checkbox-span-class check-mark-white check-mark"></span>
                                                    </label>
                                                    <span class="ml-25">Mark Restored As Unread</span>
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

        <div id="restoreMailboxOriginal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg modal-width mt-5v">
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
                                <h4 class="per-req ml-2p">Restore Selected Mailboxs to Original Location
                                </h4>
                            </div>
                        </div>
                        <form id="restoreMailboxForm" class="mb-0" onsubmit="restoreMailboxOriginal(event)">
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
                                        <h5 class="txt-blue mt-0">Mailboxes E-Mail</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder h-175p">
                                            <div class="allWidth mailboxesresultsTable">
                                                <table id="mailboxOriginalTable"
                                                    class="stripe table table-striped table-dark display nowrap allWidth">
                                                    <thead class="table-th">
                                                        <tr>
                                                            <th>
                                                                <label
                                                                    class="checkbox-top-left checkbox-container checkbox-search">
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
                                                                    <span
                                                                        class="tree-checkBox check-mark-white check-mark"></span>
                                                                </label>
                                                            </th>
                                                            <th>Mailbox</th>
                                                            <th>E-Mail</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3">
                                                                <span class="boxesCount"></span> mailboxes selected
                                                                <span class="unresolvedCount"></span>
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
                                        <div class="col-lg-12 customBorder pb-3 pt-3">

                                            <div class="row pl-3 mb-10">Specify Restore Options:</div>
                                            <div class="row">
                                                <div class="pl-3">
                                                    <label>Exclude the Following Items:</label>
                                                </div>
                                                <div class="w-100"></div>

                                                <div class="col">
                                                    <div class="relative  mb-2">
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="excludeDrafts" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">Drafts</span>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="allWidth relative  mb-2">
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="excludeDeletedItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">Deleted Items</span>
                                                    </div>
                                                </div>
                                                <div class="w-100"></div>

                                                <div class="col">
                                                    <div class="allWidth relative  mb-2">
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="excludeInplaceHolditems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">In-Place Hold Items</span>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="allWidth relative  mb-2">
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="excludeLitigationHoldItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">Litigation Hold Items</span>
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
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="changedItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">Changed Items</span>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="relative allWidth mb-2">
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="deletedItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">Deleted Items</span>
                                                    </div>
                                                </div>
                                                <div class="w-100"></div>

                                                <div class="col">
                                                    <div class="relative mb-2">
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="skipUnresolved" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">Skip Unresolved Items</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <label class="mt-3 ml-10">Flag Restored Items as Unread:</label>
                                                <div class="allWidth relative ml-15">
                                                    <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                        <input name="markRestoredAsunread" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            class="checkbox-span-class check-mark-white check-mark"></span>
                                                    </label>
                                                    <span class="ml-25">Mark Restored As Unread</span>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-12 pl-3 pr-3">
                                                    <div class="allWidth inline-flex">
                                                        <div class="allWidth relative ml-5p">
                                                            <label
                                                                class="checkbox-padding-left0-top checkbox-container">&nbsp;
                                                                <input name="RecentItemRestorePeriod" type="checkbox"
                                                                    class="form-check-input" />
                                                                <span
                                                                    class="checkbox-span-class check-mark-white check-mark"></span>
                                                            </label>
                                                            <p class="mb-0 ml-25 font-color">
                                                                Finish the Restore of Recent Items Before
                                                                Restoring the Remaining Items.
                                                            </p>
                                                            <div class="flex">
                                                                <span class="ml-25">Restore items for the
                                                                    last</span>&nbsp;
                                                                <input name="daysNumber" type="number"
                                                                    placeholder="Days" min='0'
                                                                    class="minInput custom-minInput ml-1 mr-1 days-color mt-3p" />
                                                                &nbsp;<span>first.</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">&nbsp;</div>
                                            <div class="row">&nbsp;</div>
                                            <div class="row">&nbsp;</div>
                                            <div class="row">&nbsp;</div>
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

        <div id="restoreMailboxAnother" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg modal-width">
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
                                <h4 class="per-req ml-2p">Restore Selected Mailboxs To Another Location
                                </h4>
                            </div>
                        </div>
                        <form id="restoreMailboxAnotherForm" class="mb-0" onsubmit="restoreMailboxAnother(event)">
                            <div class="custom-left-col">
                                <input type="hidden" class="mailBoxes" name="mailBoxes" />
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
                                        <div class="col-lg-12 customBorder h-170p">
                                            <div class="allWidth mailboxesresultsTable">
                                                <table id="mailboxAnotherTable"
                                                    class="stripe table table-striped table-dark display nowrap allWidth mailboxesresultsTable">
                                                    <thead class="table-th">
                                                        <tr>
                                                            <th>
                                                                <label
                                                                    class="checkbox-top-left checkbox-container checkbox-search">
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
                                                                    <span
                                                                        class="tree-checkBox check-mark-white check-mark"></span>
                                                                </label>
                                                            </th>
                                                            <th>Mailbox</th>
                                                            <th>E-Mail</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3">
                                                                <span class="boxesCount"></span> mailboxes selected
                                                                <span class="unresolvedCount"></span>
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
                                                    <input type="email"
                                                        class="form-control form_input custom-form-control font-size required"
                                                        name="mailbox" placeholder="MailBox" name="mailbox" required
                                                        autocomplete="off" />
                                                </div>
                                                <div class="allWidth">
                                                    <input type="text"
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

                                            <div class="row pl-3 mb-10">Specify Restore Options:</div>
                                            <div class="row">
                                                <div class="pl-3">
                                                    <label>Exclude the Following Items:</label>
                                                </div>
                                                <div class="w-100"></div>

                                                <div class="col">
                                                    <div class="relative  mb-2">
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="excludeDrafts" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">Drafts</span>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="allWidth relative  mb-2">
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="excludeInplaceHolditems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">In-Place Hold Items</span>
                                                    </div>
                                                </div>
                                                <div class="w-100"></div>

                                                <div class="col">
                                                    <div class="allWidth relative  mb-2">
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="excludeDeletedItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">Deleted Items</span>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="allWidth relative  mb-2">
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="excludeLitigationHoldItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">Litigation Hold Items</span>
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="pr-0 pl-3 ml-15">
                                                        <label>Restore the following items:</label>
                                                    </div>
                                                    <div class="w-100"></div>

                                                    <div class="col">
                                                        <div class="relative allWidth mb-2 ml-15">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="changedItems" type="checkbox"
                                                                    class="form-check-input" />
                                                                <span
                                                                    class="checkbox-span-class check-mark-white check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Changed Items</span>
                                                        </div>
                                                    </div>

                                                    <div class="col">
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="deletedItems" type="checkbox"
                                                                    class="form-check-input" />
                                                                <span
                                                                    class="checkbox-span-class check-mark-white check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Deleted Items</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-1">
                                                    <label class=" ml-25">Flag Restored Items as Unread:</label>
                                                    <div class="allWidth relative ml-30">
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="markRestoredAsunread" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">Mark Restored As Unread</span>
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
                        <div class="row mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p">Export Selected Mailboxs
                                </h4>
                            </div>
                        </div>
                        <form id="exportMailBoxForm" class="mb-0" onsubmit="exportMailBoxToPst(event)">
                            <input type="hidden" class="mailBoxes" name="mailBoxes" />
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
                                                class="stripe table table-striped table-dark display nowrap allWidth mailboxesresultsTable">
                                                <thead class="table-th">
                                                    <tr>
                                                        <th>
                                                            <label
                                                                class="checkbox-top-left checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span
                                                                    class="tree-checkBox check-mark-white check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>Mailbox</th>
                                                        <th>E-Mail</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> mailboxes selected
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
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="enablePstSizeLimit" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-top12 check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-21">Limit PST size to:</span>
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
                        <div class="row mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p">Export Selected Mailboxs Folders
                                </h4>
                            </div>
                        </div>
                        <form id="exportFolderForm" class="mb-0" onsubmit="exportMailBoxFolderToPst(event)">
                            <input type="hidden" class="mailBoxes" name="mailBoxes" />
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
                                            <table id="exportFoldersResultsTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <th>
                                                            <label
                                                                class="checkbox-top-left checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span
                                                                    class="tree-checkBox check-mark-white check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>Folder</th>
                                                        <th>Mailbox</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> folders selected
                                                            <span class="unresolvedCount"></span>
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
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="enablePstSizeLimit" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-top12 check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-21">Limit PST size to:</span>
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
                        <div class="row ml-100 modal-item-margin" style="margin-bottom:15px">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p modal-title">Restore Selected Items</h4>
                            </div>
                        </div>
                        <form id="restoreItemForm" class="mb-0" onsubmit="restoreItem(event)">
                            <div class="custom-left-col modal-item-left">
                                <input type="hidden" class="restoreType" name="restoreType" />
                                <input type="hidden" class="items" name="items" />
                                <input type="hidden" class="mailboxId" name="mailboxId" />
                                <input type="hidden" class="folderTitle" name="folderTitle" />
                                <input type="hidden" class="mailboxName" name="mailboxName" />
                                <input type="hidden" class="parentName" name="parentName" />
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
                                        <h5 class="txt-blue mt-0">Selected Items</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder h-230p">
                                            <div class="allWidth">
                                                <table id="itemsResultsTable"
                                                    class="stripe table table-striped table-dark display nowrap allWidth">
                                                    <thead class="table-th">
                                                        <tr>
                                                            <th>
                                                                <label
                                                                    class="checkbox-top-left checkbox-container checkbox-search">
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
                                                                    <span
                                                                        class="tree-checkBox check-mark-white check-mark"></span>
                                                                </label>
                                                            </th>
                                                            <th>Item</th>
                                                            <th>Folder</th>
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
                                                                <input type="radio" name="folderType"
                                                                    class="folderType" value="original" checked>Original
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                        <div class="radio m-0">
                                                            <label>
                                                                <input type="radio" name="folderType"
                                                                    class="folderType" value="custom" checked>Another
                                                                Folder
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-10 pl-2 allWidth">
                                                    <input type="text"
                                                        class="form-control form_input required custom-form-control font-size"
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
                                        <div class="col-lg-12 customBorder restore-items-toAnother-customBorder pb-3 pt-3">

                                            <div class="row pl-3 mb-10">Specify Restore Options:</div>
                                            <div class="row">
                                                <div class="pr-0 pl-3">
                                                    <label>Restore the following items:</label>
                                                </div>
                                                <div class="w-100"></div>

                                                <div class="col">
                                                    <div class="relative allWidth mb-2">
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="changedItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">Changed Items</span>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="relative allWidth mb-2">
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="deletedItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">Deleted Items</span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <label class="mt-2 ml-25">Flag Restored Items as Unread:</label>
                                                    <div class="allWidth relative ml-30">
                                                        <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                            <input name="markRestoredAsunread" type="checkbox"
                                                                class="form-check-input" />
                                                            <span
                                                                class="checkbox-span-class check-mark-white check-mark"></span>
                                                        </label>
                                                        <span class="ml-25">Mark Restored As Unread</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row hide restoreItemsWarning mb-4">
                                    <div class="input-form-70 mb-1">
                                        <div class="col-lg-12 customBorder pb-3 pt-3 form-control form_input">
                                            <span>This Mailbox isnt resolved</span>
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

        <div id="exportItemsModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg ">
                <div class="divBorderRight"></div>
                <div class="divBorderBottom"></div>
                <div class="divBorderleft"></div>
                <div class="divBorderUp"></div>
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modalContent">
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        <div class="row ml-15">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p">Export Selected Items</h4>
                            </div>
                        </div>
                        <form id="exportItemsForm" class="mb-0" onsubmit="exportMailBoxFolderItemsToPst(event)">
                            <input type="hidden" class="restoreType" name="restoreType" />
                            <input type="hidden" class="items" name="items" />
                            <input type="hidden" class="mailboxId" name="mailboxId" />
                            <input type="hidden" class="folderTitle" name="folderTitle" />
                            <input type="hidden" class="mailboxName" name="mailboxName" />
                            <input type="hidden" class="parentName" name="parentName" />

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
                                    <h5 class="txt-blue mt-0">Selected Items</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder">
                                        <div class="allWidth">
                                            <table id="exportItemsResultsTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <th>
                                                            <label
                                                                class="checkbox-top-left checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span
                                                                    class="tree-checkBox check-mark-white check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>Item</th>
                                                        <th>Folder</th>
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

        <div class="row h-100">
            <div class="col-2 z-10">

                <div class="custom-border">
                    <div class="row mail-box left-mail-box h-100">
                        <div class="col-lg-12 nopadding relative">
                            <div class="m-4 flex">
                                <label class="treeSearchInput flex-9">
                                    <input type="search" class="form_input form-control custom-search"
                                        placeholder="Search...">
                                    <div class="search-container">
                                        <img class="search-icon" src="/svg/search.svg">
                                    </div>
                                </label>
                                <div class="filter-container relative flex-1">
                                    <img class="filter-icon hand dropdown-toggle custom-filter-padding"
                                        data-toggle="dropdown" aria-expanded="false" src="/svg/filter.svg">
                                    <div class="dropdown-menu dropdown-menu-filter tree-filter-menu">
                                        <div class="divBorderRight"></div>
                                        <div class="divBorderBottom"></div>
                                        <div class="divBorderleft"></div>
                                        <div class="divBorderUp"></div>
                                        <form class="filter-form mb-0" onsubmit="getFilteredMailboxes(event)">
                                            <div class="filterCont flex allWidth pt-10">
                                                <div class="p-3 text-white ml-15">
                                                    <select required name="sortBoxType"
                                                        class="sortBoxType btn-sm dropdown-toggle form_dropDown form-control"
                                                        data-toggle="dropdown" required value=":">
                                                        <option value="" selected="selected">Sort</option>
                                                        <span class="fa fa-caret-down"></span>
                                                        <option value="AZ">A > Z
                                                        </option>
                                                        <option value="ZA">Z > A
                                                        </option>
                                                    </select>

                                                </div>
                                                <div class="p-3 text-white mr-15">
                                                    <select name="filterBoxType" required
                                                        class="required boxType btn-sm dropdown-toggle form_dropDown form-control"
                                                        data-toggle="dropdown" value=":">
                                                        <option value="" selected="selected">Show</option><span
                                                            class="fa fa-caret-down"></span>
                                                        <option value="all">All
                                                        </option>
                                                        <option value="users">User
                                                        </option>
                                                        <option value="archive">Archive
                                                        </option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="p-3 text-white">
                                                <label class="font-small pl-20" cellspa>Show Mailboxes
                                                    Start With:</label>
                                                <table id="reduce-padding" class="filter-table alphabet-color">
                                                    <tr>
                                                        <td>
                                                            <div id="circle"><span>A</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>B</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>C</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>D</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>E</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>F</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>G</span></div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <div id="circle"><span>H</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>I</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>J</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>K</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>L</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>M</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>N</span></div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <div id="circle"><span>O</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>P</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>Q</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>R</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>S</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>T</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>U</span></div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <div id="circle"><span>V</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>W</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>X</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>Y</span></div>
                                                        </td>
                                                        <td>
                                                            <div id="circle"><span>Z</span></div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="text-center inline-flex">
                                                <button id="apply" type="submit"
                                                    class="ml-30 w-100p btn_primary_state mr-2 pl-5 pr-5">
                                                    Apply</button>
                                                <button id="resetFilterTable" type="button"
                                                    class="btn_cancel_primary_state pl-5 pr-5 w-100p">
                                                    Reset</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <ul id="mailboxes" class="tree pl-4">

                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-10">

                <div class="row custom-col-10">
                    <div class="ml-15">
                        <button id="choose" class="btn_primary_state left-button right-button" data-toggle="modal"
                            data-target="#jobsModal">
                            Choose</button>
                    </div>&nbsp;&nbsp;
                    <div class="mt-5p">
                        <div class="txt-ctelecoms1 float-left m-6"> Date:</div>
                        <div id="rdate" class="float-left m-6"></div>
                    </div>
                    <div class="mt-5p">
                        <div class="txt-ctelecoms1 float-left m-6"> Time:</div>
                        <div id="rtime" class="float-left m-6"></div>
                    </div>

                </div>
                @php
                    $permissionClass = '';
                @endphp
                @if (!$role->hasAnyPermission('exchange_restore_actions', 'exchange_export_actions'))
                    @php
                        $permissionClass = 'hide';
                    @endphp
                @endif
                <div class="row main-button-cont {{ $permissionClass }}">
                    <div class="btnMain main-button flex">
                        <div class="btnUpMask"></div>
                        <div class="row m-0 pl-4 pr-4 allWidth">
                            <div class="col-lg-4 mailBoxButton">
                                <div class="selected-action allWidth relative">
                                    <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                        data-toggle="dropdown" aria-expanded="false">
                                        Selected MailBox Actions
                                        <span class="selectedBoxCount"></span>
                                        <span class="fa fa-caret-down"></span></button>
                                    <ul class="dropdown-menu allWidth">
                                        @if ($role->hasPermissionTo('exchange_restore_actions'))
                                            <li>
                                                <a href="javascript:restoreMailboxOriginalModal(event)"
                                                    class="tooltipSpan" title="Restore Selected to Original Location">
                                                    Restore Selected to Original Location
                                                </a>
                                            </li>
                                            <li class="restoreMailboxAnotherOption">
                                                <a href="javascript:restoreMailboxAnotherModal(event)"
                                                    class="tooltipSpan" title="Restore Selected to Another Location">
                                                    Restore Selected to Another Location
                                                </a>
                                            </li>
                                        @endif
                                        @if ($role->hasPermissionTo('exchange_export_actions'))
                                            <li>
                                                <a href="javascript:exportMailboxModal(event)" class="tooltipSpan"
                                                    title="Export to PST">
                                                    Export to PST
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4 mailBoxFolderButton">
                                <div class="selected-action allWidth relative">
                                    <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                        data-toggle="dropdown" aria-expanded="false">
                                        Selected Folders Actions
                                        <span class="selectedFolderCount"></span>
                                        <span class="fa fa-caret-down"></span></button>

                                    <ul class="dropdown-menu allWidth">
                                        @if ($role->hasPermissionTo('exchange_restore_actions'))
                                            <li>
                                                <a href="javascript:restoreFolderOriginalModal(event)"
                                                    class="tooltipSpan restoreFolderOption"
                                                    title="Restore Selected to Original Location">
                                                    Restore Selected to Original Location
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:restoreFolderAnotherModal(0)"
                                                    class="tooltipSpan restoreFolderOption"
                                                    title="Restore Selected to Another Location">
                                                    Restore Selected to Another Location
                                                </a>
                                            </li>
                                        @endif
                                        @if ($role->hasPermissionTo('exchange_export_actions'))
                                            <li>
                                                <a href="javascript:exportFolderModal(event)" class="tooltipSpan"
                                                    title="Export to PST">
                                                    Export to PST
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4 mailBoxFolderItemsButton">
                                <div class="selected-action allWidth relative">
                                    <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                        data-toggle="dropdown" aria-expanded="false">
                                        Selected Items Actions
                                        <span class="selectedItemCount"></span>
                                        <span class="fa fa-caret-down"></span></button>
                                    <ul class="dropdown-menu allWidth">
                                        @if ($role->hasPermissionTo('exchange_restore_actions'))
                                            <li>
                                                <a href="javascript:restoreItemOriginalModal(event)" class="tooltipSpan"
                                                    title="Restore Selected to Original Location">
                                                    Restore Selected to Original Location
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:restoreItemAnotherModal(event)" class="tooltipSpan"
                                                    title="Restore Selected to Another Location">
                                                    Restore Selected to Another Location
                                                </a>
                                            </li>
                                        @endif
                                        @if ($role->hasPermissionTo('exchange_export_actions'))
                                            <li>
                                                <a href="javascript:downloadMultiItems(event)" class="tooltipSpan"
                                                    title="Export Selected To .zip">
                                                    Export Selected To .zip
                                                </a>
                                            </li>
                                        @endif
                                        @if ($role->hasPermissionTo('exchange_export_actions'))
                                            <li>
                                                <a href="javascript:exportItemsModal(0)" class="tooltipSpan"
                                                    title="Export to PST">
                                                    Export to PST
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="btnDownMask"></div>
                    </div>
                </div>
                <div class="col-lg-12 left-table">
                    <div class="row ml--3 mr-3 tableRow">
                        <div class="jobsTable m-0 pt-4">

                            <div class="row loadingRow ml-0 mb-26">
                                <button onclick="stopLoad()" type="button"
                                    class="btn-sm custombtn_cancel_primary_state btn_cancel_primary_state stopLoad hand hide">Stop
                                    Loading</button>
                                <button onclick="resumeLoad()" type="button"
                                    class="btn-sm custombtn_cancel_primary_state btn_cancel_primary_state resumeLoad hand hide">Resume
                                    Loading</button>
                                <button onclick="moveToEDiscovery(1)" type="button"
                                    class="btn-sm hide moveToEDiscovery custombtn_cancel_primary_state btn_cancel_primary_state hand">Move
                                    to E-Discovery</button>
                            </div>
                            <div class="row warningRow hide mb-2">
                                <div class="col-lg-12 text-right custom-text-center txt-blue">
                                    <span class="orgSpan">There is Above {{ $data['itemsWarningLimit'] }}
                                        Items</span>
                                </div>
                            </div>
                            <div class="row stoppingRow hide mb-2">
                                <div class="col-lg-12 text-right custom-text-center txt-blue">
                                    <span class="orgSpan">There is Above {{ $data['itemsStoppingLimit'] }}
                                        Items</span>
                                </div>
                            </div>
                            <div class="itemsTable tableDiv">
                                <table id="itemsTable"
                                    class="stripe table table-striped table-dark display nowrap allWidth">
                                    <thead class="table-th">
                                        <tr>
                                            <th></th>
                                            <th class="text-left">From</th>
                                            <th>To</th>
                                            <th>Subject</th>
                                            <th>Cc</th>
                                            <th>Bcc</th>
                                            <th>Sent</th>
                                            <th>Received</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-content">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="9">
                                                <div class="custom-text-right">
                                                    <span class="orgSpan hide searchingLabel">Searching ...</span>
                                                    <span class="orgSpan countingLabel"></span>
                                                </div>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tableDiv contactsTable hide">
                                <table id="contactsTable"
                                    class=" stripe table table-striped table-dark display nowrap allWidth">
                                    <thead class="table-th">
                                        <tr>
                                            <th></th>
                                            <th>Full Name</th>
                                            <th>Mobile</th>
                                            <th>Home Phone</th>
                                            <th>Address</th>
                                            <th>Company</th>
                                            <th>Email</th>
                                            <th>Business Phone</th>
                                            <th>Web Page</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-content">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            {{-- <th colspan="8"></th> --}}
                                            <th colspan="10">
                                                <div class="custom-text-right">
                                                    <span class="orgSpan hide searchingLabel">Searching ...</span>
                                                    <span class="orgSpan countingLabel"></span>
                                                </div>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tableDiv notesTable hide">
                                <table id="notesTable"
                                    class=" stripe table table-striped table-dark display nowrap allWidth">
                                    <thead class="table-th">
                                        <tr>
                                            <th></th>
                                            {{-- Notes --}}
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-content">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            {{-- <th colspan="8"></th> --}}
                                            <th colspan="4">
                                                <div class="custom-text-right">
                                                    <span class="orgSpan hide searchingLabel">Searching ...</span>
                                                    <span class="orgSpan countingLabel"></span>
                                                </div>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tableDiv journalsTable hide">
                                <table id="journalsTable"
                                    class=" stripe table table-striped table-dark display nowrap allWidth">
                                    <thead class="table-th">
                                        <tr>
                                            <th></th>
                                            <th>Subject</th>
                                            <th>Start Date</th>
                                            <th>Duration</th>
                                            <th>Entry Type</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-content">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            {{-- <th colspan="8"></th> --}}
                                            <th colspan="6">
                                                <div class="custom-text-right">
                                                    <span class="orgSpan hide searchingLabel">Searching ...</span>
                                                    <span class="orgSpan countingLabel"></span>
                                                </div>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tableDiv appointmentsTable hide">
                                <table id="appointmentsTable"
                                    class=" stripe table table-striped table-dark display nowrap allWidth">
                                    <thead class="table-th">
                                        <tr>
                                            <th></th>
                                            <th>Organizer</th>
                                            <th>Subject</th>
                                            <th>Attendees</th>
                                            <th>Recurrence</th>
                                            <th>Location</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-content">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            {{-- <th colspan="8"></th> --}}
                                            <th colspan="9">
                                                <div class="custom-text-right">
                                                    <span class="orgSpan hide searchingLabel">Searching ...</span>
                                                    <span class="orgSpan countingLabel"></span>
                                                </div>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tableDiv tasksTable hide">
                                <table id="tasksTable"
                                    class="stripe table table-striped table-dark display nowrap allWidth">
                                    <thead class="table-th">
                                        <tr>
                                            <th></th>
                                            <th>Owner</th>
                                            <th>Status</th>
                                            <th>Percent Complete</th>
                                            <th>Start Date</th>
                                            <th>Due Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-content">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            {{-- <th colspan="8"></th> --}}
                                            <th colspan="7">
                                                <div class="custom-text-right">
                                                    <span class="orgSpan hide searchingLabel">Searching ...</span>
                                                    <span class="orgSpan countingLabel"></span>
                                                </div>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="searchModal" class="modal modal-center" role="dialog">

            <div class="modal-dialog modal-lg">
                <div class="divBorderRight"></div>
                <div class="divBorderBottom"></div>
                <div class="divBorderleft"></div>
                <div class="divBorderUp"></div>

                <!-- Modal content-->
                <div class="modal-content ">

                    <div id="seaerch_modal_id" class="modalContent">
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        <div class="row mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p">Search
                                </h4>
                            </div>
                        </div>

                        <div class="row basic-class">
                            <div class="input-form-70 mb-1">Subject:</div>
                            <div class="input-form-70 inline-flex">
                                <input type="text" class="form_input form-control w-47 custom-form-control font-size"
                                    id="basicSubject" placeholder="" />
                            </div>
                        </div>
                        <div class="row basic-class">
                            <div class="input-form-70 mb-1">Sent:</div>
                            <div class="input-form-70 inline-flex">
                                <input type="text"
                                    class="form_input form-control mr-25 font-size custom-form-control" id="from"
                                    placeholder="From" />
                                <input type="text" class="form_input form-control font-size custom-form-control"
                                    id="to" placeholder="To" />
                            </div>
                        </div>
                        <div class="row searhRow basic-class">
                            <div class="input-form-70 mb-1">Sent Date:</div>
                            <div class="input-form-70 inline-flex">
                                <input type="text"
                                    class="form_input form-control mr-25 font-size custom-form-control" id="sentFrom"
                                    placeholder="From" />
                                <input type="text" class="form_input form-control font-size custom-form-control"
                                    id="sentTo" placeholder="To" />
                            </div>
                        </div>

                        <div class="row searhRow tasks-class hide">
                            <div class="input-form-70 mb-1">Owner: <span class="ml-43">Status:</span></div>
                            <div class="input-form-70 inline-flex">
                                <input type="text"
                                    class="form_input form-control mr-25 custom-form-control font-size" id="owner"
                                    placeholder="" />
                                <input type="text" class="form_input form-control custom-form-control font-size"
                                    id="status" placeholder="" />
                            </div>
                        </div>
                        <div class="row searhRow tasks-class hide">
                            <div class="input-form-70 mb-1">Start Date:</div>
                            <div class="input-form-70 inline-flex">
                                <input type="text"
                                    class="form_input form-control mr-25 font-size custom-form-control"
                                    id="taskStartFrom" placeholder="From" />
                                <input type="text" class="form_input form-control font-size custom-form-control"
                                    id="taskStartTo" placeholder="To" />
                            </div>
                        </div>

                        <div class="row searhRow calendar-class hide">
                            <div class="input-form-70 mb-1">Organizer: <span class="ml-43">Subject:</span></div>
                            <div class="input-form-70 inline-flex">
                                <input type="text"
                                    class="form_input form-control mr-25 custom-form-control font-size" id="organizer"
                                    placeholder="" />
                                <input type="text" class="form_input form-control custom-form-control font-size"
                                    id="calendarSubject" placeholder="" />
                            </div>
                        </div>
                        <div class="row searhRow calendar-class hide">
                            <div class="input-form-70 mb-1">Start Date:</div>
                            <div class="input-form-70 inline-flex">
                                <input type="text"
                                    class="form_input form-control mr-25 font-size custom-form-control"
                                    id="calendarStartFrom" placeholder="From" />
                                <input type="text" class="form_input form-control font-size custom-form-control"
                                    id="calendarStartTo" placeholder="To" />
                            </div>
                        </div>

                        <div class="row searhRow journal-class hide">
                            <div class="input-form-70 mb-1">Subject:</div>
                            <div class="input-form-70 inline-flex">
                                <input type="text" class="form_input form-control custom-form-control font-size"
                                    id="journalSubject" placeholder="" />
                            </div>
                        </div>
                        <div class="row searhRow journal-class hide">
                            <div class="input-form-70 mb-1">Start Date:</div>
                            <div class="input-form-70 inline-flex">
                                <input type="text"
                                    class="form_input form-control mr-25 font-size custom-form-control"
                                    id="journalStartFrom" placeholder="From" />
                                <input type="text" class="form_input form-control font-size custom-form-control"
                                    id="journalStartTo" placeholder="To" />
                            </div>
                        </div>

                        <div class="row searhRow note-class hide">
                            <div class="input-form-70 mb-1">Date:</div>
                            <div class="input-form-70 inline-flex">
                                <input type="text"
                                    class="form_input form-control mr-25 font-size custom-form-control" id="noteFrom"
                                    placeholder="From" />
                                <input type="text" class="form_input form-control font-size custom-form-control"
                                    id="noteTo" placeholder="To" />
                            </div>
                        </div>

                        <div class="row searhRow contact-class hide">
                            <div class="input-form-70 mb-1">Fullname: <span class="ml-43">Company:</span></div>
                            <div class="input-form-70 inline-flex">
                                <input type="text"
                                    class="form_input form-control mr-25 custom-form-control font-size" id="fullname"
                                    placeholder="" />
                                <input type="text" class="form_input form-control custom-form-control font-size"
                                    id="company" placeholder="" />
                            </div>
                        </div>
                        <div class="row searhRow contact-class hide">
                            <div class="input-form-70 mb-1">Email:</div>
                            <div class="input-form-70 inline-flex">
                                <input type="text" class="form_input form-control custom-form-control font-size"
                                    id="contactEmail" placeholder="" />
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="input-form-70 inline-flex">
                                <button type="button" onclick="applySearch()"
                                    class="btn_primary_state  allWidth mr-25">Apply</button>
                                <button type="button" class="btn_cancel_primary_state allWidth"
                                    onclick="resetSearch()">Reset</button>
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>

                    </div>
                </div>

            </div>
        </div>
        <div id="detailsModal" class="modal modal-center" role="dialog">

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
                        <div class="row mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req">Mail Details
                                </h4>
                                <input type="hidden" class="mailboxidtext">
                                <input type="hidden" class="itemidtext">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 link-box">
                                <p class="inline-flex">
                                    <span class="itemTitle ">Mailbox: </span>
                                    <span class="itemValue mailboxtext"></span>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-form-70 link-box">
                                <p class="inline-flex">
                                    <span class="itemTitle ">From: </span>
                                    <span class="itemValue frommailtext"></span>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-form-70 link-box">
                                <p class="inline-flex">
                                    <span class="itemTitle ">To: </span>
                                    <span class="itemValue tomailtext"></span>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-form-70 link-box">
                                <p class="inline-flex">
                                    <span class="itemTitle ">Cc: </span>
                                    <span class="itemValue cctext"></span>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-form-70 link-box">
                                <p class="inline-flex">
                                    <span class="itemTitle ">Bcc: </span>
                                    <span class="itemValue bcctext"></span>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-form-70 link-box">
                                <p class="inline-flex">
                                    <span class="itemTitle ">Sent: </span>
                                    <span class="itemValue senttext"></span>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-form-70 link-box">
                                <p class="inline-flex">
                                    <span class="itemTitle ">Received: </span>
                                    <span class="itemValue receivetext"></span>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-form-70 link-box">
                                <p class="inline-flex">
                                    <span class="itemTitle ">Subject: </span>
                                    <span class="itemValue subjecttext"></span>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-form-70 inline-flex">
                                <button type="button"
                                    class="btn_primary_state allWidth mr-25 downloadMailItem">Download</button>
                                <button type="button" class="btn_cancel_primary_state allWidth"
                                    data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        //-------------------------------------------------//
        let jobType = null;
        let jobId = null;
        let jobTime = null;
        let showDeleted = null;
        let showVersions = null;
        //-------------------------------------------------//
        $(window).on('load', function() {
            var parent = $('.parent-link').attr('data-parent');
            $('.submenu-restore.submenu a[data-route="' + parent + '"]').addClass('active');
            var row = $('a.sub-menu-link.active').closest('.row');
            row.find('.left-nav-list').addClass('active').removeClass('collapsed');
            $('.submenu-restore').addClass('in');

            $('#jobsModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#jobsModal').modal('show');
        });

        function minmizeSideBar() {
            $('.main-content-div').removeClass('col-sm-10').addClass('col-sm-12');
            $('.navbarLayout').removeClass('col-sm-10').addClass('width-94');
            $('.logo-outer-div-min').removeClass('hideMenu');
            $('.leftNavBar-min').removeClass('hideMenu');
            $('.logo-outer-div-max').addClass('hideMenu');
            $('.leftNavBar-max').addClass('hideMenu');
        }
        //-------------------------------------------------//
        let mailboxId = '-1';
        let folderId = '-1';
        let folderType = 'None';
        let allowedDates = [];
        let globalItemsTable;
        let offset = 1;
        let totalRecordsShown = 0;
        let stopLoading = false;
        let isDone = false;
        let loadAll = false;
        let stoppingLimit = "{{ $data['itemsStoppingLimit'] }}";
        let warningLimit = "{{ $data['itemsWarningLimit'] }}";
        let limit = "{{ $data['itemsLimit'] }}";
        //-------------------------------------------------//
        let tableSettings = {
            'ajax': {
                "type": "POST",
                "url": "{{ url('getMailBoxFolderItems') }}",
                "dataSrc": function(data) {
                    if ($(".itemsTable").hasClass('hide'))
                        return [];
                    return data;
                },
                "data": function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.offset = 0;
                    d.mailboxId = getMailBox();
                    d.mailboxTitle = $('#' + getMailBox()).html();
                    d.folderId = getMailBoxFolder();
                    d.folderTitle = $('#' + getMailBoxFolder()).html();
                },
                "beforeSend": function() {
                    mailBoxCheckChange();
                    mailBoxFolderCheckChange();
                    mailBoxFolderItemsCheckChange();
                    if (getMailBox() == "-1") {
                        $('.jobsTable tbody').html(
                            '<tr class="odd">' +
                            '<td valign="top" colspan="35" class="dataTables_empty">No data available in table</td>' +
                            '</tr>'
                        );
                        return false;
                    }
                    let folderId = getMailBoxFolder();
                    $('.jobsTable tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="35" class="dataTables_empty processing_row"><span class="table-spinner"></span></td>' +
                        '</tr>'
                    );
                },
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
            "order": [
                [1, 'asc']
            ],
            "createdRow": function(row, data, rowIndex) {
                $.each($('td', row), function(colIndex) {
                    if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
                        $(this).attr('title', $(this).html());
                    }
                });
            },
            'columns': [{
                    "data": null,
                    "class": "after-none",
                    "width": "5%",
                    render: function(data, type, full, meta) {
                        return '<label class="custom-top-left checkbox-container checkbox-search left-17">&nbsp;' +
                            '<input type="hidden" class="mailboxId" value="' + data.mailboxId +
                            '">' +
                            '<input type="hidden" class="mailboxTitle" value="' + data
                            .mailboxTitle + '">' +
                            '<input type="hidden" class="folderId" value="' + data.folderId +
                            '">' +
                            '<input type="hidden" class="folderTitle" value="' + data
                            .folderTitle + '">' +
                            '<input type="checkbox" class="mailBoxFolderItemCheck form-check-input" value="' +
                            data.id + '"/>' +
                            '<span class="tree-checkBox check-mark-white check-mark"></span></label>';
                    }
                },
                {
                    "data": "from",
                    "width": "15%",
                    "class": "text-left fromColumn"
                },
                {
                    "width": "15%",
                    "data": "to",
                    "class": "toColumn text-left"
                },
                {
                    "width": "20%",
                    "data": "subject",
                    "class": "subjectColumn wrap"
                },
                {
                    "width": "7.5%",
                    "data": "cc"
                },
                {
                    "width": "7.5%",
                    "data": "bcc"
                },
                {
                    "width": "10%",
                    "data": null,
                    render: function(data) {
                        return formatDate(data.sent);
                    }
                },
                {
                    "width": "10%",
                    "data": null,
                    render: function(data) {
                        return formatDate(data.received);
                    }
                },
                {
                    "data": null,
                    "class": "after-none",
                    "width": "5%",
                    "title": '<img class= "tableIcone w-13 mr-0" src="/svg/download\.svg " title="Download">',
                    render: function(data, type, full, meta) {
                        @if ($role->hasPermissionTo('exchange_view_item_details'))
                            return '<img class= "hand tableIcone downloadMail w-13 mr-0" src="/svg/download\.svg " title="Download">';
                        @endif
                    }
                }
            ],
            dom: 'Bfrtip',
            buttons: [{
                text: '<img src="/svg/filter.svg" class="custom-filter-padding5">',
                titleAttr: 'Advanced Search',
                action: function(e, dt, node, config) {
                    $('#searchModal').modal('show');
                }
            }],
            "fnDrawCallback": function(data) {
                //-------------------------------------//
                resetTotalItems();
                if ($("#itemsTable_wrapper .dataTables_filter label").find('.search-icon').length == 0) {
                    var icon =
                        '<div class="search-container"><img class="search-icon mt--7" src="/svg/search.svg"></div>';
                    $('#itemsTable_wrapper .dataTables_filter label').append(icon);
                }
                $('.dataTables_filter input').addClass('form_input form-control custom-search');
                //-------------------------------------//
                mailBoxCheckChange();
                mailBoxFolderCheckChange();
                mailBoxFolderItemsCheckChange();
                //-------------------------------------//
                $('.mailBoxFolderItemCheck').change(mailBoxFolderItemsCheckChange);
                $('.tableIcone.downloadMail').unbind('click').click(function() {
                    var tr = $(this).closest('tr');
                    $('tr.current').removeClass('current');
                    tr.addClass('current');
                    downloadSingleMail();
                });
                //-------------------------------------//
                $('.dataTables_scrollHeadInner').addClass('itemsTableHeader');
                //-------------------------------------//
                let tableDataCount = $('#itemsTable').DataTable().data().count();

                if (tableDataCount > 0 && !stopLoading) {
                    increaseTotalItems($('#itemsTable').DataTable().data().count());
                    $('.countingLabel').html(getTotalItems() + ' Items Shown');
                    $('.searchingLabel').removeClass('hide');
                    $('.stopLoad').removeClass('hide');
                    $('.resumeLoad').addClass('hide');
                    $('.moveToEDiscovery').addClass('mr-2 ml-2');

                    if ($('#itemsTable').DataTable().data().count() >= stoppingLimit && !loadAll) {
                        $('.warningRow').addClass('hide');
                        $('.stoppingRow').removeClass('hide');
                        stopLoad();
                    } else if ($('#itemsTable').DataTable().data().count() > warningLimit) {
                        $('.warningRow').removeClass('hide');
                        $('.stoppingRow').addClass('hide');
                    }
                    if ((getTotalItems() % limit != 0) || getTotalItems() == 0) {
                        $('.searchingLabel').addClass('hide');
                        $('.stopLoad').addClass('hide');
                        $('.resumeLoad').addClass('hide');
                        $('.moveToEDiscovery').removeClass('mr-2 ml-2');

                        isDone = true;
                    } else if (!stopLoading) {
                        setTimeout(function() {
                            loadItems();
                        }, 1000);
                    }
                } else if (tableDataCount > 0 && stopLoading && !isDone) {
                    stopLoad();
                }

                changeSearchModal();
                adjustTable();
                //-------------------------------------//
            },
            "scrollY": "400px",
            "scrollX": false,
            "bInfo": false,
            "paging": false,
            "autoWidth": true,
            "processing": false,
            language: {
                search: "",
                searchPlaceholder: "Search...",
                'loadingRecords': '&nbsp;',
                'processing': '<div class="spinner"></div>'
            },
            'columnDefs': [{
                'targets': [0, 8], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }]
        };
        //-------------------------------------------------//
        let contactsTableSettings = {
            'ajax': {
                "type": "POST",
                "url": "{{ url('getMailBoxFolderItems') }}",
                "dataSrc": function(data) {
                    if ($(".ontactsTable").hasClass('hide'))
                        return [];
                    return data;
                },
                "data": function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.offset = 0;
                    d.mailboxId = getMailBox();
                    d.mailboxTitle = $('#' + getMailBox()).html();
                    d.folderId = getMailBoxFolder();
                    d.folderTitle = $('#' + getMailBoxFolder()).html();
                },
                "beforeSend": function() {
                    mailBoxCheckChange();
                    mailBoxFolderCheckChange();
                    mailBoxFolderItemsCheckChange();
                    if (getMailBox() == "-1") {
                        $('.jobsTable tbody').html(
                            '<tr class="odd">' +
                            '<td valign="top" colspan="35" class="dataTables_empty">No data available in table</td>' +
                            '</tr>'
                        );
                        return false;
                    }
                    let folderId = getMailBoxFolder();
                    $('.jobsTable tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="35" class="dataTables_empty processing_row"><span class="table-spinner"></span></td>' +
                        '</tr>'
                    );
                },
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
            "order": [
                [1, 'asc']
            ],
            "createdRow": function(row, data, rowIndex) {
                $.each($('td', row), function(colIndex) {
                    if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
                        $(this).attr('title', $(this).html());
                    }
                });
            },
            'columns': [{
                    "data": null,
                    "class": "after-none",
                    "width": "5%",
                    render: function(data, type, full, meta) {
                        return '<label class="custom-top-left checkbox-container checkbox-search left-17">&nbsp;' +
                            '<input type="hidden" class="mailboxId" value="' + data.mailboxId +
                            '">' +
                            '<input type="hidden" class="mailboxTitle" value="' + data
                            .mailboxTitle + '">' +
                            '<input type="hidden" class="folderId" value="' + data.folderId +
                            '">' +
                            '<input type="hidden" class="folderTitle" value="' + data
                            .folderTitle + '">' +
                            '<input type="checkbox" class="mailBoxFolderItemCheck form-check-input" value="' +
                            data.id + '"/>' +
                            '<span class="tree-checkBox check-mark-white check-mark"></span></label>';
                    }
                },
                {
                    "data": "fullName",
                    "class": "contactColumn text-left subjectColumn wrap",
                    "width": "20%",
                },
                {
                    "data": "mobile",
                    "class": "contactColumn",
                    "width": "10%",
                },
                {
                    "data": "homePhone",
                    "class": "contactColumn",
                    "width": "10%",
                },
                {
                    "data": "address",
                    "class": "contactColumn",
                    "width": "10%",
                },
                {
                    "data": "company",
                    "class": "contactColumn",
                    "width": "10%",
                },
                {
                    "data": "email",
                    "class": "contactColumn",
                    "width": "10%",
                },
                {
                    "data": "businessPhone",
                    "class": "contactColumn",
                    "width": "10%",
                },
                {
                    "data": "webPage",
                    "class": "contactColumn",
                    "width": "10%",
                },
                {
                    "data": null,
                    "class": "after-none",
                    "width": "5%",
                    "title": '<img class= "tableIcone w-13 mr-0" src="/svg/download\.svg " title="Download">',
                    render: function(data, type, full, meta) {
                        return '<img class= "hand tableIcone downloadMail w-13 mr-0" src="/svg/download\.svg " title="Download">';
                    }
                }
            ],
            dom: 'Bfrtip',
            buttons: [{
                text: '<img src="/svg/filter.svg" class="custom-filter-padding5">',
                titleAttr: 'Advanced Search',
                action: function(e, dt, node, config) {
                    $('#searchModal').modal('show');
                }
            }],
            "fnDrawCallback": function(data) {
                //-------------------------------------//

                //-------------------------------------//
                resetTotalItems();
                if ($("#contactsTable_wrapper .dataTables_filter label").find('.search-icon').length == 0) {
                    var icon =
                        '<div class="search-container"><img class="search-icon mt--7" src="/svg/search.svg"></div>';
                    $('#contactsTable_wrapper .dataTables_filter label').append(icon);
                }
                $('.dataTables_filter input').addClass('form_input form-control custom-search');
                //-------------------------------------//
                mailBoxCheckChange();
                mailBoxFolderCheckChange();
                mailBoxFolderItemsCheckChange();
                //-------------------------------------//
                $('.mailBoxFolderItemCheck').change(mailBoxFolderItemsCheckChange);
                $('.tableIcone.downloadMail').unbind('click').click(function() {
                    var tr = $(this).closest('tr');
                    $('tr.current').removeClass('current');
                    tr.addClass('current');
                    downloadSingleMail();
                });
                //-------------------------------------//
                $('.dataTables_scrollHeadInner').addClass('itemsTableHeader');
                //-------------------------------------//
                let tableDataCount = $('#contactsTable').DataTable().data().count();
                if (tableDataCount > 0 && !stopLoading) {
                    increaseTotalItems($('#contactsTable').DataTable().data().count());
                    $('.countingLabel').html(getTotalItems() + ' Items Shown');

                    $('.searchingLabel').removeClass('hide');
                    $('.stopLoad').removeClass('hide');
                    $('.resumeLoad').addClass('hide');
                    $('.moveToEDiscovery').addClass('mr-2 ml-2');

                    if ($('#contactsTable').DataTable().data().count() >= stoppingLimit && !loadAll) {
                        $('.warningRow').addClass('hide');
                        $('.stoppingRow').removeClass('hide');
                        stopLoad();
                    } else if ($('#contactsTable').DataTable().data().count() > warningLimit) {
                        $('.warningRow').removeClass('hide');
                        $('.stoppingRow').addClass('hide');
                    }
                    if ((getTotalItems() % limit != 0) || getTotalItems() == 0) {
                        $('.searchingLabel').addClass('hide');
                        $('.stopLoad').addClass('hide');
                        $('.resumeLoad').addClass('hide');
                        $('.moveToEDiscovery').removeClass('mr-2 ml-2');

                        isDone = true;
                    } else if (!stopLoading) {
                        setTimeout(function() {
                            loadItems();
                        }, 1000);
                    }
                } else if (tableDataCount > 0 && stopLoading && !isDone) {
                    stopLoad();
                }

                adjustTable();
                changeSearchModal();
                //-------------------------------------//
            },
            "scrollY": "400px",
            "scrollX": false,
            "bInfo": false,
            "paging": false,
            "autoWidth": true,
            "processing": false,
            language: {
                search: "",
                searchPlaceholder: "Search...",
                'loadingRecords': '&nbsp;',
                'processing': '<div class="spinner"></div>'
            },
            'columnDefs': [{
                'targets': [0, 9], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }]
        };
        //-------------------------------------------------//
        let notesTableSettings = {
            'ajax': {
                "type": "POST",
                "url": "{{ url('getMailBoxFolderItems') }}",
                "dataSrc": function(data) {
                    if ($(".notesTable").hasClass('hide'))
                        return [];
                    return data;
                },
                "data": function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.offset = 0;
                    d.mailboxId = getMailBox();
                    d.mailboxTitle = $('#' + getMailBox()).html();
                    d.folderId = getMailBoxFolder();
                    d.folderTitle = $('#' + getMailBoxFolder()).html();
                },
                "beforeSend": function() {
                    mailBoxCheckChange();
                    mailBoxFolderCheckChange();
                    mailBoxFolderItemsCheckChange();
                    if (getMailBox() == "-1") {
                        $('.jobsTable tbody').html(
                            '<tr class="odd">' +
                            '<td valign="top" colspan="35" class="dataTables_empty">No data available in table</td>' +
                            '</tr>'
                        );
                        return false;
                    }
                    let folderId = getMailBoxFolder();
                    $('.jobsTable tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="35" class="dataTables_empty processing_row"><span class="table-spinner"></span></td>' +
                        '</tr>'
                    );
                },
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
            "order": [
                [1, 'asc']
            ],
            "createdRow": function(row, data, rowIndex) {
                $.each($('td', row), function(colIndex) {
                    if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
                        $(this).attr('title', $(this).html());
                    }
                });
            },
            'columns': [{
                    "data": null,
                    "class": "after-none",
                    "width": "5%",
                    render: function(data, type, full, meta) {
                        return '<label class="custom-top-left checkbox-container checkbox-search left-17">&nbsp;' +
                            '<input type="hidden" class="mailboxId" value="' + data.mailboxId +
                            '">' +
                            '<input type="hidden" class="mailboxTitle" value="' + data
                            .mailboxTitle + '">' +
                            '<input type="hidden" class="folderId" value="' + data.folderId +
                            '">' +
                            '<input type="hidden" class="folderTitle" value="' + data
                            .folderTitle + '">' +
                            '<input type="checkbox" class="mailBoxFolderItemCheck form-check-input" value="' +
                            data.id + '"/>' +
                            '<span class="tree-checkBox check-mark-white check-mark"></span></label>';
                    }
                },
                {
                    "data": "name",
                    "class": "text-left noteColumn",
                    "width": "45%",
                },
                {
                    "data": null,
                    "class": "noteColumn subjectColumn wrap",
                    "width": "45%",
                    render: function(data) {
                        return formatDate(data.date);
                    }
                },
                {
                    "data": null,
                    "class": "after-none",
                    "width": "5%",
                    "title": '<img class= "tableIcone w-13 mr-0" src="/svg/download\.svg " title="Download">',
                    render: function(data, type, full, meta) {
                        return '<img class= "hand tableIcone downloadMail w-13 mr-0" src="/svg/download\.svg " title="Download">';
                    }
                }
            ],
            dom: 'Bfrtip',
            buttons: [{
                text: '<img src="/svg/filter.svg" class="custom-filter-padding5">',
                titleAttr: 'Advanced Search',
                action: function(e, dt, node, config) {
                    $('#searchModal').modal('show');
                }
            }],
            "fnDrawCallback": function(data) {
                //-------------------------------------//
                $('.countingLabel').html(getTotalItems() + ' Items Shown');
                //-------------------------------------//
                resetTotalItems();
                if ($("#notesTable_wrapper .dataTables_filter label").find('.search-icon').length == 0) {
                    var icon =
                        '<div class="search-container"><img class="search-icon mt--7" src="/svg/search.svg"></div>';
                    $('#notesTable_wrapper .dataTables_filter label').append(icon);
                }
                $('.dataTables_filter input').addClass('form_input form-control custom-search');
                //-------------------------------------//
                mailBoxCheckChange();
                mailBoxFolderCheckChange();
                mailBoxFolderItemsCheckChange();
                //-------------------------------------//
                $('.mailBoxFolderItemCheck').change(mailBoxFolderItemsCheckChange);
                $('.tableIcone.downloadMail').unbind('click').click(function() {
                    var tr = $(this).closest('tr');
                    $('tr.current').removeClass('current');
                    tr.addClass('current');
                    downloadSingleMail();
                });
                //-------------------------------------//
                $('.dataTables_scrollHeadInner').addClass('itemsTableHeader');
                //-------------------------------------//
                let tableDataCount = $('#notesTable').DataTable().data().count();
                if (tableDataCount > 0 && !stopLoading) {
                    increaseTotalItems($('#notesTable').DataTable().data().count());
                    $('.searchingLabel').removeClass('hide');
                    $('.stopLoad').removeClass('hide');
                    $('.resumeLoad').addClass('hide');
                    $('.moveToEDiscovery').addClass('mr-2 ml-2');

                    if ($('#notesTable').DataTable().data().count() >= stoppingLimit && !loadAll) {
                        $('.warningRow').addClass('hide');
                        $('.stoppingRow').removeClass('hide');
                        stopLoad();
                    } else if ($('#notesTable').DataTable().data().count() > warningLimit) {
                        $('.warningRow').removeClass('hide');
                        $('.stoppingRow').addClass('hide');
                    }
                    if ((getTotalItems() % limit != 0) || getTotalItems() == 0) {
                        $('.searchingLabel').addClass('hide');
                        $('.stopLoad').addClass('hide');
                        $('.resumeLoad').addClass('hide');
                        $('.moveToEDiscovery').removeClass('mr-2 ml-2');

                        isDone = true;
                    } else if (!stopLoading) {
                        setTimeout(function() {
                            loadItems();
                        }, 1000);
                    }
                } else if (tableDataCount > 0 && stopLoading && !isDone) {
                    stopLoad();
                }

                adjustTable();
                changeSearchModal();
                //-------------------------------------//
            },
            "scrollY": "400px",
            "scrollX": false,
            "bInfo": false,
            "paging": false,
            "autoWidth": true,
            "processing": false,
            language: {
                search: "",
                searchPlaceholder: "Search...",
                'loadingRecords': '&nbsp;',
                'processing': '<div class="spinner"></div>'
            },
            'columnDefs': [{
                'targets': [0, 3], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }]
        };
        //-------------------------------------------------//
        let journalsTableSettings = {
            'ajax': {
                "type": "POST",
                "url": "{{ url('getMailBoxFolderItems') }}",
                "dataSrc": function(data) {
                    if ($(".journalsTable").hasClass('hide'))
                        return [];
                    return data;
                },
                "data": function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.offset = 0;
                    d.mailboxId = getMailBox();
                    d.mailboxTitle = $('#' + getMailBox()).html();
                    d.folderId = getMailBoxFolder();
                    d.folderTitle = $('#' + getMailBoxFolder()).html();
                },
                "beforeSend": function() {
                    mailBoxCheckChange();
                    mailBoxFolderCheckChange();
                    mailBoxFolderItemsCheckChange();
                    if (getMailBox() == "-1") {
                        $('.jobsTable tbody').html(
                            '<tr class="odd">' +
                            '<td valign="top" colspan="35" class="dataTables_empty">No data available in table</td>' +
                            '</tr>'
                        );
                        return false;
                    }
                    let folderId = getMailBoxFolder();
                    $('.jobsTable tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="35" class="dataTables_empty processing_row"><span class="table-spinner"></span></td>' +
                        '</tr>'
                    );
                },
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
            "order": [
                [1, 'asc']
            ],
            "createdRow": function(row, data, rowIndex) {
                $.each($('td', row), function(colIndex) {
                    if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
                        $(this).attr('title', $(this).html());
                    }
                });
            },
            'columns': [{
                    "data": null,
                    "class": "after-none",
                    "width": "5%",
                    render: function(data, type, full, meta) {
                        return '<label class="custom-top-left checkbox-container checkbox-search left-17">&nbsp;' +
                            '<input type="hidden" class="mailboxId" value="' + data.mailboxId +
                            '">' +
                            '<input type="hidden" class="mailboxTitle" value="' + data
                            .mailboxTitle + '">' +
                            '<input type="hidden" class="folderId" value="' + data.folderId +
                            '">' +
                            '<input type="hidden" class="folderTitle" value="' + data
                            .folderTitle + '">' +
                            '<input type="checkbox" class="mailBoxFolderItemCheck form-check-input" value="' +
                            data.id + '"/>' +
                            '<span class="tree-checkBox check-mark-white check-mark"></span></label>';
                    }
                },
                {
                    "data": "subject",
                    "width": "30%",
                    "class": "journalColumn text-left subjectColumn wrap"
                },
                {
                    "data": null,
                    "class": "journalColumn",
                    "width": "20%",
                    render: function(data) {
                        return formatDate(data.startDate);
                    }
                },
                {
                    "data": "duration",
                    "width": "20%",
                    "class": "journalColumn"
                },
                {
                    "data": "entryType",
                    "width": "20%",
                    "class": "journalColumn"
                },
                {
                    "data": null,
                    "class": "after-none",
                    "width": "5%",
                    "title": '<img class= "tableIcone w-13 mr-0" src="/svg/download\.svg " title="Download">',
                    render: function(data, type, full, meta) {
                        return '<img class= "hand tableIcone downloadMail w-13 mr-0" src="/svg/download\.svg " title="Download">';
                    }
                }
            ],
            dom: 'Bfrtip',
            buttons: [{
                text: '<img src="/svg/filter.svg" class="custom-filter-padding5">',
                titleAttr: 'Advanced Search',
                action: function(e, dt, node, config) {
                    $('#searchModal').modal('show');
                }
            }],
            "fnDrawCallback": function(data) {
                //-------------------------------------//
                $('.countingLabel').html(getTotalItems() + ' Items Shown');
                //-------------------------------------//
                resetTotalItems();
                if ($("#journalsTable_wrapper .dataTables_filter label").find('.search-icon').length == 0) {
                    var icon =
                        '<div class="search-container"><img class="search-icon mt--7" src="/svg/search.svg"></div>';
                    $('#journalsTable_wrapper .dataTables_filter label').append(icon);
                }
                $('.dataTables_filter input').addClass('form_input form-control custom-search');
                //-------------------------------------//
                mailBoxCheckChange();
                mailBoxFolderCheckChange();
                mailBoxFolderItemsCheckChange();
                //-------------------------------------//
                $('.mailBoxFolderItemCheck').change(mailBoxFolderItemsCheckChange);
                $('.tableIcone.downloadMail').unbind('click').click(function() {
                    var tr = $(this).closest('tr');
                    $('tr.current').removeClass('current');
                    tr.addClass('current');
                    downloadSingleMail();
                });
                //-------------------------------------//
                $('.dataTables_scrollHeadInner').addClass('itemsTableHeader');
                //-------------------------------------//
                let tableDataCount = $('#journalsTable').DataTable().data().count();
                if (tableDataCount > 0 && !stopLoading) {
                    increaseTotalItems($('#journalsTable').DataTable().data().count());
                    $('.searchingLabel').removeClass('hide');

                    $('.stopLoad').removeClass('hide');
                    $('.resumeLoad').addClass('hide');
                    $('.moveToEDiscovery').addClass('mr-2 ml-2');

                    if ($('#journalsTable').DataTable().data().count() >= stoppingLimit && !loadAll) {
                        $('.warningRow').addClass('hide');
                        $('.stoppingRow').removeClass('hide');
                        stopLoad();
                    } else if ($('#journalsTable').DataTable().data().count() > warningLimit) {
                        $('.warningRow').removeClass('hide');
                        $('.stoppingRow').addClass('hide');
                    }
                    if ((getTotalItems() % limit != 0) || getTotalItems() == 0) {
                        $('.searchingLabel').addClass('hide');
                        $('.stopLoad').addClass('hide');
                        $('.resumeLoad').addClass('hide');
                        $('.moveToEDiscovery').removeClass('mr-2 ml-2');

                        isDone = true;
                    } else if (!stopLoading) {
                        setTimeout(function() {
                            loadItems();
                        }, 1000);
                    }
                } else if (tableDataCount > 0 && stopLoading && !isDone) {
                    stopLoad();
                }

                adjustTable();
                changeSearchModal();
                //-------------------------------------//
            },
            "scrollY": "400px",
            "scrollX": false,
            "bInfo": false,
            "paging": false,
            "autoWidth": true,
            "processing": false,
            language: {
                search: "",
                searchPlaceholder: "Search...",
                'loadingRecords': '&nbsp;',
                'processing': '<div class="spinner"></div>'
            },
            'columnDefs': [{
                'targets': [0, 5], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }]
        };
        //-------------------------------------------------//
        let appointmentsTableSettings = {
            'ajax': {
                "type": "POST",
                "url": "{{ url('getMailBoxFolderItems') }}",
                "dataSrc": function(data) {
                    if ($(".appointmentsTable").hasClass('hide'))
                        return [];
                    return data;
                },
                "data": function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.offset = 0;
                    d.mailboxId = getMailBox();
                    d.mailboxTitle = $('#' + getMailBox()).html();
                    d.folderId = getMailBoxFolder();
                    d.folderTitle = $('#' + getMailBoxFolder()).html();
                },
                "beforeSend": function() {
                    mailBoxCheckChange();
                    mailBoxFolderCheckChange();
                    mailBoxFolderItemsCheckChange();
                    if (getMailBox() == "-1") {
                        $('.jobsTable tbody').html(
                            '<tr class="odd">' +
                            '<td valign="top" colspan="35" class="dataTables_empty">No data available in table</td>' +
                            '</tr>'
                        );
                        return false;
                    }
                    let folderId = getMailBoxFolder();
                    $('.jobsTable tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="35" class="dataTables_empty processing_row"><span class="table-spinner"></span></td>' +
                        '</tr>'
                    );
                },
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
            "order": [
                [1, 'asc']
            ],
            "createdRow": function(row, data, rowIndex) {
                $.each($('td', row), function(colIndex) {
                    if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
                        $(this).attr('title', $(this).html());
                    }
                });
            },
            'columns': [{
                    "data": null,
                    "class": "after-none",
                    "width": "5%",
                    render: function(data, type, full, meta) {
                        return '<label class="custom-top-left checkbox-container checkbox-search left-17">&nbsp;' +
                            '<input type="hidden" class="mailboxId" value="' + data.mailboxId +
                            '">' +
                            '<input type="hidden" class="mailboxTitle" value="' + data
                            .mailboxTitle + '">' +
                            '<input type="hidden" class="folderId" value="' + data.folderId +
                            '">' +
                            '<input type="hidden" class="folderTitle" value="' + data
                            .folderTitle + '">' +
                            '<input type="checkbox" class="mailBoxFolderItemCheck form-check-input" value="' +
                            data.id + '"/>' +
                            '<span class="tree-checkBox check-mark-white check-mark"></span></label>';
                    }
                },
                {
                    "data": "organizer",
                    "width": "10%",
                    "class": "text-left appointmentColumn"
                },
                {
                    "data": "subject",
                    "width": "20%",
                    "class": "appointmentColumn subjectColumn wrap"
                },
                {
                    "data": "attendees",
                    "width": "10%",
                    "class": "appointmentColumn"
                },
                {
                    "data": "recurrencePatternFormat",
                    "width": "10%",
                    "class": "appointmentColumn"
                },
                {
                    "data": "location",
                    "width": "10%",
                    "class": "appointmentColumn"
                },
                {
                    "data": null,
                    "class": "appointmentColumn",
                    "width": "10%",
                    render: function(data) {
                        return formatDate(data.startTime);
                    }
                },
                {
                    "data": null,
                    "class": "appointmentColumn",
                    "width": "10%",
                    render: function(data) {
                        return formatDate(data.endTime);
                    }
                },
                {
                    "data": null,
                    "class": "after-none",
                    "width": "5%",
                    "title": '<img class= "tableIcone w-13 mr-0" src="/svg/download\.svg " title="Download">',
                    render: function(data, type, full, meta) {
                        return '<img class= "hand tableIcone downloadMail w-13 mr-0" src="/svg/download\.svg " title="Download">';
                    }
                }
            ],
            dom: 'Bfrtip',
            buttons: [{
                text: '<img src="/svg/filter.svg" class="custom-filter-padding5">',
                titleAttr: 'Advanced Search',
                action: function(e, dt, node, config) {
                    $('#searchModal').modal('show');
                }
            }],
            "fnDrawCallback": function(data) {
                //-------------------------------------//
                $('.countingLabel').html(getTotalItems() + ' Items Shown');

                //-------------------------------------//
                resetTotalItems();
                if ($("#appointmentsTable_wrapper .dataTables_filter label").find('.search-icon').length == 0) {
                    var icon =
                        '<div class="search-container"><img class="search-icon mt--7" src="/svg/search.svg"></div>';
                    $('#appointmentsTable_wrapper .dataTables_filter label').append(icon);
                }
                $('.dataTables_filter input').addClass('form_input form-control custom-search');
                //-------------------------------------//
                mailBoxCheckChange();
                mailBoxFolderCheckChange();
                mailBoxFolderItemsCheckChange();
                //-------------------------------------//
                $('.mailBoxFolderItemCheck').change(mailBoxFolderItemsCheckChange);
                $('.tableIcone.downloadMail').unbind('click').click(function() {
                    var tr = $(this).closest('tr');
                    $('tr.current').removeClass('current');
                    tr.addClass('current');
                    downloadSingleMail();
                });
                //-------------------------------------//
                $('.dataTables_scrollHeadInner').addClass('itemsTableHeader');
                //-------------------------------------//
                let tableDataCount = $('#appointmentsTable').DataTable().data().count();
                if (tableDataCount > 0 && !stopLoading) {
                    increaseTotalItems($('#appointmentsTable').DataTable().data().count());
                    $('.searchingLabel').removeClass('hide');
                    $('.stopLoad').removeClass('hide');
                    $('.resumeLoad').addClass('hide');
                    $('.moveToEDiscovery').addClass('mr-2 ml-2');

                    if ($('#appointmentsTable').DataTable().data().count() >= stoppingLimit && !loadAll) {
                        $('.warningRow').addClass('hide');
                        $('.stoppingRow').removeClass('hide');
                        stopLoad();
                    } else if ($('#appointmentsTable').DataTable().data().count() > warningLimit) {
                        $('.warningRow').removeClass('hide');
                        $('.stoppingRow').addClass('hide');
                    }
                    if ((getTotalItems() % limit != 0) || getTotalItems() == 0) {
                        $('.searchingLabel').addClass('hide');
                        $('.stopLoad').addClass('hide');
                        $('.resumeLoad').addClass('hide');
                        $('.moveToEDiscovery').removeClass('mr-2 ml-2');

                        isDone = true;
                    } else if (!stopLoading) {
                        setTimeout(function() {
                            loadItems();
                        }, 1000);
                    }
                } else if (tableDataCount > 0 && stopLoading && !isDone) {
                    stopLoad();
                }

                adjustTable();
                changeSearchModal();
                //-------------------------------------//
            },
            "scrollY": "400px",
            "scrollX": false,
            "bInfo": false,
            "paging": false,
            "autoWidth": true,
            "processing": false,
            language: {
                search: "",
                searchPlaceholder: "Search...",
                'loadingRecords': '&nbsp;',
                'processing': '<div class="spinner"></div>'
            },
            'columnDefs': [{
                'targets': [0, 8], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }]
        };
        //-------------------------------------------------//
        let tasksTableSettings = {
            'ajax': {
                "type": "POST",
                "url": "{{ url('getMailBoxFolderItems') }}",
                "dataSrc": function(data) {
                    if ($(".tasksTable").hasClass('hide'))
                        return [];
                    return data;
                },
                "data": function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.offset = 0;
                    d.mailboxId = getMailBox();
                    d.mailboxTitle = $('#' + getMailBox()).html();
                    d.folderId = getMailBoxFolder();
                    d.folderTitle = $('#' + getMailBoxFolder()).html();
                },
                "beforeSend": function() {
                    mailBoxCheckChange();
                    mailBoxFolderCheckChange();
                    mailBoxFolderItemsCheckChange();
                    if (getMailBox() == "-1") {
                        $('.jobsTable tbody').html(
                            '<tr class="odd">' +
                            '<td valign="top" colspan="35" class="dataTables_empty">No data available in table</td>' +
                            '</tr>'
                        );
                        return false;
                    }
                    let folderId = getMailBoxFolder();
                    $('.jobsTable tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="35" class="dataTables_empty processing_row"><span class="table-spinner"></span></td>' +
                        '</tr>'
                    );
                },
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
            "order": [
                [1, 'asc']
            ],
            "createdRow": function(row, data, rowIndex) {
                $.each($('td', row), function(colIndex) {
                    if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
                        $(this).attr('title', $(this).html());
                    }
                });
            },
            'columns': [{
                    "data": null,
                    "class": "after-none",
                    "width": "5%",
                    render: function(data, type, full, meta) {
                        return '<label class="custom-top-left checkbox-container checkbox-search left-17">&nbsp;' +
                            '<input type="hidden" class="mailboxId" value="' + data.mailboxId +
                            '">' +
                            '<input type="hidden" class="mailboxTitle" value="' + data
                            .mailboxTitle + '">' +
                            '<input type="hidden" class="folderId" value="' + data.folderId +
                            '">' +
                            '<input type="hidden" class="folderTitle" value="' + data
                            .folderTitle + '">' +
                            '<input type="checkbox" class="mailBoxFolderItemCheck form-check-input" value="' +
                            data.id + '"/>' +
                            '<span class="tree-checkBox check-mark-white check-mark"></span></label>';
                    }
                },
                {
                    "data": "owner",
                    "width": "20%",
                    "class": "tasksColumn text-left subjectColumn wrap"
                },
                {
                    "data": "status",
                    "width": "20%",
                    "class": "tasksColumn"
                },
                {
                    "data": "percentComplete",
                    "width": "20%",
                    "class": "tasksColumn"
                },
                {
                    "data": null,
                    "width": "15%",
                    "class": "tasksColumn",
                    render: function(data) {
                        return formatDate(data.startDate);
                    }
                },
                {
                    "data": null,
                    "width": "15%",
                    "class": "tasksColumn",
                    render: function(data) {
                        return formatDate(data.dueDate);
                    }
                },
                {
                    "data": null,
                    "class": "after-none",
                    "width": "5%",
                    "title": '<img class= "tableIcone w-13 mr-0" src="/svg/download\.svg " title="Download">',
                    render: function(data, type, full, meta) {
                        return '<img class= "hand tableIcone downloadMail w-13 mr-0" src="/svg/download\.svg " title="Download">';
                    }
                }
            ],
            dom: 'Bfrtip',
            buttons: [{
                text: '<img src="/svg/filter.svg" class="custom-filter-padding5">',
                titleAttr: 'Advanced Search',
                action: function(e, dt, node, config) {
                    $('#searchModal').modal('show');
                }
            }],
            "fnDrawCallback": function(data) {
                //-------------------------------------//

                $('.countingLabel').html(getTotalItems() + ' Items Shown');
                //-------------------------------------//
                resetTotalItems();
                if ($("#tasksTable_wrapper .dataTables_filter label").find('.search-icon').length == 0) {
                    var icon =
                        '<div class="search-container"><img class="search-icon mt--7" src="/svg/search.svg"></div>';
                    $('#tasksTable_wrapper .dataTables_filter label').append(icon);
                }
                $('.dataTables_filter input').addClass('form_input form-control custom-search');
                //-------------------------------------//
                mailBoxCheckChange();
                mailBoxFolderCheckChange();
                mailBoxFolderItemsCheckChange();
                //-------------------------------------//
                $('.mailBoxFolderItemCheck').change(mailBoxFolderItemsCheckChange);
                $('.tableIcone.downloadMail').unbind('click').click(function() {
                    var tr = $(this).closest('tr');
                    $('tr.current').removeClass('current');
                    tr.addClass('current');
                    downloadSingleMail();
                });
                //-------------------------------------//
                $('.dataTables_scrollHeadInner').addClass('itemsTableHeader');
                //-------------------------------------//
                let tableDataCount = $('#tasksTable').DataTable().data().count();
                if (tableDataCount > 0 && !stopLoading) {
                    increaseTotalItems($('#tasksTable').DataTable().data().count());

                    $('.searchingLabel').removeClass('hide');

                    $('.stopLoad').removeClass('hide');
                    $('.resumeLoad').addClass('hide');
                    $('.moveToEDiscovery').addClass('mr-2 ml-2');

                    if ($('#tasksTable').DataTable().data().count() >= stoppingLimit && !loadAll) {
                        $('.warningRow').addClass('hide');
                        $('.stoppingRow').removeClass('hide');
                        stopLoad();
                    } else if ($('#tasksTable').DataTable().data().count() > warningLimit) {
                        $('.warningRow').removeClass('hide');
                        $('.stoppingRow').addClass('hide');
                    }
                    if ((getTotalItems() % limit != 0) || getTotalItems() == 0) {
                        $('.searchingLabel').addClass('hide');
                        $('.stopLoad').addClass('hide');
                        $('.resumeLoad').addClass('hide');
                        $('.moveToEDiscovery').removeClass('mr-2 ml-2');

                        isDone = true;
                    } else if (!stopLoading) {
                        setTimeout(function() {
                            loadItems();
                        }, 1000);
                    }
                } else if (tableDataCount > 0 && stopLoading && !isDone) {
                    stopLoad();
                }

                changeSearchModal();
                //-------------------------------------//
                adjustTable();
            },
            "scrollY": "400px",
            "scrollX": false,
            "bInfo": false,
            "paging": false,
            "autoWidth": true,
            "processing": false,
            language: {
                search: "",
                searchPlaceholder: "Search...",
                'loadingRecords': '&nbsp;',
                'processing': '<div class="spinner"></div>'
            },
            'columnDefs': [{
                'targets': [0, 6], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }]
        };
        //-------------------------------------------------//
        function changeSearchModal() {
            if (folderType == "Task") {
                $('.searhRow').addClass('hide');
                $('.searhRow:not(.tasks-class) input').val("");
                $('.tasks-class').removeClass('hide');
            } else if (folderType == "Appointment") {
                $('.searhRow').addClass('hide');
                $('.searhRow:not(.calendar-class) input').val("");
                $('.calendar-class').removeClass('hide');
            } else if (folderType == "Journal") {
                $('.searhRow').addClass('hide');
                $('.searhRow:not(.journal-class) input').val("");
                $('.journal-class').removeClass('hide');
            } else if (folderType == "StickyNote") {
                $('.searhRow').addClass('hide');
                $('.searhRow:not(.note-class) input').val("");
                $('.note-class').removeClass('hide');
            } else if (folderType == "Contact") {
                $('.searhRow').addClass('hide');
                $('.searhRow:not(.contact-class) input').val("");
                $('.contact-class').removeClass('hide');
            } else {
                $('.searhRow').addClass('hide');
                $('.searhRow:not(.basic-class) input').val("");
                $('.basic-class').removeClass('hide');
            }
        }
        //-------------------------------------------------//
        $(document).ready(function() {

            minmizeSideBar();

            mailBoxCheckChange();
            $('.dropdown-menu.tree-filter-menu').on('click', function(event) {
                event.stopPropagation();
            });
            $('.filter-table td').click(function() {
                $(this).find('span').toggleClass('active');
            })
            $('.backupDate').keyup(function() {
                if ($(this).val() == '') {
                    $('.backupTime').html("");
                    $('.backupTime').append(new Option("Select Time", ""));
                }
            });
            $('#resetFilterTable').click(resetFilterTable);

            $('.treeSearchInput input').keyup(searchMailBox);

            $("input.date").datepicker({
                dateFormat: 'yy-mm-dd',
                onSelect: function() {
                    $('#' + getTableClass(folderType)).DataTable().draw();
                }
            });

            $(".backupDate").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: (d) => {
                    $('.backupTime').html("");
                    $(".backupTime").append(new Option("Select Time", ""));
                    allowedDates.forEach((e) => {
                        if (e.date == d) {
                            e.time.forEach((t) => {
                                $(".backupTime").append('<option value="' + t.id +
                                    '" data-job-id="' + t.job_id + '">' + t.time +
                                    '</option>');
                            })
                        }
                    });
                    $('.backupTime').removeAttr('disabled');
                },
                beforeShowDay: function(d) {
                    var dmy = "";
                    let found = false;
                    dmy += ("00" + d.getDate()).slice(-2) + "/";
                    dmy += ("00" + (d.getMonth() + 1)).slice(-2) + "/";
                    dmy += d.getFullYear();
                    if (allowedDates.length > 0) {
                        allowedDates.forEach((e) => {
                            if (dmy == e.date) {
                                found = true;
                                return;
                            }
                        });
                    }
                    return [found, ""];
                }
            });

            $('input.time').mdtimepicker();
            $('input.time').change(function() {
                $('#' + getTableClass(folderType)).DataTable().draw();
            });

            globalItemsTable = $('#itemsTable').DataTable(tableSettings);
            let tasksItemsTable = $('#tasksTable').DataTable(tasksTableSettings);
            let journalsItemsTable = $('#journalsTable').DataTable(journalsTableSettings);
            let appointementsItemsTable = $('#appointmentsTable').DataTable(appointmentsTableSettings);
            let notesItemsTable = $('#notesTable').DataTable(notesTableSettings);
            let contactsItemsTable = $('#contactsTable').DataTable(contactsTableSettings);

            $('#itemsTable').DataTable().buttons().container().prependTo('#itemsTable_filter');
            $('#tasksTable').DataTable().buttons().container().prependTo('#tasksTable_filter');
            $('#journalsTable').DataTable().buttons().container().prependTo('#journalsTable_filter ');
            $('#appointmentsTable').DataTable().buttons().container().prependTo('#appointmentsTable_filter ');
            $('#notesTable').DataTable().buttons().container().prependTo('#notesTable_filter ');
            $('#contactsTable').DataTable().buttons().container().prependTo('#contactsTable_filter ');


            $('#mailboxAnotherTable').DataTable({
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
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck">' +
                                '<span class="tree-checkBox check-mark-white check-mark"></span>' +
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

            $('#mailboxOriginalTable').DataTable({
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
                            if (data.resolved)
                                return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                    '<input type="checkbox" checked value="' + data.id +
                                    '" class="form-check-input mailboxCheck">' +
                                    '<span class="tree-checkBox check-mark-white check-mark"></span>' +
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

            $('#exportMailboxTable').DataTable({
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
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck">' +
                                '<span class="tree-checkBox check-mark-white check-mark"></span>' +
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

            $('#foldersResultsTable').DataTable({
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
                            if (data.resolved)
                                return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                    '<input type="checkbox" checked value="' + data.id +
                                    '" class="form-check-input mailboxCheck" data-mailbox-id="' +
                                    data
                                    .mailboxId + '">' +
                                    '<span class="tree-checkBox check-mark-white check-mark"></span>' +
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
                "scrollY": "127px",
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
                                '" class="form-check-input mailboxCheck" data-mailbox-id="' + data
                                .mailboxId + '">' +
                                '<span class="tree-checkBox check-mark-white check-mark"></span>' +
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
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-parentName="' + data
                                .parentName + '">' +
                                '<span class="tree-checkBox check-mark-white check-mark"></span>' +
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
                "scrollY": "160px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

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
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-parentName="' + data
                                .parentName + '">' +
                                '<span class="tree-checkBox check-mark-white check-mark"></span>' +
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
            //------------------------------------------//
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
            //------------------------------------------//
            $('.side-nav-icon').click(function() {
                $('#' + getTableClass(folderType)).DataTable().draw();
            });
            //------------------------------------------//
            $('form [name="enablePstSizeLimit"]').change(function() {
                if ($(this).prop('checked') == true) {
                    $(this).closest('form').find('[name="sizeLimit"]').attr('required', 'required');
                } else {
                    $(this).closest('form').find('[name="sizeLimit"]').removeAttr('required');
                }
            });
            //------------------------------------------//
            $('form [name="RecentItemRestorePeriod"]').change(function() {
                if ($(this).prop('checked') == true) {
                    $(this).closest('form').find('[name="daysNumber"]').attr('required', 'required');
                } else {
                    $(this).closest('form').find('[name="daysNumber"]').removeAttr('required');
                }
            });
            //------------------------------------------//
            $("#sentFrom").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $("#" + getTableClass(folderType)).DataTable().draw();
                }
            });
            $("#sentTo").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $('#' + getTableClass(folderType)).DataTable().draw();
                }
            });
            $("#taskStartFrom").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $('#' + getTableClass(folderType)).DataTable().draw();
                }
            });
            $("#taskStartTo").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $('#' + getTableClass(folderType)).DataTable().draw();
                }
            });
            $("#calendarStartFrom").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $('#' + getTableClass(folderType)).DataTable().draw();
                }
            });
            $("#calendarStartTo").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $('#' + getTableClass(folderType)).DataTable().draw();
                }
            });
            $("#journalStartFrom").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $('#' + getTableClass(folderType)).DataTable().draw();
                }
            });
            $("#journalStartTo").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $('#' + getTableClass(folderType)).DataTable().draw();
                }
            });
            $("#noteFrom").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $('#' + getTableClass(folderType)).DataTable().draw();
                }
            });
            $("#noteTo").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $('#' + getTableClass(folderType)).DataTable().draw();
                }
            });
        });
        //-------------------------------------------------//
        $(document).on('change', '.tree input.mailBoxCheck[type=checkbox]', function(e) {
            $(this).closest('div').siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);
            $(this).parentsUntil('.tree').children("input[type='checkbox']").prop('checked', this.checked);
            // Allow Single Mailbox folders
            if ($(this).parentsUntil('.tree').children("input[type='checkbox']").length > 0 && this.checked) {
                $(".tree").find("input.mailBoxFolderCheck[data-mailboxid!='" + $(this).val() + "']")
                    .prop("checked", false);
            }
            //---------------------------------//
            e.stopPropagation();
            mailBoxFolderCheckChange();
            mailBoxFolderItemsCheckChange();
        });
        //-------------------------------------------------//
        $("#jobs").change(function() {
            if (this.value != "") {
                $(".spinner_parent").css("display", "block");
                $('body').addClass('removeScroll');
                $.ajax({
                    type: "GET",
                    url: "{{ url('getRestoreTime') }}/exchange/" + this.value,
                    data: {},
                    success: function(data) {
                        $(".spinner_parent").css("display", "none");
                        $('body').removeClass('removeScroll');
                        $('.backupDate').val('');
                        $('.backupTime').val('');
                        $('.backupTime').attr('disabled', 'disabled');
                        let temp = [];
                        data.forEach(function(item) {
                            let entry = item.date;
                            let newDate = formatDateWithoutTime(entry);
                            let newTime = formatTimeWithoutDate(entry);
                            if (temp.length > 0) {
                                let isExist = false;
                                temp.forEach((e) => {
                                    if (e.date == newDate) {
                                        isExist = true;
                                        e.time.push({
                                            "job_id": item.id,
                                            "time": newTime,
                                            "id": entry
                                        });
                                    }
                                });
                                if (!isExist) {
                                    temp.push({
                                        "date": newDate,
                                        "time": [{
                                            "job_id": item.id,
                                            "time": newTime,
                                            "id": entry
                                        }]
                                    });
                                }
                            } else {
                                temp.push({
                                    "date": newDate,
                                    "time": [{
                                        "job_id": item.id,
                                        "time": newTime,
                                        "id": entry
                                    }]
                                });
                            }
                        });
                        $('.backupDate').removeAttr('disabled');
                        setAllowedDates(JSON.stringify(temp));
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
                        $("#jobs").val($("#target option:first").val());
                        $(".backupDate").val("");
                        $(".backupTime").html("");
                        $(".backupTime").append(new Option("Select Time", ""));
                        $('.backupTime').attr('disabled', 'disabled');
                        $('.backupDate').attr('disabled', 'disabled');

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

        });

        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var basicSubject = $('#basicSubject').val();
                var from = $('#from').val();
                var to = $('#to').val();
                var sentFrom = $('#sentFrom').datepicker('getDate');
                var sentTo = $('#sentTo').datepicker('getDate');

                var owner = $('#owner').val();
                var status = $('#status').val();
                var taskStartFrom = $('#taskStartFrom').datepicker('getDate');
                var taskStartTo = $('#taskStartTo').datepicker('getDate');

                var organizer = $('#organizer').val();
                var calendarSubject = $('#calendarSubject').val();
                var calendarStartFrom = $('#calendarStartFrom').datepicker('getDate');
                var calendarStartTo = $('#calendarStartTo').datepicker('getDate');

                var journalSubject = $('#journalSubject').val();
                var journalStartFrom = $('#journalStartFrom').datepicker('getDate');
                var journalStartTo = $('#journalStartTo').datepicker('getDate');

                var noteFrom = $('#noteFrom').datepicker('getDate');
                var noteTo = $('#noteTo').datepicker('getDate');

                var fullname = $('#fullname').val();
                var company = $('#company').val();
                var contactEmail = $('#contactEmail').val();
                if (folderType == "None") {
                    if (from) {
                        if (!data[1])
                            return false;
                        var value = data[1].toLowerCase();
                        if (!value.toString().includes(from.toLowerCase()))
                            return false;
                    }

                    if (to) {
                        if (!data[2])
                            return false;
                        var value = data[2].toLowerCase();
                        if (!value.toString().includes(to.toLowerCase()))
                            return false;
                    }

                    if (basicSubject) {
                        if (!data[3])
                            return false;
                        var value = data[3].toLowerCase();
                        if (!value.toString().includes(basicSubject.toLowerCase()))
                            return false;
                    }

                    if ((sentFrom || sentTo) && !data[6]) {
                        return false;
                    } else {
                        if (sentFrom) {
                            if (new Date(sentFrom) > new Date(data[6])) {
                                return false;
                            }
                        }

                        if (sentTo) {
                            if (new Date(sentTo) < new Date(data[6])) {
                                return false;
                            }
                        }
                    }
                } else if (folderType == "Task") {
                    if (owner) {
                        if (!data[9])
                            return false;
                        var value = data[9].toLowerCase();
                        if (!value.toString().includes(owner.toLowerCase()))
                            return false;
                    }

                    if (status) {
                        if (!data[10])
                            return false;
                        var value = data[10].toLowerCase();
                        if (!value.toString().includes(status.toLowerCase()))
                            return false;
                    }

                    if ((taskStartFrom || taskStartTo) && !data[12]) {
                        return false;
                    } else {
                        if (taskStartFrom) {
                            if (new Date(taskStartFrom) > new Date(data[12])) {
                                return false;
                            }
                        }

                        if (taskStartTo) {
                            if (new Date(taskStartTo) < new Date(data[12])) {
                                return false;
                            }
                        }
                    }
                } else if (folderType == "Appointment") {
                    if (organizer) {
                        if (!data[14])
                            return false;
                        var value = data[14].toLowerCase();
                        if (!value.toString().includes(organizer.toLowerCase()))
                            return false;
                    }

                    if (calendarSubject) {
                        if (!data[15])
                            return false;
                        var value = data[15].toLowerCase();
                        if (!value.toString().includes(calendarSubject.toLowerCase()))
                            return false;
                    }

                    if ((calendarStartFrom || calendarStartTo) && !data[19]) {
                        return false;
                    } else {
                        if (calendarStartFrom) {
                            if (new Date(calendarStartFrom) > new Date(data[19])) {
                                return false;
                            }
                        }

                        if (calendarStartTo) {
                            if (new Date(calendarStartTo) < new Date(data[19])) {
                                return false;
                            }
                        }
                    }
                } else if (folderType == "Journal") {
                    if (journalSubject) {
                        if (!data[21])
                            return false;
                        var value = data[21].toLowerCase();
                        if (!value.toString().includes(journalSubject.toLowerCase()))
                            return false;
                    }

                    if ((journalStartFrom || journalStartTo) && !data[22]) {
                        return false;
                    } else {
                        if (journalStartFrom) {
                            if (new Date(journalStartFrom) > new Date(data[22])) {
                                return false;
                            }
                        }

                        if (journalStartTo) {
                            if (new Date(journalStartTo) < new Date(data[22])) {
                                return false;
                            }
                        }
                    }
                } else if (folderType == "StickyNote") {
                    if ((noteFrom || noteTo) && !data[26]) {
                        return false;
                    } else {
                        if (noteFrom) {
                            if (new Date(noteFrom) > new Date(data[26])) {
                                return false;
                            }
                        }

                        if (noteTo) {
                            if (new Date(noteTo) < new Date(data[26])) {
                                return false;
                            }
                        }
                    }
                } else if (folderType == "Contact") {
                    if (fullname) {
                        if (!data[27])
                            return false;
                        var value = data[27].toLowerCase();
                        if (!value.toString().includes(fullname.toLowerCase()))
                            return false;
                    }

                    if (company) {
                        if (!data[31])
                            return false;
                        var value = data[31].toLowerCase();
                        if (!value.toString().includes(company.toLowerCase()))
                            return false;
                    }

                    if (contactEmail) {
                        if (!data[32])
                            return false;
                        var value = data[32].toLowerCase();
                        if (!value.toString().includes(contactEmail.toLowerCase()))
                            return false;
                    }
                }
                return true;
            }
        );

        //---- Mailboxes & Folders & Items Functions
        function onFolderResultChange() {
            let folders = $('#foldersResultsTable_wrapper').find('tbody .form-check-input:checked');
            let foldersCount = folders.length;
            let uncheckedFolders = $('#foldersResultsTable_wrapper').find('tbody .form-check-input:not(:checked)').length;
            let unresolvedCount = $('foldersResultsTable_wrapper').find('tbody tr').length - foldersCount -
                uncheckedFolders;

            $('#foldersResultsTable_wrapper').find('.boxesCount').html(foldersCount);
            if (unresolvedCount > 0)
                $('foldersResultsTable_wrapper').find('.unresolvedCount').html('(' + unresolvedCount +
                    ' unresolved mails)');
            else
                $('foldersResultsTable_wrapper').find('.unresolvedCount').html('');
        }
        //---------------------------------------------------//
        function onMailBoxResultChange(tableName) {
            let boxes = $('#' + tableName + '_wrapper').find('tbody .form-check-input:checked');
            let uncheckedBoxes = $('#' + tableName + '_wrapper').find('tbody .form-check-input:not(:checked)').length;
            let mailboxCount = boxes.length;
            let unresolvedCount = $('#' + tableName + '_wrapper').find('tbody tr').length - mailboxCount - uncheckedBoxes;

            $('#' + tableName + '_wrapper').find('.boxesCount').html(mailboxCount);
            if (unresolvedCount > 0)
                $('#' + tableName + '_wrapper').find('.unresolvedCount').html('(' + unresolvedCount +
                    ' unresolved mails)');
            else
                $('#' + tableName + '_wrapper').find('.unresolvedCount').html('');
        }
        //---------------------------------------------------//
        function checkFoldersCount() {
            $('#foldersResultsTable_wrapper').find('thead .form-check-input').click(function() {
                if ($(this).prop('checked'))
                    $('#foldersResultsTable_wrapper').find('tbody .form-check-input').each(function() {
                        $(this).prop('checked', true);
                    });
                else
                    $('#foldersResultsTable_wrapper').find('tbody .form-check-input').each(function() {
                        $(this).prop('checked', false);
                    });
                onFolderResultChange();
            });

            $('#foldersResultsTable_wrapper').find('tbody .form-check-input').change(function() {
                onFolderResultChange();
            });
            adjustTable();
            $("#foldersResultsTable").DataTable().draw();
        }
        //---------------------------------------------------//
        function checkMailBoxCount(tableName) {
            $('#' + tableName + '_wrapper').find('thead .form-check-input').click(function() {
                if ($(this).prop('checked'))
                    $('#' + tableName + '_wrapper').find('tbody .form-check-input').each(function() {
                        $(this).prop('checked', true);
                    });
                else
                    $('#' + tableName + '_wrapper').find('tbody .form-check-input').each(function() {
                        $(this).prop('checked', false);
                    });
                onMailBoxResultChange(tableName);
            });

            $('#' + tableName + '_wrapper').find('tbody .form-check-input').change(function() {
                onMailBoxResultChange(tableName);
            });
            adjustTable();
            $("#" + tableName).DataTable().draw();
        }
        //----------------------------------------------------//
        function mailBoxCheckChange() {
            var len = $('.tree .mailBoxCheck:checked').length;
            $(".mailBoxButton .restoreMailboxAnotherOption").addClass("hide");
            if (len == 0) {
                $('.mailBoxButton button').attr('disabled', 'disabled');
                $('.mailBoxButton .selectedBoxCount').html('');
            } else {
                if (len == 1)
                    $(".mailBoxButton .restoreMailboxAnotherOption").removeClass("hide");
                $('.mailBoxButton button').removeAttr('disabled');
                $('.mailBoxButton .selectedBoxCount').html('(' + len + ')');
            }
            mailBoxFolderCheckChange();
            mailBoxFolderItemsCheckChange();
        }
        //----------------------------------------------------//
        function mailBoxFolderCheckChange() {
            var len = $('.tree .mailBoxFolderCheck:checked').length;
            let showButton = false;
            $(".mailBoxFolderButton").find(".restoreFolderOption").addClass("hide");
            if (len == 0) {
                $('.mailBoxFolderButton button').attr('disabled', 'disabled');
                $('.mailBoxFolderButton .selectedFolderCount').html('');
            } else {
                if (len == 1) {
                    $(".mailBoxFolderButton").find(".restoreFolderOption").removeClass("hide");
                }
                $('.mailBoxFolderButton button').removeAttr('disabled');
                $('.mailBoxFolderButton .selectedFolderCount').html('(' + len + ')');

                $('.mailBoxFolderCheck:checked').each(function() {
                    var mailbox = $(this).closest('.mailbox');
                    var allCount = mailbox.find('.mailBoxFolderCheck').length;
                    var checkedCount = mailbox.find('.mailBoxFolderCheck:checked').length;
                    if (allCount > checkedCount) {
                        showButton = true;
                    }
                });
            }
            if (!showButton) {
                $('.mailBoxFolderButton button').attr('disabled', 'disabled');
            }
        }
        //----------------------------------------------------//
        function mailBoxFolderItemsCheckChange() {
            var len = $('.mailBoxFolderItemCheck:checked').length;
            if (len == 0) {
                $('.mailBoxFolderItemsButton button').attr('disabled', 'disabled');
                $('.mailBoxFolderItemsButton .selectedItemCount').html('');
            } else {
                $('.mailBoxFolderItemsButton button').removeAttr('disabled');
                $('.mailBoxFolderItemsButton .selectedItemCount').html('(' + len + ')');
            }
        }
        //----------------------------------------------------//
        function resetSearch() {
            $("input").val("");
            $('#' + getTableClass(folderType)).DataTable().draw();
        }
        //----------------------------------------------------//
        function applySearch() {
            $('#' + getTableClass(folderType)).DataTable().draw();
        }
        //----------------------------------------------------//
        function stopLoad() {
            stopLoading = true;
            $('.stopLoad').addClass('hide');
            $('.resumeLoad').removeClass('hide');
            $('.moveToEDiscovery').addClass('mr-2 ml-2');
            $('.searchingLabel').addClass('hide');
            $('.countingLabel').html(getTotalItems() + ' Items Shown')
        }
        //----------------------------------------------------//
        function resumeLoad() {
            stopLoading = false;
            if ($('#' + getTableClass(folderType)).DataTable().data().count() >= stoppingLimit) {
                loadAll = true;
                $('.stoppingRow').addClass('hide');
            }
            increaseTotalItems($('#' + getTableClass(folderType)).DataTable().data().count());
            $('.countingLabel').html(getTotalItems() + ' Items Shown');
            $('.searchingLabel').removeClass('hide');
            $('.stopLoad').removeClass('hide');
            $('.resumeLoad').addClass('hide');
            $('.moveToEDiscovery').addClass('mr-2 ml-2');

            loadItems();
        }
        //----------------------------------------------------//
        function getFolderSubFoldersMenu(result) {
            subFolders = "";
            if (result.children) {
                subFolders = "<ul class='subFolder pt-0 pb-0 mb-0 display-none'>";
                result.children.forEach(function(result) {
                    let mailBoxName = $(".tree #" + result.mailboxId).html();
                    subFolders = subFolders +
                        '<li class="mailboxfolder pb-0"><div class="relative allWidth inline-flex">' +
                        (result.hasFolders ?
                            '<span class="caret mailCaret closeMail" onclick="getFolderChildren(event)"></span>' :
                            '') +
                        '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                        '<input id="usUser" type="checkbox" class="mailBoxFolderCheck form-check-input" data-mailboxId="' +
                        result.mailboxId + '" data-mailboxName="' + mailBoxName + '" value="' +
                        result.id + '"/>' +
                        '<span class="tree-checkBox check-mark-white check-mark"></span></label>' +
                        '<img class="folderIcon" src="/svg/folders/' + getFolderIcon(result.name) + '.svg">' +
                        '<span id="' + result.id +
                        '" data-mailboxId="' + result.mailboxId +
                        '" onclick="getFolderItems(event)" data-folderType="' + result.type +
                        '" class="mail-click childmail-click mail-folder-click" title="' +
                        result
                        .name + '">' +
                        result.name + '</span></div>' + getFolderSubFoldersMenu(result) + '</li>';
                });
                subFolders = subFolders + "</ul>";
            }
            return subFolders;
        }
        //----------------------------------------------------//
        function getFolderIcon(name, type) {
            var nameArr = ['', 'Inbox', 'Archive', 'Outbox', 'Calendar', 'Clutter', 'Deleted Items',
                'Permanently Deleted Items', 'Sent Items', 'Tasks', 'Contacts', 'Conversation History', 'Drafts',
                'Journal', 'Junk Email', 'Notes', 'TeamsMessagesData'
            ];
            if ($.inArray(name, nameArr) > 0) {
                name = name.toLowerCase();
                return name.replace(/ /g, '_')
            } else {
                return 'none';
            }
        }
        //----------------------------------------------------//
        function getFolderItems(event) {
            let selectedFolderId = event.target.id;
            let selectedMailId = $('.mail-click#' + selectedFolderId).attr('data-mailboxId');
            mailboxId = selectedMailId;
            folderId = selectedFolderId;
            $('.mail-click').removeClass('active');
            $('.mail-click#' + selectedFolderId).addClass('active');
            stopLoading = true;
            isDone = false;
            resetTotalItems();
            resetOffset();
            folderType = $(".tree #" + selectedFolderId).attr("data-folderType");
            reloadDataItems(folderType);
        }
        //----------------------------------------------------//
        function getFolderChildren(event, item) {
            if ($(event.target).hasClass('closeMail'))
                $(event.target).removeClass('closeMail');
            else
                $(event.target).addClass('closeMail');
            $(event.target).closest(".mailboxfolder").find('ul:first').fadeToggle();
        }
        //----------------------------------------------------//
        function searchMailBox() {
            var value = $(this).val();
            jQuery.expr[':'].contains = function(a, i, m) {
                return jQuery(a).text().toUpperCase()
                    .indexOf(m[3].toUpperCase()) >= 0;
            };
            $('li.has').addClass('hide');
            var values = $('li .mail-click:contains("' + value + '")');
            values.closest('.has').removeClass('hide');
            $('li.has.hide').find('input:checked').removeAttr('checked');
        }
        //----------------------------------------------------//
        function getMainColumn(tr) {
            let colsArr = tr.find('.subjectColumn');
            let value;
            colsArr.each(function() {
                if ($(this).html())
                    value = $(this).html();
            });
            return value;
        }
        //----------------------------------------------------//
        function reloadDataItems(type) {
            $(document).find('.dataTables_filter input').val("");
            $('#' + getTableClass(folderType)).DataTable().search("").clear().draw();
            $('.tableDiv').addClass('hide');
            if (type == "Task") {
                $('#tasksTable').DataTable().search("").ajax.reload();
                $('.tasksTable').removeClass('hide');
            } else if (type == "None") {
                $('#itemsTable').DataTable().search("").ajax.reload();
                $('.itemsTable').removeClass('hide');
            } else if (type == "StickyNote") {
                $('#notesTable').DataTable().search("").ajax.reload();
                $('.notesTable').removeClass('hide');
            } else if (type == "Journal") {
                $('#journalsTable').DataTable().search("").ajax.reload();
                $('.journalsTable').removeClass('hide');
            } else if (type == "Contact") {
                $('#contactsTable').DataTable().search("").ajax.reload();
                $('.contactsTable').removeClass('hide');
            } else if (type == "Appointment") {
                $('#appointmentsTable').DataTable().search("").ajax.reload();
                $('.appointmentsTable').removeClass('hide');
            }
            adjustTable();
        }
        //----------------------------------------------------//
        function getTableClass(type) {
            if (type == "Task") {
                return 'tasksTable';
            } else if (type == "None") {
                return 'itemsTable';
            } else if (type == "StickyNote") {
                return 'notesTable';
            } else if (type == "Journal") {
                return 'journalsTable';
            } else if (type == "Contact") {
                return 'contactsTable';
            } else if (type == "Appointment") {
                return 'appointmentsTable';
            }
        }
        //----------------------------------------------------//


        //---- Modal Functions
        function sendItemModal() {
            //--------------------//
            let items = $('input.mailBoxFolderItemCheck:checked');
            let data = [];
            items.each(function() {
                var tr = $(this).closest('tr');
                data.push({
                    "id": $(this).val(),
                    "mailboxId": tr.find('.mailboxId').val(),
                    "folder": tr.find('.folderTitle').val()
                });
            });
            //--------------------//
            $('#sendItem').find('.items').val(JSON.stringify(data));
            //--------------------//
            $('#sendItem').modal('show');
        }
        //----------------------------------------------------//
        function restoreItemAnotherModal() {
            //--------------------//
            $('.modal-item-width').addClass('modal-width');
            $('.modal-item-left').addClass('custom-left-col');
            $('.modal-item-right').addClass('custom-right-col');
            $('.modal-item-margin').addClass('ml-100');
            $('.itemsAnotherOptions_cont').removeClass('hide');
            $('.itemsAnotherOptions_cont .required').attr('required', 'required');
            $('#restoreItem').find('.restoreType').val('another');
            $('#restoreItem').find('.modal-title').html('Restore Items to Another Location');
            //--------------------//
            let items = $('input.mailBoxFolderItemCheck:checked');
            let data = [];
            let boxId;
            let folderTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                mailboxTitle = tr.find('.mailboxTitle').val();
                folderTitle = tr.find('.folderTitle').val();
                boxId = tr.find('.mailboxId').val();
                data.push({
                    "id": $(this).val(),
                    "parentName": mailboxTitle + '-' + folderTitle,
                    "folderTitle": folderTitle,
                    "mailboxTitle": mailboxTitle,
                    "name": getMainColumn(tr),
                });
            });
            let parentName = mailboxTitle + '-' + folderTitle;
            //--------------------//
            $('#restoreItem').find('.items').val(JSON.stringify(data));
            $('#restoreItem').find('.mailboxId').val(boxId);
            $('#restoreItem').find('.folderTitle').val(folderTitle);
            $('#restoreItem').find('.mailboxTitle').val(mailboxTitle);
            $('#restoreItem').find('.parentName').val(parentName);
            //--------------------//
            $('#itemsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#itemsResultsTable').DataTable().clear().draw();
            $('#itemsResultsTable').DataTable().rows.add(data); // Add new data
            $('#itemsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable
            //--------------------//
            checkMailBoxCount('itemsResultsTable');
            adjustTable();
            $("#itemsResultsTable").DataTable().draw();
            //--------------------//
            $('#restoreItem').find(".refreshDeviceCode").click();
            $('#restoreItem').modal('show');
        }
        //----------------------------------------------------//
        function restoreMailboxOriginalModal() {
            let mailBoxes = $('#mailboxes li.mailbox input.mailBoxCheck:checked');
            var data = [];
            let mailboxCount = mailBoxes.length;
            let unresolvedCount = 0;
            mailBoxes.each(function() {
                var parent = $(this).closest('.has.mailbox');
                let resolved = true;
                if (checkResolvedMail($(this).attr('data-email'))) {
                    unresolvedCount++;
                    mailboxCount--;
                    resolved = false;
                }
                data.push({
                    id: $(this).val(),
                    name: parent.find('.mail-click').html(),
                    email: $(this).attr('data-email'),
                    resolved: resolved
                });
            });
            $('#mailboxOriginalTable_wrapper').find('.boxesCount').html(mailboxCount);
            if (unresolvedCount > 0)
                $('.mailboxesresultsTable .dataTables_wrapper').find('.unresolvedCount').html('(' + unresolvedCount +
                    ' unresolved mails)');
            else
                $('.mailboxesresultsTable').find('tfoot .unresolvedCount').html('');
            $('#mailboxOriginalTable').DataTable().clear().draw();
            $('#mailboxOriginalTable').DataTable().rows.add(data); // Add new data
            $('#mailboxOriginalTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkMailBoxCount('mailboxOriginalTable');
            adjustTable();
            $("#mailboxOriginalTable").DataTable().draw();

            $('#mailboxAnotherTable').DataTable().clear().draw();
            $('#restoreMailboxOriginal').find(".refreshDeviceCode").click();
            $('#restoreMailboxOriginal').modal('show');
        }
        //----------------------------------------------------//
        function restoreMailboxAnotherModal() {
            let mailBoxes = $('#mailboxes li.mailbox input.mailBoxCheck:checked');
            var data = [];
            let mailboxCount = mailBoxes.length;
            let unresolvedCount = 0;
            mailBoxes.each(function() {
                var parent = $(this).closest('.has.mailbox');
                data.push({
                    id: $(this).val(),
                    name: parent.find('.mail-click').html(),
                    email: $(this).attr('data-email')
                });
                if ($(this).attr('data-email') == '')
                    unresolvedCount++;
            });
            $('#mailboxAnotherTable_wrapper').find('.boxesCount').html(mailboxCount);
            if (unresolvedCount > 0)
                $('.mailboxesresultsTable .dataTables_wrapper').find('.unresolvedCount').html('(' + unresolvedCount +
                    ' unresolved mails)');
            else
                $('.mailboxesresultsTable table').find('tfoot .unresolvedCount').html('');
            $('#mailboxAnotherTable').DataTable().clear().draw();
            $('#mailboxAnotherTable').DataTable().rows.add(data); // Add new data
            $('#mailboxAnotherTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkMailBoxCount('mailboxAnotherTable');
            adjustTable();
            $("#mailboxAnotherTable").DataTable().draw();
            $('#mailboxOriginalTable').DataTable().clear().draw()
            $('#restoreMailboxAnother').find(".refreshDeviceCode").click();
            $('#restoreMailboxAnother').modal('show');
        }
        //----------------------------------------------------//
        function exportMailboxModal() {
            let mailBoxes = $('#mailboxes li.mailbox input.mailBoxCheck:checked');
            var data = [];
            let mailboxCount = mailBoxes.length;
            mailBoxes.each(function() {
                var parent = $(this).closest('.has.mailbox');
                data.push({
                    id: $(this).val(),
                    name: parent.find('.mail-click').html(),
                    email: $(this).attr('data-email')
                });
            });
            $('#exportMailboxTable_wrapper').find('.boxesCount').html(mailboxCount);
            $('#exportMailboxTable').DataTable().clear().draw();
            $('#exportMailboxTable').DataTable().rows.add(data); // Add new data
            $('#exportMailboxTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkMailBoxCount('exportMailboxTable');
            adjustTable();
            $("#exportMailboxTable").DataTable().draw();
            $("#mailboxAnotherTable").DataTable().clear().draw();
            $('#mailboxOriginalTable').DataTable().clear().draw();
            $('#exportMailboxModal').modal('show');
        }
        //----------------------------------------------------//
        function exportFolderModal() {
            let folders = $('#mailboxes li.mailbox input.mailBoxFolderCheck:checked');
            let foldersCount = folders.length;
            let data = [];
            folders.each(function() {
                var mailBox = $(this).parents('li.mailbox').find('input.mailBoxCheck');
                data.push({
                    "resolved": true,
                    "id": $(this).val(),
                    "name": $(this).parents('.mailboxfolder').find('.mail-click').html(),
                    "mailboxId": mailBox.val(),
                    "mailboxName": mailBox.parents('li.mailbox').find('.mail-click').html()
                });
            });
            $('#exportFoldersResultsTable_wrapper').find('.boxesCount').html(foldersCount);
            $('#exportFoldersResultsTable').DataTable().clear().draw();
            $('#exportFoldersResultsTable').DataTable().rows.add(data); // Add new data
            $('#exportFoldersResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkFoldersCount();
            adjustTable();
            $("#exportFoldersResultsTable").DataTable().draw();
            $('#exportFolderModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreFolderOriginalModal() {
            //--------------------//
            $('.modal-folder-width').removeClass('modal-width');
            $('.modal-folder-left').removeClass('custom-left-col');
            $('.modal-folder-right').removeClass('custom-right-col');
            $('.modal-folder-margin').removeClass('ml-108');
            $('.foldersAnotherOptions_cont').addClass('hide');
            $('.foldersAnotherOptions_cont .required').removeAttr('required');
            $('#restoreFolder').find('.restoreType').val('original');
            $('#restoreFolder').find('.modal-title').html('Restore Folders to Original Location');
            //--------------------//
            let folders = $('#mailboxes li.mailbox input.mailBoxFolderCheck:checked');
            let foldersCount = folders.length;
            let unresolvedCount = 0;
            let data = [];
            folders.each(function() {
                var folderCheck = $(this);
                var mailBox = folderCheck.parents('li.mailbox').find('input.mailBoxCheck');
                //--------------------------//
                let resolved = true;
                if (checkResolvedMail(mailBox.attr('data-email'))) {
                    unresolvedCount++;
                    foldersCount--;
                    resolved = false;
                }
                //--------------------------//
                data.push({
                    "id": $(this).val(),
                    "resolved": resolved,
                    "name": folderCheck.closest('.mailboxfolder').find('.mail-click').html(),
                    "mailboxId": mailBox.val(),
                    "mailboxName": mailBox.parents('li.mailbox').find('.mail-click').html()
                });
            });
            //--------------------------//
            $('#foldersResultsTable_wrapper').find('.boxesCount').html(foldersCount);
            $('#foldersResultsTable_wrapper').find('.unresolvedCount').html('(' + unresolvedCount + ' unresolved mails)');
            $('#foldersResultsTable').DataTable().clear().draw();
            $('#foldersResultsTable').DataTable().rows.add(data); // Add new data
            $('#foldersResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkFoldersCount();
            adjustTable();
            $("#foldersResultsTable").DataTable().draw();
            $('#restoreFolder').find(".refreshDeviceCode").click();
            $('#restoreFolder').modal('show');
        }
        //----------------------------------------------------//
        function restoreFolderAnotherModal() {
            //--------------------//
            $('.modal-folder-width').addClass('modal-width');
            $('.modal-folder-left').addClass('custom-left-col');
            $('.modal-folder-right').addClass('custom-right-col');
            $('.modal-folder-margin').addClass('ml-108');
            $('.foldersAnotherOptions_cont').removeClass('hide');
            $('.foldersAnotherOptions_cont .required').attr('required', 'required');
            $('#restoreFolder').find('.restoreType').val('another');
            //--------------------//
            let folders = $('#mailboxes li.mailbox input.mailBoxFolderCheck:checked');
            let foldersCount = folders.length;

            let data = [];
            folders.each(function() {
                var mailBox = $(this).parents('li.mailbox').find('input.mailBoxCheck');
                data.push({
                    "resolved": true,
                    "id": $(this).val(),
                    "name": $(this).parents('.mailboxfolder').find('.mail-click').html(),
                    "mailboxId": mailBox.val(),
                    "mailboxName": mailBox.parents('li.mailbox').find('.mail-click').html()
                });
            });
            $('#foldersResultsTable_wrapper').find('.boxesCount').html(foldersCount);
            $('#foldersResultsTable').DataTable().clear().draw();
            $('#foldersResultsTable').DataTable().rows.add(data); // Add new data
            $('#foldersResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkFoldersCount();
            adjustTable();
            $("#foldersResultsTable").DataTable().draw();
            $('#restoreFolder').find(".refreshDeviceCode").click();
            $('#restoreFolder').find('.modal-title').html('Restore Folders to Another Location');
            $('#restoreFolder').modal('show');
        }
        //----------------------------------------------------//
        function restoreItemOriginalModal() {
            //--------------------//
            $('.modal-item-width').removeClass('modal-width');
            $('.modal-item-left').removeClass('custom-left-col');
            $('.modal-item-right').removeClass('custom-right-col');
            $('.modal-item-margin').removeClass('ml-100');
            $('.itemsAnotherOptions_cont').addClass('hide');
            $('.itemsAnotherOptions_cont .required').removeAttr('required');
            $('#restoreItem').find('.restoreType').val('original');
            //--------------------//
            let items = $('input.mailBoxFolderItemCheck:checked');
            let data = [];
            items.each(function() {
                var tr = $(this).closest('tr');
                mailboxTitle = tr.find('.mailboxTitle').val();
                folderTitle = tr.find('.folderTitle').val();
                boxId = tr.find('.mailboxId').val();
                data.push({
                    "id": $(this).val(),
                    "parentName": mailboxTitle + '-' + folderTitle,
                    "folderTitle": folderTitle,
                    "mailboxTitle": mailboxTitle,
                    "mailboxId": boxId,
                    "name": getMainColumn(tr),
                });
            });
            //-----------------------------------------------------------//
            $('#restoreItem').find('.restoreItemsWarning').addClass('hide');
            $('#restoreItem').find('[type="submit"]').removeAttr('disabled');
            //-----------------------------------------------------------//
            $('#itemsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#itemsResultsTable').DataTable().clear().draw();
            $('#itemsResultsTable').DataTable().rows.add(data); // Add new data
            $('#itemsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable
            //--------------------//
            checkMailBoxCount('itemsResultsTable');
            adjustTable();
            $("#itemsResultsTable").DataTable().draw();
            //-----------------------------------------------------------//
            $('#restoreItem').find('.modal-title').html('Restore Items to Original Location');
            $('#restoreItem').find(".refreshDeviceCode").click();
            $('#restoreItem').modal('show');
        }
        //----------------------------------------------------//
        function exportItemsModal() {
            //--------------------//
            let items = $('input.mailBoxFolderItemCheck:checked');
            let data = [];
            items.each(function() {
                var tr = $(this).closest('tr');
                mailboxTitle = tr.find('.mailboxTitle').val();
                folderTitle = tr.find('.folderTitle').val();
                boxId = tr.find('.mailboxId').val();
                data.push({
                    "id": $(this).val(),
                    "parentName": mailboxTitle + '-' + folderTitle,
                    "folderTitle": folderTitle,
                    "mailboxTitle": mailboxTitle,
                    "mailboxId": boxId,
                    "name": getMainColumn(tr),
                });
            });
            //-----------------------------------------------------------//
            $('#exportItemsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#exportItemsResultsTable').DataTable().clear().draw();
            $('#exportItemsResultsTable').DataTable().rows.add(data); // Add new data
            $('#exportItemsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable
            //--------------------//
            checkMailBoxCount('exportItemsResultsTable');
            adjustTable();
            $("#exportItemsResultsTable").DataTable().draw();
            //-----------------------------------------------------------//
            let parentName = mailboxTitle + '-' + folderTitle;
            //--------------------//
            $('#exportItemsForm').find('.mailboxId').val(boxId);
            $('#exportItemsForm').find('.folderTitle').val(folderTitle);
            $('#exportItemsForm').find('.mailboxName').val(mailboxTitle);
            $('#exportItemsForm').find('.parentName').val(parentName);
            //--------------------//
            $('#exportItemsModal').modal('show');
        }
        //----------------------------------------------------//

        //---- Custom Function
        function parseDateValue(rawDate) {
            var dateNoTime = rawDate.split("T");
            var dateArray = dateNoTime[0].split("-");
            var parsedDate = new Date(dateArray[0] + "-" + dateArray[1] + "-" + dateArray[2]);
            return parsedDate;
        }
        //---------------------------------------------------//
        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();
            hours = '' + d.getHours();
            minutes = '' + d.getMinutes();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;
            if (hours.length < 2)
                hours = '0' + hours;
            if (minutes.length < 2)
                minutes = '0' + minutes;
            return month + "/" + day + "/" + year + " " + hours + ":" + minutes;

        }
        //---------------------------------------------------//
        function formatTimeWithoutDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();
            hours = '' + d.getHours();
            minutes = '' + d.getMinutes();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;
            if (hours.length < 2)
                hours = '0' + hours;
            if (minutes.length < 2)
                minutes = '0' + minutes;
            return hours + ":" + minutes;

        }
        //---------------------------------------------------//
        function formatDateWithoutTime(date) {
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
        //---------------------------------------------------//
        function showErrorMessage(message) {
            $(".danger-oper .danger-msg").html(message);
            $(".danger-oper").css("display", "block");
            setTimeout(function() {
                $(".danger-oper").css("display", "none");
            }, 8000);
        }
        //---------------------------------------------------//
        function showSuccessMessage(message) {
            $(".success-oper .success-msg").html(message);
            $(".success-oper").css("display", "block");
            setTimeout(function() {
                $(".success-oper").css("display", "none");
            }, 8000);
        }
        //---------------------------------------------------//
        function generateTable(itemClass) {
            if (itemClass == "IPM.Task") {
                $(".jobsTable .tableDiv").addClass('hide');
                $(".jobsTable .tasksTable").removeClass('hide');
            } else if (itemClass == "IPM.Appointment") {
                $(".jobsTable .tableDiv").addClass('hide');
                $(".jobsTable .appointmentsTable").removeClass('hide');
            } else if (itemClass == "IPM.Journal") {
                $(".jobsTable .tableDiv").addClass('hide');
                $(".jobsTable .journalsTable").removeClass('hide');
            } else if (itemClass == "IPM.StickyNote") {
                $(".jobsTable .tableDiv").addClass('hide');
                $(".jobsTable .notesTable").removeClass('hide');
            } else if (itemClass == "IPM.Contact") {
                $(".jobsTable .tableDiv").addClass('hide');
                $(".jobsTable .contactsTable").removeClass('hide');
            } else {
                $(".jobsTable .tableDiv").addClass('hide');
                $(".jobsTable .itemsTable").removeClass('hide');
            }
            let tds = $('#table-content td');
            tds.each(function() {
                if (!$(this).hasClass('after-none') && !$(this).hasClass('hasHtml')) {
                    $(this).attr('title', $(this).html());
                }
            });
        }
        //---------------------------------------------------//

        //---- Global Variables Function
        function getMailBoxFolder() {
            return folderId;
        }
        //---------------------------------------------------//
        function getAllowedDates() {
            return allowedDates;
        }
        //---------------------------------------------------//
        function setAllowedDates(dates) {
            allowedDates = JSON.parse(dates);
        }
        //---------------------------------------------------//
        function getMailBox() {
            return mailboxId;
        }
        //---------------------------------------------------//
        function getOffset() {
            return offset;
        }
        //---------------------------------------------------//
        function resetOffset() {
            offset = 1;
            $('.warningRow').addClass('hide');
            $('.stoppingRow').addClass('hide');
        }
        //---------------------------------------------------//
        function increaseOffset() {
            offset = getOffset() + 1;
        }
        //---------------------------------------------------//
        function getTotalItems() {
            return $('#' + getTableClass(folderType)).DataTable().data().count();
        }
        //---------------------------------------------------//
        function resetTotalItems() {
            totalRecordsShown = 0;
            $('.searchingLabel').addClass('hide');
            $('.stopLoad').addClass('hide');
            $('.resumeLoad').addClass('hide');
            $('.moveToEDiscovery').removeClass('mr-2 ml-2');
            $('.countingLabel').html(getTotalItems() + ' Items Shown');
        }
        //---------------------------------------------------//
        function increaseTotalItems(value) {
            totalRecordsShown = value;
        }
        //---------------------------------------------------//
        function checkResolvedMail(mail) {
            return false;
        }
        //---------------------------------------------------//
        //---------------------------------------------------//
        function moveToEDiscovery(confirm = "") {
            if (confirm) {
                return $('#confirmationModal').modal('show');
            }
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            let data = {
                "backupJobId": $('.backupTime option:selected').attr('data-job-id'),
                "restorePointType": $('#jobs').val(),
                "jobTime": $('.backupTime').val(),
                "showDeleted": $("#showDeleted")[0].checked,
                "showVersions": $("#showVersions")[0].checked,
            };
            let selectedMailboxes = [];
            let selectedFolders = [];
            $(".tree .mailBoxCheck:checked").each(function() {
                selectedMailboxes.push({
                    "mailboxId": $(this).val(),
                    "mailboxName": $(".tree #" + $(this).val()).html(),
                    "email": $(this).attr("data-email"),
                    "folderId": "-1",
                    "folderName": ""
                });
            });
            $(".tree .mailBoxFolderCheck:checked").each(function() {
                selectedFolders.push({
                    "folderId": $(this).val(),
                    "mailboxName": $(this).attr("data-mailboxName"),
                    "email": $(".tree [value='" + $(this).attr("data-mailboxId") + "']").attr("data-email"),
                    "mailboxId": $(this).attr("data-mailboxId"),
                    "folderName": $(".tree #" + $(this).val()).html()
                });
            });
            data.selectedMailboxes = JSON.stringify(selectedMailboxes);
            data.selectedFolders = JSON.stringify(selectedFolders);

            $.ajax({
                type: "POST",
                url: "{{ url('setEDiscoveryData') }}/exchange",
                data: {
                    _token: "{{ csrf_token() }}",
                    data: JSON.stringify(data),
                },
                success: function(data) {
                    window.location.href = "{{ url('e-discovery') }}" + "/exchange/edit/" + data +
                        "?type=move";
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
                    $("#mailboxes").html("");
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
        //---------------------------------------------------//


        //---- Ajax Requests
        //---------------------------------------------------//
        function downloadSingleMail() {
            let tr = $('tr.current');
            let item = tr.find('input.mailBoxFolderItemCheck').val();
            let boxId = tr.find('.mailboxId').val();
            let folderTitle = tr.find('.folderTitle').val();
            let mailboxTitle = tr.find('.mailboxTitle').val();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('downloadSingleItem') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    mailboxId: boxId,
                    folderTitle: folderTitle,
                    itemId: item,
                    jobId: $('.backupTime option:selected').attr('data-job-id')
                },
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(res.message);
                    window.open(res.file);
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
                    showErrorMessage(errMessage);
                }
            });
        }
        //---------------------------------------------------//
        function downloadMultiItems() {
            let items = $('input.mailBoxFolderItemCheck:checked');
            let itemsArr = [];
            let boxId = '';
            let tr;
            console.log("======000000000000=========",($('#jobs').val() == "all" ? "all" : "single"))
            let folderTitle = mailboxTitle = '';
            items.each(function() {
                var tr = $(this).closest('tr');
                boxId = tr.find('.mailboxId').val();
                mailboxTitle = $('#' + boxId).html();
                folderTitle = tr.find('.folderTitle').val();
                tr = $(this).closest('tr');
                itemsArr.push({
                    "id": $(this).val(),
                    parentName: mailboxTitle + '-' + folderTitle,
                    folderTitle: folderTitle,
                    name: tr.find('.subjectColumn').html(),
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('downloadMultiItems') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    jobType: ($('#jobs').val() == "all" ? "all" : "single"),
                    items: JSON.stringify(itemsArr),
                    jobId: $('.backupTime option:selected').attr('data-job-id'),
                    jobTime: $('.backupTime').val(),
                    showDeleted: $("#showDeleted")[0].checked,
                    showVersions: $("#showVersions")[0].checked,
                    mailboxId: boxId,
                    mailboxTitle: mailboxTitle,
                    folderTitle: folderTitle,
                },
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    if (res.message)
                        showSuccessMessage(res.message);
                    if (res.file)
                        window.open(res.file);
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
                    showErrorMessage(errMessage);
                }
            });
        }
        //---------------------------------------------------//
        function sendItem() {
            event.preventDefault()
            var data = $('#sendItemForm').serialize();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('sendItem') }}",
                data: data + '&_token=' + '{{ csrf_token() }}',
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');

                    $('#sendItem').modal('hide');
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
        //---------------------------------------------------//
        function loadItems() {
            $.ajax({
                type: "POST",
                async: true,
                url: "{{ url('getMailBoxFolderItems') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    offset: getTotalItems(),
                    mailboxId: getMailBox(),
                    mailboxTitle: $('#' + getMailBox()).html(),
                    folderId: getMailBoxFolder(),
                    folderTitle: $('#' + getMailBoxFolder()).html()
                },
                success: function(data) {
                    if (data.length > 0) {
                        //------------------------------------//
                        if (!stopLoading) {
                            increaseOffset();
                            $('.searchingLabel').removeClass('hide');
                            $('.stopLoad').removeClass('hide');
                            $('.resumeLoad').addClass('hide');
                            $('.moveToEDiscovery').addClass('mr-2 ml-2');

                            $('#' + getTableClass(folderType)).DataTable().rows.add(data);
                            $('#' + getTableClass(folderType)).DataTable().columns.adjust().draw();
                            $('.countingLabel').html(getTotalItems() + ' Items Shown');
                        } else {
                            $('.searchingLabel').addClass('hide');
                            $('.stopLoad').addClass('hide');
                            $('.resumeLoad').removeClass('hide');
                            $('.moveToEDiscovery').addClass('mr-2 ml-2');
                        }
                        //------------------------------------//
                    } else {
                        $('.searchingLabel').addClass('hide');
                        $('.stopLoad').addClass('hide');
                        $('.resumeLoad').addClass('hide');
                        $('.moveToEDiscovery').removeClass('mr-2 ml-2');

                        isDone = true;
                    }
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
        //---------------------------------------------------//
        function createSession(event) {
            event.preventDefault()
            mailboxId = -1;
            folderId = -1;
            reloadDataItems(folderType);
            $('.warningRow,.stoppingRow,.moveToEDiscovery').addClass('hide');

            if ($(".backupTime").find(":selected").val() != "") {
                $(".spinner_parent").css("display", "block");
                $('body').addClass('removeScroll');
                $.ajax({
                    type: "POST",
                    url: "{{ url('createExchangeSession') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        jobs: $('#jobs').val(),
                        time: $(".backupTime").find(":selected").val(),
                        showDeleted: $("#showDeleted")[0].checked,
                        showVersions: $("#showVersions")[0].checked
                    },
                    success: function(data) {
                        //-----------------------------//
                        jobType = $('#jobs').val();
                        jobId = $('.backupTime option:selected').attr('data-job-id');
                        jobTime = $(".backupTime").find(":selected").val();
                        showDeleted = $("#showDeleted")[0].checked;
                        showVersions = $("#showVersions")[0].checked;
                        //-----------------------------//
                        $('#activeclose').removeAttr('disabled');
                        $("#mailboxes").html("");
                        $(".spinner_parent").css("display", "none");
                        $('body').removeClass('removeScroll');
                        $("#choose").text("Change");
                        data.data.forEach(function(result) {
                            mailbox =
                                '<li class="has mailbox hand relative"><div class="relative allWidth">' +
                                '<span class="caret mailCaret closeMail" onclick="getMailBoxFolders(event)"></span>' +
                                '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                                '<input type="checkbox" class="mailBoxCheck form-check-input" value="' +
                                result.id + '" data-email="' + result.email + '"/>' +
                                '<span class="tree-checkBox check-mark-white check-mark"></span></label>' +
                                '<span id="' + result.id +
                                '" class="mail-click left-mail-click ml-27" onclick="getMailBoxFolders(event)" title="' +
                                result.name + '">' +
                                result.name +
                                '</span></div><div class="folder-spinner hide"></div></li>';

                            $("#mailboxes").append($(mailbox));
                        });
                        $("#rdate").html($(".backupDate").val());
                        $("#rtime").html($(".backupTime").find(":selected")
                            .text());
                        $('#jobsModal').modal('hide');
                        $('.mailBoxCheck').change(mailBoxCheckChange);
                        //------------------------------------------//
                        mailBoxCheckChange();
                        mailBoxFolderCheckChange();
                        mailBoxFolderItemsCheckChange();
                        //------------------------------------------//
                        $(".moveToEDiscovery").removeClass("hide");
                        //------------------------------------------//
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
                        $("#mailboxes").html("");

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
        }
        //---------------------------------------------------//
        function exportMailBoxToPst() {
            event.preventDefault();
            //--------------------------------------------//
            let mailboxes = $('#exportMailBoxForm .mailboxCheck:checked');
            let mailboxesArr = [];
            mailboxes.each(function() {
                let tr = $(this).closest('tr');
                mailboxesArr.push({
                    "id": $(this).val(),
                    name: tr.find('td:nth-child(2)').html(),
                    email: tr.find('td:nth-child(3)').html(),
                });
            });
            //--------------------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportMailBoxToPst') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    jobId: $('.backupTime option:selected').attr('data-job-id'),
                    jobTime: $('.backupTime').val(),
                    jobType: ($('#jobs').val() == "all" ? "all" : "single"),
                    restoreJobName: $("#exportMailBoxForm [name='restoreJobName']").val(),
                    showDeleted: $("#showDeleted")[0].checked,
                    showVersions: $("#showVersions")[0].checked,
                    enablePstSizeLimit: $("#exportMailBoxForm [name='enablePstSizeLimit']")[0].checked,
                    sizeLimit: $("#exportMailBoxForm [name='sizeLimit']").val(),
                    mailBoxes: JSON.stringify(mailboxesArr),
                },
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(res.message);

                    $('#exportMailboxModal').modal('hide');
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
                    showErrorMessage(errMessage);
                }
            });
        }
        //---------------------------------------------------//
        function resetFilterTable() {
            $('.filter-form').find('.sortBoxType').val('');
            $('.filter-form').find('.boxType').val('');
            $('.filter-table td .active').removeClass('active');
            getFilteredMailboxes();
        }
        //---------------------------------------------------//
        function getFilteredMailboxes(event) {
            if (event)
                event.preventDefault();
            var data = $('.filter-form').serialize();
            var letters = $('.filter-form td span.active');
            let lettersArr = [];
            if (letters.length > 0) {
                letters.each(function() {
                    lettersArr.push($(this).html());
                });
            }
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "GET",
                url: "{{ url('getFilteredMailboxes') }}",
                data: data + '&letters=' + lettersArr.join(','),
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    $("#mailboxes").html('');
                    res.forEach(function(result) {
                        mailbox =
                            '<li class="has mailbox hand relative"><div class="relative allWidth">' +
                            '<span onclick="getMailBoxFolders(event)" class="caret mailCaret closeMail"></span>' +
                            '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                            '<input type="checkbox" class="mailBoxCheck form-check-input" value="' +
                            result.id + '" data-email="' + result.email + '"/>' +
                            '<span class="tree-checkBox check-mark-white check-mark"></span></label>' +
                            '<span id="' + result.id +
                            '" class="mail-click left-mail-click ml-27" onclick="getMailBoxFolders(event)" title="' +
                            result.name + '">' +
                            result.name +
                            '</span></div><div class="folder-spinner hide"></div></li>';

                        $("#mailboxes").append($(mailbox));
                    });
                    $('.filter-icon').click();
                    $('.mailBoxCheck').change(mailBoxCheckChange);
                    mailBoxCheckChange();
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
                    showErrorMessage(errMessage);
                }
            });
        }
        //---------------------------------------------------//
        function exportMailBoxFolderToPst(event) {
            event.preventDefault();
            //--------------------------------------------//
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
                data: {
                    _token: "{{ csrf_token() }}",
                    jobType: ($('#jobs').val() == "all" ? "all" : "single"),
                    jobId: $('.backupTime option:selected').attr('data-job-id'),
                    jobTime: $('.backupTime').val(),
                    showDeleted: $("#showDeleted")[0].checked,
                    showVersions: $("#showVersions")[0].checked,
                    enablePstSizeLimit: $("#exportFolderForm [name='enablePstSizeLimit']")[0].checked,
                    sizeLimit: $("#exportFolderForm [name='sizeLimit']").val(),
                    restoreJobName: $("#exportFolderForm [name='restoreJobName']").val(),
                    folders: JSON.stringify(foldersArr),
                },
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(res.message);
                    $('#exportFolderModal').modal('hide');
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
                    showErrorMessage(errMessage);
                }
            });
        }
        //---------------------------------------------------//
        function exportMailBoxFolderItemsToPst() {
            event.preventDefault();
            //--------------------------------------------------//
            let items = $('#exportItemsForm .mailboxCheck:checked');
            //----------------------------------------------//
            let mailboxItems = [];
            let itemsArr = [];
            itemsArr[0] = {};
            let index;
            //-----------------------------------//
            itemsArr[0].mailboxId = getMailBox();
            itemsArr[0].mailboxTitle = $('.tree #' + mailboxId).html();
            itemsArr[0].folderId = getMailBoxFolder();
            itemsArr[0].folderTitle = $('#exportItemsForm').find(".folderTitle").val();
            //-----------------------------------//
            items.each(function() {
                let tr = $(this).closest('tr');
                mailboxItems.push({
                    id: $(this).val(),
                    parentName: $(this).attr('data-parentname'),
                    name: tr.find('td:nth-child(2)').html(),
                    folderTitle: tr.find('td:nth-child(3)').html(),

                });
            });
            itemsArr[0].items = mailboxItems;
            //----------------------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportMailBoxFolderItemsToPst') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    jobType: ($('#jobs').val() == "all" ? "all" : "single"),
                    restoreJobName: $("#exportItemsForm [name='restoreJobName']").val(),
                    jobId: $('.backupTime option:selected').attr('data-job-id'),
                    jobTime: $('.backupTime').val(),
                    showDeleted: $("#showDeleted")[0].checked,
                    showVersions: $("#showVersions")[0].checked,
                    mailboxId: $('#exportItemsForm').find('.mailboxId').val(),
                    mailboxTitle: $('#exportItemsForm').find('.mailboxName').val(),
                    folderTitle: $('#exportItemsForm').find('.folderTitle').val(),
                    items: JSON.stringify(itemsArr),
                },
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(res.message);
                    $('#exportItemsModal').modal('hide');
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
                    showErrorMessage(errMessage);
                }
            });
        }
        //---------------------------------------------------//
        function getMailBoxFolders(event) {
            $target = $(event.target);
            let spinner = $target.closest('.mailbox.has').find('.folder-spinner:first');
            if ($target.hasClass('mailCaret')) {
                mailBoxId = $target.closest('.mailbox.has').find('.mail-click').attr('id');
            } else {
                mailBoxId = $target.attr('id');
            }
            $("#" + mailBoxId).closest("div").find('.mailCaret').toggleClass('closeMail');
            if ($("#" + mailBoxId).closest(".has.mailbox").find('ul').length) {
                $("#" + mailBoxId).closest(".has.mailbox").find('ul:first').fadeToggle();
                return;
            }
            spinner.removeClass('hide');
            $.ajax({
                type: "GET",
                url: "{{ url('getMailBoxFolders') }}/" + mailBoxId,
                data: {},
                success: function(data) {
                    spinner.addClass('hide');
                    mailboxFolders = "<ul class='pt-0 pb-0'>";
                    data.forEach(function(result) {
                        mailBoxId = result.mailboxId;
                        let mailBoxName = $(".tree #" + mailBoxId).html();
                        mailboxFolders = mailboxFolders +
                            '<li class="mailboxfolder"><div class="relative allWidth inline-flex">' +
                            (result.hasFolders ?
                                '<span class="caret mailCaret closeMail" onclick="getFolderChildren(event)"></span>' :
                                '') +
                            '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                            '<input id="usUser" type="checkbox" data-mailboxId="' + mailBoxId +
                            '" data-mailboxName="' + mailBoxName +
                            '" class="mailBoxFolderCheck form-check-input" value="' +
                            result.id + '"/>' +
                            '<span class="tree-checkBox check-mark-white check-mark"></span></label>' +
                            '<img class="folderIcon" src="/svg/folders/' + getFolderIcon(result.name) +
                            '.svg">' +
                            '<span id="' + result.id +
                            '" onclick="getFolderItems(event)" data-folderType="' + result.type +
                            '" data-mailboxId="' + mailBoxId +
                            '" class="mail-click childmail-click mail-folder-click" title="' + result
                            .name + '">' +
                            result.name + '</span></div>' + getFolderSubFoldersMenu(result) + '</li>';
                    });
                    mailboxFolders = mailboxFolders + "</ul>";
                    $("#" + mailBoxId).closest("li")[0].append($(mailboxFolders)[0]);
                    $("#" + mailBoxId).closest(".has.mailbox").find('ul:first').fadeToggle();
                    // Allow Single Mailbox folders
                    $('.mailBoxFolderCheck').change(function() {
                        mailBoxFolderCheckChange();
                    });
                    //--------------------------------------//
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
        }
        //---------------------------------------------------//
        function restoreMailboxAnother() {
            event.preventDefault()

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
            //----------------------------//
            $.ajax({
                type: "POST",
                url: "{{ url('restoreMailBoxAnother') }}",
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobTime=" + $('.backupTime').val() +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&mailboxes=" + JSON.stringify(mailboxesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreMailboxAnother').modal('hide');
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
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobTime=" + $('.backupTime').val() +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&mailboxes=" + JSON.stringify(mailboxesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreMailboxOriginal').modal('hide');
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
        //---------------------------------------------------//
        function restoreFolder() {
            event.preventDefault()
            var data = $('#restoreFolderForm').serialize();
            //-----------------------------------//
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
            //----------------------------------------------//
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
            //----------------------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreFolder') }}",
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobTime=" + $('.backupTime').val() +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&folders=" + JSON.stringify(foldersArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreFolder').modal('hide');
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
        //---------------------------------------------------//
        function restoreItem() {
            event.preventDefault()
            let items = $('#restoreItemForm .mailboxCheck:checked');
            //----------------------------------------------//
            let mailboxItems = [];
            let itemsArr = [];
            itemsArr[0] = {};
            //-----------------------------------//
            itemsArr[0].mailboxId = getMailBox();
            itemsArr[0].mailboxTitle = $('.tree #' + mailboxId).html();
            itemsArr[0].folderId = getMailBoxFolder();
            itemsArr[0].folderTitle = $('#restoreItemForm').find(".folderTitle").val();
            //-----------------------------------//
            items.each(function() {
                let tr = $(this).closest('tr');
                mailboxItems.push({
                    id: $(this).val(),
                    parentName: $(this).attr('data-parentname'),
                    name: tr.find('td:nth-child(2)').html(),
                    folderTitle: tr.find('td:nth-child(3)').html(),
                });
            });
            itemsArr[0].items = mailboxItems;
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
            $.ajax({
                type: "POST",
                url: "{{ url('restoreItem') }}",
                data: data + '&items=' + JSON.stringify(itemsArr) +
                    "&_token={{ csrf_token() }}&folderTitle=" + $("#restoreItemForm").find("#folder").val() +
                    "&jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked,
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreItem').modal('hide');
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
        //---------------------------------------------------//
    </script>
@endsection
