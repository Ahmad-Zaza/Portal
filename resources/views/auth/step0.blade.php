@extends('layouts.register-head')

@section('content')
    <div class="home-page">
        @include("auth.register-nav")
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
                                                    <li><a href="#0" class="ui-link disabled-step" style="left: 0;">
                                                            <p>EULA</p> <span></span>
                                                        </a></li>
                                                    <li><a href="#" class="ui-link disabled-step" style="left: 33.333%;">
                                                            <p>Registration</p> <span></span>
                                                        </a></li>
                                                    <li><a href="#0" class="ui-link disabled-step" style="left: 66.666%;">
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
                            <div class="parent-form0 col-sm-10">

                                <h2 class="corner-border">&nbsp</h2>
                                <form class="form-horizontal" id="permissionsForm" name="permissionsForm" method="POST"
                                    action="" autocomplete="off">
                                    @csrf

                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="input-form-70">
                                                <h4 class="per-req">End User License Agreement</h4>
                                                @include('partials.session-messages')

                                                <p class="agree-licence" data-scrollbar>
                                                    1. Thank you for Starting the Registeration Process of Bactopus.
                                                    <br/>
                                                    <br/>
                                                    2. Our Cloud SaaS Backup Solution is hosted on Microsoft Azure and using
                                                    Microsoft Azure to Store your Data.
                                                    <br/>
                                                    By Clicking Accept below, you are agreeing to <a class="txt-blue"
                                                        href="https://www.microsoft.com/licensing/docs/customeragreement "
                                                        target="_blank">Microsoft Customer Agreement</a>
                                                    <br/>
                                                    3. Veeam Backup for Microsoft 365 is the Main Engine for Bactopus
                                                    and it's fully integrated within this Platform and Microsoft Azure.
                                                    Any Data that you fill for your Microsoft 365 (ex. Credential ) is
                                                    currently not Stored in our SaaS System nor our Databases.
                                                    It's only being Processed by Veeam Backup for Microsoft 365 to Backup your
                                                    Data.
                                                    <br/>
                                                    <a class="txt-blue"
                                                        href="https://helpcenter.veeam.com/docs/vbo365/guide/vbo_required_permissions.html?ver=60"
                                                        target="_blank">Check Here for all the needed Permissions by Veeam
                                                        to Process your Data</a>
                                                    <br/>
                                                </p>
                                            </div>




                                            <div class="input-form-70">
                                                <div class="">
                                                    <form id="step1-form" action="{{ route('step0') }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                    </form>
                                                    <a class="btn contact-button sign-up-btn orng-btn" href="{{ route('step0') }}" onclick="event.preventDefault();
                                                         document.getElementById('step1-form').submit();">
                                                        Accept & Continue
                                                    </a>

                                                    <form id="step1-form" action="{{ route('step0') }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                    </form>
                                                    <a href="{{ route('login') }}" class="">Cancel</a>
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
@endsection
