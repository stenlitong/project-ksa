<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\documents;


class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('cabang')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->date('time_upload1')->nullable();
            $table->string('reason1')->nullable();
            $table->string('status1')->nullable();
            $table->string('sertifikat_keselamatan')->nullable();

            $table->date('time_upload2')->nullable();
            $table->string('reason2')->nullable();
            $table->string('status2')->nullable();
            $table->string('sertifikat_garis_muat')->nullable();          

            $table->date('time_upload3')->nullable();
            $table->string('reason3')->nullable();
            $table->string('status3')->nullable();
            $table->string('penerbitan_sekali_jalan')->nullable();

            $table->date('time_upload4')->nullable();
            $table->string('reason4')->nullable();
            $table->string('status4')->nullable();
            $table->string('sertifikat_safe_manning')->nullable();

            $table->date('time_upload5')->nullable();
            $table->string('reason5')->nullable();
            $table->string('status5')->nullable();
            $table->string('endorse_surat_laut')->nullable();

            $table->date('time_upload6')->nullable();
            $table->string('reason6')->nullable();
            $table->string('status6')->nullable();
            $table->string('perpanjangan_sertifikat_sscec')->nullable();

            $table->date('time_upload7')->nullable();
            $table->string('reason7')->nullable();
            $table->string('status7')->nullable();
            $table->string('perpanjangan_sertifikat_p3k')->nullable();     

            $table->date('time_upload8')->nullable();
            $table->string('reason8')->nullable();
            $table->string('status8')->nullable();
            $table->string('biaya_laporan_dok')->nullable();     

            $table->date('time_upload9')->nullable();
            $table->string('reason9')->nullable();
            $table->string('status9')->nullable();
            $table->string('pnpb_sertifikat_keselamatan')->nullable();     

            $table->date('time_upload10')->nullable();
            $table->string('reason10')->nullable();
            $table->string('status10')->nullable();
            $table->string('pnpb_sertifikat_garis_muat')->nullable();      

            $table->date('time_upload11')->nullable();
            $table->string('reason11')->nullable();
            $table->string('status11')->nullable();
            $table->string('pnpb_surat_laut')->nullable();   

            $table->date('time_upload12')->nullable();
            $table->string('reason12')->nullable();
            $table->string('status12')->nullable();
            $table->string('sertifikat_snpp')->nullable();        

            $table->date('time_upload13')->nullable();
            $table->string('reason13')->nullable();
            $table->string('status13')->nullable();
            $table->string('sertifikat_anti_teritip')->nullable();      

            $table->date('time_upload14')->nullable();
            $table->string('reason14')->nullable();
            $table->string('status14')->nullable();
            $table->string('pnbp_snpp&snat')->nullable();       

            $table->date('time_upload15')->nullable();
            $table->string('reason15')->nullable();
            $table->string('status15')->nullable();
            $table->string('biaya_survey')->nullable(); 

            $table->date('time_upload16')->nullable();
            $table->string('reason16')->nullable();
            $table->string('status16')->nullable();
            $table->string('pnpb_sscec')->nullable();

            $table->string('due_time', 25 )->nullable();
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
        Schema::dropIfExists('Documents');
    }
}
