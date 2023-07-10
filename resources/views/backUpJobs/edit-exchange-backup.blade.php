@extends('layouts.app')
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/main.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/tabs.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/repositories.css') }}" />
<link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/generalElement.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('/css/restore-customize.css') }}" />
<style>
    .check-mark:not(.large):after {
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
                        src="/svg/arrow-right.svg"> Exchange</a></li>
            <li class="liNavbar"><a class="active" href="{{ url('backup', [$arr['typeId'], 'add']) }}">Edit Job</a></li>
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

    <div id="mainContent">
        <form class="backupForm">
            @csrf
            <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
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
                                <input type="text" required class="required form_input form-control"
                                    value="{{ $arr['job']->name }}" id="jobName" placeholder="Enter Job Name" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="jobDesc">Job Description</label>
                            <div class="col-sm-8">
                                <textarea id="jobDesc" class="form_input form-control required" required name="jobDesc" rows="1" maxlength="500"
                                    placeholder="Enter Job Description">{{ $arr['job']->description }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="repositoryName">Repository Name</label>
                            <div class="col-sm-8">
                                <select class="form_input form-control required" required name="repositories"
                                    id="repositories">
                                    <option disabled value="">Select Repository</option>
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
                                <select required class="form_input form-control required" name="days" id="days">
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
                        data-toggle="tooltip" title="This list have all users and groups">Filter & Select Data To Backup
                    </h5>

                </div>
                <div class="col-sm-12 nopadding">
                    <div class="radioDiv flex">
                        @php $allItems = optional($arr['selectedItems'][0])->type == "PartialOrganization"? true:false @endphp
                        <div class="radio m-0">
                            <label class="mr-4">
                                <input type="radio" name="itemsType" class="itemsType" value="selectedItems"
                                    @if (!$allItems) checked @endif>Selected
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
                    <div class="col-sm-2 nopadding" style="z-index: 5">
                        <ul class="nav-t nav-tabs">
                            <li class="active"><a onclick="adjustTable()" href="#tab1">Users</a></li>
                            <li><a onclick="adjustTable()" href="#tab2">Groups</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-12" style="margin-top: -40px">
                        <div id="tab1" class="tab-content active">
                            <div class="row">
                                <div class="allWidth">
                                    <table id="usersTable" class="stripe table table-striped table-dark"
                                        style="width:100%">
                                        <thead class="table-th">
                                            <th style="text-align: left;padding-left: 35px;"> Name </th>
                                            <th>Email</th>
                                            <th>Type</th>

                                            <th style="padding-left: 4px;">
                                                <label class="checkbox-container">&nbsp;
                                                    <input type="checkbox" class="form-check-input" id="allUserEmail" />
                                                    <span class="check-mark"></span>
                                                </label>
                                                User Email
                                            </th>
                                            <th style="padding-left: 4px;">
                                                <label class="checkbox-container">&nbsp;
                                                    <input type="checkbox" class="form-check-input"
                                                        id="allUserArchive" />
                                                    <span class="check-mark"></span>
                                                </label>
                                                User Archive
                                            </th>

                                        </thead>
                                        <tbody id="table-content1">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3"><span class="usersShowing">0</span> Users Showing</th>
                                                <th class="usersum" colspan="2">0 Users Selected</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row mr-0 usersPagination">
                                <div class="col-lg-4 flex">
                                    <button type="button" class="pl-0 btn-sm custom-btn-sm hand previousPage"
                                        onclick="usersPreviousPage()" title="Previous">
                                        <img class="iconColor mt-0 mr-0 hide-pre hide"
                                            src="{{ url('/svg/Pre-02.svg') }}">
                                        <img class="iconColor mt-0 mr-0 show-pre" src="{{ url('/svg/Pre.svg') }}">
                                    </button>
                                    <div class="flex mr-0 align-center">
                                        Users Page <span class="ml-1 mr-1 currentPage">0</span>
                                    </div>
                                    <button class="btn-sm custom-btn-sm hand mr-0 nextPage" onclick="usersNextPage()"
                                        title="Next" type="button">
                                        <img class="iconColor mt-0 show-next hide" src="{{ url('/svg/dash-next.svg') }}">
                                        <img class="iconColor mt-0 hide-next" src="{{ url('/svg/Next-01.svg') }}">
                                    </button>
                                </div>
                                <div class="col-lg-4"></div>
                                <div class="col-lg-4 nopadding flex flex-row-reverse">
                                </div>
                            </div>
                        </div>
                        <div id="tab2" class="tab-content hide">
                            <div class="row">
                                <div class="allWidth">
                                    <table id="groupsTable" class="stripe table table-striped table-dark"
                                        style="width:100%">
                                        <thead class="table-th">
                                            <th style="text-align: left;padding-left: 35px;"> Name </th>
                                            <th>Email</th>
                                            <th>Type</th>
                                            <th style="padding-left: 4px;">
                                                <label class="checkbox-container">&nbsp;
                                                    <input type="checkbox" class="form-check-input"
                                                        id="allGroupUserEmail" />
                                                    <span class="check-mark"></span>
                                                </label>
                                                User Email
                                            </th>
                                            <th style="padding-left: 4px;">
                                                <label class="checkbox-container">&nbsp;
                                                    <input type="checkbox" class="form-check-input"
                                                        id="allGroupUserArchive" />
                                                    <span class="check-mark"></span>
                                                </label>
                                                User Archive
                                            </th>
                                            <th style="padding-left: 4px;">
                                                <label class="checkbox-container">&nbsp;
                                                    <input type="checkbox" class="form-check-input" id="allGroupEmail" />
                                                    <span class="check-mark"></span>
                                                </label>
                                                Group Email
                                            </th>
                                        </thead>
                                        <tbody id="table-content2">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3"><span class="groupsShowing">0</span> Groups Showing
                                                </th>
                                                <th class="groupsum" colspan="3">0 Groups Selected</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row mr-0 groupsPagination">
                                <div class="col-lg-4 flex">
                                    <button type="button" class="pl-0 btn-sm custom-btn-sm hand previousPage"
                                        onclick="groupsPreviousPage()" title="Previous">
                                        <img class="iconColor mt-0 mr-0 hide-pre hide"
                                            src="{{ url('/svg/Pre-02.svg') }}">
                                        <img class="iconColor mt-0 mr-0 show-pre" src="{{ url('/svg/Pre.svg') }}">
                                    </button>
                                    <div class="flex mr-0 align-center">
                                        Groups Page <span class="ml-1 mr-1 currentPage">0</span>
                                    </div>
                                    <button class="btn-sm custom-btn-sm hand mr-0 nextPage" onclick="groupsNextPage()"
                                        title="Next" type="button">
                                        <img class="iconColor mt-0 show-next hide" src="{{ url('/svg/dash-next.svg') }}">
                                        <img class="iconColor mt-0 hide-next" src="{{ url('/svg/Next-01.svg') }}">
                                    </button>
                                </div>
                                <div class="col-lg-4"></div>
                                <div class="col-lg-4 nopadding flex flex-row-reverse">
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
                                                Mails
                                            </th>
                                            <th style="padding-left: 4px;">
                                                Archive
                                            </th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="left-col">Organization</td>
                                                <td>
                                                    @php
                                                        $allMails = optional($arr['selectedItems'][0])->mailbox;
                                                        $allArchives = optional($arr['selectedItems'][0])->archiveMailbox;
                                                    @endphp
                                                    <label class="checkbox-container" style="left: 46%;">&nbsp;
                                                        <input type="checkbox" class="form-check-input" id="allMails"
                                                            @if ($allMails) checked @endif />
                                                        <span class="check-mark"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="checkbox-container" style="left: 46%;">&nbsp;
                                                        <input type="checkbox" class="form-check-input" id="allArchives"
                                                            @if ($allArchives) checked @endif />
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
                <div class="col-sm-12 nopadding">
                    <h5 class="txt-blue">Selected Users & Groups</h5>
                </div>
                <div class="col-sm-12 nopadding" style="margin-top:-25px;">
                    <div>
                        <table id="result-table" class="table table-striped table-dark selected-items-table">
                            <thead class="table-th">
                                <th> Name </th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>User Email</th>
                                <th>User Archive</th>
                                <th>Group Email</th>
                            </thead>
                            <tbody id="result-table-content">

                            </tbody>
                            <tfoot>
                                <tr class="">
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
                <div class="col-lg-8">
                </div>
                <div class="col-lg-4 nopadding">
                    <a href="{{ url('backup', $arr['typeId']) }}"
                        class="cancel-button btn_primary_state right-float mr-0">
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
                                <label style="padding-top: 5px;left: 0px;"
                                    class="checkbox-container checkbox-search">&nbsp;
                                    <input id="usUser" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="large check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">User</span>
                            </div>
                            <div class="halfWidth" style="position: relative;">
                                <label style="padding-top: 5px;left: 0px;"
                                    class="checkbox-container checkbox-search">&nbsp;
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
                            <button type="button" class="btn_cancel_primary_state  halfWidth"
                                onclick="resetUsersSearch()">Reset</button>
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
                                <label style="padding-top: 5px;left: 0px;"
                                    class="checkbox-container checkbox-search">&nbsp;
                                    <input id="gsoffice" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="large check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Office365</span>
                            </div>
                            <div style="position: relative;width:33.3333%;">
                                <label style="padding-top: 5px;left: 0px;"
                                    class="checkbox-container checkbox-search">&nbsp;
                                    <input id="gssec" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="large check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Security
                                </span>
                            </div>
                            <div style="position: relative;width:33.3333%;">
                                <label style="padding-top: 5px;left: 0px;"
                                    class="checkbox-container checkbox-search">&nbsp;
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
                            <button type="button" class="btn_cancel_primary_state  halfWidth"
                                onclick="resetGroupsSearch()" data-dismiss="modal">Reset</button>
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>

        </div>
    </div>


    <script>
        let selectedItems = @json($arr['selectedItemsArr']);
        let usersLinks = {};
        let groupsLinks = {};
        let currentUsersPage = 1;
        let currentGroupsPage = 1;
        let usersOffset = 0;
        let groupsOffset = 0;
        let usersSetId = null;
        let groupsSetId = null;
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
            let selectedUsers = @json($arr['selectedItems']);
            $('.submenu-backup .backup1').addClass('active');
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
                "createdRow": function(row, data, rowIndex) {
                    $(row).attr("data-id", data.id);
                    $(row).attr("data-type", data.item_type);
                },
                'data': function() {
                    return getSelectedItems();
                },
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
                            if (data.usermail) {
                                res = res +
                                    '  <label class="checkbox-container" style="right: 8%;">&nbsp;' +
                                    '<input id="checkbox" type="checkbox" class="form-check-input usermail" checked id="usermail" />' +
                                    '<span class="check-mark"></span>' +
                                    '</label>';
                            } else {
                                res = res +
                                    '  <label class="checkbox-container" style="right: 8%;">&nbsp;' +
                                    '<input id="checkbox" type="checkbox" class="form-check-input usermail" id="usermail" />' +
                                    '<span class="check-mark"></span>' +
                                    '</label>';
                            }
                            return res;
                        }
                    },
                    {
                        "data": null,
                        "class": "col-lg-2",
                        "render": function(data) {
                            if (data.userarchived) {
                                return '  <label class="checkbox-container" style="right: 8%;">&nbsp;' +
                                    '<input id="checkbox" type="checkbox" class="form-check-input userarchived" checked id="userarchived" />' +
                                    '<span class="check-mark"></span>' +
                                    '</label>';
                            }
                            return '  <label class="checkbox-container" style="right: 8%;">&nbsp;' +
                                '<input id="checkbox" type="checkbox" class="form-check-input userarchived" id="userarchived" />' +
                                '<span class="check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": null,
                        "class": "col-lg-2",
                        "render": function(data) {
                            if (data.groupmail) {
                                return '<label class="checkbox-container" style="right: 8%;">&nbsp;' +
                                    '<input id="checkbox" type="checkbox" class="form-check-input groupmail" checked id="groupmail" />' +
                                    '<span class="check-mark"></span>' +
                                    '</label>';
                            } else if (data.item_type == "Group" && data.type == "Office365") {
                                return '<label class="checkbox-container" style="right: 8%;">&nbsp;' +
                                    '<input id="checkbox" type="checkbox" class="form-check-input groupmail" id="groupmail" />' +
                                    '<span class="check-mark"></span>' +
                                    '</label>';
                            }
                        }
                    }
                ],
                "initComplete": function() {
                    $("#result-table").DataTable().clear();
                    $("#result-table").DataTable().rows.add(selectedItems);
                    $("#result-table").DataTable().draw();
                    // summarySelectedItems();

                    let usersCount = $("#result-table_wrapper tbody").find(
                        "tr[data-type='User']:has(input.form-check-input:checked)").length;
                    let groupsCount = $("#result-table_wrapper tbody").find(
                        "tr[data-type!='User']:has(input.form-check-input:checked)").length;

                    let usermailCount = $("#result-table_wrapper tbody").find(
                            "tr[data-type='User']:has(input.form-check-input.usermail:checked)")
                        .length;
                    let userarchivedCount = $("#result-table_wrapper tbody").find(
                            "tr[data-type='User']:has(input.form-check-input.userarchived:checked)")
                        .length;
                    if (usersCount == 0)
                        $("#result-table_wrapper").find(".resusersum").html(usersCount +
                            " Users Selected");
                    else
                        $("#result-table_wrapper").find(".resusersum").html(usersCount +
                            " Users Selected (" + usermailCount +
                            " User Email," + userarchivedCount + " User Archive)");

                    usermailCount = $("#result-table_wrapper tbody").find(
                        "tr[data-type!='User']:has(input.form-check-input.usermail:checked)").length;
                    userarchivedCount = $("#result-table_wrapper tbody").find(
                            "tr[data-type!='User']:has(input.form-check-input.userarchived:checked)")
                        .length;
                    let usergroupCount = $("#result-table_wrapper tbody").find(
                            "tr[data-type!='User']:has(input.form-check-input.groupmail:checked)")
                        .length;
                    if (groupsCount == 0)
                        $("#result-table_wrapper").find(".resgroupsum").html(groupsCount +
                            " Groups Selected");
                    else
                        $("#result-table_wrapper").find(".resgroupsum").html(groupsCount +
                            " Groups Selected (" + usermailCount +
                            " User Email," + userarchivedCount + " User Archive," + usergroupCount +
                            " Group Emai)");
                },
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
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-type', 'User');
                    if (data.id)
                        $(row).attr('data-id', data.id);
                },
                "fnDrawCallback": function() {
                    var icon =
                        '<div class="search-container"><img class="search-icon" src="/svg/search.svg"></div>';
                    if ($("#usersTable_filter label").find('.search-icon').length == 0)
                        $('#usersTable_filter label').append(icon);
                    $('#usersTable_filter input').addClass('form_input form-control');
                    let usersCount = $("#usersTable_wrapper tbody").find(
                        "tr:has(input.form-check-input:checked)").length;
                    $("#usersTable_wrapper .dataTables_scrollFoot").find(".usersum").html(
                        "0 Users Selected");
                    if ($("#usersTable").DataTable().data().count() > 0)
                        $("#usersTable_wrapper .dataTables_scrollFoot").find(".usersum").html(
                            usersCount + " Users Selected");

                    //----------------------------------------------------------//
                    $(".usersShowing").html($("#usersTable").DataTable().data().length);
                    //----------------------------------------------------------//
                    $(".usersPagination").removeClass("hide");
                    $(".usersPagination").find(".currentPage").html(currentUsersPage);
                    $(".usersPagination").find(".show-next").addClass("hide");
                    $(".usersPagination").find(".hide-next").removeClass("hide");
                    $(".usersPagination").find(".show-pre").addClass("hide");
                    $(".usersPagination").find(".hide-pre").removeClass("hide");
                    $(".usersPagination").find(".nextPage,.previousPage").attr("disabled", "disabled");
                    if (usersLinks.next) {
                        $(".usersPagination").find(".nextPage").removeAttr("disabled");
                        $(".usersPagination").find(".show-next").removeClass("hide");
                        $(".usersPagination").find(".hide-next").addClass("hide");
                    }
                    if (usersLinks.prev) {
                        $(".usersPagination").find(".previousPage").removeAttr("disabled");
                        $(".usersPagination").find(".show-pre").removeClass("hide");
                        $(".usersPagination").find(".hide-pre").addClass("hide");
                    }
                    if(!usersLinks.next && !usersLinks.prev){
                        $(".usersPagination").addClass("hide");
                    }
                    //----------------------------------------------------------//
                },
                'ajax': {
                    "type": "GET",
                    "url": "{{ url('getOrganizationUsers') }}",
                    "dataType": "json",
                    "dataSrc": function(res) {
                        usersLinks = res.links;
                        usersSetId = res.setId;
                        return res.data;
                    },
                    "beforeSend": function() {
                        $(".usersPagination").addClass("hide");
                        $('#usersTable > tbody').html(
                            '<tr class="odd">' +
                            '<td valign="top" colspan="35" class="dataTables_empty processing_row"><span class="table-spinner"></span></td>' +
                            '</tr>');
                    },
                    "async": true,
                    data: function(dtp) {
                        dtp.offset = getUsersOffset();
                        dtp.withLinks = true;
                        if (getUsersSetId())
                            dtp.setId = getUsersSetId();
                        return dtp;
                    }
                },
                dom: 'Bfrtip',
                "scrollY": "300px",
                "scrollCollapse": true,
                "bInfo": false,
                "paging": false,
                "autoWidth": false,
                "processing": true,
                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                    'loadingRecords': '&nbsp;',
                    'processing': '<div class="spinner"></div>'
                },
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
                            let index = selectedItems.findIndex((obj => obj.id == data.id && obj
                                .usermail == true));
                            let checked = (index == -1 ? "" : "checked");
                            let res = '  <label class="checkbox-container">&nbsp;' +
                                '<input id="checkbox" type="checkbox" class="form-check-input usermail" ' +
                                checked + ' id="usermail" />' +
                                '<span class="check-mark"></span>' +
                                '</label>';
                            return res;
                        }
                    },
                    {
                        "class": "col-lg-2",
                        "data": null,
                        render: function(data, type, full, meta) {
                            let index = selectedItems.findIndex((obj => obj.id == data.id && obj
                                .userarchived == true));
                            let checked = (index == -1 ? "" : "checked");
                            let res = '  <label class="checkbox-container">&nbsp;' +
                                '<input id="checkbox" type="checkbox" class="form-check-input userarchived" ' +
                                checked + ' id="userarchived" />' +
                                '<span class="check-mark"></span>' +
                                '</label>';
                            if (data.type == "User")
                                return res;
                            else return "";
                        }
                    }
                ],
                buttons: [

                    {
                        text: '<img src="/svg/filter.svg" style="width:15px;height:30px;">',
                        titleAttr: 'Advanced Search',
                        action: function(e, dt, node, config) {
                            $('#userSearchModal').modal('show');
                        }
                    }
                ],
                'columnDefs': [{
                    'targets': [4, 3], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            $('#groupsTable').DataTable({
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-type', 'Group');
                    if (data.id)
                        $(row).attr('data-id', data.id);
                },
                "fnDrawCallback": function() {
                    var icon =
                        '<div class="search-container"><img class="search-icon" src="/svg/search.svg"></div>';
                    if ($("#groupsTable_filter label").find('.search-icon').length == 0)
                        $('#groupsTable_filter label').append(icon);
                    $('#groupsTable_filter input').addClass('form_input form-control');

                    let groupsCount = $("#groupsTable_wrapper tbody").find(
                        "tr:has(input.form-check-input:checked)").length;
                    $("#groupsTable_wrapper .dataTables_scrollFoot").find(".groupsum").html(
                        "0 Groups Selected");
                    if ($("#groupsTable").DataTable().data().count() > 0)
                        $("#groupsTable_wrapper .dataTables_scrollFoot").find(".groupsum").html(
                            groupsCount + " Groups Selected");
                    //----------------------------------------------------------//
                    $(".groupsShowing").html($("#groupsTable").DataTable().data().length);
                    //----------------------------------------------------------//
                    $(".groupsPagination").removeClass("hide");
                    $(".groupsPagination").find(".currentPage").html(currentGroupsPage);
                    $(".groupsPagination").find(".show-next").addClass("hide");
                    $(".groupsPagination").find(".hide-next").removeClass("hide");
                    $(".groupsPagination").find(".show-pre").addClass("hide");
                    $(".groupsPagination").find(".hide-pre").removeClass("hide");
                    $(".groupsPagination").find(".nextPage,.previousPage").attr("disabled", "disabled");
                    if (groupsLinks.next) {
                        $(".groupsPagination").find(".nextPage").removeAttr("disabled");
                        $(".groupsPagination").find(".show-next").removeClass("hide");
                        $(".groupsPagination").find(".hide-next").addClass("hide");
                    }
                    if (groupsLinks.prev) {
                        $(".groupsPagination").find(".previousPage").removeAttr("disabled");
                        $(".groupsPagination").find(".show-pre").removeClass("hide");
                        $(".groupsPagination").find(".hide-pre").addClass("hide");
                    }
                    if (!groupsLinks.next && !groupsLinks.prev) {
                        $(".groupsPagination").addClass("hide");
                    }
                    //----------------------------------------------------------//
                },
                'ajax': {
                    "type": "GET",
                    "url": "{{ url('getOrganizationGroups') }}",
                    "dataType": "json",
                    "dataSrc": function(res) {
                        groupsLinks = res.links;
                        groupsSetId = res.setId;
                        return res.data;
                    },
                    "beforeSend": function() {
                        $(".groupsPagination").addClass("hide");
                        $('#groupsTable > tbody').html(
                            '<tr class="odd">' +
                            '<td valign="top" colspan="35" class="dataTables_empty processing_row"><span class="table-spinner"></span></td>' +
                            '</tr>');
                    },
                    "async": true,
                    data: function(dtp) {
                        dtp.offset = getGroupsOffset();
                        dtp.withLinks = true;
                        if (getGroupsSetId())
                            dtp.setId = getGroupsSetId();
                        return dtp;
                    }
                },
                dom: 'Bfrtip',
                "scrollY": "300px",
                "scrollCollapse": true,
                "bInfo": false,
                "paging": false,
                "autoWidth": false,
                "processing": true,
                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                    'loadingRecords': '&nbsp;',
                    'processing': '<div class="spinner"></div>'
                },
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
                            let index = selectedItems.findIndex((obj => obj.id == data.id && obj
                                .usermail == true));
                            let checked = (index == -1 ? "" : "checked");
                            let res = '  <label class="checkbox-container">&nbsp;' +
                                '<input id="checkbox" type="checkbox" class="form-check-input usermail" ' +
                                checked + ' id="usermail" />' +
                                '<span class="check-mark"></span>' +
                                '</label>';
                            return res;
                        }
                    },
                    {
                        "class": "col-lg-2",
                        "data": null,
                        render: function(data, type, full, meta) {
                            let index = selectedItems.findIndex((obj => obj.id == data.id && obj
                                .userarchived == true));
                            let checked = (index == -1 ? "" : "checked");
                            let res = '  <label class="checkbox-container">&nbsp;' +
                                '<input id="checkbox" type="checkbox" class="form-check-input userarchived" ' +
                                checked + ' id="userarchived" />' +
                                '<span class="check-mark"></span>' +
                                '</label>';
                            return res;
                        }
                    },
                    {
                        "class": "col-lg-2",
                        "data": null,
                        render: function(data, type, full, meta) {
                            let index = selectedItems.findIndex((obj => obj.id == data.id && obj
                                .groupmail == true));
                            let checked = (index == -1 ? "" : "checked");
                            let res = '<label class="checkbox-container">&nbsp;' +
                                '<input id="checkbox" type="checkbox" class="form-check-input groupmail" ' +
                                checked + ' id="groupmail" />' +
                                '<span class="check-mark"></span>' +
                                '</label>';
                            if (data.type == "Office365")
                                return res;
                            else return "";
                        }
                    },
                ],
                buttons: [{
                    text: '<img src="/svg/filter.svg" style="width:15px;height:30px;">',
                    titleAttr: 'Advanced Search',
                    action: function(e, dt, node, config) {
                        $('#groupSearchModal').modal('show');
                    }
                }],
                'columnDefs': [{
                    'targets': [3, 4, 5], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            $("#usersTable,#groupsTable,#result-table").on('change', ".form-check-input", function(e) {
                onItemSelectChange($(this));
                $("#result-table").DataTable().clear();
                $("#result-table").DataTable().rows.add(selectedItems);
                $("#result-table").DataTable().draw();
                summarySelectedItems();
            });

            $('#allItemsTable').DataTable({
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



            @if ($arr['job']->schedulePolicy->scheduleEnabled == false)
                $("#schedule option[value='2']")[0].selected = true;
                $("#timepicker").val("");
                $("#days").val("");
                $("#timepicker").attr("disabled", "disabled");
                $("#days").attr("disabled", "disabled");
            @else
                $("#timepicker").val("{{ optional($arr['job']->schedulePolicy)->dailyTime }}")
                $("#days option[value='{{ optional($arr['job']->schedulePolicy)->dailyType }}']")[0].selected =
                    true;
                $("#schedule option[value='1']")[0].selected = true;
            @endif
            if ($("#repositories option[value='{{ $arr['backupRepo'] }}']")[0])
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


        $("#allUserEmail").change(function() {
            let usersRows = $("#usersTable tbody#table-content1").find("tr[data-type='User']");
            let checkStatus = $(this)[0].checked;
            usersRows.find("input.usermail").prop("checked", checkStatus);

            $("#usersTable tbody").find(".form-check-input").each(function() {
                onItemSelectChange($(this));
            });
            $("#result-table").DataTable().clear();
            $("#result-table").DataTable().rows.add(selectedItems);
            $("#result-table").DataTable().draw();
            summarySelectedItems();
        });

        $("#allUserArchive").change(function() {
            let usersRows = $("#usersTable tbody#table-content1").find("tr[data-type='User']");
            let checkStatus = $(this)[0].checked;
            usersRows.find("input.userarchived").prop("checked", checkStatus);

            $("#usersTable tbody").find(".form-check-input").each(function() {
                onItemSelectChange($(this));
            });
            $("#result-table").DataTable().clear();
            $("#result-table").DataTable().rows.add(selectedItems);
            $("#result-table").DataTable().draw();
            summarySelectedItems();
        });
        $("#allGroupUserEmail").change(function() {
            let groupsRows = $("#groupsTable tbody#table-content2").find("tr[data-type='Group']");
            let checkStatus = $(this)[0].checked;
            groupsRows.find("input.usermail").prop("checked", checkStatus);
            $("#groupsTable tbody").find(".form-check-input").each(function() {
                onItemSelectChange($(this));
            });
            $("#result-table").DataTable().clear();
            $("#result-table").DataTable().rows.add(selectedItems);
            $("#result-table").DataTable().draw();
            summarySelectedItems();
        });
        $("#allGroupUserArchive").change(function() {
            let groupsRows = $("#groupsTable tbody#table-content2").find("tr[data-type='Group']");
            let checkStatus = $(this)[0].checked;
            groupsRows.find("input.userarchived").prop("checked", checkStatus);

            $("#groupsTable tbody").find(".form-check-input").each(function() {
                onItemSelectChange($(this));
            });
            $("#result-table").DataTable().clear();
            $("#result-table").DataTable().rows.add(selectedItems);
            $("#result-table").DataTable().draw();
            summarySelectedItems();
        });

        $("#allGroupEmail").change(function() {
            let groupsRows = $("#groupsTable tbody#table-content2").find("tr[data-type='Group']");
            let checkStatus = $(this)[0].checked;
            groupsRows.find("input.groupmail").prop("checked", checkStatus);

            $("#groupsTable tbody").find(".form-check-input").each(function() {
                onItemSelectChange($(this));
            });
            $("#result-table").DataTable().clear();
            $("#result-table").DataTable().rows.add(selectedItems);
            $("#result-table").DataTable().draw();
            summarySelectedItems();
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

        function editBackupJob(event) {
            event.preventDefault();
            //-------------------------------------//
            if (!$(".backupForm")[0].checkValidity())
                return $(".backupForm")[0].reportValidity();
            jobName = $("#jobName").val();
            jobDesc = $("#jobDesc").val();
            repository = $("#repositories").val();
            schedule = $("#schedule").val();
            timepicker = $("#timepicker").val() ? timeConversion($("#timepicker").val()) : "";
            days = $("#days").find(":selected").val();
            resultRows = $("#result-table-content").find("tr");
            let itemsType = $('.itemsType:checked').val();
            let allMailCheck = $('#allMails').prop('checked');
            let allArchiveCheck = $('#allArchives').prop('checked');

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
            } else if (!allMailCheck && !allArchiveCheck && itemsType == "allItems") {
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
                    let usermail = row.find("td:nth(3)").find(".usermail").prop("checked");
                    let userarchived = row.find("td:nth(4)").find(".userarchived").prop("checked");
                    let groupmail = row.find("td:nth(5)").find(".groupmail").length > 0 ? row.find("td:nth(5)")
                        .find(".groupmail").prop("checked") : false;
                    let backUpObj = {
                        "id": id,
                        "displayName": row.find("td:first").html(),
                        "Name": row.find("td:nth(1)").html() ? row.find("td:nth(1)").html() : "-",
                        "type": row.find("td:nth(2)").html()
                    };
                    let backupRow = row.attr("data-type") == "User" ? {
                        "type": "User",
                        "user": backUpObj,
                        "mailbox": usermail,
                        "archiveMailbox": userarchived,
                        "oneDrive": false,
                        "site": false
                    } : {
                        "type": "Group",
                        "group": backUpObj,
                        "members": (usermail || userarchived),
                        "memberMailbox": usermail,
                        "memberArchiveMailbox": userarchived,
                        "memberOnedrive": false,
                        "memberSite": false,
                        "groupSite": false,
                        "site": false,
                        "mailbox": groupmail,
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
                        "mailbox": allMailCheck,
                        "archiveMailbox": allArchiveCheck,
                        "oneDrive": false,
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
                url: "{{ url('editBackup', $arr['typeId']) }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    kind: 'exchange',
                    itemsType: itemsType,
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

        function summarySelectedItems() {
            let usersCount = $("#result-table_wrapper tbody").find(
                "tr[data-type='User']:has(input.form-check-input:checked)").length;
            $("#usersTable_wrapper .dataTables_scrollFoot").find(".usersum").html("0 Users Selected");
            if ($("#usersTable").DataTable().data().count() > 0)
                $("#usersTable_wrapper .dataTables_scrollFoot").find(".usersum").html(usersCount + " Users Selected");
            let groupsCount = $("#result-table_wrapper tbody").find(
                "tr[data-type!='User']:has(input.form-check-input:checked)").length;
            $("#groupsTable_wrapper .dataTables_scrollFoot").find(".groupsum").html("0 Groups Selected");
            if ($("#groupsTable").DataTable().data().count() > 0)
                $("#groupsTable_wrapper .dataTables_scrollFoot").find(".groupsum").html(groupsCount + " Groups Selected");

            let usermailCount = $("#result-table_wrapper tbody").find(
                    "tr[data-type='User']:has(input.form-check-input.usermail:checked)")
                .length;
            let userarchivedCount = $("#result-table_wrapper tbody").find(
                "tr[data-type='User']:has(input.form-check-input.userarchived:checked)").length;
            if (usersCount == 0)
                $("#result-table_wrapper").find(".resusersum").html(usersCount + " Users Selected");
            else
                $("#result-table_wrapper").find(".resusersum").html(usersCount + " Users Selected (" + usermailCount +
                    " User Email," + userarchivedCount + " User Archive)");

            usermailCount = $("#result-table_wrapper tbody").find(
                "tr[data-type!='User']:has(input.form-check-input.usermail:checked)").length;
            userarchivedCount = $("#result-table_wrapper tbody").find(
                    "tr[data-type!='User']:has(input.form-check-input.userarchived:checked)")
                .length;
            let usergroupCount = $("#result-table_wrapper tbody").find(
                    "tr[data-type!='User']:has(input.form-check-input.groupmail:checked)")
                .length;
            if (groupsCount == 0)
                $("#result-table_wrapper").find(".resgroupsum").html(groupsCount + " Groups Selected");
            else
                $("#result-table_wrapper").find(".resgroupsum").html(groupsCount + " Groups Selected (" + usermailCount +
                    " User Email," + userarchivedCount + " User Archive," + usergroupCount + " Group Emai)");
        }

        function onItemSelectChange($this) {
            let table = $this.closest('table');
            let row = $this.closest('tr');
            let id = row.attr('data-id');
            let objIndex = selectedItems.findIndex((obj => obj.id == id));
            if ($this.hasClass('usermail')) {
                if ($this.prop("checked")) {
                    if (objIndex > -1)
                        selectedItems[objIndex].usermail = true;
                    else
                        selectedItems.push({
                            id: id,
                            name: row.find("td:nth(0)").html(),
                            email: row.find("td:nth(1)").html(),
                            type: row.find("td:nth(2)").html(),
                            usermail: true,
                            userarchived: false,
                            userarchived: false,
                            groupmail: false,
                            item_type: row.attr("data-type"),
                        })
                } else {
                    if (objIndex > -1) {
                        if (!selectedItems[objIndex].userarchived && !selectedItems[objIndex].groupmail) {
                            selectedItems.splice(objIndex, 1);
                        } else {
                            selectedItems[objIndex].usermail = false;
                        }
                    }
                }
                $("tr[data-id='" + id + "']").find(".usermail").prop("checked", $this.prop(
                    "checked"));
            } else if ($this.hasClass('userarchived')) {
                if ($this.prop("checked")) {
                    if (objIndex > -1) {
                        selectedItems[objIndex].userarchived = true;
                    } else
                        selectedItems.push({
                            id: id,
                            name: row.find("td:nth(0)").html(),
                            email: row.find("td:nth(1)").html(),
                            type: row.find("td:nth(2)").html(),
                            usermail: false,
                            userarchived: true,
                            groupmail: false,
                            item_type: row.attr("data-type"),
                        })
                } else {
                    if (objIndex > -1) {
                        if (!selectedItems[objIndex].usermail && !selectedItems[objIndex].groupmail) {
                            selectedItems.splice(objIndex, 1);
                        } else {
                            selectedItems[objIndex].userarchived = false;
                        }
                    }
                }
                $("tr[data-id='" + id + "']").find(".userarchived").prop("checked", $this.prop(
                    "checked"));
            } else if ($this.hasClass('groupmail')) {
                if ($this.prop("checked")) {
                    if (objIndex > -1) {
                        selectedItems[objIndex].groupmail = true;
                    } else
                        selectedItems.push({
                            id: id,
                            name: row.find("td:nth(0)").html(),
                            email: row.find("td:nth(1)").html(),
                            type: row.find("td:nth(2)").html(),
                            usermail: false,
                            userarchived: false,
                            groupmail: true,
                            item_type: row.attr("data-type"),
                        })
                } else {
                    if (objIndex > -1) {
                        if (!selectedItems[objIndex].usermail && !selectedItems[objIndex].userarchived) {
                            selectedItems.splice(objIndex, 1);
                        } else {
                            selectedItems[objIndex].groupmail = false;
                        }
                    }
                }
                $("tr[data-id='" + id + "']").find(".groupmail").prop("checked", $this.prop(
                    "checked"));
            }
        }

        function getUsersOffset() {
            return usersOffset;
        }

        function getGroupsOffset() {
            return groupsOffset;
        }

        function getUsersSetId() {
            return usersSetId;
        }

        function getGroupsSetId() {
            return groupsSetId;
        }

        function usersPreviousPage() {
            if (usersLinks.prev) {
                currentUsersPage--;
                usersOffset = usersLinks.prev;
            }
            $('#usersTable').DataTable().ajax.reload();
        }
        //---------------------------------------------------//
        function usersNextPage() {
            if (usersLinks.next) {
                currentUsersPage++;
                usersOffset = usersLinks.next;
            }
            $('#usersTable').DataTable().ajax.reload();
        }

        function groupsPreviousPage() {
            if (groupsLinks.prev) {
                currentGroupsPage--;
                groupsOffset = groupsLinks.prev;
            }
            $('#groupsTable').DataTable().ajax.reload();
        }
        //---------------------------------------------------//
        function groupsNextPage() {
            if (groupsLinks.next) {
                currentGroupsPage++;
                groupsOffset = groupsLinks.next;
            }
            $('#groupsTable').DataTable().ajax.reload();
        }
    </script>
@endsection
