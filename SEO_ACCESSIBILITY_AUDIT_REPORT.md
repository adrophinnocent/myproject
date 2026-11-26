# SEO, Accessibility, Performance and AI-Discovery Audit Report

Audit date: 26–27 November 2026  
Site: `https://twinasafaris.com/`  
Scope: public pages, canonical metadata, sitemap/robots, accessibility, performance, structured data and AI discovery.

> The original external scan artifact was not supplied. Issue mapping below is derived from local crawls and code audits. No live Search Console, crawler or server access was available, so live indexing/canonical consistency and server compression headers could not be confirmed.

## Verification summary

| Check | Result |
|---|---|
| Targeted SEO suite | `tests/Feature/SeoUrlTest.php`: 57 tests, 538 assertions, passing |
| Full test suite | 143 tests, 944 assertions, passing |
| Formatting | `vendor\bin\pint --test` passing |
| Blade compile | `php artisan view:cache` passing |
| Canonical-page crawl | 35 URLs, zero detected SEO/accessibility issues |
| Extended crawl | 150-page cap reached; no indexable-page issues detected |
| Baseline comparison | Unsupported hreflang, long titles/descriptions, weak anchors, missing image dimensions, unnamed buttons, heading jumps and skip links removed |

The extended helper locally returned several `429` responses on repeated `/lang/*` requests. Those are an artifact of the same-IP throttle in the local harness, not an indexing problem: language URLs redirect and are not advertised as indexable alternates.

## Issue → affected URL(s) → correction

| Issue | Affected URLs | Correction |
|---|---|---|
| Unsupported `x-default`-only hreflang tags | All indexable pages (26 in baseline crawl) | Removed alternate links from `SeoHelper::alternateUrls()`, layout and `sitemap.xml`; no invented locale URLs. |
| Missing, duplicate or overly long titles/descriptions | Home, tours, guides, blog, destination, booking and policy pages | Unique, length-bounded titles/descriptions in views plus idempotent `SeoContentCleanupSeeder` for DB-driven content. |
| Multiple or misaligned H1s; heading-order jumps | `/login`, `/tours/tour/*`, guides and static pages | One relevant H1 per page; `Accommodation Level & Pricing` changed from H3 to H2 where it follows the H1 directly; decorative headings demoted. |
| Canonical, www and parameter handling | All pages; `/lang/*`, filtered `/tours?…`, `/tours?view=…` | Preferred `https://twinasafaris.com/` enforced; self-referencing canonicals; language/variant URLs redirect; filtered tour URLs are `noindex, follow`. |
| Sitemap contained non-canonical or incomplete URLs | `/sitemap.xml` | Removed XHTML alternates; added `/booking/policies`, destination details and non-empty albums; excluded empty albums. |
| Robots allowed private booking flows | `/robots.txt` | `Disallow: /booking/` with `Allow: /booking/policies`; private responses also send `X-Robots-Tag` or meta robots. |
| Empty/generic anchors and unnamed controls | Site-wide navigation, tour review form, cookie/chat widgets | Descriptive anchor text and ARIA labels; rating buttons rendered server-side with static labels; icon-only controls labelled. |
| Missing skip link, focus states, reduced-motion support, landmarks | All public pages and `/login` | Skip link, global `:focus-visible`, reduced-motion CSS, single `<main>`, labelled chat/cookie regions. |
| Form labels not programmatically associated | `/login`, newsletter and booking forms | `for`/`id` or `aria-label` added to inputs, selects and icon buttons. |
| Images missing alt/dimensions; layout shifts | Blog article content, tours, gallery and destination pages | Meaningful/decorative alt text; 3:2 image specification; intrinsic width/height added even for older editor HTML; `loading="lazy"` below the fold and async decoding. |
| Thin/near-duplicate destination content | `/destinations`, new `/destinations/{slug}` pages | Curated per-destination facts, FAQs, related itineraries, unique metadata and `TouristDestination`/`ItemList` schema. |
| AI crawlers had no curated discovery file | `/llms.txt` | Dynamic DB-driven route with core pages, tours, safaris, destinations, guides, published posts, galleries and policies; private URLs excluded; not placed in sitemap. |
| Weak/duplicated structured data and social cards | Key indexable pages | Settings-driven Organization/WebSite graph with stable `@id`s; stable Product/TouristTrip/BlogPosting/FAQ/Breadcrumb IDs; Open Graph, Twitter and article metadata. |
| ~100 settings queries per request | All pages | `Setting::allCached()` single cached map; `Setting::set()` and cleanup seeder invalidate it. |
| Unnecessary queries on key pages | Home, tours, blog | Removed unused Home queries; hoisted destination existence check; eager-loaded review counts/averages, author and category. |
| Tailwind/Alpine CDN dependence and uncached assets | All public pages | `@vite()` built CSS/JS, preserved gold palette overrides and rebuilt `public/build` assets. |
| Compression not explicitly configured | All responses | `public/.htaccess` prefers Brotli and falls back to Gzip only when Brotli is absent, with `Vary: Accept-Encoding` merged. |

## New or materially changed URLs

- `https://twinasafaris.com/llms.txt` — curated AI-discovery index.
- `https://twinasafaris.com/destinations/{slug}` — six curated detail pages: `serengeti`, `ngorongoro`, `kilimanjaro`, `zanzibar`, `tarangire`, `lake-manyara-national-park`.
- `https://twinasafaris.com/booking/policies` — now in sitemap and crawlable.
- Christmas tour and article URLs retained unchanged; only metadata and content cleanup were applied.

## Remaining issues / deployment-dependent checks

1. **Server compression is code-level only.** Brotli/Gzip module availability and final `Content-Encoding` headers must be confirmed after deployment.
2. **Live indexing and canonical consistency cannot be verified locally.** Recheck in Google Search Console after deployment: coverage, duplicate pages, canonical selection and sitemap submission.
3. **Large legacy media remains.** Uploaded PNGs up to ~3.41 MB and four MP4s around 7.06 MB were not auto-converted because that would change admin-managed media; review and re-encode during a content pass.
4. **`GuideController::safariCost()` still scans published tours/safaris.** Acceptable for the current small catalogue; optimize with an aggregate query if the catalogue grows.
5. **`TourController` still checks `Schema::hasColumn('faqs', 'tour_id')` per request.** Kept deliberately as a partial-deployment guard; minor and safe to revisit after all environments are migrated.
6. **GLightbox CSS still loads from jsdelivr.** Self-hosting would remove a third-party dependency but was outside the verified asset pipeline change.
7. **`Translatable` N+1 may remain outside `BlogPost` on non-default locales.** A previous optimization was reverted because `SetLocale` mutates `config('app.locale')`; do not use that comparison as a translation-source check.
8. **AI crawler behavior is not measurable from the repository.** `/llms.txt` is published, but actual crawler adoption requires server log/GSC review.

## Deployment checklist

```bash
git pull origin main
php artisan migrate --force
php artisan db:seed --class=SeoContentCleanupSeeder --force
php artisan db:seed --class=ChristmasSafariTourSeeder --force
php artisan db:seed --class=ChristmasSafariArticleSeeder --force
npm ci && npm run build
php artisan optimize:clear
```

Set `CANONICAL_URL=https://twinasafaris.com` in the production environment. Confirm that the old hashed CSS asset is removed by the deploy process and that the new Vite manifest is served.

## Functional checks still required on production

- Booking flow, payment/currency selection and admin login.
- WhatsApp and AI chat widgets.
- Language switcher (redirect-only, no indexable language URLs).
- Newsletter confirmation/unsubscribe links.
- Gallery albums, tour detail pages and new destination detail pages.
- `robots.txt`, `sitemap.xml` and `llms.txt` over HTTPS with the non-www host.
