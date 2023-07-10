<div class="col-sm-12 top-bar">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- class="container-fluid" -->
        <div class="row row-nav">
            <div class="col-xs-6 pull-left">
                <button type="button" id="sidebarCollapse" class="btn profile-btn">
                    <i class="fa fa-user" aria-hidden="true"></i>
                </button>
                <span>{{ auth()->user()->first_name . ' ' . (auth()->user()->last_name ?: '') }}</span>

            </div>
            <div class="col-xs-6 pull-right">

                <a class="" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                    <p>Logout</p>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

            </div>
        </div>
    </nav>
</div>
