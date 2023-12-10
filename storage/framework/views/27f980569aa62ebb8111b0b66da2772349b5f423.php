<?php $__env->startSection('styles'); ?>
<style>
    .not_open{
        color: #ffc107;
        font-weight: bolder;
    }

    .last_call{
        color: #ffc107;
        font-weight: bolder;
    }

    .finished{
        color: #28a745;
        font-weight: bolder;
    }

    .open{
        color: #28a745;
        font-weight: bolder;
    }
    .closed{
        color: #dc3545;
        font-weight: bolder;
    }

    .wala{
        color: #007bff;
        font-weight: bolder;
        text-transform: uppercase;
    }
    .meron{
        color: #dc3545;
        font-weight: bolder;
        text-transform: uppercase;
    }
    .cancelled{
        color: #6c757d;
        font-weight: bolder;
        text-transform: uppercase;
    }
    .draw{
        color: #ffc107;
        font-weight: bolder;
        text-transform: uppercase;
    }

</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>ALL FIGHTS</strong>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>ID</th>
                            <th>Fight Number</th>
                            <th>Fight Result</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action/s</th>
                        </thead>
                        <tbody>
                            <?php
                                $fightLen = count($fights);
                            ?>
                            <?php $__currentLoopData = $fights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($f->result != NULL): ?>
                                <tr>
                                    <td><?php echo e($f->id); ?></td>
                                    <td><?php echo e($f->fight_number); ?></td>
                                    <td>
                                        <?php if($f->result == NULL): ?>
                                            <span class="draw">UNDECLARED FIGHT</span>
                                        <?php else: ?>
                                            <span class="<?php echo e($f->result); ?>"><?php echo e($f->result); ?></span>
                                        <?php endif; ?>

                                    </td>
                                    <td><?php echo e(date('m/d/Y',strtotime($f->created_at))); ?></td>
                                    <td><span class="<?php echo e($f->status); ?>"><?php echo e(strtoupper($f->status)); ?></span></td>
                                    <td>
                                        <a href="<?php echo e(route('show.bets', $f->id)); ?>" class="btn btn-secondary btn-sm"><strong>VIEW BETS</strong></a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
              </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<script>
    $(document).ready(function () {
        $('.table').DataTable();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/events/fights.blade.php ENDPATH**/ ?>