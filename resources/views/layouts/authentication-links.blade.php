<div class="rightNabarElement">
    <li class="liNavbar">
        <a>
            License used: <span
                class="txt-ctelecoms1">{{ auth()->user()->organization->veeam_licensed_users + auth()->user()->organization->veeam_trial_users }}</span>
        </a>
    </li>
    <li class="liNavbar">
        <a>
            License Expiry Date: <span class="txt-ctelecoms1">{{ auth()->user()->organization->license_expiry_date }}</span>
        </a>
    </li>
    @if(!auth()->check())
        <li class="liNavbar">
            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
        </li>
        @if (Route::has('register'))
            <li class="liNavbar">
                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
        @endif
    @else
        <li class="liNavbar dropdown user-nav">
            <a id="navbarDropdown" class="nav-link " href="#user-menu" data-toggle="collapse" aria-expanded="false"
                role="button">
                <span class="fa fa-user"></span>{{ auth()->user()->first_name }}
                <span class="caret"></span>
            </a>
        </li>
        <li class="liNavbar user-nav">
            <a class="navbarDropdown" href="{{ route('logout') }}"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <span class="fa fa-sign-out-alt"></span>{{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    @endguest
    <div id='user-menu' class="collapse p-0" aria-expanded="false">
        <a href="{{ url('bactopus-settings') }}"
            class="block p-2 text-left">
            Bactopus Settings
        </a>
        @if ($role->hasAnyPermission('users_view', 'role_view'))
        <a href="{{ url('users-roles') }}"
            class="block p-2 text-left">
            Users Management
        </a>
        @endif
    </div>
</div>
