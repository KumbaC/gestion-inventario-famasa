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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('amount');  //MONTO EN BOLIVARES
            $table->string('amount_foreign_currency')->nullable();  //MONTO MONEDA EXTRANJERA
            $table->string('exchange_rate')->nullable(); //TASA DE CAMBIO
            $table->string('amount_total'); // M0NTO TOTAL DE LA VENTA
            $table->string('return_change')->nullable(); // VUELTO
            $table->unsignedBigInteger('box_id');
            $table->unsignedBigInteger('client_id');
            $table->foreignId('type_coin_id')->default(1)->references('id')->on('type_coins'); // TIPO DE MONEDA DE LA TRANSFERENCIA
            $table->foreign('box_id')->references('id')->on('boxes')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreignId('user_id')->default(1)->references('id')->on('users'); // USUARIO QUE REALIZA LA VENTA
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
