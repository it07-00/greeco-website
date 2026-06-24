@php
    $seoTitle = $post->meta_title ?: $post->title . ' | ' . config('app.name');
    $seoDescription = $post->meta_description ?: $post->excerpt;
    $canonical = $post->canonical_url ?: route('posts.show', $post);
    $ogImage = $post->og_image
        ? asset('storage/' . $post->og_image)
        : asset('assets/images/og-default.jpg');
    $indexable = $post->is_indexable;
    $seoType = 'article';
    $seoPublishedTime = $post->published_at?->toIso8601String();
    $seoModifiedTime = $post->updated_at->toIso8601String();
    $seoSection = $post->category?->name;
    $seoTags = $post->tags ?? [];
    
    $breadcrumbs = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => 'Tin tức', 'url' => route('posts.index')],
        ['name' => $post->title, 'url' => $canonical]
    ];
    $subheaderLabel = $post->category?->name ?? 'Chi tiết bài viết';
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
        :section="$seoSection"
        :tags="$seoTags"
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
                            <li class="active">Chi tiết bài viết</li>
                        </ul>
                        <p class="text-uppercase h1 mb-0">{{ $subheaderLabel }}</p>
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
                            <div class="post-detail-meta mb-3">
                                <span class="post-detail-meta__item">
                                    <i class="fa-regular fa-eye" aria-hidden="true"></i>
                                    <span>{{ number_format($post->views_count ?? 0) }} lượt xem</span>
                                </span>
                                @if($post->category)
                                    <span class="post-detail-meta__item">
                                        <i class="fa-regular fa-folder-open" aria-hidden="true"></i>
                                        <span>{{ $post->category->name }}</span>
                                    </span>
                                @endif
                            </div>
                            <hr class="border-dashed my-4">
                            
                            @if($post->excerpt)
                                <p class="lead fw-600 text-dark fs-18 mb-4">{{ $post->excerpt }}</p>
                            @endif

                            <div class="post-text">
                                @if($post->content_type === 'pdf' && $post->pdf_path)
                                    <!-- Desktop PDF Iframe Viewer -->
                                    <div class="d-none d-lg-block mb-4">
                                        <div class="pdf-viewer-wrapper shadow-soft rounded-10px overflow-hidden border mb-4" style="border-color: rgba(0,0,0,0.08) !important;">
                                            <iframe src="{{ Storage::url($post->pdf_path) }}" 
                                                    width="100%" 
                                                    height="900px" 
                                                    style="border: none; display: block; background-color: #f3f4f6;">
                                            </iframe>
                                        </div>
                                        
                                        <div class="pdf-download-card p-4 border rounded-10px bg-light my-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="border-color: rgba(25, 135, 84, 0.2) !important;">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-success text-white p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fa-regular fa-file-pdf fs-22"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-1 text-dark fw-bold">Tài liệu đính kèm bài viết</h5>
                                                    <span class="text-muted fs-14">Tệp tin định dạng PDF</span>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="{{ Storage::url($post->pdf_path) }}" target="_blank" class="btn btn-outline-pdf-custom py-2 px-4 rounded-3 fw-bold d-flex align-items-center gap-2">
                                                    <i class="fa-solid fa-up-right-from-square"></i> Mở tab mới
                                                </a>
                                                <a href="{{ Storage::url($post->pdf_path) }}" download class="btn btn-success text-white py-2 px-4 rounded-3 fw-bold d-flex align-items-center gap-2">
                                                    <i class="fa-solid fa-download"></i> Tải bản PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mobile PDF Preview Card -->
                                    <div class="d-block d-lg-none my-4">
                                        <div class="card border-0 shadow-sm rounded-10px overflow-hidden" style="background-color: #f8fafc; border: 1px solid rgba(25, 135, 84, 0.15) !important;">
                                            <div class="card-body p-4 text-center">
                                                <div class="bg-success-subtle text-success p-3 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background-color: rgba(25, 135, 84, 0.1);">
                                                    <i class="fa-regular fa-file-pdf fs-36 text-success"></i>
                                                </div>
                                                <h4 class="text-dark fw-bold mb-2">Tài liệu đính kèm</h4>
                                                <p class="text-muted fs-14 mb-4">Bài viết này được trình bày dưới dạng tài liệu PDF. Để có trải nghiệm đọc tốt nhất trên thiết bị di động, vui lòng chọn một trong hai tùy chọn dưới đây.</p>
                                                
                                                <div class="d-grid gap-3">
                                                    <a href="{{ Storage::url($post->pdf_path) }}" target="_blank" class="btn btn-outline-pdf-custom py-3 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2">
                                                        <i class="fa-solid fa-book-open fs-18"></i> Xem trực tuyến (Toàn màn hình)
                                                    </a>
                                                    <a href="{{ Storage::url($post->pdf_path) }}" download class="btn btn-success text-white py-3 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2">
                                                        <i class="fa-solid fa-download fs-18"></i> Tải bản PDF về máy
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {!! $post->content !!}
                                @endif
                            </div>
                            
                            @if(!empty($post->tags))
                                <div class="post-tags mt-4">
                                    <span class="fw-bold me-2"><i class="fa fa-tags me-1"></i>Thẻ:</span>
                                    @foreach($post->tags as $tag)
                                        <a href="{{ route('posts.index', ['tag' => $tag]) }}" class="badge bg-success text-white text-decoration-none me-1 py-2 px-3">{{ $tag }}</a>
                                    @endforeach
                                </div>
                            @endif

                            <div class="border-top pt-3 mt-4 d-flex align-items-center gap-3 flex-wrap">
                                <span class="fw-bold text-dark"><i class="fa-solid fa-share-nodes me-2"></i>Chia sẻ bài viết:</span>
                                <div class="d-flex gap-2">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($canonical) }}" target="_blank" rel="noopener noreferrer" class="btn btn-light rounded-circle d-inline-flex align-items-center justify-content-center p-0 share-btn share-facebook" title="Chia sẻ lên Facebook">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($canonical) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" class="btn btn-light rounded-circle d-inline-flex align-items-center justify-content-center p-0 share-btn share-twitter" title="Chia sẻ lên Twitter / X">
                                        <i class="fa-brands fa-x-twitter"></i>
                                    </a>
                                    <a href="https://zalo.me/s/share?url={{ urlencode($canonical) }}" target="_blank" rel="noopener noreferrer" class="btn btn-light rounded-circle d-inline-flex align-items-center justify-content-center p-0 share-btn share-zalo" title="Chia sẻ lên Zalo">
                                        <span class="zalo-share-icon">Z</span>
                                    </a>
                                    <button class="btn btn-light rounded-circle d-inline-flex align-items-center justify-content-center p-0 share-btn share-copy position-relative" id="copy-share-link" data-url="{{ $canonical }}" title="Sao chép liên kết">
                                        <i class="fa-solid fa-link"></i>
                                        <span class="copy-tooltip" id="copy-tooltip">Đã sao chép!</span>
                                    </button>
                                </div>
                            </div>
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
                                                    {{ number_format($relPost->views_count ?? 0) }} lượt xem
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

@push('styles')
    <style>
        .btn-outline-pdf-custom {
            border: 2px solid #198754 !important;
            color: #198754 !important;
            background-color: transparent !important;
            transition: all 0.3s ease;
        }
        .btn-outline-pdf-custom:hover {
            background-color: #198754 !important;
            color: #ffffff !important;
            border-color: #198754 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ versioned_asset('assets/js/post-detail.js') }}" defer></script>
@endpush
