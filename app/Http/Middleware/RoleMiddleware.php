<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Maneja la solicitud entrante comprobando si el usuario autenticado tiene el rol requerido.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles Nombres de roles permitidos (ej: Administrador, Tecnico)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autenticado en la plataforma SoporTec.'
                ], 401);
            }

            return redirect()->guest('/login');
        }

        if (empty($roles)) {
            return $next($request);
        }

        if (!$user->role || !$user->hasRole($roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Acceso denegado: Su rol [' . ($user->role?->nombre ?? 'Sin Rol') . '] no tiene permisos para este recurso.'
                ], 403);
            }

            abort(403, 'Acceso denegado: Rol no autorizado para operar este módulo técnico.');
        }

        return $next($request);
    }
}

