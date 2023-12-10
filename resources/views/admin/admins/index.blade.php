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
                    <strong>GENERAL ADMINS</strong>
                    @if(Auth::user()->type == 'super-admin')
                        <a href="{{ route('create.admin') }}" class="btn btn-success float-right">CREATE</a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Username</th>
                                <th>Wallet Balance</th>
                                <th>Action/s</th>
                            </thead>
                            <tbody>
                                @foreach ($admins as $op)
                                <tr>
                                    <td>{{ucwords($op->name)}}</td>
                                    <td>{{$op->contact_number}}</td>
                                    <td>{{$op->username}}</td>
                                    <td>{{$op->wallet->balance}}</td>
                                    <td>
                                        <a href="{{ route('edit.account',$op->id) }}"  class="btn btn-success btn-sm">EDIT</a>
                                        <button type="button" class="btn btn-primary btn-sm btn-deposit" data-id="{{$op->id}}" >DEPOSIT</button>
                                        <button type="button" class="btn btn-danger btn-sm btn-return" data-id="{{$op->id}}" >WITHDRAW</button>
                                        <button type="button" class="btn btn-change-status {{$op->status == 'active' ? 'btn-outline-danger' : 'btn-outline-success'}} btn-sm" data-id="{{$op->id}}" data-status="{{$op->status}}">
                                            <i class="fas fa-circle"></i> {{$op->status == 'active' ? 'DEACTIVATE' : 'ACTIVATE'}}
                                        </button>
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
    @include('partials.modals._depositAmount')
    @include('partials.modals._setCommissionPercent')
    @include('partials.modals._withdrawLoad')
@endsection

@section('scripts')
@include('partials._jsvariables')
<script src="{{ asset('js/main.js') }}"></script>
@endsection
