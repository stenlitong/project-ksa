@if(Auth::user()->hasRole('supervisor'))
    @extends('../layouts.base')

    @section('title', 'Supervisor Dashboard')

    @section('container')
        <div class="row">
        @include('supervisor.sidebar')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @include('../layouts/time')
            
            <h2 class="mt-3 mb-2" style="text-align: center">Order List Cabang {{ Auth::user()->cabang }}</h2>

            <div class="d-flex justify-content-end">
                {{ $orderHeads->links() }}
            </div>

            <br>

            @if(session('status'))
                <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                    {{ session('status') }}
                </div>
            @endif

            @error('descriptions')
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Alasan Wajib Diisi
            </div>
            @enderror

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
                        <th scope="col">Status</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderHeads as $oh)
                    <tr>
                        <th>{{ $oh -> order_id}}</th>
                        @if(strpos($oh -> status, 'Rejected') !== false)
                            <td style="color: red">{{ $oh -> status}}</td>
                        @elseif(strpos($oh -> status, 'Completed') !== false)
                            <td style="color: green">{{ $oh -> status}}</td>
                        @elseif(strpos($oh -> status, 'On Delivery') !== false || strpos($oh -> status, 'Items Ready') !== false)
                            <td style="color: blue">{{ $oh -> status}}</td>
                        @elseif(strpos($oh -> status, 'Approved') !== false)
                            <td style="color: #16c9e9">{{ $oh -> status }}</td>
                        @else
                            <td>{{ $oh -> status}}</td>
                        @endif

                        @if(strpos($oh -> status, 'Rejected') !== false)
                            <td>{{ $oh -> reason}}</td>
                        @else
                            <td>{{ $oh -> descriptions}}</td>
                        @endif

                        {{-- @if(strpos($oh -> status, 'Approved') !== false || strpos($oh -> status, 'Order Completed') !== false) --}}
                        <td>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#detail-{{ $oh -> id }}">Detail</button>
                            <a href="/supervisor/{{ $oh -> id }}/download-pr" class="btn btn-warning" target="_blank">Download PR</a>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>

            </main>

            {{-- Modal detail --}}
            @foreach($orderHeads as $o)
                <div class="modal fade" id="detail-{{ $o->id }}" tabindex="-1" role="dialog" aria-labelledby="detailTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex-column">
                                        <h5 class="modal-title" id="detailTitle" style="color: white"><strong>Order ID</strong></h5>
                                        <h5 class="modal-title" id="detailTitle" style="color: white">{{ $o->order_id }}</h5>
                                    </div>
                                    <div class="d-flex-column ml-5">
                                        <h5 class="modal-title" id="detailTitle" style="color: white"><strong>Nama Kapal</strong></h5>
                                        <h5 class="modal-title" id="detailTitle" style="color: white">{{ $o->boatName }}</h5>
                                    </div>
                                </div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h5>Nomor PR : {{ $o -> noPr }}</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Item Barang</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Terakhir Diberikan</th>
                                            <th scope="col">Umur Barang</th>
                                            <th scope="col">Department</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orderDetails as $od)
                                            @if($od -> orders_id == $o -> order_id)
                                                <tr>
                                                    <td>{{ $od -> item -> itemName }}</td>
                                                    <td>{{ $od -> quantity }} {{ $od -> item -> unit }}</td>
                                                    <td>{{ $od -> item -> lastGiven }}</td>
                                                    <td>{{ $od -> item -> itemAge }}</td>
                                                    <td>{{ $od -> department }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> 
                            <div class="modal-footer">
                                {{-- Check if the order is rejected, then do not show the approve & reject button --}}
                                @if(strpos($o -> status, 'In Progress By Supervisor') !== false)

                                    {{-- Button to trigger modal 2 --}}
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reject-order-{{ $o -> id }}">Reject</button>

                                    <a href="/supervisor/{{ $o -> id }}/approve-order" class="btn btn-primary">Approve</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

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
                    <form method="POST" action="/supervisor/{{ $oh -> id }}/reject-order">
                        @method('put')
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

        <style>
            td, th{
                word-wrap: break-word;
                min-width: 150px;
                max-width: 250px;
                text-align: center;
            }
        </style>
    @endsection
@else
    @include('../layouts/notAuthorized')
@endif