@extends('../layouts.base')

@section('title', 'Crew Dashboard')

@section('container')
<div class="row">
    @include('crew.sidebar')

    
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h2>Welcome back, {{ Auth::user()->name }} !</h2>
            <h3>{{ "Today is, " . date('l M Y') }}</h3>
        </div>
        <h2 class="mt-3 mb-3" style="text-align: center">Order List</h2>
        <div class="d-flex justify-content-end">
            {{ $orderHeads->links() }}
        </div>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Status</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col" class="text-center">Action/Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderHeads as $o)
                <tr>
                    <th>{{ $o -> order_id}}</th>
                    @if(strpos($o -> status, 'Rejected') !== false)
                        <td style="color: red">{{ $o -> status}}</td>
                    @elseif(strpos($o -> status, 'Completed') !== false)
                        <td style="color: green">{{ $o -> status}}</td>
                    @else
                        <td>{{ $o -> status}}</td>
                    @endif
                    <td>{{ $o -> reason }}</td>
                    @if($o -> status == 'On Delivery')
                        <td class="d-flex justify-content-center">
                            <button type="button" class="btn btn-success" data-toggle="modal" id="detail" data-target="#editItem-{{ $o -> id }}">
                                Detail
                            </button>
                            <a href="/crew/order/{{ $o->id }}/accept" class="btn btn-primary ml-3">Accept</a>
                        </td>
                    @else
                    <td class="d-flex justify-content-center">
                        <button type="button" class="btn btn-success" data-toggle="modal" id="detail" data-target="#editItem-{{ $o -> id }}">
                            Detail
                        </button>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>

        </table>
    </main>
    @foreach($orderHeads as $o)
            <div class="modal fade" id="editItem-{{ $o->id }}" tabindex="-1" role="dialog" aria-labelledby="editItemTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editItemTitle">Nama Kapal # {{ $o -> boatName }}</h5>
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
                                        <th scope="col">Satuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderDetails as $od)
                                        @if($od -> orders_id == $o -> order_id)
                                            <tr>
                                                <td>{{ $od -> itemName }}</td>
                                                <td>{{ $od -> quantity }}</td>
                                                <td>{{ $od -> unit }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    @endforeach
</div>

@endsection
