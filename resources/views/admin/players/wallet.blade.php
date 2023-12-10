@extends('layouts.admin')
@section('styles')
<style>
    .select2-container{
        display: block !important;
    }
    .select2-selection{
        height: 38px !important;
    }

    .in{
        color: green;
        font-weight: 700;
        text-transform: uppercase;
    }
    .out{
        color: red;
        font-weight: 700;
        text-transform: uppercase;
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>TRANSACTION HISTORY</strong>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#load">CASH INS</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#withdraw">CASH OUTS</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#refunds">REFUNDS</a>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <div class="tab-pane active" id="load">
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <th>#</th>
                                    <th>TO</th>
                                    <th>FROM</th>
                                    <th>AMOUNT</th>
                                    <th>ENDING BALANCE</th>
                                    <th>REMARKS</th>
                                    <th>NOTES</th>
                                    <th>DATE/TIME</th>
                                </thead>
                                <tbody>
                                    @foreach ($loads as $i => $n)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{($n->user_to)}}</td>
                                            <td>{{($n->user_from)}}</td>
                                            <td>{{floor($n->amount*100)/100}}</td>
                                            <td>{{floor($n->ending_balance*100)/100}}</td>
                                            <td>{{($n->remarks)}}</td>
                                            <td>{{($n->user_notes)}}</td>
                                            <td>{{date('m/d/Y h:i:s A',strtotime($n->created_at))}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="withdraw">
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <th>#</th>
                                    <th>FROM</th>
                                    <th>TO</th>
                                    <th>AMOUNT</th>
                                    <th>ENDING BALANCE</th>
                                    <th>REMARKS</th>
                                    <th>NOTES</th>
                                    <th>DATE/TIME</th>
                                </thead>
                                <tbody>
                                    @foreach ($withdraws as $i => $n)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{($n->user_to)}}</td>
                                            <td>{{($n->user_from)}}</td>
                                            <td>{{floor($n->amount*100)/100}}</td>
                                            <td>{{floor($n->ending_balance*100)/100}}</td>
                                            <td>{{($n->remarks)}}</td>
                                            <td>{{($n->user_notes)}}</td>
                                            <td>{{date('m/d/Y h:i:s A',strtotime($n->created_at))}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="refunds">
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <th>#</th>
                                    <th>Event ID</th>
                                    <th>Fight No.</th>
                                    <th>Amount</th>
                                    <th>Ending Balance</th>
                                    <th>Direction</th>
                                    <th>Remarks</th>
                                    <th>Date/Time</th>
                                </thead>
                                <tbody>
                                    @foreach ($refunds as $i => $b)
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{$b->event_id}}</td>
                                        <td>{{$b->fight_number}}</td>
                                        <td>{{$b->amount}}</td>
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
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('.table').DataTable({
            "ordering": false
        });
        $('.select').select2();
    });
</script>
@endsection
