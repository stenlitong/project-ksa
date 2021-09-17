@extends('../layouts.base')

@section('title', 'Logistic Stocks')

@section('container')
@include('logistic.sidebar')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-5">
        <h1 class="mb-3">Edit Item</h1>
            <form method="POST" action="/logistic/stocks/{{ $items->id }}/edit">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="itemName">Item Name</label>
                    <input type="text" class="form-control" id="itemName" name="itemName"
                        placeholder="Input Item's Name" value="{{ $items->itemName }}">
                </div>
                <div class="form-group">
                    <label for="itemAge">Item Age</label>
                    <input type="text" class="form-control" id="itemAge" name="itemAge"
                        placeholder="Input Item's Age in Month">
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
                <button type="submit" class="btn btn-primary">Save Item</button>
            </form>
    </main>
@endsection
