<?php

namespace App\Http\Controllers\Public;

use App\Helpers\SeoHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

/**
 * Generated robots.txt.
 *
 * The wildcard rules are unchanged from the previous static file. Every AI
 * crawler group repeats those same private-area rules, because a crawler-
 * specific "Allow: /" group would otherwise override the wildcard disallows.
 * Googlebot, Bingbot and all other search crawlers keep using the wildcard
 * group and are never blocked.
 */
class RobotsController extends Controller
{
    private const PRIVATE_RULES = [
        'Disallow: /admin/',
        'Disallow: /booking/',
        'Allow: /booking/policies',
        'Disallow: /my-bookings',
        'Disallow: /login',
        'Disallow: /forgot-password',
        'Disallow: /reset-password',
        'Disallow: /admin',
        'Disallow: /proposal',
        'Disallow: /proposal/',
        'Disallow: /trip-plan',
        'Disallow: /trip-plan/',
        'Disallow: /offer/',
        'Disallow: /currency/',
        'Disallow: /newsletter/confirm/',
        'Disallow: /newsletter/unsubscribe/',
        'Disallow: /offer/*/track',
        'Disallow: /offer/*/lead',
    ];

    public function __invoke(): Response
    {
        $site = SeoHelper::siteUrl();

        $lines = [
            '# robots.txt for '.parse_url($site, PHP_URL_HOST),
            '# Generated dynamically by Laravel so the sitemap and host stay in sync.',
            '',
            '# Search engines (including Googlebot and Bingbot) and all other crawlers.',
            'User-agent: *',
            'Allow: /',
            ...self::PRIVATE_RULES,
            '',
        ];

        $crawlers = config('ai.crawlers', []);
        $crawlers = is_array($crawlers) ? array_values(array_filter($crawlers, 'is_string')) : [];

        if ($crawlers !== []) {
            $lines[] = '# AI search and answer crawlers are welcome on public content.';
            $lines[] = '# Private, authenticated and workspace URLs stay disallowed for them too.';

            foreach ($crawlers as $crawler) {
                $lines[] = '';
                $lines[] = 'User-agent: '.$crawler;
                $lines[] = 'Allow: /';
                foreach (self::PRIVATE_RULES as $rule) {
                    $lines[] = $rule;
                }
            }

            $lines[] = '';
        }

        $lines[] = 'Sitemap: '.$site.'/sitemap.xml';
        $lines[] = '';

        return response(implode("\n", $lines), 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
