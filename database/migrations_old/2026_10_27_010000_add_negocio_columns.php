<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega campos faltantes exigidos por la lógica de negocio:
 *  ordenes_servicio : folio único, id_orden_origen (reingreso por garantía)
 *  aprobaciones     : motivo_rechazo
 *  cotizaciones     : requiere_anticipo, porcentaje_anticipo
 *  versiones_cotizacion : fecha_aceptacion
 *  garantias        : conceptos_cubiertos
 */
return new class extends Migration
{
    public function up(): void
    {
        // Folio y reingreso en ordenes_servicio
        Schema::table('ordenes_servicio', function (Blueprint $table) {
            $table->string('folio', 20)->nullable()->unique()->after('id_orden');
            $table->foreignId('id_orden_origen')
                ->nullable()
                ->after('folio')
                ->constrained('ordenes_servicio', 'id_orden')
                ->nullOnDelete();
            $table->boolean('es_reingreso')->default(false)->after('id_orden_origen');
        });

        // Motivo de rechazo en aprobaciones
        Schema::table('aprobaciones', function (Blueprint $table) {
            $table->text('motivo_rechazo')->nullable()->after('comentarios');
        });

        // Anticipo en cotizaciones
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->boolean('requiere_anticipo')->default(true)->after('observaciones');
            $table->decimal('porcentaje_anticipo', 5, 2)->default(50)->after('requiere_anticipo');
        });

        // Fecha de aceptación en versiones_cotizacion
        Schema::table('versiones_cotizacion', function (Blueprint $table) {
            $table->dateTime('fecha_aceptacion')->nullable()->after('fecha_vencimiento');
        });

        // Conceptos cubiertos en garantias
        Schema::table('garantias', function (Blueprint $table) {
            $table->text('conceptos_cubiertos')->nullable()->after('condiciones');
        });
    }

    public function down(): void
    {
        Schema::table('garantias', function (Blueprint $table) {
            $table->dropColumn('conceptos_cubiertos');
        });

        Schema::table('versiones_cotizacion', function (Blueprint $table) {
            $table->dropColumn('fecha_aceptacion');
        });

        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn(['requiere_anticipo', 'porcentaje_anticipo']);
        });

        Schema::table('aprobaciones', function (Blueprint $table) {
            $table->dropColumn('motivo_rechazo');
        });

        Schema::table('ordenes_servicio', function (Blueprint $table) {
            $table->dropForeign(['id_orden_origen']);
            $table->dropColumn(['folio', 'id_orden_origen', 'es_reingreso']);
        });
    }
};
