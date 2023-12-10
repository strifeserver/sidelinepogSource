<?php if(Session::has('success')): ?>
  <div class="col-md-12">
    <div class="alert alert-success alert-dismissable" role="alert">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <?php echo Session::get('success'); ?>

    </div>
  </div>
<?php endif; ?>

<?php if(Session::has('error')): ?>

<div class="col-md-12">

  <div class="alert alert-danger alert-dismissable" role="alert">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <?php echo Session::get('error'); ?>

  </div>
</div>


<?php endif; ?>


<?php if(count($errors) > 0): ?>
  <div class="col-md-12">
    <div class="alert alert-danger alert-dismissable" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Errors:</strong>
        <ul>
          <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo $error; ?></li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
  </div>
<?php endif; ?>


<?php /**PATH G:\xampp\htdocs\Sideline\sabong-orig\resources\views/partials/_message.blade.php ENDPATH**/ ?>