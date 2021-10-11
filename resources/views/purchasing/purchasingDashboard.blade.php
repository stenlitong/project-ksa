@if(Auth::user()->hasRole('purchasing'))
    @extends('../layouts.base')

    @section('title', 'Purchasing Dashboard')

    @section('container')
    <div class="row">
        @include('purchasing.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @include('../layouts/time')

            <div class="row">
                <div class="col" style="max-width: 850px">
                    <h2 class="mt-3 mb-4" style="text-align: center">Supplier</h2>

                    @if(session('status'))
                        <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row flex-row flex-nowrap scrolling-wrapper">
                        @foreach($suppliers as $s)
                            <div class="card border-dark w-75 mr-3">
                                <div class="card-body mr-3">
                                <div class="row">
                                    <div class="col ml-2">
                                        <img src="/images/profile.png" style="height: 150px; width: 150px;">
                                        <p style="font-size: 200%; max-width: 270px"><strong>{{ $s -> supplierName }}</strong></p>
                                        <p style="font-size: 125%; max-width: 270px">(+62) {{ $s -> noTelp }}</p>
                                        <p style="font-size: 125%; max-width: 270px">{{ $s -> supplierEmail }}</p>
                                    </div>
                                    <div class="col" style="margin-left: -100px ">
                                        <div class="d-flex justify-content-between ratings">
                                            <h4>Quality</h4>
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
                                            <h4>Top</h4>
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
                                            <h4>Price</h4>
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
                                            <h4>Delivery Time</h4>
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
                                            <h4>Item<br>Availability</h4>
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
                    </div>
                </div>

                <div class="col">
                    <h2 class="mt-3 mb-4" style="text-align: center;">Order List</h2>
                    
                    @if(session('orderStatus'))
                        <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                            {{ session('status') }}
                        </div>
                    @endif

                    @error('reason')
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        Alasan Wajib Diisi
                    </div>
                    @enderror

                        <div class="d-flex flex-row justify-content-between">
                            <form class="" action="">
                                <div class="input-group mb-3 ">
                                    <input type="text" class="form-control" placeholder="Search by Order ID or Status..." name="search" id="search" style="width: 250px">
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
                                @if(strpos($oh -> status, 'Rejected') !== false)
                                    <td style="color: red">{{ $oh -> status}}</td>
                                @elseif(strpos($oh -> status, 'Completed') !== false)
                                    <td style="color: green">{{ $oh -> status}}</td>
                                @elseif(strpos($oh -> status, 'Approved') !== false)
                                    <td style="color: #8B8000">{{ $oh -> status}}</td>
                                @else
                                    <td>{{ $oh -> status }}</td>
                                @endif
                                <td>
                                    {{-- Modal button for order details --}}
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#detail-{{ $oh -> id }}">Detail</button>
                                    @if(strpos($oh -> status, 'Approved') !== false)
                                        <a href="/purchasing/{{ $oh -> id }}/download-po" class="btn btn-warning" target="_blank">Download PO</a>
                                    @endif
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
                                    <h5>Nomor PR : {{ $o -> noPr }}</h5>
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
                                                        <td>{{ $od -> quantity }} {{ $od -> item -> unit }}</td>
                                                        <td>{{ $od -> department }}</td>
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
                                        <a href="/purchasing/order/{{ $o->id }}/approve" class="btn btn-primary">Approve</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
            @endforeach
            
            {{-- Modal for edit supplier ratings --}}
            @foreach($suppliers as $s)
                <div class="modal fade" id="edit-rating-{{ $s -> id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ $s -> supplierName }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <form method="POST" action="/purchasing/{{ $s -> id }}/edit">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group d-flex justify-content-between ratings">
                                    <h4>Quality</h4>
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
                                    <h4>Top</h4>
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
                                    <h4>Price</h4>
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
                                    <h4>Delivery Time</h4>
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
                                    <h4>Availability</h4>
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
            @endforeach

        </main>

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
                    <form method="POST" action="/purchasing/order/{{ $oh->id }}/reject">
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

    <style>
        th, td{
            word-wrap: break-word;
            min-width: 100px;
            max-width: 160px;
            text-align: center;
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
            overflow-x: auto;
        }
        .card-block{
        background-color: #fff;
        background-position: center;
        background-size: cover;
        transition: all 0.2s ease-in-out !important;
        &:hover{
            transform: translateY(-5px);
            box-shadow: none;
            opacity: 0.9;
        }
    }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/fontawesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" />

    @endsection
@else
    @include('../layouts/notAuthorized')
@endif