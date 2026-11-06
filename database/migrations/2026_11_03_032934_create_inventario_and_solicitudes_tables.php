<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo', ['refaccion', 'herramienta']);
            $table->integer('stock_actual')->default(5);
            $table->integer('stock_minimo')->default(2); // Stock de seguridad para no quedar en 0
            $table->decimal('precio_unitario', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('solicitudes_repuestos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_id')->constrained('orden_servicios')->onDelete('cascade');
            $table->foreignId('tecnico_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('inventario')->onDelete('cascade');
            $table->integer('cantidad');
            $table->enum('estado', ['PENDIENTE', 'APROBADO', 'RECHAZADO'])->default('PENDIENTE');
            $table->timestamps();
        });

        // Insertar datos iniciales de refacciones y herramientas con stock estable (> 1)
        DB::table('inventario')->insert([
            ['nombre' => 'Pantalla LED HP Pavilion 15', 'tipo' => 'refaccion', 'stock_actual' => 5, 'stock_minimo' => 2, 'precio_unitario' => 500.00, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Batería Laptop HP 3 Celdas', 'tipo' => 'refaccion', 'stock_actual' => 4, 'stock_minimo' => 2, 'precio_unitario' => 350.00, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Juego de Destornilladores de Precisión', 'tipo' => 'herramienta', 'stock_actual' => 5, 'stock_minimo' => 2, 'precio_unitario' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Estación de Soldadura / Cautín', 'tipo' => 'herramienta', 'stock_actual' => 3, 'stock_minimo' => 2, 'precio_unitario' => 0.00, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('solicitudes_repuestos');
        Schema::dropIfExists('inventario');
    }
};