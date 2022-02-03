<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id()->nullable();
            $table->integer('status');
            $table->double('total_amount', 12, 2)->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_method')->nullable();
            $table->integer('user_id');
            $table->string('payment_ref')->unique()->nullable();
            $table->string('order_ref')->unique()->nullable();
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
