@props(['items'])

@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($items)->values()->map(fn ($item, $index) => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['name'],
            'item' => website_url($item['url']),
        ])->all(),
    ];
@endphp

<x-schema.json-ld :data="$schema" />
