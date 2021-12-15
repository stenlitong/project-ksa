@if(Auth::user()->hasRole('purchasingManager'))

    @extends('../layouts.base')

    @section('title', 'Checklist AP')

    @section('container')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> 

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <div class="row">
            @include('purchasingManager.sidebar')
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @include('../layouts/time')

                <h1 class="text-center">Checklist AP</h1>
                <div class="d-flex">
                    <div class="p-2 mr-auto">
                        <h5>Cabang</h5>
                        <select name="cabang" class="form-select" onchange="window.location = this.value;">
                            <option selected disabled>Pilih Cabang</option>
                            <option value="/purchasing-manager/form-ap/Jakarta" 
                                @php
                                    if($default_branch == 'Jakarta'){
                                        echo('selected');
                                    }
                                @endphp
                            >Jakarta</option>
                            <option value="/purchasing-manager/form-ap/Banjarmasin"
                                @php
                                    if($default_branch == 'Banjarmasin'){
                                        echo('selected');
                                    }
                                @endphp
                            >Banjarmasin</option>
                            <option value="/purchasing-manager/form-ap/Samarinda"
                                @php
                                    if($default_branch == 'Samarinda'){
                                        echo('selected');
                                    }
                                @endphp
                            >Samarinda</option>
                            <option value="/purchasing-manager/form-ap/Bunati"
                                @php
                                    if($default_branch == 'Bunati'){
                                        echo('selected');
                                    }
                                @endphp
                            >Bunati</option>
                            <option value="/purchasing-manager/form-ap/Babelan"
                                @php
                                    if($default_branch == 'Babelan'){
                                        echo('selected');
                                    }
                                @endphp
                            >Babelan</option>
                            <option value="/purchasing-manager/form-ap/Berau"
                                @php
                                    if($default_branch == 'Berau'){
                                        echo('selected');
                                    }
                                @endphp
                            >Berau</option>
                        </select>
                    </div>
                </div>
                
                @error('reason')
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                        Alasan Invalid & Maksimal 180 Karakter
                    </div>
                @enderror

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

                <div class="d-flex justify-content-end">
                    {{ $apList->links() }}
                </div>
            </main>
        </div>


        {{-- Modal Detail --}}
        @foreach($apList as $key => $ap)
            @if(!empty(Session::get('openApListModalWithId')) && Session::get('openApListModalWithId') == $ap -> id)
                <script>
                    let id = {!! json_encode($ap -> id) !!};
                    $(document).ready(function(){
                        $("#detail-" + id).modal('show');
                    });
                </script>
            @endif
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
                            @if(session('openApListModalWithId'))
                                <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                                    Saved Successfully
                                </div>
                            @endif
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
                                    <div>
                                        <tbody>
                                            @for($i = 1 ; $i <= 20 ; $i++)
                                                @php
                                                    // Helper var
                                                    $status = 'status_partial' . $i;
                                                    $uploadTime = 'uploadTime_partial' . $i;
                                                    $description = 'description_partial' . $i;
                                                    $filename = 'doc_partial' . $i;
                                                    $path_to_file = 'path_to_file' . $i;
                                                @endphp
                                                <tr>
                                                    <td>{{ $ap -> $uploadTime }}</td>
                                                    <td>Partial {{ $i }}</td>
                                                    <td>
                                                        @if($ap -> $status == 'On Review')
                                                            <span style="color: gray; font-weight: bold">{{ $ap -> $status }}</span>
                                                        @elseif($ap -> $status == 'Rejected')
                                                            <span style="color: Red; font-weight: bold">{{ $ap -> $status }}</span>
                                                        @else
                                                            <span style="color: green; font-weight: bold">{{ $ap -> $status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $ap -> $description }}</td>
                                                    <td>
                                                        @if($ap -> $status == 'On Review')
                                                            <div class="d-flex justify-content-between">
                                                                <form action="/purchasing-manager/form-ap/download" method="POST" target="_blank">
                                                                    @csrf
                                                                    <input type="hidden" name="filename" value="{{ $filename }}">
                                                                    <input type="hidden" name="apListId" value="{{ $ap -> id }}">
                                                                    <button class="btn_download" type="submit"><span class="icon" data-feather="download"></span></button>
                                                                </form>
                                                                
                                                                {{-- Modal to reject the file --}}
                                                                {{-- <button class="btn btn-danger btn-sm text-white">Reject</button> --}}
                                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#reject-{{ $ap -> id }}-{{ $filename }}">Reject</button>

                                                                {{-- Reject Modal --}}
                                                                <div class="modal fade" id="reject-{{ $ap -> id }}-{{ $filename }}" tabindex="-1" role="dialog" aria-labelledby="reject-orderTitle" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-danger">
                                                                                <h5 class="modal-title text-white" id="rejectTitle">Reject Document</h5>
                                                                            </div>
                                                                            <form method="POST" action="/purchasing-manager/form-ap/reject">
                                                                                @csrf
                                                                                @method('patch')

                                                                                <input type="hidden" name="apListId" value="{{ $ap -> id }}">
                                                                                <input type="hidden" name="statusColumn" value="{{ $status }}">
                                                                                <input type="hidden" name="description" value="{{ $description }}">
                                                                                <input type="hidden" name="default_branch" value="{{ $default_branch }}">

                                                                                <div class="modal-body"> 
                                                                                    <div class="d-flex justify-content-start ml-2">
                                                                                        <label class="text-left" for="reason">Alasan</label>
                                                                                    </div>
                                                                                    <textarea class="form-control" name="reason" id="reason" rows="3" placeholder="Input Alasan" required></textarea>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <form action="/purchasing-manager/form-ap/approve" method="POST">
                                                                    @csrf
                                                                    @method('patch')
                                                                    <input type="hidden" name="statusColumn" value="{{ $status }}">
                                                                    <input type="hidden" name="apListId" value="{{ $ap -> id }}">
                                                                    <input type="hidden" name="default_branch" value="{{ $default_branch }}">
                                                                    <button class="btn btn-success btn-sm text-white">Approve</button>
                                                                </form>
                                                            </div>
                                                        @elseif($ap -> $status == 'Approved')
                                                            <div class="d-flex justify-content-center">
                                                                <form action="/purchasing-manager/form-ap/download" method="POST" target="_blank">
                                                                    @csrf
                                                                    <input type="hidden" name="filename" value="{{ $filename }}">
                                                                    <input type="hidden" name="apListId" value="{{ $ap -> id }}">
                                                                    <input type="hidden" name="pathToFile" value="{{ $path_to_file }}">
                                                                    <button class="btn_download" type="submit"><span class="icon" data-feather="download"></span></button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </div>
                                </table>
                            </div>
                                
                            <div class="mt-4">
                                @forelse($apListDetail as $apDetail)
                                    @if($apDetail['aplist_id'] == $ap -> id)
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="supplierName">Nama Supplier</label>
                                                <input type="text" class="form-control" id="supplierName" value="{{ $apDetail['supplierName'] }}" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="noPr">Nomor PR</label>
                                                <input type="text" class="form-control" id="noPr" value="{{ $ap -> orderHead -> noPr }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="noInvoice">Nomor Invoice</label>
                                                <input type="text" class="form-control" id="noInvoice" placeholder="Input Nomor Invoice" value="{{ $apDetail['noInvoice'] }}" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="nominalInvoice">Nominal Invoice</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="nominalInvoice" placeholder="Input Nominal Invoice" value="Rp. {{ number_format($apDetail['nominalInvoice'], 2, ",", ".") }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="noFaktur">Nomor Faktur Pajak</label>
                                                <input type="text" class="form-control" id="noFaktur" placeholder="Input Nomor Faktur Pajak" value="{{ $apDetail['noFaktur'] }}" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="noDo">Nomor DO</label>
                                                <input type="text" class="form-control" id="noDo" placeholder="Input Nomor DO" value="{{ $apDetail['noDo'] }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="additionalInformation">Keterangan (optional)</label>
                                            <textarea class="form-control" name="additionalInformation" id="additionalInformation" placeholder="Input Keterangan..." value="{{ $apDetail['additionalInformation'] }}" rows="4" readonly></textarea>
                                        </div>
                                    @endif
                                @empty
                                    <div class="d-flex justify-content-center">
                                        <h5>No Data Found.</h5>
                                    </div>
                                @endforelse
                            </div>
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
            .btn_download{
                background: none;
                border: none;
            }
        </style>

        {{-- <script>
            $(document).ready(function(){
                $("#detail-1").modal('show');
            });
        </script> --}}

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