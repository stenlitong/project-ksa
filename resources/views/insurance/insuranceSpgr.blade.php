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
                  <h1 class="display-4">Review Form Request SPGR</h1>
                  
                  {{-- <br> --}}
                    {{-- <div class="text-md-right">
                        <button class="btn btn-outline-danger"  id="top"  style="width: 20%;">upload</button>
                    </div> --}}

                
                    <table class="table" style="margin-top: 1%">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Nama File</th>
                                <th scope="col">Upload Time</th>
                                <th scope="col">Status</th>
                                <th scope="col">Reason</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        @error('reasonbox')
                        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                            Alasan Wajib Diisi
                        </div>
                        @enderror
                        <tbody>
                            @forelse($uploadspgr as $upspgr )
                            @for ( $r = 1 ; $r <= 7 ; $r++)
                            @php
                                $viewspgrfile = array('spgr','Letter_of_Discharge','CMC','surat_laut',
                                                    'spb','load_line','surat_keterangan_bank');
                                $name = array('SPGR','LETTER OF DISCHARGE','CMC','SURAT LAUT',
                                        'SPB','LOAD LINE','SURAT KETERANGAN BANK');
                                $spgrfile = 'spgrfile'.$r;
                                $time_upload ="time_upload".$r;
                                $stats ="status".$r;
                                $reason ="reason".$r;
                            @endphp
                            @if(empty($upspgr->$stats))
                                <tr>
                                   <td> </td> {{-- agar tidak keluar hasil kosong --}}
                                </tr>
                            @else
                            <tr>
                                <td scope="col">{{ $r }}</td>
                                <td scope="col" id="nama">{{$name[$r-1]}}</td>                                        
                                <td scope="col" id="time">{{$upspgr->$time_upload}}</td>                                        
                                <td scope="col" id="status">{{$upspgr->$stats}}</td>                                      
                                <td scope="col" id="reason">{{$upspgr->$reason}}</td>                                        
                                @if ($upspgr->$stats == "on review")
                                <td scope="col">
                                        <div class="form-row">
                                            <div class="col-md-auto">
                                                <form method="POST" action="/insurance/approvespgr">
                                                    @csrf
                                                    <input type="hidden" name='status' value={{$stats}}>
                                                    <input type="hidden" name = 'cabang' value={{$upspgr->cabang}}>
                                                    <button type="submit" class="btn btn-outline-success">approve</button>
                                                </form>
                                            </div>
                                            <div class="col-md-auto">
                                                <button type="button" class="btn btn-outline-danger"  data-toggle="modal" data-target="#rejectTitle-{{$reason}}">
                                                    Reject File
                                                </button>
                                            </div>
                                            <div class="col-md-auto">
                                                <form method="post" action="/insurance/viewspgr">
                                                    @csrf
                                                    <input type="hidden" name = 'cabang' value={{$upspgr->cabang}}>
                                                    <input type="hidden" name='viewspgrfile' value={{$viewspgrfile[$r-1]}} />
                                                    <button type="submit" name="views3" class="btn btn-dark">view</button>
                                                </form>
                                            </div>
                                        </div>
        
                                    {{-- Modal  --}}
                                    <div class="modal fade" id="rejectTitle-{{$reason}}" tabindex="-1" role="dialog" aria-labelledby="rejectTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle"><strong>Reject Document ?</strong></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="/insurance/rejectspgr">
                                                        @csrf
                                                        <input type="hidden" name='reason' value={{$reason}}>
                                                        <input type="hidden" name='status' value={{$stats}}>
                                                        <input type="hidden" name = 'cabang' value={{$upspgr->cabang}}>
                                                        <div class="form-group">
                                                            <label for="reason"><strong>Reason</strong></label>
                                                            <textarea class="form-control" name="reasonbox" required id="reason" rows="3"></textarea>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" id="submitreject2" class="btn btn-danger">Reject File</button>
                                                </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                @else
                                    <td>
                                        <div class="col-md-auto">
                                        <form method="post" action="/insurance/viewspgr">
                                            @csrf
                                            <input type="hidden" name = 'cabang' value={{$upspgr->cabang}}>
                                            <input type="hidden" name='viewspgrfile' value={{$viewspgrfile[$r-1]}} />
                                            <button type="submit" name="views3" class="btn btn-dark">view</button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                                </tr>
                                @endif
                                @endfor
                                @empty
                                <tr>
                                    <td>Data not found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @if(date("d") < 28)
                        <button class="btn btn-danger" id="realsub" style="margin-left: 50%; display: none;" type="submit" name="Submit" value="Upload" onClick="">Submit</button>
                     @endif
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