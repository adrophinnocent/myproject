<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Static Pages --}}
    <url>
        <loc>https://twinasafaris.com/</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://twinasafaris.com/about</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>https://twinasafaris.com/contact</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>https://twinasafaris.com/tours</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- Tours --}}
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

    {{-- Blogs --}}
    @foreach ($blogs as $blog)
        <url>
            <loc>{{ "https://twinasafaris.com/blog/" . $blog->slug . ".html" }}</loc>
            <lastmod>{{ $blog->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach

    {{-- Destinations --}}
    @foreach ($destinations as $dest)
        <url>
            <loc>{{ route('tours.index', ['destination' => $dest->id]) }}</loc>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>
