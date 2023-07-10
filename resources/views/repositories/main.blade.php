@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/main.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/repositories.css') }}" />
<link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/generalElement.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('/css/restore-customize.css') }}" />
@section('topnav')
    <style>
        .tooltip-inner {
            background-color: black;
            color: white
        }

    </style>
    <div class="col-sm-10  navbarLayout">
        <!-- Upper navbar -->
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm upperNavBar"> -->
        <ul class="ulNavbar">

            <li class="liNavbar"><a class="active" href="/repositories">Repositories</a></li>
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
            <div class="col-lg-3 min-height-35" style="z-index: 5;">
                @if ($role->hasPermissionTo('add_repository'))
                    <button class="btn_primary_state left-float" data-toggle="modal" data-target="#createNewRepositoryModal">
                        Create New Repository</button>
                @endif
            </div>
            <div class="col-lg-9"></div>

        </div>
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <!-- Create repository Modal -->
        <div id="createNewRepositoryModal" class="modal" role="dialog">

            <div class="modal-dialog" style="margin: 10vh auto;">
                <div class="divBorderRight"></div>
                <div class="divBorderBottom"></div>
                <div class="divBorderleft"></div>
                <div class="divBorderUp"></div>
                <!-- Modal content-->
                <div class="modal-content ">
                    <form onsubmit="checkModal(event,'create')">
                        <div id="modalBody_id" class="modalContent">
                            <div class="row">&nbsp;</div>
                            <div class="row">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-5">
                                    {{-- <label class="lblText"><strong>New Repository</strong>
                                    <hr class="modalLine" />
                                </label> --}}
                                    <h4 class="per-req">New Repository</h4>
                                </div>
                                <div class="col-lg-6"></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="modal-body col-lg-8 nopadding-bottom">
                                    <input type="text" class="form_input form-control" id="modalRepoName"
                                        name="modalRepoName" placeHolder="Enter Storage Name" required>
                                </div>
                                <div class="col-lg-2"></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="modal-body col-lg-8 nopadding-bottom">

                                    <input type="password" class="form_input form-control" id="modalRepoEncryptionKey"
                                        name="modalRepoEncryptionKey" placeHolder="Enter Encryption Key" required>
                                        <p class="txt-blue col-lg-12 nopadding nowrap" data-placement="bottom"
                                        style="cursor: pointer;margin-bottom:2px" data-toggle="tooltip"
                                        title="Encryption Key is used to Encrypt the Backup Data at Rest (Storage Side Encryption)">WARNING: Encryption Key can't be restored. Don't Lose It.
                                    </p>


                                </div>
                                <div class="col-lg-2"></div>


                            </div>
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="modal-body col-lg-8">
                                    <select class="form_dropDown form-control" id="modalRepoKind" name="modalRepoKind"
                                        required>
                                        <option value="" disabled selected><label class="modalLabel">Please Select
                                                Type</label></option>
                                        <option value="exchange"><label class="modalLabel">Exchange Storage</label></option>
                                        <option value="onedrive"><label class="modalLabel">OneDrive Storage</label></option>
                                        <option value="sharepoint-teams"><label class="modalLabel">SharePoint &
                                                Teams Storage</label></option>
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
        <div class="row">&nbsp;</div>
        <!-- All repositories table -->
        <div class="row">
            <div class="repositoryTable">
                <table id="reposTable" class="stripe table table-striped table-dark" style="width:100%">
                    <thead class="table-th">
                        <th class="after-none"></th>
                        <th style="text-align: left;padding-left: 35px;">Repository Name </th>
                        <th>Type</th>
                        <th>Used Space(GB)</th>
                        <th>
                            Actions
                        </th>

                    </thead>
                    <tbody class="repo-table-padding" id="table-content">

                    </tbody>
                </table>
            </div>
        </div>


        <!------------------- delete modal ---------------------- -->
        <div id="deleteRepositoryModal" class="modal" role="dialog">

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

                            <div class="swal-title text-center">Delete Storage</div>
                            <div class="row">
                                <input type="hidden" name="deletedrep" id="deletedrep">
                                <div id="deleteTxt" class="modal-body basic-color text-center mt-22">
                                    Are You Sure ?
                                </div>
                            </div>

                            <div class="row mt-10">
                                <div class="input-form-70 inline-flex">
                                    <button type="button" class="btn_primary_state allWidth mr-25"
                                        onClick="makeDeleteRepository();">Delete</button>
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
        <!-- update repository modal -->
        <div id="updateRepositoryModal" class="modal" role="dialog">

            <div class="modal-dialog">
                <div class="divBorderRight"></div>
                <div class="divBorderBottom"></div>
                <div class="divBorderleft"></div>
                <div class="divBorderUp"></div>
                <input type="text" id="repoId_update" name="repoId_update" hidden>
                <form onsubmit="checkModal(event,'update')">
                    <!-- Modal content-->
                    <div class="modal-content ">

                        <div id="modalBody_id" class="modalContent">
                            <div class="row">&nbsp;</div>
                            <div class="row">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-5">
                                    <h4 class="per-req">Update Storage
                                    </h4>
                                </div>
                                <div class="col-lg-6"></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="modal-body col-lg-8 nopadding-bottom">
                                    <input type="text" class="form_input form-control" id="modalRepoName_update"
                                        name="modalRepoName_update" placeHolder="Enter Storage Name" required>
                                </div>
                                <div class="col-lg-2"></div>
                            </div>

                            <div class="row">&nbsp;</div>
                            <div class="row pb-10">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-8">
                                    <button type="submit" class="allWidth btn_primary_state">Save</button>
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
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="searchModal" class="modal" role="dialog">

        <div class="modal-dialog modal-lg" style="width: 500px; margin:10vh auto;">
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
                        <div class="input-form-70 mb-1">Size:</div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <input type="number" class="form_input form-control mr-25" id="sizeFrom" placeholder="From" />
                            <input type="number" class="form_input form-control" id="sizeTo" placeholder="To" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-form-70 mb-1">Type:</div>
                        <div class="input-form-70 mb-1" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;"
                                    class="checkbox-container checkbox-search">&nbsp;
                                    <input id="exchangeSearch" type="checkbox" class="form-check-input" />

                                    <span style="width: 15px !important; height: 15px !important;top:-7px!important;"
                                        class="check-mark"></span>
                                </label>
                                <label style="margin-left: 25px;">Exchange</label>
                            </div>
                            <div class="halfWidth" style="position: relative;">
                                <label style="padding-top: 5px;left: 0px;"
                                    class="checkbox-container checkbox-search">&nbsp;
                                    <input id="onedriveSearch" type="checkbox" class="form-check-input" />

                                    <span style="width: 15px !important; height: 15px !important;top:-7px!important;"
                                        class="check-mark"></span>
                                </label>
                                <label style="margin-left: 25px;">OneDrive</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;"
                                    class="checkbox-container checkbox-search">&nbsp;
                                    <input id="sharepointSearch" type="checkbox" class="form-check-input" />

                                    <span style="width: 15px !important; height: 15px !important;top:-7px!important;"
                                        class="check-mark"></span>
                                </label>
                                <label style="margin-left: 25px;">SharePoint</label>
                            </div>
                            <div style="position: relative;width:50%;">
                                <label style="padding-top: 5px;left: 0px;"
                                    class="checkbox-container checkbox-search">&nbsp;
                                    <input id="teamsSearch" type="checkbox" class="form-check-input" />

                                    <span style="width: 15px !important; height: 15px !important;top:-7px!important;"
                                        class="check-mark"></span>
                                </label>
                                <label style="margin-left: 25px;">Teams</label>
                            </div>
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

    <script>
        $(document).ready(function() {

            $('#reposTable').DataTable({
                'ajax': {
                    "type": "GET",
                    "url": "{{ url('getRepositoriesContent') }}",
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
                        "class": "col-lg-3 repos-left-col"
                    },
                    {
                        "data": "repo_kind",
                        render: function(str) {
                            if(str == "exchange")
                                return "Exchange";
                            if(str == "onedrive")
                                return "OneDrive";
                            if(str == "sharepoint-teams")
                                return "Sharepoint & Teams";
                        },
                        "class": "col-lg-3"
                    },
                    {
                        "data": "usedSpaceBytes",
                        "class": "col-lg-2",
                        render: function(data) {
                            if (data)
                                return (Math.round(data / 1024 / 1024) / 1024).toFixed(3) + " GB";
                            return '0 GB';
                        }
                    },
                    {
                        "data": null,
                        "class": "col-lg-3",
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
                    if ($(".dataTables_filter label").find('.search-icon').length == 0)
                        $('.dataTables_filter label').append(icon);
                    $('.dataTables_filter input').addClass('form_input form-control');
                },
                "scrollY": "300px",
                "scrollCollapse": true,
                "bInfo": false,
                "paging": false,
                "autoWidth": false,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                },
                'columnDefs': [{
                    'targets': [0, 4], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            $('#reposTable').DataTable().buttons().container()
                .prependTo('#reposTable_filter');

        });


        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                res = true;
                sizeFrom = $("#sizeFrom")[0].value;
                sizeTo = $("#sizeTo")[0].value;
                exchangeSearch = $("#exchangeSearch")[0].checked;
                onedriveSearch = $("#onedriveSearch")[0].checked;
                sharepointSearch = $("#sharepointSearch")[0].checked;
                teamsSearch = $("#teamsSearch")[0].checked;
                if (sizeFrom && data[3]) {
                    if (parseInt(sizeFrom) > parseInt(data[3])) {
                        res = false;
                    }
                }
                if (sizeTo && data[3]) {
                    if (parseInt(sizeTo) < parseInt(data[3])) {
                        res = false;
                    }
                }
                if (exchangeSearch == true || onedriveSearch == true || sharepointSearch == true || teamsSearch ==
                    true) {
                    if (exchangeSearch == false && data[2] == "Exchange")
                        res = false;
                    if (onedriveSearch == false && data[2] == "OneDrive")
                        res = false;
                    if (sharepointSearch == false && data[2] == "SharePoint")
                        res = false;
                    if (teamsSearch == false && data[2] == "Teams")
                        res = false;
                }
                return res;
            }
        );


        function resetSearch() {
            $("#sizeFrom").val("");
            $("#sizeTo").val("");
            $("#exchangeSearch").attr('checked', false);
            $("#onedriveSearch").attr('checked', false);
            $("#sharepointSearch").attr('checked', false);
            $("#teamsSearch").attr('checked', false);
            $('#reposTable').DataTable().draw();
        }

        function ApplySearch() {
            $('#reposTable').DataTable().draw();
        }

        function getCapacityBytes(data, type, full, meta) {
            return Math.round((data / 1024) * 100) / 100;
        }

        function getFreeSpaceBytes(data, type, full, meta) {

            return Math.round((data / 1099511627776) * 100) / 100;
        }

        function getMenue(data, type, full, meta) {
            @if ($role->hasPermissionTo('edit_repository'))
                var res =
                    '<a href="#"  data-toggle="modal" data-target="#updateRepositoryModal" onclick="getRepoUpdateData(\'' +
                    data
                    .id + '\' , \'' + data.name + '\', \'' + Math.round((data.capacityBytes / 1024) * 100) / 100 +
                    '\' )" > <img class= "tableIcone" style="margin-left:0px;" src="/svg/edit.svg " title="Edit "></a>' +
                    ' </ul>   </div>';
                return res;
            @endif
        }

        function getMainIcon() {
            return '<img class= "tableIcone"  style="margin-left:0px;width: 13px; margin-right:0;" src="/svg/r.svg " title="Repository">';
        }

        function confirmDelete(deletedRepId, deletedRepName, kind) {
            document.getElementById('deletedrep').value = deletedRepId;
            $('#deletedrep').attr('data-kind', kind);
            $("#deleteTxt").html("Delete Storage " + deletedRepName + " ?");
        }

        function checkModal(event, type) {
            event.preventDefault()

            switch (type) {
                case 'update':
                    let message = "";
                    let repoIdUpdate = document.getElementById('repoId_update').value;
                    let repoNameUpdate = document.getElementById("modalRepoName_update").value;
                    if (!repoIdUpdate) { //repo id and storage size not exist
                        showErrorMessage("Something went wrong, Please try again later.");
                    } else { //repo name and id not exist
                        if (!repoNameUpdate) { //repo name and size not exist
                            if (!repoNameUpdate) {
                                message += "Please Enter Storage Name";
                            } else {
                                message += "Please Enter Storage Size";
                            }
                            showErrorMessage(message);

                        } else { //all data is exist
                            makeUpdateRepository(repoIdUpdate, repoNameUpdate);
                        }
                    }
                    break;
                case 'create':
                    let repoName = document.getElementById("modalRepoName").value;
                    let encKey = document.getElementById('modalRepoEncryptionKey').value;
                    let repoKind = document.getElementById('modalRepoKind').value;
                    if (!repoName) {
                        showErrorMessage("Please enter repository name");
                    } else if (!encKey) {
                        showErrorMessage("Please enter encryption key");
                    } else if (!modalRepoKind || modalRepoKind.value == '') {
                        showErrorMessage("Please enter repository type");
                    } else makeCreateRepository(repoName, encKey, repoKind);
                    break;
            }
        }

        function clearModalData() {
            clearCreateModalData();
            clearUpdateModalData();
        }

        function clearCreateModalData() {
            document.getElementById('modalRepoName').value = '';
            document.getElementById('modalRepoEncryptionKey').value = '';
            document.getElementById('modalRepoKind').value = '';
        }

        function clearUpdateModalData() {
            document.getElementById('repoId_update').value = '';
            document.getElementById('modalRepoName_update').value = '';
        }


        function getRepoUpdateData(repoId, repoName) {

            document.getElementById('repoId_update').value = repoId;
            document.getElementById('modalRepoName_update').value = repoName;
        }


        function makeCreateRepository(repoName, encKey, repoKind) {
            $('#createNewRepositoryModal').modal('hide');
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "GET",
                url: "createRepository",
                data: {
                    repositoryName: repoName,
                    encryptionKey: encKey,
                    repositoryKind: repoKind
                },
                dataType: "text",
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    clearModalData();
                    getAllRepositories();
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


        function getAllRepositories() {
            $('#reposTable').DataTable().ajax.reload();
        }

        function makeUpdateRepository(repoId, repoName) {

            $('#updateRepositoryModal').modal('hide');
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "GET",
                url: "updateRepository",
                data: {
                    repositoryId: repoId,
                    repositoryName: repoName,
                },
                dataType: "text",
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    clearModalData();
                    getAllRepositories();
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

        function makeDeleteRepository() {
            $('#deleteRepositoryModal').modal('hide');
            let repoId = document.getElementById('deletedrep').value;
            let kind = $('#deletedrep').attr('data-kind');
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');

            $.ajax({
                type: "GET",
                url: "deleteRepository",
                data: {
                    kind: kind,
                    repoId: repoId
                },
                dataType: "text",
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');

                    getAllRepositories();

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

        function showErrorMessage(message) {
            $(".danger-oper .danger-msg").html(message);
            $(".danger-oper").css("display", "block");
            setTimeout(function() {
                $(".danger-oper").css("display", "none");

            }, 8000);
        }
        $(window).load(function() {
            clearModalData()
        });
    </script>
@endsection
