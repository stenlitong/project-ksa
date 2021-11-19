<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\documentsamarinda;
class CreateSamarindadb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('samarindadb', function (Blueprint $table) {
            $table->id();
            $table->string('cabang', 10)->nullable();
            $table->unsignedBigInteger('user_id');
           
            $table->date('time_upload1')->nullable();
            $table->string('status1', 10)->nullable();
            $table->string('reason1', 180)->nullable();
            $table->string('sertifikat_keselamatan(perpanjangan)', 180)->nullable();

            $table->date('time_upload2')->nullable();
            $table->string('status2', 10)->nullable();
            $table->string('reason2', 180)->nullable();
            $table->string('perubahan_ok_13_ke_ok_1', 180)->nullable();

            $table->date('time_upload3') ->nullable();
            $table->string('status3',  10)->nullable();
            $table->string('reason3', 180)->nullable();
            $table->string('keselamatan_(tahunan)', 180)->nullable();

            $table->date('time_upload4') ->nullable();
            $table->string('status4', 10)->nullable();
            $table->string('reason4', 180)->nullable();
            $table->string('keselamatan_(dok)', 180)->nullable();

            $table->date('time_upload5') ->nullable();
            $table->string('status5', 10)->nullable();
            $table->string('reason5', 180)->nullable();
            $table->string('keselamatan_(pengaturan_dok)', 180)->nullable();

            $table->date('time_upload6') ->nullable();
            $table->string('status6', 10)->nullable();
            $table->string('reason6', 180)->nullable();
            $table->string('keselamatan_(penundaan_dok)', 180)->nullable();

            $table->date('time_upload7') ->nullable();
            $table->string('status7', 10)->nullable();
            $table->string('reason7', 180)->nullable();
            $table->string('sertifikat_garis_muat', 180)->nullable();

            $table->date('time_upload8') ->nullable();
            $table->string('status8', 10)->nullable();
            $table->string('reason8', 180)->nullable();
            $table->string('laporan_pemeriksaan_garis_muat', 180)->nullable();

            $table->date('time_upload9') ->nullable();
            $table->string('status9', 10)->nullable();
            $table->string('reason9', 180)->nullable();
            $table->string('sertifikat_anti_fauling', 180)->nullable();

            $table->date('time_upload10') ->nullable();
            $table->string('status10', 10)->nullable();
            $table->string('reason10', 180)->nullable();
            $table->string('surat_laut_permanen', 180)->nullable();

            $table->date('time_upload11') ->nullable();
            $table->string('status11', 10)->nullable();
            $table->string('reason11', 180)->nullable();
            $table->string('surat_laut_endorse', 180)->nullable();

            $table->date('time_upload12') ->nullable();
            $table->string('status12', 10)->nullable();
            $table->string('reason12', 180)->nullable();
            $table->string('call_sign', 180)->nullable();

            $table->date('time_upload13') ->nullable();
            $table->string('status13', 10)->nullable();
            $table->string('reason13', 180)->nullable();
            $table->string('perubahan_sertifikat_keselamatan', 180)->nullable();

            $table->date('time_upload14') ->nullable();
            $table->string('status14', 10)->nullable();
            $table->string('reason14', 180)->nullable();
            $table->string('perubahan_kawasan_tanpa_notadin', 180)->nullable();

            $table->date('time_upload15') ->nullable();
            $table->string('status15', 10)->nullable();
            $table->string('reason15', 180)->nullable();
            $table->string('snpp_permanen', 180)->nullable();

            $table->date('time_upload16') ->nullable();
            $table->string('status16', 10)->nullable();
            $table->string('reason16', 180)->nullable();
            $table->string('snpp_endorse', 180)->nullable();

            $table->date('time_upload17') ->nullable();
            $table->string('status17', 10)->nullable();
            $table->string('reason17', 180)->nullable();
            $table->string('laporan_pemeriksaan_snpp', 180)->nullable();;

            $table->date('time_upload18') ->nullable();
            $table->string('status18', 10)->nullable();
            $table->string('reason18', 180)->nullable();
            $table->string('laporan_pemeriksaan_keselamatan', 180)->nullable();

            $table->date('time_upload19') ->nullable();
            $table->string('status19', 10)->nullable();
            $table->string('reason19', 180)->nullable();
            $table->string('buku_kesehatan', 180)->nullable();

            $table->date('time_upload20') ->nullable();
            $table->string('status20', 10)->nullable();
            $table->string('reason20' , 180)->nullable();
            $table->string('sertifikat_sanitasi_water&p3k', 180)->nullable();

            $table->date('time_upload21') ->nullable();
            $table->string('status21', 10)->nullable();
            $table->string('reason21' , 180)->nullable();
            $table->string('pengaturan_non_ke_klas_bki', 180)->nullable();

            $table->date('time_upload22') ->nullable();
            $table->string('status22', 10)->nullable();
            $table->string('reason22' , 180)->nullable();
            $table->string('pengaturan_klas_bki_(dok_ss)', 180)->nullable();

            $table->date('time_upload23') ->nullable();
            $table->string('status23', 10)->nullable();
            $table->string('reason23' , 180)->nullable();
            $table->string('surveyor_endorse_tahunan_bki', 180)->nullable();

            $table->date('time_upload24') ->nullable();
            $table->string('status24', 10)->nullable();
            $table->string('reason24' , 180)->nullable();
            $table->string('pr_supplier_bki', 180)->nullable();

            $table->date('time_upload25') ->nullable();
            $table->string('status25', 10)->nullable();
            $table->string('reason25' , 180)->nullable();
            $table->string('balik_nama_grosse', 180)->nullable();

            $table->date('time_upload26') ->nullable();
            $table->string('status26', 10)->nullable();
            $table->string('reason26' , 180)->nullable();
            $table->string('kapal_baru_body_(set_dokumen)', 180)->nullable();

            $table->date('time_upload27') ->nullable();
            $table->string('status27', 10)->nullable();
            $table->string('reason27' , 180)->nullable();
            $table->string('halaman_tambahan_grosse', 180)->nullable();

            $table->date('time_upload28') ->nullable();
            $table->string('status28', 10)->nullable();
            $table->string('reason28' , 180)->nullable();
            $table->string('pnbp&pup', 180)->nullable();

            $table->date('time_upload29') ->nullable();
            $table->string('status29', 10)->nullable();
            $table->string('reason29' , 180)->nullable();
            $table->string('laporan_pemeriksaan_anti_teriti', 180)->nullable();

            $table->date('time_upload30') ->nullable();
            $table->string('status30', 10)->nullable();
            $table->string('reason30', 180)->nullable();
            $table->string('surveyor_pengedokan', 180)->nullable();

            $table->date('time_upload31') ->nullable();
            $table->string('status31', 10)->nullable();
            $table->string('reason31', 180)->nullable();
            $table->string('surveyor_penerimaan_klas_bki', 180)->nullable();

            $table->date('time_upload32') ->nullable();
            $table->string('status32', 10)->nullable();
            $table->string('reason32', 180)->nullable();
            $table->string('nota_tagihan_jasa_perkapalan', 180)->nullable();

            $table->date('time_upload33') ->nullable();
            $table->string('status33', 10)->nullable();
            $table->string('reason33', 180)->nullable();
            $table->string('gambar_kapal_baru_(bki)', 180)->nullable();

            $table->date('time_upload34') ->nullable();
            $table->string('status34', 10)->nullable();
            $table->string('reason34', 180)->nullable();
            $table->string('dana_jaminan_(clc)', 180)->nullable();

            $table->date('time_upload35') ->nullable();
            $table->string('status35', 10)->nullable();
            $table->string('reason35', 180)->nullable();
            $table->string('surat_ukur_dalam_negeri', 180)->nullable();

            $table->date('time_upload36') ->nullable();
            $table->string('status36', 10)->nullable();
            $table->string('reason36', 180)->nullable();
            $table->string('penerbitan_sertifikat_kapal_baru', 180)->nullable();

            $table->date('time_upload37') ->nullable();
            $table->string('status37', 10)->nullable();
            $table->string('reason37', 180)->nullable();
            $table->string('buku_stabilitas', 180)->nullable();

            $table->date('time_upload38') ->nullable();
            $table->string('status38', 10)->nullable();
            $table->string('reason38', 180)->nullable();
            $table->string('grosse_akta', 180)->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('due_time', 16)->nullable();
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
        Schema::dropIfExists('samarindadb');
    }
}
