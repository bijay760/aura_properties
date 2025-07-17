<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        then: function () {
//            RateLimiter::for('api', function (Request $request) {
//                return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
//            });
//
//            RateLimiter::for('otp', function (Request $request) {
//                return Limit::perMinute(3)->by($request->ip());
//            });
//
//            RateLimiter::for('authenticated', function (Request $request) {
//                return Limit::perMinute(100)->by($request->user()->id);
//            });
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'apiHash' => \App\Http\Middleware\ApiMiddleware::class,
            'sanitize' => \App\Http\Middleware\XssSanitization::class,
            'api-response'=>\App\Http\Middleware\ForceJsonResponse::class,
            'validated-user'=>\App\Http\Middleware\ApiAuthenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
