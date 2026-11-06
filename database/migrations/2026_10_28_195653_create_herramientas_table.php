<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('herramientas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('numero_serie', 100)->nullable();
            $table->integer('stock_total');
            $table->integer('stock_disponible'); // Al prestarlas disminuye este stock
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('herramientas');
    }
};