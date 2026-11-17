<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garantias', function (Blueprint $table) {
            $table->id('id_garantia');

            $table->foreignId('id_orden')
                ->unique()
                ->constrained('ordenes_servicio', 'id_orden')
                ->restrictOnDelete();

            $table->foreignId('id_cliente')
                ->constrained('clientes', 'id_cliente')
                ->restrictOnDelete();

            $table->foreignId('id_usuario')
                ->constrained('users', 'id')
                ->restrictOnDelete();

            $table->date('fecha_inicio');
            $table->date('fecha_fin');

            $table->string('duracion', 50)->nullable();
            $table->text('condiciones')->nullable();
            $table->string('estado', 30)->default('Vigente');
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garantias');
    }
};