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
                    <strong>CREATE EVENT</strong>

                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('event.create')); ?>" method="POST">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Event ID</label>
                                    <input type="text" name="event_id" class="form-control" value="<?php echo e(uniqid()); ?>" readonly>
                                </div>
                                <?php echo e(csrf_field()); ?>


                                <div class="form-group">
                                    <label>Game</label>
                                    <select name="game_id" class="form-control" required>
                                        <option value="">Select Game</option>
                                        <?php $__currentLoopData = $games; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($g->id); ?>"><?php echo e(strtoupper($g->name)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Event Name</label>
                                    <input type="text" maxlength="35" name="name" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Live Fight URL</label>
                                    <input type="text" name="live_url" class="form-control" required>
                                </div>

                                <button class="btn btn-success btn-sm float-right">SAVE EVENT</button>
                            </div>
                            <div class="col-md-7 offset-md-1">
                                <strong>Video Preview</strong>
                                <div id="vid"></div>
                                
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/events/create.blade.php ENDPATH**/ ?>