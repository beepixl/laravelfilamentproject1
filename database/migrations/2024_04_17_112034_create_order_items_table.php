<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();  
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->unsignedBigInteger('item_id')->nullable()->index();    
            $table->unsignedInteger('qty')->nullable();
            $table->decimal('price', 10, 2)->nullable();  
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
