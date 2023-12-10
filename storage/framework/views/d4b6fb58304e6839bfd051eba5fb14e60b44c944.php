<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create Account</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo e(asset('bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css')); ?>">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo e(asset('bower_components/admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')); ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo e(asset('bower_components/admin-lte/dist/css/adminlte.min.css')); ?>">

  <style>
    .login-page{
        justify-content: normal !important;
    }
    .login-box{
        width: 460px;
    }
    .card-header:before {
        content: "";
        display: block;
        position: absolute;
        width: 6px;
        left: 0;
        top: 0;
        bottom: 0;
        background-color: #f34141;
        border-radius: 3px 0 0 0;
    }

    @media  only screen and (max-width: 768px) {
        .login-box{
            width: 360px;
        }
    }
  </style>
</head>
<body class="login-page dark-mode">
    <div class="login-box">
        <div class="login-logo">
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-header">
                <p class="login-box-msg text-uppercase text-left">Already have an account?</p>
            </div>
            <div class="card-body login-card-body">

              <p class="mt-3 mb-2 text-center">
                <a href="<?php echo e(route('home')); ?>" class="btn btn-success btn-block">CLICK HERE TO LOGIN</a>
              </p>
            </div>
            <!-- /.login-card-body -->
        </div>
        <div class="text-center">
            <h3 class="text-primary">OR</h3>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <p class="login-box-msg text-uppercase text-left">Create Your Player Account</p>
            </div>
          <div class="card-body login-card-body">
              <?php echo $__env->make('partials._message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <form action="<?php echo e(route('create.account')); ?>" method="post">

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Name">
                        <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-address-card"></span>
                        </div>
                        </div>
                    </div>

                    <input type="hidden" class="form-control" value="<?php echo e($user->id); ?>" name="referred_by">
                    <input type="hidden" class="form-control" value="<?php echo e($user->id); ?>" name="created_by">
                    <input type="hidden" class="form-control" value="<?php echo e($user->type); ?>" name="referrer_type">

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username">
                        <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="number" class="form-control" name="contact_number" placeholder="Contact Number">
                        <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-phone"></span>
                        </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                        <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php echo e(csrf_field()); ?>

                        <!-- /.col -->
                        <div class="col-md-12">
                        <button type="submit" class="btn btn-danger float-right">REGISTER</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
          </div>
          <!-- /.login-card-body -->
        </div>
    </div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo e(asset('bower_components/admin-lte/plugins/jquery/jquery.min.js')); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo e(asset('bower_components/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo e(asset('bower_components/admin-lte/dist/js/adminlte.min.js')); ?>"></script>
</body>
</html>
<?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/client/register.blade.php ENDPATH**/ ?>