<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        // Si no está Logueado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Si su rol no está Permitido
        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'No tienes los suficientes permisos para acceder a esta sección!.');
        }

        return $next($request);
    }
}