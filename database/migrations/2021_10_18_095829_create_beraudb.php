<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\documentberau;

class CreateBeraudb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beraudb', function (Blueprint $table) {
            $table->id();
            $table->string('cabang', 15)->nullable();
            $table->unsignedBigInteger('user_id');

            $table->string('nama_kapal',100)->nullable();
            $table->date('periode_awal')->nullable();
            $table->date('periode_akhir')->nullable();
            
            $table->date('time_upload1') ->nullable();
            $table->string('status1', 10)->nullable();
            $table->string('reason1')->nullable();
            $table->string('pnbp_sertifikat_konstruksi')->nullable();

            $table->date('time_upload2') ->nullable();
            $table->string('status2', 10)->nullable();
            $table->string('reason2')->nullable();
            $table->string('jasa_urus_sertifikat')->nullable();

            $table->date('time_upload3') ->nullable();
            $table->string('status3', 10)->nullable();
            $table->string('reason3')->nullable();
            $table->string('pnbp_sertifikat_perlengkapan')->nullable();

            $table->date('time_upload4') ->nullable();
            $table->string('status4', 10)->nullable();
            $table->string('reason4')->nullable();
            $table->string('pnbp_sertifikat_radio')->nullable();

            $table->date('time_upload5') ->nullable();
            $table->string('status5', 10)->nullable();
            $table->string('reason5')->nullable();
            $table->string('pnbp_sertifikat_ows')->nullable();

            $table->date('time_upload6') ->nullable();
            $table->string('status6', 10)->nullable();
            $table->string('reason6')->nullable();
            $table->string('pnbp_garis_muat')->nullable();
            
            $table->date('time_upload7') ->nullable();
            $table->string('status7', 10)->nullable();
            $table->string('reason7')->nullable();
            $table->string('pnbp_pemeriksaan_endorse_sl')->nullable();

            $table->date('time_upload8') ->nullable();
            $table->string('status8', 10)->nullable();
            $table->string('reason8')->nullable();
            $table->string('pemeriksaan_sertifikat')->nullable();

            $table->date('time_upload9') ->nullable();
            $table->string('status9', 10)->nullable();
            $table->string('reason9')->nullable();
            $table->string('marine_inspektor')->nullable();

            $table->date('time_upload10') ->nullable();
            $table->string('status10', 10)->nullable();
            $table->string('reason10')->nullable();
            $table->string('biaya_clearance')->nullable() ;

            $table->date('time_upload11') ->nullable();
            $table->string('status11', 10)->nullable();
            $table->string('reason11')->nullable();
            $table->string('pnbp_master_cable')->nullable() ; 

            $table->date('time_upload12') ->nullable();
            $table->string('status12', 10)->nullable();
            $table->string('reason12')->nullable();
            $table->string('cover_deck_logbook')->nullable() ;

            $table->date('time_upload13') ->nullable();
            $table->string('status13', 10)->nullable();
            $table->string('reason13')->nullable();
            $table->string('cover_engine_logbook')->nullable() ;

            $table->date('time_upload14') ->nullable();
            $table->string('status14', 10)->nullable();
            $table->string('reason14')->nullable();
            $table->string('exibitum_dect_logbook')->nullable() ;

            $table->date('time_upload15') ->nullable();
            $table->string('status15', 10)->nullable();
            $table->string('reason15')->nullable();
            $table->string('exibitum_engine_logbook')->nullable() ;

            $table->date('time_upload16') ->nullable();
            $table->string('status16', 10)->nullable();
            $table->string('reason16')->nullable();
            $table->string('pnbp_deck_logbook')->nullable() ;

            $table->date('time_upload17') ->nullable();
            $table->string('status17', 10)->nullable();
            $table->string('reason17')->nullable();
            $table->string('pnbp_engine_logbook')->nullable() ;

            $table->date('time_upload18') ->nullable();
            $table->string('status18', 10)->nullable();
            $table->string('reason18')->nullable();
            $table->string('biaya_docking')->nullable() ;

            $table->date('time_upload19') ->nullable();
            $table->string('status19', 10)->nullable();
            $table->string('reason19')->nullable();
            $table->string('lain-lain')->nullable();

            $table->date('time_upload20') ->nullable();
            $table->string('status20', 10)->nullable();
            $table->string('reason20')->nullable();
            $table->string('biaya_labuh_tambat')->nullable();

            $table->date('time_upload21') ->nullable();
            $table->string('status21', 10)->nullable();
            $table->string('reason21')->nullable();
            $table->string('biaya_rambu')->nullable();

            $table->date('time_upload22') ->nullable();
            $table->string('status22', 10)->nullable();
            $table->string('reason22')->nullable();
            $table->string('pnbp_pemeriksaan')->nullable();

            $table->date('time_upload23') ->nullable();
            $table->string('status23', 10)->nullable();
            $table->string('reason23')->nullable();
            $table->string('sertifikat_bebas_sanitasi&p3k')->nullable(); 

            $table->date('time_upload24') ->nullable();
            $table->string('status24', 10)->nullable();
            $table->string('reason24')->nullable();
            $table->string('sertifikat_garis_muat')->nullable();

            $table->date('time_upload25') ->nullable();
            $table->string('status25', 10)->nullable();
            $table->string('reason25')->nullable();
            $table->string('pnpb_sscec')->nullable();

            $table->date('time_upload26') ->nullable();
            $table->string('status26', 10)->nullable();
            $table->string('reason26')->nullable();
            $table->string('ijin_sekali_jalan')->nullable();
            
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
        Schema::dropIfExists('beraudb');
    }
}
