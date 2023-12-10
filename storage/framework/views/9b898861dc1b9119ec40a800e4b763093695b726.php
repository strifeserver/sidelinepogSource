<div class="modal fade" id="convertCommissionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="<?php echo e(route('convert.commission')); ?>" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo e(Auth::user()->type =='super-admin' ? 'Reset Plasada' : 'Convert Commission'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <?php echo e(csrf_field()); ?>

                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" max="<?php echo e(Auth::user()->wallet->commission); ?>" class="form-control" name="amount" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/partials/modals/_convertCommission.blade.php ENDPATH**/ ?>