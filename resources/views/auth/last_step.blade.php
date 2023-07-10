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

</script>

@endsection
