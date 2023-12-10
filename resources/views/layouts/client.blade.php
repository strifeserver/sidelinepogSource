<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/eagles.png') }}" />
    @include('partials._head')
    <link rel="stylesheet" href="{{ asset('bower_components/admin-lte/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/admin-lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
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
    @yield('styles')
</head>
<body class="control-sidebar-slide-open layout-navbar-fixed layout-footer-fixed dark-mode text-sm sidebar-collapse">
<div class="wrapper">
  <!-- Navbar -->
    @include('partials._topbar')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
    @include('partials.menus._player')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="">
      <div class="container-fluid">
        @yield('content')
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
    {{-- @include('partials._footer') --}}
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
@include('partials._js')
<script src="{{ asset('bower_components/admin-lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@yield('scripts')
</body>
</html>
