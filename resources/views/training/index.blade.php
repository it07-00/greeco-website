@php
    $pageNumber = max((int) request()->query('page', 1), 1);
    $hasFilters = filled($search) || filled($category);
    $seoTitle = 'Đào tạo & Bồi dưỡng Chuyên môn Bền vững - Viện GREECO';
    $seoDescription = 'Viện GREECO cung cấp các chương trình đào tạo và bồi dưỡng chuyên sâu, thực tiễn từ cơ bản đến nâng cao. Đội ngũ chuyên gia của chúng tôi hỗ trợ doanh nghiệp xây dựng năng lực nội tại, chuẩn hóa hệ thống quản lý và đáp ứng các tiêu chuẩn xuất khẩu khắt khe về phát triển bền vững và trung hòa carbon.';
    if ($pageNumber > 1) {
        $seoTitle .= ' - Trang ' . $pageNumber;
    }

    // Canonical always points to clean URL (no ?search= or ?category=) to avoid duplicate content.
    // Only ?page= is kept for paginated canonical.
    $canonical = route('services.dao-tao', array_filter([
        'page' => $pageNumber > 1 ? $pageNumber : null,
    ]));

    // Filtered pages should not be indexed — they are duplicates of the main listing.
    $indexable = !$hasFilters;

    $ogImage = asset('assets/images/services/iso-9001-course.png');
    $programItems = collect($programs->items());
    $firstCoursePosition = $programs->firstItem() ?? 1;

    $breadcrumbs = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => 'Đào tạo & Bồi dưỡng', 'url' => route('services.dao-tao')],
    ];

    $courseListSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'Chương trình đào tạo GREECO',
        'itemListElement' => $programItems->values()->map(fn ($program, $index) => [
            '@type' => 'ListItem',
            'position' => $firstCoursePosition + $index,
            'name' => $program['title'],
            'url' => route('slug.show', $program['slug']),
        ])->all(),
    ];
@endphp

@extends('layouts.app')

@if($programs->previousPageUrl())
    @push('seo')
        <link rel="prev" href="{{ $programs->previousPageUrl() }}">
    @endpush
@endif

@if($programs->nextPageUrl())
    @push('seo')
        <link rel="next" href="{{ $programs->nextPageUrl() }}">
    @endpush
@endif

@push('styles')
    <style>
        .academy-catalog-page {
            font-family: 'Be Vietnam Pro', sans-serif;
            background-color: #f8fafc;
        }

        .academy-hero-banner {
            background: linear-gradient(135deg, #0f2c28 0%, #173d37 100%) !important;
            color: #ffffff !important;
            padding: 8rem 0 2.5rem !important;
            position: relative;
            text-align: center;
        }

        .academy-hero-title {
            font-size: clamp(1.6rem, 2.5vw, 2.3rem);
            font-weight: 800;
            line-height: 1.15;
        }

        .academy-hero-copy {
            font-size: clamp(0.88rem, 1.05vw, 0.98rem);
        }

        .academy-hero-banner .container {
            max-width: 1060px;
        }

        .search-box-wrapper {
            max-width: 500px;
            margin: 0 auto;
            position: relative;
        }

        .search-box-input {
            border: 2px solid #008f79;
            border-radius: 50px;
            padding: 9px 20px;
            width: 100%;
            font-size: 0.88rem;
            outline: none;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .search-box-btn {
            position: absolute;
            right: 4px;
            top: 4px;
            background: #008f79;
            border: none;
            color: #ffffff;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            transition: background 0.2s ease;
        }

        .search-box-btn:hover {
            background: #007463;
        }

        .course-search-suggestions {
            position: absolute;
            top: calc(100% + 10px);
            left: 0;
            right: 0;
            z-index: 1050;
            max-height: 420px;
            overflow-y: auto;
            color: #1e293b;
            text-align: left;
            background: #ffffff;
            border: 1px solid #dbe4ea;
            border-radius: 16px;
            box-shadow: 0 20px 45px rgba(2, 20, 17, 0.28);
        }

        .course-search-suggestions[hidden] {
            display: none;
        }

        .course-search-suggestions-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            color: #475569;
            font-size: 0.78rem;
            font-weight: 700;
            border-bottom: 1px solid #edf2f7;
        }

        .course-search-suggestions-header small {
            color: #008f79;
            font-weight: 700;
        }

        .course-search-suggestion {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            color: #1e293b;
            text-decoration: none;
            border-bottom: 1px solid #edf2f7;
            transition: background-color 0.15s ease;
        }

        .course-search-suggestion:hover,
        .course-search-suggestion:focus-visible {
            color: #006c5b;
            background: #f0fdfa;
        }

        .course-search-suggestion img {
            width: 56px;
            height: 56px;
            flex: 0 0 56px;
            border-radius: 10px;
            object-fit: cover;
            background: #f1f5f9;
        }

        .course-search-suggestion-content {
            min-width: 0;
            flex: 1;
        }

        .course-search-suggestion-content strong,
        .course-search-suggestion-content small {
            display: block;
        }

        .course-search-suggestion-content strong {
            display: -webkit-box;
            overflow: hidden;
            font-size: 0.9rem;
            line-height: 1.4;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .course-search-suggestion-content small {
            margin-top: 4px;
            overflow: hidden;
            color: #64748b;
            font-size: 0.72rem;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .course-search-suggestion > i {
            color: #94a3b8;
            font-size: 0.75rem;
        }

        .course-search-suggestions-empty,
        .course-search-suggestions-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 92px;
            padding: 18px;
            color: #64748b;
            font-size: 0.85rem;
        }

        .course-search-suggestions-all {
            display: block;
            padding: 12px 16px;
            color: #008f79;
            font-size: 0.82rem;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            background: #f8fafc;
        }

        .course-search-suggestions-all:hover {
            color: #006c5b;
            background: #ecfdf5;
        }

        .stat-item {
            text-align: center;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.8rem;
        }

        .stat-icon {
            font-size: 1.25rem;
            color: #00ffca;
            margin-bottom: 4px;
            display: block;
        }

        .category-box {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            border-radius: 10px;
            min-height: 84px;
            padding: 10px 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: #ffffff;
        }

        .category-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            border-color: #008f79;
        }

        .category-box.active {
            border-color: #008f79 !important;
            background-color: #f0fdfa !important;
            box-shadow: 0 6px 16px rgba(0, 143, 121, 0.1) !important;
            transform: translateY(-2px);
        }

        .category-icon-wrapper {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            margin-bottom: 6px;
        }

        .academy-category-panel {
            max-width: 1120px;
            margin: 2rem auto;
        }

        .academy-category-panel .category-count {
            color: #64748b;
            font-size: 0.7rem;
            font-weight: 500;
            margin-top: 3px;
        }

        .featured-courses-section {
            background:
                radial-gradient(circle at 8% 12%, rgba(0, 143, 121, .09), transparent 28%),
                #f2f7f5;
            padding: 110px 0 120px;
        }

        .featured-courses-header {
            align-items: end;
            display: flex;
            gap: 32px;
            justify-content: space-between;
            margin-bottom: 42px;
        }

        .featured-courses-title {
            color: #111;
            font-size: clamp(2.15rem, 3.3vw, 3.75rem);
            font-weight: 800;
            letter-spacing: -.05em;
            line-height: 1;
            margin: 0 0 12px;
        }

        .featured-courses-count {
            color: #3f4644;
            font-size: .92rem;
        }

        .featured-courses-request {
            align-items: center;
            border-bottom: 1px solid #8aa59e;
            color: #111;
            display: inline-flex;
            font-size: .85rem;
            font-weight: 800;
            gap: 9px;
            padding: 0 0 8px;
            text-decoration: none;
            transition: border-color .25s ease, color .25s ease, gap .25s ease;
        }

        .featured-courses-request:hover {
            border-color: #111;
            color: #111;
            gap: 13px;
        }

        #courses-catalog-grid {
            --bs-gutter-x: 24px;
            --bs-gutter-y: 24px;
        }

        .course-catalog-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            opacity: 1 !important;
            position: relative;
            transition: all 0.3s ease;
        }

        .course-catalog-card:hover {
            border-color: #008f79;
            box-shadow: 0 12px 30px rgba(0, 143, 121, 0.12);
            transform: translateY(-5px);
        }

        .course-card-img-wrapper {
            aspect-ratio: 16 / 10;
            background: #f1f5f9;
            overflow: hidden;
            opacity: 1 !important;
            position: relative;
        }

        .course-card-img-wrapper a {
            display: block;
            height: 100%;
        }

        .course-card-img-wrapper img {
            height: 100%;
            object-fit: cover;
            object-position: center;
            transform: scale(1);
            transition: transform 0.4s ease;
            width: 100%;
        }

        .course-catalog-card:hover .course-card-img-wrapper img {
            transform: scale(1.06);
        }

        .course-card-body {
            display: flex;
            flex: 1;
            flex-direction: column;
            padding: 20px 22px;
        }

        .course-card-topic {
            color: #111;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .03em;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .course-card-title {
            color: #111;
            display: -webkit-box;
            font-size: 1.1rem;
            font-weight: 800;
            letter-spacing: -.025em;
            line-height: 1.38;
            margin: 0 0 10px;
            overflow: hidden;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .course-card-title a {
            color: #111 !important;
            text-decoration: none !important;
            transition: color 0.2s ease;
        }

        .course-card-title a:hover {
            color: #111 !important;
            text-decoration: underline !important;
            text-decoration-color: #008f79 !important;
            text-underline-offset: 4px;
        }

        .course-rating-stars i {
            color: #ffc107;
            font-size: 0.85rem;
        }

        .course-description {
            color: #2f3533 !important;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            font-size: .86rem !important;
            line-height: 1.6;
            margin-bottom: 16px !important;
            overflow: hidden;
        }

        .course-card-footer {
            align-items: center;
            border-top: 1px solid #f1f5f9;
            display: flex;
            gap: 16px;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 14px;
        }

        .course-card-price-label {
            color: #4e5553;
            display: block;
            font-size: .7rem;
            font-weight: 600;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .course-card-price {
            color: #111;
            display: block;
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -.02em;
        }

        .btn-register-now {
            align-items: center;
            background: #008f79;
            border: 0;
            border-radius: 50%;
            color: #fff !important;
            display: inline-flex;
            flex: 0 0 38px;
            height: 38px;
            justify-content: center;
            text-decoration: none;
            transition: background .25s ease, transform .25s ease;
            width: 38px;
        }

        .btn-register-now:hover {
            background: #006c5c;
            color: #fff !important;
            transform: translateX(2px);
        }

        #course-results {
            transition: opacity 0.2s ease;
        }

        #course-results.is-loading {
            opacity: 0.45;
            pointer-events: none;
        }

        .course-empty-state {
            border: 1px dashed #cbd5e1;
            border-radius: 16px;
            background: #f8fafc;
            padding: 4rem 1rem;
        }

        .course-empty-state > i {
            color: #008f79;
            font-size: 2rem;
        }

        .instructor-avatar-card {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .testimonial-bubble {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            position: relative;
        }

        .newsletter-section {
            background: linear-gradient(135deg, #102a26 0%, #163d37 100%) !important;
            color: #ffffff;
            border-radius: 16px;
            padding: 50px 40px;
        }

        @media (max-width: 767px) {
            .featured-courses-section {
                padding: 76px 0 84px;
            }

            .featured-courses-header {
                align-items: flex-start;
                flex-direction: column;
                margin-bottom: 30px;
            }

            .course-card-title {
                min-height: auto;
            }
        }
    </style>
@endpush

@section('content')
    <x-schema.breadcrumb :items="$breadcrumbs" />
    <x-schema.json-ld :data="$courseListSchema" />

    <div class="academy-catalog-page">
        <!-- Hero Section -->
        <section class="academy-hero-banner">
            <div class="container">
                <h1 class="academy-hero-title text-white mb-2">Nâng cao năng lực Phát triển bền vững</h1>
                <p class="academy-hero-copy text-white-50 max-width-600 mx-auto mb-3">
                    Các khóa học chất lượng cao về ISO, HSE, ESG, Carbon giúp bạn và doanh nghiệp bứt phá trong kỷ nguyên
                    xanh.
                </p>

                <!-- Course Categories -->
                <div class="academy-category-panel my-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h2 class="fw-bold fs-6 mb-0 text-white">Danh mục khóa học</h2>
                        <a href="{{ route('services.dao-tao', array_filter(['search' => $search])) }}#chuong-trinh-dao-tao"
                            id="clear-category-filter" class="text-white fw-bold text-decoration-none small">
                            Xem tất cả
                        </a>
                    </div>

                    <div class="row row-cols-2 row-cols-md-4 g-2">
                        @foreach ($categories as $cat)
                            <div class="col">
                                <a href="{{ route('services.dao-tao', array_filter(['search' => $search, 'category' => $cat->slug])) }}#chuong-trinh-dao-tao"
                                    class="category-box h-100 {{ $category === $cat->slug ? 'active' : '' }}"
                                    data-category="{{ $cat->slug }}"
                                    aria-pressed="{{ $category === $cat->slug ? 'true' : 'false' }}">
                                    <div class="category-icon-wrapper"
                                        style="background-color: {{ $cat->bg }}; color: {{ $cat->color }};">
                                        <i class="fa-solid {{ $cat->icon }}"></i>
                                    </div>
                                    <strong class="academy-heading small text-dark" style="font-size: 0.78rem; line-height: 1.3;">{{ $cat->name }}</strong>
                                    <span class="category-count">{{ $cat->courses_count }} khóa học</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Search box -->
                <div class="search-box-wrapper">
                    <form action="{{ route('services.dao-tao') }}" method="GET" id="search-courses-form" role="search">
                        <input type="text" class="search-box-input" placeholder="Tìm khóa học bạn quan tâm..."
                            id="search-input" name="search" value="{{ $search }}" maxlength="100" autocomplete="off"
                            aria-autocomplete="list" aria-controls="course-search-suggestions" aria-expanded="false">
                        <input type="hidden" name="category" id="active-category-input" value="{{ $category }}">
                        <button type="submit" class="search-box-btn" aria-label="Tìm kiếm"><i
                                class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                    <div id="course-search-suggestions" class="course-search-suggestions"
                        role="listbox" aria-label="Gợi ý khóa học" aria-live="polite" hidden></div>
                </div>
            </div>
        </section>

        <!-- Featured Courses Section -->
        <section class="featured-courses-section" id="chuong-trinh-dao-tao">
            <div class="container">
                <div class="featured-courses-header">
                    <div>
                        <h2 class="featured-courses-title">Khóa học nổi bật</h2>
                        <span class="featured-courses-count" id="course-results-summary">{{ $programs->total() }} khóa học phù hợp</span>
                    </div>
                    <a
                        href="{{ route('contact') }}?message={{ urlencode('Tôi muốn yêu cầu tư vấn thiết kế chương trình đào tạo riêng cho doanh nghiệp.') }}"
                        class="featured-courses-request"
                    >
                        Yêu cầu chương trình riêng
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                @include('training.partials.course-results', ['programs' => $programs])
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="py-5 bg-white">
            <div class="container py-lg-4 max-width-800">
                <h2 class="academy-heading fw-bold fs-4 text-center mb-5">Học viên nói gì về GREECO</h2>
                <div class="testimonial-bubble mx-auto mb-4">
                    <p class="text-dark italic mb-4">
                        "Khóa học rất thực tế, giảng viên giàu kinh nghiệm, hỗ trợ nhiệt tình. Chứng chỉ của GREECO giúp ích
                        rất nhiều cho công việc của tôi."
                    </p>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="academy-icon-sm" style="width: 40px; height: 40px;"><i
                                    class="fa-solid fa-user"></i></span>
                            <div class="text-start">
                                <strong class="d-block academy-heading small">Nguyễn Hoàng Nam</strong>
                                <small class="text-dark" style="font-size: 0.75rem;">Chuyên viên QHSE</small>
                            </div>
                        </div>
                        <div class="course-rating-stars">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                            <small class="fw-bold text-dark ms-2">5.0</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var searchForm = document.getElementById('search-courses-form');
            var searchInput = document.getElementById('search-input');
            var searchSuggestions = document.getElementById('course-search-suggestions');
            var categoryInput = document.getElementById('active-category-input');
            var resultsSummary = document.getElementById('course-results-summary');
            var categoryLinks = document.querySelectorAll('.category-box');
            var clearFilterLink = document.getElementById('clear-category-filter');
            var debounceTimer;
            var activeRequest;

            function hideSearchSuggestions() {
                searchSuggestions.hidden = true;
                searchInput.setAttribute('aria-expanded', 'false');
            }

            function showSearchSuggestions(html) {
                searchSuggestions.innerHTML = html;
                searchSuggestions.hidden = false;
                searchInput.setAttribute('aria-expanded', 'true');
            }

            function showSearchSuggestionsLoading() {
                showSearchSuggestions(
                    '<div class="course-search-suggestions-loading">' +
                        '<span class="spinner-border spinner-border-sm text-success" aria-hidden="true"></span>' +
                        '<span>Đang tìm khóa học...</span>' +
                    '</div>'
                );
            }

            function setActiveCategory(category) {
                categoryInput.value = category;

                categoryLinks.forEach(function (link) {
                    var isActive = link.getAttribute('data-category') === category;
                    link.classList.toggle('active', isActive);
                    link.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                });
            }

            function buildUrl(pageUrl) {
                var url = new URL(pageUrl || searchForm.action, window.location.origin);
                var search = searchInput.value.trim();
                var category = categoryInput.value;

                if (!pageUrl) {
                    url.searchParams.delete('page');
                }

                if (search) {
                    url.searchParams.set('search', search);
                } else {
                    url.searchParams.delete('search');
                }

                if (category) {
                    url.searchParams.set('category', category);
                } else {
                    url.searchParams.delete('category');
                }

                return url;
            }

            function updateBrowserUrl(url) {
                var publicUrl = new URL(url.toString());
                publicUrl.hash = 'chuong-trinh-dao-tao';
                window.history.replaceState({}, '', publicUrl.toString());
            }

            function loadCourses(pageUrl, shouldScroll, shouldShowSuggestions) {
                var url = buildUrl(pageUrl);
                var currentResults = document.getElementById('course-results');
                var hasSearchQuery = searchInput.value.trim() !== '';

                if (activeRequest) {
                    activeRequest.abort();
                }

                if (shouldShowSuggestions && hasSearchQuery) {
                    showSearchSuggestionsLoading();
                }

                activeRequest = new AbortController();
                currentResults.classList.add('is-loading');
                currentResults.setAttribute('aria-busy', 'true');

                fetch(url.toString(), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    signal: activeRequest.signal
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Course search failed');
                        }

                        return response.json();
                    })
                    .then(function (payload) {
                        currentResults.outerHTML = payload.html;
                        resultsSummary.textContent = payload.count + ' khóa học phù hợp';
                        updateBrowserUrl(url);

                        if (shouldShowSuggestions) {
                            if (hasSearchQuery) {
                                showSearchSuggestions(payload.suggestions_html);
                            } else {
                                hideSearchSuggestions();
                            }
                        }

                        if (shouldScroll) {
                            document.getElementById('chuong-trinh-dao-tao').scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    })
                    .catch(function (error) {
                        if (error.name !== 'AbortError') {
                            currentResults.classList.remove('is-loading');
                            currentResults.removeAttribute('aria-busy');

                            if (shouldShowSuggestions) {
                                showSearchSuggestions(
                                    '<div class="course-search-suggestions-empty">' +
                                        '<span>Không thể tải gợi ý. Vui lòng thử lại.</span>' +
                                    '</div>'
                                );
                            }
                        }
                    });
            }

            searchForm.addEventListener('submit', function (event) {
                event.preventDefault();
                hideSearchSuggestions();
                loadCourses(null, true, false);
            });

            searchInput.addEventListener('input', function () {
                window.clearTimeout(debounceTimer);

                if (searchInput.value.trim() === '') {
                    hideSearchSuggestions();
                } else {
                    showSearchSuggestionsLoading();
                }

                debounceTimer = window.setTimeout(function () {
                    loadCourses(null, false, searchInput.value.trim() !== '');
                }, 300);
            });

            searchInput.addEventListener('focus', function () {
                if (searchInput.value.trim() !== '') {
                    loadCourses(null, false, true);
                }
            });

            searchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    hideSearchSuggestions();
                }
            });

            categoryLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();

                    var selectedCategory = link.getAttribute('data-category');
                    var nextCategory = categoryInput.value === selectedCategory ? '' : selectedCategory;

                    setActiveCategory(nextCategory);
                    hideSearchSuggestions();
                    loadCourses(null, true, false);
                });
            });

            clearFilterLink.addEventListener('click', function (event) {
                event.preventDefault();
                setActiveCategory('');
                hideSearchSuggestions();
                loadCourses(null, true, false);
            });

            document.addEventListener('click', function (event) {
                var pageLink = event.target.closest('#course-results .pagination a');

                if (pageLink) {
                    event.preventDefault();
                    hideSearchSuggestions();
                    loadCourses(pageLink.href, true, false);
                }

                if (!event.target.closest('.search-box-wrapper')) {
                    hideSearchSuggestions();
                }
            });

            window.addEventListener('popstate', function () {
                var currentUrl = new URL(window.location.href);
                searchInput.value = currentUrl.searchParams.get('search') || '';
                setActiveCategory(currentUrl.searchParams.get('category') || '');
                hideSearchSuggestions();
                loadCourses(currentUrl.toString(), false, false);
            });
        });
    </script>
@endpush
