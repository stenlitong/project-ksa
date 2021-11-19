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
                      <h1 class="display-4">Upload your RPK Documents</h1>
                        <p class="lead">please only upload file size max 1MB with .pdf format only .
                          <br>
                            Please upload your document request & fund request form  !
                        </p>
                        <button class="btn btn-danger"  id="top" style="margin-left: 80%; width: 20%;">upload</button>
                        <br>
                        <table class="table" style="margin-top: 1%">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Nama File</th>
                                    <th scope="col">Upload Time</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Reason</th>
                                    <th scope="col">Due Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <form action="/picsite/uploadrpk" method="POST" enctype="multipart/form-data" name="formUploadrpk" id="formUploadrpk">
                                    @csrf
                                @if (Auth::user()->cabang == 'Babelan')
                                @for ($a = 1 ; $a <= 7 ; $a++)
                                    @php
                                        $name = array('Surat Keterangan Asal Barang','Cargo Manifest','Voyage Report/ Term Sheet'
                                                    ,'Bill of Lading','Ijin Olah Gerak Kapal',
                                                    'Docking','Surat Keterangan Persiapan Kapal');
                                        $rfile = 'rfile'.$a;
                                        $time_upload ="time_upload".$a;
                                        $stats ="status".$a;
                                        $reason ="reason".$a;
                                    @endphp
                                    <tr>
                                        <td scope="col" id="nama">{{$name[$a-1]}}</td>
                                        @foreach ( $docrpk as $doc)
                                        <td id="">{{$doc->$time_upload}}</td>
                                        <td id="">{{$doc->$stats}}</td>
                                        <td id="">{{$doc->$reason}}</td>
                                        <td id="">{{$doc->due_time}}</td>
                                        @endforeach
                                        <td><input name="{{$rfile}}" id="rfile" type="file"/></td>
                                    </tr>
                                @endfor
                                @endif

                                @if (Auth::user()->cabang == 'Berau')
                                @for ($a = 1 ; $a <= 7 ; $a++)
                                @php
                                    $name = array('Surat Keterangan Asal Barang','Cargo Manifest','Voyage Report/ Term Sheet'
                                                    ,'Bill of Lading','Ijin Olah Gerak Kapal',
                                                    'Docking','Surat Keterangan Persiapan Kapal');
                                    $brfile = 'brfile'.$a;
                                    $time_upload ="time_upload".$a;
                                    $stats ="status".$a;
                                    $reason ="reason".$a;
                                @endphp
                                <tr>
                                    <td scope="col" id="nama">{{$name[$a-1]}}</td>
                                    @foreach ( $docrpk as $doc)
                                    <td id="">{{$doc->$time_upload}}</td>
                                    <td id="">{{$doc->$stats}}</td>
                                    <td id="">{{$doc->$reason}}</td>
                                    <td id="">{{$doc->due_time}}</td>
                                    @endforeach
                                    <td><input name="{{$brfile}}" id="rfile" type="file"/></td>
                                </tr>
                                @endfor
                                @endif
                                @if (Auth::user()->cabang == 'Banjarmasin')
                                @for ($a = 1 ; $a <= 7 ; $a++)
                                @php
                                    $name = array('Surat Keterangan Asal Barang','Cargo Manifest','Voyage Report/ Term Sheet'
                                                    ,'Bill of Lading','Ijin Olah Gerak Kapal',
                                                    'Docking','Surat Keterangan Persiapan Kapal');
                                    $bjrfile = 'bjrfile'.$a;
                                    $time_upload ="time_upload".$a;
                                    $stats ="status".$a;
                                    $reason ="reason".$a;
                                @endphp
                                <tr>
                                    <td scope="col" id="nama">{{$name[$a-1]}}</td>
                                    @foreach ( $docrpk as $doc)
                                    <td id="">{{$doc->$time_upload}}</td>
                                    <td id="">{{$doc->$stats}}</td>
                                    <td id="">{{$doc->$reason}}</td>
                                    <td id="">{{$doc->due_time}}</td>
                                    @endforeach
                                    <td><input name="{{$bjrfile}}" id="rfile" type="file"/></td>
                                </tr>
                                @endfor
                                @endif

                                @if (Auth::user()->cabang == 'Samarinda')
                                @for ($a = 1 ; $a <= 7 ; $a++)
                                @php
                                    $name = array('Surat Keterangan Asal Barang','Cargo Manifest','Voyage Report/ Term Sheet'
                                                    ,'Bill of Lading','Ijin Olah Gerak Kapal',
                                                    'Docking','Surat Keterangan Persiapan Kapal');
                                    $smrfile = 'smrfile'.$a;
                                    $time_upload ="time_upload".$a;
                                    $stats ="status".$a;
                                    $reason ="reason".$a;
                                @endphp
                                <tr>
                                    <td scope="col" id="nama">{{$name[$a-1]}}</td>
                                    @foreach ( $docrpk as $doc)
                                    <td id="">{{$doc->$time_upload}}</td>
                                    <td id="">{{$doc->$stats}}</td>
                                    <td id="">{{$doc->$reason}}</td>
                                    <td id="">{{$doc->due_time}}</td>
                                    @endforeach
                                    <td><input name="{{$smrfile}}" id="rfile" type="file"/></td>
                                </tr>
                                @endfor
                                @endif
                                
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
                            @if(session()->has('message'))
                            <div class="alert alert-success"style="width: 40%; margin-left: 30%">
                                {{ session()->get('message') }}
                            </div>
                            @endif
                            
                            @if($errors->any())
                            {!! implode('', $errors->all('<div>:message</div>')) !!}
                            @endif
                            {{-- @error()
                            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                                file type/size is Invalid
                            </div>
                            @enderror --}}
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