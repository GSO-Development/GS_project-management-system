<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    /**
     * Handle an incoming request for system-created users who must change their password.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->must_change_password && $user->isSystemCreated()) {
            // Allow access to the force password change view, logout action, and Livewire internal endpoints
            if (!$request->routeIs('force-password-change') 
                && !$request->routeIs('logout') 
                && !$request->is('livewire/*')) {
                return redirect()->route('force-password-change');
            }
        }

        // Redirect away from force-password-change screen if password is already changed
        if ($user && !$user->must_change_password && $request->routeIs('force-password-change')) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
