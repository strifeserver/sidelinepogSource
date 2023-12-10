<div class="modal fade" id="addSystemBalanceModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="<?php echo e(route('system.load')); ?>" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add System Balance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <?php echo e(csrf_field()); ?>

                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" class="form-control" name="amount">
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
<?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/partials/modals/_addSystemBalance.blade.php ENDPATH**/ ?>