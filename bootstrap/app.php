<?php

use App\Http\Middleware\CustomApiAuth;
use App\Http\Middleware\FilterRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // RouteMiddleware / Alias
        $middleware->alias([
            'XSS' => \App\Http\Middleware\XSS::class,
            'ModuleCheckEnable' => \App\Http\Middleware\CheckModuleEnable::class,
            'updater' => \App\Http\Middleware\CheckUpdater::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $exception, $request) {
            if ($request->is('api/*')) { 
                return response()->json([
                    'message' => 'Session Expired. Please login again.',
                    'error' => 'Unauthenticated'
                ], 401);
            }
        });
    })->create();
