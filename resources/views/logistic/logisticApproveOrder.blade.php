@extends('../layouts.base')

@section('title', 'Logistic Dashboard')

@section('container')
<div class="row">
    @include('logistic.sidebar')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="mt-5 mb-5">
            <h2 class="text-center mb-5">Create Purchase Requisition</h2>
            <form>
                <div class="form-row justify-content-between">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Nama Kapal</label>
                        <input type="email" class="form-control" id="inputEmail4" placeholder="Nama Kapal">
                    </div>
                    {{-- <div class="form-group col-md-5">
                        <label for="inputState">Department</label>
                        <select id="inputState" class="form-control">
                            <option>Deck</option>
                            <option>Mesin</option>
                        </select>
                    </div> --}}
                </div>
                <div class="form-row justify-content-between">
                    <div class="form-group col-md-3">
                        <label for="inputEmail4">Department</label>
                        <input type="text" class="form-control" id="inputEmail4" placeholder="deck" readonly>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputCity">Perusahaan</label>
                        <input type="text" class="form-control" id="inputCity" placeholder="Ex : ISA">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputState">Daerah</label>
                        <select id="inputState" class="form-control">
                            <option>Jakarta</option>
                            <option>Samarinda</option>
                            <option>Banjarmasin</option>
                            <option>Maluku</option>
                            <option>Medan</option>
                            <option>Bunati</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputZip">Item Barang</label>
                        <input type="text" class="form-control" id="inputZip" placeholder="Ex : Tali">
                    </div>
                </div>
                <div class="form-row justify-content-between">
                    <div class="form-group col-md-3">
                        <label for="inputEmail4">Tanggal PR</label>
                        <input type="date" class="form-control" id="inputEmail4" placeholder="Email">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputEmail4">Serial Number / Part Number</label>
                        <input type="text" class="form-control" id="inputEmail4" placeholder="">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputEmail4">Quantity</label>
                        <input type="text" class="form-control" id="inputEmail4" placeholder="12 PCS" readonly>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputEmail4">Code Master Item</label>
                        <input type="text" class="form-control" id="inputEmail4" placeholder="">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputAddress2">Note</label>
                        <textarea class="form-control" name="reason" id="reason" rows="3"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </main>
</div>

@endsection
