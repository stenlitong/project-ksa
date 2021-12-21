@extends('../layouts.base')

@section('title', 'Pic-admin-page')

@section('container')
<div class="row">
    @include('picadmin.picAdminsidebar')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="row">
            <div class="jumbotron">
                <h1 class="Header-5">Document & Fund Request Review</h1>
                <hr class="my-4">
                <select name="cabang" class="form-select w-25" onchange="window.location = this.value;">
                    <option selected disabled hidden='true' value="">Pilih Cabang</option>
                    <option value="/picadmin/dana?search=All">Semua Cabang</option>
                    <option value="/picadmin/dana?search=Babelan">Babelan</option>
                    <option value="/picadmin/dana?search=Berau">Berau</option>
                    <option value="/picadmin/dana?search=Samarinda">Samarinda</option>
                    <option value="/picadmin/dana?search=Banjarmasin">Banjarmasin</option>
                   </select>
                <br>
                  @error('reasonbox')
                  <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                      Alasan Wajib Diisi
                  </div>
                  @enderror
                  <script>
                    setTimeout(function(){
                    $("div.alert").remove();
                    }, 5000 ); // 5 secs
                  </script>
            <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
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
{{-- Babelan------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ --}}
                      @forelse($document as $doc )
                       @for ( $a = 1 ; $a <= 16 ; $a++)
                       @php
                            $BABELAN = array('sertifikat_keselamatan',
                              'sertifikat_garis_muat','penerbitan_sekali_jalan','sertifikat_safe_manning',
                              'endorse_surat_laut','perpanjangan_sertifikat_sscec','perpanjangan_sertifikat_p3k' ,
                              'biaya_laporan_dok','pnpb_sertifikat_keselamatan','pnpb_sertifikat_garis_muat',
                              'pnpb_surat_laut','sertifikat_snpp','sertifikat_anti_teritip',    
                              'pnbp_snpp&snat','biaya_survey' ,'pnpb_sscec');

                            $names = array('Sertifikat Keselamatan' , 'Sertifikat Garis Muat' , 'Penerbitan 1 Kali Jalan' , 'Sertifikat Safe Manning' ,
                             'Endorse Surat Laut' , 'Perpanjangan Sertifikat SSCEC' , 'Perpanjangan Sertifikat P3K' , 'Biaya Laporan Dok' , 
                             'PNPB Sertifikat Keselamatan' , 'PNPB Sertifikat Garis Muat' , 'PNPB Surat Laut'  , 'Sertifikat SNPP' ,
                              'Sertifikat Anti Teritip' , 'PNBP SNPP & SNAT', 'Biaya Survey' , 'PNPB SSCEC');
                            $time_upload ="time_upload".$a;
                            $stats ="status".$a;
                            $reason = "reason".$a;
                            $date = date('Y-m-28');
                       @endphp
                        @if(empty($doc->$stats))
                        <tr>
                            {{-- agar tidak keluar hasil kosong --}}
                        </tr>
                        @else
                        <tr>
                            <td scope="col">{{ $a }}</td>
                            <td scope="col" id="nama">{{$names[$a-1]}}</td>                                        
                            <td scope="col" id="cabang">{{$doc->cabang}}</td>                                        
                            <td scope="col" id="time">{{$doc ->$time_upload}}</td>                                        
                            <td scope="col" id="status">{{$doc->$stats}}</td>                                      
                            <td scope="col" id="duetime1">{{$date}}</td> 
                            @if ($doc->$stats == "on review")
                            <td scope="col">
                              <div class="form-row">
                                <div class="col-md-auto">
                                  <form method="POST" action="/picadmin/dana/approvedana">
                                  @csrf
                                  <input type="hidden" name='status' value={{$stats}}>
                                  <input type="hidden" name = 'cabang' value={{$doc->cabang}}>
                                  <button type="submit" class="btn btn-outline-success"  >approve</button>
                                  </form>
                                </div>
                                <div class="col-md-auto">
                                  <button type="button" class="btn btn-outline-danger"  data-toggle="modal" data-target="#rejectTitle-{{$reason}}">
                                    Reject File
                                  </button>
                                </div>
                                <div class="col-md-auto">
                                  <form method="post" action="/picadmin/dana/view">
                                    @csrf
                                    <input type="hidden" name = 'cabang' value={{$doc->cabang}}>
                                    <input type="hidden" name='viewdoc' value={{$BABELAN[$a-1]}} />
                                    <button type="submit" name="views3" class="btn btn-dark">view</button>
                                  </form>  
                                </div>
                              </div>
                              
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
                                          <form method="POST" action="/picadmin/dana/rejectdana">
                                            @csrf
                                              <input type="hidden" name='reason' value={{$reason}}>
                                              <input type="hidden" name='status' value={{$stats}}>
                                              <input type="hidden" name ='cabang' value={{$doc->cabang}}>
                                              <div class="form-group">
                                                  <label for="reason">Reason</label>
                                                  <textarea class="form-control" name="reasonbox" required id="reason" rows="3"></textarea>
                                              </div>
                                              <button type="submit" id="submitreject" class="btn btn-danger" style="display: none;">Reject File</button>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="submit" id="submitreject2" class="btn btn-outline-danger">Reject File</button>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  
                              </td>
                              @else
                                <td></td>
                              @endif
                          </tr>
                        @endif
                        @endfor
                        @empty
                          <tr>
                              {{-- nothing --}}
                          </tr>
                        @endforelse
{{-- Berau----------------------------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
                    @forelse($documentberau as $d )
                       @for ( $a = 1 ; $a <= 26 ; $a++)
                       @php
                       $BERAU = array('pnbp_sertifikat_konstruksi','jasa_urus_sertifikat',
                        'pnbp_sertifikat_perlengkapan','pnbp_sertifikat_radio','pnbp_sertifikat_ows',
                        'pnbp_garis_muat','pnbp_pemeriksaan_endorse_sl','pemeriksaan_sertifikat',
                        'marine_inspektor','biaya_clearance','pnbp_master_cable', 
                        'cover_deck_logbook','cover_engine_logbook','exibitum_dect_logbook',
                        'exibitum_engine_logbook','pnbp_deck_logbook','pnbp_engine_logbook',
                        'biaya_docking','lain-lain','biaya_labuh_tambat',
                        'biaya_rambu','pnbp_pemeriksaan','sertifikat_bebas_sanitasi&p3k',
                        'sertifikat_garis_muat','pnpb_sscec','ijin_sekali_jalan');

                        $name = array('PNBP Sertifikat Konstruksi','Jasa Urus Sertifikat','PNBP Sertifikat Perlengkapan',
                                        'PNBP Sertifikat Radio','PNBP Sertifikat OWS','PNBP Garis Muat',
                                        'PNBP Pemeriksaan Endorse SL','Pemeriksaan Sertifikat','Marine Inspektor',
                                        'Biaya Clearance','PNBP Master Cable','Cover Deck LogBook',
                                        'Cover Engine LogBook','Exibitum Dect LogBook','Exibitum Engine LogBook',
                                        'PNBP Deck Logbook','PNBP Engine Logbook','Biaya Docking',
                                        'Lain-lain','Biaya Labuh Tambat','Biaya Rambu',
                                        'PNBP Pemeriksaan','Sertifikat Bebas Sanitasi & P3K','Sertifikat Garis Muat',
                                        'PNBP SSCEC','Ijin Sekali Jalan');
                            $time_upload ="time_upload".$a;
                            $stats ="status".$a;
                            $reason = "reason".$a;
                            $date = date('Y-m-28');
                       @endphp
                        @if(empty($d->$stats))
                        <tr>
                            {{-- agar tidak keluar hasil kosong --}}
                        </tr>
                        @else
                        <tr>
                            <td scope="col">{{ $a }}</td>
                            <td scope="col" id="nama">{{$name[$a-1]}}</td>                                        
                            <td scope="col" id="cabang">{{$d->cabang}}</td>                                        
                            <td scope="col" id="time">{{$d ->$time_upload}}</td>                                        
                            <td scope="col" id="status">{{$d->$stats}}</td>                                      
                            <td scope="col" id="duetime1">{{$date}}</td> 
                            @if ($d->$stats == "on review")
                              <td scope="col">
                                <div class="form-row">
                                  <div class="col-md-auto">
                                    <form method="POST" action="/picadmin/dana/approvedana">
                                      @csrf
                                      <input type="hidden" name='status' value={{$stats}}>
                                      <input type="hidden" name ='cabang' value={{$d->cabang}}>
                                      <button type="submit" class="btn btn-outline-success">approve</button>
                                  </form>
                                  </div>
                                  <div class="col-md-auto">
                                    <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#rejectTitle-{{$reason}}">
                                      Reject File
                                </button>
                                  </div>
                                  <div class="col-md-auto">
                                    <form method="post" action="/picadmin/dana/view">
                                      @csrf
                                      <input type="hidden" name ='cabang' value={{$d->cabang}}>
                                      <input type="hidden" name='viewdoc' value={{$BERAU[$a-1]}} />
                                      <button type="submit" name="views3" class="btn btn-dark">view</button>
                                    </form>
                                  </div>
                                </div>
                                
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
                                          <form method="POST" action="/picadmin/dana/rejectdana">
                                            @csrf
                                              <input type="hidden" name='reason' value={{$reason}}>
                                              <input type="hidden" name='status' value={{$stats}}>
                                              <input type="hidden" name ='cabang' value={{$d->cabang}}>
                                              <div class="form-group">
                                                  <label for="reason">Reason</label>
                                                  <textarea class="form-control" name="reasonbox" required id="reason" rows="3"></textarea>
                                              </div>
                                              <button type="submit" id="submitreject" class="btn btn-danger" style="display: none;">Reject File</button>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="submit" id="submitreject2" class="btn btn-danger">Reject File</button>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                              </td>
                              @else
                                <td></td>
                              @endif
                        </tr>
                        @endif
                        @endfor
                        @empty
                        <tr>
                            {{-- nothing --}}
                        </tr>
                        @endforelse
{{-- Banjarmasin---------------------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
                    @forelse($documentbanjarmasin as $b )
                       @for ( $a = 1 ; $a <= 31 ; $a++)
                       @php
                       $BANJARMASIN = array('perjalanan','sertifikat_keselamatan','sertifikat_anti_fauling','surveyor',
                                            'drawing&stability','laporan_pengeringan','berita_acara_lambung',
                                            'laporan_pemeriksaan_nautis','laporan_pemeriksaan_anti_faulin','laporan_pemeriksaan_radio',
                                            'laporan_pemeriksaan_snpp','bki','snpp_permanen',
                                            'snpp_endorse','surat_laut_endorse','surat_laut_permanen',
                                            'compas_seren','keselamatan_(tahunan)','keselamatan_(pengaturan_dok)',
                                            'keselamatan_(dok)','garis_muat','dispensasi_isr',
                                            'life_raft_1_2_pemadam','sscec','seatrail',
                                            'laporan_pemeriksaan_umum','laporan_pemeriksaan_mesin','nota_dinas_perubahan_kawasan',
                                            'PAS','invoice_bki','safe_manning');

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
                            $time_upload ="time_upload".$a;
                            $stats ="status".$a;
                            $reason = "reason".$a;
                            $date = date('Y-m-28');
                       @endphp
                        @if(empty($b->$stats))
                        <tr>
                            {{-- agar tidak keluar hasil kosong --}}
                        </tr>
                        @else
                        <tr>
                            <td scope="col">{{ $a }}</td>
                            <td scope="col" id="nama">{{$name[$a-1]}}</td>                                        
                            <td scope="col" id="cabang">{{$b->cabang}}</td>                                        
                            <td scope="col" id="time">{{$b ->$time_upload}}</td>                                        
                            <td scope="col" id="status">{{$b->$stats}}</td>                                      
                            <td scope="col" id="duetime1">{{$date}}</td> 
                            @if ($b->$stats == "on review")
                            <td scope="col">
                              <div class="form-row">
                                <div class="col-md-auto">
                                  <form method="POST" action="/picadmin/dana/approvedana">
                                  @csrf
                                  <input type="hidden" name='status' value={{$stats}}>
                                  <input type="hidden" name ='cabang' value={{$b->cabang}}>
                                  <button type="submit" class="btn btn-outline-success">approve</button>
                                </form>
                                </div>
                                <div class="col-md-auto">
                                  <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#rejectTitle-{{$reason}}">
                                    Reject File
                                </button>
                                </div>
                                <div class="col-md-auto">
                                  <form method="post" action="/picadmin/dana/view">
                                    @csrf
                                    <input type="hidden" name ='cabang' value={{$b->cabang}}>
                                    <input type="hidden" name='viewdoc' value={{$BANJARMASIN[$a-1]}} />
                                    <button type="submit" name="views3" class="btn btn-dark">view</button>
                                  </form>
                                </div>
                              </div>
                              
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
                                          <form method="POST" action="/picadmin/dana/rejectdana">
                                            @csrf
                                              <input type="hidden" name='reason' value={{$reason}}>
                                              <input type="hidden" name='status' value={{$stats}}>
                                              <input type="hidden" name ='cabang' value={{$b->cabang}}>
                                              <div class="form-group">
                                                  <label for="reason">Reason</label>
                                                  <textarea class="form-control" name="reasonbox" required id="reason" rows="3"></textarea>
                                              </div>
                                              <button type="submit" id="submitreject" class="btn btn-danger" style="display: none;">Reject File</button>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="submit" id="submitreject2" class="btn btn-danger">Reject File</button>
                                          </form>
                                        </div>
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
                    @empty
                      <tr>
                          {{-- nothing --}}
                      </tr>
                    @endforelse
{{-- Samarinda-------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
                      @forelse($documentsamarinda as $s )
                       @for ( $a = 1 ; $a <= 38 ; $a++)
                       @php
                       $SAMARINDA = array('sertifikat_keselamatan(perpanjangan)','perubahan_ok_13_ke_ok_1',
                                          'keselamatan_(tahunan)','keselamatan_(dok)','keselamatan_(pengaturan_dok)',
                                          'keselamatan_(penundaan_dok)','sertifikat_garis_muat','laporan_pemeriksaan_garis_muat',
                                          'sertifikat_anti_fauling','surat_laut_permanen','surat_laut_endorse',
                                          'call_sign','perubahan_sertifikat_keselamatan','perubahan_kawasan_tanpa_notadin',
                                          'snpp_permanen','snpp_endorse','laporan_pemeriksaan_snpp',
                                          'laporan_pemeriksaan_keselamatan','buku_kesehatan','sertifikat_sanitasi_water&p3k',
                                          'pengaturan_non_ke_klas_bki','pengaturan_klas_bki_(dok_ss)','surveyor_endorse_tahunan_bki',
                                          'pr_supplier_bki','balik_nama_grosse','kapal_baru_body_(set_dokumen)',
                                          'halaman_tambahan_grosse','pnbp&pup','laporan_pemeriksaan_anti_teriti',
                                          'surveyor_pengedokan','surveyor_penerimaan_klas_bki','nota_tagihan_jasa_perkapalan',
                                          'gambar_kapal_baru_(bki)','dana_jaminan_(clc)','surat_ukur_dalam_negeri',
                                          'penerbitan_sertifikat_kapal_baru','buku_stabilitas','grosse_akta');

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
                            $time_upload ="time_upload".$a;
                            $stats ="status".$a;
                            $reason = "reason".$a;
                            $date = date('Y-m-28');
                       @endphp
                        @if(empty($s->$stats))
                        <tr>
                            {{-- agar tidak keluar hasil kosong --}}
                        </tr>
                        @else
                        <tr>
                            <td scope="col">{{ $a }}</td>
                            <td scope="col" id="nama">{{$name[$a-1]}}</td>                                        
                            <td scope="col" id="cabang">{{$s->cabang}}</td>                                        
                            <td scope="col" id="time">{{$s ->$time_upload}}</td>                                        
                            <td scope="col" id="status">{{$s->$stats}}</td>                                      
                            <td scope="col" id="duetime1">{{$date}}</td> 
                            @if ($s->$stats == "on review")
                            <td scope="col">
                              <div class="form-row">
                                <div class="col-md-auto">
                                  <form method="POST" action="/picadmin/dana/approvedana">
                                    @csrf
                                    <input type="hidden" name='status' value={{$stats}}>
                                    <input type="hidden" name = 'cabang' value={{$s->cabang}}>
                                    <button type="submit" class="btn btn-outline-success">approve</button>
                                  </form>
                                </div>
                                <div class="col-md-auto">
                                  <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#rejectTitle-{{$reason}}">
                                    Reject File
                                </button>
                                </div>
                                <div class="col-md-auto">
                                  <form method="post" action="/picadmin/dana/view">
                                    @csrf
                                    <input type="hidden" name ='cabang' value={{$s->cabang}}>
                                    <input type="hidden" name='viewdoc' value={{$SAMARINDA[$a-1]}} />
                                    <button type="submit" name="views3" class="btn btn-dark">view</button>
                                  </form>
                                </div>
                              </div>
                              
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
                                          <form method="POST" action="/picadmin/dana/rejectdana">
                                            @csrf
                                            <input type="hidden" name='reason' value={{$reason}}>
                                            <input type="hidden" name='status' value={{$stats}}>
                                            <input type="hidden" name ='cabang' value={{$s->cabang}}>
                                            <div class="form-group">
                                                <label for="reason">Reason</label>
                                                <textarea class="form-control" name="reasonbox" required id="reason" rows="3"></textarea>
                                              </div>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="submit" id="submitreject2" class="btn btn-danger">Reject File</button>
                                            </form>
                                            </div>
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
                        @empty
                          <tr> 
                            {{-- nothing --}}
                          </tr>
                        @endforelse
                  </tbody>
                </table>
              </div>
        </div>
    </main>
</div>
@endsection