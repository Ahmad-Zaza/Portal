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
                                <div class="relative" style="width: 60%">
                                    <label style="padding-top: 5px; left: 0px"
                                        class="checkbox-search checkbox-container">&nbsp;
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
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
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
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
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
                                                                <span class="boxesCount"></span> channels selected
                                                                <span class="unresolvedCount"></span>
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
                                                            <input type="radio" name="pointType" class="pointType"
                                                                checked value="custom">Restore Posts for The Specified Time
                                                            Period:
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
                                                    class="form_input form-control date custom-form-control font-size"
                                                    required name="restoreTo" placeholder="To" />
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
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
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
                                                                <span class="boxesCount"></span> channels selected
                                                                <span class="unresolvedCount"></span>
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
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
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
                                                                <span class="boxesCount"></span> channels selected
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
                                            <div>
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
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
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
                                                                <span class="boxesCount"></span> channels selected
                                                                <span class="unresolvedCount"></span>
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
                                                placeholder="Job Name" name="restoreJobName" required
                                                autocomplete="off" />
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
                                                placeholder="Job Name" name="restoreJobName" required
                                                autocomplete="off" />
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
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
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
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
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
        <div class="row">&nbsp;</div>
        <div>
            <a href="/restore/teams">
                @if ($role->hasPermissionTo('teams_create_restore_session'))
                    <button class="custom-back-button btn_primary_state custom-back-btn left-float">
                        New Teams Restore
                    </button>
                @endif
            </a>
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
                                @if ($role->hasPermissionTo('teams_view_history_details'))
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

            //--------------------------------------------------//
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
                            @if ($role->hasPermissionTo('teams_download_exported_files'))
                                if (data.fileName)
                                    return '<img data-id="' + data.id +
                                        '" class="tableIcone hand downloadExportedFile"' +
                                        ' style="width: 13px; margin-right:0;" src="/svg/download\.svg"' +
                                        ' title="Download">';
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
            //--------------------------------------------------//
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
                "scrollY": "220px",
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

            //--------------------------------------------------//
            $('#historyTable').DataTable().buttons().container()
                .prependTo('#historyTable_filter');
            $("#sizeFrom").change(function() {
                $('#historyTable').DataTable().draw();
            });
            //--------------------------------------------------//
            $('input[name="siteType"]').change(function() {
                let form = $(this).closest('form');
                if ($(this).val() == 'original') {
                    form.find('input[name="alias"]').attr('disabled', 'disabled').val('');
                } else {
                    form.find('input[name="alias"]').removeAttr('disabled');
                }
            });
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
            //--------------------------------------------------//
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
        });

        function getMenu(data) {
            var temp = data.sub_type;
            var isRestore = (temp.match(/Restore/g) || []).length;
            //-----------------------//
            let buttons = '';
            //-----------------------//
            if (isRestore == 0 && data.status != 'Expired' && data.status != 'In Progress' && data.status != 'Canceled') {
                buttons +=
                    '<img class="tableIcone hand exportedFiles ml-2" style="width: 13px; margin-right:0;" data-id="' + data
                    .id + '" src="/svg/details\.svg" title="Exported Files">';
            }
            //-----------------------//

            @if ($role->hasPermissionTo('teams_restore_again'))
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
            @if ($role->hasPermissionTo('teams_export_again'))
                if (isRestore == 0 && $.inArray(data.status, ['Canceled', 'Expired', 'Failed', 'Waiting']) != -1) {
                    buttons += '<img data-type="' + data.type + '" data-sub-type="' + data.sub_type +
                        '" class="tableIcone hand restoreAgain ml-2" style="width: 13px; margin-right:0;" data-id="' + data
                        .id +
                        '" src="/svg/restore_again\.svg " title="Export Again">';
                }
                if (isRestore == 0 && data.status == 'Failed' && !checkFiledAll(data)) {
                    buttons += '<img data-type="' + data.type + '" data-sub-type="' + data.sub_type +
                        '" class="tableIcone hand restoreFailedAgain ml-2" style="width: 13px; margin-right:0;" data-id="' +
                        data.id +
                        '" src="/svg/restore_failed\.svg " title="Export Failed Again">';
                }
            @endif
            //-----------------------//
            @if ($role->hasPermissionTo('teams_force_expire'))
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
                url: "{{ url('forceExpire') }}/teams",
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
                url: "{{ url('downloadExportedFile') }}/teams/" + id,
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
            });
            $('#exportedFilesTable').DataTable().clear().draw();
            $('#exportedFilesTable').DataTable().rows.add(data); // Add new data
            $('#exportedFilesTable').DataTable().columns.adjust().draw();
            $('#exportedFilesModal').modal('show');
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
                        $('#restoreFilesModal').find('.refreshDeviceCode').click();
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
                    console.log("error while getting data in ajax ===");
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
                    //-----------------------------//
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
                        //--------------------//
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
                        //--------------------//
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
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "channels=" + JSON.stringify(channelsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
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
            posts.each(function() {
                let tr = $(this).closest('tr');
                if (postsArr.filter(e => e.teamId === $(this).attr("data-teamId") && e.channelId === $(this).attr(
                        "data-channelId")).length == 0) {
                    let teamPosts = $('#exportPostsForm .mailboxCheck:checked[data-teamId="' + $(this).attr(
                        "data-teamId") + '"][data-channelId="' + $(this).attr("data-channelId") + '"]');
                    let teamPostsArr = [];
                    teamPosts.each(function() {
                        tr = $(this).closest('tr');
                        teamPostsArr.push({
                            "id": $(this).val(),
                            "teamName": tr.find('td:nth-child(4)').html(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "channelName": tr.find('td:nth-child(3)').html(),
                            "channelId": $(this).attr("data-channelid"),
                            "teamId": $(this).attr("data-teamId"),
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
        //---------------------------------------------------//
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
                    if (!data[1])
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
            $("#CompletionFrom").val();
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
