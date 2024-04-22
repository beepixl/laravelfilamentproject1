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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('totalamount'); 
            $table->string('address')->nullable()->after('totalamount');
            $table->string('city')->nullable()->after('totalamount');
            $table->string('state')->nullable()->after('totalamount'); 
            $table->string('country')->nullable()->after('totalamount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
