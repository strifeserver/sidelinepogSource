<nav class="main-header navbar navbar-expand navbar-dark">
<!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars topbar-links"></i></a>
        </li>
    </ul>
    @if(Auth::user()->type == "player" || Auth::user()->type == "booster")
        {{-- <div class="input-group input-group-sm credits-container">
            <div class="input-group-prepend">
                <span class="input-group-text" style="background-color: transparent;border: none;"><i class="fas fa-coins text-warning"></i></span>
            </div>
            <input type="text" class="pl-0 form-control text-warning font-weight-bold wallet-balance border-0 bg-dark" style="color:#ffc107!important;" readonly value="{{floor(Auth::user()->wallet->balance * 100)/100}}">

            <div class="input-group-append border-success">
                <button class="input-group-text border-0 bg-warning text-white" data-toggle="modal" data-target="#playerCreditRequest"><i class="fas fa-plus"></i></button>
            </div>
        </div> --}}
    @endif
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-user topbar-links"></i>
            {{Auth::user()->username}}
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-divider"></div>
                {{-- <a href="{{ route('edit.profile') }}" class="dropdown-item">Update Profile</a> --}}
                <a href="{{ route('password.update') }}" class="dropdown-item">Change Password</a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item"> Logout</a>
            </div>
        </li>
    </ul>
</nav>
