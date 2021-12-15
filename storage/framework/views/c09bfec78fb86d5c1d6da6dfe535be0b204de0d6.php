<?php if(Auth::user()->hasRole('purchasing')): ?>
    

    <?php $__env->startSection('title', 'Purchasing Approve Order'); ?>

    <?php $__env->startSection('container'); ?>
    <div class="row">
        <?php echo $__env->make('purchasing.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

            <h2 class="mt-3" style="text-align: center">Order <?php echo e($orderHeads -> order_id); ?></h2>
            
            <?php if(session('status')): ?>
                <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('dropStatus')): ?>
                <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                    Dropped Successfully, <a href="/purchasing/order/<?php echo e($orderHeads -> id); ?>/<?php echo e(Session::get('dropStatus')); ?>/undo">Click Here To Undo !</a>
                </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php $__errorArgs = ['boatName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    Nama Kapal Invalid
                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['noPr'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    Nomor PR Invalid
                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['noPo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    Nomor PO Invalid
                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['invoiceAddress'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    Alamat Pengiriman Invoice Invalid
                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['itemAddress'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    Alamat Barang Invoice Invalid
                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['supplier_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    Nama Supplier Invalid
                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    Diskon Invalid
                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['itemPrice'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    Harga Item Invalid
                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['supplier_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    Nama Supplier Invalid
                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['ppn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    PPN Invalid
                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <div class="row mt-4">
                    <div class="col">
                        <?php
                            if(strpos($orderHeads -> status, 'Revised') !== false){
                                $route = 'revise';
                            }else{
                                $route = 'approve';
                            }
                        ?>
                        <form method="POST" action="/purchasing/order/<?php echo e($orderHeads -> id); ?>/<?php echo e($route); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="approvedBy">Approved By</label>
                                <input type="text" class="form-control" id="boatName" name="boatName" value="<?php echo e(Auth::user()->name); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="boatName">Nama Kapal</label>
                                <input type="text" class="form-control" id="boatName" name="boatName" value="<?php echo e($orderHeads -> boatName); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="noPr">Nomor Purchase Requisition</label>
                                <input type="text" class="form-control" id="noPr" name="noPr" value="<?php echo e($orderHeads -> noPr); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="noPo">Nomor Purchase Order</label>
                                <input type="text" class="form-control" id="noPo" name="noPo" value="<?php echo e($poNumber); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="invoiceAddress">Alamat Pengiriman Invoice</label>
                                <select name="invoiceAddress" id="invoiceAddress" class="form-control" onchange="showfield(this.options[this.selectedIndex].value)" required> 
                                    <option value="" disabled>Choose</option>
                                    <option value="Gran Rubina, Jl. HR Rasuna Said Lt.12, Karet Kuningan, Setia Budi, Jak-sel">Gran Rubina, Jl. HR Rasuna Said Lt.12, Karet Kuningan, Setia Budi, Jak-sel</option>
                                    <option value="Jl. Jelawat No.23 RT.002 RW.001 Kel. Sidomulyo-Samarinda">Jl. Jelawat No.23 RT.002 RW.001 Kel. Sidomulyo-Samarinda</option>
                                    <option value="Jl. Olah Bebaya 04 RW 02 Sungai Lais, Kel. Pulau Atas Kec. Sambutan Samarinda - kalimantan Timur ">Jl. Olah Bebaya 04 RW 02 Sungai Lais, Kel. Pulau Atas Kec. Sambutan Samarinda - kalimantan Timur </option>
                                    <option value="Jl. Djok Mentaya no.27-28 Ruko Naga Mas, Banjarmasin">Jl. Djok Mentaya no.27-28 Ruko Naga Mas, Banjarmasin</option>
                                    <option value="Jl. Provinsi KM 150 Sebamban 2 Blok C, No 07 Rt.25 Desa Sumber Baru, Kec.Angsana, Kab. TanahBumbu - Kalimantan Selatan ">Jl. Provinsi KM 150 Sebamban 2 Blok C, No 07 Rt.25 Desa Sumber Baru, Kec.Angsana, Kab. TanahBumbu - Kalimantan Selatan </option>
                                    <option value="Jl. Gajah Mada no.531 RT 16 (Depan Hotel Mitra), Tanjung Redeb, Kab.Berau - Kalimantan timur">Jl. Gajah Mada no.531 RT 16 (Depan Hotel Mitra), Tanjung Redeb, Kab.Berau - Kalimantan timur</option>
                                    <option value="Jl. bunga seroja no 88 E, Kendari, Sulawesi Tenggara">Jl. bunga seroja no 88 E, Kendari, Sulawesi Tenggara</option>
                                    <option value="Perumahan Tre Vista residence blok A1 no 5, kelurahan kebalen, kec babelan, kab bekasi. 17610">Perumahan Tre Vista residence blok A1 no 5, kelurahan kebalen, kec babelan, kab bekasi. 17610</option>
                                    <option value="Jl. Cendana Gg. Belakang PolsekPlajau Rt.08 Rw.02, Desa Bersujud, Batu licin - Kalimantan Selatan ">Jl. Cendana Gg. Belakang PolsekPlajau Rt.08 Rw.02, Desa Bersujud, Batu licin - Kalimantan Selatan </option>
                                    <option value="Other">Alamat Lain, Input Manual</option>
                                </select>
                                <div id="div1"></div>
                            </div>
                            <div class="form-group">
                                <label for="itemAddress">Alamat Pengiriman Barang</label>
                                <select name="itemAddress" id="itemAddress" class="form-control" onchange="showfield2(this.options[this.selectedIndex].value)" required> 
                                    <option value="" disabled>Choose</option>
                                    <option value="Gran Rubina, Jl. HR Rasuna Said Lt.12, Karet Kuningan, Setia Budi, Jak-sel">Gran Rubina, Jl. HR Rasuna Said Lt.12, Karet Kuningan, Setia Budi, Jak-sel</option>
                                    <option value="Jl. Jelawat No.23 RT.002 RW.001 Kel. Sidomulyo-Samarinda">Jl. Jelawat No.23 RT.002 RW.001 Kel. Sidomulyo-Samarinda</option>
                                    <option value="Jl. Djok Mentaya no.27-28 Ruko Naga Mas, Banjarmasin">Jl. Djok Mentaya no.27-28 Ruko Naga Mas, Banjarmasin</option>
                                    <option value="Other2">Alamat Lain, Input Manual</option>
                                </select>
                                <div id="div2"></div>
                            </div>
                            <div class="form-group">
                                <label for="ppn">Tipe PPN</label>
                                <select class="form-control" id="ppn" name="ppn" required>
                                    <option value="10" 
                                        <?php if($orderHeads->ppn == 10): ?>
                                            <?php echo e('selected'); ?>

                                        <?php endif; ?>
                                    >PPN</option>
                                    <option value="0"
                                        <?php if($orderHeads->ppn == 0): ?>
                                            <?php echo e('selected'); ?>

                                        <?php endif; ?>
                                    >Non - PPN</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="discount">Discount (%)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-white">%</div>
                                    </div>
                                    <input type="number" class="form-control" id="discount" name="discount" min="0" max="100" step="0.1" placeholder="Input Diskon Dalam Angka" value="<?php echo e($orderHeads -> discount); ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="price" class="mb-2">Total Harga (sebelum ppn & diskon)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-white">Rp.</div>
                                    </div>
                                    
                                    <input type="text" class="form-control" id="totalPrice" name="totalPrice" value="<?php echo e(number_format($orderHeads -> totalPriceBeforeCalculation, 2, ",", ".")); ?>" readonly>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-5">
                                <a href="/dashboard" class="btn btn-danger mr-3">Cancel</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="col mt-3 overflow-auto">
                        <table class="table" id="myTable">
                            <thead class="thead bg-danger">
                                    <th scope="col">Item Barang</th>
                                    <th scope="col" class="center">Quantity</th>
                                    <th scope="col">Harga per Barang</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Supplier</th>
                                    <th scope="col" class="center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $od): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <h5><?php echo e($od -> item -> itemName); ?> - (<?php echo e($od -> department); ?>)</h5>
                                        </td>

                                        <td class="center">
                                            <h5><?php echo e($od -> acceptedQuantity); ?> <?php echo e($od -> item -> unit); ?></h5>
                                        </td>

                                        <form action="/purchasing/order/<?php echo e($orderHeads -> id); ?>/<?php echo e($od -> id); ?>/edit" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('patch'); ?>
                                            <td>
                                                <div class="form-group d-flex">
                                                    <h5 class="mr-2">Rp. </h5>
                                                    <input type="number" class="form-control" id="itemPrice" name="itemPrice" value="<?php echo e($od -> itemPrice); ?>" min="1" step="0.01">
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <h5>Rp. <?php echo e(number_format($od -> totalItemPrice, 2, ",", ".")); ?></h5>
                                            </td>
                                            
                                            <td>
                                                <div class="form-group">
                                                    <select class="form-control" id="supplier" name="supplier">
                                                        <option class="h-25 w-50" value="" disabled>Choose Supplier...</option>
                                                        <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option class="h-25 w-50" value="<?php echo e($s -> supplierName); ?>"><?php echo e($s -> supplierName); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </td>

                                            <td class="center">
                                                <?php if(count($orderDetails) > 1): ?>
                                                    <button type="button" class="btn btn-sm mr-2" data-toggle="modal" data-target="#drop-<?php echo e($od -> id); ?>"><span data-feather="trash-2"></span></button>
                                                <?php endif; ?>
                                                <button type="submit" class="btn btn-info btn-sm">Save</button>
                                            </td>
                                        </form>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
    </div>

    <?php $__currentLoopData = $orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $od): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="modal fade" id="drop-<?php echo e($od -> id); ?>" tabindex="-1" role="dialog" aria-labelledby="reject-orderTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="rejectTitle" style="color: white">Reject Item - <?php echo e($od -> item -> itemName); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/purchasing/order/<?php echo e($od -> id); ?>/drop">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('patch'); ?>
                    <input type="hidden" name="orderHeadsId" value="<?php echo e($orderHeads -> id); ?>">
                    <div class="modal-body"> 
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <span class="text-danger" data-feather="alert-circle" style="height: 15%; width: 15%;"></span>
                            <h5 class="font-weight-bold mt-3">Are You Sure To Reject This Item ?</h5>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <script type="text/javascript">
        function showfield(name){
            if(name == 'Other') {
                document.getElementById('div1').innerHTML = '<div class="form-group mt-3 mb-3"><input type="text" class="form-control" id="invoiceAddress" name="invoiceAddress" placeholder="Input Alamat..."></div>';
            }
            else {
                document.getElementById('div1').innerHTML='';
            }
        }
        function showfield2(name){
            if(name == 'Other2') {
                document.getElementById('div2').innerHTML = '<div class="form-group mt-3 mb-3"><input type="text" class="form-control" id="itemAddress" name="itemAddress" placeholder="Input Alamat..."></div>';
            }
            else {
                document.getElementById('div2').innerHTML='';
            }
        }

        setTimeout(function() {
            $('.alert').fadeOut('fast');
        }, 3000);
    </script>

    <style>
        h5{
            font-size: 16px;
        }
        label{
            font-weight: bold;
        }
        th{
            color: white;
        }
        td, th{
            word-wrap: break-word;
            min-width: 140px;
            max-width: 140px;
            text-align: left;
            vertical-align: middle;
        }
        .center{
            text-align: center;
        }
        .alert{
                text-align: center;
        }
        .modal-backdrop {
            height: 100%;
            width: 100%;
        }
    </style>

    <?php $__env->stopSection(); ?>
<?php else: ?>
    <?php echo $__env->make('../layouts/notAuthorized', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/purchasing/purchasingApprovedPage.blade.php ENDPATH**/ ?>