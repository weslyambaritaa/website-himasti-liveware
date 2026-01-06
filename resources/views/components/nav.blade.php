<nav class="sidebar-nav scroll-sidebar" data-simplebar>
    <ul id="sidebarnav">

        <!-- ---------------------------------- -->
        <!-- Main -->
        <!-- ---------------------------------- -->
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Main</span>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('app.beranda') ? 'active' : '' }}"
                href="{{ route('app.beranda') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-home"></i>
                </span>
                <span class="hide-menu">Beranda</span>
            </a>
        </li>

        <!-- ---------------------------------- -->
        <!-- SPM Area -->
        <!-- ---------------------------------- -->
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">SPM Area</span>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('app.tim') ? 'active' : '' }}" href="{{ route('app.tim') }}"
                aria-expanded="false">
                <span>
                    <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Tim SPM</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('app.standar') ? 'active' : '' }}"
                href="{{ route('app.tim') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-notebook"></i>
                </span>
                <span class="hide-menu">Standar</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('app.unit') ? 'active' : '' }}" href="{{ route('app.tim') }}"
                aria-expanded="false">
                <span>
                    <i class="ti ti-buildings"></i>
                </span>
                <span class="hide-menu">Daftar Unit</span>
            </a>
        </li>

        @if (in_array('Admin', $auth->roles) || in_array('Pengguna', $auth->akses))
            <li class="sidebar-item">
                <a class="sidebar-link {{ request()->routeIs('app.hak-akses') ? 'active' : '' }}"
                    href="{{ route('app.hak-akses') }}" aria-expanded="false">
                    <span>
                        <i class="ti ti-lock"></i>
                    </span>
                    <span class="hide-menu">Hak Akses</span>
                </a>
            </li>
        @endif


    </ul>
</nav>
