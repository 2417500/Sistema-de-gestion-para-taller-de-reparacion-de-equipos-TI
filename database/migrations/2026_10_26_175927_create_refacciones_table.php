<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refacciones', function (Blueprint $table) {
            $table->id('id_refaccion');

            $table->string('nombre', 120);
            $table->string('marca', 80)->nullable();
            $table->string('numero_parte', 100)->nullable()->unique();

            $table->text('descripcion')->nullable();

            $table->unsignedInteger('existencia')->default(0);
            $table->unsignedInteger('existencia_minima')->default(1);

            $table->decimal('costo_unitario', 12, 2)->default(0);
            $table->decimal('precio_venta', 12, 2)->default(0);

            $table->string('ubicacion', 100)->nullable();
            $table->boolean('estado')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refacciones');
    }
};