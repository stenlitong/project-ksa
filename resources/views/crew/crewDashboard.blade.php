@if(Auth::user()->hasRole('crew'))
    @extends('../layouts.base')

    @section('title', 'Crew Dashboard')

    @section('container')
    <div class="row">
        @include('crew.sidebar')

        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @include('../layouts/time')
            
            <h2 class="mt-3 mb-3" style="text-align: center">Order List</h2>
            <div class="d-flex justify-content-end">
                {{ $orderHeads->links() }}
            </div>

            @if(session('status'))
                <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                    {{ session('status') }}
                </div>
            @endif

            <div class="d-flex justify-content-end mb-3">
                <a href="{{ Route('crew.completed-order') }}" class="btn btn-success mr-3">Completed ({{  $completed }})</a>
                <a href="{{ Route('crew.in-progress-order') }}" class="btn btn-danger mr-3">In Progress ({{ $in_progress }})</a>
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
                        @elseif($o -> status == 'On Delivery' || $o -> status == 'Items Ready')
                            <td style="color: blue">{{ $o -> status}}</td>
                        @else
                            <td>{{ $o -> status}}</td>
                        @endif
                        
                        @if(strpos($o -> status, 'Rejected') !== false)
                            <td style="word-wrap: break-word;min-width: 250px;max-width: 250px;">{{ $oh -> reason}}</td>
                        @else
                            <td style="word-wrap: break-word;min-width: 250px;max-width: 250px;">{{ $o -> descriptions}}</td>
                        @endif

                        @if($o -> status == 'On Delivery' || $o -> status == 'Items Ready')
                            <td >
                                <button type="button" style="margin-left: 40%" class="btn btn-info" data-toggle="modal" id="detail" data-target="#editItem-{{ $o -> id }}">
                                    Detail
                                </button>
                                <a href="/crew/order/{{ $o->id }}/accept" class="btn btn-primary ml-3">Accept</a>
                            </td>
                        @else
                        <td>
                            <button type="button" style="margin-left: 40%" class="btn btn-info" data-toggle="modal" id="detail" data-target="#editItem-{{ $o -> id }}">
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
                            <div class="modal-header bg-danger">
                                <div class="d-flex-column">
                                    <h5 class="modal-title" id="detailTitle" style="color: white"><strong>Nama Kapal</strong></h5>
                                    <h5 class="modal-title" id="detailTitle" style="color: white">{{ $o->boatName }}</h5>
                                </div>
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
                                                    <td>{{ $od -> item -> itemName }}</td>
                                                    <td>{{ $od -> quantity }}</td>
                                                    <td>{{ $od -> item ->unit }}</td>
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

    <style>
        td, th{
            word-wrap: break-word;
            min-width: 200px;
            max-width: 200px;
            text-align: center;
        }
    </style>

    @endsection
@else
    @include('../layouts/notAuthorized')
@endif