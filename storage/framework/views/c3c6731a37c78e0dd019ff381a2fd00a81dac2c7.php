<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link 
                <?php
                    if(basename($_SERVER['REQUEST_URI']) == 'dashboard'){
                        echo('active');
                    }
                ?>" href="/dashboard">
                    <span data-feather="user-plus"></span>
                    Add More Contact
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link 
                <?php
                    if(basename($_SERVER['REQUEST_URI']) == 'form-ap'){
                        echo('active');
                    }
                ?>" aria-current="page" href="<?php echo e(Route('adminPurchasing.formApPage')); ?>">
                    <span data-feather="file-plus"></span>
                    Form AP
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link 
                <?php
                    if(basename($_SERVER['REQUEST_URI']) == 'report-ap'){
                        echo('active');
                    }
                ?>" href="<?php echo e(Route('adminPurchasing.reportAp')); ?>">
                    <span data-feather="bookmark"></span>
                    Report AP
                </a>
            </li>
        </ul>
    </div>
</nav><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/adminPurchasing/sidebar.blade.php ENDPATH**/ ?>