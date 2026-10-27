<?php

namespace App\Services;

use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * NotificacionService
 * -------------------
 * Centraliza la creación de notificaciones internas del sistema.
 *
 * Reglas de negocio cubiertas:
 *  - Notificar cuando se genera una cotización.
 *  - Notificar cuando se requiere una autorización.
 *  - Notificar cuando cambia el estado de una orden.
 *  - Notificar cuando se registra un pago.
 *  - Notificar cuando el equipo está listo para entrega.
 */
class NotificacionService
{
    /**
     * Crea una notificación para un usuario específico.
     */
    public function notificar(
        int $idUsuario,
        string $titulo,
        string $mensaje,
        string $tipo = 'General',
        ?string $enlace = null,
    ): Notificacion {
        return Notificacion::create([
            'id_usuario' => $idUsuario,
            'titulo'     => $titulo,
            'mensaje'    => $mensaje,
            'tipo'       => $tipo,
            'enlace'     => $enlace,
            'leida'      => false,
        ]);
    }

    /**
     * Notifica a todos los usuarios que tengan el rol indicado.
     *
     * @return Notificacion[]
     */
    public function notificarRol(string $nombreRol, string $titulo, string $mensaje, string $tipo = 'General', ?string $enlace = null): array
    {
        $usuarios = User::whereHas('rol', fn ($q) => $q->where('nombre_rol', $nombreRol))->pluck('id');

        $notificaciones = [];
        foreach ($usuarios as $idUsuario) {
            $notificaciones[] = $this->notificar($idUsuario, $titulo, $mensaje, $tipo, $enlace);
        }

        return $notificaciones;
    }

    // ── Métodos semánticos ───────────────────────────────────────────────────

    public function cotizacionGenerada(int $idCliente, string $folio): void
    {
        $this->notificar(
            idUsuario: $this->userIdDeCliente($idCliente),
            titulo: 'Cotización lista',
            mensaje: "Se ha generado una cotización para la orden {$folio}. Revísala y comunícanos tu decisión.",
            tipo: 'Cotizacion',
        );
    }

    public function autorizacionRequerida(string $descripcion): void
    {
        $this->notificarRol('Administrador', 'Autorización requerida', $descripcion, 'Autorizacion');
    }

    public function cambioEstadoOrden(int $idCliente, string $folio, string $nuevoEstado): void
    {
        $userId = $this->userIdDeCliente($idCliente);
        if (! $userId) return;

        $this->notificar(
            idUsuario: $userId,
            titulo: "Orden {$folio}: {$nuevoEstado}",
            mensaje: "El estado de tu orden de servicio {$folio} ha cambiado a: {$nuevoEstado}.",
            tipo: 'Orden',
        );
    }

    public function pagoRegistrado(int $idCliente, string $folio, string $monto): void
    {
        $userId = $this->userIdDeCliente($idCliente);
        if (! $userId) return;

        $this->notificar(
            idUsuario: $userId,
            titulo: 'Pago registrado',
            mensaje: "Se registró un pago de {$monto} para la orden {$folio}.",
            tipo: 'Pago',
        );
    }

    public function equipoListoParaEntrega(int $idCliente, string $folio): void
    {
        $userId = $this->userIdDeCliente($idCliente);
        if (! $userId) return;

        $this->notificar(
            idUsuario: $userId,
            titulo: '¡Tu equipo está listo!',
            mensaje: "La reparación de la orden {$folio} ha concluido. Puedes pasar a recoger tu equipo.",
            tipo: 'Entrega',
        );
    }

    public function incidenciaRequiereAprobacion(int $idCliente, string $folio, string $descripcion): void
    {
        $userId = $this->userIdDeCliente($idCliente);
        if (! $userId) return;

        $this->notificar(
            idUsuario: $userId,
            titulo: "Aprobación requerida – Orden {$folio}",
            mensaje: "Durante la reparación se detectó: {$descripcion}. Por favor indica si autorizas continuar.",
            tipo: 'Incidencia',
        );
    }

    public function stockBajo(string $refaccion, int $existencia): void
    {
        $this->notificarRol(
            'Administrador',
            'Stock bajo – Refacción',
            "La refacción '{$refaccion}' tiene existencia de {$existencia} unidad(es) y ha llegado al mínimo. Se requiere autorización para reposición.",
            'Inventario',
        );
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function userIdDeCliente(int $idCliente): ?int
    {
        return (int) DB::table('users')->where('id_cliente', $idCliente)->value('id') ?: null;
    }
}
