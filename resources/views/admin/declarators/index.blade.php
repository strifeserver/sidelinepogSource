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
                    <strong>DECLARATORS</strong>
                    <a href="{{ route('create.declarator') }}" class="btn btn-success float-right">CREATE</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Contact Number</th>
                            <th>Action/s</th>
                        </thead>
                        <tbody>
                            @foreach ($declarators as $op)
                            <tr>
                                <td>{{$op->username}}</td>
                                <td>{{ucwords($op->name)}}</td>
                                <td>{{$op->contact_number}}</td>
                                <td>
                                    <a href="{{ route('edit.account',$op->id) }}"  class="btn btn-success btn-sm">EDIT</a>
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
    @include('partials.modals._depositAmount')
    @include('partials.modals._setCommissionPercent')
@endsection

@section('scripts')
@include('partials._jsvariables')
<script src="{{ asset('js/main.js') }}"></script>
@endsection
