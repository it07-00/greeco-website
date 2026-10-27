@props(['course', 'url', 'image'])

@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Course',
        'name' => $course->title,
        'description' => $course->description ?: __('Chương trình đào tạo') . ' ' . $course->title . ' ' . __('của GREECO Institute.'),
        'url' => website_url($url),
        'image' => $image,
        'provider' => [
            '@type' => 'Organization',
            'name' => __('Viện Nghiên cứu và Phát triển Kinh tế Xanh'),
            'alternateName' => 'GREECO Institute',
            'url' => url('/'),
        ],
    ];

    if ($course->category) {
        $schema['about'] = $course->category->name;
    }
@endphp

<x-schema.json-ld :data="$schema" />
