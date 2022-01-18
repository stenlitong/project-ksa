@extends('../layouts.base')

@section('title', 'insiden-insurance-SPGR-Notes')

@section('container')
<x-guest-layout>
<div class="row">
    @include('picadmin.picAdminsidebar')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="col" style="margin-top: 15px">
            <div class="jumbotron jumbotron-fluid" >
                <div class="container">
                    <h1 class="display-4"><strong>Rekapitulasi Dana</strong></h1>
                    
                    <table class="table" style="margin-top: 1%">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Periode</th>
                                <th scope="col">Cabang</th>
                                <th scope="col">Nama Tug</th>
                                <th scope="col">Nama Barge</th>
                                <th scope="col">status pembayaran</th>
                                <th scope="col">Nilai Jumlah Di setujui</th>
                                {{-- <th scope="col">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapdana as $rekap )
                            @php
                                $date = date('Y-m-28');
                            @endphp
                            <tr>
                                <td class="table-info">{{$loop->index+1}}</td>
                                <td class="table-info" style="text-transform: uppercase;">{{$rekap->DateNote1}} - {{$rekap->DateNote2}}</td>
                                <td class="table-info">{{$rekap->Cabang}}</td>
                                <td class="table-info">{{$rekap->NamaTug}}</td>
                                <td class="table-info">{{$rekap->NamaBarge}}</td>
                                <td class="table-info">{{$rekap->status_pembayaran}}</td>
                                <td class="table-info">{{$rekap->mata_uang_nilai}} - {{number_format($rekap->Nilai, 2)}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td>No Notes Uploaded Yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                        <script>
                            setTimeout(function(){
                            $("div.alert").remove();
                            }, 5000 ); // 5 secs
                        </script>
                        <script 
                            src="https://code.jquery.com/jquery-3.2.1.min.js">
                        </script>
                        @if ($success = Session::get('success'))
                        <div class="alert alert-success alert-block" id="success">
                            <strong>{{ $success }}</strong>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>   
    </main>
</div>
</x-guest-layout>
@endsection