@props([
    'headline',
    'description' => null,
    'image' => null,
    'publishDate' => null,
    'modifiedDate' => null,
    'url' => null,
    'section' => null,
    'tags' => [],
])

@php
    $articleUrl = website_url($url ?? url()->current());
    $publishedAt = $publishDate ?? now()->toIso8601String();
    $imageUrl = $image ?? asset('assets/images/og-default.jpg');
    $tags = collect(is_iterable($tags) ? $tags : [])
        ->filter(fn ($tag) => filled($tag))
        ->map(fn ($tag) => trim($tag))
        ->values();

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        '@id' => $articleUrl . '#article',
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => $articleUrl,
        ],
        'url' => $articleUrl,
        'inLanguage' => app()->getLocale() === 'en' ? 'en' : 'vi-VN',
        'isPartOf' => [
            '@type' => 'WebSite',
            '@id' => url('/') . '#website',
            'name' => setting('site_name', config('app.name')),
            'url' => url('/'),
        ],
        'headline' => $headline,
        'description' => $description,
        'image' => [
            '@type' => 'ImageObject',
            'url' => $imageUrl,
        ],
        'thumbnailUrl' => $imageUrl,
        'datePublished' => $publishedAt,
        'dateModified' => $modifiedDate ?? $publishedAt,
        'author' => [
            '@type' => 'Organization',
            'name' => __('Viện Nghiên cứu và Phát triển Kinh tế Xanh'),
            'alternateName' => 'GREECO Institute',
            'url' => url('/'),
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => __('Viện Nghiên cứu và Phát triển Kinh tế Xanh'),
            'alternateName' => 'GREECO Institute',
            'url' => url('/'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => setting('logo_dark') ? asset('storage/' . setting('logo_dark')) : asset('assets/images/logo-greeco.png'),
            ],
        ],
    ];

    if (filled($section)) {
        $schema['articleSection'] = $section;
    }

    if ($tags->isNotEmpty()) {
        $schema['keywords'] = $tags->implode(', ');
    }
@endphp

<x-schema.json-ld :data="$schema" />
