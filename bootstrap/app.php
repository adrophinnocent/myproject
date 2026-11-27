<?php

use App\Http\Middleware\DiscoveryHeaders;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\NegotiateMarkdown;
use App\Http\Middleware\RedirectWww;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => IsAdmin::class,
        ]);
        $middleware->web(append: [
            RedirectWww::class,
            SetLocale::class,
            SecurityHeaders::class,
            NegotiateMarkdown::class,
            DiscoveryHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
