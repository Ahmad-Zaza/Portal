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

                <div class="modal-content">
                    <div id="search_modal_id" class="modalContent">
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        <div class="row mb-15">
                            <div class="input-form-70">
                                <h4 class="modal-title per-req ml-2p">Search</h4>
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
                                    <input type="text" class="form_input form-control custom-form-control font-size"
                                        id="ItemsCountFrom" placeholder="From" />
                                </div>

                                <div class="relative">
                                    <input type="text" class="form_input form-control custom-form-control font-size"
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
                                    class="btn_primary_state halfWidth mr-25">Apply</button>
                                <button type="button" class="btn_cancel_primary_state halfWidth"
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
            <div class="modal-dialog m-20v-restore">

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
                                    <h4 class="modal-title per-req">Exported Files</h4>
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
            <div class="modal-dialog modal-lg modal-width modal-mt-10 mt-10v">
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
                                                            <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                <input name="restorePermissions" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="check-mark checkbox-span-class"></span>
                                                            </label>
                                                            <span class="ml-25">Restore Permissions</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                <input name="sendSharedLinksNotification" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="check-mark checkbox-span-class"></span>
                                                            </label>
                                                            <span class="ml-25">Send Shared Links Notifications</span>
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
                                                                <label
                                                                    class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                    <input name="changedItems" type="checkbox"
                                                                        class="form-check-input">
                                                                    <span class="check-mark checkbox-span-class"></span>
                                                                </label>
                                                                <span class="ml-25">Changed Items</span>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="relative allWidth mb-2">
                                                                <label
                                                                    class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                    <input name="deletedItems" type="checkbox"
                                                                        class="form-check-input">
                                                                    <span class="check-mark checkbox-span-class"></span>
                                                                </label>
                                                                <span class="ml-25">Deleted Items</span>
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
                                                                <label
                                                                    class="checkbox-container checkbox-search checkbox-top-left">
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
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
                                                    <select class="allWidth form-control form_input user_onedrive required"
                                                        required id="onedrive" name="onedrive">
                                                    </select>
                                                </div>
                                                <div class="allWidth">
                                                    <input type="text" required
                                                        class="required form-control form_input" id="folder"
                                                        placeholder="Folder" name="folder" required
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
                                            <div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                <input name="skipUnresolved" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="check-mark checkbox-span-class"></span>
                                                            </label>
                                                            <span class="ml-25">Skip Unresolved</span>
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
                                                                <label
                                                                    class="checkbox-container checkbox-search checkbox-top-left">
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
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
                                        <div class="col-lg-12 customBorder h-253p">
                                            <div class="allWidth onedrivesResultsTable">
                                                <table id="oneDriveCopyTable"
                                                    class="stripe table table-striped table-dark display nowrap allWidth">
                                                    <thead class="table-th">
                                                        <tr>
                                                            <th>
                                                                <label
                                                                    class="checkbox-container checkbox-search checkbox-top-left">
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
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
                                                    <select required style="width:100%"
                                                        class="js-data-example-ajax users">
                                                        <option value="">Select User</option>
                                                    </select>
                                                </div>
                                                <div class="mb-10 allWidth relative">
                                                    <div class="onedrive-spinner hide"></div>
                                                    <select
                                                        class="form-control form_input user_onedrive custom-form-control w-100"
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
                                                            <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                <input name="restorePermissions" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="check-mark checkbox-span-class"></span>
                                                            </label>
                                                            <span class="ml-25">Restore Permissions</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                <input name="sendSharedLinksNotification" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="check-mark checkbox-span-class"></span>
                                                            </label>
                                                            <span class="ml-25">Send Shared Links Notifications</span>
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
                                                            <div class="relative allWidth ml-16 ">
                                                                <label
                                                                    class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                    <input name="changedItems" type="checkbox"
                                                                        class="form-check-input">
                                                                    <span class="check-mark checkbox-span-class"></span>
                                                                </label>
                                                                <span class="ml-25">Changed Items</span>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="relative allWidth">
                                                                <label
                                                                    class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                    <input name="deletedItems" type="checkbox"
                                                                        class="form-check-input">
                                                                    <span class="check-mark checkbox-span-class"></span>
                                                                </label>
                                                                <span class="ml-25">Deleted Items</span>
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
                                <h4 class="modal-title per-req ml-2p">Export Selected Onedrives To .Zip
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
                                                            <label
                                                                class="checkbox-container checkbox-search checkbox-top-left">
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
                                <h4 class="modal-title per-req">Export Selected Onedrive Folders To .Zip
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
                                                            <label
                                                                class="checkbox-container checkbox-search checkbox-top-left">
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
                                                            <label
                                                                class="checkbox-container checkbox-search checkbox-top-left">
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
                                                                <label
                                                                    class="checkbox-container checkbox-search checkbox-top-left">
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
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
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
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
        <div class="row">&nbsp;</div>
        <div>
            @if ($role->hasPermissionTo('onedrive_create_restore_session'))
                <a href="/restore/onedrive">
                    <button class="custom-back-button btn_primary_state custom-back-btn left-float">
                        New OneDrive Restore
                    </button>
                </a>
            @endif
        </div>
        <!-- All History table -->
        <div class="row">
            <div class="repositoryTable">
                <table id="historyTable" class="stripe table nowrap table-striped table-dark w-100">
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
        $(document).ready(function() {
            let jobType = null;
            let jobId = null;
            let jobTime = null;
            let showDeleted = null;
            let showVersions = null;
            var parent = $('.parent-link').attr('data-parent');
            $('.submenu-restore-history.submenu a[data-route="' + parent + '"]').addClass('active');
            var row = $('a.sub-menu-link.active').closest('.row');
            row.find('.left-nav-list').addClass('active').removeClass('collapsed');
            $('.submenu-restore-history').addClass('in');

            $('#historyTable').DataTable({
                'ajax': {
                    "type": "GET",
                    "url": "{{ url('getHistoryContent/' . $data['repo_kind']) }}",
                    "dataSrc": function(json) {
                        CheckHistoryTable();
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
                                @if ($role->hasPermissionTo('onedrive_view_history_details'))
                                    return "<a href='/restore/" +
                                        "{{ strtolower($data['repo_kind']) }}" + "/session/" + data
                                        .restore_session_guid +
                                        "'>" + data.name + "</a>";
                                @endif
                            } else
                                return "<div title='" + data.name + "'>" + data.name + "</div>";
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
                            statusClass = (data.status == 'Failed') ? 'text-danger' : statusClass;
                            statusClass = (data.status == 'Success') ? 'text-success' : statusClass;
                            statusClass = (data.status == 'Warning') ? 'text-warning' : statusClass;
                            statusClass = (data.status == 'In Progress') ? 'text-primary' :
                                statusClass;
                            if (data.status == 'In Progress' && !data.restore_session_guid)
                                data.status = "Waiting";
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
                        let subType = $(this).attr('data-sub-type');
                        let isRestore = (subType.match(/Restore/g) || subType.match(/Copy/g) ||
                            []).length;
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
                        let subType = $(this).attr('data-sub-type');
                        let isRestore = (subType.match(/Restore/g) || subType.match(/Copy/g) ||
                            []).length;
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
                            @if ($role->hasPermissionTo('onedrive_download_exported_files'))
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

            $('#oneDriveOriginalTable').DataTable({
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
                "scrollY": "100px",
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
            $("#RequestFrom").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $("#historyTable").DataTable().draw();
                }
            });
            $("#RequestTo").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(datetime) {
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
            //---------------------------------------//
        });


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

        function getMenu(data) {
            var temp = data.sub_type;
            var isRestore = (temp.match(/Restore/g) || temp.match(/Copy/g) || []).length;
            //-----------------------//
            let buttons = '';
            //-----------------------//
            if (isRestore == 0 && data.status != 'Expired' && data.status != 'In Progress' && data.status != 'Canceled') {
                buttons +=
                    '<img class="tableIcone hand exportedFiles ml-2 w-13 mr-0" data-id="' + data
                    .id + '" src="/svg/details\.svg" title="Exported Files">';
            }
            //-----------------------//
            if (['In Progress', 'Waiting'].includes(data.status)) {
                buttons +=
                    '<img class="tableIcone hand cancelJob ml-2 w-15 mr-0" data-id="' + data
                    .id + '" src="/svg/stop\.svg" title="Cancel Job">';
            }
            //-----------------------//
            @if ($role->hasPermissionTo('onedrive_export_again'))
                if (isRestore == 0 && $.inArray(data.status, ['Canceled', 'Expired', 'Failed', 'Waiting', 'Success']) != -
                    1) {
                    buttons += '<img data-type="' + data.type + '" data-sub-type="' + data.sub_type +
                        '" class="tableIcone hand restoreAgain ml-2 w-13 mr-0" data-id="' + data.id +
                        '" src="/svg/restore_again\.svg " title="Restore Again">';
                }
                if (isRestore == 0 && data.status == 'Failed' && !checkFiledAll(data)) {
                    buttons += '<img data-type="' + data.type + '" data-sub-type="' + data.sub_type +
                        '" class="tableIcone hand restoreFailedAgain ml-2 w-13 mr-0" data-id="' + data.id +
                        '" src="/svg/restore_failed\.svg " title="Restore Failed Again">';
                }
            @endif
            @if ($role->hasPermissionTo('onedrive_restore_again'))
                if (isRestore > 0 && $.inArray(data.status, ['Failed', 'Waiting', 'Canceled', 'Success']) != -1) {
                    buttons += '<img data-type="' + data.type + '" data-sub-type="' + data.sub_type +
                        '" class="tableIcone hand restoreAgain ml-2 w-13 mr-0" data-id="' + data.id +
                        '" src="/svg/restore_again\.svg " title="Restore Again">';
                }
                if (isRestore > 0 && data.status == 'Failed' && !checkFiledAll(data)) {
                    buttons += '<img data-type="' + data.type + '" data-sub-type="' + data.sub_type +
                        '" class="tableIcone hand restoreFailedAgain ml-2 w-13 mr-0" data-id="' + data.id +
                        '" src="/svg/restore_failed\.svg " title="Restore Failed Again">';
                }
            @endif
            //-----------------------//
            @if ($role->hasPermissionTo('onedrive_force_expire'))
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

        function getDetails(data, type, full, meta) {
            if (data) {
                let arr = data.details;
                let final = [];
                arr.forEach((item) => {
                    if (item.link) {
                        final.push("<a target='_blank' rel='noopener noreferrer' href='" + item.link + "'>" + item
                            .name + "</a>");
                    } else {
                        final.push(item.name);
                    }
                });
                let finalStr = final.join('<br>');
                return finalStr;
            }
        }

        function getMainIcon(data) {
            let details = JSON.stringify(data.details);
            return '<div name="details" class="details hide">' + details + '</div>' +
                '<img class= "tableIcone ml-0 w-13 mr-0" src="/svg/history.svg " title="History">';
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
                url: "{{ url('forceExpire') }}/onedrive",
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
                url: "{{ url('downloadExportedFile') }}/onedrive/" + id,
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
        function CheckHistoryTable() {
            setTimeout(() => {
                $('#historyTable').DataTable().ajax.reload();
            }, 15000);
        }
        //-------------------------------------------------------------//

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


        //---- Modal Functions
        //----------------------------------------------------//
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
                if (!e.item_name) {
                    $('#exportedFilesTable').DataTable().column(0).visible(false);
                }
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
                    //--------------------------------------------------// OndeDrives
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
                        if (options.toOnedrive) { // copy
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
                        } else { // original
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
                            //-------------------------------------//
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
                        //-------------------------------------------------------------------//Folders
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
                        //-------------------------------------------------//
                        /////////// empty==============

                        //-------------------------------------------------//
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
                            $('#restoreFolderForm').find('.restoreAction').val('');
                            $('#restoreFolderForm').find('.restoreType').val('another');
                            //--------------------//
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
                            if ($('#restoreFolderForm input[name="restoreAction"]').val() == 'overwrite') {
                                $('#restoreFolder').find('.modal-title').text(
                                    'Restore Selected Folders To Original Location (Overwrite)');
                            } else {
                                $('#restoreFolder').find('.modal-title').text(
                                    'Restore Selected Folders To Original Location (Keep)');
                            }
                            $('#restoreFolderForm .restoreAnother_cont').addClass('hide');
                            $('.modal-mt-10').addClass('mt-10v');
                            $('.modal-h-190p').addClass('h-253p');
                            $('.modal-h-190p').removeClass('h-190p');
                            $('#restoreFolderForm .restoreAnother_cont .required').removeAttr('required');
                            //--------------------//
                        }
                        //-------------------------------------------------//
                        $('#restoreFolder').find('.refreshDeviceCode').click();
                        $('#restoreFolder').modal('show');
                    } else if (type == "document") {
                        options = JSON.parse(data.options);
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
                            $('#docsCopyResultsTable_wrapper').find('.boxesCount').html(tableData.length);
                            $('#docsCopyResultsTable').DataTable().clear().draw();
                            $('#docsCopyResultsTable').DataTable().rows.add(tableData); // Add new data
                            $('#docsCopyResultsTable').DataTable().columns.adjust()
                                .draw(); // Redraw the DataTable

                            checkTableCount('docsCopyResultsTable');
                            adjustTable();
                            $("#docsCopyResultsTable").DataTable().draw();
                            //--------------------//
                            $('#restoreItemCopy').find('.modal-title').text(
                                'Copy Selected Documents To Another Location');
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
        function restoreOneDriveCopyModal() {
            let onedrives = $('#mailboxes li.mailbox input.mailBoxCheck:checked');
            var data = [];
            let drivesCount = onedrives.length;
            onedrives.each(function() {
                var parent = $(this).closest('.has.mailbox');
                data.push({
                    id: $(this).val(),
                    name: parent.find('.mail-click').html(),
                    url: $(this).attr('data-url')
                });
            });
            $('#oneDriveCopyTable_wrapper').find('.boxesCount').html(drivesCount);
            $('#oneDriveCopyTable').DataTable().clear().draw();
            $('#oneDriveCopyTable').DataTable().rows.add(data); // Add new data
            $('#oneDriveCopyTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('oneDriveCopyTable');
            adjustTable();
            $("#oneDriveCopyTable").DataTable().draw();
            $('#restoreOneDriveCopyModal').find('.refreshDeviceCode').click();
            $('#restoreOneDriveCopyModal').modal('show');
        }
        //----------------------------------------------------//
        function exportOnedriveModal() {
            let onedrives = $('#mailboxes li.mailbox input.mailBoxCheck:checked');
            var data = [];
            let drivesCount = onedrives.length;
            onedrives.each(function() {
                var parent = $(this).closest('.has.mailbox');
                data.push({
                    id: $(this).val(),
                    name: parent.find('.mail-click').html(),
                    url: $(this).attr('data-url')
                });
            });
            $('#exportOneDriveTable_wrapper').find('.boxesCount').html(drivesCount);
            $('#exportOneDriveTable').DataTable().clear().draw();
            $('#exportOneDriveTable').DataTable().rows.add(data); // Add new data
            $('#exportOneDriveTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportOneDriveTable');
            adjustTable();
            $("#exportOneDriveTable").DataTable().draw();

            $('#exportOnedriveModal').modal('show');
        }
        //----------------------------------------------------//
        function exportOnedriveFoldersModal() {
            //--------------------//
            $('.foldersAnotherOptions_cont').addClass('hide');
            $('.foldersAnotherOptions_cont.targetMailbox input').removeAttr('required');
            //--------------------//
            let folders = $('#mailboxes li.mailbox input.mailBoxFolderCheck:checked');
            let foldersCount = folders.length;
            let data = [];
            //--------------------//
            if (foldersCount > 0) {
                folders.each(function() {
                    var folderCheck = $(this);
                    var mailBox = folderCheck.parents('li.mailbox').find('input.mailBoxCheck');
                    data.push({
                        "id": $(this).val(),
                        "name": folderCheck.closest('.mailboxfolder').find('.mail-click').html(),
                        "onedriveId": mailBox.val(),
                        "onedriveName": mailBox.parents('li.mailbox').find('.mail-click').html()
                    });
                });
            } else {
                folders = $('input.mailBoxFolderItemCheck:checked[data-isfolder="true"]');
                foldersCount = folders.length;

                folders.each(function() {
                    var folderCheck = $(this);
                    let tr = folderCheck.closest('tr');
                    data.push({
                        "id": $(this).val(),
                        "name": tr.find('.folderTitle').val(),
                        "onedriveId": tr.find('.onedriveId').val(),
                        "onedriveName": tr.find('.onedriveTitle').val()
                    });
                });
            }
            //--------------------------------------------------//
            $('#exportFoldersResultsTable_wrapper').find('.boxesCount').html(foldersCount);
            $('#exportFoldersResultsTable').DataTable().clear().draw();
            $('#exportFoldersResultsTable').DataTable().rows.add(data); // Add new data
            $('#exportFoldersResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportFoldersResultsTable');
            adjustTable();
            $("#exportFoldersResultsTable").DataTable().draw();
            $('#exportOnedriveFoldersModal').modal('show');
        }
        //----------------------------------------------------//
        function exportOnedriveDocumentsModal() {
            //--------------------//
            let items = $('input.mailBoxFolderItemCheck[data-isfolder="false"]:checked');
            let data = [];
            let driveId;
            let folderTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                onedriveTitle = tr.find('.onedriveTitle').val();
                driveId = tr.find('.onedriveId').val();
                folderTitle = tr.find('.folderTitle').val();
                data.push({
                    "id": $(this).val(),
                    "onedriveId": driveId,
                    "onedriveTitle": onedriveTitle,
                    "folderTitle": folderTitle == "null" ? onedriveTitle : folderTitle,
                    "name": tr.find('.fileNameColumn').html(),
                });
            });
            let parentName = onedriveTitle + '-' + folderTitle;
            //--------------------//
            $('#exportDocsResultsTable_wrapper').find('.boxesCount').html(data.length);
            $('#exportDocsResultsTable').DataTable().clear().draw();
            $('#exportDocsResultsTable').DataTable().rows.add(data); // Add new data
            $('#exportDocsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportDocsResultsTable');
            adjustTable();
            $("#exportDocsResultsTable").DataTable().draw();
            //--------------------//
            $('#exportOnedriveDocumentsModal').modal('show');
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
            var data = $('#restoreOneDriveCopyForm').serialize();
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
        function exportOnedriveDocuments() {
            event.preventDefault()
            let data = $('#exportOnedriveDocumentsForm').serialize();
            //-----------------------------------//
            let docs = $('#exportOnedriveDocumentsForm .mailboxCheck:checked');
            let docsArr = [];
            docs.each(function() {
                if (docsArr.filter(e => e.onedriveId === $(this).attr("onedriveId")).length == 0) {
                    let onedriveItems = $('#exportOnedriveDocumentsForm .mailboxCheck:checked[onedriveId="' + $(
                        this).attr("onedriveId") + '"]');
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
            data += "&docs=" + JSON.stringify(docsArr);
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
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                res = true;
                JobName = $("#job_name").val();
                JobType = $("#job_type").val();
                RequestFrom = $("#RequestFrom").datepicker('getDate');
                RequestTo = $("#RequestTo").datepicker('getDate');
                CompletionFrom = $("#CompletionFrom").datepicker('getDate');
                CompletionTo = $("#CompletionTo").datepicker('getDate');
                ExpirationFrom = $("#ExpirationFrom").datepicker('getDate');
                ExpirationTo = $("#ExpirationTo").datepicker('getDate');
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
                    conditionsArray.push("failed")
                }

                if (conditionsArray.length > 0 && (!data[3] || conditionsArray.indexOf(data[3].toLowerCase()) === -1)) {
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
            $("#ItemsCountFrom").val("");
            $("#ItemsCountTo").val("");
            $("#successCheckBox").attr('checked', false);
            $("#failedCheboxBox").attr('checked', false);
            $("#jobsTable").DataTable().draw();
        }

        function applySearch() {
            $("#jobsTable").DataTable().draw();
        }
        //----------------------------------------------------//
    </script>
@endsection
