<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoteSpgrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_spgrs', function (Blueprint $table) {
            $table->id();
            $table->date('DateNote')->nullable();
            $table->string('No_SPGR')->nullable();
            $table->string('No_FormClaim')->nullable();
            $table->string('Nama_Kapal')->nullable();
            $table->string('status_pembayaran')->nullable();
            $table->decimal('Nilai', 14, 2)->nullable();
            $table->decimal('Nilai_Claim', 14, 2)->nullable();
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
        Schema::dropIfExists('note_spgrs');
    }
}
