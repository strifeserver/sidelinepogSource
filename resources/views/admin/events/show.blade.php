@extends('layouts.admin')
@section('styles')
<style>
    .trend-item {
        width: 25px;
        height: 25px;
        line-height: 25px;
        font-size: 10px;
        text-align: center;
        border-radius: 50%;
    }

    .trend-table td, .trend-table th {
        padding: .1rem !important;
        border-top: none !important;
        height: 32px !important;
    }

    /* .btn-declare{
        width: 100px !important;
        max-width: 100% !important;
        max-height: 100% !important;
        height: 50px !important;
        text-align: center;
        padding: 0px;
        font-size:14px;
        margin-right: 55px;
    } */

    .btn-declare {
        width: 80px !important;
        max-width: 100% !important;
        max-height: 100% !important;
        height: 50px !important;
        text-align: center;
        padding: 0px;
        margin-right: 2px;
    }

    .bg-disabled{
        background-color: rgb(230, 227, 227);
    }

    .btn-last-call{
        display: none;
    }
    .btn-closed{
        display: none;
    }

    .wala{
        background-color: #007bff!important;
        color: #ffffff;
    }

    .meron{
        background-color: #dc3545!important;
        color: #ffffff;
    }

    .draw{
        background-color: #e0a800!important;
        color: #333333;
    }

    .cancelled{
        color: #333333;
    }

    .btn-fight-status{
        margin-top: 0px !important;
    }

    .text-xsmall{
        font-size: 13px;
    }

    .text-xsmall td, .text-xsmall th{
        padding: 0.2rem;
    }
    .select2-container .select2-selection--single {
        height: calc(2.25rem + 2px) !important;

    }
    .select2-container {
        display: block !important;
    }
    .uppercase-bet{
        text-transform: uppercase;
    }
    .bets-meron, .bets-wala{
        font-weight: bold;
    }
    #vid{
        height: 400px;
        background: #000;
    }
    .subtitle{
        font-size: 13px;
    }
    .table-bets{
        height: 400px;
        overflow-y: auto;
    }
    .table td,.table th{
        padding: 0.25rem !important;
    }
    .table-responsive{
        min-height: 205px !important;
    }
    @media only screen and (max-width: 1444px) {
        #vid{
            height: 335px;
            background: #000;
        }
    }
    @media only screen and (max-width: 900px) {
        #vid{
            height: 215px;
            background: #000;
        }
    }
</style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>EVENT</strong>
                    @if($event->status == 'closed')
                        <button class="btn btn-success btn-event-status btn-sm float-right" data-url="{{ route('start.event', $event->id) }}">START EVENT</button>
                    @endif

                    @if($event->status == 'open')
                        <button class="btn btn-danger btn-event-status btn-sm float-right" data-url="{{ route('end.event', $event->id) }}">END EVENT</button>
                    @endif


                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Live Video</strong>
                            <div id="vid">
                                @if($event->status == 'open' || $event->status == 'closed')
                                <iframe width="100%" height="400" src="{{$event->live_url}}" title="Live Cockfight" frameborder="1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                @endif
                            </div>
                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-bets">
                                                <table class="table table-bordered text-xsmall">
                                                    <thead>
                                                        <th>No.</th>
                                                        <th>Username</th>
                                                        <th>Bet</th>
                                                        <th>Amount</th>
                                                    </thead>
                                                    <tbody class="live-bets">
                                                        @foreach ($allBets as $i => $b)
                                                        <tr class="bets-data">
                                                            <td>{{$i+1}}</td>
                                                            <td>{{$b->username}}</td>
                                                            <td><span class="badge {{$b->bet}}">{{strtoupper($b->bet)}}</span></td>
                                                            <td>{{$b->amount}}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-5">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong>Controls</strong>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>WINNING HISTORY</strong>
                                        </div>
                                        <div class="card-body" style="padding: 5px;">
                                            <div class="table-responsive" style="height: 205px !important;">
                                                <table class="table trend-table"></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- <div class="card">
                                        <div class="card-header">
                                            <strong>BETTING PAYOUT</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 text-center">
                                                    <div class="btn-group d-flex mb-3" role="group" aria-label="Basic example">
                                                        <button type="button" data-value="100" class="btn-bet-amt btn btn-outline-dark w-100">100</button>
                                                        <button type="button" data-value="200" class="btn-bet-amt btn btn-outline-dark w-100">200</button>
                                                        <button type="button" data-value="500" class="btn-bet-amt btn btn-outline-dark w-100">500</button>
                                                        <button type="button" data-value="1000" class="btn-bet-amt btn btn-outline-dark w-100">1000</button>
                                                        <button type="button" data-value="5000" class="btn-bet-amt btn btn-outline-dark w-100">5000</button>
                                                        <button type="button" data-value="10000" class="btn-bet-amt btn btn-outline-dark w-100">10000</button>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="number" id="ghost-bet-amount" placeholder="Bet Amount" min="100" step="1" max="10000" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <button class="btn btn-secondary btn-block btn-ghost-bet" data-bet="meron"><strong>GHOST BET</strong></button>
                                                </div>
                                            </div>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <th class="bg-danger text-center">MERON</th>
                                                    <th class="bg-primary text-center">WALA</th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-danger">
                                                            <strong class="odds-meron">{{$odds['meron']}}</strong>
                                                        </td>
                                                        <td class="text-primary">
                                                            <strong class="odds-wala">{{$odds['wala']}}</strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> --}}
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>JUMP TO FIGHT</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="fightNumber" placeholder="Fight number">
                                                <div class="input-group-append" id="button-addon4">
                                                  <button class="btn btn-info btn-jump-fight" type="button"><strong>SET FIGHT</strong></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>BET STATUS</strong>
                                        </div>
                                        <div class="card-body">
                                            <button class="btn btn-success btn-block btn-open btn-fight-status" data-value="OPEN">
                                                <strong>OPEN BET</strong>
                                            </button>
                                            <button class="btn btn-warning btn-block btn-last-call btn-fight-status" data-value="LAST CALL">
                                                <strong>LAST CALL</strong>
                                            </button>
                                            <button class="btn btn-danger btn-closed btn-block btn-fight-status" data-value="CLOSED">
                                                <strong>CLOSE BET</strong>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($event->status == 'open')
                            {{-- <div class="row">
                                <div class="col-md-6">

                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>REDECLARE WINNER</strong>
                                        </div>
                                        <div class="card-body">
                                            <button class="btn btn-success btn-block" data-toggle="modal" data-target="#redeclareFightModal" ><strong>REDECLARE</strong></button>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="card">
                                <div class="card-header">
                                    <strong>DECLARE WINNER</strong>
                                    <span class="float-right font-weight-bolder">FIGHT #<span class="fight-number">{{$fight->fight_number}}</span></span>
                                </div>
                                <div class="card-body text-center" id="btn-declarator">
                                    <button class="btn btn-danger btn-declare" data-value="meron"><strong>DECLARE<br>MERON</strong></button>
                                    <button class="btn btn-primary btn-declare" data-value="wala"><strong>DECLARE<br>WALA</strong></button>
                                    <button class="btn btn-warning btn-declare" data-value="draw"><strong>DECLARE<br>DRAW</strong></button>
                                    <button class="btn btn-secondary btn-declare btn-declare-cancel" data-value="cancelled"><strong>DECLARE<br>CANCEL</strong></button>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <strong>BETTING INFO</strong>
                                </div>
                                <div class="card-body" style="padding:5px;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="2" class="text-center bg-secondary">TOTAL BETS</th>
                                                <th colspan="2" class="text-center bg-success">PAYOUT</th>
                                            </tr>
                                            <tr>
                                                <th class="bg-danger text-center">MERON</th>
                                                <th class="bg-primary text-center">WALA</th>
                                                <th class="bg-danger text-center">MERON</th>
                                                <th class="bg-primary text-center">WALA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="bets-meron">{{bcdiv($betMeron,1,2)}}</td>
                                                <td class="bets-wala">{{bcdiv($betWala,1,2)}}</td>
                                                <td class="text-danger">
                                                    <strong class="odds-meron">{{$odds['meron']}}</strong>
                                                </td>
                                                <td class="text-primary">
                                                    <strong class="odds-wala">{{$odds['wala']}}</strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    {{-- <table class="table table-bordered">
                                        <thead>

                                        </thead>
                                        <tbody>
                                            <tr>

                                            </tr>
                                        </tbody>
                                    </table> --}}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('partials.modals._redeclareWinner')
@section('scripts')
<script>
    var currentFightId = "{{$fight->id}}";
    var token = "{{Session::token()}}";
    var event_id = "{{$event->id}}";
    var fightStat = "{{route('fight.status')}}";
    var declareWin = "{{route('declare.winner')}}";
    var jumpFight = "{{route('jump.fight')}}";
    var redeclareWin = "{{route('redeclare.winner')}}";
    var ghost = "{{route('place.ghost')}}";
    var results = {!!$wins!!};
    var fightStatus = "{{$fight->status}}";
    let trs = ''
    let trd = ''
    var eventIDString = "{{$event->event_id}}";
    var placeBetURL = "{{route('auto.place.bet')}}";
    var betAmt = 0;
    var oddsMeron = "{{$odds['meron']}}";
    var oddsWala = "{{$odds['wala']}}";
    var maxRow = 8;
    $('#btn-declarator button').prop('disabled',true);
    $('.select2').select2();
</script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
{{-- <script src="{{ asset('js/app.js') }}"></script> --}}
<script src="{{ asset('js/declare-realtime.js') }}"></script>
<script src="{{ asset('js/declare.js') }}"></script>
{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/clappr/latest/clappr.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/clappr.level-selector/latest/level-selector.min.js"></script> --}}
<script>
    // var player = new Clappr.Player({
    //         source: "{{$event->live_url}}",
    //         mimeType: "application/x-mpegURL",
    //         autoPlay: true,
    //         height: "100%",
    //         width: "100%",
    //         plugins: {"core": [LevelSelector]},
    //         parentId: "#vid"
    // });
</script>
@endsection
