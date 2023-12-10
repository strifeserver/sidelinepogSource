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
                <strong>WITHDRAWAL LOGS</strong>
            </div>
            <div class="card-body">
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
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/wallet/withdraws.blade.php ENDPATH**/ ?>