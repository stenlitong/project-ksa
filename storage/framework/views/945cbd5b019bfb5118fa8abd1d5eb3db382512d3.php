<?php if(Auth::user()->hasRole('logistic')): ?>
    

    <?php $__env->startSection('title', 'Logistic Request DO'); ?>

    <?php $__env->startSection('container'); ?>
        <div class="row">
            <?php echo $__env->make('logistic.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                    <h1 class="d-flex justify-content-center mb-3">My Request DO</h1>
                    <br>
                    
                    <?php if(session('success')): ?>
                        <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="table-wrapper-scroll-y my-custom-scrollbar tableFixHead">
                        <table class="table table-bordered sortable">
                            <thead class="thead bg-danger">
                            <tr>
                                <th scope="col" style="width: 100px">Nomor</th>
                                <th scope="col">Item Barang</th>
                                <th scope="col">Cabang Tujuan</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $ongoingOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>
                                        <td><?php echo e($o -> item_requested -> itemName); ?></td>
                                        <td><?php echo e($o -> toCabang); ?></td>
                                        <td><?php echo e($o -> quantity); ?> <?php echo e($o -> item_requested -> unit); ?></td>
                                        <?php if(strpos($o -> status, 'Rejected') !== false): ?>
                                            <td><strong style="color: red"><?php echo e($o -> status); ?></strong></td>
                                        <?php elseif(strpos($o -> status, 'On Delivery') !== false): ?>
                                            <td><strong style="color: blue"><?php echo e($o -> status); ?></strong></td>
                                        <?php elseif(strpos($o -> status, 'Accepted') !== false): ?>
                                            <td><strong style="color: green"><?php echo e($o -> status); ?></strong></td>
                                        <?php else: ?>
                                            <td><?php echo e($o -> status); ?></td>
                                        <?php endif; ?>
                                        <td>
                                            <a href="/logistic/request-do/<?php echo e($o -> id); ?>/download" target="_blank"><span data-feather="download" class="icon mr-2"></span></a>
                                            <?php if(strpos($o -> status, 'On Delivery') !== false): ?>
                                                <a href="/logistic/request-do/<?php echo e($o -> id); ?>/accept-do" class="btn btn-info">Accept Delivery</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>

        <style>
            .tableFixHead          { overflow: auto; height: 250px; }
            .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }

            .my-custom-scrollbar {
                position: relative;
                height: 700px;
                overflow: auto;
            }
            .table-wrapper-scroll-y {
                display: block;
            }
            th{
                color: white;
            }
            td, th{
                word-wrap: break-word;
                min-width: 160px;
                max-width: 160px;
                text-align: center;
            }
            .icon{
                margin-bottom: -10px;
                color: black;
                height: 30px;
                width: 30px;
            }
            .alert{
                text-align: center;
            }
        </style>
        <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
    <?php $__env->stopSection(); ?>
<?php else: ?>
    <?php echo $__env->make('../layouts/notAuthorized', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/logistic/logisticOngoingDO.blade.php ENDPATH**/ ?>