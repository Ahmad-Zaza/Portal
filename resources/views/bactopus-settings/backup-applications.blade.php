@push('styles')
    <style>
        .checkbox-container .check-mark:after {
            left: 3px !important;
            top: -6px !important;
            width: 7px !important;
            height: 13px !important;
        }
    </style>
    <style>
        .arr-down {
            left: -20px;
        }

        .hand {
            cursor: pointer;
        }

        @keyframes move {
            50% {
                transform: translate(10px, 0);
            }
        }

        .copy-alert-div {
            display: none;
            padding: 0.5rem;
            border-radius: 5px;
            box-shadow: rgba(0, 0, 0, 0.5) 2px 2px 10px;
            background-color: #2b3135;
            border: 1px solid #9e9e9e;
            transform: translateZ(0);
            animation: move .4s ease 3;
            position: absolute;
            top: 9%;
            left: 35%;
            z-index: 55;
        }

        .copy-alert-div:before {
            content: "";
            position: absolute;
            top: 5px;
            left: -9px;
            border-style: solid;
            border-width: 9px 9px 9px 0;
            border-color: transparent #9e9e9e;
            display: block;
            width: 0;
            z-index: 1;
        }

        .copy-alert-div:after {
            content: "";
            position: absolute;
            top: 7px;
            left: -7px;
            border-style: solid;
            border-width: 7px 7px 7px 0;
            border-color: transparent #2b3135;
            display: block;
            width: 0;
            z-index: 1;
        }

        .application_name,
        .deviceCode {
            width: 300px;
        }

        button[disabled='disabled'] {
            background: #6E5243;
            color: #737779;
        }

        .lbl-step {
            font-weight: 700;
        }

        .loading-image.show {
            display: block !important;
            position: absolute;
            right: 10px;
            top: 7px;
        }

        .copy-alert-text {
            color: white;
        }
    </style>
@endpush
<div class="row azure-custom-info ml-25 mr-4 mb-20">
    <div class="col-lg-11">
        <div class="row pl-0">
            <h5 class="txt-blue">Description</h5>
        </div>
        <div class="row newInfoRow mb-0">
            <div class="rowBorderRight"></div>
            <div class="rowBorderBottom"></div>
            <div class="rowBorderleft"></div>
            <div class="rowBorderUp"></div>
            <p class="fn-13">Add Auxiliary AAD Applications to process Sharepoint Online and OneDrive for Business
                Data faster. Using
                multiple Applications will increase Backup Performance and Avoid Throttling From Microsoft side.</p>
        </div>
    </div>
</div>
<!-- Backup Account table -->

<div class="row mt-2 ml-10p mr-80">
    <div class="verCodeTable w-893">
        <div class="row mb-4">
            <div class="col-sm-6">
                <h5 class="txt-blue">Add and Select Azure Applications to Authenticate with Microsoft 365</h5>
            </div>
            <div class="col-sm-6">
                <div class="ml-auto flex justify-content-end">
                    <button type="button" id="activateApplications"
                        class="hide btn_primary_state mr-4">Activate\Deactivate Applications</button>
                    <button type="button" id="addApplications" class="btn_primary_state">Add Backup
                        Applications</button>
                </div>

            </div>
        </div>
        <table id="backupAcountTable" class="stripe table table-striped table-dark w-100">
            <thead class="table-th">
                <th class="after-none"></th>
                <th class="text-left pl-35">Application Name</th>
                <th>Application ID</th>
                <th>Activated</th>
            </thead>
            <tbody class="repo-table-padding" id="table-content">

            </tbody>
        </table>
    </div>
</div>
<div id="createApplicationModal" class="modal modal-center" role="dialog">
    <div class="modal-dialog modal-lg ">
        <div class="divBorderRight"></div>
        <div class="divBorderBottom"></div>
        <div class="divBorderleft"></div>
        <div class="divBorderUp"></div>

        <!-- Modal Content -->
        <div class="modal-content">
            <div id="pass_modal_id" class="modalContent">
                <div class="row">&nbsp;</div>
                <div class="row">&nbsp;</div>
                <div class="row mb-15">
                    <div class="input-form-70">
                        <h4 class="per-req">Create Backup Applications</h4>
                    </div>
                </div>
                <form id="createApplicationForm" onsubmit="saveApplicationForm(event)">
                    @csrf
                    <div class="row">
                        <div class="input-form-70">
                            <img src="{{ url('img/arr_down.png') }}" alt="arr_down" class="img-responsive arr-down" />
                            <label for="step" class="lbl-step ml-0">Step 1:</label>
                            <div class="form-group link-box mb-1">
                                <p class="flex">
                                    Copy the below code.
                                </p>
                                <div class="mb-0 mt-2 allWidth flex relative">
                                    <input type="text"
                                        class="form-control form_input custom-form-control font-size w-80 deviceCode"
                                        placeholder="" name="deviceCode" readonly required autocomplete="off" />
                                    <div class="copy-alert-div">
                                        <span class="copy-alert-text">Copied!</span>
                                    </div>
                                    <span class="fa fa-refresh txt-blue absolute hand refreshDeviceCode"
                                        style="right:98px;top:30%"></span>
                                    <span class="fa fa-refresh fa-spin refreshingDeviceCode absolute txt-blue hide"
                                        style="right:98px;top:30%"></span>
                                    <a type="button" class="hand nowrap align-self-center copyText mb-0"><span
                                            class="fa fa-copy txt-blue ml-2 mr-2"></span>Copy Code</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-form-70">
                            <img src="{{ url('img/arr_down.png') }}" alt="arr_down" class="img-responsive arr-down" />
                            <label for="step" class="lbl-step ml-0">Step 2:</label>
                            <div class="form-group link-box mb-1 relative">
                                <p>
                                    <span class="loading-image">
                                        <img src="{{ url('img/loading.gif') }}" alt="loading-image"
                                            class="img-responsive" style="width: 20px;" />
                                    </span>
                                    <a class="nowrap mr-0 d-inline hand" type="button"
                                        onclick="openBackupApplicationsDeviceCodeWindow()">Click
                                        here
                                    </a>
                                    to paste the above code to authenticate with your
                                    Microsoft 365 Organization. The provided account must have Global Administrator Role on the same Organization.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70">
                            <img src="{{ url('img/arr_down.png') }}" alt="arr_down" class="img-responsive arr-down" />
                            <label for="step" class="lbl-step ml-0">Step 3:</label>
                            <div class="form-group link-box mb-1">
                                <p class="flex">
                                    How many Applications you want to create?
                                </p>
                                <input type="number" min="1" max="10"
                                    class="form_input form-control mt-2" name="applicationsCount"
                                    placeholder="Applications Count" />
                                <p class="mb-0 mt-2">Make sure you are authenticated before clicking Create</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-form-70 inline-flex">
                            <button type="submit" class="btn_primary_state halfWidth mr-25"
                                disabled="disabled">Create</button>
                            <button type="button" class="btn_cancel_primary_state halfWidth"
                                data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>

                <div class="row">&nbsp;</div>
                <div class="row">&nbsp;</div>
            </div>
        </div>
    </div>
</div>
<div id="activateApplicationsModal" class="modal modal-center" role="dialog">
    <div class="modal-dialog modal-lg ">
        <div class="divBorderRight"></div>
        <div class="divBorderBottom"></div>
        <div class="divBorderleft"></div>
        <div class="divBorderUp"></div>

        <!-- Modal Content -->
        <div class="modal-content">
            <div id="pass_modal_id" class="modalContent">
                <div class="row">&nbsp;</div>
                <div class="row">&nbsp;</div>
                <div class="row mb-15">
                    <div class="input-form-70">
                        <h4 class="per-req">Configure Backup Applications</h4>
                    </div>
                </div>
                <form id="updateApplicationsForm" onsubmit="onUpdateFormSubmit(event)">
                    @csrf
                    <div class="row">
                        <div class="input-form-70">
                            <img src="{{ url('img/arr_down.png') }}" alt="arr_down"
                                class="img-responsive arr-down" />
                            <label for="step" class="lbl-step ml-0">Step 1:</label>
                            <div class="form-group link-box mb-1">
                                <p class="flex">
                                    Copy the below code.
                                </p>
                                <div class="mb-0 mt-2 allWidth flex relative">
                                    <input type="text"
                                        class="form-control form_input custom-form-control font-size w-80 deviceCode"
                                        placeholder="" name="deviceCode" readonly required autocomplete="off" />
                                    <div class="copy-alert-div">
                                        <span class="copy-alert-text">Copied!</span>
                                    </div>
                                    <span class="fa fa-refresh txt-blue absolute hand refreshDeviceCode"
                                        style="right:98px;top:30%"></span>
                                    <span class="fa fa-refresh fa-spin refreshingDeviceCode absolute txt-blue hide"
                                        style="right:98px;top:30%"></span>
                                    <a type="button" class="hand nowrap align-self-center copyText mb-0"><span
                                            class="fa fa-copy txt-blue ml-2 mr-2"></span>Copy Code</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70">
                            <img src="{{ url('img/arr_down.png') }}" alt="arr_down"
                                class="img-responsive arr-down" />
                            <label for="step" class="lbl-step ml-0">Step 2:</label>
                            <div class="form-group link-box mb-1 relative">
                                <p>
                                    <span class="loading-image">
                                        <img src="{{ url('img/loading.gif') }}" alt="loading-image"
                                            class="img-responsive" style="width: 20px;" />
                                    </span>
                                    <a class="nowrap mr-0 d-inline hand" type="button"
                                        onclick="openUpdateApplicationsDeviceCodeWindow()">Click
                                        here
                                    </a>
                                    to paste the above code to authenticate with your
                                    Microsoft 365 Organization. The provided account must have Global Administrator Role on the same Organization.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70 inline-flex">
                            <button type="submit" id="activeApplication" class="btn_primary_state halfWidth mr-4"
                                onclick="submitActivate(event)" disabled="disabled">Activate</button>
                            <button type="submit" id="deactiveApplication" class="btn_primary_state halfWidth mr-4"
                                onclick="submitDeactivate(event)" disabled="disabled">Deactivate</button>
                            <button type="button" class="btn_cancel_primary_state halfWidth"
                                data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>

                <div class="row">&nbsp;</div>
                <div class="row">&nbsp;</div>
            </div>
        </div>
    </div>
</div>

<script>
    //-------------------------------------------//
    let backupDeviceWindow = {};
    let activateDeviceWindow = {};
    let backupDeviceCodeClicked = false;
    let activateBackupDeviceCodeClicked = false;
    //-------------------------------------------//
    $(document).ready(function() {
        //-------------------------------------------//
        $('#backupAcountTable').DataTable({
            'ajax': {
                "type": "GET",
                "url": "{{ url('getBackupApplications') }}",
                "dataSrc": '',
                "dataType": "json",
                "beforeSend": function() {
                    $('#backupAcountTable > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="4" class="dataTables_empty processing_row"><span class="table-spinner"></span></td>' +
                        '</tr>'
                    );
                },
            },
            "order": [
                [1, 'asc']
            ],
            'columns': [{
                    "data": null,
                    "render": function(data) {
                        return '<label class="checkbox-padding-left checkbox-container">&nbsp;' +
                            '<input type="checkbox" class="form-check-input applicationCheck" data-applicationId="' +
                            data.applicationId + '" data-applicationName="' + data
                            .displayName + '">' +
                            '<span class="checkbox-span-class check-mark-white check-mark"></span>' +
                            '</label>';
                    },
                    "class": "after-none"
                },
                {
                    "data": "displayName",
                    "class": "text-left"
                },
                {
                    "data": "applicationId",
                },
                {
                    "data": null,
                    "render": function(data) {
                        if (data.is_active) {
                            return '<span data-id="' + data.applicationId +
                                '" class="fa fa-check text-success hand activeBackupApplication configureAccount"></span>';
                        } else
                            return '<span data-id="' + data.applicationId +
                                '" class="fa fa-close text-danger hand activeBackupApplication configureAccount"></span>';
                    }
                }
            ],
            dom: 'Bfrtip',
            buttons: [],
            "fnDrawCallback": function() {
                //----------------------------------//
                $("#backupAcountTable").find(".applicationCheck").change(function() {
                    let len = $("#backupAcountTable").find(".applicationCheck:checked")
                        .length;
                    $("#activateApplications").addClass("hide");
                    if (len > 0)
                        $("#activateApplications").removeClass("hide");
                });
                //----------------------------------//
                let len = $("#backupAcountTable").find(".applicationCheck:checked").length;
                if (len == 0)
                    $("#activateApplications").addClass("hide");
                //----------------------------------//
            },
            "scrollY": "300px",
            "scrollCollapse": true,
            "bInfo": false,
            "paging": false,
            "autoWidth": false,
            "processing": false,
            "searching": false,
            language: {
                'loadingRecords': '&nbsp;',
                'processing': '<div class="spinner"></div>'
            },
            'columnDefs': [{
                'targets': [0, 3], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }]
        });
        //-------------------------------------------//
        $("#addApplications").click(function() {
            $("#createApplicationModal").modal("show");
            $("#createApplicationModal .refreshDeviceCode").click();
        });
        //-------------------------------------------//
        $("#activateApplications").click(function() {
            let selectedItems = $("#backupAcountTable").find(".applicationCheck:checked");
            let data = [];
            selectedItems.each(function() {
                data.push({
                    applicationId: $(this).attr("data-applicationId"),
                    applicationName: $(this).attr("data-applicationName"),
                });
            });
            $("#activateApplicationsModal").modal("show");
            $("#activateApplicationsModal .refreshDeviceCode").click();
        });
        //-------------------------------------------//
    });
    //-------------------------------------------//
    $(function() {
        $(".refreshDeviceCode").click(function() {
            let parent = $(this).closest("form").prop("id");
            generateDeviceCode(parent);
        });
        $(".copyText").click(function() {
            let parent = $(this).closest("form").prop("id");
            console.log("parent= " + parent);
            let value = $("#" + parent).find(".deviceCode").val();
            if (value) {
                copyToClipboard(value);
                $("#" + parent).find(".copy-alert-div").fadeIn("fast");
                setTimeout(function() {
                    $("#" + parent).find(".copy-alert-div").fadeOut("fast");
                }, 1500);
            }
        });
    });
    //-------------------------------------------//
    function generateDeviceCode(parent) {
        $("#" + parent).find(".deviceCode").val("");
        $("#" + parent).find("[type='submit']").attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "{{ url('step3/generateDeviceCode') }}",
            data: {
                _token: "{{ csrf_token() }}",
            },
            beforeSend: function() {
                $("#" + parent).find(".refreshDeviceCode").addClass("hide");
                $("#" + parent).find(".refreshingDeviceCode").removeClass("hide");
            },
            success: function(res) {
                //-------------------------------------//
                $("#" + parent).find(".refreshDeviceCode").removeClass("hide");
                $("#" + parent).find(".refreshingDeviceCode").addClass("hide");
                //-------------------------------------//
                if (res.userCode) {
                    $("#" + parent).find(".deviceCode").val(res.userCode);
                }
                //-------------------------------------//
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
                $(".spinner_parent").css("display", "none");
                $('body').removeClass('removeScroll');
                $("#" + parent).find(".refreshDeviceCode").removeClass("hide");
                $("#" + parent).find(".refreshingDeviceCode").addClass("hide");
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
    //-------------------------------------------//
    function copyToClipboard(text) {
        var sampleTextarea = document.createElement("textarea");
        document.body.appendChild(sampleTextarea);
        sampleTextarea.value = text; //save main text in it
        sampleTextarea.select(); //select textarea contenrs
        document.execCommand("copy");
        document.body.removeChild(sampleTextarea);
    }
    //-------------------------------------------//
    function saveApplicationForm(event) {
        event.preventDefault();
        if (!backupDeviceCodeClicked || !backupDeviceWindow || !backupDeviceWindow.closed) {
            showErrorMessage("{{ __('variables.errors.generating_device_not_clicked') }}");
            return;
        }
        let data = $("#createApplicationForm").serialize();
        $(".spinner_parent").css("display", "block");
        $('body').addClass('removeScroll');
        $.ajax({
            type: "POST",
            url: "{{ url('saveBackupApplication') }}",
            data: data,
            success: function(res) {
                //-------------------------------------//
                $(".spinner_parent").css("display", "none");
                $('body').removeClass('removeScroll');
                //-------------------------------------//
                $("#createApplicationModal").modal("hide");
                $("#backupAcountTable").DataTable().ajax.reload();
                //-------------------------------------//
                $("#createApplicationModal").find('[type="submit"]').attr("disabled", "disabled");
                //-------------------------------------//
                backupDeviceWindow = {};
                backupDeviceCodeClicked = false;
                $("#createApplicationModal").find(".deviceCode").val("");
                //-------------------------------------//
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
                //-----------------------------------//
                backupDeviceWindow = {};
                backupDeviceCodeClicked = false;
                $("#createApplicationModal").find(".deviceCode").val("");
                $("#createApplicationModal").find("[type='submit']").attr("disabled", "disabled");
                //-----------------------------------//
                $(".spinner_parent").css("display", "none");
                $('body').removeClass('removeScroll');
                $("#createApplicationModal").find(".refreshDeviceCode").removeClass("hide");
                $("#createApplicationModal").find(".refreshingDeviceCode").addClass("hide");
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
    //-------------------------------------------//
    function updateApplication(event, type) {
        event.preventDefault();
        if (!activateBackupDeviceCodeClicked || !activateDeviceWindow || !activateDeviceWindow.closed) {
            showErrorMessage("{{ __('variables.errors.generating_device_not_clicked') }}");
            return;
        }
        //-----------------------------------------------------//
        let applicationsArr = [];
        let selectedApplications = $("#backupAcountTable").find(".applicationCheck:checked");
        selectedApplications.each(function() {
            applicationsArr.push({
                applicationId: $(this).attr("data-applicationId"),
                applicationName: $(this).attr("data-applicationName"),
            });
        });
        //-----------------------------------------------------//
        $(".spinner_parent").css("display", "block");
        $('body').addClass('removeScroll');
        $.ajax({
            type: "POST",
            url: "{{ url('activateBackupApplication') }}",
            data: {
                _token: "{{ csrf_token() }}",
                deviceCode: $("#updateApplicationsForm").find(".deviceCode").val(),
                applications: applicationsArr,
                type: type
            },
            success: function(res) {
                //-------------------------------------//
                $(".spinner_parent").css("display", "none");
                $('body').removeClass('removeScroll');
                //-------------------------------------//
                activateDeviceWindow = {};
                activateBackupDeviceCodeClicked = false;
                $("#activateApplicationsModal").find(".deviceCode").val("");
                //-------------------------------------//
                $("#activateApplicationsModal").modal("hide");
                $("#backupAcountTable").DataTable().ajax.reload();
                //-------------------------------------//
                $("#activateApplicationsModal").find('[type="submit"]').attr("disabled", "disabled");
                //-------------------------------------//
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
                //----------------------------------------------------------//
                // activateDeviceWindow = {};
                // activateBackupDeviceCodeClicked = false;
                // $("#activateApplicationsModal").find(".deviceCode").val("");
                //----------------------------------------------------------//
                $(".spinner_parent").css("display", "none");
                $('body').removeClass('removeScroll');
                $("#activateApplicationsModal").find(".refreshDeviceCode").removeClass("hide");
                $("#activateApplicationsModal").find(".refreshingDeviceCode").addClass("hide");
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
    //-------------------------------------------//
    function showErrorMessage(message) {
        $(".danger-oper .danger-msg").html(message);
        $(".danger-oper").css("display", "block");
        setTimeout(function() {
            $(".danger-oper").css("display", "none");
        }, 8000);
    }
    //---------------------------------------------------//
    function showSuccessMessage(message) {
        $(".success-oper .success-msg").html(message);
        $(".success-oper").css("display", "block");
        setTimeout(function() {
            $(".success-oper").css("display", "none");
        }, 8000);
    }
    //-------------------------------------------//
    function openBackupApplicationsDeviceCodeWindow() {
        let refreshIntervalId = setInterval(() => {
            $("#createApplicationModal .loading-image").addClass("show");
            if (backupDeviceWindow.closed) {
                $("#createApplicationModal .loading-image").removeClass("show");
                clearInterval(refreshIntervalId);
                $('#createApplicationModal [type="submit"]').removeAttr("disabled");
            }
        }, 1000);
        let popupWinWidth = 400;
        let popupWinHeight = 600;
        var left = (screen.width - popupWinWidth) / 2;
        var top = (screen.height - popupWinHeight) / 4;

        backupDeviceWindow = window.open("https://microsoft.com/devicelogin", 'deviceCodeWindow',
            'top=' + top + ',left=' + left +
            ',toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=' + popupWinWidth +
            ',height=' + popupWinHeight
        );
        if (!backupDeviceWindow) {
            //TODO
            // The window wasn't allowed to open
            // This is likely caused by built-in popup blockers.

        }
        backupDeviceCodeClicked = true;
        return false;
    }
    //-------------------------------------------//
    function openUpdateApplicationsDeviceCodeWindow() {
        let refreshIntervalId = setInterval(() => {
            $("#activateApplicationsModal .loading-image").addClass("show");
            if (activateDeviceWindow.closed) {
                $("#activateApplicationsModal .loading-image").removeClass("show");
                clearInterval(refreshIntervalId);
                $('#activateApplicationsModal [type="submit"]').removeAttr("disabled");
            }
        }, 1000);
        let popupWinWidth = 400;
        let popupWinHeight = 600;
        var left = (screen.width - popupWinWidth) / 2;
        var top = (screen.height - popupWinHeight) / 4;

        activateDeviceWindow = window.open("https://microsoft.com/devicelogin", 'deviceCodeWindow',
            'top=' + top + ',left=' + left +
            ',toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=' + popupWinWidth +
            ',height=' + popupWinHeight
        );
        if (!activateDeviceWindow) {
            //TODO
            // The window wasn't allowed to open
            // This is likely caused by built-in popup blockers.

        }
        activateBackupDeviceCodeClicked = true;
        return false;
    }
    //-------------------------------------------//
    function onUpdateFormSubmit(event) {
        event.preventDefault();
    }
    //-------------------------------------------//
    function submitActivate(event) {
        updateApplication(event, "A");
    }
    //-------------------------------------------//
    function submitDeactivate(event) {
        updateApplication(event, "D");
    }
    //-------------------------------------------//
</script>
