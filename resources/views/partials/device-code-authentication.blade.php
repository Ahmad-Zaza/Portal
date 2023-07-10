@once
    @push('styles')
        <style>
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

            .deviceCode.need-verify {
                border-left: 2px solid #F65555 !important;
                color: #F65555 !important;
            }
            .device-code-verify-alert{
                margin-top: -3px;
            }
        </style>
    @endpush
@endonce
<div class="row">
    <div class="input-form-70 mb-1">
        <h5 class="txt-blue mt-0">Login to Microsoft 365 <img width="12px" class="device-code-verify-alert"
                src="{{ url('svg/modal-i-red.svg') }}" /></h5>
    </div>
    <div class="input-form-70">
        <div class="col-lg-12 customBorder pb-3 pt-3">
            <p data-toggle="tooltip" class="txt-blue mb-1"
                title="The provided account must have Exchange Application Impersonation Role and Exchange, SharePoint and Teams Administrator Role on Microsoft 365 Organization.">
                    What is the needed permissions?
            </p>
            <p class="mb-2">
                To authenticate, <a type="button" class="txt-blue" style="display:inline;"
                    onclick="openDeviceCodeWindow($(this))">Click Here</a> and paste the below code.
            </p>

            <div class="mb-0 allWidth flex relative">
                <input type="text" class="form-control form_input custom-form-control font-size w-80 deviceCode"
                    placeholder="" name="deviceCode" readonly required autocomplete="off" />
                <div class="copy-alert-div">
                    <span class="copy-alert-text">Copied!</span>
                </div>
                <input type="hidden" name="restoreSessionGuid" class="restoreSessionGuid">
                <span class="fa fa-refresh txt-blue absolute hand refreshDeviceCode" style="right:94px;top:26%"></span>
                <span class="fa fa-refresh fa-spin refreshingDeviceCode absolute txt-blue hide"
                    style="right:94px;top:26%"></span>
                <a type="button" class="nowrap align-self-center copyText">
                    <span class="fa fa-copy txt-blue ml-2 mr-2"></span>Copy Code</a>
            </div>
            <div class="loading-image">
                <img src="{{ url('img/loading.gif') }}" alt="loading-image" class="img-responsive"
                    style="width: 20px;" />
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            let deviceWindow = {};
            let deviceCodeClicked = false;
            $(function() {
                $(".refreshDeviceCode").click(function() {
                    let parent = $(this).closest("form").prop("id");
                    generateDeviceCode(parent);
                });
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
                $("#" + parent + " .loading-image").css("display", "none");
                $("#" + parent).find(".deviceCode").val("").addClass("need-verify");
                $("#" + parent).find(".device-code-verify-alert").removeClass("hide");
                $("#" + parent).find("[type='submit']").attr("disabled", "disabled");
                $.ajax({
                    type: "POST",
                    url: "{{ url('generate' . Illuminate\Support\Str::ucfirst($data['repo_kind']) . 'DeviceCode') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        jobType: jobType,
                        jobTime: jobTime,
                        showDeleted: showDeleted,
                        showVersions: showVersions,
                        jobId: jobId
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
                            $("#" + parent).find(".restoreSessionGuid").val(res.restoreSessionGuid);
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
            function openDeviceCodeWindow($this) {
                checkDeviceCodeWindow($this);
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
                return false;
            }
            //-----------------------------------//
            function checkDeviceCodeWindow($this) {
                let parent = $this.closest('form').prop("id");
                refreshIntervalId = setInterval(() => {
                    $("#" + parent + " .loading-image").css("display", "block");
                        //deviceWindow.location.href

                    if (deviceWindow.closed) {
                        $("#" + parent + " .loading-image").css("display", "none");
                        clearInterval(refreshIntervalId);
                        $('#' + parent + ' [type="submit"]').removeAttr("disabled");
                        $("#" + parent).find(".deviceCode").removeClass("need-verify");
                        $("#" + parent).find(".device-code-verify-alert").addClass("hide");
                    }
                }, 1000);
            }
            //-----------------------------------//
        </script>
    @endpush
@endonce
