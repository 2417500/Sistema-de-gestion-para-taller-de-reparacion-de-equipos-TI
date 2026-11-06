<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reglas_descuento', function (Blueprint $table) {
            $table->id('id_regla_descuento');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('reparaciones_requeridas')->default(5);
            $table->decimal('porcentaje_descuento', 5, 2)->default(0);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id('id_cotizacion');

            $table->foreignId('id_orden')
                ->unique()
                ->constrained('ordenes_servicio', 'id_orden')
                ->restrictOnDelete();

            $table->string('estado', 30)->default('Pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('versiones_cotizacion', function (Blueprint $table) {
            $table->id('id_version');

            $table->foreignId('id_cotizacion')
                ->constrained('cotizaciones', 'id_cotizacion')
                ->restrictOnDelete();

            $table->foreignId('id_regla_descuento')
                ->nullable()
                ->constrained('reglas_descuento', 'id_regla_descuento')
                ->restrictOnDelete();

            $table->foreignId('id_usuario')
                ->constrained('users', 'id')
                ->restrictOnDelete();

            $table->unsignedInteger('numero_version');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('porcentaje_descuento_aplicado', 5, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('condiciones')->nullable();
            $table->dateTime('fecha_emision');
            $table->dateTime('fecha_vencimiento')->nullable();

            $table->unique(['id_cotizacion', 'numero_version']);
            $table->timestamps();
        });

        Schema::create('detalle_cotizacion', function (Blueprint $table) {
            $table->id('id_detalle');

            $table->foreignId('id_version')
                ->constrained('versiones_cotizacion', 'id_version')
                ->restrictOnDelete();

            $table->string('tipo_concepto', 30);
            $table->string('descripcion', 255);
            $table->decimal('cantidad', 10, 2)->default(1);
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('importe', 12, 2);
            $table->timestamps();
        });

        Schema::create('aprobaciones', function (Blueprint $table) {
            $table->id('id_aprobacion');

            $table->foreignId('id_version')
                ->unique()
                ->constrained('versiones_cotizacion', 'id_version')
                ->restrictOnDelete();

            $table->foreignId('id_cliente')
                ->constrained('clientes', 'id_cliente')
                ->restrictOnDelete();

            $table->string('estado', 30)->default('Pendiente');
            $table->dateTime('fecha_respuesta')->nullable();
            $table->text('comentarios')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aprobaciones');
        Schema::dropIfExists('detalle_cotizacion');
        Schema::dropIfExists('versiones_cotizacion');
        Schema::dropIfExists('cotizaciones');
        Schema::dropIfExists('reglas_descuento');
    }
};