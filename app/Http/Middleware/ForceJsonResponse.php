<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ForceJsonResponse
 *
 * Prepended to the API middleware stack so that every /api/* request
 * always carries the Accept: application/json header. This guarantees
 * Laravel will NEVER serve an HTML error page through an API route.
 */
class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
