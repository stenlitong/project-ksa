@extends('../layouts.base')

@section('title', 'Logistic Dashboard')

@section('container')
<div class="row">
    @include('logistic.sidebar')

        @error('itemName')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Nama Barang Wajib Diisi
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

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="mt-5 mb-5">
            <h2 class="text-center mb-5">Create Purchase Requisition</h2>
            <form method="POST" action="/logistic/order/{{ $order -> id }}/approve">
                @csrf
                <div class="form-row justify-content-between">
                    <div class="form-group col-md-6">
                        <label for="boatName">Nama Kapal</label>
                        <input type="text" class="form-control" id="boatName" name="boatName" placeholder="Nama Kapal">
                    </div>
                </div>
                <div class="form-row justify-content-between">
                    <div class="form-group col-md-3">
                        <label for="department">Department</label>
                        <input type="text" class="form-control" name="department" id="department" value="{{ $order -> department }}" placeholder="{{ $order -> department }}" readonly>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="company">Perusahaan</label>
                        <input type="text" class="form-control" id="company" name="company" placeholder="Ex : ISA">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="location">Daerah</label>
                        <select id="location" name="location" class="form-control">
                            <option value="JKT">Jakarta</option>
                            <option value="SMD">Samarinda</option>
                            <option value="BNJ">Banjarmasin</option>
                            <option value="MLK">Maluku</option>
                            <option value="MDN">Medan</option>
                            <option value="BNT">Bunati</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="itemName">Item Barang</label>
                        <input type="text" class="form-control" id="itemName" name="itemName" value="{{ $order -> item-> itemName }}" placeholder="{{ $order -> item -> itemName }}" readonly>
                    </div>
                </div>
                <div class="form-row justify-content-between">
                    <div class="form-group col-md-3">
                        <label for="prDate">Tanggal PR</label>
                        <input type="date" class="form-control" id="prDate" name="prDate" placeholder="">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="serialNo">Serial Number / Part Number</label>
                        <input type="text" class="form-control" id="serialNo" name="serialNo" placeholder="">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="quantity">Quantity</label>
                        <input type="text" class="form-control" id="quantity" name="quantity" value="{{ $order -> quantity }}" placeholder="{{ $order -> quantity }}" readonly>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="codeMasterItem">Code Master Item</label>
                        <input type="text" class="form-control" id="codeMasterItem" name="codeMasterItem" placeholder="">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="note">Note</label>
                        <textarea class="form-control" name="note" id="note" rows="3"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3" style="margin-left: 45%; width: 100px">Create</button>
            </form>
        </div>
    </main>
</div>

@endsection
