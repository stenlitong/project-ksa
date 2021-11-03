

<?php $__env->startSection('title', 'Admin Purchasing Dashboard'); ?>

<?php $__env->startSection('container'); ?>
<div class="row">
    <?php echo $__env->make('adminPurchasing.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <?php echo $__env->make('../layouts/time', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php if(session('status')): ?>
            <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <?php $__errorArgs = ['supplierName'];
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

        <?php $__errorArgs = ['noTelp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Nomor Telepon Invalid
        </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <?php $__errorArgs = ['supplierEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Email Supplier Invalid
        </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <?php $__errorArgs = ['supplierAddress'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Alamat Supplier Invalid
        </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <?php $__errorArgs = ['supplierNPWP'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            NPWP Supplier Invalid
        </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <div class="row">
            <div class="col" style="overflow-x: auto; max-width: 850px">
                <h2 class="mb-4" style="text-align: center">Contact Suppliers</h2>
                <div class="flex-column flex-nowrap scrolling-wrapper">
                    <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card border-dark w-100 mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <img src="/images/profile.png" style="height: 100px; width: 100px;">
                                        <h5 class="mt-3"><?php echo e($s -> supplierName); ?></h5>
                                    </div>
                                    <div class="col" style="">
                                            <h5 class="smaller-screen-size"><span data-feather="phone"></span> (+62) <?php echo e($s -> noTelp); ?></h5>
                                            <h5 class="smaller-screen-size"><span data-feather="mail"></span> <?php echo e($s -> supplierEmail); ?></h5>
                                            <h5 class="smaller-screen-size"><span data-feather="home"></span> <?php echo e($s -> supplierAddress); ?></h5>
                                            <h5 class="smaller-screen-size"><span data-feather="credit-card"></span> <?php echo e($s -> supplierNPWP); ?></h5>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-sm btn-success mt-2 mr-3" data-toggle="modal" id="detail" data-target="#editItem-<?php echo e($s->id); ?>">Edit</button>
                                            
                                            <button class="btn btn-sm btn-danger mt-2" data-toggle="modal" id="delete" data-target="#deleteSupplier-<?php echo e($s -> id); ?>">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="col">
                <h2 class="mb-4" style="text-align: center">Add Suppliers</h2>
                <form method="POST" action="<?php echo e(Route('adminPurchasing.add-supplier')); ?>">
                    <?php echo csrf_field(); ?>
                        <div class="form-group p-2">
                            <label for="supplierName">Nama Supplier</label>
                            <input name="supplierName" type="text" class="form-control" id="supplierName" placeholder="Input nama supplier..."
                                style="height: 50px" value=<?php echo e(old('supplierName')); ?>>
                        </div>
                        <div class="form-group p-2">
                            <label for="noTelp">No. Telp Supplier</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">(+62)</div>
                                </div>
                                <input type="text" class="form-control" id="noTelp" name="noTelp" style="height: 50px" placeholder="Input nomor telepon dalam angka..." value=<?php echo e(old('noTelp')); ?>>
                            </div>
                        </div>
                        <div class="form-group p-2">
                            <label for="supplierEmail" class="mb-2">Email Supplier</label>
                            <input name="supplierEmail" type="text" class="form-control" id="supplierEmail" placeholder="Input email supplier..."
                                style="height: 50px" value=<?php echo e(old('supplierEmail')); ?>>
                        </div>
                        <div class="form-group p-2">
                            <label for="supplierAddress" class="mb-2">Alamat Supplier</label>
                            <input name="supplierAddress" type="text" class="form-control" id="supplierAddress" placeholder="Input alamat supplier..."
                                style="height: 50px" value=<?php echo e(old('supplierAddress')); ?>>
                        </div>
                        <div class="form-group p-2">
                            <label for="supplierNPWP" class="mb-2">NPWP Supplier</label>
                            <input name="supplierNPWP" type="text" class="form-control" id="supplierNPWP" placeholder="Input NPWP supplier..."
                                style="height: 50px" value=<?php echo e(old('supplierNPWP')); ?>>
                        </div>
                    <br>
                    <div class="d-flex ml-3 justify-content-center pb-3">
                        <button type="submit" class="btn btn-primary">Add Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="editItem-<?php echo e($s->id); ?>" tabindex="-1" role="dialog" aria-labelledby="editItemTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger" style="color: white">
                    <h5 class="modal-title" id="editItemTitle">Edit Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/admin-purchasing/<?php echo e($s -> id); ?>/edit">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('put'); ?>
                            <div class="form-group p-2">
                                <label for="supplierName" class="mb-2">Nama Supplier</label>
                                <input name="supplierName" type="text" class="form-control" id="supplierName" placeholder="Input nama supplier..."
                                    style="height: 50px" value="<?php echo e($s -> supplierName); ?>">
                            </div>
                            <div class="form-group p-2">
                                <label for="noTelp" class="mb-2">No. Telp Supplier</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">(+62)</div>
                                    </div>
                                    <input type="text" class="form-control" id="noTelp" name="noTelp" style="width: 450px; height: 50px" placeholder="Input nomor telepon dalam angka...">
                                </div>
                            </div>
                            <div class="form-group p-2">
                                <label for="supplierEmail" class="mb-2">Email Supplier</label>
                                <input name="supplierEmail" type="text" class="form-control" id="supplierEmail" placeholder="Input email supplier..."
                                    style="height: 50px" value="<?php echo e($s -> supplierEmail); ?>">
                            </div>
                            <div class="form-group p-2">
                                <label for="supplierAddress" class="mb-2">Alamat Supplier</label>
                                <input name="supplierAddress" type="text" class="form-control" id="supplierAddress" placeholder="Input alamat supplier..."
                                    style="height: 50px" value="<?php echo e($s -> supplierAddress); ?>">
                            </div>
                            <div class="form-group p-2">
                                <label for="supplierNPWP" class="mb-2">NPWP Supplier</label>
                                <input name="supplierNPWP" type="text" class="form-control" id="supplierNPWP" placeholder="Input NPWP supplier..."
                                    style="height: 50px" value=<?php echo e($s -> supplierNPWP); ?>>
                            </div>
                        <div class="d-flex ml-3 justify-content-center pb-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="deleteSupplier-<?php echo e($s->id); ?>" tabindex="-1" role="dialog" aria-labelledby="deleteSupplierTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="editItemTitle" style="color: white">Delete Supplier: <?php echo e($s -> supplierName); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <br>
                    <h5 style="text-align: center">Are You Sure To Delete This Supplier ?</h5>
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <form method="POST" action="/admin-purchasing/<?php echo e($s -> id); ?>/delete" >
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('delete'); ?>
                        <button class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<script>
    setTimeout(function() {
        $('.alert').fadeOut('fast');
    }, 3000);
</script>

<style>
    .modal-backdrop {
        height: 100%;
        width: 100%;
    }
    .scrolling-wrapper{
        overflow-y: auto;
        max-height: 600px;
    }
    .card-block{
	height: 425px;
	background-color: #fff;
	background-position: center;
	background-size: cover;
	transition: all 0.2s ease-in-out !important;
	border-radius: 24px;
	&:hover{
		box-shadow: none;
		opacity: 0.9;
	}
    .alert{
        text-align: center;
    }
    @media (min-width: 300px) and (max-width: 768){
        .smaller-screen-size{
            width: 150px;
            word-break: break-all;
            font-size: 12px;
        }
    }
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/adminPurchasing/adminPurchasingDashboard.blade.php ENDPATH**/ ?>