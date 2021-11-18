@if(Auth::user()->hasRole('purchasing') || Auth::user()->hasRole('purchasingManager'))
    @extends('../layouts.base')

    @section('title', 'Purchasing Approve Order')

    @section('container')
    <div class="row">
        @include('purchasing.sidebar')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

                <h2 class="mt-3" style="text-align: center">Order {{ $orderHeads -> order_id }}</h2>
                
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
                
                <div class="row mt-4">
                    <div class="col">
                        <div class="form-group">
                            <label for="approvedBy">Approved By</label>
                            <input type="text" class="form-control" id="boatName" name="boatName" value="{{ $orderHeads -> approvedBy }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="boatName">Nama Kapal</label>
                            <input type="text" class="form-control" id="boatName" name="boatName" value="{{ $orderHeads -> boatName }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="noPr">Nomor Purchase Requisition</label>
                            <input type="text" class="form-control" id="noPr" name="noPr" value="{{ $orderHeads -> noPr }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="noPo">Nomor Purchase Order</label>
                            <input type="text" class="form-control" id="noPo" name="noPo" value="{{ $orderHeads -> noPo }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="invoiceAddress">{{ $orderHeads -> invoiceAddress }}</label>
                            <input type="text" class="form-control" id="invoiceAddress" name="invoiceAddress" value="{{ $orderHeads -> invoiceAddress }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="itemAddress">{{ $orderHeads -> itemAddress }}</label>
                            <input type="text" class="form-control" id="itemAddress" name="itemAddress" value="{{ $orderHeads -> itemAddress }}" readonly>
                        </div>
                        @php
                            if($orderHeads -> ppn == 10){
                                $ppn_val = 'PPN (10%)';
                            }else{
                                $ppb_val = 'NON PPN';
                            }
                        @endphp
                        <div class="form-group">
                            <label for="ppn">PPN</label>
                            <input type="text" class="form-control" id="ppn" name="ppn" value="{{ $ppn_val }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="discount">Discount (%)</label>
                            <input type="text" class="form-control" id="discount" name="discount" value="{{ $orderHeads -> discount }}%" readonly>
                        </div>
                        <div class="form-group">
                            <label for="price">Total Harga (setelah ppn & diskon)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-white">Rp.</div>
                                </div>
                                <input type="text" class="form-control" id="totalPrice" name="totalPrice" value="{{ number_format($orderHeads -> totalPrice, 2, ",", ".") }}" readonly>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-5">
                            <form method="POST" action="/purchasing-manager/order/{{ $orderHeads -> id }}/reject">
                                @method('patch')
                                @csrf
                                <button type="submit" class="btn btn-danger mr-3">Reject</button>
                            </form>
                                {{-- <a href="/purchasing-manager/order/1/approve" class="btn btn-danger mr-3">Reject</a> --}}
                            <form method="POST" action="/purchasing-manager/order/{{ $orderHeads -> id }}/approve">
                                @csrf
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                    <div class="col mt-3">
                        <table class="table" id="myTable">
                            <thead class="thead bg-danger">
                                    <th scope="col">Item Barang</th>
                                    <th scope="col" class="center">Quantity</th>
                                    <th scope="col">Harga per Barang</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Supplier</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderDetails as $od)
                                    <tr>
                                        <td>
                                            <h5>{{ $od -> item -> itemName }} - ({{ $od -> department }})</h5>
                                        </td>

                                        <td class="center">
                                            <h5>{{ $od -> quantity }} {{ $od -> item -> unit }}</h5>
                                        </td>


                                            <td>
                                                <div class="d-flex">
                                                    <h5 class="mr-2">Rp. {{ $od -> itemPrice }}</h5>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <h5>Rp. {{ number_format($od -> totalItemPrice, 2, ",", ".")}}</h5>
                                            </td>
                                            
                                            <td>
                                                <h5>
                                                    {{ $od -> supplier_id }}
                                                </h5>
                                            </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
    </div>

    <script type="text/javascript">
        setTimeout(function() {
            $('.alert').fadeOut('fast');
        }, 3000);
    </script>

    <style>
        h5{
            font-size: 16px;
        }
        label{
            font-weight: bold;
        }
        th{
            color: white;
        }
        td, th{
            word-wrap: break-word;
            min-width: 120px;
            max-width: 120px;
            text-align: left;
            vertical-align: middle;
        }
        .center{
            text-align: center;
        }
        .alert{
                text-align: center;
            }
    </style>

    @endsection
@else
    @include('../layouts/notAuthorized')
@endif