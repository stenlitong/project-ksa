<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentJakartasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_jakartas', function (Blueprint $table) {
            $table->id();
            $table->string('cabang')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            
            $table->string('nama_kapal',70)->nullable();
            $table->date('periode_awal')->nullable();
            $table->date('periode_akhir')->nullable();

            $table->dateTime('time_upload1')->nullable();
            $table->string('reason1', 170)->nullable();
            $table->string('status1', 10)->nullable();
            $table->string('pnbp_rpt', 170)->nullable();

            $table->dateTime('time_upload2')->nullable();
            $table->string('reason2', 170)->nullable();
            $table->string('status2', 10)->nullable();
            $table->string('pps', 170)->nullable();

            $table->dateTime('time_upload3')->nullable();
            $table->string('reason3', 170)->nullable();
            $table->string('status3', 10)->nullable();
            $table->string('pnbp_spesifikasi_kapal', 170)->nullable();

            $table->dateTime('time_upload4')->nullable();
            $table->string('reason4', 170)->nullable();
            $table->string('status4', 10)->nullable();
            $table->string('anti_fauling_permanen', 170)->nullable();

            $table->dateTime('time_upload5')->nullable();
            $table->string('reason5', 170)->nullable();
            $table->string('status5', 10)->nullable();
            $table->string('pnbp_pemeriksaan_anti_fauling', 170)->nullable();

            $table->dateTime('time_upload6')->nullable();
            $table->string('reason6', 170)->nullable();
            $table->string('status6', 10)->nullable();
            $table->string('snpp_permanen', 170)->nullable();

            $table->dateTime('time_upload7')->nullable();
            $table->string('reason7', 170)->nullable();
            $table->string('status7', 10)->nullable();
            $table->string('pengesahan_gambar', 170)->nullable();

            $table->dateTime('time_upload8')->nullable();
            $table->string('reason8', 170)->nullable();
            $table->string('status8', 10)->nullable();
            $table->string('surat_laut_permanen', 170)->nullable();

            $table->dateTime('time_upload9')->nullable();
            $table->string('reason9', 170)->nullable();
            $table->string('status9', 10)->nullable();
            $table->string('pnbp_surat_laut', 170)->nullable();

            $table->dateTime('time_upload10')->nullable();
            $table->string('reason10', 170)->nullable();
            $table->string('status10', 10)->nullable();
            $table->string('pnbp_surat_laut_(ubah_pemilik)', 170)->nullable();

            // <!-- belas puluhan -->
            $table->dateTime('time_upload11')->nullable();
            $table->string('reason11', 170)->nullable();
            $table->string('status11', 10)->nullable();
            $table->string('clc_bunker', 170)->nullable();

            $table->dateTime('time_upload12')->nullable();
            $table->string('reason12', 170)->nullable();
            $table->string('status12', 10)->nullable();
            $table->string('nota_dinas_penundaan_dok_i', 170)->nullable();

            $table->dateTime('time_upload13')->nullable();
            $table->string('reason13', 170)->nullable();
            $table->string('status13', 10)->nullable();
            $table->string('nota_dinas_penundaan_dok_ii', 170)->nullable();

            $table->dateTime('time_upload14')->nullable();
            $table->string('reason14', 170)->nullable();
            $table->string('status14', 10)->nullable();
            $table->string('nota_dinas_perubahan_kawasan' , 170)->nullable();

            $table->dateTime('time_upload15')->nullable();
            $table->string('reason15', 170)->nullable();
            $table->string('status15', 10)->nullable();
            $table->string('call_sign', 170)->nullable();

            $table->dateTime('time_upload16')->nullable();
            $table->string('reason16', 170)->nullable();
            $table->string('status16', 10)->nullable();
            $table->string('perubahan_kepemilikan_kapal', 170)->nullable();

            $table->dateTime('time_upload17')->nullable();
            $table->string('reason17', 170)->nullable();
            $table->string('status17', 10)->nullable();
            $table->string('nota_dinas_bendera_(baru)', 170)->nullable();

            $table->dateTime('time_upload18')->nullable();
            $table->string('reason18', 170)->nullable();
            $table->string('status18', 10)->nullable();
            $table->string('pup_safe_manning', 170)->nullable();

            $table->dateTime('time_upload19')->nullable();
            $table->string('reason19', 170)->nullable();
            $table->string('status19', 10)->nullable();
            $table->string('corporate', 170)->nullable();

            $table->dateTime('time_upload20')->nullable();
            $table->string('reason20', 170)->nullable();
            $table->string('status20', 10)->nullable();
            $table->string('dokumen_kapal_asing_(baru)', 170)->nullable();

            // <!-- dua puluhan -->
            $table->dateTime('time_upload21')->nullable();
            $table->string('reason21', 170)->nullable();
            $table->string('status21', 10)->nullable();
            $table->string('rekomendasi_radio_kapal', 170)->nullable();

            $table->dateTime('time_upload22')->nullable();
            $table->string('reason22', 170)->nullable();
            $table->string('status22', 10)->nullable();
            $table->string('izin_stasiun_radio_kapal', 170)->nullable();

            $table->dateTime('time_upload23')->nullable();
            $table->string('reason23', 170)->nullable();
            $table->string('status23', 10)->nullable();
            $table->string('mmsi', 170)->nullable();

            $table->dateTime('time_upload24')->nullable();
            $table->string('reason24', 170)->nullable();
            $table->string('status24', 10)->nullable();
            $table->string('pnbp_pemeriksaan_konstruksi', 170)->nullable();

            $table->dateTime('time_upload25')->nullable();
            $table->string('reason25', 170)->nullable();
            $table->string('status25', 10)->nullable();
            $table->string('ok_1_skb', 170)->nullable();

            $table->dateTime('time_upload26')->nullable();
            $table->string('reason26', 170)->nullable();
            $table->string('status26', 10)->nullable();
            $table->string('ok_1_skp', 170)->nullable();

            $table->dateTime('time_upload27')->nullable();
            $table->string('reason27', 170)->nullable();
            $table->string('status27', 10)->nullable();
            $table->string('ok_1_skr', 170)->nullable();

            $table->dateTime('time_upload28')->nullable();
            $table->string('reason28', 170)->nullable();
            $table->string('status28', 10)->nullable();
            $table->string('status_hukum_kapal', 170)->nullable();

            $table->dateTime('time_upload29')->nullable();
            $table->string('reason29', 170)->nullable();
            $table->string('status29', 10)->nullable();
            $table->string('autorization_garis_muat', 170)->nullable();

            $table->dateTime('time_upload30')->nullable();
            $table->string('reason30', 170)->nullable();
            $table->string('status30', 10)->nullable();
            $table->string('otorisasi_klas', 170)->nullable();

            // <!-- tiga puluhan -->
            $table->dateTime('time_upload31')->nullable();
            $table->string('reason31', 170)->nullable();
            $table->string('status31', 10)->nullable();
            $table->string('pnbp_otorisasi(all)', 170)->nullable();

            $table->dateTime('time_upload32')->nullable();
            $table->string('reason32', 170)->nullable();
            $table->string('status32', 10)->nullable();
            $table->string('halaman_tambah_grosse_akta', 170)->nullable();

            $table->dateTime('time_upload33')->nullable();
            $table->string('reason33', 170)->nullable();
            $table->string('status33', 10)->nullable();
            $table->string('pnbp_surat_ukur', 170)->nullable();

            $table->dateTime('time_upload34')->nullable();
            $table->string('reason34', 170)->nullable();
            $table->string('status34', 10)->nullable();
            $table->string('nota_dinas_penundaan_klas_bki_ss', 170)->nullable();

            $table->dateTime('time_upload35')->nullable();
            $table->string('reason35', 170)->nullable();
            $table->string('status35', 10)->nullable();
            $table->string('uwild_pengganti_doking', 170)->nullable();

            $table->dateTime('time_upload36')->nullable();
            $table->string('reason36', 170)->nullable();
            $table->string('status36', 10)->nullable();
            $table->string('update_nomor_call_sign', 170)->nullable();

            $table->dateTime('time_upload37')->nullable();
            $table->string('reason37', 170)->nullable();
            $table->string('status37', 10)->nullable();
            $table->string('clc_badan_kapal', 170)->nullable();

            $table->dateTime('time_upload38')->nullable();
            $table->string('reason38', 170)->nullable();
            $table->string('status38', 10)->nullable();
            $table->string('wreck_removal', 170)->nullable();

            $table->dateTime('time_upload39') ->nullable();
            $table->string('status39', 10)->nullable();
            $table->string('reason39', 170)->nullable();
            $table->string('biaya_percepatan_proses', 170)->nullable();

            $table->dateTime('time_upload40') ->nullable();
            $table->string('status40', 10)->nullable();
            $table->string('reason40', 170)->nullable();
            $table->string('BKI', 170)->nullable();

            $table->dateTime('time_upload41') ->nullable();
            $table->string('status41', 10)->nullable();
            $table->string('reason41', 170)->nullable();
            $table->string('Lain_Lain1', 170)->nullable();

            $table->dateTime('time_upload42') ->nullable();
            $table->string('status42', 10)->nullable();
            $table->string('reason42', 170)->nullable();
            $table->string('Lain_Lain2', 170)->nullable();

            $table->dateTime('time_upload43') ->nullable();
            $table->string('status43', 10)->nullable();
            $table->string('reason43', 170)->nullable();
            $table->string('Lain_Lain3', 170)->nullable();

            $table->dateTime('time_upload44') ->nullable();
            $table->string('status44', 10)->nullable();
            $table->string('reason44', 170)->nullable();
            $table->string('Lain_Lain4', 170)->nullable();

            $table->dateTime('time_upload45') ->nullable();
            $table->string('status45', 10)->nullable();
            $table->string('reason45', 170)->nullable();
            $table->string('Lain_Lain5', 170)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_jakartas');
    }
}
