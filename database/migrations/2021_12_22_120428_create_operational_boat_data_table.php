<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationalBoatDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operational_boat_data', function (Blueprint $table) {
            $table->id();

            // Primary Data
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('tug_id');
            $table->foreign('tug_id')->references('id')->on('tugs')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('barge_id')->nullable();
            $table->foreign('barge_id')->references('id')->on('barges')->onDelete('cascade')->onUpdate('cascade');

            $table->bigInteger('jetty');
            $table->string('taskType', 30);
            $table->string('status', 15)->default('On Going');

            // Secondary Data
            $table->string('from', 30)->nullable();
            $table->string('to', 30)->nullable();
            $table->string('condition', 50)->nullable();
            $table->string('estimatedTime', 30)->nullable();
            $table->bigInteger('cargoAmount')->nullable();
            $table->longText('description')->nullable();

            // Calculation Data
            $table->dateTime('faJetty')->nullable();
            $table->dateTime('departureJetty')->nullable();

            $table->dateTime('arrivalPOL')->nullable();
            $table->dateTime('departurePOL')->nullable();

            $table->dateTime('startAsideL')->nullable();
            $table->dateTime('asideL')->nullable();
            $table->dateTime('commenceLoadL')->nullable();
            $table->dateTime('completedLoadingL')->nullable();
            $table->dateTime('cOffL')->nullable();

            $table->dateTime('DOH')->nullable();
            $table->dateTime('DOB')->nullable();

            $table->dateTime('departurePOD')->nullable();
            $table->dateTime('arrivalPOD')->nullable();
            $table->dateTime('startAsidePOD')->nullable();
            $table->dateTime('asidePOD')->nullable();
            $table->dateTime('commenceLoadPOD')->nullable();
            $table->dateTime('completedLoadingPOD')->nullable();
            $table->dateTime('cOffPOD')->nullable();
            $table->dateTime('DOHPOD')->nullable();
            $table->dateTime('DOBPOD')->nullable();

            $table->dateTime('startAsideMV')->nullable();
            $table->dateTime('asideMV')->nullable();
            $table->dateTime('commMV')->nullable();
            $table->dateTime('compMV')->nullable();
            $table->dateTime('departureMV')->nullable();
            $table->time('MV')->nullable();

            // Days calculation
            $table->bigInteger('DOKDays')->nullable();
            $table->bigInteger('perbaikanDays')->nullable();
            $table->bigInteger('kandasDays')->nullable();
            $table->bigInteger('tungguDOKDays')->nullable();
            $table->bigInteger('tungguTugDays')->nullable();
            $table->bigInteger('tungguDokumenDays')->nullable();
            $table->bigInteger('standbyDOKDays')->nullable();
            $table->bigInteger('bocor')->nullable();

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
        Schema::dropIfExists('operational_boat_data');
    }
}
