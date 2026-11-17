<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id('id_notificacion');

            $table->foreignId('id_usuario')
                ->constrained('users', 'id')
                ->restrictOnDelete();

            $table->string('titulo', 150);
            $table->text('mensaje');

            $table->string('tipo', 50)->default('General');
            $table->string('enlace')->nullable();

            $table->boolean('leida')->default(false);
            $table->dateTime('fecha_lectura')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};