<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/client.css')); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    #fight-number{
        font-size: 18px;
    }
    #vid{
        /* height: 620px; */
        position: relative;
        padding-bottom: 56.25%;
    }

    #vid>iframe{
            /* height: 620px; */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
    }
    /* @media  only screen and (max-width: 768px) {
        #vid{
            height: 280px;
        }

        #vid>iframe{
            height: 250px;
        }
    } */
    .btn-bet-amt{
        font-size: 13px !important;
    }
    /* .bg-warning{
        background-color: #ff6a07!important;
    } */
    .border{
        padding: 0px !important;
        border-color: #ffc107 !important;
        border-radius: 10px;
        padding-bottom: 0.25rem !important;

    }
    .rules-text{
        font-size: 20px;
        font-weight: bolder;
    }
    .rules-emp{
        font-size: 16px;
        font-weight: bolder;
    }

    .btn-primary {
        color: #fff;
        background-color: #007bff !important;
        border-color: #007bff !important;
        box-shadow: none;
    }

    .btn-warning {
        color: #1f2d3d;
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
        box-shadow: none;
    }

    .content-wrapper{
        background-image: url("<?php echo e(asset('images/bg-ppc.png')); ?>");
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        color: #fff;
    }
    body,aside{
        background-color: #000 !important;
    }

    .dark-mode .card{
        background-color: #1e2024 !important;
    }

    .bg-black{
        background-color: #1e2024 !important;
    }

    .statusChange{
        padding: 5px 15px;
        border-radius: 5px;
    }

    .odds {
        font-size: 18px;
        font-weight: 400;
        display: inline-block !important;
    }
    .winnings {
        color: rgb(255, 255, 255) !important;
        font-size: 16px;
    }
    .rowcol-border{
        padding:10px 5px;
        border: 1px solid #dee2e6!important;
    }
    .remove-side-margins{
        margin-left: 0px !important;
        margin-right: 0px !important;
    }

    .remove-side-paddings{
        padding-left: 0px !important;
        padding-right: 0px !important;
    }
    .marquee {
        height: 35px;
        width: 100%;
        padding: 5px 0px;
        overflow: hidden;
        position: relative;
    }

    .marquee div {
        display: block;
        width: 200%;
        height: 30px;

        position: absolute;
        overflow: hidden;

        animation: marquee 15s linear infinite;
    }

    .marquee span {
        float: left;
        width: 50%;
        font-weight: 700;
        font-size: 13.5px !important;
    }

    @keyframes  marquee {
        0% { left: 0; }
        100% { left: -100%; }
    }
    .card-body{
        padding: 1rem !important;
        padding-top: 0.5rem !important;
    }
    .warning-below-120{
        font-size: 12px !important;
    }

    .sticky { position:fixed; top:0; width: 93%; z-index: 1100;}

    .black-text{
        color: #1e2024 !important;
    }
    .bet-header{
        border-top-right-radius: 10px;
        border-top-left-radius: 10px;
    }

    .col-6-override{
        max-width: 49% !important;
    }

    .ml-10{
        margin-left: 5px !important;
    }
    .rad-5{
        border-radius: 5px;
    }
    @import  url('https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@400;500;700&display=swap');

    #clock,#date,#username{
        font-size: 15px;
        text-align: center;
        font-family: 'Source Code Pro', monospace;
        text-shadow: 0 0 5px #00c6ff;
        font-weight: bold;
    }
    #username{
        margin-left: 15px;
    }
    .text-18{
        font-size: 18px !important;
    }

    .bet-input{
        height: 50px;
        font-weight: 400;
        border-radius: 3px;
        padding: 1rem;
    }
    .counts-table tr td div{
        width: 32px;
        height: 32px;
        text-align: center;
        vertical-align: middle;
        border-radius: 55px;
        line-height: 2;
        margin: auto;
    }

    .counts-table tr td h5{
        text-align: center;
        margin-top: 15px
    }

    .counts-table tr td h5{
        font-size: 14px;
    }

    .prevResult{
        text-transform:uppercase;
        font-size: 14px;
    }
    .prevFNum{
        font-size: 14px;
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row remove-side-margins">
        <div class="col-md-6 mb-2 mt-2">
            <?php if($event): ?>
                <?php if(Auth::user()->wallet->balance >= 0 || $myBetMeron > 0 || $myBetWala > 0): ?>
                    <?php if($event->status == 'open'): ?>
                        <div class="row mb-0 pt-1 pb-1 remove-side-margins">
                            <div class="col-12 text-left text-warning font-weight-bolder" >
                            <span><?php echo e(strtoupper($event->name)); ?></span>
                            </div>
                        </div>
                        <div class="row mb-0 pt-1 pb-1 remove-side-margins" style="margin-top: -6px;">
                            <div class="col-12 text-center">
                                <div class="text-white">
                                    <span id="date"></span>  <span id="clock" class="timenow"></span> <span id="username"><?php echo e(Auth::user()->username); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="video-container" id="vid">
                            <iframe width="100%" scrolling="no" src="<?php echo e($event->live_url); ?>" title="Live Cockfight" frameBorder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    <?php else: ?>
                        <div class="no-fight-available">
                            <span class="no-available">No Fight Available. Refresh the page or try again later.</span>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="no-fight-available">
                        <span class="no-available">No Credits. Please add credits to watch live stream.</span>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-fight-available">
                    <span class="no-available">No Fight Available. Refresh the page or try again later.</span>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <?php if(Auth::user()->wallet->balance >= 0 || $myBetMeron > 0 || $myBetWala > 0 || Auth::id() == 10): ?>
                <div class="card mb-1">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col remove-side-paddings">
                                <div class="bg-warning rad-5">
                                    <div class="p-1 text-center">
                                        <strong>
                                            <span class="warning-below-120 black-text">Payout with 120 and below shall be cancelled.</span>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col text-center text-white font-weight-bolder rowcol-border">
                                <span>BETTING</span>
                            </div>
                            <div class="col text-center text-white font-weight-bolder rowcol-border" style="margin-left: -1px">
                                <span>FIGHT #</span> <span class="badge badge-secondary"> <strong class="prevFNum"></strong>: <strong class="prevResult"></strong></span>
                            </div>
                        </div>

                        <div class="row" style="margin-top: -1px;">
                            <div class="col text-center text-white font-weight-bolder rowcol-border" id="fight-status">
                                <?php if($event): ?>
                                    <span class="statusChange"><?php echo e(removeUnderscore($fight->status)); ?></span>
                                <?php else: ?>
                                    <span class="statusChange">NO FIGHT AVAILABLE</span>
                                <?php endif; ?>
                            </div>
                            <div class="col text-center text-white font-weight-bolder rowcol-border"  style="margin-left: -1px">
                                <span><span class="text-warning" id="fight-number"></span></span>
                            </div>
                        </div>

                        <div class="row betting mb-2 mt-2">
                            <div class="col-6 text-center border col-6-override mb-1 pb-1">
                                <h3 class="text-white bg-danger p-1 bet-header"><strong>MERON</strong></h3>
                                <span class="text-warning bets" id="betMeron">0</span>
                                <div class="text-18">PAYOUT= <span class="odds text-warning"><span class="percentage" id="percentMeron"></span>%</span></div>

                                <div class="winnings meron-wins text-18 mb-3">
                                    <?php echo e(number_format($myBetMeron*$timer->value,0,".",",")); ?>

                                </div>
                                <button class="btn btn-danger bet-btn btn-lg btn-lock-bet btn-block text-18" data-bet="meron"><i class="fas fa-plus-circle"></i> <strong>BET MERON</strong></button>
                            </div>

                            <div class="col-6 text-center border col-6-override ml-10 mb-1 pb-1">
                                <h3 class="text-white bg-primary p-1 bet-header"><strong>WALA</strong></h3>
                                <span class="text-warning bets" id="betWala">0</span>
                                <div class="text-18">PAYOUT= <span class="odds text-warning"><span class="percentage" id="percentWala"></span>%</span></div>

                                <div class="winnings wala-wins text-18 mb-3"><?php echo e(number_format($myBetWala*$timer->value,2,".",",")); ?></div>
                                <button class="btn btn-primary bet-btn btn-lg btn-lock-bet btn-block text-18" data-bet="wala"><i class="fas fa-plus-circle"></i> <strong>BET WALA</strong></button>
                            </div>
                        </div>
                        <div class="row pb-2">
                            <div class="col-md-8">
                                <div class="input-group input-group-sm w-75">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="background-color: transparent;border: none;">
                                            <strong class="mr-2 text-18">Current Points: </strong>
                                            <i class="fas fa-money-bill-alt text-18 text-warning"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="pl-0 text-18 form-control text-warning font-weight-bold wallet-balance border-0 bg-black" style="color:#ffc107!important;" readonly value="<?php echo e(bcdiv(Auth::user()->wallet->balance,1,2)); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center p-0 mt-3">
                                <div class="form-group">
                                    <input type="number" id="input-bet-amount" placeholder="Bet Amount" min="100" step="1"  class="form-control text-18 bet-input">
                                </div>

                                <div class="btn-group d-flex mb-0" role="group" aria-label="Basic example">
                                    <button type="button" data-value="20" class="btn-bet-amt btn btn-outline-warning w-100">20</button>
                                    <button type="button" data-value="50" class="btn-bet-amt btn btn-outline-warning w-100">50</button>
                                    <button type="button" data-value="100" class="btn-bet-amt btn btn-outline-warning w-100">100</button>
                                    <button type="button" data-value="500" class="btn-bet-amt btn btn-outline-warning w-100">500</button>
                                    <button type="button" data-value="1000" class="btn-bet-amt btn btn-outline-warning w-100">1000</button>
                                    <button type="button" data-value="5000" class="btn-bet-amt btn btn-outline-warning w-100">5000</button>
                                </div>
                            </div>
                        </div>
                        <div class="row winner mt-3 border-top">
                            <div class="col text-center">
                                <div class="result">RESULT: <span class="fight-result"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-warning bet-btn btn-lg btn-lock-bet btn-block text-18" data-bet="draw"><i class="fas fa-plus-circle"></i> <strong>BET DRAW</strong></button>
                                <p class="mt-2 text-white">DRAW WINS X 8. Max. DRAW bet 1000/fight</p>
                            </div>
                            <div class="col text-center">
                                <span class="text-warning bets text-18" id="betDraw" style="display: none"></span>
                                <div class="winnings draw-winnings mt-1 text-18">
                                    <?php echo e(number_format($myBetDraw,0,".",",")); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="card">
                <div class="card-body bg-white">
                    <div class="table-responsive">
                        <table class="table trend-table"></table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body bg-dark">
                    <table class="table counts-table">
                        <tr>
                            <td>
                                <div id="countMeron" class="bg-danger">0</div>
                                <h5>MERON</h5>

                            </td>
                            <td >
                                <div id="countWala" class="bg-primary">0</div>
                                <h5>WALA</h5>

                            </td>
                            <td >
                                <div id="countDraw" class="bg-warning">0</div>
                                <h5>DRAW</h5>

                            </td>
                            <td >
                                <div id="countCancel" class="bg-secondary">0</div>
                                <h5>CANCEL</h5>

                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body bg-white">
                    <div class="table-responsive">
                        <table class="table winnings-table"></table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">

        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    let trs = '';
    let wintrs = '';
    var userID = "<?php echo e(Auth::id()); ?>";
    var token = "<?php echo e(Session::token()); ?>";
    var placeBetURL = "<?php echo e(route('place.bet')); ?>";
    <?php if($event): ?>
    var eventID = "<?php echo e($event->id); ?>";
    var fightID = "<?php echo e($fight->id); ?>";
    var fightNumber = "<?php echo e($fight->fight_number); ?>";
    var fightStatus = "<?php echo e($fight->status); ?>";
    var betMeron = "<?php echo e(str_replace(',', '', $betMeron)); ?>";
    var betWala = "<?php echo e(str_replace(',', '', $betWala)); ?>";
    var betDraw = "<?php echo e(str_replace(',', '', $betDraw)); ?>";
    var percentWala = "<?php echo e($odds['wala']); ?>";
    var percentMeron = "<?php echo e($odds['meron']); ?>";
    var results = <?php echo $wins; ?>;
    var myBetWala = "<?php echo e($myBetWala); ?>"
    var myBetMeron = "<?php echo e($myBetMeron); ?>"
    var myBetDraw = "<?php echo e($myBetDraw); ?>"
    var eventIDString = "<?php echo e($event->event_id); ?>";
    var maxRow = 8;
    var multiplier = "<?php echo e($timer->value); ?>";
    <?php endif; ?>

    function startTime() {
        var today = new Date();
        var hr = today.getHours();
        var min = today.getMinutes()-1;
        var sec = today.getSeconds();
        ap = (hr < 12) ? "<span>AM</span>" : "<span>PM</span>";
        hr = (hr == 0) ? 12 : hr;
        hr = (hr > 12) ? hr - 12 : hr;
        //Add a zero in front of numbers<10
        hr = checkTime(hr);
        min = checkTime(min);
        sec = checkTime(sec);
        document.getElementById("clock").innerHTML = hr + ":" + min + ":" + sec + " " + ap;

        var months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        var curWeekDay = days[today.getDay()];
        var curDay = today.getDate();
        var curMonth = months[today.getMonth()];
        var curYear = today.getFullYear();
        var date = curWeekDay+" "+curMonth+"/"+curDay+"/"+curYear;
        document.getElementById("date").innerHTML = date;

        var time = setTimeout(function(){ startTime() }, 500);
    }
    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }
    startTime();
</script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<script src="<?php echo e(asset('js/bet.js')); ?>"></script>
<script src="<?php echo e(asset('js/realtime.js')); ?>"></script>
<script>
    function make_sticky(id) {
    var e = $(id);
    var w = $(window);
    $('<div/>').insertBefore(id);
    $('<div/>').hide().css('height',e.outerHeight()).insertAfter(id);
    var n = e.next();
    var p = e.prev();
    function sticky_relocate() {
      var window_top = w.scrollTop();
      var div_top = p.offset().top;
      if (window_top > div_top) {
        e.addClass('sticky');
        n.show();
      } else {
        e.removeClass('sticky');
        n.hide();
      }
    }
    w.scroll(sticky_relocate);
    sticky_relocate();
}

// make_sticky('#vid');

function countMeronWins(){
    let meronCounts = results.filter(function(item){
        return item.result == "meron";
    })

    $('#countMeron').text(meronCounts.length)
}

function countWalaWins(){
    let walaCounts = results.filter(function(item){
        return item.result == "wala";
    })

    $('#countWala').text(walaCounts.length)
}

function countDrawWins(){
    let drawCounts = results.filter(function(item){
        return item.result == "draw";
    })

    $('#countDraw').text(drawCounts.length)
}

function countCancelWins(){
    let cancelCounts = results.filter(function(item){
        return item.result == "cancelled";
    })

    $('#countCancel').text(cancelCounts.length)
}

function getLastWinningResult(){
    let len = results.length;

    let prevFight = results[len-1];

    $('.prevFNum').text(prevFight.fight_number);
    $('.prevResult').text(prevFight.result);
}
countCancelWins();
countDrawWins();
countWalaWins();
countMeronWins();
getLastWinningResult();

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/client/betting.blade.php ENDPATH**/ ?>