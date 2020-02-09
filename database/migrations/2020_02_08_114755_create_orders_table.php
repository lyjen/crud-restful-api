<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('order_status_id');
            $table->date('order_date')->nullable();
            $table->integer('total_product')->nullable();
            $table->float('subtotal')->nullable();
            $table->float('total')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('users');
            $table->foreign('order_status_id')->references('order_status_id')->on('order_status');
            $table->foreign('payment_id')->references('payment_id')->on('payment_options');
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
        Schema::dropIfExists('orders');
    }
}
