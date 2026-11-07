<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orden_servicios', function (Blueprint $table) {
            $table->text('fotos_recepcion')->nullable()->after('falla_reportada');
            $table->text('fotos_entrega')->nullable()->after('fotos_recepcion');
        });
    }

    public function down(): void
    {
        Schema::table('orden_servicios', function (Blueprint $table) {
            $table->dropColumn(['fotos_recepcion', 'fotos_entrega']);
        });
    }
};