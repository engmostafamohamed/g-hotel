<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Exceptions\UnauthorizedException;
use App\Helpers\ApiResponse;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'validate.register' => \App\Http\Middleware\ValidateGuestRegister::class,
            'validate.login' => \App\Http\Middleware\ValidateGuestLogin::class,
            'validate.verify_otp' => \App\Http\Middleware\ValidateVerifyOTP::class,
            'validate.resend_otp' => \App\Http\Middleware\ValidateResendOTP::class,
            'validate.reset_password' => \App\Http\Middleware\ValidateResetPassword::class,
            'validate.request_reset_password' => \App\Http\Middleware\ValidateRequestResetPassword::class,
        ]);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'hotel.context' => \App\Http\Middleware\SetHotelContext::class,
        ]);
		$middleware->append(\Illuminate\Http\Middleware\HandleCors::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function(UnauthorizedException $e, $request) {
            return ApiResponse::error(__('employee.employee_should_not_be_allowed'), [], 403);
        });
    })->create();
