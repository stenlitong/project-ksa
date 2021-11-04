<?php if(Auth::user()->hasRole('logistic')): ?>
    

    <?php $__env->startSection('title', 'Logistic Approve Order'); ?>

    <?php $__env->startSection('container'); ?>
    <div class="row">
        <?php echo $__env->make('logistic.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

                <div class="wrapper flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                    <h2 class="mt-3 mb-2" style="text-align: center">Order <?php echo e($orderHeads -> order_id); ?></h2>
                    <h1 class="mt-3 mb-2" style="text-align: center">Mail Of Goods Out</h1>
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>
                    
                    <?php if(session('status')): ?>
                        <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <?php $__errorArgs = ['acceptedQuantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                            Invalid Quantity
                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <div class="row mt-5">
                        <div class="col">
                            <form method="POST" action="/logistic/order/<?php echo e($orderHeads -> id); ?>/approve">
                                <?php echo csrf_field(); ?>
                                <div class="form-group">
                                    <label for="boatName">Nama Kapal</label>
                                    <input type="text" class="form-control" id="boatName" name="boatName" value="<?php echo e($orderHeads -> boatName); ?>" readonly>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="sender">Yang Menyerahkan</label>
                                            <input type="text" class="form-control" id="sender" name="sender" value="<?php echo e(Auth::user()->name); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for="receiver">Yang Menerima</label>
                                                <input type="text" class="form-control" id="receiver" name="receiver" value="<?php echo e($orderHeads -> user -> name); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="expedition">Ekspedisi</label>
                                    <select class="form-control" id="expedition" name="expedition">
                                        <option value="onsite">Onsite</option>
                                        <option value="JNE">JNE</option>
                                        <option value="TIKI">TIKI</option>
                                        <option value="JWT">JWT</option>
                                        <option value="Lion">Lion</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="noResi">Nomor Resi (optional)</label>
                                    <input type="text" class="form-control" id="noResi" name="noResi"
                                        placeholder="Input Nomor Resi (optional)">
                                </div>
                                <div class="form-group">
                                    <label for="company">Perusahaan (PR Requirements)</label>
                                    <select class="form-control" name="company" id="company">
                                        <option value="KSA">KSA</option>
                                        <option value="ISA">ISA</option>
                                        <option value="KSAO">KSA OFFSHORE</option>
                                        <option value="KSAM">KSA MARITIME</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="description">Deskripsi (optional)</label>
                                    <textarea class="form-control" name="description" id="description" rows="3"
                                        placeholder="Input Keterangan"></textarea>
                                </div>
                                
                                <div class="d-flex justify-content-center">
                                    <a href="/dashboard" class="btn btn-danger">Cancel</a>
                                    <button type="submit" class="btn btn-primary ml-2">Submit</button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col mt-3 table-wrapper-scroll-y my-custom-scrollbar tableFixHead" style="overflow-x:auto;">
                            <table class="table">
                                <thead class="thead bg-danger">
                                    <tr>
                                        <th scope="col">Nama Barang</th>
                                        <th scope="col">Request Quantity</th>
                                        <th scope="col">Department</th>
                                        <th scope="col">Stok</th>
                                        <th scope="col">Accepted Qty</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $od): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="bg-white"><strong><?php echo e($od -> item -> itemName); ?></strong></td>
                                        <td class="bg-white"><strong><?php echo e($od -> quantity); ?> <?php echo e($od -> item -> unit); ?></strong></td>
                                        <td class="bg-white"><?php echo e($od -> department); ?></td>
                                        <?php if(preg_replace('/[a-zA-z ]/', '', $od -> quantity) > $od -> item -> itemStock): ?>
                                            <td class="bg-white" style="color: red"><strong><?php echo e($od -> item -> itemStock); ?> <?php echo e($od -> item -> unit); ?> (Stok Tidak Mencukupi)</strong></td>
                                        <?php else: ?>
                                            <td class="bg-white" style="color: green"><strong><?php echo e($od -> item -> itemStock); ?> <?php echo e($od -> item -> unit); ?></strong></td>
                                        <?php endif; ?>
                                        <form action="/logistic/order/<?php echo e($orderHeads -> id); ?>/edit/<?php echo e($od -> id); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('patch'); ?>
                                            <td class="bg-white">
                                                <input class="w-50" type="number" min="1" max="<?php echo e($od -> item -> itemStock); ?>" value="<?php echo e($od -> acceptedQuantity); ?>" name="acceptedQuantity" id="acceptedQuantity"> <?php echo e($od -> item -> unit); ?>

                                            </td>
                                            <td class="bg-white">
                                                <button type="submit" class="btn btn-info btn-sm">Save</button>
                                            </td>
                                        </form>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
    </div>

    <style>
        th{
            color: white;
        }
        td, th{
            word-wrap: break-word;
            min-width: 120px;
            max-width: 120px;
            text-align: center;
            vertical-align: middle;
        }
        label{
            font-weight: bold;
        }
        .wrapper{
            padding: 10px;
            border-radius: 10px;
            background-color: antiquewhite;
            height: 850px;
            /* height: 100%; */
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
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/logistic/logisticApprovedOrder.blade.php ENDPATH**/ ?>