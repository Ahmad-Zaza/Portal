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
        .tooltip-inner {
            background-color: black;
            color: white
        }

        .downloadIMG {
            text-align-last: center;
        }
    </style>
    <div class="col-sm-10  navbarLayout">
        <!-- Upper navbar -->
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm upperNavBar"> -->
        <ul class="ulNavbar">

            <li class="liNavbar"><a class="active" href="{{ url('restore-history', $data['repo_kind']) }}">Restore History
                    <img class="nav-arrow" src="/svg/arrow-right-active.svg">
                    {{ getDataType($data['repo_kind']) }}</a></li>
            <!-- Authentication Links -->
            @include('layouts.authentication-links')
        </ul>
    </div>
@endsection
@section('content')
    <div id="mainContent">
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <div id="searchModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg w-500 mv-top">
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

                        <div class="row">
                            <div class="input-form-70 mb-1 inline-flex">
                                <div class="input-form-70 mb-1">Job Name:</div>
                                <div class="input-form-70 mb-1 ml-30">Job Type: </div>
                            </div>
                            <div class="input-form-70 inline-flex">
                                <div class="mr-25 relative">
                                    <input type="text" class="form_input form-control custom-form-control font-size"
                                        id="job_name" placeholder="" />
                                </div>

                                <div class="relative">
                                    <input type="text" class="form_input form-control custom-form-control font-size"
                                        id="job_type" placeholder="" />
                                </div>
                            </div>
                        </div>

                        <div class="row" id="Duration-Section">
                            <div class="input-form-70 mb-1">Request Time:</div>
                            <div class="input-form-70 inline-flex">
                                <div class="mr-25 relative">
                                    <input type="text" class="form_input form-control custom-form-control font-size"
                                        id="RequestFrom" placeholder="From" />
                                </div>

                                <div class="relative">
                                    <input type="text" class="form_input form-control custom-form-control font-size"
                                        id="RequestTo" placeholder="To" />
                                    {{-- <span style="right:12px;" class="timepicker-icon text-white">max</span> --}}
                                </div>
                            </div>
                        </div>
                        <div class="row" id="Duration-Section">
                            <div class="input-form-70 mb-1">Completion Time:</div>
                            <div class="input-form-70 inline-flex">
                                <div class="mr-25 relative">
                                    <input type="text" class="form_input form-control custom-form-control font-size"
                                        id="CompletionFrom" placeholder="From" />
                                </div>

                                <div class="relative">
                                    <input type="text" class="form_input form-control custom-form-control font-size"
                                        id="CompletionTo" placeholder="To" />
                                </div>
                            </div>
                        </div>
                        <div class="row" id="Duration-Section">
                            <div class="input-form-70 mb-1">Expiration Time:</div>
                            <div class="input-form-70 inline-flex">
                                <div class="mr-25 relative">
                                    <input type="text" class="form_input form-control custom-form-control font-size"
                                        id="ExpirationFrom" placeholder="From" />
                                </div>

                                <div class="relative">
                                    <input type="text" class="form_input form-control custom-form-control font-size"
                                        id="ExpirationTo" placeholder="To" />
                                </div>
                            </div>
                        </div>
                        <div class="row" id="Duration-Section">
                            <div class="input-form-70 mb-1">Items Count:</div>
                            <div class="input-form-70 inline-flex">
                                <div class="mr-25 relative">
                                    <input type="number" class="form_input form-control custom-form-control font-size"
                                        id="ItemsCountFrom" placeholder="From" />
                                </div>
                                <div class="relative">
                                    <input type="number" class="form_input form-control custom-form-control font-size"
                                        id="ItemsCountTo" placeholder="To" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-form-70 mb-1">Job Status:</div>
                            <div class="input-form-70 inline-flex">
                                <div class="relative w-60">
                                    <label class="checkbox-container checkbox-search checkbox-padding-left0">&nbsp;
                                        <input id="successCheckBox" type="checkbox" class="form-check-input" />
                                        <span class="check-mark checkbox-span-class"></span>
                                    </label>
                                    <span class="ml-25">Success</span>
                                </div>
                                <div class="halfWidth relative">
                                    <label class="checkbox-container checkbox-search checkbox-padding-left0">&nbsp;
                                        <input id="failedCheboxBox" type="checkbox" class="form-check-input" />
                                        <span class="check-mark checkbox-span-class"></span>
                                    </label>
                                    <span class="ml-25">Failed</span>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 inline-flex">
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
        <div id="confirmationModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content ">
                    <div id="modalBody_id" class="modalContent">
                        <div class="alert  exchange-custom-confirmation" role="alert">
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
        <div id="exportedFilesModal" class="modal modal-center" role="dialog">
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
                                <div>
                                    <h4 class="per-req">Exported Files</h4>
                                    <div class="temp-header basic-color"></div>
                                </div>
                            </div>
                        </div>
                        <form id="exportedFilesForm" class="mb-0">
                            <input type="hidden" name="historyId">
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Exported Files</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder">
                                        <div class="allWidth exportedFilesTable mb-3">
                                            <table id="exportedFilesTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <td>Name</td>
                                                        <td>File</td>
                                                        <td>Download</td>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-form-70 inline-flex">
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
                                                                <label
                                                                    class="checkbox-container checkbox-search checkbox-top-left">
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
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
                                                    <label class="mr-2 m-0 nowrap">Folder to Restore:</label>
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
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Changed Items</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="relative allWidth mb-2">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="deletedItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Deleted Items</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="ml-10">Flag Restored Items as Unread:</label>
                                                <div class="allWidth relative ml-15">
                                                    <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                        <input name="markRestoredAsunread" type="checkbox"
                                                            class="form-check-input" />
                                                        <span class="check-mark checkbox-span-class"></span>
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
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
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
                                        <div class="col-lg-12 customBorder pb-3 pt-3 h-392p">

                                            <div class="row pl-3 mb-10">Specify Restore Options:</div>
                                            <div class="row">
                                                <div class="pl-3">
                                                    <label>Exclude the Following Items:</label>
                                                </div>
                                                <div class="w-100"></div>
                                                <div class="col">
                                                    <div class="relative  mb-2">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="excludeDrafts" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Drafts</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="allWidth relative  mb-2">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="excludeDeletedItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Deleted Items</span>
                                                    </div>
                                                </div>
                                                <div class="w-100"></div>
                                                <div class="col">
                                                    <div class="allWidth relative  mb-2">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="excludeInplaceHolditems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">In-Place Hold Items</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="allWidth relative  mb-2">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="excludeLitigationHoldItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-class"></span>
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
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="changedItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Changed Items</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="relative allWidth mb-2">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="deletedItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Deleted Items</span>
                                                    </div>
                                                </div>
                                                <div class="w-100"></div>
                                                <div class="col">
                                                    <div class="relative mb-2">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="skipUnresolved" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Skip Unresolved Items</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <label class="mt-3 ml-10">Flag Restored Items as Unread:</label>
                                                <div class="allWidth relative mb-3 ml-15">
                                                    <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                        <input name="markRestoredAsunread" type="checkbox"
                                                            class="form-check-input" />
                                                        <span class="check-mark checkbox-span-class"></span>
                                                    </label>
                                                    <span class="ml-25">Mark Restored As Unread</span>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-12 pl-3 pr-3">
                                                    <div class="allWidth inline-flex">
                                                        <div class="allWidth relative ml-5p">
                                                            <label
                                                                class="checkbox-container checkbox-padding-left0-top">&nbsp;
                                                                <input name="RecentItemRestorePeriod" type="checkbox"
                                                                    class="form-check-input" />
                                                                <span class="check-mark checkbox-span-class"></span>
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
                        <div class="row mb-15">
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
                                                            <label
                                                                class="checkbox-container checkbox-search checkbox-top-left">
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
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="enablePstSizeLimit" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-top12"></span>
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
                        <div class="row mb-15">
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
                                                            <label
                                                                class="checkbox-container checkbox-search checkbox-top-left">
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
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="enablePstSizeLimit" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-top12"></span>
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
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <div id="exportItemModal" class="modal modal-center" role="dialog">
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
                        <div class="row mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p">Export Selected Items</h4>
                            </div>
                        </div>
                        <form id="exportItemForm" class="mb-0" onsubmit="exportItem(event)">
                            <input type="hidden" class="restoreType" name="restoreType" />
                            <input type="hidden" class="items" name="items" />
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
                                                        <label
                                                            class="checkbox-container checkbox-search checkbox-top-left">
                                                            <input type="checkbox" checked class="form-check-input">
                                                            <span class="tree-checkBox check-mark"></span>
                                                        </label>
                                                    </td>
                                                    <td>Mailbox</td>
                                                    <td>Subject</td>
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
                                        <div class="col-lg-12 customBorder h-180p">
                                            <div class="allWidth mailboxesresultsTable">
                                                <table id="mailboxAnotherTable"
                                                    class="stripe table table-striped table-dark display nowrap allWidth mailboxesresultsTable">
                                                    <thead class="table-th">
                                                        <tr>
                                                            <td>
                                                                <label
                                                                    class="checkbox-container checkbox-search checkbox-top-left">
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
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
                                                <div class='w-100'></div>
                                                <div class="col">
                                                    <div class="relative  mb-2">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="excludeDrafts" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Drafts</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="allWidth relative  mb-2">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="excludeInplaceHolditems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">In-Place Hold Items</span>
                                                    </div>
                                                </div>
                                                <div class="w-100"></div>
                                                <div class="col">
                                                    <div class="allWidth relative  mb-2">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="excludeDeletedItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Deleted Items</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="allWidth relative  mb-2">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="excludeLitigationHoldItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Litigation Hold Items</span>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="pr-0 pl-3 ml-15">
                                                        <label>Restore the following items:</label>
                                                    </div>
                                                    <div class="w-100"></div>
                                                    <div class="col">
                                                        <div class="relative allWidth mb-2 ml-15">
                                                            <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                <input name="changedItems" type="checkbox"
                                                                    class="form-check-input" />
                                                                <span class="check-mark checkbox-span-class"></span>
                                                            </label>
                                                            <span class="ml-25">Changed Items</span>
                                                        </div>
                                                    </div>
                                                    <div class='col'>
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                <input name="deletedItems" type="checkbox"
                                                                    class="form-check-input" />
                                                                <span class="check-mark checkbox-span-class"></span>
                                                            </label>
                                                            <span class="ml-25">Deleted Items</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-1">
                                                    <label class="mt-3 ml-25">Flag Restored Items as Unread:</label>
                                                    <div class="allWidth relative ml-30">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="markRestoredAsunread" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-class"></span>
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
                                <input type="hidden" class="items" name="items" />
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
                                                            <label
                                                                class="checkbox-container checkbox-search checkbox-top-left">
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
                                                    <label class="mr-2 m-0 nowrap">Folder to Restore:</label>
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
                                        <div class="col-lg-12 customBorder pb-3 pt-3">

                                            <div class="row pl-3 mb-10">Specify Restore Options:</div>
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
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Changed Items</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="relative allWidth mb-2">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="deletedItems" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Deleted Items</span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <label class="mt-2 ml-25">Flag Restored Items as Unread:</label>
                                                    <div class="allWidth relative ml-30">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="markRestoredAsunread" type="checkbox"
                                                                class="form-check-input" />
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Mark Restored As Unread</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row restoreItemsWarning mb-4">
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
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <div class="row">&nbsp;</div>
        <div>
            @if ($role->hasPermissionTo('exchange_create_restore_session'))
                <a href="/restore/exchange">
                    <button class="custom-back-button btn_primary_state custom-back-btn left-float">
                        New Exchange Restore
                    </button>
                </a>
            @endif
        </div>
        <!-- All History table -->
        <div class="row">
            <div class="repositoryTable">
                <table id="historyTable" class="stripe nowrap table table-striped table-dark w-100">
                    <thead class="table-th">
                        <th class="noExport firstAfter">
                        </th>
                        <th>Job Name</th>
                        <th>Job Type</th>
                        <th>Status</th>
                        <th>Count</th>
                        <th>Request Time</th>
                        <th>Completion Time</th>
                        <th>Expiration Time</th>
                        <th class="noExport">Actions</th>
                    </thead>
                    <tbody class="repo-table-padding" id="table-content">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
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

            $('#historyTable').DataTable({
                'ajax': {
                    "type": "GET",
                    "async": false,
                    "url": "{{ url('getHistoryContent/' . $data['repo_kind']) }}",
                    "dataSrc": function(json) {
                        CheckHistoryTable();
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
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if ($(this).hasClass('jobName'))
                            $(this).attr('title', $(this).find('a').html());
                        else if (!$(this).hasClass('hasHTML'))
                            $(this).attr('title', $(this).html());
                    });
                },
                "order": [
                    [5, 'desc']
                ],
                'columns': [{
                        "data": null,
                        "class": "hasHTML pl-4",
                        "width": "2%",
                        render: getMainIcon
                    },
                    {
                        "data": null,
                        "width": "20%",
                        "class": "hasHTML text-left jobName",
                        render: function(data) {
                            if (data.restore_session_guid) {
                                @if ($role->hasPermissionTo('exchange_view_history_details'))
                                    return "<a href='/restore/" + "{{ $data['repo_kind'] }}" +
                                        "/session/" + data.restore_session_guid +
                                        "'>" + data.name + "</a>";
                                @endif
                                return '';
                            } else
                                return "<div style='word-wrap: break-word;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;' title='" +
                                    data.name + "'>" + data.name + "</div>";
                        }
                    },
                    {
                        "data": "sub_type",
                        "width": "17%",
                        "class": "text-center"
                    },
                    {
                        "class": "hasHTML",
                        "width": "10%",
                        "data": null,
                        render: function(data, type, full, meta) {
                            var statusClass = 'text-orange1';
                            if (data.status == 'In Progress' && !data.restore_session_guid)
                                data.status = "Waiting";
                            statusClass = (data.status == 'Failed') ? 'text-danger' : statusClass;
                            statusClass = (data.status == 'Success') ? 'text-success' : statusClass;
                            statusClass = (data.status == 'In Progress') ? 'text-primary' :
                                statusClass;
                            statusClass = (data.status == 'Waiting') ? 'text-warning' : statusClass;
                            statusClass = (data.status == 'Canceled') ? 'text-secondary' :
                                statusClass;
                            statusClass = (data.status == 'Expired') ? 'text-secondary' :
                                statusClass;
                            if (data.restore_session_guid)
                                return "<a class='" + statusClass + "' href='/restore/" +
                                    "{{ strtolower($data['repo_kind']) }}" + "/session/" +
                                    data.restore_session_guid + "'>" + data.status + "</a>";
                            return data.status ? '<a  href="#" class="' + statusClass + '"> ' + data
                                .status + ' <a>' : "";
                        }
                    },
                    {
                        "width": "5%",
                        "data": "items_count"
                    },
                    {
                        "width": "10%",
                        "data": "request_time"
                    },
                    {
                        "width": "12.5%",
                        "data": "completion_time"
                    },
                    {
                        "width": "12.5%",
                        "data": "expiration_time"
                    },
                    {
                        "data": null,
                        "class": "hasHTML",
                        "width": "10%",
                        render: getMenu
                    }
                ],
                dom: 'Bfrtip',
                buttons: [

                    {
                        extend: 'csvHtml5',
                        text: '<img src="/svg/excel.svg" class="new-filter">',
                        titleAttr: 'Export to csv',
                        exportOptions: {
                            columns: 'thead th:not(.noExport)',
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<img src="/svg/pdf.svg" class="new-filter">',
                        customize: function(doc) {
                            customizePdf(doc)
                        },
                        titleAttr: 'Export to pdf',
                        exportOptions: {
                            columns: 'th:not(:first-child,:last-child)',
                            format: {
                                body: function(data, column, row) {
                                    data = data + "";
                                    //if it is html, return the text of the html instead of html
                                    if (data.includes("<i")) {
                                        return 1;
                                    } else if (/<\/?[^>]*>/.test(data)) {
                                        return $(data).text();
                                    } else {
                                        return data;
                                    }
                                }
                            }
                        }
                    },
                    {
                        text: '<img src="/svg/filter.svg" class="new-filter">',
                        titleAttr: 'Advanced Search',
                        action: function(e, dt, node, config) {
                            $('#searchModal').modal('show');
                        }
                    }
                ],
                "fnDrawCallback": function() {
                    var icon =
                        '<div class="search-container"><img class="search-icon history-search-icon" src="/svg/search.svg"></div>';
                    if ($(".dataTables_filter label").find('.search-icon').length == 0)
                        $('.dataTables_filter label').append(icon);
                    $('.dataTables_filter input').addClass('form_input form-control mb-25');
                    //--------------------------------------//
                    $('.cancelJob').click(function() {
                        cancelRestoreModal($(this).attr('data-id'));
                    });
                    //--------------------------------------//
                    $('.expireJob').click(function() {
                        expireRestoreModal($(this).attr('data-id'));
                    });
                    //--------------------------------------//
                    $('.restoreAgain').click(function() {
                        //----------------------------------------//

                        //----------------------------------------//
                        let subType = $(this).attr('data-sub-type');
                        let isRestore = (subType.match(/Restore/g) || []).length;
                        if (isRestore > 0)
                            restoreAgainModal('all', $(this).attr('data-id'), $(this).attr(
                                'data-type'), subType);
                        else
                            exportAgainModal('all', $(this).attr('data-id'), $(this).attr(
                                'data-type'), subType);
                        //----------------------------------------//
                    });
                    //--------------------------------------//
                    $('.restoreFailedAgain').click(function() {
                        //--------------------------------------------//
                        //--------------------------------------------//
                        let subType = $(this).attr('data-sub-type');
                        let isRestore = (subType.match(/Restore/g) || []).length;
                        //--------------------------------------------//
                        if (isRestore > 0)
                            restoreAgainModal('failed', $(this).attr('data-id'), $(this).attr(
                                'data-type'), subType);
                        else
                            exportAgainModal('failed', $(this).attr('data-id'), $(this).attr(
                                'data-type'), subType);
                        //--------------------------------------------//
                    });
                    //--------------------------------------//
                    $('.exportedFiles').click(function() {
                        var tr = $(this).closest('tr');
                        exportedFilesModal($(this).attr('data-id'), tr.find('.details')
                            .html(), tr.find('.jobName a').html());
                    });
                    //--------------------------------------//
                },
                "scrollY": "600px",
                "scrollCollapse": true,
                "bInfo": false,
                "paging": false,
                "autoWidth": false,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                },
                'columnDefs': [{
                    'targets': [0, 8], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            $('#historyTable').DataTable().buttons().container()
                .prependTo('#historyTable_filter');
            $("#sizeFrom").change(function() {
                $('#historyTable').DataTable().draw();
            });
            //-------------------------------------------//
            $('form [name="enablePstSizeLimit"]').change(function() {
                if ($(this).prop('checked') == true) {
                    $(this).closest('form').find('[name="sizeLimit"]').attr('required', 'required');
                } else {
                    $(this).closest('form').find('[name="sizeLimit"]').removeAttr('required');
                }
            });
            //-------------------------------------------//
            $('#exportedFilesTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('hasHTML'))
                            $(this).attr('title', $(this).html());
                    });
                },
                "order": [
                    [0, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": "name",
                        "width": "40%"
                    },
                    {
                        "data": "fileName",
                        "width": "40%"
                    },
                    {
                        "data": null,
                        "class": "hasHTML downloadIMG",
                        "width": "20%",
                        render: function(data) {
                            @if ($role->hasPermissionTo('exchange_download_exported_files'))
                                if (data.fileName)
                                    return '<img data-id="' + data.id +
                                        '" class="tableIcone hand downloadExportedFile w-13 mr-0" src="/svg/download\.svg " title="Download">';
                            @endif
                        }
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {
                    $('.downloadExportedFile').click(function() {
                        downloadExportedFiles($(this).attr('data-id'));
                    })
                },
                "scrollY": "200px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [2], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //----------------------
            $('#mailboxesTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('hasHTML'))
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
            //----------------------
            $('#foldersTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('hasHTML'))
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
            //----------------------
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
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
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
                "scrollY": "130px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //----------------------
            $('#exportFolderTable').DataTable({
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
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
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
            //----------------------
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
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
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
                "scrollY": "102px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //----------------------
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
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
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
            //----------------------
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
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
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
                "scrollY": "110px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //----------------------
            $('#mailboxItemsTable').DataTable({
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
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
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
            //----------------------
            $('#exportItemTable').DataTable({
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
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" mailboxId="' + data
                                .mailboxId + '" mailboxTitle="' + data.mailboxTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "mailboxTitle",
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
            //-------------------------------------------//
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
            //-------------------------------------------//
            $('form [name="RecentItemRestorePeriod"]').change(function() {
                if ($(this).prop('checked') == true) {
                    $(this).closest('form').find('[name="daysNumber"]').attr('required', 'required');
                } else {
                    $(this).closest('form').find('[name="daysNumber"]').removeAttr('required');
                }
            });
            //-------------------------------------------//
            $("#RequestFrom").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $("#historyTable").DataTable().draw();
                }
            });
            $("#RequestTo").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $("#historyTable").DataTable().draw();
                }
            });
            $("#CompletionFrom").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $("#historyTable").DataTable().draw();
                }
            });
            $("#CompletionTo").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $("#historyTable").DataTable().draw();
                }
            });
            $("#ExpirationFrom").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $("#historyTable").DataTable().draw();
                }
            });
            $("#ExpirationTo").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $("#historyTable").DataTable().draw();
                }
            });
            //-------------------------------------------//
        });

        function getMenu(data) {
            var temp = data.sub_type;
            var isRestore = (temp.match(/Restore/g) || []).length;
            //-----------------------//
            let buttons = '';
            //-----------------------//
            if (isRestore == 0 && !['Expired', 'In Progress', 'Canceled', 'Waiting'].includes(data.status)) {
                buttons +=
                    '<img class="tableIcone hand exportedFiles ml-2 w-15 mr-0" data-id="' + data
                    .id + '" src="/svg/exported_files\.svg" title="Exported Files">';
            }
            //-----------------------//
            if (['In Progress', 'Waiting'].includes(data.status)) {
                buttons +=
                    '<img class="tableIcone hand cancelJob ml-2 w-15 mr-0" data-id="' + data
                    .id + '" src="/svg/stop\.svg" title="Cancel Job">';
            }
            //-----------------------//
            @if ($role->hasPermissionTo('exchange_restore_again'))
                if (isRestore > 0 && ($.inArray(data.status, ['Failed','Waiting', 'Canceled', 'Success']) != -1 )) {
                    buttons += '<img data-type="' + data.type + '" data-sub-type="' + data.sub_type +
                        '" class="tableIcone hand restoreAgain ml-2 w-15 mr-0" data-id="' + data.id +
                        '" src="/svg/restore_again\.svg " title="Restore Again">';
                }

                if (isRestore > 0 && data.status == 'Failed' && !checkFiledAll(data)) {
                    buttons += '<img data-type="' + data.type + '" data-sub-type="' + data.sub_type +
                        '" class="tableIcone hand restoreFailedAgain ml-2 w-15 mr-0" data-id="' + data.id +
                        '" src="/svg/restore_failed\.svg " title="Restore Failed Again">';
                }
            @endif
            @if ($role->hasPermissionTo('exchange_export_again'))
                if (isRestore == 0 && $.inArray(data.status, ['Canceled', 'Expired', 'Failed', 'Waiting']) != -1) {
                    buttons += '<img data-type="' + data.type + '" data-sub-type="' + data.sub_type +
                        '" class="tableIcone hand restoreAgain ml-2 w-15 mr-0" data-id="' + data.id +
                        '" src="/svg/restore_again\.svg " title="Restore Again">';
                }
                if (isRestore == 0 && data.status == 'Failed' && !checkFiledAll(data)) {
                    buttons += '<img data-type="' + data.type + '" data-sub-type="' + data.sub_type +
                        '" class="tableIcone hand restoreFailedAgain ml-2 w-15 mr-0" data-id="' + data.id +
                        '" src="/svg/restore_failed\.svg " title="Restore Failed Again">';
                }
            @endif
            //-----------------------//
            @if ($role->hasPermissionTo('exchange_force_expire'))
                if ($.inArray(data.status, ['In Progress', 'Canceled', 'Expired']) == -1 && isRestore == 0) {
                    buttons += '<img class="tableIcone hand expireJob ml-2 w-13 mr-0" data-id="' +
                        data.id + '" src="/svg/stop\.svg" title="Force Expire">';
                }
            @endif
            //-----------------------//
            return buttons;
        }

        function checkFiledAll(data) {
            let curr_details = data.details;
            for (let i = 0; i < curr_details.length; i++) {
                if (curr_details[i].status == "Success")
                    return false;
            }
            return true;
        }

        function getMainIcon(data) {
            let details = JSON.stringify(data.details);
            return '<div name="details" class="details hide">' + details + '</div>' +
                '<input type="hidden" class="historyStatus" value="' + data.status + '">' +
                '<img class= "tableIcone w-13 mr-0 ml-0" src="/svg/history.svg " title="History">';
        }

        function confirmDelete(deletedRepId, deletedRepName) {
            document.getElementById('deletedrep').value = deletedRepId;
            $("#deleteTxt").html("Delete Storage " + deletedRepName + " ?");

        }

        function showErrorMessage(message) {
            $(".danger-oper .danger-msg").html(message);
            $(".danger-oper").css("display", "block");
            setTimeout(function() {
                $(".danger-oper").css("display", "none");

            }, 8000);
        }

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
            return day + "/" + month + "/" + year + " " + hours + ":" + minutes;

        }

        //-------------------------------------------------------------//
        function CheckHistoryTable() {
            setTimeout(() => {
                $('#historyTable').DataTable().ajax.reload();
            }, 15000);
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
        function restoreAgainModal(state = 'all', id, type, subType) {
            $("[name='restoreJobName']").val("");
            let options = [];
            let tableData = [];
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "GET",
                url: "{{ url('getHistoryDetails', $data['repo_kind']) }}/" + id,
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
                    if (type == "mailbox") {
                        options = JSON.parse(data.options);
                        let mailBoxes = data.details;
                        tableData = [];
                        let unresolvedCount = 0;
                        let mailboxCount = mailBoxes.length;
                        mailBoxes.forEach((e) => {
                            //-------------------------------------//
                            let resolved = true;
                            if (!options.toMailBox) {
                                if (checkResolvedMail(e.item_parent_name)) {
                                    unresolvedCount++;
                                    mailboxCount--;
                                    resolved = false;
                                }
                            }
                            //-------------------------------------//
                            if (state == 'all' || e.status == "Failed")
                                tableData.push({
                                    id: e.item_id,
                                    name: e.item_name,
                                    resolved: resolved,
                                    email: e.item_parent_name
                                });
                        });
                        if (options.toMailBox) {
                            //---------------------------------------------//
                            $('#mailboxAnotherTable_wrapper').find('.boxesCount').html(mailboxCount);
                            $('#mailboxAnotherTable').DataTable().clear().draw();
                            $('#mailboxAnotherTable').DataTable().rows.add(tableData); // Add new data
                            $('#mailboxAnotherTable').DataTable().columns.adjust()
                                .draw(); // Redraw the DataTable
                            //------------------------------------------------//
                            checkTableCount('mailboxAnotherTable');
                            adjustTable();
                            $("#mailboxAnotherTable").DataTable().draw();
                            //------------------------------------------------//
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
                            //---------------------------------------------//
                            $('#mailboxOriginalTable_wrapper').find('.boxesCount').html(mailboxCount);
                            $('#mailboxOriginalTable').DataTable().clear().draw();
                            $('#mailboxOriginalTable').DataTable().rows.add(tableData); // Add new data
                            $('#mailboxOriginalTable').DataTable().columns.adjust()
                                .draw(); // Redraw the DataTable
                            //------------------------------------------------//
                            checkTableCount('mailboxOriginalTable');
                            adjustTable();
                            $("#mailboxOriginalTable").DataTable().draw();
                            //---------------------------------------------//
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
                            $("#restoreMailboxAnother .mailboxesresultsTable table").DataTable().clear()
                                .draw();
                            $('#restoreMailboxOriginal').find(".refreshDeviceCode").click();
                            $('#restoreMailboxOriginal').modal('show');
                        }
                    } else if (type == "folder") {

                        //-------------------------------------------------//
                        tableData = [];
                        options = JSON.parse(data.options);
                        let folders = data.details;
                        let unresolvedCount = 0;
                        folders.forEach((e) => {
                            let resolved = true;
                            if (state == 'all' || e.status == "Failed")
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
                        $('#foldersResultsTable').DataTable().columns.adjust()
                            .draw(); // Redraw the DataTable

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
                                    $('#restoreFolderForm [name="folderType"][value="' + value +
                                            '"]')
                                        .prop("checked", "checked");
                                    if (value == "original") {
                                        $('#restoreFolderForm [name="folder"]').val("").attr(
                                            'disabled',
                                            'disabled');
                                    } else {
                                        $('#restoreFolderForm [name="folder"]').removeAttr(
                                            'disabled');
                                    }
                                } else if (key != "folderType") {
                                    $('#restoreFolderForm input[name="' + key + '"]')
                                        .removeProp('checked');
                                }
                            });
                            $('.modal-folder-width').addClass('modal-width');
                            $('.modal-folder-left').addClass('custom-left-col');
                            $('.modal-folder-right').addClass('custom-right-col');
                            $('.modal-folder-margin').addClass('ml-108');
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

                        //--------------------//
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
                                        $('#restoreItemForm .targetMailbox input[name="folder"]')
                                            .attr(
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
                                        $('#restoreItemForm [name="folder"]').val("").attr(
                                            'disabled',
                                            'disabled');
                                    } else {
                                        $('#restoreItemForm [name="folder"]').removeAttr(
                                            'disabled');
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
                            $('#restoreItem').find('.modal-title').html(
                                'Restore Items to Another Location');
                            $('#restoreItem').find('.restoreType').val('another');
                        } else {
                            $('.modal-item-width').removeClass('modal-width');
                            $('.modal-item-left').removeClass('custom-left-col');
                            $('.modal-item-right').removeClass('custom-right-col');
                            $('.modal-item-margin').removeClass('ml-100');
                            $('.itemsAnotherOptions_cont').addClass('hide');
                            $('.itemsAnotherOptions_cont .required').removeAttr('required');
                            $('#restoreItem').find('.modal-title').html(
                                'Restore Items to Original Location');
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
        function exportAgainModal(state = 'all', id, type, subType) {
            $("[name='restoreJobName']").val("");
            let options = [];
            let tableData = [];
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "GET",
                url: "{{ url('getHistoryDetails', $data['repo_kind']) }}/" + id,
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
                            if (state == 'all' || e.status == "Failed")
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
                        $('#exportMailboxTable').DataTable().columns.adjust()
                            .draw(); // Redraw the DataTable
                        //------------------------------------------------//
                        checkTableCount('exportMailboxTable');
                        adjustTable();
                        $("#exportMailboxTable").DataTable().draw();
                        $("#restoreMailboxAnother .mailboxesresultsTable table").DataTable().clear().draw();
                        $("#restoreMailboxOriginal .mailboxesresultsTable table").DataTable().clear()
                            .draw();
                        //------------------------------------------------//
                        if (options.enablePstSizeLimit == "true") {
                            $('#exportMailboxForm').find('[name="enablePstSizeLimit"]').attr('checked',
                                "checked");
                            $('#exportMailboxForm').find('[name="sizeLimit"]').attr("required", "required");
                        } else {
                            $('#exportMailboxForm').find('[name="enablePstSizeLimit"]').removeAttr(
                                'checked');
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
                            if (state == 'all' || e.status == "Failed")
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
                            $('#exportFolderForm').find('[name="enablePstSizeLimit"]').removeAttr(
                                'checked');
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
        function exportedFilesModal(id, details, jobName) {
            $('#exportedFilesModal .temp-header').html("<br> " + jobName);
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
        //-------------------------------------------------------------//
        function exportFolder() {
            event.preventDefault();
            //--------------------------------------------//
            let data = $('#exportFolderForm').serialize();
            data += "&enablePstSizeLimit=" + $('#exportFolderForm [name="enablePstSizeLimit"]')[0].checked;
            data += "&sizeLimit=" + $('#exportFolderForm [name="sizeLimit"]').val();
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
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&_token={{ csrf_token() }}&folders=' + JSON.stringify(foldersArr),
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(res.message);
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
        function exportItem() {
            event.preventDefault();
            //--------------------------------------------------//
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
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportMailBoxFolderItemsToPst') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&_token={{ csrf_token() }}&' +
                    'mailboxTitle=' + $('#exportItemForm').find('.mailboxName').val() + '&' +
                    'items=' + JSON.stringify(mailboxArr),
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(res.message);
                    $('#exportItemModal').modal('hide');
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
        function downloadExportedFiles(id) {
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "GET",
                url: "{{ url('downloadExportedFile') }}/exchange/" + id,
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: {},
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
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
            }, 8000);
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
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "&mailboxes=" + JSON.stringify(mailboxesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
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
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "&mailboxes=" + JSON.stringify(mailboxesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
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
            if ($('#restoreFolderForm').find(".restoreType").val() == "another") {
                if (!$('#restoreFolderForm').find("[name='changedItems']").prop("checked") && !$('#restoreFolderForm')
                    .find(
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
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "&folders=" + JSON.stringify(foldersArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
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
            //-------------------------------------------------//
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
            //-------------------------------------------------//
            $.ajax({
                type: "POST",
                url: "{{ url('restoreItem') }}",
                data: data + '&items=' + JSON.stringify(mailboxArr) +
                    "&_token={{ csrf_token() }}&folderTitle=" + $("#restoreItemForm").find("#folder").val(),
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
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
        //-------------------------------------------------------------//
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                res = true;
                JobName = $("#job_name").val();
                JobType = $("#job_type").val();
                RequestFrom = $("#RequestFrom").datepicker('getDate');
                RequestTo = $("#RequestTo").datepicker('getDate');
                CompletionFrom = $("#CompletionFrom").datepicker('getDate');
                CompletionTo = $("#CompletionTo").datepicker('getDate');
                ExpirationFrom = $("#ExpirationFrom").datepicker("getDate");
                ExpirationTo = $("#ExpirationTo").datepicker("getDate");
                ItemsCountFrom = $("#ItemsCountFrom")[0].value;
                ItemsCountTo = $("#ItemsCountTo")[0].value;
                let conditionsArray = [];

                if (JobName) {
                    if (!data[1])
                        return false;
                    var value = data[1].toLowerCase();
                    if (!value.toString().includes(JobName.toLowerCase()))
                        return false;
                }

                if (JobType) {
                    if (!data[2])
                        return false;
                    var value = data[2].toLowerCase();
                    if (!value.toString().includes(JobType.toLowerCase()))
                        return false;
                }

                if ((RequestFrom || RequestTo) && !data[5]) {
                    res = false;
                } else {
                    if (RequestFrom) {
                        if (new Date(RequestFrom) > new Date(data[5])) {
                            res = false;
                        }
                    }
                    if (RequestTo) {
                        if (new Date(RequestTo) < new Date(data[5])) {
                            res = false;
                        }
                    }
                }
                if ((CompletionFrom || CompletionTo) && !data[6]) {
                    res = false;
                } else {

                    if (CompletionFrom) {

                        if (new Date(CompletionFrom) > new Date(data[6])) {
                            res = false;
                        }
                    }
                    if (CompletionTo) {
                        if (new Date(CompletionTo) < new Date(data[6])) {
                            res = false;
                        }
                    }
                }

                if ((ExpirationFrom || ExpirationTo) && !data[7]) {
                    res = false;
                } else {

                    if (ExpirationFrom) {

                        if (new Date(ExpirationFrom) > new Date(data[7])) {
                            res = false;
                        }
                    }
                    if (ExpirationTo) {
                        if (new Date(ExpirationTo) < new Date(data[7])) {
                            res = false;
                        }
                    }
                }

                if (ItemsCountFrom && ItemsCountFrom > data[4]) {
                    res = false;
                }
                if (ItemsCountTo && ItemsCountTo < data[4]) {
                    res = false;
                }

                if ($("#successCheckBox")[0].checked === true) {
                    conditionsArray.push("success");
                }
                if ($("#failedCheboxBox")[0].checked === true) {
                    conditionsArray.push("failed");
                }
                if (conditionsArray.length > 0 && (!data[3] || conditionsArray.indexOf(data[3].toLowerCase()) === -
                        1)) {
                    res = false;
                }
                return res;
            }
        );

        function resetSearch() {
            $("#job_name").val("");
            $("#job_type").val("");
            $("#RequestFrom").val("");
            $("#RequestTo").val("");
            $("#CompletionFrom").val("");
            $("#CompletionTo").val("");
            $("#ExpirationFrom").val("");
            $("#ExpirationTo").val("");
            $("#ItemsCountTo").val('');
            $("#ItemsCountFrom").val('');
            $("#successCheckBox").attr('checked', false);
            $("#failedCheboxBox").attr('checked', false);
            $('#jobsTable').DataTable().draw();
        }

        function applySearch() {
            $('#jobsTable').DataTable().draw();
        }
        //-------------------------------------------------------------//
        function checkResolvedMail(mail) {
            return false;
        }
        //-------------------------------------------------------------//
    </script>
@endsection
