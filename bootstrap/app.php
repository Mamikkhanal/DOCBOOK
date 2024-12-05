<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsDoctor;
use App\Http\Middleware\IsPatient;
use Illuminate\Foundation\Application;
use App\Http\Middleware\IsDoctorOrAdmin;
use App\Http\Middleware\AddDefaultHeaders;
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
        $middleware->append(AddDefaultHeaders::class);
        $middleware->alias(
            [
                'IsDoctor' => IsDoctor::class,
                'IsPatient' => IsPatient::class,
                'IsAdmin' => IsAdmin::class,
                'IsDoctorOrAdmin' => IsDoctorOrAdmin::class,
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
