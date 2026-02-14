<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCspHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip CSP headers for Filament/Livewire requests to avoid conflicts
        if ($request->is('admin/*') || 
            $request->expectsJson() || 
            $request->ajax() || 
            $request->wantsJson() ||
            str_contains($request->path(), 'livewire') ||
            str_contains($request->path(), 'filament')) {
            return $next($request);
        }
        
        // For other requests, set CSP headers
        $response = $next($request);

        // Only set CSP if response is valid and headers can be accessed
        if ($response instanceof Response && method_exists($response, 'headers')) {
            if (!$response->headers->has('Content-Security-Policy')) {
                $csp = "default-src 'self'; " .
                       "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://www.google-analytics.com; " .
                       "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
                       "font-src 'self' https://fonts.gstatic.com data:; " .
                       "img-src 'self' data: https:; " .
                       "connect-src 'self' https://www.google-analytics.com wss: ws:; " .
                       "frame-src 'self'; " .
                       "object-src 'none'; " .
                       "base-uri 'self'; " .
                       "form-action 'self'; " .
                       "frame-ancestors 'none';";

                $response->headers->set('Content-Security-Policy', $csp);
            }
        }

        return $response;
    }
}
