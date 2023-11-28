<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class RequestIdentifierMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Generate a unique identifier
        $requestIdentifier = Str::uuid()->toString();

        // Attach the identifier to the request
        $request->requestIdentifier = $requestIdentifier;

        // Continue processing the request
        return $next($request);
    }
}
