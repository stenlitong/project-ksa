<?php if(Auth::user()->hasRole('adminPurchasing')): ?>

    

    <?php $__env->startSection('title', 'Checklist AP'); ?>

    <?php $__env->startSection('container'); ?>
        <div class="row">
            <?php echo $__env->make('adminPurchasing.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?php echo $__env->make('../layouts/time', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <h1 class="text-center">Upload List AP</h1>
                

                <div class="d-flex">
                    <div class="p-2 mr-auto">
                        <h5>Cabang</h5>
                        <select name="cabang" class="form-select" onchange="window.location = this.value;">
                            <option selected disabled>Pilih Cabang</option>
                            <option value="/admin-purchasing/form-ap/Jakarta" 
                                <?php
                                    if($default_branch == 'Jakarta'){
                                        echo('selected');
                                    }
                                ?>
                            >Jakarta</option>
                            <option value="/admin-purchasing/form-ap/Banjarmasin"
                                <?php
                                    if($default_branch == 'Banjarmasin'){
                                        echo('selected');
                                    }
                                ?>
                            >Banjarmasin</option>
                            <option value="/admin-purchasing/form-ap/Samarinda"
                                <?php
                                    if($default_branch == 'Samarinda'){
                                        echo('selected');
                                    }
                                ?>
                            >Samarinda</option>
                            <option value="/admin-purchasing/form-ap/Bunati"
                                <?php
                                    if($default_branch == 'Bunati'){
                                        echo('selected');
                                    }
                                ?>
                            >Bunati</option>
                            <option value="/admin-purchasing/form-ap/Babelan"
                                <?php
                                    if($default_branch == 'Babelan'){
                                        echo('selected');
                                    }
                                ?>
                            >Babelan</option>
                            <option value="/admin-purchasing/form-ap/Berau"
                                <?php
                                    if($default_branch == 'Berau'){
                                        echo('selected');
                                    }
                                ?>
                            >Berau</option>
                        </select>
                    </div>
                </div>
                
                <div id="content" style="overflow-x:auto;">
                    <table class="table">
                        <thead class="thead bg-danger">
                        <tr>
                            <th scope="col">Time Created</th>
                            <th scope="col">Status</th>
                            <th scope="col">Nomor PO</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $apList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($ap -> creationTime); ?></td>
                                <?php if($ap -> status == 'OPEN'): ?>
                                    <td><span style="color: green; font-weight: bold; font-size: 18px"><?php echo e($ap -> status); ?></span></td>
                                <?php else: ?>
                                    <td><span style="color: red; font-weight: bold; font-size: 18px"><?php echo e($ap -> status); ?></span></td>
                                <?php endif; ?>
                                <td><?php echo e($ap -> orderHead -> noPo); ?></td>
                                <td>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#detail-<?php echo e($ap -> id); ?>">Detail</button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

            </main>
        </div>

        <?php $__currentLoopData = $apList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="modal fade" id="detail-<?php echo e($ap -> id); ?>" tabindex="-1" role="dialog" aria-labelledby="detailTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <div class="d-flex justify-content-start">
                                    <h3 style="color: white"><?php echo e($ap -> orderHead -> noPo); ?></h3>
                                </div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="d-flex justify-content-end mb-3 mr-3">
                                    <div class="p-2 mr-auto">
                                        <h5>Total Harga : Rp. <?php echo e(number_format($ap -> orderHead -> totalPrice, 2, ",", ".")); ?></h5>
                                    </div>
                                <form action="/admin-purchasing/<?php echo e($default_branch); ?>/form-ap/upload" method="POST" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('put'); ?>
                                    <button type="submit" class="btn btn-info mr-3">Submit</button>
                                    <button class="btn btn-success">Close PO</button>
                                </div>
                                    <div class="table-modal">
                                        <table class="table">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th class="table-header">Date Uploaded</th>
                                                    <th class="table-header">Name</th>
                                                    <th class="table-header">Status</th>
                                                    <th class="table-header">Description</th>
                                                    <th class="table-header">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php for($i = 1 ; $i <= 20 ; $i++): ?>
                                                    <?php
                                                        $status = 'status_partial' . $i;
                                                        $uploadTime = 'uploadTime_partial' . $i;
                                                        $description = 'description_partial' . $i;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo e($ap -> $uploadTime); ?></td>
                                                        <td>Partial <?php echo e($i); ?></td>
                                                        <td><?php echo e($ap -> $status); ?></td>
                                                        <td><?php echo e($ap -> $description); ?></td>
                                                        <td>
                                                            <input type="hidden" name="apListId" value="<?php echo e($ap -> id); ?>">
                                                            <input type="file" name="doc_partial<?php echo e($i); ?>" class="form-control">
                                                        </td>
                                                    </tr>
                                                <?php endfor; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                                <div class="mt-4">
                                    <form action="" method="POST">
                                        <div class="form-row">
                                          <div class="form-group col-md-6">
                                            <label for="supplierName">Nama Supplier</label>
                                            <select class="form-control" id="supplier_id" name="supplier_id">
                                                <option class="h-25 w-50" value="" disabled>Choose Supplier...</option>
                                                <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option class="h-25 w-50" value="<?php echo e($s -> id); ?>"><?php echo e($s -> supplierName); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                          </div>
                                          <div class="form-group col-md-6">
                                            <label for="noPr">Nomor PR</label>
                                            <input type="text" class="form-control" id="noPr" value="<?php echo e($ap -> orderHead -> noPr); ?>" readonly>
                                          </div>
                                        </div>
                                        <div class="form-row">
                                          <div class="form-group col-md-6">
                                            <label for="noInvoice">Nomor Invoice</label>
                                            <input type="text" class="form-control" id="noInvoice" placeholder="Input Nomor Invoice">
                                          </div>
                                          <div class="form-group col-md-6">
                                            <label for="nominalInvoice">Nominal Invoice</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp. </div>
                                                </div>
                                                <input type="number" class="form-control" id="nominalInvoice" min="1" step="0.1" placeholder="Input Nominal Invoice">
                                            </div>
                                          </div>
                                        </div>
                                        <div class="form-row">
                                          <div class="form-group col-md-6">
                                            <label for="noFaktur">Nomor Faktur Pajak</label>
                                            <input type="text" class="form-control" id="noFaktur" placeholder="Input Nomor Faktur Pajak">
                                          </div>
                                          <div class="form-group col-md-6">
                                            <label for="noDo">Nomor DO</label>
                                            <input type="text" class="form-control" id="noDo" placeholder="Input Nomor DO">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="additionalInformation">Keterangan (optional)</label>
                                            <textarea class="form-control" name="additionalInformation" id="additionalInformation" placeholder="Input Keterangan..." rows="4"></textarea>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div> 
                            <div class="modal-footer">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <style>
            /* .tableFixHead          { overflow: auto; height: 250px; }
            .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }
            .my-custom-scrollbar {
            position: relative;
            height: 600px;
            overflow: auto;
            }
            .table-wrapper-scroll-y {
                display: block;
            } */
            th{
                color: white;
            }
            th, td{
                word-wrap: break-word;
                min-width: 160px;
                max-width: 160px;
                text-align: center;
            }
            .table-properties{
                word-wrap: break-word;
                min-width: 120px;
                max-width: 120px;
                text-align: center;
            }
            .table-modal{
                height: 400px;
                overflow-y: auto;
            }
            .table-header{
                position: sticky;
                top: 0;
                z-index: 10;
            }
            .icon{
                color: black;
                height: 24px;
                width: 24px
            }
            .center{
                margin-left: 25%;
                width: 50%;
            }
            .alert{
                text-align: center;
            }
            .modal-backdrop {
                height: 100%;
                width: 100%;
            }
        </style>
        <script>
            function refreshDiv(){
                $('#content').load(location.href + ' #content')
            }
            setInterval(refreshDiv, 60000);

            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 3000);
        </script>
        <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
    <?php $__env->stopSection(); ?>

<?php else: ?>
    <?php echo $__env->make('../layouts/notAuthorized', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/adminPurchasing/adminPurchasingFormAp.blade.php ENDPATH**/ ?>