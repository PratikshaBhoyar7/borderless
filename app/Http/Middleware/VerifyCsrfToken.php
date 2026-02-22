<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifyCsrfToken;
use Closure;

class VerifyCsrfToken extends BaseVerifyCsrfToken
{
    /**
     * The URIs that should be excluded from CSRF verification.
     * These are API endpoints that should accept requests from external clients
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
        'api/auth/*',
        'api/auth/login',
        'api/auth/check',
        'api/auth/logout',
    ];

    /**
     * Override handle method to exclude API routes from CSRF
     */
    public function handle($request, Closure $next)
    {
        // Skip CSRF verification for API endpoints
        if ($request->path() === 'api/auth/login' ||
            $request->path() === 'api/auth/check' ||
            $request->path() === 'api/auth/me' ||
            $request->path() === 'api/auth/logout' ||
            strpos($request->path(), 'api/auth/') === 0) {
            // For API endpoints, skip CSRF and continue
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
