

<?php $__env->startSection('title', 'Logistic Dashboard'); ?>

<?php $__env->startSection('container'); ?>
<div class="row">
    <?php echo $__env->make('logistic.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <br>
    <?php if(session('status')): ?>
        <div class="alert alert-success" style="width: 40%; margin-left: 30%">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="mt-5 mb-5">
            <h2 class="text-center mb-5">Create Purchase Requisition</h2>
            <form method="POST" action="/logistic/order/<?php echo e($order -> id); ?>/approve">
                <?php echo csrf_field(); ?>
                <div class="form-row justify-content-between">
                    <div class="form-group col-md-6">
                        <label for="boatName">Nama Kapal</label>
                        <input type="text" class="form-control" id="boatName" name="boatName" placeholder="Nama Kapal">
                    </div>
                </div>
                <div class="form-row justify-content-between">
                    <div class="form-group col-md-3">
                        <label for="department">Department</label>
                        <input type="text" class="form-control" name="department" id="department" value="<?php echo e($order -> department); ?>" placeholder="<?php echo e($order -> department); ?>" readonly>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="company">Perusahaan</label>
                        <input type="text" class="form-control" id="company" name="company" placeholder="Ex : ISA">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="location">Daerah</label>
                        <select id="location" name="location" class="form-control">
                            <option value="JKT">Jakarta</option>
                            <option value="SMD">Samarinda</option>
                            <option value="BNJ">Banjarmasin</option>
                            <option value="MLK">Maluku</option>
                            <option value="MDN">Medan</option>
                            <option value="BNT">Bunati</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="itemName">Item Barang</label>
                        <input type="text" class="form-control" id="itemName" name="itemName" value="<?php echo e($order -> item-> itemName); ?>" placeholder="<?php echo e($order -> item -> itemName); ?>" readonly>
                    </div>
                </div>
                <div class="form-row justify-content-between">
                    <div class="form-group col-md-3">
                        <label for="prDate">Tanggal PR</label>
                        <input type="date" class="form-control" id="prDate" name="prDate" placeholder="">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="serialNo">Serial Number / Part Number</label>
                        <input type="text" class="form-control" id="serialNo" name="serialNo" placeholder="">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="quantity">Quantity</label>
                        <input type="text" class="form-control" id="quantity" name="quantity" value="<?php echo e($order -> quantity); ?>" placeholder="<?php echo e($order -> quantity); ?>" readonly>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="codeMasterItem">Code Master Item</label>
                        <input type="text" class="form-control" id="codeMasterItem" name="codeMasterItem" placeholder="">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="note">Note</label>
                        <textarea class="form-control" name="note" id="note" rows="3"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3" style="margin-left: 45%; width: 100px">Create</button>
            </form>
        </div>
    </main>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/logistic/logisticApproveOrder.blade.php ENDPATH**/ ?>