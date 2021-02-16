<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ url('/') }}/img/logo.png" alt="CSMC Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">Inventory <span class="text-warning">System</span></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <?php $name = auth()->user()->fname." ".auth()->user()->lname; ?>
            <div class="image">
                <img src="https://ui-avatars.com/api/?name={{ $name }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ $name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/po') }}" class="nav-link {{ request()->is('po*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i> Purchase Order
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-truck"></i> Deliveries
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-search-dollar"></i> Inspection & Acceptance
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-warehouse"></i> Inventory Storage
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-book"></i> Requisition
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-people-carry"></i> Issuance/Releasing
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-print"></i> Reporting
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-trash"></i> Disposal
                    </a>
                </li>
                <li class="nav-item has-treeview {{ request()->is('misc/*') ? ' menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('misc/*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p class="text-white">
                            Misc.
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ml-4">
                        <li class="nav-item">
                            <a href="{{ route('supplier') }}" class="nav-link {{ request()->is('misc/supplier') ? 'active' : '' }}">
                                <i class="fas fa-store-alt nav-icon"></i> Supplier
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-hand-holding-usd nav-icon"></i> Fund Cluster
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/misc/unit') }}" class="nav-link {{ request()->is('misc/unit') ? 'active' : '' }}">
                                <i class="fas fa-balance-scale-left nav-icon"></i> Unit Measure
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">ACCOUNT SETTINGS</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-lock-open nav-icon"></i> Change Password
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="fas fa-sign-out-alt nav-icon"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
