<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'rol' => \App\Http\Middleware\CheckRol::class,
            'permiso' => \App\Http\Middleware\CheckPermiso::class,
            'verificar.estado' => \App\Http\Middleware\VerificarEstadoUsuario::class,
        ]);

        $middleware->appendToGroup('web', [
            \App\Http\Middleware\VerificarEstadoUsuario::class,
        ]);

        $middleware->replace(
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \App\Http\Middleware\EncryptCookies::class,
        );

        $middleware->replace(
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
