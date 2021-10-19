<?php if(Auth::user()->hasRole('supervisor') || Auth::user()->hasRole('supervisorMaster')): ?>
    

    <?php $__env->startSection('title', 'Supervisor Reports'); ?>

    <?php $__env->startSection('container'); ?>
    <div class="row">
        <?php echo $__env->make('supervisor.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            
            <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                <h1 class="d-flex justify-content-center mb-4">Reports PR/PO</h1>

                <?php if(count($orderHeads) > 0): ?>
                    <div class="d-flex justify-content-end mr-3">
                        <a href="<?php echo e(Route('supervisor.downloadReport')); ?>" class="btn btn-outline-danger mb-3" target="_blank">Export</a>
                    </div>
                <?php endif; ?>

                <div class="table-wrapper-scroll-y my-custom-scrollbar tableFixHead">
                    <table class="table table-bordered sortable">
                        <thead class="thead bg-danger">
                        <tr>
                            <th scope="col">Nomor</th>
                            <th scope="col">Tanggal PR</th>
                            <th scope="col">Nomor PR</th>
                            <th scope="col">Supplier</th>
                            <th scope="col">Nomor PO</th>
                            <th scope="col">Nama Kapal</th>
                            <th scope="col">Keterangan</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $orderHeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$oh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td><?php echo e($oh -> prDate); ?></td>
                                    <td><?php echo e($oh -> noPr); ?></td>
                                    <td><?php echo e($oh -> supplier -> supplierName); ?></td>
                                    <td><?php echo e($oh -> noPo); ?></td>
                                    <td><?php echo e($oh -> boatName); ?></td>
                                    <td><?php echo e($oh -> descriptions); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
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
    </style>
    <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
    <?php $__env->stopSection(); ?>
<?php else: ?>
    <?php echo $__env->make('../layouts/notAuthorized', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/supervisor/supervisorReport.blade.php ENDPATH**/ ?>