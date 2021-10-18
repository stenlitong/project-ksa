

<?php $__env->startSection('title', 'Logistic Dashboard'); ?>

<?php $__env->startSection('container'); ?>
<div class="row">
    <?php echo $__env->make('logistic.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <h2 class="mt-3 mb-3" style="text-align: center">Approved Order List</h2>
        <div class="d-flex justify-content-center">
            <?php echo e($transactions->links()); ?>

        </div>

        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Item</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Department</th>
                    <th scope="col">No. PR</th>
                    <th scope="col">Progress</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <th><?php echo e($t -> id); ?></th>
                    <td><?php echo e($t -> itemName); ?></td>
                    <td><?php echo e($t -> quantity); ?></td>
                    <td><?php echo e($t -> department); ?></td>
                    <td><?php echo e($t -> noPr); ?> Bulan</td>
                    <?php if($t -> status === 'Awaiting Approval'): ?>
                        <td>
                            In Progress (Purchasing)
                        </td>
                        <td>
                            <a href="/logistic/order/<?php echo e($t -> id); ?>/download" class="btn btn-success">Download</a>
                        </td>
                    <?php elseif($t -> status === 'Approved'): ?>
                        <td>Rejected by Purchasing</td>
                        <td>Rejected</td>
                    <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

    </main>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/logistic/approvedOrderPage.blade.php ENDPATH**/ ?>