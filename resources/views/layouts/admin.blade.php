<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/eagles.png') }}" />
    @include('partials._head')
    <link rel="stylesheet" href="{{ asset('bower_components/admin-lte/plugins/sweetalert2/sweetalert2.min.css') }}">
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
    @yield('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
    <!-- Navbar -->
        @include('partials._topbar')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
        @if(Auth::user()->type == 'super-admin')
            @include('partials.menus._super-admin')

        @elseif(Auth::user()->type == 'admin')
            @include('partials.menus._admin')

        @elseif(Auth::user()->type == 'operator')
            @include('partials.menus._operator')

        @elseif(Auth::user()->type == 'sub-operator')
            @include('partials.menus._sub-operator')

        @elseif(Auth::user()->type == 'master-agent')
            @include('partials.menus._master-agent')

        @elseif(Auth::user()->type == 'gold-agent')
            @include('partials.menus._gold-agent')

        @elseif(Auth::user()->type == 'silver-agent')
            @include('partials.menus._silver-agent')

        @elseif(Auth::user()->type == 'declarator')
            @include('partials.menus._declarator')

        @elseif(Auth::user()->type == 'player')
            @include('partials.menus._player')
        @endif

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
                @include('partials._message')
            </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
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
