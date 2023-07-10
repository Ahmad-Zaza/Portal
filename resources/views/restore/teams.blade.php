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

        .after-none {
            width: 13px !important;
        }

        .selected-customBorder {
            height: 278px;
        }

        .selected-teams-customBorder {
            height: 300px;
        }

        .selected-channels-customBorder,
        .restore-option-customBorder {
            height: 325px;
        }

        .selected-channels-col-customBorder {
            height: 108px;
        }
    </style>
    <div class="col-sm-10 navbarLayout">
        <!-- Upper navbar -->
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm upperNavBar"> -->
        <ul class="ulNavbar">
            <li>
                <div class="col-sm-2 custom-col-sm-2">
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
                                        <span class="check-mark checkbox-span-class"></span>
                                    </label>
                                    <span class="ml-25">Show Items That Have Been Deleted By
                                        User</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-form-70">
                                    <label class="checkbox-container checkbox-padding-left">&nbsp;
                                        <input id="showVersions" type="checkbox" class="form-check-input">
                                        <span class="check-mark checkbox-span-class"></span>
                                    </label>
                                    <span class="ml-25">Show All Versions Of Items That Have
                                        Been Modified By User</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-form-70 inline-flex">
                                    <button id="activeapply" type="submit"
                                        class="btn_primary_state allWidth mr-25">Apply</button>
                                    <button id="activeclose" type="button" class="btn_cancel_primary_state  allWidth"
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
        <div id="postDetailsModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg w-600 mt-5v">
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
                            <div class="input-form-70 w-80">
                                <h4 class="per-req">Post Details
                                </h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 h-flow w-80">
                                <div class="col-lg-12">
                                    <div id="postContent"> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 inline-flex w-80">
                                <button type="button" class="btn_cancel_primary_state  allWidth"
                                    data-dismiss="modal">Close</button>
                            </div>
                        </div>
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
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="restoreChangedItems" type="checkbox"
                                                                class="form-check-input">
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Changed Items</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="relative allWidth mb-2">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="restoreMissingItems" type="checkbox"
                                                                class="form-check-input">
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Missing Items</span>
                                                    </div>
                                                </div>
                                                <div class="w-100"></div>
                                                <div class="col">
                                                    <div class="relative allWidth">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="restoreMembers" type="checkbox"
                                                                class="form-check-input">
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Members</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="relative allWidth">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="restoreSettings" type="checkbox"
                                                                class="form-check-input">
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Settings</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row">&nbsp;</div> --}}
                            </div>

                            <div class="custom-right-col">
                                <div class="row">
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Selected Teams</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder selected-teams-customBorder">
                                            <div class="allWidth">
                                                <table id="teamsTable"
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
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="restoreChangedItems" type="checkbox"
                                                                class="form-check-input">
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Changed Items</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="relative allWidth">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="restoreMissingItems" type="checkbox"
                                                                class="form-check-input">
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Missing Items</span>
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
                                        <div class="col-lg-12 customBorder selected-customBorder">
                                            <div class="allWidth">
                                                <table id="channelsTable"
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
                                        <div class="col-lg-12 customBorder h-325p">
                                            <div class="allWidth">
                                                <table id="channelsPostsTable"
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
                                        <div class="col-lg-12 customBorder selected-channels-col-customBorder">
                                            <div class="allWidth">
                                                <table id="channelsFilesTable"
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
                                        <div
                                            class="col-lg-12 customBorder restore-option-customBorder selected-customBorder pb-3 pt-3">
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
                                                            <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                <input name="restoreChangedItems" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="check-mark checkbox-span-class"></span>
                                                            </label>
                                                            <span class="ml-25">Changed Items</span>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                <input name="restoreMissingItems" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="check-mark checkbox-span-class"></span>
                                                            </label>
                                                            <span class="ml-25">Missing Items</span>
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
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="restoreChangedItems" type="checkbox"
                                                                class="form-check-input">
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Changed Items</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="relative allWidth">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="restoreMissingItems" type="checkbox"
                                                                class="form-check-input">
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Missing Items</span>
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
                                        <div class="col-lg-12 customBorder selected-customBorder h-215p">
                                            <div class="allWidth">
                                                <table id="channelsTabsTable"
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
                        {{-- <div class="row">&nbsp;</div> --}}

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
                        <div class="row mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p">Export Selected Channels Posts
                                </h4>
                            </div>
                        </div>
                        <form id="exportChannelsPostsForm" class="mb-0" onsubmit="exportChannelsPosts(event)">
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
                                            <table id="exportChannelsPostsTable"
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
                        <div class="row mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p">Export Selected Channels Files
                                </h4>
                            </div>
                        </div>
                        <form id="exportChannelsFilesForm" class="mb-0" onsubmit="exportChannelsFiles(event)">
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
                                                            <label
                                                                class="checkbox-container checkbox-search checkbox-top-left">
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
                                        <div class="col-lg-12 customBorder selected-customBorder pb-3 pt-3">
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
                                            <div class="flex pt-3">
                                                <label class="mr-4 m-0 nowrap">File Last Version Action:</label>
                                                <div class="radioDiv pb-10">
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
                                                <div class="row">
                                                    <div class="pr-0 pl-4">
                                                        <label>Restore the following items:</label>
                                                    </div>
                                                    <div class="w-100"></div>
                                                    <div class="col">
                                                        <div class="relative allWidth">
                                                            <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                <input name="restoreChangedItems" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="check-mark checkbox-span-class"></span>
                                                            </label>
                                                            <span class="ml-25">Changed Items</span>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="relative allWidth">
                                                            <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                <input name="restoreMissingItems" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="check-mark checkbox-span-class"></span>
                                                            </label>
                                                            <span class="ml-25">Missing Items</span>
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
                                                                <label
                                                                    class="checkbox-container checkbox-search checkbox-top-left">
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
                                <h4 class="per-req ml-2p">Export Selected Files
                                </h4>
                            </div>
                        </div>
                        <form id="exportFilesForm" class="mb-0" onsubmit="exportFiles(event)">
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
                                                            <label
                                                                class="checkbox-container checkbox-search checkbox-top-left">
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
                                <h4 class="per-req ml-2p">Export Selected Posts
                                </h4>
                            </div>
                        </div>
                        <form id="exportPostsForm" class="mb-0" onsubmit="exportPosts(event)">
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
                                                            <label
                                                                class="checkbox-container checkbox-search checkbox-top-left">
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
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="restoreChangedItems" type="checkbox"
                                                                class="form-check-input">
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Changed Items</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="relative allWidth">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="restoreMissingItems" type="checkbox"
                                                                class="form-check-input">
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Missing Items</span>
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
                                                                <label
                                                                    class="checkbox-container checkbox-search checkbox-top-left">
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
                                    <div class="dropdown-menu dropdown-menu-filter mainItem-filter-menu">
                                        <div class="divBorderRight"></div>
                                        <div class="divBorderBottom"></div>
                                        <div class="divBorderleft"></div>
                                        <div class="divBorderUp"></div>
                                        <form class="filter-form mb-0" onsubmit="getFilteredTeams(event)">
                                            <div class="filterCont flex allWidth pt-10">
                                                <div class="p-3 text-white ml-15">
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

                                                <div class="p-3 text-white mr-15">
                                                    <select name="privacyType" required
                                                        class="required privacyType boxType btn-sm dropdown-toggle form_dropDown form-control"
                                                        data-toggle="dropdown" value=":">
                                                        <option value="" selected="selected">Show</option><span
                                                            class="fa fa-caret-down"></span>
                                                        <option value="all">All
                                                        </option>
                                                        <option value="public">Public
                                                        </option>
                                                        <option value="private">Private
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="p-3 text-white">
                                                <label class="font-small pl-20" cellspa>Show Teams
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
                    if (!$role->hasAnyPermission('sharepoint_restore_actions', 'sharepoint_export_actions')) {
                        $permissionClass = 'hide';
                    }
                @endphp
                <div class="row main-button-cont {{ $permissionClass }}">
                    <div class="btnMain main-button flex">
                        <div class="btnUpMask"></div>
                        <div class="row m-0 pl-4 pr-4 allWidth">
                            @if ($role->hasPermissionTo('teams_restore_actions'))
                                <div class="col-lg-4 teamsButton">
                                    <div class="selected-action allWidth relative">
                                        <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                            data-toggle="dropdown" aria-expanded="false">
                                            Teams Actions
                                            <span class="selectedBoxCount"></span>
                                            <span class="fa fa-caret-down"></span></button>
                                        <ul class="dropdown-menu allWidth">
                                            <li>
                                                <a href="javascript:restoreTeamsModal(event)" class="tooltipSpan"
                                                    title="Restore Selected Teams">
                                                    Restore Selected Teams
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-4 channelButton">
                                <div class="selected-action allWidth relative">
                                    <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                        data-toggle="dropdown" aria-expanded="false">
                                        Channels Actions
                                        <span class="selectedFolderCount"></span>
                                        <span class="fa fa-caret-down"></span></button>
                                    <ul class="dropdown-menu allWidth">
                                        @if ($role->hasPermissionTo('teams_restore_actions'))
                                            <li>
                                                <a href="javascript:restoreChannelsModal(event)" class="tooltipSpan"
                                                    title="Restore Selected Channels">
                                                    Restore Selected Channels
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:restoreChannelsPostsModal(event)"
                                                    class="tooltipSpan" title="Restore Selected Channels Posts">
                                                    Restore Selected Channels Posts
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:restoreChannelsFilesModal(event)"
                                                    class="tooltipSpan" title="Restore Selected Channels Files">
                                                    Restore Selected Channels Files
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:restoreChannelsTabsModal(event)" class="tooltipSpan"
                                                    title="Restore Selected Channels Tabs">
                                                    Restore Selected Channels Tabs
                                                </a>
                                            </li>
                                        @endif
                                        @if ($role->hasPermissionTo('teams_export_actions'))
                                            <li>
                                                <a href="javascript:exportChannelsPostsModal(event)" class="tooltipSpan"
                                                    title="Export Selected Channels Posts to .Zip">
                                                    Export Selected Channels Posts to .Zip
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:exportChannelsFilesModal(event)" class="tooltipSpan"
                                                    title="Export Selected Channels Files to .Zip">
                                                    Export Selected Channels Files to .Zip
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4 tabsButton">
                                <div class="selected-action allWidth relative">
                                    <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                        data-toggle="dropdown" aria-expanded="false">
                                        Channel Content Actions
                                        <span class="selectedItemCount"></span>
                                        <span class="fa fa-caret-down"></span></button>

                                    <ul class="dropdown-menu allWidth">
                                        @if ($role->hasPermissionTo('teams_restore_actions'))
                                            <li class="filesLi">
                                                <a href="javascript:restoreFilesModal(event)" class="tooltipSpan"
                                                    title="Restore Selected Files">
                                                    Restore Selected Files
                                                </a>
                                            </li>
                                            <li class="tabsLi">
                                                <a href="javascript:restoreTabsModal(event)" class="tooltipSpan"
                                                    title="Restore Selected Tabs">
                                                    Restore Selected Tabs
                                                </a>
                                            </li>
                                        @endif
                                        @if ($role->hasPermissionTo('teams_export_actions'))
                                            <li class="postsLi">
                                                <a href="javascript:exportPostsModal(event)" class="tooltipSpan"
                                                    title="Export Selected Posts">
                                                    Export Selected Posts
                                                </a>
                                                {{-- <span class="tooltipSpan color-disabled pl-3 cursor-ban"
                                                title="(Comming Soon) Export Selected Posts to .Zip">
                                                (Comming Soon) Export Selected Posts to .Zip
                                            </span> --}}
                                            </li>
                                            <li class="filesLi">
                                                <a href="javascript:exportFilesModal(event)" class="tooltipSpan"
                                                    title="Export Selected Files to .Zip">
                                                    Export Selected Files to .Zip
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
                                        <th>Type</th>
                                        <th>Content Url</th>

                                        <th></th>
                                        <th></th>
                                        <th>Author</th>
                                        <th>Subject</th>
                                        <th>Created At</th>
                                        <th>Last Modified</th>
                                        <th></th>

                                        <th>Name</th>
                                        <th>Version</th>
                                        <th>Size</th>
                                        <th>Modified By</th>
                                        <th>Modified At</th>
                                        <th></th>

                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="table-content">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="8">
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

                        <div class="row posts-class">
                            <div class="input-form-70 mb-1">Author: <span class="ml-43">Subject:</span></div>
                            <div class="input-form-70 inline-flex">
                                <input type="text" class="form_input form-control mr-25 font-size" id="author"
                                    placeholder="" />
                                <input type="text" class="form_input form-control font-size" id="subject"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="row posts-class">
                            <div class="input-form-70 mb-1">Created At:</div>
                            <div class="input-form-70 inline-flex">
                                <input type="text" class="form_input form-control mr-25 font-size"
                                    id="createdAtFrom" placeholder="From" />
                                <input type="text" class="form_input form-control font-size" id="createdAtTo"
                                    placeholder="To" />
                            </div>
                        </div>
                        <div class="row files-class hide">
                            <div class="input-form-70 mb-1">Name: <span class="ml-43">Modified By:</span></div>
                            <div class="input-form-70 inline-flex">
                                <input type="text" class="form_input form-control mr-25 font-size" id="name"
                                    placeholder="" />
                                <input type="text" class="form_input form-control font-size" id="modifiedBy"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="row tabs-class hide">
                            <div class="input-form-70 mb-1">Name: <span class="ml-43">Type:</span></div>
                            <div class="input-form-70 inline-flex">
                                <input type="text" class="form_input form-control mr-25 font-size" id="tabsName"
                                    placeholder="" />
                                <input type="text" class="form_input form-control font-size" id="type"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="input-form-70 inline-flex">
                                <button type="button" onclick="ApplySearch()"
                                    class="btn_primary_state  allWidth mr-25">Apply</button>
                                <button type="button" class="btn_cancel_primary_state allWidth"
                                    onclick="resetSearch()" data-dismiss="modal">Reset</button>
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
        let teamId = '-1';
        let folderId = '-1';
        let channelId = '-1';
        let contentType = 'posts';
        let allowedDates = [];
        let globalItemsTable;
        let offset = 1;
        let fullPath = [];
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
                "url": "{{ url('getTeamChannelContent') }}",
                "dataSrc": '',
                "data": function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.offset = 0;
                    d.teamId = getTeam();
                    d.type = getContentType();
                    d.teamTitle = $('#' + getTeam()).html();
                    d.channelId = getChannel();
                    d.channelTitle = $('[id="' + getChannel() + '"]').html();
                    d.folderId = getFolder();
                    d.folderTitle = $('#' + getFolder()).html();
                },
                "beforeSend": function() {
                    teamCheckChange();
                    channelCheckChange();
                    contentFolderCheckChange();
                    if (getTeam() == "-1") {
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
                "dataType": "json",
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
            },
            "order": [
                [18, 'desc'],
                [2, 'asc']
            ],
            "createdRow": function(row, data, rowIndex) {
                $.each($('td', row), function(colIndex) {
                    if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
                        $(this).attr('title', $(this).html());
                    } else if ($(this).hasClass('hasHtml')) {
                        $(this).attr('title', $(this).find('a').html());
                    }
                });
            },
            'columns': [{
                    "data": null,
                    "class": "custom-after-none hasHtml",
                    "width": "15px",
                    render: function(data, type, full, meta) {
                        return '' +
                            '<label class="custom-teams-top-left checkbox-container checkbox-search">&nbsp;' +
                            '<input type="hidden" class="teamId" value="' + data.teamId +
                            '">' +
                            '<input type="hidden" class="teamTitle" value="' + data.teamTitle +
                            '">' +
                            '<input type="hidden" class="channelId" value="' + data.channelId +
                            '">' +
                            '<input type="hidden" class="channelTitle" value="' + data.channelTitle +
                            '">' +
                            '<input type="hidden" class="isFolder" value="' + data.isFolder +
                            '">' +
                            '<input type="hidden" class="folderId" value="' + data.folderId +
                            '">' +
                            '<input type="hidden" class="folderTitle" value="' + data.folderTitle + '">' +
                            '<input type="checkbox" data-type="' + data.type + '" data-isFolder="' + data
                            .isFolder + '" class="contentFolderItemCheck form-check-input" value="' +
                            data.id + '"/>' +
                            '<span class="tree-checkBox check-mark"></span></label>' +
                            (data.type == "post" ?
                                '<span class="caret mailCaret closeMail postCaret ml-10" onclick="getPostChildren(event)"></span>' :
                                '') +
                            '<span class="folder-spinner relative hide teams-m-b"></span>';
                    }
                },
                {
                    "data": null,
                    "class": "after-none hasHtml wrap",
                    "width": "15px",
                    "render": function(data) {
                        return ((data.isFolder) ?
                            '<img class= "tableIcone w-13 mr-0" src="/svg/folders/none\.svg " title="Folder">' :
                            '' +
                            '<img class= "tableIcone w-13 mr-0" src="/svg/folders/tasks\.svg " title="File">'
                        );
                    }
                },
                {
                    "class": "text-left tabsColumn",
                    "data": "displayName"
                }, {
                    "class": "tabsColumn",
                    "data": "tabType"
                }, {
                    "class": "tabsColumn",
                    "data": "url"
                },
                {
                    "data": null,
                    "class": "after-none hasHtml",
                    "title": '<img class= "tableIcone w-13 mr-0" src="/svg/important\.svg " title="Important">',
                    "width": "15px",
                    render: function(data, type, full, meta) {
                        if (data.isImportant)
                            return '<img class= "tableIcone w-13 mr-0" src="/svg/important\.svg " title="Important">';
                        return '';
                    }
                },
                {
                    "data": null,
                    "class": "after-none hasHtml",
                    "width": "15px",
                    "title": '<img class= "tableIcone w-13 mr-0" src="/svg/attach\.svg " title="has Attachments">',
                    render: function(data, type, full, meta) {
                        if (data.type == "post")
                            if (data.attachments.length > 0)
                                return '<img class= "tableIcone w-13 mr-0" src="/svg/attach\.svg " title="has Attachments">';
                        return '';
                    }
                },
                {
                    "class": "text-left postColumn hasHtml",
                    "data": null,
                    "render": function(data) {
                        @if ($role->hasPermissionTo('teams_view_item_details'))
                            return '<a type="button" class="viewPost">' + data.author + '</a>';
                        @endif
                        return '';
                    }
                },
                {
                    "class": "postColumn",
                    "data": "subject"
                },
                {
                    "class": "postColumn",
                    "data": null,
                    render: function(data) {
                        return formatDate(data.createdTime);
                    }
                },
                {
                    "class": "postColumn",
                    "data": null,
                    render: function(data) {
                        return formatDate(data.lastModifiedTime);
                    }
                },
                {
                    "data": null,
                    "width": "15px",
                    "class": "after-none postColumn hasHtml",
                    "title": '<img class= "tableIcone w-13 mr-0" src="/svg/download\.svg" title="Download">',
                    render: function(data, type, full, meta) {
                        @if ($role->hasPermissionTo('teams_view_item_details'))
                            return '<img class="hand tableIcone downloadDocument w-13 mr-0" src="/svg/download\.svg" title="Download">';
                        @endif
                        return '';
                    }
                },
                {
                    "data": null,
                    "class": "text-left fileNameColumn hasHtml fileColumn",
                    "render": function(data) {
                        if (data.isFolder)
                            return '<a class="text-orange infolder">' + data.name + '</a>';
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
                    "data": "modifiedBy"
                },
                {
                    "data": null,
                    render: function(data) {
                        return formatDate(data.modified);
                    }
                },
                {
                    "data": null,
                    "width": "15px",
                    "title": '<img class= "tableIcone w-13 mr-0" src="/svg/download\.svg " title="Download">',
                    "class": "after-none fileColumn hasHtml",
                    render: function(data, type, full, meta) {
                        @if ($role->hasPermissionTo('teams_view_item_details'))
                            return '<img class="hand tableIcone downloadDocument w-13 mr-0" src="/svg/download\.svg " title="Download">';
                        @endif
                        return '';
                    }
                },
                {
                    "data": "isFolder"
                },
                {
                    "data": null,
                    "title": '<img class= "tableIcone w-13 mr-0" src="/svg/important\.svg " title="Important">',
                    "class": "after-none postColumn",
                    "width": "15px",
                    render: function(data, type, full, meta) {
                        if (data.isImportant)
                            return '<img class= "tableIcone w-13 mr-0" src="/svg/important\.svg " title="Important">';
                        return '';
                    }
                },
                {
                    "data": null,
                    "title": '<img class= "tableIcone w-13 mr-0" src="/svg/attach\.svg " title="Attachments">',
                    "class": "custom-after-none postColumn hasHtml",
                    "width": "15px",
                    render: function(data, type, full, meta) {
                        if (data.type == "post")
                            if (data.attachments.length > 0)
                                return '<img class= "tableIcone w-13 mr-0" src="/svg/attach\.svg " title="has Attachments">';
                        return '';
                    }
                }
            ],
            dom: 'Bfrtip',
            buttons: [{
                text: '<img src="/svg/filter.svg" class="new-filter">',
                titleAttr: 'Advanced Search',
                action: function(e, dt, node, config) {
                    $('#searchModal').modal('show');
                }
            }],
            "orderFixed": {
                "pre": [18, 'desc']
            },
            "fnDrawCallback": function(data) {
                resetTotalItems();
                //-----------------------------//
                if (data.json) {
                    if (contentType == "tab") {
                        resetVisible([2, 3, 4]);
                    } else if (contentType == "post") {
                        resetVisible([7, 8, 9, 10, 11, 19, 20]);
                    } else if (contentType == "file") {
                        resetVisible([1, 12, 13, 14, 15, 16, 17]);
                    }
                }
                //-----------------------------//
                var icon =
                    '<div class="search-container"><img class="teams-search-icon" src="/svg/search.svg"></div>';
                if ($(".dataTables_filter label").find('.search-icon').length == 0)
                    $('.dataTables_filter label').append(icon);
                $('.dataTables_filter input').addClass('form_input form-control');
                //-----------------------------//
                if ($('#itemsTable_wrapper .dataTables_scroll').find('.folderPathRow').length == 0) {
                    let div = '<div class="row folderPathRow m-0 hide">' +
                        '<div class="col-lg-12 p-2">' +
                        '<span class="folderPathSpan ml-2" class="basic-color"></span>' +
                        '</div>' +
                        '</div>';
                    $('#itemsTable_wrapper .dataTables_scroll').prepend(div)
                }
                //-----------------------------//
                teamCheckChange();
                channelCheckChange();
                contentFolderCheckChange();
                $('.contentFolderItemCheck,.contentFolderCheck').change(folderTableChange);
                //-----------------------------//
                $('.infolder').unbind('click').click(getFolderInItems);
                //-----------------------------//
                $('.fileColumn .tableIcone.downloadDocument').unbind('click').click(function() {
                    var tr = $(this).closest('tr');
                    $('tr.current').removeClass('current');
                    tr.addClass('current');
                    downloadTeamsFile();
                });
                //-----------------------------//
                $('.postColumn .tableIcone.downloadDocument').unbind('click').click(function() {
                    var tr = $(this).closest('tr');
                    $('tr.current').removeClass('current');
                    tr.addClass('current');
                    downloadTeamsPost();
                });
                //-----------------------------//
                $('.viewPost').unbind('click').click(function() {
                    var tr = $(this).closest('tr');
                    $('tr.current').removeClass('current');
                    tr.addClass('current');
                    viewTeamsPost();
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
                        $('.warningRow,.stoppingRow').addClass('hide');
                        $('.searchingLabel').addClass('hide');
                        $('.stopLoad').addClass('hide');
                        $('.resumeLoad').addClass('hide');
                        $('.moveToEDiscovery').removeClass('mr-2 ml-2');
                        isDone = true;
                    } else if (!stopLoading) {
                        setTimeout(function() {
                            loadItems();
                        }, 500);
                    }
                } else if (tableDataCount > 0 && stopLoading && !isDone) {
                    stopLoad();
                }
                //-------------------------------------------//
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
                'targets': [0, 1, 11, 16, 17, 19, 20], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }, {
                'targets': [18], // column index (start from 0)
                'visible': false,
            }, {
                'targets': [7, 8, 9, 10, 11, 19, 20], // column index (start from 0)
                'visible': false,
            }, {
                'targets': [12, 13, 14, 15, 16, 17], // column index (start from 0)
                'visible': false,
            }, {
                'targets': [5, 6], // column index (start from 0)
                'visible': false,
            }, ]
        };
        //-------------------------------------------------//
        function resetVisible(arr = []) {
            for (let i = 0; i <= 20; i++) {
                if (i > 0) {
                    if ($.inArray(i, arr) == -1)
                        globalItemsTable.column(i).visible(false);
                    else
                        globalItemsTable.column(i).visible(true);
                } else
                    globalItemsTable.column(i).visible(true);
            }
        }
        //-------------------------------------------------//
        $(document).ready(function() {
            minmizeSideBar();

            teamCheckChange();
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
                        "class": 'after-none hasHtml',
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
                "scrollY": "220px",
                "paging": false,
                "autoWidth": true,
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
                        "class": 'after-none hasHtml',
                        "render": function(data) {
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
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
                "scrollY": function() {
                    let parent = $('#channelsTable').closest(".col-lg-12.customBorder");
                    if (parent.hasClass("h-325p"))
                        return "255px";
                    return "108px";
                },
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
                        "class": 'after-none hasHtml',
                        "render": function(data) {
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
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
                            return data.name + ' - Posts';
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
                        "class": 'after-none hasHtml',
                        "render": function(data) {
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
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
                            return data.name + ' - Posts';
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
                        "class": 'after-none hasHtml',
                        "render": function(data) {
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
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
                            return data.name + ' - Files';
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
                "scrollY": "40px",
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
                        "class": 'after-none hasHtml',
                        "render": function(data) {
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
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
                            return data.name + ' - Files';
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
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
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
                            return data.name + ' - Files';
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
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
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
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
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
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
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
            $('.side-nav-icon').click(function() {
                $('#itemsTable').DataTable().draw();
            });

            $('#itemsTable').DataTable().buttons().container()
                .prependTo('#itemsTable_filter ');

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

            $('input[name="pointType"]').change(function() {
                let form = $(this).closest('form');
                if ($(this).val() == 'all') {
                    form.find('.restorePointRow').addClass('hide');
                    // $('.mt-btn').removeClass('mt-32');
                    // $('.mt-fbtn').removeClass('mt-45').addClass('mt-77');
                    form.find('.restorePointRow input').removeAttr('required');
                } else {
                    form.find('.restorePointRow').removeClass('hide');
                    // $('.mt-btn').addClass('mt-32');
                    // $('.mt-fbtn').addClass('mt-45').removeClass('mt-77');
                    form.find('.restorePointRow input').attr('required', 'required');
                }
            });
            $('input[name="pointType"]').change();

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
        $(document).on('change', '.tree input.teamCheck[type=checkbox]', function(e) {
            // $(this.closest('div')).siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);
            // $(this).parentsUntil('.tree').children("input[type='checkbox']").prop('checked', this.checked);
            e.stopPropagation();
            // Allow single team channel
            if ($(this).parentsUntil('.tree').children("input[type='checkbox']").length > 0 && this.checked) {
                $(".tree .channelCheck[data-teamid!='" + $(this).val() + "']").prop("checked", false);
            }
            //--------------------//
            contentFolderCheckChange();
            channelCheckChange();
        });

        $("#jobs").change(function() {
            if (this.value != "") {
                $(".spinner_parent").css("display", "block");
                $('body').addClass('removeScroll');
                $.ajax({
                    type: "GET",
                    url: "{{ url('getRestoreTime') }}/teams/" + this.value,
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
                var author = $('#author').val();
                var subject = $('#subject').val();
                var createdAtFrom = $('#createdAtFrom').datepicker('getDate');
                var createdAtTo = $('#createdAtTo').datepicker('getDate');

                var name = $('#name').val();
                var modifiedBy = $('#modifiedBy').val();

                var tabsName = $('#tabsName').val();
                var type = $('#type').val();

                if (author) {
                    if (!data[7])
                        return false;
                    var value = data[7].toLowerCase();
                    if (!value.toString().includes(author.toLowerCase()))
                        return false;
                }

                if (subject) {
                    if (!data[8])
                        return false;
                    var value = data[8].toLowerCase();
                    if (!value.toString().includes(subject.toLowerCase()))
                        return false;
                }

                if ((createdAtFrom || createdAtTo) && !data[9]) {
                    return false;
                } else {
                    if (createdAtFrom) {
                        if (new Date(createdAtFrom) > new Date(data[9])) {
                            return false;
                        }
                    }

                    if (createdAtTo) {
                        if (new Date(createdAtTo) < new Date(data[9])) {
                            return false;
                        }
                    }
                }

                if (name) {
                    if (!data[12])
                        return false;
                    var value = data[12].toLowerCase();
                    if (!value.toString().includes(name.toLowerCase()))
                        return false;
                }

                if (modifiedBy) {
                    if (!data[15])
                        return false;
                    var value = data[15].toLowerCase();
                    if (!value.toString().includes(modifiedBy.toLowerCase()))
                        return false;
                }

                if (tabsName) {
                    if (!data[2])
                        return false;
                    var value = data[2].toLowerCase();
                    if (!value.toString().includes(tabsName.toLowerCase()))
                        return false;
                }

                if (type) {
                    if (!data[3])
                        return false;
                    var value = data[3].toLowerCase();
                    if (!value.toString().includes(type.toLowerCase()))
                        return false;
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
        function teamCheckChange() {
            var len = $('.tree .teamCheck:checked').length;
            if (len == 0) {
                $('.teamsButton button').attr('disabled', 'disabled');
                $('.teamsButton .selectedBoxCount').html('');
            } else {
                $('.teamsButton button').removeAttr('disabled');
                $('.teamsButton .selectedBoxCount').html('(' + len + ')');
            }
        }
        //----------------------------------------------------//
        function channelCheckChange(tempChannelId, $this) {
            // Allow single team channel
            if (tempChannelId) {
                if ($this.prop("checked"))
                    $(".tree .channelCheck[value!='" + tempChannelId + "']").prop("checked", false);
            }
            //--------------------//
            var items = $('.tree .channelCheck:checked').length;
            if (items == 0) {
                $('.channelButton button').attr('disabled', 'disabled');
                $('.channelButton .selectedFolderCount').html('');
            } else {
                $('.channelButton button').removeAttr('disabled');
                $('.channelButton .selectedFolderCount').html('(' + items + ')');
            }
        }
        //----------------------------------------------------//
        function contentFolderCheckChange() {
            var len = $('.contentFolderItemCheck:checked').length;
            if (len == 0) {
                $('.tabsButton button').attr('disabled', 'disabled');
                $('.tabsButton .selectedItemCount').html('');
            } else {
                $('.tabsButton button').removeAttr('disabled');
                $('.tabsButton .selectedItemCount').html('(' + len + ')');
                $('.tabsButton .postsLi,.tabsButton .tabsLi,.tabsButton .filesLi').addClass('hide');
                let postsLen = $('.contentFolderItemCheck[data-type="post"]:checked').length;
                let filesLen = $('.contentFolderItemCheck[data-type="file"]:checked').length;
                let tabsLen = $('.contentFolderItemCheck[data-type="tab"]:checked').length;
                if (postsLen > 0)
                    $('.tabsButton .postsLi').removeClass('hide');
                if (tabsLen > 0)
                    $('.tabsButton .tabsLi').removeClass('hide');
                if (filesLen > 0)
                    $('.tabsButton .filesLi').removeClass('hide');
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
        }
        //----------------------------------------------------//
        function resetSearch() {
            $("input").val("");
            $('#itemsTable').DataTable().draw();
        }
        //----------------------------------------------------//
        function ApplySearch() {
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
        function getChannelPosts(event) {
            let $target = $(event.target);
            teamId = $target.attr('data-teamId');
            contentType = "post";
            channelId = $target.attr('data-channelId');
            fullPath = [];
            showFolderPath();
            $('.tree .active').removeClass('active');
            $('[id="' + channelId + '"]').addClass('active');
            $target.addClass('active');

            stopLoading = false;
            isDone = false;
            resetTotalItems();
            resetOffset();
            $(document).find('.dataTables_filter input').val("");
            $('#itemsTable').DataTable().search("").ajax.reload();

            $('.posts-class').removeClass('hide');
            $('.files-class').addClass('hide');
            $('.tabs-class').addClass('hide');

            $('.files-class input').val("");
            $('.tabs-class input').val("");
        }
        //----------------------------------------------------//
        function getChannelFiles(event) {
            let $target = $(event.target);
            teamId = $target.attr('data-teamId');
            folderId = -1;
            fullPath = [];
            showFolderPath();
            contentType = "file";
            channelId = $target.attr('data-channelId');

            $('.tree .active').removeClass('active');
            $('[id="' + channelId + '"]').addClass('active');
            $target.addClass('active');

            stopLoading = false;
            isDone = false;
            resetTotalItems();
            resetOffset();
            $(document).find('.dataTables_filter input').val("");
            $('#itemsTable').DataTable().search("").ajax.reload();

            $('.posts-class').addClass('hide');
            $('.files-class').removeClass('hide');
            $('.tabs-class').addClass('hide');

            $('.posts-class input').val("");
            $('.tabs-class input').val("");
        }
        //----------------------------------------------------//
        function getChannelTabs(event) {
            let $target = $(event.target);
            teamId = $target.attr('data-teamId');
            contentType = "tab";
            channelId = $target.attr('data-channelId');
            fullPath = [];
            showFolderPath();
            $('.tree .active').removeClass('active');
            $('[id="' + channelId + '"]').addClass('active');
            $target.addClass('active');

            stopLoading = false;
            isDone = false;
            resetTotalItems();
            resetOffset();

            contentFolderCheckChange();
            $(document).find('.dataTables_filter input').val("");
            $('#itemsTable').DataTable().search("").ajax.reload();

            $('.posts-class').addClass('hide');
            $('.files-class').addClass('hide');
            $('.tabs-class').removeClass('hide');

            $('.posts-class input').val("");
            $('.files-class input').val("");
        }
        //----------------------------------------------------//
        function getPostChildren(event) {
            let $target = $(event.target);
            var tr = $target.closest('tr');
            var row = globalItemsTable.row(tr);
            tr.find('.mailCaret').toggleClass('closeMail');
            let postId = tr.find('.contentFolderItemCheck').val();
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                tr.find('.mailCaret').toggleClass('closeMail').addClass('hide');
                tr.find('.folder-spinner').removeClass('hide');
                $.ajax({
                    type: "POST",
                    url: "{{ url('getChannelPostReplies') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        postId: postId,
                        teamId: teamId,
                        teamTitle: $('.tree #' + teamId).html(),
                        channelId: channelId,
                        channelTitle: $('.tree [id="' + channelId + '"]').val()
                    },
                    success: function(data) {
                        //-------------------------------------//
                        tr = $('.contentFolderItemCheck[value="' + postId + '"]').closest('tr');
                        tr.find('.folder-spinner').addClass('hide');
                        tr.find('.mailCaret').removeClass('hide');
                        //-------------------------------------//
                        if (data.length > 0) {
                            // let color = tr.find('td').css('background-color');
                            //-------------------------------------//
                            let html =
                                '<table class="childTable stripe table table-striped table-dark display nowrap allWidth dataTable no-footer pl-3" style="border:none;background-color:#343a40">';
                            data.forEach((e) => {
                                html += formatChildRow(e);
                            });
                            html += '</table>';
                            // Open this row
                            row.child(html).show();
                            tr.addClass('shown');
                            let child = $('.childTable').closest('td').addClass('custom-child');
                            //--------------------------------------//
                            $('.viewPost').unbind('click').click(function() {
                                var tr = $(this).closest('tr');
                                $('tr.current').removeClass('current');
                                tr.addClass('current');
                                viewTeamsPost();
                            });
                            //--------------------------------------//
                            $('.childTable .tableIcone.downloadDocument').unbind('click').click(function() {
                                var tr = $(this).closest('tr');
                                $('tr.current').removeClass('current');
                                tr.addClass('current');
                                downloadTeamsPost();
                            });
                            //--------------------------------------//
                            $('.contentFolderItemCheck').change(contentFolderCheckChange);
                            //--------------------------------------//
                        } else {
                            tr.find('.mailCaret').remove();
                        }
                        //-------------------------------------//
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
                        //-------------------//
                        let errMessage = "   ERROR   ";
                        if (error.responseJSON) {
                            if (error.responseJSON.message)
                                errMessage = "ERROR  : " + error.responseJSON.message;
                            tr = $('.contentFolderItemCheck[value="' + error.responseJSON.postId + '"]')
                                .closest('tr');
                        }
                        //-------------------//
                        tr.find('.folder-spinner').addClass('hide');
                        tr.find('.mailCaret').removeClass('hide');
                        //-------------------//

                        $(".danger-oper .danger-msg").html(errMessage);
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                            $(".danger-oper").css("display", "none");

                        }, 8000);
                    }
                });
            }
        }
        //----------------------------------------------------//
        function formatChildRow(data) {
            let attachments = isImportant = '';
            if (data.attachments.length > 0)
                attachments =
                '<img class= "tableIcone hand w-13 mr-0" src="/svg/attach\.svg " title="has Attachments">';
            if (data.isImportant)
                isImportant =
                '<img class= "tableIcone hand w-13 mr-0" src="/svg/important\.svg " title="Important">';

            return '<tr>' +
                '<td class="after-none" style="width: 1px!important; background-color: #343a40!important;">' + '</td>' +
                '<td class=" after-none" style="background-color: #343a40!important;">' +
                '<label class="checkbox-container checkbox-search top-left">&nbsp;' +
                '<input type="hidden" class="teamId" value="' + data.teamId +
                '">' +
                '<input type="hidden" class="teamTitle" value="' + data.teamTitle +
                '">' +
                '<input type="hidden" class="channelId" value="' + data.channelId +
                '">' +
                '<input type="hidden" class="channelTitle" value="' + data.contentTitle +
                '">' +
                '<input type="hidden" class="isFolder" value="' + data.isFolder +
                '">' +
                '<input type="hidden" class="folderId" value="' + data.folderId +
                '">' +
                '<input type="hidden" class="folderTitle" value="' + data.folderTitle + '">' +
                '<input type="checkbox" data-type="' + data.type + '" data-isFolder="' + data.isFolder +
                '" class="contentFolderItemCheck form-check-input" value="' +
                data.id + '"/>' +
                '<span class="tree-checkBox check-mark"></span></label>' +
                '</td>' +
                '<td class="text-left" style="background-color: #343a40!important;">' +
                @if ($role->hasPermissionTo('teams_view_item_details'))
                    '<a type="button" class="viewPost">' + data.author + '</a>' +
                @endif
            '</td>' +
            '<td style="background-color: #343a40!important;">' + data.subject + '</td>' +
                '<td style="background-color: #343a40!important;">' + formatDate(data.createdTime) + '</td>' +
                '<td style="background-color: #343a40!important;">' + formatDate(data.lastModifiedTime) + '</td>' +
                '<td style="background-color: #343a40!important;" class=" after-none">' +
                @if ($role->hasPermissionTo('teams_view_item_details'))
                    '<img class="hand tableIcone downloadDocument w-13 mr-0" src="/svg/download\.svg " title="Download">' +
                @endif
            '</td>' +
            '<td class="after-none" style="background-color: #343a40!important;">' + isImportant + '</td>' +
                '<td class="custom-after-none" style="background-color: #343a40!important;">' + attachments + '</td>' +
                '</tr>';
        }
        //----------------------------------------------------//
        function getFolderInItems() {
            resetOffset();
            var tr = $(this).closest('tr');
            let selectedFolderId = tr.find('.contentFolderItemCheck').val();
            let selectedChannelId = tr.find('.channelId').val();
            let selectedTeamId = tr.find('.teamId').val();
            //---------------------------------------------//
            teamId = selectedTeamId;
            channelId = selectedChannelId;
            folderId = selectedFolderId;
            //---------------------------------------------//
            if (fullPath.length == 0) {
                fullPath.push({
                    "id": selectedChannelId,
                    "name": $('.tree [id="' + channelId + '"]').html(),
                    "type": "channel"
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
            stopLoading = false;
            isDone = false;
            resetTotalItems();
            resetOffset();
            $(document).find('.dataTables_filter input').val("");
            $('#itemsTable').DataTable().search("").ajax.reload();
            //---------------------------------------------//
        }
        //----------------------------------------------------//
        function getTableFiles(channel, folder = -1) {
            //---------------------------------------------//
            channelId = channel;
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
                let tempChannelId;
                fullPath.forEach(function(e) {
                    if (e.type == "channel") {
                        tempChannelId = e.id;
                        pathArr.push('<a onclick="getTableFiles(\'' + e.id + '\')">' + e.name + '</a>');
                    } else {
                        pathArr.push('<a onclick="getTableFiles(\'' + tempChannelId + '\',\'' + e.id + '\')">' + e
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
            let selectedTeams = [];
            let selectedChannels = [];
            $(".tree .teamCheck:checked").each(function() {
                selectedTeams.push({
                    "teamId": $(this).val(),
                    "teamName": $(".tree #" + $(this).val()).html(),
                    "url": $(this).attr("data-url"),
                    "channelId": "-1",
                    "channelName": ""
                });
            });
            $(".tree .channelCheck:checked").each(function() {
                selectedChannels.push({
                    "teamId": $(this).attr("data-teamId"),
                    "teamName": $(this).attr("data-teamName"),
                    "channelId": $(this).val(),
                    "url": $(".tree [value='" + $(this).attr("data-teamId") + "']").attr("data-url"),
                    "channelName": $(".tree [id='" + $(this).val() + "']").html()
                });
            });

            data.selectedTeams = JSON.stringify(selectedTeams);
            data.selectedChannels = JSON.stringify(selectedChannels);

            $.ajax({
                type: "POST",
                url: "{{ url('setEDiscoveryData') }}/teams",
                data: {
                    _token: "{{ csrf_token() }}",
                    data: JSON.stringify(data),
                },
                success: function(data) {
                    window.location.href = "{{ url('e-discovery') }}" + "/teams/edit/" + data + "?type=move";
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
                    "teamId": tr.find('.teamId').val(),
                    "folder": tr.find('.folderTitle').val()
                });
            });
            //--------------------//
            $('#sendItem').find('.items').val(JSON.stringify(data));
            //--------------------//
            $('#sendItem').modal('show');
        }
        //----------------------------------------------------//
        function restoreTeamsModal() {
            let teams = $('#mailboxes li.mainItem input.teamCheck:checked');
            var tableData = [];
            let teamsCount = teams.length;
            let unresolvedCount = 0;
            teams.each(function() {
                var parent = $(this).closest('.has.mainItem');
                tableData.push({
                    id: $(this).val(),
                    name: parent.find('.item-click').html(),
                    email: $(this).attr('data-email')
                });
            });
            $('#teamsTable_wrapper').find('.boxesCount').html(teamsCount);
            $('#teamsTable').DataTable().clear().draw();
            $('#teamsTable').DataTable().rows.add(tableData); // Add new data
            $('#teamsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('teamsTable');
            adjustTable();
            $("#teamsTable").DataTable().draw();
            $('#restoreTeamsModal').find('.refreshDeviceCode').click();
            $('#restoreTeamsModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreChannelsModal() {
            let channels = $('.tree input.channelCheck:checked');
            var tableData = [];
            let channelsCount = channels.length;
            let unresolvedCount = 0;
            channels.each(function() {
                var teamId = $(this).closest('.teamCont').attr('data-teamId');
                tableData.push({
                    id: $(this).val(),
                    teamId: teamId,
                    name: $(this).closest('.teamChannel').find('.channel-click').html(),
                    teamName: $('#' + teamId).html()
                });
            });
            $('#channelsTable_wrapper').find('.boxesCount').html(channelsCount);
            $('#channelsTable').DataTable().clear().draw();
            $('#channelsTable').DataTable().rows.add(tableData); // Add new data
            $('#channelsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('channelsTable');
            adjustTable();
            $("#channelsTable").DataTable().draw();
            $('#restoreChannelsModal').find('.refreshDeviceCode').click();
            $('#restoreChannelsModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreChannelsPostsModal() {
            let channels = $('.tree input.channelCheck:checked');
            var tableData = [];
            let channelsCount = channels.length;
            let unresolvedCount = 0;
            channels.each(function() {
                var teamId = $(this).closest('.teamCont').attr('data-teamId');
                tableData.push({
                    id: $(this).val(),
                    teamId: teamId,
                    name: $(this).closest('.teamChannel').find('.channel-click').html(),
                    teamName: $('#' + teamId).html()
                });
            });
            $('#channelsPostsTable_wrapper').find('.boxesCount').html(channelsCount);
            $('#channelsPostsTable').DataTable().clear().draw();
            $('#channelsPostsTable').DataTable().rows.add(tableData); // Add new data
            $('#channelsPostsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable
            console.log("we will add class here");
            // $('.customBorder.h-325p').removeClass('h-325p').addClass('h-270p');
            checkTableCount('channelsPostsTable');
            adjustTable();
            $("#channelsPostsTable").DataTable().draw();
            $('#restoreChannelsPostsModal').find('.refreshDeviceCode').click();
            $('#restoreChannelsPostsModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreChannelsFilesModal() {
            let channels = $('.tree input.channelCheck:checked');
            var tableData = [];
            let channelsCount = channels.length;
            let unresolvedCount = 0;
            channels.each(function() {
                var teamId = $(this).closest('.teamCont').attr('data-teamId');
                tableData.push({
                    id: $(this).val(),
                    teamId: teamId,
                    name: $(this).closest('.teamChannel').find('.channel-click').html(),
                    teamName: $('#' + teamId).html()
                });
            });
            $('#channelsFilesTable_wrapper').find('.boxesCount').html(channelsCount);
            $('#channelsFilesTable').DataTable().clear().draw();
            $('#channelsFilesTable').DataTable().rows.add(tableData); // Add new data
            $('#channelsFilesTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('channelsFilesTable');
            adjustTable();
            $("#channelsFilesTable").DataTable().draw();
            $('#restoreChannelsFilesModal').find('.refreshDeviceCode').click();
            $('#restoreChannelsFilesModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreChannelsTabsModal() {
            let channels = $('.tree input.channelCheck:checked');
            var tableData = [];
            let channelsCount = channels.length;
            let unresolvedCount = 0;
            channels.each(function() {
                var teamId = $(this).closest('.teamCont').attr('data-teamId');
                tableData.push({
                    id: $(this).val(),
                    teamId: teamId,
                    name: $(this).closest('.teamChannel').find('.channel-click').html(),
                    teamName: $('#' + teamId).html()
                });
            });
            $('#channelsTabsTable_wrapper').find('.boxesCount').html(channelsCount);
            $('#channelsTabsTable').DataTable().clear().draw();
            $('#channelsTabsTable').DataTable().rows.add(tableData); // Add new data
            $('#channelsTabsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('channelsTabsTable');
            adjustTable();
            $("#channelsTabsTable").DataTable().draw();
            $('#restoreChannelsTabsModal').find('.refreshDeviceCode').click();
            $('#restoreChannelsTabsModal').modal('show');
        }
        //----------------------------------------------------//
        function exportChannelsPostsModal() {
            let channels = $('.tree input.channelCheck:checked');
            var tableData = [];
            let channelsCount = channels.length;
            let unresolvedCount = 0;
            channels.each(function() {
                var teamId = $(this).closest('.teamCont').attr('data-teamId');
                tableData.push({
                    id: $(this).val(),
                    teamId: teamId,
                    name: $(this).closest('.teamChannel').find('.channel-click').html(),
                    teamName: $('#' + teamId).html()
                });
            });
            $('#exportChannelsPostsTable_wrapper').find('.boxesCount').html(channelsCount);
            $('#exportChannelsPostsTable').DataTable().clear().draw();
            $('#exportChannelsPostsTable').DataTable().rows.add(tableData); // Add new data
            $('#exportChannelsPostsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportChannelsPostsTable');
            adjustTable();
            $("#exportChannelsPostsTable").DataTable().draw();
            $('#exportChannelsPostsModal').modal('show');
        }
        //----------------------------------------------------//
        function exportChannelsFilesModal() {
            let channels = $('.tree input.channelCheck:checked');
            var tableData = [];
            let channelsCount = channels.length;
            let unresolvedCount = 0;
            channels.each(function() {
                var teamId = $(this).closest('.teamCont').attr('data-teamId');
                tableData.push({
                    id: $(this).val(),
                    teamId: teamId,
                    name: $(this).closest('.teamChannel').find('.channel-click').html(),
                    teamName: $('#' + teamId).html()
                });
            });
            $('#exportChannelsFilesTable_wrapper').find('.boxesCount').html(channelsCount);
            $('#exportChannelsFilesTable').DataTable().clear().draw();
            $('#exportChannelsFilesTable').DataTable().rows.add(tableData); // Add new data
            $('#exportChannelsFilesTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportChannelsFilesTable');
            adjustTable();
            $("#exportChannelsFilesTable").DataTable().draw();
            $('#exportChannelsFilesModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreFilesModal() {
            let files = $('.contentFolderItemCheck[data-type="file"]:checked');
            var tableData = [];
            let filesCount = files.length;
            let unresolvedCount = 0;
            files.each(function() {
                var tr = $(this).closest('tr');
                tableData.push({
                    id: $(this).val(),
                    teamId: tr.find('.teamId').val(),
                    teamName: tr.find('.teamTitle').val(),
                    channelId: tr.find('.channelId').val(),
                    channelName: tr.find('.channelTitle').val(),
                    name: tr.find('.fileColumn').html(),
                });
            });
            $('#filesTable_wrapper').find('.boxesCount').html(filesCount);
            $('#filesTable').DataTable().clear().draw();
            $('#filesTable').DataTable().rows.add(tableData); // Add new data
            $('#filesTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('filesTable');
            adjustTable();
            $("#filesTable").DataTable().draw();
            $('#restoreFilesModal').find('.refreshDeviceCode').click();
            $('#restoreFilesModal').modal('show');
        }
        //----------------------------------------------------//
        function exportFilesModal() {
            let files = $('.contentFolderItemCheck[data-type="file"]:checked');
            var tableData = [];
            let filesCount = files.length;
            let unresolvedCount = 0;
            files.each(function() {
                var tr = $(this).closest('tr');
                tableData.push({
                    id: $(this).val(),
                    teamId: tr.find('.teamId').val(),
                    teamName: tr.find('.teamTitle').val(),
                    channelId: tr.find('.channelId').val(),
                    channelName: tr.find('.channelTitle').val(),
                    name: tr.find('.fileColumn').html(),
                });
            });
            $('#exportFilesTable_wrapper').find('.boxesCount').html(filesCount);
            $('#exportFilesTable').DataTable().clear().draw();
            $('#exportFilesTable').DataTable().rows.add(tableData); // Add new data
            $('#exportFilesTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportFilesTable');
            adjustTable();
            $("#exportFilesTable").DataTable().draw();
            $('#exportFilesModal').modal('show');
        }
        //----------------------------------------------------//
        function exportPostsModal() {
            let posts = $('.contentFolderItemCheck[data-type="post"]:checked');
            var tableData = [];
            let postsCount = posts.length;
            let unresolvedCount = 0;
            posts.each(function() {
                var tr = $(this).closest('tr');
                tableData.push({
                    id: $(this).val(),
                    teamId: tr.find('.teamId').val(),
                    teamName: tr.find('.teamTitle').val(),
                    channelId: tr.find('.channelId').val(),
                    channelName: tr.find('.channelTitle').val(),
                    name: tr.find('.postColumn a').html(),
                });
            });
            $('#exportPostsTable_wrapper').find('.boxesCount').html(postsCount);
            $('#exportPostsTable').DataTable().clear().draw();
            $('#exportPostsTable').DataTable().rows.add(tableData); // Add new data
            $('#exportPostsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportPostsTable');
            adjustTable();
            $("#exportPostsTable").DataTable().draw();
            $('#exportPostsModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreTabsModal() {
            let tabs = $('.contentFolderItemCheck[data-type="tab"]:checked');
            var tableData = [];
            let tabsCount = tabs.length;
            let unresolvedCount = 0;
            tabs.each(function() {
                var tr = $(this).closest('tr');
                tableData.push({
                    id: $(this).val(),
                    teamId: tr.find('.teamId').val(),
                    teamName: tr.find('.teamTitle').val(),
                    channelId: tr.find('.channelId').val(),
                    channelName: tr.find('.channelTitle').val(),
                    name: tr.find('.text-left.tabsColumn').html(),
                });
            });
            $('#tabsTable_wrapper').find('.boxesCount').html(tabsCount);
            $('#tabsTable').DataTable().clear().draw();
            $('#tabsTable').DataTable().rows.add(tableData); // Add new data
            $('#tabsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('tabsTable');
            adjustTable();
            $("#tabsTable").DataTable().draw();
            $('#restoreTabsModal').find('.refreshDeviceCode').click();
            $('#restoreTabsModal').modal('show');
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
        function getTeam() {
            return teamId;
        }
        //---------------------------------------------------//
        function getChannel() {
            return channelId;
        }
        //---------------------------------------------------//
        function getContentType() {
            return contentType;
        }
        //---------------------------------------------------//
        function getFolder() {
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
                url: "{{ url('getTeamChannelContent') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    offset: getTotalItems(),
                    type: getContentType(),
                    teamId: getTeam(),
                    teamTitle: $('#' + getTeam()).html(),
                    channelId: getChannel(),
                    channelTitle: $('[id="' + getChannel() + '"]').html(),
                    folderId: getFolder(),
                    folderTitle: $('#' + getFolder()).html(),
                },
                success: function(data) {},
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
                    $('.resumeLoad,.stopLoad,.searchingLabel').addClass('hide');
                    $('.moveToEDiscovery').removeClass('mr-2 ml-2');
                    $('.stoppingRow,.warningRow').addClass('hide');
                    isDone = true;
                }
            });
        }
        //---------------------------------------------------//
        function createSession(event) {
            event.preventDefault()
            teamId = -1;
            folderId = -1;
            fullPath = [];
            $(document).find('.dataTables_filter input').val("");
            $('#itemsTable').DataTable().search("").ajax.reload();

            teamCheckChange();
            contentFolderCheckChange();
            $('.warningRow,.stoppingRow,.moveToEDiscovery').addClass('hide');

            if ($(".backupTime").find(":selected").val() != "") {
                $(".spinner_parent").css("display", "block");
                $('body').addClass('removeScroll');
                $.ajax({
                    type: "POST",
                    url: "{{ url('createTeamsSession') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        jobs: $('#jobs').val(),
                        time: $(".backupTime").find(":selected").val(),
                        showDeleted: $("#showDeleted")[0].checked,
                        showVersions: $("#showVersions")[0].checked
                    },
                    success: function(data) {
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
                                '<li class="has mainItem hand relative teamCont" data-teamId="' + result
                                .id + '"><div class="relative allWidth">' +
                                '<span class="caret mailCaret closeMail" onclick="getTeamChannels(event)"></span>' +
                                '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                                '<input type="checkbox" class="teamCheck form-check-input" value="' +
                                result.id + '" data-email="' + result.groupEmail + '"/>' +
                                '<span class="tree-checkBox check-mark"></span></label>' +
                                '<span id="' + result.id +
                                '" class="item-click left-mail-click ml-27" title="' +
                                result.displayName + '" ' +
                                ' onclick="getTeamChannels(event)" data-email="' + result.groupEmail +
                                '" data-privacy="' + result.privacy +
                                '" data-toggle="popover" data-placement="right" ' +
                                '  >' +
                                result.displayName +
                                '</span></div><div class="folder-spinner hide"></div>' +
                                '</li>';
                            $("#mailboxes").append($(mainItem));
                        });
                        $("#rdate").html($(".backupDate").val());
                        $("#rtime").html($(".backupTime").find(":selected")
                            .text());
                        $('#jobsModal').modal('hide');
                        $('.teamCheck').change(teamCheckChange);
                        //------------------------------------------//
                        var delay = 1000,
                            setTimeoutConst;
                        $('[data-toggle="popover"]').popover({
                            container: 'body',
                            trigger: 'manual',
                            content: function() {
                                return '<div class="flex"><span>Email: </span><span class="ellipsis" title="' +
                                    $(this).attr('data-email') + '">' + $(this).attr('data-email') +
                                    '</span></div>' +
                                    '<div> <span>Privacy: </span><span>' + $(this).attr(
                                        'data-privacy') + '</span></div>';
                            },
                            html: true,
                            delay: {
                                "hide": 500,
                                "show": 500
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
        function resetFilterTable() {
            $('.filter-form').find('.sortBoxType').val('');
            $('.filter-form').find('.privacyType').val('');
            $('.filter-table td .active').removeClass('active');
            getFilteredTeams();
        }
        //---------------------------------------------------//
        function getFilteredTeams(event) {
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
                url: "{{ url('getFilteredTeams') }}",
                data: data + '&letters=' + lettersArr.join(','),
                success: function(res) {
                    $("#mailboxes").html("");
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    $("#choose").text("Change")
                    res.forEach(function(result) {
                        mainItem =
                            '<li class="has mainItem hand relative teamCont" data-teamId="' + result
                            .id + '"><div class="relative allWidth">' +
                            '<span class="caret mailCaret closeMail" onclick="getTeamChannels(event)"></span>' +
                            '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                            '<input type="checkbox" class="teamCheck form-check-input" value="' +
                            result.id + '" data-email="' + result.groupEmail + '"/>' +
                            '<span class="tree-checkBox check-mark"></span></label>' +
                            '<span id="' + result.id +
                            '" class="item-click left-mail-click ml-27" title="' +
                            (result.groupEmail + ',' + result.privacy + ',' + (result.isArchived ==
                                "true" ? "Archived" : "Not Archived")) + '" ' +
                            ' onclick="getTeamChannels(event)" data-email="' + result.groupEmail +
                            '">' +
                            result.displayName +
                            '</span></div><div class="folder-spinner hide"></div>' +
                            '</li>';
                        $("#mailboxes").append($(mainItem));
                    });
                    $("#rdate").html($(".backupDate").val());
                    $("#rtime").html($(".backupTime").find(":selected")
                        .text());
                    $('#jobsModal').modal('hide');
                    $('.filter-icon').click();
                    $('.teamCheck').change(teamCheckChange);
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
        function getTeamChannels(event) {
            $target = $(event.target);
            if ($target.hasClass('mailCaret')) {
                teamId = $target.closest('.teamCont').find('.item-click').attr('id');
            } else {
                teamId = $target.attr('id');
            }
            $("#" + teamId).closest("div").find('.mailCaret').toggleClass('closeMail');
            if ($("#" + teamId).closest(".teamCont").find('ul').length) {
                $("#" + teamId).closest(".teamCont").find('ul:first').fadeToggle();
                return;
            }
            //----------------------------------------------//
            $("#" + teamId).closest(".teamCont").find('.folder-spinner').toggleClass('hide');
            //----------------------------------------------//
            $.ajax({
                type: "GET",
                url: "{{ url('getTeamChannels') }}/" + teamId,
                data: {},
                success: function(data) {
                    let teamChannels = "<ul class='pt-0 pb-0 mb-0'>";
                    data.forEach(function(result) {
                        teamId = result.teamId;
                        let teamName = $("#" + teamId).html();
                        teamChannels = teamChannels +
                            '<li class="teamChannel" data-channelId="' + result.id +
                            '"><div class="relative allWidth inline-flex">' +
                            '<span class="caret mailCaret closeMail" onclick="getChannelContent(event)"></span>' +
                            '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                            '<input type="checkbox" class="channelCheck form-check-input" value="' +
                            result.id + '" data-teamId="' + teamId + '" data-teamName="' + teamName +
                            '"/>' +
                            '<span class="tree-checkBox check-mark"></span></label>' +
                            '<img class="folderIcon" src="/svg/folders/none.svg">' +
                            '<span id="' + result.id +
                            '" onclick="getChannelContent(event)" data-teamId="' + teamId +
                            '" class="item-click childmail-click item-folder-click channel-click" title="' +
                            result.displayName + '">' +
                            result.displayName + '</span></div>' +
                            '<ul class="pt-0 pb-0 mb-0">' +
                            '<li class="channelContent">' +
                            '<div class="relative allWidth inline-flex">' +
                            '<img class="folderIcon" src="/svg/folders/none.svg">' +
                            '<span data-channelId="' + result.id +
                            '" onclick="getChannelPosts(event)" data-teamid="' + teamId +
                            '" class="item-click item-folder-click" title="Posts">Posts</span>' +
                            '</div>' +
                            '</li>' +
                            '<li class="channelContent">' +
                            '<div class="relative allWidth inline-flex">' +
                            '<img class="folderIcon" src="/svg/folders/none.svg">' +
                            '<span data-channelId="' + result.id +
                            '" onclick="getChannelFiles(event)" data-teamid="' + teamId +
                            '" class="item-click item-folder-click" title="Files">Files</span>' +
                            '</div>' +
                            '</li>' +
                            '<li class="channelContent">' +
                            '<div class="relative allWidth inline-flex">' +
                            '<img class="folderIcon" src="/svg/folders/none.svg">' +
                            '<span data-channelId="' + result.id +
                            '" onclick="getChannelTabs(event)" data-teamid="' + teamId +
                            '" class="item-click item-folder-click" title="Others Tabs">Others Tabs</span>' +
                            '</div>' +
                            '</li>' +
                            '</ul>' +
                            '</li>';
                    });
                    teamChannels = teamChannels + "</ul>";
                    //-------------//
                    $('.teamCont[data-teamId="' + teamId + '"]').find('.folder-spinner:first').addClass('hide');
                    //-------------//
                    $('.teamCont[data-teamId="' + teamId + '"]').append($(teamChannels)[0]);
                    $('.teamCont[data-teamId="' + teamId + '"]').find('ul:first').fadeToggle();
                    //-------------//
                    $('.channelCheck').change(function() {
                        // Allow single team channel
                        channelCheckChange($(this).val(), $(this));
                    });
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
            //----------------------------------------------//
        }
        //---------------------------------------------------//
        function getChannelContent(event) {
            $target = $(event.target);
            //---------------------------------------------//
            if ($target.hasClass('mailCaret')) {
                channelId = $target.closest('.teamChannel').attr('data-channelid');
            } else {
                channelId = $target.attr('id');
            }
            //---------------------------------------------//
            $('.teamChannel[data-channelId="' + channelId + '"]').find('.mailCaret').toggleClass('closeMail');
            $('.teamChannel[data-channelId="' + channelId + '"]').find('ul:first').fadeToggle();
            //---------------------------------------------//
        }
        //---------------------------------------------------//



        //---------------------------------------------------//
        function restoreTeams() {
            event.preventDefault()
            var data = $('#restoreTeamsForm').serialize();
            //----------------------------//
            if (!$('#restoreTeamsForm').find("[name='restoreChangedItems']").prop("checked") && !$(
                    '#restoreTeamsForm').find("[name='restoreMissingItems']").prop("checked")) {
                $(".danger-oper .danger-msg").html(
                    "{{ __('variables.errors.restore_required_options') }}");
                $(".danger-oper").css("display", "block");
                setTimeout(function() {
                    $(".danger-oper").css("display", "none");
                }, 3000);
                return false;
            }
            //----------------------------//
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
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&teams=" + JSON.stringify(teamsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreTeamsModal').modal('hide');
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
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&channels=" + JSON.stringify(channelsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreChannelsModal').modal('hide');
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
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&channels=" + JSON.stringify(channelsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreChannelsPostsModal').modal('hide');
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
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&channels=" + JSON.stringify(channelsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreChannelsFilesModal').modal('hide');
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
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&channels=" + JSON.stringify(channelsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreChannelsTabsModal').modal('hide');
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
        function exportChannelsFiles() {
            event.preventDefault()
            let channels = $('#exportChannelsFilesForm .mailboxCheck:checked');
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
                url: "{{ url('exportChannelsFiles') }}",
                data: "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&restoreJobName=" + $("#exportChannelsFilesForm [name='restoreJobName']").val() +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&channels=" + JSON.stringify(channelsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#exportChannelsFilesModal').modal('hide');
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
        function exportChannelsPosts() {
            event.preventDefault()
            let channels = $('#exportChannelsPostsForm .mailboxCheck:checked');
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
                url: "{{ url('exportChannelsPosts') }}",
                data: "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&restoreJobName=" + $("#exportChannelsPostsForm [name='restoreJobName']").val() +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&channels=" + JSON.stringify(channelsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#exportChannelsPostsModal').modal('hide');
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
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&files=" + JSON.stringify(filesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreFilesModal').modal('hide');
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
        function exportFiles() {
            event.preventDefault()
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
                data: "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&restoreJobName=" + $('#exportFilesForm [name="restoreJobName"]').val() +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&files=" + JSON.stringify(filesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#exportFilesModal').modal('hide');
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
        function exportPosts() {
            event.preventDefault()
            //----------------------------------------------//
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
            //----------------------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportTeamsPosts') }}",
                data: "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&restoreJobName=" + $("#exportPostsForm [name='restoreJobName']").val() +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&posts=" + JSON.stringify(postsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#exportPostsModal').modal('hide');
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
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                    "&jobType=" + ($('#jobs').val() == "all" ? "all" : "single") +
                    "&jobTime=" + $('.backupTime').val() +
                    "&showDeleted=" + $("#showDeleted")[0].checked +
                    "&showVersions=" + $("#showVersions")[0].checked +
                    "&tabs=" + JSON.stringify(tabsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreTabsModal').modal('hide');
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
        function downloadTeamsFile() {
            let tr = $('tr.current');
            let item = tr.find('input.contentFolderItemCheck').val();
            let pointTeamId = tr.find('.teamId').val();
            let channelId = tr.find('.channelId').val();
            let teamName = tr.find('.teamTitle').val();
            let channelName = tr.find('.channelTitle').val();
            let fileSize = tr.find('.fileSizeColumn').html();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('downloadTeamsFile') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    jobId: $('.backupTime option:selected').attr('data-job-id'),
                    jobTime: $('.backupTime').val(),
                    showDeleted: $("#showDeleted")[0].checked,
                    showVersions: $("#showVersions")[0].checked,
                    teamId: pointTeamId,
                    channelId: channelId,
                    teamName: teamName,
                    channelName: channelName,
                    fileSize: fileSize,
                    fileId: item,
                    name: tr.find('.fileNameColumn').html(),
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
        function downloadTeamsPost() {
            let tr = $('tr.current');
            let item = tr.find('input.contentFolderItemCheck').val();
            let pointTeamId = tr.find('.teamId').val();
            let channelId = tr.find('.channelId').val();
            let teamName = tr.find('.teamTitle').val();
            let channelName = tr.find('.channelTitle').val();
            let fileSize = tr.find('.fileSizeColumn').html();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('downloadTeamsPost') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    jobId: $('.backupTime option:selected').attr('data-job-id'),
                    jobTime: $('.backupTime').val(),
                    showDeleted: $("#showDeleted")[0].checked,
                    showVersions: $("#showVersions")[0].checked,
                    teamId: pointTeamId,
                    fileSize: fileSize,
                    postId: item,
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
        function viewTeamsPost() {
            let tr = $('tr.current');
            let item = tr.find('input.contentFolderItemCheck').val();
            let pointTeamId = tr.find('.teamId').val();
            let channelId = tr.find('.channelId').val();
            let teamName = tr.find('.teamTitle').val();
            let channelName = tr.find('.channelTitle').val();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('viewTeamsPost') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    jobId: $('.backupTime option:selected').attr('data-job-id'),
                    jobTime: $('.backupTime').val(),
                    showDeleted: $("#showDeleted")[0].checked,
                    showVersions: $("#showVersions")[0].checked,
                    teamId: pointTeamId,
                    postId: item,
                    jobId: $('.backupTime option:selected').attr('data-job-id')
                },
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    if (res.html) {
                        viewPostModal(res.html);
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
        function viewPostModal(content) {
            var div = $('#postContent');
            var item = div.html(content);
            var header = $(item).find('.conversation-header').css({
                "display": "none"
            });
            var oldBackground = $(item).find('.background-canvas').addClass('custom-background-canvas');
            var newBackground = $(item).find('.custom-background-canvas').removeClass('background-canvas');
            var contentInfo = $(item).find('.content-info').css({
                "display": "none"
            });
            var oldDate = $(item).find('.date-separator').addClass('custom-date-separator');
            var newDate = $(item).find('.custom-date-separator').removeClass('date-separator').addClass('pt-10 txt-blue');
            var oldContent = $(item).find('.content').addClass('custom-content');
            var newContent = $(item).find('.custom-content').removeClass('content');
            var linkName = $(item).find('.link-name').addClass('txt-blue');
            var linkView = $(item).find('.link-view').addClass('txt-blue');
            $('#postDetailsModal').modal('show');
        }
        //---------------------------------------------------//
    </script>
@endsection
