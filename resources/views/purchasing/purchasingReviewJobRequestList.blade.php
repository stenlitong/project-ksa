{{-- @if(Auth::user()->hasRole('purchasing')) --}}
    @extends('../layouts.base')

    @section('title', 'Purchasing Dashboard')

    @section('container')
    <div class="row">
        @include('purchasing.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="padding-bottom: 150px">
            <div class="d-flex">
                @include('../layouts/time')

                <div class="p-2 ml-auto mt-5">
                    <h5>Cabang</h5>
                    <select name="cabang" class="form-select" onchange="window.location = this.value;">
                        <option selected disabled>Pilih Cabang</option>
                        <option value="/purchasing/dashboard/Jakarta" 
                            @php
                                if($default_branch == 'Jakarta'){
                                    echo('selected');
                                }
                            @endphp
                        >Jakarta</option>
                        <option value="/purchasing/dashboard/Banjarmasin"
                            @php
                                if($default_branch == 'Banjarmasin'){
                                    echo('selected');
                                }
                            @endphp
                        >Banjarmasin</option>
                        <option value="/purchasing/dashboard/Samarinda"
                            @php
                                if($default_branch == 'Samarinda'){
                                    echo('selected');
                                }
                            @endphp
                        >Samarinda</option>
                        <option value="/purchasing/dashboard/Bunati"
                            @php
                                if($default_branch == 'Bunati'){
                                    echo('selected');
                                }
                            @endphp
                        >Bunati</option>
                        <option value="/purchasing/dashboard/Babelan"
                            @php
                                if($default_branch == 'Babelan'){
                                    echo('selected');
                                }
                            @endphp
                        >Babelan</option>
                        <option value="/purchasing/dashboard/Berau"
                            @php
                                if($default_branch == 'Berau'){
                                    echo('selected');
                                }
                            @endphp
                        >Berau</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col" style="max-width: 850px">
                    <h2 class="mt-3 mb-4" style="text-align: center">Supplier</h2>
                    @if(session('statusA'))
                        <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                            {{ session('statusA') }}
                        </div>
                    @endif

                    @if(session('errorA'))
                        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                            {{ session('errorA') }}
                        </div>
                    @endif
                    
                    <input type="text" id="myFilter" class="form-control ml-3 my-3 w-50" onkeyup="myFunction()" placeholder="Search for supplier...">
                    <div class="row ml-3 flex-column flex-nowrap scrolling-wrapper" id="mySupplier">
                        @if(count($suppliers) == 0)
                            <h5>No Data Found.</h5>
                        @else
                            @foreach($suppliers as $s)
                                <div class="card border-dark w-100 mb-3">
                                    <div class="card-body mr-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="ml-2 d-flex flex-column justify-content-center align-items-center">
                                                {{-- <img src="/images/profile.png" style="height: 150px; width: 150px;"> --}}
                                                <img src="/images/profile.png" class="w-75">
                                                <h5 class="supplier-name mt-2 font-weight-bold">{{ $s -> supplierName }}</h5>
                                                <h5 class="supplier-code mt-2">{{ $s -> supplierCode }}</h5>
                                                <h5 class="supplier-pic mt-2">{{ $s -> supplierPic }}</h5>
                                                <button class="btn btn-outline-primary mt-2" data-toggle="modal" data-target="#detail-supplier-{{ $s -> id }}" >Info</button>
                                            </div>
                                            <div class="mr-5 my-3">
                                                <div class="d-flex justify-content-between ratings">
                                                    <h5 class="smaller-screen-size">Quality</h5>
                                                    <div class="rating d-flex justify-content-end mt-2">
                                                        @for($i = 1 ; $i <= $s->quality ; $i++)
                                                            <i class="fa fa-star checked"></i>
                                                        @endfor
                                                        @for($j = $s->quality + 1 ; $j <= 5 ; $j++)
                                                            <i class = "fa fa-star"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between ratings">
                                                    <h5 class="smaller-screen-size">Top</h5>
                                                    <div class="rating d-flex justify-content-end mt-2">
                                                        @for($i = 1 ; $i <= $s->top ; $i++)
                                                            <i class="fa fa-star checked"></i>
                                                        @endfor
                                                        @for($j = $s->top + 1 ; $j <= 5 ; $j++)
                                                            <i class = "fa fa-star"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between ratings">
                                                    <h5 class="smaller-screen-size">Price</h5>
                                                    <div class="rating d-flex justify-content-end mt-2">
                                                        @for($i = 1 ; $i <= $s->price ; $i++)
                                                            <i class="fa fa-star checked"></i>
                                                        @endfor
                                                        @for($j = $s->price + 1 ; $j <= 5 ; $j++)
                                                            <i class = "fa fa-star"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between ratings">
                                                    <h5 class="smaller-screen-size">Delivery Time</h5>
                                                    <div class="rating d-flex justify-content-end mt-2">
                                                        @for($i = 1 ; $i <= $s->deliveryTime ; $i++)
                                                            <i class="fa fa-star checked"></i>
                                                        @endfor
                                                        @for($j = $s->deliveryTime + 1 ; $j <= 5 ; $j++)
                                                            <i class = "fa fa-star"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between ratings">
                                                    <h5 class="smaller-screen-size">Item<br>Availability</h5>
                                                    <div class="rating d-flex justify-content-end mt-3">
                                                        @for($i = 1 ; $i <= $s->availability ; $i++)
                                                            <i class="fa fa-star checked"></i>
                                                        @endfor
                                                        @for($j = $s->availability + 1 ; $j <= 5 ; $j++)
                                                            <i class = "fa fa-star"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <button class="btn btn-info mt-3" style="margin-left: 40%" data-toggle="modal" data-target="#edit-rating-{{ $s -> id }}">Edit Rating</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col">
                    <h2 class="mt-3 mb-4" style="text-align: center;">Order List Cabang {{ $default_branch }}</h2>
                    
                    @if(session('statusB'))
                        <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                            {{ session('statusB') }}
                        </div>
                    @endif

                    @if(session('errorB'))
                        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                            {{ session('errorB') }}
                        </div>
                    @endif

                    @error('reasonbox')
                        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                            Alasan Wajib Diisi
                        </div>
                    @enderror

                    <div class="d-flex mb-3">
                        <form class="mr-auto w-50" action="">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search by Order ID or Status..." value="{{ request('search') }}" name="search" id="search">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </form>
                        <div>
                            <a href="/purchasing/completed-order/{{ $default_branch }}" class="btn btn-success mr-3">Completed ({{  $completed }})</a>
                            <a href="/purchasing/in-progress-order/{{ $default_branch }}" class="btn btn-danger mr-3">In Progress ({{ $in_progress }})</a>
                        </div>
                    </div>

                    <div id="content" style="overflow-x:auto;">
                        <table class="table">
                            <thead class="thead bg-danger">
                            <tr>
                                <th scope="col">Order/Job ID</th>
                                <th scope="col">Status</th>
                                <th scope="col">Description</th>
                                <th scope="col">Detail</th>
                            </tr>
                            </thead>
                            <tbody>                      
                            {{-- job dashboard Details --}}
                                @foreach ($JobRequestHeads as $jr )
                                    <tr>
                                        <td><strong>{{ $jr -> Headjasa_id}}</strong></td>
                                        @if(strpos($jr -> status, 'Job Request Approved By') !== false)
                                            <td style="color: green; font-weight: bold">{{ $jr -> status}}</td>
                                        @endif
                                        
                                        @if(strpos($jr -> status, 'Rejected') !== false)
                                            <td style="word-wrap: break-word;min-width: 250px;max-width: 250px;">{{ $jr -> reason}}</td>
                                        @else
                                            <td style="word-wrap: break-word;min-width: 250px;max-width: 250px;">This Job Request is Awaiting for Review</td>
                                        @endif

                                        @if($jr -> status == 'Job Request In Progress By Logistics')
                                            <td>
                                                <button type="button" class="btn btn-info" data-toggle="modal" id="detail" data-target="#editJob-{{ $jr -> id }}">
                                                    Detail
                                                </button>
                                            </td>
                                        @elseif ($jr -> status == 'Job Request Approved By Purchasing')
                                            <td>
                                                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#detailjob-{{ $jr -> id }}">Detail</button>
                                            </td>
                                        @else
                                            <td>
                                            <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCenter-{{ $jr -> id }}">
                                                    Details
                                                </button>
                                            
                                            <!-- Modal -->
                                                <div class="modal fade " id="ModalCenter-{{ $jr -> id }}" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Job Request Details</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="center">
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
                                                            <div class="modal-footer">
                                                                <a class="btn btn-outline-success" href="/purchasing/Review-Job/{{$jr -> id }}">Approve Request</a>
                                                                <button class="btn btn-outline-warning" data-toggle="modal" data-target="#revisejob-{{ $jr -> id }}">Revise Request</button>
                                                                
                                                                <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#rejectjob-{{ $jr -> id }}">Reject Request</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    {{ $JobRequestHeads->links() }}
                </div>

            </div>

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
                            <form method="POST" action="/purchasing/Review-Job-Rejected">
                                @csrf
                            <div class="modal-body">
                                <input type="hidden" name='jobhead_id' value= {{$jr->Headjasa_id}}>
                                <input type="hidden" name='jobhead_name' value= {{$jr->created_by}}>
                                    <div class="form-group">
                                        <label for="reason">Reason</label>
                                        <textarea class="form-control" name="reasonbox" required id="reason" rows="3" required></textarea>
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

            {{-- modal job revise details --}}
            @foreach($JobRequestHeads as $jr )
                <div class="modal fade" id="revisejob-{{ $jr -> id }}" tabindex="-1" role="dialog" aria-labelledby="editJobTitle" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-dark">
                                <div class="d-flex-column">
                                    <h5 class="modal-title" id="detailTitle" style="color: white"><strong>Ask To Revise Job Request?</strong></h5>
                                </div>
                                <button type="button" style="color: white" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="/purchasing/Review-Job-Revised">
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
                                    <button type="submit" id="submitreject2" class="btn btn-danger">Revise Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            
            {{--modal for Job order  --}}
            @foreach($JobRequestHeads as $jr)
                <div class="modal fade" id="detailjob-{{ $jr->id }}" tabindex="-1" role="dialog" aria-labelledby="detailTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <div class="d-flex justify-content-around">
                                    <h5><span style="color: white">Order : {{ $jr -> JobRequestHeads }}</span></h5>
                                    <h5 class="ml-5"><span style="color: white">Processed By : {{ $jr -> check_by }}</span></h5>
                                    <h5 class="ml-5"><span style="color: white">Tipe Order : JOB Request</span></h5>
                                </div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex justify-content-around mb-3">
                                    <h5>Nomor JR : {{ $jr -> noJr }}</h5>
                                    <h5>Nomor JO : {{ $jr -> noPo }}</h5>
                                </div>
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Note</th>
                                            <th scope="col">Status Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($jobDetails as $od)
                                            @if($od -> jasa_id == $jr -> id)
                                                <tr>
                                                    <td>{{ $od -> note }}</td>
                                                    <td>{{ $od -> quantity }}</td>
                                                    <td>
                                                        @if(strpos($jr -> Status , 'Approved') !== false)
                                                            <span style="color: green; font-weight: bold;">{{ $jr -> status }}</span>
                                                        @else
                                                            <span style="color: red; font-weight: bold;">{{ $jr -> status }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> 
                            <div class="modal-footer">
                                {{-- Check if the order is already progressed to the next stage/rejected, then do not show the approve & reject button --}}
                                {{-- @if($oh -> status == 'Order In Progress By Purchasing')
                                    Button to trigger modal 2
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reject-order-{{ $oh -> id }}">Reject</button>
                                    <a href="/purchasing/order/{{ $oh -> id }}/approve" class="btn btn-primary mr-3">Approve</a>
                                @elseif(strpos($oh -> status, 'Rechecked') !== false)
                                    <a href="/purchasing/order/{{ $oh -> id }}/approve" class="btn btn-primary mr-3">Review Order</a>
                                @elseif(strpos($oh -> status, 'Revised') !== false)
                                    <a href="/purchasing/order/{{ $oh -> id }}/revise" class="btn btn-primary mr-3">Revise Order</a>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="modal fade" id="reject-order-{{ $jr -> id }}" tabindex="-1" role="dialog" aria-labelledby="reject-orderTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title" id="rejectTitle" style="color: white">Reject Order {{ $jr -> order_id }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="/purchasing/order/{{ $jr -> id }}/reject">
                            @csrf
                            <div class="modal-body"> 
                                <label for="reason">Alasan</label>
                                <textarea class="form-control" name="reason" id="reason" rows="3" placeholder="Input Alasan Reject Order"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    </div>
                </div> --}}
            @endforeach

            {{-- Modal for edit supplier ratings --}}
            @foreach($suppliers as $s)
                <div class="modal fade" id="edit-rating-{{ $s -> id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-danger" style="color: white">
                        <h5 class="modal-title" id="exampleModalLabel">{{ $s -> supplierName }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <form method="POST" action="/purchasing/{{ $s -> id }}/edit">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group d-flex justify-content-between ratings">
                                    <h5 class="smaller-screen-size">Quality</h5>
                                    <div class="rating-css">
                                        <div class="star-icon">
                                            <input type="radio" value="1" name="quality" checked id="rating1">
                                            <label for="rating1" class="fa fa-star"></label>
                                            <input type="radio" value="2" name="quality" id="rating2">
                                            <label for="rating2" class="fa fa-star"></label>
                                            <input type="radio" value="3" name="quality" id="rating3">
                                            <label for="rating3" class="fa fa-star"></label>
                                            <input type="radio" value="4" name="quality" id="rating4">
                                            <label for="rating4" class="fa fa-star"></label>
                                            <input type="radio" value="5" name="quality" id="rating5">
                                            <label for="rating5" class="fa fa-star"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group d-flex justify-content-between ratings">
                                    <h5 class="smaller-screen-size">Top</h5>
                                    <div class="rating-css star-icon">
                                        <input type="radio" value="1" name="top" checked id="b1">
                                        <label for="b1" class="fa fa-star"></label>
                                        <input type="radio" value="2" name="top" id="b2">
                                        <label for="b2" class="fa fa-star"></label>
                                        <input type="radio" value="3" name="top" id="b3">
                                        <label for="b3" class="fa fa-star"></label>
                                        <input type="radio" value="4" name="top" id="b4">
                                        <label for="b4" class="fa fa-star"></label>
                                        <input type="radio" value="5" name="top" id="b5">
                                        <label for="b5" class="fa fa-star"></label>
                                    </div>
                                </div>
                                <div class="form-group d-flex justify-content-between ratings">
                                    <h5 class="smaller-screen-size">Price</h5>
                                    <div class="rating-css star-icon">
                                        <input type="radio" value="1" name="price" checked id="c1">
                                        <label for="c1" class="fa fa-star"></label>
                                        <input type="radio" value="2" name="price" id="c2">
                                        <label for="c2" class="fa fa-star"></label>
                                        <input type="radio" value="3" name="price" id="c3">
                                        <label for="c3" class="fa fa-star"></label>
                                        <input type="radio" value="4" name="price" id="c4">
                                        <label for="c4" class="fa fa-star"></label>
                                        <input type="radio" value="5" name="price" id="c5">
                                        <label for="c5" class="fa fa-star"></label>
                                    </div>
                                </div>
                                <div class="form-group d-flex justify-content-between ratings">
                                    <h5 class="smaller-screen-size">Delivery Time</h5>
                                    <div class="rating-css star-icon">
                                        <input type="radio" value="1" name="deliveryTime" checked id="d1">
                                        <label for="d1" class="fa fa-star"></label>
                                        <input type="radio" value="2" name="deliveryTime" id="d2">
                                        <label for="d2" class="fa fa-star"></label>
                                        <input type="radio" value="3" name="deliveryTime" id="d3">
                                        <label for="d3" class="fa fa-star"></label>
                                        <input type="radio" value="4" name="deliveryTime" id="d4">
                                        <label for="d4" class="fa fa-star"></label>
                                        <input type="radio" value="5" name="deliveryTime" id="d5">
                                        <label for="d5" class="fa fa-star"></label>
                                    </div>
                                </div>
                                <div class="form-group d-flex justify-content-between ratings">
                                    <h5 class="smaller-screen-size">Availability</h5>
                                    <div class="rating-css star-icon">
                                        <input type="radio" value="1" name="availability" checked id="e1">
                                        <label for="e1" class="fa fa-star"></label>
                                        <input type="radio" value="2" name="availability" id="e2">
                                        <label for="e2" class="fa fa-star"></label>
                                        <input type="radio" value="3" name="availability" id="e3">
                                        <label for="e3" class="fa fa-star"></label>
                                        <input type="radio" value="4" name="availability" id="e4">
                                        <label for="e4" class="fa fa-star"></label>
                                        <input type="radio" value="5" name="availability" id="e5">
                                        <label for="e5" class="fa fa-star"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
                <div class="modal fade" id="detail-supplier-{{ $s->id }}" tabindex="-1" role="dialog" aria-labelledby="editItemTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger" style="color: white">
                                <h5 class="modal-title" id="editItemTitle">Edit/Detail Supplier</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="">
                                    <input type="hidden" name="supplier_id" value="{{ $s -> id }}">
                                    <div class="form-row my-2">
                                        <div class="form-group col-md-6">
                                            <label for="supplierEmail">Email Supplier</label>
                                            <input type="email" class="form-control" name="supplierEmail" id="supplierEmail" value="{{ $s -> supplierEmail }}" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="supplierAddress">Alamat Supplier</label>
                                            <input type="text" class="form-control" name="supplierAddress" id="supplierAddress" value="{{ $s -> supplierAddress}}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-row my-2">
                                        <div class="form-group col-md-6">
                                            <label for="supplierNoRek">No. Rekening Supplier</label>
                                            <input type="text" class="form-control" name="supplierNoRek" id="supplierNoRek" value="{{ $s -> supplierNoRek }}" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="supplierNPWP">NPWP</label>
                                            <input type="text" class="form-control" name="supplierNPWP" id="supplierNPWP" value="{{ $s -> supplierNPWP }}" readonly>
                                        </div>
                                    </div>
                                    {{-- <h5><u>No. Telp</u></h5>
                                    <div class="form-row my-3">
                                        <div class="col">
                                            <label for="noTelpBks">Bekasi</label>
                                            <input type="text" class="form-control" name="noTelpBks" value="{{ $s -> noTelpBks }}" readonly>
                                        </div>
                                        <div class="col">
                                            <label for="noTelpSmd">Samarinda</label>
                                            <input type="text" class="form-control" name="noTelpSmd" value="{{ $s -> noTelpSmd }}" readonly>
                                        </div>
                                        <div class="col">
                                            <label for="noTelpBer">Berau</label>
                                            <input type="text" class="form-control" name="noTelpBer" value="{{ $s -> noTelpBer }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-row my-3">
                                        <div class="col">
                                            <label for="noTelpBnt">Bunati</label>
                                            <input type="text" class="form-control" name="noTelpBnt" value="{{ $s -> noTelpBnt }}" readonly>
                                        </div>
                                        <div class="col">
                                            <label for="noTelpBnj">Banjarmasin</label>
                                            <input type="text" class="form-control" name="noTelpBnj" value="{{ $s -> noTelpBnj }}" readonly>
                                        </div>
                                        <div class="col">
                                            <label for="noTelpJkt">Jakarta</label>
                                            <input type="text" class="form-control" name="noTelpJkt" value="{{ $s -> noTelpJkt }}" readonly>
                                        </div>
                                    </div> --}}
                                    <div class="form-group">
                                        <label for="supplierNote">Note</label>
                                        <textarea class="form-control" id="supplierNote" name="supplierNote" rows="10">{{ $s -> supplierNote }}</textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </main>
    </div>

    <style>
        th{
            color: white;
        }
        th, td{
            word-wrap: break-word;
            min-width: 160px;
            max-width: 160px;
            text-align: center;
        }
        .center {
            margin-left: auto;
            margin-right: auto;
        }
        .fa-star{
            font-size: 20px;
        }
        .fa-star.checked{
            color: #ffe400;
        }
        .rating-css {
            color: #ffe400;
            font-size: 20px;
            font-family: sans-serif;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
        }
        /* .rating-css input {
            display: none;
        } */
        .rating-css input + label {
            font-size: 20px;
            text-shadow: 1px 1px 0 #8f8420;
            cursor: pointer;
        }
        .rating-css input:checked + label ~ label {
            color: #b4afaf;
        }
        .rating-css label:active {
            transform: scale(0.8);
            transition: 0.3s ease;
        }
        .scrolling-wrapper{
            overflow-y: auto;
            max-height: 800px;
        }
        .card-block{
            background-color: #fff;
            background-position: center;
            background-size: cover;
            border-radius: 24px;
            transition: all 0.2s ease-in-out !important;
            &:hover{
                transform: translateY(-5px);
                box-shadow: none;
                opacity: 0.9;
            }
        }
        .alert{
            text-align: center;
        }
        .modal-backdrop {
            height: 100%;
            width: 100%;
        }
        @media (min-width: 300px) and (max-width: 768){
            .smaller-screen-size{
                width: 150px;
                word-break: break-all;
                font-size: 12px;
            }
            .fa-star{
                font-size: 14px;
            }
        }
    </style>

    <script type="text/javascript">
        setTimeout(function() {
            $('.alert').fadeOut('fast');
        }, 3000);

        function myFunction() {
            var input, filter, cards, cardContainer, title, i;
            
            input = document.getElementById("myFilter");
            filter = input.value.toUpperCase();
            cardContainer = document.getElementById("mySupplier");
            cards = cardContainer.getElementsByClassName("card");
            
            for (i = 0; i < cards.length; i++) {
                title = cards[i].querySelector(".supplier-name");
                code = cards[i].querySelector(".supplier-code");
                pic = cards[i].querySelector(".supplier-pic");
                if (title.innerText.toUpperCase().indexOf(filter) > -1 || code.innerText.toUpperCase().indexOf(filter) > -1 || pic.innerText.toUpperCase().indexOf(filter) > -1) {
                    cards[i].style.display = "";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/fontawesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" />

    @endsection
{{-- @else
    @include('../layouts/notAuthorized')
@endif --}}