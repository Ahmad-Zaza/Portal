@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/main.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/repositories.css') }}" />
<link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/generalElement.css') }}">
<script src="/js/html-duration-picker.min.js"></script>
@section('topnav')
    <style>
        .controlsDivStyle {
            display: none !important
        }

        #durationFrom,
        #durationTo {
            text-align: left;
        }

        i {
            padding: 0px 3px;
        }

        .sess-info {
            margin-bottom: 10px;
            padding-left: 0px;
            padding-right: 0px;
            font-size: 13px;
        }

        .sess-title {
            font-weight: 600;
        }

        .newJobRow {
            padding-top: 20px;
        }

        .sess-info-details {
            color: #c1c0c0;
        }

    </style>
    <div class="col-sm-10 navbarLayout">
        <!-- Upper navbar -->
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm upperNavBar"> -->
        <ul class="ulNavbar">
            @php $parent = strtolower($arr['repo_kind']) @endphp
            <li class="liNavbar"><a class="parent-link" data-parent="{{ $parent }}"
                    href="{{ url('backup', $arr['typeId']) }}"> Backup Jobs <img class="nav-arrow"
                        src="/svg/arrow-right.svg"> {{ $arr['repo_kind'] }}</a></li>
            <li class="liNavbar"><a class="active" href="{{ url('backup').'/'. $arr['typeId'].'/'.'session'.'/'.$arr['jobId'] }}">Backup Session Details</a></li>
            <!-- Authentication Links -->
            @include('layouts.authentication-links')
        </ul>
    </div>

@endsection
@section('content')


    <div id="mainContent">
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <!-- Create new repository button  -->
        <div class="row job-session-details" style="margin-bottom: 50px !important;">

            <div class="col-lg-4">
                <div class="col-lg-11" style="padding-left: 0px;">
                    <h5 class="txt-blue">Status</h5>
                </div>

                <div class="col-lg-12 newJobRow">
                    <div class="rowBorderRight"></div>
                    <div class="rowBorderBottom"></div>
                    <div class="rowBorderleft"></div>
                    <div class="rowBorderUp"></div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-6 nopadding sess-title nopadding">
                            Session Status:
                        </div>
                        <div id="sessionStatus" class="sess-info-details col-lg-6 nopadding">
                        </div>
                    </div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-6 nopadding sess-title">
                            BottleNeck:
                        </div>
                        <div id="bottleNeck" class="sess-info-details col-lg-6 nopadding">

                        </div>
                    </div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-6 nopadding sess-title">
                            Last Backup:
                        </div>
                        <div id="cdate" class="sess-info-details col-lg-6 nopadding">

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="col-lg-11" style=" padding-left: 0px;">
                    <h5 class="txt-blue">Data</h5>
                </div>
                <div class="col-lg-12 newJobRow">
                    <div class="rowBorderRight"></div>
                    <div class="rowBorderBottom"></div>
                    <div class="rowBorderleft"></div>
                    <div class="rowBorderUp"></div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-6 nopadding sess-title">
                            Process Rate:
                        </div>
                        <div id="processRate" class="sess-info-details col-lg-6 nopadding">

                        </div>

                    </div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-6 nopadding sess-title">
                            Read Rate:
                        </div>
                        <div id="readRate" class="sess-info-details col-lg-6 nopadding">

                        </div>

                    </div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-6 nopadding sess-title">
                            Write Rate:
                        </div>
                        <div id="writeRate" class="sess-info-details col-lg-6 nopadding">

                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="col-lg-11" style=" padding-left: 0px;">
                    <h5 class="txt-blue">Summary</h5>
                </div>
                <div class="col-lg-12 newJobRow">
                    <div class="rowBorderRight"></div>
                    <div class="rowBorderBottom"></div>
                    <div class="rowBorderleft"></div>
                    <div class="rowBorderUp"></div>
                    <div class="col-lg-12 sess-info">
                        {{-- @if($arr['jobStatus'] != "Running") --}}
                        <div class="col-lg-6 nopadding sess-title">
                            Duration:
                        </div>
                        <div id="duration" class="sess-info-details col-lg-6 nopadding">
                        </div>
                        {{-- @endif --}}
                    </div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-6 nopadding sess-title">
                            Objects:
                        </div>
                        <div id="objects" class="sess-info-details col-lg-6 nopadding">

                        </div>

                    </div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-6 nopadding sess-title">
                            Transferred:
                        </div>
                        <div id="transferred" class="sess-info-details col-lg-6 nopadding">

                        </div>

                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-3">
                <div class="col-lg-11" style=" padding-left: 0px;">
                    <h5 class="txt-blue">Information</h5>
                </div>
                <div class="col-lg-12 newJobRow">
                    <div class="rowBorderRight"></div>
                    <div class="rowBorderBottom"></div>
                    <div class="rowBorderleft"></div>
                    <div class="rowBorderUp"></div>
                    <div class="col-lg-12 sess-info">
                        <div class="col-lg-9 nopadding sess-title">
                            Backedup Users:
                        </div>
                        <div id="busers" class="nopadding sess-info-details col-lg-3">

                        </div>

                    </div>
                    <div class="col-lg-12 sess-info">
                        <div class="nopadding col-lg-9 sess-title">
                            Backedup Groups:
                        </div>
                        <div id="bgroups" class="sess-info-details nopadding col-lg-3">

                        </div>

                    </div>
                    <div style="height:18px" class="col-lg-12 sess-info">
                        <div class="nopadding col-lg-9 sess-title">

                        </div>
                        <div id="lastRun" class="sess-info-details nopadding col-lg-3">

                        </div>
                    </div>
                </div>
            </div> --}}

        </div>


        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <!-- All repositories table -->
        <div class="row">
            <div class="col-lg-4">
                <h5 class="txt-blue">Details</h5>
            </div>

        </div>
        <div class="jobsTable" style="margin-left:0px; margin-top: -38px;">
            <table id="sessionsTable" class="stripe table table-striped table-dark" style="width:100%">
                <thead class="table-th">
                    <th class="col-sm-8 left-col">Action</th>
                    <th class="col-sm-2 left-col">Duration</th>
                    <th class="col-sm-2 left-col">Status</th>
                </thead>
                <tbody id="session-content">

                </tbody>
            </table>
        </div>
        <div class="row">&nbsp;</div>
    </div>


    <div id="searchModal" class="modal" role="dialog">

        <div class="modal-dialog modal-lg" style="width: 500px; margin:20vh auto;">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>

            <!-- Modal content-->
            <div class="modal-content ">

                <div id="seaerch_modal_id" class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="per-req">Search
                            </h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-form-70 mb-1">Duration:</div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div class="mr-25" style="position: relative">
                                <input class="form_input form-control html-duration-picker" id="durationFrom"
                                    placeholder="From" />
                                <span style="right:12px;" class="timepicker-icon text-white">min</span>
                            </div>

                            <div style="position: relative">
                                <input class="form_input form-control html-duration-picker" id="durationTo"
                                    placeholder="To" />
                                <span style="right:12px;" class="timepicker-icon text-white">max</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70 mb-1">Status:</div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container checkbox-search">&nbsp;
                                    <input id="Success" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Success</span>
                            </div>
                            <div class="halfWidth" style="position: relative;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container checkbox-search">&nbsp;
                                    <input id="Stop" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Stop</span>
                            </div>

                        </div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container checkbox-search">&nbsp;
                                    <input id="Warning" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Warning</span>
                            </div>
                            <div class="halfWidth" style="position: relative;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container checkbox-search">&nbsp;
                                    <input id="Error" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Error</span>
                            </div>
                        </div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container checkbox-search">&nbsp;
                                    <input id="Running" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Running</span>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70" style="display: inline-flex;">
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


    <script>
        $(document).ready(function() {
            var parent = $('.parent-link').attr('data-parent');
            $('.submenu-backup.submenu a[data-route="' + parent + '"]').addClass('active');
            var row = $('a.sub-menu-link.active').closest('.row');
            row.find('.left-nav-list').addClass('active').removeClass('collapsed');
            $('.submenu-backup').addClass('in');

            $('#sessionsTable').on('xhr.dt', function(e, settings, json, xhr) {

                if (json && json.session) {
                    $("#sessionStatus").html(json.session.status)
                    $("#bottleNeck").html(json.session.statistics.bottleneck)
                    $("#lastRun").html()

                    $("#processRate").html(Math.round((json.session.statistics.processingRateBytesPS) /
                        1024) + " KB/s")
                    $("#readRate").html(Math.round((json.session.statistics.readRateBytesPS) / 1024) +
                        " KB/s")
                    $("#writeRate").html(Math.round((json.session.statistics.writeRateBytesPS) / 1024) +
                        " KB/s")
                    $("#cdate").html(formatDate(json.session.creationTime));
                    if (json.session.status)
                        $("#duration").html(json.session.duration)
                    $("#busers").html(json.session.usersCount)
                    $("#bgroups").html(json.session.groupsCount)
                    json.session.totalObj ? $("#objects").html(json.session.progress + "/" + json.session
                        .totalObj) : $("#objects").html(json.session.progress)
                    $("#transferred").html(Math.round(((json.session.statistics.transferredDataBytes) /
                        1048576) * 100) / 100 + " MB")
                }

            }).DataTable({
                'ajax': {
                    "type": "GET",
                    "url": "{{ url('getBackupJobSession',[$arr['typeId'], $arr['jobId']]) }}",
                    "dataSrc": function(json) {
                         CheckTable();
                        return json['logItems']
                    },
                    "data": '',
                    "dataType": "json",
                },
                'columns': [{
                        "class": "col-lg-8",
                        "data": "title",
                        render: function(data, type, full, meta) {
                            var n = data.indexOf("]");
                            var status = data.slice(1, n);
                            var res = data.slice(n + 1);
                            if (status == "Success") {
                                res =
                                    '<div class="col-lg-1" style="margin-right: -5%;padding-top: 2px;padding-left: 0px;"><i  class="fa fa-check-circle"  style="font-size:14px;color:green"></i> </div><div style="padding-left: 0px;" class="col-lg-11">' +
                                    res + ' </div>';

                            } else if (status == "Stop") {
                                res =
                                    '<div class="col-lg-1" style="margin-right: -5%;padding-top: 2px;padding-left: 0px;"><i  class="fa fa-stop"  style="font-size:14px;color:red"></i> </div><div style="padding-left: 0px;" class="col-lg-11">' +
                                    res + ' </div>';

                            } else if (status == "Warning") {
                                res =
                                    '<div class="col-lg-1" style="margin-right: -5%;padding-top: 2px;padding-left: 0px;"><i  class="fas fa-exclamation-triangle"  style="font-size:14px;color:#efff00"></i> </div><div style="padding-left: 0px;" class="col-lg-11">' +
                                    res + ' </div>';

                            } else if (status == "Error") {
                                res =
                                    '<div class="col-lg-1" style="margin-right: -5%;padding-top: 2px;padding-left: 0px;"><i  class="fa fa-ban"  style="font-size:14px;color:red"></i> </div><div style="padding-left: 0px;" class="col-lg-11">' +
                                    res + ' </div>';

                            } else if (status == "Running") {
                                res =
                                    '<div class="col-lg-1" style="margin-right: -5%;padding-top: 2px;padding-left: 0px;"><i  class="fa fa-hourglass-start"  style="font-size:14px;color:green"></i> </div><div style="padding-left: 0px;" class="col-lg-11">' +
                                    res + ' </div>';

                            }


                            return res;
                        }
                    },
                    {
                        "data": "duration",
                        "class": "col-lg-2",
                        render: function(data, type, full, meta) {
                            return data ? toHHMMSS(data) : "";
                        }
                    },

                    {
                        "data": null,
                        "class": "col-lg-2",
                        render: function(data, type, full, meta) {
                            var n = data.title.indexOf("]");
                            var status = data.title.slice(1, n);
                            var statusClass = 'text-orange1';
                            statusClass = (status == 'Stop') ? 'text-danger' :
                                statusClass;
                            statusClass = (status == 'Success') ? 'text-success' :
                                statusClass;
                            statusClass = (status == 'Error') ? 'text-danger' :
                                statusClass;
                            statusClass = (status == 'Warning') ? 'text-warning' :
                                statusClass;
                            statusClass = (status == 'Running') ? 'text-primary' :
                                statusClass;
                            return '<span class="' + statusClass + '">' + status + '</span>';
                        }
                    }
                ],
                dom: 'Bfrtip',
                "fnDrawCallback": function() {
                    var icon =
                        '<div class="search-container"><img class="search-icon" src="/svg/search.svg"></div>';
                    if($(".dataTables_filter label").find('.search-icon').length == 0)
                    $('.dataTables_filter label').append(icon);
                    $('.dataTables_filter input').addClass('form_input form-control');
                },
                buttons: [

                    {
                        extend: 'csvHtml5',
                        text: '<img src="/svg/excel.svg" style="width:15px;height:30px;">',
                        titleAttr: 'Export to csv',
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<img src="/svg/pdf.svg" style="width:15px;height:30px;">',
                        customize: function(doc) {
                            customizePdf(doc)
                        },
                        titleAttr: 'Export to pdf',
                        // exportOptions: {
                        //    // columns: 'th:not(:first-child,:last-child)',
                        //     format: {
                        //         body: function(data, column, row) {
                        //             data=data+"";
                        //             //if it is html, return the text of the html instead of html
                        //             if (data.includes("<i")) {
                        //                 return $(data)[1].innerText;
                        //             }
                        //             else   if (/<\/?[^>]*>/.test(data)) {
                        //                 return $(data).text();
                        //             }
                        //             else {
                        //                 return data;
                        //             }
                        //         }
                        //     }
                        // }
                    },
                    {
                        text: '<img src="/svg/filter.svg" style="width:15px;height:30px;">',
                        titleAttr: 'Advanced Search',
                        action: function(e, dt, node, config) {
                            $('#searchModal').modal('show');
                        }
                    }
                ],
                "processing": false,
                "scrollY": "400px",
                //  "scrollCollapse": true,
                "bInfo": false,
                "paging": false,
                // "searching": false,
                "autoWidth": false,
                "bSort": false,
                language: {
                    "sEmptyTable": "No available session info",
                    search: "",
                    searchPlaceholder: "Search...",
                    loadingRecords: '&nbsp;',
                    // processing: '<div style="margin-top: 1%; width: 6rem; height: 6rem;" class="spinner-border"></div>'
                },
                'columnDefs': [{
                    'targets': [0, 1], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            $('#sessionsTable').DataTable().buttons().container()
                .prependTo('#sessionsTable_filter');

        });

        function CheckTable() {
            if("{{ $arr['jobStatus'] }}" == "Running"){
                setTimeout(() => {
                        $('#sessionsTable').DataTable().ajax.reload();
                }, 15000);
            }
        }

        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                res = true;
                durationFrom = $("#durationFrom")[0].value;
                durationTo = $("#durationTo")[0].value;

                if (durationFrom && durationFrom.toString() != "00:00:00" && (!data[1] || Date.parse("03/09/2013 " +
                        durationFrom) > Date.parse("03/09/2013 " + data[1]))) {
                    res = false;
                }
                if (durationTo && durationTo.toString() != "00:00:00" && (!data[1] || Date.parse("03/09/2013 " +
                        durationTo) < Date.parse("03/09/2013 " + data[1]))) {
                    res = false;
                }

                sSuccess = $("#Success")[0].checked;
                sStop = $("#Stop")[0].checked;
                sError = $("#Error")[0].checked;
                sWarning = $("#Warning")[0].checked;
                sRunning = $("#Running")[0].checked;

                if (sSuccess == true || sStop == true || sError == true || sWarning == true || sRunning == true) {

                    if (sSuccess == false && data[2] == "Success")
                        res = false;
                    if (sStop == false && data[2] == "Stop")
                        res = false;
                    if (sError == false && data[2] == "Error")
                        res = false;
                    if (sWarning == false && data[2] == "Warning")
                        res = false;
                    if (sRunning == false && data[2] == "Running")
                        res = false;


                }



                return res;
            }
        );

        function resetSearch() {
            $("#durationFrom").val("");
            $("#durationTo").val("");
            $("#Success").attr('checked', false);
            $("#Stop").attr('checked', false);
            $("#Error").attr('checked', false);
            $("#Warning").attr('checked', false);
            $("#Running").attr('checked', false);
            $('#sessionsTable').DataTable().draw();
        }

        function applySearch() {
            $('#sessionsTable').DataTable().draw();
        }

        function toHHMMSS(secs) {
            var sec_num = parseInt(secs, 10); // don't forget the second param
            var hours = Math.floor(sec_num / 3600);
            var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
            var seconds = sec_num - (hours * 3600) - (minutes * 60);

            if (hours < 10) {
                hours = "0" + hours;
            }
            if (minutes < 10) {
                minutes = "0" + minutes;
            }
            if (seconds < 10) {
                seconds = "0" + seconds;
            }
            return hours + ':' + minutes + ':' + seconds;
        }

        function toMin(timeString) {

            let timeArr = timeString.split(":");
            return parseInt(timeArr[0]) * 60 + parseInt(timeArr[1]);
        }

        function formatDate(date) {
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

    </script>
@endsection
