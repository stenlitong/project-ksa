<?php if(Auth::user()->hasRole('logistic')): ?>
    

    <?php $__env->startSection('title', 'Logistic Approve Order'); ?>

    <?php $__env->startSection('container'); ?>
    <div class="row">
        <?php echo $__env->make('logistic.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

            <h2 class="mt-3 mb-2" style="text-align: center">Order # <?php echo e($orderHeads -> order_id); ?></h2>

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
                                <label for="description">Deskripsi (optional)</label>
                                <textarea class="form-control" name="description" id="description" rows="3"
                                    placeholder="Input Keterangan"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" style="margin-left: 45%">Submit</button>
                        </form>
                    </div>
                    <div class="col mt-3">
                        <table class="table" id="myTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Item Barang</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $od): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($od -> item -> itemName); ?></td>
                                    <td><?php echo e($od -> quantity); ?> <?php echo e($od -> unit); ?></td>
                                    <td><?php echo e($od -> department); ?></td>
                                    <?php if(preg_replace('/[a-zA-z ]/', '', $od -> quantity) > $od -> item -> itemStock): ?>
                                        <td style="color: red; font-weight: bold"><?php echo e($od -> item -> itemStock); ?> <?php echo e($od -> item -> unit); ?> (Stok Tidak Mencukupi)</td>
                                    <?php else: ?>
                                        <td style="color: green; font-weight: bold"><?php echo e($od -> item -> itemStock); ?> <?php echo e($od -> item -> unit); ?></td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
    </div>
    <?php $__env->stopSection(); ?>
<?php else: ?>
    <?php echo $__env->make('../layouts/notAuthorized', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('../layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/logistic/logisticApprovedOrder.blade.php ENDPATH**/ ?>