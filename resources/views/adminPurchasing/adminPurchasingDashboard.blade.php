@extends('../layouts.base')

@section('title', 'Admin Purchasing Dashboard')

@section('container')
<div class="row">
    @include('adminPurchasing.sidebar')
    
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        @include('../layouts/time')

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
            <div class="col" style="overflow-x: auto; max-width: 850px">
                <h2 class="mb-4" style="text-align: center">Contact Suppliers</h2>
                <div class="flex-column flex-nowrap scrolling-wrapper">
                    @foreach($suppliers as $s)
                        <div class="card border-dark w-100 mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <img src="/images/profile.png" style="height: 100px; width: 100px;">
                                        <h5 class="mt-3">{{ $s -> supplierName }}</h5>
                                    </div>
                                    <div class="col" style="">
                                            <h5 class="smaller-screen-size"><span data-feather="phone"></span> (+62) {{ $s -> noTelp }}</h5>
                                            <h5 class="smaller-screen-size"><span data-feather="mail"></span> {{ $s -> supplierEmail }}</h5>
                                            <h5 class="smaller-screen-size"><span data-feather="home"></span> {{ $s -> supplierAddress }}</h5>
                                            <h5 class="smaller-screen-size"><span data-feather="credit-card"></span> {{ $s -> supplierNPWP }}</h5>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-sm btn-success mt-2 mr-3" data-toggle="modal" id="detail" data-target="#editItem-{{ $s->id }}">Edit</button>
                                            {{-- <button class="btn btn-danger mt-2">Delete</button> --}}
                                            <button class="btn btn-sm btn-danger mt-2" data-toggle="modal" id="delete" data-target="#deleteSupplier-{{ $s -> id }}">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col">
                <h2 class="mb-4" style="text-align: center">Add Suppliers</h2>
                <form method="POST" action="{{ Route('adminPurchasing.add-supplier') }}">
                    @csrf
                        <div class="form-group p-2">
                            <label for="supplierName">Nama Supplier</label>
                            <input name="supplierName" type="text" class="form-control" id="supplierName" placeholder="Input nama supplier..."
                                style="height: 50px" value={{ old('supplierName') }}>
                        </div>
                        <div class="form-group p-2">
                            <label for="noTelp">No. Telp Supplier</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">(+62)</div>
                                </div>
                                <input type="text" class="form-control" id="noTelp" name="noTelp" style="height: 50px" placeholder="Input nomor telepon dalam angka..." value={{ old('noTelp') }}>
                            </div>
                        </div>
                        <div class="form-group p-2">
                            <label for="supplierEmail" class="mb-2">Email Supplier</label>
                            <input name="supplierEmail" type="text" class="form-control" id="supplierEmail" placeholder="Input email supplier..."
                                style="height: 50px" value={{ old('supplierEmail') }}>
                        </div>
                        <div class="form-group p-2">
                            <label for="supplierAddress" class="mb-2">Alamat Supplier</label>
                            <input name="supplierAddress" type="text" class="form-control" id="supplierAddress" placeholder="Input alamat supplier..."
                                style="height: 50px" value={{ old('supplierAddress') }}>
                        </div>
                        <div class="form-group p-2">
                            <label for="supplierNPWP" class="mb-2">NPWP Supplier</label>
                            <input name="supplierNPWP" type="text" class="form-control" id="supplierNPWP" placeholder="Input NPWP supplier..."
                                style="height: 50px" value={{ old('supplierNPWP') }}>
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
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger" style="color: white">
                    <h5 class="modal-title" id="editItemTitle">Edit Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/admin-purchasing/{{ $s -> id }}/edit">
                        @csrf
                        @method('put')
                            <div class="form-group p-2">
                                <label for="supplierName" class="mb-2">Nama Supplier</label>
                                <input name="supplierName" type="text" class="form-control" id="supplierName" placeholder="Input nama supplier..."
                                    style="height: 50px" value="{{ $s -> supplierName }}">
                            </div>
                            <div class="form-group p-2">
                                <label for="noTelp" class="mb-2">No. Telp Supplier</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">(+62)</div>
                                    </div>
                                    <input type="text" class="form-control" id="noTelp" name="noTelp" style="width: 450px; height: 50px" placeholder="Input nomor telepon dalam angka...">
                                </div>
                            </div>
                            <div class="form-group p-2">
                                <label for="supplierEmail" class="mb-2">Email Supplier</label>
                                <input name="supplierEmail" type="text" class="form-control" id="supplierEmail" placeholder="Input email supplier..."
                                    style="height: 50px" value="{{ $s -> supplierEmail }}">
                            </div>
                            <div class="form-group p-2">
                                <label for="supplierAddress" class="mb-2">Alamat Supplier</label>
                                <input name="supplierAddress" type="text" class="form-control" id="supplierAddress" placeholder="Input alamat supplier..."
                                    style="height: 50px" value="{{ $s -> supplierAddress }}">
                            </div>
                            <div class="form-group p-2">
                                <label for="supplierNPWP" class="mb-2">NPWP Supplier</label>
                                <input name="supplierNPWP" type="text" class="form-control" id="supplierNPWP" placeholder="Input NPWP supplier..."
                                    style="height: 50px" value={{ $s -> supplierNPWP }}>
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

@foreach($suppliers as $s)
    <div class="modal fade" id="deleteSupplier-{{ $s->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteSupplierTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="editItemTitle" style="color: white">Delete Supplier: {{ $s -> supplierName }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <br>
                    <h5 style="text-align: center">Are You Sure To Delete This Supplier ?</h5>
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <form method="POST" action="/admin-purchasing/{{ $s -> id }}/delete" >
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
    setTimeout(function() {
        $('.alert').fadeOut('fast');
    }, 3000);
</script>

<style>
    .modal-backdrop {
        height: 100%;
        width: 100%;
    }
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
		box-shadow: none;
		opacity: 0.9;
	}
    .alert{
        text-align: center;
    }
    @media (min-width: 300px) and (max-width: 768){
        .smaller-screen-size{
            width: 150px;
            word-break: break-all;
            font-size: 12px;
        }
    }
</style>

@endsection
