@extends('layouts.admin')
@section('styles')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>PLAYERS</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Username</th>
                                <th>Wallet Balance</th>
                                <th>Action/s</th>
                            </thead>
                            <tbody>
                                @foreach ($direct_players as $dp )
                                    <tr>
                                        <td>{{$dp->name}}</td>
                                        <td>{{$dp->contact_number}}</td>
                                        <td>{{$dp->username}}</td>
                                        <td>{{floor($dp->wallet->balance*100)/100}}</td>
                                        <td>
                                            <a type="button" class="btn btn-secondary btn-sm" href="{{ route('wallet.history', ['id'=>$dp->id]) }}">WALLET HISTORY</a>
                                            <a type="button" class="btn btn-warning btn-sm" href="{{ route('player.events', ['id'=>$dp->id]) }}">BETTING HISTORY</a>
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
