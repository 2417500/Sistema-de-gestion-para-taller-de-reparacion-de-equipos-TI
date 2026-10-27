<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cierra la sesión si el rol, el empleado o el cliente vinculado
 * al usuario está dado de baja (campo "estado" = false).
 *
 * Uso: ->middleware('activo')
 */
class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $user->loadMissing(['rol', 'empleado', 'cliente']);

        $motivo = match (true) {
            $user->rol && ! $user->rol->estado => 'Tu rol se encuentra desactivado.',
            $user->empleado && ! $user->empleado->estado => 'Tu cuenta de empleado está inactiva.',
            $user->cliente && ! $user->cliente->estado => 'Tu cuenta de cliente está inactiva.',
            default => null,
        };

        if ($motivo === null) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => $motivo], 403);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.publico')->withErrors(['acceso' => $motivo]);
    }
}
