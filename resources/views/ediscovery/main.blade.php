@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/main.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/repositories.css') }}" />
<link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/generalElement.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('/css/restore-customize.css') }}" />
@section('topnav')
    <style>
        .dataTables_scrollBody {
            height: auto!important;
            overflow-x: hidden!important;
        }
        table.nowrap:not(.filter-table) td:not(.wrap) {
            word-wrap: break-word;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }
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
            <li class="liNavbar"><a class="active" href="{{ url('e-discovery', $arr['kind']) }}">E-Discovery Jobs
                    <img class="nav-arrow" src="/svg/arrow-right-active.svg">
                    {{ getDataType($arr['kind']) }}</a></li>
            <!-- Authentication Links -->
            @include('layouts.authentication-links')
        </ul>
    </div>

@endsection
@section('content')
    <div id="mainContent">
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <!-- Create new repository button  -->
        <div class="row">
            <div class="col-lg-3" style="z-index: 5">
                <button onclick="createEDiscoveryJob()" class="btn_primary_state left-float">
                    New E-Discovery Job
                </button>
            </div>
            <div class="col-lg-9"></div>
        </div>
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <!-- All repositories table -->
        <div class="row">
            <div class="jobsTable">
                <table id="jobsTable" class="stripe table table-striped table-dark display nowrap allWidth" style="width:100%">
                    <thead class="table-th">
                        <th class="after-none"></th>
                        <th class="text-left">Job Name</th>
                        <th>Status</th>
                        <th>Duration</th>
                        <th>Total Items</th>
                        <th>Request Time</th>
                        <th>Completion Time</th>
                        <th>Expiration Time</th>
                        <th>Actions</th>
                    </thead>
                    <tbody id="table-content">
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <!------------------- delete modal ---------------------- -->
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
                        <div class="swal-title text-center confirmTitle">Confirm Title</div>
                        <div class="row">
                            <div class="modal-body basic-color text-center mt-22">
                                Are You Sure ?
                            </div>
                        </div>

                        <div class="row mt-10">
                            <div class="input-form-70 inline-flex">
                                <button type="button" class="btn_primary_state allWidth confirmButton mr-25"
                                    onClick="manageJob();">Yes</button>
                                <button type="button" class="btn_cancel_primary_state allWidth"
                                    data-dismiss="modal">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="searchModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg w-500 mv-top">
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
                        <div class="input-form-70 mb-1 inline-flex ">
                            <div class="input-form-70 mb-1 ml-0">Job Name:</div>
                        </div>
                        <div class="input-form-70 inline-flex">
                            <div class="w-100 relative">
                                <input type="text" class="form_input form-control custom-form-control font-size" id="job_name"
                                    placeholder="" />
                            </div>
                        </div>
                    </div>

                    <div class="row" >
                        <div class="input-form-70 mb-1">Request Time:</div>
                        <div class="input-form-70 inline-flex">
                            <div class="halfWidth mr-25 relative">
                                <input type="text" class="form_input form-control custom-form-control font-size date" id="RequestFrom"
                                    placeholder="From" />
                            </div>

                            <div class="halfWidth relative">
                                <input type="text" class="form_input form-control custom-form-control font-size date" id="RequestTo"
                                    placeholder="To" />
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="input-form-70 mb-1">Completion Time:</div>
                        <div class="input-form-70 inline-flex">
                            <div class="halfWidth mr-25 relative">
                                <input type="text" class="form_input form-control custom-form-control font-size date"
                                    id="CompletionFrom" placeholder="From" />
                            </div>

                            <div class="halfWidth relative">
                                <input type="text" class="form_input form-control custom-form-control font-size date" id="CompletionTo"
                                    placeholder="To" />
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="input-form-70 mb-1">Expiration Time:</div>
                        <div class="input-form-70 inline-flex">
                            <div class="halfWidth mr-25 relative">
                                <input type="text" class="form_input form-control custom-form-control font-size date"
                                    id="ExpirationFrom" placeholder="From" />
                            </div>

                            <div class="halfWidth relative">
                                <input type="text" class="form_input form-control custom-form-control font-size date" id="ExpirationTo"
                                    placeholder="To" />
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
        function getMainIcon() {
            return '<img class= "tableIcone"  style="margin-left:0px;width: 13px; margin-right:0;" src="/svg/discovery1.svg " title="E-Discovery Job">';
        }
        $(document).ready(function() {
            $('.submenu-discovery').addClass('collapse in');

            $('#jobsTable').DataTable({
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if ($(this).children().length > 0) {
                            $(this).attr('title', $(this).find(":first").html());
                        }
                        else if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                'ajax': {
                    "type": "GET",
                    "async": true,
                    "url": "{{ url('e-discoveryJobs', $arr['kind']) }}",
                    "dataSrc": function(json) {
                        return json;
                    },
                    "data": {},
                    "dataType": "json",
                    "statusCode": {
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
                },
                "order": [
                    [5, 'desc']
                ],
                'columns': [
                    {
                        "data": null,
                        "class": "after-none",
                        render: getMainIcon
                    },
                    {
                        "data": null,
                        "class": "text-left",
                        render: function(data) {
                            if(data.restore_session_guid && data.status == 'Success')
                                return '<a href="/e-discovery/'+data.backup_job_kind+'/result/'+data.restore_session_guid+'" > ' + data
                                    .name + ' <a>';
                            return data.name;
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, full, meta) {
                            var statusClass = 'text-orange1';
                            statusClass = (data.status == 'Failed') ? 'text-danger' : statusClass;
                            statusClass = (data.status == 'Expired') ? 'text-secondary' : statusClass;
                            statusClass = (data.status == 'Canceled') ? 'text-secondary' : statusClass;
                            statusClass = (data.status == 'Success') ? 'text-success' :
                                statusClass;
                            statusClass = (data.status == 'Waiting') ? 'text-warning' :
                                statusClass;
                            statusClass = (data.status == 'Stopped' || data.status == 'Running') ? 'text-primary' :
                                statusClass;
                            if(data.restore_session_guid && data.status == 'Success')
                                return '<a class="'+statusClass+'" href="/e-discovery/'+data.backup_job_kind+'/result/'+data.restore_session_guid+'" > ' + data
                                .status + ' <a>';
                            return '<span class="'+statusClass+'">'+data.status+'</span>';
                        }
                    },
                    {
                        "data": "duration",
                    },
                    {
                        "data": "total_items",
                    },
                    {
                        "data": "request_time",
                        // render: function(data, type, full, meta) {
                        //     return data ? formatDate(data) : "";
                        // }
                    },
                    {
                        "data": "completion_time",
                        // render: function(data, type, full, meta) {
                        //     return data ? formatDate(data) : "";
                        // }
                    },
                    {
                        "data": "expiration_time",
                        // render: function(data, type, full, meta) {
                        //     return data ? formatDate(data) : "";
                        // }
                    },
                    {
                        "data": null,
                        "width": "120px",
                        "class": "actions-td wrap",
                        render: function(data, type, full, meta) {
                            icons = (data.status == "Success")?'<a href="/e-discovery/'+data.backup_job_kind+'/result/'+(data.restore_session_guid)+'" title="View Result"><img class="tableIcone " src="/svg/details.svg"></a>':'';
                            icons += (data.status != "Running")?'<a href="/e-discovery/'+data.backup_job_kind+'/edit/'+(data.restore_session_guid?data.restore_session_guid:data.id)+'" title="Edit Job"><img class="tableIcone " src="/svg/edit.svg"></a>':'';
                            icons += (data.status != "Running")?'<a onclick="manageJob(\''+data.id+'\',\'run\',1)" title="reRun Job"><img class="tableIcone " src="/svg/start.svg"></a>':'';
                            // icons += (data.status == "Running")?'<a onclick="manageJob(\''+data.id+'\',\'cancel\',1)" title="Stop Job"><img class="tableIcone " src="/svg/stop.svg"></a>':'';
                            icons += (data.status != "Running")?'<a onclick="manageJob(\''+data.id+'\',\'copy\',1)" title="Duplicate Job"><img class="tableIcone " src="/svg/duplicate.svg"></a>':'';
                            icons += (data.status != "Running")?'<a onclick="manageJob(\''+data.id+'\',\'delete\',1)" title="Delete Job"><img class="tableIcone " src="/svg/Delete.svg"></a>':'';
                            icons += (data.status != "Running" && data.status != "Expired")?'<a onclick="manageJob(\''+data.id+'\',\'expire\',1)" title="Force Expire Job"><img class="tableIcone " src="/svg/stop.svg"></a>':'';
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

                    if($('#jobsTable').DataTable().data().count() > 0){
                        setTimeout(function(){
                            $('#jobsTable').DataTable().ajax.reload();
                        },15000)
                    }
                },
                "scrollY": "500px",
                "scrollX": false,
                "bInfo": false,
                "paging": false,
                "autoWidth": true,
                "processing": false,
                "bInfo": false,
                "paging": false,
                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                    'loadingRecords': '&nbsp;',
                    'processing': '<div class="spinner"></div>'
                },
                "processing": false,
                'columnDefs': [{
                    'targets': [0,8], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $('#jobsTable').DataTable().buttons().container()
                .prependTo('#jobsTable_filter');

            $(".date").datepicker({
                dateFormat: 'dd/mm/yy',
            });
        });

        function createEDiscoveryJob() {
            window.location = "{{ url('e-discovery/'.$arr['kind'].'/add') }}";
        }

        function checkTable() {
            setTimeout(() => {
                $('#jobsTable').DataTable().ajax.reload();
            }, 15000);
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

        function manageJob(jobId='',status='',confirm=0) {
            if(confirm == 1){
                $('#confirmationModal').find(".jobId").val(jobId);
                $('#confirmationModal').find(".status").val(status);
                if(status == "run")
                    $('#confirmationModal').find(".confirmTitle").html("Running e-discovery job will delete all result");
                else if(status == "copy")
                    $('#confirmationModal').find(".confirmTitle").html("Copy Job");
                else if(status == "delete")
                    $('#confirmationModal').find(".confirmTitle").html("Delete Job");
                else if(status == "expire")
                    $('#confirmationModal').find(".confirmTitle").html("Expire Job will delete all result");
                return $('#confirmationModal').modal('show');
            }
            jobId = $('#confirmationModal').find(".jobId").val();
            status = $('#confirmationModal').find(".status").val();
            $('#confirmationModal').modal('hide');
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                // url: "/"+status+"EdiscoveryJob/"+"{{$arr['kind']}}",
                url: "{{url('')}}/"+status+"EdiscoveryJob/"+"{{$arr['kind']}}",
                data: {
                    _token: "{{ csrf_token() }}",
                    jobId: jobId,
                    status: status
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

        function editEDiscoveryJob(jobId) {
            window.location = "{{ url('edit-ediscovery') }}" + "/" + jobId;
        }

        function getSession(jobId) {
            window.location = "{{ url('e-discovery/session') }}" + "/" + jobId;
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

        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                res = true;
                let JobName = $("#job_name").val();
                let RequestFrom = $("#RequestFrom").datepicker('getDate');
                let RequestTo = $("#RequestTo").datepicker('getDate');
                let CompletionFrom = $("#CompletionFrom").datepicker('getDate');
                let CompletionTo = $("#CompletionTo").datepicker('getDate');
                let ExpirationFrom = $("#ExpirationFrom").datepicker("getDate");
                let ExpirationTo = $("#ExpirationTo").datepicker("getDate");
                let conditionsArray = [];
                if (JobName) {
                    if (!data[1])
                        return false;
                    var value = data[1].toLowerCase();
                    if (!value.toString().includes(JobName.toLowerCase()))
                        return false;
                }
                if ((RequestFrom || RequestTo) && !data[5]) {
                    return false;
                } else {
                    if (RequestFrom) {
                        if (new Date(RequestFrom) > new Date(convertStringToDate(data[5]))) {
                            return false;
                        }
                    }
                    if (RequestTo) {
                        if (new Date(RequestTo) < new Date(convertStringToDate(data[5]))) {
                            return false;
                        }
                    }
                }
                if ((CompletionFrom || CompletionTo) && !data[6]) {
                    return false;
                } else {
                    if (CompletionFrom) {
                        if (new Date(CompletionFrom) > new Date(convertStringToDate(data[6]))) {
                            return false;
                        }
                    }
                    if (CompletionTo) {
                        if (new Date(CompletionTo) < new Date(convertStringToDate(data[6]))) {
                            return false;
                        }
                    }
                }

                if ((ExpirationFrom || ExpirationTo) && !data[7]) {
                    return false;
                } else {

                    if (ExpirationFrom) {

                        if (new Date(ExpirationFrom) > new Date(convertStringToDate(data[7]))) {
                            return false;
                        }
                    }
                    if (ExpirationTo) {
                        if (new Date(ExpirationTo) < new Date(convertStringToDate(data[7]))) {
                            return false;
                        }
                    }
                }

                if ($("#successCheckBox")[0].checked === true) {
                    conditionsArray.push("success");
                }
                if ($("#failedCheboxBox")[0].checked === true) {
                    conditionsArray.push("failed");
                }
                if (conditionsArray.length > 0 && (!data[2] || conditionsArray.indexOf(data[2].trim().toLowerCase()) === -1)) {
                    return false;
                }
                return res;
            }
        );

        function resetSearch() {
            $("#searchModal input").val("");
            $("#successCheckBox").attr('checked', false);
            $("#failedCheboxBox").attr('checked', false);
            $('#jobsTable').DataTable().draw();
        }

        function applySearch() {
            $('#jobsTable').DataTable().draw();
        }

        function convertStringToDate(string,split="/"){
            string = string.split(" ")[0];
            var from = string.split(split);
            return new Date(from[2], from[1] - 1, from[0]);
        }
    </script>
@endsection
