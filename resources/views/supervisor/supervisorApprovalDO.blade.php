@if(Auth::user()->hasRole('supervisorLogistic') || Auth::user()->hasRole('supervisorLogisticMaster'))
    @extends('../layouts.base')

    @section('title', 'Supervisor Approval DO')

    @section('container')
        <div class="row">
            @include('supervisor.sidebar')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                    <h1 class="d-flex justify-content-center mb-3">Approval DO Site</h1>
                    <br>
                    
                    @if(session('status'))
                        <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                            {{ session('status') }}
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
                                    <th scope="col">Dari Cabang</th>
                                    <th scope="col">Cabang Tujuan</th>
                                    <th scope="col">Request Qty</th>
                                    <th scope="col">Stok Cabang Tujuan</th>
                                    <th scope="col">Nama Requester</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                    <th scope="col">Approval</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($ongoingOrders as $key => $o)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td><strong>{{ $o -> item_requested -> itemName }}</strong></td>
                                            <td>{{ $o -> fromCabang}}</td>
                                            <td>{{ $o -> toCabang}}</td>
                                            <td><strong>{{ $o -> quantity}} {{ $o -> item_requested -> unit}}</strong></td>
                                            @if($o -> quantity > $o -> item_requested_from -> itemStock )
                                                <td><span style="color: red; font-weight: bold">{{ $o -> item_requested_from -> itemStock}} {{ $o -> item_requested -> unit}}</span></td>
                                            @else
                                                <td><span style="color: green; font-weight: bold">{{ $o -> item_requested_from -> itemStock}} {{ $o -> item_requested -> unit}}</span></td>
                                            @endif
                                            <td>{{ $o -> user -> name }}</td>
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
                                                <a href="/supervisor/approval-do/{{ $o -> id }}/download"><span data-feather="download" class="icon"></span></a>
                                            </td>
                                            {{-- scenario #1 : If the order needs to be approved by the requested branches --}}
                                            @if($o -> fromCabang == Auth::user()->cabang and strpos($o -> status, 'In Progress By Supervisor Cabang ' . Auth::user()->cabang) !== false)
                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <a href="/supervisor/approval-do/{{ $o -> id }}/deny" class="btn btn-danger btn-sm">Reject</a>
                                                        <a href="/supervisor/approval-do/{{ $o -> id }}/forward" class="btn btn-success btn-sm">Approve</a>
                                                    </div>
                                                </td>
                                            {{-- scenario #2 : If the order is already approved by their respective branches, then the destination branches also need to approve --}}
                                            @elseif($o -> toCabang == Auth::user()->cabang and strpos($o -> status, 'Waiting Approval By Supervisor Cabang '. Auth::user()->cabang) !== false)
                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <a href="/supervisor/approval-do/{{ $o -> id }}/reject" class="btn btn-danger btn-sm">Reject</a>
                                                        <a href="/supervisor/approval-do/{{ $o -> id }}/approve" class="btn btn-success btn-sm">Approve</a>
                                                    </div>
                                                </td>
                                            @else
                                                <td></td>
                                            @endif
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
                min-width: 80px;
                max-width: 80px;
                text-align: center;
            }
            .icon{
                margin-bottom: -10px;
                color: black;
                height: 34px;
                width: 34px;
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

            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 3000);
        </script>
        
        <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
    @endsection
@else
    @include('../layouts/notAuthorized')
@endif