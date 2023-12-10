<?php $__env->startSection('styles'); ?>
<style>
    #vid{
        height: 400px;
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>UPDATE EVENT</strong>

                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('update.event',$event->id)); ?>" method="POST">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Event ID</label>
                                    <input type="text" name="event_id" class="form-control" value="<?php echo e($event->event_id); ?>" readonly>
                                </div>
                                <?php echo e(csrf_field()); ?>

                                <div class="form-group">
                                    <label>Event Name</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo e($event->name); ?>">
                                </div>

                                <div class="form-group">
                                    <label>Live Fight URL</label>
                                    <input type="text" name="live_url" class="form-control" value="<?php echo e($event->live_url); ?>">
                                </div>

                                <button class="btn btn-success btn-sm float-right">UPDATE EVENT</button>
                            </div>
                            <div class="col-md-7 offset-md-1">
                                <strong>Video Preview</strong>
                                <div id="vid">
                                    <iframe width="100%" height="400" src="<?php echo e($event->live_url); ?>" title="Live Cockfight" frameborder="1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>
              </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/events/update.blade.php ENDPATH**/ ?>