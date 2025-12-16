<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // global middleware (empty for minimal project)
    ];

    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'api' => [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'check.ticket.rate' => \App\Http\Middleware\CheckTicketRate::class,
        // Spatie role middleware: ensure correct namespace (note: singular 'Middleware' in vendor)
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    ];
}
