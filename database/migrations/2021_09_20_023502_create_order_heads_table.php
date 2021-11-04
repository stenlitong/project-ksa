<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_heads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('order_id')->nullable();
            $table->string('cabang');
            $table->string('boatName')->nullable();
            $table->string('noPr')->nullable();
            $table->string('noPo')->nullable();
            $table->string('company')->nullable();
            $table->string('totalPrice')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('created_by');
            $table->string('sender')->nullable();
            $table->string('receiver')->nullable();
            $table->string('expedition')->nullable();
            $table->string('noResi')->nullable();
            $table->string('prDate')->nullable();
            $table->string('invoiceAddress')->nullable();
            $table->string('itemAddress')->nullable();
            $table->string('status')->default('In Progress(Logistic)');
            $table->string('descriptions')->nullable();
            $table->string('reason')->nullable();
            $table->string('approved_at')->nullable();
            $table->integer('order_tracker');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('cascade');
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
        Schema::dropIfExists('order_heads');
    }
}
