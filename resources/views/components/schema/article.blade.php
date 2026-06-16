@props([
    'headline',
    'description' => null,
    'image' => null,
    'publishDate' => null,
    'modifiedDate' => null,
    'url' => null,
])

@php
    $articleUrl = $url ?? url()->current();
    $publishedAt = $publishDate ?? now()->toIso8601String();

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => $articleUrl,
        ],
        'headline' => $headline,
        'description' => $description,
        'image' => $image ?? asset('assets/images/og-default.jpg'),
        'datePublished' => $publishedAt,
        'dateModified' => $modifiedDate ?? $publishedAt,
        'author' => [
            '@type' => 'Organization',
            'name' => 'Viện Nghiên cứu và Phát triển Kinh tế Xanh',
            'alternateName' => 'GREECO Institute',
            'url' => url('/'),
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Viện Nghiên cứu và Phát triển Kinh tế Xanh',
            'alternateName' => 'GREECO Institute',
            'url' => url('/'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => setting('logo_dark') ? asset('storage/' . setting('logo_dark')) : asset('assets/images/logo-greeco.png'),
            ],
        ],
    ];
@endphp

<x-schema.json-ld :data="$schema" />
