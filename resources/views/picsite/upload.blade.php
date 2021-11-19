@extends('../layouts.base')

@section('title', 'Picsite-Upload-Form')

@section('container')
<div class="row">
    @include('picsite.sidebarpic')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="row">
            <div class="col" style="margin-top: 15px">
                <div class="jumbotron jumbotron-fluid" >
                    <div class="container">
                      <h1 class="display-4">Upload your Fund Request Form</h1>
                        <p class="lead">please only upload file with .zip format only .
                          <br>
                            Please upload your document request & fund request form  !
                        </p>
                        <br>
                        <button class="btn btn-danger" id="topsubmit" style="margin-left: 80%; width: 20%;" onClick="">Submit</button>
                        <table class="table"style="margin-top: 1%">
                            <thead class="thead-dark" >
                                <tr>
                                    <th scope="col">Nama File</th>
                                    <th scope="col">Upload Time</th>
                                    <th scope="col">status</th>
                                    <th scope="col">Reason</th>
                                    <th scope="col">Due Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <form action="/picsite/upload" method="post" enctype="multipart/form-data" name="formUpload" id="formUpload">
                                    @csrf
{{--Babelan ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
                                    @if (Auth::user()->cabang == 'Babelan')     
                                    <tr>
                                        <td>Sertifikat Keselamatan</td>
                                        @foreach ($document as $doc )
                                        
                                        <td scope="col" id="time1">{{$doc->time_upload1}}</td> 
                                        
                                        <td scope="col" id="status1">{{$doc->status1}}</td>
                                        
                                        <td scope="col" id="reason1">{{$doc->reason1}}</td>
                                        
                                        <td scope="col" id="duetime1">{{$doc->due_time}}</td> 
                                        
                                        @endforeach
                                        <td scope="col">
                                            <input name="ufile1" id="ufile1" type="file" onClick=""/> 
                                            <a href="/picsite/view" target="_blank">view</a>
                                        </td>  
                                    </tr>
                                    
                                    <tr>
                                        <td>Sertifikat Garis Muat</td>
                                        @foreach ($document as $doc)
                                        <td scope="col" id="time2">{{$doc->time_upload2}}</td> 
                                        
                                        <td scope="col" id="status2">{{$doc->status2}}</td>
                                        
                                        <td scope="col" id="reason2">{{$doc->reason2}}</td>
                                        
                                        <td scope="col" id="duetime2">{{$doc->due_time}}</td>
                                        @endforeach

                                        <td><input name="ufile2" id="ufile2" type="file"/></td>
                                    </tr>   
                                    <tr> 
                                        <td>Penerbitan 1 Kali Jalan</td>
                                        @foreach ($document as $doc)
                                        <td scope="col" id="time3">{{$doc->time_upload3}}</td> 
                                        
                                        <td scope="col" id="status3">{{$doc->status3}}</td>
                                        
                                        <td scope="col" id="reason3">{{$doc->reason3}}</td>
                                        
                                        <td scope="col" id="duetime3">{{$doc->due_time}}</td>
                                        @endforeach
                                        <td><input name="ufile3" id="ufile3" type="file"/></td>                         
                                    </tr>
                                    <tr>    
                                        <td>Sertifikat Safe Manning</td>
                                        @foreach ($document as $doc)
                                        <td scope="col" id="time4">{{$doc->time_upload4}}</td> 
                                        
                                        <td scope="col" id="status4">{{$doc->status4}}</td>
                                        
                                        <td scope="col" id="reason4">{{$doc->reason4}}</td>
                                        
                                        <td scope="col" id="duetime4">{{$doc->due_time}}</td>
                                        @endforeach
                                        <td><input name="ufile4" id="ufile4" type="file"/></td>                              
                                    </tr>
                                    <tr>  
                                        <td>Endorse Surat Laut</td>
                                        @foreach ($document as $doc)
                                        <td scope="col" id="time5">{{$doc->time_upload5}}</td> 
                                        
                                        <td scope="col" id="status5">{{$doc->status5}}</td>
                                        
                                        <td scope="col" id="reason5">{{$doc->reason5}}</td>
                                        
                                        <td scope="col" id="duetime5">{{$doc->due_time}}</td>
                                        @endforeach
                                        <td><input name="ufile5" id="ufile5" type="file"/></td>
                                    </tr>  
                                    <tr>  
                                        <td>Perpanjangan Sertifikat SSCEC</td>
                                        @foreach ($document as $doc)
                                        <td scope="col" id="time6">{{$doc->time_upload6}}</td> 
                                        
                                        <td scope="col" id="status6">{{$doc->status6}}</td>
                                        
                                        <td scope="col" id="reason6">{{$doc->reason6}}</td>
                                        
                                        <td scope="col" id="duetime6">{{$doc->due_time}}</td>
                                        @endforeach
                                        <td><input name="ufile6" id="ufile6" type="file"/></td>
                                    </tr>
                                    <tr>  
                                        <td>Perpanjangan Sertifikat P3K</td>
                                        @foreach ($document as $doc)
                                        <td scope="col" id="time">{{$doc->time_upload7}}</td> 
                                        
                                        <td scope="col" id="status7">{{$doc->status7}}</td>
                                        
                                        <td scope="col" id="reason7">{{$doc->reason7}}</td>
                                        
                                        <td scope="col" id="duetime7">{{$doc->due_time}}</td>
                                        @endforeach
                                        <td><input name="ufile7" id="ufile7" type="file"/></td>
                                    </tr>
                                    <tr>  
                                        <td>Biaya Laporan Dok</td>
                                        @foreach ($document as $doc)
                                        <td scope="col" id="time8">{{$doc->time_upload8}}</td> 
                                        
                                        <td scope="col" id="status8">{{$doc->status8}}</td>
                                        
                                        <td scope="col" id="reason8">{{$doc->reason8}}</td>
                                        
                                        <td scope="col" id="duetime8">{{$doc->due_time}}</td>
                                        @endforeach
                                        <td><input name="ufile8" id="ufile8" type="file"/></td>
                                    </tr> 
                                    <tr>  
                                        <td>PNPB Sertifikat Keselamatan</td>
                                        @foreach ($document as $doc)
                                        <td scope="col" id="time9">{{$doc->time_upload9}}</td> 
                                        
                                        <td scope="col" id="status9">{{$doc->status9}}</td>
                                        
                                        <td scope="col" id="reason9">{{$doc->reason9}}</td>
                                        
                                        <td scope="col" id="duetime9">{{$doc->due_time}}</td>
                                        @endforeach
                                        <td><input name="ufile9" id="ufile9" type="file"/></td>
                                    </tr>
                                    <tr> 
                                        <td>PNPB Sertifikat Garis Muat</td>
                                        @foreach ($document as $doc)
                                        <td scope="col" id="time10">{{$doc->time_upload10}}</td> 
                                        
                                        <td scope="col" id="status10">{{$doc->status10}}</td>
                                        
                                        <td scope="col" id="reason10">{{$doc->reason10}}</td>
                                        
                                        <td scope="col" id="duetime10">{{$doc->due_time}}</td>
                                        @endforeach
                                        <td><input name="ufile10" id="ufile10" type="file"/></td>
                                    </tr> 
                                    <tr>  
                                        <td>PNPB Surat Laut</td>
                                        @foreach ($document as $doc)
                                        <td scope="col" id="time">{{$doc->time_upload11}}</td> 
                                        
                                        <td scope="col" id="status11">{{$doc->status11}}</td>
                                        
                                        <td scope="col" id="reason11">{{$doc->reason11}}</td>
                                        
                                        <td scope="col" id="duetime11">{{$doc->due_time}}</td>
                                        @endforeach
                                        <td><input name="ufile11" id="ufile11" type="file"/></td>
                                    </tr>
                                    <tr>  
                                        <td>Sertifikat SNPP</td>
                                        @foreach ($document as $doc)
                                        <td scope="col" id="time12">{{$doc->time_upload12}}</td> 
                                        
                                        <td scope="col" id="status12">{{$doc->status12}}</td>
                                        
                                        <td scope="col" id="reason12">{{$doc->reason12}}</td>
                                        
                                        <td scope="col" id="duetime12">{{$doc->due_time}}</td>
                                        @endforeach
                                        <td><input name="ufile12" id="ufile12" type="file"/></td>
                                    </tr>
                                    <tr>  
                                        <td>Sertifikat Anti Teritip</td>
                                        @foreach ($document as $doc)
                                        <td scope="col" id="time13">{{$doc->time_upload13}}</td> 
                                        
                                        <td scope="col" id="status13">{{$doc->status13}}</td>
                                        
                                        <td scope="col" id="reason13">{{$doc->reason13}}</td>
                                        
                                        <td scope="col" id="duetime13">{{$doc->due_time}}</td>
                                        @endforeach
                                        <td><input name="ufile13" id="ufile13" type="file"/></td>
                                    </tr> 
                                    <tr>  
                                        <td>PNBP SNPP & SNAT</td>
                                        @foreach ($document as $doc)
                                        <td scope="col" id="time14">{{$doc->time_upload14}}</td> 
                                        
                                        <td scope="col" id="status14">{{$doc->status14}}</td>
                                        
                                        <td scope="col" id="reason14">{{$doc->reason14}}</td>
                                        
                                        <td scope="col" id="duetime14">{{$doc->due_time}}</td>
                                        @endforeach
                                        <td><input name="ufile14" id="ufile14" type="file"/></td>
                                    </tr>
                                    <tr>  
                                        <td>Biaya Survey</td>
                                        @foreach ($document as $doc)
                                        <td scope="col" id="time15">{{$doc->time_upload15}}</td> 
                                        
                                        <td scope="col" id="status15">{{$doc->status15}}</td>
                                        
                                        <td scope="col" id="reason15">{{$doc->reason15}}</td>
                                        
                                        <td scope="col" id="duetime15">{{$doc->due_time}}</td>
                                        @endforeach
                                        <td><input name="ufile15" id="ufile15" type="file"/></td>
                                    </tr>
                                    <tr>  
                                        <td>PNPB SSCEC</td>
                                        @foreach ($document as $doc)
                                        <td scope="col" id="time16">{{$doc->time_upload16}}</td> 
                                        
                                        <td scope="col" id="status16">{{$doc->status16}}</td>
                                        
                                        <td scope="col" id="reason16">{{$doc->reason16}}</td>
                                        
                                        <td scope="col" id="duetime16">{{$doc->due_time}}</td>
                                        @endforeach
                                        <td><input name="ufile16" id="ufile16" type="file"/></td>
                                    </tr>
                                    @endif
{{--Berau ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
                                    @if (Auth::user()->cabang == 'Berau')
                                    @for ($a = 1 ; $a <= 26 ; $a++)    
                                    @php
                                        $name = array('PNBP Sertifikat Konstruksi','Jasa Urus Sertifikat','PNBP Sertifikat Perlengkapan',
                                        'PNBP Sertifikat Radio','PNBP Sertifikat OWS','PNBP Garis Muat',
                                        'PNBP Pemeriksaan Endorse SL','Pemeriksaan Sertifikat','Marine Inspektor',
                                        'Biaya Clearance','PNBP Master Cable','Cover Deck LogBook',
                                        'Cover Engine LogBook','Exibitum Dect LogBook','Exibitum Engine LogBook',
                                        'PNBP Deck Logbook','PNBP Engine Logbook','Biaya Docking',
                                        'Lain-lain','Biaya Labuh Tambat','Biaya Rambu',
                                        'PNBP Pemeriksaan','Sertifikat Bebas Sanitasi & P3K','Sertifikat Garis Muat',
                                        'PNBP SSCEC','Ijin Sekali Jalan');
                                        $beraufile = 'beraufile'.$a;
                                        $time_upload ="time_upload".$a;
                                        $stats ="status".$a;
                                        $reason ="reason".$a;
                                    @endphp
                                            <tr>   
                                                <td scope="col" id="nama">{{$name[$a-1]}}</td>
                                                @foreach ($documentberau as $d )
                                                {{-- <td>PNBP Sertifikat Konstruksi</td> --}}                                
                                                <td scope="col" id="time">{{$d ->$time_upload}}</td>                                        
                                                <td scope="col" id="status">{{$d->$stats}}</td>                                      
                                                <td scope="col" id="reason">{{$d->$reason}}</td>                                        
                                                <td scope="col" id="duetime1">{{$d->due_time}}</td> 
                                                @endforeach
                                                <td scope="col">
                                                    <input name="{{$beraufile}}" id="beraufile" type="file" onClick=""/> 
                                                    <a href="/picsite/view" target="_blank">view</a>
                                                </td>  
                                            </tr>
                                            @endfor
                                    @endif
{{--Banjarmasin --------------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
                                    @if (Auth::user()->cabang == 'Banjarmasin')
                                    @for ($a = 1 ; $a <= 31 ; $a++)
                                    @php
                                    $name = array('Perjalanan','Sertifikat Keselamatan','Sertifikat Anti Fauling','Surveyor',
                                                'Drawing & Stability','Laporan Pengeringan','Berita Acara Lambung',
                                                'Laporan Pemeriksaan Nautis','Laporan Pemeriksaan Anti Faulin','Laporan Pemeriksaan Radio ',
                                                'Berita Acara Lambung','Laporan Pemeriksaan SNPP','BKI',
                                                'SNPP Permanen','SNPP Endorse','Surat Laut Endorse',
                                                'Surat Laut Permanen','Compas Seren','Keselamatan (Tahunan)',
                                                'Keselamatan (Pengaturan Dok)','Keselamatan (Dok)','Garis Muat',
                                                'Dispensasi ISR','Life Raft 1 2, Pemadam',
                                                'SSCEC','Seatrail','Laporan Pemeriksaan Umum',
                                                'Laporan Pemeriksaan Mesin','Nota Dinas Perubahan Kawasan','PAS',
                                                'Invoice BKI','Safe Manning',);
                                    $banjarmasinfile = 'banjarmasinfile'.$a;
                                    $time_upload ="time_upload".$a;
                                    $stats ="status".$a;
                                    $reason ="reason".$a;
                                @endphp
                                     <tr>   
                                        <td scope="col" id="nama">{{$name[$a-1]}}</td>
                                        @foreach ($documentbanjarmasin as $b )
                                        {{-- <td>PNBP Sertifikat Konstruksi</td> --}}                                
                                        <td scope="col" id="time">{{$b->$time_upload}}</td>                                        
                                        <td scope="col" id="status">{{$b->$stats}}</td>                                      
                                        <td scope="col" id="reason">{{$b->$reason}}</td>                                        
                                        <td scope="col" id="duetime1">{{$b->due_time}}</td> 
                                        @endforeach
                                        <td scope="col">
                                            <input name="{{$banjarmasinfile}}" id="banjarmasinfile" type="file" onClick=""/> 
                                            <a href="/picsite/view" target="_blank">view</a>
                                        </td>  
                                    </tr>      
                                    @endfor
                                    @endif
{{--Samarinda ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
                                    @if (Auth::user()->cabang == 'Samarinda')
                                    @for ($a = 1 ; $a <= 38 ; $a++)
                                    @php
                                    $name = array("Sertifikat Keselamatan (Perpanjangan)","Perubahan OK 13 ke OK 1","Keselamatan (Tahunan)",
                                                    "Keselamatan (Dok)","Keselamatan (Pengaturan Dok)","Keselamatan (Penundaan Dok)",
                                                    "Sertifikat Garis Muat","Laporan Pemeriksaan Garis Muat","Sertifikat Anti Fauling",
                                                    'Surat Laut Permanen','Surat Laut Endorse','Call Sign',
                                                    'Perubahan Sertifikat Keselamatan','Perubahan Kawasan Tanpa NotaDin',
                                                    'SNPP Permanen','SNPP Endorse','Laporan Pemeriksaan SNPP',
                                                    'Laporan Pemeriksaan Keselamatan','Buku Kesehatan','Sertifikat Sanitasi Water & P3K',
                                                    'Pengaturan Non ke Klas BKI','Pengaturan Klas BKI (Dok SS)','Surveyor Endorse Tahunan BKI',
                                                    'PR Supplier bki','Balik Nama Grosse','Kapal Baru Body (Set Dokumen)',
                                                    'Halaman Tambahan Grosse','PNBP & PUP','Laporan Pemeriksaan Anti Teriti',
                                                    'Surveyor Pengedokan','Surveyor Penerimaan Klas BKI','Nota Tagihan Jasa Perkapalan',
                                                    'Gambar Kapal Baru (BKI)','Dana Jaminan (CLC)','Surat Ukur Dalam Negeri',
                                                    'Penerbitan Sertifikat Kapal Baru','Buku Stabilitas','Grosse Akta');
                                    $samarindafile = 'samarindafile'.$a;
                                    $time_upload ="time_upload".$a;
                                    $stats ="status".$a;
                                    $reason ="reason".$a;
                                    @endphp
                                        <tr>   
                                            <td scope="col" id="nama">{{$name[$a-1]}}</td>
                                            @foreach ($documentsamarinda as $s )                           
                                            <td scope="col" id="time">{{$s->$time_upload}}</td>                                        
                                            <td scope="col" id="status">{{$s->$stats}}</td>                                      
                                            <td scope="col" id="reason">{{$s->$reason}}</td>                                        
                                            <td scope="col" id="duetime1">{{$s->due_time}}</td> 
                                            @endforeach
                                            <td scope="col">
                                                <input name="{{$samarindafile}}" id="samarindafile" type="file" onClick=""/> 
                                            </td>  
                                        </tr>      
                                        @endfor
                                    @endif
                                </tbody>   
                        </table>
                        @if(date("d") < 28)
                            <button class="btn btn-danger" id="realsubmit" style="margin-left: 50%; display: none;" type="submit" name="Submit" value="Upload" onClick="">Submit</button>
                         @endif

                        <script>
                            document.getElementById('topsubmit').addEventListener('click', openDialog);
                            function openDialog() {
                                document.getElementById('realsubmit').click();
                            }
                        </script>

                        @if(session()->has('message'))
                            <div class="alert alert-success"style="width: 40%; margin-left: 30%">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        
                        @error('ufile' || $beraufile || $banjarmasinfile || $samarindafile)
                        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                            file type/size is Invalid
                        </div>
                        @enderror
                    </form>
                    </div>
                </div>
            </div>
                </table>    
            </div>
        </div>
    </main>
</div>
@endsection