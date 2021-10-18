

<?php $__env->startSection('title', 'Logistic Dashboard'); ?>

<?php $__env->startSection('container'); ?>
<div class="row">
    <?php echo $__env->make('logistic.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <h2 class="mt-3 mb-5" style="text-align: center">Reject Order : #<?php echo e($order -> id); ?></h2>
        <div class="d-flex flex-row align-items-end">
            <h3 class="p-2">Item Name &emsp;: </h3>
            <h4 class="p-2 "><?php echo e($order -> item -> itemName); ?></h4>
        </div>
        <div class="d-flex flex-row align-items-end">
            <h3 class="p-2">Quantity &emsp;&emsp;: </h3>
            <h4 class="p-2 "><?php echo e($order -> quantity); ?></h4>
        </div>
        <div class="d-flex flex-row align-items-end mb-5">
            <h3 class="p-2">Department &ensp;: </h3>
            <h4 class="p-2 "><?php echo e($order -> department); ?></h4>
        </div>

        <form method="POST" action="/logistic/order/<?php echo e($order -> id); ?>/reject">
            <?php echo csrf_field(); ?>
            <label for="reason">Reason</label>
            <textarea class="form-control" name="reason" id="reason" rows="3"></textarea>
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </main>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/logistic/logisticRejectOrder.blade.php ENDPATH**/ ?>