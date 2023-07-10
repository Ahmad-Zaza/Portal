@extends('layouts.register-head')

@section('content')
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
                                                    <li><a href="#0" class="ui-link disabled-step" style="left: 66.666%;">
                                                            <p class="nowrap">Tenant Provisioning</p><span></span>
                                                        </a></li>
                                                    <li><a href="#0" class="ui-link disabled-step" style="left: 100%;">
                                                            <p class="nowrap">Connecting to Microsoft 365</p>
                                                            <span></span>
                                                        </a></li>
                                                </ol>
                                                <span class="filling-line" aria-hidden="true"
                                                    style="transform: scaleX(0);"></span>
                                            </div>
                                        </div>
                                    </div> <!-- .timeline -->
                                </div>

                            </div>
                            <div class="parent-form2 col-sm-10">

                                <h2 class="corner-border">&nbsp</h2>
                                <form class="form-horizontal" id="registrationForm" name="registrationForm" method="POST"
                                    action="{{ url('step1') }}" autocomplete="off">
                                    @csrf

                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="input-form-70">
                                                <div class="form-group ">
                                                    <input type="text" class="form-control" id="compnayname"
                                                        placeholder="Compnay Name" name="company_name" disabled
                                                        autocomplete="off" value="{{ $user->organization->company_name }}">
                                                    @error('company_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>


                                            <div class="input-form-70">
                                                <div class="form-group ">
                                                    <input type="text" class="form-control" id="FirstName"
                                                        placeholder="First Name" name="FirstName" disabled required
                                                        autocomplete="off" value="{{ $user->first_name }}" />
                                                    @error('FirstName')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>


                                            <div class="input-form-70">
                                                <div class="form-group ">
                                                    <input type="text" class="form-control" id="LastName"
                                                        placeholder="LastName" name="Last Name" required
                                                        {{ $user->last_name ? 'disabled' : '' }} autocomplete="off"
                                                        value="{{ $user->last_name }}" />
                                                    @error('LastName')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="input-form-70">
                                                <div class="form-group ">
                                                    <input type="text" class="form-control numm" id="Phone"
                                                        placeholder="Phone" name="Phone" required autocomplete="off"
                                                        value="{{ $user->phone }}" />
                                                    @error('Phone')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="input-form-70">
                                                <div class="form-group ">
                                                    <input type="email" class="form-control" id="email"
                                                        placeholder="Email" value="{{ $user->email }}" name="email"
                                                        {{ $user->email ? 'disabled' : '' }} required autocomplete="off">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="input-form-70">
                                                <div class="form-group ">
                                                    <label for="">Subscription Type:</label>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="subscription_type"
                                                                class="subscrTypeRadio" id="vCodeRadio" required value="1"
                                                                {{ old('subscription_type') && old('subscription_type') == '1' ? 'checked="checked"' : '' }}
                                                                checked>Verification Code
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" value="0" name="subscription_type"
                                                                class="subscrTypeRadio" id="trialRadio"
                                                                {{ ((old('subscription_type') && old('subscription_type') == '0') || $user->organization->marketplace_subscription_guid) ? 'checked="checked"' : '' }}>Trial
                                                            <span class="checkmark"></span>

                                                        </label>
                                                    </div>


                                                    @error('subscription_type')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="vCodeChild animated infinite slideInDown">
                                                <div class="input-form-70">
                                                    <div class="form-group ">
                                                        <input type="text" class="form-control" id="vcode"
                                                            placeholder="Verification Code" value="{{ old('code') }}"
                                                            name="code" autocomplete="off">
                                                        @error('code')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>


                                                </div>

                                            </div>
                                            <div class="input-form-70">
                                                <div class="form-group ">
                                                    <button type="submit"
                                                        class="btn contact-button sign-up-btn orng-btn">Next</button>
                                                </div>
                                            </div>

                                            <!-- </div> -->
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
                                        <img src="{{ url('img/Veeam_logo.png') }}" alt="Veeam_logo" class="img-responsive">
                                    </a>
                                </div>
                                <div class="hostedby col-sm-6 col-xs-6 flex align-items-center">
                                    <span>Hosted on</span>
                                    <a href="https://azure.microsoft.com/en-us/" target="_blank">
                                        <img src="{{ url('img/azure_logo.png') }}" alt="Veeam_logo" class="img-responsive">
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


    <script>
        function setInputFilter(textbox, inputFilter) {
            ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
                textbox.addEventListener(event, function() {
                    if (inputFilter(this.value)) {
                        this.oldValue = this.value;
                        this.oldSelectionStart = this.selectionStart;
                        this.oldSelectionEnd = this.selectionEnd;
                    } else if (this.hasOwnProperty("oldValue")) {
                        this.value = this.oldValue;
                        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                    } else {
                        this.value = "";
                    }
                });
            });
        }

        setInputFilter(document.getElementById("Phone"), function(value) {
            return /^\d*$/.test(value);
        });
    </script>
@endsection
