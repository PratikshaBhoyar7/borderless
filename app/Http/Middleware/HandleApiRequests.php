<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to handle API requests
 * - Ensures session middleware is loaded for API authentication
 * - Skips CSRF verification for programmatic API clients
 */
class HandleApiRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        // Start or resume session if not already started
        if (!$request->session()->isStarted()) {
            $request->session()->start();
        }

        return $next($request);
    }
}
