@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/main.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/generalElement.css') }}" />

@section('topnav')
    <div class="col-sm-10 navbarLayout">
        <!-- Upper navbar -->
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm upperNavBar"> -->
        <ul class="ulNavbar">

            <!-- Authentication Links -->
            @include('layouts.authentication-links')
        </ul>

    </div> <!-- End of navbar -->
@endsection

@section('content')
    <!-- organization admin section-->
    <div class="home-row row">
        @if ($role->hasAnyPermission('view_repository'))
        <div class="col-sm-4 pb-4">
            <a href="repositories">
                <button class="btnMain">
                    <div class="btnUpMask"></div>
                    <div class="row" style="margin: 0!important">
                        <div class="col-sm-1 nopadding"></div>
                        <div class="col-sm-7 nopadding" style="text-align: left;">
                            <img class="iconColor" src="{{ url('/svg/r.svg') }}">
                            Repositories
                        </div>
                        <div class="col-sm-3 nopadding go-link-container">
                            <img class="iconColor" src="{{ url('/svg/dash-next.svg') }}">
                        </div>
                        <div class="col-sm-1 nopadding"></div>
                    </div>

                    <div class="btnDownMask"></div>
                </button>
            </a>
        </div>
        @endif
        @if ($role->hasAnyPermission('exchange_view_backup', 'onedrive_view_backup', 'sharepoint_view_backup', 'teams_view_backup'))
        <div class="col-sm-4 pb-4">
            <a href=".bck-sm" data-toggle="collapse" aria-expanded="false">
                <button class="btnMain">
                    <div class="btnUpMask"></div>
                    <div class="row" style="margin: 0!important">
                        <div class="col-sm-1 nopadding"></div>
                        <div class="col-sm-7 nopadding" style="text-align: left;">
                            <img class="iconColor" style="width: 28px;margin-right: 16px;"
                                src="{{ url('/svg/backup.svg') }}">
                            Backup Jobs
                        </div>
                        <div class="col-sm-3 nopadding go-link-container">
                            <img class="iconColor" src="{{ url('/svg/dash-next.svg') }}">
                        </div>
                        <div class="col-sm-1 nopadding"></div>
                    </div>

                    <div class="btnDownMask"></div>
                </button>
            </a>
        </div>
        @endif
        @if ($role->hasAnyPermission('exchange_create_restore_session', 'onedrive_create_restore_session', 'sharepoint_create_restore_session', 'teams_create_restore_session'))
        <div class="col-sm-4 pb-4">
            <a href=".res-sm" data-toggle="collapse" aria-expanded="false">
                <button class="btnMain">
                    <div class="btnUpMask"></div>
                    <div class="row" style="margin: 0!important">
                        <div class="col-sm-1 nopadding"></div>
                        <div class="col-sm-7 nopadding" style="text-align: left;">
                            <img class="iconColor" style="width: 28px;margin-right: 16px;"
                                src="{{ url('/svg/restore.svg') }}">
                            Restore
                        </div>
                        <div class="col-sm-3 nopadding go-link-container">
                            <img class="iconColor" src="{{ url('/svg/dash-next.svg') }}">
                        </div>
                        <div class="col-sm-1 nopadding"></div>
                    </div>
                    <div class="btnDownMask"></div>
                </button>
            </a>
        </div>
        @endif
        @if ($role->hasAnyPermission('exchange_view_history', 'onedrive_view_history', 'sharepoint_view_history', 'teams_view_history'))
        <div class="col-sm-4 pb-4">
            <a href=".res-his-sm" data-toggle="collapse" aria-expanded="false">
                <button class="btnMain">
                    <div class="btnUpMask"></div>
                    <div class="row" style="margin: 0!important">
                        <div class="col-sm-1 nopadding"></div>
                        <div class="col-sm-7 nopadding" style="text-align: left;">
                            <img class="iconColor" style="width: 28px;margin-right: 16px;"
                                src="{{ url('/svg/history.svg') }}">
                            Restore History
                        </div>
                        <div class="col-sm-3 nopadding go-link-container">
                            <img class="iconColor" src="{{ url('/svg/dash-next.svg') }}">
                        </div>
                        <div class="col-sm-1 nopadding"></div>
                    </div>
                    <div class="btnDownMask"></div>
                </button>
            </a>
        </div>
        @endif
        <div class="col-sm-4 pb-4">
            <a href="https://support.ctelecoms.com.sa/" target="_blank">
                <button class="btnMain">
                    <div class="btnUpMask"></div>
                    <div class="row" style="margin: 0!important">
                        <div class="col-sm-1 nopadding"></div>
                        <div class="col-sm-7 nopadding" style="text-align: left;">
                            <img class="iconColor" style="width: 28px;margin-right: 16px;"
                                src="{{ url('/svg/support.svg') }}">
                            Support
                        </div>
                        <div class="col-sm-3 nopadding go-link-container">
                            <img class="iconColor" src="{{ url('/svg/dash-next.svg') }}">
                        </div>
                        <div class="col-sm-1 nopadding"></div>
                    </div>

                    <div class="btnDownMask"></div>
                </button>
            </a>
        </div>
    </div>



    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
@endsection
