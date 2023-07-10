@extends('layouts.register-head')

@section('content')
    <style>
        .support_btn {
            border: 1px solid #E9E9E9 !important;
            background: none;
            margin-top: 10px;
        }

        .btn {
            width: 210px;
            min-width: fit-content;
        }

        .bactopus_logo {
            width: 50%;
            /* margin-right: -5%; */
        }

        .btn-containers {
            flex-direction: column;
            width: 50%;
            margin-left: auto;
            margin-right: auto;
        }
        .footer{
            margin-left: -15px;
        }
    </style>
    <div class="home-page">
        <section class="global-section registration-section col-sm-12" style="background-image: url('img/steps-bg.png');">
            <div class="container">
                <div class="row">
                    <div class="global-section-details flex align-items-center">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6 text-center">
                            <h1 class="text-white">Welcome to Bactopus</h1>
                            <img src="{{ asset('images/logo_slogan.png') }}" alt="" class="bactopus_logo">
                            <br>
                            <div>
                                <p>{{ __("variables.messages.marketplace_landing_page") }}</p>
                            </div>
                            <br>
                            <div class="flex justify-content-center btn-containers">

                                <a class="btn contact-button sign-up-btn orng-btn"
                                    href="{{ route('configure-subscription') }}" style="margin-right:15px;">
                                    <span class="fa fa-th-large" style="margin-right:5px;"></span> Configure Your Account
                                </a>

                                <a href="https://support.ctelecoms.com.sa/" target="_blank" class="btn support_btn"><span class="fa fa-headphones"
                                        style="margin-right:5px;"></span> Contact Us</a>
                            </div>

                        </div>
                        <div class="col-sm-3"></div>

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
