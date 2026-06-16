@php
    $title = trim($title ?? config('app.name'));
    $description = trim($description ?? '');
    $canonical = $canonical ?? url()->current();
    $type = $type ?? 'website';
    $image = $image ?? null;
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
<link rel="canonical" href="{{ $canonical }}">
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

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
@if($description !== '')
    <meta name="twitter:description" content="{{ $description }}">
@endif

@if(!empty($image))
    <meta name="twitter:image" content="{{ $image }}">
    <meta name="twitter:image:alt" content="{{ $title }}">
@endif
