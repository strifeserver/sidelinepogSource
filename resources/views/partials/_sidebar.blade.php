<aside class="main-sidebar sidebar-dark-primary elevation-4">
<!-- Brand Logo -->
<a href="#" class="brand-link text-center">
    <span class="brand-text font-weight-light" style="text-transform: uppercase; font-size:16px; font-weight:bold !important;">...</span>
</a>
<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header">GENERAL</li>
        @if(notAPlayer(Auth::user()->type))
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
        @else
            <li class="nav-item">
                <a href="{{ route('select.game') }}" class="nav-link">
                    <i class="nav-icon fas fa-play"></i>
                    <p>Play</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('client.bets') }}" class="nav-link">
                    <i class="nav-icon fas fa-calendar-alt"></i>
                    <p>Bet History</p>
                </a>
            </li>
        @endif
        @if(canCreateEvent(Auth::user()->type))
        <li class="nav-item">
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-calendar-alt"></i>
                    <p>
                        Events
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('create.event') }}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Create Event</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('events') }}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>All Events</p>
                    </a>
                  </li>
                </ul>
            </li>
        </li>
        @endif

        @if(canLoad(Auth::user()->type))

            <li class="nav-header">LOADING STATION</li>
            @if (Auth::user()->type != 'admin')
                <li class="nav-item">
                    <a href="{{ route('deposits') }}" class="nav-link">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>
                        Loading History
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('withdrawals') }}" class="nav-link">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>
                        Withdrawals
                        </p>
                    </a>
                </li>
                @if(Auth::user()->type == 'admin' || Auth::user()->type == 'loader')
                    <li class="nav-item">
                        <a href="{{ route('commission.withdrawals') }}" class="nav-link">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>
                            Commission Withdrawals
                            </p>
                        </a>
                    </li>
                @endif
            @endif



        @endif
        @if(canLoad(Auth::user()->type))
        <li class="nav-header">PLAYERS</li>
            <li class="nav-item">
                <a href="{{ route('players') }}" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                        <p>
                            Direct Players
                        </p>
                </a>
            </li>
            @if(agentOnly(Auth::user()->type))
            <li class="nav-item">
                <a href="{{ route('pending.players') }}" class="nav-link">
                    <i class="nav-icon fas fa-user-clock"></i>
                        <p>
                            Pending Approval
                        </p>
                </a>
            </li>
            @endif
        @endif
        @if(Auth::user()->type =='master_agent')
            <li class="nav-item">
                <a href="{{ route('pending.players') }}" class="nav-link">
                    <i class="nav-icon fas fa-people-arrows"></i>
                    <p>
                        Referred Players
                    </p>
                </a>
            </li>
        @endif

        @if(canViewMasterAgents(Auth::user()->type) || canViewAgents(Auth::user()->type))
            <li class="nav-header">USERS</li>
        @endif
        @if(canViewOperators(Auth::user()->type))
            {{-- <li class="nav-item">
                <a href="{{ route('sub.operators') }}" class="nav-link">
                    <i class="nav-icon fas fa-user-astronaut"></i>
                    <p>Sub-Operator</p>
                </a>
            </li> --}}
        @endif
        @if(canViewMasterAgents(Auth::user()->type))
            <li class="nav-item">
                <a href="{{ route('master.agents') }}" class="nav-link">
                    <i class="nav-icon fas fa-user-secret"></i>
                    <p>Operators</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('master.agents') }}" class="nav-link">
                    <i class="nav-icon fas fa-user-secret"></i>
                    <p>Sub Operators</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('master.agents') }}" class="nav-link">
                    <i class="nav-icon fas fa-user-secret"></i>
                    <p>Master Agents</p>
                </a>
            </li>
        @endif

        @if(canViewAgents(Auth::user()->type))
            <li class="nav-item">
                <a href="{{ route('agents') }}" class="nav-link">
                    <i class="nav-icon fas fa-user-tie"></i>
                    <p>Gold Agents</p>
                </a>
            </li>
            @if(Auth::user()->type == 'master_agent')
                <li class="nav-item">
                    <a href="{{ route('pending.agents') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-clock"></i>
                        <p>Pending Agents</p>
                    </a>
                </li>
            @endif
        @endif

        @if(canViewWallet(Auth::user()->type))
            <li class="nav-header">MISCELLANEOUS</li>
            @if(Auth::user()->type != 'admin')
                <li class="nav-item">
                    <a href="{{ route('wallet') }}" class="nav-link">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p class="text">Wallet</p>
                    </a>
                </li>
            @endif

            @if(adminOnly(Auth::user()->type))
            <li class="nav-item">
                <a href="{{ route('archives') }}" class="nav-link">
                    <i class="nav-icon fas fa-archive"></i>
                    <p class="text">Archive</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('all.commissions') }}" class="nav-link">
                    <i class="nav-icon fas fa-wallet"></i>
                    <p class="text">Commission History</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('settings') }}" class="nav-link">
                    <i class="nav-icon fas fa-cogs"></i>
                    <p class="text">Settings</p>
                </a>
            </li>
            @endif
            @if(Auth::user()->type == 'master_agent')
                <li class="nav-item">
                    <a href="{{ route('ma.commissions') }}" class="nav-link">
                        <i class="nav-icon fas fa-percent"></i>
                        <p class="text">Commission History</p>
                    </a>
                </li>
            @endif
            @if(Auth::user()->type != 'admin')
                <li class="nav-item">
                    <a href="{{ route('agent.withdrawal') }}" class="nav-link">
                        <i class="nav-icon fas fa-money-bill-alt"></i>
                        <p>My Withdrawals</p>
                    </a>
                </li>
            @endif
        @endif
    </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
