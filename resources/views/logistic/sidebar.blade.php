<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="/dashboard">
                    <span data-feather="home"></span>
                    Dashboard
                </a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link" href="{{ Route("logistic.report") }}">
                    <span data-feather="file"></span>
                    Make Report
                </a>
            </li> --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ Route("logistic.history") }}">
                    <span data-feather="file"></span>
                    View History
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ Route("logistic.stocks") }}">
                    <span data-feather="file"></span>
                    Item Stocks
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ Route("logistic.makeOrder") }}">
                    <span data-feather="file"></span>
                    Make Order
                </a>
            </li>
        </ul>
    </div>
</nav>