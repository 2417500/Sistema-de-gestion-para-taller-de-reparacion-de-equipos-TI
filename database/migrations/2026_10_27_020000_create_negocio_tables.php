<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tablas nuevas requeridas por la lógica de negocio:
 *  reservas_refacciones  – reserva de existencias por orden
 *  solicitudes_autorizacion – autorización de múltiples equipos / excepciones
 *  incidencias_reparacion   – interrupciones e imprevistos durante la reparación
 *  pruebas_finales          – verificación antes de entrega
 *  entregas                 – registro de entrega al cliente
 *  bitacora_acciones        – auditoría de operaciones importantes
 */
return new class extends Migration
{
    public function up(): void
    {
        // Reservas de refacciones por orden
        Schema::create('reservas_refacciones', function (Blueprint $table) {
            $table->id('id_reserva');
            $table->foreignId('id_orden')->constrained('ordenes_servicio', 'id_orden')->cascadeOnDelete();
            $table->foreignId('id_refaccion')->constrained('refacciones', 'id_refaccion');
            $table->unsignedInteger('cantidad');
            $table->string('estado', 30)->default('Reservada'); // Reservada|Entregada|Liberada
            $table->timestamps();
        });

        // Solicitudes de autorización (multiequipo, excepción de anticipo, etc.)
        Schema::create('solicitudes_autorizacion', function (Blueprint $table) {
            $table->id('id_solicitud');
            $table->foreignId('id_cliente')->constrained('clientes', 'id_cliente');
            $table->foreignId('id_usuario_solicitante')->constrained('users', 'id');
            $table->foreignId('id_usuario_autorizador')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->string('tipo', 60); // multiple_equipo|excepcion_anticipo|otro
            $table->text('descripcion');
            $table->string('estado', 30)->default('Pendiente'); // Pendiente|Aprobada|Rechazada
            $table->text('motivo_rechazo')->nullable();
            $table->dateTime('fecha_respuesta')->nullable();
            $table->timestamps();
        });

        // Incidencias durante la reparación
        Schema::create('incidencias_reparacion', function (Blueprint $table) {
            $table->id('id_incidencia');
            $table->foreignId('id_reparacion')->constrained('reparaciones', 'id_reparacion')->cascadeOnDelete();
            $table->foreignId('id_tecnico')->constrained('users', 'id');
            $table->string('tipo', 60); // falla_adicional|material_faltante|interrupcion|otro
            $table->text('descripcion');
            $table->text('acciones_tomadas')->nullable();
            $table->string('estado', 30)->default('Abierta'); // Abierta|Resuelta|PendienteCliente
            $table->boolean('requiere_aprobacion_cliente')->default(false);
            $table->dateTime('fecha_resolucion')->nullable();
            $table->timestamps();
        });

        // Pruebas finales antes de entrega
        Schema::create('pruebas_finales', function (Blueprint $table) {
            $table->id('id_prueba');
            $table->foreignId('id_reparacion')->constrained('reparaciones', 'id_reparacion')->cascadeOnDelete();
            $table->foreignId('id_tecnico')->constrained('users', 'id');
            $table->boolean('equipo_reparado')->default(true);
            $table->text('descripcion_prueba');
            $table->text('resultado')->nullable();
            $table->text('causa_no_reparado')->nullable();
            $table->dateTime('fecha_prueba');
            $table->timestamps();
        });

        // Entregas al cliente
        Schema::create('entregas', function (Blueprint $table) {
            $table->id('id_entrega');
            $table->foreignId('id_orden')->unique()->constrained('ordenes_servicio', 'id_orden');
            $table->foreignId('id_responsable_entrega')->constrained('users', 'id'); // recepcionista
            $table->string('nombre_receptor', 150);
            $table->string('identificacion_receptor', 100)->nullable();
            $table->dateTime('fecha_entrega');
            $table->string('folio_comprobante', 60)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        // Bitácora de acciones importantes
        Schema::create('bitacora_acciones', function (Blueprint $table) {
            $table->id('id_bitacora');
            $table->foreignId('id_usuario')->constrained('users', 'id');
            $table->string('accion', 80);          // cambio_estado|aprobacion|rechazo|pago|etc.
            $table->string('modulo', 60);           // OrdenServicio|Cotizacion|Pago|Inventario|etc.
            $table->unsignedBigInteger('id_referencia')->nullable(); // PK del registro afectado
            $table->text('detalle')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacora_acciones');
        Schema::dropIfExists('entregas');
        Schema::dropIfExists('pruebas_finales');
        Schema::dropIfExists('incidencias_reparacion');
        Schema::dropIfExists('solicitudes_autorizacion');
        Schema::dropIfExists('reservas_refacciones');
    }
};
