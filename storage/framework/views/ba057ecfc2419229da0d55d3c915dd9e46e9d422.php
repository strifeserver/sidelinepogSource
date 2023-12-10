<div class="modal fade" id="withdrawCommModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="<?php echo e(route('withdraw.commission')); ?>" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Withdraw Commission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <?php echo e(csrf_field()); ?>

                    <div class="form-group">
                        <label>Amount to Withdraw</label>
                        <input type="number" class="form-control" name="amount" required>
                        <input type="hidden" class="form-control" name="user_id" id="userIdComm" required>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="remarks" class="form-control" rows="5"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/partials/modals/_withdrawComm.blade.php ENDPATH**/ ?>