@extends('../layouts.base')

@section('title', 'Pic-admin-page')

@section('container')
<div class="row">
    @include('picadmin.picAdminsidebar')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="row">
            <div class="jumbotron">
                <h1 class="Header-5">RPK Review</h1>
                <hr class="my-4">

                <div class="form-row">
                    {{-- cabang filter --}}
                    <div class="col-md-auto">
                        <select name="cabang" class="form-select" placeholder="Pilih Cabang" onchange="window.location = this.value;">
                            <option selected disabled hidden='true' value="">Pilih Cabang</option>
                            <option value="/picadmin/rpk?search=All">Semua Cabang</option>
                            <option value="/picadmin/rpk?search=Babelan">Babelan</option>
                            <option value="/picadmin/rpk?search=Berau">Berau</option>
                            <option value="/picadmin/rpk?search=Samarinda">Samarinda</option>
                            <option value="/picadmin/rpk?search=Banjarmasin">Banjarmasin</option>
                            <option value="/picadmin/dana?search=Jakarta">Jakarta</option>
                            <option value="/picadmin/dana?search=Bunati">Bunati</option>
                            <option value="/picadmin/dana?search=Kendari">Kendari</option>
                            <option value="/picadmin/dana?search=Morosi">Morosi</option>
                        </select>
                    </div>
                    {{-- search bar --}}
                    <div class="col-md-auto">
                        <form method="GET" action="/picadmin/RPK/search" role="search">
                            <div class="auto-cols-auto">
                                <div class="col" style="margin-left:-1%" >
                                    <label class="sr-only" for="search_kapal">Nama Kapal</label>
                                    <div class="input-group">
                                    {{-- <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                            </svg>
                                        </div>
                                    </div> --}}
                                    <input type="text" style="text-transform: uppercase;" name="search_kapal" id="search_kapal" class="form-control" placeholder="Search Nama Kapal" autofocus>
                                    <button type="submit" class="btn btn-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                          </svg> 
                                    </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                @error('reasonbox')
                  <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                      Alasan Wajib Diisi
                  </div>
                @enderror
                <table class="table" style="margin-top: 2%">
                  <thead class="thead-dark">
                      <tr>
                        <th>Time Uploaded</th>
                        <th>Cabang</th>
                        <th>Nama Kapal</th>
                        <th>Periode (Y-M-D)</th>
                        <th>Nama File</th>
                        <th>Jenis File</th>
                        <th>Status</th>
                        <th>Reason</th>
                        <th>Action</th>
                      </tr>
                  </thead>
                  
                  <tbody>
                    @forelse($docrpk as $d )
                    @for ( $r = 1 ; $r <= 7 ; $r++)
                    @php
                    $RPK = array('surat_barang', 'cargo_manifest',
                                'voyage','bill_lading',
                                'gerak_kapal','docking',
                                'surat_kapal');
                    $names = array('Surat Keterangan Asal Barang', 'Cargo Manifest',
                                    'Voyage Report/ Term Sheet','Bill of Lading',
                                    'Ijin Olah Gerak Kapal','Docking',
                                    'Surat Keterangan Persiapan Kapal');
                    $time_upload ="time_upload".$r;
                    $stats ="status".$r;
                    $reason = "reason".$r;
                    $date = date('Y-m-28');
                    $scan = $RPK[$r-1];
                    @endphp
                    @if(empty($d->$stats))
                        <tr>
                            {{-- agar tidak keluar hasil kosong --}}
                        </tr>
                    @else
                    <tr>
                        @if ($d->$stats == "rejected")
                            <td class="table-danger">{{ $d->created_at }}</td>
                            <td class="table-danger"><strong>{{ $d->cabang }}</strong></td>
                            <td class="table-danger" style="text-transform: uppercase;" id="namakapal">{{$d->nama_kapal}}</td>                                        
                            <td class="table-danger" id="periode"><strong>{{$d->periode_awal}} To {{$d->periode_akhir}}</strong></td>                                   
                            <td class="table-danger" id="namafile">{{$names[$r-1]}}</td>   
                            <td class="table-secondary" id="jenisfile"><strong>RPK</strong></td>    
                            <td class="table-danger" id="status">{{$d->$stats}}</td>                                      
                            <td class="table-danger" id="reason">{{$d->$reason}}</td>
                        @elseif ($d->$stats == "approved")
                            <td class="table-success">{{ $d->created_at }}</td>
                            <td class="table-success"><strong>{{ $d->cabang }}</strong></td>
                            <td class="table-success" style="text-transform: uppercase;" id="namakapal">{{$d->nama_kapal}}</td>                                        
                            <td class="table-success" id="periode"><strong>{{$d->periode_awal}} To {{$d->periode_akhir}}</strong></td>                                   
                            <td class="table-success" id="namafile">{{$names[$r-1]}}</td>     
                            <td class="table-secondary" id="jenisfile"><strong>RPK</strong></td>     
                            <td class="table-success" id="status">{{$d->$stats}}</td>                                      
                            <td class="table-success" id="reason">{{$d->$reason}}</td>
                        @endif
                        @if ($d->$stats == "on review")
                        <td class="table-warning"><strong>{{ $d->created_at }}</strong></td>
                        <td class="table-warning"><strong>{{ $d->cabang }}</strong></td>
                        <td class="table-warning" style="text-transform: uppercase;" id="namakapal">{{$d->nama_kapal}}</td>                                        
                        <td class="table-warning" id="periode"><strong>{{$d->periode_awal}} To {{$d->periode_akhir}}</strong></td>                                   
                        <td class="table-warning" id="namafile">{{$names[$r-1]}}</td>     
                        <td class="table-secondary" id="jenisfile"><strong>RPK</strong></td>     
                        <td class="table-warning" id="status">{{$d->$stats}}</td>                                      
                        <td class="table-warning" id="reason">{{$d ->$reason}}</td>       
                        <td scope="col">
                            <div class="form-row">
                                {{-- approve button --}}
                                {{-- check if cabang is banjarmasin --}}
                                @if($d->cabang == 'Banjarmasin' or $d->cabang == 'Bunati')
                                <div class="col-md-auto">
                                    <form method="POST" action="/picadmin/rpk/update-status">
                                        @csrf
                                        <input type="hidden" name='viewdocrpk' value={{$RPK[$r-1]}} />
                                            <input type="hidden" name='result' value={{$d->$scan}} />
                                            <input type="hidden" name='status' value={{$stats}}>
                                            <input type="hidden" name='cabang' value={{$d->cabang}}>
                                        <button type="submit" class="btn btn-outline-success">Approve File</button>
                                    </form>
                                </div>
                                @else
                                    <div class="col-md-auto">
                                        <button type="button" class="btn btn-outline-success"  data-toggle="modal" data-target="#ApproveTitle-{{$reason}}">
                                            Approve File
                                        </button>
                                    </div>
                                @endif
                                {{-- reject button --}}
                                    <div class="col-md-auto">
                                        <button type="button" class="btn btn-outline-danger"  data-toggle="modal" data-target="#rejectTitle-{{$reason}}">
                                            Reject File
                                        </button>
                                    </div>
                                {{-- View button --}}
                                    <div class="col-md-auto">
                                        <form method="post" action="/picadmin/rpk/view" target="_blank">
                                            @csrf
                                            <input type="hidden" name ='tipefile' value='RPK'>
                                            <input type="hidden" name = 'kapal_nama' value={{$d->nama_kapal}}>
                                            <input type="hidden" name='viewdocrpk' value={{$RPK[$r-1]}} />
                                            <input type="hidden" name='result' value={{$d->$scan}} />
                                            <input type="hidden" name='cabang' value={{$d->cabang}}>
                                            <button type="submit" name="views3" class="btn btn-dark">view</button>
                                        </form>
                                    </div>
                            </div>

                        <!-- Modal Approve-->
                            <div class="modal fade" id="ApproveTitle-{{$reason}}" tabindex="-1" role="dialog" aria-labelledby="ApproveTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Approve Document ?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="/picadmin/rpk/update-status">
                                            @csrf
                                            <input type="hidden" name='viewdocrpk' value={{$RPK[$r-1]}} />
                                            <input type="hidden" name='result' value={{$d->$scan}} />
                                            <input type="hidden" name='status' value={{$stats}}>
                                            <input type="hidden" name='reason' value={{$reason}}>
                                            <input type="hidden" name='cabang' value={{$d->cabang}}>
                                            <div class="form-group">
                                                <label for="reason">Reason</label>
                                                <textarea class="form-control" name="reasonbox" required id="reason" rows="3"></textarea>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-outline-success">Approve File</button>
                                    </div>
                                    </form>
                                </div>
                                </div>
                            </div>
                        <!-- Modal reject-->
                            <div class="modal fade" id="rejectTitle-{{$reason}}" tabindex="-1" role="dialog" aria-labelledby="rejectTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Reject Document ?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="/picadmin/rpk/rejectrpk">
                                            @csrf
                                            <input type="hidden" name='viewdocrpk' value={{$RPK[$r-1]}} />
                                            <input type="hidden" name='result' value={{$d->$scan}} />
                                            <input type="hidden" name='status' value={{$stats}}>
                                            <input type="hidden" name='reason' value={{$reason}}>
                                            <input type="hidden" name = 'cabang' value={{$d->cabang}}>
                                            <div class="form-group">
                                                <label for="reason">Reason</label>
                                                <textarea class="form-control" name="reasonbox" required id="reason" rows="3"></textarea>
                                            </div>
                                            {{-- <button type="submit" id="submitreject" class="btn btn-danger" style="display: none">Reject File</button> --}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" id="submitreject2" class="btn btn-danger">Reject File</button>
                                    </div>
                                    </form>
                                </div>
                                </div>
                            </div>
                        </td>
                        @else
                            <td> </td>
                        @endif
                        </tr>
                        @endif
                        @endfor
                            <tr>
                                <td> </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
              </div>
        </div>
    </main>
</div>
@endsection