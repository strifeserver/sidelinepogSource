<?php $__env->startSection('styles'); ?>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <?php if(Auth::user()->type == 'super-admin'): ?>
        <div class="col-md-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>₱ <?php echo e(bcdiv(Auth::user()->wallet->balance,1,2)); ?></h3>
                    <p>Wallet Balance</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <a href="#" data-toggle="modal" data-target="#addSystemBalanceModal" class="btn-sm small-box-footer text-white"><i class="fas fa-coins"></i> Add Credits</a>
            </div>
        </div>
    <?php else: ?>
        <div class="col-md-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>₱ <?php echo e(bcdiv(Auth::user()->wallet->balance,1,2)); ?></h3>
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
                    <h3>₱ <?php echo e(bcdiv(Auth::user()->wallet->commission,1,2)); ?></h3>
                    <p>Commission (<?php echo e(Auth::user()->commission); ?>%)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <a href="#" data-target="#convertCommissionModal" data-toggle="modal" class="btn-sm small-box-footer text-white">
                    <i class="fas fa-coins"></i> Convert Commission
                </a>
            </div>
        </div>
    <?php endif; ?>


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

                    <?php if(Auth::user()->type != 'player' && Auth::user()->type != 'super-admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#commission">COMMISSIONS</a>
                        </li>
                    <?php endif; ?>

                    <?php if(Auth::user()->type == 'player'): ?>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#bets">BETS</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#refunds">REFUNDS</a>
                        </li>
                    <?php endif; ?>
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
                                    <?php $__currentLoopData = $loads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($i+1); ?></td>
                                            <td><?php echo e(($n->user_to)); ?></td>
                                            <td><?php echo e(($n->user_from)); ?></td>
                                            <td><?php echo e(bcdiv($n->amount,1,2)); ?></td>
                                            <td><?php echo e(bcdiv($n->ending_balance,1,2)); ?></td>
                                            <td><?php echo e(($n->remarks)); ?></td>
                                            <td><?php echo e(($n->user_notes)); ?></td>
                                            <td><?php echo e(date('m/d/Y h:i:s A',strtotime($n->created_at))); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                    <?php $__currentLoopData = $withdraws; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($i+1); ?></td>
                                            <td><?php echo e(($n->user_to)); ?></td>
                                            <td><?php echo e(($n->user_from)); ?></td>
                                            <td><?php echo e(bcdiv($n->amount,1,2)); ?></td>
                                            <td><?php echo e(bcdiv($n->ending_balance,1,2)); ?></td>
                                            <td><?php echo e(($n->remarks)); ?></td>
                                            <td><?php echo e(($n->user_notes)); ?></td>
                                            <td><?php echo e(date('m/d/Y h:i:s A',strtotime($n->created_at))); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                    <?php $__currentLoopData = $myCommissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $comm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(Auth::user()->type == 'operator'): ?>
                                        <?php if($comm->operator_commission > 0): ?>
                                            <tr>
                                                <td><?php echo e($i+1); ?></td>
                                                <td><?php echo e($comm->id); ?></td>
                                                <td><?php echo e(strtoupper($comm->event_name)); ?></td>
                                                <td><?php echo e($comm->fight_number); ?></td>
                                                <td><?php echo e(bcdiv($comm->amount,1,2)); ?></td>
                                                <td><?php echo e(bcdiv($comm->operator_commission,1,2)); ?></td>
                                                <td><?php echo e(strtoupper($comm->result)); ?></td>
                                                <td><?php echo e(date('M d,Y h:i A',strtotime($comm->created_at))); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php elseif(Auth::user()->type == 'sub-operator'): ?>
                                        <?php if($comm->sub_operator_commission > 0): ?>
                                        <tr>
                                            <td><?php echo e($i+1); ?></td>
                                            <td><?php echo e($comm->id); ?></td>
                                            <td><?php echo e(strtoupper($comm->event_name)); ?></td>
                                            <td><?php echo e($comm->fight_number); ?></td>
                                            <td><?php echo e(bcdiv($comm->amount,1,2)); ?></td>
                                            <td><?php echo e(bcdiv($comm->sub_operator_commission,1,2)); ?></td>
                                            <td><?php echo e(strtoupper($comm->result)); ?></td>
                                            <td><?php echo e(date('M d,Y h:i A',strtotime($comm->created_at))); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    <?php elseif(Auth::user()->type == 'master-agent'): ?>
                                        <?php if($comm->master_agent_commission > 0): ?>
                                            <tr>
                                                <td><?php echo e($i+1); ?></td>
                                                <td><?php echo e($comm->id); ?></td>
                                                <td><?php echo e(strtoupper($comm->event_name)); ?></td>
                                                <td><?php echo e($comm->fight_number); ?></td>
                                                <td><?php echo e(bcdiv($comm->amount,1,2)); ?></td>
                                                <td><?php echo e(bcdiv($comm->master_agent_commission,1,2)); ?></td>
                                                <td><?php echo e(strtoupper($comm->result)); ?></td>
                                                <td><?php echo e(date('M d,Y h:i A',strtotime($comm->created_at))); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php elseif(Auth::user()->type == 'gold-agent'): ?>
                                        <?php if($comm->gold_agent_commission > 0): ?>
                                            <tr>
                                                <td><?php echo e($i+1); ?></td>
                                                <td><?php echo e($comm->id); ?></td>
                                                <td><?php echo e(strtoupper($comm->event_name)); ?></td>
                                                <td><?php echo e($comm->fight_number); ?></td>
                                                <td><?php echo e(bcdiv($comm->amount,1,2)); ?></td>
                                                <td><?php echo e(bcdiv($comm->gold_agent_commission,1,2)); ?></td>
                                                <td><?php echo e(strtoupper($comm->result)); ?></td>
                                                <td><?php echo e(date('M d,Y h:i A',strtotime($comm->created_at))); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php elseif(Auth::user()->type == 'silver-agent'): ?>
                                        <?php if($comm->silver_agent_commission > 0): ?>
                                            <tr>
                                                <td><?php echo e($i+1); ?></td>
                                                <td><?php echo e($comm->id); ?></td>
                                                <td><?php echo e(strtoupper($comm->event_name)); ?></td>
                                                <td><?php echo e($comm->fight_number); ?></td>
                                                <td><?php echo e(bcdiv($comm->amount,1,2)); ?></td>
                                                <td><?php echo e(bcdiv($comm->silver_agent_commission,1,2)); ?></td>
                                                <td><?php echo e(strtoupper($comm->result)); ?></td>
                                                <td><?php echo e(date('M d,Y h:i A',strtotime($comm->created_at))); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                    <?php $__currentLoopData = $bets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($i+1); ?></td>
                                            <td><?php echo e($b->event_name); ?></td>
                                            <td><?php echo e($b->fight_number); ?></td>
                                            <td><?php echo e(bcdiv($b->amount,1,2)); ?></td>
                                            <td><?php echo e($b->direction == 'out' ? '0' : $b->amount_won); ?></td>
                                            <td><?php echo e(bcdiv($b->ending_balance,1,2)); ?></td>
                                            <td><span class="<?php echo e($b->bet); ?> text-uppercase"><?php echo e($b->bet); ?></span> <span class="badge <?php echo e(strpos($b->result,$b->bet) !== false ? 'badge-success':'badge-danger'); ?>"><?php echo e(strpos($b->result,$b->bet) !== false ? 'WIN':'LOSE'); ?></span></td>
                                            <td><span class="text-uppercase"><?php echo e($b->result); ?></span></td>
                                            <td><?php echo e($b->remarks); ?></td>
                                            <td><?php echo e(date('m/d/Y h:i:s A',strtotime($b->created_at))); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                    <?php $__currentLoopData = $refunds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($i+1); ?></td>
                                        <td><?php echo e($b->event_id); ?></td>
                                        <td><?php echo e($b->fight_number); ?></td>
                                        <td><?php echo e($b->amount); ?></td>
                                        <td><?php echo e($b->ending_balance); ?></td>
                                        <td><span class="<?php echo e($b->direction); ?>"><?php echo e($b->direction); ?></span></td>
                                        <td><?php echo e($b->remarks); ?></td>
                                        <td><?php echo e(date('m/d/Y h:i:s A',strtotime($b->created_at))); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php if(Auth::user()->type == 'super-admin'): ?>
    <?php echo $__env->make('partials.modals._addCredits', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.modals._addSystemBalance', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.modals._widthdrawCommission', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.modals._withdrawCredits', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('partials.modals._convertCommission', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startSection('scripts'); ?>
<script>
    $(document).ready(function () {
        $('.table').DataTable({
            "ordering": false
        });
        $('.select').select2();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/wallet/index.blade.php ENDPATH**/ ?>