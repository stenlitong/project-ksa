<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="/dashboard">
                    <span data-feather="home"></span>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ Route("logistic.stocks") }}">
                    <span data-feather="layers"></span>
                    Item Stocks
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ Route("logistic.makeOrder") }}">
                    <span data-feather="plus-circle"></span>
                    Make Order
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ Route("logistic.historyOut") }}">
                    <span data-feather="file"></span>
                    Report Goods In/Out
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ Route("logistic.report") }}">
                    <span data-feather="archive"></span>
                    Report PR/PO
                </a>
            </li>
        </ul>
    </div>
</nav>