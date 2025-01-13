<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CORS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        if (env("APP_ENV") != 'production') {
            $request->headers->set('Access-Control-Allow-Origin', '*');
        }

        $request->headers->set('Accept', 'application/json');
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('Access-Control-Allow-Credentials', 'true');
        $request->headers->set('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE, OPTIONS, PATCH');

        return $next($request);
    }
}
