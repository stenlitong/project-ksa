@extends('../layouts.base')

@section('title', 'Purchasing Dashboard')

@section('container')
<div class="row">
    @include('purchasing.sidebar')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        @include('../layouts/time')

        <div class="row">
            <div class="col mt-3">
                <h1>Supplier Card</h1>
            </div>
            <div class="col">
                <h2 class="mt-3 mb-4" style="text-align: center">Order List</h2>
                    <div class="d-flex flex-row justify-content-between">
                        <form class="" action="">
                            <div class="input-group mb-3 ">
                                <input type="text" class="form-control" placeholder="Search by Order ID or Status..." name="search" id="search" style="width: 400px">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </form>
                        <div class="">
                            {{ $orderHeads->links() }}
                        </div>
                    </div>
                <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">Order ID</th>
                        <th scope="col">Status</th>
                        <th scope="col">Detail</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($orderHeads as $oh)
                        <tr>
                            <td><strong>{{ $oh -> order_id }}</strong></td>
                            <td>{{ $oh -> status }}</td>
                            <td>
                                {{-- Modal button for order details --}}
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#detail-{{ $oh -> id }}">Detail</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

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
                                            <th scope="col">Department</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orderDetails as $od)
                                            @if($od -> orders_id == $o -> order_id)
                                                <tr>
                                                    <td>{{ $od -> item -> itemName }}</td>
                                                    <td>{{ $od -> quantity }} {{ $od -> unit }}</td>
                                                    <td>{{ $od -> department }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> 
                            <div class="modal-footer">
                                {{-- Check if the order is rejected, then do not show the approve & reject button --}}
                                @if(strpos($o -> status, 'In Progress (Purchasing)') !== false)
                                    {{-- Button to trigger modal 2 --}}
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reject-order-{{ $o -> id }}">Reject</button>
                                    <a href="" class="btn btn-primary">Approve</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        @endforeach

    </main>
</div>

@endsection