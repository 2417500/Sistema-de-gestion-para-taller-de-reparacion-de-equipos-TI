<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecepcionMiddleware
{
    /**
     * Permite el acceso a personal de recepción y administradores.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $request->expectsJson()
                ? response()->json(['message' => 'No autenticado.'], 401)
                : redirect()->guest('/login');
        }

        if (!$user->isRecepcion() && !$user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Módulo de recepción y admisión reservado para Recepción y Administrador.'
                ], 403);
            }

            abort(403, 'Acceso denegado: Requiere rol de Recepción o Administrador.');
        }

        return $next($request);
    }
}

