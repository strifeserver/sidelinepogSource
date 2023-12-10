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
            <li class="nav-header">GENERAL</li>
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
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
    </aside>
