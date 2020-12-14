<!-- Main sidebar -->
<div class="sidebar sidebar-dark sidebar-main  sidebar-fixed sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->


    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                {{-- <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu"
                        title="Main"></i>
                </li> --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->segment(2) == 'home' ? 'active' : '' }}">
                        <i class="icon-home4"></i>
                        <span>
                            Dashboard
                        </span>
                    </a>
                </li>
                <li
                    class="nav-item nav-item-submenu {{ in_array(request()->segment(2), ['user'])  ? 'nav-item-open nav-item-expanded' : '' }}">
                    <a href="#" class="nav-link"><i class="icon-user"></i> <span>User Management</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                        <li class="nav-item"><a href="{{ route('user.index') }}"
                                class="nav-link {{ request()->segment(2) == 'user' ? 'active' : '' }}">Users</a>
                        </li>
                    </ul>
                </li>   
                <li
                    class="nav-item nav-item-submenu {{ in_array(request()->segment(2), ['hr'])  ? 'nav-item-open nav-item-expanded' : '' }}">
                    <a href="#" class="nav-link"><i class="icon-users"></i> <span>HR Management</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                        <li class="nav-item"><a href="{{ route('employee.index') }}"
                                class="nav-link {{ request()->segment(3) == 'employee' ? 'active' : '' }}">Employee</a>
                        </li>
                        <li class="nav-item"><a href="{{ route('departement.index') }}"
                                class="nav-link {{ request()->segment(3) == 'departement' ? 'active' : '' }}">Departement</a>
                        </li>
                    </ul>
                </li>   
                <li class="nav-item">
                    <a href="{{ route('car_request.index') }}"
                        class="nav-link {{ request()->segment(2) == 'request' ? 'active' : '' }}">
                        <i class="icon-car"></i>
                        <span>
                            Car Request
                        </span>
                    </a>
                </li>
                <li
                class="nav-item nav-item-submenu {{ in_array(request()->segment(2), ['maintenance'])  ? 'nav-item-open nav-item-expanded' : '' }}">
                <a href="#" class="nav-link"><i class="icon-cogs"></i> <span>Maintenance</span></a>

                <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                    <li class="nav-item"><a href="{{ route('mobil.index') }}"
                            class="nav-link {{ request()->segment(3) == 'mobil' ? 'active' : '' }}">Mobil</a>
                    </li>
                    <li class="nav-item"><a href="{{ route('supir.index') }}"
                            class="nav-link {{ request()->segment(3) == 'supir' ? 'active' : '' }}">Supir</a>
                    </li>
                </ul>
            </li>   
                
                <!-- /main -->
            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>
<!-- /main sidebar -->
