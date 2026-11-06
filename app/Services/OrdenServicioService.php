<?php

namespace App\Services;

use App\Models\Equipo;
use App\Models\EvidenciaIngreso;
use App\Models\HistorialEstado;
use App\Models\OrdenServicio;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OrdenServicioService
{
    /**
     * Registra un nuevo equipo e ingresa una orden de servicio al taller.
     *
     * @param array $equipoData [cliente_id, tipo_equipo, marca, modelo, numero_serie]
     * @param array $ordenData [recepcion_id, tipo_servicio, falla_reportada, tecnico_id (opcional)]
     * @param array $evidencias Lista opcional de [ruta_imagen, descripcion_estado]
     */
    public function crearOrden(array $equipoData, array $ordenData, array $evidencias = []): OrdenServicio
    {
        return DB::transaction(function () use ($equipoData, $ordenData, $evidencias) {
            // 1. Obtener o registrar equipo por número de serie
            $equipo = Equipo::firstOrCreate(
                ['numero_serie' => $equipoData['numero_serie']],
                [
                    'cliente_id' => $equipoData['cliente_id'],
                    'tipo_equipo' => $equipoData['tipo_equipo'],
                    'marca' => $equipoData['marca'],
                    'modelo' => $equipoData['modelo'],
                ]
            );

            // 2. Generar código único de servicio (ej: ST-2026-0001)
            $anio = date('Y');
            $consecutivo = OrdenServicio::whereYear('created_at', $anio)->count() + 1;
            $codigoServicio = sprintf('ST-%s-%04d', $anio, $consecutivo);

            // Asegurar unicidad
            while (OrdenServicio::where('codigo_servicio', $codigoServicio)->exists()) {
                $consecutivo++;
                $codigoServicio = sprintf('ST-%s-%04d', $anio, $consecutivo);
            }

            // 3. Crear la orden de servicio
            $orden = OrdenServicio::create([
                'codigo_servicio' => $codigoServicio,
                'equipo_id' => $equipo->id,
                'recepcion_id' => $ordenData['recepcion_id'],
                'tecnico_id' => $ordenData['tecnico_id'] ?? null,
                'tipo_servicio' => $ordenData['tipo_servicio'] ?? 'Reparacion',
                'falla_reportada' => $ordenData['falla_reportada'],
                'estado' => 'Pendiente',
                'requiere_aprobacion_admin' => false,
            ]);

            // 4. Registrar evidencias fotográficas o de estado inicial
            foreach ($evidencias as $evidencia) {
                EvidenciaIngreso::create([
                    'orden_servicio_id' => $orden->id,
                    'ruta_imagen' => $evidencia['ruta_imagen'] ?? 'evidencias/default.jpg',
                    'descripcion_estado' => $evidencia['descripcion_estado'] ?? 'Inspección inicial de recepción',
                ]);
            }

            // 5. Asentar en historial de auditoría
            HistorialEstado::create([
                'orden_servicio_id' => $orden->id,
                'modulo' => 'Recepción',
                'estado' => 'Pendiente',
                'comentarios' => 'Ingreso de hardware registrado en recepción. Ticket generado: ' . $codigoServicio,
                'usuario_id' => $ordenData['recepcion_id'],
            ]);

            return $orden->load(['equipo', 'recepcion', 'evidencias']);
        });
    }

    /**
     * Asigna un técnico de banco a una orden de servicio.
     */
    public function asignarTecnico(int $ordenId, int $tecnicoId): OrdenServicio
    {
        $orden = OrdenServicio::findOrFail($ordenId);
        $tecnico = User::findOrFail($tecnicoId);

        $orden->update([
            'tecnico_id' => $tecnico->id,
            'estado' => $orden->estado === 'Pendiente' ? 'En_Proceso' : $orden->estado,
        ]);

        HistorialEstado::create([
            'orden_servicio_id' => $orden->id,
            'modulo' => 'Técnico',
            'estado' => $orden->estado,
            'comentarios' => "Orden asignada al técnico: {$tecnico->name}",
            'usuario_id' => $tecnicoId,
        ]);

        return $orden->fresh(['tecnico', 'equipo']);
    }

    /**
     * Actualiza el estado de la orden de servicio asentando en bitácora.
     */
    public function actualizarEstado(int $ordenId, string $nuevoEstado, int $usuarioId, ?string $comentarios = null): OrdenServicio
    {
        $estadosPermitidos = ['Pendiente', 'En_Proceso', 'Terminado', 'Entregado', 'Cancelado'];

        if (!in_array($nuevoEstado, $estadosPermitidos, true)) {
            throw new InvalidArgumentException("Estado de servicio no válido: [{$nuevoEstado}].");
        }

        $orden = OrdenServicio::findOrFail($ordenId);
        $estadoAnterior = $orden->estado;

        $orden->update(['estado' => $nuevoEstado]);

        $usuario = User::find($usuarioId);
        $modulo = $usuario?->role?->nombre ?? 'Sistema';

        HistorialEstado::create([
            'orden_servicio_id' => $orden->id,
            'modulo' => $modulo,
            'estado' => $nuevoEstado,
            'comentarios' => $comentarios ?? "Transición de estado: [{$estadoAnterior}] a [{$nuevoEstado}]",
            'usuario_id' => $usuarioId,
        ]);

        return $orden->fresh();
    }

    /**
     * Búsqueda por código de servicio para portal público o interno.
     */
    public function buscarPorCodigo(string $codigo): ?OrdenServicio
    {
        return OrdenServicio::with([
            'equipo.cliente.user',
            'recepcion',
            'tecnico',
            'diagnosticos',
            'cotizaciones.detalles.refaccion',
            'ticketPago',
            'historiales.usuario'
        ])->where('codigo_servicio', trim($codigo))->first();
    }

    /**
     * Búsqueda de órdenes por número de serie del equipo.
     */
    public function buscarPorNumeroSerie(string $numeroSerie): Collection
    {
        return OrdenServicio::with(['equipo', 'tecnico', 'ticketPago'])
            ->whereHas('equipo', function ($query) use ($numeroSerie) {
                $query->where('numero_serie', 'LIKE', '%' . trim($numeroSerie) . '%');
            })
            ->latest()
            ->get();
    }
}

