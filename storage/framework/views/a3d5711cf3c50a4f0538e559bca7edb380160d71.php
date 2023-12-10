<?php $__env->startSection('styles'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong><i class="fas fa-bars"></i> List of REMOVED PLAYERS</strong>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Points</th>
                            <th>Action/s</th>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $deleted_players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($dp->name); ?></td>
                                    <td><?php echo e($dp->contact_number); ?></td>
                                    <td><?php echo e($dp->wallet->balance); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-restore-account btn-outline-success btn-sm" data-url="<?php echo e(route('restore.account',$dp->id)); ?>">
                                            <i class="fas fa-circle"></i> RESTORE ACCOUNT
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php echo $__env->make('partials._jsvariables', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script src="<?php echo e(asset('js/main.js')); ?>"></script>.
<script>
$('.table').on('click','.btn-restore-account',function(){
        let urlDelete = $(this).data('url');
        Swal.fire({
            title: "Are you sure you want to do this?",
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type : 'GET',
                    url : urlDelete,
                    success : function(res){
                        Swal.fire(
                            res.msg,
                            '',
                            'success'
                        );
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    },
                    error : function(err){
                        console.log(err)
                    }
                })
            }
        })
    })
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/players/deleted.blade.php ENDPATH**/ ?>