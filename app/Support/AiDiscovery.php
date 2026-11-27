<?php

namespace App\Support;

use App\Helpers\SeoHelper;
use Illuminate\Http\Request;

/**
 * Shared URL logic for the machine-readable representations of public pages.
 *
 * The HTML URL is always canonical. The ".md" URL and the Accept-negotiated
 * response are alternate representations of that same URL, never separate
 * indexable pages.
 */
final class AiDiscovery
{
    /** Route names that have a Markdown representation. */
    private const MARKDOWN_ROUTES = [
        'tours.index',
        'tours.show',
        'itineraries.show',
        'destinations.index',
        'destinations.show',
        'blog.index',
        'blog.show',
        'guides.safari-cost',
        'guides.best-time',
        'guides.serengeti',
        'guides.ngorongoro',
        'guides.kilimanjaro',
        'guides.zanzibar',
    ];

    /** Planning-guide route name => public path. */
    private const GUIDE_SLUGS = [
        'guides.safari-cost' => 'tanzania-safari-cost',
        'guides.best-time' => 'best-time-for-tanzania-safari',
        'guides.serengeti' => 'serengeti-safari',
        'guides.ngorongoro' => 'ngorongoro-tour',
        'guides.kilimanjaro' => 'kilimanjaro-trekking-company',
        'guides.zanzibar' => 'zanzibar-holiday-packages',
    ];

    public static function hasMarkdownEquivalent(?string $routeName): bool
    {
        return $routeName !== null && in_array($routeName, self::MARKDOWN_ROUTES, true);
    }

    public static function guideSlug(?string $routeName): ?string
    {
        return $routeName !== null ? (self::GUIDE_SLUGS[$routeName] ?? null) : null;
    }

    /**
     * Turn a canonical HTML URL into the URL of its Markdown representation.
     * "/tours/foo.html" becomes "/tours/foo.md", "/tours" becomes "/tours.md".
     * Query strings are dropped: filters and pagination are not separate
     * Markdown documents.
     */
    public static function markdownUrl(string $canonicalUrl): string
    {
        $path = explode('?', $canonicalUrl, 2)[0];

        if (str_ends_with($path, '.html')) {
            return substr($path, 0, -5).'.md';
        }

        return rtrim($path, '/').'.md';
    }

    public static function canonicalUrl(Request $request): string
    {
        return SeoHelper::canonicalUrl($request);
    }

    /**
     * True when the client explicitly prefers text/markdown over HTML.
     * Browsers never match because they do not send text/markdown in Accept.
     */
    public static function wantsMarkdown(Request $request): bool
    {
        $accept = strtolower((string) $request->header('Accept'));

        if ($accept === '' || ! str_contains($accept, 'text/markdown')) {
            return false;
        }

        return self::quality('text/markdown', $accept) >= max(
            self::quality('text/html', $accept),
            self::quality('*/*', $accept)
        );
    }

    private static function quality(string $mediaType, string $accept): float
    {
        foreach (explode(',', $accept) as $part) {
            $pieces = array_map('trim', explode(';', $part));
            $type = array_shift($pieces);

            if ($type !== $mediaType) {
                continue;
            }

            foreach ($pieces as $parameter) {
                if (str_starts_with($parameter, 'q=')) {
                    return (float) substr($parameter, 2);
                }
            }

            return 1.0;
        }

        return 0.0;
    }
}
