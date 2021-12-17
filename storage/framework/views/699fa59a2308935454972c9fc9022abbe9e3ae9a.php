<?php if(Auth::user()->hasRole('supervisorLogistic') || Auth::user()->hasRole('supervisorLogisticMaster')): ?>
    

    <?php $__env->startSection('title', 'Supervisor Dashboard'); ?>

    <?php $__env->startSection('container'); ?>
        <div class="row">
        <?php echo $__env->make('supervisor.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="padding-bottom: 200px">
            <?php echo $__env->make('../layouts/time', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <h2 class="mt-3 mb-2" style="text-align: center">Order List Cabang <?php echo e(Auth::user()->cabang); ?></h2>

            <div class="d-flex justify-content-end mr-3">
                <?php echo e($orderHeads->links()); ?>

            </div>

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

            <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Alasan Wajib Diisi
            </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <div class="d-flex mb-3">
                <form class="mr-auto w-50" action="">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by Order ID or Status..." name="search" id="search">
                        <button class="btn btn-primary" type="submit" value="<?php echo e(request('search')); ?>">Search</button>
                    </div>
                </form>
                <div>
                    <a href="<?php echo e(Route('supervisor.completed-order')); ?>" class="btn btn-success mr-3">Completed (<?php echo e($completed); ?>)</a>
                    <a href="<?php echo e(Route('supervisor.in-progress-order')); ?>" class="btn btn-danger mr-3">In Progress (<?php echo e($in_progress); ?>)</a>
                </div>
            </div>

            <div id="content" style="overflow-x:auto;">
                <table class="table" id="myTable">
                    <thead class="thead bg-danger">
                        <tr>
                            <th scope="col">Order ID</th>
                            <th scope="col">Status</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $orderHeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($oh -> order_id); ?></strong></td>
                            <?php if(strpos($oh -> status, 'Rejected') !== false): ?>
                                <td><span style="color: red; font-weight: bold"><?php echo e($oh -> status); ?></span></td>
                            <?php elseif(strpos($oh -> status, 'Completed') !== false): ?>
                                <td><span style="color: green; font-weight: bold"><?php echo e($oh -> status); ?></span></td>
                            <?php elseif(strpos($oh -> status, 'Delivered') !== false): ?>
                                <td><span style="color: blue; font-weight: bold"><?php echo e($oh -> status); ?></span></td>
                            <?php else: ?>
                                <td><?php echo e($oh -> status); ?></td>
                            <?php endif; ?>

                            <?php if(strpos($oh -> status, 'Rejected') !== false): ?>
                                <td><?php echo e($oh -> reason); ?></td>
                            <?php else: ?>
                                <td><?php echo e($oh -> descriptions); ?></td>
                            <?php endif; ?>

                            <td>
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#detail-<?php echo e($oh -> id); ?>">Detail</button>
                                
                                <?php if(strpos($oh -> status, 'Delivered') !== false || strpos($oh -> status, 'Order Completed') !== false): ?>
                                    <a href="/supervisor/<?php echo e($oh -> id); ?>/download-pr" class="btn btn-warning" target="_blank">Download PR</a>
                                <?php endif; ?>
                            </td>

                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

            </div>

            
            <?php $__currentLoopData = $orderHeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="modal fade" id="detail-<?php echo e($o->id); ?>" tabindex="-1" role="dialog" aria-labelledby="detailTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex-column">
                                        <h5 class="modal-title" id="detailTitle" style="color: white"><strong>Order ID</strong></h5>
                                        <h5 class="modal-title" id="detailTitle" style="color: white"><?php echo e($o->order_id); ?></h5>
                                    </div>
                                    <div class="d-flex-column ml-5">
                                        <h5 class="modal-title" id="detailTitle" style="color: white"><strong>Nama Kapal</strong></h5>
                                        <h5 class="modal-title" id="detailTitle" style="color: white"><?php echo e($o->boatName); ?></h5>
                                    </div>
                                </div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex justify-content-around">
                                    <h5>Nomor PR : <?php echo e($o -> noPr); ?></h5>
                                    <h5>Tipe Order : <?php echo e($o -> orderType); ?></h5>
                                </div>
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Item Barang</th>
                                            <?php if(strpos($o -> order_id, 'ROID') !== false): ?>
                                                <th scope="col">Accepted Quantity</th>
                                            <?php else: ?>
                                                <th scope="col">Quantity</th>
                                            <?php endif; ?>
                                            
                                            <th scope="col">Department</th>
                                            <th scope="col">Terakhir Diberikan</th>
                                            <?php if(strpos($o -> status, 'Order In Progress By Supervisor') !== false): ?>
                                                <th scope="col">Stok Barang</th>
                                            <?php endif; ?>
                                            <th scope="col">Note</th>
                                            <th scope="col">Status Barang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $od): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($od -> orders_id == $o -> id): ?>
                                                <tr>
                                                    <td><strong><?php echo e($od -> item -> itemName); ?></strong></td>
                                                    <td>
                                                        <strong><?php echo e($od -> acceptedQuantity); ?> <?php echo e($od -> item -> unit); ?></strong>
                                                    </td>
                                                    
                                                    <td><?php echo e($od -> department); ?></td>
                                                    <td><?php echo e($od -> item -> lastGiven); ?></td>
                                                    <?php if(strpos($o -> status, 'Order In Progress By Supervisor') !== false): ?>
                                                        <td><strong><?php echo e($od -> item -> itemStock); ?> <?php echo e($od -> item -> unit); ?></strong></td>
                                                    <?php endif; ?>
                                                    <td><?php echo e($od -> note); ?></td>
                                                    <td>
                                                        <?php if($od -> orderItemState == 'Accepted'): ?>
                                                            <span style="color: green; font-weight: bold;"><?php echo e($od -> orderItemState); ?></span>
                                                        <?php else: ?>
                                                            <span style="color: red; font-weight: bold;"><?php echo e($od -> orderItemState); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div> 
                            <div class="modal-footer">
                                
                                <?php if(strpos($o -> status, 'In Progress By Supervisor') !== false): ?>

                                    
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reject-order-<?php echo e($o -> id); ?>">Reject</button>

                                    <a href="/supervisor/<?php echo e($o -> id); ?>/approve-order" class="btn btn-primary">Approve</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="reject-order-<?php echo e($o -> id); ?>" tabindex="-1" role="dialog" aria-labelledby="reject-orderTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title" style="color: white" id="rejectTitle">Reject Order <?php echo e($o -> order_id); ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="/supervisor/<?php echo e($o -> id); ?>/reject-order">
                            <?php echo method_field('put'); ?>
                            <?php echo csrf_field(); ?>
                            <div class="modal-body"> 
                                <label for="reason">Alasan</label>
                                <textarea class="form-control" name="reason" id="reason" rows="3" placeholder="Input Alasan Reject Order"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Submit</button>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </main>
        </div>

        <style>
            th{
                color: white;
            }
            td, th{
                word-wrap: break-word;
                min-width: 150px;
                max-width: 150px;
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
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/supervisor/supervisorDashboard.blade.php ENDPATH**/ ?>