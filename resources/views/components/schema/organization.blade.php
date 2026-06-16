@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Viện Nghiên cứu và Phát triển Kinh tế Xanh',
        'alternateName' => 'GREECO Institute',
        'url' => url('/'),
        'logo' => setting('logo_dark') ? asset('storage/' . setting('logo_dark')) : asset('assets/images/logo-greeco.png'),
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => setting('phone', '09369 96390'),
            'contactType' => 'customer service',
            'areaServed' => 'VN',
            'availableLanguage' => ['Vietnamese'],
        ],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => setting('address', '150 Đường 38-CL, Phường Cát Lái, TP. HCM'),
            'addressLocality' => 'TP. Hồ Chí Minh',
            'addressCountry' => 'VN',
        ],
        'email' => setting('email', 'info@greeco.vn'),
    ];
@endphp

<x-schema.json-ld :data="$schema" />
