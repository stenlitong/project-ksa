@if(Auth::user()->hasRole('supervisor') || Auth::user()->hasRole('supervisorMaster'))
    @extends('../layouts.base')

    @section('title', 'Supervisor Dashboard')

    @section('container')
        <div class="row">
        @include('supervisor.sidebar')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="padding-bottom: 200px">
            @include('../layouts/time')
            
            <h2 class="mt-3 mb-2" style="text-align: center">Order List Cabang {{ Auth::user()->cabang }}</h2>

            <div class="d-flex justify-content-end mr-3">
                {{ $orderHeads->links() }}
            </div>

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

            @error('reason')
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Alasan Wajib Diisi
            </div>
            @enderror

            <div class="d-flex mb-3">
                <form class="mr-auto w-50" action="">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by Order ID or Status..." name="search" id="search">
                        <button class="btn btn-primary" type="submit" value="{{ request('search') }}">Search</button>
                    </div>
                </form>
                <div>
                    <a href="{{ Route('supervisor.completed-order') }}" class="btn btn-success mr-3">Completed ({{  $completed }})</a>
                    <a href="{{ Route('supervisor.in-progress-order') }}" class="btn btn-danger mr-3">In Progress ({{ $in_progress }})</a>
                </div>
            </div>

            <div id="content" style="overflow-x:auto;">
                <table class="table" id="myTable">
                    <thead class="thead bg-danger">
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
                            <td><strong>{{ $oh -> order_id}}</strong></td>
                            @if(strpos($oh -> status, 'Rejected') !== false)
                                <td><span style="color: red; font-weight: bold">{{ $oh -> status}}</span></td>
                            @elseif(strpos($oh -> status, 'Completed') !== false)
                                <td><span style="color: green; font-weight: bold">{{ $oh -> status}}</span></td>
                            @elseif(strpos($oh -> status, 'Delivered') !== false)
                                <td><span style="color: blue; font-weight: bold">{{ $oh -> status}}</span></td>
                            @else
                                <td>{{ $oh -> status}}</td>
                            @endif

                            @if(strpos($oh -> status, 'Rejected') !== false)
                                <td>{{ $oh -> reason}}</td>
                            @else
                                <td>{{ $oh -> descriptions}}</td>
                            @endif

                            <td>
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#detail-{{ $oh -> id }}">Detail</button>
                                <a href="/supervisor/{{ $oh -> id }}/download-pr" class="btn btn-warning" target="_blank">Download PR</a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            </main>

            {{-- Modal detail --}}
            @foreach($orderHeads as $o)
                <div class="modal fade" id="detail-{{ $o->id }}" tabindex="-1" role="dialog" aria-labelledby="detailTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered modal-lg" role="document">
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
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Item Barang</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Umur Barang</th>
                                            <th scope="col">Department</th>
                                            @if(strpos($oh -> status, 'Order In Progress') !== false)
                                                <th scope="col">Stok Barang</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orderDetails as $od)
                                            @if($od -> orders_id == $o -> id)
                                                <tr>
                                                    <td>{{ $od -> item -> itemName }}</td>
                                                    <td><strong>{{ $od -> quantity }} {{ $od -> item -> unit }}</strong></td>
                                                    <td>{{ $od -> item -> itemAge }}</td>
                                                    <td>{{ $od -> department }}</td>
                                                    @if(strpos($oh -> status, 'Order In Progress') !== false)
                                                        <td><strong>{{ $od -> item -> itemStock }} {{ $od -> item -> unit }}</strong></td>
                                                    @endif
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
            th{
                color: white;
            }
            td, th{
                word-wrap: break-word;
                min-width: 150px;
                max-width: 150px;
                text-align: center;
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
    @endsection
@else
    @include('../layouts/notAuthorized')
@endif