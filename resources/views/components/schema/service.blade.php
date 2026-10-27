@props(['name', 'description', 'url'])

@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => __($name),
        'description' => __($description),
        'url' => website_url($url),
        'provider' => [
            '@type' => 'Organization',
            'name' => __('Viện Nghiên cứu và Phát triển Kinh tế Xanh'),
            'alternateName' => 'GREECO Institute',
            'url' => url('/'),
            'logo' => setting('logo_dark') ? asset('storage/' . setting('logo_dark')) : asset('assets/images/logo-greeco.png'),
        ],
        'areaServed' => [
            '@type' => 'Country',
            'name' => 'Vietnam',
        ],
    ];
@endphp

<x-schema.json-ld :data="$schema" />
