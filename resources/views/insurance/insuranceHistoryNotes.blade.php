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
                  
                    <table class="table" style="margin-top: 1%">
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
                                <td class="table-info">RP.{{number_format($UpNotes->Nilai, 2)}}</td>
                                <td class="table-info">RP.{{number_format($UpNotes->Nilai_Claim, 2)}}</td>
                                {{-- <td scope="col">
                                    <form action="/insurance/NoteSpgr/destroy/{{$UpNotes->id}}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" id="realsub" onClick="return confirm('Are you sure?')" class="btn btn-outline-dark">Delete</button>
                                    </form> --}}
                                </td>
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
@endsection