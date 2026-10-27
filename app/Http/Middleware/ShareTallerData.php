<?php

namespace App\Http\Middleware;

use App\Models\ConfiguracionTaller;
use App\Models\Notificacion;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Comparte con todas las vistas Blade:
 *  - $taller: configuración activa del taller (nombre, logo, moneda...)
 *  - $usuarioActual: usuario con su rol cargado
 *  - $notificacionesPendientes: número de notificaciones sin leer
 *
 * Se registra automáticamente en el grupo "web".
 */
class ShareTallerData
{
    public function handle(Request $request, Closure $next): Response
    {
        View::share('taller', $this->configuracion());

        $user = $request->user();
        $user?->loadMissing('rol');

        View::share('usuarioActual', $user);
        View::share('notificacionesPendientes', $user
            ? Notificacion::where('id_usuario', $user->id)->where('leida', false)->count()
            : 0);

        return $next($request);
    }

    private function configuracion(): ?ConfiguracionTaller
    {
        return Cache::remember('configuracion_taller', now()->addMinutes(10), function () {
            if (! Schema::hasTable('configuracion_taller')) {
                return null;
            }

            return ConfiguracionTaller::where('estado', true)->latest('id_configuracion')->first();
        });
    }
}
