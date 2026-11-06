<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Permite el acceso únicamente a usuarios con rol de Administrador.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $request->expectsJson()
                ? response()->json(['message' => 'No autenticado.'], 401)
                : redirect()->guest('/login');
        }

        if (!$user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Operación reservada exclusivamente para el Administrador del taller.'
                ], 403);
            }

            abort(403, 'Permisos insuficientes: Requiere privilegios de Administrador.');
        }

        return $next($request);
    }
}

