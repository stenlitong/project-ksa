<?php if(Auth::user()->hasRole('adminLogistic')): ?>
    

    <?php $__env->startSection('title', 'Make Order Page'); ?>

    <?php $__env->startSection('container'); ?>
    <div class="row">
        <?php echo $__env->make('adminLogistic.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex-column container">
                <h1 class="mb-3" style="margin-top: 10%">Pilih Cabang</h1>

                <?php if(session('status')): ?>
                    <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>

                <form action="/admin-logistic/create-order" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label for="cabang">Cabang</label>
                        <select class="form-control" id="cabang" name="cabang">
                            <option selected disabled="">Choose...</option>
                            <option value="Jakarta" id="Jakarta">Jakarta</option>
                            <option value="Banjarmasin" id="Banjarmasin">Banjarmasin</option>
                            <option value="Samarinda" id="Samarinda">Samarinda</option>
                            <option value="Bunati" id="Bunati">Bunati</option>
                            <option value="Babelan" id="Babelan">Babelan</option>
                            <option value="Berau" id="Berau">Berau</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </main>
    </div>
    <?php $__env->stopSection(); ?>

<?php endif; ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/adminLogistic/adminLogisticPreMakeOrderPage.blade.php ENDPATH**/ ?>