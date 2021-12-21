<?php if(Auth::user()->hasRole('purchasing')): ?>
    

    <?php $__env->startSection('title', 'Purchasing Reports'); ?>

    <?php $__env->startSection('container'); ?>
    <div class="row">
        <?php echo $__env->make('purchasing.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            
            <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                <h1 class="d-flex justify-content-center mb-4">Reports PO Cabang <?php echo e($default_branch); ?> (<?php echo e($str_month); ?>)</h1>
                
                <div class="d-flex justify-content-between mr-3">
                    <div class="p-2">
                        <select name="cabang" class="form-select" onchange="window.location = this.value;">
                            <option selected disabled>Pilih Cabang</option>
                            <option value="/purchasing/report/Jakarta" 
                                <?php
                                    if($default_branch == 'Jakarta'){
                                        echo('selected');
                                    }
                                ?>
                            >Jakarta</option>
                            <option value="/purchasing/report/Banjarmasin"
                                <?php
                                    if($default_branch == 'Banjarmasin'){
                                        echo('selected');
                                    }
                                ?>
                            >Banjarmasin</option>
                            <option value="/purchasing/report/Samarinda"
                                <?php
                                    if($default_branch == 'Samarinda'){
                                        echo('selected');
                                    }
                                ?>
                            >Samarinda</option>
                            <option value="/purchasing/report/Bunati"
                                <?php
                                    if($default_branch == 'Bunati'){
                                        echo('selected');
                                    }
                                ?>
                            >Bunati</option>
                            <option value="/purchasing/report/Babelan"
                                <?php
                                    if($default_branch == 'Babelan'){
                                        echo('selected');
                                    }
                                ?>
                            >Babelan</option>
                            <option value="/purchasing/report/Berau"
                                <?php
                                    if($default_branch == 'Berau'){
                                        echo('selected');
                                    }
                                ?>
                            >Berau</option>
                        </select>
                    </div>
                        <?php if(count($orders) > 0): ?>
                            <div class="p-2">
                                <a href="/purchasing/report/download/<?php echo e($default_branch); ?>" class="btn btn-outline-danger mb-3" target="_blank">Export</a>
                            </div>
                        <?php endif; ?>
                </div>

                <div class="table-wrapper-scroll-y my-custom-scrollbar tableFixHead" style="overflow-x:auto;">
                    <table class="table table-bordered sortable">
                        <thead class="thead bg-danger">
                        <tr>
                            <th scope="col">Nomor</th>
                            <th scope="col">Tanggal PR</th>
                            <th scope="col">Nomor PR</th>
                            <th scope="col">Supplier</th>
                            <th scope="col">Tanggal PO</th>
                            <th scope="col">Nomor PO</th>
                            <th scope="col">Golongan</th>
                            <th scope="col">Nama Kapal</th>
                            <th scope="col">Serial Number</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Keterangan</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td><?php echo e($o -> prDate); ?></td>
                                    <td><?php echo e($o -> noPr); ?></td>
                                    <?php if(isset($o -> supplier)): ?>
                                        <td><?php echo e($o -> supplier); ?></td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                    <td><?php echo e($o -> poDate); ?></td>
                                    <td><?php echo e($o -> noPo); ?></td>
                                    <td><?php echo e($o -> item['golongan']); ?></td>
                                    <td><?php echo e($o -> boatName); ?></td>
                                    <td><?php echo e($o -> item -> serialNo); ?></td>
                                    <td><?php echo e($o -> acceptedQuantity); ?> <?php echo e($o -> item -> unit); ?></td>
                                    <td>Rp. <?php echo e(number_format($o -> totalItemPrice, 2, ",", ".")); ?></td>
                                    <td><?php echo e($o -> descriptions); ?></td>
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
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/purchasing/purchasingReport.blade.php ENDPATH**/ ?>