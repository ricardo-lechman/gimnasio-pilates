<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  Rol requerido para acceder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        // Verifica si el usuario está autenticado y tiene el rol requerido (case insensitive)
        if (!$user || strtolower($user->role) !== strtolower($role)) {
            abort(403, 'Acceso no autorizado');
        }

        return $next($request);
    }
}
