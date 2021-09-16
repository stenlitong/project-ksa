@extends('../layouts.base')

@section('title', 'Crew Order')

@section('container')
<div class="row">
    @include('crew.sidebar')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
            <h1 style="margin-left: 40%">Create Order</h1>

            <form method="POST" action="{{ Route("crew.order") }}">
                @csrf
                <div class="d-flex justify-content-around ml-3 mr-3">
                    <div class="form-group p-2">
                        <label for="itemName" class="mt-3 mb-3">Item</label>
                        <br>
                        <select class="custom-select" id="itemName" style="width: 400px; height:50px;">
                            <option selected>Choose...</option>
                            @foreach($items as $i)
                                <option value="{{ $i -> itemName }}">{{ $i -> itemName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group p-2">
                        <label for="departmentName" class="mt-3 mb-3">Department</label>
                        <br>
                        <select class="custom-select" id="departmentName" style="width: 400px; height:50px;">
                            <option selected>Choose...</option>
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
                        <label for="satuan" class="mt-3 mb-3">Item</label>
                        <br>
                        <select class="custom-select" id="satuan" style="width: 400px; height:50px;">
                            <option selected>Choose...</option>
                            <option value="MTR">MTR</option>
                            <option value="LTR">LTR</option>
                            <option value="PCS">PCS</option>
                        </select>
                    </div>
                </div>

                <br>

                <button type="submit" class="btn btn-primary" style="margin-left: 45%">Submit Order</button>
            </form>
        </div>

    </main>

</div>
@endsection
