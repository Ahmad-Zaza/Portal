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
            <li class="liNavbar"><a class="active" href="{{ url('users-roles') }}">Users and Roles
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
                @if ($role->hasPermissionTo('users_view'))
                    <li><a class="off-white" href="#tab1">Users</a></li>
                @endif
                @if ($role->hasPermissionTo('roles_view'))
                    <li><a class="off-white" href="#tab2">Roles</a></li>
                @endif
            </ul>
        </div>
    </div>

    <div class="row azure-custom-info ml-25 mr-4 mb-20">
        <div class="col-lg-11">
            <div class="ml-15n mb-0 bg-color mr-9 autoHeight">
                @if ($role->hasPermissionTo('users_view'))
                    <div id="tab1">
                        @include('users-management.users')
                    </div>
                @endif
                @if ($role->hasPermissionTo('roles_view'))
                    <div id="tab2" class="hide">
                        @include('users-management.roles')
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        $(window).on('load', function() {
            $('.select2-selection__arrow').addClass('mt-4p');
            let from = getUrlParameter('from');
            $(".nav-tabs .active").removeClass("active");
            if (from == "roles") {
                $(".nav-tabs li:last").addClass("active");
                $("#tab1").addClass("hide");
                $("#tab2").removeClass("hide");
            } else {
                $(".nav-tabs li:first").addClass("active");
                $("#tab2").addClass("hide");
                $("#tab1").removeClass("hide");
            }
            adjustTable();
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

        function showSuccessMessage(message) {
            $(".success-oper .success-msg").html(message);
            $(".success-oper").css("display", "block");
            setTimeout(function() {
                $(".success-oper").css("display", "none");
            }, 8000);
        }

        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
            return false;
        };
    </script>
@endsection
