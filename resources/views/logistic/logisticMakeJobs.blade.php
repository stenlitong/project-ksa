@if(Auth::user()->hasRole('logistic'))
    @extends('../layouts.base')

    @section('title', 'Logistic Make Jobs')

    @section('container')
    <div class="row">
        @include('logistic.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3 wrapper">
                <h1 class="mt-3" style="text-align: center">Create Job</h1>
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
                
                @error('item_id')
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        Nama Barang
                    </div>
                @enderror

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

                @error('department')
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        Nama Department Invalid
                    </div>
                @enderror
                
                @error('golongan')
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        Golongan Invalid
                    </div>
                @enderror

                @error('orderType')
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        Tipe Order Invalid
                    </div>
                @enderror
                
                <div class="row">
                    {{-- <div> --}}
                    <div class="col">
                        <form method="POST" action="/logistic/{{ Auth::user()->id }}/add-cart">
                            @csrf
                            <div class="form-group p-2">
                                <label for="item_id" class="mt-3 mb-3">Jasa</label>
                                <br>
                                <input type="text"class="form-control" name="item_id" id="item_id" style="height:50px;" placeholder="Input nama Jasa" required>
                            </div>
                            <div class="form-group p-2">
                                <label for="tgl_request" class="mb-3">Tanggal Permintaan</label>
                                <input name="tgl_request" type="date" class="form-control" id="tgl_request" placeholder="Input Tanggal Permintaan..."
                                    style="height: 50px" required>
                            </div>
                            <div class="form-group p-2">
                                <label for="Lokasi" class="mb-3">Lokasi</label>
                                <br>
                                <select class="form-control" name="Lokasi" id="Lokasi" style="height:50px;" required>
                                    <option value="None" disabled selected>Pilih Cabang</option>
                                    <option value="Jakarta" id="Jakarta">Jakarta</option>
                                    <option value="Banjarmasin" id="Banjarmasin">Banjarmasin</option>
                                    <option value="Samarinda" id="Samarinda">Samarinda</option>
                                    <option value="Bunati" id="Bunati">Bunati</option>
                                    <option value="Babelan" id="Babelan">Babelan</option>
                                    <option value="Berau" id="Berau">Berau</option>
                                    <option value="Kendari" id="Kendari">Kendari</option>
                                </select>
                            </div>
                            <div class="form-group p-2">
                                <label for="note">Note</label>
                                <br>
                                <textarea class="form-control" name="note" Note="3"
                                    placeholder="Input Deskripsi Jasa" style="height: 100px" required autofocus></textarea>
                            </div>

                            <br>
                            <div class="d-flex ml-3 justify-content-center">
                                {{-- Add Item To Cart --}}
                                <button type="submit" class="btn btn-success mr-3" style="">Add To Cart</button>
                                
                                {{-- Modal --}}
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#submit-order">Submit Order</button>

                            </div>
                        </form>
                    </div>
                    <div class="col mt-5 mr-3 table-wrapper-scroll-y my-custom-scrollbar tableFixHead">
                        <table class="table">
                            <thead class="thead bg-danger">
                                <tr>
                                    <th scope="col">Nomor</th>
                                    <th scope="col">Nama Jasa</th>
                                    <th scope="col">Tanggal Permintaan</th>
                                    <th scope="col">Lokasi Perbaikan</th>
                                    <th scope="col">Note</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carts as $key => $c)
                                    <tr>
                                        <td class="bg-white">{{ $key + 1 }}</td>
                                        <td class="bg-white">{{ $c -> item -> itemName }}</td>
                                        <td class="bg-white">{{ $c -> quantity }} {{ $c -> item -> unit }}</td>
                                        <td class="bg-white">{{ $c -> department }}</td>
                                        <td class="bg-white">{{ $c -> note }}</td>
                                        {{-- Delete Item --}}
                                        <form method="POST" action="/logistic/{{ $c -> id }}/deletejasa">
                                            @csrf
                                            @method('delete')
                                            <td class="bg-white"><button class="btn btn-warning btn-sm">Delete Item</button></td>
                                        </form>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- </div> --}}
                </div>
            </div>
        </main>

        <div class="modal fade" id="submit-order" tabindex="-1" role="dialog" aria-labelledby="submit-orderTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title ml-3" id="submitTitle" style="color: white">Input PR Requirements</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/logistic/{{ Auth::user()->id }}/submit-order">
                    @csrf
                    <div class="modal-body"> 
                        <div class="form-group p-2">
                            <label for="tugs">Pilih Tug:</label>
                            <select class="form-control" name="tugName" id="tugName">
                                @foreach($tugs as $t)
                                    <option value="{{ $t -> tugName }}">{{ $t -> tugName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group p-2">
                            <label for="bargeName">Pilih Barge (optional):</label>
                            <select class="form-control" name="bargeName" id="bargeName">
                                <option value="">None</option>
                                @foreach($barges as $b)
                                    <option value="{{ $b -> bargeName }}">{{ $b -> bargeName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group p-2">
                            <label for="company" class="mb-3">Perusahaan</label>
                            <select class="form-control" name="company" id="company">
                                <option value="KSA">KSA</option>
                                <option value="ISA">ISA</option>
                                <option value="KSAO">KSA OFFSHORE</option>
                                <option value="KSAM">KSA MARITIME</option>
                            </select>
                        </div>
                        
                        <div class="form-group p-2">
                            <label for="descriptions" class="mb-3">Deskripsi (optional)</label>
                            <textarea class="form-control" name="descriptions" id="descriptions" placeholder="Input Deskripsi..." rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary mr-3">Submit</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(function() {
            $('.alert').fadeOut('fast');
        }, 3000); 
    </script>

    <style>
        body{
            /* background-image: url('/images/logistic-background.png'); */
            background-repeat: no-repeat;
            background-size: cover;
        }
        .wrapper{
            padding: 10px;
            border-radius: 10px;
            background-color: antiquewhite;
            height: 1100px;
            /* height: 100%; */
        }
        label{
            font-weight: bold;
        }
        th{
            color: white;
        }
        td, th{
            word-wrap: break-word;
            min-width: 100px;
            max-width: 120px;
            text-align: center;
        }
        .tableFixHead          { overflow: auto; height: 250px; }
        .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }

        .my-custom-scrollbar {
            position: relative;
            height: 700px;
            overflow: auto;
        }
        .table-wrapper-scroll-y {
            display: block;
        }
        .alert{
                text-align: center;
        }
        .modal-backdrop {
            height: 100%;
            width: 100%;
        }
        @media (max-width: 768px) {
        #row-wrapper{
            overflow-x: auto;
        }
    </style>
    @endsection
@else
    @include('../layouts/notAuthorized')
@endif