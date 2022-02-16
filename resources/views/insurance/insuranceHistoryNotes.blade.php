@extends('../layouts.base')

@section('title', 'Insurance-Check-Spgr')

@section('container')
<x-guest-layout>
<div class="row">
    @include('insurance.insuranceSidebar')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="col" style="margin-top: 15px">
            <div class="jumbotron jumbotron-fluid" >
                <div class="container">
                  <h1 class="display-4">History Notes SPGR</h1>
                  
                    <table id="content" class="table" style="margin-top: 1%">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Tahun/Bulan/Tanggal</th>
                                <th scope="col">No.SPGR</th>
                                <th scope="col">No Form Claim</th>
                                <th scope="col">Nama Kapal</th>
                                <th scope="col">status pembayaran</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">Nilai Claim yang di setujui</th>
                                {{-- <th scope="col">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($UploadNotes as $UpNotes )
                            <tr>
                                <td class="table-info">{{$loop->index+1}}</td>
                                <td class="table-info">{{$UpNotes->DateNote}}</td>
                                <td class="table-info">{{$UpNotes->No_SPGR}}</td>
                                <td class="table-info">{{$UpNotes->No_FormClaim}}</td>
                                <td class="table-info">{{$UpNotes->Nama_Kapal}}</td>
                                <td class="table-info">{{$UpNotes->status_pembayaran}}</td>
                                <td class="table-info">{{$UpNotes->mata_uang_nilai}} - {{number_format($UpNotes->Nilai, 2)}}</td>
                                <td class="table-info">{{$UpNotes->mata_uang_claim}} - {{number_format($UpNotes->Nilai_Claim, 2)}}</td>
                                {{-- <td scope="col">
                                    <div class="row">
                                        <div class="col-md-auto">
                                            <form action="/insurance/destroy/{{$UpNotes->id}}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" id="realsub" onClick="return confirm('Are you sure?')" class="btn btn-outline-dark">Delete</button>
                                            </form>
                                        </div>
                                        <div class="col-md-auto">
                                            <form action="/insurance/update/{{$UpNotes->id}}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                    <button class="btn btn-outline-primary"   data-toggle="modal" data-target="#Updatespgrnote">Update</button>
                                                Modal 
                                                <div class="modal fade" id="Updatespgrnote" tabindex="-1" role="dialog" aria-labelledby="Updatespgrnote" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="Updatespgrnote">Add Note ?</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="Datebox">Date</label>
                                                        <input type="date" class="form-control" name="Datebox" required id="Datebox" >
                
                                                        <br>
                                                        <label for="No_SPGR">No.SPGR</label>
                                                        <input type="text" class="form-control" name="No_SPGR" required id="No_SPGR" >
                
                                                        <br>
                                                        <label for="No_FormClaim">No Form Claim</label>
                                                        <input type="text" class="form-control" name="No_FormClaim" required id="No_FormClaim" >
                
                                                        <br>
                                                        <label for="NamaKapal">Nama Kapal</label>
                                                        <input type="text" class="form-control" name="NamaKapal" required id="NamaKapal" >
                                                        
                                                        <br>
                                                        <label for="status_pembayaran">status pembayaran</label>
                                                        <select name="status_pembayaran" id="status_pembayaran" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required autofocus>
                                                            <option selected disabled value="">Please Choose...</option>
                                                            <option value="Paid" >Paid</option>
                                                            <option value="unpaid" >unpaid</option>
                                                            <option value="partial" >partial</option>
                                                        </select>
                
                                                        <br>
                                                        <label for="Nilai">Nilai</label>
                                                        <div class="input-group mb-1">
                                                            <select class="btn btn-outline-secondary" name="mata_uang_nilai">
                                                                <option selected value="USD" id="">USD</option>
                                                                <option value="IDR" id="">IDR</option>
                                                            </select>
                                                            <input type="number" class="form-control" name="Nilai" required id="Nilai" >
                                                        </div>

                                                        <br>
                                                        <label for="NilaiClaim">Nilai Claim yang di setujui</label>
                                                        <div class="input-group mb-1">
                                                            <select class="btn btn-outline-secondary" name="mata_uang_claim">
                                                                <option selected value="USD" id="">USD</option>
                                                                <option value="IDR" id="">IDR</option>
                                                            </select>
                                                            <input type="number" class="form-control" name="NilaiClaim" required id="Nilai_Claim" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" id="submitreject2" class="btn btn-outline-dark">Add Note</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td> --}}
                            </tr>
                            @empty
                            <tr>
                                <td>No Notes Uploaded Yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <script>
                        document.getElementById('top').addEventListener('click', openDialog);
                        function openDialog() {
                            document.getElementById('realsub').click();
                        }
                    </script>
                    <script>
                        setTimeout(function(){
                        $("div.alert").remove();
                        }, 5000 ); // 5 secs
                    </script>
                    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
                </div>
            </div>
        </div>   
    </main>
</div>
</x-guest-layout>
<script type="text/javascript">
    function refreshDiv(){
        $('#content').load(location.href + ' #content')
    }
    setInterval(refreshDiv, 60000);
</script>
@endsection