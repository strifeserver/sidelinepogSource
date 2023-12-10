<?php $__env->startSection('styles'); ?>
<style>
    .alert-text{
        font-size: 13px;
        font-style: italic;
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>SYSTEM SETTINGS</strong>

                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <span class="alert-text">Changes are automatically saved upon typing. Changes here will be applied to all OPERATORS</span>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Max Player Deduction</label>
                                    <input type="text" name="value" id="player_deduction" value="<?php echo e($deduction->value); ?>" onkeyup="saveChanges(<?php echo e($deduction->id); ?>,this);" class="form-control deduction-values">
                                </div>
                                <?php echo e(csrf_field()); ?>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Base Operator Commission</label>
                                    <input type="text" name="value" id="operator_commission" value="<?php echo e($operator->value); ?>" onkeyup="saveChanges(<?php echo e($operator->id); ?>,this);" class="form-control deduction-values">
                                </div>
                                <?php echo e(csrf_field()); ?>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Bet Multiplier</label>
                                    <input type="text" name="value" id="multiplier" value="<?php echo e($timer->value); ?>" onkeyup="saveChanges(<?php echo e($timer->id); ?>,this);" class="form-control deduction-values">
                                </div>
                                <?php echo e(csrf_field()); ?>

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

        // $('.deduction-values').on('keyup',function(){
        //     var sum = 0;
        //     $('.deduction-values').each(function(){
        //         sum += parseFloat(this.value);
        //     });

        //     $('#player_deduction').val(sum);
        // })
    });

    function ValidateNumber(strNumber) {
        var regExp = new RegExp("^\\d+");
        var isValid = regExp.test(strNumber);
        return isValid;
    }

    function saveChanges(id,el){
        let formData = {
            id : id,
            value : el.value,
            _token : "<?php echo e(Session::token()); ?>"
        }

        if(ValidateNumber(el.value)){
            $.ajax({
                type : 'POST',
                url : "<?php echo e(route('save.settings')); ?>",
                data : formData,
                success:function(res){
                    console.log(res);
                },
                error:function(err){
                    console.log(err);
                }
            })
        }

    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>