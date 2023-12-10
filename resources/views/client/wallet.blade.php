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
    .notes{
        font-style: italic;
        font-size: 12px;
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
                <a href="#" data-toggle="modal" data-target="#addSystemBalanceModal" class="btn-sm small-box-footer text-white"><i class="fas fa-coins"></i> Create Points</a>
            </div>
        </div>
    @endif
    @if(Auth::user()->type == 'player')
    <div class="col-md-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>₱ {{bcdiv(Auth::user()->wallet->balance,1,2)}}</h3>
                <p>Wallet Balance</p>
            </div>
            <div class="icon">
                <i class="fas fa-wallet"></i>
            </div>
            {{-- <a href="#" data-toggle="modal" data-target="#withdrawCreditsModal" class="btn-sm small-box-footer text-white"><i class="fas fa-coins"></i> Withdraw Points</a> --}}
        </div>
    </div>
    @endif
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>TRANSACTION HISTORY</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>DATE LOADED</th>
                            <th>LOADED FROM</th>
                            <th>POINTS</th>
                            <th>EVENT</th>
                            <th>DATE EVENT</th>
                            <th>FIGHT#</th>
                            <th>BET</th>
                            <th>BET AMOUNT</th>
                            <th>LOADED BY</th>
                            <th>BALANCE</th>
                            <th>NOTES</th>
                        </thead>
                        <tbody>
                            @foreach ($trans as $i => $n)
                                @php
                                    if($n->bet_id != null){
                                        $eventData = getEventDataFromBetID($n->bet_id);
                                        //$fightData = getFightDataFromBetID($n->bet_id);
                                    }
                                @endphp
                                <tr>
                                    <td>{{date('m/d/Y h:i:s A',strtotime($n->created_at))}}</td>
                                    <td>{{$n->bet_id == null ? 'PASALOAD' : 'BET'}}</td>
                                    <td><span class="badge {{$n->direction == 'out' ? 'badge-danger' : 'badge-success'}}">
                                        {{$n->direction == 'out' ? '-' : '+'}}
                                        @if($n->bet_id == null)
                                            {{bcdiv($n->amount,1,2)}}
                                        @else
                                            @php
                                                $betDetails = \App\Models\Bet::find($n->bet_id);
                                            @endphp
                                            @if($n->direction == 'in')
                                                 {{bcdiv($betDetails->amount_won,1,2)}}
                                            @else
                                                {{bcdiv($betDetails->amount,1,2)}}
                                            @endif
                                        @endif
                                    </span></td>
                                    <td>{{$n->bet_id == null ? '' : $eventData['event']->name}}</td>
                                    <td>{{$n->bet_id == null ? '' : date('m/d/Y',strtotime($eventData['event']->created_at))}}</td>
                                    <td>{{$n->bet_id == null ? '' : $eventData['fight']->fight_number}}</td>
                                    <td>{{$n->bet_id == null ? '' : strtoupper($eventData['bet']->bet)}}</td>
                                    <td>{{$n->bet_id == null ? '' : bcdiv($n->amount,1,2)}}</td>
                                    <td>
                                        @if($n->load_id != null)
                                        {{findLoadedBy($n->load_id)->username}}
                                        @endif

                                        @if($n->withdraw_id != null)
                                        {{findWithdrawnBy($n->withdraw_id)->username}}
                                        @endif
                                    </td>
                                    <td>{{bcdiv($n->ending_balance,1,2)}}</td>
                                    <td>
                                        <p>{{($n->remarks)}}</p>
                                        <p class="notes">{{$n->user_notes}}</p>
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
{{-- @include('partials.modals._withdrawCredits') --}}
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('.table').DataTable({
            "ordering": false,
            "pageLength" : 50,
        });
        $('.select').select2();
    });
</script>
@endsection
