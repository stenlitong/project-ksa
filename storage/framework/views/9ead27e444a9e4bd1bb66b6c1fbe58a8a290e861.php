<?php if(Auth::user()->hasRole('logistic')): ?>
    

    <?php $__env->startSection('title', 'Logistic Stocks'); ?>

    <?php $__env->startSection('container'); ?>
        <?php echo $__env->make('logistic.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-5">
            <h1 class="mb-3" style="margin-left: 40%">Stock Availability</h1>

            <br>
            
            <?php if(session('itemInvalid')): ?>
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    <?php echo e(session('itemInvalid')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                    <?php echo e(session('success')); ?>

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
            </div>
            
            <div class="d-flex justify-content-end">
                <?php echo e($items->links()); ?>

            </div>

            <table class="table mb-5">
                <thead class="thead bg-danger">
                  <tr>
                    <th scope="col" style="color: white">No</th>
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
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <th><?php echo e($key + 1); ?></th>
                            <td><?php echo e($i -> itemName); ?></td>
                            <td><?php echo e($i -> itemAge); ?></td>
                            <td><?php echo e($i -> itemStock); ?> <?php echo e($i -> unit); ?></td>
                            <td><?php echo e($i -> serialNo); ?></td>
                            <td><?php echo e($i -> codeMasterItem); ?></td>
                            <td><?php echo e($i -> cabang); ?></td>
                            <td><?php echo e($i -> description); ?></td>
                            <?php if($i -> cabang != Auth::user()->cabang): ?>
                                <td><button class="btn btn-warning" data-toggle="modal" data-target="#request-stock-<?php echo e($i -> id); ?>" style="color: white">Request Delivery</button></td>
                            <?php else: ?>
                                <td></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
              </table>

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
                                                <input type="text" class="form-control" id="quantity" name="quantity"
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
            th, td{
                word-wrap: break-word;
                min-width: 140px;
                max-width: 140px;
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
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/logistic/stocksPage.blade.php ENDPATH**/ ?>