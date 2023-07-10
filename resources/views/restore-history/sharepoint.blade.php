@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/main.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/repositories.css') }}" />
<link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/generalElement.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/restore.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/restore-customize.css') }}" />

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
                    <div id="search-modal-id" class="modalContent">
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        <div class="row" style="margin-bottom: 15px">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p">Search</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-form-70 mb-1 inline-flex">
                                <div class="input-form-70 mb-1">Job Name: </div>
                                <div class="input-form-70 mb-1 ml-30">Job Type: </div>
                            </div>
                            <div class="input-form-70 inline-flex">
                                <div class="mr-25" style="position: relative">
                                    <input type="text" class="form_input form-control custom-form-control font-size"
                                        id="job_name" placeholder="" />
                                </div>

                                <div class="relative">
                                    <input type="text" class="form_input form-control custom-form-control font-size"
                                        id="job_type" placeholder="" />
                                </div>
                            </div>
                        </div>

                        <div class="row" id="duration-Section">
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
                                <div class="relative" style="width: 60%">
                                    <label style="padding-top: 5px; left: 0px"
                                        class="checkbox-container checkbox-search">&nbsp;
                                        <input id="successCheckBox" type="checkbox" class="form-check-input" />
                                        <span style="width: 15px!important; height: 15px!important; top: -5px!important"
                                            class="check-mark"></span>
                                    </label>
                                    <span class="ml-25">Success</span>
                                </div>

                                <div class="halfWidth relative">
                                    <label style="padding-top: 5px; left: 0px"
                                        class="checkbox-container checkbox-search">&nbsp;
                                        <input id="failedCheboxBox" type="checkbox" class="form-check-input" />
                                        <span style="width: 15px!important; height: 15px!important; top: -5px!important"
                                            class="check-mark"></span>
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
        <div id="exportSiteItemsModal" class="modal modal-center" role="dialog">
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
        <div id="exportedFilesModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg" style="width: 600px!important; margin:20vh auto;">
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
                                        <div class="col-lg-12 customBorder h-320p pt-3 pb-3">
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
                                                    <div class="pr-o pl-4 mt-2">
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
                                                                <input id="changedItems" name="changedItems"
                                                                    type="checkbox" class="form-check-input">
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
                                                                <input id="deletedItems" name="deletedItems"
                                                                    type="checkbox" class="form-check-input">
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
                                                                <input name="sendSharedLinksNotification"
                                                                    type="checkbox" class="form-check-input">
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
                                                                <input name="sendSharedLinksNotification"
                                                                    type="checkbox" class="form-check-input">
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
        <div class="row">&nbsp;</div>
        <div>
            @if ($role->hasPermissionTo('sharepoint_create_restore_session'))
                <a href="/restore/sharepoint">
                    <button class="custom-back-button btn_primary_state custom-back-btn left-float">
                        New SharePoint Restore
                    </button>
                </a>
            @endif
        </div>
        <!-- All History table -->
        <div class="row">
            <div class="repositoryTable">
                <table id="historyTable" class="stripe table nowrap table-striped table-dark" style="width:100%">
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
        let loadingSites = false;
        $(document).ready(function() {
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
                        return json;
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
                        else if (!$(this).hasClass('hasHTML') && $(this).children().length == 0)
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
                                @if ($role->hasPermissionTo('sharepoint_view_history_details'))
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
                        text: '<img src="/svg/excel.svg" style="width:15px;height:30px;">',
                        titleAttr: 'Export to csv',
                        exportOptions: {
                            columns: 'thead th:not(.noExport)',
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<img src="/svg/pdf.svg" style="width:15px;height:30px;">',
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
                        text: '<img src="/svg/filter.svg" style="width:15px;height:30px;">',
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
            $('#exportedFilesTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('hasHTML') && $(this).children().length == 0)
                            $(this).attr('title', $(this).html());
                    });
                },
                "order": [
                    [0, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": "fileName",
                        "width": "80%"
                    },
                    {
                        "data": null,
                        "class": "hasHTML downloadIMG",
                        "width": "20%",
                        render: function(data) {
                            @if ($role->hasPermissionTo('sharepoint_download_exported_files'))
                                if (data.fileName)
                                    return '<img data-id="' + data.id +
                                        '" class="tableIcone hand downloadExportedFile" style="width: 13px; margin-right:0;" src="/svg/download\.svg " title="Download">';
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
                "scrollY": "72px",
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

            $('input[name="siteType"]').change();

            $('input[name="listType"]').change(function() {
                let form = $(this).closest('form');
                if ($(this).val() == 'original') {
                    form.find('input[name="list"]').attr('disabled', 'disabled').val('');
                } else {
                    form.find('input[name="list"]').removeAttr('disabled');
                }
            });
            $('input[name="listType"]').change();

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
                dateFormt: 'dd/mm/yy',
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
        });

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

        function getMenu(data) {
            var temp = data.sub_type;
            var isRestore = (temp.match(/Restore/g) || []).length;
            //-----------------------//
            let buttons = '';
            //-----------------------//
            if (data.status == 'In Progress') {
                buttons += '<img class="tableIcone hand cancelJob ml-2" style="width: 13px; margin-right:0;" data-id="' +
                    data.id + '" src="/svg/cancel\.svg " title="Cancel Job">';
            }
            //-----------------------//
            if (isRestore == 0 && data.status != 'Expired' && data.status != 'In Progress' && data.status != 'Canceled') {
                buttons +=
                    '<img class="tableIcone hand exportedFiles ml-2" style="width: 13px; margin-right:0;" data-id="' + data
                    .id + '" src="/svg/details\.svg" title="Exported Files">';
            }
            //-----------------------//
            @if ($role->hasPermissionTo('onedrive_export_again'))
                if (isRestore == 0 && $.inArray(data.status, ['Canceled', 'Expired', 'Failed', 'Waiting', 'Success']) != -
                    1) {
                    buttons += '<img data-type="' + data.type + '" data-sub-type="' + data.sub_type +
                        '" class="tableIcone hand restoreAgain ml-2" style="width: 13px; margin-right:0;" data-id="' + data
                        .id +
                        '" src="/svg/restore_again\.svg " title="Restore Again">';
                }
                if (isRestore == 0 && data.status == 'Failed' && !checkFiledAll(data)) {
                    buttons += '<img data-type="' + data.type + '" data-sub-type="' + data.sub_type +
                        '" class="tableIcone hand restoreFailedAgain ml-2" style="width: 13px; margin-right:0;" data-id="' +
                        data.id +
                        '" src="/svg/restore_failed\.svg " title="Restore Failed Again">';
                }
            @endif
            @if ($role->hasPermissionTo('onedrive_restore_again'))
                if (isRestore > 0 && $.inArray(data.status, ['Failed', 'Waiting', 'Canceled', 'Success']) != -1) {
                    buttons += '<img data-type="' + data.type + '" data-sub-type="' + data.sub_type +
                        '" class="tableIcone hand restoreAgain ml-2" style="width: 13px; margin-right:0;" data-id="' + data
                        .id +
                        '" src="/svg/restore_again\.svg " title="Restore Again">';
                }
                if (isRestore > 0 && data.status == 'Failed' && !checkFiledAll(data)) {
                    buttons += '<img data-type="' + data.type + '" data-sub-type="' + data.sub_type +
                        '" class="tableIcone hand restoreFailedAgain ml-2" style="width: 13px; margin-right:0;" data-id="' +
                        data.id +
                        '" src="/svg/restore_failed\.svg " title="Restore Failed Again">';
                }
            @endif
            //-----------------------//
            @if ($role->hasPermissionTo('sharepoint_force_expire'))
                if ($.inArray(data.status, ['In Progress', 'Canceled', 'Expired']) == -1 && isRestore == 0) {
                    buttons +=
                        '<img class="tableIcone hand expireJob ml-2" style="width: 13px; margin-right:0;" data-id="' +
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
                '<img class= "tableIcone"  style="margin-left:0px;width: 13px; margin-right:0;" src="/svg/history.svg " title="History">';
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
                url: "{{ url('forceExpire') }}/sharepoint",
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
                url: "{{ url('downloadExportedFile') }}/sharepoint/" + id,
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
        //---------------------------------------------------//
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
        }
        //----------------------------------------------------//
        function siteFolderItemsCheckChange() {
            var itemsLength = $('.contentFolderItemCheck:checked[data-isFolder="false"][data-type="list"]').length;
            var docsLength = $('.contentFolderItemCheck:checked[data-isFolder="false"][data-type="library"]').length;
            if (itemsLength == 0) {
                $('.siteItemsButton button').attr('disabled', 'disabled');
                $('.siteItemsButton .selectedItemCount').html('');
            } else {
                $('.siteItemsButton button').removeAttr('disabled');
                $('.siteItemsButton .selectedItemCount').html('(' + itemsLength + ')');
            }

            if (docsLength == 0) {
                $('.siteDocumentButton button').attr('disabled', 'disabled');
                $('.siteDocumentButton .selectedItemCount').html('');
            } else {
                $('.siteDocumentButton button').removeAttr('disabled');
                $('.siteDocumentButton .selectedItemCount').html('(' + docsLength + ')');
            }
            $('.contentFolderItemCheck,.contentFolderCheck').change(folderTableChange);
            contentFolderCheckChange();
            siteContentCheckChange();
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
                if (!e.exported_file_size) {
                    $('#exportedFilesTable').DataTable().columns(1).visible(false);
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
                    //--------------------------------//
                    jobType = data.restore_point_type;
                    jobTime = data.restore_point_time;
                    showDeleted = data.is_restore_point_show_deleted == 1;
                    showVersions = data.is_restore_point_show_version == 1;
                    jobId = data.backup_job_id;
                    //--------------------------------//
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
                        options = JSON.parse(data.options);
                        tableData = [];
                        //--------------------//
                        let items = data.details;
                        items.forEach(function(e) {
                            let siteFolders = JSON.parse(e.item_id);
                            siteFolders.forEach(function(e1) {
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
                        $('#restoreFoldersModal').find('.refreshDeviceCode').click();
                        $('#restoreFoldersModal').modal('show');
                        //--------------------//
                        $.each(options, function(key, value) {
                            if (key == "list") {
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
                        options = JSON.parse(data.options);
                        tableData = [];
                        //--------------------//
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
                        options = JSON.parse(data.options);
                        let items = data.details;
                        items.forEach(function(e) {
                            let siteItems = JSON.parse(e.item_id);
                            siteItems.forEach(function(e1) {
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
                url: "{{ url('getHistoryDetails', $data['repo_kind']) }}/" + id,
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
                    //--------------------------------//
                    $('.jobId').val(data.backup_job_id);
                    $('.jobType').val(data.restore_point_type);
                    $('.jobTime').val(data.restore_point_time);
                    $('.showDeleted').val(data.is_restore_point_show_deleted == 1);
                    $('.showVersions').val(data.is_restore_point_show_version == 1);
                    if (type == "library") {
                        options = JSON.parse(data.options);
                        let libraries = data.details;
                        tableData = [];
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
                        $('#exportLibrariesModal').find('.refreshDeviceCode').click();
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
                        //--------------------//
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
                        options = JSON.parse(data.options);
                        tableData = [];
                        //--------------------//
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



        //---- Ajax Functions
        //----------------------------------------------------//
        function restoreSite() {
            event.preventDefault()
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
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}",
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
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}",
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
                    conditionsArray.push("failed");
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
