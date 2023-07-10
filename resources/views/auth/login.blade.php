@extends('layouts.register-head')

@section('content')

    <div class="home-page">
        <section class="section1 col-sm-12" style="background-image: url({{ url('img/login-bg.png') }});">
            <div class="container">
                <div class="row">
                    <div class="section1-details">
                        <div class="parent-form1 col-md-4 col-sm-6">
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                </div>
                            </div>
                            <form class="form-horizontal" method="POST" action="{{ route('login') }}" id="formLogin">

                                @csrf

                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <div class="form-group text-center">
                                                <img src="{{ asset('images/login_logo2.png') }}" alt="" style="width:70%;margin-top:-40px;margin-bottom:40px;">
                                                <a href="{{ url('sso') }}" class="btn login-button orng-btn"
                                                    style="margin-top: 10px"><span class="fa fa-th-large"
                                                        style="margin-right:5px;"></span> Login with Microsoft Work
                                                    Account</a>
                                                @include('partials.session-messages')
                                            </div>
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
                                        <img src="{{ url('img/Veeam_logo.png') }}" alt="Veeam_logo"
                                            class="img-responsive">
                                    </a>
                                </div>
                                <div class="hostedby col-sm-6 col-xs-6 flex align-items-center">
                                    <span>Hosted on</span>
                                    <a href="https://azure.microsoft.com/en-us/">
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
