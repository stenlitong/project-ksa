@if(Auth::user()->hasRole('crew'))
    @extends('../layouts.base')

    @section('title', 'Crew Dashboard')

    @section('container')
    <div class="row">
        @include('crew.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="padding-bottom: 30px">
            @include('../layouts/time')            
            <div class="wrapper">

            
            <h1 class="mt-3 mb-3" style="text-align: center">Order List</h1>

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

            <div class="d-flex">
                <div class="p-2 mr-auto">
                    <h5>Cabang: {{ Auth::user()->cabang }}</h5>
                    <form action="{{ Route('crew.changeBranch') }}" method="POST">
                        @csrf
                        <div class="d-flex">
                            <select class="form-select mr-3" aria-label="Default select example" name="cabang" id="cabang">
                                <option value="Jakarta" id="Jakarta" 
                                    @php if(Auth::user()->cabang == 'Jakarta') {
                                        echo('selected');
                                    } 
                                    @endphp
                                >Jakarta</option>
                                <option value="Banjarmasin" id="Banjarmasin"
                                    @php if(Auth::user()->cabang == 'Banjarmasin') {
                                        echo('selected');
                                    } 
                                    @endphp
                                >Banjarmasin</option>
                                <option value="Samarinda" id="Samarinda"
                                    @php if(Auth::user()->cabang == 'Samarinda') {
                                        echo('selected');
                                    } 
                                    @endphp
                                >Samarinda</option>
                                <option value="Bunati" id="Bunati"
                                    @php if(Auth::user()->cabang == 'Bunati') {
                                        echo('selected');
                                    } 
                                    @endphp
                                >Bunati</option>
                                <option value="Babelan" id="Babelan"
                                    @php if(Auth::user()->cabang == 'Babelan') {
                                        echo('selected');
                                    } 
                                    @endphp
                                >Babelan</option>
                                <option value="Berau" id="Berau"
                                    @php if(Auth::user()->cabang == 'Berau') {
                                        echo('selected');
                                    } 
                                    @endphp
                                >Berau</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

                <div class="p-2 mt-auto">
                    <a href="{{ Route('crew.completed-order') }}" class="btn btn-outline-success mr-3">Completed ({{  $completed }})</a>
                    <a href="{{ Route('crew.in-progress-order') }}" class="btn btn-outline-primary mr-3">In Progress ({{ $in_progress }})</a>
                    {{-- <button class="btn btn-outline-success mr-3">Job Request completed({{  $job_completed }})</button>
                    <button class="btn btn-outline-primary mr-3">Job Request In Progress({{ $job_in_progress }})</button> --}}
                </div>

                <div class="p-2 mt-auto">
                    {{ $orderHeads->links() }}
                </div>
            </div>

            <div id="content" style="overflow-x:auto;">
                <table class="table">
                    <thead class="thead bg-danger">
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
                            <td><strong>{{ $o -> order_id}}</strong></td>
                            @if(strpos($o -> status, 'Rejected') !== false)
                                <td style="color: red; font-weight: bold">{{ $o -> status}}</td>
                            @elseif(strpos($o -> status, 'Completed') !== false)
                                <td style="color: green; font-weight: bold">{{ $o -> status}}</td>
                            @elseif($o -> status == 'On Delivery' || $o -> status == 'Items Ready')
                                <td style="color: blue; font-weight: bold">{{ $o -> status}}</td>
                            @else
                                <td>{{ $o -> status}}</td>
                            @endif
                            
                            @if(strpos($o -> status, 'Rejected') !== false)
                                <td style="word-wrap: break-word;min-width: 250px;max-width: 250px;">{{ $o -> reason}}</td>
                            @else
                                <td style="word-wrap: break-word;min-width: 250px;max-width: 250px;">{{ $o -> descriptions}}</td>
                            @endif

                            @if($o -> status == 'On Delivery' || $o -> status == 'Items Ready')
                                <td >
                                    <button type="button" class="btn btn-info" data-toggle="modal" id="detail" data-target="#editItem-{{ $o -> id }}">
                                        Detail
                                    </button>
                                    <a href="/crew/order/{{ $o->id }}/accept" class="btn btn-primary ml-3">Accept</a>
                                </td>
                            @else
                            <td>
                                <button type="button" class="btn btn-info" data-toggle="modal" id="detail" data-target="#editItem-{{ $o -> id }}">
                                    Detail
                                </button>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                        {{-- job request
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
                            
                            @if(strpos($jr -> status, 'Job Request Rejected By Logistics') !== false)
                                <td style="word-wrap: break-word;min-width: 250px;max-width: 250px;">{{ $jr -> reason}}</td>
                            @else
                                <td style="word-wrap: break-word;min-width: 250px;max-width: 250px;">This job request is generated at {{ $jr -> jrDate}}</td>
                            @endif

                            @if($jr -> status == 'Job Request In Progress By Logistics' or $jr -> status == 'Job Request Approved By Logistics')
                                <td>
                                    <button type="button" class="btn btn-info" data-toggle="modal" id="detail" data-target="#editJob-{{ $jr -> id }}">
                                        Detail
                                    </button>
                                </td>
                            @endif
                        </tr>
                        @empty
                        @endforelse --}}
                    </tbody>

                </table>
            </div>
        </div>
        </main>
        {{-- modal job details --}}
            {{-- @foreach ($JobRequestHeads as $jr)
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
                                        @foreach($jobDetails as $c)
                                            @if($c -> jasa_id == $jr -> id)
                                                <tr>
                                                    <td class="bg-white" style="text-transform: uppercase;"><strong>{{ $c ->tugName }} / {{ $c ->bargeName }}</td>
                                                    <td class="bg-white"style="text-transform: uppercase;"><strong>{{ $c ->lokasi }}</td>
                                                    <td class="bg-white">{{ $c ->note }}</td>
                                                    <td class="bg-white">{{ $c ->quantity }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach --}}
        
        {{-- modal order details --}}
            @foreach($orderHeads as $o)
                    <div class="modal fade" id="editItem-{{ $o->id }}" tabindex="-1" role="dialog" aria-labelledby="editItemTitle"
                        aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
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
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">Item Barang</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Department</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orderDetails as $od)
                                                @if($od -> orders_id == $o -> id)
                                                    <tr>
                                                        <td>{{ $od -> item -> itemName }}</td>
                                                        <td>{{ $od -> quantity }} {{ $od -> item -> unit }}</td>
                                                        <td>{{ $od -> department }}</td>
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
            max-width: 120px;
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