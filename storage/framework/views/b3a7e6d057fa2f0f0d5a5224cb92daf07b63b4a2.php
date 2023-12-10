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
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>COMMISSION LOGS</strong>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>#</th>
                            <th>Event Name</th>
                            <th>Fight Number</th>
                            <th>Bet Amount</th>
                            <th>Earned Commission</th>
                            <th>Date/Time</th>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $myCommissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $comm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($user->type == 'operator'): ?>
                                    <?php if($comm->operator_commission > 0): ?>
                                        <tr>
                                            <td><?php echo e($i+1); ?></td>
                                            <td><?php echo e(strtoupper($comm->event_name)); ?></td>
                                            <td><?php echo e($comm->fight_number); ?></td>
                                            <td><?php echo e(bcdiv($comm->amount,1,2)); ?></td>
                                            <td><?php echo e(bcdiv($comm->operator_commission,1,2)); ?></td>
                                            <td><?php echo e(date('M d,Y h:i A',strtotime($comm->created_at))); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php elseif($user->type == 'sub-operator'): ?>
                                    <?php if($comm->sub_operator_commission > 0): ?>
                                    <tr>
                                        <td><?php echo e($i+1); ?></td>
                                        <td><?php echo e(strtoupper($comm->event_name)); ?></td>
                                        <td><?php echo e($comm->fight_number); ?></td>
                                        <td><?php echo e(bcdiv($comm->amount,1,2)); ?></td>
                                        <td><?php echo e(bcdiv($comm->sub_operator_commission,1,2)); ?></td>
                                        <td><?php echo e(date('M d,Y h:i A',strtotime($comm->created_at))); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                <?php elseif($user->type == 'master-agent'): ?>
                                    <?php if($comm->master_agent_commission > 0): ?>
                                        <tr>
                                            <td><?php echo e($i+1); ?></td>
                                            <td><?php echo e(strtoupper($comm->event_name)); ?></td>
                                            <td><?php echo e($comm->fight_number); ?></td>
                                            <td><?php echo e(bcdiv($comm->amount,1,2)); ?></td>
                                            <td><?php echo e(bcdiv($comm->master_agent_commission,1,2)); ?></td>
                                            <td><?php echo e(date('M d,Y h:i A',strtotime($comm->created_at))); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php elseif($user->type == 'gold-agent'): ?>
                                    <?php if($comm->gold_agent_commission > 0): ?>
                                        <tr>
                                            <td><?php echo e($i+1); ?></td>
                                            <td><?php echo e(strtoupper($comm->event_name)); ?></td>
                                            <td><?php echo e($comm->fight_number); ?></td>
                                            <td><?php echo e(bcdiv($comm->amount,1,2)); ?></td>
                                            <td><?php echo e(bcdiv($comm->gold_agent_commission,1,2)); ?></td>
                                            <td><?php echo e(date('M d,Y h:i A',strtotime($comm->created_at))); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php elseif($user->type == 'silver-agent'): ?>
                                    <?php if($comm->silver_agent_commission > 0): ?>
                                        <tr>
                                            <td><?php echo e($i+1); ?></td>
                                            <td><?php echo e(strtoupper($comm->event_name)); ?></td>
                                            <td><?php echo e($comm->fight_number); ?></td>
                                            <td><?php echo e(bcdiv($comm->amount,1,2)); ?></td>
                                            <td><?php echo e(bcdiv($comm->silver_agent_commission,1,2)); ?></td>
                                            <td><?php echo e(date('M d,Y h:i A',strtotime($comm->created_at))); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endif; ?>

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
            "pageLength" : 50
        });
        $('.select').select2();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/wallet/comms.blade.php ENDPATH**/ ?>