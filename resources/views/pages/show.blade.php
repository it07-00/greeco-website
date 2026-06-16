@php
    $seoTitle = $page->meta_title ?: $page->title . ' | ' . config('app.name');
    $seoDescription = $page->meta_description ?: $page->excerpt;
    $canonical = $page->canonical_url ?: route('pages.show', $page);
    $ogImage = $page->og_image
        ? asset('storage/' . $page->og_image)
        : asset('assets/images/og-default.jpg');
    $indexable = $page->is_indexable;

    $breadcrumbs = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => $page->title, 'url' => $canonical],
    ];
@endphp

@extends('layouts.app')

@section('content')
    <x-schema.breadcrumb :items="$breadcrumbs" />

    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        <section id="subheader" class="relative">
            <div class="container relative z-index-1000">
                <div class="row">
                    <div class="col-lg-8">
                        <ul class="crumb">
                            <li><a href="{{ route('home') }}">Trang chủ</a></li>
                            <li class="active">{{ $page->title }}</li>
                        </ul>
                        <h1 class="text-uppercase">{{ $page->title }}</h1>
                        @if ($page->excerpt)
                            <p class="col-lg-10 lead">{{ $page->excerpt }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="page-content">
                            {!! $page->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
