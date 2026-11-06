<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClienteMiddleware
{
    /**
     * Permite el acceso a clientes autenticados en su portal personal (y a administradores).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $request->expectsJson()
                ? response()->json(['message' => 'No autenticado.'], 401)
                : redirect()->guest('/login');
        }

        if (!$user->isCliente() && !$user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Portal reservado para Clientes con órdenes activas o Administradores.'
                ], 403);
            }

            abort(403, 'Acceso denegado: Portal de Clientes.');
        }

        return $next($request);
    }
}

