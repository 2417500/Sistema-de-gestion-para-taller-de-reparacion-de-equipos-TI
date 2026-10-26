<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reparaciones', function (Blueprint $table) {
            $table->id('id_reparacion');

            $table->foreignId('id_orden')
                ->unique()
                ->constrained('ordenes_servicio', 'id_orden')
                ->restrictOnDelete();

            $table->foreignId('id_tecnico')
                ->constrained('users', 'id')
                ->restrictOnDelete();

            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_fin')->nullable();
            $table->text('trabajo_realizado')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('estado', 30)->default('Pendiente');

            $table->timestamps();
        });

        Schema::create('refacciones_utilizadas', function (Blueprint $table) {
            $table->id('id_refaccion_utilizada');

            $table->foreignId('id_reparacion')
                ->constrained('reparaciones', 'id_reparacion')
                ->restrictOnDelete();

            $table->foreignId('id_refaccion')
                ->constrained('refacciones', 'id_refaccion')
                ->restrictOnDelete();

            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('importe', 12, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refacciones_utilizadas');
        Schema::dropIfExists('reparaciones');
    }
};