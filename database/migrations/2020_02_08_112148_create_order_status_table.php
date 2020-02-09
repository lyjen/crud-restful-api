<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_status', function (Blueprint $table) {
            $table->bigIncrements('order_status_id');
            $table->string('order_status')->unique();
        });

        DB::table('order_status')->insert([
                ['order_status' => 'Canceled'],
                ['order_status' => 'Canceled Reversal'],
                ['order_status' => 'Chargeback'],
                ['order_status' => 'Complete'],
                ['order_status' => 'Denied'],
                ['order_status' => 'Expired'],
                ['order_status' => 'Failed'],
                ['order_status' => 'Missing Orders'],
                ['order_status' => 'Pending'],
                ['order_status' => 'Processed'],
                ['order_status' => 'Processing'],
                ['order_status' => 'Refunded'],
                ['order_status' => 'Reversed'],
                ['order_status' => 'Shipped'],
                ['order_status' => 'Voided']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_status');
    }
}
