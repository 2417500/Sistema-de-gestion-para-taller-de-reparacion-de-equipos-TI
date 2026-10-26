<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnosticos', function (Blueprint $table) {
            $table->id('id_diagnostico');

            $table->foreignId('id_orden')
                ->constrained('ordenes_servicio', 'id_orden')
                ->restrictOnDelete();

            $table->foreignId('id_tecnico')
                ->constrained('users', 'id')
                ->restrictOnDelete();

            $table->dateTime('fecha_diagnostico');
            $table->text('falla_detectada');
            $table->text('causa_probable')->nullable();
            $table->text('solucion_recomendada');
            $table->boolean('requiere_refacciones')->default(false);
            $table->boolean('requiere_reparacion')->default(true);
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnosticos');
    }
};