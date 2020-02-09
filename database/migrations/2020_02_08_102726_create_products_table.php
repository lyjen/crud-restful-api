<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->string('title')->unique();
            $table->text('description');
            $table->string('sku')->unique();
            $table->float('price');
            $table->integer('quantity');
            $table->unsignedBigInteger('product_status_id')->default(2);
            $table->unsignedBigInteger('category_id');
            $table->foreign('product_status_id')->references('product_status_id')->on('product_status');
            $table->foreign('category_id')->references('category_id')->on('category');
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
        Schema::dropIfExists('products');
    }
}
