o super<aside class="main-sidebar sidebar-dark-primary elevation-4">
<!-- Brand Logo -->
<a href="#" class="brand-link text-center">
    <span class="brand-text font-weight-light" style="text-transform: uppercase; font-size:16px; font-weight:bold !important;">
        ...
    </span>
</a>
<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header">GENERAL</li>
        <li class="nav-item">
            <a href="<?php echo e(route('dashboard')); ?>" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>

        <li class="nav-item">
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
                    <a href="<?php echo e(route('create.event')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Create Event</p>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a href="<?php echo e(route('events')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>All Events</p>
                    </a>
                    </li>
                </ul>
            </li>
        </li>

        

        <li class="nav-header">Points</li>
        <li class="nav-item">
            <a href="<?php echo e(route('wallet')); ?>" class="nav-link">
                <i class="nav-icon fas fa-wallet"></i>
                <p class="text">Wallet</p>
            </a>
        </li>

        <li class="nav-header">Players</li>
        <li class="nav-item">
            <a href="<?php echo e(route('user.accounts')); ?>" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                    <p>
                        All Accounts
                    </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo e(route('players')); ?>" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                    <p>
                        Active Players
                    </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo e(route('pending.players')); ?>" class="nav-link">
                <i class="nav-icon fas fa-user-check"></i>
                    <p>
                        Approval Players
                    </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo e(route('admins')); ?>" class="nav-link">
                <i class="nav-icon fas fa-user-secret"></i>
                <p>Admins</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo e(route('operators')); ?>" class="nav-link">
                <i class="nav-icon fas fa-user-secret"></i>
                <p>Operators</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo e(route('sub.operators')); ?>" class="nav-link">
                <i class="nav-icon fas fa-user-secret"></i>
                <p>Sub Operators</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo e(route('master.agents')); ?>" class="nav-link">
                <i class="nav-icon fas fa-user-secret"></i>
                <p>Master Agents</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo e(route('agents')); ?>" class="nav-link">
                <i class="nav-icon fas fa-user-tie"></i>
                <p>Sub Agents</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo e(route('silver.agents')); ?>" class="nav-link">
                <i class="nav-icon fas fa-user-tie"></i>
                <p>Silver Agents</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo e(route('declarators')); ?>" class="nav-link">
                <i class="nav-icon fas fa-user-clock"></i>
                <p>Declarators</p>
            </a>
        </li>

        <li class="nav-header">Settings</li>
        <li class="nav-item">
            <a href="<?php echo e(route('settings')); ?>" class="nav-link">
                <i class="nav-icon fas fa-cogs"></i>
                <p class="text">Settings</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo e(route('password.update')); ?>" class="nav-link">
                <i class="nav-icon fas fa-key"></i>
                <p class="text">Change Password</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo e(route('logout')); ?>" class="nav-link">
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
<?php /**PATH G:\xampp\htdocs\project0source\resources\views/partials/menus/_super-admin.blade.php ENDPATH**/ ?>