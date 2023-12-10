<?php $__env->startSection('styles'); ?>
<style>
    .alert-warning,.alert-info{
        font-size: 13px;
    }
    .inner>p{
        font-size: 14px;
    }
    .small-box-footer{
        font-size: 14px;
    }
    .inner>h3{
        font-size: 1.6rem;
    }
    @media  only screen and (max-width: 1366px) {
        .inner>p{
            font-size: 12px;
        }
        .inner>h3{
            font-size: 26px !important;
        }
        .small-box-footer{
            font-size: 12px;
        }
    }

    .info-box-text{
        font-size: 1.1rem !important;
    }
    .info-box-number{
        font-size: 2rem !important;
    }

</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<!-- Info boxes -->
    <?php if(Auth::user()->type != 'super-admin'): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card"  role="alert">
                    <div class="card-body">
                        <div class="alert alert-warning" role="alert">
                            Please take notes of your refferal link below, All players that will register under this link will atomatically be under your account.
                        </div>
                        <h4><strong>Referral Link:</strong></h4>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control text-danger bg-white border-0" id="link-input" value="<?php echo e(route('register', Auth::user()->referral_code)); ?>" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-secondary" onclick="copyToClipBoard('link-input')">Copy Link</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if(Auth::user()->type == 'super-admin'): ?>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-primary elevation-1">
                        <i class="fas fa-dollar-sign"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-text">Plasada Income</span>
                      <span class="info-box-number">₱ <?php echo e(number_format(($plasadaTotal),2,".",",")); ?></span>
                    </div>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1">
                        <i class="fas fa-cash-register"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-text">Operator Commision</span>
                      <span class="info-box-number">₱ <?php echo e(number_format(($operatorCommissions),2,".",",")); ?></span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-primary elevation-1">
                        <i class="fas fa-dollar-sign"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-text">SubOp Commision</span>
                      <span class="info-box-number">₱ <?php echo e(number_format(($subOperatorCommissions),2,".",",")); ?></span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1">
                        <i class="fas fa-hand-holding-usd"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-text">MA Commissions</span>
                      <span class="info-box-number">₱ <?php echo e(number_format(($masterAgentCommissions),2,".",",")); ?></span>
                    </div>
                </div>
            </div>
            <!-- /.col -->
            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-secondary elevation-1">
                        <i class="fas fa-hand-holding-usd"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-text">SA Commissions</span>
                      <span class="info-box-number">₱ <?php echo e(number_format(($agentCommissions),2,".",",")); ?></span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-secondary elevation-1">
                        <i class="fas fa-hand-holding-usd"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-text">Silver Commissions</span>
                      <span class="info-box-number">₱ <?php echo e(number_format(($silverAgentCommissions),2,".",",")); ?></span>
                    </div>
                </div>
            </div>

            <!-- /.col -->

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1">
                        <i class="far fa-credit-card"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-text">All Users Credits</span>
                      <span class="info-box-number">₱ <?php echo e(number_format(($userCredits),2,".",",")); ?></span>
                    </div>
                </div>
            </div>
            <?php
            $adminCredits = [];
            ?>
            <?php $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $adminCredits[] = $ad->wallet->balance;
                ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1">
                        <i class="far fa-credit-card"></i>
                    </span>
                    <div class="info-box-content">
                    <span class="info-box-text">Admin Points</span>
                    <span class="info-box-number">₱ <?php echo e(bcdiv(array_sum($adminCredits),1,2)); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1">
                        <i class="fas fa-wallet"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-text">Operator Withdrawals</span>
                      <span class="info-box-number">₱ <?php echo e(number_format(($operatorPointsWithdrawals),2,".",",")); ?></span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1">
                        <i class="fas fa-wallet"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-text">Operator Comm. Withdrawals</span>
                      <span class="info-box-number">₱ <?php echo e(number_format(($operatorCommiWithdrawals),2,".",",")); ?></span>
                    </div>
                </div>
            </div>

        </div>
        </div>
    <?php endif; ?>

    <?php if(agentOnly(Auth::user()->type)): ?>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-6">
                <div class="info-box mb-3 bg-primary">
                    <div class="info-box-content">
                      <span class="info-box-text">Current Wallet</span>
                      <span class="info-box-number">Your Points: <?php echo e(number_format((Auth::user()->wallet->balance),2,".",",")); ?></span>
                    </div>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-6">
                 <div class="info-box mb-3 bg-success">
                    <div class="info-box-content">
                      <span class="info-box-text">Current Commision <strong class="text-danger">(<?php echo e(Auth::user()->commission); ?>%)</strong></span>
                      <span class="info-box-number">Your Commission: <?php echo e(number_format((Auth::user()->wallet->commission),2,".",",")); ?></span>
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script>
    var playerCreds = "<?php echo e($userCredits); ?>";
    function copyToClipBoard(id) {
    /* Get the text field */
        var copyText = document.getElementById(id);
        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */
        /* Copy the text inside the text field */
        navigator.clipboard.writeText(copyText.value);

        /* Alert the copied text */
        //alert("Referral link copied!");
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/admin/dashboard/index.blade.php ENDPATH**/ ?>