@extends('../layouts.base')

@section('title', 'Logistic Stocks')

@section('container')
<div class="row">
    @include('logistic.sidebar')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-5">
        <h1 class="mb-3" style="margin-left: 40%">Stock Availability</h1>

        <br>
        @if(session('status'))
            <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                {{ session('status') }}
            </div>
        @endif

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModalCenter">
            Add Item +
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Add New Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ Route("logistic.stocks") }}">
                            @csrf
                            <div class="form-group">
                                <label for="itemName">Item Name</label>
                                <input type="text" class="form-control" id="itemName" name="itemName"
                                    placeholder="Input Item's Name">
                            </div>
                            <div class="form-group">
                                <label for="itemAge">Item Age</label>
                                <input type="text" class="form-control" id="itemAge" name="itemAge"
                                    placeholder="Input Item's Age in Number">
                            </div>
                            <div class="form-group">
                                <label for="itemStock">Item Stock</label>
                                <input type="text" class="form-control" id="itemStock" name="itemStock"
                                    placeholder="Input Item's Stock in Number">
                            </div>
                            <div class="form-group">
                                <label for="satuan">Satuan</label>
                                <select class="form-control" id="satuan" name="satuan" onfocus='this.size=5;'
                                    onblur='this.size=1;' onchange='this.size=1; this.blur();'>
                                    <option value="MTR">MTR</option>
                                    <option value="LTR">LTR</option>
                                    <option value="PCS">PCS</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="3"
                                    placeholder="Input Item's Description"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Add Item</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @foreach($items as $i)
        <div class="card mt-3">
            <h5 class="card-header">{{ $i -> itemName }}</h5>
            <div class="card-body">
                <h5 class="card-title">Stock : {{ $i -> itemStock }}</h5>
                <p class="card-text d-inline">Description : {{ $i -> description }}</p>
                <a href="#" class="btn btn-primary" style="margin-left: 80%">Edit Item</a>
            </div>
        </div>
        @endforeach
    </main>

    @endsection
