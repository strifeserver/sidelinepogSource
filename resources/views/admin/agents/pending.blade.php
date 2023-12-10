@extends('layouts.admin')
@section('styles')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>PENDING AGENTS</strong>
                    <a href="{{ route('create.agent') }}" class="btn btn-success float-right">CREATE</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Wallet Balance</th>
                            <th>Action/s</th>
                        </thead>
                        <tbody>
                            @foreach ($agents as $agent)
                            <tr>
                                <td>{{ucwords($agent->name)}}</td>
                                <td>{{ucwords($agent->contact_number)}}</td>
                                <td>{{$agent->email}}</td>
                                <td>{{$agent->wallet->balance}}</td>
                                <td>
                                    <button type="button" class="btn {{$agent->status == 'active' ? 'btn-outline-danger' : 'btn-outline-success'}} btn-sm btn-change-status" data-id="{{$agent->id}}" data-status="{{$agent->status == 'active' ? 'inactive' : 'active'}}">
                                        <i class="fas fa-circle"></i> {{$agent->status == 'active' ? 'DEACTIVATE' : 'ACTIVATE'}}
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
@endsection
@section('scripts')
@include('partials._jsvariables')
<script src="{{ asset('js/main.js') }}"></script>
@endsection
