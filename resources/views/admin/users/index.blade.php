@extends('layouts.admin')
@section('styles')
<style>
    .data-table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
    }
</style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong><i class="fas fa-bars"></i> List of ACCOUNTS</strong>
                    {{-- <a class="btn btn-success float-right" href="{{ route('create.player') }}">CREATE</a> --}}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" value="{{$username}}" placeholder="Search username" id="username-search">
                                <div class="input-group-append">
                                  <button class="btn btn-success" id="btn-search" data-url="{{ route('user.accounts') }}" type="button">
                                    <i class="fas fa-search"></i>
                                  </button>
                                </div>
                              </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <th>Username</th>
                                <th>Upline</th>
                                <th>Points</th>
                                <th>Type</th>
                                <th>Action/s</th>
                            </thead>
                            <tbody>
                                @foreach ($accounts as $dp )
                                    <tr>
                                        <td>{{$dp->username}}</td>
                                        <td>
                                            @if($dp->referred_by != null)
                                                @php
                                                    $us = \App\Models\User::withTrashed()->find($dp->referred_by);
                                                @endphp
                                                {{$us->username}}
                                            @endif
                                        </td>
                                        <td>{{bcdiv($dp->wallet->balance,1,2)}}</td>
                                        <td>{{$dp->type == 'gold-agent' ? 'SUB AGENT' : strtoupper($dp->type)}}</td>
                                        <td>
                                            @if($dp->type == 'player')
                                                @if(Auth::user()->type == 'admin' || Auth::user()->type == 'super-admin')
                                                    <a href="{{ route('edit.account',$dp->id) }}"  class="btn btn-success btn-sm">EDIT</a>
                                                    <button type="button" class="btn btn-danger btn-sm btn-delete" data-url="{{route('hard.delete')}}" data-id="{{$dp->id}}">DELETE USER</button>
                                                @endif
                                                <button type="button" class="btn btn-primary btn-sm btn-deposit" data-id="{{$dp->id}}" >DEPOSIT</button>
                                                <button type="button" class="btn btn-danger btn-sm btn-return" data-id="{{$dp->id}}" >WITHDRAW</button>
                                                <a type="button" class="btn btn-secondary btn-sm" href="{{ route('wallet.history', ['id'=>$dp->id]) }}">WALLET HISTORY</a>
                                                <a type="button" class="btn btn-warning btn-sm" href="{{ route('player.events', ['id'=>$dp->id]) }}">BETTING HISTORY</a>
                                                <button type="button" class="btn btn-change-status {{$dp->status == 'active' ? 'btn-outline-danger' : 'btn-outline-success'}} btn-sm" data-id="{{$dp->id}}" data-status="{{$dp->status}}">
                                                    <i class="fas fa-circle"></i> {{$dp->status == 'active' ? 'DEACTIVATE' : 'ACTIVATE'}}
                                                </button>
                                            @else
                                                @if(Auth::user()->type == 'admin' || Auth::user()->type == 'super-admin')
                                                    <a href="{{ route('edit.account',$dp->id) }}"  class="btn btn-success btn-sm">EDIT</a>
                                                @endif
                                                <button type="button" class="btn btn-primary btn-sm btn-deposit" data-id="{{$dp->id}}" >DEPOSIT</button>
                                                <button type="button" class="btn btn-danger btn-sm btn-return" data-id="{{$dp->id}}" >WITHDRAW</button>
                                                <button type="button" class="btn btn-secondary btn-sm btn-comm" data-id="{{$dp->id}}" >WITHDRAW COMM</button>
                                                <a href="{{ route('load.history',$dp->id) }}" class="btn btn-success btn-sm" >LOAD HISTORY</a>
                                                <a href="{{ route('agent.events',$dp->id) }}" class="btn btn-success btn-sm" >COMM HISTORY</a>
                                                @if($dp->type != 'admin')
                                                    <button type="button" class="btn btn-warning btn-sm btn-commission" data-id="{{$dp->id}}">SET COMMISSION</button>
                                                @endif
                                                @if($dp->flag == 'legal' || Auth::user()->type == 'admin' || Auth::user()->type == 'super-admin')
                                                    <button type="button" class="btn btn-change-status {{$dp->status == 'active' ? 'btn-outline-danger' : 'btn-outline-success'}} btn-sm" data-id="{{$dp->id}}" data-status="{{$dp->status}}">
                                                        <i class="fas fa-circle"></i> {{$dp->status == 'active' ? 'DEACTIVATE' : 'ACTIVATE'}}
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm btn-delete" data-url="{{route('hard.delete')}}" data-id="{{$dp->id}}">DELETE USER</button>
                                                @endif
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            {{$accounts->links()}}
                        </div>
                    </div>
                </div>
              </div>
        </div>
    </div>
    @include('partials.modals._depositAmount')
    @include('partials.modals._setCommissionPercent')
    @include('partials.modals._withdrawLoad')
    @include('partials.modals._withdrawComm')
@endsection
@section('scripts')
@include('partials._jsvariables')
<script src="{{ asset('js/main.js') }}"></script>
@endsection
