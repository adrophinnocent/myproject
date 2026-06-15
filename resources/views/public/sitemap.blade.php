<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Static Pages --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('about') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('contact.index') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('tours.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- Tours --}}
    @foreach ($tours as $tour)
        <url>
            <loc>{{ route('tours.show', ['type' => 'tour', 'slug' => $tour->slug]) }}</loc>
            <lastmod>{{ $tour->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach

    {{-- Safaris --}}
    @foreach ($safaris as $safari)
        <url>
            <loc>{{ route('tours.show', ['type' => 'safari', 'slug' => $safari->slug]) }}</loc>
            <lastmod>{{ $safari->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach

    {{-- Blogs --}}
    @foreach ($blogs as $blog)
        <url>
            <loc>{{ route('blog.show', $blog->slug) }}</loc>
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
