<?php

namespace App\Http\Middleware;

use App\Helpers\SeoHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectWww
{
    /**
     * Redirect www requests to the canonical host.
     *
     * The target host comes from the configured canonical URL, never from the
     * raw Host header, so a spoofed Host header cannot turn this into an open
     * redirect. This keeps the redirect host identical to canonical/sitemap.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost());
        $canonicalHost = strtolower((string) parse_url(SeoHelper::siteUrl(), PHP_URL_HOST));

        if ($canonicalHost !== '' && $host === 'www.'.$canonicalHost) {
            $newUrl = $request->getScheme().'://'.$canonicalHost.$request->getRequestUri();

            return redirect()->to($newUrl, 301);
        }

        return $next($request);
    }
}
