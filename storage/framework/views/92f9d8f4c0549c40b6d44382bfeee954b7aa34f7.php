<?php if(Auth::user()->hasRole('adminPurchasing')): ?>

    

    <?php $__env->startSection('title', 'Admin Purchasing Dashboard'); ?>

    <?php $__env->startSection('container'); ?>
        <div class="row">
            <?php echo $__env->make('adminPurchasing.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?php echo $__env->make('../layouts/time', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="row">
                    <div class="col mt-3">
                        <div class="jumbotron bg-light jumbotron-fluid" style="border-radius: 25px;">
                            <div class="container">
                                <form method="POST" action="/admin-purchasing/form-ap/upload" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <h1 class="mb-3" style="text-align: center">Please upload your form LIST AP</h1>

                                    <?php if(session('status')): ?>
                                        <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                                            <?php echo e(session('status')); ?>

                                        </div>
                                    <?php endif; ?>

                                    <?php $__errorArgs = ['filename'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                                        Input File Invalid
                                    </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                    <img class="w-25 h-25" style="margin-left: 37%;" data-feather="upload">

                                    <div class="custom-file mt-3 w-50 bg-white center">
                                        <input type="file" name="filename" class="form-control" data-browse-on-zone-click="true">
                                    </div>

                                    <p class="mt-3" style="text-align: center">Format: zip/pdf (Max. 5MB)</p>
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col mt-3" id="content">
                        <div class="table-wrapper-scroll-y my-custom-scrollbar tableFixHead" style="overflow-x:auto;">
                            <table class="table sortable">
                                <thead class="thead bg-secondary">
                                    <tr>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Nama File</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($doc -> submissionTime); ?></td>
                                        <td><?php echo e($doc -> filename); ?></td>
                                        <?php if(strpos($doc -> status, 'Denied') !== false): ?>
                                            <td><span style="color: red;font-weight: bold;"><?php echo e($doc -> status); ?></span></td>
                                        <?php elseif(strpos($doc -> status, 'Approved') !== false): ?>
                                            <td><span style="color: green;font-weight: bold;"><?php echo e($doc -> status); ?></span></td>
                                        <?php else: ?>
                                            <td><?php echo e($doc -> status); ?></td>
                                        <?php endif; ?>
                                        <td><?php echo e($doc -> description); ?></td>
                                        <td>
                                            <a href="/admin-purchasing/form-ap/<?php echo e($doc -> id); ?>/download" target="_blank"><span class="icon" data-feather="download"></span></a>
                                        </td>
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
            th{
                color: white;
            }
            th, td{
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
            .center{
                margin-left: 25%;
                width: 50%;
            }
            .alert{
                text-align: center;
            }
        </style>
        <script>
            function refreshDiv(){
                $('#content').load(location.href + ' #content')
            }
            setInterval(refreshDiv, 60000);

            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 3000);
        </script>
        <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
    <?php $__env->stopSection(); ?>

<?php else: ?>
    <?php echo $__env->make('../layouts/notAuthorized', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/adminPurchasing/adminPurchasingFormAp.blade.php ENDPATH**/ ?>