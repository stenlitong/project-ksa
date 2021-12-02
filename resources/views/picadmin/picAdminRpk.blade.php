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
                <select name="cabang" class="form-select w-25" onchange="window.location = this.value;">
                    <option selected disabled hidden='true' value="">Pilih Cabang</option>
                    <option value="/picadmin/rpk?search=All">Semua Cabang</option>
                    <option value="/picadmin/rpk?search=Babelan">Babelan</option>
                    <option value="/picadmin/rpk?search=Berau">Berau</option>
                    <option value="/picadmin/rpk?search=Samarinda">Samarinda</option>
                    <option value="/picadmin/rpk?search=Banjarmasin">Banjarmasin</option>
                   </select>
                <br>
                <table class="table" style="margin-top: 2%">
                  <thead class="thead-dark">
                      <tr>
                          <th scope="col">No.</th>
                          <th scope="col">Nama File</th>
                          <th scope="col">Cabang</th>
                          <th scope="col">Upload Time</th>
                          <th scope="col">status</th>
                          <th scope="col">Due Date</th>
                          <th scope="col">Action</th>
                      </tr>
                  </thead>
                  <tbody>
                    @forelse($docrpk as $d )
                    @for ( $r = 1 ; $r <= 7 ; $r++)
                    @php
                    $nama = array('Surat Keterangan Asal Barang', 'Cargo Manifest',
                                    'Voyage Report/ Term Sheet','Bill of Lading',
                                    'Ijin Olah Gerak Kapal','Docking',
                                    'Surat Keterangan Persiapan Kapal');
                    $time_upload ="time_upload".$r;
                    $stats ="status".$r;
                    $reason = "reason".$r;
                    $date = date('Y-m-28');
                    $approve = "approve".$r;
                    $decline ="decline".$r;
                    @endphp
                    @if(empty($d->$stats))
                        <tr>
                            {{-- agar tidak keluar hasil kosong --}}
                        </tr>
                    @else
                    <tr>
                        <td scope="col">{{ $r }}</td>
                        <td scope="col" id="nama">{{$nama[$r-1]}}</td>                                        
                        <td scope="col" id="cabang">{{$d->cabang}}</td>                                        
                        <td scope="col" id="time">{{$d->$time_upload}}</td>                                        
                        <td scope="col" id="status">{{$d->$stats}}</td>                                      
                        <td scope="col" id="duetime1">{{$date}}</td> 
                        <td scope="col">
                            <form method="POST" action="/picadmin/rpk/update-status">
                                @csrf
                                <input type="hidden" name='status' value={{$stats}}>
                                <input type="hidden" name = 'cabang' value={{$d->cabang}}>
                                    <button type="submit" class="btn btn-success">approve</button>
                            </form>
                                
                                <button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#rejectTitle-{{$reason}}">
                                    Reject File
                                </button>

                                <!-- Modal -->
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
                                                <input type="hidden" name='reason' value={{$reason}}>
                                                <input type="hidden" name='status' value={{$stats}}>
                                                <input type="hidden" name = 'cabang' value={{$d->cabang}}>
                                                <div class="form-group">
                                                    <label for="reason">Reason</label>
                                                    <textarea class="form-control" name="reasonbox" id="reason" rows="3"></textarea>
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
                            <a href="#" target="_blank">view</a>
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
        </div>
    </main>
</div>
@endsection