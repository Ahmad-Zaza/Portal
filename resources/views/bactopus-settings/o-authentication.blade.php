@push('styles')
    <style>
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
            <p class="fn-13">You can change the Authentication Method with Microsoft 365 here to allow Bactopus to
                access your Microsoft 365 Data.</p>
        </div>
    </div>
</div>
<form class="form-horizontal" id="authenticationForm" name="authenticationForm" method="POST"
    onsubmit="veeamAuth(event)" autocomplete="on">
    @csrf
    <div class="row azure-custom-info ml-25 mr-4 mb-20">
        <div class="col-lg-11">
            <div class="row newInfoRow mb-0">
                <div class="rowBorderRight"></div>
                <div class="rowBorderBottom"></div>
                <div class="rowBorderleft"></div>
                <div class="rowBorderUp"></div>
                {{-- <div class="col-sm-1"></div> --}}
                <div class="col-sm-10">
                    <div class="row">
                        <h4 class="per-req mt-0 pt-0" for="">Authentication Method with Microsoft 365</h4>
                        <br>
                        <div class="col-12">
                            <div class="row">
                                <p>
                                    A new Veeam Backup for Microsoft 365 application will be registered
                                    in the specified Microsoft 365 organization and granted with the
                                    required permissions.
                                    <a class="txt-blue"
                                        href="https://helpcenter.veeam.com/archive/vbo365/50/guide/ad_app_permissions_sd.html"
                                        target="_blank"> What are the needed Permissions?</a>
                                </p>
                            </div>
                        </div>

                        @if (\Session::has('errors_api_check'))
                            <div class=" alert-danger">
                                <ul>
                                    <li>{!! \Session::get('errors_api_check') !!}</li>
                                </ul>
                            </div>
                        @endif


                        <div class="col-12">
                            <div class="row">
                                <input type="text" class="form-control application_name" id="application_name"
                                    placeholder="Application Name" value="{{ old('application_name') }}"
                                    name="application_name" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12 pt-3">
                            <div class="row">
                                <p class="mb-0">
                                    Provided account must have permissions to authenticate to the
                                    Microsoft 365 organization.

                                    To sign in, authenticate with the below code at:
                                </p>
                                <a type="button" class="txt-blue hand pb-2"
                                    onclick="openDeviceCodeWindow()">https://microsoft.com/devicelogin</a>
                                <div class="mb-0 allWidth flex relative">
                                    <input type="text"
                                        class="form-control form_input custom-form-control font-size deviceCode"
                                        placeholder="" name="deviceCode" readonly required autocomplete="off" />
                                    <div class="copy-alert-div">
                                        <span class="copy-alert-text">Copied!</span>
                                    </div>
                                    <span class="fa fa-refresh txt-blue absolute hand refreshDeviceCode"
                                        style="left:277px;top:30%"></span>
                                    <span class="fa fa-refresh fa-spin refreshingDeviceCode absolute txt-blue hide"
                                        style="left:277px;top:30%"></span>
                                    <a type="button" class="hand nowrap align-self-center copyText mb-0"><span
                                            class="fa fa-copy txt-blue ml-2 mr-2"></span>Copy Code</a>
                                </div>
                                <div class="loading-image">
                                    <img src="{{ url('img/loading.gif') }}" alt="loading-image" class="img-responsive"
                                        style="width: 20px;" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn_primary_state custom-right-float-auth mr-0 mt-4"
                    disabled="disabled">Save</button>
            </div>
        </div>

    </div>
</form>
@push('scripts')
    <script>
        let deviceWindow = {};
        let deviceCodeClicked = false;
        $(function() {

            $("#authenticationForm").on("submit", function(e) {
                e.preventDefault();
                if (!deviceCodeClicked || !deviceWindow || !deviceWindow.closed) {
                    showErrorMessage("{{ __('variables.errors.generating_device_not_clicked') }}");
                    return;
                }
                let message = "You won't be able to update your entered data later";
                $(".swal-modal").show();
                swal({
                    title: "Do you want to send it anyway?",
                    text: ("{{ auth()->user()->organization->marketplace_subscription_guid }}" ?
                        "{{ __('variables.messages.marketplace_alert_pop_message') }}" :
                        message
                    ),
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $(this).unbind('submit').submit();
                    } else {
                        $(".swal-modal").hide();
                    }
                });
            });
        })
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
        //---------------------------------------------//
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
        //-----------------------------------//
        function copyToClipboard(text) {
            var sampleTextarea = document.createElement("textarea");
            document.body.appendChild(sampleTextarea);
            sampleTextarea.value = text; //save main text in it
            sampleTextarea.select(); //select textarea contenrs
            document.execCommand("copy");
            document.body.removeChild(sampleTextarea);
        }
        //-----------------------------------//
        function openDeviceCodeWindow() {
            checkDeviceCodeWindow();
            let popupWinWidth = 400;
            let popupWinHeight = 600;
            var left = (screen.width - popupWinWidth) / 2;
            var top = (screen.height - popupWinHeight) / 4;

            deviceWindow = window.open("https://microsoft.com/devicelogin", 'deviceCodeWindow',
                'top=' + top + ',left=' + left +
                ',toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=' + popupWinWidth +
                ',height=' + popupWinHeight
            );
            if (!deviceWindow) {
                //TODO
                // The window wasn't allowed to open
                // This is likely caused by built-in popup blockers.

            }
            deviceCodeClicked = true;
            return false;
        }
        //-----------------------------------//
        function showErrorMessage(message) {
            $(".danger-oper .danger-msg").html(message);
            $(".danger-oper").css("display", "block");
            setTimeout(function() {
                $(".danger-oper").css("display", "none");

            }, 8000);
        }
        //-----------------------------------//
        function checkDeviceCodeWindow() {
            refreshIntervalId = setInterval(() => {
                $("#authenticationForm .loading-image").css("display", "block");
                if (deviceWindow.closed) {
                    $("#authenticationForm .loading-image").css("display", "none");
                    clearInterval(refreshIntervalId);
                    $('#authenticationForm [type="submit"]').removeAttr("disabled");
                }
            }, 1000);
        }
        //-----------------------------------//
    </script>
@endpush
