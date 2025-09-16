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
        Schema::create('inventory_sale', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreignId('inventory_id')->references('id')->on('inventories')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_sale');
    }
};
