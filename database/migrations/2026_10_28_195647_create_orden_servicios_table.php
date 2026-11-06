<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_servicios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_servicio', 30)->unique();
            $table->foreignId('equipo_id')->constrained('equipos');
            $table->foreignId('recepcion_id')->constrained('users');
            $table->foreignId('tecnico_id')->nullable()->constrained('users');
            $table->enum('tipo_servicio', ['Diagnostico', 'Reparacion']);
            $table->text('falla_reportada');
            $table->enum('estado', [
                'Pendiente',
                'En_Proceso',
                'Terminado',
                'Entregado',
                'Cancelado'
            ])->default('Pendiente');
            $table->boolean('requiere_aprobacion_admin')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_servicios');
    }
};