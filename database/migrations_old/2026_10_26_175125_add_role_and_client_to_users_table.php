<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('id_rol')
                ->nullable()
                ->constrained('roles', 'id_rol')
                ->restrictOnDelete();

            $table->foreignId('id_cliente')
                ->nullable()
                ->unique()
                ->constrained('clientes', 'id_cliente')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_rol']);
            $table->dropForeign(['id_cliente']);
            $table->dropUnique(['id_cliente']);
            $table->dropColumn(['id_rol', 'id_cliente']);
        });
    }
};