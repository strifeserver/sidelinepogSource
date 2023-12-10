<?php $__env->startSection('styles'); ?>
<style>
    .alert-warning,.alert-info{
        font-size: 13px;
    }

    .remove-float{
        float: none !important;
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        font-weight: bolder;
    }

    .img-card-holder{
        height: 170px;
        width: 100%;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
    }

    .rounded-corners{
        border-radius: 15px;
    }

    .coming-soon{
        position: absolute;
        left: 0;
        top: 0;
        background-color:rgba(0, 0, 0, 0.8);
        background-size: contain !important;
    }

    .remove-bottom-rounded{
        border-bottom-left-radius: 0px !important;
        border-bottom-right-radius: 0px !important;
    }

    .add-bottom-rounded{
        border-bottom-left-radius: 15px !important;
        border-bottom-right-radius: 15px !important;
    }

    .my-points{
        text-align: right;
    }
    .text-18 {
        font-size: 18px !important;
    }
    @media  only screen and (max-width: 768px) {
        .my-points{
            text-align: left;
            margin-top: 2.5rem;
        }
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<!-- Info boxes -->
    <?php if(Auth::user()->type == 'player'): ?>
       <div class="container">
        <div class="row">
            <div class="col-12 mt-4 mb-4">
                <div class="row border-bottom">
                    <div class="col-md-6">
                        <h4><strong>Today's Events</strong></h4>
                    </div>
                    <div class="col-md-6 my-points">
                        <p class="text-warning">Your Points: <span><?php echo e(bcdiv(Auth::user()->wallet->balance,1,2)); ?></span></p>
                    </div>
                </div>
            </div>
            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 col-sm-6 col-md-6">
                <div class="card">
                    <?php
                        $game = \App\Models\Game::find($ev->game_id);
                        $f = \App\Models\Fight::find($ev->active_fight)
                    ?>

                    <div class="card-body text-center img-card-holder remove-bottom-rounded" style="background-image: url(<?php echo e(asset($game->banner)); ?>)">
                        <?php if($game->status =='coming_soon'): ?>
                            <div class="img-card-holder coming-soon" style="background-image: url(<?php echo e(asset('images/soon.png')); ?>)"></div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer pt-2">
                        <h6 class="text-left text-18 text-warning mb-2"><strong><?php echo e(strtoupper($ev->name)); ?></strong></h6>
                        <h6 class="text-left mb-2"><strong><?php echo e(date('l, F d, Y')); ?></strong></h6>
                        <h6 class="text-left mb-2">Fight #: <?php echo e(strtoupper($f->fight_number)); ?></h6>

                        <a href="<?php echo e($game->status == 'coming_soon' ? '#' : route('live.fight',$ev->id)); ?>" class="btn btn-outline-warning btn-lg btn-block text-warning mt-3">
                            <span class="text-18">ENTER EVENT</span>
                        </a>

                    </div>



                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
       </div>
        <!-- /.row -->
    <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/client/games.blade.php ENDPATH**/ ?>