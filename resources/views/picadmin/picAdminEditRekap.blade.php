@extends('../layouts.base')

@section('title', 'insiden-insurance-SPGR-Notes')

@section('container')
<x-guest-layout>
<div class="row">
    @include('picincident.sidebarincident')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="col" style="margin-top: 15px">
            <div class="jumbotron jumbotron-fluid" >
                <div class="container">
                  <h1 class="display-4"><strong>Rekapulasi Dana</strong></h1>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/picadmin/RekapulasiDana/update/{{$rekap->id}}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-outline-success">Update</button>
                </div>

                <div class="form-group">
                    <label for="Datebox">Date</label>
                    <input type="date" class="form-control" value="{{$rekap->DateNote}}" name="Datebox" required id="Datebox" >

                    <br>
                    <label for="Cabang">Cabang</label>
                    <select name="Cabang" class="form-control" required id="Cabang" >
                        <option value="Babelan">Babelan</option>
                        <option value="Berau">Berau</option>
                        <option value="Banjarmasin">Banjarmasin</option>
                        <option value="Samarinda">Samarinda</option>
                        <option value="Kendari">Kendari</option>
                    </select>

                    <br>
                    <label for="NamaKapal">Nama Kapal</label>
                    <input type="text" class="form-control" value="{{$rekap->Nama_Kapal}}" name="NamaKapal" required id="NamaKapal" >
                    
                    <br>
                    <label for="status_pembayaran">status pembayaran</label>
                    <select name="status_pembayaran" id="status_pembayaran" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required autofocus>
                        <option selected disabled value="">Please Choose...</option>
                        <option value="Paid" >Paid</option>
                        <option value="unpaid" >unpaid</option>
                        <option value="partial" >partial</option>
                    </select>

                    <br>
                    <label for="Nilai">Nilai Jumlah Di Bayar</label>
                    <div class="input-group mb-1">
                        <select class="btn btn-outline-secondary" name="mata_uang_nilai">
                            <option selected value="IDR" id="">RP</option>
                        </select>
                        <input type="number" class="form-control" value="{{$rekap->Nilai}}" name="Nilai" required id="Nilai" >
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>
</x-guest-layout>
@endsection