<?php $__env->startSection('styles'); ?>
<style>
    .data-table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong><i class="fas fa-bars"></i> List of PLAYERS</strong>
                    
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search username" id="username-search">
                                <div class="input-group-append">
                                  <button class="btn btn-success" id="btn-search" data-url="<?php echo e(route('players')); ?>" type="button">
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
                                <th>Action/s</th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $direct_players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($dp->username); ?></td>
                                        <td>
                                            <?php if($dp->referred_by != null): ?>
                                                <?php
                                                    $us = \App\Models\User::withTrashed()->find($dp->referred_by);
                                                ?>
                                                <?php echo e($us->username); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e(bcdiv($dp->wallet->balance,1,2)); ?></td>
                                        <td>
                                            <?php if(Auth::user()->type == 'admin' || Auth::user()->type == 'super-admin'): ?>
                                                <a href="<?php echo e(route('edit.account',$dp->id)); ?>"  class="btn btn-success btn-sm">EDIT</a>
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-url="<?php echo e(route('hard.delete')); ?>" data-id="<?php echo e($dp->id); ?>">DELETE USER</button>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-primary btn-sm btn-deposit" data-id="<?php echo e($dp->id); ?>" >DEPOSIT</button>
                                            <button type="button" class="btn btn-danger btn-sm btn-return" data-id="<?php echo e($dp->id); ?>" >WITHDRAW</button>
                                            <a type="button" class="btn btn-secondary btn-sm" href="<?php echo e(route('wallet.history', ['id'=>$dp->id])); ?>">WALLET HISTORY</a>
                                            <a type="button" class="btn btn-warning btn-sm" href="<?php echo e(route('player.events', ['id'=>$dp->id])); ?>">BETTING HISTORY</a>
                                            <button type="button" class="btn btn-change-status <?php echo e($dp->status == 'active' ? 'btn-outline-danger' : 'btn-outline-success'); ?> btn-sm" data-id="<?php echo e($dp->id); ?>" data-status="<?php echo e($dp->status); ?>">
                                                <i class="fas fa-circle"></i> <?php echo e($dp->status == 'active' ? 'DEACTIVATE' : 'ACTIVATE'); ?>

                                            </button>
                                            <?php if(Auth::user()->type != 'silver-agent'): ?>
                                                <button type="button" class="btn btn-warning btn-sm btn-convert" data-id="<?php echo e($dp->id); ?>">CONVERT TO AGENT</button>
                                            <?php endif; ?>

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
    <?php echo $__env->make('partials.modals._depositAmount', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.modals._withdrawLoad', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php echo $__env->make('partials._jsvariables', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script src="<?php echo e(asset('js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/players/index.blade.php ENDPATH**/ ?>