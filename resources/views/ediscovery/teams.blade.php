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
        .conditions:not(:valid){
            color: #999!important;
        }
        .jobNameForm input {
            min-width: 238px;
        }

        .resetBtn{
            width: 150px!important;
            max-width: 150px!important;
        }
    </style>
    <div class="col-sm-10 navbarLayout">
        <!-- Upper navbar -->
        <ul class="ulNavbar">
            <li>
                <div class="col-sm-2 custom-col-sm-2">
                </div>
            </li>

            <li class="liNavbar custom-nav-border"><a href="{{ url('e-discovery', $arr['kind']) }}">E-Discovery
                    <img class="nav-arrow" src="/svg/arrow-right-active.svg">
                    {{ getDataType($arr['kind']) }}</a></li>
            <li class="liNavbar">
                @if(optional(optional($arr)['job'])->id)
                    <a class="active" href="{{ url('e-discovery', ['edit',$arr['kind'],optional($arr)['job']->restore_session_guid]) }}">Edit E-Discovery Job</a>
                @else
                    <a class="active" href="{{ url('e-discovery', ['add',$arr['kind']]) }}">New E-Discovery Job</a>
                @endif
            </li>
            <!-- Authenticat ion Links -->
            @include('layouts.authentication-links')
        </ul>
    </div>

@endsection
@section('content')
    <script src="{{ url('/js/timepicker/mdtimepicker.js') }}"></script>
    <link href="{{ url('/css/timepicker/mdtimepicker.css') }}" rel="stylesheet" type="text/css">

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

                        <div class="swal-title text-center confirmTitle">Editing E-Discovery Job will delete the previous result</div>
                        <div class="row">
                            <div id="deleteTxt" class="modal-body basic-color text-center mt-22">
                                Are You Sure ?
                            </div>
                        </div>

                        <div class="row mt-10">
                            <div class="input-form-70 inline-flex">
                                <button type="button" class="btn_primary_state allWidth confirmButton mr-25"
                                    onClick="startEdiscovery();">Yes</button>
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
    <div id="mainContent" class="m-0">
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <div id="jobsModal" class="modal modal-center" role="dialog">
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
                        <div class="row mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req">Specify Point in Time
                                </h4>
                            </div>
                        </div>
                        <form class="jobsForm mb-0" onsubmit="createSession(event)">
                            <input type="hidden" name="kind" value="{{ $arr['kind'] }}">
                            <div class="row">
                                <div class="input-form-70 mb-1">Backup Job:</div>
                                <div class="input-form-70 inline-flex">
                                    <select class="form_input form-control w-47 font-size" name="jobs" id="jobs" required>
                                        @php
                                            $selected = optional(optional($arr)['job'])->restore_point_type == 'all' ? 'selected' : '';
                                        @endphp
                                        <option value="" disabled {{ $selected?"":"selected" }}>Select Job</option>
                                        <option value="all" {{ $selected }}>All</option>
                                        @if (!empty($arr['jobs']))
                                            @foreach ($arr['jobs'] as $job)
                                                @php
                                                    $selectedJob = '';
                                                    if (!$selected) {
                                                        $selectedJob = optional(optional($arr)['job'])->backup_job_id == $job->backup_job_id ? 'selected' : '';
                                                    }
                                                @endphp
                                                <option value="{{ $job->backup_job_id }}" {{ $selectedJob }}>
                                                    {{ $job->backup_job_name }}</option>
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
                                    <div class="dropdown-menu dropdown-menu-filter tree-filter-menu">
                                        <div class="divBorderRight"></div>
                                        <div class="divBorderBottom"></div>
                                        <div class="divBorderleft"></div>
                                        <div class="divBorderUp"></div>
                                        <form class="filter-form mb-0" onsubmit="getFilteredTeams(event)">
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
                            <ul id="mainTree" class="tree pl-4">

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
                    <div class="mt-5p col-lg-7 ml-auto">
                        <div id="" class="float-right">
                    <form class="jobNameForm mb-0" onsubmit="jobNameFormSubmit(event)">
                        <input placeholder="Job Name" type="text" value="{{ optional(optional($arr)['job'])->name }}" name="jobName" id="jobName" required
                            class="form_input form-control" />
                    </form>
                </div>
                        <div class="txt-blue float-right"><h5 class="mr-3 pl-3"> Job Name</h5></div>

                    </div>
                </div>

                <form class="teamTableForm">
                    <div class="col-lg-12 left-table">
                        <div class="row" style="margin-bottom: 11px;">
                            <div class="ml-2 col-sm-12 nopadding">
                                <h5 class="txt-blue">Selected Teams & Folders</h5>
                            </div>
                        </div>
                        <div class="row mr-3 tableRow">
                            <table id="selectedTeamTable"
                                class="stripe table table-striped table-dark display nowrap allWidth">
                                <thead class="table-th">
                                    <tr>
                                        <th></th>
                                        <th>Team</th>
                                        <th>Email</th>
                                        <th>Channel</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4">
                                            <div class="text-right allWidth">
                                                <span class="boxesCount"></span> teams selected
                                            </div>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </form>
                <form onsubmit="return addToConditionList(event)">
                    <div class="row main-button-cont">
                        <div class="row ml-2 mt-5">
                            <div class="col-sm-12 nopadding">
                                <h5 class="txt-blue">Search Criteria</h5>
                            </div>
                        </div>
                        <div class="btnMain main-button flex mt-3">
                            <div class="btnUpMask"></div>
                            <div class="row m-0 pl-4 pr-4 allWidth">
                                <div class="col-lg-2 pr-0">
                                    <div class="selected-action allWidth relative">
                                        <select class="form_input form-control font-size category select2 required" required
                                            name="category" required>
                                            {{-- <option value="All" class="tooltipSpan" title="All">All</option> --}}
                                            @if (!empty($arr['categories']))
                                                @foreach ($arr['categories'] as $item)
                                                    <option value="{{ $item }}" class="tooltipSpan"
                                                        title="{{ $item }}">{{ $item }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 pr-0">
                                    <div class="selected-action allWidth relative">
                                        <select class="form_input form-control font-size fields select2 required"
                                            name="fields" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 pr-0">
                                    <div class="selected-action allWidth relative">
                                        <select class="form_input form-control font-size conditions required"
                                            name="conditions" required>
                                            <option disabled value="" style="color:#999!important;">Select Condition</option>
                                            @foreach ($arr['conditions'] as $item)
                                                <option value="{{ $item['name'] }}" data-type="{{ $item['type'] }}">
                                                    {{ $item['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 pr-0">
                                    <div class="selected-action valuesCont allWidth relative flex">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="selected-action allWidth flex justify-content-end">
                                        <button type="submit" class="btn_primary_state addToConditionList mr-4">
                                            <span class="fa fa-plus"></span></button>
                                        <button type="button" class="resetBtn btn_primary_state w-100"
                                            onclick="resetConditionsFields()">
                                            Reset</button>
                                    </div>
                                </div>
                            </div>
                            <div class="btnDownMask"></div>
                        </div>
                    </div>
                </form>
                <form class="conditionsTableForm">
                    <div class="col-lg-12 left-table">
                        <div class="row" style="margin-bottom: 11px;">
                            <div class="ml-2 col-sm-12 nopadding">
                                <h5 class="txt-blue">Selected Conditions</h5>
                            </div>
                        </div>
                        <div class="row mr-3 tableRow">
                            <table id="conditionsTable"
                                class="stripe table table-striped table-dark display nowrap allWidth">
                                <thead class="table-th">
                                    <tr>
                                        <th></th>
                                        <th>Category</th>
                                        <th>Field</th>
                                        <th>Condition</th>
                                        <th>Value</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4">
                                            <div class="text-right allWidth">
                                                <span class="boxesCount"></span> conditions
                                            </div>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </form>
                <div class="row actions-row">
                    <div class="col-lg-8">
                    </div>
                    <div class="col-lg-4 nopadding" style="margin-bottom: 25px;margin-top:25px;">
                        <button onclick="startEdiscovery({{ optional(optional($arr)['job'])->id }})" class="btn_primary_state right-float">
                            Start</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(window).bind('beforeunload',function(){
            $('.loading').css("opacity",100).css("display","block");
            $('body').addClass('removeScroll');
        });
        $(window).on('load', function() {
            if(!"{{ optional(optional($arr)['job'])->id }}" && "{{ optional($arr)['pageType'] }}" != "move"){
                $('#jobsModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#jobsModal').modal('show');
            }
            treeCheckChange();
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
        let channelId = '-1';
        let allowedDates = [];
        //-------------------------------------------------//
        $(document).ready(function() {
            minmizeSideBar();
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

            $('.treeSearchInput input').keyup(searchTeam);

            $("input.date").datepicker({
                dateFormat: 'yy-mm-dd'
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
            //------------------------------------------//
            $('#selectedTeamTable').DataTable({
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
                        "class": "after-none",
                        "render": function() {
                            return '<img class= "tableIcone"  style="margin-left:0px;width: 13px; margin-right:0;" src="/svg/discovery1.svg " title="E-Discovery Job">';
                        }
                    },
                    {
                        "data": "teamName",
                        "class": "text-left",
                        "width": "30%"
                    },
                    {
                        "data": "email",
                        "width": "30%"
                    },
                    {
                        "data": "channelName",
                        "width": "30%"
                    }, {
                        "data": null,
                        "width": "10%",
                        "render": function(data) {
                            return '' +
                                '<input type="hidden" name="teamId[]" value="' + data.teamId +
                                '">' +
                                '<input type="hidden" name="channelId[]" value="' + data.channelId +
                                '">' +
                                '<input type="hidden" name="teamName[]" value="' + data
                                .teamName + '">' +
                                '<input type="hidden" name="channelName[]" value="' + data
                                .channelName + '">' +
                                '<input type="hidden" name="email[]" value="' + data.email + '">' +
                                '<a data-teamId="' + data.teamId +
                                '" data-channelId="' + data.channelId +
                                '" title="Delete" class="deleteTeams"><img class="tableIcone w-13" src="/svg/Delete.svg"></a>';
                        }
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function(data) {
                    $('.deleteTeams').click(deleteTeamSelectedItem);
                    $('#selectedTeamTable_wrapper').find('.boxesCount').html($(
                        '#selectedTeamTable').DataTable().data().count());
                },
                "scrollY": "170px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0, 4], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //------------------------------------------//
            $('#conditionsTable').DataTable({
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
                        "class": "after-none",
                        "render": function() {
                            return '<img class= "tableIcone"  style="margin-left:0px;width: 13px; margin-right:0;" src="/svg/discovery1.svg " title="E-Discovery Job">';
                        }
                    },
                    {
                        "data": "category",
                        "class": "text-left",
                        "width": "20%"
                    },
                    {
                        "data": "field",
                        "width": "20%"
                    },
                    {
                        "data": "condition",
                        "width": "20%"
                    }, {
                        "data": "value",
                        "width": "20%"
                    }, {
                        "data": null,
                        "render": function(data) {
                            return '' +
                                '<input type="hidden" name="category[]" value="' + data.category +
                                '">' +
                                '<input type="hidden" name="field[]" value="' + data.field + '">' +
                                '<input type="hidden" name="condition[]" value="' + data.condition +
                                '">' +
                                '<input type="hidden" name="value[]" value="' + data.value + '">' +
                                '<a title="Delete" class="deleteConditions"><img class="tableIcone w-13" src="/svg/Delete.svg"></a>';
                        }
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {
                    $('.deleteConditions').click(deleteConditionsRows);
                    $('#conditionsTable_wrapper').find('.boxesCount').html($('#conditionsTable')
                        .DataTable().data().count());
                    if($('#conditionsTable').DataTable().data().count() > 0)
                        $('.category').attr("disabled","disabled");
                    else
                        $('.category').removeAttr("disabled");
                },
                "scrollY": "170px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0, 5], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //------------------------------------------//
            //------------------------------//
            $('select.category').select2({
                allowClear: true,
                placeholder: {
                    id: "-1",
                    text: "Select Category",
                    selected: 'selected'
                },
            });
            $("select.category").select2("val", "-1");
            let data = @json($arr['categoriesFields']);
            //---------------------------------//
            let categoryFields = $.map(data['All'], function(item) {
                return {
                    id: item['code'],
                    text: item['name'],
                    type: item['type']
                };
            });
            categoryFields = categoryFields.sort(function(a, b){return a.text.localeCompare(b.text)});
            //---------------------------------//
            $('select.fields').empty().select2({
                allowClear: true,
                placeholder: {
                    id: "-1",
                    text: "Select Field",
                    selected: 'selected'
                },
                data: categoryFields,
                templateResult: function(data, container) {
                    $(data.element).attr('data-type', data.type);
                    return data.text;
                }
            });
            //------------------------------//
            $("select.fields").select2("val", "-1");
            //---------------------------------//
            $('select.category').change(function() {
                $('select.fields').val();
                let category = $(this).val();
                if (!category)
                    return false;
                categoryFields = data[category];
                categoryFields = $.map(categoryFields, function(item) {
                    return {
                        id: item['code'],
                        text: item['name'],
                        type: item['type']
                    };
                });
                categoryFields = categoryFields.sort(function(a, b){return a.text.localeCompare(b.text)});
                $('select.fields').empty().select2({
                    allowClear: true,
                    placeholder: {
                        id: "-1",
                        text: "Select Field",
                        selected: 'selected'
                    },
                    data: categoryFields,
                    templateResult: function(data, container) {
                        $(data.element).attr('data-type', data.type);
                        return data.text;
                    }
                });
                $("select.fields").select2("val", "-1");
            })
            //------------------------------//
            $('select.fields').change(function() {
                let type = $('.fields option:selected').attr('data-type');
                $('select.conditions').val("");
                $('.valuesCont').html('');
                $('select.conditions option[data-type]').addClass('hide');
                $('select.conditions option[data-type="' + type + '"]').removeClass('hide');
            }).change();
            //------------------------------//
            $('select.conditions').change(getConditionValue)
            //------------------------------//
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
                        statusCode: {
                            401: function() {
                                window.location.href = "{{ url('/') }}";
                            },
                            402: function() {
                                let errMessage = "   ERROR   ";
                                $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
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
            //------------------------------------------//
            if("{{ optional(optional($arr)['job'])->id }}" || "{{ optional($arr)['pageType'] }}" == "move"){
                setBackupJobPoints();
                setTreeTeams();
                teamCheckChange();
                setConditionsTable();
            }
            //------------------------------------------//
        });
        //-------------------------------------------------//
        //---- Edit Functions
        //----------------------------------------------------//
        function setBackupJobPoints(){
            let data = @json(optional($arr)['jobPoints']);
            let selectedDate = formatDateWithoutTime("{{ optional(optional($arr)['job'])->restore_point_time }}")
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
            $('.backupDate').val(selectedDate);
            //------------------------------------------//
            $('.backupTime').html("");
            $(".backupTime").append(new Option("Select Time", ""));
            allowedDates.forEach((e) => {
                if (e.date == selectedDate) {
                    e.time.forEach((t) => {
                        $(".backupTime").append('<option value="' + t.id +
                            '" data-job-id="' + t.job_id + '">' + t.time +
                            '</option>');
                    })
                }
            });
            $('.backupTime').removeAttr('disabled');
            $('.backupTime').val("{{ optional(optional($arr)['job'])->restore_point_time }}");
            //------------------------------------------//
            $('#showDeleted').prop("checked","{{ optional(optional($arr)['job'])->is_restore_point_show_deleted }}" == 1? true: false)
            $('#showVersions').prop("checked","{{ optional(optional($arr)['job'])->is_restore_point_show_version }}"== 1? true: false)
            //------------------------------------------//
        }
        //----------------------------------------------------//
        function setTreeTeams() {
            let data = @json(optional($arr)['teams']);
            $('#activeclose').removeAttr('disabled');
            $("#mainTree").html("");
            $("#choose").text("Change")
            data.forEach(function(result) {
                mainItem =
                    '<li class="has teamLi hand relative teamCont" data-teamId="' + result
                    .id + '"><div class="relative allWidth">' +
                    '<span class="caret mailCaret '+(result.channels?"":"closeMail")+'" onclick="getTeamChannels(event)"></span>' +
                    '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                    '<input type="checkbox" class="teamCheck form-check-input" value="' +
                    result.id + '" data-email="' + result.groupEmail + '" '+(result.selected?"checked":"")+' />' +
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
                    (result.channels?setTeamChannels(result):'')+
                    '</li>';
                $("#mainTree").append($(mainItem));
            });
            $("#rdate").html($(".backupDate").val());
            $("#rtime").html($(".backupTime").find(":selected")
                .text());
            $('.tree input.teamCheck[type=checkbox]:not([data-parent-checked]),.tree input.channelCheck[type=checkbox]:not([data-parent-checked])')
                .attr('data-parent-checked', false);
            $('.has.teamLi').has(".mailCaret:not(.closeMail)").find('> ul').fadeIn();
        }
        //----------------------------------------------------//
        function setTeamChannels(data){
            let teamChannels = '<ul class="pt-0 pb-0 mb-0">';
            data.channels.forEach((result)=>{
                teamChannels = teamChannels +
                    '<li class="teamChannel" data-channelId="' + result.id +
                    '"><div class="relative allWidth inline-flex">' +
                    '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                    '<input type="checkbox" class="channelCheck form-check-input" value="' +
                    result.id + '" data-parentId="'+data.id+'" '+(result.selected?"checked":"")+' data-parent-checked="'+(data.selectedParent?true:false)+'"/>' +
                    '<span class="tree-checkBox check-mark"></span></label>' +
                    '<img class="folderIcon" src="/svg/folders/none.svg">' +
                    '<span id="' + result.id +
                    '" data-teamId="' + data.id +
                    '" class="item-click childmail-click item-folder-click channel-click" title="' +
                    result.displayName + '">' +
                    result.displayName + '</span></div>' +
                    '</li>';
                });
            teamChannels = teamChannels + "</ul>";
            return teamChannels;
        }
        //----------------------------------------------------//
        function setConditionsTable(){
            let conditionsData = JSON.parse(@json(optional(optional($arr)['job'])->search_criteria));
            if(conditionsData)
                conditionsData.forEach(function(e){
                    let row = {
                        "category": e.category,
                        "field": e.field,
                        "condition": e.condition,
                        "value": e.value
                    };
                    $('#conditionsTable').DataTable().row.add(row);
                    $('#conditionsTable').DataTable().columns.adjust().draw();
                });
        }
        //----------------------------------------------------//




        //----------------------------------------------------//
        function searchTeam() {
            var value = $(this).val();
            jQuery.expr[':'].contains = function(a, i, m) {
                return jQuery(a).text().toUpperCase()
                    .indexOf(m[3].toUpperCase()) >= 0;
            };
            $('li.has').addClass('hide');
            var values = $('li .mail-click:contains("' + value + '")');
            values.closest('.has').removeClass('hide');
            //$('li.has.hide').find('input:checked').removeAttr('checked');
        }
        //----------------------------------------------------//
        function treeCheckChange() {
            $(document).on('change','.tree input.teamCheck[type=checkbox],.tree input.channelCheck[type=checkbox]',
                function(e) {
                    //---- Change All Children
                    $(this.closest('div')).siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);
                    $(this).parentsUntil('.tree').children("input[type='checkbox']").prop('checked', this.checked);
                    $(this).closest('li').find('ul .channelCheck').attr('data-parent-checked', $(this).prop(
                        'checked'));
                    //----------------------------------------------//
                    if (!this.checked) {
                        $('.tree [value="' + $(this).attr('data-parentId') + '"]').prop('checked', false);
                        $('.tree [data-parentId="' + $(this).attr('data-parentId') + '"]').attr('data-parent-checked',false);
                        if ($('.tree [value="' + $(this).attr('data-parentId') + '"]').attr('data-parentId'))
                            uncheckAllParents($('.tree [value="' + $(this).attr('data-parentId') + '"]').attr('data-parentId'));
                    }
                    //----------------------------------------------//
                    teamCheckChange($(this));
                });
        }
        //----------------------------------------------------//
        function uncheckAllParents(item) {
            $('.tree [value="' + item + '"]').prop('checked', false);
            $('.tree [data-parentId="' + item + '"]').attr('data-parent-checked',false);
            if ($('.tree [value="' + item + '"]').attr('data-parentId')) {
                uncheckAllParents($('.tree [value="' + item + '"]').attr('data-parentId'));
            }
        }
        //----------------------------------------------------//
        function teamCheckChange() {
            //---------------------------//
            let selectedTeams = $('#mainTree .teamCheck:checked');
            let data = [];
            selectedTeams.each(function() {
                let id = $(this).val();
                data.push({
                    "teamId": id,
                    "email": $(this).attr('data-email'),
                    "teamName": $('.has.teamLi #' + id).html(),
                    "channelId": "-1",
                    "channelName": ""
                });
            });
            let selectedFolders = $('#mainTree .channelCheck:checked[data-parent-checked="false"]');
            selectedFolders.each(function() {
                let id = $(this).val();
                let teamId = $('.tree [id="'+id+'"]').attr('data-teamId');
                data.push({
                    "teamId": teamId,
                    "channelId": id,
                    "email": $('.tree [value="' + teamId + '"]').attr('data-email'),
                    "teamName": $('.tree #' + teamId).html(),
                    "channelName": $('.tree [id="'+id+'"]').html(),
                });
            });
            $('#selectedTeamTable').DataTable().clear().draw();
            $('#selectedTeamTable').DataTable().rows.add(data); // Add new data
            $('#selectedTeamTable_wrapper').find('.boxesCount').html(data.length);
            $('#selectedTeamTable').DataTable().columns.adjust().draw();
        }
        //----------------------------------------------------//
        function getParentFolderName(channelId) {
            if ($('#' + channelId).hasClass('mail-folder-click'))
                return getParentFolderName($('.tree .channelCheck[value="' + channelId + '"]').attr('data-parentId')) +
                    "/" + $('#' + channelId).html()
            else
                return '';
        }
        //----------------------------------------------------//
        function deleteTeamSelectedItem() {
            let teamId = $(this).attr('data-teamId');
            let channelId = $(this).attr('data-channelId');
            if (channelId != -1)
                $('.tree .channelCheck[value="' + channelId + '"]').prop('checked', false).change();
            else
                $('.tree .teamCheck[value="' + teamId + '"]').prop('checked', false).change();
        }
        //----------------------------------------------------//
        function deleteConditionsRows() {
            let row = $(this).closest('tr');
            $('#conditionsTable').DataTable().row(row).remove().draw();
        }
        //----------------------------------------------------//
        function addToConditionList(event) {
            event.preventDefault();
            let value = $('.value').val() ?? $('select.conditions').val();
            let sec_value = ($('.sec_value').length ? " - " + $('.sec_value').val() : "");
            if (!checkDuplicateConditionRow(value)) {
                let row = {
                    "category": $('select.category').val(),
                    "field": $('select.fields option:selected').html(),
                    "condition": $('select.conditions').val(),
                    "value": value + sec_value
                };
                $('#conditionsTable').DataTable().row.add(row);
                $('#conditionsTable').DataTable().columns.adjust().draw();
            } else {
                showErrorMessage("{{ __('variables.alerts.ediscovery.alert_duplicate_condition') }}");
            }
        }
        //----------------------------------------------------//
        function checkDuplicateConditionRow(value,sec_value='') {
            let rows = $("#conditionsTable tbody tr");
            let isExist = false;
            rows.each(function() {
                let row = $(this);
                let field = row.find("td:nth-child(3):contains('" + $('.fields option:selected').html() + "')");
                let condition = row.find('td:nth-child(4):contains("' + $('.conditions').val() + '")');
                let $value = row.find("td:nth-child(5):contains('" + value + "')");
                if (field.length && condition.length && $value.length)
                    isExist = true;
            });
            return isExist;
        }
        //----------------------------------------------------//
        function resetConditionsFields() {
            $('.category').val('').change();
            $('.fields').val('').change();
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

        //---- Global Variables Function
        function getAllowedDates() {
            return allowedDates;
        }
        //---------------------------------------------------//
        function setAllowedDates(dates) {
            allowedDates = JSON.parse(dates);
        }
        //---------------------------------------------------//


        //---- Ajax Requests
        function createSession(event) {
            event.preventDefault()
            teamId = -1;
            channelId = -1;
            $('#itemsTable').DataTable().ajax.reload();
            $('.warningRow,.stoppingRow').addClass('hide');

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
                        $("#activeclose").removeAttr('disabled')
                        $("#mainTree").html("");
                        $(".spinner_parent").css("display", "none");
                        $('body').removeClass('removeScroll');
                        $("#choose").text("Change")
                        //----------------------------------------------//
                        data.data.forEach(function(result) {
                            mainItem =
                                '<li class="has teamLi hand relative teamCont" data-teamId="' + result
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
                            $("#mainTree").append($(mainItem));
                        });
                        //----------------------------------------------//
                        $("#rdate").html($(".backupDate").val());
                        $("#rtime").html($(".backupTime").find(":selected")
                            .text());
                        $('#jobsModal').modal('hide');
                        $('.tree input.teamCheck[type=checkbox][data-parent-checked!=true],.tree input.channelCheck[type=checkbox][data-parent-checked!=true]')
                            .attr('data-parent-checked', false);
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
                    statusCode: {
                        401: function() {
                            window.location.href = "{{ url('/') }}";
                        },
                        402: function() {
                            let errMessage = "   ERROR   ";
                            $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
                            $(".danger-oper").css("display", "block");
                            setTimeout(function() {
                                                        $(".danger-oper").css("display", "none");
                        window.location.reload();
                            }, 3000);
                        }
                    },
                    error: function(error) {
                        $("#mainTree").html("");

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
                        let parentChecked = $('.tree [value="'+teamId+'"]').prop("checked");
                        teamChannels = teamChannels +
                            '<li class="teamChannel" data-channelId="' + result.id +
                            '"><div class="relative allWidth inline-flex">' +
                            '<label class="checkbox-padding-left10 checkbox-container checkbox-search">&nbsp;' +
                            '<input type="checkbox" class="channelCheck form-check-input" value="' +
                            result.id + '" data-parentId="'+teamId+'" '+(parentChecked?"checked":"")+' data-parent-checked="'+parentChecked+'"/>' +
                            '<span class="tree-checkBox check-mark"></span></label>' +
                            '<img class="folderIcon" src="/svg/folders/none.svg">' +
                            '<span id="' + result.id +
                            '" data-teamId="' + teamId +
                            '" class="item-click childmail-click item-folder-click channel-click" title="' +
                            result.displayName + '">' +
                            result.displayName + '</span></div>' +
                            '</li>';
                    });
                    teamChannels = teamChannels + "</ul>";
                    //-------------//
                    $('.teamCont[data-teamId="' + teamId + '"]').find('.folder-spinner:first').addClass('hide');
                    //-------------//
                    $('.teamCont[data-teamId="' + teamId + '"]').append($(teamChannels)[0]);
                    $('.teamCont[data-teamId="' + teamId + '"]').find('ul:first').fadeToggle();
                    //-------------//
                },
                statusCode: {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
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
                '<input type="hidden" class="channelId" value="' + data.channelId +
                '">' +
                '<input type="hidden" class="folderTitle" value="' + data.folderTitle + '">' +
                '<input type="checkbox" data-type="' + data.type + '" data-isFolder="' + data.isFolder +
                '" class="contentFolderItemCheck form-check-input" value="' +
                data.id + '"/>' +
                '<span class="tree-checkBox check-mark"></span></label>' +
                '</td>' +
                '<td class="text-left" style="background-color: #343a40!important;">' +
                '<a type="button" class="viewPost">' + data.author + '</a>' + '</td>' +
                '<td style="background-color: #343a40!important;">' + data.subject + '</td>' +
                '<td style="background-color: #343a40!important;">' + formatDate(data.createdTime) + '</td>' +
                '<td style="background-color: #343a40!important;">' + formatDate(data.lastModifiedTime) + '</td>' +
                '<td style="background-color: #343a40!important;" class=" after-none"><img class= "hand tableIcone downloadDocument w-13 mr-0" src="/svg/download\.svg " title="Download"></td>' +
                '<td class="after-none" style="background-color: #343a40!important;">' + isImportant + '</td>' +
                '<td class="custom-after-none" style="background-color: #343a40!important;">' + attachments + '</td>' +
                '</tr>';
        }
        //---------------------------------------------------//
        function getChannelContent(event) {
            $target = $(event.target);
            //---------------------------------------------//
            if ($target.hasClass('mailCaret')) {
                channelId = $target.closest('.teamCont').find('.channel-click').attr('id');
            } else {
                channelId = $target.attr('id');
            }
            //---------------------------------------------//
            $('.teamChannel[data-channelId="' + channelId + '"]').toggleClass('closeMail');
            $('.teamChannel[data-channelId="' + channelId + '"]').find('ul:first').fadeToggle();
            //---------------------------------------------//
        }
        //---------------------------------------------------//
        function getFilteredTeams(event) {
            if(event)
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
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    $("#mainTree").html('');
                    res.forEach(function(result) {
                            mainItem =
                                '<li class="has teamLi hand relative teamCont" data-teamId="' + result
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
                            $("#mainTree").append($(mainItem));
                        });
                    $('.filter-icon').click();
                    $('.tree input.teamCheck[type=checkbox][data-parent-checked!=true],.tree input.channelCheck[type=checkbox][data-parent-checked!=true]')
                        .attr('data-parent-checked', false);
                },
                statusCode: {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
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
        function getConditionValue(event) {
            event.preventDefault()
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $('.valuesCont').html('');
            $.ajax({
                type: "GET",
                url: "{{ url('getConditionValue') }}/{{ $arr['kind'] }}",
                data: {
                    type: $('select.fields option:selected').data('type'),
                    condition: $('select.conditions').val(),
                    field: $('select.fields').val(),
                },
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    //------------------------------------------//
                    $('.valuesCont').html(data);
                    //------------------------------------------//
                    $('.date').datepicker();
                    //------------------------------------------//
                },
                statusCode: {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
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
        function startEdiscovery(id=-1) {
            //---------------------------------------------------//
            if(id != -1)
                return $('#confirmationModal').modal('show');
            $('#confirmationModal').modal('hide');
            //---------------------------------------------------//
            if (!$(".jobNameForm")[0].checkValidity())
                return $(".jobNameForm")[0].reportValidity()
            if ($('.teamTableForm [name="teamId[]"]').length == 0)
                return showErrorMessage("{{ __('variables.alerts.ediscovery.alert_choose_search_items') }}");
            if ($('.conditionsTableForm [name="category[]"]').length == 0)
                return showErrorMessage("{{ __('variables.alerts.ediscovery.alert_choose_search_criteria') }}");
            if (!$('.jobsForm .backupTime option:selected').val())
                return showErrorMessage("{{ __('variables.alerts.ediscovery.alert_choose_job_point') }}");
            //---------------------------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            let conditionsData = $('.conditionsTableForm').serialize();
            let teamData = $('.teamTableForm').serialize();
            let jobData = $('.jobsForm').serialize() +
                "&jobId=" + $('.backupTime option:selected').attr('data-job-id') +
                "&showDeleted=" + $("#showDeleted")[0].checked +
                "&showVersions=" + $("#showVersions")[0].checked +
                "&jobName=" + $("#jobName").val() +
                "&ediscoveryJobId={{ optional(optional($arr)['job'])->id }}";
            $.ajax({
                type: "POST",
                url: "{{ url('saveEDiscoveryJob') }}",
                data: jobData + '&' + teamData + "&" + conditionsData + "&_token={{ csrf_token() }}",
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    //------------------------------------------//
                    window.location.href = "{{ url('e-discovery', $arr['kind']) }}";
                    //------------------------------------------//
                },
                statusCode: {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
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
        function jobNameFormSubmit(event){
            event.preventDefault();
            return false;
        }
        //---------------------------------------------------//
    </script>
@endsection
