@extends('../layouts.base')

@section('title', 'Logistic Stocks')

@section('container')
    @include('logistic.sidebar')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-5">
        <h1 class="mb-3" style="margin-left: 40%">Stock Availability</h1>

        <br>
        @if(session('status'))
            <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                {{ session('status') }}
            </div>
        @endif

        <!-- Button trigger modal #1 -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addItem">
            Add Item +
        </button>

        <div class="row">
            <div class="col-md-6">
                <form action="">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search Item..." name="search" id="search">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="d-flex justify-content-end">
            {{ $items->links() }}
        </div>

        <!-- Modal #1 -->
        <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addItem"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addItemTitle">Add New Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ Route("logistic.stocks") }}">
                            @csrf
                            <div class="form-group">
                                <label for="itemName">Nama Barang</label>
                                <input type="text" class="form-control" id="itemName" name="itemName"
                                    placeholder="Input Nama Barang">
                            </div>
                            <div class="form-group">
                                <label for="itemAge">Umur Barang</label>
                                <input type="text" class="form-control" id="itemAge" name="itemAge"
                                    placeholder="Input Umur Barang Dalam Angka">
                            </div>
                            <div class="form-group">
                                <label for="itemStock">Stok Barang</label>
                                <input type="text" class="form-control" id="itemStock" name="itemStock"
                                    placeholder="Input Stok Barang">
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
                                <label for="description">Deskripsi</label>
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
            <div class="card mt-3 mb-5">
                <h5 class="card-header">{{ $i -> itemName }}</h5>
                <div class="card-body">
                    <h5 class="card-title">Stok : {{ $i -> itemStock }}</h5>
                    <p class="card-text d-inline">Deskripsi : {{ $i -> description }}</p>
                    <!-- Button trigger modal #2 -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" id="detail" style="margin-left: 90%" data-target="#editItem-{{ $i->id }}">
                        Edit Item
                    </button>
                    {{-- <a href="/logistic/stocks/{{ $i->id }}/edit"class="btn btn-primary" style="margin-left: 90%">Edit Item</a> --}}
                </div>
            </div>
        @endforeach
        <!-- Modal #2 -->
        @foreach($items as $i)
            <div class="modal fade" id="editItem-{{ $i->id }}" tabindex="-1" role="dialog" aria-labelledby="editItemTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editItemTitle">Edit Item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="/logistic/stocks/{{ $i->id }}/edit">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label for="itemName">Nama Barang</label>
                                    <input type="text" class="form-control" id="itemName" name="itemName"
                                        placeholder="Input Nama Barang" value="{{ $i->itemName }}">
                                </div>
                                <div class="form-group">
                                    <label for="itemAge">Umur Barang</label>
                                    <input type="text" class="form-control" id="itemAge" name="itemAge"
                                        placeholder="Input Umur Barang Dalam Angka" value="{{ $i->itemAge }}">
                                </div>
                                <div class="form-group">
                                    <label for="itemStock">Stok Barang</label>
                                    <input type="text" class="form-control" id="itemStock" name="itemStock"
                                        placeholder="Input Stok Barang">
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
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control" name="description" id="description" rows="3"
                                        placeholder="Input Item's Description"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save Item</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </main>
@endsection
