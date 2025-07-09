<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRol
{
    public function handle(Request $request, Closure $next, $rol)
    {
        $usuario = auth()->user();

        if (!$usuario || !$usuario->hasRole($rol)) {
            abort(403, 'Acceso no autorizado');
        }

        return $next($request);
    }
}
