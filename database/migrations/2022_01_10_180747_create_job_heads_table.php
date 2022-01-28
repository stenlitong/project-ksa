<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_heads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('Headjasa_id')->nullable();
            $table->date('jrDate')->nullable();
            $table->string('noJr')->nullable();
            $table->string('created_by')->nullable();
            $table->string('check_by')->nullable();
            $table->string('cabang')->nullable();
            $table->string('status')->nullable();
            $table->string('descriptions')->nullable();
            $table->string('reason')->nullable();
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
        Schema::dropIfExists('job_heads');
    }
}
