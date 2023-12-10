<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link text-center">
        <span class="brand-text font-weight-light" style="text-transform: uppercase; font-size:16px; font-weight:bold !important;">...</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Events
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                            <a href="{{ route('create.event') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create Event</p>
                            </a>
                            </li>
                            <li class="nav-item">
                            <a href="{{ route('events') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Events</p>
                            </a>
                            </li>
                        </ul>
                    </li>
                </li> --}}

                <li class="nav-item">
                    <a href="{{ route('summary') }}" class="nav-link">
                        <i class="nav-icon fas fa-chart-area"></i>
                        <p>Summary Report</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('load.logs') }}" class="nav-link">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Load Logs</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('commission.logs') }}" class="nav-link">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Commission Logs</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('withdraw.logs') }}" class="nav-link">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Withdrawal Logs</p>
                    </a>
                </li>

                <li class="nav-header">Players</li>
                
                <li class="nav-item">
                    <a href="{{ route('players') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                            <p>
                                Active Players
                            </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('pending.players') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-check"></i>
                            <p>
                                Approval Players
                            </p>
                    </a>
                </li>

                <li class="nav-header">Users</li>
                <li class="nav-item">
                    <a href="{{ route('user.accounts') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                                <p>
                                    All Accounts
                                </p>
                        </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('operators') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-secret"></i>
                        <p>Operators</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('sub.operators') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-secret"></i>
                        <p>Sub Operators</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('master.agents') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-secret"></i>
                        <p>Master Agents</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('agents') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Sub Agents</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('silver.agents') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Silver Agents</p>
                    </a>
                </li>



                <li class="nav-header">Settings</li>

                <li class="nav-item">
                    <a href="{{ route('password.update') }}" class="nav-link">
                        <i class="nav-icon fas fa-key"></i>
                        <p class="text">Change Password</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p class="text">Logout</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
