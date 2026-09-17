<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'must_change_password' => \App\Http\Middleware\EnsurePasswordIsChanged::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException|\Illuminate\Auth\AccessDeniedException $e, \Illuminate\Http\Request $request) {
            if ($request->isMethod('GET') && !$request->wantsJson() && auth()->check()) {
                $user = auth()->user();
                $targetRoute = ($user->isPmoAdmin() || $user->isSuperAdmin()) ? route('projects.index') : route('projects.my-leads');
                session()->flash('error', '🔒 Unauthorized Access: You do not have permission to view or access this project workspace.');
                return redirect()->to($targetRoute);
            }
        });
    })->create();
