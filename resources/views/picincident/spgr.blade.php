@extends('../layouts.base')

@section('title', 'PicIncident-spgr')

@section('container')
<x-guest-layout>
<div class="form-row">
    @include('picincident.sidebarincident')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="form-row">
        <div class="col" style="margin-top: 1%">
            <div class="jumbotron jumbotron-fluid" >
                <div class="container">
                  <h4 class="display-4" style="margin-top: -6%; ">Upload SPGR Request Document</h4>
                    <p class="lead" style="font-size:16px; margin-top: 2%;">please only upload file .PDF format only .
                      <br>
                        Please upload your SPGR Request form!
                    </p>

                    <br>

                    <form method="post" action="#" ectype="multipart/form-data">
                        @csrf
                        {{-- <div class="mb-3">
                            <label for="formFile" class="form-label">Default file input example</label>
                            <input class="form-control" type="file" id="uploadspgr">
                        </div> --}}

                        <div class="file-upload-wrapper">
                            <label for="formFile" class="form-label">Insert file here (can drag & drop)</label>
                            <input type="file" id="input-file-now" class="file-upload" />
                        </div>

                        <div class="row d-flex justify-content-center mt-100">
                            <div class="col-md-20">
                                <div class="card Light card">
                                    <div class="card-header">
                                        <h5>File Upload</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="#" class="dropzone dz-clickable" >
                                            <div class="dz-default dz-message"><span> Drop files here to upload </span></div>
                                            <div class="file-upload-wrapper">
                                                <input type="file" style="display:none;" id="input-file-now" class="file-upload" />
                                            </div>
                                        </form>
                                        <div class="text-center m-t-15">
                                            <button id="top" class="btn btn-danger">Upload Now</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                    {{-- <button class="btn btn-danger"   style="margin-left: 80%; width: 20%;">upload</button> --}}
        
                    @if(date("d") < 28)
                        <button class="btn btn-danger" id="realsub" style="margin-left: 50%; display: none;" type="submit" name="Submit" value="Upload" onClick="">Submit</button>
                    @endif

                </div>
            </div>
        </div> 
        <div class="col" style="margin-top: 1%">
            <div class="jumbotron jumbotron-fluid" >
                <div class="container">
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
                    
                    {{-- @forelse($claims as $claim )
                    <table class="table" style="margin-top: 2%">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Jenis Incident</th>
                                <th scope="col">Item</th>
                                <th scope="col">Deductible</th>
                                <th scope="col">Description</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td scope="col">{{$loop->index+1}}</td>
                                    <td scope="col">{{$claim->jenis_incident}}</td>
                                    <td scope="col">{{$claim->item}}</td>
                                    <td scope="col">{{$claim->deductible}}</td>
                                    <td scope="col">{{$claim->description}}</td>
                                    <td scope="col">{{$claim->amount}}</td>
                                    <td scope="col">
                                        <form action="/picincident/formclaim/destroy/{{$claim->id}}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" id="Deletepost" onClick="return confirm('Are you sure?')" class="btn btn-danger">Delete</button>
                                        </form>
                                        <form>

                                        </form>
                                        <button type="submit" id="realsub" onClick="return confirm('Are you sure?')" style="display: none" class="btn btn-danger">Delete</button>
                                    </td>
                                </tr>
                                @empty
                                    <tr> forms request not yet added </tr>
                                @endforelse --}}
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
                        $('.file-upload').file_upload();
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
    </div>
    </main>
</div>
</x-guest-layout>
@endsection