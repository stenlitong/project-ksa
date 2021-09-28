@extends('../layouts.base')

@section('title', 'Logistic Dashboard')

@section('container')
<div class="row">
    @include('logistic.sidebar')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        
        @include('../layouts/time')

        @if(Auth::user()->hasRole('logistic'))
            <h2 class="mt-3 mb-2" style="text-align: center">Order List Cabang {{ Auth::user()->cabang }}</h2>
        @else
            <h2 class="mt-3 mb-2" style="text-align: center">Order List</h2>
        @endif
        <div class="d-flex justify-content-end">
            {{ $orderHeads->links() }}
        </div>

        <br>

        @if(session('error'))
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                {{ session('error') }}
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                {{ session('status') }}
            </div>
        @endif

        @error('reason')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Alasan Wajib Diisi
        </div>
        @enderror

        {{-- <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search by status.."> --}}
        <div class="row">
            <div class="col-md-6">
                <form action="">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search by Order ID or Status..." name="search" id="search">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <table class="table" id="myTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Cabang</th>
                    <th scope="col">Status</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Detail/Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderHeads as $oh)
                <tr>
                    <th>{{ $oh -> order_id}}</th>
                    <td>{{ $oh -> cabang}}</th>
                    @if(strpos($oh -> status, 'Rejected') !== false)
                        <td style="color: red">{{ $oh -> status}}</td>
                    @elseif(strpos($oh -> status, 'Completed') !== false)
                        <td style="color: green">{{ $oh -> status}}</td>
                    @elseif(strpos($oh -> status, 'On Delivery') !== false)
                        <td style="color: blue">{{ $oh -> status}}</td>
                    @else
                        <td>{{ $oh -> status}}</td>
                    @endif

                    <td style="word-wrap: break-word;min-width: 160px;max-width: 160px;">{{ $oh -> reason}}</td>
                    
                    {{-- Button to trigger the modal detail --}}
                    <td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#detail-{{ $oh -> id }}">
                        Detail
                    </button></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Modal detail --}}
        @foreach($orderHeads as $o)
                <div class="modal fade" id="detail-{{ $o->id }}" tabindex="-1" role="dialog" aria-labelledby="detailTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="detailTitle">Order ID # {{ $o->order_id }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Item Barang</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Terakhir Diberikan</th>
                                            <th scope="col">Umur Barang</th>
                                            <th scope="col">Department</th>
                                            <th scope="col">Stok Barang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orderDetails as $od)
                                            @if($od -> orders_id == $o -> order_id)
                                                <tr>
                                                    <td>{{ $od -> item -> itemName }}</td>
                                                    <td>{{ $od -> quantity }} {{ $od -> unit }}</td>
                                                    <td>{{ $o ->  approved_at}}</td>
                                                    <td>{{ $od -> item -> itemAge }}</td>
                                                    <td>{{ $od -> department }}</td>
                                                    @if(preg_replace('/[a-zA-z ]/', '', $od -> quantity) > $od -> item -> itemStock)
                                                        <td style="color: red">{{ $od -> item -> itemStock}} {{ $od -> item -> unit }} (Stok Tidak Mencukupi)</td>
                                                    @else
                                                        <td style="color: green">{{ $od -> item -> itemStock}} {{ $od -> item -> unit }}</td>
                                                    @endif
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> 
                            <div class="modal-footer">
                                {{-- Check if the order is rejected, then do not show the approve & reject button --}}
                                @if(strpos($o -> status, 'In Progress') !== false)
                                    {{-- Button to trigger modal 2 --}}
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reject-order-{{ $o -> id }}">Reject</button>
                                    <a href="/logistic/order/{{ $o->id }}/approve" class="btn btn-primary">Approve</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        @endforeach

        {{-- Modal 2 --}}
        @foreach($orderHeads as $oh)
            <div class="modal fade" id="reject-order-{{ $oh -> id }}" tabindex="-1" role="dialog" aria-labelledby="reject-orderTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectTitle">Reject Order {{ $oh -> order_id }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="/logistic/order/{{ $oh->id }}/reject">
                        @csrf
                        <div class="modal-body"> 
                            <label for="reason">Alasan</label>
                            <textarea class="form-control" name="reason" id="reason" rows="3" placeholder="Input Alasan Reject Order"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        @endforeach
        
    </main>
</div>



@endsection