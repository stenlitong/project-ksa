<nav id="sidebarMenu" class="col-md-2 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="/dashboard">
                    <span data-feather="home"></span>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="<?php echo e(Route('supervisor.itemStock')); ?>">
                    <span data-feather="database"></span>
                    Item Stock
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="<?php echo e(Route('supervisor.approvalDoPage')); ?>">
                    <span data-feather="shopping-bag"></span>
                    Approval DO
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(Route('supervisor.historyOut')); ?>">
                    <span data-feather="file"></span>
                    Report Goods In/Out
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(Route('supervisor.report')); ?>">
                    <span data-feather="archive"></span>
                    Report PR/PO
                </a>
            </li>
        </ul>
    </div>
</nav><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/supervisor/sidebar.blade.php ENDPATH**/ ?>