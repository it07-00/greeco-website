<div id="course-results" aria-live="polite">
    @if($programs->isNotEmpty())
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="courses-catalog-grid">
            @foreach ($programs as $program)
                @php
                    $displayTitle = (string) \Illuminate\Support\Str::of($program['title'])
                        ->lower()
                        ->ucfirst()
                        ->replace(
                            ['iso', 'hse', 'esg', 'qhse', 'psm', 'pdca'],
                            ['ISO', 'HSE', 'ESG', 'QHSE', 'PSM', 'PDCA']
                        );
                    $displayDescription = (string) \Illuminate\Support\Str::of(strip_tags($program['description']))
                        ->lower()
                        ->ucfirst()
                        ->replace(
                            ['iso', 'hse', 'esg', 'qhse', 'psm', 'pdca'],
                            ['ISO', 'HSE', 'ESG', 'QHSE', 'PSM', 'PDCA']
                        );
                @endphp
                <div class="col course-catalog-item">
                    <article class="course-catalog-card h-100 d-flex flex-column">
                        <div class="course-card-img-wrapper">
                            <a href="{{ website_route('slug.show', $program['slug']) }}">
                                <img src="{{ $program['image'] }}" width="1024" height="1024" alt="{{ $program['title'] }}" loading="lazy" decoding="async">
                            </a>
                        </div>
                        <div class="course-card-body">
                            <div class="course-card-topic">{{ $program['tag'] ?? __('Đào tạo chuyên môn') }}</div>
                            <h3 class="course-card-title">
                                <a href="{{ website_route('slug.show', $program['slug']) }}"
                                    class="text-reset text-decoration-none">
                                    {{ $displayTitle }}
                                </a>
                            </h3>
                            <p class="text-dark small mb-3 flex-grow-1 course-description">
                                {{ $displayDescription }}
                            </p>
                            <div class="course-card-footer">
                                <div>
                                    <span class="course-card-price-label">{{ __('Học phí') }}</span>
                                    <strong class="course-card-price">{{ $program['price'] }}</strong>
                                    @if(!empty($program['original_price']))
                                        <small class="text-secondary text-decoration-line-through">{{ $program['original_price'] }}</small>
                                    @endif
                                </div>
                                <a href="{{ website_route('slug.show', $program['slug']) }}"
                                    class="btn btn-sm btn-register-now"
                                    aria-label="{{ __('Xem chi tiết') }} {{ $program['title'] }}">
                                    <span class="visually-hidden">{{ __('Xem chi tiết') }}</span>
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        @if($programs->hasPages())
            <div class="mt-5 d-flex justify-content-center course-pagination">
                {{ $programs->fragment('chuong-trinh-dao-tao')->links('pagination::bootstrap-5', ['ariaLabel' => __('Phân trang khóa học')]) }}
            </div>
        @endif
    @else
        <div class="course-empty-state text-center">
            <i class="fa-solid fa-magnifying-glass mb-3" aria-hidden="true"></i>
            <h3 class="academy-heading fs-5 fw-bold">{{ __('Chưa tìm thấy khóa học phù hợp') }}</h3>
            <p class="text-secondary mb-0">{{ __('Hãy thử từ khóa khác hoặc chọn lại danh mục.') }}</p>
        </div>
    @endif
</div>
