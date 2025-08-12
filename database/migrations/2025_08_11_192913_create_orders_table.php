<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();   
            $table->uuid('uuid')->unique(); 
            $table->uuid('user_id');
            $table->string('order_number')->unique();
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])
                  ->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->text('shipping_address');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('uuid')->on('users');
            $table->index('order_number');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};