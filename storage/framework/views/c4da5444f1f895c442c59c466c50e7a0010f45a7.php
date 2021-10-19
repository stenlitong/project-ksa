<?php if(Auth::user()->hasRole('supervisor') || Auth::user()->hasRole('supervisorMaster')): ?>
    

    <?php $__env->startSection('title', 'Supervisor Stocks'); ?>

    <?php $__env->startSection('container'); ?>
        <?php echo $__env->make('supervisor.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-5">
            <h1 class="mb-3" style="margin-left: 40%">Stock Availability</h1>

            <br>
            <?php if(session('status')): ?>
                <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                    <?php echo e(session('status')); ?>

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
            
            <?php $__errorArgs = ['itemAge'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Umur Barang Invalid
            </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['itemStock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Stok Barang Invalid
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
                Satuan Unit Invalid
            </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            
            <?php $__errorArgs = ['codeMasterItem'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Code Master Item Invalid
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

            <?php if(Auth::user()->hasRole('supervisorMaster')): ?>
                <!-- Button trigger modal #1 -->
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addItem">
                    Add Item +
                </button>
            <?php endif; ?>
            
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

            <!-- Modal #1 -->
            <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addItem"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addItemTitle">Add New Item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="/supervisor/item-stocks">
                                <?php echo csrf_field(); ?>
                                <div class="form-group">
                                    <label for="itemName">Nama Barang</label>
                                    <input type="text" class="form-control" id="itemName" name="itemName"
                                        placeholder="Input Nama Barang">
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="itemAge">Umur Barang</label>
                                            <input type="text" class="form-control" id="itemAge" name="itemAge"
                                                placeholder="Input Umur Barang Dalam Angka">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="umur">Bulan/Tahun</label>
                                            <select class="form-control" id="umur" name="umur">
                                                <option value="Bulan">Bulan</option>
                                                <option value="Tahun">Tahun</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="itemStock">Stok Barang</label>
                                            <input type="text" class="form-control" id="itemStock" name="itemStock"
                                                placeholder="Input Stok Barang">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Unit<input list="unit" name="unit" class="mt-2" style="width: 400px; height:40px"/></label>
                                            <datalist id="unit">
                                            <option value="Bks">
                                            <option value="Btg">
                                            <option value="Btl">
                                            <option value="Cm">
                                            <option value="Crt">
                                            <option value="Cyl">
                                            <option value="Doz">
                                            <option value="Drm">
                                            <option value="Duz">
                                            <option value="Gln">
                                            <option value="Jrg">
                                            <option value="Kbk">
                                            <option value="Kg">
                                            <option value="Klg">
                                            <option value="Ktk">
                                            <option value="Lbr">
                                            <option value="Lgt">
                                            <option value="Ls">
                                            <option value="Ltr">
                                            <option value="Mtr">
                                            <option value="Pak">
                                            <option value="Pal">
                                            <option value="Pax">
                                            <option value="Pc">
                                            <option value="Pcs">
                                            <option value="Plt">
                                            <option value="Psg">
                                            <option value="Ptg">
                                            <option value="Ret">
                                            <option value="Rol">
                                            <option value="Sak">
                                            <option value="SET">
                                            <option value="Tbg">
                                            <option value="Trk">
                                            <option value="Unt">
                                            <option value="Zak">
                                            </datalist>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="serialNo">Serial Number / Part Number (optional)</label>
                                    <input type="text" class="form-control" id="serialNo" name="serialNo"
                                        placeholder="Input Serial Number">
                                </div>
                                <div class="form-group">
                                    <label for="codeMasterItem">Code Master Item</label>
                                    <input type="text" class="form-control" id="codeMasterItem" name="codeMasterItem"
                                        placeholder="Input Code Master Item (xx-xxxx-)">
                                </div>
                                <div class="form-group">
                                    <label for="cabang">Cabang</label>
                                    <select class="form-control" id="cabang" name="cabang">
                                        <option selected disabled="">Choose...</option>
                                        <option value="Jakarta" id="Jakarta">Jakarta</option>
                                        <option value="Banjarmasin" id="Banjarmasin">Banjarmasin</option>
                                        <option value="Samarinda" id="Samarinda">Samarinda</option>
                                        <option value="Bunati" id ="Bunati">Bunati</option>
                                        <option value="Babelan"id ="Babelan">Babelan</option>
                                        <option value="Berau" id ="Berau">Berau</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="description">Deskripsi (optional)</label>
                                    <textarea class="form-control" name="description" id="description" rows="3"
                                        placeholder="Input Deskripsi Barang"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Add Item</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
                    <?php if(Auth::user()->hasRole('supervisorMaster')): ?>
                        <th scope="col" style="color: white">Action</th>
                    <?php endif; ?>
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
                            <?php if(Auth::user()->hasRole('supervisorMaster')): ?>
                            <td>
                                <div class="d-flex justify-content-start">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal" id="detail" data-target="#editItem-<?php echo e($i->id); ?>">
                                        Edit
                                    </button>
                                    
                                    <form method="POST" action="" >
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('delete'); ?>
                                        <button class="btn btn-danger">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
              </table>

            <!-- Modal #2 -->
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="modal fade" id="editItem-<?php echo e($i->id); ?>" tabindex="-1" role="dialog" aria-labelledby="editItemTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editItemTitle">Edit Item</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="/supervisor/item-stocks/<?php echo e($i -> id); ?>/edit-item">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label for="itemName">Nama Barang</label>
                                        <input type="text" class="form-control" id="itemName" name="itemName"
                                            placeholder="Input Nama Barang" value="<?php echo e($i -> itemName); ?>">
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="itemAge">Umur Barang</label>
                                                <input type="text" class="form-control" id="itemAge" name="itemAge"
                                                    placeholder="Input Umur Barang Dalam Angka">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="umur">Bulan/Tahun</label>
                                                <select class="form-control" id="umur" name="umur">
                                                    <option value="Bulan">Bulan</option>
                                                    <option value="Tahun">Tahun</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="itemStock">Stok Barang</label>
                                                <input type="text" class="form-control" id="itemStock" name="itemStock"
                                                    placeholder="Input Stok Barang" value="<?php echo e($i -> itemStock); ?>">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Unit<input list="unit" name="unit" class="mt-2" style="width: 400px; height:40px"/></label>
                                                <datalist id="unit">
                                                <option value="Bks">
                                                <option value="Btg">
                                                <option value="Btl">
                                                <option value="Cm">
                                                <option value="Crt">
                                                <option value="Cyl">
                                                <option value="Doz">
                                                <option value="Drm">
                                                <option value="Duz">
                                                <option value="Gln">
                                                <option value="Jrg">
                                                <option value="Kbk">
                                                <option value="Kg">
                                                <option value="Klg">
                                                <option value="Ktk">
                                                <option value="Lbr">
                                                <option value="Lgt">
                                                <option value="Ls">
                                                <option value="Ltr">
                                                <option value="Mtr">
                                                <option value="Pak">
                                                <option value="Pal">
                                                <option value="Pax">
                                                <option value="Pc">
                                                <option value="Pcs">
                                                <option value="Plt">
                                                <option value="Psg">
                                                <option value="Ptg">
                                                <option value="Ret">
                                                <option value="Rol">
                                                <option value="Sak">
                                                <option value="SET">
                                                <option value="Tbg">
                                                <option value="Trk">
                                                <option value="Unt">
                                                <option value="Zak">
                                                </datalist>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="serialNo">Serial Number / Part Number (optional)</label>
                                        <input type="text" class="form-control" id="serialNo" name="serialNo"
                                            placeholder="Input Serial Number" value="<?php echo e($i -> serialNo); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="codeMasterItem">Code Master Item</label>
                                        <input type="text" class="form-control" id="codeMasterItem" name="codeMasterItem"
                                            placeholder="Input Code Master Item (xx-xxxx-)" value="<?php echo e($i -> codeMasterItem); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Deskripsi (optional)</label>
                                        <textarea class="form-control" name="description" id="description" rows="3"
                                            placeholder="Input Deskripsi Barang"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Edit Item</button>
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
                min-width: 160px;
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
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/supervisor/supervisorItemStock.blade.php ENDPATH**/ ?>