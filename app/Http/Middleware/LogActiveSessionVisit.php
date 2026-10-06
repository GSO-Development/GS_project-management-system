<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivityLog;
use App\Models\User;

class LogActiveSessionVisit
{
    /**
     * Handle an incoming request to log session restoration / active session visits.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check()) {
            if (!$request->session()->has('audit_session_logged')) {
                $request->session()->put('audit_session_logged', true);

                $user = auth()->user();
                try {
                    ActivityLog::create([
                        'user_id'     => $user->id,
                        'action'      => 'user_session_resumed',
                        'module'      => 'users',
                        'record_type' => User::class,
                        'record_id'   => $user->id,
                        'new_values'  => [
                            'email'      => $user->email,
                            'name'       => $user->name,
                            'login_type' => 'active_session_resumed',
                        ],
                        'ip_address'  => $request->ip(),
                        'user_agent'  => $request->userAgent(),
                    ]);
                } catch (\Throwable $e) {
                    // Silently absorb exceptions
                }
            }
        }

        return $response;
    }
}
