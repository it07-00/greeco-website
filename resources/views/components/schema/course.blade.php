@props(['course', 'url', 'image'])

@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Course',
        'name' => $course->title,
        'description' => $course->description ?: 'Chương trình đào tạo ' . $course->title . ' của GREECO Institute.',
        'url' => $url,
        'image' => $image,
        'provider' => [
            '@type' => 'Organization',
            'name' => 'Viện Nghiên cứu và Phát triển Kinh tế Xanh',
            'alternateName' => 'GREECO Institute',
            'url' => url('/'),
        ],
    ];

    if ($course->category) {
        $schema['about'] = $course->category->name;
    }
@endphp

<x-schema.json-ld :data="$schema" />
