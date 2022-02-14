@if(Auth::user()->hasRole('logistic'))
    @extends('../layouts.base')

    @section('title', 'Logistic Review Job Request')

    @section('container')
    <div class="row">
        @include('logistic.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3 wrapper">
                <h1 class="mt-3" style="text-align: center">Review Job Request</h1> 
                <br>
                @if (session('status'))
                    <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                        {{session('status')}}
                    </div>
                @endif
                @if ($success = Session::get('success'))
                        <div class="alert alert-success alert-block" id="success">
                            <strong>{{ $success }}</strong>
                        </div>
                @endif
                @if ($failed = Session::get('failed'))
                        <div class="alert alert-danger alert-block" id="failed">
                            <strong>{{ $failed }}</strong>
                        </div>
                @endif
                
                <div class="row">
                    {{-- <div> --}}
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
                                        @else
                                            <td style="word-wrap: break-word;min-width: 250px;max-width: 250px;">Awaiting for Review</td>
                                        @endif
                        
                                        @if($jr -> status == 'Job Request In Progress By Logistics')
                                            <td>
                                                <button type="button" class="btn btn-info" data-toggle="modal" id="detail" data-target="#editJob-{{ $jr -> id }}">
                                                    Detail
                                                </button>
                                            </td>
                                        @else
                                            <td>
                                                <a href="/logistic/{{ $jr -> id }}/download-JR" style="color: white" class="btn btn-warning" target="_blank">Download JR</a>
                                            </td>
                                        @endif
                                        <td>
                                            <div class="row">
                                                <div class="col">
                                                    {{-- <button class="btn btn-success btn-sm">Approve Request</button> --}}
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#approvecompany-{{$jr-> id}}">
                                                        Approve Request
                                                    </button>
                                                </div>
                                                <div class="col">
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#rejectjob-{{ $jr -> id }}">Reject Request</button>
                                                </div>
                                            </div>
                                        </td>

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
                                                    <th scope="col">Note</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($datadetails as $c)
                                                    @if($c -> jasa_id == $jr -> id)
                                                        <tr>
                                                            <td class="bg-white" style="text-transform: uppercase;"><strong>{{ $c ->tugName }} / {{ $c ->bargeName }}</td>
                                                            <td class="bg-white"style="text-transform: uppercase;"><strong>{{ $c ->lokasi }}</td>
                                                            <td class="bg-white">{{ $c ->note }}</td>
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
                    {{-- modal job reject details --}}
                        @foreach($JobRequestHeads as $jr )
                            <div class="modal fade" id="rejectjob-{{ $jr -> id }}" tabindex="-1" role="dialog" aria-labelledby="editJobTitle" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <div class="d-flex-column">
                                                <h5 class="modal-title" id="detailTitle" style="color: white"><strong>Reject Job Request?</strong></h5>
                                            </div>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="/logistic/Review-Job-Rejected">
                                            @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name='jobhead_id' value= {{$jr->Headjasa_id}}>
                                            <input type="hidden" name='jobhead_name' value= {{$jr->created_by}}>
                                                <div class="form-group">
                                                    <label for="reason">Reason</label>
                                                    <textarea class="form-control" name="reasonbox" required id="reason" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" id="submitreject2" class="btn btn-danger">Reject Request</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    <!-- Modal approve -->
                        @foreach($JobRequestHeads as $jr )
                            <div class="modal fade" id="approvecompany-{{$jr-> id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-success">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Approve Request</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <form method="POST" action="/logistic/Review-Job-Approved">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name='jobhead_id' value= {{$jr->Headjasa_id}}>
                                            <input type="hidden" name='jobhead_name' value= {{$jr->created_by}}>
                                            {{-- send input to identify --}}
                                            <label for="company" class="mb-3">Perusahaan</label>
                                            <select class="form-control" name="company" id="company" required>
                                                <option value="KSA">KSA</option>
                                                <option value="ISA">ISA</option>
                                                <option value="KSAO">KSA OFFSHORE</option>
                                                <option value="KSAM">KSA MARITIME</option>
                                                <option value="SKB">SKB</option>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-success">Approve Request</button>
                                        </div>
                                    </form>
                                </div>
                                </div>
                            </div>
                        @endforeach
                    {{-- </div> --}}
                </div>
            </div>
        </main>
    </div>
    
    <script>
        setTimeout(function() {
            $('.alert').fadeOut('fast');
        }, 3000); 
    </script>

    <style>
        body{
            /* background-image: url('/images/logistic-background.png'); */
            background-repeat: no-repeat;
            background-size: cover;
        }
        .wrapper{
            padding: 10px;
            border-radius: 10px;
            background-color: antiquewhite;
            height: 1100px;
            /* height: 100%; */
        }
        label{
            font-weight: bold;
        }
        th{
            color: white;
        }
        td, th{
            word-wrap: break-word;
            min-width: 100px;
            max-width: 120px;
            text-align: center;
        }
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
        .alert{
                text-align: center;
        }
        .modal-backdrop {
            height: 100%;
            width: 100%;
        }
        @media (max-width: 768px) {
        #row-wrapper{
            overflow-x: auto;
        }
        }
    </style>
    @endsection
@else
    @include('../layouts/notAuthorized')
@endif