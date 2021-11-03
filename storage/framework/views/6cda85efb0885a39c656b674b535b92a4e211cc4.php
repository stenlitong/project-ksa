<?php if(Auth::user()->hasRole('crew')): ?>
    

    <?php $__env->startSection('title', 'Crew Order'); ?>

    <?php $__env->startSection('container'); ?>
    <div class="row">
        <?php echo $__env->make('crew.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                <h1 style="text-align: center">Create Order</h1>
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

                <div class="row">
                    <div class="col">
                        <form method="POST" action="/crew/<?php echo e(Auth::user()->id); ?>/add-cart">
                            <?php echo csrf_field(); ?>
                            <div class="form-group p-2">
                                <label for="item_id" class="mt-3 ">Item</label>
                                <br>
                                <select class="form-control" name="item_id" id="item_id" style=" height:50px;">
                                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($i -> id); ?>"><?php echo e($i -> itemName); ?> (<?php echo e($i -> unit); ?>) - (<?php echo e(Auth::user()->cabang); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
        
                            <div class="form-group p-2">
                                <label for="department" class="mt-3 ">Department</label>
                                <br>
                                <select class="form-control" name="department" id="department" style=" height:50px;">
                                    <option value="deck">Deck</option>
                                    <option value="mesin">Mesin</option>
                                </select>
                            </div>

                            <div class="form-group p-2">
                                <label for="quantity" class="mt-3 ">Quantity</label>
                                <input name="quantity" type="number" min="1" class="form-control" id="quantity" placeholder="Input quantity dalam angka..."
                                    style=" height: 50px" required>
                            </div>
            
                            <br>
                            <div class="d-flex ml-3 justify-content-center">
                                
                                <button type="submit" class="btn btn-success mr-3" style="">Add To Cart</button>
                                
                                
                                
                                
                                
                                
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#submit-order">Submit Order</button>

                            </div>
                        </form>
                    </div>
                    <div class="col mt-5 table-wrapper-scroll-y my-custom-scrollbar tableFixHead" style="overflow-x:auto;">
                        <table class="table">
                            <thead class="thead bg-danger">
                                <tr>
                                    <th scope="col">Nomor</th>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $carts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>
                                        <td><?php echo e($c -> item -> itemName); ?></td>
                                        <td><?php echo e($c -> quantity); ?> <?php echo e($c -> item -> unit); ?></td>
                                        <td><?php echo e($c -> department); ?></td>
                                        
                                        <form method="POST" action="/crew/<?php echo e($c -> id); ?>/delete">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('delete'); ?>
                                            <td><button class="btn btn-warning">Delete Item</button></td>
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
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger" style="color: white">
                    <h5 class="modal-title" id="submitTitle">Input Nama Kapal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/crew/<?php echo e(Auth::user()->id); ?>/submit-order">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body"> 
                        <div class="row">
                            <div class="col">
                                <label>Tug</label>
                                <select class="form-control" name="tugName" id="tugName" style=" height:50px;">
                                    <?php $__currentLoopData = $tugs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($t -> tugName); ?>"><?php echo e($t -> tugName); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                
                            </div>
                            <div class="col">
                                <label>Barge (Optional)</label>
                                <select class="form-control" name="bargeName" id="bargeName" style=" height:50px;">
                                        <option value="">None</option>
                                    <?php $__currentLoopData = $barges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($b -> bargeName); ?>"><?php echo e($b -> bargeName); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
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
        td, th{
            word-wrap: break-word;
            min-width: 100px;
            max-width: 120px;
            text-align: center;
            vertical-align: middle;
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
        .modal-backdrop {
            height: 100%;
            width: 100%;
        }
    </style>

    <script>
        setTimeout(function() {
            $('.alert').fadeOut('fast');
        }, 3000); 
    </script>

    <?php $__env->stopSection(); ?>
<?php else: ?>
    <?php echo $__env->make('../layouts/notAuthorized', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/crew/crewOrder.blade.php ENDPATH**/ ?>