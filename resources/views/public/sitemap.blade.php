<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Core Static Pages (HTTPS, Non-WWW, Clean 200 OK URLs) --}}
    <url>
        <loc>https://twinasafaris.com/</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://twinasafaris.com/about</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>https://twinasafaris.com/contact</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>https://twinasafaris.com/tours</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>https://twinasafaris.com/destinations</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>https://twinasafaris.com/blog</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>https://twinasafaris.com/faqs</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>https://twinasafaris.com/gallery</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>https://twinasafaris.com/custom-safari</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- Tour Packages --}}
    @foreach ($tours as $tour)
        @if($tour->slug)
        <url>
            <loc>{{ "https://twinasafaris.com/tours/tour/" . $tour->slug . ".html" }}</loc>
            <lastmod>{{ $tour->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
        @endif
    @endforeach

    {{-- Safaris --}}
    @foreach ($safaris as $safari)
        @if($safari->slug)
        <url>
            <loc>{{ "https://twinasafaris.com/tours/safari/" . $safari->slug . ".html" }}</loc>
            <lastmod>{{ $safari->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
        @endif
    @endforeach

    {{-- Blog Posts --}}
    @foreach ($blogs as $blog)
        <url>
            <loc>{{ "https://twinasafaris.com/blog/" . $blog->slug . ".html" }}</loc>
            <lastmod>{{ $blog->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>
