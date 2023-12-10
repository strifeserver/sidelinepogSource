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

    .card-body{
        padding: 5px !important;
    }
</style>
@endsection
@section('content')
<div class="row">
    @if(Auth::user()->type == 'super-admin')
        <div class="col-md-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>₱ {{bcdiv(Auth::user()->wallet->balance,1,2)}}</h3>
                    <p>Wallet Balance</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <a href="#" data-toggle="modal" data-target="#addSystemBalanceModal" class="btn-sm small-box-footer text-white"><i class="fas fa-coins"></i> Add Credits</a>
            </div>
        </div>
    @else
        <div class="col-md-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>₱ {{bcdiv(Auth::user()->wallet->balance,1,2)}}</h3>
                    <p>Wallet Balance</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <a href="#" class="btn-sm small-box-footer text-white"><i class="fas fa-coins"></i> Points Available</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>₱ {{bcdiv(Auth::user()->wallet->commission,1,2)}}</h3>
                    <p>Commission ({{Auth::user()->commission}}%)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <a href="#" data-target="#convertCommissionModal" data-toggle="modal" class="btn-sm small-box-footer text-white">
                    <i class="fas fa-coins"></i> Convert Commission
                </a>
            </div>
        </div>
    @endif


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

                    @if(Auth::user()->type != 'player' && Auth::user()->type != 'super-admin')
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#commission">COMMISSIONS</a>
                        </li>
                    @endif

                    @if(Auth::user()->type == 'player')
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#bets">BETS</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#refunds">REFUNDS</a>
                        </li>
                    @endif
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
                                            <td>{{bcdiv($n->amount,1,2)}}</td>
                                            <td>{{bcdiv($n->ending_balance,1,2)}}</td>
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
                                            <td>{{bcdiv($n->amount,1,2)}}</td>
                                            <td>{{bcdiv($n->ending_balance,1,2)}}</td>
                                            <td>{{($n->remarks)}}</td>
                                            <td>{{($n->user_notes)}}</td>
                                            <td>{{date('m/d/Y h:i:s A',strtotime($n->created_at))}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="commission">
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <th>#</th>
                                    <th>Bet ID</th>
                                    <th>Event Name</th>
                                    <th>Fight Number</th>
                                    <th>Bet Amount</th>
                                    <th>Earned Commission</th>
                                    <th>Fight Result</th>
                                    <th>Date/Time</th>
                                </thead>
                                <tbody>
                                    @foreach ($myCommissions as $i => $comm)
                                    @if (Auth::user()->type == 'operator')
                                        @if($comm->operator_commission > 0)
                                            <tr>
                                                <td>{{$i+1}}</td>
                                                <td>{{$comm->id}}</td>
                                                <td>{{strtoupper($comm->event_name)}}</td>
                                                <td>{{$comm->fight_number}}</td>
                                                <td>{{bcdiv($comm->amount,1,2)}}</td>
                                                <td>{{bcdiv($comm->operator_commission,1,2)}}</td>
                                                <td>{{strtoupper($comm->result)}}</td>
                                                <td>{{date('M d,Y h:i A',strtotime($comm->created_at))}}</td>
                                            </tr>
                                        @endif
                                    @elseif (Auth::user()->type == 'sub-operator')
                                        @if($comm->sub_operator_commission > 0)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{$comm->id}}</td>
                                            <td>{{strtoupper($comm->event_name)}}</td>
                                            <td>{{$comm->fight_number}}</td>
                                            <td>{{bcdiv($comm->amount,1,2)}}</td>
                                            <td>{{bcdiv($comm->sub_operator_commission,1,2)}}</td>
                                            <td>{{strtoupper($comm->result)}}</td>
                                            <td>{{date('M d,Y h:i A',strtotime($comm->created_at))}}</td>
                                        </tr>
                                        @endif
                                    @elseif (Auth::user()->type == 'master-agent')
                                        @if($comm->master_agent_commission > 0)
                                            <tr>
                                                <td>{{$i+1}}</td>
                                                <td>{{$comm->id}}</td>
                                                <td>{{strtoupper($comm->event_name)}}</td>
                                                <td>{{$comm->fight_number}}</td>
                                                <td>{{bcdiv($comm->amount,1,2)}}</td>
                                                <td>{{bcdiv($comm->master_agent_commission,1,2)}}</td>
                                                <td>{{strtoupper($comm->result)}}</td>
                                                <td>{{date('M d,Y h:i A',strtotime($comm->created_at))}}</td>
                                            </tr>
                                        @endif
                                    @elseif (Auth::user()->type == 'gold-agent')
                                        @if($comm->gold_agent_commission > 0)
                                            <tr>
                                                <td>{{$i+1}}</td>
                                                <td>{{$comm->id}}</td>
                                                <td>{{strtoupper($comm->event_name)}}</td>
                                                <td>{{$comm->fight_number}}</td>
                                                <td>{{bcdiv($comm->amount,1,2)}}</td>
                                                <td>{{bcdiv($comm->gold_agent_commission,1,2)}}</td>
                                                <td>{{strtoupper($comm->result)}}</td>
                                                <td>{{date('M d,Y h:i A',strtotime($comm->created_at))}}</td>
                                            </tr>
                                        @endif
                                    @elseif (Auth::user()->type == 'silver-agent')
                                        @if($comm->silver_agent_commission > 0)
                                            <tr>
                                                <td>{{$i+1}}</td>
                                                <td>{{$comm->id}}</td>
                                                <td>{{strtoupper($comm->event_name)}}</td>
                                                <td>{{$comm->fight_number}}</td>
                                                <td>{{bcdiv($comm->amount,1,2)}}</td>
                                                <td>{{bcdiv($comm->silver_agent_commission,1,2)}}</td>
                                                <td>{{strtoupper($comm->result)}}</td>
                                                <td>{{date('M d,Y h:i A',strtotime($comm->created_at))}}</td>
                                            </tr>
                                        @endif
                                    @endif

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="bets">
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <th>#</th>
                                    <th>Event</th>
                                    <th>Fight No.</th>
                                    <th>Bet Amount</th>
                                    <th>Amount Won</th>
                                    <th>Ending Balance</th>
                                    <th>Bet</th>
                                    <th>Result</th>
                                    <th>Remarks</th>
                                    <th>Date/Time</th>
                                </thead>
                                <tbody>
                                    @foreach ($bets as $i => $b)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{$b->event_name}}</td>
                                            <td>{{$b->fight_number}}</td>
                                            <td>{{bcdiv($b->amount,1,2)}}</td>
                                            <td>{{$b->direction == 'out' ? '0' : $b->amount_won}}</td>
                                            <td>{{bcdiv($b->ending_balance,1,2)}}</td>
                                            <td><span class="{{$b->bet}} text-uppercase">{{$b->bet}}</span> <span class="badge {{strpos($b->result,$b->bet) !== false ? 'badge-success':'badge-danger'}}">{{strpos($b->result,$b->bet) !== false ? 'WIN':'LOSE'}}</span></td>
                                            <td><span class="text-uppercase">{{$b->result}}</span></td>
                                            <td>{{$b->remarks}}</td>
                                            <td>{{date('m/d/Y h:i:s A',strtotime($b->created_at))}}</td>
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
@if(Auth::user()->type == 'super-admin')
    @include('partials.modals._addCredits')
    @include('partials.modals._addSystemBalance')
    @include('partials.modals._widthdrawCommission')
    @include('partials.modals._withdrawCredits')
@endif
@include('partials.modals._convertCommission')
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
