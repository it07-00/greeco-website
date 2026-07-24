@php
    $seoTitle = $post->meta_title ?: $post->title . ' | ' . config('app.name');
    $seoDescription = $post->meta_description ?: $post->excerpt;
    $canonical = $post->canonical_url ?: route('slug.show', $post->slug);
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
    $subheaderLabel = $post->category?->name ?? 'Tin tức';
    $wordCount = str_word_count(strip_tags($post->content ?? $post->excerpt ?? ''));
    $readTime = max(1, (int) ceil($wordCount / 200));
@endphp

@extends('layouts.app')

@push('styles')
    <style>
        .posts-show-page {
            padding-bottom: 90px;
            background-color: #f8fafc;
        }

        #subheader.article-hero-header {
            background: linear-gradient(135deg, #09201c 0%, #123832 50%, #0d2d28 100%) !important;
            padding: 135px 0 55px !important;
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        #subheader.article-hero-header::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(0, 255, 202, 0.08) 0%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .category-badge-hero {
            background: rgba(0, 255, 202, 0.14);
            color: #00ffca;
            border: 1px solid rgba(0, 255, 202, 0.35);
            font-size: 0.8rem;
            font-weight: 700;
            padding: 5px 15px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .article-read-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            padding: 36px;
            margin-bottom: 30px;
        }

        @media (max-width: 767px) {
            .article-read-card {
                padding: 20px;
            }
        }

        .post-detail-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            align-items: center;
            padding: 14px 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #edf2f7;
            color: #64748b;
            font-size: 0.88rem;
            margin-bottom: 24px;
        }

        .post-detail-meta__item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .post-detail-meta__item i {
            color: #008f79;
        }

        .article-lead-box {
            background: #f0fdfa;
            border-left: 4px solid #008f79;
            border-radius: 0 12px 12px 0;
            padding: 20px 24px;
            color: #0f766e;
            font-size: 1.08rem;
            line-height: 1.7;
            font-weight: 500;
            margin-bottom: 30px;
        }

        .post-text {
            color: #1e293b;
            font-size: 1.05rem;
            line-height: 1.85;
        }

        .post-text h2,
        .post-text h3,
        .post-text h4 {
            color: #0f172a;
            font-weight: 700;
            margin-top: 36px;
            margin-bottom: 16px;
            line-height: 1.35;
        }

        .post-text h2 {
            font-size: 1.6rem;
            border-left: 4px solid #008f79;
            padding-left: 14px;
        }

        .post-text h3 {
            font-size: 1.35rem;
        }

        .post-text img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 20px 0;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        }

        .post-text blockquote {
            background: #f8fafc;
            border-left: 4px solid #008f79;
            margin: 24px 0;
            padding: 20px 24px;
            border-radius: 0 12px 12px 0;
            font-style: italic;
            color: #334155;
        }

        .share-bar-container {
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
            margin-top: 35px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .share-btn {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            transition: all 0.25s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
        }

        .share-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .share-facebook {
            background: #1877f2 !important;
            color: #ffffff !important;
        }

        .share-twitter {
            background: #000000 !important;
            color: #ffffff !important;
        }

        .share-zalo {
            background: #0068ff !important;
            color: #ffffff !important;
            font-weight: 800;
        }

        .share-copy {
            background: #f1f5f9 !important;
            color: #475569 !important;
        }

        /* Sidebar Card Custom */
        .sidebar-widget-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            padding: 24px;
            margin-bottom: 25px;
        }

        .sidebar-widget-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 20px;
            padding-left: 12px;
            border-left: 4px solid #008f79;
        }

        .related-post-item {
            display: flex;
            gap: 14px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .related-post-item:last-child {
            border-bottom: none;
        }

        .related-post-img {
            width: 76px;
            height: 76px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .related-post-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .related-post-title:hover {
            color: #008f79;
        }

        .tag-pill {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 50px;
            text-decoration: none;
            margin: 0 4px 8px 0;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
        }

        .tag-pill:hover {
            background: #008f79;
            color: #ffffff;
            border-color: #008f79;
        }

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

    <div class="no-bottom no-top posts-show-page" id="content">
        <div id="top"></div>

        <!-- Article Hero Header -->
        <section id="subheader" class="relative article-hero-header">
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

        <!-- Main Article Container -->
        <section class="py-5">
            <div class="container">
                <div class="row gx-5">
                    <!-- Main Content -->
                    <div class="col-lg-8">
                        <div class="article-read-card blog-read">
                            <h1 class="text-dark fs-36 fw-700 lh-1-3 mb-3">{{ $post->title }}</h1>

                            <!-- Article Metadata Bar -->
                            <div class="post-detail-meta mb-3">
                                <span class="post-detail-meta__item">
                                    <i class="fa-solid fa-building me-1"></i>
                                    <span class="fw-semibold text-dark">Viện GREECO</span>
                                </span>
                                @if($post->published_at)
                                    <span class="post-detail-meta__item">
                                        <i class="fa-regular fa-calendar me-1"></i>
                                        <time datetime="{{ $post->published_at->format('Y-m-d') }}">{{ $post->published_at->format('d/m/Y') }}</time>
                                    </span>
                                @endif
                                <span class="post-detail-meta__item">
                                    <i class="fa-regular fa-eye me-1"></i>
                                    <span>{{ number_format($post->views_count ?? 0) }} lượt xem</span>
                                </span>
                                <span class="post-detail-meta__item">
                                    <i class="fa-regular fa-clock me-1"></i>
                                    <span>{{ $readTime }} phút đọc</span>
                                </span>
                            </div>

                            @if($post->excerpt)
                                <div class="article-lead-box">
                                    <i class="fa-solid fa-quote-left me-2 opacity-50"></i>{{ $post->excerpt }}
                                </div>
                            @endif

                            <!-- Post Main Content -->
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
                            
                            <!-- Article Tags -->
                            @if(!empty($post->tags))
                                <div class="post-tags mt-4 pt-3 border-top">
                                    <span class="fw-bold me-2 text-dark"><i class="fa-solid fa-tags me-1 text-success"></i>Thẻ bài viết:</span>
                                    @foreach($post->tags as $tag)
                                        <a href="{{ route('posts.index', ['tag' => $tag]) }}" rel="nofollow" class="tag-pill">{{ $tag }}</a>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Social Share Bar -->
                            <div class="share-bar-container">
                                <span class="fw-bold text-dark"><i class="fa-solid fa-share-nodes me-2 text-success"></i>Chia sẻ bài viết:</span>
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
                        <!-- Related Posts Card -->
                        <div class="sidebar-widget-card">
                            <h4 class="sidebar-widget-title">Bài viết liên quan</h4>
                            @if($relatedPosts->isNotEmpty())
                                <div class="d-flex flex-column">
                                    @foreach($relatedPosts as $relPost)
                                        <div class="related-post-item">
                                            <img src="{{ $relPost->thumbnail ? asset('storage/' . $relPost->thumbnail) : asset('assets/images/blog-thumbnail/2.webp') }}" alt="{{ $relPost->title }}" class="related-post-img" loading="lazy" decoding="async">
                                            <div>
                                                <a href="{{ route('slug.show', $relPost->slug) }}" class="related-post-title">{{ $relPost->title }}</a>
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fa-regular fa-eye me-1"></i>{{ number_format($relPost->views_count ?? 0) }} lượt xem
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted small mb-0">Không có bài viết liên quan khác.</p>
                            @endif
                        </div>
                        
                        <!-- Popular Tags Card -->
                        <div class="sidebar-widget-card">
                            <h4 class="sidebar-widget-title">Từ khóa phổ biến</h4>
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('posts.index', ['tag' => 'Kinh tế xanh']) }}" rel="nofollow" class="tag-pill">Kinh tế xanh</a>
                                <a href="{{ route('posts.index', ['tag' => 'ESG']) }}" rel="nofollow" class="tag-pill">ESG</a>
                                <a href="{{ route('posts.index', ['tag' => 'Net Zero']) }}" rel="nofollow" class="tag-pill">Net Zero</a>
                                <a href="{{ route('posts.index', ['tag' => 'Carbon']) }}" rel="nofollow" class="tag-pill">Carbon</a>
                                <a href="{{ route('posts.index', ['tag' => 'Môi trường']) }}" rel="nofollow" class="tag-pill">Môi trường</a>
                                <a href="{{ route('posts.index', ['tag' => 'Tái chế']) }}" rel="nofollow" class="tag-pill">Tái chế</a>
                                <a href="{{ route('posts.index', ['tag' => 'Bền vững']) }}" rel="nofollow" class="tag-pill">Bền vững</a>
                                <a href="{{ route('posts.index', ['tag' => 'Tuần hoàn']) }}" rel="nofollow" class="tag-pill">Tuần hoàn</a>
                            </div>
                        </div>

                        <!-- Consultation CTA Card -->
                        <div class="sidebar-widget-card bg-light border-0 text-center py-4">
                            <i class="fa-solid fa-lightbulb text-success fs-1 mb-3"></i>
                            <h5 class="fw-bold text-dark mb-2">Tư vấn Phát triển Bền vững</h5>
                            <p class="text-secondary small mb-4">Liên hệ Viện GREECO để được tư vấn chuyên sâu về tín chỉ carbon, ESG và chuẩn hóa quốc tế.</p>
                            <a href="{{ route('contact') }}" class="btn btn-success text-white rounded-pill px-4 py-2 fw-bold w-100 shadow-sm">Liên hệ ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ versioned_asset('assets/js/post-detail.js') }}" defer></script>
@endpush

