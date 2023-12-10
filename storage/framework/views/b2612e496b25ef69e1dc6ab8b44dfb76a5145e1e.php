<?php $__env->startSection('styles'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong><i class="fas fa-bars"></i> List of MASTER AGENTS</strong>
                    
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search username" id="username-search">
                                <div class="input-group-append">
                                  <button class="btn btn-success" id="btn-search" data-url="<?php echo e(route('master.agents')); ?>" type="button">
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
                                <th>Upline</th>
                                <th>Points</th>
                                <th>Commission(%)</th>
                                <th>Earned Commissions</th>
                                <th>Action/s</th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($op->username); ?></td>
                                    <td>
                                        <?php if($op->referred_by != null): ?>
                                            <?php
                                                $us = \App\Models\User::withTrashed()->find($op->referred_by);
                                            ?>
                                            <?php echo e($us->username); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(bcdiv($op->wallet->balance,1,2)); ?></td>
                                    <td><?php echo e($op->commission); ?></td>
                                    <td><?php echo e(bcdiv($op->wallet->commission,1,2)); ?></td>
                                    <td>
                                        <?php if(Auth::user()->type == 'admin' || Auth::user()->type == 'super-admin'): ?>
                                            <a href="<?php echo e(route('edit.account',$op->id)); ?>"  class="btn btn-success btn-sm">EDIT</a>
                                            <a href="<?php echo e(route('dl.players',$op->id)); ?>" class="btn btn-primary btn-sm" >PLAYERS</a>
                                            <a href="<?php echo e(route('dl.agents',$op->id)); ?>" class="btn btn-warning btn-sm" >AGENTS</a>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-warning btn-sm btn-accsumm" data-url="<?php echo e(route('acc.summary',$op->id)); ?>" data-id="<?php echo e($op->id); ?>">ACCOUNT SUMMARY</button>
                                        <button type="button" class="btn btn-primary btn-sm btn-deposit" data-id="<?php echo e($op->id); ?>" >DEPOSIT</button>
                                        <button type="button" class="btn btn-danger btn-sm btn-return" data-id="<?php echo e($op->id); ?>" >WITHDRAW</button>
                                        <button type="button" class="btn btn-secondary btn-sm btn-comm" data-id="<?php echo e($op->id); ?>" >WITHDRAW COMM</button>
                                        <a href="<?php echo e(route('load.history',$op->id)); ?>" class="btn btn-success btn-sm" >LOAD HISTORY</a>
                                        <a href="<?php echo e(route('agent.events',$op->id)); ?>" class="btn btn-success btn-sm" >COMM HISTORY</a>
                                        <button type="button" class="btn btn-warning btn-sm btn-commission" data-id="<?php echo e($op->id); ?>">SET COMMISSION</button>
                                        <?php if($op->flag == 'legal' || Auth::user()->type == 'admin' || Auth::user()->type == 'super-admin'): ?>
                                            <button type="button" class="btn btn-change-status <?php echo e($op->status == 'active' ? 'btn-outline-danger' : 'btn-outline-success'); ?> btn-sm" data-id="<?php echo e($op->id); ?>" data-status="<?php echo e($op->status); ?>">
                                                <i class="fas fa-circle"></i> <?php echo e($op->status == 'active' ? 'DEACTIVATE' : 'ACTIVATE'); ?>

                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-url="<?php echo e(route('hard.delete')); ?>" data-id="<?php echo e($op->id); ?>">DELETE USER</button>
                                        <?php endif; ?>


                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <?php echo e($agents->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('partials.modals._depositAmount', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('partials.modals._setCommissionPercent', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('partials.modals._withdrawLoad', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('partials.modals._withdrawComm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('partials.modals._accountSummary', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startSection('scripts'); ?>
<?php echo $__env->make('partials._jsvariables', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script src="<?php echo e(asset('js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/masteragents/index.blade.php ENDPATH**/ ?>