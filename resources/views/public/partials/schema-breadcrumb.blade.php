{{--
    Reusable BreadcrumbList JSON-LD for simple public pages.
    $crumbs is a list of ['name' => string, 'url' => string] entries.
--}}
@php
    $schemaCanonical = \App\Helpers\SeoHelper::canonicalUrl(request());
    $schemaCrumbs = is_array($crumbs ?? null) ? $crumbs : [];
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        '@id' => $schemaCanonical.'#breadcrumb',
        'itemListElement' => collect($schemaCrumbs)->map(fn (array $crumb, int $index) => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $crumb['name'],
            'item' => $crumb['url'],
        ])->values()->all(),
    ];
@endphp
<script type="application/ld+json">
@json($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
</script>
