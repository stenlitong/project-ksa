@if(Auth::user()->hasRole('crew'))
    @extends('../layouts.base')

    @section('title', 'Crew Order')

    @section('container')
    <div class="row">
        @include('crew.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                <h1 style="margin-left: 40%">Create Order</h1>
                <br>
                @if (session('status'))
                    <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                        {{session('status')}}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        {{session('error')}}
                    </div>
                @endif

                @if (session('errorCart'))
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        {{session('errorCart')}}
                    </div>
                @endif
                
                @error('quantity')
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        Quantity Invalid
                    </div>
                @enderror

                @error('tugName')
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        Nama Tug Invalid
                    </div>
                @enderror

                @error('bargeName')
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        Nama Barge Invalid
                    </div>
                @enderror

                <div class="row">
                    <div class="col">
                        <form method="POST" action="/crew/{{ Auth::user()->id }}/add-cart">
                            @csrf
                            <div class="d-flex justify-content-around ml-3 mr-3">
                                <div class="form-group p-2">
                                    <label for="item_id" class="mt-3 mb-3">Item</label>
                                    <br>
                                    <select class="form-control" name="item_id" id="item_id" style="width: 400px; height:50px;">
                                        @foreach($items as $i)
                                            <option value="{{ $i -> id }}">{{ $i -> itemName }}</option>
                                        @endforeach
                                    </select>
                                </div>
            
                                <div class="form-group p-2">
                                    <label for="department" class="mt-3 mb-3">Department</label>
                                    <br>
                                    <select class="form-control" name="department" id="department" style="width: 400px; height:50px;">
                                        <option value="deck">Deck</option>
                                        <option value="mesin">Mesin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-around ml-3 mr-3">
                                <div class="form-group p-2">
                                    <label for="quantity" class="mt-3 mb-3">Quantity</label>
                                    <input name="quantity" type="text" class="form-control" id="quantity" placeholder="Input quantity dalam angka..."
                                        style="width: 400px; height: 50px">
                                </div>
                            </div>
            
                            <br>
                            <div class="d-flex ml-3 justify-content-center">
                                {{-- Add Item To Cart --}}
                                <button type="submit" class="btn btn-success mr-3" style="">Add To Cart</button>
                                {{-- <a class="btn btn-primary ml-3" style="">Submit Order</a> --}}
                                
                                {{-- Submit Cart To Order --}}
                                {{-- <a href="/crew/{{ Auth::user()->id }}/submit-order" class="btn btn-primary ml-3" style="">Submit Order</a> --}}
                                
                                {{-- Modal --}}
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#submit-order">Submit Order</button>

                            </div>
                        </form>
                    </div>
                    <div class="col mt-5 table-wrapper-scroll-y my-custom-scrollbar tableFixHead">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carts as $c)
                                    <tr>
                                        <td>{{ $c -> item -> itemName }}</td>
                                        <td>{{ $c -> quantity }} {{ $c -> item -> unit }}</td>
                                        <td>{{ $c -> department }}</td>
                                        {{-- Delete Item --}}
                                        <form method="POST" action="/crew/{{ $c -> id }}/delete">
                                            @csrf
                                            @method('delete')
                                            <td><button class="btn btn-danger">Delete Item</button></td>
                                        </form>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <div class="modal fade" id="submit-order" tabindex="-1" role="dialog" aria-labelledby="submit-orderTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submitTitle">Input Nama Kapal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/crew/{{ Auth::user()->id }}/submit-order">
                    @csrf
                    <div class="modal-body"> 
                        <div class="row">
                            <div class="col">
                                <label>Tug<input list="tugName" name="tugName" class="mt-3 mb-3" style="width: 200px; height:45px"/></label>
                                <datalist id="tugName">
                                    @foreach($tugs as $t)
                                        <option value="{{ $t -> tugName }}">{{ $t -> tugName }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="col">
                                <label>Barge (Optional)<input list="bargeName" name="bargeName" class="mt-3 mb-3" style="width: 200px; height:45px"/></label>
                                <datalist id="bargeName">
                                    <option value="">None</option>
                                    @foreach($barges as $b)
                                    <option value="{{ $b -> bargeName }}">{{ $b -> bargeName }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

    <style>
        td{
            word-wrap: break-word;
            min-width: 100px;
            max-width: 160px;
        }
        .tableFixHead          { overflow: auto; height: 250px; }
        .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }

        .my-custom-scrollbar {
            position: relative;
            height: 550px;
            overflow: auto;
        }
        .table-wrapper-scroll-y {
            display: block;
        }
        .alert{
                text-align: center;
            }
    </style>

    @endsection
@else
    @include('../layouts/notAuthorized')
@endif