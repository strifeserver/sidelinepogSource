<nav class="main-header navbar navbar-expand navbar-dark">
<!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars topbar-links"></i></a>
        </li>
    </ul>
    <?php if(Auth::user()->type == "player" || Auth::user()->type == "booster"): ?>
        
    <?php endif; ?>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-user topbar-links"></i>
            <?php echo e(Auth::user()->username); ?>

        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-divider"></div>
                
                <a href="<?php echo e(route('password.update')); ?>" class="dropdown-item">Change Password</a>
                <div class="dropdown-divider"></div>
                <a href="<?php echo e(route('logout')); ?>" class="dropdown-item"> Logout</a>
            </div>
        </li>
    </ul>
</nav>
<?php /**PATH G:\xampp\htdocs\Sideline\sabong-orig\resources\views/partials/_topbar.blade.php ENDPATH**/ ?>