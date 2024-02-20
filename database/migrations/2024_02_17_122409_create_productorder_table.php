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
            $table->float('num_pieces');
            $table
                ->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();
            $table
                ->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();
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
