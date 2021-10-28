<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orders_id');
            $table->unsignedBigInteger('item_id');
            $table->string('quantity');
            $table->string('department')->nullable();
            $table->string('golongan')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->foreign('orders_id')->references('id')->on('order_heads')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
