<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aprobacion_admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('users');
            $table->foreignId('orden_servicio_id')->nullable()->constrained('orden_servicios');
            $table->string('tipo_solicitud'); // 'Exceso_Equipos', 'Exceso_Herramientas', 'Exceso_Refacciones'
            $table->enum('estado', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente');
            $table->text('motivo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aprobacion_admins');
    }
};