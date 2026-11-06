<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restringe una ruta a uno o varios roles (tabla roles.nombre_rol).
 *
 * Uso: ->middleware('rol:Administrador,Recepcionista')
 * El rol "Administrador" siempre tiene acceso.
 */
class CheckRole
{
    public const ADMIN = 'administrador';

    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return $this->deny($request, 401, 'No autenticado.');
        }

        $rolUsuario = self::normalize($user->rol?->nombre_rol);

        if ($rolUsuario === '') {
            return $this->deny($request, 403, 'El usuario no tiene un rol asignado.');
        }

        $permitidos = array_map(self::normalize(...), $roles);

        if ($rolUsuario === self::ADMIN || in_array($rolUsuario, $permitidos, true)) {
            return $next($request);
        }

        return $this->deny($request, 403, 'No tienes permiso para acceder a este recurso.');
    }

    /** Compara roles sin importar mayúsculas ni acentos (Técnico == tecnico). */
    public static function normalize(?string $rol): string
    {
        return Str::lower(Str::ascii(trim((string) $rol)));
    }

    private function deny(Request $request, int $status, string $mensaje): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $mensaje], $status);
        }

        if ($status === 401) {
            return redirect()->guest(route('portal.publico'));
        }

        abort($status, $mensaje);
    }
}
