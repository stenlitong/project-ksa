@if(Auth::user()->hasRole('supervisor') || Auth::user()->hasRole('supervisorMaster'))
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

                    <div class="table-wrapper-scroll-y my-custom-scrollbar tableFixHead">
                        <table class="table table-bordered sortable">
                            <thead class="thead bg-danger">
                            <tr>
                                <th scope="col" style="width: 100px">Nomor</th>
                                <th scope="col">Item Barang</th>
                                <th scope="col">Dari Cabang</th>
                                <th scope="col">Cabang Tujuan</th>
                                <th scope="col">Qty</th>
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
                                        <td>{{ $o -> item_requested -> itemName }}</td>
                                        <td>{{ $o -> fromCabang}}</td>
                                        <td>{{ $o -> toCabang}}</td>
                                        <td>{{ $o -> quantity}} {{ $o -> item_requested -> unit}}</td>
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
                                                <div class="d-flex justify-content-between">
                                                    <a href="/supervisor/approval-do/{{ $o -> id }}/forward" class="btn btn-success">Approve</a>
                                                    <a href="/supervisor/approval-do/{{ $o -> id }}/deny" class="btn btn-danger">Reject</a>
                                                </div>
                                            </td>
                                        {{-- scenario #2 : If the order is already approved by their respective branches, then the destination branches also need to approve --}}
                                        @elseif($o -> toCabang == Auth::user()->cabang and strpos($o -> status, 'Waiting Approval By Supervisor Cabang '. Auth::user()->cabang) !== false)
                                            <td>
                                                <div class="d-flex justify-content-around">
                                                    <a href="/supervisor/approval-do/{{ $o -> id }}/approve" class="btn btn-success">Approve</a>
                                                    <a href="/supervisor/approval-do/{{ $o -> id }}/reject" class="btn btn-danger">Reject</a>
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
                min-width: 100px;
                max-width: 100px;
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
        </style>
        <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
    @endsection
@else
    @include('../layouts/notAuthorized')
@endif