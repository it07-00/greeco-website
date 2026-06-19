@props([
    'title' => null,
    'description' => null,
    'canonical' => null,
    'image' => null,
    'indexable' => true,
    'type' => 'website',
    'publishedTime' => null,
    'modifiedTime' => null,
    'section' => null,
    'tags' => [],
])

@php
    $title = trim($title ?? config('app.name'));
    $description = trim($description ?? '');
    $canonical = $canonical ?? url()->current();
    $type = trim($type ?: 'website');
    $image = $image ?? null;
    $section = filled($section) ? trim($section) : null;
    $tags = collect(is_iterable($tags) ? $tags : [])
        ->filter(fn ($tag) => filled($tag))
        ->map(fn ($tag) => trim($tag))
        ->values();
    $robots = isset($indexable) && $indexable === false
        ? 'noindex, nofollow'
        : 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
@endphp

<title>{{ $title }}</title>

@if($description !== '')
    <meta name="description" content="{{ $description }}">
@endif

<meta name="author" content="{{ setting('site_name', config('app.name')) }}">
<meta name="theme-color" content="#008f79">
<meta name="format-detection" content="telephone=no">
<meta name="language" content="vi">
<link rel="canonical" href="{{ $canonical }}">
<link rel="alternate" hreflang="vi-VN" href="{{ $canonical }}">
<link rel="alternate" hreflang="x-default" href="{{ $canonical }}">
<meta name="robots" content="{{ $robots }}">

<meta property="og:site_name" content="{{ setting('site_name', config('app.name')) }}">
<meta property="og:locale" content="vi_VN">
<meta property="og:title" content="{{ $title }}">
@if($description !== '')
    <meta property="og:description" content="{{ $description }}">
@endif
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $canonical }}">

@if(!empty($image))
    <meta property="og:image" content="{{ $image }}">
    <meta property="og:image:secure_url" content="{{ $image }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $title }}">
@endif

@if($type === 'article')
    @if(!empty($publishedTime))
        <meta property="article:published_time" content="{{ $publishedTime }}">
    @endif
    @if(!empty($modifiedTime))
        <meta property="article:modified_time" content="{{ $modifiedTime }}">
        <meta property="og:updated_time" content="{{ $modifiedTime }}">
    @endif
    @if(!empty($section))
        <meta property="article:section" content="{{ $section }}">
    @endif
    @foreach($tags as $tag)
        <meta property="article:tag" content="{{ $tag }}">
    @endforeach
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:url" content="{{ $canonical }}">
@if($description !== '')
    <meta name="twitter:description" content="{{ $description }}">
@endif

@if(!empty($image))
    <meta name="twitter:image" content="{{ $image }}">
    <meta name="twitter:image:alt" content="{{ $title }}">
@endif
