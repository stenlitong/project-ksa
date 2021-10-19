

<?php $__env->startSection('title', 'Crew Task'); ?>

<?php $__env->startSection('container'); ?>
    <div class="row">
        <?php echo $__env->make('crew.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                <h1 style="margin-left: 40%">Create Task</h1>
                <br>
                <?php if(session('status')): ?>
                    <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>

            </div>
        </main>
        
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/crew/crewTask.blade.php ENDPATH**/ ?>