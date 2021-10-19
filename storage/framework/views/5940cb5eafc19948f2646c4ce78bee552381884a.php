<?php if(Auth::user()->hasRole('logistic')): ?>
    

    <?php $__env->startSection('title', 'Logistic Make Order'); ?>

    <?php $__env->startSection('container'); ?>
    <div class="row">
        <?php echo $__env->make('logistic.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                <h1 style="margin-left: 40%">Create Order</h1>
                <br>
                <?php if(session('status')): ?>
                    <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('errorCart')): ?>
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        <?php echo e(session('errorCart')); ?>

                    </div>
                <?php endif; ?>
                
                <?php $__errorArgs = ['item_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        Nama Barang
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

                <?php $__errorArgs = ['tugName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        Nama Tug Invalid
                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <?php $__errorArgs = ['bargeName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        Nama Barge Invalid
                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <?php $__errorArgs = ['department'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        Nama Department Invalid
                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                
                <?php $__errorArgs = ['golongan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        Golongan Invalid
                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <div class="row">
                    <div class="col">
                        <form method="POST" action="/logistic/<?php echo e(Auth::user()->id); ?>/add-cart">
                            <?php echo csrf_field(); ?>
                            <div class="d-flex justify-content-around mr-3">
                                <div class="form-group p-2">
                                    <label for="item_id" class="mt-3 mb-3">Item</label>
                                    <br>
                                    <select class="form-control" name="item_id" id="item_id" style="width: 500px; height:50px;">
                                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($i -> id); ?>"><?php echo e($i -> itemName); ?> (<?php echo e($i -> cabang); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-around mr-3">
                                <div class="form-group p-2">
                                    <label for="quantity" class="mb-3">Quantity</label>
                                    <input name="quantity" type="text" class="form-control" id="quantity" placeholder="Input quantity dalam angka..."
                                        style="width: 500px; height: 50px">
                                </div>
                            </div>
                            <div class="d-flex justify-content-around mr-3">
                                <div class="form-group p-2">
                                    <label for="department" class="mb-3">Department (optional)</label>
                                    <br>
                                    <select class="form-control" name="department" id="department" style="width: 500px; height:50px;">
                                        <option value="">None</option>
                                        <option value="deck">Deck</option>
                                        <option value="mesin">Mesin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-around mr-3">
                                <div class="form-group p-2">
                                    <label for="golongan" class="mb-3">Golongan</label>
                                    <br>
                                    <select class="form-control" name="golongan" id="golongan" style="width: 500px; height:50px;">
                                        <option value="none">None</option>
                                        <option value="Floating">Floating</option>
                                        <option value="Dock">Dock</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-around mr-3">
                                <div class="form-group p-2">
                                    <label for="note">Note (optional)</label>
                                    <br>
                                    <textarea class="form-control" name="note" Note="note" Note="3"
                                        placeholder="Input Deskripsi Barang" style="width: 500px; height: 100px"></textarea>
                                </div>
                            </div>
            
                            <br>
                            <div class="d-flex ml-3 justify-content-center">
                                
                                <button type="submit" class="btn btn-success mr-3" style="">Add To Cart</button>
                                
                                
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#submit-order">Submit Order</button>

                            </div>
                        </form>
                    </div>
                    <div class="col mt-5 mr-3 table-wrapper-scroll-y my-custom-scrollbar tableFixHead">
                        <table class="table">
                            <thead class="thead bg-danger">
                                <tr>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Golongan</th>
                                    <th scope="col">Note</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $carts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($c -> item -> itemName); ?></td>
                                        <td><?php echo e($c -> quantity); ?> <?php echo e($c -> item -> unit); ?></td>
                                        <td><?php echo e($c -> department); ?></td>
                                        <td><?php echo e($c -> golongan); ?></td>
                                        <td><?php echo e($c -> note); ?></td>
                                        
                                        <form method="POST" action="/logistic/<?php echo e($c -> id); ?>/delete">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('delete'); ?>
                                            <td><button class="btn btn-danger">Delete Item</button></td>
                                        </form>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <div class="modal fade" id="submit-order" tabindex="-1" role="dialog" aria-labelledby="submit-orderTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 600px;">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title ml-3" id="submitTitle">Input PR Requirements</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/logistic/<?php echo e(Auth::user()->id); ?>/submit-order">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body"> 
                        <div class="d-flex justify-content-start mr-3">
                            <div class="form-group p-2">
                                <div class="col">
                                    <label for="tugs">Pilih Tug:</label>
                                </div>
                                <div class="col">
                                    <input list="tugName" name="tugName" class="mt-2 mb-2" style="width: 400px; height:45px"/>
                                    <datalist id="tugName">
                                        <?php $__currentLoopData = $tugs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($t -> tugName); ?>"><?php echo e($t -> tugName); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-start mr-3">
                            <div class="form-group p-2">
                                <div class="col">
                                    <label for="bargeName">Pilih Barge (Optional):</label>
                                </div>
                                <div class="col">
                                    <input list="bargeName" name="bargeName" class="mb-2" style="width: 400px; height:45px"/>
                                    <datalist id="bargeName">
                                        <?php $__currentLoopData = $barges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($b -> bargeName); ?>"><?php echo e($b -> bargeName); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-start mr-3">
                            <div class="form-group p-2">
                                <div class="col">
                                    <label for="company" class="mb-3">Perusahaan</label>
                                </div>
                                <div class="col">
                                    <select class="form-control" name="company" id="company" style="width: 400px; height:50px;">
                                        <option value="KSA">KSA</option>
                                        <option value="ISA">ISA</option>
                                        <option value="KSAO">KSA OFFSHORE</option>
                                        <option value="KSAM">KSA MARITIME</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary mr-3">Submit</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

    <style>
        th{
            color: white;
        }
        td{
            word-wrap: break-word;
            min-width: 100px;
            max-width: 160px;
        }
        .tableFixHead          { overflow: auto; height: 250px; }
        .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }

        .my-custom-scrollbar {
            position: relative;
            height: 600px;
            overflow: auto;
        }
        .table-wrapper-scroll-y {
            display: block;
        }
        .alert{
                text-align: center;
            }
    </style>

    <?php $__env->stopSection(); ?>
<?php else: ?>
    <?php echo $__env->make('../layouts/notAuthorized', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/logistic/logisticMakeOrder.blade.php ENDPATH**/ ?>