@extends('layouts.admin')
@section('styles')
    <style>
        .fs-small{
            font-size: 13px;
        }
        button{
            font-weight: bolder;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong><i class="fas fa-bars"></i> List of OPERATORS</strong>
                    {{-- <a href="{{ route('create.operator') }}" class="btn btn-success float-right">CREATE</a> --}}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search username" id="username-search">
                                <div class="input-group-append">
                                  <button class="btn btn-success" id="btn-search" data-url="{{ route('operators') }}" type="button">
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
                                <th>Wallet Balance</th>
                                <th>Commission(%)</th>
                                <th>Earned Commissions</th>
                                <th>Action/s</th>
                            </thead>
                            <tbody>
                                @foreach ($operators as $op)
                                <tr>
                                    <td>{{$op->username}}</td>
                                    <td>
                                        @if($op->referred_by != null)
                                            @php
                                                $us = \App\Models\User::withTrashed()->find($op->referred_by);
                                            @endphp
                                            {{$us->username}}
                                        @endif
                                    </td>
                                    <td>{{bcdiv($op->wallet->balance,1,2)}}</td>
                                    <td>{{$op->commission}}</td>
                                    <td>{{bcdiv($op->wallet->commission,1,2)}}</td>
                                    <td>
                                        @if(Auth::user()->type == 'admin' || Auth::user()->type == 'super-admin')
                                            <a href="{{ route('edit.account',$op->id) }}"  class="btn btn-success btn-sm">EDIT</a>
                                            <a href="{{ route('dl.players',$op->id) }}" class="btn btn-primary btn-sm" >PLAYERS</a>
                                            <a href="{{ route('dl.agents',$op->id) }}" class="btn btn-warning btn-sm" >AGENTS</a>
                                        @endif
                                        <button type="button" class="btn btn-warning btn-sm btn-accsumm" data-url="{{route('acc.summary',$op->id)}}" data-id="{{$op->id}}">ACCOUNT SUMMARY</button>
                                        <button type="button" class="btn btn-primary btn-sm btn-deposit" data-id="{{$op->id}}" >DEPOSIT</button>
                                        <button type="button" class="btn btn-danger btn-sm btn-return" data-id="{{$op->id}}" >WITHDRAW</button>
                                        <button type="button" class="btn btn-secondary btn-sm btn-comm" data-id="{{$op->id}}" >WITHDRAW COMM</button>
                                        <a href="{{ route('load.history',$op->id) }}" class="btn btn-success btn-sm" >LOAD HISTORY</a>
                                        <a href="{{ route('agent.events',$op->id) }}" class="btn btn-success btn-sm" >COMM HISTORY</a>
                                        <button type="button" class="btn btn-warning btn-sm btn-commission" data-id="{{$op->id}}">SET COMMISSION</button>
                                        @if($op->flag == 'legal' || Auth::user()->type == 'admin' || Auth::user()->type == 'super-admin')
                                            <button type="button" class="btn btn-change-status {{$op->status == 'active' ? 'btn-outline-danger' : 'btn-outline-success'}} btn-sm" data-id="{{$op->id}}" data-status="{{$op->status}}">
                                                <i class="fas fa-circle"></i> {{$op->status == 'active' ? 'DEACTIVATE' : 'ACTIVATE'}}
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-url="{{route('hard.delete')}}" data-id="{{$op->id}}">DELETE USER</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            {{$operators->links()}}
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
    @include('partials.modals._accountSummary')
@endsection

@section('scripts')
@include('partials._jsvariables')
<script src="{{ asset('js/main.js') }}"></script>
@endsection
