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
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
            $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

            // Private/auth pages must never be indexed, including standalone
            // views that do not extend the public layout. Do not overwrite a
            // more specific value already set by a controller (e.g. PDFs).
            if (! $response->headers->has('X-Robots-Tag') && $request->is(
                'admin',
                'admin/*',
                'booking',
                'booking/*',
                'my-bookings',
                'trip-plan',
                'trip-plan/*',
                'proposal',
                'proposal/*',
                'offer',
                'offer/*',
                'login',
                'forgot-password',
                'reset-password/*',
                'newsletter/confirm/*',
                'newsletter/unsubscribe/*',
            )) {
                $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');
            }

            // CSP: no wildcard plain-HTTP sources, no plugins, no framing by third parties.
            // 'unsafe-inline'/'unsafe-eval' are required by Alpine.js and the inline
            // scripts used throughout the public site.
            $csp = "default-src 'self'; "
                 ."script-src 'self' 'unsafe-inline' 'unsafe-eval' https:; "
                 ."style-src 'self' 'unsafe-inline' https:; "
                 ."img-src 'self' data: blob: https:; "
                 ."font-src 'self' data: https:; "
                 ."connect-src 'self' https: wss:; "
                 ."frame-src 'self' https:; "
                 ."child-src 'self' https:; "
                 ."object-src 'none'; "
                 ."base-uri 'self'; "
                 ."form-action 'self' https:; "
                 ."frame-ancestors 'self'; "
                 .'upgrade-insecure-requests;';

            $response->headers->set('Content-Security-Policy', $csp);

            // HSTS (Strict Transport Security)
            if ($request->isSecure()) {
                $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
            }
        }

        return $response;
    }
}
