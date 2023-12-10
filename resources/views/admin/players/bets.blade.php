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
                <strong>BET HISTORY</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>Fight No.</th>
                            <th>Event</th>
                            <th>Bet Amount</th>
                            <th>Amount Won</th>
                            <th>Bet</th>
                            <th>Fight Result</th>
                            <th>Plasada</th>
                            <th>Date/Time</th>
                        </thead>
                        <tbody>
                            @php
                                $totPlas = [];
                            @endphp
                            @foreach ($playerBets as $i => $bet)
                                @php
                                    $totPlas[] = $bet->plasada;
                                @endphp
                                <tr>
                                    <td>{{$bet->fight_number}}</td>
                                    <td>{{$bet->event_name}}</td>
                                    <td>{{$bet->amount}}</td>
                                    @if($bet->result == 'draw')
                                        @if($bet->bet == 'draw')
                                            <td>{{floor(($bet->amount*8)*100)/100}}</td>
                                        @else
                                            <td>{{floor($bet->amount_won*100)/100}}</td>
                                        @endif
                                    @else
                                        <td>{{floor($bet->amount_won*100)/100}}</td>
                                    @endif
                                    <td>{{strtoupper($bet->bet)}}</td>
                                    <td>{{strtoupper($bet->result)}}</td>
                                    <td>{{strtoupper($bet->plasada)}}</td>
                                    <td>{{date('M d,Y h:i A',strtotime($bet->created_at))}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                    <div class="row">
                        <div class="col text-right">
                            <span class="font-weight-bolder">TOTAL PLASADA: </span><span class="text-success font-weight-bolder">{{number_format(array_sum($totPlas),2,'.',',')}}</span>
                        </div>
                    </div>

            </div>
            </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('.table').DataTable();
    });
</script>
@endsection
