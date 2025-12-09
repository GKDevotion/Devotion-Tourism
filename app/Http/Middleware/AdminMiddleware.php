<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // // Skip auth check for a specific URL
        // if ($request->is('admin/company-account-summery/*')) {
        //     return $next($request);
        // }

        if (Auth::check() ) { // && Auth::user()->is_admin Ensure user is an admin
            return $next($request);
        }
        return redirect('/'); // Redirect unauthorized users
    }
}
