@extends('layouts.web')
@section('styles')
<link rel="stylesheet" href="{{ asset('css/client.css') }}">
<style>
    #vid{
        height: 400px;
    }
    @media only screen and (max-width: 768px) {
        #vid{
            height: 225px;
        }
    }
    .btn-bet-amt{
        font-size: 13px !important;
    }
    .bg-warning{
        background-color: #ff6a07!important;
    }
    .fight-no{
        background: rgb(58, 56, 56);
        padding: 5px;
        padding-left: 25px;
        font-size: 25px;
        font-weight: bolder;
    }

    .timer{
        background: red;
        padding: 5px;
        padding-left: 25px;
        font-size: 25px;
        font-weight: bolder;
    }
</style>

</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="fight-no">
                <span>FIGHT #<span id="fight-number"></span></span>
            </div>

            <div class="timer">
                {{date('m/d/Y')}} - <span id="clock"></span>
            </div>
        </div>
    </div>
</div>

@endsection
@include('partials.modals._playerCreditRequest')
@section('scripts')
<script>
    let trs = ''
    var userID = "{{Auth::id()}}";
    var token = "{{Session::token()}}";
    var placeBetURL = "{{route('place.bet')}}";
    @if($event)
    var eventID = "{{$event->id}}";
    var fightID = "{{$fight->id}}";
    var fightNumber = "{{$fight->fight_number}}";
    var fightStatus = "{{$fight->status}}";
    @endif

</script>
<script src="https://js.pusher.com/7.1/pusher.min.js"></script>
<script src="{{ asset('js/bet.js') }}"></script>
<script src="{{ asset('js/realtime.js') }}"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/clappr/latest/clappr.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/clappr.level-selector/latest/level-selector.min.js"></script>
<script>
    function currentTime() {
    let date = new Date();
    let hh = date.getHours();
    let mm = date.getMinutes();
    let ss = date.getSeconds();
    let session = "AM";

    if(hh === 0){
        hh = 12;
    }
    if(hh > 12){
        hh = hh - 12;
        session = "PM";
    }

    hh = (hh < 10) ? "0" + hh : hh;
    mm = (mm < 10) ? "0" + mm : mm;
    ss = (ss < 10) ? "0" + ss : ss;

    let time = hh + ":" + mm + ":" + ss + " " + session;

    document.getElementById("clock").innerText = time;
    let t = setTimeout(function(){ currentTime() }, 1000);
    }

    currentTime();
</script>
@endsection
