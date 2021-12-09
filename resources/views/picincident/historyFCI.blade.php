@extends('../layouts.base')

@section('title', 'PicIncident-history-FCI')

@section('container')
<x-guest-layout>
<div class="row">
    @include('picincident.sidebarincident')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="col" style="margin-top: 15px">
            <div class="jumbotron jumbotron-fluid" >
                <div class="container">
                  <h1 class="display-4">History Form Claim</h1>
                    <p class="lead">please only upload file size max 1MB with .PDF format only .
                      <br>
                        Please upload your SPGR Request form!
                    </p>
                    <button class="btn btn-danger"  id="top" style="margin-left: 80%; width: 20%;">upload</button>

                    @if($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert error alert-danger" id="error">{{ $error }}
                                <strong>    Please check the file is a PDF and Size 1MB. </strong>
                            </div>
                        @endforeach
                    @endif
                    @if ($success = Session::get('message'))
                        <div class="alert alert-success alert-block" id="message">
                            <strong>{{ $success }}</strong>
                        </div>
                    @endif
                    
                    <table class="table" style="margin-top: 1%">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Nama File</th>
                                <th scope="col">Upload Time</th>
                                <th scope="col">Status</th>
                                <th scope="col">Reason</th>
                                <th scope="col">Due Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <form action="/picincident/uploadFCI" method="POST" enctype="multipart/form-data" name="formUploadFCI" id="formUploadFCI">
                                Babelan
                                @csrf
                            @if (Auth::user()->cabang == 'Babelan')
                            @for ($a = 1 ; $a <= 7 ; $a++)
                                <tr>
                                    <td scope="col">{{ $a }}</td>
                                    <td scope="col" id="nama">{{$name[$a-1]}}</td>
                                    @foreach ( $docrpk as $doc)
                                    <td id="">{{$doc->$time_upload}}</td>
                                    <td id="">{{$doc->$stats}}</td>
                                    <td id="">{{$doc->$reason}}</td>
                                    @endforeach
                                    <td id="">{{$date}}</td>
                                    @if (empty($doc->$stats))
                                        <td><input name="{{$rfile}}" id="rfile" type="file"/></td>
                                    @else
                                        <td> </td>
                                    @endif
                                </tr>
                            @endfor
                            @endif --}}
                        </tbody>
                    </table>
                    {{-- @if(date("d") < 28) --}}
                        <button class="btn btn-danger" id="realsub" style="margin-left: 50%; display: none;" type="submit" name="Submit" value="Upload" onClick="">Submit</button>
                     {{-- @endif --}}
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
                    </form>
                </div>
            </div>
        </div>   
        </div>
    </div>
    </main>
</div>
</x-guest-layout>
@endsection