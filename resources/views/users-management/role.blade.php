@extends('layouts.app')
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/main.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/tabs.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/repositories.css') }}" />
<link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/generalElement.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('/css/restore-customize.css') }}" />
@section('topnav')
    <div class="col-sm-10 navbarLayout">
        <!-- Upper navbar -->
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm upperNavBar"> -->
        <ul class="ulNavbar">

            <li class="liNavbar"><a href="{{ url('users-roles') }}">Users & Roles</a></li>
            <li class="liNavbar"><a class="active" href="{{ url('role/add') }}">New Role</a></li>
            <!-- Authentication Links -->
            @include('layouts.authentication-links')
        </ul>
    </div>
@endsection
<style>
    .check-mark:after {
        left: 3px !important;
        top: -5px !important;
        width: 6px !important;
        height: 12px !important;
    }

    .left-col{
        width: 20vw!important;
    }

</style>
@section('content')
    <script src="{{ url('/js/timepicker/mdtimepicker.js') }}"></script>
    <link href="{{ url('/css/timepicker/jquerysctiptop.css') }}" rel="styltesheet" type="text/css">
    <link href="{{ url('/css/timepicker/mdtimepicker.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('/css/checkbox.css') }}" rel="stylesheet" type="text/css">
    <div id="mainContent" style="margin-left: 2%;margin-right:4%;">
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <form onsubmit="saveRole(event)" id="roleForm">
            <!-- Create new repository button  -->
            <div class="row" style="margin-bottom: 11px;">
                <div class="col-sm-12 nopadding">
                    <h5 class="txt-blue">Role Details</h5>
                </div>
            </div>
            <div class="row newJobRow" style="margin-bottom: 50px;">
                <div class="rowBorderRight"></div>
                <div class="rowBorderBottom"></div>
                <div class="rowBorderleft"></div>
                <div class="rowBorderUp"></div>
                <div class="col-lg-5">
                    <div class="form-horizontal">
                        <div class="form-group mb-0">
                            <label class="control-label col-sm-3" for="roleName">Role Name</label>
                            <div class="col-sm-7">
                                <input type="hidden" name="roleId" value="{{ optional($bac_role)->id }}">
                                <input type="text" class="form_input form-control required" required id="roleName"
                                    name="roleName" placeholder="Enter Role Name"
                                    value="{{ optional($bac_role)->name }}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
                <div class="col-lg-5">
                    <div class="form-horizontal">
                        <div class="form-group mb-0">
                            <label class="control-label col-sm-4" for="roleName">Role Description</label>
                            <div class="col-sm-8">
                                <textarea id="roleDescription" class="required form_input form-control" name="roleDescription" rows="1"
                                    maxlength="500">{{ optional($bac_role)->description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($bac_role)
                <div class="row" style="margin-bottom: 11px;">
                    <div class="col-sm-6 nopadding">
                        <h5 class="txt-blue">Assigned Users</h5>
                    </div>
                    <div class="col-sm-6 relative flex align-items-center justify-content-end">
                    </div>
                </div>
                <div class="row" style="margin-bottom: 50px;">
                    <div class="col-lg-12 p-0">
                        <table id="assignedUsersTable" class="stripe table table-striped table-dark" style="width:100%">
                            <thead class="table-th">
                                <th class="left-col">Email</th>
                                <th>Assigned on Date</th>
                            </thead>
                            <tbody>
                                @foreach ($assignedUsers as $record)
                                    <tr>
                                        <td class="left-col">{{ $record->email }}</td>
                                        <td>{{ $record->role_assigned_date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            @foreach ($permission_categories as $category)
                <div class="row" style="margin-bottom: 11px;">
                    <div class="col-sm-12 nopadding">
                        <h5 class="txt-blue">{{ $category->name }} Permissions</h5>
                        <p>{{ $category->description }}</p>
                    </div>
                    <div class="col-sm-6 relative flex align-items-center justify-content-end">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 p-0">
                        @php
                            $tableId = strtolower(str_replace(' ', '-', $category->name)) . '-table';
                        @endphp
                        <div class="mb-5">
                            <table id="{{ $tableId }}"
                                class="stripe table table-striped table-dark permissionCategoriesTable" style="width:100%;">
                                <thead class="table-th">
                                    <th class="left-col">

                                    </th>
                                    @foreach ($category->distinct_permissions as $permission)
                                        <th>
                                            <label
                                                style="padding-top: 5px;left: 0px;position:relative;text-align: left;padding-left:20px;"
                                                class="checkbox-container checkbox-search">&nbsp;
                                                <input type="checkbox" class="form-check-input category_check_column" />
                                                <span style="top: 37%;left:6px!important;" class="check-mark"></span>
                                                {{ $permission->display_name }}
                                            </label>
                                        </th>
                                    @endforeach
                                </thead>
                                <tbody>
                                    @php
                                    @endphp
                                    @if (count($category->subCategories) > 0)
                                        @foreach ($category->subCategories as $subCat)
                                            <tr>
                                                <td class="left-col">
                                                    <label
                                                        style="padding-top: 5px;left: 0px;position:relative;padding-left: 16px;"
                                                        class="checkbox-container checkbox-search">&nbsp;
                                                        <input id="" type="checkbox"
                                                            class="form-check-input category_check" />
                                                        <span style="top: 37%;left: 0!important;"
                                                            class="check-mark"></span>
                                                        {{ $subCat->name }}
                                                    </label>
                                                </td>
                                                @foreach ($subCat->permissions as $permission)
                                                    <td>
                                                        @php
                                                            $attr = '';
                                                            if ($bac_role) {
                                                                $attr = in_array($permission->id, $bac_role->permissions) ? "checked='checked'" : '';
                                                            }
                                                        @endphp
                                                        @if($permission->display_name)
                                                        <label style="padding-top: 5px;left: 0px;position:relative"
                                                            class="checkbox-container checkbox-search">&nbsp;
                                                            <input name="{{ $permission->name }}" type="checkbox"
                                                                {{ $attr }}
                                                                class="form-check-input permission_check" />
                                                            <span style="top: 37%;left:6px!important;"
                                                                class="check-mark"></span>
                                                        </label>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="left-col">
                                                <label
                                                    style="padding-top: 5px;left: 0px;position:relative;padding-left: 16px;"
                                                    class="checkbox-container checkbox-search">&nbsp;
                                                    <input id="" type="checkbox" class="form-check-input category_check" />
                                                    <span style="top: 37%;left: 0!important;" class="check-mark"></span>
                                                    {{ $category->name }}
                                                </label>
                                            </td>
                                            @foreach ($category->distinct_permissions as $permission)
                                                <td>
                                                    <label style="padding-top: 5px;left: 0px;position:relative"
                                                        class="checkbox-container checkbox-search">&nbsp;
                                                        @php
                                                            $attr = '';
                                                            if ($bac_role) {
                                                                $attr = in_array($permission->id, $bac_role->permissions) ? "checked='checked'" : '';
                                                            }
                                                        @endphp
                                                        <input name="{{ $permission->name }}" type="checkbox"
                                                            {{ $attr }}
                                                            class="form-check-input permission_check" />
                                                        <span style="top: 37%;left:6px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="row actions-row" style="margin-bottom: 25px;margin-top:25px;">
                <div class="col-lg-8">

                </div>
                <div class="col-lg-4 nopadding">
                    <a href="{{ url('roles') }}" class="cancel-button btn_primary_state right-float mr-0">
                        Cancel</a>
                    <button type="submit" onclick="saveRole(event)" class="btn_primary_state right-float">
                        Save Role</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        $(window).on('load', function() {
            $('.select2-selection__arrow').addClass('mt-4p');
            $('.nav-tabs > li > a:not([disabled])').click(function(event) {
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
                adjustTable();
            });

            categoryCheckChange();
            categoryCheckColumnChange();
            permissionCheck();
        });

        function categoryCheckChange() {
            $(".category_check").change(function() {
                let row = $(this).closest('tr');
                row.find(".permission_check").prop("checked", $(this).prop("checked"));
                permissionCheck();

            });
        }

        function categoryCheckColumnChange() {
            $(".category_check_column").change(function() {
                let table = $(this).closest("table").prop("id");
                let position = $(this).closest("th").index();
                $("#" + table).find("td:nth-child(" + (position + 1) + ") .permission_check").prop("checked", $(
                    this).prop("checked"));
                permissionCheck();
            });
        }

        function permissionCheck() {
            $(".permission_check").change(function() {
                let row = $(this).closest("tr");
                let total = row.find(".permission_check").length;
                let checked = row.find(".permission_check:checked").length;
                row.find(".category_check").prop("checked", total == checked);
                //--------------------------------
                let table = $(this).closest("table").prop("id");
                let position = $(this).closest("td").index();
                checked = $("#" + table).find("td:nth-child(" + (position + 1) + ") .permission_check:checked")
                    .length;
                total = $("#" + table).find("td:nth-child(" + (position + 1) + ") .permission_check").length;
                $("#" + table).find("th:nth-child(" + (position + 1) + ") .category_check_column").prop("checked",
                    total == checked);
            })

            $(".permission_check").each(function() {
                let row = $(this).closest("tr");
                let total = row.find(".permission_check").length;
                let checked = row.find(".permission_check:checked").length;
                row.find(".category_check").prop("checked", total == checked);
                //--------------------------------
                let table = $(this).closest("table").prop("id");
                let position = $(this).closest("td").index();
                checked = $("#" + table).find("td:nth-child(" + (position + 1) + ") .permission_check:checked")
                    .length;
                total = $("#" + table).find("td:nth-child(" + (position + 1) + ") .permission_check").length;
                $("#" + table).find("th:nth-child(" + (position + 1) + ") .category_check_column").prop("checked",
                    total == checked);
            })
        }

        function minmizeSideBar() {
            $('.main-content-div').removeClass('col-sm-10').addClass('col-sm-12');
            $('.navbarLayout').removeClass('col-sm-10').addClass('width-94');
            $('.logo-outer-div-min').removeClass('hideMenu');
            $('.leftNavBar-min').removeClass('hideMenu');
            $('.logo-outer-div-max').addClass('hideMenu');
            $('.leftNavBar-max').addClass('hideMenu');
        }
        $(document).ready(function() {
            minmizeSideBar();
        });

        function showSuccessMessage(message) {
            $(".success-oper .success-msg").html(message);
            $(".success-oper").css("display", "block");
            setTimeout(function() {
                $(".success-oper").css("display", "none");
            }, 8000);
        }
    </script>
    <script>
        $(function() {

            $('#assignedUsersTable').DataTable({
                "scrollY": "250px",
                "scrollCollapse": true,
                "bInfo": false,
                "paging": false,
                "autoWidth": false,
                "searching": false,
                // dom: 'Bfrtip',
                "language": {
                    "emptyTable": "No Users Assigned to this Role Yet."
                }
            });
            $(".permissionCategoriesTable").each(function() {
                let id = $(this).prop('id');
                $('#' + id).DataTable({
                    // "scrollY": "250px",
                    // "scrollCollapse": true,
                    "bInfo": false,
                    "paging": false,
                    "sorting": false,
                    "sort": false,
                    "autoWidth": false,
                    "searching": false,
                    "fnDrawCallback": function() {
                        categoryCheckChange();
                        categoryCheckColumnChange();
                    }
                });
            });
        });

        function saveRole(event) {
            event.preventDefault();
            if (!$("#roleForm")[0].checkValidity())
                return $("#roleForm")[0].reportValidity();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            let data = $("#roleForm").serialize();
            $.ajax({
                type: "POST",
                url: "{{ url('saveRole') }}",
                data: "_token={{ csrf_token() }}&" + data,
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    window.location = "{{ url('users-roles') }}?from=roles";
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
    </script>
@endsection
