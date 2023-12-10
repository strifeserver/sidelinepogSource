<?php $__env->startSection('styles'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong><i class="fas fa-bars"></i> List of APPROVAL PLAYERS</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search username" id="username-search">
                                <div class="input-group-append">
                                  <button class="btn btn-success" id="btn-search" data-url="<?php echo e(route('pending.players')); ?>" type="button">
                                    <i class="fas fa-search"></i>
                                  </button>
                                </div>
                              </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <th>Username</th>
                                <th>Points</th>
                                <th>Action/s</th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $direct_players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($dp->username); ?></td>
                                        <td><?php echo e($dp->wallet->balance); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-change-status <?php echo e($dp->status == 'active' ? 'btn-outline-danger' : 'btn-outline-success'); ?> btn-sm" data-id="<?php echo e($dp->id); ?>" data-status="<?php echo e($dp->status); ?>">
                                                <i class="fas fa-circle"></i> <?php echo e($dp->status == 'active' ? 'DEACTIVATE' : 'ACTIVATE'); ?>

                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <?php echo e($direct_players->links()); ?>

                        </div>
                    </div>
                </div>
              </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php echo $__env->make('partials._jsvariables', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script src="<?php echo e(asset('js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/players/pending.blade.php ENDPATH**/ ?>