@extends('../layouts.base')

@section('title', 'Admin Purchasing Dashboard')

@section('container')
<div class="row">
    @include('adminPurchasing.sidebar')
    
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        @include('../layouts/time')

        <h2 class="mb-4" style="text-align: center">Contact Suppliers</h2>

        @if(session('status'))
            <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                {{ session('status') }}
            </div>
        @endif

        @error('supplierName')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Nama Supplier Invalid
        </div>
        @enderror

        @error('noTelp')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Nomor Telepon Invalid
        </div>
        @enderror

        @error('supplierEmail')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Email Supplier Invalid
        </div>
        @enderror

        @error('supplierAddress')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Alamat Supplier Invalid
        </div>
        @enderror

        @error('supplierNPWP')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            NPWP Supplier Invalid
        </div>
        @enderror

        <div class="row">
            <div class="col ml-4">
                <div class="row flex-column flex-nowrap scrolling-wrapper">
                    @foreach($suppliers as $s)
                        <div class="card border-dark w-100 mb-3">
                            <div class="card-body mr-3">
                            <div class="row">
                                <div class="col" style="margin-left: 5%;">
                                    <img src="/images/profile.png" style="height: 100px; width: 100px;">
                                    <h2 class="mt-3" style="max-width: 270px">{{ $s -> supplierName }}</h2>
                                </div>
                                <div class="col" style="margin-left: -150px ">
                                    <div class="d-flex mb-2">
                                        <h4 style="max-width: 250px;">(+62) {{ $s -> noTelp }}</h4>
                                    </div>
                                    <div class="d-flex mb-2">
                                        <h4 style="max-width: 400px;">{{ $s -> supplierEmail }}</h4>
                                    </div>
                                    <div class="d-flex mb-2">
                                        <h4 style="max-width: 250px;">{{ $s -> supplierAddress }}</h4>
                                    </div>
                                    <div class="d-flex mb-2">
                                        <h4 style="max-width: 250px;">{{ $s -> supplierNPWP }}</h4>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-success mt-2 mr-3" style="width: 70px" data-toggle="modal" id="detail" data-target="#editItem-{{ $s->id }}">Edit</button>
                                        <button class="btn btn-danger mt-2" style="width: 80px">Delete</button>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col">
                <form method="POST" action="{{ Route('adminPurchasing.add-supplier') }}">
                    @csrf
                    <div class="d-flex justify-content-around mr-2">
                        <div class="form-group p-2">
                            <label for="supplierName">Nama Supplier</label>
                            <input name="supplierName" type="text" class="form-control" id="supplierName" placeholder="Input nama supplier..."
                                style="width: 500px; height: 50px">
                        </div>
                    </div>
                    <div class="d-flex justify-content-around mr-2">
                        <div class="form-group p-2">
                            <label for="noTelp">No. Telp Supplier</label>
                            
                            {{-- <input name="noTelp" type="text" class="form-control" id="noTelp" placeholder="Input nomor telepon dalam angka..."
                                style="width: 500px; height: 50px"> --}}
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">(+62)</div>
                                </div>
                                <input type="text" class="form-control" id="noTelp" name="noTelp" style="width: 450px; height: 50px" placeholder="Input nomor telepon dalam angka...">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-around mr-2">
                        <div class="form-group p-2">
                            <label for="supplierEmail" class="mb-2">Email Supplier</label>
                            <input name="supplierEmail" type="text" class="form-control" id="supplierEmail" placeholder="Input email supplier..."
                                style="width: 500px; height: 50px">
                        </div>
                    </div>
                    <div class="d-flex justify-content-around mr-2">
                        <div class="form-group p-2">
                            <label for="supplierAddress" class="mb-2">Alamat Supplier</label>
                            <input name="supplierAddress" type="text" class="form-control" id="supplierAddress" placeholder="Input alamat supplier..."
                                style="width: 500px; height: 50px">
                        </div>
                    </div>
                    <div class="d-flex justify-content-around mr-2">
                        <div class="form-group p-2">
                            <label for="supplierNPWP" class="mb-2">NPWP Supplier</label>
                            <input name="supplierNPWP" type="text" class="form-control" id="supplierNPWP" placeholder="Input NPWP supplier..."
                                style="width: 500px; height: 50px">
                        </div>
                    </div>
                    <br>
                    <div class="d-flex ml-3 justify-content-center pb-3">
                        <button type="submit" class="btn btn-primary">Add Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

@foreach($suppliers as $s)
    <div class="modal fade" id="editItem-{{ $s->id }}" tabindex="-1" role="dialog" aria-labelledby="editItemTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" style="width: 600px" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemTitle">Edit Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/admin-purchasing/{{ $s -> id }}/edit">
                        @csrf
                        @method('put')
                        <div class="d-flex justify-content-around mr-2">
                            <div class="form-group p-2">
                                <label for="supplierName" class="mb-2">Nama Supplier</label>
                                <input name="supplierName" type="text" class="form-control" id="supplierName" placeholder="Input nama supplier..."
                                    style="width: 500px; height: 50px" value="{{ $s -> supplierName }}">
                            </div>
                        </div>
                        <div class="d-flex justify-content-around mr-2">
                            <div class="form-group p-2">
                                <label for="noTelp" class="mb-2">No. Telp Supplier</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">(+62)</div>
                                    </div>
                                    <input type="text" class="form-control" id="noTelp" name="noTelp" style="width: 450px; height: 50px" placeholder="Input nomor telepon dalam angka...">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around mr-2">
                            <div class="form-group p-2">
                                <label for="supplierEmail" class="mb-2">Email Supplier</label>
                                <input name="supplierEmail" type="text" class="form-control" id="supplierEmail" placeholder="Input email supplier..."
                                    style="width: 500px; height: 50px" value="{{ $s -> supplierEmail }}">
                            </div>
                        </div>
                        <div class="d-flex justify-content-around mr-2">
                            <div class="form-group p-2">
                                <label for="supplierAddress" class="mb-2">Alamat Supplier</label>
                                <input name="supplierAddress" type="text" class="form-control" id="supplierAddress" placeholder="Input alamat supplier..."
                                    style="width: 500px; height: 50px" value="{{ $s -> supplierAddress }}">
                            </div>
                        </div>
                        <div class="d-flex justify-content-around mr-2">
                            <div class="form-group p-2">
                                <label for="supplierNPWP" class="mb-2">NPWP Supplier</label>
                                <input name="supplierNPWP" type="text" class="form-control" id="supplierNPWP" placeholder="Input NPWP supplier..."
                                    style="width: 500px; height: 50px" value={{ $s -> supplierNPWP }}>
                            </div>
                        </div>
                        <div class="d-flex ml-3 justify-content-center pb-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<style>
  .scrolling-wrapper{
        overflow-y: auto;
        max-height: 600px;
    }
    .card-block{
	height: 425px;
	background-color: #fff;
	background-position: center;
	background-size: cover;
	transition: all 0.2s ease-in-out !important;
	border-radius: 24px;
	&:hover{
		transform: translateX(-5px);
		box-shadow: none;
		opacity: 0.9;
	}
}
</style>

@endsection
