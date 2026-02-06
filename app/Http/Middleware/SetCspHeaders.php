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
        $response = $next($request);

        // Only set CSP if not already set (allows server-level CSP to take precedence)
        if ($response->headers->has('Content-Security-Policy')) {
            return $response;
        }

        // Allow unsafe-eval for Livewire/Filament (required for dynamic component rendering)
        // In production, consider using nonce-based CSP instead
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

        return $response;
    }
}
