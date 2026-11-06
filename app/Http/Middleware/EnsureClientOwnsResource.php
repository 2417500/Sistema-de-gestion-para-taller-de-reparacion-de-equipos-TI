<?php

namespace App\Http\Middleware;

use App\Models\Cotizacion;
use App\Models\Equipo;
use App\Models\Garantia;
use App\Models\OrdenServicio;
use App\Models\Pago;
use App\Models\VersionCotizacion;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Si el usuario autenticado es un cliente, verifica que los modelos
 * enlazados en la ruta (equipo, orden, cotización, pago, garantía...)
 * le pertenezcan. El personal del taller pasa sin restricción.
 *
 * Uso: ->middleware('cliente.propietario')
 */
class EnsureClientOwnsResource
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || CheckRole::normalize($user->rol?->nombre_rol) !== 'cliente') {
            return $next($request);
        }

        $idCliente = $user->id_cliente;

        foreach ($request->route()?->parameters() ?? [] as $parametro) {
            $dueno = $this->clienteDe($parametro);

            if ($dueno !== false && (int) $dueno !== (int) $idCliente) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Recurso no encontrado.'], 404);
                }

                abort(404);
            }
        }

        return $next($request);
    }

    /** Devuelve el id_cliente dueño del modelo, o false si no aplica. */
    private function clienteDe(mixed $modelo): int|string|null|false
    {
        return match (true) {
            $modelo instanceof Equipo => $modelo->id_cliente,
            $modelo instanceof OrdenServicio => $modelo->equipo?->id_cliente,
            $modelo instanceof Cotizacion => $modelo->orden?->equipo?->id_cliente,
            $modelo instanceof VersionCotizacion => $modelo->cotizacion?->orden?->equipo?->id_cliente,
            $modelo instanceof Pago, $modelo instanceof Garantia => $modelo->id_cliente,
            default => false,
        };
    }
}
