@extends('../layouts.base')

@section('title', 'Logistic Report')

@section('container')
    <div class="row">
        @include('logistic.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                <h1 class="d-flex justify-content-center">Goods In Report</h1>
                <br>
                @if (session('status'))
                    <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                        {{session('status')}}
                    </div>
                @endif
                
                <nav class="navbar navbar-light">
                    <form class="form-inline">
                        <a href="{{ Route('logistic.historyOut') }}" class="btn btn-outline-success mr-3">Goods Out</a>
                        <a href="{{ Route('logistic.historyIn') }}" class="btn btn-outline-secondary">Goods In</a>

                        @if(count($orderHeads) > 0)
                            <a href="" class="btn btn-outline-danger" style="margin-left: 1220px">Export</a>
                        @endif
                    </form>
                </nav>
                
                <div class="table-wrapper-scroll-y my-custom-scrollbar tableFixHead">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                          <tr>
                            <th scope="col">Order ID</th>
                            <th scope="col">Tanggal Masuk</th>
                            <th scope="col">Item Barang Masuk</th>
                            <th scope="col">Serial Number</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Satuan</th>
                            <th scope="col">Nama Supplier</th>
                            <th scope="col">Note</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($orderHeads as $oh)
                                <tr>
                                    <td>{{ $oh -> order_id }}</td>
                                    <td>{{ $oh -> approved_at }}</td>
                                    <td>{{ $oh -> item -> itemName }}</td>
                                    <td>{{ $oh -> serialNo}}</td>
                                    <td>{{ $oh -> quantity}}</td>
                                    <td>{{ $oh -> unit}}</td>
                                    <td>{{ $oh -> supplierName}}</td>
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

        td{
            word-wrap: break-word;
            min-width: 160px;
            max-width: 160px;
        }
    </style>
@endsection