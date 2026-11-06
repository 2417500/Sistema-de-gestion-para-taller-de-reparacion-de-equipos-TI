<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AlmacenMiddleware
{
    /**
     * Permite el acceso a personal de almacén, suministros y administradores.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $request->expectsJson()
                ? response()->json(['message' => 'No autenticado.'], 401)
                : redirect()->guest('/login');
        }

        if (!$user->isAlmacen() && !$user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Módulo de almacén e inventario reservado para Almacén y Administrador.'
                ], 403);
            }

            abort(403, 'Acceso denegado: Requiere rol de Almacén o Administrador.');
        }

        return $next($request);
    }
}

