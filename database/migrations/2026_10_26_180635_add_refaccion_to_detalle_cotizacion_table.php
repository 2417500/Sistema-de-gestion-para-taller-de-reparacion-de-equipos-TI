<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->foreignId('id_refaccion')
                ->nullable()
                ->after('id_version')
                ->constrained('refacciones', 'id_refaccion')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->dropForeign(['id_refaccion']);
            $table->dropColumn('id_refaccion');
        });
    }
};