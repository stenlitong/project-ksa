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
            
            $table->date('time_upload1') ->nullable();
            $table->string('status1', 10)->nullable();
            $table->string('reason1', 200) ->nullable();
            $table->string('perjalanan')->nullable();

            $table->date('time_upload2') ->nullable();
            $table->string('status2', 10) ->nullable();
            $table->string('reason2', 200) ->nullable();
            $table->string('sertifikat_keselamatan')->nullable();

            $table->date('time_upload3') ->nullable();
            $table->string('status3', 10)->nullable();
            $table->string('reason3', 200)->nullable();
            $table->string('sertifikat_anti_fauling')->nullable();

            $table->date('time_upload4') ->nullable();
            $table->string('status4', 10)->nullable();
            $table->string('reason4', 200)->nullable();
            $table->string('surveyor')->nullable();

            $table->date('time_upload5') ->nullable();
            $table->string('status5', 10)->nullable();
            $table->string('reason5', 200)->nullable();
            $table->string('drawing&stability')->nullable();
            
            $table->date('time_upload6') ->nullable();
            $table->string('status6', 10)->nullable();
            $table->string('reason6', 200)->nullable();
            $table->string('laporan_pengeringan')->nullable();

            $table->date('time_upload7') ->nullable();
            $table->string('status7', 10)->nullable();
            $table->string('reason7', 200)->nullable();
            $table->string('berita_acara_lambung')->nullable();

            $table->date('time_upload8') ->nullable();
            $table->string('status8', 10)->nullable();
            $table->string('reason8', 200)->nullable();
            $table->string('laporan_pemeriksaan_nautis')->nullable();

            $table->date('time_upload9') ->nullable();
            $table->string('status9', 10)->nullable();
            $table->string('reason9', 200)->nullable();
            $table->string('laporan_pemeriksaan_anti_faulin')->nullable();

            $table->date('time_upload10') ->nullable();
            $table->string('status10', 10)->nullable();
            $table->string('reason10' , 200)->nullable();
            $table->string('laporan_pemeriksaan_radio')->nullable();

            $table->date('time_upload11') ->nullable();
            $table->string('status11', 10)->nullable();
            $table->string('reason11' , 200)->nullable();
            $table->string('laporan_pemeriksaan_snpp')->nullable();

            $table->date('time_upload12') ->nullable();
            $table->string('status12', 10)->nullable();
            $table->string('reason12' , 200)->nullable();
            $table->string('bki')->nullable();

            $table->date('time_upload13') ->nullable();
            $table->string('status13', 10)->nullable();
            $table->string('reason13' , 200)->nullable();
            $table->string('snpp_permanen')->nullable();

            $table->date('time_upload14') ->nullable();
            $table->string('status14', 10)->nullable();
            $table->string('reason14' , 200)->nullable();
            $table->string('snpp_endorse')->nullable();

            $table->date('time_upload15') ->nullable();
            $table->string('status15', 10)->nullable();
            $table->string('reason15' , 200)->nullable();
            $table->string('surat_laut_endorse')->nullable();

            $table->date('time_upload16') ->nullable();
            $table->string('status16', 10)->nullable();
            $table->string('reason16' , 200)->nullable();
            $table->string('surat_laut_permanen')->nullable();

            $table->date('time_upload17') ->nullable();
            $table->string('status17', 10)->nullable();
            $table->string('reason17' , 200)->nullable();
            $table->string('compas_seren')->nullable();

            $table->date('time_upload18') ->nullable();
            $table->string('status18', 10)->nullable();
            $table->string('reason18' , 200)->nullable();
            $table->string('keselamatan_(tahunan)')->nullable();

            $table->date('time_upload19') ->nullable();
            $table->string('status19', 10)->nullable();
            $table->string('reason19' , 200)->nullable();
            $table->string('keselamatan_(pengaturan_dok)')->nullable();

            $table->date('time_upload20') ->nullable();
            $table->string('status20' , 10)->nullable();
            $table->string('reason20' , 200)->nullable();
            $table->string('keselamatan_(dok)')->nullable();

            $table->date('time_upload21') ->nullable();
            $table->string('status21' , 10)->nullable();
            $table->string('reason21' , 200)->nullable();
            $table->string('garis_muat')->nullable();

            $table->date('time_upload22') ->nullable();
            $table->string('status22' , 10)->nullable();
            $table->string('reason22' , 200)->nullable();
            $table->string('dispensasi_isr')->nullable();

            $table->date('time_upload23') ->nullable();
            $table->string('status23' , 10)->nullable();
            $table->string('reason23' , 200)->nullable();
            $table->string('life_raft_1_2_pemadam')->nullable();

            $table->date('time_upload24') ->nullable();
            $table->string('status24' , 10)->nullable();
            $table->string('reason24' , 200)->nullable();
            $table->string('sscec')->nullable();

            $table->date('time_upload25') ->nullable();
            $table->string('status25' , 10)->nullable();
            $table->string('reason25' , 200)->nullable();
            $table->string('seatrail')->nullable();

            $table->date('time_upload26') ->nullable();
            $table->string('status26' , 10)->nullable();
            $table->string('reason26' , 200)->nullable();
            $table->string('laporan_pemeriksaan_umum')->nullable();

            $table->date('time_upload27') ->nullable();
            $table->string('status27' , 10)->nullable();
            $table->string('reason27' , 200)->nullable();
            $table->string('laporan_pemeriksaan_mesin')->nullable();

            $table->date('time_upload28') ->nullable();
            $table->string('status28' , 10)->nullable();
            $table->string('reason28' , 200)->nullable();
            $table->string('nota_dinas_perubahan_kawasan')->nullable();

            $table->date('time_upload29') ->nullable();
            $table->string('status29' , 10)->nullable();
            $table->string('reason29' , 200)->nullable();
            $table->string('PAS')->nullable();

            $table->date('time_upload30') ->nullable();
            $table->string('status30', 10)->nullable();
            $table->string('reason30', 200)->nullable();
            $table->string('invoice_bki')->nullable();

            $table->date('time_upload31') ->nullable();
            $table->string('status31', 10)->nullable();
            $table->string('reason31', 200)->nullable();
            $table->string('safe_manning')->nullable();

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
