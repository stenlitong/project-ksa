<nav id="sidebarMenu" class="col-md-2 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link 
                <?php
                    if(basename($_SERVER['REQUEST_URI']) == 'dashboard'){
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
                    if(basename($_SERVER['REQUEST_URI']) == 'form-ap'){
                        echo('active');
                    }
                ?>" href="<?php echo e(Route('purchasing.form-ap')); ?>">
                    <span data-feather="file"></span>
                    Checklist AP
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link 
                <?php
                    if(basename($_SERVER['REQUEST_URI']) == 'report'){
                        echo('active');
                    }
                ?>" href="<?php echo e(Route('purchasing.report')); ?>">
                    <span data-feather="archive"></span>
                    Report PR/PO
                </a>
            </li>
        </ul>
    </div>
</nav><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/purchasing/sidebar.blade.php ENDPATH**/ ?>