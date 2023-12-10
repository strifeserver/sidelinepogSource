<?php $__env->startSection('styles'); ?>
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
    @media  only screen and (max-width: 1444px) {
        #vid{
            height: 335px;
            background: #000;
        }
    }
    @media  only screen and (max-width: 900px) {
        #vid{
            height: 215px;
            background: #000;
        }
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>EVENT</strong>
                    <?php if($event->status == 'closed'): ?>
                        <button class="btn btn-success btn-event-status btn-sm float-right" data-url="<?php echo e(route('start.event', $event->id)); ?>">START EVENT</button>
                    <?php endif; ?>

                    <?php if($event->status == 'open'): ?>
                        <button class="btn btn-danger btn-event-status btn-sm float-right" data-url="<?php echo e(route('end.event', $event->id)); ?>">END EVENT</button>
                    <?php endif; ?>


                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Live Video</strong>
                            <div id="vid">
                                <?php if($event->status == 'open' || $event->status == 'closed'): ?>
                                <iframe width="100%" height="400" src="<?php echo e($event->live_url); ?>" title="Live Cockfight" frameborder="1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                <?php endif; ?>
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
                                                        <?php $__currentLoopData = $allBets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr class="bets-data">
                                                            <td><?php echo e($i+1); ?></td>
                                                            <td><?php echo e($b->username); ?></td>
                                                            <td><span class="badge <?php echo e($b->bet); ?>"><?php echo e(strtoupper($b->bet)); ?></span></td>
                                                            <td><?php echo e($b->amount); ?></td>
                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <?php if($event->status == 'open'): ?>
                            
                            <div class="card">
                                <div class="card-header">
                                    <strong>DECLARE WINNER</strong>
                                    <span class="float-right font-weight-bolder">FIGHT #<span class="fight-number"><?php echo e($fight->fight_number); ?></span></span>
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
                                                <td class="bets-meron"><?php echo e(bcdiv($betMeron,1,2)); ?></td>
                                                <td class="bets-wala"><?php echo e(bcdiv($betWala,1,2)); ?></td>
                                                <td class="text-danger">
                                                    <strong class="odds-meron"><?php echo e($odds['meron']); ?></strong>
                                                </td>
                                                <td class="text-primary">
                                                    <strong class="odds-wala"><?php echo e($odds['wala']); ?></strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('partials.modals._redeclareWinner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startSection('scripts'); ?>
<script>
    var currentFightId = "<?php echo e($fight->id); ?>";
    var token = "<?php echo e(Session::token()); ?>";
    var event_id = "<?php echo e($event->id); ?>";
    var fightStat = "<?php echo e(route('fight.status')); ?>";
    var declareWin = "<?php echo e(route('declare.winner')); ?>";
    var jumpFight = "<?php echo e(route('jump.fight')); ?>";
    var redeclareWin = "<?php echo e(route('redeclare.winner')); ?>";
    var ghost = "<?php echo e(route('place.ghost')); ?>";
    var results = <?php echo $wins; ?>;
    var fightStatus = "<?php echo e($fight->status); ?>";
    let trs = ''
    let trd = ''
    var eventIDString = "<?php echo e($event->event_id); ?>";
    var placeBetURL = "<?php echo e(route('auto.place.bet')); ?>";
    var betAmt = 0;
    var oddsMeron = "<?php echo e($odds['meron']); ?>";
    var oddsWala = "<?php echo e($odds['wala']); ?>";
    var maxRow = 8;
    $('#btn-declarator button').prop('disabled',true);
    $('.select2').select2();
</script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<script src="<?php echo e(asset('js/declare-realtime.js')); ?>"></script>
<script src="<?php echo e(asset('js/declare.js')); ?>"></script>

<script>
    // var player = new Clappr.Player({
    //         source: "<?php echo e($event->live_url); ?>",
    //         mimeType: "application/x-mpegURL",
    //         autoPlay: true,
    //         height: "100%",
    //         width: "100%",
    //         plugins: {"core": [LevelSelector]},
    //         parentId: "#vid"
    // });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/events/show.blade.php ENDPATH**/ ?>