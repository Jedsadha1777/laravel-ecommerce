<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__.'/../routes/web.php', //ไม่ใช้หน้าเว็บ
        // api: __DIR__.'/../routes/api.php', 
        then: function () {
            Route::prefix('admin-api')
                ->middleware('api')
                ->group(base_path('routes/admin-api.php'));

            Route::prefix('shop-api')
                ->middleware('api')
                ->group(base_path('routes/shop-api.php'));
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
     ->withMiddleware(function (Middleware $middleware) {
     
        $middleware->redirectGuestsTo(function (Request $request) {
            return $request->is('api/*') ? null : '/login';
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->shouldRenderJsonWhen(fn (Request $r) => $r->is('api/*'));
    })
    ->create();
