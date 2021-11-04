<?php if(Auth::user()->hasRole('logistic')): ?>
    

    <?php $__env->startSection('title', 'Logistic Stocks'); ?>

    <?php $__env->startSection('container'); ?>
        <?php echo $__env->make('logistic.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-5">
            <div class="wrapper">
            <h1 class="mb-3" style="text-align: center">Stock Availability</h1>
                
<?php
// Program to display complete URL
  
$link = $_SERVER['PHP_SELF'];
  
// Display the complete URL
echo $link;
?>
            <br>
            
            <?php if(session('itemInvalid')): ?>
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    <?php echo e(session('itemInvalid')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php $__errorArgs = ['itemName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Nama Barang Invalid
            </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['cabang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Cabang Invalid
            </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['cabang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Cabang Invalid
            </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Quantity Invalid
            </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Satuan Invalid
            </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <div class="row">
                <div class="col-md-6">
                    <form action="">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Search Item by Nama, Cabang, Kode Barang..." name="search" id="search">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
                <div class="col">
                    <select name="cabang" class="form-select w-25" onchange="window.location = this.value;">
                        <option selected disabled>Pilih Cabang</option>
                        <option value="/logistic/stocks?search=All">Semua Cabang</option>
                        <option value="/logistic/stocks?search=Jakarta">Jakarta</option>
                        <option value="/logistic/stocks?search=Banjarmasin">Banjarmasin</option>
                        <option value="/logistic/stocks?search=Samarinda">Samarinda</option>
                        <option value="/logistic/stocks?search=Bunati">Bunati</option>
                        <option value="/logistic/stocks?search=Babelan">Babelan</option>
                        <option value="/logistic/stocks?search=Berau">Berau</option>
                       </select>
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <?php echo e($items->links()); ?>

            </div>

            <div id="content" style="overflow-x: auto">
                <table class="table mb-5">
                    <thead class="thead bg-danger">
                    <tr>
                        <th scope="col" style="color: white">Item Barang</th>
                        <th scope="col" style="color: white">Umur Barang</th>
                        <th scope="col" style="color: white">Quantity</th>
                        <th scope="col" style="color: white">Serial Number</th>
                        <th scope="col" style="color: white">Code Master Item</th>
                        <th scope="col" style="color: white">Cabang</th>
                        <th scope="col" style="color: white">Deskripsi</th>
                        <th scope="col" style="color: white">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="bg-white"><?php echo e($i -> itemName); ?></td>
                                <td class="bg-white"><?php echo e($i -> itemAge); ?></td>
                                <td class="bg-white"><?php echo e($i -> itemStock); ?> <?php echo e($i -> unit); ?></td>
                                <td class="bg-white"><?php echo e($i -> serialNo); ?></td>
                                <td class="bg-white"><?php echo e($i -> codeMasterItem); ?></td>
                                <td class="bg-white"><?php echo e($i -> cabang); ?></td>
                                <td class="bg-white"><?php echo e($i -> description); ?></td>
                                <?php if($i -> cabang != Auth::user()->cabang): ?>
                                    <td class="bg-white"><button class="btn btn-warning" data-toggle="modal" data-target="#request-stock-<?php echo e($i -> id); ?>" style="color: white">Request Delivery</button></td>
                                <?php else: ?>
                                    <td class="bg-white"></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            </div>

            <!-- Modal #1 -->
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="modal fade" id="request-stock-<?php echo e($i->id); ?>" tabindex="-1" role="dialog" aria-labelledby="requestStockTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="editItemTitle" style="color: white">Request Stock</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="/logistic/stocks/<?php echo e($i -> id); ?>/request">
                                    <?php echo csrf_field(); ?>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="itemName">Nama Barang <strong>(Periksa Kembali Nama Barang)</strong></label>
                                                <input type="text" class="form-control" id="itemName" name="itemName"
                                                    placeholder="Input Nama Barang" value="<?php echo e($i -> itemName); ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="cabang">Cabang</label>
                                                <input type="text" class="form-control" id="cabang" name="cabang" value="<?php echo e($i -> cabang); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="quantity">Quantity <strong>(Periksa Kembali Stok Barang)</strong></label>
                                                <input type="number" min="1" class="form-control" id="quantity" name="quantity"
                                                    placeholder="Input Quantity Dalam Angka">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="unit">Satuan</label>
                                                <input type="text" class="form-control" id="unit" name="unit" value="<?php echo e($i -> unit); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Deskripsi (optional)</label>
                                        <textarea class="form-control" name="description" id="description" rows="3"
                                            placeholder="Input Deskripsi Tambahan"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </main>

        <style>
            body{
                /* background-image: url('/images/logistic-background.png'); */
                background-repeat: no-repeat;
                background-size: cover;
            }
            .wrapper{
                padding: 15px;
                margin: 15px;
                border-radius: 10px;
                background-color: antiquewhite;
                height: 1000px;
                /* height: 100%; */
            }
            
            th, td{
                word-wrap: break-word;
                min-width: 140px;
                max-width: 140px;
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

        <script type="text/javascript">
            function refreshDiv(){
                $('#content').load(location.href + ' #content')
            }
            setInterval(refreshDiv, 60000);
            
            setTimeout(function() {
            $('.alert').fadeOut('fast');
        }, 3000); 
        </script>

    <?php $__env->stopSection(); ?>
<?php else: ?>
    <?php echo $__env->make('../layouts/notAuthorized', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/logistic/stocksPage.blade.php ENDPATH**/ ?>