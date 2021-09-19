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
            
            @error('quantity')
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    Quantity Invalid
                </div>
            @enderror

            <form method="POST" action="{{ Route("crew.order") }}">
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
                        <label for="departmentName" class="mt-3 mb-3">Department</label>
                        <br>
                        <select class="form-control" name="departmentName" id="departmentName" style="width: 400px; height:50px;">
                            <option value="deck">Deck</option>
                            <option value="mesin">Mesin</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-around ml-3 mr-3">
                    <div class="form-group p-2">
                        <label for="quantity" class="mt-3 mb-3">Quantity</label>
                        <input name="quantity" type="text" class="form-control" id="quantity" placeholder="Enter quantity"
                            style="width: 400px">
                    </div>

                    <div class="form-group p-2">
                        <label for="satuan" class="mt-3 mb-3">Satuan</label>
                        <br>
                        <select class="form-control" id="satuan" name="satuan" style="width: 400px; height:50px;">
                            <option value="MTR">MTR</option>
                            <option value="LTR">LTR</option>
                            <option value="PCS">PCS</option>
                        </select>
                    </div>
                </div>

                <br>

                <button type="submit" class="btn btn-primary" style="margin-left: 44%">Submit Order</button>
            </form>
        </div>
    </main>
</div>
@endsection
