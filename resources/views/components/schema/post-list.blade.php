@props([
    'items',
    'name',
    'description' => null,
    'url' => null,
])

@php
    $pageUrl = website_url($url ?? url()->current());
    $collection = method_exists($items, 'items') ? collect($items->items()) : collect($items);
    $firstPosition = method_exists($items, 'firstItem') && $items->firstItem() ? $items->firstItem() : 1;

    $itemListElements = $collection->values()->map(function ($item, int $index) use ($firstPosition) {
        $itemUrl = website_route('slug.show', $item->slug);
        $imageUrl = $item->thumbnail
            ? asset('storage/' . $item->thumbnail)
            : asset('assets/images/og-default.jpg');

        $article = [
            '@type' => 'Article',
            '@id' => $itemUrl . '#article',
            'headline' => $item->title,
            'url' => $itemUrl,
            'image' => $imageUrl,
            'dateModified' => $item->updated_at?->toIso8601String(),
            'author' => [
                '@type' => 'Organization',
                'name' => __('Viện Nghiên cứu và Phát triển Kinh tế Xanh'),
                'alternateName' => 'GREECO Institute',
                'url' => url('/'),
            ],
        ];

        if ($item->published_at) {
            $article['datePublished'] = $item->published_at->toIso8601String();
        }

        if (filled($item->excerpt)) {
            $article['description'] = $item->excerpt;
        }

        return [
            '@type' => 'ListItem',
            'position' => $firstPosition + $index,
            'url' => $itemUrl,
            'item' => $article,
        ];
    })->all();

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        '@id' => $pageUrl . '#webpage',
        'url' => $pageUrl,
        'name' => $name,
        'description' => $description,
        'inLanguage' => app()->getLocale() === 'en' ? 'en' : 'vi-VN',
        'isPartOf' => [
            '@type' => 'WebSite',
            '@id' => url('/') . '#website',
            'name' => setting('site_name', config('app.name')),
            'url' => url('/'),
        ],
        'mainEntity' => [
            '@type' => 'ItemList',
            'itemListOrder' => 'https://schema.org/ItemListOrderDescending',
            'numberOfItems' => count($itemListElements),
            'itemListElement' => $itemListElements,
        ],
    ];
@endphp

<x-schema.json-ld :data="$schema" />
