@extends('layouts.register-head')

@section('content')
    <div class="home-page">


        <!-- Upper Bar -->
        @include('auth.register-nav')



        <!--  ====================================================================================================  -->

        <section class="global-section tenant-section col-sm-12" style="background-image: url('img/steps-bg.png');">
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
                                                    <li><a href="#0" class="ui-link disabled-step" style="left: 100%;">
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
                            <div class="col-sm-12">
                                <div class="parent-form3 col-sm-10 slider-step first-step">
                                    <h2 class="corner-border">&nbsp</h2>
                                    <form class="form-horizontal" id="formNewTenant" name="formNewTenant" method="POST"
                                        action="{{ url('step2/newTenant') }}">

                                        @csrf
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="input-form-70">
                                                    <div class="form-group ">
                                                        <p>
                                                            Bactopus will require a Stroage to run on
                                                        </p>
                                                        <p data-toggle="tooltip"
                                                            title=" We will use the information below to suggest creating the
                                                        correct Azure subscription Location for you. please choose
                                                        your Current Azure AD Tenant Country.">
                                                            <a href="https://portal.azure.com/#blade/Microsoft_AAD_IAM/ActiveDirectoryMenuBlade/Properties"
                                                                target="_blank">What should I choose?</a>
                                                        </p>
                                                        <select class="form-control" id="country" name="Country" required
                                                            onchange="changeCountry(event)">

                                                            <option selected="selected" style="color: #fff;" value="-2">
                                                                Country
                                                            </option>
                                                            <option value="-1">Other</option>

                                                            @foreach ($countries as $country)
                                                                <option value="{{ $country->code }}"
                                                                    {{ old('Country') == $country->code ? 'selected' : '' }}>
                                                                    {{ $country->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-withoutcountry-newtenant">
                                                <div class="input-form-70">
                                                    <div class="form-group ">
                                                        <p>
                                                            Existing Tenant means that your current Azure AD Tenant will
                                                            have
                                                            the Azure Subscription created on your behalf. New Tenant means
                                                            that
                                                            we will provision new Tenant and we will create the Azure
                                                            subscription there.

                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="input-form-70 tenant-group">
                                                    <div class="form-group ">
                                                        <label for="">Tenant:</label>

                                                        <div class="radio ms-t">
                                                            <label>
                                                                <input type="radio" name="tenantType" class="tenantType"
                                                                    id="microsoftTenant3" value="microsoftTenant" checked
                                                                    {{ old('tenantType') == 'microsoftTenant' ? 'checked=' . '"' . 'checked' . '"' : '' }}>Current
                                                                Tenant
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                        <div class="radio nt">
                                                            <label>
                                                                <input type="radio" name="tenantType" class="tenantType"
                                                                    id="newTenant3" value="newTenant"
                                                                    {{ old('tenantType') == 'newTenant' ? 'checked=' . '"' . 'checked' . '"' : '' }}>New
                                                                Tenant
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>




                                                <div class="input-form-70">
                                                    <div class="form-group ">
                                                        <div class="col-sm-8">
                                                            <div class="row">
                                                                <input type="text" class="form-control" id="Domain"
                                                                    placeholder="Domain" name="Domain"
                                                                    value="{{ old('Domain') }}" required
                                                                    autocomplete="off" />
                                                                <div class="loading-image">
                                                                    <img src="{{ url('img/loading.gif') }}"
                                                                        alt="loading-image" class="img-responsive"
                                                                        style="width: 20px;" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="row">
                                                                <label for=""
                                                                    class="domain-lbl">.onmicrosoft.com</label>
                                                            </div>
                                                        </div>

                                                        <span class="alert-danger domain-available" id="wrong-domain"
                                                            style="display:none" role="alert">
                                                            <strong> Tenant name already existed</strong><i
                                                                class="fa fa-close"></i>
                                                        </span>
                                                        <span class="alert-danger domain-available" id="wrong-domain1"
                                                            style="display:none" role="alert">
                                                            <strong> Tenant name has invalid characters</strong><i
                                                                class="fa fa-close"></i>
                                                        </span>

                                                        <span class="alert-success domain-available" id="success-domain"
                                                            style="display:none" role="alert">
                                                            <strong>Vaild</strong><i class="fa fa-check"></i>
                                                        </span>
                                                        @error('Domain')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="input-form-70">
                                                    <div class="form-group ">
                                                        <input type="number" class="form-control num" id="vat_num"
                                                            placeholder="VAT Number" name="vat_num" required
                                                            autocomplete="off" value="{{ old('vat_num') }}" />
                                                        @error('vat_num')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="input-form-70">
                                                    <div class="form-group ">
                                                        <input type="text" class="form-control" id="City"
                                                            placeholder="City" name="City"
                                                            value="{{ old('City') ? old('City') : session('tenantInfoCity') }}"
                                                            required autocomplete="off" />
                                                        @error('City')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="input-form-70">
                                                    <div class="form-group ">
                                                        <input type="text" class="form-control" id="State"
                                                            placeholder="State" name="State"
                                                            value="{{ old('State') ? old('State') : session('tenantInfoState') }}"
                                                            required autocomplete="off" />
                                                        @error('State')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="input-form-70">
                                                    <div class="form-group ">
                                                        <input type="text" class="form-control numm" id="PostalCode"
                                                            placeholder="Postal Code" name="PostalCode" required
                                                            autocomplete="off" value="{{ old('PostalCode') }}" />
                                                        @error('PostalCode')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="input-form-70">

                                                    <div class="form-group ">
                                                        <select class="form-control" id="country4" name="Country"
                                                            required onchange="changeCountry(event)" style="display:none">
                                                            <!-- <option value="?" selected="selected"></option> -->

                                                            <option selected="selected" style="color: #fff;"
                                                                value="-2">
                                                                Country
                                                            </option>
                                                            <option value="-1">Other</option>

                                                            @foreach ($countries as $country)
                                                                <option value="{{ $country->code }}"
                                                                    {{ old('Country') == $country->code ? 'selected' : '' }}>
                                                                    {{ $country->name }}</option>
                                                            @endforeach
                                                        </select>

                                                        @error('Country')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="input-form-70">

                                                    @if (\Session::has('errors_api'))
                                                        <div class="invalid-feedback">
                                                            <ul>
                                                                <li>{!! \Session::get('errors_api') !!}</li>
                                                            </ul>
                                                        </div>
                                                    @endif

                                                </div>

                                                <div class="input-form-70">
                                                    <div class="form-group ">
                                                        <button type="submit"
                                                            class="btn contact-button new-tenant-btn orng-btn">Next</button>
                                                    </div>
                                                </div>

                                                <!-- </div> -->
                                            </div>
                                        </div>
                                    </form>
                                    <h2 class=bottom-corner>&nbsp</h2>
                                </div>
                                <div class="parent-form4 col-sm-10 slider-step">
                                    <h2 class="corner-border">&nbsp</h2>
                                    <form class="form-horizontal" id="formMicrosoftTenant" name="formMicrosoftTenant"
                                        method="post" action="{{ url('/step2/checkTenant') }}">
                                        @csrf
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="input-form-70">
                                                    <div class="form-group ">
                                                        <p data-toggle="tooltip"
                                                            title=" We will use the information below to suggest creating the
                                                        correct Azure subscription Location for you. please choose
                                                        your Current Azure AD Tenant Country.">
                                                            <a href="https://portal.azure.com/#blade/Microsoft_AAD_IAM/ActiveDirectoryMenuBlade/Properties"
                                                                target="_blank"> What should I choose? </a>

                                                        </p>
                                                        <select class="form-control" id="country5" name="Country"
                                                            required onchange="changeCountry(event)">
                                                            <option selected="selected" style="color: #fff;"
                                                                value="-2">
                                                                Country
                                                            </option>
                                                            <option value="-1">Other</option>
                                                            @foreach ($countries as $country)
                                                                <option value="{{ $country->code }}"
                                                                    {{ old('Country') == $country->code ? 'selected' : '' }}>
                                                                    {{ $country->name }}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-withoutcountry-currentenant">
                                                <div class="input-form-70">
                                                    <div class="form-group ">
                                                        <p>
                                                            Existing Tenant means that your current Azure AD Tenant will
                                                            have
                                                            the Azure Subscription created on your behalf. New Tenant means
                                                            that
                                                            we will provision new Tenant and we will create the Azure
                                                            subscription there.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="input-form-70">
                                                    <div class="form-group ">
                                                        <label for="">Tenant:</label>
                                                        <div class="radio ms-t">
                                                            <label>
                                                                <input type="radio" name="tenantType"
                                                                    class="tenantType" id="microsoftTenant4"
                                                                    value="microsoftTenant" checked
                                                                    {{ old('tenantType') == 'microsoftTenant' ? 'checked=' . '"' . 'checked' . '"' : '' }}>Current
                                                                Tenant
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                        <div class="radio nt">
                                                            <label>
                                                                <input type="radio" name="tenantType"
                                                                    class="tenantType" id="newTenant4" value="newTenant"
                                                                    {{ old('tenantType') == 'newTenant' ? 'checked=' . '"' . 'checked' . '"' : '' }}>New
                                                                Tenant
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- <div class="input-form-70">
                                                        <div class="form-group ">
                                                            <input type="text" class="form-control" id="domainName"
                                                                placeholder="Domain Name" name="domainname">
                                                        </div>
                                                    </div> -->
                                                <div class="input-form-70">
                                                    <img src="{{ url('img/arr_down.png') }}" alt="arr_down"
                                                        class="img-responsive arr-down" />
                                                    <label for="step" class="lbl-step">Step 1:</label>
                                                    <div class="form-group link-box">
                                                        <p>
                                                            <a href="https://admin.microsoft.com/Adminportal/Home?invType=ResellerRelationship&partnerId=4b1991c7-cd6f-47e5-ab04-3a129d1c0240&msppId=0&DAP=True#/BillingAccounts/partner-invitation"
                                                                target="_blank">Click
                                                                here</a> to Add Ctelecoms to your Tenant
                                                            <!-- by Access The Below URL as
                                                                Global Admin -->
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="input-form-70">
                                                    <img src="{{ url('img/arr_down.png') }}" alt="arr_down"
                                                        class="img-responsive arr-down" />
                                                    <label for="step" class="lbl-step">Step 2:</label>
                                                    <div class="form-group ">
                                                        <p data-toggle="tooltip"
                                                            title=" We will use the information below to suggest creating the
                                                        correct Azure subscription Location for you. please choose
                                                        your Current Azure AD Tenant Country.">
                                                            <a href="https://portal.azure.com/#blade/Microsoft_AAD_IAM/ActiveDirectoryMenuBlade/Properties"
                                                                target="_blank">What should I choose?</a>

                                                        </p>
                                                        <input type="text" class="form-control" id="tenantId"
                                                            placeholder="Tenant Id" name="tenantId"
                                                            value="{{ old('tenantId') }}" required autocomplete="off">
                                                        @error('tenantId')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="input-form-70">

                                                    @if (\Session::has('errors_api_check'))
                                                        <div class="invalid-feedback">
                                                            <ul>
                                                                <li>{!! \Session::get('errors_api_check') !!}</li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="input-form-70" style="margin-top: 50px;">
                                                    <div class="form-group ">
                                                        <button type="submit"
                                                            class="btn contact-button ms-tenant-btn orng-btn">Next</button>
                                                    </div>
                                                </div>
                                                <!-- </div> -->
                                            </div>
                                        </div>
                                    </form>
                                    <h2 class=bottom-corner>&nbsp</h2>
                                </div>
                            </div>




                        </div>
                        <div class="col-sm-3 col-md-3 hidden-xs hidden-sm"></div>

                    </div>
                    <div class="global-footer">
                        <div class="col-sm-3 col-md-3 hidden-xs hidden-sm"></div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
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
                        <div class="col-sm-3 col-md-3 hidden-xs hidden-sm"></div>
                    </div>
                </div>
            </div>
        </section>

        <!--  ====================================================================================================  -->

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

        setInputFilter(document.getElementById("PostalCode"), function(value) {
            return /^\d*$/.test(value);
        });
    </script>
@endsection
