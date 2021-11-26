@if(Auth::user()->hasRole('adminPurchasing'))

    @extends('../layouts.base')

    @section('title', 'Checklist AP')

    @section('container')
        <div class="row">
            @include('adminPurchasing.sidebar')
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @include('../layouts/time')

                <h1 class="text-center">Upload List AP</h1>
                {{-- <div class="row">
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
                    <div class="col mt-3" id="content">
                        <div class="table-wrapper-scroll-y my-custom-scrollbar tableFixHead" style="overflow-x:auto;">
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
                </div> --}}

                <div class="d-flex">
                    <div class="p-2 mr-auto">
                        <h5>Cabang</h5>
                        <select name="cabang" class="form-select" onchange="window.location = this.value;">
                            <option selected disabled>Pilih Cabang</option>
                            <option value="/admin-purchasing/form-ap/Jakarta" 
                                @php
                                    if($default_branch == 'Jakarta'){
                                        echo('selected');
                                    }
                                @endphp
                            >Jakarta</option>
                            <option value="/admin-purchasing/form-ap/Banjarmasin"
                                @php
                                    if($default_branch == 'Banjarmasin'){
                                        echo('selected');
                                    }
                                @endphp
                            >Banjarmasin</option>
                            <option value="/admin-purchasing/form-ap/Samarinda"
                                @php
                                    if($default_branch == 'Samarinda'){
                                        echo('selected');
                                    }
                                @endphp
                            >Samarinda</option>
                            <option value="/admin-purchasing/form-ap/Bunati"
                                @php
                                    if($default_branch == 'Bunati'){
                                        echo('selected');
                                    }
                                @endphp
                            >Bunati</option>
                            <option value="/admin-purchasing/form-ap/Babelan"
                                @php
                                    if($default_branch == 'Babelan'){
                                        echo('selected');
                                    }
                                @endphp
                            >Babelan</option>
                            <option value="/admin-purchasing/form-ap/Berau"
                                @php
                                    if($default_branch == 'Berau'){
                                        echo('selected');
                                    }
                                @endphp
                            >Berau</option>
                        </select>
                    </div>
                </div>
                
                <div id="content" style="overflow-x:auto;">
                    <table class="table">
                        <thead class="thead bg-danger">
                        <tr>
                            <th scope="col">Time Created</th>
                            <th scope="col">Status</th>
                            <th scope="col">Nomor PO</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($apList as $ap)
                            <tr>
                                <td>{{ $ap -> creationTime }}</td>
                                @if($ap -> status == 'OPEN')
                                    <td><span style="color: green; font-weight: bold; font-size: 18px">{{ $ap -> status }}</span></td>
                                @else
                                    <td><span style="color: red; font-weight: bold; font-size: 18px">{{ $ap -> status }}</span></td>
                                @endif
                                <td>{{ $ap -> orderHead -> noPo }}</td>
                                <td>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#detail-{{ $ap -> id }}">Detail</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </main>
        </div>

        @foreach($apList as $ap)
                <div class="modal fade" id="detail-{{ $ap -> id }}" tabindex="-1" role="dialog" aria-labelledby="detailTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <div class="d-flex justify-content-start">
                                    <h3 style="color: white">{{ $ap -> orderHead -> noPo }}</h3>
                                </div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="d-flex justify-content-end mb-3 mr-3">
                                    <div class="p-2 mr-auto">
                                        <h5>Total Harga : Rp. {{ number_format($ap -> orderHead -> totalPrice, 2, ",", ".") }}</h5>
                                    </div>
                                <form action="/admin-purchasing/{{ $default_branch }}/form-ap/upload" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <button type="submit" class="btn btn-info mr-3">Submit</button>
                                    <button class="btn btn-success">Close PO</button>
                                </div>
                                    <div class="table-modal">
                                        <table class="table">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th class="table-header">Date Uploaded</th>
                                                    <th class="table-header">Name</th>
                                                    <th class="table-header">Status</th>
                                                    <th class="table-header">Description</th>
                                                    <th class="table-header">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for($i = 1 ; $i <= 20 ; $i++)
                                                    @php
                                                        $status = 'status_partial' . $i;
                                                        $uploadTime = 'uploadTime_partial' . $i;
                                                        $description = 'description_partial' . $i;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $ap -> $uploadTime }}</td>
                                                        <td>Partial {{ $i }}</td>
                                                        <td>{{ $ap -> $status }}</td>
                                                        <td>{{ $ap -> $description }}</td>
                                                        <td>
                                                            <input type="hidden" name="apListId" value="{{ $ap -> id }}">
                                                            <input type="file" name="doc_partial{{ $i }}" class="form-control">
                                                        </td>
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                                <div class="mt-4">
                                    <form action="" method="POST">
                                        <div class="form-row">
                                          <div class="form-group col-md-6">
                                            <label for="supplierName">Nama Supplier</label>
                                            <select class="form-control" id="supplier_id" name="supplier_id">
                                                <option class="h-25 w-50" value="" disabled>Choose Supplier...</option>
                                                @foreach($suppliers as $s)
                                                    <option class="h-25 w-50" value="{{ $s -> id }}">{{ $s -> supplierName }}</option>
                                                @endforeach
                                            </select>
                                          </div>
                                          <div class="form-group col-md-6">
                                            <label for="noPr">Nomor PR</label>
                                            <input type="text" class="form-control" id="noPr" value="{{ $ap -> orderHead -> noPr }}" readonly>
                                          </div>
                                        </div>
                                        <div class="form-row">
                                          <div class="form-group col-md-6">
                                            <label for="noInvoice">Nomor Invoice</label>
                                            <input type="text" class="form-control" id="noInvoice" placeholder="Input Nomor Invoice">
                                          </div>
                                          <div class="form-group col-md-6">
                                            <label for="nominalInvoice">Nominal Invoice</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp. </div>
                                                </div>
                                                <input type="number" class="form-control" id="nominalInvoice" min="1" step="0.1" placeholder="Input Nominal Invoice">
                                            </div>
                                          </div>
                                        </div>
                                        <div class="form-row">
                                          <div class="form-group col-md-6">
                                            <label for="noFaktur">Nomor Faktur Pajak</label>
                                            <input type="text" class="form-control" id="noFaktur" placeholder="Input Nomor Faktur Pajak">
                                          </div>
                                          <div class="form-group col-md-6">
                                            <label for="noDo">Nomor DO</label>
                                            <input type="text" class="form-control" id="noDo" placeholder="Input Nomor DO">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="additionalInformation">Keterangan (optional)</label>
                                            <textarea class="form-control" name="additionalInformation" id="additionalInformation" placeholder="Input Keterangan..." rows="4"></textarea>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div> 
                            <div class="modal-footer">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        <style>
            /* .tableFixHead          { overflow: auto; height: 250px; }
            .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }
            .my-custom-scrollbar {
            position: relative;
            height: 600px;
            overflow: auto;
            }
            .table-wrapper-scroll-y {
                display: block;
            } */
            th{
                color: white;
            }
            th, td{
                word-wrap: break-word;
                min-width: 160px;
                max-width: 160px;
                text-align: center;
            }
            .table-properties{
                word-wrap: break-word;
                min-width: 120px;
                max-width: 120px;
                text-align: center;
            }
            .table-modal{
                height: 400px;
                overflow-y: auto;
            }
            .table-header{
                position: sticky;
                top: 0;
                z-index: 10;
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
            .modal-backdrop {
                height: 100%;
                width: 100%;
            }
        </style>
        <script>
            function refreshDiv(){
                $('#content').load(location.href + ' #content')
            }
            setInterval(refreshDiv, 60000);

            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 3000);
        </script>
        <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
    @endsection

@else
    @include('../layouts/notAuthorized')
@endif