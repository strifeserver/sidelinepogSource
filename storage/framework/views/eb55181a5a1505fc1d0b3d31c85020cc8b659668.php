<?php $__env->startSection('styles'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <strong>UPDATE USER ACCOUNT</strong>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('update.account',$user->id)); ?>" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo e(csrf_field()); ?>

                                

                                <div class="form-group">
                                    <label>ACCOUNT TYPE</label>
                                    <input type="text" value="<?php echo e(ucwords($user->type)); ?>" class="form-control" disabled>
                                </div>

                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" value="<?php echo e($user->name); ?>" name="name" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" value="<?php echo e($user->username); ?>" name="username" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" value="<?php echo e($user->email); ?>" name="email" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" value="<?php echo e($user->contact_number); ?>" name="contact_number" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>

                                <button class="btn btn-success btn-sm float-right">UPDATE ACCOUNT</button>
                            </div>
                        </div>
                    </form>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/players/update.blade.php ENDPATH**/ ?>