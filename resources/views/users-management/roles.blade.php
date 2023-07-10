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
<!-- ----------------------------------------------------------------------------------------------------------------------------- -->
<!-- All roles table -->
<div class="row mt-2 ml-10p mr-80">
    <div class="repositoryTable">
        <table id="rolesTable" class="stripe table table-striped table-dark" style="width:100%">
            <thead class="table-th">
                <th class="after-none"></th>
                <th class="text-left">Name</th>
                <th>Description</th>
                <th>Number of Users</th>
                <th>Creation Date</th>
                <th>Last Modified Date</th>
                <th></th>
            </thead>
            <tbody>

            </tbody>
        </table>
        @if ($role->hasPermissionTo('roles_add'))
        <div class="col-lg-12" style="z-index: 5;">
            <a class="editBtn btn_primary_state custom-right-float-account" href="{{ url('role/add') }}">Add Role</a>
        </div>
        @endif
    </div>
</div>


<!------------------- delete modal ---------------------- -->
<div id="deleteUserModal" class="modal" role="dialog">

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

                    <div class="swal-title text-center">Delete User</div>
                    <div class="row">
                        <input type="hidden" name="deletedrep" id="deletedrep">
                        <div id="deleteTxt" class="modal-body basic-color text-center mt-22">
                            Are You Sure ?
                        </div>
                    </div>

                    <div class="row mt-10">
                        <div class="input-form-70 inline-flex">
                            <button type="button" class="btn_primary_state allWidth mr-25"
                                onClick="deleteUser();">Delete</button>
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

        $('#rolesTable').DataTable({
            'ajax': {
                "type": "GET",
                "url": "{{ url('getRoles') }}",
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
                    render: getMainIcon
                },
                {
                    "data": "name",
                    "class": "text-left"
                },
                {
                    "data": "description",
                },
                {
                    "data": "users_count",
                },
                {
                    "data": null,
                    "render": function(data) {
                        if (data.created_at)
                            return formatDate(data.created_at)
                    }
                },
                {
                    "data": null,
                    "render": function(data) {
                        if (data.updated_at)
                            return formatDate(data.updated_at)
                    }
                },
                {
                    "data": null,
                    render: getMenue
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
                if ($("#rolesTable_wrapper .dataTables_filter label").find('.search-icon').length ==
                    0)
                    $('.dataTables_filter label').append(icon);
                $('#rolesTable_wrapper .dataTables_filter input').addClass(
                    'form_input form-control');
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
                'targets': [0, 6], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }]
        });
        $('#rolesTable').DataTable().buttons().container()
            .prependTo('#rolesTable_filter');

    });

    function getCapacityBytes(data, type, full, meta) {
        return Math.round((data / 1024) * 100) / 100;
    }

    function getFreeSpaceBytes(data, type, full, meta) {

        return Math.round((data / 1099511627776) * 100) / 100;
    }

    function getMenue(data, type, full, meta) {
        if (data.organization_id) {
            var res = "";
            @if ($role->hasPermissionTo('roles_edit'))
            res += '<a href="/role/edit/' + (data.id) +
                '" title="Edit Role"><img class="tableIcone " src="/svg/edit.svg"></a>';
            @endif
            @if ($role->hasPermissionTo('roles_delete'))
            res += '<a onclick="deleteRole(\'' + data.id +
                '\')" title="Delete Role"><img class="tableIcone " src="/svg/Delete.svg"></a>';
            @endif
            return res;
        }
    }

    function getMainIcon() {
        return '<img class= "tableIcone"  style="margin-left:0px;width: 13px; margin-right:0;" src="/svg/r.svg " title="User">';
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
