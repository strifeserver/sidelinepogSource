<?php $__env->startSection('styles'); ?>
<style>
    .completed{
        color: #dc3545;
        font-weight: bolder;
    }

    .open{
        color: #28a745;
        font-weight: bolder;
    }
    .closed{
        color: #ffc107;
        font-weight: bolder;
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>ALL EVENTS - <?php echo e($user->name); ?></strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <th>ID</th>
                                <th>Event Name</th>
                                <th>Date Created</th>
                                <th>Status</th>
                                <th>Action/s</th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($event->event_id); ?></td>
                                    <td><?php echo e($event->name); ?></td>
                                    <td><?php echo e(date('m/d/Y',strtotime($event->created_at))); ?></td>
                                    <td><span class="<?php echo e($event->status); ?>"><?php echo e(strtoupper($event->status)); ?></span></td>
                                    <td>
                                        <a href="<?php echo e(route('agent.commission',['id'=>$id,'event'=>$event->id])); ?>" class="btn btn-primary btn-sm"><strong>VIEW COMMISSION HISTORY</strong></a>
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
            "ordering": false
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/wallet/events.blade.php ENDPATH**/ ?>