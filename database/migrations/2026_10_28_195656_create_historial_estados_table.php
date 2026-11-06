<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_servicio_id')->constrained('orden_servicios')->onDelete('cascade');
            $table->string('modulo'); // Recepción, Técnico, Almacén, Administrador
            $table->string('estado'); // Pendiente, En_Proceso, Terminado, etc.
            $table->text('comentarios')->nullable();
            $table->foreignId('usuario_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_estados');
    }
};