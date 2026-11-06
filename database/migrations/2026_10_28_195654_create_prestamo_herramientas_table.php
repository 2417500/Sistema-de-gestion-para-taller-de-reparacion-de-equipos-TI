<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestamo_herramientas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_servicio_id')->constrained('orden_servicios');
            $table->foreignId('herramienta_id')->constrained('herramientas');
            $table->foreignId('tecnico_id')->constrained('users');
            $table->integer('cantidad_prestada');
            $table->enum('estado_prestamo', ['Solicitado', 'Aprobado_Admin', 'Entregado', 'Devuelto'])->default('Solicitado');
            $table->timestamp('fecha_prestamo')->useCurrent();
            $table->timestamp('fecha_devolucion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestamo_herramientas');
    }
};