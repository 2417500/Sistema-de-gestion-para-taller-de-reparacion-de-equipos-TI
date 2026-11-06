<?php

namespace App\Http\Middleware;

use App\Models\HistorialEstado;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    /**
     * Registra auditoría técnica en solicitudes críticas que modifiquen el estado del sistema.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Si es una petición mutante exitosa (POST, PUT, PATCH, DELETE) y hay un usuario autenticado
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true) && $request->user()) {
            try {
                $user = $request->user();
                $modulo = $user->role?->nombre ?? 'Sistema';
                $ordenId = $request->route('ordenId') ?? $request->input('orden_servicio_id');

                if ($ordenId) {
                    HistorialEstado::create([
                        'orden_servicio_id' => (int) $ordenId,
                        'modulo' => $modulo,
                        'estado' => $request->input('estado') ?? 'Actualización',
                        'comentarios' => "Acción: [{$request->method()}] en ruta {$request->path()}",
                        'usuario_id' => $user->id,
                    ]);
                }
            } catch (\Throwable $e) {
                // Registrar en log sin interrumpir la respuesta de la solicitud
                Log::warning("Fallo en AuditLogMiddleware: " . $e->getMessage());
            }
        }

        return $response;
    }
}

