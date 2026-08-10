<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Autorise uniquement les utilisateurs dont le role est 'admin'.
     * Utilise sur les routes /users/* et /statistiques (BF11).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            abort(403, "Accès réservé à l'administrateur.");
        }

        return $next($request);
    }
}
