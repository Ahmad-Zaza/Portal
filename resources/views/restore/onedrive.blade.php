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

        .copy-oneDrives-customBorder {
            height: 190px;
        }

        .selected-items-oneDrive-documents {
            height: 150px;
        }
    </style>
    <div class="col-sm-10 navbarLayout">
        <!-- Upper navbar -->
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm upperNavBar"> -->
        <ul class="ulNavbar">
            <li>
                <div class="col-sm-2 custom-col-sm-2 pl-50">
                </div>
            </li>

            <li class="liNavbar"><a class="active" href="{{ url('restore', $data['repo_kind']) }}">Restore
                    <img class="nav-arrow" src="/svg/arrow-right-active.svg">
                    {{ getDataType($data['repo_kind']) }}</a></li>
            <!-- Authentication Links -->
            @include('layouts.authentication-links')
        </ul>
    </div>
@endsection
@section('content')
    <script src="{{ url('/js/timepicker/mdtimepicker.js') }}"></script>
    <link href="{{ url('/css/timepicker/mdtimepicker.css') }}" rel="stylesheet" type="text/css">

    <div id="mainContent m-0">
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <div id="jobsModal" class="modal modal-center" role="dialog">
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
                                <h4 class="modal-title per-req ml-2p">Specify Point in Time
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
                                        class="btn_cancel_primary_state  allWidth activeclose"
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
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="restorePermissions" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Restore Permissions</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="sendSharedLinksNotification" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Send Shared Links
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
                                                                <label
                                                                    class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                    <input name="changedItems" type="checkbox"
                                                                        class="form-check-input">
                                                                    <span class="checkbox-span-class check-mark"></span>
                                                                </label>
                                                                <span class="ml-25">Changed Items</span>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="relative allWidth mb-2">
                                                                <label
                                                                    class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                    <input name="deletedItems" type="checkbox"
                                                                        class="form-check-input">
                                                                    <span class="checkbox-span-class check-mark"></span>
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
                                        <div class="col-lg-12 customBorder modal-h-190p h-190p">
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
                                <h4 class="modal-title per-req ml-2p">Restore Selected OneDrives To Original Location
                                </h4>
                            </div>
                        </div>
                        <form id="restoreOnedriveForm" class="mb-0" onsubmit="restoreOneDrive(event)">
                            <div class="custom-left-col">
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
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="skipUnresolved" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
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
                                        <div class="col-lg-12 customBorder modal-h-290p ">
                                            <div class="allWidth onedrivesResultsTable">
                                                <table id="oneDriveOriginalTable"
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
                                                            <th>OneDrive</th>
                                                            <th>Url</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3">
                                                                <span class="boxesCount"></span> onedrives selected
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

        <div id="restoreOneDriveCopyModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg modal-width">
                <div class="divBorderRight"></div>
                <div class="divBordeper-req ml-2prBottom"></div>
                <div class="divBorderleft"></div>
                <div class="divBorderUp"></div>
                <!-- Modal content-->
                <div class="modal-content ">
                    <div class="modalContent">
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        <div class="row ml-100 mb-15">
                            <div class="input-form-70">
                                <h4 class="modal-title per-req ml-2p">Copy Selected Onedrives To Another Location
                                </h4>
                            </div>
                        </div>
                        <form id="restoreOneDriveCopyForm" class="mb-0" onsubmit="restoreOneDriveCopy(event)">
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
                                        <h5 class="txt-blue mt-0">Onedrives</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder copy-oneDrives-customBorder">
                                            <div class="allWidth onedrivesResultsTable">
                                                <table id="oneDriveCopyTable"
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
                                                            <th>OneDrive</th>
                                                            <th>Url</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3">
                                                                <span class="boxesCount"></span> onedrives selected
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
                                                        class="w-100 form-control form_input user_onedrive custom-form-control"
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
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="restorePermissions" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Restore Permissions</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="sendSharedLinksNotification" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Send Shared Links
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
                                                                <label
                                                                    class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                    <input name="changedItems" type="checkbox"
                                                                        class="form-check-input">
                                                                    <span class="checkbox-span-class check-mark"></span>
                                                                </label>
                                                                <span class="ml-25">Changed Items</span>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="relative allWidth">
                                                                <label
                                                                    class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                    <input name="deletedItems" type="checkbox"
                                                                        class="form-check-input">
                                                                    <span class="checkbox-span-class check-mark"></span>
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
                                                                class="checkbox-top-left checkbox-container checkbox-search">
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

        <div id="exportOnedriveFoldersModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg modal-center">
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
                                                                class="checkbox-top-left checkbox-container checkbox-search">
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

        <div id="exportOnedriveDocumentsModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg modal-center">
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

                            </div>

                            <div class="custom-right-col">
                                <div class="row">
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Onedrive Documents</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder modal-h-250">
                                            <div class="allWidth">
                                                <table id="docsResultsTable"
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
                                        <div class="col-lg-12 customBorder selected-items-oneDrive-documents pb-3 pt-3">
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
                                        <form class="filter-form mb-0" onsubmit="getFilteredOnedrives(event)">
                                            <div class="filterCont flex allWidth pt-10">
                                                <div class="p-3 text-white pad-left-right">
                                                    <select name="sortBoxType" required
                                                        class="required sortBoxType btn-sm dropdown-toggle form_dropDown form-control"
                                                        data-toggle="dropdown" value=":">
                                                        <option value="" selected="selected">Sort</option>
                                                        <span class="fa fa-caret-down"></span>
                                                        <option value="AZ">A > Z
                                                        </option>
                                                        <option value="ZA">Z > A
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="p-3 text-white">
                                                <label class="font-small pl-20" cellspa>Show Onedrives
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
                                                <button id="apply" type="button"
                                                    class="btn_primary_state mr-2 pl-5 pr-5 ml-30 w-100">
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
                @if (!$role->hasAnyPermission('onedrive_restore_actions', 'onedrive_export_actions'))
                    @php
                        $permissionClass = 'hide';
                    @endphp
                @endif
                <div class="row main-button-cont {{ $permissionClass }}">
                    <div class="btnMain main-button flex">
                        <div class="btnUpMask"></div>
                        <div class="row m-0 pl-4 pr-4 allWidth">
                            <div class="col-lg-4 onedriveItemsButton">
                                <div class="selected-action allWidth relative">
                                    <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                        data-toggle="dropdown" aria-expanded="false">
                                        Selected Onedrive Actions
                                        <span class="selectedBoxCount"></span>
                                        <span class="fa fa-caret-down"></span></button>
                                    <ul class="dropdown-menu allWidth">
                                        @if ($role->hasPermissionTo('onedrive_restore_actions'))
                                            <li>
                                                <a href="javascript:restoreOnedriveOverwriteModal(event)"
                                                    title="Restore Selected to Original Location (Overwrite)"
                                                    class="tooltipSpan">
                                                    Restore Selected to Original Location (Overwrite)
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:restoreOnedriveKeepModal(event)" class="tooltipSpan"
                                                    title="Restore Selected to Original Location (Keep)">
                                                    Restore Selected to Original Location (Keep)
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:restoreOneDriveCopyModal(event)"
                                                    class="tooltipSpan restoreOnedriveToAnotherLocation"
                                                    title="Copy Selected to Another Location">
                                                    Copy Selected to Another Location
                                                </a>
                                            </li>
                                        @endif
                                        @if ($role->hasPermissionTo('onedrive_export_actions'))
                                            <li>
                                                <a href="javascript:exportOnedriveModal(event)" class="tooltipSpan"
                                                    title="Export Selected to .Zip">
                                                    Export Selected to .Zip
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-4 onedriveFoldersButton">
                                <div class="selected-action allWidth relative">
                                    <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                        data-toggle="dropdown" aria-expanded="false">
                                        Selected Folders Actions
                                        <span class="selectedFolderCount"></span>
                                        <span class="fa fa-caret-down"></span></button>

                                    <ul class="dropdown-menu allWidth">
                                        @if ($role->hasPermissionTo('onedrive_restore_actions'))
                                            <li>
                                                <a href="javascript:restoreOnedriveFoldersOverwriteModal(event)"
                                                    class="tooltipSpan"
                                                    title="Restore Selected to Original Location (Overwrite)">
                                                    Restore Selected to Original Location (Overwrite)
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:restoreOnedriveFoldersKeepModal(event)"
                                                    class="tooltipSpan"
                                                    title="Restore Selected to Original Location (Keep)">
                                                    Restore Selected to Original Location (Keep)
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:restoreOnedriveFoldersAnotherModal(event)"
                                                    class="tooltipSpan" title="Copy Selected to Another Location">
                                                    Copy Selected to Another Location
                                                </a>
                                            </li>
                                        @endif
                                        @if ($role->hasPermissionTo('onedrive_export_actions'))
                                            <li>
                                                <a href="javascript:exportOnedriveFoldersModal(0)" class="tooltipSpan"
                                                    title="Export Selected to .Zip">
                                                    Export Selected to .Zip
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-4 onedriveDocumentsButton">
                                <div class="selected-action allWidth relative">
                                    <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                        data-toggle="dropdown" aria-expanded="false">
                                        Selected Documents Actions
                                        <span class="selectedItemCount"></span>
                                        <span class="fa fa-caret-down"></span>
                                    </button>
                                    <ul class="dropdown-menu allWidth">
                                        @if ($role->hasPermissionTo('onedrive_restore_actions'))
                                            <li>
                                                <a href="javascript:restoreOnedriveDocumentsOverwriteModal(event)"
                                                    class="tooltipSpan"
                                                    title="Restore Selected to Original Location (Overwrite)">
                                                    Restore Selected to Original Location (Overwrite)
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:restoreOnedriveDocumentsKeepModal(event)"
                                                    class="tooltipSpan"
                                                    title="Restore Selected to Original Location (Keep)">
                                                    Restore Selected to Original Location (Keep)
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:restoreOnedriveDocumentsCopyModal(event)"
                                                    title="Copy Selected to Another Location" class="tooltipSpan">
                                                    Copy Selected to Another Location
                                                </a>
                                            </li>
                                        @endif
                                        @if ($role->hasPermissionTo('onedrive_export_actions'))
                                            <li>
                                                <a href="javascript:exportOnedriveDocumentsModal(event)"
                                                    title="Export Selected to .Zip" class="tooltipSpan">
                                                    Export Selected to .Zip
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

                            <table id="itemsTable"
                                class="stripe table table-striped table-dark display nowrap allWidth">
                                <thead class="table-th">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th class="text-left">Name</th>
                                        <th>Version</th>
                                        <th>File Size</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
                                        <th>Modified By</th>
                                        <th>Modified At</th>
                                        <th></th>
                                        <th></th>
                                    </tr>

                                </thead>
                                <tbody id="table-content">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        {{-- <th colspan="9"></th> --}}
                                        <th colspan="10">
                                            <div class="custom-text-right">
                                                <span class="custom-text-right orgSpan hide searchingLabel ">Searching
                                                    ...</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <span class="custom-text-right orgSpan hide countingLabel"></span>
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

        <div id="searchModal" class="modal modal-center" role="dialog">

            <div class="modal-dialog modal-lg  mt-20v">
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
                                <h4 class="modal-title per-req ml-2p">Search
                                </h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-form-70 mb-1">Name: <span class="ml-43">Created By:</span></div>
                            <div class="input-form-70 inline-flex">
                                <input type="text"
                                    class="form_input form-control mr-25 custom-form-control font-size" id="name"
                                    placeholder="" />
                                <input type="text" class="form_input form-control custom-form-control font-size"
                                    id="createdBy" placeholder="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 mb-1">Created At:</div>
                            <div class="input-form-70 inline-flex">
                                <input type="text"
                                    class="form_input form-control mr-25 custom-form-control font-size"
                                    id="createdAtFrom" placeholder="From" />
                                <input type="text" class="form_input form-control custom-form-control font-size"
                                    id="createdAtTo" placeholder="To" />
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="input-form-70 inline-flex">
                                <button type="button" onclick="applySearch()"
                                    class="btn_primary_state  allWidth mr-25">Apply</button>
                                <button type="button" onclick="resetSearch()"
                                    class="btn_cancel_primary_state allWidth">Reset</button>
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
                                <h4 class="modal-title per-req">Mail Details
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
        let onedriveId = '-1';
        let folderId = '-1';
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
        let jobType = null;
        let jobId = null;
        let jobTime = null;
        let showDeleted = null;
        let showVersions = null;
        //-------------------------------------------------//
        let tableSettings = {
            'ajax': {
                "type": "POST",
                "url": "{{ url('getOnedriveFolderItems') }}",
                "dataSrc": '',
                "data": function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.offset = 0;
                    d.onedriveId = getOneDrive();
                    d.onedriveTitle = $('#' + getOneDrive()).html();
                    d.folderId = getOneDriveFolder();
                    d.folderTitle = $('#' + getOneDriveFolder()).html();
                },
                "beforeSend": function() {
                    oneDriveCheckChange();
                    oneDriveFolderCheckChange();
                    oneDriveFolderItemsCheckChange();
                    if (getOneDrive() == "-1") {
                        $('#itemstable > tbody').html(
                            '<tr class="odd">' +
                            '<td valign="top" colspan="35" class="dataTables_empty">No data available in table</td>' +
                            '</tr>'
                        );
                        return false;
                    }
                    $('#itemstable > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="35" class="dataTables_empty processing_row"><span class="table-spinner"></span></td>' +
                        '</tr>'
                    );
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
                "dataType": "json"
            },
            "order": [
                [10, 'desc'],
                [2, 'asc']
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
                    "width": "1%",
                    render: function(data, type, full, meta) {
                        return '<label class="checkbox-container checkbox-search">&nbsp;' +
                            '<input type="hidden" class="onedriveId" value="' + data.onedriveId +
                            '">' +
                            '<input type="hidden" class="isFolder" value="' + data.isFolder +
                            '">' +
                            '<input type="hidden" class="onedriveTitle" value="' + data.onedriveTitle + '">' +
                            '<input type="hidden" class="folderId" value="' + data.folderId +
                            '">' +
                            '<input type="hidden" class="folderTitle" value="' + data.folderTitle + '">' +
                            '<input type="checkbox" data-isFolder="' + data.isFolder +
                            '" class="mailBoxFolderItemCheck form-check-input" value="' +
                            data.id + '" data-onedriveId="' + data.onedriveId + '" data-onedriveName="' + data
                            .onedriveTitle + '"/>' +
                            '<span class="tree-checkBox check-mark"></span></label>';
                    }
                },
                {
                    "data": null,
                    "class": "after-none",
                    "width": "1%",
                    "render": function(data) {
                        return ((data.isFolder) ?
                            '<img class= "tableIcone w-13 mr-0" src="/svg/folders/none\.svg " title="Folder">' :
                            '' +
                            '<img class= "tableIcone w-13 mr-0" src="/svg/folders/tasks\.svg " title="File">'
                        );
                    }
                },
                {
                    "data": null,
                    "width": "18%",
                    "class": "text-left fileNameColumn wrap",
                    "render": function(data) {
                        if (data.isFolder)
                            return '<a class="text-orange infolder">' + data.name + '</a>';
                        return data.name;
                    }
                },
                {
                    "width": "7.5%",
                    "data": "version",
                },
                {
                    "width": "7.5%",
                    "data": null,
                    "class": "fileSizeColumn",
                    "render": function(data) {
                        if (data.sizeBytes > 0) {
                            return (data.sizeBytes / 1024 / 1024).toFixed(2) + ' MB';
                        }
                        return data.sizeBytes;
                    }
                },
                {
                    "width": "15%",
                    "data": "createdBy"
                },
                {
                    "width": "15%",
                    "data": null,
                    render: function(data) {
                        return formatDate(data.creationTime);
                    }
                },
                {
                    "width": "15%",
                    "data": "modifiedBy"
                },
                {
                    "width": "15%",
                    "data": null,
                    render: function(data) {
                        return formatDate(data.modificationTime);
                    }
                },
                {
                    "data": null,
                    "class": "after-none",
                    "width": "5%",
                    "title": '<img class= "tableIcone w-13 mr-0" src="/svg/download\.svg " title="Download">',
                    render: function(data, type, full, meta) {
                        @if ($role->hasPermissionTo('onedrive_view_item_details'))
                            if (data.isFolder == false && data.sizeBytes > 0)
                                return '<img class= "hand tableIcone downloadMail w-13 mr-0" src="/svg/download\.svg " title="Download">';
                        @endif
                        return '';
                    }
                }, {
                    "data": "isFolder"
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
            "fnDrawCallback": function() {
                resetTotalItems();
                //-----------------------------//
                var icon =
                    '<div class="search-container"><img class="search-icon mt--7" src="/svg/search.svg"></div>';
                if ($(".dataTables_filter label").find('.search-icon').length == 0)
                    $('.dataTables_filter label').append(icon);
                $('.dataTables_filter input').addClass('form_input form-control custom-search');
                //-----------------------------//
                if ($('#itemsTable_wrapper .dataTables_scroll').find('.folderPathRow').length == 0) {
                    let div = '<div class="row folderPathRow m-0 hide">' +
                        '<div class="col-lg-12 p-2">' +
                        '<span class="folderPathSpan ml-2 basic-color"></span>' +
                        '</div>' +
                        '</div>';
                    $('#itemsTable_wrapper .dataTables_scroll').prepend(div)
                }
                //-----------------------------//
                oneDriveCheckChange();
                $('.mailBoxFolderItemCheck,.mailBoxFolderCheck').change(folderTableChange);
                oneDriveFolderCheckChange();
                oneDriveFolderItemsCheckChange();
                $('.mailBoxFolderItemCheck').change(oneDriveFolderItemsCheckChange);
                //-----------------------------//
                $('.tableIcone.downloadMail').unbind('click').click(function() {
                    var tr = $(this).closest('tr');
                    $('tr.current').removeClass('current');
                    tr.addClass('current');
                    downloadSingleDocument();
                });
                //-----------------------------//
                $('.dataTables_scrollHeadInner').addClass('itemsTableHeader');
                //-----------------------------//
                let tableDataCount = $('#itemsTable').DataTable().data().count();
                if (tableDataCount > 0 && !stopLoading) {
                    increaseTotalItems($('#itemsTable').DataTable().data().count());
                    $('.countingLabel').html(getTotalItems() + getTotalFolders() + ' Items Shown');
                    $('.searchingLabel').removeClass('hide');
                    $('.countingLabel').removeClass('hide');
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
                        }, 500)
                    }

                    if (isDone) {
                        $('.warningRow').addClass('hide');
                        $('.stoppingRow').addClass('hide');
                    }
                } else if (tableDataCount > 0 && stopLoading && !isDone) {
                    stopLoad();
                }
                //-------------------------------------------//
                $('.infolder').unbind('click').click(getFolderInItems);
                //-------------------------------------------//
                if (isDone)
                    $('.warningRow,.stoppingRow').addClass('hide');
                //-------------------------------------------//
            },
            "orderFixed": {
                "pre": [10, 'desc']
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
                'targets': [0, 1, 9], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }, {
                'targets': [10], // column index (start from 0)
                'visible': false,
            }, ]
        };
        //-------------------------------------------------//
        $(document).ready(function() {
            minmizeSideBar();

            oneDriveCheckChange();
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
                    $('#itemsTable').DataTable().draw()
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
                $('#itemsTable').DataTable().draw()
            });

            globalItemsTable = $('#itemsTable').DataTable(tableSettings);

            $('.side-nav-icon').click(function() {
                $('#itemsTable').DataTable().draw();
            });

            $('#itemsTable').DataTable().buttons().container()
                .prependTo('#itemsTable_filter ');


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
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
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
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
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
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-onedrive-id="' + data
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
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck">' +
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
                "scrollY": "180px",
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
                                '" class="form-check-input mailboxCheck">' +
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
                                '" class="form-check-input mailboxCheck">' +
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
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-onedrive-id="' + data
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
            getUsers();
            //---------------------------------------//
            $("#createdAtFrom").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $("#itemsTable").DataTable().draw();
                }
            });
            $("#createdAtTo").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateTime) {
                    $("#itemsTable").DataTable().draw();
                }
            });
        });
        //--------------------------------------//
        function getUsers() {
            $.ajax({
                type: "GET",
                url: "{{ url('getOnedriveUsers') }}",
                data: {},
                success: function(data) {
                    let users = data;
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
        //--------------------------------------//
        $(document).on('change', '.tree input.mailBoxCheck[type=checkbox]', function(e) {
            $(this.closest('div')).siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);
            $(this).parentsUntil('.tree').children("input[type='checkbox']").prop('checked', this.checked);
            e.stopPropagation();
            // Allow Single Onedrive folders
            if ($(this).parentsUntil('.tree').children("input[type='checkbox']").length > 0 && this.checked) {
                $(".tree").find("input.mailBoxFolderCheck[data-onedriveid!='" + $(this).val() + "']")
                    .prop("checked", false);
            }
            //--------------------------//
            oneDriveFolderItemsCheckChange();
            $('.mailBoxFolderItemCheck,.mailBoxFolderCheck').change(folderTableChange);
            oneDriveFolderCheckChange();
        });
        //--------------------------------------//
        $("#jobs").change(function() {
            if (this.value != "") {
                $(".spinner_parent").css("display", "block");
                $('body').addClass('removeScroll');
                $.ajax({
                    type: "GET",
                    url: "{{ url('getRestoreTime') }}/onedrive/" + this.value,
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
                var name = $('#name').val();
                var createdBy = $('#createdBy').val();
                var createdAtFrom = $('#createdAtFrom').datepicker('getDate');
                var createdAtTo = $('#createdAtTo').datepicker('getDate');

                if (name) {
                    if (!data[2])
                        return false;
                    var value = data[2].toLowerCase();
                    if (!value.toString().includes(name.toLowerCase()))
                        return false;
                }

                if (createdBy) {
                    if (!data[5])
                        return false;
                    var value = data[5].toLowerCase();
                    if (!value.toString().includes(createdBy.toLowerCase()))
                        return false;
                }

                if ((createdAtFrom || createdAtTo) && !data[6]) {
                    return false;
                } else {
                    if (createdAtFrom) {
                        if (new Date(createdAtFrom) > new Date(data[6])) {
                            return false;
                        }
                    }

                    if (createdAtTo) {
                        if (new Date(createdAtTo) < new Date(data[6])) {
                            return false;
                        }
                    }
                }
                return true;
            }
        );

        //---- Mailboxes & Folders & Items Functions
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
        function oneDriveCheckChange() {
            var len = $('.tree .mailBoxCheck:checked').length;
            $(".onedriveItemsButton .restoreOnedriveToAnotherLocation").addClass("hide");
            if (len == 0) {
                $('.onedriveItemsButton button').attr('disabled', 'disabled');
                $('.onedriveItemsButton .selectedBoxCount').html('');
            } else {
                if (len == 1)
                    $(".onedriveItemsButton .restoreOnedriveToAnotherLocation").removeClass("hide");
                $('.onedriveItemsButton button').removeAttr('disabled');
                $('.onedriveItemsButton .selectedBoxCount').html('(' + len + ')');
            }
            $('.mailBoxFolderItemCheck,.mailBoxFolderCheck').change(folderTableChange);
            oneDriveFolderCheckChange();
            oneDriveFolderItemsCheckChange();
        }
        //----------------------------------------------------//
        function onOneDriveResultChange(tableName) {
            let boxes = $('#' + tableName + '_wrapper').find('tbody .form-check-input:checked');
            let drivesCount = boxes.length;
            let unresolvedCount = 0;
            boxes.each(function() {
                if ($(this).attr('data-email') == '')
                    unresolvedCount++;
            })
            $('#' + tableName + '_wrapper').find('.boxesCount').html(drivesCount);
            if (unresolvedCount > 0)
                $('#' + tableName + '_wrapper').find('.unresolvedCount').html('(' + unresolvedCount +
                    ' unresolved onedrives)');
            else
                $('#' + tableName + '_wrapper').find('.unresolvedCount').html('');
        }
        //----------------------------------------------------//
        function folderTableChange() {
            if ($(this).hasClass('mailBoxFolderItemCheck') && $(this).attr('data-isfolder') == "true") {
                $('.tree .mailBoxFolderCheck:checked').prop('checked', false);
            } else if ($(this).hasClass('mailBoxFolderCheck')) {
                $('.mailBoxFolderItemCheck:checked[data-isFolder="true"]').prop('checked', false);
            }
            oneDriveFolderCheckChange();
        }
        //----------------------------------------------------//
        function oneDriveFolderCheckChange(tempOnedriveId, $this) {
            //------------------------------//
            // Allow Single Onedrive folders
            if (tempOnedriveId) {
                if ($this.prop("checked"))
                    $(".mailBoxFolderCheck[data-onedriveid!='" + tempOnedriveId + "']").prop("checked", false);
            }
            //------------------------------//
            var mainFolders = $('.tree .mailBoxFolderCheck:checked').length;
            var tableFolders = $('.mailBoxFolderItemCheck:checked[data-isFolder="true"]').length;
            let len = tableFolders + mainFolders;
            if (len == 0) {
                $('.onedriveFoldersButton button').attr('disabled', 'disabled');
                $('.onedriveFoldersButton .selectedFolderCount').html('');
            } else {
                $('.onedriveFoldersButton button').removeAttr('disabled');
                $('.onedriveFoldersButton .selectedFolderCount').html('(' + len + ')');
            }
        }
        //----------------------------------------------------//
        function oneDriveFolderItemsCheckChange() {
            var len = $('.mailBoxFolderItemCheck:checked[data-isFolder="false"]').length;
            if (len == 0) {
                $('.onedriveDocumentsButton button').attr('disabled', 'disabled');
                $('.onedriveDocumentsButton .selectedItemCount').html('');
            } else {
                $('.onedriveDocumentsButton button').removeAttr('disabled');
                $('.onedriveDocumentsButton .selectedItemCount').html('(' + len + ')');
            }
            $('.mailBoxFolderItemCheck,.mailBoxFolderCheck').change(folderTableChange);
            oneDriveFolderCheckChange();
        }
        //----------------------------------------------------//
        function resetSearch() {
            $("input").val("");
            $('#itemsTable').DataTable().draw();
        }
        //----------------------------------------------------//
        function applySearch() {
            $('#itemsTable').DataTable().draw();
        }
        //----------------------------------------------------//
        function stopLoad() {
            stopLoading = true;
            $('.stopLoad').addClass('hide');
            $('.resumeLoad').removeClass('hide');
            $('.moveToEDiscovery').addClass('mr-2 ml-2');
            $('.searchingLabel').addClass('hide');
            $('.countingLabel').html(getTotalItems() + getTotalFolders() + ' Items Shown')
        }
        //----------------------------------------------------//
        function resumeLoad() {
            stopLoading = false;
            if ($('#itemsTable').DataTable().data().count() >= stoppingLimit) {
                loadAll = true;
                $('.stoppingRow').addClass('hide');
            }
            increaseTotalItems($('#itemsTable').DataTable().data().count());
            $('.countingLabel').html(getTotalItems() + getTotalFolders() + ' Items Shown');
            $('.searchingLabel').removeClass('hide');
            $('.countingLabel').removeClass('hide');
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
                    let onedriveName = $(".tree #" + result.onedriveId).html();
                    subFolders = subFolders +
                        '<li class="mailboxfolder pb-0" data-folderid="' + result.id +
                        '"><div class="relative allWidth inline-flex">' +
                        (result.hasFolders ?
                            '<span class="caret mailCaret closeMail" onclick="getFolderChildren(event)"></span>' :
                            '') +
                        '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                        '<input type="checkbox" class="mailBoxFolderCheck form-check-input" value="' +
                        result.id + '" data-onedriveId="' + result.onedriveId + '" data-onedriveName="' +
                        onedriveName + '"/>' +
                        '<input type="checkbox" class="folderPath" value="' + result.path + '"/>' +
                        '<span class="tree-checkBox check-mark"></span></label>' +
                        '<img class="folderIcon" src="/svg/folders/' + getFolderIcon(result.name) + '.svg">' +
                        '<span id="' + result.id +
                        '" data-onedriveId="' + result.onedriveId +
                        '" onclick="getFolderItems(event)" class="mail-click childmail-click mail-folder-click" title="' +
                        result.name + '">' +
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
            let selectedOnedriveId = $('.mail-click#' + selectedFolderId).attr('data-onedriveId');
            getFolderItemsTable(selectedOnedriveId, selectedFolderId);
            onedriveId = selectedOnedriveId;
            folderId = selectedFolderId;
            $('.mail-click').removeClass('active');
            $('.mail-click#' + selectedFolderId).addClass('active');

            stopLoading = false;
            isDone = false;
            resetTotalItems();
            resetOffset();
            $(document).find('.dataTables_filter input').val("");
            $('#itemsTable').DataTable().search("").ajax.reload();
        }
        //----------------------------------------------------//
        function getFolderInItems() {
            resetOffset();
            var tr = $(this).closest('tr');
            let selectedFolderId = tr.find('.mailBoxFolderItemCheck').val();
            let selectedOnedriveId = tr.find('.onedriveId').val();
            getFolderItemsTable(selectedOnedriveId, selectedFolderId);
        }
        //----------------------------------------------------//
        function getFolderItemsTable(selectedOnedriveId, selectedFolderId) {
            showFolderPath(selectedFolderId, selectedOnedriveId);
            onedriveId = selectedOnedriveId;
            folderId = selectedFolderId;
            $('.mail-click').removeClass('active');
            $('.mail-click#' + selectedFolderId).addClass('active');
            stopLoading = false;
            isDone = false;
            resetTotalItems();
            resetOffset();
            $(document).find('.dataTables_filter input').val("");
            $('#itemsTable').DataTable().search("").ajax.reload();
            //---------------------------------------------//
            $('.tree .active').removeClass('active');
            $('#' + selectedFolderId).addClass('active');
            let mainLi = $('#' + selectedOnedriveId).closest('.has.mailbox');
            mainLi.find('.mailCaret:not(.closeMail)').click();
            mainLi.find('.closeMail:first').click();
            //-----------------------------------------------//
            let tempId = selectedFolderId;
            let parentUl = $('#' + tempId).closest('li').closest('ul');
            let parentLi = parentUl.closest('li');
            while (!parentUl.hasClass('tree') && tempId) {
                parentLi = parentUl.closest('li');
                parentLi.find('>div>.closeMail:first').click();
                tempId = parentLi.find('.mail-folder-click:first').prop('id');
                parentUl = parentLi.closest('ul');
            }
            //-----------------------------------------------//
        }
        //----------------------------------------------------//
        function showFolderPath(folderId, onedriveId) {
            let li = $('li[data-folderid="' + folderId + '"]');
            let onedriveTitle = $('#' + onedriveId).html();
            let folderPath = li.find('.folderPath').val();
            if (folderPath) {
                let foldersArr = folderPath.split('/');
                let pathArr = [];
                let folderName = '';
                pathArr.push('<a onclick="getOnedriveItemsTable(\'' + onedriveId + '\')">' + onedriveTitle + '</a>');
                foldersArr.forEach((e) => {
                    folderName = $('#' + e).html();
                    pathArr.push('<a onclick="getFolderItemsTable(\'' + onedriveId + '\',\'' + e + '\')">' +
                        folderName + '</a>')
                });
                $('.folderPathRow').removeClass('hide');
                $('.path-header').removeClass('hide');
                $('.folderPathSpan').html($(pathArr.join('<img class="nav-arrow" src="/svg/arrow-right.svg">')));
            }
        }
        //----------------------------------------------------//
        function getOnedriveItemsTable(selectedOnedriveId) {
            $('.folderPathRow').addClass('hide');
            $('.path-header').addClass('hide');
            $('.folderPathSpan').html('');
            onedriveId = selectedOnedriveId;
            folderId = -1;
            $('.mail-click').removeClass('active');
            $('.mail-click#' + selectedOnedriveId).addClass('active');
            $('.mail-click#' + selectedOnedriveId).closest('li.mailbox').find('.caret-onedrive.closeMail:first').click();

            stopLoading = false;
            isDone = false;
            resetTotalItems();
            resetOffset();
            $(document).find('.dataTables_filter input').val("");
            $('#itemsTable').DataTable().search("").ajax.reload();
        }
        //----------------------------------------------------//
        function getOnedriveItems(event) {
            let selectedOnedriveId = event.target.id;
            getOnedriveItemsTable(selectedOnedriveId);
        }
        //----------------------------------------------------//
        function getFolderChildren(event, item) {
            if ($(event.path)) {
                if ($(event.target).hasClass('closeMail'))
                    $(event.target).removeClass('closeMail');
                else
                    $(event.target).addClass('closeMail');
                $(event.target).closest(".mailboxfolder").find('ul:first').fadeToggle();
            }
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
            let selectedOnedrives = [];
            let selectedFolders = [];
            $(".tree .mailBoxCheck:checked").each(function() {
                selectedOnedrives.push({
                    "onedriveId": $(this).val(),
                    "onedriveName": $(".tree #" + $(this).val()).html(),
                    "url": $(this).attr("data-url"),
                    "folderId": "-1",
                    "folderName": ""
                });
            });
            $(".tree .mailBoxFolderCheck:checked").each(function() {
                selectedFolders.push({
                    "folderId": $(this).val(),
                    "onedriveName": $(this).attr("data-onedriveName"),
                    "url": $(".tree [value='" + $(this).attr("data-onedriveId") + "']").attr("data-url"),
                    "onedriveId": $(this).attr("data-onedriveId"),
                    "folderName": $(".tree #" + $(this).val()).html()
                });
            });

            $(".tree .mailBoxFolderItemCheck:checked[isfolder='true']").each(function() {
                f(selectedFolders.filter(e => e.folderId === $(this).val()).length == 0)
                selectedFolders.push({
                    "folderId": $(this).val(),
                    "onedriveName": $(this).attr("data-onedriveName"),
                    "url": $(".tree [value='" + $(this).attr("data-onedriveId") + "']").attr("data-url"),
                    "onedriveId": $(this).attr("data-onedriveId"),
                    "folderName": $(".tree #" + $(this).val()).html()
                });
            });
            data.selectedOnedrives = JSON.stringify(selectedOnedrives);
            data.selectedFolders = JSON.stringify(selectedFolders);

            $.ajax({
                type: "POST",
                url: "{{ url('setEDiscoveryData') }}/onedrive",
                data: {
                    _token: "{{ csrf_token() }}",
                    data: JSON.stringify(data),
                },
                success: function(data) {
                    window.location.href = "{{ url('e-discovery') }}" + "/onedrive/edit/" + data +
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
                    "onedriveId": tr.find('.onedriveId').val(),
                    "folder": tr.find('.folderTitle').val()
                });
            });
            //--------------------//
            $('#sendItem').find('.items').val(JSON.stringify(data));
            //--------------------//
            $('#sendItem').modal('show');
        }
        //----------------------------------------------------//
        function restoreOnedriveOverwriteModal() {
            $('#restoreOneDriveModal').find('.modal-title').text(
                'Restore Selected OneDrives to Original Location (Overwrite)');
            $('#restoreOnedriveForm').find('.restoreAction').val('overwrite');
            let onedrives = $('#mailboxes li.mailbox input.mailBoxCheck:checked');

            var data = [];
            let drivesCount = onedrives.length;
            let unresolvedCount = 0;
            onedrives.each(function() {
                var parent = $(this).closest('.has.mailbox');
                data.push({
                    id: $(this).val(),
                    name: parent.find('.mail-click').html(),
                    url: $(this).attr('data-url'),
                });
            });
            $('#oneDriveOriginalTable_wrapper').find('.boxesCount').html(drivesCount);
            $('#oneDriveOriginalTable').DataTable().clear().draw();
            $('#oneDriveOriginalTable').DataTable().rows.add(data); // Add new data
            $('#oneDriveOriginalTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('oneDriveOriginalTable');
            adjustTable();
            $("#oneDriveOriginalTable").DataTable().draw();

            $('.modal-h-290p').addClass('h-290p');

            $('#restoreOneDriveModal').find(".refreshDeviceCode").click();
            $('#restoreOneDriveModal').modal('show');
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
            $('#restoreOneDriveCopyModal').find(".refreshDeviceCode").click();
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
            $('#exportDocsResultsTable_wrapper').find('.boxesCount').html(items.length);
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
        function restoreOnedriveKeepModal() {
            $('#restoreOneDriveModal').find('.modal-title').text('Restore Selected OneDrives to Original Location (Keep)');
            $('#restoreOnedriveForm').find('.restoreAction').val('keep');
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
            $('#oneDriveOriginalTable_wrapper').find('.boxesCount').html(drivesCount);
            $('#oneDriveOriginalTable').DataTable().clear().draw();
            $('#oneDriveOriginalTable').DataTable().rows.add(data); // Add new data
            $('#oneDriveOriginalTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('oneDriveOriginalTable');
            adjustTable();
            $("#oneDriveOriginalTable").DataTable().draw();

            $('.modal-h-290p').addClass('h-290p');

            $('#restoreOneDriveModal').find(".refreshDeviceCode").click();
            $('#restoreOneDriveModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreOnedriveFoldersOverwriteModal() {
            $('#restoreFolder').find(".per-req").text('Restore Selected Folders To Original Location (Overwrite)');
            $('#restoreFolderForm').find('.restoreAction').val('overwrite');
            $('#restoreFolderForm').find('.restoreType').val('original');
            //--------------------//
            $('#restoreFolderForm .restoreAnother_cont').addClass('hide');
            $('.modal-mt-10').addClass('mt-10v');
            $('.modal-h-190p').addClass('h-253p');
            $('.modal-h-190p').removeClass('h-190p');
            $('#restoreFolderForm .restoreAnother_cont .required').removeAttr('required');
            //--------------------//
            restoreOnedriveFoldersModal();
        }
        //----------------------------------------------------//
        function restoreOnedriveFoldersKeepModal() {
            $('#restoreFolder').find(".per-req").text('Restore Selected Folders To Original Location (Keep)');
            $('#restoreFolderForm').find('.restoreAction').val('keep');
            $('#restoreFolderForm').find('.restoreType').val('original');
            //--------------------//
            $('#restoreFolderForm .restoreAnother_cont').addClass('hide');
            $('.modal-mt-10').addClass('mt-10v');
            $('.modal-h-190p').addClass('h-253p');
            $('.modal-h-190p').removeClass('h-190p');
            $('#restoreFolderForm .restoreAnother_cont .required').removeAttr('required');
            //--------------------//
            restoreOnedriveFoldersModal();
        }
        //----------------------------------------------------//
        function restoreOnedriveFoldersAnotherModal() {
            //--------------------//
            $('#restoreFolder').find(".per-req").text('Copy Selected Folders To Another Location');
            $('#restoreFolderForm').find('.restoreAction').val('');
            $('#restoreFolderForm').find('.restoreType').val('another');
            //--------------------//
            $('#restoreFolderForm .restoreAnother_cont').removeClass('hide');
            $('.modal-mt-10').removeClass('mt-10v');
            $('.modal-h-190p').addClass('h-190p');
            $('.modal-h-190p').removeClass('h-253p');
            $('#restoreFolderForm .restoreAnother_cont .required').attr('required', 'required');
            //--------------------//
            restoreOnedriveFoldersModal();
        }
        //----------------------------------------------------//
        function restoreOnedriveFoldersModal() {
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
            $('#foldersResultsTable_wrapper').find('.boxesCount').html(foldersCount);
            $('#foldersResultsTable').DataTable().clear().draw();
            $('#foldersResultsTable').DataTable().rows.add(data); // Add new data
            $('#foldersResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('foldersResultsTable');
            adjustTable();
            $("#foldersResultsTable").DataTable().draw();
            $('#restoreFolder').find(".refreshDeviceCode").click();
            $('#restoreFolder').modal('show');
        }
        //----------------------------------------------------//
        function restoreOnedriveDocumentsOverwriteModal() {
            $('#restoreItem').find('.per-req').text('Restore Selected Documents To Original Location (Overwrite)');
            $('#restoreDocumentForm').find('.restoreAction').val('overwrite');
            $('#restoreDocumentForm').find('.restoreType').val('original');
            //--------------------//
            $('#restoreDocumentForm .restoreAnother_cont').addClass('hide');
            $('#restoreDocumentForm .restoreAnother_cont .required').removeAttr('required');
            //--------------------//
            restoreOnedriveDocumentsModal();
        }
        //----------------------------------------------------//
        function restoreOnedriveDocumentsKeepModal() {
            $('#restoreItem').find('.per-req').text('Restore Selected Documents To Original Location (Keep)');
            $('#restoreDocumentForm').find('.restoreAction').val('keep');
            $('#restoreDocumentForm').find('.restoreType').val('original');
            //--------------------//
            $('#restoreDocumentForm .restoreAnother_cont').addClass('hide');
            $('#restoreDocumentForm .restoreAnother_cont .required').removeAttr('required');
            //--------------------//
            restoreOnedriveDocumentsModal();
        }
        //----------------------------------------------------//
        function restoreOnedriveDocumentsCopyModal() {
            //--------------------//
            $('#restoreItemCopy').find('.modal-title').text('Copy Selected Documents To Another Location');
            $('#restoreDocumentCopyForm').find('.restoreAction').val('');
            $('#restoreDocumentCopyForm').find('.restoreType').val('another');
            //--------------------//
            $('#restoreDocumentCopyForm .restoreAnother_cont').removeClass('hide');
            $('#restoreDocumentCopyForm .restoreAnother_cont .required').attr('required', 'required');
            //--------------------//
            restoreOnedriveDocumentsCopy();
        }
        //----------------------------------------------------//
        function restoreOnedriveDocumentsCopy() {
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
            $('#docsCopyResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#docsCopyResultsTable').DataTable().clear().draw();
            $('#docsCopyResultsTable').DataTable().rows.add(data); // Add new data
            $('#docsCopyResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('docsCopyResultsTable');
            adjustTable();
            $("#docsCopyResultsTable").DataTable().draw();
            //--------------------//
            $('#restoreItemCopy').find('.refreshDeviceCode').click();
            $('#restoreItemCopy').modal('show');
        }
        //----------------------------------------------------//
        function restoreOnedriveDocumentsModal() {
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
            $('#docsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#docsResultsTable').DataTable().clear().draw();
            $('#docsResultsTable').DataTable().rows.add(data); // Add new data
            $('#docsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('docsResultsTable');
            adjustTable();
            $("#docsResultsTable").DataTable().draw();
            //--------------------//
            $('.modal-h-250').addClass('h-253p');
            $('#restoreItem').find('.refreshDeviceCode').click();
            $('#restoreItem').modal('show');
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
        function checkResolvedOnedrive(val) {
            return ($('.users option[data-url="' + val + '"]').length == 0);
        }
        //---------------------------------------------------//

        //---- Global Variables Function
        function getOneDriveFolder() {
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
        function getOneDrive() {
            return onedriveId;
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
            let foldersItems = $('#itemsTable').find('tr .isFolder[value="true"]').length;
            return totalRecordsShown - foldersItems;
        }
        //---------------------------------------------------//
        function getTotalFolders() {
            let foldersItems = $('#itemsTable').find('tr .isFolder[value="true"]').length;
            return foldersItems;
        }
        //---------------------------------------------------//
        function resetTotalItems() {
            totalRecordsShown = 0;
            $('.searchingLabel').addClass('hide');
            $('.stopLoad').addClass('hide');
            $('.resumeLoad').addClass('hide');
            $('.moveToEDiscovery').removeClass('mr-2 ml-2');
            $('.countingLabel').html(getTotalItems() + getTotalFolders() + ' Items Shown').addClass('hide');
        }
        //---------------------------------------------------//
        function increaseTotalItems(value) {
            totalRecordsShown = value;
        }
        //---------------------------------------------------//


        //---- Ajax Requests
        function downloadSingleDocument() {
            let tr = $('tr.current');
            let item = tr.find('input.mailBoxFolderItemCheck').val();
            let driveId = tr.find('.onedriveId').val();
            let folderTitle = tr.find('.folderTitle').val();
            let onedriveTitle = tr.find('.onedriveTitle').val();
            let fileSize = tr.find('.fileSizeColumn').html();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('downloadOnedriveDocument') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    jobId: $('.backupTime option:selected').attr('data-job-id'),
                    jobTime: $('.backupTime').val(),
                    showDeleted: $("#showDeleted")[0].checked,
                    showVersions: $("#showVersions")[0].checked,
                    onedriveId: driveId,
                    folderTitle: folderTitle,
                    fileSize: fileSize,
                    documentId: item,
                    name: tr.find('.fileNameColumn').html(),
                    onedriveTitle: onedriveTitle,
                    jobId: $('.backupTime option:selected').attr('data-job-id')
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
        function downloadMail() {
            let items = $('input.mailBoxFolderItemCheck:checked');
            let itemsArr = [];
            let boxId = '';
            let folderTitle = '';
            items.each(function() {
                var tr = $(this).closest('tr');
                boxId = tr.find('.onedriveId').val();
                folderTitle = tr.find('.folderTitle').val();
                itemsArr.push({
                    "id": $(this).val()
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('downloadItem') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    onedriveId: boxId,
                    folderTitle: folderTitle,
                    items: JSON.stringify(itemsArr),
                    jobId: $('.backupTime option:selected').attr('data-job-id')
                },
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(res.message);
                    window.open('/' + res.file);
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
        function loadItems() {
            $.ajax({
                type: "POST",
                async: true,
                url: "{{ url('getOnedriveFolderItems') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    offset: getTotalItems(),
                    onedriveId: getOneDrive(),
                    onedriveTitle: $('#' + getOneDrive()).html(),
                    folderId: getOneDriveFolder(),
                    folderTitle: $('#' + getOneDriveFolder()).html()
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
                            data.forEach((e) => {
                                globalItemsTable.row.add(e);
                            });
                            globalItemsTable.draw();
                            $('.countingLabel').html(getTotalItems() + getTotalFolders() + ' Items Shown')
                                .removeClass(
                                    'hide');

                        } else {
                            $('.searchingLabel').addClass('hide');
                            $('.stopLoad').addClass('hide');
                            $('.resumeLoad').removeClass('hide');
                            $('.moveToEDiscovery').addClass('mr-2 ml-2');
                        }
                    } else {
                        $('.searchingLabel').addClass('hide');
                        $('.stoppingRow,.warningRow').addClass('hide');
                        $('.stopLoad,.resumeLoad').addClass('hide');
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
            })
        }
        //---------------------------------------------------//
        function createSession(event) {
            event.preventDefault()
            onedriveId = -1;
            folderId = -1;
            $(document).find('.dataTables_filter input').val("");
            $('#itemsTable').DataTable().search("").ajax.reload();

            oneDriveCheckChange();
            $('.mailBoxFolderItemCheck,.mailBoxFolderCheck').change(folderTableChange);
            oneDriveFolderCheckChange();
            oneDriveFolderItemsCheckChange();

            $('.warningRow,.stoppingRow,.moveToEDiscovery').addClass('hide');

            if ($(".backupTime").find(":selected").val() != "") {
                $(".spinner_parent").css("display", "block");
                $('body').addClass('removeScroll');
                $.ajax({
                    type: "POST",
                    url: "{{ url('createOnedriveSession') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        jobs: $('#jobs').val(),
                        time: $(".backupTime").find(":selected").val(),
                        showDeleted: $("#showDeleted")[0].checked,
                        showVersions: $("#showVersions")[0].checked,
                    },
                    success: function(data) {
                        $(".moveToEDiscovery").removeClass("hide");
                        $("#activeclose").removeAttr('disabled')
                        $("#mailboxes").html("");
                        $(".spinner_parent").css("display", "none");
                        $('body').removeClass('removeScroll');
                        $("#choose").text("Change");
                        //-----------------------------//
                        jobType = $('#jobs').val();
                        jobId = $('.backupTime option:selected').attr('data-job-id');
                        jobTime = $(".backupTime").find(":selected").val();
                        showDeleted = $("#showDeleted")[0].checked;
                        showVersions = $("#showVersions")[0].checked;
                        //-----------------------------//
                        data.data.forEach(function(result) {
                            mailbox =
                                '<li class="has mailbox hand relative mb-0"><div class="relative allWidth">' +
                                '<span class="caret mailCaret closeMail caret-onedrive" onclick="getOnedriveFolders(event)"></span>' +
                                '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                                '<input type="checkbox" class="mailBoxCheck form-check-input" value="' +
                                result.id + '" data-url="' + result.url + '"/>' +
                                '<span class="tree-checkBox check-mark"></span></label>' +
                                '<span id="' + result.id +
                                '" class="mail-click left-mail-click ml-27" onclick="getOnedriveItems(event)" data-toggle="popover" title="' +
                                result.name + '" data-url="' + result.url + '">' +
                                result.name +
                                '</span></div><div class="folder-spinner hide"></div></li>';

                            $("#mailboxes").append($(mailbox));
                        });
                        $("#rdate").html($(".backupDate").val());
                        $("#rtime").html($(".backupTime").find(":selected")
                            .text());
                        $('#jobsModal').modal('hide');
                        $('.mailBoxCheck').change(oneDriveCheckChange);
                        //------------------------------------------//
                        var delay = 1000,
                            setTimeoutConst;
                        $('[data-toggle="popover"]').popover({
                            container: 'body',
                            trigger: 'manual',
                            content: function() {
                                return '<div class="flex"><span>Url: </span><span class="ellipsis" title="' +
                                    $(this).attr('data-url') + '">' + $(this).attr('data-url') +
                                    '</span></div>';
                            },
                            html: true,
                            delay: {
                                "hide": 500
                            }
                        }).on("mouseenter", function() {
                            var _this = this;
                            setTimeoutConst = setTimeout(function() {
                                $(_this).popover("show");
                                $(_this).siblings(".popover").on("mouseleave", function() {
                                    $(_this).popover('hide');
                                });
                                $('.popover').on("mouseleave", function() {
                                    $(_this).popover('hide');
                                });
                            }, delay);
                        }).on("mouseleave", function() {
                            var _this = this;
                            clearTimeout(setTimeoutConst);
                            setTimeout(function() {
                                if (!$(".popover:hover").length) {
                                    $(_this).popover("hide")
                                }
                            }, 100);
                        });

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
        function resetFilterTable() {
            $('.filter-form').find('.sortBoxType').val('');
            $('.filter-table td .active').removeClass('active');
            getFilteredOnedrives();
        }
        //---------------------------------------------------//
        function getFilteredOnedrives(event) {
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
                url: "{{ url('getFilteredItems') }}",
                data: data + '&letters=' + lettersArr.join(','),
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    $("#mailboxes").html('');
                    res.forEach(function(result) {
                        mailbox =
                            '<li class="has mailbox hand relative mb-0"><div class="relative allWidth">' +
                            '<span onclick="getOnedriveFolders(event)" class="caret mailCaret closeMail caret-onedrive"></span>' +
                            '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                            '<input type="checkbox" class="mailBoxCheck form-check-input" value="' +
                            result.id + '" data-url="' + result.url + '"/>' +
                            '<span class="tree-checkBox check-mark"></span></label>' +
                            '<span id="' + result.id +
                            '" class="mail-click left-mail-click ml-27" onclick="getOnedriveFolders(event)" title="' +
                            result.url + '">' +
                            result.name +
                            '</span></div><div class="folder-spinner hide"></div></li>';

                        $("#mailboxes").append($(mailbox));
                    });
                    $('.mailBoxCheck').change(oneDriveCheckChange);
                    $('.filter-icon').click();
                    oneDriveCheckChange();
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
        function getOnedriveFolders(event) {
            $target = $(event.target);
            let spinner = $target.closest('.mailbox.has').find('.folder-spinner:first');
            if ($target.hasClass('mailCaret')) {
                onedriveId = $target.closest('.mailbox.has').find('.mail-click').attr('id');
            } else {
                onedriveId = $target.attr('id');
            }
            $("#" + onedriveId).closest("div").find('.mailCaret').toggleClass('closeMail');
            if ($("#" + onedriveId).closest(".has.mailbox").find('ul').length) {
                $("#" + onedriveId).closest(".has.mailbox").find('ul:first').fadeToggle();
                return;
            }
            spinner.removeClass('hide');
            $.ajax({
                type: "GET",
                url: "{{ url('getOnedriveFolders') }}/" + onedriveId,
                data: {},
                success: function(data) {
                    spinner.addClass('hide');
                    onedriveFolders = "<ul class='pt-0 pb-0 mb-0'>";
                    data.forEach(function(result) {
                        onedriveId = result.onedriveId;
                        onedriveName = $(".tree #" + onedriveId).html();
                        onedriveFolders = onedriveFolders +
                            '<li class="mailboxfolder" data-folderId="' + result.id +
                            '"><div class="relative allWidth inline-flex">' +
                            (result.hasFolders ?
                                '<span class="caret mailCaret closeMail" onclick="getFolderChildren(event)"></span>' :
                                '') +
                            '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                            '<input type="checkbox" class="mailBoxFolderCheck form-check-input" value="' +
                            result.id + '" data-onedriveId="' + onedriveId + '" data-onedriveName="' +
                            onedriveName + '"/>' +
                            '<input type="hidden" class="folderPath" value="' + result.path + '">' +
                            '<span class="tree-checkBox check-mark"></span></label>' +
                            '<img class="folderIcon" src="/svg/folders/' + getFolderIcon(result.name) +
                            '.svg">' +
                            '<span id="' + result.id +
                            '" onclick="getFolderItems(event)" data-onedriveId="' + onedriveId +
                            '" class="mail-click childmail-click mail-folder-click" title="' + result
                            .name + '">' +
                            result.name + '</span></div>' + getFolderSubFoldersMenu(result) + '</li>';
                    });
                    onedriveFolders = onedriveFolders + "</ul>";
                    $("#" + onedriveId).closest("li")[0].append($(onedriveFolders)[0]);
                    $("#" + onedriveId).closest(".has.mailbox").find('ul:first').fadeToggle();

                    $('.mailBoxFolderCheck').change(function() {
                        // Allow Single Onedrive folders
                        oneDriveFolderCheckChange($(this).attr("data-onedriveid"), $(this));
                        //--------------------------//
                    })
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
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&onedrives=" + JSON.stringify(onedrivesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreOneDriveModal').modal('hide');
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
        function restoreOneDriveCopy() {
            event.preventDefault()
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
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&onedrives=" + JSON.stringify(onedrivesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreOneDriveCopyModal').modal('hide');
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
        function exportOnedrive() {
            event.preventDefault()

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
                data: "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&restoreJobName=" + $("#exportOnedriveForm [name='restoreJobName']").val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&onedrives=" + JSON.stringify(onedrivesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#exportOnedriveModal').modal('hide');
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
        function exportOnedriveFolders() {
            event.preventDefault()
            let folders = $('#exportOnedriveFoldersForm .mailboxCheck:checked');
            let onedrivesFolders = [];
            let index;
            //-----------------------------------//
            folders.each(function() {
                let tr = $(this).closest('tr');
                let folderParentId = $(this).attr('data-onedrive-id');
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
                data: "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&restoreJobName=" + $("#exportOnedriveFoldersForm [name='restoreJobName']").val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&folders=" + JSON.stringify(onedrivesFolders),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#exportOnedriveFoldersModal').modal('hide');
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
        function exportOnedriveDocuments() {
            event.preventDefault()
            let docs = $('#exportOnedriveDocumentsForm .mailboxCheck:checked');
            let docsArr = [];
            let index;
            let data = $('#exportOnedriveDocumentsForm').serialize();
            //-----------------------------------//
            docs.each(function() {
                let tr = $(this).closest('tr');
                let folderParentId = $(this).attr('data-onedrive-id');
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
                data: data +
                    "&_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked,
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#exportOnedriveDocumentsModal').modal('hide');
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
        function restoreFolder(event) {
            event.preventDefault();
            let folders = $('#restoreFolderForm .mailboxCheck:checked');
            let onedrivesFolders = [];
            let index;
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
            folders.each(function() {
                let tr = $(this).closest('tr');
                let folderParentId = $(this).attr('data-onedrive-id');
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
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&folders=" + JSON.stringify(onedrivesFolders),
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
            let docs = $('#restoreDocumentForm .mailboxCheck:checked');
            let docsArr = [];
            let index;
            //-----------------------------------//
            docs.each(function() {
                let tr = $(this).closest('tr');
                let folderParentId = $(this).attr('data-onedrive-id');
                docsArr.push({
                    id: $(this).val(),
                    name: tr.find('td:nth-child(2)').html(),
                    onedriveId: tr.find('.onedriveId').val(),
                    onedriveTitle: tr.find('.onedriveTitle').val(),
                    folderTitle: tr.find('.folderTitle').val(),
                });
            });
            let onedriveDocsArr = [];
            onedriveDocsArr.push({
                "onedriveId": docsArr[0].onedriveId,
                "onedriveTitle": docsArr[0].onedriveTitle,
                "folderTitle": docsArr[0].folderTitle,
                "docs": docsArr
            });
            //-----------------------------------//
            var data = $('#restoreDocumentForm').serialize();
            let toOnedrive = {
                'id': $('#restoreDocumentForm #onedrive').val(),
                'name': $('#restoreDocumentForm .users option:selected').html(),
                'url': $('#restoreDocumentForm #onedrive option:first').html()
            };
            data += "&onedrive=" + JSON.stringify(toOnedrive);
            data += "&docs=" + JSON.stringify(onedriveDocsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreOnedriveDocs') }}",
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
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
        function restoreItemCopy() {
            event.preventDefault()
            let docs = $('#restoreDocumentCopyForm .mailboxCheck:checked');
            //-----------------------------------//
            let docsArr = [];
            let index;
            //-----------------------------------//
            docs.each(function() {
                let tr = $(this).closest('tr');
                let folderParentId = $(this).attr('data-onedrive-id');
                docsArr.push({
                    id: $(this).val(),
                    name: tr.find('td:nth-child(2)').html(),
                    onedriveId: tr.find('.onedriveId').val(),
                    onedriveTitle: tr.find('.onedriveTitle').val(),
                    folderTitle: tr.find('.folderTitle').val(),
                });
            });
            let onedriveDocsArr = [];
            onedriveDocsArr.push({
                "onedriveId": docsArr[0].onedriveId,
                "onedriveTitle": docsArr[0].onedriveTitle,
                "folderTitle": docsArr[0].folderTitle,
                "docs": docsArr
            });
            //-----------------------------------//
            var data = $('#restoreDocumentCopyForm').serialize();
            let toOnedrive = {
                'id': $('#restoreDocumentCopyForm #onedrive').val(),
                'name': $('#restoreDocumentCopyForm .users option:selected').html(),
                'url': $('#restoreDocumentCopyForm #onedrive option:first').html()
            };
            data += "&onedrive=" + JSON.stringify(toOnedrive);
            data += "&docs=" + JSON.stringify(onedriveDocsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreOnedriveDocs') }}",
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked,
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
        //---------------------------------------------------//
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
        //---------------------------------------------------//
    </script>
@endsection
