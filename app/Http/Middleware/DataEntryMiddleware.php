<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DataEntryMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Allow admin or data_entry role
        if ($user->hasRole('admin') || $user->hasRole('data_entry')) {
            return $next($request);
        }

        abort(403, 'Unauthorized access');
    }
}
