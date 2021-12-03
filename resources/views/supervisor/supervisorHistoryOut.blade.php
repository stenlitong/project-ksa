@if(Auth::user()->hasRole('supervisorLogistic') || Auth::user()->hasRole('supervisorLogisticMaster'))
    @extends('../layouts.base')

    @section('title', 'Supervisor Order History')

    @section('container')
        <div class="row">
            @include('supervisor.sidebar')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                    <h1 class="d-flex justify-content-center">Goods Out Report</h1>
                    <br>
                    
                    <div class="d-flex justify-content-start mb-3">
                        <a href="{{ Route('supervisor.historyOut') }}" class="btn btn-outline-success mr-3">Goods Out</a>
                        <a href="{{ Route('supervisor.historyIn') }}" class="btn btn-outline-secondary">Goods In</a>
                        
                        @if(count($orderHeads) > 0)
                            <a href="{{ Route('supervisor.downloadOut') }}" class="btn btn-outline-danger ml-auto mr-3" target="_blank">Export</a>
                        @endif
                    </div>
                    
                    <div class="table-wrapper-scroll-y my-custom-scrollbar tableFixHead" style="overflow-x:auto;">
                        <table class="table table-bordered sortable">
                            <thead class="thead bg-danger">
                            <tr>
                                <th scope="col">Nomor</th>
                                <th scope="col">Tanggal Keluar</th>
                                <th scope="col">Item Barang Keluar</th>
                                <th scope="col">Serial Number</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Satuan</th>
                                <th scope="col">No. Resi</th>
                                <th scope="col">Note</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($orderHeads as $key => $oh)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $oh -> item -> lastGiven }}</td>
                                        <td>{{ $oh -> item -> itemName }}</td>
                                        <td>{{ $oh -> item -> serialNo}}</td>
                                        <td>{{ $oh -> quantity}}</td>
                                        <td>{{ $oh -> item -> unit}}</td>
                                        <td>{{ $oh -> noResi}}</td>
                                        <td>{{ $oh -> descriptions}}</td>
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
                min-width: 160px;
                max-width: 160px;
                text-align: center;
            }
        </style>
        <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
    @endsection
@else
    @include('../layouts/notAuthorized')
@endif