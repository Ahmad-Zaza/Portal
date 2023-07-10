@extends('layouts.register-head')

@section('content')
<style>
    .mid-screen {
        top: 90%;
        position: absolute;
    }

    #finishVideo {
        position: fixed;
        right: 0;
        bottom: 0;
        z-index: -1;
        min-width: 100%;
        min-height: 100%;
    }
</style>
<div class="home-page">
    @include("auth.register-nav")

    <video autoplay muted loop id="finishVideo">
        <source src="finish.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>
    <section class="global-section registration-section col-sm-12">
        <div class="container">
            <div class="row">
                <div class="global-section-details">


                    <div class="mid-screen col-md-12 col-sm-12 col-xs-12">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">

                            <div class="col-md-10 col-sm-10 col-xs-10"> Finalizing Configuration</div>
                            <div class="col-md-12 col-sm-12 col-xs-12">

                                <div class="progress">
                                    <div id="progress-bar" style="background-color:#fa9351;" class="progress-bar active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:1%">
                                        1%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2"></div>

                    </div>

                </div>
                <div class="global-footer">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        <div class="row footer">
                            <div class="poweredby col-sm-6 col-xs-6  flex align-items-center justify-content-end">
                                <span>Powered by</span>
                                <a href="https://www.veeam.com/" target="_blank">
                                    <img src="{{url('img/Veeam_logo.png')}}" alt="Veeam_logo" class="img-responsive">
                                </a>
                            </div>
                            <div class="hostedby col-sm-6 col-xs-6 flex align-items-center">
                                <span>Hosted on</span>
                                <a href="https://azure.microsoft.com/en-us/" target="_blank">
                                    <img src="{{url('img/azure_logo.png')}}" alt="Veeam_logo" class="img-responsive">
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
    updateBar();
    function updateBar() {
        $.ajax({
            type: "GET",
            url: "getProgress",
            async: false,
            data: {},
            dataType: "json",
            success: function(data) {
                progress = Math.round(data);
                $("#progress-bar").html(progress + " %");
                $("#progress-bar").width(progress + "%");
                if (data == 100) {
                    window.location = "/home";
                } else {
                    setTimeout(function() {
                        updateBar();
                    }, 6000);
                }
            },
            error: function(error) {
                let errMessage = "   ERROR Finishing Registeration  ";
                $(".danger-oper").html(errMessage);
                $(".danger-oper").css("display", "block");
                setTimeout(function() {
                    $(".danger-oper").css("display", "none");
                }, 8000);
            }
        });
    }
</script>

@endsection
