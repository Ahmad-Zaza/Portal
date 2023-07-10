<!-- ----------------------------------------------------------------------------------------------------------------------------- -->
<div class="row azure-custom-info ml-25 mr-0 mb-20">
    <div class="col-lg-12">
        <div class="row pl-0">
            <h5 class="txt-blue">Description</h5>
        </div>
        <div class="row newInfoRow mb-0">
            <div class="rowBorderRight"></div>
            <div class="rowBorderBottom"></div>
            <div class="rowBorderleft"></div>
            <div class="rowBorderUp"></div>
            <p class="fn-13">Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum, quia placeat amet
                quae accusantium
                reiciendis facilis.</p>
        </div>
    </div>
</div>
<!-- Create user Modal -->
<div id="createNewUserModal" class="modal" role="dialog">

    <div class="modal-dialog" style="margin: 10vh auto;">
        <div class="divBorderRight"></div>
        <div class="divBorderBottom"></div>
        <div class="divBorderleft"></div>
        <div class="divBorderUp"></div>
        <!-- Modal content-->
        <div class="modal-content ">
            <form id="userForm" onsubmit="saveUser(event)">
                <div id="userModal" class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-5">
                            <h4 class="per-req">User Info</h4>
                        </div>
                        <div class="col-lg-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="modal-body col-lg-8 nopadding-bottom">
                            <input type="hidden" name="userId">
                            <input type="email" class="form_input form-control" id="email" name="email"
                                placeHolder="Enter User Email" required>
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="modal-body col-lg-8">
                            <select class="form_dropDown form-control" id="roleId" name="roleId" required>
                                <option value="">Select Role</option>
                                @foreach ($roles as $bac_role)
                                    <option value="{{ $bac_role->id }}">{{ $bac_role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                    <div class="row pb-10">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <button type="submit" class="btn_primary_state allWidth">Save</button>
                        </div>
                        <div class="col-lg-2"></div>

                    </div>
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <button type="button" class="btn_cancel_primary_state" data-dismiss="modal"
                                onClick="clearModalData()">Close</button>
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </form>
        </div>

    </div>
</div>
<!-- ----------------------------------------------------------------------------------------------------------------------------- -->
<!-- All users table -->
<div class="row mt-2 ml-10p mr-80">
    <div class="repositoryTable">
        <table id="usersTable" class="stripe table table-striped table-dark" style="width:100%">
            <thead class="table-th">
                <th class="after-none"></th>
                <th class="text-left">Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Registration Date</th>
                <th>Role</th>
                <th>Status</th>
                <th>Last Login Time</th>
                <th></th>
            </thead>
            <tbody>

            </tbody>
        </table>
        @if ($role->hasPermissionTo('users_add'))
            <div class="col-lg-12" style="z-index: 5;">
                <button class="editBtn btn_primary_state custom-right-float-account" data-toggle="modal"
                    data-target="#createNewUserModal">
                    Add User</button>
            </div>
        @endif
    </div>
</div>
<!-- ----------------------------------------------------------------------------------------------------------------------------- -->


<!------------------- delete modal ---------------------- -->
<div id="userActionModal" class="modal" role="dialog">

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

                    <div class="swal-title text-center mb-0"><span class="action"></span> User</div>
                    <div class="row">
                        <input type="hidden" name="userId" id="userId">
                        <div id="deleteTxt" class="modal-body basic-color text-center mt-22">
                            Are You Sure ?
                        </div>
                    </div>

                    <div class="row mt-10">
                        <div class="input-form-70 inline-flex">
                            <button type="button" class="btn_primary_state allWidth mr-25"
                                onClick="actionUser(event);"><span class="action"></span></button>
                            <button type="button" class="btn_cancel_primary_state allWidth"
                                data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- ----------------------------------------------------------------------------------------------------------------------------- -->
<script>
    $(document).ready(function() {

        $('#usersTable').DataTable({
            'ajax': {
                "type": "GET",
                "url": "{{ url('getUsers') }}",
                "dataSrc": '',
                "data": "",
                "dataType": "json",
            },
            "order": [
                [1, 'asc']
            ],
            'columns': [{
                    "data": null,
                    "class": "after-none",
                    render: function(data, type, full, meta) {
                        return '<img class= "tableIcone" style="margin-left:0px;width: 13px; margin-right:0;" src="/svg/r.svg " title="User">' +
                            '<input type="hidden" class="id" value="' + data.id + '">' +
                            '<input type="hidden" class="email" value="' + data.email + '">' +
                            '<input type="hidden" class="role_id" value="' + data.role_id +
                            '">';
                    }
                },
                {
                    "data": null,
                    "class": "text-left",
                    "render": function(data) {
                        if (data.first_name)
                            return data.first_name + " " + (data.last_name?data.last_name:"");
                    }
                },
                {
                    "data": "email",
                },
                {
                    "data": "phone",
                },
                {
                    "data": null,
                    "render": function(data) {
                        if (data.registration_date)
                            return formatDate(data.registration_date);
                    }
                },
                {
                    "data": null,
                    "render": function(data) {
                        if (data.role)
                            return data.role.name;
                    }
                },
                {
                    "data": null,
                    "render": function(data) {
                        if (data.status == "active")
                            return "<span class='fa fa-check text-success'></span>";
                        return "<span class='fa fa-close text-danger'></span>";
                    }
                },
                {
                    "data": null,
                    "render": function(data) {
                        if (data.last_login_date)
                            return formatDate(data.last_login_date);
                    }
                },
                {
                    "data": null,
                    render: getUsersTableMenu
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
                if ($(".dataTables_filter label").find('.search-icon').length == 0)
                    $('.dataTables_filter label').append(icon);
                $('.dataTables_filter input').addClass('form_input form-control');
                usersModalActions();
            },
            "scrollY": "500px",
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
        $('#usersTable').DataTable().buttons().container()
            .prependTo('#usersTable_filter');

    });

    function getUsersTableMenu(data, type, full, meta) {
        if (data.is_super_admin != '1' && data.id != "{{ auth()->user()->id }}" && (data.role_id == 1 && "{{ auth()->user()->is_super_admin }}" == 1)) {
            var res = "";
            @if ($role->hasPermissionTo('users_edit'))
                res +=
                    '<a type="button" title="Edit User" class="editUser"><img class="tableIcone " src="/svg/edit.svg"></a>';
            @endif
            @if ($role->hasPermissionTo('users_edit'))
                if (data.status != 'active')
                    res +=
                    '<a type="button" class="enableUser" title="Enable User"><img class="tableIcone " src="/svg/enable.svg"></a>';
                else if (data.status != 'inactive')
                    res +=
                    '<a type="button" class="disableUser" title="Disable User"><img class="tableIcone " src="/svg/stop.svg"></a>';
            @endif
            return res;
        }
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


    function confirmDelete(deletedRepId, deletedRepName, kind) {
        document.getElementById('deletedrep').value = deletedRepId;
        $('#deletedrep').attr('data-kind', kind);
        $("#deleteTxt").html("Delete Storage " + deletedRepName + " ?");
    }

    function showErrorMessage(message) {
        $(".danger-oper .danger-msg").html(message);
        $(".danger-oper").css("display", "block");
        setTimeout(function() {
            $(".danger-oper").css("display", "none");

        }, 8000);
    }

    function usersModalActions(event) {
        $(".editUser").click(function() {
            let row = $(this).closest("tr");;
            $("#createNewUserModal").find("[name='userId']").val(row.find(".id").val());
            $("#createNewUserModal").find("[name='email']").val(row.find(".email").val());
            $("#createNewUserModal").find("[name='roleId']").val(row.find(".role_id").val());
            $("#createNewUserModal").modal("show");
        });
        $(".deleteUser").click(function() {
            let row = $(this).closest("tr");;
            $("#userActionModal").find(".action").html("Delete");
            $("#userActionModal").find("[name='userId']").val(row.find(".id").val());
            $("#userActionModal").modal("show");
        });
        $(".enableUser").click(function() {
            let row = $(this).closest("tr");;
            $("#userActionModal").find(".action").html("Enable");
            $("#userActionModal").find("[name='userId']").val(row.find(".id").val());
            $("#userActionModal").modal("show");
        });
        $(".disableUser").click(function() {
            let row = $(this).closest("tr");;
            $("#userActionModal").find(".action").html("Disable");
            $("#userActionModal").find("[name='userId']").val(row.find(".id").val());
            $("#userActionModal").modal("show");
        });
    }

    function saveUser(event) {
        event.preventDefault();
        if (!$("#userForm")[0].checkValidity())
            return $("#userForm")[0].reportValidity();
        let data = $("#userForm").serialize();
        $(".spinner_parent").css("display", "block");
        $('body').addClass('removeScroll');
        $.ajax({
            type: "POST",
            url: "{{ url('saveUser') }}",
            data: "_token={{ csrf_token() }}&" + data,
            success: function(data) {
                $(".spinner_parent").css("display", "none");
                $('body').removeClass('removeScroll');
                $('#usersTable').DataTable().ajax.reload();
                $("#createNewUserModal").modal('hide');
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

    function actionUser(event) {
        event.preventDefault();
        $(".spinner_parent").css("display", "block");
        $('body').addClass('removeScroll');

        $.ajax({
            type: "POST",
            url: "{{ url('actionUser') }}",
            data: "_token={{ csrf_token() }}&userId=" + $("#userActionModal").find("#userId").val() +
                "&action=" + $("#userActionModal").find(".action").html(),
            success: function(data) {
                $(".spinner_parent").css("display", "none");
                $('body').removeClass('removeScroll');
                $('#usersTable').DataTable().ajax.reload();
                $("#userActionModal").modal('hide');
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

    function clearModalData() {
        $("#createNewUserModal").find("input").val("");
        $("#createNewUserModal").find("select").val("");
    }
</script>
