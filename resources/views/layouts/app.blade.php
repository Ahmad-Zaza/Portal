<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bactopus Portal | SaaS Backup & Recovery Solution for Microsoft 365</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/bactopus_favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.css') }}">

    <!-- Scripts -->

    <!-- <script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script> -->
    <script src="{{ asset('js/jquery-1.12.4.min.js') }}"></script>

    <script src="{{ asset('js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/buttons.html5.min.js') }}"></script>

    <script src="{{ asset('js/pdf-customize.js') }}"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">

    <link rel="stylesheet" class="en-style" href="{{ asset('css/ltr_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <script src="{{ asset('js/fonts.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <link href="{{ url('/css/checkbox.css') }}" rel="stylesheet" type="text/css">

    @stack("styles")
</head>

<body class="removeScroll">

    <div class="loading">
        <div class="wrapper">
            <div class="loader-outer">
                <div class="loader-inner">
                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                </div>
            </div>
            <h1><span>LOADING</span></h1>
        </div>
    </div>
    <link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/mainAppStyle.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/spinner.css') }}" />
    <div class="spinner_parent">
        <div class="wrapper">
            <div class="sk-fading-circle">
                <div class="sk-circle1 sk-circle"></div>
                <div class="sk-circle2 sk-circle"></div>
                <div class="sk-circle3 sk-circle"></div>
                <div class="sk-circle4 sk-circle"></div>
                <div class="sk-circle5 sk-circle"></div>
                <div class="sk-circle6 sk-circle"></div>
                <div class="sk-circle7 sk-circle"></div>
                <div class="sk-circle8 sk-circle"></div>
                <div class="sk-circle9 sk-circle"></div>
                <div class="sk-circle10 sk-circle"></div>
                <div class="sk-circle11 sk-circle"></div>
                <div class="sk-circle12 sk-circle"></div>
            </div>
            <h1><span>PLEASE WAIT...</span></h1>
        </div>
    </div>
    <div id="resizeError" class="hide"></div>
    <div id="app">

        <div class="row logorow ">
            <div
                class="logo-outer-div logo-outer-div-min hideMenu text-center flex align-items-center place-content-center">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/bactopus_logo.png') }}" alt="" style="max-height: 30px;">
                </a>
                <img style="width:20px" class="side-nav-icon nav-icon" src="{{ url('/svg/next.svg') }}">
            </div>


            <div class="col-sm-2 logo-outer-div logo-outer-div-max text-center flex align-items-center">
                <a href="{{ route('home') }}" style="padding-left: 0.5vw;">
                    <img src="{{ asset('images/login_logo.png') }}" alt="" style="max-height: 35px;">
                </a>
                <img style="width:20px;margin-right: -3px" class="side-nav-icon nav-icon"
                    src="{{ url('/svg/next.svg') }}">
            </div>
            @yield('topnav')
        </div>



        <div class="contaier-fluid mainApp">
            <!-- left dashbord menu-->
            <div class="leftNavBar leftNavBar-min hideMenu">
                <div class="row">
                    <div class="col-sm-12 nopadding">
                        <a class="left-nav-list" href="{{ url('home') }}">
                            <button class="btnDashbord text-left">
                                <img title="Dashboard" class="iconColor-min nav-icon"
                                    src="{{ url('/svg/speedometer.svg') }}" style="width: 28px; margin-right:16px;">
                            </button>
                        </a>
                    </div>
                </div>
                @if ($role->hasAnyPermission('view_repository'))
                    <div class="row">
                        <div class="col-sm-12 nopadding">
                            <a class="left-nav-list" href="{{ url('repositories') }}">
                                <button class="btnDashbord text-left">
                                    <img title="Repositories" class="iconColor-min nav-icon"
                                        src="{{ url('/svg/r.svg') }}">
                                </button>
                            </a>
                        </div>
                    </div>
                @endif
                @if ($role->hasAnyPermission(
                    'exchange_view_backup',
                    'onedrive_view_backup',
                    'sharepoint_view_backup',
                    'teams_view_backup'))
                    <div class="row">
                        <div class="col-sm-12 nopadding">
                            <a class="left-nav-list dropdown collapsed" href="#submenu2" data-toggle="collapse"
                                aria-expanded="false">
                                <button class=" btnDashbord text-left">
                                    <img title="Backup" class="iconColor-min nav-icon"
                                        src="{{ url('/svg/backup.svg') }}" style="width: 28px; margin-right:5px;">
                                    <span class="caret"></span>
                                </button>
                            </a>
                            <div id='submenu2'
                                class="bck-sm submenu-backup submenu sidebar-submenu sidebar-submenu-min collapse"
                                aria-expanded="false">
                                @if ($role->hasPermissionTo('exchange_view_backup'))
                                    <a data-route="exchange" href="{{ url('backup', 'exchange') }}"
                                        class=" sub-menu-link text-left backup1" title="Exchange Backup">
                                        <img class="iconColor-sub-min nav-icon" src="{{ url('/svg/exchange.svg') }}">
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('onedrive_view_backup'))
                                    <a data-route="onedrive" href="{{ url('backup', 'onedrive') }}"
                                        class=" sub-menu-link text-left backup2" title="OneDrive Backup">
                                        <img class="iconColor-sub-min nav-icon" src="{{ url('/svg/onedrive.svg') }}">
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('sharepoint_view_backup'))
                                    <a data-route="sharepoint" href="{{ url('backup', 'sharepoint') }}"
                                        class=" sub-menu-link text-left backup3" title="SharePoint Backup">
                                        <img class="iconColor-sub-min nav-icon"
                                            src="{{ url('/svg/sharepoint.svg') }}">
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('teams_view_backup'))
                                    <a data-route="teams" href="{{ url('backup', 'teams') }}"
                                        class=" sub-menu-link text-left backup4" title="Teams Backup">
                                        <img class="iconColor-sub-min nav-icon" src="{{ url('/svg/teams.svg') }}">
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if ($role->hasAnyPermission(
                    'exchange_create_restore_session',
                    'onedrive_create_restore_session',
                    'sharepoint_create_restore_session',
                    'teams_create_restore_session'))
                    <div class="row">
                        <div class="col-sm-12 nopadding">
                            <a class="left-nav-list dropdown collapsed" href="#submenu3" data-toggle="collapse"
                                aria-expanded="false">
                                <button class=" btnDashbord text-left">
                                    <img title="Restore" class="iconColor-min nav-icon"
                                        src="{{ url('/svg/restore.svg') }}" style="width: 28px; margin-right:5px;">
                                    <span class="caret"></span>
                                </button>
                            </a>
                            <div id='submenu3'
                                class="res-sm submenu-restore submenu sidebar-submenu sidebar-submenu-min collapse"
                                aria-expanded="false">
                                @if ($role->hasPermissionTo('exchange_create_restore_session'))
                                    <a data-route="exchange" href="{{ url('restore', 'exchange') }}"
                                        class=" sub-menu-link text-left" title="Exchange Restore">
                                        <img class="iconColor-sub-min nav-icon" src="{{ url('/svg/exchange.svg') }}">
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('onedrive_create_restore_session'))
                                    <a data-route="onedrive" href="{{ url('restore', 'onedrive') }}"
                                        class=" sub-menu-link text-left" title="OneDrive Restore">
                                        <img class="iconColor-sub-min nav-icon" src="{{ url('/svg/onedrive.svg') }}">
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('sharepoint_create_restore_session'))
                                    <a data-route="sharepoint" href="{{ url('restore', 'sharepoint') }}"
                                        class=" sub-menu-link text-left" title="Sharepoint Restore">
                                        <img class="iconColor-sub-min nav-icon"
                                            src="{{ url('/svg/sharepoint.svg') }}">
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('teams_create_restore_session'))
                                    <a data-route="teams" href="{{ url('restore', 'teams') }}"
                                        class=" sub-menu-link text-left" title="Teams Restore">
                                        <img class="iconColor-sub-min nav-icon" src="{{ url('/svg/teams.svg') }}">
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if ($role->hasAnyPermission(
                    'exchange_view_history',
                    'onedrive_view_history',
                    'sharepoint_view_history',
                    'teams_view_history'))
                    <div class="row">
                        <div class="col-sm-12 nopadding">
                            <a class="left-nav-list dropdown collapsed" href="#historySubmenu2"
                                data-toggle="collapse" aria-expanded="false">
                                <button class=" btnDashbord text-left">
                                    <img title="Restore History" class="iconColor-min nav-icon"
                                        src="{{ url('/svg/history.svg') }}" style="width: 28px; margin-right:5px;">
                                    <span class="caret"></span>
                                </button>
                            </a>
                            <div id='historySubmenu2'
                                class="res-his-sm submenu-restore-history submenu sidebar-submenu sidebar-submenu-min collapse"
                                aria-expanded="false">
                                @if ($role->hasPermissionTo('exchange_view_history'))
                                    <a data-route="exchange" href="{{ url('restore-history', 'exchange') }}"
                                        class="sub-menu-link text-left" title="Exchange Restore History">
                                        <img class="iconColor-sub-min nav-icon" src="{{ url('/svg/exchange.svg') }}">
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('onedrive_view_history'))
                                    <a data-route="onedrive" href="{{ url('restore-history', 'onedrive') }}"
                                        class="sub-menu-link text-left" title="OneDrive Restore History">
                                        <img class="iconColor-sub-min nav-icon" src="{{ url('/svg/onedrive.svg') }}">
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('sharepoint_view_history'))
                                    <a data-route="sharepoint" href="{{ url('restore-history', 'sharepoint') }}"
                                        class="sub-menu-link text-left" title="Sharepoint Restore History">
                                        <img class="iconColor-sub-min nav-icon"
                                            src="{{ url('/svg/sharepoint.svg') }}">
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('teams_view_history'))
                                    <a data-route="teams" href="{{ url('restore-history', 'teams') }}"
                                        class="sub-menu-link text-left" title="Teams Restore History">
                                        <img class="iconColor-sub-min nav-icon" src="{{ url('/svg/teams.svg') }}">
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if ($role->hasAnyPermission(
                    'exchange_view_ediscovery_jobs',
                    'onedrive_view_ediscovery_jobs',
                    'sharepoint_view_ediscovery_jobs',
                    'teams_view_ediscovery_jobs'))
                    <div class="row">
                        <div class="col-sm-12 nopadding">
                            <a class="left-nav-list dropdown collapsed" href="#eDiscovrySubmenu2"
                                data-toggle="collapse" aria-expanded="false">
                                <button class=" btnDashbord text-left">
                                    <img title="eDiscovery" class="iconColor-min nav-icon"
                                        src="{{ url('/svg/discovery1.svg') }}"
                                        style="width: 28px; margin-right:5px;">
                                    <span class="caret"></span>
                                </button>
                            </a>
                            <div id='eDiscovrySubmenu2'
                                class="res-sm submenu-discovery submenu sidebar-submenu sidebar-submenu-min collapse"
                                aria-expanded="false">
                                @if ($role->hasPermissionTo('exchange_view_ediscovery_jobs'))
                                    <a data-route="exchange" href="{{ url('e-discovery', 'exchange') }}"
                                        class="sub-menu-link text-left" title="Exchange eDiscovery">
                                        <img class="iconColor-sub-min nav-icon" src="{{ url('/svg/exchange.svg') }}">
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('onedrive_view_ediscovery_jobs'))
                                    <a data-route="onedrive" href="{{ url('e-discovery', 'onedrive') }}"
                                        class="sub-menu-link text-left" title="OneDrive eDiscovery">
                                        <img class="iconColor-sub-min nav-icon" src="{{ url('/svg/onedrive.svg') }}">
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('sharepoint_view_ediscovery_jobs'))
                                    <a data-route="sharepoint" href="{{ url('e-discovery', 'sharepoint') }}"
                                        class="sub-menu-link text-left" title="SharePoint eDiscovery">
                                        <img class="iconColor-sub-min nav-icon"
                                            src="{{ url('/svg/sharepoint.svg') }}">
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('teams_view_ediscovery_jobs'))
                                    <a data-route="teams" href="{{ url('e-discovery', 'teams') }}"
                                        class="sub-menu-link text-left" title="Teams eDiscovery">
                                        <img class="iconColor-sub-min nav-icon" src="{{ url('/svg/teams.svg') }}">
                                    </a>
                                @endif

                            </div>
                        </div>
                    </div>
                @endif
                <!-- last -->
                <!-- end last -->
                <div class="row">
                    <div class="col-sm-12 nopadding">
                        <a class="left-nav-list" href="https://support.ctelecoms.com.sa/" target="_blank">
                            <button class=" btnDashbord text-left">
                                <img title="Support" class="iconColor-min nav-icon"
                                    src="{{ url('/svg/support.svg') }}" style="width: 28px; margin-right:5px;">
                            </button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-2 leftNavBar leftNavBar-max custom-flex">

                <div class="row">
                    <div class="col-lg-12">
                        <br />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 nopadding">
                        <a class="left-nav-list" href="{{ url('home') }}">
                            <button class="btnDashbord text-left">
                                <img class="iconColor nav-icon" src="{{ url('/svg/speedometer.svg') }}"
                                    style="width: 28px; margin-right:16px;">
                                <span>Dashboard</span>
                            </button>
                        </a>
                    </div>
                </div>
                @if ($role->hasAnyPermission('view_repository'))
                    <div class="row">
                        <div class="col-sm-12 nopadding">
                            <a class="left-nav-list" href="{{ url('repositories') }}">
                                <button class="btnDashbord text-left">
                                    <img class="iconColor nav-icon" src="{{ url('/svg/r.svg') }}">
                                    <span>Repositories</span>
                                </button>
                            </a>
                        </div>
                    </div>
                @endif
                @if ($role->hasAnyPermission(
                    'exchange_view_backup',
                    'onedrive_view_backup',
                    'sharepoint_view_backup',
                    'teams_view_backup'))
                    <div class="row">
                        <div class="col-sm-12 nopadding">
                            <a class="left-nav-list collapsed" href="#submenu1" data-toggle="collapse"
                                aria-expanded="true">
                                <button class=" btnDashbord text-left">
                                    <img class="iconColor nav-icon" src="{{ url('/svg/backup.svg') }}"
                                        style="width: 28px; margin-right:16px;">
                                    <span>Backup Jobs</span>
                                    <span style="margin-left: 10px;" class="caret"></span>
                                </button>
                            </a>
                            <!-- Submenu content -->
                            <div id='submenu1' class="bck-sm submenu-backup submenu sidebar-submenu collapse"
                                aria-expanded="false">
                                @if ($role->hasPermissionTo('exchange_view_backup'))
                                    <a data-route="exchange" href="{{ url('backup', 'exchange') }}"
                                        class=" sub-menu-link text-left backup1 exchange-color">
                                        <div class="">Exchange</div>
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('onedrive_view_backup'))
                                    <a data-route="onedrive" href="{{ url('backup', 'onedrive') }}"
                                        class=" sub-menu-link text-left backup2 onedrive-color">
                                        <div class="">OneDrive</div>
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('sharepoint_view_backup'))
                                    <a data-route="sharepoint" href="{{ url('backup', 'sharepoint') }}"
                                        class=" sub-menu-link text-left backup3 sharepoint-color">
                                        <div class="">SharePoint</div>
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('teams_view_backup'))
                                    <a data-route="teams" href="{{ url('backup', 'teams') }}"
                                        class="backup4 sub-menu-link text-left teams-color">
                                        <div class="">Teams</div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if ($role->hasAnyPermission(
                    'exchange_create_restore_session',
                    'onedrive_create_restore_session',
                    'sharepoint_create_restore_session',
                    'teams_create_restore_session'))
                    <div class="row">
                        <div class="col-sm-12 nopadding">
                            <a class="left-nav-list collapsed" href="#submenu4" data-toggle="collapse"
                                aria-expanded="true">
                                <button class=" btnDashbord text-left">
                                    <img class="iconColor nav-icon" src="{{ url('/svg/restore.svg') }}"
                                        style="width: 28px; margin-right:16px;">
                                    <span>Restore</span>
                                    <span style="margin-left: 10px;" class="caret"></span>
                                </button>
                            </a>
                            <!-- Submenu content -->
                            <div id='submenu4' class="res-sm submenu-restore submenu sidebar-submenu collapse"
                                aria-expanded="true">
                                @if ($role->hasPermissionTo('exchange_create_restore_session'))
                                    <a data-route="exchange" href="{{ url('restore', 'exchange') }}"
                                        class=" sub-menu-link text-left exchange-color">
                                        <div class="">Exchange</div>
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('onedrive_create_restore_session'))
                                    <a data-route="onedrive" href="{{ url('restore', 'onedrive') }}"
                                        class=" sub-menu-link text-left onedrive-color">
                                        <div class="">OneDrive</div>
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('sharepoint_create_restore_session'))
                                    <a data-route="sharepoint" href="{{ url('restore', 'sharepoint') }}"
                                        class="sub-menu-link text-left sharepoint-color">
                                        <div class="">Sharepoint</div>
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('teams_create_restore_session'))
                                    <a data-route="teams" href="{{ url('restore', 'teams') }}"
                                        class="sub-menu-link text-left teams-color">
                                        <div class="">Teams</div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if ($role->hasAnyPermission(
                    'exchange_view_history',
                    'onedrive_view_history',
                    'sharepoint_view_history',
                    'teams_view_history'))
                    <div class="row">
                        <div class="col-sm-12 nopadding">
                            <a class="left-nav-list collapsed" href="#historySubmenu1" data-toggle="collapse"
                                aria-expanded="true">
                                <button class=" btnDashbord text-left">
                                    <img class="iconColor nav-icon" src="{{ url('/svg/history.svg') }}"
                                        style="width: 28px; margin-right:16px;">
                                    <span>Restore History</span>
                                    <span style="margin-left: 10px;" class="caret"></span>
                                </button>
                            </a>
                            <!-- Submenu content -->
                            <div id='historySubmenu1'
                                class="res-his-sm submenu-restore-history submenu sidebar-submenu collapse"
                                aria-expanded="true">
                                @if ($role->hasPermissionTo('exchange_view_history'))
                                    <a data-route="exchange" href="{{ url('restore-history', 'exchange') }}"
                                        class=" sub-menu-link text-left exchange-color">
                                        <div class="">Exchange</div>
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('onedrive_view_history'))
                                    <a data-route="onedrive" href="{{ url('restore-history', 'onedrive') }}"
                                        class=" sub-menu-link text-left onedrive-color">
                                        <div class="">Onedrive</div>
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('sharepoint_view_history'))
                                    <a data-route="sharepoint" href="{{ url('restore-history', 'sharepoint') }}"
                                        class=" sub-menu-link text-left sharepoint-color">
                                        <div class="">Sharepoint</div>
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('teams_view_history'))
                                    <a data-route="teams" href="{{ url('restore-history', 'teams') }}"
                                        class=" sub-menu-link text-left teams-color">
                                        <div class="">Teams</div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if ($role->hasAnyPermission(
                    'exchange_view_ediscovery_jobs',
                    'onedrive_view_ediscovery_jobs',
                    'sharepoint_view_ediscovery_jobs',
                    'teams_view_ediscovery_jobs'))
                    <div class="row">
                        <div class="col-sm-12 nopadding">
                            <a class="left-nav-list collapsed" href="#eDiscovrySubmenu1" data-toggle="collapse"
                                aria-expanded="true">
                                <button class=" btnDashbord text-left">
                                    <img class="iconColor nav-icon" src="{{ url('/svg/discovery1.svg') }}"
                                        style="width: 28px; margin-right:16px;">
                                    <span>eDiscovery</span>
                                    <span style="margin-left: 10px;" class="caret"></span>
                                </button>
                            </a>
                            <!-- Submenu content -->
                            <div id='eDiscovrySubmenu1'
                                class="edis-sm submenu-discovery submenu sidebar-submenu collapse"
                                aria-expanded="true">
                                @if ($role->hasPermissionTo('exchange_view_ediscovery_jobs'))
                                    <a data-route="exchange" href="{{ url('e-discovery', 'exchange') }}"
                                        class=" sub-menu-link text-left exchange-color">
                                        <div class="">Exchange</div>
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('onedrive_view_ediscovery_jobs'))
                                    <a data-route="onedrive" href="{{ url('e-discovery', 'onedrive') }}"
                                        class=" sub-menu-link text-left onedrive-color">
                                        <div class="">Onedrive</div>
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('sharepoint_view_ediscovery_jobs'))
                                    <a data-route="sharepoint" href="{{ url('e-discovery', 'sharepoint') }}"
                                        class=" sub-menu-link text-left sharepoint-color">
                                        <div class="">Sharepoint</div>
                                    </a>
                                @endif
                                @if ($role->hasPermissionTo('teams_view_ediscovery_jobs'))
                                    <a data-route="teams" href="{{ url('e-discovery', 'teams') }}"
                                        class=" sub-menu-link text-left teams-color">
                                        <div class="">Teams</div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row pb-30">
                    <div class="col-sm-12 nopadding">
                        <a class="left-nav-list collapsed" href="https://support.ctelecoms.com.sa/" target="_blank">
                            <button class=" btnDashbord text-left">
                                <img class="iconColor nav-icon" src="{{ url('/svg/support.svg') }}"
                                    style="width: 28px; margin-right:16px;">
                                <span>Support</span>
                            </button>
                        </a>
                    </div>
                </div>
                @if (Auth::user()->organization->license_alert_type)
                    @php $alertsArr = explode(',',Auth::user()->organization->license_alert_type); @endphp
                    @foreach ($alertsArr as $item)
                        <div class="row pr-5p pl-10 mt-auto min-msg">
                            <div class="col-sm-12 nopadding b-0">
                                <div class="parent-div-min">
                                    <div class="ml-20p pt-10 float-right mr-5p">
                                        <button class="push-btn push-me">
                                            <i class="fas fa-minus basic-color"></i>
                                        </button>
                                    </div>

                                    <div class="child-div-min"></div>

                                    <div class="mt-45n ml-20p">
                                        <a class="dark-color font-s">
                                            <b>
                                                {{ trans('variables.alerts.' . $item . '_link') }}
                                            </b>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row pr-5p pl-10 mt-auto max-msg hide">
                            <div class="col-sm-12 nopadding b-0">
                                <div class="parent-div-max">
                                    <div class="ml-20p pt-10 float-right mr-5p">
                                        <button class="push-btn pull-me">
                                            <i class="fas fa-minus basic-color"></i>
                                        </button>
                                    </div>

                                    <div class="child-div-max"></div>

                                    <div class="text-center mt-30n mr-10 ml-10">
                                        <h5 class="dark-color f-13">
                                            <b>{{ trans('variables.alerts.' . $item . '_title') }}</b>
                                        </h5>
                                        <h6 class="basic-color f-11">
                                            {{ trans('variables.alerts.' . $item . '_description') }}
                                        </h6>
                                        <a class="dark-color">
                                            <b>
                                                {{ trans('variables.alerts.' . $item . '_link') }}
                                            </b>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <!-- ============================================= -->
            <!-- center and right side -->
            <div class="nav-padding col-sm-10 main-content-div">
                <!-- ============================================= -->
                <!-- Page content -->
                <main class="py-4">

                    <div class="alert custom-swal-modal-success custom-success-oper success-oper" role="alert">
                        <div class="custom-swal-icon swal-icon--sucess">
                            <span class="swal-icon--success__line swal-icon--success__line--long"></span>
                            <span class="swal-icon--success__line swal-icon--success__line--tip"></span>
                        </div>
                        <div class="swal-title text-center">Done!</div>
                        <div class="success-msg mb-10 text-center"></div>
                    </div>

                    <div class="alert custom-swal-modal custom-danger-oper danger-oper" role="alert">
                        <div class="swal-icon swal-icon--error">
                            <div class="swal-icon--error__x-mark">
                                <span class="swal-icon--error__line swal-icon--error__line--left"></span>
                                <span class="swal-icon--error__line swal-icon--error__line--right"></span>
                            </div>
                        </div>
                        <div class="swal-title text-center">Error</div>
                        <div class="danger-msg mb-10 text-center"></div>
                    </div>

                    @yield('content')

                </main>
                <!-- ============================================= -->
            </div>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/smooth-scrollbar.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>
        $('[data-toggle="tooltip"]').tooltip();
        $(window).on('load', function() {
            $('.loading').delay(1000).animate({
                'opacity': '0'
            }, function() {
                $(this).css({
                    'display': 'none'
                });
                $('.removeScroll').removeClass('removeScroll');
            });
            detectMobile();
        });
        $(window).bind('beforeunload', function() {
            $('.loading').css("opacity", 100).css("display", "block");
            $('body').addClass('removeScroll');
        });

        function adjustTable() {
            setTimeout(
                function() {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust();
                }, 50);
        }
        $(function() {
            var cookie = getCookie('set-minimized');
            if (cookie == 1)
                minmizeSideBar();
            $('.logo-outer-div-max .side-nav-icon').click(function() {
                setCookie('set-minimized', 1);
                minmizeSideBar();
                adjustTable();
            });

            $('.logo-outer-div-min .side-nav-icon').click(function() {
                setCookie('set-minimized', 0);
                exapndSideBar();
                adjustTable();
            });

            $('.dataTables_filter input').unbind();
            // $('.dataTables_filter input').bind('keyup', function(e) {
            //     if (e.keyCode == 13)
            //         $("#" + $(this).attr("aria-controls")).DataTable().search(this.value).draw();
            // });
            $(document).on('keyup', '.dataTables_filter input', function(e) {
                if (e.keyCode == 13)
                    $("#" + $(this).attr("aria-controls")).DataTable().search(this.value).draw();
            });
            $(document).on('click', '.dataTables_filter .search-icon', function(e) {
                let input = $('.dataTables_filter .search-icon').closest('.search-container').siblings(
                    'input');
                let tableName = input.attr("aria-controls");
                $("#" + tableName).DataTable().search(input.val()).draw();
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

        function exapndSideBar() {
            $('.main-content-div').addClass('col-sm-10').removeClass('col-sm-12');
            $('.navbarLayout').addClass('col-sm-10').removeClass('width-94');
            $('.logo-outer-div-min').addClass('hideMenu');
            $('.leftNavBar-min').addClass('hideMenu');
            $('.logo-outer-div-max').removeClass('hideMenu');
            $('.leftNavBar-max').removeClass('hideMenu');
        }

        function setCookie(key, value, days = 7) {
            var expires = new Date();
            expires.setTime(expires.getTime() + 86400 * days); //1 year
            document.cookie = key + '=' + value + ';expires=' + expires.toUTCString() + '; path=/';
        }

        function getCookie(key) {
            var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
            return keyValue ? keyValue[2] : null;
        }


        function detectMobile() {
            window.setInterval(function() {
                let width = window.innerWidth;
                if (width < 1050) {
                    $('body').addClass('removeScroll-size');
                    $('#resizeError').removeClass('hide');
                } else {
                    $('body').removeClass('removeScroll-size');
                    $('#resizeError').addClass('hide');
                }
            }, 500);
        }

        $('.push-me').on('click', function() {
            $('.min-msg').addClass('hide');
            $('.max-msg').removeClass('hide');
        });

        $('.pull-me').on('click', function() {
            $('.max-msg').addClass('hide');
            $('.min-msg').removeClass('hide');
        });

        function showErrorMessage(message) {
            $(".danger-oper .danger-msg").html(message);
            $(".danger-oper").css("display", "block");
            setTimeout(function() {
                $(".danger-oper").css("display", "none");
            }, 8000);
        }
        //---------------------------------------------------//
        function showSuccessMessage(message) {
            $(".success-oper .success-msg").html(message);
            $(".success-oper").css("display", "block");
            setTimeout(function() {
                $(".success-oper").css("display", "none");
            }, 8000);
        }
        //---------------------------------------------------//
        //---------------------------------------------------//
    </script>
    <style>
        #resizeError {
            background-image: url(/images/404_error.png);
            background-size: cover;
            background-repeat: no-repeat;
            overflow: hidden;
            width: 100vw;
            height: 100vh;
            position: absolute;
            top: 0;
            z-index: 100;
        }
    </style>
    @stack("scripts")
</body>

</html>
