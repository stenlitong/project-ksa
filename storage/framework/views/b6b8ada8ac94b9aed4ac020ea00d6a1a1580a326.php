<nav id="sidebarMenu" class="col-md-2 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link 
                <?php
                    if(strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false){
                        echo('active');
                    }
                ?>" aria-current="page" href="/dashboard">
                    <span data-feather="home"></span>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link 
                <?php
                    if(strpos($_SERVER['REQUEST_URI'], 'report') !== false){
                        echo('active');
                    }
                ?>" href="<?php echo e(Route('purchasing.report')); ?>">
                    <span data-feather="archive"></span>
                    Report PO
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link 
                <?php
                    if(strpos($_SERVER['REQUEST_URI'], 'report-ap') !== false){
                        echo('active');
                    }
                ?>" href="<?php echo e(Route('purchasing.reportAp')); ?>">
                    <span data-feather="bookmark"></span>
                    Report AP
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link 
                <?php
                    if(basename($_SERVER['REQUEST_URI']) == 'supplier'){
                        echo('active');
                    }
                ?>" href="/purchasing/supplier">
                    <span data-feather="user-plus"></span>
                    Add More Contact
                </a>
            </li>
        </ul>
    </div>
</nav><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/purchasing/sidebar.blade.php ENDPATH**/ ?>