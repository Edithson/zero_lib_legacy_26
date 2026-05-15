<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // On vérifie si l'utilisateur est connecté et s'il est au moins admin (type_id 2 ou 3)
        if (auth()->check() && in_array(auth()->user()->type_id, [2, 3])) {
            return $next($request);
        }

        // Sinon, erreur 403 (Accès interdit)
        abort(403, 'Accès non autorisé. Vous devez être administrateur.');
    }
}
