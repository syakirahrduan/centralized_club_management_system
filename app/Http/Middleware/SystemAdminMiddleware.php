<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SystemAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Using the Auth facade makes VS Code happy
        if (Auth::check() && Auth::user()->role === 'system_admin') {
            return $next($request);
        }

        abort(403, 'Unauthorized action. System Admin access required.');
    }
}