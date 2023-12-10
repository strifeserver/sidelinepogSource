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
    .notes{
        font-style: italic;
        font-size: 12px;
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
                <a href="#" data-toggle="modal" data-target="#addSystemBalanceModal" class="btn-sm small-box-footer text-white"><i class="fas fa-coins"></i> Create Points</a>
            </div>
        </div>
    <?php endif; ?>
    <?php if(Auth::user()->type == 'player'): ?>
    <div class="col-md-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>₱ <?php echo e(bcdiv(Auth::user()->wallet->balance,1,2)); ?></h3>
                <p>Wallet Balance</p>
            </div>
            <div class="icon">
                <i class="fas fa-wallet"></i>
            </div>
            
        </div>
    </div>
    <?php endif; ?>
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
                            <?php $__currentLoopData = $trans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    if($n->bet_id != null){
                                        $eventData = getEventDataFromBetID($n->bet_id);
                                        //$fightData = getFightDataFromBetID($n->bet_id);
                                    }
                                ?>
                                <tr>
                                    <td><?php echo e(date('m/d/Y h:i:s A',strtotime($n->created_at))); ?></td>
                                    <td><?php echo e($n->bet_id == null ? 'PASALOAD' : 'BET'); ?></td>
                                    <td><span class="badge <?php echo e($n->direction == 'out' ? 'badge-danger' : 'badge-success'); ?>">
                                        <?php echo e($n->direction == 'out' ? '-' : '+'); ?>

                                        <?php if($n->bet_id == null): ?>
                                            <?php echo e(bcdiv($n->amount,1,2)); ?>

                                        <?php else: ?>
                                            <?php
                                                $betDetails = \App\Models\Bet::find($n->bet_id);
                                            ?>
                                            <?php if($n->direction == 'in'): ?>
                                                 <?php echo e(bcdiv($betDetails->amount_won,1,2)); ?>

                                            <?php else: ?>
                                                <?php echo e(bcdiv($betDetails->amount,1,2)); ?>

                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </span></td>
                                    <td><?php echo e($n->bet_id == null ? '' : $eventData['event']->name); ?></td>
                                    <td><?php echo e($n->bet_id == null ? '' : date('m/d/Y',strtotime($eventData['event']->created_at))); ?></td>
                                    <td><?php echo e($n->bet_id == null ? '' : $eventData['fight']->fight_number); ?></td>
                                    <td><?php echo e($n->bet_id == null ? '' : strtoupper($eventData['bet']->bet)); ?></td>
                                    <td><?php echo e($n->bet_id == null ? '' : bcdiv($n->amount,1,2)); ?></td>
                                    <td>
                                        <?php if($n->load_id != null): ?>
                                        <?php echo e(findLoadedBy($n->load_id)->username); ?>

                                        <?php endif; ?>

                                        <?php if($n->withdraw_id != null): ?>
                                        <?php echo e(findWithdrawnBy($n->withdraw_id)->username); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(bcdiv($n->ending_balance,1,2)); ?></td>
                                    <td>
                                        <p><?php echo e(($n->remarks)); ?></p>
                                        <p class="notes"><?php echo e($n->user_notes); ?></p>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script>
    $(document).ready(function () {
        $('.table').DataTable({
            "ordering": false,
            "pageLength" : 50,
        });
        $('.select').select2();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/client/wallet.blade.php ENDPATH**/ ?>