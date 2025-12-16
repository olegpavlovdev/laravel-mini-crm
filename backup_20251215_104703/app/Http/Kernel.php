<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // global middleware (empty for minimal project)
    ];

    protected $middlewareGroups = [
        'web' => [],
        'api' => [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'check.ticket.rate' => \App\Http\Middleware\CheckTicketRate::class,
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
    ];
}
