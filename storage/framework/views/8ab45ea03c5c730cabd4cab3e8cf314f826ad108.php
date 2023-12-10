<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/eagles.png')); ?>" />
    <?php echo $__env->make('partials._head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="stylesheet" href="<?php echo e(asset('bower_components/admin-lte/plugins/sweetalert2/sweetalert2.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('bower_components/admin-lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* div,span,p,h1,h2,h3,h4,h5,h6,li,a,button{
            font-family: 'Chakra Petch', sans-serif !important;
        } */
        .input-group-text {
            border-color: #343a40 !important;
        }
        .container-fluid{
            padding-right: 0px !important;
            padding-left: 0px !important;
        }
    </style>
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body class="control-sidebar-slide-open layout-navbar-fixed layout-footer-fixed dark-mode text-sm sidebar-collapse">
<div class="wrapper">
  <!-- Navbar -->
    <?php echo $__env->make('partials._topbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
    <?php echo $__env->make('partials.menus._player', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="">
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
<?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/layouts/client.blade.php ENDPATH**/ ?>