<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Public\MarkdownController;
use App\Support\AiDiscovery;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Serves text/markdown as an alternate representation of a public HTML URL.
 *
 * The HTML URL stays canonical and no new indexable URL is created. Caches
 * must vary on Accept (added by DiscoveryHeaders) so a markdown response can
 * never be served to a browser that asked for HTML.
 */
class NegotiateMarkdown
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('ai.markdown.negotiation_enabled', true)
            || $request->getMethod() !== 'GET'
            || ! AiDiscovery::wantsMarkdown($request)) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if (! AiDiscovery::hasMarkdownEquivalent($routeName)) {
            return $next($request);
        }

        $params = $request->route()->parameters();
        $controller = app(MarkdownController::class);

        $response = match (true) {
            $routeName === 'tours.index' => $controller->tours(),
            $routeName === 'tours.show' => $controller->tour((string) ($params['type'] ?? ''), (string) ($params['slug'] ?? '')),
            $routeName === 'itineraries.show' => $controller->tourBySlug((string) ($params['slug'] ?? '')),
            $routeName === 'destinations.index' => $controller->destinations(),
            $routeName === 'destinations.show' => $controller->destination((string) ($params['slug'] ?? '')),
            $routeName === 'blog.index' => $controller->blogIndex(),
            $routeName === 'blog.show' => $controller->blog((string) ($params['slug'] ?? '')),
            AiDiscovery::guideSlug($routeName) !== null => $controller->guide(AiDiscovery::guideSlug($routeName)),
            default => null,
        };

        return $response ?? $next($request);
    }
}
