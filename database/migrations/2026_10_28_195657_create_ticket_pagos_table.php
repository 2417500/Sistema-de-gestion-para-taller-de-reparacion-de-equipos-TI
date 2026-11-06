<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_servicio_id')->unique()->constrained('orden_servicios');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('porcentaje_descuento', 5, 2)->default(0.00); // 15.00%
            $table->decimal('monto_descuento', 10, 2)->default(0.00);
            $table->decimal('total_pagar', 10, 2);
            $table->enum('metodo_pago', ['Efectivo', 'Tarjeta']);
            $table->timestamp('fecha_pago')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_pagos');
    }
};