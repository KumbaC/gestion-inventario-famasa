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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('stock');
            $table->foreignId('type_coin_id')->default(1)->references('id')->on('type_coins'); // TIPO DE MONEDA DE LA TRANSFERENCIA
            $table->foreignId('supplier_id')->default(1)->references('id')->on('suppliers'); 
            $table->double('amount'); // PRECIO POR UNIDAD
            $table->double('amount_total'); // PRECIO TOTAL
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
