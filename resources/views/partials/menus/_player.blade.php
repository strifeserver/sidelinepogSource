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
            <li class="nav-header">MENU</li>
            <li class="nav-item">
                <a href="{{ route('select.game') }}" class="nav-link">
                    <i class="nav-icon fas fa-play"></i>
                    <p>PLAY</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('player.txs') }}" class="nav-link">
                    <i class="nav-icon fas fa-history"></i>
                    <p class="text">HISTORY</p>
                </a>
            </li>

        </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
    </aside>
