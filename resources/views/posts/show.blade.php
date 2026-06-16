@php
    $seoTitle = $post->meta_title ?: $post->title . ' | ' . config('app.name');
    $seoDescription = $post->meta_description ?: $post->excerpt;
    $canonical = $post->canonical_url ?: route('posts.show', $post);
    $ogImage = $post->og_image
        ? asset('storage/' . $post->og_image)
        : asset('assets/images/og-default.jpg');
    $indexable = $post->is_indexable;
    
    $breadcrumbs = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => 'Tin tức', 'url' => route('posts.index')],
        ['name' => $post->title, 'url' => $canonical]
    ];
@endphp

@extends('layouts.app')


@section('content')
    <x-schema.breadcrumb :items="$breadcrumbs" />
    <x-schema.article 
        :headline="$post->title"
        :description="$seoDescription"
        :image="$post->thumbnail ? asset('storage/' . $post->thumbnail) : asset('assets/images/background/8.webp')"
        :publishDate="$post->published_at ? $post->published_at->toIso8601String() : now()->toIso8601String()"
        :modifiedDate="$post->updated_at->toIso8601String()"
        :url="$canonical"
    />

    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        <section id="subheader" class="relative">
            <div class="container relative z-index-1000">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="crumb">
                            <li><a href="{{ route('home') }}">Trang chủ</a></li>
                            <li><a href="{{ route('posts.index') }}">Tin tức</a></li>
                            <li class="active">Tin tức chi tiết</li>
                        </ul>
                        <p class="text-uppercase h1 mb-0">Tin tức</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row gx-5">
                    <div class="col-lg-8">
                        <div class="blog-read">
                            <h1 class="text-dark fs-36 fw-700 lh-1-3 mb-3">{{ $post->title }}</h1>
                            @if($post->published_at)
                                <div class="post-meta mb-3 text-muted fs-14">
                                    <i class="fa-regular fa-calendar-days me-2"></i>{{ $post->published_at->format('d/m/Y') }}
                                    @if($post->category)
                                        <span class="mx-2">|</span><i class="fa-regular fa-folder-open me-2"></i>{{ $post->category->name }}
                                    @endif
                                </div>
                            @endif
                            <hr class="border-dashed my-4">
                            
                            @if($post->excerpt)
                                <p class="lead fw-600 text-dark fs-18 mb-4">{{ $post->excerpt }}</p>
                            @endif

                            <div class="post-text">
                                {!! $post->content !!}
                            </div>
                            
                            @if(!empty($post->tags))
                                <div class="post-tags mt-4">
                                    <span class="fw-bold me-2"><i class="fa fa-tags me-1"></i>Thẻ:</span>
                                    @foreach($post->tags as $tag)
                                        <a href="{{ route('posts.index', ['tag' => $tag]) }}" class="badge bg-success text-white text-decoration-none me-1 py-2 px-3">{{ $tag }}</a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <div class="widget widget-post">
                            <h4>Bài viết liên quan</h4>
                            @if($relatedPosts->isNotEmpty())
                                <ul class="de-bloglist-type-1">
                                    @foreach($relatedPosts as $relPost)
                                        <li>
                                            <div class="d-image">
                                                <img src="{{ $relPost->thumbnail ? asset('storage/' . $relPost->thumbnail) : asset('assets/images/blog-thumbnail/2.webp') }}" alt="{{ $relPost->title }}">
                                            </div>
                                            <div class="d-content">
                                                <a href="{{ route('posts.show', $relPost) }}"><h4>{{ $relPost->title }}</h4></a>
                                                <div class="d-date">
                                                    {{ $relPost->published_at ? 'Ngày ' . $relPost->published_at->format('d') . ' tháng ' . $relPost->published_at->format('m') . ' năm ' . $relPost->published_at->format('Y') : '' }}
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">Không có bài viết liên quan khác.</p>
                            @endif
                        </div>
                        
                        <div class="widget widget_tags">
                            <h4>Từ khóa phổ biến</h4>
                            <ul>
                                <li><a href="{{ route('posts.index', ['tag' => 'Kinh tế xanh']) }}">Kinh tế xanh</a></li>
                                <li><a href="{{ route('posts.index', ['tag' => 'ESG']) }}">ESG</a></li>
                                <li><a href="{{ route('posts.index', ['tag' => 'Net Zero']) }}">Net Zero</a></li>
                                <li><a href="{{ route('posts.index', ['tag' => 'Carbon']) }}">Carbon</a></li>
                                <li><a href="{{ route('posts.index', ['tag' => 'Môi trường']) }}">Môi trường</a></li>
                                <li><a href="{{ route('posts.index', ['tag' => 'Tái chế']) }}">Tái chế</a></li>
                                <li><a href="{{ route('posts.index', ['tag' => 'Bền vững']) }}">Bền vững</a></li>
                                <li><a href="{{ route('posts.index', ['tag' => 'Tuần hoàn']) }}">Tuần hoàn</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
