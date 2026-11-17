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

            // Content Security Policy (CSP) with Google reCAPTCHA, Analytics & GTM
            $csp = "upgrade-insecure-requests; "
                 . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdn.jsdelivr.net https://www.googletagmanager.com https://connect.facebook.net https://www.google.com/recaptcha/ https://www.gstatic.com/recaptcha/ https://www.google.com/ https://www.gstatic.com/; "
                 . "frame-src 'self' https://www.google.com/recaptcha/ https://recaptcha.google.com/recaptcha/ https://www.google.com/ https://www.google.com/maps/ https://www.youtube.com/ https://youtube.com/; "
                 . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; "
                 . "font-src 'self' https://fonts.gstatic.com data:; "
                 . "img-src 'self' data: https: blob:; "
                 . "connect-src 'self' https://www.google-analytics.com https://www.google.com/recaptcha/ https://www.gstatic.com/recaptcha/ https://www.google.com/ https://stats.g.doubleclick.net;";

            $response->headers->set('Content-Security-Policy', $csp);

            // HSTS (Strict Transport Security)
            if ($request->isSecure()) {
                $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
            }
        }

        return $response;
    }
}
