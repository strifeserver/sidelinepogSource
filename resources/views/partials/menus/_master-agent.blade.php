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

                <li class="nav-header">Points</li>
                <li class="nav-item">
                    <a href="{{ route('wallet') }}" class="nav-link">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p class="text">Wallet</p>
                    </a>
                </li>

                <li class="nav-header">Players</li>
                <li class="nav-item">
                    <a href="{{ route('agents') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Agents</p>
                    </a>
                </li>
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

                <li class="nav-item">
                    <a href="{{ route('deleted.accounts') }}" class="nav-link">
                        <i class="nav-icon fas fa-users-slash"></i>
                            <p>
                                Deleted Players
                            </p>
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
