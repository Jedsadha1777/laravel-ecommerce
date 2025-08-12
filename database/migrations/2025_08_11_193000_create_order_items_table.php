<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();   
            $table->uuid('uuid')->unique(); 
            $table->uuid('order_id');
            $table->uuid('product_id');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
            
            $table->foreign('order_id')->references('uuid')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('uuid')->on('products');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};