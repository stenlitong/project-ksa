<?php if(Auth::user()->hasRole('adminLogistic')): ?>
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="/dashboard">
                        <span data-feather="layers"></span>
                        Item Stocks
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(Route('adminLogistic.preMakeOrderPage')); ?>">
                        <span data-feather="plus-circle"></span>
                        Make Order
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(Route('adminLogistic.historyOut')); ?>">
                        <span data-feather="file"></span>
                        Report Goods In/Out
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">
                        <span data-feather="archive"></span>
                        Report PR/PO
                    </a>
                </li>
            </ul>
        </div>
    </nav>
<?php endif; ?>
<?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/adminLogistic/sidebar.blade.php ENDPATH**/ ?>