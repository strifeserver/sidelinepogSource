@extends('layouts.client')
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

                <table class="table table-bordered table-condensed">
                    <thead>
                        <th>#</th>
                        <th>Event ID</th>
                        <th>Fight No.</th>
                        <th>Amount</th>
                        <th>Amount Won</th>
                        <th>Ending Balance</th>
                        <th>Direction</th>
                        <th>Remarks</th>
                        <th>Date/Time</th>
                    </thead>
                    <tbody>
                        @foreach ($myBets as $i => $b)
                        <tr>
                            <td>{{$i+1}}</td>
                            <td>{{$b->event_id}}</td>
                            <td>{{$b->fight_number}}</td>
                            <td>{{$b->amount}}</td>
                            <td>{{$b->direction == 'out' ? '0' : $b->amount_won}}</td>
                            <td>{{$b->ending_balance}}</td>
                            <td><span class="{{$b->direction}}">{{$b->direction}}</span></td>
                            <td>{{$b->remarks}}</td>
                            <td>{{date('m/d/Y h:i:s A',strtotime($b->created_at))}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
