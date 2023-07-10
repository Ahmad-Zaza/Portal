@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/main.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/tabs.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/repositories.css') }}" />
<link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/generalElement.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/restore.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/bactopus-settings.css') }}" />

@section('topnav')
    <style>
        i {
            padding: 0px 3px;
        }

    </style>
    <div class="col-sm-10 navbarLayout">
        <!-- Upper navbar -->
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm upperNavBar"> -->
        <ul class="ulNavbar">
            <li class="liNavbar"><a class="active" href="{{ route('bactopus-settings') }}">Bactopus Settings
                </a></li>
            <!-- Authentication Links -->
            @include('layouts.authentication-links')
        </ul>
    </div>
@endsection

@section('content')
    <div class="allWidth ml-25">
        <div class="nopadding zi-5">
            <ul class="nav-t nav-tabs">
                <li class="active"><a class="off-white" href="#tab1">My Profile</a></li>
                @if ($role->hasPermissionTo('backup_applications'))
                    <li><a class="off-white" href="#tab3">AAD Backup Applications</a></li>
                @endif
                @if ($role->hasPermissionTo('license_management'))
                    <li><a class="off-white" href="#tab4">License Management</a></li>
                @endif
                @if ($role->hasPermissionTo('notifications_management'))
                    <li><a disabled="disabled" type="button" class="off-white" href="#tab5">Notifications (Coming
                            Soon)</a></li>
                @endif
            </ul>
        </div>
    </div>

    <div class="row azure-custom-info ml-25 mr-4 mb-20">
        <div class="col-lg-11">
            <div class="ml-15n mb-0 bg-color mr-9 autoHeight">
                <div id="tab1">
                    @include('bactopus-settings.profile-information')
                </div>
                @if ($role->hasPermissionTo('backup_applications'))
                <div id="tab3" class="hide">
                    @include('bactopus-settings.backup-applications')
                </div>
                @endif
                @if ($role->hasPermissionTo('license_management'))
                <div id="tab4" class="hide">
                    @include('bactopus-settings.license-management')
                </div>
                @endif
                @if ($role->hasPermissionTo('notifications_management'))
                <div id="tab5" class="hide">
                    @include('bactopus-settings.notification')
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        $(window).on('load', function() {

            $('.select2-selection__arrow').addClass('mt-4p');
            $('.nav-tabs > li > a:not([disabled])').click(function(event) {
                event.preventDefault(); //stop browser to take action for clicked anchor
                //get displaying tab content jQuery selector
                var active_tab_selector = $('.nav-tabs > li.active > a').attr('href');
                //find actived navigation and remove 'active' css
                var actived_nav = $('.nav-tabs > li.active');
                actived_nav.removeClass('active');
                //add 'active' css into clicked navigation
                $(this).parents('li').addClass('active');
                //hide displaying tab content
                $(active_tab_selector).removeClass('active');
                $(active_tab_selector).addClass('hide');
                //show target tab content
                var target_tab_selector = $(this).attr('href');
                $(target_tab_selector).removeClass('hide');
                $(target_tab_selector).addClass('active');
                adjustTable();
            });
        });

        function minmizeSideBar() {
            $('.main-content-div').removeClass('col-sm-10').addClass('col-sm-12');
            $('.navbarLayout').removeClass('col-sm-10').addClass('width-94');
            $('.logo-outer-div-min').removeClass('hideMenu');
            $('.leftNavBar-min').removeClass('hideMenu');
            $('.logo-outer-div-max').addClass('hideMenu');
            $('.leftNavBar-max').addClass('hideMenu');
        }


        $(document).ready(function() {
            minmizeSideBar();
        });

        function veeamAuth(event) {
            event.preventDefault();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            let data = $('#authenticationForm').serialize();
            $.ajax({
                type: "POST",
                url: "{{ url('authUserVeeam') }}",
                data: data,
                dataType: 'json',
                success: function(data) {
                    //------------------------------------------//
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    //------------------------------------------//
                    showSuccessMessage(data.message);
                    //------------------------------------------//
                },
                "statusCode": {
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
                    let errMessage = "   ERROR   ";
                    if (error.responseText) {
                        let err = JSON.parse(error.responseText);
                        if (err.message)
                            errMessage = "ERROR  : " + err.message;
                    }
                    $(".danger-oper .danger-msg").html(errMessage);
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");
                    }, 8000);
                }
            });
        }

        function showSuccessMessage(message) {
            $(".success-oper .success-msg").html(message);
            $(".success-oper").css("display", "block");
            setTimeout(function() {
                $(".success-oper").css("display", "none");
            }, 8000);
        }
    </script>
@endsection
