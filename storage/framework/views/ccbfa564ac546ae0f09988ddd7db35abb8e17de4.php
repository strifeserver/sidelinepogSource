<script>
    var statusUpdateURL = "<?php echo e(route('change.status')); ?>";
    var token = "<?php echo e(Session::token()); ?>";
    var convertURL = "<?php echo e(route('convert.account')); ?>";
    var subUser = "<?php echo e(getSubUser(Auth::user()->type)); ?>";
</script>
<?php /**PATH /home/dmiphonl/domains/source.dmiph.online/public_html/resources/views/partials/_jsvariables.blade.php ENDPATH**/ ?>