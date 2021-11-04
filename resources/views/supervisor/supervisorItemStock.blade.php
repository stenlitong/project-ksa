@if(Auth::user()->hasRole('supervisor') || Auth::user()->hasRole('supervisorMaster'))
    @extends('../layouts.base')

    @section('title', 'Supervisor Stocks')

    @section('container')
        @include('supervisor.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-5">
            <h1 class="mb-3" style="text-align: center">Stock Availability</h1>

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

            @error('itemPrice')
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Harga Barang Invalid
            </div>
            @enderror

            @error('unit')
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Satuan Unit Invalid
            </div>
            @enderror
            
            @error('codeMasterItem')
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Code Master Item Invalid
            </div>
            @enderror

            @error('cabang')
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                Cabang Invalid
            </div>
            @enderror

            @if(Auth::user()->hasRole('supervisorMaster'))
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
                aria-hidden="true" data-backdrop="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title" id="addItemTitle" style="color: white">Add New Item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="/supervisor/item-stocks">
                                @csrf
                                <div class="form-group">
                                    <label for="itemName">Nama Barang</label>
                                    <input type="text" class="form-control" id="itemName" name="itemName"
                                        placeholder="Input Nama Barang" required>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="itemAge">Umur Barang</label>
                                            <input type="number" min="1" class="form-control" id="itemAge" name="itemAge"
                                                placeholder="Input Umur Barang Dalam Angka" required>
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
                                            <input type="number" min="1" class="form-control" id="itemStock" name="itemStock"
                                                placeholder="Input Stok Barang" required>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="unit">Unit</label>
                                            <select class="form-control" name="unit" id="unit">
                                                <option value="Bks">Bks</option>
                                                    <option value="Btg">Btg</option>
                                                    <option value="Btl">Btl</option>
                                                    <option value="Cm">Cm</option>
                                                    <option value="Crt">Crt</option>
                                                    <option value="Cyl">Cyl</option>
                                                    <option value="Doz">Doz</option>
                                                    <option value="Drm">Drm</option>
                                                    <option value="Duz">Duz</option>
                                                    <option value="Gln">Gln</option>
                                                    <option value="Jrg">Jrg</option>
                                                    <option value="Kbk">Kbk</option>
                                                    <option value="Kg">Kg</option>
                                                    <option value="Klg">Klg</option>
                                                    <option value="Ktk">Ktk</option>
                                                    <option value="Lbr">Lbr</option>
                                                    <option value="Lgt">Lgt</option>
                                                    <option value="Ls">Ls</option>
                                                    <option value="Ltr">Ltr</option>
                                                    <option value="Mtr">Mtr</option>
                                                    <option value="Pak">Pak</option>
                                                    <option value="Pal">Pal</option>
                                                    <option value="Pax">Pax</option>
                                                    <option value="Pc">Pc</option>
                                                    <option value="Pcs">Pcs</option>
                                                    <option value="Plt">Plt</option>
                                                    <option value="Psg">Psg</option>
                                                    <option value="Ptg">Ptg</option>
                                                    <option value="Ret">Ret</option>
                                                    <option value="Rol">Rol</option>
                                                    <option value="Sak">Sak</option>
                                                    <option value="SET">SET</option>
                                                    <option value="Tbg">Tbg</option>
                                                    <option value="Trk">Trk</option>
                                                    <option value="Unt">Unt</option>
                                                    <option value="Zak">Zak</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="noTelp">Harga Barang</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Rp.</div>
                                            </div>
                                            <input type="number" min="1" class="form-control" id="itemPrice" name="itemPrice" placeholder="Input harga barang dalam angka...">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="golongan">Golongan</label>
                                            <select class="form-control" id="golongan" name="golongan">
                                                <option value="None">None</option>
                                                <option value="Floating">Floating</option>
                                                <option value="Dock">Dock</option>
                                            </select>
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
                                        placeholder="Input Code Master Item (xx-xxxx-)" required>
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

            <div id="content" style="overflow-x:auto;">
            <table class="table mb-5">
                <thead class="thead bg-danger">
                    <tr>
                        <th scope="col" style="color: white">Item Barang</th>
                        <th scope="col" style="color: white">Umur Barang</th>
                        <th scope="col" style="color: white">Quantity</th>
                        <th scope="col" style="color: white">Harga Barang</th>
                        <th scope="col" style="color: white">Golongan</th>
                        <th scope="col" style="color: white">Serial Number</th>
                        <th scope="col" style="color: white">Code Master Item</th>
                        <th scope="col" style="color: white">Cabang</th>
                        <th scope="col" style="color: white">Deskripsi</th>
                        @if(Auth::user()->hasRole('supervisorMaster'))
                            <th scope="col" style="color: white">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $i)
                    <tr>
                        <td><strong>{{ $i -> itemName }}</strong></td>
                        <td>{{ $i -> itemAge }}</td>
                        <td><strong>{{ $i -> itemStock }} {{ $i -> unit }}</strong></td>
                        <td>Rp. {{ $i -> itemPrice }}</td>
                        <td>{{ $i -> golongan }}</td>
                        <td>{{ $i -> serialNo }}</td>
                        <td><strong>{{ $i -> codeMasterItem }}</strong></td>
                        <td>{{ $i -> cabang }}</td>
                        <td>{{ $i -> description }}</td>
                        @if(Auth::user()->hasRole('supervisorMaster'))
                        <td>
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-primary mr-2" data-toggle="modal" id="detail" data-target="#editItem-{{ $i->id }}">
                                    Edit
                                </button>
                                
                                {{-- <form method="POST" action="/supervisor/item-stocks/{{ $i -> id }}/delete-item" >
                                    @csrf
                                    @method('delete') --}}
                                <button class="btn btn-danger" data-toggle="modal" id="delete" data-target="#deleteItem-{{ $i->id }}">
                                    Delete
                                </button>
                                {{-- </form> --}}
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            </div>

            <!-- Modal #2 -->
            @foreach($items as $i)
                <div class="modal fade" id="editItem-{{ $i->id }}" tabindex="-1" role="dialog" aria-labelledby="editItemTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="editItemTitle" style="color: white">Edit Item: {{ $i -> itemName }} ({{ $i -> cabang }})</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="/supervisor/item-stocks/{{ $i -> id }}/edit-item">
                                    @csrf
                                    <div class="form-group">
                                        <label for="itemName">Nama Barang</label>
                                        <input type="text" class="form-control" id="itemName" name="itemName"
                                            placeholder="Input Nama Barang" value="{{ $i -> itemName }}" required>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="itemAge">Umur Barang</label>
                                                <input type="number" min="1" class="form-control" id="itemAge" name="itemAge"
                                                    placeholder="Input Umur Barang Dalam Angka" required>
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
                                                <input type="number" min="1" class="form-control" id="itemStock" name="itemStock"
                                                    placeholder="Input Stok Barang" value="{{ $i -> itemStock }}" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="unit">Unit</label>
                                                <select class="form-control" name="unit" id="unit">
                                                    <option value="Bks">Bks</option>
                                                    <option value="Btg">Btg</option>
                                                    <option value="Btl">Btl</option>
                                                    <option value="Cm">Cm</option>
                                                    <option value="Crt">Crt</option>
                                                    <option value="Cyl">Cyl</option>
                                                    <option value="Doz">Doz</option>
                                                    <option value="Drm">Drm</option>
                                                    <option value="Duz">Duz</option>
                                                    <option value="Gln">Gln</option>
                                                    <option value="Jrg">Jrg</option>
                                                    <option value="Kbk">Kbk</option>
                                                    <option value="Kg">Kg</option>
                                                    <option value="Klg">Klg</option>
                                                    <option value="Ktk">Ktk</option>
                                                    <option value="Lbr">Lbr</option>
                                                    <option value="Lgt">Lgt</option>
                                                    <option value="Ls">Ls</option>
                                                    <option value="Ltr">Ltr</option>
                                                    <option value="Mtr">Mtr</option>
                                                    <option value="Pak">Pak</option>
                                                    <option value="Pal">Pal</option>
                                                    <option value="Pax">Pax</option>
                                                    <option value="Pc">Pc</option>
                                                    <option value="Pcs">Pcs</option>
                                                    <option value="Plt">Plt</option>
                                                    <option value="Psg">Psg</option>
                                                    <option value="Ptg">Ptg</option>
                                                    <option value="Ret">Ret</option>
                                                    <option value="Rol">Rol</option>
                                                    <option value="Sak">Sak</option>
                                                    <option value="SET">SET</option>
                                                    <option value="Tbg">Tbg</option>
                                                    <option value="Trk">Trk</option>
                                                    <option value="Unt">Unt</option>
                                                    <option value="Zak">Zak</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="noTelp">Harga Satuan Barang</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input type="number" min="1" class="form-control" id="itemPrice" name="itemPrice" placeholder="Input harga barang dalam angka...">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="golongan">Golongan</label>
                                                <select class="form-control" id="golongan" name="golongan">
                                                    <option value="None">None</option>
                                                    <option value="Floating">Floating</option>
                                                    <option value="Dock">Dock</option>
                                                </select>
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
                                            placeholder="Input Code Master Item (xx-xxxx-)" value="{{ $i -> codeMasterItem }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Deskripsi (optional)</label>
                                        <textarea class="form-control" name="description" id="description" rows="3"
                                            placeholder="Input Deskripsi Barang">{{ $i -> description }}</textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Edit Item</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Modal #3 -->
            @foreach($items as $i)
                <div class="modal fade" id="deleteItem-{{ $i->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteItemTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="editItemTitle" style="color: white">Delete Item: {{ $i -> itemName }} ({{ $i -> cabang }})</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <br>
                                <h5 style="text-align: center">Are You Sure To Delete This Item ?</h5>
                                <br>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <form method="POST" action="/supervisor/item-stocks/{{ $i -> id }}/delete-item" >
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger">Delete Item</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </main>

        <style>
            th, td{
                word-wrap: break-word;
                min-width: 120px;
                max-width: 120px;
                text-align: center;
                vertical-align: middle;
            }
            .alert{
                text-align: center;
            }
            .modal-backdrop {
                height: 100%;
                width: 100%;
            }
        </style>

        <script type="text/javascript">
            function refreshDiv(){
                $('#content').load(location.href + ' #content')
            }
            setInterval(refreshDiv, 60000);

            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 3000);
        </script>

    @endsection
@else
    @include('../layouts/notAuthorized')
@endif