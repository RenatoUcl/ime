<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarEstadoUsuario
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && (int) Auth::user()->estado === 0) {
            Auth::logout();
            $request->session()->flush();

            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta ha sido desactivada. Contacta al administrador.',
            ]);
        }

        return $next($request);
    }
}
