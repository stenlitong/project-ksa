<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\formclaims;

class Formclaim extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formclaim', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tgl_insiden') ->nullable();
            $table->date('tgl_formclaim') ->nullable();
            $table->string('name')->nullable();
            $table->string('item')->nullable();
            $table->string('jenis_incident', 2 )->nullable();
            $table->string('no_FormClaim')->nullable();
            $table->integer('TOW')->nullable();
            $table->integer('total_sum_insurade')->nullable();
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
        //
    }
}
