@extends('layouts.register-head')

@section('content')
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

        .loading-image.show {
            display: block !important;
            position: absolute;
            right: 10px;
            top: 7px;
        }
    </style>
    <div class="home-page">
        @include('auth.register-nav')

        <section class="global-section registration-section col-sm-12" style="background-image: url('img/steps-bg.png');">
            <div class="container">
                <div class="row">
                    <div class="global-section-details">

                        <div class="col-sm-3 col-md-3 hidden-xs hidden-sm"></div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="cd-horizontal-timeline loaded col-sm-12">
                                <div class="row">
                                    <div class="timeline">
                                        <div class="events-wrapper">
                                            <div class="events">
                                                <ol>
                                                    <li><a href="#0" class="ui-link " style="left: 0;">
                                                            <p>EULA</p> <span></span>
                                                        </a></li>
                                                    <li><a href="#" class="ui-link " style="left: 33.333%;">
                                                            <p>Registration</p> <span></span>
                                                        </a></li>
                                                    <li><a href="#0" class="ui-link " style="left: 66.666%;">
                                                            <p class="nowrap">Tenant Provisioning</p><span></span>
                                                        </a></li>
                                                    <li><a href="#0" class="ui-link " style="left: 100%;">
                                                            <p class="nowrap">Connecting to Microsoft 365</p> <span></span>
                                                        </a></li>
                                                </ol>
                                                <span class="filling-line" aria-hidden="true"
                                                    style="transform: scaleX(0);"></span>
                                            </div>
                                        </div>
                                    </div> <!-- .timeline -->
                                </div>

                            </div>
                            <div class="parent-form5 col-sm-10">

                                <h2 class="corner-border">&nbsp</h2>
                                <form class="form-horizontal" id="authenticationForm" name="authenticationForm"
                                    method="POST" action="{{ route('saveStep3') }}" autocomplete="on">
                                    @csrf
                                    <div class="col-sm-12">
                                        <div class="row">

                                            @if (\Session::has('errors_api_check'))
                                                <div class=" alert-danger">
                                                    <ul>
                                                        <li>{!! \Session::get('errors_api_check') !!}</li>
                                                    </ul>
                                                </div>
                                            @endif
                                            <div class="input-form-70">
                                                <div class="row">
                                                    <p>
                                                        Bactopus requires an Azure AD Application to Backup and
                                                        Restore data from/to your Microsoft 365 Organization.
                                                        <br>
                                                        <br>
                                                        Please complete the following steps so we can create the application
                                                        in your organization with the needed permissions. <a
                                                            class="txt-blue"
                                                            href="https://helpcenter.veeam.com/archive/vbo365/50/guide/ad_app_permissions_sd.html"
                                                            target="_blank"> What are the created permissions?</a>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="input-form-70">
                                                <img src="{{ url('img/arr_down.png') }}" alt="arr_down"
                                                    class="img-responsive arr-down" />
                                                <label for="step" class="lbl-step">Step 1:</label>
                                                <div class="form-group link-box">
                                                    <p class="flex">
                                                        Copy the below code.
                                                    </p>
                                                    <div class="mb-0 mt-2 allWidth flex relative">
                                                        <input type="text"
                                                            class="form-control form_input custom-form-control font-size w-80 deviceCode"
                                                            placeholder="" name="deviceCode" readonly required
                                                            autocomplete="off" />
                                                        <div class="copy-alert-div">
                                                            <span class="copy-alert-text">Copied!</span>
                                                        </div>
                                                        <span class="fa fa-refresh txt-blue absolute hand refreshDeviceCode"
                                                            style="right:88px;top:30%"></span>
                                                        <span
                                                            class="fa fa-refresh fa-spin refreshingDeviceCode absolute txt-blue hide"
                                                            style="right:88px;top:30%"></span>
                                                        <a type="button"
                                                            class="hand nowrap align-self-center copyText mb-0"><span
                                                                class="fa fa-copy txt-blue ml-2 mr-2"></span>Copy Code</a>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="input-form-70">
                                                <img src="{{ url('img/arr_down.png') }}" alt="arr_down"
                                                    class="img-responsive arr-down" />
                                                <label for="step" class="lbl-step">Step 2:</label>
                                                <div class="form-group link-box relative">
                                                    <p>
                                                        <span class="loading-image">
                                                            <img src="{{ url('img/loading.gif') }}" alt="loading-image"
                                                                class="img-responsive" style="width: 20px;" />
                                                        </span>
                                                        <a class="nowrap mr-0 d-inline hand" type="button"
                                                            onclick="openDeviceCodeWindow()">Click
                                                            Here
                                                        </a>
                                                        to paste the above code to authenticate with your
                                                        Microsoft 365 Organization. The provided account must have Global Administrator Role on the same Organization.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="input-form-70">
                                                <img src="{{ url('img/arr_down.png') }}" alt="arr_down"
                                                    class="img-responsive arr-down" />
                                                <label for="step" class="lbl-step">Step 3:</label>

                                                <div class="form-group">
                                                    <p>
                                                        Click Next to complete the step. <br> <span class="txt-bold">(Make sure you are authenticated before clicking Next)</span>.
                                                    </p>
                                                    <button type="submit" disabled="disabled"
                                                        class="btn contact-button sign-up-btn orng-btn">Next</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <h2 class=bottom-corner>&nbsp</h2>
                            </div>
                        </div>
                        <div class="col-sm-3 col-md-3 hidden-xs hidden-sm"></div>
                    </div>
                    <div class="global-footer">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6">
                            <div class="row footer">
                                <div class="poweredby col-sm-6 col-xs-6  flex align-items-center justify-content-end">
                                    <span>Powered by</span>
                                    <a href="https://www.veeam.com/" target="_blank">
                                        <img src="{{ url('img/Veeam_logo.png') }}" alt="Veeam_logo"
                                            class="img-responsive">
                                    </a>
                                </div>
                                <div class="hostedby col-sm-6 col-xs-6 flex align-items-center">
                                    <span>Hosted on</span>
                                    <a href="https://azure.microsoft.com/en-us/" target="_blank">
                                        <img src="{{ url('img/azure_logo.png') }}" alt="Veeam_logo"
                                            class="img-responsive">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('bottom')
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
            }).click();
            $(".copyText").click(function() {
                let parent = $(this).closest("form").prop("id");
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
                $(".loading-image").addClass("show");
                if (deviceWindow.closed) {
                    $(".loading-image").removeClass("show");
                    clearInterval(refreshIntervalId);
                    $('[type="submit"]').removeAttr("disabled");
                    $(".deviceCode").removeClass("need-verify");
                    $(".device-code-verify-alert").addClass("hide");
                }
            }, 1000);
        }
        //-----------------------------------//
    </script>
@endpush
