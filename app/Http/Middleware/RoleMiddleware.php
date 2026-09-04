<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Vérifie que l'utilisateur connecté a bien l'un des rôles autorisés.
     * Usage dans les routes : ->middleware('role:admin') ou ->middleware('role:client,livreur')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles, true)) {
            abort(403, "Accès non autorisé.");
        }

        return $next($request);
    }
}
