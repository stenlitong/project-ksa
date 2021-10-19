<?php if(Auth::user()->hasRole('crew')): ?>
    

    <?php $__env->startSection('title', 'Crew Dashboard'); ?>

    <?php $__env->startSection('container'); ?>
    <div class="row">
        <?php echo $__env->make('crew.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <?php echo $__env->make('../layouts/time', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <h2 class="mt-3 mb-3" style="text-align: center">Order List</h2>
            <div class="d-flex justify-content-end">
                <?php echo e($orderHeads->links()); ?>

            </div>

            <?php if(session('status')): ?>
                <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-end mb-3">
                <a href="<?php echo e(Route('crew.completed-order')); ?>" class="btn btn-success mr-3">Completed (<?php echo e($completed); ?>)</a>
                <a href="<?php echo e(Route('crew.in-progress-order')); ?>" class="btn btn-danger mr-3">In Progress (<?php echo e($in_progress); ?>)</a>
            </div>

            <table class="table">
                <thead class="thead-dark">
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
                        <th><?php echo e($o -> order_id); ?></th>
                        <?php if(strpos($o -> status, 'Rejected') !== false): ?>
                            <td style="color: red"><?php echo e($o -> status); ?></td>
                        <?php elseif(strpos($o -> status, 'Completed') !== false): ?>
                            <td style="color: green"><?php echo e($o -> status); ?></td>
                        <?php elseif($o -> status == 'On Delivery' || $o -> status == 'Items Ready'): ?>
                            <td style="color: blue"><?php echo e($o -> status); ?></td>
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
                                <button type="button" style="margin-left: 40%" class="btn btn-info" data-toggle="modal" id="detail" data-target="#editItem-<?php echo e($o -> id); ?>">
                                    Detail
                                </button>
                                <a href="/crew/order/<?php echo e($o->id); ?>/accept" class="btn btn-primary ml-3">Accept</a>
                            </td>
                        <?php else: ?>
                        <td>
                            <button type="button" style="margin-left: 40%" class="btn btn-info" data-toggle="modal" id="detail" data-target="#editItem-<?php echo e($o -> id); ?>">
                                Detail
                            </button>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>

            </table>
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
                                    <thead>
                                        <tr>
                                            <th scope="col">Item Barang</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Satuan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $od): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($od -> orders_id == $o -> order_id): ?>
                                                <tr>
                                                    <td><?php echo e($od -> item -> itemName); ?></td>
                                                    <td><?php echo e($od -> quantity); ?></td>
                                                    <td><?php echo e($od -> item ->unit); ?></td>
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
        td, th{
            word-wrap: break-word;
            min-width: 200px;
            max-width: 200px;
            text-align: center;
        }
        .alert{
                text-align: center;
            }
    </style>

    <?php $__env->stopSection(); ?>
<?php else: ?>
    <?php echo $__env->make('../layouts/notAuthorized', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/crew/crewDashboard.blade.php ENDPATH**/ ?>