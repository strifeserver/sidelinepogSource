<?php $__env->startSection('styles'); ?>
    <style>
        .fs-small{
            font-size: 13px;
        }
        button{
            font-weight: bolder;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>GENERAL ADMINS</strong>
                    <?php if(Auth::user()->type == 'super-admin'): ?>
                        <a href="<?php echo e(route('create.admin')); ?>" class="btn btn-success float-right">CREATE</a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Username</th>
                                <th>Wallet Balance</th>
                                <th>Action/s</th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(ucwords($op->name)); ?></td>
                                    <td><?php echo e($op->contact_number); ?></td>
                                    <td><?php echo e($op->username); ?></td>
                                    <td><?php echo e($op->wallet->balance); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('edit.account',$op->id)); ?>"  class="btn btn-success btn-sm">EDIT</a>
                                        <button type="button" class="btn btn-primary btn-sm btn-deposit" data-id="<?php echo e($op->id); ?>" >DEPOSIT</button>
                                        <button type="button" class="btn btn-danger btn-sm btn-return" data-id="<?php echo e($op->id); ?>" >WITHDRAW</button>
                                        <button type="button" class="btn btn-change-status <?php echo e($op->status == 'active' ? 'btn-outline-danger' : 'btn-outline-success'); ?> btn-sm" data-id="<?php echo e($op->id); ?>" data-status="<?php echo e($op->status); ?>">
                                            <i class="fas fa-circle"></i> <?php echo e($op->status == 'active' ? 'DEACTIVATE' : 'ACTIVATE'); ?>

                                        </button>
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
    <?php echo $__env->make('partials.modals._depositAmount', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.modals._setCommissionPercent', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.modals._withdrawLoad', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php echo $__env->make('partials._jsvariables', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script src="<?php echo e(asset('js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/admins/index.blade.php ENDPATH**/ ?>