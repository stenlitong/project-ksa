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
            $table->unsigneddecimal('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->date('tgl_insiden') ->nullable();
            $table->date('tgl_formclaim') ->nullable();
            $table->string('name')->nullable();
            $table->string('item')->nullable();
            $table->string('jenis_incident', 2 )->nullable();
            $table->string('no_FormClaim')->nullable();
            $table->string('barge')->nullable();
            $table->decimal('TSI_TugBoat', 14, 2)->nullable();
            $table->decimal('TSI_barge', 14, 2)->nullable();
            $table->decimal('deductible', 14, 2)->nullable();
            $table->decimal('amount', 14, 2)->nullable();
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
