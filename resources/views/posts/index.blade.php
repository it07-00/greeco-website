@php
    $pageNumber = max((int) request()->query('page', 1), 1);
    $hasActiveTag = filled($activeTag);
    $seoTitle = $hasActiveTag
        ? 'Tin tức về ' . $activeTag . ' - Viện GREECO'
        : 'Tin tức & Sự kiện - Viện Nghiên cứu và Phát triển Kinh tế Xanh - GREECO';
    $seoDescription = $hasActiveTag
        ? 'Các bài viết, tin tức và góc nhìn chuyên gia của Viện GREECO về ' . $activeTag . '.'
        : 'Cập nhật tin tức, sự kiện mới nhất về kinh tế xanh, biến đổi khí hậu và chuyển đổi phát triển bền vững tại Việt Nam.';

    if ($pageNumber > 1) {
        $seoTitle .= ' - Trang ' . $pageNumber;
    }

    // Canonical always points to clean URL (no ?tag=) to avoid duplicate content.
    // Only ?page= is kept for paginated canonical.
    $canonical = route('posts.index', array_filter([
        'page' => $pageNumber > 1 ? $pageNumber : null,
    ]));

    // Tag-filtered pages should not be indexed — they are duplicates of the main listing.
    $indexable = !$hasActiveTag;

    $breadcrumbs = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => 'Tin tức', 'url' => route('posts.index')]
    ];
@endphp

@extends('layouts.app')

@if($posts->previousPageUrl())
    @push('seo')
        <link rel="prev" href="{{ $posts->previousPageUrl() }}">
    @endpush
@endif

@if($posts->nextPageUrl())
    @push('seo')
        <link rel="next" href="{{ $posts->nextPageUrl() }}">
    @endpush
@endif

@section('content')
    <x-schema.breadcrumb :items="$breadcrumbs" />
    <x-schema.post-list
        :items="$posts"
        :name="$seoTitle"
        :description="$seoDescription"
        :url="$canonical"
    />

    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        <section id="subheader" class="relative">
            <div class="container relative z-index-1000">
                <div class="row">
                    <div class="col-lg-6">
                        <ul class="crumb">
                            <li><a href="{{ route('home') }}">Trang chủ</a></li>
                            <li class="active">Tin tức</li>
                        </ul>
                        <h1 class="text-uppercase">Tin tức</h1>
                        <p class="col-lg-10 lead">Kiến tạo không gian xanh hướng tới tương lai bền vững!</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row g-4">
                    @if($posts->isNotEmpty())
                        @foreach($posts as $post)
                            @php
                                $postImg = $post->thumbnail ? asset('storage/' . $post->thumbnail) : asset('assets/images/blog/' . (($loop->index % 6) + 1) . '.webp');
                            @endphp
                            <!-- post -->
                            <div class="col-lg-6">
                                <div class="rounded-1 bg-light overflow-hidden blog-post-card">
                                    <div class="row g-2 h-100">
                                        <div class="col-sm-6">
                                            <div class="blog-post-img-container relative">
                                                <img src="{{ $postImg }}" width="1024" height="1024" class="blog-post-img" alt="{{ $post->title }}" loading="lazy" decoding="async">
                                            </div>
                                        </div>   
                                        <div class="col-sm-6 relative">
                                            <div class="p-30 pb-40">
                                                <h4><a class="text-dark" href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h4>
                                                <div class="post-card-meta mb-2">
                                                    <i class="fa-regular fa-eye" aria-hidden="true"></i>
                                                    <span>{{ number_format($post->views_count ?? 0) }} lượt xem</span>
                                                </div>
                                                <p class="mb-0 fs-14 text-muted">{{ Str::limit($post->excerpt, 120) }}</p>
                                            </div>
                                        </div>                             
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">Đang cập nhật danh sách bài viết...</p>
                        </div>
                    @endif

                    @if($posts->hasPages())
                        <div class="col-lg-12 pt-4 d-flex justify-content-center">
                            {{ $posts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
        
    </div>
@endsection
