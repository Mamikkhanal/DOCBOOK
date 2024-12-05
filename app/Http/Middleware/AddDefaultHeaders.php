<?php

namespace App\Http\Middleware;

use Closure;

class AddDefaultHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Add Accept header to the request
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
