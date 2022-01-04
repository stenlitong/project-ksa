<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\documentbanjarmasin;
class CreateBanjarmasindb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banjarmasindb', function (Blueprint $table) {
            $table->id();
            $table->string('cabang', 15)->nullable();
            $table->unsignedBigInteger('user_id');

            $table->string('nama_kapal',70)->nullable();
            $table->date('periode_awal')->nullable();
            $table->date('periode_akhir')->nullable();
            
            $table->dateTime('time_upload1') ->nullable();
            $table->string('status1', 10)->nullable();
            $table->string('reason1', 190) ->nullable();
            $table->string('perjalanan')->nullable();

            $table->dateTime('time_upload2') ->nullable();
            $table->string('status2', 10) ->nullable();
            $table->string('reason2', 190) ->nullable();
            $table->string('sertifikat_keselamatan')->nullable();

            $table->dateTime('time_upload3') ->nullable();
            $table->string('status3', 10)->nullable();
            $table->string('reason3', 190)->nullable();
            $table->string('sertifikat_anti_fauling')->nullable();

            $table->dateTime('time_upload4') ->nullable();
            $table->string('status4', 10)->nullable();
            $table->string('reason4', 190)->nullable();
            $table->string('surveyor')->nullable();

            $table->dateTime('time_upload5') ->nullable();
            $table->string('status5', 10)->nullable();
            $table->string('reason5', 190)->nullable();
            $table->string('drawing&stability')->nullable();
            
            $table->dateTime('time_upload6') ->nullable();
            $table->string('status6', 10)->nullable();
            $table->string('reason6', 190)->nullable();
            $table->string('laporan_pengeringan')->nullable();

            $table->dateTime('time_upload7') ->nullable();
            $table->string('status7', 10)->nullable();
            $table->string('reason7', 190)->nullable();
            $table->string('berita_acara_lambung')->nullable();

            $table->dateTime('time_upload8') ->nullable();
            $table->string('status8', 10)->nullable();
            $table->string('reason8', 190)->nullable();
            $table->string('laporan_pemeriksaan_nautis')->nullable();

            $table->dateTime('time_upload9') ->nullable();
            $table->string('status9', 10)->nullable();
            $table->string('reason9', 190)->nullable();
            $table->string('laporan_pemeriksaan_anti_faulin')->nullable();

            $table->dateTime('time_upload10') ->nullable();
            $table->string('status10', 10)->nullable();
            $table->string('reason10' , 190)->nullable();
            $table->string('laporan_pemeriksaan_radio')->nullable();

            $table->dateTime('time_upload11') ->nullable();
            $table->string('status11', 10)->nullable();
            $table->string('reason11' , 190)->nullable();
            $table->string('laporan_pemeriksaan_snpp')->nullable();

            $table->dateTime('time_upload12') ->nullable();
            $table->string('status12', 10)->nullable();
            $table->string('reason12' , 190)->nullable();
            $table->string('bki')->nullable();

            $table->dateTime('time_upload13') ->nullable();
            $table->string('status13', 10)->nullable();
            $table->string('reason13' , 190)->nullable();
            $table->string('snpp_permanen')->nullable();

            $table->dateTime('time_upload14') ->nullable();
            $table->string('status14', 10)->nullable();
            $table->string('reason14' , 190)->nullable();
            $table->string('snpp_endorse')->nullable();

            $table->dateTime('time_upload15') ->nullable();
            $table->string('status15', 10)->nullable();
            $table->string('reason15' , 190)->nullable();
            $table->string('surat_laut_endorse')->nullable();

            $table->dateTime('time_upload16') ->nullable();
            $table->string('status16', 10)->nullable();
            $table->string('reason16' , 190)->nullable();
            $table->string('surat_laut_permanen')->nullable();

            $table->dateTime('time_upload17') ->nullable();
            $table->string('status17', 10)->nullable();
            $table->string('reason17' , 190)->nullable();
            $table->string('compas_seren')->nullable();

            $table->dateTime('time_upload18') ->nullable();
            $table->string('status18', 10)->nullable();
            $table->string('reason18' , 190)->nullable();
            $table->string('keselamatan_(tahunan)')->nullable();

            $table->dateTime('time_upload19') ->nullable();
            $table->string('status19', 10)->nullable();
            $table->string('reason19' , 190)->nullable();
            $table->string('keselamatan_(pengaturan_dok)')->nullable();

            $table->dateTime('time_upload20') ->nullable();
            $table->string('status20' , 10)->nullable();
            $table->string('reason20' , 190)->nullable();
            $table->string('keselamatan_(dok)')->nullable();

            $table->dateTime('time_upload21') ->nullable();
            $table->string('status21' , 10)->nullable();
            $table->string('reason21' , 190)->nullable();
            $table->string('garis_muat')->nullable();

            $table->dateTime('time_upload22') ->nullable();
            $table->string('status22' , 10)->nullable();
            $table->string('reason22' , 190)->nullable();
            $table->string('dispensasi_isr')->nullable();

            $table->dateTime('time_upload23') ->nullable();
            $table->string('status23' , 10)->nullable();
            $table->string('reason23' , 190)->nullable();
            $table->string('life_raft_1_2_pemadam')->nullable();

            $table->dateTime('time_upload24') ->nullable();
            $table->string('status24' , 10)->nullable();
            $table->string('reason24' , 190)->nullable();
            $table->string('sscec')->nullable();

            $table->dateTime('time_upload25') ->nullable();
            $table->string('status25' , 10)->nullable();
            $table->string('reason25' , 190)->nullable();
            $table->string('seatrail')->nullable();

            $table->dateTime('time_upload26') ->nullable();
            $table->string('status26' , 10)->nullable();
            $table->string('reason26' , 190)->nullable();
            $table->string('laporan_pemeriksaan_umum')->nullable();

            $table->dateTime('time_upload27') ->nullable();
            $table->string('status27' , 10)->nullable();
            $table->string('reason27' , 190)->nullable();
            $table->string('laporan_pemeriksaan_mesin')->nullable();

            $table->dateTime('time_upload28') ->nullable();
            $table->string('status28' , 10)->nullable();
            $table->string('reason28' , 190)->nullable();
            $table->string('nota_dinas_perubahan_kawasan')->nullable();

            $table->dateTime('time_upload29') ->nullable();
            $table->string('status29' , 10)->nullable();
            $table->string('reason29' , 190)->nullable();
            $table->string('PAS')->nullable();

            $table->dateTime('time_upload30') ->nullable();
            $table->string('status30', 10)->nullable();
            $table->string('reason30', 190)->nullable();
            $table->string('invoice_bki')->nullable();

            $table->dateTime('time_upload31') ->nullable();
            $table->string('status31', 10)->nullable();
            $table->string('reason31', 190)->nullable();
            $table->string('safe_manning')->nullable();

            $table->dateTime('time_upload32') ->nullable();
            $table->string('status32', 10)->nullable();
            $table->string('reason32', 180)->nullable();
            $table->string('Lain_Lain1', 180)->nullable();

            $table->dateTime('time_upload33') ->nullable();
            $table->string('status33', 10)->nullable();
            $table->string('reason33', 180)->nullable();
            $table->string('Lain_Lain2', 180)->nullable();

            $table->dateTime('time_upload34') ->nullable();
            $table->string('status34', 10)->nullable();
            $table->string('reason34', 180)->nullable();
            $table->string('Lain_Lain3', 180)->nullable();

            $table->dateTime('time_upload35') ->nullable();
            $table->string('status35', 10)->nullable();
            $table->string('reason35', 180)->nullable();
            $table->string('Lain_Lain4', 180)->nullable();

            $table->dateTime('time_upload36') ->nullable();
            $table->string('status36', 10)->nullable();
            $table->string('reason36', 180)->nullable();
            $table->string('Lain_Lain5', 180)->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('banjarmasindb');
    }
}
