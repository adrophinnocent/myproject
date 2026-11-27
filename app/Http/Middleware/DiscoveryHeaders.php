<?php

namespace App\Http\Middleware;

use App\Helpers\SeoHelper;
use App\Support\AiDiscovery;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Advertises the canonical URL, Markdown representation, llms.txt and XML
 * sitemap via HTTP Link headers. These complement, not replace, the HTML
 * <link> tags and do not change any indexability decision.
 */
class DiscoveryHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! config('ai.link_headers.enabled', true)
            || ! $response instanceof Response
            || $response->getStatusCode() >= 400
            || ! str_contains(strtolower((string) $response->headers->get('Content-Type')), 'text/html')) {
            return $response;
        }

        $canonical = SeoHelper::canonicalUrl($request);
        $site = SeoHelper::siteUrl();
        $links = [];

        if ($existing = $response->headers->get('Link')) {
            $links[] = $existing;
        }

        $links[] = '<'.$canonical.'>; rel="canonical"';

        if (AiDiscovery::hasMarkdownEquivalent($request->route()?->getName())) {
            $links[] = '<'.AiDiscovery::markdownUrl($canonical).'>; rel="alternate"; type="text/markdown"; title="Markdown version"';
        }

        $links[] = '<'.$site.'/sitemap.xml>; rel="sitemap"; type="application/xml"';

        if ($request->is('/')) {
            $links[] = '<'.$site.'/llms.txt>; rel="alternate"; type="text/plain"; title="llms.txt"';
        }

        $response->headers->set('Link', implode(', ', $links));
        $this->addVaryAccept($response);

        return $response;
    }

    /** Content negotiation must be visible to Cloudflare and browser caches. */
    private function addVaryAccept(Response $response): void
    {
        $vary = (string) $response->headers->get('Vary');
        $values = array_map('strtolower', array_map('trim', explode(',', $vary)));

        if (in_array('accept', $values, true)) {
            return;
        }

        $response->headers->set('Vary', $vary === '' ? 'Accept' : $vary.', Accept');
    }
}
