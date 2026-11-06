<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\ReglaDescuento;
use Illuminate\Support\Facades\DB;

/**
 * DescuentoService
 * ----------------
 * Evalúa y calcula los descuentos aplicables según las reglas del taller.
 *
 * Reglas de negocio cubiertas:
 *  - 5 % al acumular 5 equipos reparados y pagados.
 *  - 10 % con al menos 1 año de antigüedad (hasta 15 % con criterios adicionales del admin).
 *  - Los descuentos NO se acumulan automáticamente.
 *  - Se registra el porcentaje, el importe y la autorización correspondiente.
 */
class DescuentoService
{
    private const DESCUENTO_FIDELIDAD_REPARACIONES = 5;
    private const UMBRAL_REPARACIONES = 5;

    private const DESCUENTO_ANTIGUEDAD_BASE = 10;
    private const DESCUENTO_ANTIGUEDAD_MAX  = 15;
    private const ANTIGUEDAD_MIN_ANIOS = 1;

    /**
     * Evalúa los descuentos aplicables al cliente y retorna el mejor disponible.
     *
     * @return array{porcentaje: float, regla: ReglaDescuento|null, motivo: string}
     */
    public function evaluar(int $idCliente): array
    {
        $cliente = Cliente::findOrFail($idCliente);
        $candidatos = [];

        // ── Descuento por fidelidad (5 reparaciones pagadas) ────────────────
        $totalReparadas = $this->contarReparacionesPagadas($idCliente);
        if ($totalReparadas >= self::UMBRAL_REPARACIONES) {
            $regla = ReglaDescuento::where('estado', true)
                ->where('reparaciones_requeridas', '<=', self::UMBRAL_REPARACIONES)
                ->orderByDesc('porcentaje_descuento')
                ->first();

            if ($regla) {
                $candidatos[] = [
                    'porcentaje' => (float) $regla->porcentaje_descuento,
                    'regla'      => $regla,
                    'motivo'     => "Descuento por {$totalReparadas} reparaciones pagadas",
                ];
            }
        }

        // ── Descuento por antigüedad ─────────────────────────────────────────
        $antiguedadAnios = $cliente->fecha_registro->diffInYears(now());
        if ($antiguedadAnios >= self::ANTIGUEDAD_MIN_ANIOS) {
            $porcentaje = $antiguedadAnios >= 2
                ? self::DESCUENTO_ANTIGUEDAD_MAX
                : self::DESCUENTO_ANTIGUEDAD_BASE;

            $regla = ReglaDescuento::where('estado', true)
                ->where('porcentaje_descuento', $porcentaje)
                ->first();

            $candidatos[] = [
                'porcentaje' => (float) $porcentaje,
                'regla'      => $regla,
                'motivo'     => "Descuento por antigüedad ({$antiguedadAnios} año(s))",
            ];
        }

        if (empty($candidatos)) {
            return ['porcentaje' => 0.0, 'regla' => null, 'motivo' => 'Sin descuento aplicable'];
        }

        // Seleccionar el descuento MÁS favorable (no acumulables)
        usort($candidatos, fn ($a, $b) => $b['porcentaje'] <=> $a['porcentaje']);

        return $candidatos[0];
    }

    /**
     * Calcula el descuento monetario para una cotización.
     *
     * @return array{float $subtotal, float $descuento, float $porcentaje, ReglaDescuento|null $regla}
     */
    public function calcularParaCotizacion(int $idCliente, float $subtotal): array
    {
        $resultado  = $this->evaluar($idCliente);
        $porcentaje = $resultado['porcentaje'];
        $descuento  = round($subtotal * ($porcentaje / 100), 2);

        return [$subtotal, $descuento, $porcentaje, $resultado['regla']];
    }

    // ── Helper ───────────────────────────────────────────────────────────────

    private function contarReparacionesPagadas(int $idCliente): int
    {
        return (int) DB::table('ordenes_servicio as os')
            ->join('equipos as e', 'e.id_equipo', '=', 'os.id_equipo')
            ->join('pagos as p', 'p.id_orden', '=', 'os.id_orden')
            ->where('e.id_cliente', $idCliente)
            ->where('os.estado_orden', 'Entregada')
            ->where('p.estado', 'Registrado')
            ->distinct('os.id_orden')
            ->count('os.id_orden');
    }
}
