<!-- Sidebar -->
<!--
    Helper classes

    Adding .sidebar-mini-hide to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
    Adding .sidebar-mini-show to an element will make it visible (opacity: 1) when the sidebar is in mini mode
        If you would like to disable the transition, just add the .sidebar-mini-notrans along with one of the previous 2 classes

    Adding .sidebar-mini-hidden to an element will hide it when the sidebar is in mini mode
    Adding .sidebar-mini-visible to an element will show it only when the sidebar is in mini mode
        - use .sidebar-mini-visible-b if you would like to be a block when visible (display: block)
-->
<nav id="sidebar">
    <!-- Sidebar Content -->
    <div class="sidebar-content">
        <!-- Side Header -->
        <div class="content-header content-header-fullrow px-15">
            <!-- Mini Mode -->
            <div class="content-header-section sidebar-mini-visible-b">
                <!-- Logo -->
                <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                    <span class="text-dual-primary-dark">h</span><span class="text-primary">a</span>
                </span>
                <!-- END Logo -->
            </div>
            <!-- END Mini Mode -->

            <!-- Normal Mode -->
            <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                <!-- Close Sidebar, Visible only on mobile screens -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                    <i class="fa fa-times text-danger"></i>
                </button>
                <!-- END Close Sidebar -->

                <!-- Logo -->
                <div class="content-header-item">
                    <a class="link-effect font-w700" href="/">
                        <i class="si si-energy text-primary"></i>
                        <span class="font-size-xl text-dual-primary-dark">harvester</span><span class="font-size-xl text-primary">App</span>
                    </a>
                </div>
                <!-- END Logo -->
            </div>
            <!-- END Normal Mode -->
        </div>
        <!-- END Side Header -->

        <!-- Sidebar Scrolling -->
        <div class="js-sidebar-scroll">
            <!-- Side User -->
            <div class="content-side content-side-full content-side-user px-10 align-parent">
                <!-- Visible only in mini mode -->
                <div class="sidebar-mini-visible-b align-v animated fadeIn">
                    <img class="img-avatar img-avatar32" src="{{ asset('media/avatars/avatar15.jpg') }}" alt="">
                </div>
                <!-- END Visible only in mini mode -->

                <!-- Visible only in normal mode -->
                <div class="sidebar-mini-hidden-b text-center">
                    <a class="img-link" href="javascript:void(0)">
                        <img class="img-avatar" src="{{ asset('media/avatars/avatar15.jpg') }}" alt="">
                    </a>
                    <ul class="list-inline mt-10">
                        <li class="list-inline-item">
                            <a class="link-effect text-dual-primary-dark font-size-sm font-w600 text-uppercase" href="javascript:void(0)">
                                blue
                            </a>
                        </li>
                        <?php /*
                        <li class="list-inline-item">
                            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                            <a class="link-effect text-dual-primary-dark" data-toggle="layout" data-action="sidebar_style_inverse_toggle" href="javascript:void(0)">
                                <i class="si si-drop"></i>
                            </a>
                        </li>
                        */ ?>
                        <li class="list-inline-item">
                            <a class="link-effect text-dual-primary-dark" href="/">
                                <i class="si si-settings"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a class="link-effect text-dual-primary-dark" href="/" onclick="return confirm('Logout?')">
                                <i class="si si-logout"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- END Visible only in normal mode -->
            </div>
            <!-- END Side User -->

            <!-- Side Navigation -->
            <div class="content-side content-side-full">
                <ul class="nav-main">
                    <li>
                        <a class="{{ request()->is('dashboard') ? ' active' : '' }}" href="/dashboard">
                            <i class="si si-rocket"></i><span class="sidebar-mini-hide">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-main-heading">
                        <span class="sidebar-mini-visible">ST</span><span class="sidebar-mini-hidden">Stages</span>
                    </li>
                    <li>
                        <a class="{{ request()->is('schedule') ? ' active' : '' }}" href="/schedule">
                            <i class="si si-calendar"></i><span class="sidebar-mini-hide">Schedule</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->is('schedule/history') ? ' active' : '' }}" href="/schedule/history">
                            <i class="si si-clock"></i><span class="sidebar-mini-hide">History</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->is('schedule/settings') ? ' active' : '' }}" href="/schedule/settings">
                            <i class="si si-settings"></i><span class="sidebar-mini-hide">Settings</span>
                        </a>
                    </li>
                    <li class="nav-main-heading">
                        <span class="sidebar-mini-visible">GR</span><span class="sidebar-mini-hidden">Growroom</span>
                    </li>
                    <li class="{{ request()->is('pages/*') ? ' open' : '' }}">
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
                            <i class="si si-bulb"></i><span class="sidebar-mini-hide">Lighting</span>
                        </a>
                        <ul>
                            <li>
                                <a class="{{ request()->is('pages/datatables') ? ' active' : '' }}" href="/pages/datatables">Data</a>
                            </li>
                            <li>
                                <a class="{{ request()->is('pages/slick') ? ' active' : '' }}" href="/pages/slick">Media</a>
                            </li>
                            <li>
                                <a class="{{ request()->is('pages/blank') ? ' active' : '' }}" href="/pages/blank">Blank</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
                            <i class="si si-chart"></i><span class="sidebar-mini-hide">Sensors</span>
                        </a>
                        <ul>
                            <li>
                                <a class="{{ request()->is('pages/blank') ? ' active' : '' }}" href="/pages/blank">Blank</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
                            <i class="si si-wrench"></i><span class="sidebar-mini-hide">Devices</span>
                        </a>
                        <ul>
                            <li>
                                <a class="{{ request()->is('pages/blank') ? ' active' : '' }}" href="/pages/blank">Blank</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="{{ request()->is('settings') ? ' active' : '' }}" href="/settings">
                            <i class="si si-settings"></i><span class="sidebar-mini-hide">Settings</span>
                        </a>
                    </li>
                    <li class="nav-main-heading">
                        <span class="sidebar-mini-visible">DB</span><span class="sidebar-mini-hidden">Database</span>
                    </li>
                    <li>
                        <a class="{{ request()->is('db') ? ' active' : '' }}" href="/db">
                            <i class="si si-list"></i><span class="sidebar-mini-hide">Database</span>
                        </a>
                    </li>
                    <li class="nav-main-heading">
                        <span class="sidebar-mini-visible">+</span><span class="sidebar-mini-hidden">More</span>
                    </li>
                    <li>
                        <a href="https://scriptlabs.de/harvester-app" target="_blank">
                            <i class="si si-globe"></i><span class="sidebar-mini-hide">FAQ</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://scriptlabs.de/harvester-app/help" target="_blank">
                            <i class="si si-globe"></i><span class="sidebar-mini-hide">Help</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- END Side Navigation -->
        </div>
        <!-- END Sidebar Scrolling -->
    </div>
    <!-- Sidebar Content -->
</nav>
<!-- END Sidebar -->
