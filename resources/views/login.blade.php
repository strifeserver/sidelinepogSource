<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>..</title>
  <link rel="icon" type="image/png" href="{{ asset('images/gradas.png') }}" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('bower_components/admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('bower_components/admin-lte/dist/css/adminlte.min.css') }}">

  <style>
    body{
        background-image: url("{{asset('images/bg2.webp')}}");
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    }
  </style>
</head>
<body class="login-page dark-mode">
    <div class="login-box">
        <div class="login-logo">
            {{-- <img src="{{ asset('images/gg-roosters.png') }}" class="img-fluid" alt=""> --}}
        </div>
        <!-- /.login-logo -->
        <div class="card">
          <div class="card-body login-card-body">
            {{-- <p class="login-box-msg">Sign in to start playing</p> --}}
              @include('partials._message')
            <form action="{{ route('g.login') }}" method="post">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="username" placeholder="Username">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-user"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                {{ csrf_field() }}
                <!-- /.col -->
                <div class="col-md-12">
                  <button type="submit" class="btn btn-success btn-block">Sign In</button>
                </div>
                <!-- /.col -->
              </div>
            </form>

            {{-- <p class="mt-3 mb-2 text-center">
              No account yet? <a href="#">Contact Us</a>
            </p> --}}
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
