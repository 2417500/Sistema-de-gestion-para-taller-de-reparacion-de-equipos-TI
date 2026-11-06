<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refaccions', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->decimal('precio_unitario', 10, 2);
            $table->integer('stock_disponible');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refaccions');
    }
};