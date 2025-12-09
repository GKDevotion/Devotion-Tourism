<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $timeout = env('SESSION_LIFETIME', 1440) * 60; // 2 hours in seconds

        if (Auth::guard('admin')->check()) {
            $lastActivity = session('lastActivityTime');
            $currentTime = time();

            if ($lastActivity && ($currentTime - $lastActivity > $timeout)) {
                Auth::guard('admin')->logout();
                session()->flush();
                return redirect('/login')->with('message', 'You have been logged out due to inactivity.');
            }

            session(['lastActivityTime' => $currentTime]);
        }

        return $next($request);
    }
}
