<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // On vérifie s'il est connecté et s'il est strictement super admin (type_id 3)
        if (auth()->check() && auth()->user()->type_id == 3) {
            return $next($request);
        }

        abort(403, 'Action réservée au Super Administrateur.');
    }
}
