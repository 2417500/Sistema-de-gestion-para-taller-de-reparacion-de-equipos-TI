<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_equipo', function (Blueprint $table) {
            $table->id('id_tipo_equipo');
            $table->string('nombre_tipo', 80)->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        Schema::create('equipos', function (Blueprint $table) {
            $table->id('id_equipo');

            $table->foreignId('id_cliente')
                ->constrained('clientes', 'id_cliente')
                ->restrictOnDelete();

            $table->foreignId('id_tipo_equipo')
                ->constrained('tipos_equipo', 'id_tipo_equipo')
                ->restrictOnDelete();

            $table->string('marca', 80);
            $table->string('modelo', 100)->nullable();
            $table->string('numero_serie', 120)->nullable()->unique();
            $table->text('caracteristicas')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipos');
        Schema::dropIfExists('tipos_equipo');
    }
};