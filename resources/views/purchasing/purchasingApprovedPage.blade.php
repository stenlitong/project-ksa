@extends('../layouts.base')

@section('title', 'Purchasing Approve Order')

@section('container')
<div class="row">
    @include('purchasing.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <h2 class="mt-3" style="text-align: center">Order # {{ $orderHeads -> order_id }}</h2>

        @error('boatName')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Nama Kapal Invalid
        </div>
        @enderror

        @error('noPr')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Nomor PR Invalid
        </div>
        @enderror

        @error('noPo')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Nomor PO Invalid
        </div>
        @enderror

        @error('invoiceAddress')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Alamat Pengiriman Invoice Invalid
        </div>
        @enderror

        @error('itemAddress')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Alamat Barang Invoice Invalid
        </div>
        @enderror

        @error('supplier_id')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Nama Supplier Invalid
        </div>
        @enderror

        @error('price')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Harga Invalid
        </div>
        @enderror

            <div class="row mt-4">
                <div class="col">
                    <form method="POST" action="">
                        @csrf
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
                            <input type="text" class="form-control" id="noPo" name="noPo" value="{{ $poNumber }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="invoiceAddress">Alamat Pengiriman Invoice</label>
                            <select name="invoiceAddress" id="invoiceAddress" class="form-control" onchange="showfield(this.options[this.selectedIndex].value)" required> 
                                <option value="" disabled>Choose</option>
                                <option value="Gran Rubina, Jl. HR Rasuna Said Lt.12, Karet Kuningan, Setia Budi, Jak-sel">Gran Rubina, Jl. HR Rasuna Said Lt.12, Karet Kuningan, Setia Budi, Jak-sel</option>
                                <option value="Jl. Jelawat No.23 RT.002 RW.001 Kel. Sidomulyo-Samarinda">Jl. Jelawat No.23 RT.002 RW.001 Kel. Sidomulyo-Samarinda</option>
                                <option value="Jl. Olah Bebaya 04 RW 02 Sungai Lais, Kel. Pulau Atas Kec. Sambutan Samarinda - kalimantan Timur ">Jl. Olah Bebaya 04 RW 02 Sungai Lais, Kel. Pulau Atas Kec. Sambutan Samarinda - kalimantan Timur </option>
                                <option value="Jl. Djok Mentaya no.27-28 Ruko Naga Mas, Banjarmasin">Jl. Djok Mentaya no.27-28 Ruko Naga Mas, Banjarmasin</option>
                                <option value="Jl. Provinsi KM 150 Sebamban 2 Blok C, No 07 Rt.25 Desa Sumber Baru, Kec.Angsana, Kab. TanahBumbu - Kalimantan Selatan ">Jl. Provinsi KM 150 Sebamban 2 Blok C, No 07 Rt.25 Desa Sumber Baru, Kec.Angsana, Kab. TanahBumbu - Kalimantan Selatan </option>
                                <option value="Jl. Gajah Mada no.531 RT 16 (Depan Hotel Mitra), Tanjung Redeb, Kab.Berau - Kalimantan timur">Jl. Gajah Mada no.531 RT 16 (Depan Hotel Mitra), Tanjung Redeb, Kab.Berau - Kalimantan timur</option>
                                <option value="Jl. bunga seroja no 88 E, Kendari, Sulawesi Tenggara">Jl. bunga seroja no 88 E, Kendari, Sulawesi Tenggara</option>
                                <option value="Perumahan Tre Vista residence blok A1 no 5, kelurahan kebalen, kec babelan, kab bekasi. 17610">Perumahan Tre Vista residence blok A1 no 5, kelurahan kebalen, kec babelan, kab bekasi. 17610</option>
                                <option value="Jl. Cendana Gg. Belakang PolsekPlajau Rt.08 Rw.02, Desa Bersujud, Batu licin - Kalimantan Selatan ">Jl. Cendana Gg. Belakang PolsekPlajau Rt.08 Rw.02, Desa Bersujud, Batu licin - Kalimantan Selatan </option>
                                <option value="Other">Alamat Lain, Input Manual</option>
                            </select>
                            <div id="div1"></div>
                        </div>
                        <div class="form-group">
                            <label for="itemAddress">Alamat Pengiriman Barang</label>
                            <select name="itemAddress" id="itemAddress" class="form-control" onchange="showfield2(this.options[this.selectedIndex].value)" required> 
                                <option value="" disabled>Choose</option>
                                <option value="Gran Rubina, Jl. HR Rasuna Said Lt.12, Karet Kuningan, Setia Budi, Jak-sel">Gran Rubina, Jl. HR Rasuna Said Lt.12, Karet Kuningan, Setia Budi, Jak-sel</option>
                                <option value="Jl. Jelawat No.23 RT.002 RW.001 Kel. Sidomulyo-Samarinda">Jl. Jelawat No.23 RT.002 RW.001 Kel. Sidomulyo-Samarinda</option>
                                <option value="Jl. Djok Mentaya no.27-28 Ruko Naga Mas, Banjarmasin">Jl. Djok Mentaya no.27-28 Ruko Naga Mas, Banjarmasin</option>
                                <option value="Other2">Alamat Lain, Input Manual</option>
                            </select>
                            <div id="div2"></div>
                        </div>
                        <div class="form-group">
                            <label for="supplier_id">Supplier</label>
                            <select class="form-control" id="supplier_id" name="supplier_id">
                                <option value="" disabled>Choose Supplier...</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s -> id }}">{{ $s -> supplierName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="price" class="mb-2">Harga</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Rp.</div>
                                </div>
                                <input type="text" class="form-control" id="price" name="price" placeholder="Input total harga">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descriptions">Deskripsi (optional)</label>
                            <textarea class="form-control" name="descriptions" id="descriptions" rows="3"
                                placeholder="Input Keterangan"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary mb-5" style="margin-left: 45%">Submit</button>
                    </form>
                </div>
                <div class="col mt-3">
                    <table class="table" id="myTable">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Item Barang</th>
                                <th scope="col">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderDetails as $od)
                            <tr>
                                <td>{{ $od -> item -> itemName }}</td>
                                <td>{{ $od -> quantity }} {{ $od -> item -> unit }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
</div>

<script type="text/javascript">
    function showfield(name){
        if(name == 'Other') {
            document.getElementById('div1').innerHTML = '<div class="form-group mt-3 mb-3"><input type="text" class="form-control" id="invoiceAddress" name="invoiceAddress" placeholder="Input Alamat..."></div>';
        }
        else {
            document.getElementById('div1').innerHTML='';
        }
    }
    function showfield2(name){
        if(name == 'Other2') {
            document.getElementById('div2').innerHTML = '<div class="form-group mt-3 mb-3"><input type="text" class="form-control" id="itemAddress" name="itemAddress" placeholder="Input Alamat..."></div>';
        }
        else {
            document.getElementById('div2').innerHTML='';
        }
    }
</script>

@endsection