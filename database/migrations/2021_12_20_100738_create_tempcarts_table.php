<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempcartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tempcarts', function (Blueprint $table) {
            $table->id();
            
            $table->date('tgl_insiden') ->nullable();
            $table->date('tgl_formclaim') ->nullable();
            $table->string('name')->nullable();
            $table->string('item')->nullable();
            $table->string('jenis_incident', 2 )->nullable();
            $table->string('no_FormClaim')->nullable();
            $table->integer('barge')->nullable();
            $table->integer('TSI_TugBoat')->nullable();
            $table->integer('TSI_barge')->nullable();
            $table->integer('deductible')->nullable();
            $table->integer('amount')->nullable();
            $table->string('surveyor')->nullable();
            $table->string('tugBoat')->nullable();
            $table->string('incident')->nullable();
            $table->longText('description')->nullable();

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
        Schema::dropIfExists('tempcarts');
    }
}
