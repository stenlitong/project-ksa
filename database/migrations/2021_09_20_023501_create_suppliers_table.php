<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplierName');
            $table->string('noTelp');
            $table->string('supplierEmail')->unique();
            $table->string('supplierAddress');
            $table->string('supplierNPWP');
            $table->string('supplierNoRek')->nullable();
            $table->integer('quality')->default(1);
            $table->integer('top')->default(1);
            $table->integer('price')->default(1);
            $table->integer('deliveryTime')->default(1);
            $table->integer('availability')->default(1);
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
        Schema::dropIfExists('suppliers');
    }
}
