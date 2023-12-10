<div class="modal fade" id="redeclareFightModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Redeclare Winner</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo e(csrf_field()); ?>

                    <div class="form-group">
                        <label>Fight Number</label>
                        <select name="fight_id" id="select-fight-id" class="form-control select2">
                            <option value="" selected>Select Fight Number</option>
                            <?php $__currentLoopData = $fights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($fight->id); ?>">Fight Number <?php echo e($fight->fight_number); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Declare Winner</label>
                        <select class="form-control" id="select-result">
                            <option value="" selected>Choose...</option>
                            <option value="meron">Meron</option>
                            <option value="wala">Wala</option>
                            <option value="draw">Draw</option>
                            <option value="cancelled">Cancel</option>
                          </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-redeclare">Redeclare Winner</button>
                </div>
        </div>
    </div>
</div>
<?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/partials/modals/_redeclareWinner.blade.php ENDPATH**/ ?>