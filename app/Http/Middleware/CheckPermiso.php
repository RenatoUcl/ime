<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermiso
{
    public function handle(Request $request, Closure $next, $permiso)
    {
        if (!Auth::check() || !Auth::user()->hasPermiso($permiso)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
