<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evidencia_ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_servicio_id')->constrained('orden_servicios')->onDelete('cascade');
            $table->string('ruta_imagen');
            $table->text('descripcion_estado');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidencia_ingresos');
    }
};