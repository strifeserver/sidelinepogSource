@extends('layouts.admin')
@section('styles')
<style>
    .select2-container{
        display: block !important;
    }
    .select2-selection{
        height: 38px !important;
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>ARCHIVES</strong>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#loading">LOAD HISTORY</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#withdraw">WITHDRAW HISTORY</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#events">EVENTS HISTORY</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#bets">BET HISTORY</a>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <div class="tab-pane active" id="loading">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <th>Requested by</th>
                                <th>Reference No.</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </thead>
                            <tbody>
                                @foreach ($loads as $load)
                                    <tr>
                                        <td>{{strtoupper($load->name)}}</td>
                                        <td>{{strtoupper($load->reference_number)}}</td>
                                        <td>{{number_format($load->amount,2,'.',',')}}</td>
                                        <td>{{date('M d,Y h:i A',strtotime($load->created_at))}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="withdraw">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <th>Requested by</th>
                                <th>Amount</th>
                                <th>Account Name</th>
                                <th>Account No</th>
                                <th>Date</th>
                            </thead>
                            <tbody>
                                @foreach ($withdraws as $w)
                                    <tr>
                                        <td>{{strtoupper($w->name)}}</td>
                                        <td>{{number_format($w->amount,2,'.',',')}}</td>
                                        <td>{{strtoupper($w->account_name)}}</td>
                                        <td>{{strtoupper($w->account_number)}}</td>
                                        <td>{{date('M d,Y h:i A',strtotime($w->created_at))}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="events">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <th>ID</th>
                                <th>Event Name</th>
                                <th>Fight URL</th>
                                <th>Date Created</th>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                <tr>
                                    <td>{{$event->event_id}}</td>
                                    <td>{{$event->name}}</td>
                                    <td>{{$event->live_url}}</td>
                                    <td>{{date('m/d/Y',strtotime($event->created_at))}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="bets">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <th>Player Name</th>
                                <th>Fight No.</th>
                                <th>Event ID</th>
                                <th>Event</th>
                                <th>Bet Amount</th>
                                <th>Amount Won</th>
                                <th>Bet</th>
                                <th>Fight Result</th>
                                <th>Date/Time</th>
                            </thead>
                            <tbody>
                                @foreach ($bets as $i => $bet)
                                    <tr>
                                        <td>{{$bet->player_name}}</td>
                                        <td>{{$bet->fight_number}}</td>
                                        <td>{{$bet->event_hash}}</td>
                                        <td>{{$bet->event_name}}</td>
                                        <td>{{$bet->amount}}</td>
                                        <td>{{$bet->amount_won}}</td>
                                        <td>{{strtoupper($bet->bet)}}</td>
                                        <td>{{strtoupper($bet->result)}}</td>
                                        <td>{{date('M d,Y h:i A',strtotime($bet->created_at))}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@include('partials.modals._addCredits')
@include('partials.modals._widthdrawCommission')
@include('partials.modals._convertCommission')
@section('scripts')
<script>
    $(document).ready(function () {
        $('.table').DataTable();
        $('.select').select2();
    });
</script>
@endsection
