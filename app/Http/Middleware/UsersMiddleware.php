<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersMiddleware
{
    /**
     * Manejar una solicitud entrante.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si el usuario está autenticado y es administrador
        if (auth()->check() && auth()->user()->role === 'user') {
            return $next($request);
        }

        // Redirige si no es administrador
        abort(403, 'Acceso denegado: esta sección es solo para usuarios.');
    }
}
