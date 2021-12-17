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
            <h1 class="h1-responsive ; text-center">History</h1>
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

                <tbody>
                    @forelse($spgrfile as $upspgr )
                    @for ( $r = 1 ; $r <= 7 ; $r++)
                    @php
                        $viewspgrfile = array('spgr','Letter_of_Discharge','CMC','surat_laut',
                                            'spb','lot_line','surat_keterangan_bank');
                        $name = array('SPGR','LETTER OF DISCHARGE','CMC','SURAT LAUT',
                                        'SPB','LOT LINE','SURAT KETERANGAN BANK');
                        $spgrfile = 'spgrfile'.$r;
                        $time_upload ="time_upload".$r;
                        $stats ="status".$r;
                        $reason ="reason".$r;
                    @endphp
                    @if(empty($upspgr->$stats))
                        <tr>
                           <td> </td> {{-- agar tidak keluar hasil kosong --}}
                        </tr>
                    @elseif ($upspgr->$stats == 'on review')
                        <tr>
                             {{-- agar tidak keluar hasil on review --}}
                        </tr>
                    @elseif ($upspgr->$stats == 'rejected')
                    <tr>
                        <td class="table-danger">{{ $r }}</td>
                        <td class="table-danger" id="nama">{{$name[$r-1]}}</td>                                        
                        <td class="table-danger" id="time">{{$upspgr->$time_upload}}</td>                                        
                        <td class="table-danger" id="status">{{$upspgr->$stats}}</td>                                      
                        <td class="table-danger" id="reason">{{$upspgr->$reason}}</td>                                        
                        <td class="table-danger">
                            <div class="col-md-auto">
                                <form method="post" action="/insurance/viewspgr">
                                    @csrf
                                    <input type="hidden" name = 'cabang' value={{$upspgr->cabang}}>
                                    <input type="hidden" name='viewspgrfile' value={{$viewspgrfile[$r-1]}} />
                                    <button type="submit" name="views3" class="btn btn-dark">view</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @else
                        <tr>
                            <td class="table-info">{{ $r }}</td>
                            <td class="table-info" id="nama">{{$name[$r-1]}}</td>                                        
                            <td class="table-info" id="time">{{$upspgr->$time_upload}}</td>                                        
                            <td class="table-info" id="status">{{$upspgr->$stats}}</td>                                      
                            <td class="table-info" id="reason">{{$upspgr->$reason}}</td>                                        
                            <td class="table-info">
                                <div class="col-md-auto">
                                    <form method="post" action="/insurance/viewspgr">
                                        @csrf
                                        <input type="hidden" name = 'cabang' value={{$upspgr->cabang}}>
                                        <input type="hidden" name='viewspgrfile' value={{$viewspgrfile[$r-1]}} />
                                        <button type="submit" name="views3" class="btn btn-dark">view</button>
                                    </form>
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