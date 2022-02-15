@extends('../layouts.base')

@section('title', 'insiden-insurance-SPGR-Notes')

@section('container')
<x-guest-layout>
<div class="row">
    @include('picsite.sidebarpic')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="col" style="margin-top: 15px">
            <div class="jumbotron jumbotron-fluid" >
                <div class="container">
                  <h1 class="display-4"><strong>Rekapitulasi Dana</strong></h1>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/picsite/RekapulasiDana/update/{{$rekap->id}}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-outline-success">Update</button>
                </div>

                <div class="form-group">
                    <label for="Datebox1">Periode Awal</label>
                    <input type="date" class="form-control" value="{{$rekap->DateNote1}}" name="Datebox1" required id="Datebox1" >
                    <br>

                    <label for="Datebox2">Periode Akhir</label>
                    <input type="date" class="form-control" value="{{$rekap->DateNote2}}" name="Datebox2" required id="Datebox2" >

                    <br>
                    <label for="NamaTug_Barge">Nama Tug</label>
                    <input type="text" class="form-control" value="{{$rekap->NamaTug_Barge}}" name="NamaTug_Barge" readonly="readonly" id="NamaTug_Barge" >

                    <br>
                    <label for="Nama_File">Nama File</label>
                    <input list="Nama_Files" name="Nama_File" value="{{$rekap->Nama_File}}" id="Nama_File" class="col-lg-full custom-select custom-select-md">
                    <datalist id="Nama_Files">
                        @if(Auth::user()->cabang == 'Babelan')
                            <option value='Sertifikat Keselamatan'>Sertifikat Keselamatan</option>
                            <option value='Sertifikat Garis Muat'>Sertifikat Garis Muat</option>
                            <option value='Penerbitan 1 Kali Jalan'>Penerbitan 1 Kali Jalan</option>  
                            <option value='Sertifikat Safe Manning'>Sertifikat Safe Manning</option>  
                            <option value='Endorse Surat Laut'>Endorse Surat Laut</option>
                            <option value='Perpanjangan Sertifikat SSCEC'>Perpanjangan Sertifikat SSCEC</option>
                            <option value='Perpanjangan Sertifikat P3K'>Perpanjangan Sertifikat P3K</option>
                            <option value='Biaya Laporan Dok'>Biaya Laporan Dok</option>
                            <option value='PNPB Sertifikat Keselamatan'>PNPB Sertifikat Keselamatan</option>  
                            <option value='PNPB Sertifikat Garis Muat'>PNPB Sertifikat Garis Muat</option>
                            <option value='PNPB Surat Laut'>PNPB Surat Laut</option>
                            <option value='Sertifikat SNPP'>Sertifikat SNPP</option>
                            <option value='Sertifikat Anti Teritip'>Sertifikat Anti Teritip</option>
                            <option value='PNBP SNPP & SNAT'>PNBP SNPP & SNAT</option>
                            <option value='Biaya Survey'>Biaya Survey</option>
                            <option value='PNPB SSCEC'>PNPB SSCEC</option>
                            <option value='BKI Lambung'>BKI Lambung</option>
                            <option value='BKI Mesin'>BKI Mesin</option>
                            <option value='BKI Garis Muat'>BKI Garis Muat</option>
                            <option value='File extra 1'>File extra 1</option>
                            <option value='File extra 2'>File extra 2</option>
                            <option value='File extra 3'>File extra 3</option>
                            <option value='File extra 4'>File extra 4</option>
                            <option value='File extra 5'>File extra 5</option>
                        @endif

                        @if (Auth::user()->cabang == 'Berau')
                            <option value='PNBP Sertifikat Konstruksi'>PNBP Sertifikat Konstruksi</option>
                            <option value='Jasa Urus Sertifikat'>Jasa Urus Sertifikat</option>
                            <option value='PNBP Sertifikat Perlengkapan'>PNBP Sertifikat Perlengkapan</option>
                            <option value='PNBP Sertifikat Radio'>PNBP Sertifikat Radio</option>
                            <option value='PNBP Sertifikat OWS'>PNBP Sertifikat OWS</option>
                            <option value='PNBP Garis Muat'>PNBP Garis Muat</option>
                            <option value='PNBP Pemeriksaan Endorse SL'>PNBP Pemeriksaan Endorse SL</option>
                            <option value='Pemeriksaan Sertifikat'>Pemeriksaan Sertifikat</option>
                            <option value='Marine Inspektor'>Marine Inspektor</option>
                            <option value='Biaya Clearance'>Biaya Clearance</option>
                            <option value='PNBP Master Cable'>PNBP Master Cable</option>
                            <option value='Cover Deck LogBook'>Cover Deck LogBook</option>
                            <option value='Cover Engine LogBook'>Cover Engine LogBook</option>
                            <option value='Exibitum Dect LogBook'>Exibitum Dect LogBook</option>
                            <option value='Exibitum Engine LogBook'>Exibitum Engine LogBook</option>
                            <option value='PNBP Deck Logbook'>PNBP Deck Logbook</option>
                            <option value='PNBP Engine Logbook'>PNBP Engine Logbook</option>
                            <option value='Biaya Docking'>Biaya Docking</option>
                            <option value='Lain-lain'>Lain-lain</option>
                            <option value='Biaya Labuh Tambat'>Biaya Labuh Tambat</option>
                            <option value='Biaya Rambu'>Biaya Rambu</option>
                            <option value='PNBP Pemeriksaan'>PNBP Pemeriksaan</option>
                            <option value='Sertifikat Bebas Sanitasi & P3K'>Sertifikat Bebas Sanitasi & P3K</option>
                            <option value='Sertifikat Garis Muat'>Sertifikat Garis Muat</option>
                            <option value='PNBP SSCEC'>PNBP SSCEC</option>
                            <option value='Ijin Sekali Jalan'>Ijin Sekali Jalan</option>
                            <option value='BKI Lambung'>BKI Lambung</option>
                            <option value='BKI Mesin'>BKI Mesin</option>
                            <option value='BKI Garis Muat'>BKI Garis Muat</option>
                            <option value='File extra 1'>File extra 1 </option>
                            <option value='File extra 2'>File extra 2 </option>
                            <option value='File extra 3'>File extra 3 </option>
                            <option value='File extra 4'>File extra 4 </option>
                            <option value='File extra 5'>File extra 5</option>
                        @endif

                        @if (Auth::user()->cabang == 'Banjarmasin' or Auth::user()->cabang == 'Bunati')
                            <option value='Perjalanan'>Perjalanan</option>
                            <option value='Sertifikat Keselamatan'>Sertifikat Keselamatan</option>
                            <option value='Sertifikat Anti Fauling'>Sertifikat Anti Fauling</option>
                            <option value='Surveyor'>Surveyor</option>
                            <option value='Drawing & Stability'>Drawing & Stability</option>
                            <option value='Laporan Pengeringan'>Laporan Pengeringan</option>
                            <option value='Berita Acara Lambung'>Berita Acara Lambung</option>
                            <option value='Laporan Pemeriksaan Nautis'>Laporan Pemeriksaan Nautis</option>
                            <option value='Laporan Pemeriksaan Anti Faulin'>Laporan Pemeriksaan Anti Faulin</option>
                            <option value='Laporan Pemeriksaan Radio '>Laporan Pemeriksaan Radio </option>
                            <option value='Berita Acara Lambung'>Berita Acara Lambung</option>
                            <option value='Laporan Pemeriksaan SNPP'>Laporan Pemeriksaan SNPP</option>
                            <option value='BKI'>BKI</option>
                            <option value='SNPP Permanen'>SNPP Permanen</option>
                            <option value='SNPP Endorse'>SNPP Endorse</option>
                            <option value='Surat Laut Endorse'>Surat Laut Endorse</option>
                            <option value='Surat Laut Permanen'>Surat Laut Permanen</option>
                            <option value='Compas Seren'>Compas Seren</option>
                            <option value='Keselamatan (Tahunan)'>Keselamatan (Tahunan)</option>
                            <option value='Keselamatan (Pengaturan Dok)'>Keselamatan (Pengaturan Dok)</option>
                            <option value='Keselamatan (Dok)'>Keselamatan (Dok)</option>
                            <option value='Garis Muat'>Garis Muat</option>
                            <option value='Dispensasi ISR'>Dispensasi ISR</option>
                            <option value='Life Raft 1 2 , Pemadam'>Life Raft 1 2 , Pemadam</option>
                            <option value='SSCEC'>SSCEC</option>
                            <option value='Seatrail'>Seatrail</option>
                            <option value='Laporan Pemeriksaan Umum'>Laporan Pemeriksaan Umum</option>
                            <option value='Laporan Pemeriksaan Mesin'>Laporan Pemeriksaan Mesin</option>
                            <option value='Nota Dinas Perubahan Kawasan'>Nota Dinas Perubahan Kawasan</option>
                            <option value='PAS'>PAS</option>
                            <option value='Invoice BKI'>Invoice BKI</option>
                            <option value='Safe Manning'>Safe Manning</option>
                            <option value='BKI Lambung'>BKI Lambung</option>
                            <option value='BKI Mesin'>BKI Mesin</option>
                            <option value='BKI Garis Muat'>BKI Garis Muat</option>
                            <option value='File extra 1' >File extra 1 </option>
                            <option value='File extra 2' >File extra 2 </option>
                            <option value='File extra 3' >File extra 3 </option>
                            <option value='File extra 4' >File extra 4 </option>
                            <option value='File extra 5'>File extra 5</option>
                        @endif

                        @if (Auth::user()->cabang == 'Samarinda' or Auth::user()->cabang == 'Kendari' or Auth::user()->cabang == 'Morosi')
                            <option value="Sertifikat Keselamatan (Perpanjangan)">Sertifikat Keselamatan (Perpanjangan)</option>
                            <option value="Perubahan OK 13 ke OK 1">Perubahan OK 13 ke OK 1</option>
                            <option value="Keselamatan (Tahunan)">Keselamatan (Tahunan)</option>
                            <option value="Keselamatan (Dok)">Keselamatan (Dok)</option>
                            <option value="Keselamatan (Pengaturan Dok)">Keselamatan (Pengaturan Dok)</option>
                            <option value="Keselamatan (Penundaan Dok)">Keselamatan (Penundaan Dok)</option>
                            <option value="Sertifikat Garis Muat">Sertifikat Garis Muat</option>
                            <option value="Laporan Pemeriksaan Garis Muat">Laporan Pemeriksaan Garis Muat</option>
                            <option value="Sertifikat Anti Fauling">Sertifikat Anti Fauling</option>
                            <option value='Surat Laut Permanen'>Surat Laut Permanen</option>
                            <option value='Surat Laut Endorse'>Surat Laut Endorse</option>
                            <option value='Call Sign'>Call Sign</option>
                            <option value='Perubahan Sertifikat Keselamatan'>Perubahan Sertifikat Keselamatan</option>
                            <option value='Perubahan Kawasan Tanpa NotaDin'>Perubahan Kawasan Tanpa NotaDin</option>
                            <option value='SNPP Permanen'>SNPP Permanen</option>
                            <option value='SNPP Endorse'>SNPP Endorse</option>
                            <option value='Laporan Pemeriksaan SNPP'>Laporan Pemeriksaan SNPP</option>
                            <option value='Laporan Pemeriksaan Keselamatan'>Laporan Pemeriksaan Keselamatan</option>
                            <option value='Buku Kesehatan'>Buku Kesehatan</option>
                            <option value='Sertifikat Sanitasi Water & P3K'>Sertifikat Sanitasi Water & P3K</option>
                            <option value='Pengaturan Non ke Klas BKI'>Pengaturan Non ke Klas BKI</option>
                            <option value='Pengaturan Klas BKI (Dok SS)'>Pengaturan Klas BKI (Dok SS)</option>
                            <option value='Surveyor Endorse Tahunan BKI'>Surveyor Endorse Tahunan BKI</option>
                            <option value='PR Supplier bki'>PR Supplier bki</option>
                            <option value='Balik Nama Grosse'>Balik Nama Grosse</option>
                            <option value='Kapal Baru Body (Set Dokumen)'>Kapal Baru Body (Set Dokumen)</option>
                            <option value='Halaman Tambahan Grosse'>Halaman Tambahan Grosse</option>
                            <option value='PNBP & PUP'>PNBP & PUP</option>
                            <option value='Laporan Pemeriksaan Anti Teriti'>Laporan Pemeriksaan Anti Teriti</option>
                            <option value='Surveyor Pengedokan'>Surveyor Pengedokan</option>
                            <option value='Surveyor Penerimaan Klas BKI'>Surveyor Penerimaan Klas BKI</option>
                            <option value='Nota Tagihan Jasa Perkapalan'>Nota Tagihan Jasa Perkapalan</option>
                            <option value='Gambar Kapal Baru (BKI)'>Gambar Kapal Baru (BKI)</option>
                            <option value='Dana Jaminan (CLC)'>Dana Jaminan (CLC)</option>
                            <option value='Surat Ukur Dalam Negeri'>Surat Ukur Dalam Negeri</option>
                            <option value='Penerbitan Sertifikat Kapal Baru'>Penerbitan Sertifikat Kapal Baru</option>
                            <option value='Buku Stabilitas'>Buku Stabilitas</option>
                            <option value='Grosse Akta'>Grosse Akta</option>
                            <option value='Penerbitan Nota Dinas Pertama'>Penerbitan Nota Dinas Pertama</option>
                            <option value='Penerbitan Nota Dinas Kedua'>Penerbitan Nota Dinas Kedua</option>
                            <option value='BKI Lambung'>BKI Lambung</option>
                            <option value='BKI Mesin'>BKI Mesin</option>
                            <option value='BKI Garis Muat'>BKI Garis Muat</option>
                            <option value='File extra 1' >File extra 1</option>
                            <option value='File extra 2' >File extra 2</option>
                            <option value='File extra 3' >File extra 3</option>
                            <option value='File extra 4' >File extra 4</option>
                            <option value='File extra 5'>File extra 5</option>
                        @endif

                        @if (Auth::user()->cabang == 'Jakarta')
                            <option value='PNBP RPT'>PNBP RPT</option>
                            <option value='PPS'>PPS</option>
                            <option value='PNBP Spesifikasi Kapal'>PNBP Spesifikasi Kapal</option>
                            <option value='Anti Fauling Permanen'>Anti Fauling Permanen</option>
                            <option value='PNBP Pemeriksaan Anti Fauling'>PNBP Pemeriksaan Anti Fauling</option>
                            <option value='SNPP Permanen'>SNPP Permanen</option>
                            <option value='Pengesahan Gambar'>Pengesahan Gambar</option>
                            <option value='Surat Laut Permanen'>Surat Laut Permanen</option>
                            <option value='PNBP Surat Laut'>PNBP Surat Laut</option>
                            <option value='PNBP Surat Laut (Ubah Pemilik)'>PNBP Surat Laut (Ubah Pemilik)</option>
                            <option value='CLC Bunker'>CLC Bunker</option>
                            <option value='Nota Dinas Penundaan Dok I'>Nota Dinas Penundaan Dok I</option>
                            <option value='Nota Dinas Penundaan Dok II'>Nota Dinas Penundaan Dok II</option>
                            <option value='Nota Dinas Perubahan Kawasan'>Nota Dinas Perubahan Kawasan</option>
                            <option value='Call Sign'>Call Sign</option>
                            <option value='Perubahan Kepemilikan Kapal'>Perubahan Kepemilikan Kapal</option>
                            <option value='Nota Dinas Bendera (Baru)'>Nota Dinas Bendera (Baru)</option>
                            <option value='PUP Safe Manning'>PUP Safe Manning</option>
                            <option value='Corporate'>Corporate</option>
                            <option value='Dokumen Kapal Asing (Baru)'>Dokumen Kapal Asing (Baru)</option>
                            <option value='Rekomendasi Radio Kapal'>Rekomendasi Radio Kapal</option>
                            <option value='Izin Stasiun Radio Kapal'>Izin Stasiun Radio Kapal</option>
                            <option value='MMSI'>MMSI</option>
                            <option value='PNBP Pemeriksaan Konstruksi'>PNBP Pemeriksaan Konstruksi</option>
                            <option value='OK 1 SKB'>OK 1 SKB</option>
                            <option value='OK 1 SKP'>OK 1 SKP</option>
                            <option value='OK 1 SKR'>OK 1 SKR</option>
                            <option value='Status Hukum Kapal'>Status Hukum Kapal</option>
                            <option value='Autorization Garis Muat'>Autorization Garis Muat</option>
                            <option value='Otorisasi Klas'>Otorisasi Klas</option>
                            <option value='PNBP Otorisasi (AII)'>PNBP Otorisasi (AII)</option>
                            <option value='Halaman Tambah Grosse Akta'>Halaman Tambah Grosse Akta</option>
                            <option value='PNBP Surat Ukur'>PNBP Surat Ukur</option>
                            <option value='Nota Dinas Penundaan Klas BKI SS'>Nota Dinas Penundaan Klas BKI SS</option>
                            <option value='UWILD Pengganti Doking'>UWILD Pengganti Doking</option>
                            <option value='Update Nomor Call Sign'>Update Nomor Call Sign</option>
                            <option value='CLC Badan Kapal'>CLC Badan Kapal</option>
                            <option value='Wreck Removal'>Wreck Removal</option>
                            <option value='Biaya Percepatan Proses' >Biaya Percepatan Proses </option>
                            <option value='BKI Lambung'>BKI Lambung</option>
                            <option value='BKI Mesin'>BKI Mesin</option>
                            <option value='BKI Garis Muat'>BKI Garis Muat</option>
                            <option value='File extra 1' >File extra 1 </option>
                            <option value='File extra 2' >File extra 2 </option>
                            <option value='File extra 3' >File extra 3 </option>
                            <option value='File extra 4' >File extra 4 </option>
                            <option value='File extra 5'>File extra 5</option>
                        @endif
                    </datalist>
                    <br>
                    <br>
                    <label for="Nilai">Nilai Jumlah Di Ajukan</label>
                    <div class="input-group mb-1">
                        <select class="btn btn-outline-secondary" name="mata_uang_nilai">
                            <option selected value="IDR" id="">RP</option>
                        </select>
                        <input type="number" class="form-control" min="1000" max="999999999999" value="{{$rekap->Nilai}}" name="Nilai" required id="Nilai" >
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>
</x-guest-layout>
@endsection