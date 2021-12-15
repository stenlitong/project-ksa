@extends('../layouts.base')

@section('title', 'PicAdmin Dashboard')

@section('container')

<div class="row">
    @include('insurance.insuranceSidebar')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h2>Welcome back, {{ Auth::user()->name }} ! - Insurance</h2>
            <h2>Cabang : {{ Auth::user()->cabang }}</h2>
            <h3>
                <div id="txt"></div>

                <script>
                    function startTime() {
                    const today = new Date();
                    let h = today.getHours();
                    let m = today.getMinutes();
                    let s = today.getSeconds();
                    m = checkTime(m);
                    s = checkTime(s);
                    document.getElementById('txt').innerHTML =  h + ":" + m + ":" + s;
                    setTimeout(startTime, 1000);
                    }
                    
                    function checkTime(i) {
                    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
                        return i;
                    }
                </script>
            </h3>
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
                    @forelse($spgrfile as $upspgr )
                    @for ( $r = 1 ; $r <= 7 ; $r++)
                    @php
                        $viewspgrfile = array('spgr','Letter_of_Discharge','CMC','surat_laut',
                                            'spb','lot_line','surat_keterangan_bank');
                        $name = array('spgr','Letter of Discharge','CMC','surat laut',
                                        'spb','lot line','surat keterangan bank');
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
                        <td scope="col">
                            <div class="col-md-auto">
                                <form method="post" action="/insurance/viewspgr">
                                    @csrf
                                    <input type="hidden" name = 'cabang' value={{$upspgr->cabang}}>
                                    <input type="hidden" name='viewspgrfile' value={{$viewspgrfile[$r-1]}} />
                                    <button type="submit" name="views3" class="btn btn-dark">view</button>
                                </form>
                            </div>
                        </div>
                    </td>
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
        </div>
    </main>
</div>
@endsection 