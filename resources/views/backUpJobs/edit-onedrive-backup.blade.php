@extends('layouts.app')
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/main.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/tabs.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/repositories.css') }}" />
<link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/generalElement.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('/css/restore-customize.css') }}" />
<style>
    .check-mark:not(.large):after {
        left: 3px!important;
        top: -5px!important;
        width: 6px!important;
        height: 12px!important;
    }
</style>
@section('topnav')

    <div class="col-sm-10 navbarLayout">
        <!-- Upper navbar -->
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm upperNavBar"> -->
        <ul class="ulNavbar">

            <li class="liNavbar"><a href="{{ url('backup', $arr['typeId']) }}">Backup Jobs <img class="nav-arrow"
                        src="/svg/arrow-right.svg"> OneDrive</a></li>
            <li class="liNavbar"><a class="active" href="{{ url('backup',[$arr['typeId'],'add']) }}">Edit Job</a></li>
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
            <div class="row" style="margin-bottom: 11px">
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
                                <input type="text" required class="required form_input form-control" value="{{ $arr['job']->name }}"
                                    id="jobName" placeholder="Enter Job Name" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="jobDesc">Job Description</label>
                            <div class="col-sm-8">
                                <textarea id="jobDesc" required class="required form_input form-control" name="jobDesc" rows="1"
                                    maxlength="500" placeholder="Enter Job Description">{{ $arr['job']->description }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="repositoryName">Repository Name</label>
                            <div class="col-sm-8">
                                <select class="form_input form-control required" required name="repositories" id="repositories">
                                    <option value="" disabled>Select Repository</option>
                                    @if (!empty($arr['repos']))
                                        @foreach ($arr['repos'] as $repo)
                                            <option value="{{ $repo->id }}">{{ $repo->name }}</option>
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
                                <select class="form_input form-control required" required name="schedule" id="schedule">
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

                                    <option value="Everyday">Every Day</option>
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
            <div class="row" style="margin-bottom: 60px">
                <div class="col-sm-12 nopadding" style="margin-bottom: 11px;">
                    <h5 class="txt-blue" style="width: fit-content;" data-placement="bottom" style="cursor: pointer;"
                        data-toggle="tooltip" title="This list have all users and groups">Filter & Select Data To Backup</h5>

                </div>
                <div class="col-sm-12 nopadding">
                    <div class="radioDiv flex">
                        @php $allItems = optional($arr['selectedItems'][0])->type == "PartialOrganization"? true:false @endphp
                        <div class="radio m-0">
                            <label class="mr-4">
                                <input type="radio" name="itemsType" class="itemsType" value="selectedItems" @if (!$allItems) checked @endif>Selected
                                Items
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="radio" style="margin-top: 0!important;">
                            <label class="mr-4">
                                <input type="radio" name="itemsType" class="itemsType" value="allItems" @if ($allItems) checked @endif>All Items
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div id="selectedItemsCont" class="allWidth">
                    <div class="col-sm-2 nopadding" style="z-index: 5">
                        <ul class="nav-t nav-tabs">
                            <li class="active"><a onclick="adjustTable()" href="#tab1">Users</a></li>
                            <li><a onclick="adjustTable()" href="#tab2">Groups</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-12" style="margin-top: -40px;">
                        <div id="tab1" class="tab-content active">
                            <div class="row">
                                <div class="allWidth">
                                    <table id="usersTable" class="stripe table table-striped table-dark" style="width:100%">
                                        <thead class="table-th">
                                            <th style="text-align: left;padding-left: 35px;"> Name </th>
                                            <th>Email</th>
                                            <th>Type</th>
                                            <th style="padding-left: 4px;">
                                                <label class="checkbox-container">&nbsp;
                                                    <input type="checkbox" class="form-check-input" id="allUserOneDrive" />
                                                    <span class="check-mark"></span>
                                                </label>
                                                User OneDrive
                                            </th>
                                            <th style="padding-left: 4px;">
                                                <label class="checkbox-container">&nbsp;
                                                    <input type="checkbox" class="form-check-input" id="allUserSite" />
                                                    <span class="check-mark"></span>
                                                </label>
                                                User Site
                                            </th>

                                        </thead>
                                        <tbody id="table-content1">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3"></th>
                                                <th class="usersum" colspan="2">0 Users Selected</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <div id="tab2" class="tab-content hide">
                            <div class="row">

                                <div class="allWidth">
                                    <table id="groupsTable" class="stripe table table-striped table-dark" style="width:100%">
                                        <thead class="table-th">
                                            <th style="text-align: left;padding-left: 35px;"> Name </th>
                                            <th>Email</th>
                                            <th>Type</th>

                                            <th style="padding-left: 4px;">
                                                <label class="checkbox-container">&nbsp;
                                                    <input type="checkbox" class="form-check-input" id="allGroupUserOneDrive" />
                                                    <span class="check-mark"></span>
                                                </label>
                                                User OneDrive
                                            </th>
                                            <th style="padding-left: 4px;">
                                                <label class="checkbox-container">&nbsp;
                                                    <input type="checkbox" class="form-check-input" id="allGroupUserSite" />
                                                    <span class="check-mark"></span>
                                                </label>
                                                User Site
                                            </th>
                                            <th style="padding-left: 4px;">
                                                <label class="checkbox-container">&nbsp;
                                                    <input type="checkbox" class="form-check-input" id="allGroupSite" />
                                                    <span class="check-mark"></span>
                                                </label>
                                                Group Site
                                            </th>

                                        </thead>
                                        <tbody id="table-content2">


                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3"></th>
                                                <th class="groupsum" colspan="3">0 Groups Selected</th>
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
                                    <table id="allItemsTable" class="stripe table table-striped table-dark" style="width:100%">
                                        <thead class="table-th">
                                            <th style="text-align: left;padding-left: 35px;">Members</th>
                                            <th style="padding-left: 4px;">
                                                Onedrives
                                            </th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="left-col">Organization</td>
                                                <td>
                                                    @php
                                                        $allOnedrives = optional($arr['selectedItems'][0])->oneDrive;
                                                    @endphp
                                                    <label class="checkbox-container" style="left: 46%;">&nbsp;
                                                        <input type="checkbox" class="form-check-input" id="allOnedrives" @if ($allOnedrives) checked @endif />
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
            <div class="row resultTableCont">
                <div class="col-sm-12 nopadding">
                    <h5 class="txt-blue">Selected Users & Groups</h5>
                </div>
                <div class="col-sm-12 nopadding" style="margin-top: -25px;">
                    <div>
                        <table id="result-table" class="table table-striped table-dark selected-items-table">
                            <thead class="table-th">
                                <th> Name </th>
                                <th>Email</th>
                                <th>Type</th>

                                <th>User OneDrive</th>
                                <th>User Site</th>
                                <th>Group Site</th>

                            </thead>
                            <tbody id="result-table-content">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6">


                                        <div class="resgroupsum"> 0 Groups Selected</div>
                                        <div class="sep">|</div>
                                        <div class="resusersum">0 Users Selected</div>

                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row actions-row" style="margin-bottom: 10px;margin-top:25px;">
                <div class="col-lg-8"></div>
                <div class="col-lg-4 nopadding">
                    <a href="{{ url('backup', $arr['typeId']) }}" class="cancel-button btn_primary_state right-float mr-0">
                        Cancel Backup Job</a>
                    <button onclick="editBackupJob(event)" class="btn_primary_state right-float">
                        Save Backup Job</button>
                </div>
            </div>
        </form>
    </div>



    <div id="userSearchModal" class="modal" role="dialog">
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
                        <div class="input-form-70 mb-1">User Type:</div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container checkbox-search">&nbsp;
                                    <input id="usUser" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="large check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">User</span>
                            </div>
                            <div class="halfWidth" style="position: relative;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container checkbox-search">&nbsp;
                                    <input id="usSharedMail" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="large check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Shared Mail</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70" style="display: inline-flex;">
                            <button type="button" onclick="applyUsersSearch()"
                                class="btn_primary_state  halfWidth mr-25">Apply</button>
                            <button type="button" class="btn_cancel_primary_state  halfWidth" onclick="resetUsersSearch()"
                                data-dismiss="modal">Reset</button>
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>


        </div>
    </div>
    <div id="groupSearchModal" class="modal" role="dialog">
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
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:33.3333%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container checkbox-search">&nbsp;
                                    <input id="gsoffice" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="large check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Office365</span>
                            </div>
                            <div style="position: relative;width:33.3333%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container checkbox-search">&nbsp;
                                    <input id="gssec" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="large check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Security
                                </span>
                            </div>
                            <div style="position: relative;width:33.3333%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container checkbox-search">&nbsp;
                                    <input id="gsdist" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="large check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Distribution
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-form-70" style="display: inline-flex;">
                            <button type="button" onclick="applyGroupsSearch()"
                                class="btn_primary_state  halfWidth mr-25">Apply</button>
                            <button type="button" class="btn_cancel_primary_state halfWidth"
                                onclick="resetGroupsSearch()">Reset</button>
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>

        </div>
    </div>



    <script>
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
        })
        $(window).load(function() {
            let selectedItems = @json($arr['selectedItems']);
            $('.submenu-backup .backup2').addClass('active');
            var row = $('a.sub-menu-link.active').closest('.row');
            row.find('.left-nav-list').addClass('active').removeClass('collapsed');
            $('.submenu-backup').addClass('in');

            $('#timepicker').mdtimepicker({ is24hour :true,format: 'hh:mm' });
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
                        "data": "email",
                    },
                    {
                        "class": "col-lg-1",
                        "data": "type",
                    },
                    {
                        "data": null,
                        "class": "col-lg-2",
                        "render": function(data) {
                            let res = `<input type="hidden" class="resultId" value="${data.id}" />`;
                            if (data.userEmail) {
                                res = res + `<i class="fa fa-check"></i>`;
                            }
                            return res;
                        }
                    },
                    {
                        "data": null,
                        "class": "col-lg-2",
                        "render": function(data) {
                            if (data.userArchive) {
                                return `<i class="fa fa-check"></i>`;
                            }
                        }
                    },
                    {
                        "data": null,
                        "class": "col-lg-2",
                        "render": function(data) {
                            if (data.groupEmail) {
                                return `<i class="fa fa-check"></i>`;
                            }
                        }
                    }
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

            $('#usersTable').DataTable({
                "initComplete": function(settings, json) {
                    selectedItems.forEach(function(element){
                        if(element.type == "User"){
                            let row = $('#usersTable tr#'+element.user.id);
                            row.find(".useronedrive").attr("checked", element.oneDrive);
                            row.find(".usersite").attr("checked", element.site);
                        }
                    });
                    fillResultTable();
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-type', 'User');
                    $(row).attr('id', data.id);


                },
                "fnDrawCallback": function() {
                    var icon =
                        '<div class="search-container"><img class="search-icon" src="/svg/search.svg"></div>';
                    if($("#usersTable_filter label").find('.search-icon').length == 0)
                    $('#usersTable_filter label').append(icon);
                    $('#usersTable_filter input').addClass('form_input form-control');
                },
                'ajax': {
                    "type": "GET",
                    "url": "{{ url('getOrganizationUsers') }}",
                    "dataSrc": '',
                    "data": "",
                    "dataType": "json",

                },
                "scrollY": "300px",
                "scrollCollapse": true,
                "bInfo": false,
                "paging": false,
                "autoWidth": false,
                "processing": true,
                "columns": [{
                        "class": "col-lg-3 left-col",
                        "data": "displayName"
                    },
                    {
                        "class": "col-lg-4 left-col",
                        "data": "name"
                    },
                    {
                        "class": "col-lg-1",
                        "data": "type"
                    },
                    {
                        "class": "col-lg-2",
                        "data": null,
                        render: function(data, type, full, meta) {
                            let res = '  <label class="checkbox-container">&nbsp;' +
                                '<input id="checkbox" type="checkbox" class="form-check-input useronedrive" id="useronedrive" />' +
                                '<span class="check-mark"></span>' +
                                '</label>';
                            if (data.type == "User")
                                return res;
                            else return "";
                        }
                    },
                    {
                        "class": "col-lg-2",
                        "data": null,
                        render: function(data, type, full, meta) {
                            let res = '  <label class="checkbox-container">&nbsp;' +
                                '<input id="checkbox" type="checkbox" class="form-check-input usersite" id="usersite" />' +
                                '<span class="check-mark"></span>' +
                                '</label>';
                            if (data.type == "User")
                                return res;
                            else return "";
                        }
                    }
                ],
                dom: 'Bfrtip',
                buttons: [

                    {
                        text: '<img src="/svg/filter.svg" style="width:15px;height:30px;">',
                        titleAttr: 'Advanced Search',
                        action: function(e, dt, node, config) {
                            // $('#userSearchModal').modal('show');
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
                    'targets': [4, 3], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });


            $('#groupsTable').DataTable({
                "initComplete": function(settings, json) {
                    selectedItems.forEach(function(element){
                        if(element.type == "Group"){
                            let row = $('#groupsTable tr#'+element.group.id);
                            row.find(".useronedrive").attr("checked", element.memberOnedrive);
                            row.find(".usersite").attr("checked", element.memberSite);
                            row.find(".groupsite").attr("checked", element.groupSite);
                        }
                    });
                    fillResultTable();
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-type', 'Group');
                    $(row).attr('id', data.id);


                },
                "fnDrawCallback": function() {
                    var icon =
                        '<div class="search-container"><img class="search-icon" src="/svg/search.svg"></div>';
                    if($("#groupsTable_filter label").find('.search-icon').length == 0)
                    $('#groupsTable_filter label').append(icon);
                    $('#groupsTable_filter input').addClass('form_input form-control');
                },
                'ajax': {
                    "type": "GET",
                    "url": "{{ url('getOrganizationGroups') }}",
                    "dataSrc": '',
                    "data": "",
                    "dataType": "json",

                },
                "scrollY": "300px",
                "scrollCollapse": true,
                "bInfo": false,
                "paging": false,
                "autoWidth": false,
                "processing": true,
                "columns": [{
                        "class": "col-lg-2 left-col",
                        "data": "displayName"
                    },
                    {
                        "class": "col-lg-3 left-col",
                        "data": null,
                        render: function(data, type, full, meta) {
                            if (data.name) return data.name;
                            else return "";
                        }
                    },
                    {
                        "class": "col-lg-1",
                        "data": "type"
                    },
                    {
                        "class": "col-lg-2",
                        "data": null,
                        render: function(data, type, full, meta) {
                            let res = '  <label class="checkbox-container">&nbsp;' +
                                '<input id="checkbox" type="checkbox" class="form-check-input useronedrive" id="useronedrive" />' +
                                '<span class="check-mark"></span>' +
                                '</label>';
                            return res;
                        }
                    },
                    {
                        "class": "col-lg-2",
                        "data": null,
                        render: function(data, type, full, meta) {
                            let res = '  <label class="checkbox-container">&nbsp;' +
                                '<input id="checkbox" type="checkbox" class="form-check-input usersite" id="usersite" />' +
                                '<span class="check-mark"></span>' +
                                '</label>';
                            return res;
                        }
                    },
                    {
                        "class": "col-lg-2",
                        "data": null,
                        render: function(data, type, full, meta) {
                            let res = '  <label class="checkbox-container">&nbsp;' +
                                '<input id="checkbox" type="checkbox" class="form-check-input groupsite" id="groupsite" />' +
                                '<span class="check-mark"></span>' +
                                '</label>';
                            if (data.type == "Office365")
                                return res;
                            else return "";
                        }
                    },
                ],
                dom: 'Bfrtip',
                buttons: [

                    {
                        text: '<img src="/svg/filter.svg" style="width:15px;height:30px;">',
                        titleAttr: 'Advanced Search',
                        action: function(e, dt, node, config) {
                            $('#groupSearchModal').modal('show');
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
                    'targets': [3, 4, 5], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            $('#allItemsTable').DataTable({
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-type', 'Group');
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
            $('#usersTable').DataTable().buttons().container()
                .prependTo('#usersTable_filter');

            $('#groupsTable').DataTable().buttons().container()
                .prependTo('#groupsTable_filter');
            $("#usersTable").on('change', ".form-check-input", function(e) {
                fillResultTable();
            });
            $("#groupsTable").on('change', ".form-check-input", function(e) {
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
                $("#days option[value='{{ optional($arr['job']->schedulePolicy)->dailyType }}']")[0].selected = true;
                $("#schedule option[value='1']")[0].selected = true;


            @endif
            $("#repositories option[value='{{ $arr['backupRepo'] }}']")[0].selected = true;
        });

        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                res = true;

                if (settings.nTable.id !== 'usersTable') {
                    return true;
                }

                usUser = $("#usUser")[0].checked;
                usSharedMail = $("#usSharedMail")[0].checked;

                if (usUser == true || usSharedMail == true) {

                    if (usUser == false && data[2] == "User")
                        res = false;
                    if (usSharedMail == false && data[2] == "SharedMail")
                        res = false;


                }
                return res;
            }
        );

        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                res = true;

                if (settings.nTable.id !== 'groupsTable') {
                    return true;
                }

                gsoffice = $("#gsoffice")[0].checked;
                gssec = $("#gssec")[0].checked;
                gsdist = $("#gsdist")[0].checked;

                if (gsoffice == true || gssec == true || gsdist == true) {

                    if (gsoffice == false && data[2] == "Office365")
                        res = false;
                    if (gssec == false && data[2] == "Security")
                        res = false;
                    if (gsdist == false && data[2] == "Distribution")
                        res = false;

                }



                return res;
            }
        );


        function resetUsersSearch() {
            $("#usUser").attr('checked', false);
            $("#usSharedMail").attr('checked', false);

            $('#usersTable').DataTable().draw();
        }

        function resetGroupsSearch() {
            $("#gsoffice").attr('checked', false);
            $("#gssec").attr('checked', false);
            $("#gsdist").attr('checked', false);
            $('#groupsTable').DataTable().draw();
        }

        function applyUsersSearch() {
            $('#usersTable').DataTable().draw();
        }

        function applyGroupsSearch() {
            $('#groupsTable').DataTable().draw();
        }



        $("#allUserOneDrive").change(function() {
            let usersRows = $("#usersTable tbody#table-content1").find("tr[data-type='User']");
            let checkStatus = $(this)[0].checked;
            usersRows.find("input.useronedrive").prop("checked", checkStatus);
            fillResultTable();
        });

        $("#allUserSite").change(function() {
            let usersRows = $("#usersTable tbody#table-content1").find("tr[data-type='User']");
            let checkStatus = $(this)[0].checked;
            usersRows.find("input.usersite").prop("checked", checkStatus);
            fillResultTable();
        });
        $("#allGroupUserOneDrive").change(function() {
            let groupsRows = $("#groupsTable tbody#table-content2").find("tr[data-type='Group']");
            let checkStatus = $(this)[0].checked;
            groupsRows.find("input.useronedrive").prop("checked", checkStatus);
            fillResultTable();
        });
        $("#allGroupUserSite").change(function() {
            let groupsRows = $("#groupsTable tbody#table-content2").find("tr[data-type='Group']");
            let checkStatus = $(this)[0].checked;
            groupsRows.find("input.usersite").prop("checked", checkStatus);
            fillResultTable();
        });

        $("#allGroupSite").change(function() {
            let groupsRows = $("#groupsTable tbody#table-content2").find("tr[data-type='Group']");
            let checkStatus = $(this)[0].checked;
            groupsRows.find("input.groupsite").prop("checked", checkStatus);
            fillResultTable();
        });



        function fillResultTable() {
            let rows = $("#usersTable_wrapper").find("tr[data-type='User']:has(input:checked)");
            let data = [];
            let usersum = useronedriveSum = usersiteSum = 0;
            let groupsum = groupUserEmailSum = groupUserArchiveSum = groupEmailSum = 0;
            rows.each(function() {
                let row = $(this);
                useronedrive = row.find(".useronedrive:checked").length > 0;
                usersite = row.find(".usersite:checked").length > 0;
                if (useronedrive) useronedriveSum++;
                if (usersite) usersiteSum++;
                data.push({
                    "id": row.prop("id"),
                    "name": row.find("td:first").html(),
                    "email": row.find("td:nth(1)").html(),
                    "type": "User",
                    "userEmail": useronedrive ? true : false,
                    "userArchive": row.find(".usersite:checked").length > 0 ? true : false,
                });
                usersum++;
            });
            rows = $("#groupsTable_wrapper").find("tr[data-type='Group']:has(input:checked)");
            rows.each(function() {
                let row = $(this);
                useronedrive = row.find(".useronedrive:checked").length > 0;
                usersite = row.find(".usersite:checked").length > 0;
                groupsite = row.find(".groupsite:checked").length > 0;
                if (useronedrive) groupUserEmailSum++;
                if (usersite) groupUserArchiveSum++;
                if (groupsite) groupEmailSum++;
                data.push({
                    "id": row.prop("id"),
                    "name": row.find("td:first").html(),
                    "email": row.find("td:nth(1)").html(),
                    "type": row.find("td:nth(2)").html(),
                    "userEmail": useronedrive ? true : false,
                    "userArchive": usersite ? true : false,
                    "groupEmail": groupsite ? true : false,
                });
                groupsum++;
            });
            $("#result-table").DataTable().clear();
            $("#result-table").DataTable().rows.add(data);
            $(".usersum").html(usersum + " Users Selected");
            $(".groupsum").html(groupsum + " Groups Selected")
            adjustTable();
            //------------------------------------//
            if (usersum > 0) {
                $(".resusersum").html(usersum + " Users Selected (" + useronedriveSum + " User Onedrive, " + usersiteSum +
                    " User Site)");
            } else $(".resusersum").html("0 Users Selected");
            if (groupsum > 0)
                $(".resgroupsum").html(groupsum + " Groups Selected (" + groupUserEmailSum + " User Onedrive, " +
                    groupUserArchiveSum + " User Site, " + groupEmailSum + " Group Site)")
            else $(".resgroupsum").html("0 Groups Selected");
            //------------------------------------//
            $("#result-table").DataTable().draw();
        }

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

        function editBackupJob(event) {
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
            let allOnedrives = $('#allOnedrives').prop('checked');

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
            } else if (!allOnedrives && itemsType == "allItems") {
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
                    let id = row.find(".resultId").val();
                    let useronedrive = row.find("td:nth(3) i").length > 0;
                    let usersite = row.find("td:nth(4) i").length > 0;
                    let groupsite = row.find("td:nth(5) i").length > 0;
                    let backUpObj = {
                        "id": id,
                        "displayName": row.find("td:first").html(),
                        "Name": row.find("td:nth(1)").html() ? row.find("td:nth(1)").html() : "-",
                        "type": row.find("td:nth(2)").html()
                    };
                    let backupRow = row.find("td:nth(2)").html() == "User" ? {
                        "type": "User",
                        "user": backUpObj,
                        "mailbox": false,
                        "archiveMailbox": false,
                        "oneDrive": useronedrive ? true : false,
                        "site": usersite ? true : false
                    } : {
                        "type": "Group",
                        "group": backUpObj,
                        "members": (useronedrive || usersite) ? true : false,
                        "memberMailbox": false,
                        "memberArchiveMailbox": false,
                        "memberOnedrive": useronedrive ? true : false,
                        "memberSite": usersite ? true : false,
                        "mailbox": false,
                        "groupSite": groupsite ? true : false,
                        "site": groupsite ? true : false
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
                        "oneDrive": allOnedrives,
                        "site": false,
                        "teams": false,
                    }],
                    "schedulePolicy": schedulePolicy
                };
            }
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('editBackup',$arr['typeId']) }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    itemsType: itemsType,
                    kind: 'onedrive',
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

    </script>

@endsection
