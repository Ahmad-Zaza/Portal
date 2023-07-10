@extends('layouts.app')
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/main.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/tabs.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/repositories.css') }}" />
<link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/generalElement.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('/css/restore-customize.css') }}" />
<style>
    .resumeLoad {
        width: 150px;
    }

    .check-mark:after {
        left: 3px !important;
        top: -5px !important;
        width: 6px !important;
        height: 12px !important;
    }
</style>
@section('topnav')
    <div class="col-sm-10 navbarLayout">
        <!-- Upper navbar -->
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm upperNavBar"> -->
        <ul class="ulNavbar">

            <li class="liNavbar"><a href="{{ url('backup', $arr['typeId']) }}">Backup Jobs <img class="nav-arrow"
                        src="/svg/arrow-right.svg"> SharePoint</a></li>
            <li class="liNavbar"><a class="active" href="{{ url('backup', [$arr['typeId'], 'add']) }}">New
                    Job</a></li>
            <!-- Authentication Links -->
            @include('layouts.authentication-links')
        </ul>
    </div>
@endsection

@section('content')
    <script src="{{ url('/js/timepicker/mdtimepicker.js') }}"></script>
    <link href="{{ url('/css/timepicker/jquerysctipttop.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('/css/timepicker/mdtimepicker.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('/css/checkbox.css') }}" rel="stylesheet" type="text/css">
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="mainContent">
        <form class="backupForm">
            <!-- Create new repository button  -->
            <div class="row" style="margin-bottom: 11px;">
                <div class="col-sm-12 nopadding">
                    <h5 class="txt-blue">Job Details</h5>
                </div>
            </div>
            <div class="row newJobRow" style="margin-bottom: 60px;">
                <div class="rowBorderRight"></div>
                <div class="rowBorderBottom"></div>
                <div class="rowBorderleft"></div>
                <div class="rowBorderUp"></div>
                <div class="col-lg-5">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="jobName">Job Name</label>
                            <div class="col-sm-8">
                                <input type="text" required class="required form_input form-control"
                                    value="{{ $arr['job']->name }}" id="jobName" placeholder="Enter Job Name" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="jobDesc">Job Description</label>
                            <div class="col-sm-8">
                                <textarea id="jobDesc" required class="required form_input form-control" name="jobDesc" rows="1" maxlength="500"
                                    placeholder="Enter Job Description">{{ $arr['job']->description }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="repositoryName">Repository Name</label>
                            <div class="col-sm-8">
                                <select class="form_input form-control required" required name="repositories"
                                    id="repositories">
                                    <option value="" disabled>Select Repository</option>
                                    @if (!empty($arr['repos']))
                                        @foreach ($arr['repos'] as $repo)
                                            @if ($repo->id == $arr['backupRepo'])
                                                <option selected value="{{ $repo->id }}">{{ $repo->name }}</option>
                                            @else
                                                <option value="{{ $repo->id }}">{{ $repo->name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-2">
                </div>
                <div class="col-lg-5">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="schedule">Run Schedule</label>
                            <div class="col-sm-8">
                                <select class="form_input form-control" name="schedule" id="schedule">
                                    <option value="1">Automatic</option>
                                    <option value="2">Manual</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="dailyTime">Daily At This Time</label>
                            <div class="col-sm-8">
                                <div style="position: relative">
                                    <input type="text" class="form_input form-control" id="timepicker" />
                                    <img class="timepicker-icon nav-icon" src="{{ url('/svg/calendar.svg') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="days">Every</label>
                            <div class="col-sm-8">
                                <select class="form_input form-control required" required name="days" id="days">
                                    <option value="EveryDay">Every Day</option>
                                    <option value="Sunday">Sunday</option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
            {{-- <div class="row">
                <button onclick="resumeLoad()" type="button"
                    class="btn-sm z-index-500 custombtn_cancel_primary_state btn_cancel_primary_state resumeLoad hand hide">Load
                    All Sites</button>
            </div> --}}
            <div class="row" style="margin-bottom: 60px;">
                <div class="col-sm-12 nopadding" style="margin-bottom: 11px;">
                    <h5 class="txt-blue" style="width: fit-content;" data-placement="bottom" style="cursor: pointer;"
                        data-toggle="tooltip"
                        title="This list have users and groups that don’t exist in other Backup Jobs">
                        Filter & Select Data To Backup</h5>
                </div>
                <div class="col-sm-12 nopadding">
                    <div class="radioDiv flex">
                        @php $allItems = optional($arr['selectedItems'][0])->type == "PartialOrganization"? true:false @endphp
                        <div class="radio m-0">
                            <label class="mr-4">
                                <input type="radio" name="itemsType" class="itemsType"
                                    @if (!$allItems) checked @endif value="selectedItems">Selected
                                Items
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="radio" style="margin-top: 0!important;">
                            <label class="mr-4">
                                <input type="radio" name="itemsType" class="itemsType" value="allItems"
                                    @if ($allItems) checked @endif>All Items
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div id="selectedItemsCont" class="allWidth">
                    <div class="col-sm-12 pl-0">
                        <button onclick="resumeLoad()" type="button"
                            class="btn-sm z-index-500 d-block relative custombtn_cancel_primary_state btn_cancel_primary_state resumeLoad hand hide">Load
                            All Sites</button>
                    </div>
                    <div class="col-sm-12" style="margin-top: -31px">
                        <div id="tab1" class="tab-content active">
                            <div class="row">
                                <div class="allWidth">
                                    <table id="sitesTable" class="stripe table table-striped table-dark "
                                        style="width:100%">
                                        <thead class="table-th">
                                            <th style="text-align: left;padding-left: 35px;"> Name </th>
                                            <th>URL</th>
                                            <th style="padding-left: 4px;">
                                                <label class="checkbox-container">&nbsp;
                                                    <input type="checkbox" class="form-check-input" id="allSites" />
                                                    <span class="check-mark"></span>
                                                </label>
                                                Backup
                                            </th>
                                        </thead>
                                        <tbody id="table-content1">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th><span class="sitesCount">0 Sites</span> <span class="sitessum">0 Sites
                                                        Selected</span></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="allItemsCont" class="hide allWidth">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12 nopadding">
                                <div class="allWidth">
                                    <table id="allItemsTable" class="stripe table table-striped table-dark"
                                        style="width:100%">
                                        <thead class="table-th">
                                            <th style="text-align: left;padding-left: 35px;">Members</th>
                                            <th style="padding-left: 4px;">
                                                Sites
                                            </th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="left-col">Organization</td>
                                                <td>
                                                    @php
                                                        $allSites = optional($arr['selectedItems'][0])->site;
                                                    @endphp
                                                    <label class="checkbox-container" style="left: 46%;">&nbsp;
                                                        <input type="checkbox" class="form-check-input"
                                                            id="allOrganizationSites"
                                                            @if ($allSites) checked @endif />
                                                        <span class="check-mark"></span>
                                                    </label>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- All repositories table -->
            <div class="row resultTableCont">
                <div class="col-sm-4 nopadding">
                    <h5 class="txt-blue" style="width: fit-content;cursor: pointer;" data-placement="bottom">Selected
                        Sites</h5>
                </div>
                <div class="col-sm-12 nopadding" style="margin-top: -31px;">
                    <div>
                        <table id="result-table" class="table table-striped table-dark selected-items-table">
                            <thead class="table-th">
                                <th> Name </th>
                                <th>URL</th>
                                <th>Backup</th>
                            </thead>
                            <tbody id="result-table-content">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="ressitessum" colspan="3">0 Sites Selected</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row actions-row" style="margin-bottom: 25px;margin-top:25px;">
                <div class="col-lg-8">
                    <form method="post" />
                    @csrf
        </form>
    </div>
    <div class="col-lg-4 nopadding">
        <a href="{{ url('backup', $arr['typeId']) }}" class="cancel-button btn_primary_state right-float mr-0">
            Cancel Backup Job</a>
        <button onclick="addBackupJob(event)" class="btn_primary_state right-float">
            Save Backup Job</button>
    </div>
    </div>
    </form>
    </div>
    <script>
        let offset = 0;
        let limit = 50;
        let done = false;
        let xhr = null;
        $(function() {
            $('.itemsType').change(function() {
                let value = $('.itemsType:checked').val();
                if (value == "selectedItems") {
                    $('#selectedItemsCont,.resultTableCont').removeClass('hide');
                    $('#allItemsCont').addClass('hide');
                } else {
                    $('#selectedItemsCont,.resultTableCont').addClass('hide');
                    $('#allItemsCont').removeClass('hide');
                }
            }).change();
            $('#allItemsTable').DataTable({
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('id', data.id);
                },
                "fnDrawCallback": function() {},
                "bInfo": false,
                "paging": false,
                "autoWidth": false,
                "searching": false,
                "processing": false,
                dom: 'Bfrtip',
                buttons: [],
            });
        })
        $(window).load(function() {
            $('.submenu-backup .backup3').addClass('active');
            var row = $('a.sub-menu-link.active').closest('.row');
            row.find('.left-nav-list').addClass('active').removeClass('collapsed');
            $('.submenu-backup').addClass('in');

            $('#timepicker').mdtimepicker({
                is24hour: true,
                format: 'hh:mm'
            });
            $('.timepicker-icon').click(function() {
                $("#timepicker").click();
            });
            $('.nav-tabs > li > a').click(function(event) {
                event.preventDefault(); //stop browser to take action for clicked anchor
                //get displaying tab content jQuery selector
                var active_tab_selector = $('.nav-tabs > li.active > a').attr('href');
                //find actived navigation and remove 'active' css
                var actived_nav = $('.nav-tabs > li.active');
                actived_nav.removeClass('active');
                //add 'active' css into clicked navigation
                $(this).parents('li').addClass('active');
                //hide displaying tab content
                $(active_tab_selector).removeClass('active');
                $(active_tab_selector).addClass('hide');
                //show target tab content
                var target_tab_selector = $(this).attr('href');
                $(target_tab_selector).removeClass('hide');
                $(target_tab_selector).addClass('active');
            });

            $('#result-table').DataTable({
                'columns': [{
                        "class": "col-lg-2 left-col",
                        "data": "name"
                    },
                    {
                        "class": "col-lg-3 left-col",
                        "data": "url",
                    },
                    {
                        "data": null,
                        "class": "col-lg-2",
                        "render": function(data) {
                            let res = `<input type="hidden" class="resultId" value="${data.id}" />`;
                            if (data.backupSite) {
                                res = res + `<i class="fa fa-check"></i>`;
                            }
                            res +=
                                '<textarea class="siteDetails hide" sytle="display:none">' + data
                                .siteDetails + '</textarea>';
                            return res;
                        }
                    },
                ],
                "fnDrawCallback": function() {
                    var icon =
                        '<div class="search-container"><img class="search-icon" src="/svg/search.svg"></div>';
                    if ($("#result-table_filter label").find('.search-icon').length == 0)
                        $('#result-table_filter label').append(icon);
                    $('#result-table_filter input').addClass('form_input form-control');
                },
                "scrollY": "250px",
                "scrollCollapse": true,
                "bInfo": false,
                "paging": false,
                "autoWidth": false,
                "processing": false,
                dom: 'Bfrtip',
                buttons: [

                    {
                        extend: 'csvHtml5',
                        text: '<img src="/svg/excel.svg" style="width:15px;height:30px;">',
                        titleAttr: 'Export to csv',
                        exportOptions: {
                            format: {
                                body: function(data, column, row) {
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
                        extend: 'pdfHtml5',
                        text: '<img src="/svg/pdf.svg" style="width:15px;height:30px;">',
                        customize: function(doc) {
                            customizePdf(doc)
                        },
                        titleAttr: 'Export to pdf',
                        exportOptions: {
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

                    }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                }
            });
            $('#result-table').DataTable().buttons().container()
                .prependTo('#result-table_filter');

            $('#sitesTable').DataTable({
                "initComplete": function(settings, json) {
                    // loadSitesData();
                    let selectedSites = @json($arr['selectedItems']);
                    selectedSites.forEach(function(element) {
                        let row = $('#sitesTable tr#' + element.site.id);
                        row.find(".backupsite").attr("checked", true);
                    });
                    fillResultTable();
                },
                createdRow: function(row, data, dataIndex) {

                    $(row).attr('id', data.id);

                },
                "fnDrawCallback": function() {
                    var icon =
                        '<div class="search-container"><img class="search-icon" src="/svg/search.svg"></div>';
                    if ($("#sitesTable_filter label").find('.search-icon').length == 0)
                        $('#sitesTable_filter label').append(icon);
                    $('#sitesTable_filter input').addClass('form_input form-control');
                    let total = $('#sitesTable').DataTable().data().count();
                    $(".sitesCount").html(total + " Sites");

                    if (offset > 0 && total == offset && !done) {
                        $(".resumeLoad").html("Loading...").prop("disabled", "disabled").removeClass(
                            "hand");
                        setTimeout(function() {
                            resumeLoad();
                        }, 500);
                    } else if (offset == 0 && !done) {
                        offset = $('#sitesTable').DataTable().data().count();
                        $(".resumeLoad").removeClass('hide');
                    } else {
                        $(".resumeLoad").addClass('hide');
                    }
                },
                'ajax': {
                    "type": "GET",
                    "url": "{{ url('getOrganizationSites') }}",
                    "dataSrc": '',
                    "data": {
                        offset: getOffset(),
                    },
                    "dataType": "json",
                    'beforeSend': function(request) {
                        request.setRequestHeader("connection", "keep-alive");
                    }
                },
                "scrollY": "300px",
                "scrollCollapse": true,
                "bInfo": false,
                "paging": false,
                "autoWidth": false,
                "processing": true,
                "columns": [{
                        "class": "col-lg-4 left-col",
                        "data": "name"
                    },
                    {
                        "class": "col-lg-6 left-col",
                        "data": "url"
                    },
                    {
                        "class": "col-lg-2",
                        "data": null,
                        render: function(data, type, full, meta) {
                            let res = '  <label class="checkbox-container">&nbsp;' +
                                '<input id="checkbox" type="checkbox" class="form-check-input backupsite" />' +
                                '<span class="check-mark"></span>' +
                                '</label>' +
                                '<textarea class="siteDetails hide" sytle="display:none">' + JSON
                                .stringify(data) + '</textarea>';
                            return res;
                        }
                    }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                    'loadingRecords': '&nbsp;',
                    'processing': '<div class="spinner"></div>'
                },
                'columnDefs': [{
                    'targets': [2], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $("#sitesTable").on('change', ".form-check-input", function(e) {
                fillResultTable();
            });

            @if ($arr['job']->schedulePolicy->scheduleEnabled == false)
                $("#schedule option[value='2']")[0].selected = true;
                $("#timepicker").val("");
                $("#days").val("");
                $("#timepicker").attr("disabled", "disabled");
                $("#days").attr("disabled", "disabled");
            @else
                $("#timepicker").val("{{ optional($arr['job']->schedulePolicy)->dailyTime }}")
                $("#days option[value='{{ optional($arr['job']->schedulePolicy)->dailyType }}']").selected =
                    true;
                $("#schedule option[value='1']")[0].selected = true;
            @endif
            $("#repositories option[value='{{ $arr['backupRepo'] }}']").selected = true;

        });


        $("#allSites").change(function() {
            let groupsRows = $("#sitesTable tbody#table-content1").find("tr");
            let checkStatus = $(this)[0].checked;
            groupsRows.find("input.backupsite").prop("checked", checkStatus);
            fillResultTable();
        });

        $("#schedule").change(function() {

            if (this.value == 1) {
                $("#timepicker").removeAttr("disabled");
                $("#days").removeAttr("disabled");
            } else if (this.value == 2) {
                $("#timepicker").val("");
                $("#days").val("");
                $("#timepicker").attr("disabled", "disabled");
                $("#days").attr("disabled", "disabled");
            }

        });

        function getOffset() {
            return offset;
        }

        function fillResultTable() {
            let rows = $("#sitesTable_wrapper").find("tr[id]:has(input:checked)");
            let data = [];
            let sitesum = 0;
            rows.each(function() {
                let row = $(this);
                backupsite = row.find(".backupsite:checked").length > 0;
                sitesum++;
                data.push({
                    "id": row.prop("id"),
                    "name": row.find("td:first").html(),
                    "url": row.find("td:nth(1)").html(),
                    "siteDetails": row.find(".siteDetails").html(),
                    "backupSite": backupsite ? true : false,
                });
            });
            $("#result-table").DataTable().clear();
            $("#result-table").DataTable().rows.add(data);
            $(".ressitessum").html(sitesum + " Sites Selected");
            $(".sitessum").html(sitesum + " Sites Selected");
            adjustTable();
            //------------------------------------//
            $("#result-table").DataTable().draw();
        }

        function addBackupJob(event) {
            event.preventDefault();
            //-------------------------------------//
            if (!$(".backupForm")[0].checkValidity())
                return $(".backupForm")[0].reportValidity();
            //-------------------------------------//
            jobName = $("#jobName").val();
            jobDesc = $("#jobDesc").val();
            repository = $("#repositories").val();
            schedule = $("#schedule").val();
            timepicker = $("#timepicker").val() ? timeConversion($("#timepicker").val()) : "";
            days = $("#days").find(":selected").val();
            resultRows = $("#result-table-content").find("tr");
            let itemsType = $('.itemsType:checked').val();
            let allSites = $('#allOrganizationSites').prop('checked');


            if (!jobName || !jobDesc || !repository || !schedule || (($("#schedule").val() == 1) && (!timepicker || !
                    days))) {
                $(".danger-oper .danger-msg").html("You have to complete Job Details before submitting!");
                $(".danger-oper").css("display", "block");
                setTimeout(function() {
                    $(".danger-oper").css("display", "none");

                }, 6000);
                return;
            } else if (!resultRows.length > 0 && itemsType == "selectedItems") {
                $(".danger-oper .danger-msg").html("You have to select data to backup before submitting!");
                $(".danger-oper").css("display", "block");
                setTimeout(function() {
                    $(".danger-oper").css("display", "none");

                }, 6000);
                return;
            } else if (!allSites && itemsType == "allItems") {
                $(".danger-oper .danger-msg").html("You have to select data to backup before submitting!");
                $(".danger-oper").css("display", "block");
                setTimeout(function() {
                    $(".danger-oper").css("display", "none");
                }, 6000);
                return;
            }

            let schedulePolicy = {
                "scheduleEnabled": $("#schedule").val() == 2 ? false : true,
                "backupWindowEnabled": false,

                "type": "Daily",

                "dailyType": days ? days : "Friday",

                "dailyTime": timepicker ? timepicker : "12:00",
                "retryEnabled": false
            };
            let backupData = {};
            let backUpRows = [];

            if (itemsType == "selectedItems") {
                resultRows.each(function() {
                    let row = $(this);
                    let backUpObj = JSON.parse(row.find('.siteDetails').html());
                    let backupRow = {
                        "Type": "Site",
                        "site": backUpObj
                    };
                    backUpRows.push(backupRow);
                });
                backupData = {
                    "name": jobName,
                    "description": jobDesc,
                    "isEnabled": true,
                    "backupType": "SelectedItems",
                    "RepositoryId": repository,
                    "SelectedItems": backUpRows,
                    "schedulePolicy": schedulePolicy
                };
            } else {
                backupData = {
                    "name": jobName,
                    "description": jobDesc,
                    "isEnabled": true,
                    "backupType": "SelectedItems",
                    "RepositoryId": repository,
                    "SelectedItems": [{
                        "type": "PartialOrganization",
                        "mailbox": false,
                        "archiveMailbox": false,
                        "oneDrive": false,
                        "site": allSites,
                        "teams": false,
                    }],
                    "schedulePolicy": schedulePolicy
                };
            }

            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');

            $.ajax({
                type: "POST",
                url: "{{ url('editBackup', $arr['typeId']) }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    itemsType: itemsType,
                    kind: 'sharepoint',
                    jobId: "{{ $arr['job']->id }}",
                    backupData: backupData
                },

                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');

                    window.location = "{{ url('backup', $arr['typeId']) }}";
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

        function timeConversion(s) {

            const ampm = s.slice(-2);
            const hours = Number(s.split(":")[0]);
            let time = s.slice(0, -2);
            if (ampm === 'AM') {
                if (hours === 12) {
                    return time.replace(s.split(":")[0], '00');
                }
                return time;
            } else if (ampm === 'PM') {
                if (hours !== 12) {
                    return time.replace(s.split(":")[0], String(hours + 12));
                }
                return time;
            }
            return s;
        }
        window.onbeforeunload = onUnload;

        function onUnload() {
            if (xhr) xhr.abort();
        };

        function resumeLoad() {
            $(".resumeLoad").html("Loading...").prop("disabled", "disabled").removeClass("hand");
            if (xhr)
                xhr.abort();
            xhr = $.ajax({
                type: "GET",
                url: "{{ url('getOrganizationSites') }}",
                async: true,
                data: {
                    offset: getOffset(),
                },
                'beforeSend': function(request) {
                    request.setRequestHeader("connection", "keep-alive");
                },
                success: function(data) {
                    offset = $('#sitesTable').DataTable().data().count() + data.length;
                    if (data.length == 0)
                        done = true;
                    else {
                        $('#sitesTable').DataTable().rows.add(data);
                        if (data.length < limit)
                            done = true;
                    }
                    $('#sitesTable').DataTable().columns.adjust().draw();
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
    </script>
@endsection
