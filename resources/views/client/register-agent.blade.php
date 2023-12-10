<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create Account</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('bower_components/admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('bower_components/admin-lte/dist/css/adminlte.min.css') }}">

  <style>
    .login-box{
        width: 360px;
    }
  </style>
</head>
<body class="login-page">
    <div class="login-box">
        <div class="login-logo">
        </div>
        <!-- /.login-logo -->
        <div class="card">
          <div class="card-body login-card-body">
            <p class="login-box-msg">Create Your Agent Account</p>
              @include('partials._message')
                <form action="{{ route('create.account') }}" method="post">

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Name">
                        <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-address-card"></span>
                        </div>
                        </div>
                    </div>

                    <input type="hidden" class="form-control" value="{{$user->id}}" name="referred_by">
                    <input type="hidden" class="form-control" value="{{$user->id}}" name="created_by">
                    <input type="hidden" class="form-control" value="agent" name="type">

                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email">
                        <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                        </div>
                    </div>

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
                        <input type="password_confirmation" class="form-control" name="password" placeholder="Confirm Password">
                        <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                        </div>
                    </div>

                    <div class="row">
                        {{ csrf_field() }}
                        <!-- /.col -->
                        <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-block">Create Account</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

            <p class="mt-3 mb-2 text-center">
              <a href="{{ route('home') }}">Login</a>
            </p>
          </div>
          <!-- /.login-card-body -->
        </div>
    </div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('bower_components/admin-lte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('bower_components/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('bower_components/admin-lte/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
