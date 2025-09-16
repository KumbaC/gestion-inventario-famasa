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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('inventory_id')->references('id')->on('inventories')->onDelete('cascade'); //PRODUCTO
            $table->foreignId('box_id')->references('id')->on('boxes')->onDelete('cascade');
            $table->double('amount');  //MONTO EN BOLIVARES
            $table->double('amount_foreign_currency')->nullable();  //MONTO MONEDA EXTRANJERA
            $table->double('exchange_rate')->nullable(); //TASA DE CAMBIO
            $table->double('amount_total'); // M0NTO TOTAL DE LA COMPRA
            $table->integer('stock'); //STOCK DE PRODUCTO
            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreignId('type_coin_id')->default(1)->references('id')->on('type_coins'); // TIPO DE MONEDA DE LA TRANSFERENCIA
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
