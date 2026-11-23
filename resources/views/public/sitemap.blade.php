<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Core Static Pages (HTTPS, Non-WWW, Clean 200 OK URLs) --}}
    {{-- Static pages intentionally omit lastmod: they have no reliable content timestamp. --}}
    <url>
        <loc>{{ $siteUrl }}/</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ $siteUrl }}/about</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ $siteUrl }}/contact</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ $siteUrl }}/tours</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ $siteUrl }}/destinations</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ $siteUrl }}/blog</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ $siteUrl }}/faqs</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ $siteUrl }}/gallery</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ $siteUrl }}/custom-safari</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ $siteUrl }}/privacy-policy</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ $siteUrl }}/terms-of-service</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    {{-- Tour Packages --}}
    @foreach ($tours as $tour)
        @if($tour->slug)
        <url>
            <loc>{{ $siteUrl }}/tours/tour/{{ $tour->slug }}.html</loc>
            <lastmod>{{ $tour->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
        @endif
    @endforeach

    {{-- Safaris / Itineraries --}}
    @foreach ($safaris as $safari)
        @if($safari->slug)
        <url>
            <loc>{{ $siteUrl }}/tours/safari/{{ $safari->slug }}.html</loc>
            <lastmod>{{ $safari->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
        @endif
    @endforeach

    {{-- Blog Posts --}}
    @foreach ($blogs as $blog)
        <url>
            <loc>{{ $siteUrl }}/blog/{{ $blog->slug }}.html</loc>
            <lastmod>{{ $blog->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>
