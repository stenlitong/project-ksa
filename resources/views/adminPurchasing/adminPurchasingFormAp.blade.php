@if(Auth::user()->hasRole('adminPurchasing'))

    @extends('../layouts.base')

    @section('title', 'Admin Purchasing Dashboard')

    @section('container')
        <div class="row">
            @include('adminPurchasing.sidebar')
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @include('../layouts/time')

                <div class="row">
                    <div class="col mt-3">
                        <div class="jumbotron bg-light jumbotron-fluid" style="border-radius: 25px;">
                            <div class="container">
                                <form method="POST" action="/admin-purchasing/form-ap/upload" enctype="multipart/form-data">
                                    @csrf
                                    <h1 class="mb-3" style="text-align: center">Please upload your form LIST AP</h1>

                                    @if(session('status'))
                                        <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    @error('filename')
                                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                                        Input File Invalid
                                    </div>
                                    @enderror

                                    <img class="w-25 h-25" style="margin-left: 37%;" data-feather="upload">

                                    <div class="custom-file mt-3 w-50 bg-white center">
                                        <input type="file" name="filename" class="form-control" data-browse-on-zone-click="true">
                                    </div>

                                    <p class="mt-3" style="text-align: center">Format: zip/pdf (Max. 5MB)</p>
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col mt-3">
                        <div class="table-wrapper-scroll-y my-custom-scrollbar tableFixHead">
                            <table class="table sortable">
                                <thead class="thead bg-secondary">
                                    <tr>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Nama File</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documents as $doc)
                                    <tr>
                                        <td>{{ $doc -> submissionTime }}</td>
                                        <td>{{ $doc -> filename }}</td>
                                        @if(strpos($doc -> status, 'Denied') !== false)
                                            <td><span style="color: red;font-weight: bold;">{{ $doc -> status }}</span></td>
                                        @elseif(strpos($doc -> status, 'Approved') !== false)
                                            <td><span style="color: green;font-weight: bold;">{{ $doc -> status }}</span></td>
                                        @else
                                            <td>{{ $doc -> status }}</td>
                                        @endif
                                        <td>{{ $doc -> description }}</td>
                                        <td>
                                            <a href="/admin-purchasing/form-ap/{{ $doc -> id }}/download" target="_blank"><span class="icon" data-feather="download"></span></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <style>
            .tableFixHead          { overflow: auto; height: 250px; }
            .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }
            .my-custom-scrollbar {
            position: relative;
            height: 600px;
            overflow: auto;
            }
            .table-wrapper-scroll-y {
                display: block;
            }
            th{
                color: white;
            }
            th, td{
                word-wrap: break-word;
                min-width: 160px;
                max-width: 160px;
                text-align: center;
            }
            .icon{
                color: black;
                height: 24px;
                width: 24px
            }
            .center{
                margin-left: 25%;
                width: 50%;
            }
            .alert{
                text-align: center;
            }
        </style>
        <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
    @endsection

@else
    @include('../layouts/notAuthorized')
@endif