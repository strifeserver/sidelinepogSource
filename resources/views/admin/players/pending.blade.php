@extends('layouts.admin')
@section('styles')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong><i class="fas fa-bars"></i> List of APPROVAL PLAYERS</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search username" id="username-search">
                                <div class="input-group-append">
                                  <button class="btn btn-success" id="btn-search" data-url="{{ route('pending.players') }}" type="button">
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
                                <th>Points</th>
                                <th>Action/s</th>
                            </thead>
                            <tbody>
                                @foreach ($direct_players as $dp )
                                    <tr>
                                        <td>{{$dp->username}}</td>
                                        <td>{{$dp->wallet->balance}}</td>
                                        <td>
                                            <button type="button" class="btn btn-change-status {{$dp->status == 'active' ? 'btn-outline-danger' : 'btn-outline-success'}} btn-sm" data-id="{{$dp->id}}" data-status="{{$dp->status}}">
                                                <i class="fas fa-circle"></i> {{$dp->status == 'active' ? 'DEACTIVATE' : 'ACTIVATE'}}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            {{$direct_players->links()}}
                        </div>
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
