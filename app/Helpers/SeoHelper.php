<?php

namespace App\Helpers;

use App\Models\Safari;
use App\Models\Tour;
use Illuminate\Http\Request;

class SeoHelper
{
    /**
     * Single source of truth for the public, non-www domain.
     */
    public static function siteUrl(): string
    {
        return rtrim((string) config('app.canonical_url', 'https://twinasafaris.com'), '/');
    }

    /**
     * @return array<int, string>
     */
    public static function supportedLocales(): array
    {
        $locales = config('app.supported_locales', ['en', 'de', 'fr', 'es', 'it', 'zh', 'nl']);

        return is_array($locales) ? array_values($locales) : ['en', 'de', 'fr', 'es', 'it', 'zh', 'nl'];
    }

    public static function defaultLocale(): string
    {
        return (string) config('app.fallback_locale', 'en');
    }

    /**
     * Build the canonical path from the named route so the ".html" suffix is
     * always identical to the URL that actually returns 200.
     */
    public static function canonicalPath(Request $request): string
    {
        $path = $request->getPathInfo();
        $route = $request->route();

        if (! $route) {
            return $path;
        }

        $params = $route->parameters();

        return match ($route->getName()) {
            'tours.show' => route('tours.show', [
                'type' => $params['type'] ?? 'tour',
                'slug' => self::cleanSlug($params['slug'] ?? ''),
            ], false),
            'blog.show' => route('blog.show', [
                'slug' => self::cleanSlug($params['slug'] ?? ''),
            ], false),
            'itineraries.show' => self::preferredTourPath(self::cleanSlug($params['slug'] ?? '')),
            default => $path,
        };
    }

    /**
     * The /itineraries/... alias serves the same content as /tours/... so it
     * must point at the preferred tour URL instead of creating a duplicate.
     */
    public static function preferredTourPath(string $slug): string
    {
        if ($slug === '') {
            return '/tours';
        }

        $type = Safari::where('slug', $slug)->exists() ? 'safari' : 'tour';

        return route('tours.show', ['type' => $type, 'slug' => $slug], false);
    }

    public static function canonicalUrl(Request $request): string
    {
        $url = self::siteUrl().self::canonicalPath($request);
        $locale = app()->getLocale();

        if (self::isSupported($locale) && $locale !== self::defaultLocale()) {
            return $url.'?lang='.$locale;
        }

        return $url;
    }

    /**
     * @return array<string, string>
     */
    public static function alternateUrls(Request $request): array
    {
        $base = self::siteUrl().self::canonicalPath($request);
        $alternates = [];

        foreach (self::supportedLocales() as $locale) {
            $alternates[$locale] = $locale === self::defaultLocale()
                ? $base
                : $base.'?lang='.$locale;
        }

        $alternates['x-default'] = $base;

        return $alternates;
    }

    public static function isSupported(?string $locale): bool
    {
        return $locale !== null && in_array($locale, self::supportedLocales(), true);
    }

    public static function ogLocale(string $locale): string
    {
        return match ($locale) {
            'de' => 'de_DE',
            'fr' => 'fr_FR',
            'es' => 'es_ES',
            'it' => 'it_IT',
            'zh' => 'zh_CN',
            'nl' => 'nl_NL',
            default => 'en_US',
        };
    }

    private static function cleanSlug(string $slug): string
    {
        return str_replace('.html', '', $slug);
    }
}
