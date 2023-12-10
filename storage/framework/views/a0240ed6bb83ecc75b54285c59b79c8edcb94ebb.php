<!DOCTYPE html>
<html lang="en">
   <head>
      <title>...</title>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
      <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700%7CSource+Sans+Pro:400,700" rel="stylesheet">
      <link rel="stylesheet" href="<?php echo e(asset('bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css')); ?>">
      <link href="<?php echo e(asset('data-login/bootstrap.css')); ?>" rel="stylesheet">
      <link href="<?php echo e(asset('data-login/all.min.css')); ?>" rel="stylesheet">
      <link href="<?php echo e(asset('data-login/v4-shims.min.css')); ?>" rel="stylesheet">
      <link href="<?php echo e(asset('data-login/style-basketball-dark.css')); ?>" rel="stylesheet">
      
      <style type="text/css">
         .loginBtn {
            background-color: #58D68D
         }

         body {
            background-image: url("<?php echo e(asset('data-login/bg2.jpg')); ?>");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            color: #9a9da2;
            font-size: 15px;
            line-height: 26px;
            font-family: Source Sans Pro,sans-serif;
            font-weight: 400;
        }
      </style>
   </head>
   <body data-template="template-basketball">
      <div class="site-wrapper clearfix">
         <div class="site-overlay"></div>
         <div class="site-content img-bg">
            <div class="container">
               <div class="row">
                  <div class="col-md-6 text-center justify-content-center align-items-center"></div>
                  <div class="col-md-6">
                     <div class="card" style="background: transparent !important; border: 1px solid #717D7E;">
                        <div class="card__header" style="border-bottom: 1px solid #717D7E;">
                           <h4><i class="fa fa-key"></i> SIGN-IN ACCOUNT TO CONTINUE...</h4>
                        </div>
                        <div class="card__content">
                            <form action="<?php echo e(route('post.login')); ?>" method="post">
                                <div class="loginMessage" style="color: #FFF !important">
                                    <?php echo $__env->make('partials._message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                <div>
                                   <div class="form-group">
                                      <label for="login-name"><i class="fas fa-user"></i> YOUR Username</label>
                                      <input type="text" class="form-control" name="email" style="color: #FFCC00; font-weight: bold" placeholder="Enter your username...">
                                   </div>
                                   <div class="form-group">
                                      <label for="login-password"><i class="fas fa-lock"></i> YOUR Password</label>
                                      <input type="password" class="form-control" name="password" style="color: #FFCC00; font-weight: bold" placeholder="Enter your password...">
                                   </div>
                                   <?php echo e(csrf_field()); ?>

                                   <div class="form-group form-group--sm">
                                      <button class="btn btn-primary btn-lg btn-block" type="submit"><i class="fa fa-sign-in"></i> SIGN-IN Account</button>
                                   </div>
                                </div>
                            </form>

                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <script src="<?php echo e(asset('data-login/jquery-3.1.1.min.js')); ?>"></script>
      <script src="<?php echo e(asset('data-login/popper.min.js')); ?>"></script>
      <script src="<?php echo e(asset('data-login/bootstrap.min.js')); ?>"></script>
   </body>
</html>
<?php /**PATH G:\xampp\htdocs\project0source\resources\views/login2.blade.php ENDPATH**/ ?>