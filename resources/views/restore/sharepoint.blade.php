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

        .selected-customBorder {
            height: 142px;
        }

        .restore-option-customBorder {
            height: 320px;
        }

        .restore-option-folders-customBorder {
            height: 215px;
        }

        .restore-items-restore-options {
            height: 215px;
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
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <div id="jobsModal" class="modal modal-center" role="dialog">
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
                        <div class="row mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p">Specify Point in Time
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
                                        <span class="checkbox-span-class check-mark"></span>
                                    </label>
                                    <span class="ml-25">Show Items That Have Been Deleted By
                                        User</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-form-70">
                                    <label class="checkbox-padding-left checkbox-container">&nbsp;
                                        <input id="showVersions" type="checkbox" class="form-check-input">
                                        <span class="checkbox-span-class check-mark"></span>
                                    </label>
                                    <span class="ml-25">Show All Versions Of Items That Have
                                        Been Modified By User</span>
                                </div>
                            </div>

                            <div class="row">
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
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <div id="restoreSiteModal" class="modal modal-center" role="dialog">
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
                                <h4 class="per-req ml-2p">Restore Selected Sites To Original Location
                                </h4>
                            </div>
                        </div>
                        <form id="restoreSiteForm" class="mb-0" onsubmit="restoreSite(event)">
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
                                        <h5 class="txt-blue mt-0">Selected Sites</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder">
                                            <div class="allWidth sitesResultsTable">
                                                <table id="sitesTable"
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

                            <div class="custom-right-col restore-select-sites-col">
                                <div class="row">
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Restore Options</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder restore-option-customBorder pt-3 pb-3">
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
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="sendSharedLinksNotification" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Send Shared Links
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
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="restoreListViews" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">List Views</span>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="restoreSubsites" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Subsites</span>
                                                        </div>
                                                    </div>
                                                    <div class="w-100"></div>

                                                    <div class="col">
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="restoreMasterPages" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Master Pages</span>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="restorePermissions" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Permissions</span>
                                                        </div>
                                                    </div>
                                                    <div class="w-100"></div>

                                                    <div class="col">
                                                        <div class="relative allWidth">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input id="changedItems" name="changedItems"
                                                                    type="checkbox" class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Changed Items</span>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="relative allWidth">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input id="deletedItems" name="deletedItems"
                                                                    type="checkbox" class="form-check-input">
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
                                <h4 class="per-req ml-2p">Restore Selected <span class="contentType"></span>
                                </h4>
                            </div>
                        </div>
                        <form id="restoreContentForm" class="mb-0" onsubmit="restoreContent(event)">
                            <div class="custom-left-col">
                                <input type="hidden" name="contentType">
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
                                        <div class="col-lg-12 customBorder selected-customBorder">
                                            <div class="allWidth">
                                                <table id="contentsTable"
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
                                            <div class="allWidth">
                                                <div class="authCont custom-authCont pt-3 pb-3">
                                                    <label class="mr-4 m-0 nowrap">Specify Site to Restore to:</label>
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
                                        <div class="col-lg-12 customBorder pb-3 pt-3">
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
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="sendSharedLinksNotification" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Send Shared Links
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
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="restoreListViews" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">List Views</span>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="restorePermissions" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Permissions</span>
                                                        </div>
                                                    </div>
                                                    <div class="w-100"></div>

                                                    <div class="col">
                                                        <div class="relative allWidth">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="changedItems" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Changed Items</span>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="relative allWidth">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
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
                                <h4 class="per-req ml-2p">Export Selected Libraries to .Zip
                                </h4>
                            </div>
                        </div>
                        <form id="exportLibrariesForm" class="mb-0" onsubmit="exportLibraries(event)">
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
                                                            <label
                                                                class="checkbox-top-left checkbox-container checkbox-search">
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
                                <h4 class="per-req ml-2p">Export Selected Site Documents To .Zip
                                </h4>
                            </div>
                        </div>
                        <form id="exportSiteDocumentsForm" class="mb-0" onsubmit="exportSiteDocuments(event)">
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
                                                            <label
                                                                class="checkbox-top-left checkbox-container checkbox-search">
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
        <div id="exportSiteItemsModal" class="modal modal-center" role="dialog">
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
                                <h4 class="per-req ml-2p">Export Site Items Attachments To .Zip
                                </h4>
                            </div>
                        </div>
                        <form id="exportSiteItemsForm" class="mb-0" onsubmit="exportSiteItems(event)">
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
                                                            <label
                                                                class="checkbox-top-left checkbox-container checkbox-search">
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
        <div id="restoreSiteDocumentsModal" class="modal modal-center" role="dialog">
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
                                <h4 class="per-req ml-2p">Restore Selected Documents</h4>
                            </div>
                        </div>
                        <form id="restoreSiteDocumentsForm" class="mb-0" onsubmit="restoreSiteDocuments(event)">
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
                                        <h5 class="txt-blue mt-0">Site Documents</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder">
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
                                        <div class="col-lg-12 customBorder restore-option-folders-customBorder pb-3 pt-3">
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
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="sendSharedLinksNotification" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Send Shared Links
                                                                Notifications</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="relative allWidth">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="restorePermissions" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Permissions</span>
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
                                <h4 class="per-req ml-2p">Restore Selected Items</h4>
                            </div>
                        </div>
                        <form id="restoreSiteItemsForm" class="mb-0" onsubmit="restoreSiteItems(event)">
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
                                                                <label
                                                                    class="checkbox-top-left checkbox-container checkbox-search">
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
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
                                        <div class="col-lg-12 customBorder restore-items-restore-options pb-3 pt-3">
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
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="sendSharedLinksNotification" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Send Shared Links
                                                                Notifications</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="relative allWidth">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="restorePermissions" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Permissions</span>
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
                                <h4 class="per-req ml-2p">Restore Selected Folders</h4>
                            </div>
                        </div>
                        <form id="restoreFoldersForm" class="mb-0" onsubmit="restoreFolders(event)">
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
                                        <h5 class="txt-blue mt-0">Selected Folders</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder">
                                            <div class="allWidth">
                                                <table id="foldersResultTable"
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
                                        <div class="col-lg-12 customBorder restore-option-folders-customBorder pb-3 pt-3">
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
                                                            <label
                                                                class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="sendSharedLinksNotification"
                                                                    type="checkbox" class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Send Shared Links
                                                                Notifications</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="relative allWidth">
                                                            <label
                                                                class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="restorePermissions" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Permissions</span>
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
                                <h4 class="per-req ml-2p">Export Selected Folders To .Zip
                                </h4>
                            </div>
                        </div>
                        <form id="exportFoldersForm" class="mb-0" onsubmit="exportFolders(event)">
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
                                                            <label
                                                                class="checkbox-top-left checkbox-container checkbox-search">
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
        <div class="row h-100">
            <div class="col-2 z-10">

                <div class="custom-border">
                    <div class="row mail-box left-mail-box h-100">
                        <div class="col-lg-12 nopadding relative">
                            <div class="m-4 flex">
                                <label class="treeSearchInput flex-9">
                                    <input type="search" class="form_input form-control custom-search"
                                        placeholder="Search..." aria-controls="itemsTable">
                                    <div class="search-container">
                                        <img class="search-icon" src="/svg/search.svg">
                                    </div>
                                </label>
                                <div class="filter-container relative flex-1">
                                    <img class="filter-icon hand dropdown-toggle custom-filter-padding"
                                        data-toggle="dropdown" aria-expanded="false" src="/svg/filter.svg">
                                    <div class="dropdown-menu dropdown-menu-filter mainItem-filter-menu">
                                        <div class="divBorderRight"></div>
                                        <div class="divBorderBottom"></div>
                                        <div class="divBorderleft"></div>
                                        <div class="divBorderUp"></div>
                                        <form class="filter-form mb-0" onsubmit="getFilteredSites(event)">
                                            <div class="filterCont flex allWidth pt-10">
                                                <div class="p-3 text-white pad-left-right">
                                                    <select name="sortBoxType" required
                                                        class="required sortBoxType btn-sm dropdown-toggle form_dropDown form-control"
                                                        data-toggle="dropdown" value=":">
                                                        <option value="" selected="selected">Sort</option><span
                                                            class="fa fa-caret-down"></span>
                                                        <option value="AZ">A > Z
                                                        </option>
                                                        <option value="ZA">Z > A
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="p-3 text-white">
                                                <label class="font-small pl-20" cellspa>Show Sites
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
                                                    class="btn_primary_state mr-2 pl-5 pr-5 ml-30 w-100p">
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
                @if (!$role->hasAnyPermission('sharepoint_restore_actions', 'sharepoint_export_actions'))
                    @php
                        $permissionClass = 'hide';
                    @endphp
                @endif
                <div class="row main-button-cont {{ $permissionClass }}">
                    <div class="btnMain main-button flex">
                        <div class="btnUpMask"></div>
                        <div class="row m-0 pl-4 pr-4 allWidth">
                            @if ($role->hasPermissionTo('sharepoint_restore_actions'))
                                <div class="col-lg-3 sitesButton">
                                    <div class="selected-action allWidth relative">
                                        <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                            data-toggle="dropdown" aria-expanded="false">
                                            Sites Actions
                                            <span class="selectedBoxCount"></span>
                                            <span class="fa fa-caret-down"></span></button>
                                        <ul class="dropdown-menu allWidth">
                                            <li>
                                                <a href="javascript:restoreSiteModal(event)" class="tooltipSpan"
                                                    title="Restore Selected Sites To Original Location">
                                                    Restore Selected Sites To Original Location
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-3 siteContentButtons">
                                <div class="selected-action allWidth relative">
                                    <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                        data-toggle="dropdown" aria-expanded="false">
                                        Content Actions
                                        <span class="selectedFolderCount"></span>
                                        <span class="fa fa-caret-down"></span></button>

                                    <ul class="dropdown-menu allWidth">
                                        @if ($role->hasPermissionTo('sharepoint_restore_actions'))
                                            <li class="siteLibrariesButton restoreSiteLibrariesButton">
                                                <a href="javascript:restoreLibrariesModal(event)" class="tooltipSpan"
                                                    title="Restore Selected Libraries">
                                                    Restore Selected Libraries
                                                </a>
                                            </li>
                                            <li class="siteListsButton restoreSiteListsButton">
                                                <a href="javascript:restoreListsModal(event)" class="tooltipSpan"
                                                    title="Restore Selected Lists">
                                                    Restore Selected Lists
                                                </a>
                                            </li>
                                        @endif
                                        @if ($role->hasPermissionTo('sharepoint_export_actions'))
                                            <li class="siteLibrariesButton">
                                                <a href="javascript:exportLibrariesModal(event)" class="tooltipSpan"
                                                    title="Export Selected Libraries to .Zip">
                                                    Export Selected Libraries to .Zip
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 siteFoldersButton">
                                <div class="selected-action allWidth relative">
                                    <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                        data-toggle="dropdown" aria-expanded="false">
                                        Folders Actions
                                        <span class="selectedFoldersCount"></span>
                                        <span class="fa fa-caret-down"></span></button>

                                    <ul class="dropdown-menu allWidth">
                                        @if ($role->hasPermissionTo('sharepoint_restore_actions'))
                                            <li>
                                                <a href="javascript:restoreFoldersModal(event)" class="tooltipSpan"
                                                    title="Restore Selected Folders">
                                                    Restore Selected Folders
                                                </a>
                                            </li>
                                        @endif
                                        @if ($role->hasPermissionTo('sharepoint_export_actions'))
                                            <li>
                                                <a href="javascript:exportFoldersModal(event)" class="tooltipSpan"
                                                    title="Export Selected Folders to .Zip">
                                                    Export Selected Folders to .Zip
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 siteItemsButton">
                                <div class="selected-action allWidth relative">
                                    <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                        data-toggle="dropdown" aria-expanded="false">
                                        Documents & Items Actions
                                        <span class="selectedItemCount"></span>
                                        <span class="fa fa-caret-down"></span></button>
                                    <ul class="dropdown-menu allWidth">
                                        @if ($role->hasPermissionTo('sharepoint_restore_actions'))
                                            <li class="docLi">
                                                <a href="javascript:restoreSiteDocumentsModal(event)"
                                                    class="tooltipSpan" title="Restore Selected Documents">
                                                    Restore Selected Documents
                                                </a>
                                            </li>
                                            <li class="itemsLi">
                                                <a href="javascript:restoreSiteItemsModal(event)" class="tooltipSpan"
                                                    title="Restore Selected Items">
                                                    Restore Selected Items
                                                </a>
                                            </li>
                                        @endif
                                        @if ($role->hasPermissionTo('sharepoint_export_actions'))
                                            <li class="docLi">
                                                <a href="javascript:exportSiteDocumentsModal(event)" class="tooltipSpan"
                                                    title="Export Selected Documents to .Zip">
                                                    Export Selected Documents to .Zip
                                                </a>
                                            </li>
                                            <li class="itemsLi">
                                                <a href="javascript:exportSiteItemsModal(event)" class="tooltipSpan"
                                                    title="Export Selected Items Attachments">
                                                    Export Selected Items Attachments
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
                                        <th colspan="10">
                                            <div class="custom-text-right">
                                                <span class="orgSpan hide searchingLabel">Searching
                                                    ...</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <span class="orgSpan hide countingLabel"></span>
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

            <div class="modal-dialog modal-lg w-500 mt-20v">
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
                            <div class="input-form-70 mb-1">Name: <span class="ml-43">Created By:</span></div>
                            <div class="input-form-70 inline-flex">
                                <input type="text" class="form_input form-control mr-25 font-size" id="name"
                                    placeholder="" />
                                <input type="text" class="form_input form-control font-size" id="createdBy"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 mb-1">Created At:</div>
                            <div class="input-form-70 inline-flex">
                                <input type="text" class="form_input form-control mr-25 font-size"
                                    id="createdAtFrom" placeholder="From" />
                                <input type="text" class="form_input form-control font-size" id="createdAtTo"
                                    placeholder="To" />
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
        let siteId = '-1';
        let folderId = '-1';
        let fullPath = [];
        let contentId = '-1';
        let contentType = 'library';
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
        let loadingSites = false;
        let jobType = null;
        let jobId = null;
        let jobTime = null;
        let showDeleted = null;
        let showVersions = null;
        //-------------------------------------------------//
        let tableSettings = {
            'ajax': {
                "type": "POST",
                "url": "{{ url('getSiteContentItems') }}",
                "dataSrc": '',
                "data": function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.offset = 0;
                    d.siteId = getSite();
                    d.type = getContentType();
                    d.siteTitle = $('#' + getSite()).html();
                    d.contentId = getContent();
                    d.contentTitle = $('#' + getContent()).html();
                    d.folderId = getSiteFolder();
                    d.folderTitle = $('#' + getSiteFolder()).html();
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html("{{ __('variables.errors.restore_session_expired') }}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");
                            window.location.reload();
                        }, 3000);
                    }
                },
                "beforeSend": function() {
                    siteCheckChange();
                    siteContentCheckChange();
                    contentFolderCheckChange();
                    if (getSite() == "-1") {
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
                    } else if ($(this).hasClass('fileNameColumn')) {
                        $(this).attr('title', $(this).find('a').html());
                    }
                });
            },
            'columns': [{
                    "data": null,
                    "class": "after-none",
                    render: function(data, type, full, meta) {
                        return '<label class="checkbox-container checkbox-search">&nbsp;' +
                            '<input type="hidden" class="siteId" value="' + data.siteId +
                            '">' +
                            '<input type="hidden" class="siteTitle" value="' + data.siteTitle +
                            '">' +
                            '<input type="hidden" class="contentId" value="' + data.contentId +
                            '">' +
                            '<input type="hidden" class="contentTitle" value="' + data.contentTitle +
                            '">' +
                            '<input type="hidden" class="isFolder" value="' + data.isFolder +
                            '">' +
                            '<input type="hidden" class="folderId" value="' + data.folderId +
                            '">' +
                            '<input type="hidden" class="folderTitle" value="' + data.folderTitle + '">' +
                            '<input type="checkbox" data-type="' + data.type + '" data-isFolder="' + data
                            .isFolder + '" class="contentFolderItemCheck form-check-input" value="' +
                            data.id + '"/>' +
                            '<span class="tree-checkBox check-mark"></span></label>';
                    }
                },
                {
                    "data": null,
                    "class": "after-none",
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
                    "class": "text-left fileNameColumn wrap",
                    "render": function(data) {
                        if (data.isFolder)
                            return '<a class="text-orange infolder">' + data.name + '</a>';
                        else if (data.title)
                            return data.title;
                        return data.name;
                    }
                },
                {
                    "data": null,
                    "render": function(data) {
                        if (data.version)
                            return '<a href="#" class="versionColumn">' + data.version + '</a>';
                        return '';
                    }
                },
                {
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
                    "data": "createdBy"
                },
                {
                    "data": null,
                    render: function(data) {
                        return formatDate(data.creationTime);
                    }
                },
                {
                    "data": "modifiedBy"
                },
                {
                    "data": null,
                    render: function(data) {
                        return formatDate(data.modificationTime);
                    }
                },
                {
                    "data": null,
                    "class": "after-none",
                    "title": '<img class= "tableIcone w-13 mr-0" src="/svg/download\.svg " title="Download">',
                    render: function(data, type, full, meta) {
                        @if ($role->hasPermissionTo('sharepoint_view_item_details'))
                            if (data.isFolder == false)
                                return '<img class= "hand tableIcone downloadDocument w-13 mr-0" src="/svg/download\.svg " title="Download ' +
                                    (contentType == "list" ? "Item Attachments" : "Document") + '">';
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
            "fnDrawCallback": function(data) {
                //-----------------------------//
                if (data.json)
                    if (data.json[0]) {
                        if (data.json[0].type == "list")
                            $('#itemsTable').DataTable().columns(4).visible(false);
                        else
                            $('#itemsTable').DataTable().columns(4).visible(true);
                    }
                //-----------------------------//
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
                siteCheckChange();
                $('.contentFolderItemCheck,.contentFolderCheck').change(folderTableChange);
                siteContentCheckChange();
                contentFolderCheckChange();
                //-----------------------------//
                $('.tableIcone.downloadDocument').unbind('click').click(function() {
                    var tr = $(this).closest('tr');
                    $('tr.current').removeClass('current');
                    tr.addClass('current');
                    if (contentType == 'library')
                        downloadSingleDocument();
                    else if (contentType == 'list')
                        downloadSingleItem();
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
                } else if (tableDataCount > 0 && stopLoading && !isDone) {
                    stopLoad();
                }
                //-------------------------------------------//
                $('.infolder').unbind('click').click(getFolderInItems);
                //-------------------------------------------//
                $('.contentFolderItemCheck[data-isFolder="true"]').change(contentFolderCheckChange);
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
            }, {
                'targets': [0, 1, 9], // column index (start from 0)
                'width': "15",
            }, ]
        };
        //-------------------------------------------------//
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
        //-------------------------------------------------//
        $(document).ready(function() {
            minmizeSideBar();

            siteCheckChange();
            $('.dropdown-menu.mainItem-filter-menu').on('click', function(event) {
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


            $('#sitesTable').DataTable({
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
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '" data-contentId="' + data.contentId + '">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<input class="folderTitle" value="' + data.folderTitle + '">' +
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
                "scrollY": "60px",
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
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '" data-contentId="' + data.contentId + '">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<input class="folderTitle" value="' + data.folderTitle + '">' +
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
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '" data-contentId="' + data.contentId + '">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<input class="folderTitle" value="' + data.folderTitle + '">' +
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
                "scrollY": "60px",
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
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '" data-contentId="' + data.contentId + '">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<input class="folderTitle" value="' + data.folderTitle + '">' +
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
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '" data-contentId="' + data.contentId + '">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<input class="folderTitle" value="' + data.folderTitle + '">' +
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
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '" data-contentId="' + data.contentId + '">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<input class="folderTitle" value="' + data.folderTitle + '">' +
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
            //--------------------------------------------------//
            $('input[name="siteType"]').change();
            //--------------------------------------------------//
            $('input[name="listType"]').change(function() {
                let form = $(this).closest('form');
                if ($(this).val() == 'original') {
                    form.find('input[name="list"]').attr('disabled', 'disabled').val('');
                } else {
                    form.find('input[name="list"]').removeAttr('disabled');
                }
            });
            $('input[name="listType"]').change();
            //--------------------------------------------------//
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
        $(document).on('change', '.tree input.mailBoxCheck[type=checkbox]', function(e) {
            e.stopPropagation();
            siteFolderItemsCheckChange();
            $('.contentFolderItemCheck').change(siteFolderItemsCheckChange);
            $('.contentFolderItemCheck,.contentFolderCheck').change(folderTableChange);
            contentFolderCheckChange();
            siteContentCheckChange();
        });

        $("#jobs").change(function() {
            if (this.value != "") {
                $(".spinner_parent").css("display", "block");
                $('body').addClass('removeScroll');
                $.ajax({
                    type: "GET",
                    url: "{{ url('getRestoreTime') }}/sharepoint/" + this.value,
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
        function siteCheckChange() {
            var len = $('.tree .mailBoxCheck:checked').length;
            if (len == 0) {
                $('.sitesButton button').attr('disabled', 'disabled');
                $('.sitesButton .selectedBoxCount').html('');
            } else {
                $('.sitesButton button').removeAttr('disabled');
                $('.sitesButton .selectedBoxCount').html('(' + len + ')');
            }
            $('.contentFolderItemCheck,.contentFolderCheck').change(folderTableChange);
            siteContentCheckChange();
            contentFolderCheckChange();
            siteFolderItemsCheckChange();
        }
        //----------------------------------------------------//
        function siteContentCheckChange() {
            var items = $('.tree .siteContentCheck:checked').length;
            var librariesLen = $('.tree .siteContentCheck[data-type="library"]:checked').length;
            var listsLen = $('.tree .siteContentCheck[data-type="list"]:checked').length;
            if (librariesLen > 0) {
                $('.siteContentButtons .siteLibrariesButton').removeClass('hide');
                $(".siteContentButtons .restoreSiteLibrariesButton").addClass("hide");
                if (librariesLen == 1)
                    $(".siteContentButtons .restoreSiteLibrariesButton").removeClass("hide");
            } else {
                $('.siteContentButtons .siteLibrariesButton').addClass('hide');
            }

            if (listsLen > 0) {
                $('.siteContentButtons .siteListsButton').removeClass('hide');
                $(".siteContentButtons .restoreSiteListsButton").addClass("hide");
                if (listsLen == 1)
                    $(".siteContentButtons .restoreSiteListsButton").removeClass("hide");
            } else {
                $('.siteContentButtons .siteListsButton').addClass('hide');
            }

            if (items == 0) {
                $('.siteContentButtons button').attr('disabled', 'disabled');
                $('.siteContentButtons .selectedFolderCount').html('');
            } else {
                $('.siteContentButtons button').removeAttr('disabled');
                $('.siteContentButtons .selectedFolderCount').html('(' + items + ')');
            }
        }
        //----------------------------------------------------//
        function contentFolderCheckChange() {
            var len = $('.contentFolderItemCheck[data-isFolder="true"]:checked').length;
            if (len == 0) {
                $('.siteFoldersButton button').attr('disabled', 'disabled');
                $('.siteFoldersButton .selectedFoldersCount').html('');
            } else {
                $('.siteFoldersButton button').removeAttr('disabled');
                $('.siteFoldersButton .selectedFoldersCount').html('(' + len + ')');
            }
        }
        //----------------------------------------------------//
        function folderTableChange() {
            if ($(this).hasClass('contentFolderItemCheck') && $(this).attr('data-isfolder') == "true") {
                $('.tree .contentFolderCheck:checked').prop('checked', false);
            } else if ($(this).hasClass('contentFolderCheck')) {
                $('.contentFolderItemCheck:checked[data-isFolder="true"]').prop('checked', false);
            }
            contentFolderCheckChange();
            siteContentCheckChange();
            siteFolderItemsCheckChange();
        }
        //----------------------------------------------------//
        function siteFolderItemsCheckChange() {
            var itemsLength = $('.contentFolderItemCheck:checked[data-isFolder="false"][data-type="list"]').length;
            var docsLength = $('.contentFolderItemCheck:checked[data-isFolder="false"][data-type="library"]').length;
            if (itemsLength > 0 || docsLength > 0) {
                $('.siteItemsButton button').removeAttr('disabled');
            }
            if (itemsLength == 0 && docsLength == 0) {
                $('.siteItemsButton button').attr('disabled', 'disabled');
                $('.siteItemsButton .selectedItemCount').html('');
                $('.siteItemsButton .itemsLi').addClass('hide');
                $('.siteItemsButton .docLi').addClass('hide');
            }
            if (itemsLength == 0) {
                $('.siteItemsButton .itemsLi').addClass('hide');
            }
            if (docsLength == 0) {
                $('.siteItemsButton .docLi').addClass('hide');
            }
            if (itemsLength > 0) {
                $('.siteItemsButton .itemsLi').removeClass('hide');
                $('.siteItemsButton button').removeAttr('disabled');
                $('.siteItemsButton .selectedItemCount').html('(' + itemsLength + ')');
            }
            if (docsLength > 0) {
                $('.siteItemsButton button').removeAttr('disabled');
                $('.siteItemsButton .selectedItemCount').html('(' + docsLength + ')');
                $('.siteItemsButton .docLi').removeClass('hide');
            }
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
        function getContentItems(event) {
            let selectedContentId = event.target.id;
            let selectedSiteId = $(event.target).attr('data-siteId');
            siteId = selectedSiteId;
            contentId = selectedContentId;
            contentType = $('#' + selectedContentId).attr('data-type');
            folderId = -1;
            fullPath = [];
            showFolderPath();
            $('.item-click').removeClass('active');
            $('.item-click#' + selectedContentId).addClass('active');

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
            let selectedFolderId = tr.find('.contentFolderItemCheck').val();
            let selectedSiteId = tr.find('.siteId').val();
            let selectedContentId = tr.find('.contentId').val();
            siteId = selectedSiteId;
            folderId = selectedFolderId;
            contentId = selectedContentId;
            stopLoading = false;
            isDone = false;
            resetTotalItems();
            resetOffset();
            $(document).find('.dataTables_filter input').val("");
            $('#itemsTable').DataTable().search("").ajax.reload();
            //---------------------------------------------//
            if (fullPath.length == 0) {
                let parent = $('.tree [id="' + contentId + '"]').closest('.mainItem').find('.left-mail-click').html();
                fullPath.push({
                    "id": selectedContentId,
                    "name": parent + ' - ' + $('.tree [id="' + contentId + '"]').html(),
                    "type": "content"
                });
                fullPath.push({
                    "id": folderId,
                    "name": tr.find('.infolder').html(),
                    "type": "folder"
                });
            } else {
                fullPath.push({
                    "id": folderId,
                    "name": tr.find('.infolder').html(),
                    "type": "folder"
                });
            }
            //---------------------------------------------//
            showFolderPath();
            //---------------------------------------------//
        }
        //----------------------------------------------------//
        function getTableFiles(content, folder = -1) {
            //---------------------------------------------//
            contentId = content;
            folderId = folder;
            //---------------------------------------------//
            if (folder == -1)
                fullPath = [];
            else {
                let selectedIndex = fullPath.findIndex(x => x.id === folder);
                fullPath = fullPath.slice(0, selectedIndex + 1);
            }
            //---------------------------------------------//
            showFolderPath();
            //---------------------------------------------//
            stopLoading = false;
            isDone = false;
            resetTotalItems();
            resetOffset();
            $(document).find('.dataTables_filter input').val("");
            $('#itemsTable').DataTable().search("").ajax.reload();
        }
        //----------------------------------------------------//
        function showFolderPath() {
            if (fullPath.length > 0) {
                let pathArr = [];
                let folderName = '';
                let tempCcontentId;
                fullPath.forEach(function(e) {
                    if (e.type == "content") {
                        tempContentId = e.id;
                        pathArr.push('<a onclick="getTableFiles(\'' + e.id + '\')">' + e.name + '</a>');
                    } else {
                        pathArr.push('<a onclick="getTableFiles(\'' + tempCcontentId + '\',\'' + e.id + '\')">' + e
                            .name + '</a>');
                    }
                })
                $('.folderPathRow').removeClass('hide');
                $('.path-header').removeClass('hide');
                $('.folderPathSpan').html($(pathArr.join('<img class="nav-arrow" src="/svg/arrow-right.svg">')));
            } else {
                $('.folderPathRow').addClass('hide');
                $('.path-header').addClass('hide');
                $('.folderPathSpan').html('');
            }
        }
        //----------------------------------------------------//
        function getSiteItems(event) {
            let selectedSiteId = event.target.id;

            siteId = selectedSiteId;
            folderId = -1;
            fullPath = [];
            showFolderPath();
            $('.item-click').removeClass('active');
            $('.item-click#' + selectedSiteId).addClass('active');
            $('.item-click#' + selectedSiteId).closest('li.mainItem').find('.closeMail:first').click();

            stopLoading = false;
            isDone = false;
            resetTotalItems();
            resetOffset();
            $(document).find('.dataTables_filter input').val("");
            $('#itemsTable').DataTable().search("").ajax.reload();
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
            var values = $('li .item-click:contains("' + value + '")');
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
            let selectedSites = [];
            let selectedContents = [];
            $(".tree .mailBoxCheck:checked").each(function() {
                selectedSites.push({
                    "siteId": $(this).val(),
                    "siteName": $(".tree #" + $(this).val()).html(),
                    "url": $(this).attr("data-url"),
                    "contentId": "-1",
                    "contentName": ""
                });
            });
            $(".tree .siteContentCheck:checked").each(function() {
                selectedContents.push({
                    "contentId": $(this).val(),
                    "siteName": $(this).attr("data-siteName"),
                    "url": $(".tree [value='" + $(this).attr("data-siteId") + "']").attr("data-url"),
                    "siteId": $(this).attr("data-siteId"),
                    "contentName": $(".tree #" + $(this).val()).html()
                });
            });

            data.selectedSites = JSON.stringify(selectedSites);
            data.selectedContents = JSON.stringify(selectedContents);

            $.ajax({
                type: "POST",
                url: "{{ url('setEDiscoveryData') }}/sharepoint",
                data: {
                    _token: "{{ csrf_token() }}",
                    data: JSON.stringify(data),
                },
                success: function(data) {
                    window.location.href = "{{ url('e-discovery') }}" + "/sharepoint/edit/" + data +
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
            let items = $('input.contentFolderItemCheck:checked');
            let data = [];
            items.each(function() {
                var tr = $(this).closest('tr');
                data.push({
                    "id": $(this).val(),
                    "siteId": tr.find('.siteId').val(),
                    "folder": tr.find('.folderTitle').val()
                });
            });
            //--------------------//
            $('#sendItem').find('.items').val(JSON.stringify(data));
            //--------------------//
            $('#sendItem').modal('show');
        }
        //----------------------------------------------------//
        function restoreSiteModal() {
            let sites = $('#mailboxes li.mainItem input.mailBoxCheck:checked');
            var tableData = [];
            let sitesCount = sites.length;
            let unresolvedCount = 0;
            sites.each(function() {
                var parent = $(this).closest('.has.mainItem');
                tableData.push({
                    id: $(this).val(),
                    name: parent.find('.item-click').html(),
                    url: $(this).attr('data-url')
                });
            });
            $('#sitesTable_wrapper').find('.boxesCount').html(sitesCount);
            $('#sitesTable').DataTable().clear().draw();
            $('#sitesTable').DataTable().rows.add(tableData); // Add new data
            $('#sitesTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('sitesTable');
            adjustTable();
            $("#sitesTable").DataTable().draw();
            $('#restoreSiteModal').find('.refreshDeviceCode').click();
            $('#restoreSiteModal').modal('show');
            //----------------------------------------//
            getSites();
            //----------------------------------------//
        }
        //----------------------------------------------------//
        function restoreLibrariesModal() {
            //--------------------//
            $('#restoreContentModal').find('input[name="contentType"]').val('library');
            $('#restoreContentModal').find('.contentType').html('Libraries');
            $('#restoreContentModal').find('.contentTypeSingle').html('library');
            //--------------------//
            let libraries = $('#mailboxes input.siteContentCheck[data-type="library"]:checked');
            let librariesCount = libraries.length;
            let tableData = [];
            //--------------------//
            libraries.each(function() {
                var folderCheck = $(this);
                var library = folderCheck.parents('li.mainItem').find('input.mailBoxCheck');
                tableData.push({
                    "id": $(this).val(),
                    "name": folderCheck.closest('.mailboxfolder').find('.item-click').html(),
                    "siteId": library.val(),
                    "siteTitle": $('#' + library.val()).html()
                });
            });
            $('#contentsTable_wrapper').find('.boxesCount').html(librariesCount);
            $('#contentsTable').DataTable().clear().draw();
            $('#contentsTable').DataTable().rows.add(tableData); // Add new data
            $('#contentsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('contentsTable');
            adjustTable();
            $("#contentsTable").DataTable().draw();
            $('#restoreContentModal').find('.refreshDeviceCode').click();
            $('#restoreContentModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreListsModal() {
            //--------------------//
            $('#restoreContentModal').find('input[name="contentType"]').val('list');
            $('#restoreContentModal').find('.contentType').html('Lists');
            $('#restoreContentModal').find('.contentTypeSingle').html('list');
            //--------------------//
            let lists = $('#mailboxes input.siteContentCheck[data-type="list"]:checked');
            let listsCount = lists.length;
            let tableData = [];
            //--------------------//
            lists.each(function() {
                var folderCheck = $(this);
                var list = folderCheck.parents('li.mainItem').find('input.mailBoxCheck');
                tableData.push({
                    "id": $(this).val(),
                    "name": folderCheck.closest('.mailboxfolder').find('.item-click').html(),
                    "siteId": list.val(),
                    "siteTitle": $('#' + list.val()).html()
                });
            });
            $('#contentsTable_wrapper').find('.boxesCount').html(listsCount);
            $('#contentsTable').DataTable().clear().draw();
            $('#contentsTable').DataTable().rows.add(tableData); // Add new data
            $('#contentsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('contentsTable');
            adjustTable();
            $("#contentsTable").DataTable().draw();
            $('#restoreContentModal').find('.refreshDeviceCode').click();
            $('#restoreContentModal').modal('show');
        }
        //----------------------------------------------------//
        function exportLibrariesModal() {
            //--------------------//
            let libraries = $('#mailboxes input.siteContentCheck[data-type="library"]:checked');
            let librariesCount = libraries.length;
            let tableData = [];
            //--------------------//
            libraries.each(function() {
                var folderCheck = $(this);
                var library = folderCheck.parents('li.mainItem').find('input.mailBoxCheck');
                tableData.push({
                    "id": $(this).val(),
                    "name": folderCheck.closest('.mailboxfolder').find('.item-click').html(),
                    "siteId": library.val(),
                    "siteTitle": $('#' + library.val()).html()
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
        }
        //----------------------------------------------------//
        function exportSiteDocumentsModal() {
            //--------------------//
            let items = $('input.contentFolderItemCheck[data-isfolder="false"][data-type="library"]:checked');
            let tableData = [];
            let pointSiteId, folderTitle, contentTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                siteTitle = tr.find('.siteTitle').val();
                pointSiteId = tr.find('.siteId').val();
                folderTitle = tr.find('.folderTitle').val();
                contentTitle = tr.find('.contentTitle').val();
                tableData.push({
                    "id": $(this).val(),
                    "siteId": pointSiteId,
                    "siteTitle": siteTitle,
                    "contentTitle": contentTitle,
                    "folderTitle": folderTitle == "null" ? siteTitle : folderTitle,
                    "name": tr.find('.fileNameColumn').html(),
                });
            });
            let parentName = siteTitle + '-' + folderTitle;
            //--------------------//
            $('#exportDocsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#exportDocsResultsTable').DataTable().clear().draw();
            $('#exportDocsResultsTable').DataTable().rows.add(tableData); // Add new data
            $('#exportDocsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportDocsResultsTable');
            adjustTable();
            $("#exportDocsResultsTable").DataTable().draw();
            //--------------------//
            $('#exportSiteDocumentsModal').modal('show');
        }
        //----------------------------------------------------//
        function exportSiteItemsModal() {
            //--------------------//
            let items = $('input.contentFolderItemCheck[data-isfolder="false"][data-type="list"]:checked');
            let tableData = [];
            let pointSiteId, folderTitle, contentTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                siteTitle = tr.find('.siteTitle').val();
                pointSiteId = tr.find('.siteId').val();
                folderTitle = tr.find('.folderTitle').val();
                contentTitle = tr.find('.contentTitle').val();
                tableData.push({
                    "id": $(this).val(),
                    "siteId": pointSiteId,
                    "siteTitle": siteTitle,
                    "contentTitle": contentTitle,
                    "folderTitle": folderTitle == "null" ? siteTitle : folderTitle,
                    "name": tr.find('.fileNameColumn').html(),
                });
            });
            let parentName = siteTitle + '-' + folderTitle;
            //--------------------//
            $('#exportItemsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#exportItemsResultsTable').DataTable().clear().draw();
            $('#exportItemsResultsTable').DataTable().rows.add(tableData); // Add new data
            $('#exportItemsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportItemsResultsTable');
            adjustTable();
            $("#exportItemsResultsTable").DataTable().draw();
            //--------------------//
            $('#exportSiteItemsModal').modal('show');
        }
        //----------------------------------------------------//
        function exportFoldersModal() {
            //--------------------//
            let items = $('input.contentFolderItemCheck[data-isfolder="true"]:checked');
            let tableData = [];
            let pointSiteId, contentTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                siteTitle = tr.find('.siteTitle').val();
                pointSiteId = tr.find('.siteId').val();
                folderTitle = tr.find('.folderTitle').val();
                contentTitle = tr.find('.contentTitle').val();
                tableData.push({
                    "id": $(this).val(),
                    "siteId": pointSiteId,
                    "siteTitle": siteTitle,
                    "contentId": tr.find('.contentId').val(),
                    "contentTitle": contentTitle,
                    "name": tr.find('.fileNameColumn a').html(),
                });
            });
            //--------------------//
            $('#exportFoldersResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#exportFoldersResultsTable').DataTable().clear().draw();
            $('#exportFoldersResultsTable').DataTable().rows.add(tableData); // Add new data
            $('#exportFoldersResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportFoldersResultsTable');
            adjustTable();
            $("#exportFoldersResultsTable").DataTable().draw();
            //--------------------//
            $('#exportFoldersModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreSiteDocumentsModal() {
            //--------------------//
            let items = $('input.contentFolderItemCheck[data-isfolder="false"][data-type="library"]:checked');
            let tableData = [];
            let pointSiteId;
            let folderTitle;
            let contentTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                siteTitle = tr.find('.siteTitle').val();
                pointSiteId = tr.find('.siteId').val();
                folderTitle = tr.find('.folderTitle').val();
                contentTitle = tr.find('.contentTitle').val();
                tableData.push({
                    "id": $(this).val(),
                    "siteId": pointSiteId,
                    "siteTitle": siteTitle,
                    "contentId": tr.find('.contentId').val(),
                    "contentTitle": contentTitle,
                    "folderTitle": folderTitle == "null" ? siteTitle : folderTitle,
                    "name": tr.find('.fileNameColumn').html(),
                });
            });
            let parentName = siteTitle + '-' + contentTitle + '-' + folderTitle;
            //--------------------//
            $('#docsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#docsResultsTable').DataTable().clear().draw();
            $('#docsResultsTable').DataTable().rows.add(tableData); // Add new data
            $('#docsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('docsResultsTable');
            adjustTable();
            $("#docsResultsTable").DataTable().draw();
            //--------------------//
            $('#restoreSiteDocumentsModal').find('.refreshDeviceCode').click();
            $('#restoreSiteDocumentsModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreSiteItemsModal() {
            //--------------------//
            let items = $('input.contentFolderItemCheck[data-isfolder="false"][data-type="list"]:checked');
            let tableData = [];
            let pointSiteId;
            let folderTitle;
            let contentTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                siteTitle = tr.find('.siteTitle').val();
                pointSiteId = tr.find('.siteId').val();
                folderTitle = tr.find('.folderTitle').val();
                contentTitle = tr.find('.contentTitle').val();
                tableData.push({
                    "id": $(this).val(),
                    "siteId": pointSiteId,
                    "siteTitle": siteTitle,
                    "contentId": tr.find('.contentId').val(),
                    "contentTitle": contentTitle,
                    "folderTitle": folderTitle == "null" ? siteTitle : folderTitle,
                    "name": tr.find('.fileNameColumn').html(),
                });
            });
            let parentName = siteTitle + '-' + contentTitle + '-' + folderTitle;
            //--------------------//
            $('#itemsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#itemsResultsTable').DataTable().clear().draw();
            $('#itemsResultsTable').DataTable().rows.add(tableData); // Add new data
            $('#itemsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('itemsResultsTable');
            adjustTable();
            $("#itemsResultsTable").DataTable().draw();
            //--------------------//
            $('#restoreSiteItemsModal').find('.refreshDeviceCode').click();
            $('#restoreSiteItemsModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreFoldersModal() {
            //--------------------//
            let items = $('input.contentFolderItemCheck[data-isfolder="true"]:checked');
            let tableData = [];
            let pointSiteId;
            let folderTitle;
            let contentTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                siteTitle = tr.find('.siteTitle').val();
                pointSiteId = tr.find('.siteId').val();
                folderTitle = tr.find('.folderTitle').val();
                contentTitle = tr.find('.contentTitle').val();
                tableData.push({
                    "id": $(this).val(),
                    "siteId": pointSiteId,
                    "siteTitle": siteTitle,
                    "contentId": tr.find('.contentId').val(),
                    "contentTitle": contentTitle,
                    "name": tr.find('.fileNameColumn a').html(),
                });
            });
            //--------------------//
            $('#foldersResultTable_wrapper').find('.boxesCount').html(items.length);
            $('#foldersResultTable').DataTable().clear().draw();
            $('#foldersResultTable').DataTable().rows.add(tableData); // Add new data
            $('#foldersResultTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('foldersResultTable');
            adjustTable();
            $("#foldersResultTable").DataTable().draw();
            //--------------------//
            $('#restoreFoldersModal').find('.refreshDeviceCode').click();
            $('#restoreFoldersModal').modal('show');
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
        function checkResolvedSite(val) {
            return ($('.users option[data-url="' + val + '"]').length == 0);
        }
        //---------------------------------------------------//

        //---- Global Variables Function
        function getSite() {
            return siteId;
        }
        //---------------------------------------------------//
        function getContent() {
            return contentId;
        }
        //---------------------------------------------------//
        function getContentType() {
            return contentType;
        }
        //---------------------------------------------------//
        function getSiteFolder() {
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
            let item = tr.find('input.contentFolderItemCheck').val();
            let pointSiteId = tr.find('.siteId').val();
            let libraryTitle = tr.find('.contentTitle').val();
            let siteTitle = tr.find('.siteTitle').val();
            let fileSize = tr.find('.fileSizeColumn').html();
            let contentType = tr.find('input.contentFolderItemCheck').attr('data-type');
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('downloadSiteDocument') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    jobId: $('.backupTime option:selected').attr('data-job-id'),
                    jobTime: $('.backupTime').val(),
                    showDeleted: $("#showDeleted")[0].checked,
                    showVersions: $("#showVersions")[0].checked,
                    siteId: pointSiteId,
                    libraryTitle: libraryTitle,
                    fileSize: fileSize,
                    documentId: item,
                    name: tr.find('.fileNameColumn').html(),
                    siteTitle: siteTitle,
                    contentType: contentType,
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
        //---------------------------------------------------//
        function downloadSingleItem() {
            let tr = $('tr.current');
            let item = tr.find('input.contentFolderItemCheck').val();
            let pointSiteId = tr.find('.siteId').val();
            let listTitle = tr.find('.contentTitle').val();
            let siteTitle = tr.find('.siteTitle').val();
            let contentType = tr.find('input.contentFolderItemCheck').attr('data-type');
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('downloadSiteItem') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    jobId: $('.backupTime option:selected').attr('data-job-id'),
                    jobTime: $('.backupTime').val(),
                    showDeleted: $("#showDeleted")[0].checked,
                    showVersions: $("#showVersions")[0].checked,
                    siteId: pointSiteId,
                    listTitle: listTitle,
                    documentId: item,
                    name: tr.find('.fileNameColumn').html(),
                    siteTitle: siteTitle,
                    contentType: contentType,
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
        //---------------------------------------------------//
        function downloadDocument() {
            let items = $('input.contentFolderItemCheck:checked');
            let itemsArr = [];
            let boxId = '';
            let folderTitle = '';
            items.each(function() {
                var tr = $(this).closest('tr');
                boxId = tr.find('.siteId').val();
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
                    siteId: boxId,
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
        function loadItems() {
            $.ajax({
                type: "POST",
                url: "{{ url('getSiteContentItems') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    offset: getTotalItems(),
                    type: getContentType(),
                    siteId: getSite(),
                    siteTitle: $('#' + getSite()).html(),
                    contentId: getContent(),
                    contentTitle: $('#' + getContent()).html(),
                    folderId: getSiteFolder(),
                    folderTitle: $('#' + getSiteFolder()).html(),
                },
                success: function(data) {},
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
            }).then(function(data) {
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
                        $('.countingLabel').html(getTotalItems() + getTotalFolders() + ' Items Shown').removeClass(
                            'hide');
                    } else {
                        $('.searchingLabel').addClass('hide');
                        $('.stopLoad').addClass('hide');
                        $('.resumeLoad').removeClass('hide');
                        $('.moveToEDiscovery').addClass('mr-2 ml-2');
                    }
                } else {
                    $('.searchingLabel').addClass('hide');
                    $('.stopLoad').addClass('hide');
                    $('.resumeLoad').addClass('hide');
                    $('.moveToEDiscovery').removeClass('mr-2 ml-2');
                    isDone = true;
                }
            });
        }
        //---------------------------------------------------//
        function createSession(event) {
            event.preventDefault()
            siteId = -1;
            folderId = -1;
            fullPath = [];
            showFolderPath();
            $(document).find('.dataTables_filter input').val("");
            $('#itemsTable').DataTable().search("").ajax.reload();

            siteCheckChange();
            $('.contentFolderItemCheck,.contentFolderCheck').change(folderTableChange);
            siteContentCheckChange();
            contentFolderCheckChange();
            siteFolderItemsCheckChange();
            $('.warningRow,.stoppingRow,.moveToEDiscovery').addClass('hide');
            if ($(".backupTime").find(":selected").val() != "") {
                $(".spinner_parent").css("display", "block");
                $('body').addClass('removeScroll');
                $.ajax({
                    type: "POST",
                    url: "{{ url('createSharepointSession') }}",
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
                        $(".moveToEDiscovery").removeClass("hide");
                        $('#activeclose').removeAttr('disabled');
                        $("#mailboxes").html("");
                        $(".spinner_parent").css("display", "none");
                        $('body').removeClass('removeScroll');
                        $("#choose").text("Change")
                        data.data.forEach(function(result) {
                            mainItem =
                                '<li class="has mainItem hand relative siteCont"><div class="relative allWidth">' +
                                '<span class="caret mailCaret closeMail" onclick="getSiteDetails(event)"></span>' +
                                '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                                '<input type="checkbox" class="mailBoxCheck form-check-input" value="' +
                                result.id + '" data-url="' + result.url + '"/>' +
                                '<span class="tree-checkBox check-mark"></span></label>' +
                                '<span id="' + result.id +
                                '" class="item-click left-mail-click ml-27" title="' +
                                result
                                .name +
                                '" onclick="getSiteDetails(event)" data-toggle="popover" data-url="' +
                                result.url + '">' +
                                result.name +
                                '</span></div>' +
                                (getSiteHtml(result.id, result.hasSubsites, JSON.stringify(result
                                    .children))) +
                                '</li>';
                            $("#mailboxes").append($(mainItem));
                        });
                        $("#rdate").html($(".backupDate").val());
                        $("#rtime").html($(".backupTime").find(":selected")
                            .text());
                        $('#jobsModal').modal('hide');
                        //------------------------------------------//
                        $('.tree .mailBoxCheck').change(function() {
                            if ($(this).prop("checked")) {
                                $('.tree .mailBoxCheck[value!="' + $(this).val() + '"]').prop("checked",
                                    false);
                            }
                            siteCheckChange();
                        });
                        //------------------------------------------//
                        var delay = 1000,
                            setTimeoutConst;
                        $('[data-toggle="popover"]').popover({
                            container: 'body',
                            trigger: 'manual',
                            content: function() {
                                return '<div class="flex"><span></span><span class="ellipsis" title="' +
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
        function getSiteHtml(parentId, hasSubsites = false, children) {
            return '' +
                '<ul class="pt-0 pb-0 block mb-0">' +
                '<li class="subsites relative">' +
                '<div class="relative allWidth inline-flex drive-folder-padding">' +
                (hasSubsites ?
                    '<span class="caret mailCaret closeMail drive-arrow-margin" onclick="getSiteSubsites(event)"></span>' :
                    '') +
                '<img class="folderIcon ml--3" src="/svg/folders/none.svg">' +
                '<span onclick="getSiteSubsites(event)" data-siteId="' + parentId +
                '" class="subsitesFolder item-folder-click" title="Subsites">Subsites</span>' +
                '</div>' +
                (hasSubsites ? '<ul class="pt-0 pb-0 mb-0">' + getSiteSubsitesHtml(children) + '</ul>' : '') +
                '</li>' +
                '<li class="subsites relative">' +
                '<div class="relative allWidth inline-flex drive-folder-padding">' +
                '<span class="caret mailCaret closeMail drive-arrow-margin" onclick="getSiteContent(event)"></span>' +
                '<img class="folderIcon ml--3" src="/svg/folders/none.svg">' +
                '<span onclick="getSiteContent(event)" data-siteId="' + parentId +
                '" class="item-click childmail-click contentFolder item-folder-click" title="Content">Content</span>' +
                '</div>' +
                '<div class="folder-spinner hide"></div>' +
                '</li>' +
                '</ul>';
        }
        //---------------------------------------------------//
        function getSiteContent(event) {
            $target = $(event.target);
            //--------------------------------------------//
            $target.unbind("click");
            //--------------------------------------------//
            if ($target.hasClass('mailCaret')) {
                siteId = $target.closest('.subsites').find('.contentFolder').attr('data-siteId');
            } else {
                siteId = $target.attr('data-siteId');
            }
            //--------------------------------------------//
            $('.contentFolder[data-siteId="' + siteId + '"]').closest('.subsites').find('.mailCaret').toggleClass(
                'closeMail');
            if ($('.contentFolder[data-siteId="' + siteId + '"]').closest('.subsites').find('ul').length) {
                $('.contentFolder[data-siteId="' + siteId + '"]').closest('.subsites').find('ul:first').fadeToggle();
                return;
            }
            //--------------------------------------------//
            $('.contentFolder[data-siteId="' + siteId + '"]').closest('.subsites').find('.folder-spinner:first')
                .removeClass('hide');
            //--------------------------------------------//
            $.ajax({
                type: "GET",
                url: "{{ url('getSiteContent') }}/" + siteId,
                data: {},
                success: function(data) {
                    let siteContent = "<ul class='pt-0 pb-0 mb-0'>";
                    data.forEach(function(result) {
                        siteId = result.siteId;
                        let siteName = $("#" + result.siteId).html();
                        siteContent = siteContent +
                            '<li class="mailboxfolder"><div class="relative allWidth inline-flex">' +
                            '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                            '<input type="checkbox" data-type="' + result.type +
                            '" class="siteContentCheck form-check-input" value="' +
                            result.id + '" data-siteId="' + siteId + '" data-siteName="' + siteName +
                            '"/>' +
                            '<span class="tree-checkBox check-mark"></span></label>' +
                            '<img class="folderIcon" src="/svg/folders/' + (result.type == "list" ?
                                'tasks' : 'none') + '.svg">' +
                            '<span id="' + result.id +
                            '" onclick="getContentItems(event)" data-type="' + result.type +
                            '" data-siteId="' + siteId +
                            '" class="item-click childmail-click item-folder-click" title="' + result
                            .url + '">' +
                            result.name + '</span></div></li>';
                    });
                    siteContent = siteContent + "</ul>";
                    //-------------//
                    $('.contentFolder[data-siteId="' + siteId + '"]').closest('.subsites').find(
                        '.folder-spinner:first').addClass('hide');
                    //-------------//
                    $('.contentFolder[data-siteId="' + siteId + '"]').closest('.subsites').append($(
                        siteContent)[0]);
                    $('.contentFolder[data-siteId="' + siteId + '"]').closest('.subsites').find('ul:first')
                        .fadeToggle();
                    //-------------//
                    $('.siteContentCheck').change(function() {
                        // Allow Single Site Content
                        siteContentCheckChange($(this).attr("data-siteId"), $(this));
                    });
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
            //--------------------------------------------//
        }
        //---------------------------------------------------//
        function getSiteSubsites(event) {
            $target = $(event.target);
            if ($target.hasClass('mailCaret')) {
                siteId = $target.closest('.subsites').find('.subsitesFolder').attr('data-siteId');
            } else {
                siteId = $target.attr('data-siteId');
            }
            let subsiteFolder = $("#" + siteId).closest(".siteCont").find('.subsitesFolder:first');
            subsiteFolder.closest('.subsites').find('.mailCaret:first').toggleClass('closeMail');
            subsiteFolder.closest('.subsites').find('ul:first').fadeToggle();
            return;
        }
        //---------------------------------------------------//
        function getSiteSubsitesHtml(children) {
            let data = JSON.parse(children);
            let str = '';
            data.forEach(function(result) {
                str +=
                    '<li class="has mainItem hand relative siteCont"><div class="relative allWidth">' +
                    '<span class="caret mailCaret closeMail" onclick="getSiteDetails(event)"></span>' +
                    '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                    '<input type="checkbox" class="mailBoxCheck form-check-input" value="' +
                    result.id + '" data-url="' + result.url + '"/>' +
                    '<span class="tree-checkBox check-mark"></span></label>' +
                    '<span id="' + result.id +
                    '" class="item-click left-mail-click ml-27" title="' + result.url +
                    '" onclick="getSiteDetails(event)">' +
                    result.name +
                    '</span></div><div class="folder-spinner hide"></div>' +
                    (getSiteHtml(result.id, result.hasSubsites, JSON.stringify(result.children))) +
                    '</li>';
            });
            return str;
        }
        //---------------------------------------------------//
        function resetFilterTable() {
            $('.filter-form').find('.sortBoxType').val('');
            $('.filter-table td .active').removeClass('active');
            getFilteredSites();
        }
        //---------------------------------------------------//
        function getFilteredSites(event) {
            if (event) event.preventDefault();
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
                url: "{{ url('getFilteredSites') }}",
                data: data + '&letters=' + lettersArr.join(','),
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    $("#mailboxes").html('');
                    res.forEach(function(result) {
                        mainItem =
                            '<li class="has mainItem hand relative siteCont"><div class="relative allWidth">' +
                            '<span class="caret mailCaret closeMail" onclick="getSiteDetails(event)"></span>' +
                            '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                            '<input type="checkbox" class="mailBoxCheck form-check-input" value="' +
                            result.id + '" data-url="' + result.url + '"/>' +
                            '<span class="tree-checkBox check-mark"></span></label>' +
                            '<span id="' + result.id +
                            '" class="item-click left-mail-click ml-27" title="' +
                            result.url +
                            '" onclick="getSiteDetails(event)">' +
                            result.name +
                            '</span></div>' +
                            (getSiteHtml(result.id, result.hasSubsites, JSON.stringify(result
                                .children))) +
                            '</li>';
                        $("#mailboxes").append($(mainItem));
                    });
                    $('.filter-icon').click();
                    $('.mailBoxCheck').change(siteCheckChange);
                    siteCheckChange();
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
        //---------------------------------------------------//
        function getSiteDetails(event) {
            $target = $(event.target);
            if ($target.hasClass('mailCaret')) {
                siteId = $target.closest('.siteCont').find('.item-click').attr('id');
            } else {
                siteId = $target.attr('id');
            }
            $("#" + siteId).closest("div").find('.mailCaret').toggleClass('closeMail');
            if ($("#" + siteId).closest(".siteCont").find('ul').length) {
                $("#" + siteId).closest(".siteCont").find('ul:first').fadeToggle();
                return;
            }
        }
        //---------------------------------------------------//



        //---------------------------------------------------//
        function restoreSite() {
            event.preventDefault();
            if ($("#changedItems")[0].checked || $("#deletedItems")[0].checked) {
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
                    data: data + '&' +
                        "_token={{ csrf_token() }}&" +
                        "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                        "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                        "&jobTime=" + $('.backupTime').val() +
                        "&showDeleted=" + $("#showDeleted")[0].checked +
                        "&showVersions=" + $("#showVersions")[0].checked +
                        "&sites=" + JSON.stringify(sitesArr),
                    success: function(data) {
                        $(".spinner_parent").css("display", "none");
                        $('body').removeClass('removeScroll');
                        showSuccessMessage(data.message);
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


            // return false;
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
                    data: data + '&' +
                        "_token={{ csrf_token() }}&" +
                        "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                        "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                        "&jobTime=" + $('.backupTime').val() +
                        "&showDeleted=" + $("#showDeleted")[0].checked +
                        "&showVersions=" + $("#showVersions")[0].checked +
                        "&content=" + JSON.stringify(contentsArr),
                    success: function(data) {
                        $(".spinner_parent").css("display", "none");
                        $('body').removeClass('removeScroll');
                        showSuccessMessage(data.message);
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
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportSiteLibraries') }}",
                data: "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobTime=" + $('.backupTime').val() +
                    "&restoreJobName=" + $("#exportLibrariesForm [name='restoreJobName']").val() +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&libraries=" + JSON.stringify(librariesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
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
            data = "docs=" + JSON.stringify(docsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportSiteDocuments') }}",
                data: data +
                    "&_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&restoreJobName=" + $("#exportSiteDocumentsForm [name='restoreJobName']").val() +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked,
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
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
            data = "docs=" + JSON.stringify(docsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportSiteItems') }}",
                data: data +
                    "&_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&restoreJobName=" + $("#exportSiteItemsForm [name='restoreJobName']").val() +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked,
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
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
            var data = "folders=" + JSON.stringify(foldersArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportSiteFolders') }}",
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&restoreJobName=" + $("#exportFoldersForm [name='restoreJobName']").val() +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked,
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
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
        //---------------------------------------------------//
    </script>
@endsection
