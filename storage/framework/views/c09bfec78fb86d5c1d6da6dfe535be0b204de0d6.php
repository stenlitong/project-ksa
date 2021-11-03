<?php if(Auth::user()->hasRole('purchasing')): ?>
    

    <?php $__env->startSection('title', 'Purchasing Approve Order'); ?>

    <?php $__env->startSection('container'); ?>
    <div class="row">
        <?php echo $__env->make('purchasing.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

            <h2 class="mt-3" style="text-align: center">Order <?php echo e($orderHeads -> order_id); ?></h2>

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

            <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Harga Invalid
            </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['kurs'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Kurs Invalid
            </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <div class="row mt-4">
                    <div class="col">
                        <form method="POST" action="">
                            <?php echo csrf_field(); ?>
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
                                <label for="supplier_id">Supplier</label>
                                <select class="form-control" id="supplier_id" name="supplier_id">
                                    <option value="" disabled>Choose Supplier...</option>
                                    <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($s -> id); ?>"><?php echo e($s -> supplierName); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="price" class="mb-2">Total Harga</label>
                                <div class="input-group">
                                    
                                    <select class="form-control" id="kurs" name="kurs">
                                        <option value="" disabled>Choose Kurs...</option>
                                        <option value="Rp.">Rupiah</option>
                                        <option value="$">Dollar</option>
                                    </select>
                                    <input type="number" min="1" class="form-control w-75" id="price" name="price" placeholder="Input total harga dalam angka..." required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="descriptions">Deskripsi (optional)</label>
                                <textarea class="form-control" name="descriptions" id="descriptions" rows="3"
                                    placeholder="Input Keterangan"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary mb-5" style="margin-left: 45%">Submit</button>
                        </form>
                    </div>
                    <div class="col mt-3">
                        <table class="table" id="myTable">
                            <thead class="thead bg-danger">
                                <tr>
                                    <th scope="col">Nomor</th>
                                    <th scope="col">Item Barang</th>
                                    <th scope="col">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $od): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>
                                        <td><?php echo e($od -> item -> itemName); ?></td>
                                        <td><?php echo e($od -> quantity); ?> <?php echo e($od -> item -> unit); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
    </div>

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
        th{
            color: white;
        }
        td, th{
            word-wrap: break-word;
            min-width: 140px;
            max-width: 160px;
            text-align: center;
        }
        .alert{
                text-align: center;
            }
    </style>

    <?php $__env->stopSection(); ?>
<?php else: ?>
    <?php echo $__env->make('../layouts/notAuthorized', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/purchasing/purchasingApprovedPage.blade.php ENDPATH**/ ?>