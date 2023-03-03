<nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <div class="nav navbar-nav flex-row">
                <a href="#" class="navbar-brand " style="padding: 17px 8px;">
                    <img src="{{ asset('backend/images/icons/Bridge_logo_yok.png') }}" width="130px" height="20px"
                        alt="" class="brand-logo">
                </a>
            </div>
        </div>
        <div class="nav navbar-nav float-right logout">
            <a href="{{ url('admin/logout') }}" class="btn-logout text-center" id="logout">Logout</a>
        </div>
    </div>
</nav>

