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

        @error('itemName')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Nama Barang Invalid
        </div>
        @enderror
        
        @error('itemAge')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Umur Barang Invalid
        </div>
        @enderror

        @error('itemStock')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Stok Barang Invalid
        </div>
        @enderror

        @error('serialNo')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Serial Number Invalid
        </div>
        @enderror
        
        @error('codeMasterItem')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Code Master Item Invalid
        </div>
        @enderror

        @if(Auth::user()->hasRole('adminLogistic'))
            <!-- Button trigger modal #1 -->
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addItem">
                Add Item +
            </button>
        @endif

        <div class="row">
            <div class="col-md-6">
                <form action="">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search Item by Nama, Cabang, Kode Barang..." name="search" id="search">
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
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="itemAge">Umur Barang</label>
                                        <input type="text" class="form-control" id="itemAge" name="itemAge"
                                            placeholder="Input Umur Barang Dalam Angka">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="umur">Bulan/Tahun</label>
                                        <select class="form-control" id="umur" name="umur">
                                            <option value="Bulan">Bulan</option>
                                            <option value="Tahun">Tahun</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="itemStock">Stok Barang</label>
                                        <input type="text" class="form-control" id="itemStock" name="itemStock"
                                            placeholder="Input Stok Barang">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Unit<input list="unit" name="unit" class="mt-2" style="width: 400px; height:40px"/></label>
                                        <datalist id="unit">
                                        <option value="Bks">
                                        <option value="Btg">
                                        <option value="Btl">
                                        <option value="Cm">
                                        <option value="Crt">
                                        <option value="Cyl">
                                        <option value="Doz">
                                        <option value="Drm">
                                        <option value="Duz">
                                        <option value="Gln">
                                        <option value="Jrg">
                                        <option value="Kbk">
                                        <option value="Kg">
                                        <option value="Klg">
                                        <option value="Ktk">
                                        <option value="Lbr">
                                        <option value="Lgt">
                                        <option value="Ls">
                                        <option value="Ltr">
                                        <option value="Mtr">
                                        <option value="Pak">
                                        <option value="Pal">
                                        <option value="Pax">
                                        <option value="Pc">
                                        <option value="Pcs">
                                        <option value="Plt">
                                        <option value="Psg">
                                        <option value="Ptg">
                                        <option value="Ret">
                                        <option value="Rol">
                                        <option value="Sak">
                                        <option value="SET">
                                        <option value="Tbg">
                                        <option value="Trk">
                                        <option value="Unt">
                                        <option value="Zak">
                                        </datalist>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="serialNo">Serial Number / Part Number (optional)</label>
                                <input type="text" class="form-control" id="serialNo" name="serialNo"
                                    placeholder="Input Serial Number">
                            </div>
                            <div class="form-group">
                                <label for="codeMasterItem">Code Master Item</label>
                                <input type="text" class="form-control" id="codeMasterItem" name="codeMasterItem"
                                    placeholder="Input Code Master Item (xx-xxxx-)">
                            </div>
                            <div class="form-group">
                                <label for="cabang">Cabang</label>
                                <select class="form-control" id="cabang" name="cabang">
                                    <option selected disabled="">Choose...</option>
                                    <option value="Jakarta" id="Jakarta">Jakarta</option>
                                    <option value="Banjarmasin" id="Banjarmasin">Banjarmasin</option>
                                    <option value="Samarinda" id="Samarinda">Samarinda</option>
                                    <option value="Bunati" id ="Bunati">Bunati</option>
                                    <option value="Babelan"id ="Babelan">Babelan</option>
                                    <option value="Berau" id ="Berau">Berau</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Deskripsi (optional)</label>
                                <textarea class="form-control" name="description" id="description" rows="3"
                                    placeholder="Input Deskripsi Barang"></textarea>
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
                <div class="card-header d-inline-flex justify-content-between">
                    <h5>
                        {{ $i -> itemName }}
                    </h5>
                    <h5>
                        Kode Barang # {{ $i -> codeMasterItem }}
                    </h5>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Stok : {{ $i -> itemStock }} {{ $i -> unit }}</h5>
                    <p class="card-text">Umur Barang : {{ $i -> itemAge }}</p>
                    @if(Auth::user()->hasRole('adminLogistic'))
                        <p class="card-text">Cabang : {{ $i -> cabang }}</p>
                    @endif
                    <p class="card-text d-inline">Deskripsi : {{ $i -> description }}</p>

                    @if(Auth::user()->hasRole('adminLogistic'))
                        <!-- Button trigger modal #2 -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" id="detail" style="margin-left: 90%" data-target="#editItem-{{ $i->id }}">
                            Edit Item
                        </button>
                    @endif
                    
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
                                        placeholder="Input Nama Barang" value="{{ $i -> itemName }}">
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="itemAge">Umur Barang</label>
                                            <input type="text" class="form-control" id="itemAge" name="itemAge"
                                                placeholder="Input Umur Barang Dalam Angka">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="umur">Bulan/Tahun</label>
                                            <select class="form-control" id="umur" name="umur">
                                                <option value="Bulan">Bulan</option>
                                                <option value="Tahun">Tahun</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="itemStock">Stok Barang</label>
                                            <input type="text" class="form-control" id="itemStock" name="itemStock"
                                                placeholder="Input Stok Barang" value="{{ $i -> itemStock }}">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Unit<input list="unit" name="unit" class="mt-2" style="width: 400px; height:40px"/></label>
                                            <datalist id="unit">
                                            <option value="Bks">
                                            <option value="Btg">
                                            <option value="Btl">
                                            <option value="Cm">
                                            <option value="Crt">
                                            <option value="Cyl">
                                            <option value="Doz">
                                            <option value="Drm">
                                            <option value="Duz">
                                            <option value="Gln">
                                            <option value="Jrg">
                                            <option value="Kbk">
                                            <option value="Kg">
                                            <option value="Klg">
                                            <option value="Ktk">
                                            <option value="Lbr">
                                            <option value="Lgt">
                                            <option value="Ls">
                                            <option value="Ltr">
                                            <option value="Mtr">
                                            <option value="Pak">
                                            <option value="Pal">
                                            <option value="Pax">
                                            <option value="Pc">
                                            <option value="Pcs">
                                            <option value="Plt">
                                            <option value="Psg">
                                            <option value="Ptg">
                                            <option value="Ret">
                                            <option value="Rol">
                                            <option value="Sak">
                                            <option value="SET">
                                            <option value="Tbg">
                                            <option value="Trk">
                                            <option value="Unt">
                                            <option value="Zak">
                                            </datalist>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="serialNo">Serial Number / Part Number (optional)</label>
                                    <input type="text" class="form-control" id="serialNo" name="serialNo"
                                        placeholder="Input Serial Number" value="{{ $i -> serialNo }}">
                                </div>
                                <div class="form-group">
                                    <label for="codeMasterItem">Code Master Item</label>
                                    <input type="text" class="form-control" id="codeMasterItem" name="codeMasterItem"
                                        placeholder="Input Code Master Item (xx-xxxx-)" value="{{ $i -> codeMasterItem }}">
                                </div>
                                <div class="form-group">
                                    <label for="description">Deskripsi (optional)</label>
                                    <textarea class="form-control" name="description" id="description" rows="3"
                                        placeholder="Input Deskripsi Barang"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Edit Item</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </main>
@endsection
