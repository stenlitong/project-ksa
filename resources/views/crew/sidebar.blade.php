<nav id="sidebarMenu" class="col-md-3 col-lg-2 col-sm-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link 
                @php
                    if(basename($_SERVER['REQUEST_URI']) == 'dashboard'){
                        echo('active');
                    }
                @endphp" aria-current="page" href="/dashboard">
                    <span data-feather="home"></span>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link 
                @php
                    if(basename($_SERVER['REQUEST_URI']) == 'order'){
                        echo('active');
                    }
                @endphp" href="{{ Route('crew.order') }}">
                    <span data-feather="file"></span>
                    Make Order
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link
                    @php
                        if(basename($_SERVER['REQUEST_URI']) == 'create-task'){
                        echo('active');
                    }
                    @endphp
                "   
                href="{{ Route('crew.createTask') }}">
                    <span data-feather="plus-square"></span>
                    Create Task
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link
                @php
                    if(basename($_SERVER['REQUEST_URI']) == 'detail'){
                    echo('active');
                }
                @endphp
                " href="{{ Route('crew.taskDetail') }}">
                    <span data-feather="clock"></span>
                    Ongoing Task
                </a>
            </li>
        </ul>
    </div>
</nav>