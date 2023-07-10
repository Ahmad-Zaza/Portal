@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/main.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/repositories.css') }}" />
<link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/generalElement.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('/css/restore-customize.css') }}" />
<style>
    .check-mark:after {
        left: 3px!important;
        top: -5px!important;
        width: 6px!important;
        height: 12px!important;
    }
</style>
@section('topnav')
    <style>
        i {
            padding: 0px 3px;
        }

        .dataTables_processing {
            background-color: #191d1f;
            height: 25px !important;
            top: 100% !important;
        }

        .spinner::before {
            top: 8px !important;
        }

    </style>
    <div class="col-sm-10 navbarLayout">
        <!-- Upper navbar -->
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm upperNavBar"> -->
        <ul class="ulNavbar">

            <li class="liNavbar"><a class="active" href="{{ url('backup', $arr['typeId']) }}">Backup Jobs
                    <img class="nav-arrow" src="/svg/arrow-right-active.svg">
                    {{ $arr['repo_kind'] }}</a></li>
            <!-- Authentication Links -->
            @include('layouts.authentication-links')
        </ul>
    </div>

@endsection
@section('content')


    <div id="mainContent">
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <!-- Create new repository button  -->
        <div class="row min-height-35">
            <div class="col-lg-3" style="z-index: 5">
                @if ($role->hasPermissionTo($arr["typeId"].'_add_backup'))
                <button onclick="addbackup()" class="btn_primary_state left-float">
                    New Backup Job
                </button>
                @endif
            </div>
            <div class="col-lg-9"></div>
        </div>


        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <!-- All repositories table -->
        <div class="row">
            <div class="jobsTable">
                <table id="jobsTable" class="stripe table table-striped table-dark" style="width:100%">
                    <thead class="table-th">
                        <th class="after-none"></th>
                        <th style="text-align: left;"> Name </th>
                        <th>Enabled</th>
                        <th>Status
                            <!-- <a class="txt-blue"  data-placement="up"  data-toggle="tooltip" title="Status is being updated every 8 seconds">!</a> -->
                        </th>
                        <th>Last Backup</th>
                        <th>Next Backup</th>
                        <th>Repository</th>
                        <th>Actions</th>

                    </thead>
                    <tbody id="table-content">


                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <!------------------- delete modal ---------------------- -->
    <div id="confirmationModal" class="modal" role="dialog">

        <div class="modal-dialog">
            <!-- Modal content-->

            <div class="modal-content">
                <div id="modalBody_id" class="modalContent">
                    <div class="alert swal-modal-confirmation custom-confirmation" role="alert">
                        <div class="swal-icon swal-icon--warning" style="background-color: #FA9351!important">
                            <span class="swal-icon--warning__body">
                                <span class="swal-icon--warning__dot"></span>
                            </span>
                        </div>

                        <div id="delmdlHdr" class="swal-title text-center"></div>
                        <div class="row">
                            <div id="delMdlTxt" class="modal-body basic-color text-center mt-22">
                                Are You Sure ?
                            </div>
                        </div>

                        <div class="row mt-10">
                            <div class="input-form-70 inline-flex">
                                <input type="hidden" id="modalJobId" name="modalJobId" />
                            <input type="hidden" id="modalAction" name="modalAction" />
                            <button type="button" class="btn_primary_state allWidth mr-25"
                                onClick="handleAction()">Confirm</button>
                                <button type="button" class="btn_cancel_primary_state allWidth" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
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
                        <div class="input-form-70 mb-1">Last Backup:</div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <input type="text" class="form_input form-control halfWidth mr-25" id="lastFromDate"
                                placeholder="From" />
                            <input type="text" class="form_input form-control halfWidth" id="lastToDate" placeholder="To" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70 mb-1">Next Backup:</div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <input type="text" class="form_input form-control halfWidth mr-25" id="nextFromDate"
                                placeholder="From" />
                            <input type="text" class="form_input form-control halfWidth" id="nextToDate" placeholder="To" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70 mb-1">Size:</div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <input type="number" class="form_input form-control halfWidth mr-25" id="sizeFrom"
                                placeholder="From" />
                            <input type="number" class="form_input form-control halfWidth" id="sizeTo" placeholder="To" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70">
                            <label style="padding-top: 5px;left: -2px;" class="checkbox-container checkbox-search">&nbsp;
                                <input id="activatedSearch" type="checkbox" class="form-check-input" />
                                <span style="top:-5px!important;width: 15px !important; height: 15px !important;"
                                    class="check-mark"></span>
                            </label>
                            <span style="margin-left: 25px;">Enabled</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-form-70" style="display: inline-flex;">
                            <button type="button" onclick="ApplySearch()"
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
    <!--
                                                                                                                                                                <style>
                                                                                                                                                                 .tooltip-arrow, .tooltip-inner {

                                                                                                                                                                     z-index: 100;
                                                                                                                                                                     position: fixed;
                                                                                                                                                                     }
                                                                                                                                                                </style> -->






    <script>
        function getMainIcon() {
            return '<img class= "tableIcone"  style="margin-left:0px;width: 13px; margin-right:0;" src="/svg/backup.svg " title="backup job">';
        }
        $(document).ready(function() {
            $('.submenu-backup').addClass('collapse in');


            $('#jobsTable').DataTable({
                'ajax': {
                    "type": "GET",
                    "url": "{{ url('getBackupJobs', $arr['typeId']) }}",
                    "dataSrc": '',
                    "data": "",
                    "beforeSend": function(){
                        $('#jobsTable > tbody').html(
                            '<tr class="odd">' +
                            '<td valign="top" colspan="35" class="dataTables_empty processing_row"><span class="table-spinner"></span></td>' +
                            '</tr>');
                    },
                    "dataType": "json",
                },

                "order": [
                    [1, 'asc']
                ],
                'columns': [{
                        "data": null,
                        "class": "after-none",
                        render: getMainIcon
                    },
                    {
                        "data": null,
                        "class": "text-left",
                        render: function(data, type, full, meta) {
                            return '<a href="#" onclick="getSession(\'' + data.id + '\')" > ' + data
                                .name + ' <a>';
                        }
                    },
                    {
                        "type": "html-input",
                        "data": null,
                        render: function(data, type, full, meta) {
                            return data.isEnabled ?
                                '  <i style="cursor: context-menu;color:white" class="fa fa-check"></i>' :
                                '<i style="cursor: context-menu;color:white" class="fa fa-close"></i>';
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, full, meta) {
                            var statusClass = 'text-orange1';
                            statusClass = (data.lastStatus == 'Failed') ? 'text-danger' :
                                statusClass;
                            statusClass = (data.lastStatus == 'Success') ? 'text-success' :
                                statusClass;
                            statusClass = (data.lastStatus == 'Warning') ? 'text-warning' :
                                statusClass;
                            statusClass = (data.lastStatus == 'Stopped') ? 'text-secondary' :
                                statusClass;
                            statusClass = (data.lastStatus == 'Running') ? 'text-primary' :
                                statusClass;
                            return data.lastStatus ? '<a  href="#" onclick="getSession(\'' + data
                                .id + '\')" class="' + statusClass + '"> ' + data.lastStatus +
                                ' <a>' :
                                "";
                        }
                    },
                    {
                        "data": "lastRun",
                        render: function(data, type, full, meta) {
                            return data ? formatDate(data) : "";
                        }
                    },
                    {
                        "data": "nextRun",
                        render: function(data, type, full, meta) {
                            return data ? formatDate(data) : "";
                        }
                    },
                    {
                        "data": "repoName"
                    },
                    {
                        "data": null,
                        "class": "actions-td",
                        render: function(data, type, full, meta) {
                            icons = '';
                            @if ($role->hasPermissionTo($arr["typeId"].'_actions_backup'))
                            icons = (data.lastStatus == "Running") ? '<a onclick="manageJob(\'' +
                                data.id +
                                '\',\'stop\')" title="Stop"><img class="tableIcone" src="/svg/pause.svg"></a>' :
                                '<a onclick="manageJob( \'' + data.id +
                                '\',\'start\')" title="Start"><img class="tableIcone" src="/svg/start.svg"></a>';
                            if (data.isEnabled) {
                                icons = icons + '<a onclick="confirmAction( \'' + data.id +
                                    '\',\'' + data.name +
                                    '\',\'disable\')"  data-toggle="modal" data-target="#confirmationModal" title="Disable"><img class="tableIcone" src="/svg/stop.svg"></a>'
                            } else {
                                icons = icons + '<a onclick="manageJob(\'' + data.id +
                                    '\',\'enable\')" title="Enable"><img class="tableIcone" src="/svg/enable.svg"></a>'
                            }
                            @endif
                            @if ($role->hasPermissionTo($arr["typeId"].'_edit_backup'))
                            icons = icons + '<a onclick="editJob(\'' + data.id +
                                '\')" title="Edit"><img class="tableIcone" src="/svg/edit.svg"></a>';
                            @endif
                            @if ($role->hasPermissionTo($arr["typeId"].'_delete_backup'))
                            icons = icons +
                                '<a onclick="confirmAction( \'' + data.id + '\',\'' + data.name +
                                '\',\'delete\')" data-toggle="modal" data-target="#confirmationModal" title="Delete"><img class="tableIcone" src="/svg/Delete.svg"></a>';
                            @endif
                            return icons;
                        }
                    }
                ],
                dom: 'Bfrtip',
                buttons: [

                    {
                        extend: 'csvHtml5',
                        text: '<img src="/svg/excel.svg" style="width:15px;height:30px;">',
                        titleAttr: 'Export to csv',
                        exportOptions: {
                            columns: 'th:not(:first-child,:last-child)',
                            format: {
                                body: function(data, column, row) {
                                    //if it is html, return the text of the html instead of html
                                    if (data) {
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
                        '<div class="search-container"><img class="search-icon" src="/svg/search.svg"></div>';
                    if($(".dataTables_filter label").find('.search-icon').length == 0)
                    $('.dataTables_filter label').append(icon);
                    $('.dataTables_filter input').addClass('form_input form-control');
                },
                "scrollY": "500px",
                "scrollCollapse": true,
                "bInfo": false,
                "paging": false,
                "processing": false,
                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                    'loadingRecords': '&nbsp;',
                    'processing': '<div class="spinner"></div>'
                },
                'columnDefs': [{
                    'targets': [0, 2, 7], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $('#jobsTable').DataTable().buttons().container()
                .prependTo('#jobsTable_filter');

            $("#lastFromDate").datepicker({
                dateFormat: 'dd/mm/yy',
            });
            $("#lastToDate").datepicker({
                dateFormat: 'dd/mm/yy',
            });
            $("#nextFromDate").datepicker({
                dateFormat: 'dd/mm/yy',
            });
            $("#nextToDate").datepicker({
                dateFormat: 'dd/mm/yy',
            });
        });

        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                res = true;
                lastFromDate = $("#lastFromDate").datepicker('getDate');
                lastToDate = $("#lastToDate").datepicker('getDate');
                nextFromDate = $("#nextFromDate").datepicker('getDate');
                nextToDate = $("#nextToDate").datepicker('getDate');
                sizeFrom = $("#sizeFrom")[0].value;
                sizeTo = $("#sizeTo")[0].value;
                if ((lastFromDate || lastToDate) && !data[5]) {
                    res = false;
                } else {
                    if (lastFromDate) {
                        if (lastFromDate > parseDateValue(data[5])) {
                            res = false;
                        }
                    }
                    if (lastToDate) {
                        if (lastToDate < parseDateValue(data[5])) {
                            res = false;
                        }
                    }
                }
                if ((nextFromDate || nextToDate) && !data[6]) {
                    res = false;
                } else {
                    if (nextFromDate) {
                        if (nextFromDate > parseDateValue(data[6])) {
                            res = false;
                        }
                    }
                    if (nextToDate) {
                        if (nextToDate < parseDateValue(data[6])) {
                            res = false;
                        }
                    }
                }
                if (sizeFrom && data[2]) {
                    if (parseInt(sizeFrom) > parseInt(data[2])) {
                        res = false;
                    }
                }
                if (sizeTo && data[2]) {
                    if (parseInt(sizeTo) < parseInt(data[2])) {
                        res = false;
                    }
                }
                if ($("#activatedSearch")[0].checked == true && !data[3]) {
                    res = false;
                }
                return res;
            }
        );

        function resetSearch() {
            $("#lastFromDate").val("");
            $("#lastToDate").val("");
            $("#nextFromDate").val("");
            $("#nextToDate").val("");
            $("#sizeFrom").val("");
            $("#sizeTo").val("");
            $("#activatedSearch").attr('checked', false);

            $('#jobsTable').DataTable().draw();
        }

        function ApplySearch() {
            $('#jobsTable').DataTable().draw();
        }

        function addbackup() {
            window.location = "{{ url('backup',[$arr['typeId'],'add']) }}";
        }

        function manageJob(jobId, action) {
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                asunc: false,
                type: "GET",
                url: "{{ url('manageBackupJob',$arr['typeId']) }}",
                data: {
                    jobId: jobId,
                    action: action
                },
                dataType: "json",
                success: function(data) {
                    $('#jobsTable').DataTable().ajax.reload();
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
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

        function confirmAction(jobId, jobName, action) {
            $('#modalJobId').val(jobId);
            $('#modalAction').val(action);
            if (action == "disable") {
                $("#delmdlHdr").html("Disable Job");
                $("#delMdlTxt").html("Are You Sure you want to disable Job " + jobName + " ?");
            } else {
                $("#delmdlHdr").html("Delete Job");
                $("#delMdlTxt").html("Are You Sure you want to delete Job " + jobName + " ?");
            }

        }

        function handleAction() {
            $('#confirmationModal').modal('hide');
            if ($('#modalAction').val() == "delete")
                deleteJob($("#modalJobId").val());
            else
                manageJob($("#modalJobId").val(), "disable");
        }

        function deleteJob(jobId) {
            var deleted = jobId;
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');

            $.ajax({
                type: "GET",
                url: "{{ url('deleteBackupJob',$arr['typeId']) }}",
                data: {
                    jobId: jobId
                },
                dataType: "json",
                success: function(data) {
                    $('#jobsTable').DataTable().ajax.reload();
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
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

        function editJob(jobId) {
            window.location = "{{ url('backup') }}/" + "{{ $arr['typeId'] }}" + "/edit/" + jobId;
        }

        function getSession(jobId) {
            window.location = "{{ url('backup') }}/" + "{{ $arr['typeId'] }}" + "/session/" + jobId;
        }

        function parseDateValue(rawDate) {
            var dateNoTime = rawDate.split(" ");
            var dateArray = dateNoTime[0].split("/");
            var parsedDate = new Date(dateArray[2] + "-" + dateArray[1] + "-" + dateArray[0]);
            return parsedDate;
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

    </script>
@endsection
