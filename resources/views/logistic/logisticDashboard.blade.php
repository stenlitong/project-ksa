@if(Auth::user()->hasRole('logistic'))
    @extends('../layouts.base')

    @section('title', 'Logistic Dashboard')

    @section('container')
    <div class="row">
        @include('logistic.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="padding-bottom: 30px">
            
            @include('../layouts/time')
            <div class="wrapper">
            <h2 class="mt-3 mb-2" style="text-align: center">Order List Cabang {{ Auth::user()->cabang }}</h2>

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

            @error('descriptions')
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    Alasan Wajib Diisi
                </div>
            @enderror

            <br>

            <div class="d-flex justify-content-end">
                {{ $orderHeads->links() }}
            </div>

            <div class="d-flex mb-3">
                <form class="mr-auto w-50" action="">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by Order ID or Status..." name="search" id="search" value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
                <div>
                    <a href="{{ Route('logistic.completed-order') }}" class="btn btn-success mr-3">Completed ({{  $completed }})</a>
                    <a href="{{ Route('logistic.in-progress-order') }}" class="btn btn-danger mr-3">In Progress ({{ $in_progress }})</a>
                    <button class="btn btn-outline-success mr-3">Job Request completed({{  $job_completed }})</button>
                    <button class="btn btn-outline-primary mr-3">Job Request In Progress({{ $job_in_progress }})</button>
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
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderHeads as $oh)
                        <tr>
                            <td class="bg-white"><strong>{{ $oh -> order_id}}</strong></td>
                            @if(strpos($oh -> status, 'Rejected') !== false)
                                <td class="bg-white"><span style="color: red;font-weight: bold;">{{ $oh -> status}}</span></td>
                            @elseif(strpos($oh -> status, 'Completed') !== false)
                                <td class="bg-white"><span style="color: green;font-weight: bold;">{{ $oh -> status}}</span></td>
                            @elseif(strpos($oh -> status, 'On Delivery') !== false || strpos($oh -> status, 'Items Ready') !== false)
                                <td class="bg-white"><span style="color: blue;font-weight: bold;">{{ $oh -> status}}</span></td>
                            @elseif(strpos($oh -> status, 'Delivered') !== false)
                                <td class="bg-white"><span style="color: #16c9e9;font-weight: bold;">{{ $oh -> status }}</span></td>
                            @else
                                <td class="bg-white">{{ $oh -> status}}</td>
                            @endif

                            @if(strpos($oh -> status, 'Rejected') !== false)
                                <td class="bg-white">{{ $oh -> reason}}</td>
                            @else
                                <td class="bg-white">{{ $oh -> descriptions}}</td>
                            @endif

                            {{-- @if(strpos($oh -> status, 'Approved') !== false || strpos($oh -> status, 'Order Completed') !== false) --}}

                            {{-- @if(strpos($oh -> status, 'Order') !== false || strpos($oh -> status, 'Delivered') !== false)
                                <td>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#detail-{{ $oh -> id }}">Detail</button>
                                    <a href="/logistic/{{ $oh -> id }}/download-pr" style="color: white" class="btn btn-warning" target="_blank">Download PR</a>
                                </td>
                            @else --}}
                                {{-- Button to trigger the modal detail --}}
                                <td class="bg-white"><button type="button" class="btn btn-info" data-toggle="modal" data-target="#detail-{{ $oh -> id }}">
                                    Detail
                                </button></td>
                            {{-- @endif --}}

                            <td class="bg-white">
                                {{-- @if(strpos($oh -> status, 'Order In Progress') !== false || strpos($oh -> status, 'Delivered') !== false || strpos($oh -> status, 'Order Completed') !== false || strpos($oh -> status, 'Rechecked') !== false || strpos($oh -> status, 'Being Finalized') !== false || strpos($oh -> status, 'Revised') !== false) --}}
                                @if(strpos($oh -> status, 'Delivered') !== false || strpos($oh -> status, 'Order Completed') !== false)
                                    <a href="/logistic/{{ $oh -> id }}/download-pr" style="color: white" class="btn btn-warning" target="_blank">Download PR</a>
                                @endif
                                @if(strpos($oh -> status, 'Delivered') !== false)
                                    <a href="/logistic/stock-order/{{ $oh -> id }}/accept-order" class="btn btn-primary">Accept</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        {{-- job dashboard Details --}}
                        @forelse ($JobRequestHeads as $jr )
                        <tr>
                            <td><strong>{{ $jr -> Headjasa_id}}</strong></td>
                            @if(strpos($jr -> status, 'Job Request Rejected By Logistics') !== false)
                            <td style="color: red; font-weight: bold">{{ $jr -> status}}</td>
                            @elseif(strpos($jr -> status, 'Job Request Approved By Logistics') !== false)
                                <td style="color: green; font-weight: bold">{{ $jr -> status}}</td>
                            @elseif($jr -> status == 'Job Request In Progress By Logistics')
                                <td style="color: blue; font-weight: bold">{{ $jr -> status}}</td>
                            @else
                                <td>{{ $jr -> status}}</td>
                            @endif
                            
                            @if(strpos($jr -> status, 'Rejected') !== false)
                                <td style="word-wrap: break-word;min-width: 250px;max-width: 250px;">{{ $jr -> reason}}</td>
                            @elseif(strpos($jr -> status, 'Approved') !== false)
                                <td style="word-wrap: break-word;min-width: 250px;max-width: 250px;">This Job Request is now on Progress</td>
                            @else
                                <td style="word-wrap: break-word;min-width: 250px;max-width: 250px;">This Job Request is Awaiting for Review</td>
                            @endif

                            @if($jr -> status == 'Job Request In Progress By Logistics')
                                <td>
                                    <button type="button" class="btn btn-info" data-toggle="modal" id="detail" data-target="#editJob-{{ $jr -> id }}">
                                        Detail
                                    </button>
                                </td>
                            @elseif(strpos($jr -> status, 'Rejected') !== false)
                                <td>
                                    {{-- show nothing --}}
                                </td>
                                <td>
                                    {{-- show nothing --}}
                                </td>
                            @else
                                <td>
                                    <button type="button" class="btn btn-info" data-toggle="modal" id="detail" data-target="#editJob-{{ $jr -> id }}">
                                        Detail
                                    </button>
                                </td>
                                <td>
                                    <a href="/logistic/{{ $jr -> id }}/download-JR" style="color: white" class="btn btn-warning" target="_blank">Download JR</a>
                                </td>
                            @endif
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- modal job details --}}
                @foreach ($JobRequestHeads as $jr)
                    <div class="modal fade" id="editJob-{{ $jr->id }}" tabindex="-1" role="dialog" aria-labelledby="editJobTitle" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <div class="d-flex-column">
                                        <h5 class="modal-title" id="detailTitle" style="color: white"><strong>Detail Job Request</strong></h5>
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">Nama Tugboat / barge</th>
                                                <th scope="col">Lokasi Perbaikan</th>
                                                <th scope="col">description</th>
                                                <th scope="col">quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($jobDetails as $c)
                                                @if($c -> jasa_id == $jr -> id)
                                                    <tr>
                                                        <td class="bg-white" style="text-transform: uppercase;"><strong>{{ $c ->tugName }} / {{ $c ->bargeName }}</td>
                                                        <td class="bg-white"style="text-transform: uppercase;"><strong>{{ $c ->lokasi }}</td>
                                                        <td class="bg-white">{{ $c ->note }}</td>
                                                        <td class="bg-white">{{ $c ->quantity }}</td>
                                                    </tr>
                                                @endif
                                            @empty
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            {{-- Modal detail --}}
            @foreach($orderHeads as $oh)
                <div class="modal fade" id="detail-{{ $oh -> id }}" tabindex="-1" role="dialog" aria-labelledby="detailTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex-column">
                                        <h5 class="modal-title" id="detailTitle" style="color: white"><strong>Order ID</strong></h5>
                                        <h5 class="modal-title" id="detailTitle" style="color: white">{{ $oh -> order_id }}</h5>
                                    </div>
                                    <div class="d-flex-column ml-5">
                                        <h5 class="modal-title" id="detailTitle" style="color: white"><strong>Nama Kapal</strong></h5>
                                        <h5 class="modal-title" id="detailTitle" style="color: white">{{ $oh -> boatName }}</h5>
                                    </div>
                                    <div class="d-flex-column ml-5">
                                        <h5 class="modal-title" id="detailTitle" style="color: white"><strong>Request By</strong></h5>
                                        <h5 class="modal-title" id="detailTitle" style="color: white">{{ $oh -> user -> name }}</h5>
                                    </div>
                                </div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @if(strpos($oh -> status, 'Order') !== false || strpos($oh -> status, 'Delivered') !== false)
                                    <div class="d-flex justify-content-around">
                                        <h5>Nomor PR : {{ $oh -> noPr }}</h5>
                                        <h5>Tipe Order : {{ $oh -> orderType }}</h5>
                                    </div>
                                @endif
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Item Barang</th>
                                            <th scope="col">Request Quantity</th>
                                            @if(strpos($oh -> status, 'Items Ready') !== false || strpos($oh -> status, 'On Delivery') !== false || strpos($oh -> status, 'Request Completed') !== false)
                                                <th scope="col">Accepted Quantity</th>
                                            @endif

                                            {{-- @if(strpos($oh -> status, 'Request') !== false || strpos($oh -> status, 'Items Ready') !== false || strpos($oh -> status, 'On Delivery') !== false) --}}
                                            @if(strpos($oh -> order_id, 'COID') !== false)
                                                <th scope="col">Terakhir Diberikan</th>
                                            @endif
                                            <th scope="col">Umur Barang</th>
                                            <th scope="col">Department</th>
                                            <th scope="col">Golongan</th>
                                            
                                            @if(strpos($oh -> order_id, 'ROID') !== false || strpos($oh -> order_id, 'LOID') !== false)
                                                <th scope="col">Status Barang</th>
                                            @endif

                                            @if(strpos($oh -> status, 'Request In Progress') !== false)
                                                <th scope="col">Stok Barang</th>
                                            @endif

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orderDetails as $od)
                                            @if($od -> orders_id == $oh -> id)
                                                <tr>
                                                    <td><strong>{{ $od -> item -> itemName }}</strong></td>
                                                    <td><strong>{{ $od -> quantity }} {{ $od -> item -> unit }}</strong></td>
                                                    @if(strpos($oh -> status, 'Items Ready') !== false || strpos($oh -> status, 'On Delivery') !== false || strpos($oh -> status, 'Request Completed') !== false)
                                                        <td><strong>{{ $od -> acceptedQuantity }} {{ $od -> item -> unit }}</strong></td>
                                                    @endif

                                                    @if(strpos($oh -> order_id, 'COID') !== false)
                                                        <td>{{ $od -> item -> lastGiven }}</td>
                                                    @endif

                                                    <td>{{ $od -> item -> itemAge }}</td>
                                                    <td>{{ $od -> department }}</td>
                                                    <td>{{ $od -> item -> golongan }}</td>

                                                    @if(strpos($oh -> order_id, 'ROID') !== false || strpos($oh -> order_id, 'LOID') !== false)
                                                        <td>
                                                            @if($od -> orderItemState == 'Accepted')
                                                                <span style="color: green; font-weight: bold;">{{ $od -> orderItemState }}</span>
                                                            @else
                                                                <span style="color: red; font-weight: bold;">{{ $od -> orderItemState }}</span>
                                                            @endif
                                                        </td>
                                                    @endif

                                                    @if(strpos($od -> status, 'Request In Progress') !== false)
                                                        @if($od -> quantity > $od -> item -> itemStock)
                                                            <td style="color: red; font-weight: bold;">{{ $od -> item -> itemStock}} {{ $od -> item -> unit }} (Stok Tidak Mencukupi)</td>
                                                        @else
                                                            <td style="color: green; font-weight: bold;">{{ $od -> item -> itemStock}} {{ $od -> item -> unit }}</td>
                                                        @endif
                                                    @endif
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> 
                            <div class="modal-footer">
                                {{-- Check if the order is rejected, then do not show the approve & reject button --}}
                                @if(strpos($oh -> status, 'In Progress By Logistic') !== false)
                                    {{-- Button to trigger modal 2 --}}
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reject-order-{{ $oh -> id }}">Reject</button>
                                    <a href="/logistic/order/{{ $oh -> id }}/approve" class="btn btn-primary">Approve</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="reject-order-{{ $oh -> id }}" tabindex="-1" role="dialog" aria-labelledby="reject-orderTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title" style="color: white" id="rejectTitle">Reject Order {{ $oh -> order_id }}</h5>
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
            
            </div>
        </main>
    </div>

    <style>
        body{
            /* background-image: url('/images/logistic-background.png'); */
            background-repeat: no-repeat;
            background-size: cover;
        }
        .wrapper{
            padding: 15px;
            margin: 15px;
            border-radius: 10px;
            background-color: antiquewhite;
            height: 900px;
            /* height: 100%; */
        }
        th{
            color: white;
        }
        td, th{
            word-wrap: break-word;
            min-width: 120px;
            max-width: 160px;
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