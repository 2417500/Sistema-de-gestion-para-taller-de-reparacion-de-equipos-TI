<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion_taller', function (Blueprint $table) {
            $table->id('id_configuracion');

            $table->string('nombre_comercial', 150);
            $table->string('razon_social', 150)->nullable();
            $table->string('telefono', 25)->nullable();
            $table->string('correo_contacto')->nullable();
            $table->string('direccion')->nullable();

            $table->string('logo')->nullable();
            $table->string('moneda', 10)->default('MXN');

            $table->text('terminos_condiciones')->nullable();
            $table->boolean('estado')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_taller');
    }
};