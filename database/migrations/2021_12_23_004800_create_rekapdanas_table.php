<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekapdanasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekapdanas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->date('DateNote1')->nullable();
            $table->date('DateNote2')->nullable();
            $table->string('Cabang')->nullable();
            $table->string('NamaTug')->nullable();
            $table->string('NamaBarge')->nullable();
            $table->string('status_pembayaran')->nullable();
            $table->decimal('Nilai', 14, 2)->nullable();
            $table->string('mata_uang_nilai')->nullable();
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
        Schema::dropIfExists('rekapdanas');
    }
}
