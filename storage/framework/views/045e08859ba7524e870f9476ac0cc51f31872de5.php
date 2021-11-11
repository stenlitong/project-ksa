<?php if(Auth::user()->hasRole('crew')): ?>
    

    <?php $__env->startSection('title', 'Crew Dashboard'); ?>

    <?php $__env->startSection('container'); ?>
    <div class="row">
        <?php echo $__env->make('crew.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <?php echo $__env->make('../layouts/time', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>            

            <h2 class="mt-3 mb-3" style="text-align: center"><strong>Order List</strong></h2>

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

            <div class="d-flex">
                <div class="p-2 mr-auto">
                    <h5>Cabang: <?php echo e(Auth::user()->cabang); ?></h5>
                    <form action="<?php echo e(Route('crew.changeBranch')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="d-flex">
                            <select class="form-select mr-3" aria-label="Default select example" name="cabang" id="cabang">
                                <option value="Jakarta" id="Jakarta" 
                                    <?php if(Auth::user()->cabang == 'Jakarta') {
                                        echo('selected');
                                    } 
                                    ?>
                                >Jakarta</option>
                                <option value="Banjarmasin" id="Banjarmasin"
                                    <?php if(Auth::user()->cabang == 'Banjarmasin') {
                                        echo('selected');
                                    } 
                                    ?>
                                >Banjarmasin</option>
                                <option value="Samarinda" id="Samarinda"
                                    <?php if(Auth::user()->cabang == 'Samarinda') {
                                        echo('selected');
                                    } 
                                    ?>
                                >Samarinda</option>
                                <option value="Bunati" id="Bunati"
                                    <?php if(Auth::user()->cabang == 'Bunati') {
                                        echo('selected');
                                    } 
                                    ?>
                                >Bunati</option>
                                <option value="Babelan" id="Babelan"
                                    <?php if(Auth::user()->cabang == 'Babelan') {
                                        echo('selected');
                                    } 
                                    ?>
                                >Babelan</option>
                                <option value="Berau" id="Berau"
                                    <?php if(Auth::user()->cabang == 'Berau') {
                                        echo('selected');
                                    } 
                                    ?>
                                >Berau</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

                <div class="p-2 mt-auto">
                    <a href="<?php echo e(Route('crew.completed-order')); ?>" class="btn btn-success mr-3">Completed (<?php echo e($completed); ?>)</a>
                    <a href="<?php echo e(Route('crew.in-progress-order')); ?>" class="btn btn-danger mr-3">In Progress (<?php echo e($in_progress); ?>)</a>
                </div>

                <div class="p-2 mt-auto">
                    <?php echo e($orderHeads->links()); ?>

                </div>
            </div>

            <div id="content" style="overflow-x:auto;">
                <table class="table">
                    <thead class="thead bg-danger">
                        <tr>
                            <th scope="col">Order ID</th>
                            <th scope="col">Status</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col" class="text-center">Action/Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $orderHeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($o -> order_id); ?></strong></td>
                            <?php if(strpos($o -> status, 'Rejected') !== false): ?>
                                <td style="color: red; font-weight: bold"><?php echo e($o -> status); ?></td>
                            <?php elseif(strpos($o -> status, 'Completed') !== false): ?>
                                <td style="color: green; font-weight: bold"><?php echo e($o -> status); ?></td>
                            <?php elseif($o -> status == 'On Delivery' || $o -> status == 'Items Ready'): ?>
                                <td style="color: blue; font-weight: bold"><?php echo e($o -> status); ?></td>
                            <?php else: ?>
                                <td><?php echo e($o -> status); ?></td>
                            <?php endif; ?>
                            
                            <?php if(strpos($o -> status, 'Rejected') !== false): ?>
                                <td style="word-wrap: break-word;min-width: 250px;max-width: 250px;"><?php echo e($o -> reason); ?></td>
                            <?php else: ?>
                                <td style="word-wrap: break-word;min-width: 250px;max-width: 250px;"><?php echo e($o -> descriptions); ?></td>
                            <?php endif; ?>

                            <?php if($o -> status == 'On Delivery' || $o -> status == 'Items Ready'): ?>
                                <td >
                                    <button type="button" class="btn btn-info" data-toggle="modal" id="detail" data-target="#editItem-<?php echo e($o -> id); ?>">
                                        Detail
                                    </button>
                                    <a href="/crew/order/<?php echo e($o->id); ?>/accept" class="btn btn-primary ml-3">Accept</a>
                                </td>
                            <?php else: ?>
                            <td>
                                <button type="button" class="btn btn-info" data-toggle="modal" id="detail" data-target="#editItem-<?php echo e($o -> id); ?>">
                                    Detail
                                </button>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>

                </table>
            </div>
            
        </main>
        
        <?php $__currentLoopData = $orderHeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="modal fade" id="editItem-<?php echo e($o->id); ?>" tabindex="-1" role="dialog" aria-labelledby="editItemTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <div class="d-flex-column">
                                    <h5 class="modal-title" id="detailTitle" style="color: white"><strong>Nama Kapal</strong></h5>
                                    <h5 class="modal-title" id="detailTitle" style="color: white"><?php echo e($o->boatName); ?></h5>
                                </div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Item Barang</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Department</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $od): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($od -> orders_id == $o -> id): ?>
                                                <tr>
                                                    <td><?php echo e($od -> item -> itemName); ?></td>
                                                    <td><?php echo e($od -> quantity); ?> <?php echo e($od -> item -> unit); ?></td>
                                                    <td><?php echo e($od -> department); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <style>
        th{
            color: white;
        }
        td, th{
            word-wrap: break-word;
            min-width: 200px;
            max-width: 200px;
            text-align: center;
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
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/crew/crewDashboard.blade.php ENDPATH**/ ?>