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
                    <strong>AGENT/OPERATOR DOWNLINES</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Wallet Balance</th>
                                <th>Commission(%)</th>
                                <th>Earned Commissions</th>
                                <th>Action/s</th>
                            </thead>
                            <tbody>
                                @foreach ($operators as $op)
                                <tr>
                                    <td>
                                        <div>{{ucwords($op->name)}}</div>
                                        <div class="fs-small">{{$op->contact_number}}</div>
                                    </td>
                                    <td>{{$op->username}}</td>
                                    <td>{{$op->wallet->balance}}</td>
                                    <td>{{$op->commission}}</td>
                                    <td>{{$op->wallet->commission}}</td>
                                    <td>
                                        @if(Auth::user()->type == 'admin' || Auth::user()->type == 'super-admin')
                                            <a href="{{ route('edit.account',$op->id) }}"  class="btn btn-success btn-sm">EDIT</a>
                                            <a href="{{ route('dl.players',$op->id) }}" class="btn btn-primary btn-sm" >PLAYERS</a>
                                            <a href="{{ route('dl.agents',$op->id) }}" class="btn btn-warning btn-sm" >AGENTS</a>
                                        @endif
                                        <a href="{{ route('load.history',$op->id) }}" class="btn btn-success btn-sm" >LOAD HISTORY</a>
                                        <a href="{{ route('agent.events',$op->id) }}" class="btn btn-success btn-sm" >COMM HISTORY</a>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
              </div>
        </div>
    </div>
@endsection

@section('scripts')
@include('partials._jsvariables')
<script src="{{ asset('js/main.js') }}"></script>
@endsection
