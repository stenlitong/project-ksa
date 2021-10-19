<?php if(Auth::user()->hasRole('purchasing')): ?>
    

    <?php $__env->startSection('title', 'Purchasing Form AP'); ?>

    <?php $__env->startSection('container'); ?>
    <div class="row">
        <?php echo $__env->make('purchasing.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="padding-bottom: 150px">
            <h2 class="mt-5 mb-3" style="text-align: center">Form AP List</h2>

            <?php if(session('status')): ?>
                <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    Dekripsi Wajib Diisi
                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <div class="table-wrapper-scroll-y my-custom-scrollbar tableFixHead mt-5">
                <table class="table table-bordered sortable">
                    <thead class="thead bg-danger">
                    <tr>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Status</th>
                        <th scope="col">Nama File</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">Action</th>
                        <th scope="col">Approval</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($doc -> submissionTime); ?></td>
                            <?php if(strpos($doc -> status, 'Denied') !== false): ?>
                                <td><span style="color: red;font-weight: bold;"><?php echo e($doc -> status); ?></span></td>
                            <?php elseif(strpos($doc -> status, 'Approved') !== false): ?>
                                <td><span style="color: green;font-weight: bold;"><?php echo e($doc -> status); ?></span></td>
                            <?php else: ?>
                                <td><?php echo e($doc -> status); ?></td>
                            <?php endif; ?>
                            <td><?php echo e($doc -> filename); ?></td>
                            <td><?php echo e($doc -> description); ?></td>
                            <td><a href="/purchasing/form-ap/<?php echo e($doc -> id); ?>/download" target="_blank"><span class="icon" data-feather="download"></span></a></td>
                            <?php if(strpos($doc -> status, 'Denied') !== false || strpos($doc -> status, 'Approved') !== false): ?>
                                <td></td>
                            <?php else: ?>
                                <td>
                                    <a href="/purchasing/form-ap/<?php echo e($doc -> id); ?>/approve" class="btn btn-success">Accept</a>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reject-form-<?php echo e($doc -> id); ?>">Reject</button>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    
    <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="modal fade" id="reject-form-<?php echo e($doc -> id); ?>" tabindex="-1" role="dialog" aria-labelledby="reject-formTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="rejectTitle" style="color: white">Reject</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/purchasing/form-ap/<?php echo e($doc -> id); ?>/reject">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body"> 
                        <label for="description">Alasan</label>
                        <textarea class="form-control" name="description" id="description" rows="3" placeholder="Input Alasan Reject Form"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <style>
        .tableFixHead          { overflow: auto; height: 250px; }
        .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }

        .my-custom-scrollbar {
            position: relative;
            height: 700px;
            overflow: auto;
        }
        .table-wrapper-scroll-y {
            display: block;
        }
        th{
            color: white;
        }
        td, th{
            word-wrap: break-word;
            min-width: 160px;
            max-width: 160px;
            text-align: center;
        }
        .icon{
            color: black;
            height: 24px;
            width: 24px
        }
        .alert{
                text-align: center;
            }
    </style>
    <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>

    <?php $__env->stopSection(); ?>
<?php else: ?>
    <?php echo $__env->make('../layouts/notAuthorized', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/purchasing/purchasingFormAP.blade.php ENDPATH**/ ?>