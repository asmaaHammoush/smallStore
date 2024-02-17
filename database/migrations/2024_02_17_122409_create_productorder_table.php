<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productorder', function (Blueprint $table) {
            $table->id();
            $table->float('numPieces');
            $table->unsignedBigInteger('orderId');
            $table->foreign('orderId')->references('id')->on('orders');
            $table->unsignedBigInteger('productId');
            $table->foreign('productId')->references('id')->on('products');
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
        Schema::dropIfExists('productorder');
    }
}
