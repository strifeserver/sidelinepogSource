<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/eagles.png')); ?>" />
    <?php echo $__env->make('partials._head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="stylesheet" href="<?php echo e(asset('bower_components/admin-lte/plugins/sweetalert2/sweetalert2.min.css')); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* div,span,p,h1,h2,h3,h4,h5,h6,li,a,button{
            font-family: 'Chakra Petch', sans-serif !important;
        } */
        .nav-tabs .nav-item .nav-link {
            font-size: 13px !important;
        }

        .topbar-links{
            color: #ffffff !important;
        }
    </style>
    <?php echo $__env->yieldContent('styles'); ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
    <!-- Navbar -->
        <?php echo $__env->make('partials._topbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
        <?php if(Auth::user()->type == 'super-admin'): ?>
            <?php echo $__env->make('partials.menus._super-admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php elseif(Auth::user()->type == 'admin'): ?>
            <?php echo $__env->make('partials.menus._admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php elseif(Auth::user()->type == 'operator'): ?>
            <?php echo $__env->make('partials.menus._operator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php elseif(Auth::user()->type == 'sub-operator'): ?>
            <?php echo $__env->make('partials.menus._sub-operator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php elseif(Auth::user()->type == 'master-agent'): ?>
            <?php echo $__env->make('partials.menus._master-agent', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php elseif(Auth::user()->type == 'gold-agent'): ?>
            <?php echo $__env->make('partials.menus._gold-agent', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php elseif(Auth::user()->type == 'silver-agent'): ?>
            <?php echo $__env->make('partials.menus._silver-agent', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php elseif(Auth::user()->type == 'declarator'): ?>
            <?php echo $__env->make('partials.menus._declarator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php elseif(Auth::user()->type == 'player'): ?>
            <?php echo $__env->make('partials.menus._player', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <?php echo $__env->make('partials._message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
        <div class="container-fluid">
            <?php echo $__env->yieldContent('content'); ?>
        </div><!--/. container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
        
    </div>
<!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <?php echo $__env->make('partials._js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script src="<?php echo e(asset('bower_components/admin-lte/plugins/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <?php echo $__env->yieldContent('scripts'); ?>

</body>
</html>
<?php /**PATH G:\xampp\htdocs\project0source\resources\views/layouts/admin.blade.php ENDPATH**/ ?>