<?php if(Auth::user()->hasRole('logistic')): ?>
    

    <?php $__env->startSection('title', 'Logistic Order History'); ?>

    <?php $__env->startSection('container'); ?>
        <div class="row">
            <?php echo $__env->make('logistic.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3 wrapper">
                    <h1 class="d-flex justify-content-center">Goods Out Report</h1>
                    <br>
                    
                    <div class="d-flex justify-content-start mb-3">
                        <a href="<?php echo e(Route('logistic.historyOut')); ?>" class="btn btn-outline-success mr-3">Goods Out</a>
                        <a href="<?php echo e(Route('logistic.historyIn')); ?>" class="btn btn-outline-secondary">Goods In</a>
                        
                        <?php if(count($orderHeads) > 0): ?>
                            <a href="<?php echo e(Route('logistic.downloadOut')); ?>" class="btn btn-outline-danger ml-auto mr-3" target="_blank">Export</a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="table-wrapper-scroll-y my-custom-scrollbar tableFixHead" style="overflow-x:auto;">
                        <table class="table table-bordered sortable">
                            <thead class="thead bg-danger">
                            <tr>
                                <th scope="col">Nomor</th>
                                <th scope="col">Tanggal Keluar</th>
                                <th scope="col">Item Barang Keluar</th>
                                <th scope="col">Serial Number</th>
                                <th scope="col">Qty</th>
                                <th scope="col">No. Resi</th>
                                <th scope="col">Note</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $orderHeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $oh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="bg-white"><?php echo e($key + 1); ?></td>
                                        <td class="bg-white"><?php echo e($oh -> item -> lastGiven); ?></td>
                                        <td class="bg-white"><strong><?php echo e($oh -> item -> itemName); ?></strong></td>
                                        <td class="bg-white"><?php echo e($oh -> item -> serialNo); ?></td>
                                        <td class="bg-white"><strong><?php echo e($oh -> quantity); ?> <?php echo e($oh -> item -> unit); ?></strong></td>
                                        <td class="bg-white"><strong><?php echo e($oh -> noResi); ?></strong></td>
                                        <td class="bg-white"><?php echo e($oh -> descriptions); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>

        <style>
            body{
                /* background-image: url('/images/logistic-background.png'); */
                background-repeat: no-repeat;
                background-size: cover;
            }
            .wrapper{
                padding: 10px;
                border-radius: 10px;
                background-color: antiquewhite;
                height: 1000px;
                /* height: 100%; */
            }
            .tableFixHead          { overflow: auto; height: 250px; }
            .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }

            .my-custom-scrollbar {
                position: relative;
                height: 800px;
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
        </style>
        <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
    <?php $__env->stopSection(); ?>
<?php else: ?>
    <?php echo $__env->make('../layouts/notAuthorized', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/logistic/logisticHistory.blade.php ENDPATH**/ ?>