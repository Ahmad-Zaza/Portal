@extends('layouts.register-head')

@section('content')
    <style>
        .support_btn {
            border: 1px solid #E9E9E9 !important;
            background: none;
            margin-top: 10px;
        }
    </style>
    <div class="home-page">
        <section class="section1 col-sm-12" style="background-image: url({{ url('img/marketplace_background.png') }});">
            <div class="container">
                <div class="row">
                    <div class="section1-details">
                        <div class="parent-form1 col-md-5 col-sm-6">
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                </div>
                            </div>
                            <form class="form-horizontal" method="POST" action="{{ route('login') }}" id="formLogin">

                                @csrf

                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-10 flex flex-direction-column">
                                            <h1 class="text-white norwap" style="font-family:AvianoBold;">Welcome to
                                                Bactopus</h1>
                                            <br>
                                            <div>
                                                <p>{{ __('variables.messages.marketplace_landing_page') }}</p>
                                            </div>
                                            <br>
                                            <a class="btn contact-button sign-up-btn orng-btn"
                                                href="{{ route('configure-subscription') }}"
                                                style="margin-right:15px;width:250px;">
                                                <span class="fa fa-th-large" style="margin-right:5px;"></span> Configure
                                                Your Account
                                            </a>
                                            <a href="https://support.ctelecoms.com.sa/" target="_blank"
                                                class="btn support_btn" style="width:250px;"><span class="fa fa-headphones"
                                                    style="margin-right:5px;"></span> Contact Us</a>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="global-footer">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6">
                            <div class="row footer">
                                <div class="poweredby col-sm-6 col-xs-6  flex align-items-center justify-content-end">
                                    <span>Powered by</span>
                                    <a href="https://www.veeam.com/">
                                        <img src="{{ url('img/Veeam_logo.png') }}" alt="Veeam_logo" class="img-responsive">
                                    </a>
                                </div>
                                <div class="hostedby col-sm-6 col-xs-6 flex align-items-center">
                                    <span>Hosted on</span>
                                    <a href="https://azure.microsoft.com/en-us/">
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
