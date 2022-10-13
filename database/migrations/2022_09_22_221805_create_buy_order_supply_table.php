<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_order_supply', function (Blueprint $table) {
            $table->integer('quantity');
            $table->integer('partial_price');
            $table->bigInteger('buy_order_id');
            $table->bigInteger('supply_id');
            $table->timestamps();

            $table->foreign('buy_order_id')->references('id')->on('buy_orders');
            $table->foreign('supply_id')->references('id')->on('supplies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buy_order_supply');
    }
};
