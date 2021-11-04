<nav id="sidebarMenu" class="col-md-3 col-lg-2 col-sm-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link 
                <?php
                    if(basename($_SERVER['PHP_SELF']) == 'dashboard'){
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
                    if(basename($_SERVER['PHP_SELF']) == 'order'){
                        echo('active');
                    }
                ?>" href="<?php echo e(Route("crew.order")); ?>">
                    <span data-feather="file"></span>
                    Make Order
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/dashboard">
                    <span data-feather="file"></span>
                    My Task
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/dashboard">
                    <span data-feather="file"></span>
                    Ongoing Task
                </a>
            </li>
        </ul>
    </div>
</nav><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/crew/sidebar.blade.php ENDPATH**/ ?>