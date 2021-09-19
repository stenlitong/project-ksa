<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');//
            $table->foreignId('crew_id');//
            $table->string('boatName');//
            $table->string('noPr');//
            $table->string('noPo')->nullable();
            $table->string('department');//
            $table->string('company');//
            $table->string('location');//
            $table->string('itemName');//
            $table->string('price')->nullable();
            $table->string('supplier')->nullable();
            $table->date('prDate');//
            $table->string('serialNo');//
            $table->string('quantity');//
            $table->string('codeMasterItem');//
            $table->string('note')->nullable();
            $table->string('invoiceAddress')->nullable();
            $table->string('itemAddress')->nullable();
            $table->string('status')->default('Awaiting Approval');
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
        Schema::dropIfExists('transactions');
    }
}
