@if(Auth::user()->hasRole('logistic'))
    @extends('../layouts.base')

    @section('title', 'Logistic Request DO')

    @section('container')
        <div class="row">
            @include('logistic.sidebar')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3 wrapper">
                    <h1 class="d-flex justify-content-center mb-3">My Request DO</h1>
                    <br>
                    
                    @if(session('success'))
                        <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div id="content">
                        <div class="table-wrapper-scroll-y my-custom-scrollbar tableFixHead">
                            <table class="table table-bordered sortable">
                                <thead class="thead bg-danger">
                                <tr>
                                    <th scope="col" style="width: 100px">Nomor</th>
                                    <th scope="col">Item Barang</th>
                                    <th scope="col">Cabang Tujuan</th>
                                    <th scope="col">Request Qty</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($ongoingOrders as $key => $o)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td><strong>{{ $o -> item_requested -> itemName }}</strong></td>
                                            <td>{{ $o -> toCabang}}</td>
                                            <td><strong>{{ $o -> quantity}} {{ $o -> item_requested -> unit}}</strong></td>
                                            @if(strpos($o -> status, 'Rejected') !== false)
                                                <td><strong style="color: red">{{ $o -> status }}</strong></td>
                                            @elseif(strpos($o -> status, 'On Delivery') !== false)
                                                <td><strong style="color: blue">{{ $o -> status }}</strong></td>
                                            @elseif(strpos($o -> status, 'Accepted') !== false)
                                                <td><strong style="color: green">{{ $o -> status }}</strong></td>
                                            @else
                                                <td>{{ $o -> status }}</td>
                                            @endif
                                            <td>
                                                <a href="/logistic/request-do/{{ $o -> id }}/download" target="_blank"><span data-feather="download" class="icon mr-2"></span></a>
                                                @if(strpos($o -> status, 'On Delivery') !== false)
                                                    <a href="/logistic/request-do/{{ $o -> id }}/accept-do" class="btn btn-info">Accept Delivery</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </main>
        </div>

        <style>
            body{
                background-image: url('/images/logistic-background.png');
                background-repeat: no-repeat;
                background-size: cover;
            }
            .wrapper{
                padding: 10px;
                border-radius: 10px;
                background-color: antiquewhite;
                /* height: 800px; */
                /* height: 100%; */
            }
            .tableFixHead          { overflow: auto; height: 250px; }
            .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }

            .my-custom-scrollbar {
                position: relative;
                height: 700px;
                overflow: auto;
            }
            .table-wrapper-scroll-y {
                display: block;
            }
            th{
                color: white;
            }
            td, th{
                word-wrap: break-word;
                min-width: 120px;
                max-width: 120px;
                text-align: center;
            }
            .icon{
                margin-bottom: -10px;
                color: black;
                height: 30px;
                width: 30px;
            }
            .alert{
                text-align: center;
            }
            .modal-backdrop {
                height: 100%;
                width: 100%;
            }
        </style>

        <script type="text/javascript">
            function refreshDiv(){
                $('#content').load(location.href + ' #content')
            }
            setInterval(refreshDiv, 60000);
        </script>

        <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
    @endsection
@else
    @include('../layouts/notAuthorized')
@endif