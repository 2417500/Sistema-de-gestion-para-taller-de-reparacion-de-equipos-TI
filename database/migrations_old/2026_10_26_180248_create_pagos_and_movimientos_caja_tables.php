<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');

            $table->foreignId('id_orden')
                ->constrained('ordenes_servicio', 'id_orden')
                ->restrictOnDelete();

            $table->foreignId('id_cliente')
                ->constrained('clientes', 'id_cliente')
                ->restrictOnDelete();

            $table->foreignId('id_usuario')
                ->constrained('users', 'id')
                ->restrictOnDelete();

            $table->decimal('monto', 12, 2);
            $table->string('metodo_pago', 40);
            $table->string('referencia', 100)->nullable();
            $table->dateTime('fecha_pago');
            $table->string('estado', 30)->default('Registrado');
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });

        Schema::create('movimientos_caja', function (Blueprint $table) {
            $table->id('id_movimiento');

            $table->foreignId('id_pago')
                ->nullable()
                ->constrained('pagos', 'id_pago')
                ->restrictOnDelete();

            $table->foreignId('id_usuario')
                ->constrained('users', 'id')
                ->restrictOnDelete();

            $table->string('tipo_movimiento', 20);
            $table->decimal('monto', 12, 2);
            $table->string('concepto', 150);
            $table->dateTime('fecha_movimiento');
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_caja');
        Schema::dropIfExists('pagos');
    }
};