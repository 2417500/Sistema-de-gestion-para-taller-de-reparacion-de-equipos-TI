<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TecnicoMiddleware
{
    /**
     * Permite el acceso a técnicos de laboratorio y administradores.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $request->expectsJson()
                ? response()->json(['message' => 'No autenticado.'], 401)
                : redirect()->guest('/login');
        }

        if (!$user->isTecnico() && !$user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Módulo técnico reservado para Técnicos de Banco y Administradores.'
                ], 403);
            }

            abort(403, 'Acceso denegado: Requiere credenciales de Técnico de Banco.');
        }

        return $next($request);
    }
}

