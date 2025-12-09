<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PreventMultipleLogins
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle( $request, Closure $next)
    {
        if ( Auth::guard('admin')->user() ) {
            $userId = Auth::guard('admin')->user()->id;
            $currentSessionId = session()->getId();

            $cachedSessionId = Cache::get('user_session_' . $userId);

            $checkPreventLogin = getField( "configurations", "key", "value", "IS_PREVENT_MULTIPLE_LOGIN" );

            if ( $checkPreventLogin && $cachedSessionId && $cachedSessionId !== $currentSessionId) {

                Auth::guard('admin')->logout();
                session()->invalidate();
                session()->regenerateToken();

                session()->flash('error', 'You have been logged out because your account was logged in from another device.' );
                return redirect()->route('admin.login');
            }

            // Store current session ID
            Cache::put('user_session_' . $userId, $currentSessionId, now()->addHours(24));
        }

        return $next($request);
    }
}
