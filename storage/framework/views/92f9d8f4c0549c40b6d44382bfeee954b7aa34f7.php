<?php if(Auth::user()->hasRole('adminPurchasing')): ?>

    

    <?php $__env->startSection('title', 'Checklist AP'); ?>

    <?php $__env->startSection('container'); ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> 

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <div class="row">
            <?php echo $__env->make('adminPurchasing.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?php echo $__env->make('../layouts/time', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <h1 class="text-center">Upload List AP</h1>
                
                <?php if(session('status')): ?>
                    <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('fail')): ?>
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        <?php echo e(session('fail')); ?>

                    </div>
                <?php endif; ?>

                <?php if(count($errors) > 0): ?>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                            <?php echo e($message); ?>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

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
                
                <div class="content" style="overflow-x:auto;">
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
                            <?php $__currentLoopData = $apList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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

                <div class="d-flex justify-content-end">
                    <?php echo e($apList->links()); ?>

                </div>
            </main>
        </div>


        
        <?php $__currentLoopData = $apList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(!empty(Session::get('openApListModalWithId')) && Session::get('openApListModalWithId') == $ap -> id): ?>
                <script>
                    let id = <?php echo json_encode($ap -> id); ?>;
                    $(document).ready(function(){
                        $("#detail-" + id).modal('show');
                    });
                </script>
            <?php endif; ?>

            <div class="modal fade" id="detail-<?php echo e($ap -> id); ?>" tabindex="-1" role="dialog" aria-labelledby="detailTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered" role="document">
                    <div class="modal-content">
                        
                        <div class="modal-header bg-danger">
                            <div class="d-flex justify-content-start">
                                <h3 style="color: white"><?php echo e($ap -> orderHead -> noPo); ?></h3>
                            </div>
                        </div>

                        <div class="modal-body">
                            <?php if(session('openApListModalWithId')): ?>
                                <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                                    Saved Successfully
                                </div>
                            <?php endif; ?>
                            
                            <?php if(session('errorClosePo')): ?>
                                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                                    PO Already Been Closed
                                </div>
                            <?php endif; ?>

                            <div class="d-flex justify-content-end mb-3 mr-3">
                            <h5 class="mr-auto">Price To Paid : Rp. <?php echo e(number_format($ap -> orderHead -> totalPrice - $ap -> paidPrice, 2, ",", ".")); ?></h5>
                            <form action="/admin-purchasing/form-ap/upload" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('put'); ?>
                                <input type="hidden" name="apListId" value="<?php echo e($ap -> id); ?>">
                                <?php if($ap -> status == 'OPEN'): ?>
                                    <?php if($ap -> orderHead -> itemType == 'Barang'): ?>
                                        <button type="submit" class="btn btn-info mr-3">Submit</button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#close-<?php echo e($ap -> id); ?>">Close PO</button>
                                <?php endif; ?>
                            </div>

                            <h5 class="mr-auto mb-3">Original Price : Rp. <?php echo e(number_format($ap -> orderHead -> totalPrice, 2, ",", ".")); ?></h5>

                            <?php if($ap -> orderHead -> itemType == 'Barang'): ?>
                                <div class="table-modal">
                                    <table class="table myTable table-refresh<?php echo e($key); ?>">
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
                                                    // Helper var
                                                    $status = 'status_partial' . $i;
                                                    $uploadTime = 'uploadTime_partial' . $i;
                                                    $description = 'description_partial' . $i;
                                                    $filename = 'doc_partial' . $i;
                                                    $path_to_file = 'path_to_file' . $i;
                                                ?>
                                                <tr>
                                                    <td><?php echo e($ap -> $uploadTime); ?></td>
                                                    <td>Partial <?php echo e($i); ?></td>
                                                    <td>
                                                        <?php if($ap -> $status == 'On Review'): ?>
                                                            <span style="color: gray; font-weight: bold"><?php echo e($ap -> $status); ?></span>
                                                        <?php elseif($ap -> $status == 'Rejected'): ?>
                                                            <span style="color: Red; font-weight: bold"><?php echo e($ap -> $status); ?></span>
                                                        <?php else: ?>
                                                            <span style="color: green; font-weight: bold"><?php echo e($ap -> $status); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e($ap -> $description); ?></td>
                                                    <td>
                                                        <?php if($ap -> $status == 'On Review' || $ap -> $status == 'Approved' || $ap -> status == 'CLOSED'): ?>
                                                            <span><?php echo e($ap -> $filename); ?></span>
                                                        <?php else: ?>
                                                            <input type="hidden" name="apListId" value="<?php echo e($ap -> id); ?>">
                                                            <input type="hidden" name="cabang" value="<?php echo e($default_branch); ?>">
                                                            <input type="file" name="doc_partial<?php echo e($i); ?>" class="form-control">
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endfor; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                            </form>
                            <div class="mt-4">
                                <form action="/admin-purchasing/form-ap/ap-detail" method="POST">
                                    <?php echo csrf_field(); ?>

                                    <input type="hidden" name="totalPrice" value="<?php echo e($ap -> orderHead -> totalPrice); ?>">
                                    <input type="hidden" name="apListId" value="<?php echo e($ap -> id); ?>">
                                    <input type="hidden" name="cabang" value="<?php echo e($default_branch); ?>">

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="supplierName">Nama Supplier</label>
                                            <select class="form-control" id="supplierName" name="supplierName"
                                                <?php if($ap -> status == 'CLOSED'): ?>
                                                    <?php echo e('disabled'); ?>

                                                <?php endif; ?>
                                            >
                                                <option class="h-25 w-50" value="" disabled>Choose Supplier...</option>
                                                <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option class="h-25 w-50" value="<?php echo e($s -> supplierName); ?>"><?php echo e($s -> supplierName); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="noPr">Nomor PR</label>
                                            <input type="text" class="form-control" name="noPr" id="noPr" value="<?php echo e($ap -> orderHead -> noPr); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="noInvoice">Nomor Invoice</label>
                                            <input type="text" class="form-control" name="noInvoice" id="noInvoice" placeholder="Input Nomor Invoice" required
                                                <?php if($ap -> status == 'CLOSED'): ?>
                                                    <?php echo e('readonly'); ?>

                                                <?php endif; ?>
                                            >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="dueDate">Due Date</label>
                                            <input type="date" class="form-control" id="dueDate" name="dueDate" required
                                                <?php if($ap -> status == 'CLOSED'): ?>
                                                    <?php echo e('readonly'); ?>

                                                <?php endif; ?>
                                            >
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="noFaktur">Nomor Faktur Pajak</label>
                                            <input type="text" class="form-control" id="noFaktur" placeholder="Input Nomor Faktur Pajak" name="noFaktur" required
                                                <?php if($ap -> status == 'CLOSED'): ?>
                                                    <?php echo e('readonly'); ?>

                                                <?php endif; ?>
                                            >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="noDo">Nomor DO</label>
                                            <input type="text" class="form-control" id="noDo" placeholder="Input Nomor DO" name="noDo" required
                                                <?php if($ap -> status == 'CLOSED'): ?>
                                                    <?php echo e('readonly'); ?>

                                                <?php endif; ?>
                                            >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nominalInvoice">Nominal Invoice Yang Harus Dibayar</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Rp. </div>
                                            </div>
                                            <input type="number" class="form-control" id="nominalInvoice" name="nominalInvoice" min="1" step="0.01" placeholder="Input Nominal Invoice" required 
                                                <?php if($ap -> status == 'CLOSED'): ?>
                                                    <?php echo e('readonly'); ?>

                                                <?php endif; ?>
                                            >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="additionalInformation">Keterangan (optional)</label>
                                        <textarea class="form-control" name="additionalInformation" id="additionalInformation" placeholder="Input Keterangan..." rows="4"
                                            <?php if($ap -> status == 'CLOSED'): ?>
                                                <?php echo e('readonly'); ?>

                                            <?php endif; ?>
                                        ></textarea>
                                    </div>
                                    <?php if($ap -> status != 'CLOSED'): ?>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>

            <div class="modal fade" id="close-<?php echo e($ap -> id); ?>" tabindex="-1" role="dialog" aria-labelledby="detailTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <div class="d-flex justify-content-start">
                                <h5 class="text-white">Close PO</h5>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex justify-content-center align-items-center">
                                <h5>Are you sure you want to close this PO?</h5>
                            </div>
                            <div class="d-flex justify-content-center align-items-center mt-2">
                                <span data-feather="alert-circle" style="width: 10vw; height: 10vh;stroke: red;
                                stroke-width: 2;"></span>
                            </div>
                            <div class="d-flex justify-content-center align-items-center mt-3">
                                <button type="button" data-dismiss="modal" class="btn btn-danger">No</button>
                                <form action="/admin-purchasing/form-ap/close" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('patch'); ?>
                                    <input type="hidden" name="apListId" value="<?php echo e($ap -> id); ?>">
                                    <button type="submit" class="btn btn-primary ml-3">Yes</button>
                                </form>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <style>
            th{
                color: white;
            }
            th, td{
                word-wrap: break-word;
                min-width: 100px;
                max-width: 100px;
                text-align: center;
            }
            .table-properties{
                word-wrap: break-word;
                min-width: 120px;
                max-width: 120px;
                text-align: center;
            }
            /* .myTable tr td:last-child{
                width: 300px;
            } */
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
            setTimeout(function() {
                $('.alert').fadeOut('fast');
                // $('div.alert').remove();
            }, 3000);
        </script>

        <script type="text/javascript">
            let id = <?php echo json_encode(count($apList)); ?>;
            function refreshDiv(){
                $('.content').load(location.href + ' .content')
            }
            setInterval(refreshDiv, 60000);

            // setInterval(() => {
            //     for(i = 0 ; i <= id - 1 ; i++){
            //         $('.table-refresh' + i).empty()
            //         $('.table-refresh' + i).load(location.href + ' .table-refresh' + i)
            //     }
            // }, 10000)
        </script>
    <?php $__env->stopSection(); ?>

<?php else: ?>
    <?php echo $__env->make('../layouts/notAuthorized', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/adminPurchasing/adminPurchasingFormAp.blade.php ENDPATH**/ ?>