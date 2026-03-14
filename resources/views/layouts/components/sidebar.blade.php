<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard') : route('marketing.dashboard') }}" class="logo">
                <img src="{{ asset('assets/img/kaiadmin/logo_light.svg') }}" class="navbar-brand" height="20">
            </a>

            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>

            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                @if(auth()->user()->role == 'admin')
                    <li class="nav-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-home"></i>
                            <p>Dashboard Admin</p>
                        </a>
                    </li>

                    <li class="nav-item {{ Route::is('users.*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}">
                            <i class="fas fa-users"></i>
                            <p>Manajemen User</p>
                        </a>
                    </li>

                    <li class="nav-item {{ Route::is('produk.*') ? 'active' : '' }}">
                        <a href="{{ route('produk.index') }}">
                            <i class="fas fa-box"></i>
                            <p>Produk</p>
                        </a>
                    </li>

                    <li class="nav-item {{ Route::is('targets.*') ? 'active' : '' }}">
                        <a href="{{ route('targets.index') }}">
                            <i class="fas fa-bullseye"></i>
                            <p>Target</p>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role == 'marketing')
                    <li class="nav-item {{ Route::is('marketing.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('marketing.dashboard') }}">
                            <i class="fas fa-home"></i>
                            <p>Dashboard Marketing</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item {{ Route::is('konsumen.*') ? 'active' : '' }}">
                    <a href="{{ route('konsumen.index') }}">
                        <i class="fas fa-user-friends"></i>
                        <p>Data Konsumen</p>
                    </a>
                </li>

                <li class="nav-item {{ Route::is('transaksi.*') ? 'active' : '' }}">
                    <a href="{{ route('transaksi.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Transaksi</p>
                    </a>
                </li>

                <li class="nav-item {{ Route::is('followups.*') ? 'active' : '' }}">
                    <a href="{{ route('followups.index') }}">
                        <i class="fas fa-phone"></i>
                        <p>Follow Up</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>