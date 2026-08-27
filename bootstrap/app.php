<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\SamperinAuth;
use App\Http\Middleware\SamperinRoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(web: __DIR__ . '/../routes/web.php', commands: __DIR__ . '/../routes/console.php', health: '/up')

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'samperin.auth' => SamperinAuth::class,

        'samperin.role' => SamperinRoleMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
    //
})

    ->create();