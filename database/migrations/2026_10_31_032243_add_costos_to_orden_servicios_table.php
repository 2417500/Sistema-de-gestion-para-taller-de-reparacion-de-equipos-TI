<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('orden_servicios', function (Blueprint $table) {
        $table->decimal('costo_mano_obra', 10, 2)->nullable()->default(0);
        $table->decimal('costo_refacciones', 10, 2)->nullable()->default(0);
        $table->decimal('costo_total', 10, 2)->nullable()->default(0);
        $table->decimal('anticipo', 10, 2)->nullable()->default(0);
    });
}

public function down(): void
{
    Schema::table('orden_servicios', function (Blueprint $table) {
        $table->dropColumn(['costo_mano_obra', 'costo_refacciones', 'costo_total', 'anticipo']);
    });
}
};
