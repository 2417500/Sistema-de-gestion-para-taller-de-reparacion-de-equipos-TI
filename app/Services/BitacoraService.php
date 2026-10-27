<?php

namespace App\Services;

use App\Models\BitacoraAccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * BitacoraService
 * ---------------
 * Responsable de registrar en bitacora_acciones las operaciones
 * importantes realizadas en el sistema, con trazabilidad completa.
 *
 * Reglas de negocio cubiertas:
 *  - El sistema registra cambios de estado, aprobaciones, rechazos,
 *    modificaciones de cotizaciones, pagos, cancelaciones y movimientos
 *    de inventario.
 *  - Se guarda el usuario, la acción, el módulo, el registro afectado,
 *    el detalle y la IP.
 */
class BitacoraService
{
    /**
     * Registra una acción en la bitácora.
     *
     * @param  string               $accion       Identificador corto (ej. cambio_estado, pago_registrado)
     * @param  string               $modulo       Nombre de la entidad afectada (ej. OrdenServicio, Cotizacion)
     * @param  int|null             $idReferencia PK del registro afectado
     * @param  string|array|null    $detalle      Información adicional legible
     * @param  int|null             $idUsuario    Si null se usa el usuario autenticado
     * @param  Request|null         $request      Para capturar la IP
     */
    public function registrar(
        string $accion,
        string $modulo,
        ?int $idReferencia = null,
        string|array|null $detalle = null,
        ?int $idUsuario = null,
        ?Request $request = null,
    ): BitacoraAccion {
        $idUsuario ??= Auth::id(); // null = acción de sistema (sin sesión activa)
        $ip = $request?->ip() ?? request()->ip();
        $detalleTexto = is_array($detalle) ? json_encode($detalle, JSON_UNESCAPED_UNICODE) : $detalle;

        return BitacoraAccion::create([
            'id_usuario'    => $idUsuario,
            'accion'        => $accion,
            'modulo'        => $modulo,
            'id_referencia' => $idReferencia,
            'detalle'       => $detalleTexto,
            'ip'            => $ip,
        ]);
    }

    // ── Métodos semánticos para los módulos principales ──────────────────────

    public function cambioEstadoOrden(int $idOrden, string $anterior, string $nuevo, ?int $idUsuario = null): void
    {
        $this->registrar('cambio_estado', 'OrdenServicio', $idOrden,
            "Estado: {$anterior} → {$nuevo}", $idUsuario);
    }

    public function aprobacionCotizacion(int $idVersion, string $tipo = 'aprobada', ?string $motivo = null): void
    {
        $this->registrar("cotizacion_{$tipo}", 'VersionCotizacion', $idVersion, $motivo);
    }

    public function pagoRegistrado(int $idPago, string $monto, string $metodo): void
    {
        $this->registrar('pago_registrado', 'Pago', $idPago, "Monto: {$monto} | Método: {$metodo}");
    }

    public function movimientoInventario(int $idMovimiento, string $tipo, int $idRefaccion, int $cantidad): void
    {
        $this->registrar('movimiento_inventario', 'MovimientoInventario', $idMovimiento,
            "Tipo: {$tipo} | Refacción: {$idRefaccion} | Cantidad: {$cantidad}");
    }

    public function cancelacionOrden(int $idOrden, string $motivo): void
    {
        $this->registrar('cancelacion_orden', 'OrdenServicio', $idOrden, $motivo);
    }

    public function rechazoSolicitud(int $idSolicitud, string $motivo, string $modulo = 'SolicitudAutorizacion'): void
    {
        $this->registrar('rechazo', $modulo, $idSolicitud, $motivo);
    }
}
