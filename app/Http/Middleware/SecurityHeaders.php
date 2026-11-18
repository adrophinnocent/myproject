<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof Response) {
            // Basic Security Headers
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

            // Robust Content Security Policy (CSP) allowing all external embeds (YouTube, Vimeo, Google Maps, reCAPTCHA, OpenStreetMap, etc.)
            $csp = "upgrade-insecure-requests; "
                 . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https: http:; "
                 . "frame-src 'self' https: http: data: blob:; "
                 . "child-src 'self' https: http: data: blob:; "
                 . "style-src 'self' 'unsafe-inline' https: http:; "
                 . "font-src 'self' https: http: data:; "
                 . "img-src 'self' data: https: http: blob:; "
                 . "connect-src 'self' https: http: wss:;";

            $response->headers->set('Content-Security-Policy', $csp);

            // HSTS (Strict Transport Security)
            if ($request->isSecure()) {
                $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
            }
        }

        return $response;
    }
}
