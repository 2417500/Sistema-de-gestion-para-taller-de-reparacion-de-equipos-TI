<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id('id_movimiento_inventario');

            $table->foreignId('id_refaccion')
                ->constrained('refacciones', 'id_refaccion')
                ->restrictOnDelete();

            $table->foreignId('id_usuario')
                ->constrained('users', 'id')
                ->restrictOnDelete();

            $table->string('tipo_movimiento', 30);
            $table->unsignedInteger('cantidad');

            $table->decimal('costo_unitario', 12, 2)->nullable();

            $table->string('motivo', 150);
            $table->string('referencia', 100)->nullable();
            $table->dateTime('fecha_movimiento');

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};