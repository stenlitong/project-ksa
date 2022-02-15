<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item ">
                <a class="nav-link 
                @php
                    if(basename($_SERVER['REQUEST_URI']) == 'dashboard'){
                        echo('active');
                    }
                @endphp" aria-current="page" href="/dashboard">
                    <span data-feather="file-plus"></span>
                    Form AP
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link 
                @php
                    if(basename($_SERVER['REQUEST_URI']) == 'report-ap'){
                        echo('active');
                    }
                @endphp" href="{{ Route('adminPurchasing.reportAp') }}">
                    <span data-feather="bookmark"></span>
                    Report AP
                </a>
            </li>
        </ul>
    </div>
</nav>