<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes_servicio', function (Blueprint $table) {
            $table->id('id_orden');

            $table->foreignId('id_equipo')
                ->constrained('equipos', 'id_equipo')
                ->restrictOnDelete();

            $table->foreignId('id_recepcionista')
                ->constrained('users', 'id')
                ->restrictOnDelete();

            $table->dateTime('fecha_recepcion');
            $table->text('falla_reportada');
            $table->text('accesorios_recibidos')->nullable();
            $table->string('estado_fisico_ingreso', 100)->nullable();

            $table->string('estado_orden', 40)->default('Recibida');
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });

        Schema::create('evidencias_ingreso', function (Blueprint $table) {
            $table->id('id_evidencia');

            $table->foreignId('id_orden')
                ->constrained('ordenes_servicio', 'id_orden')
                ->restrictOnDelete();

            $table->string('ruta_archivo', 255);
            $table->string('tipo_archivo', 30)->nullable();
            $table->text('descripcion')->nullable();

            $table->foreignId('id_usuario_registro')
                ->constrained('users', 'id')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidencias_ingreso');
        Schema::dropIfExists('ordenes_servicio');
    }
};