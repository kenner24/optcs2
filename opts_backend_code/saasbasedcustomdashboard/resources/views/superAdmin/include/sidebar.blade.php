@push('style')
    .user-panel img {
    height: 40px;
    width: 40px;
    }
@endpush
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <!-- <a href="index3.html" class="brand-link">
        <img src="{{ asset('admin/images/AdminLTELogo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Saas</span>
    </a> -->
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if (!empty(auth()->user()->image))
                    <img src="{{ asset(auth()->user()->image) }}" class="img-circle elevation-2" alt="User Image">
                @else
                    <img src="{{ asset('admin/dummy.jpeg') }}" class="img-circle elevation-2" alt="User Image">
                @endif
            </div>
            <div class="info text-center" style="color:white;">
                {{ auth()->user()->name }}
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ url('/admin/dashboard') }}"
                        class="nav-link @if (request()->is('/admin/dashboard')) active @endif">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>
                            Admin Dashboard
                            <!-- <i class="right fas fa-angle-left"></i> -->
                        </p>
                    </a>
                </li>

                <li class="nav-item @if (request()->is('admin/company-list') ||
                        request()->is('admin/user-staff-list') ||
                        request()->is('admin/user-staff-permissions')) menu-open @endif">
                    <a href="#" class="nav-link @if (request()->is('admin/company-list') ||
                            request()->is('admin/user-staff-list') ||
                            request()->is('admin/user-staff-permissions')) active @endif">
                        <i class="fas fa-users"></i>
                        <p>
                            Company Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ url('/admin/company-list') }}"
                                class="nav-link @if (request()->is('admin/company-list') || request()->is('admin/user-staff-list')) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Company List</p>
                            </a>
                        </li>

                        <!-- <li class="nav-item">
                            <a href="{{ url('/admin/activity') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Logs and activity</p>
                            </a>
                        </li> -->

                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-dollar-sign"></i>
                        <p>
                            Subscription Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('subscription.plan.list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Plan List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('subscription') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Subscriptions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('subscription.logs') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Subscriptions Logs</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-chart-bar"></i>
                        <p>
                            Analytics and Reporting
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Users Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Subscription Report</p>
                            </a>
                        </li>
                    </ul>
                </li> -->

                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fa fa-question-circle"></i>
                        <p>
                            Support and Helpdesk
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tickets List</p>
                            </a>
                        </li>
                    </ul>
                </li> -->

                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-sitemap"></i>
                        <p>
                            Integration and API Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>List</p>
                            </a>
                        </li>
                    </ul>
                </li> -->
                <li class="nav-item">
                    <a href="{{ route('admin.website-management.view') }}"
                        class="nav-link @if (request()->is('/admin/pages')) active @endif">
                        <i class="fas fa-pager"></i>
                        <p>
                            Website Page Managment

                        </p>
                    </a>
                </li>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
