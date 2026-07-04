<div id="course-results" aria-live="polite">
    @if($programs->isNotEmpty())
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="courses-catalog-grid">
            @foreach ($programs as $program)
                <div class="col course-catalog-item">
                    <article class="course-catalog-card h-100 d-flex flex-column">
                        <div class="course-card-img-wrapper">
                            <a href="{{ route('services.course-detail', ['slug' => $program['slug']]) }}">
                                <img src="{{ $program['image'] }}" width="1024" height="1024" alt="{{ $program['title'] }}" loading="lazy" decoding="async">
                            </a>
                            @if(!empty($program['tag']))
                                <span class="badge course-tag-badge {{ $program['badge_color'] }} text-white">
                                    {{ $program['tag'] }}
                                </span>
                            @endif
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h3 class="academy-heading fs-6 fw-bold mb-2">
                                <a href="{{ route('services.course-detail', ['slug' => $program['slug']]) }}"
                                    class="text-reset text-decoration-none">
                                    {{ $program['title'] }}
                                </a>
                            </h3>
                            <p class="text-dark small mb-3 flex-grow-1 course-description">
                                {{ $program['description'] }}
                            </p>
                            <div class="text-dark small mb-3">Viện GREECO</div>

                            <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top">
                                <div>
                                    <strong class="text-success fs-5 d-block">{{ $program['price'] }}</strong>
                                    @if(!empty($program['original_price']))
                                        <small class="text-secondary text-decoration-line-through">{{ $program['original_price'] }}</small>
                                    @endif
                                </div>
                                <a href="{{ route('services.course-detail', ['slug' => $program['slug']]) }}"
                                    class="btn btn-sm btn-register-now">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        @if($programs->hasPages())
            <div class="mt-5 d-flex justify-content-center course-pagination">
                {{ $programs->fragment('chuong-trinh-dao-tao')->links('pagination::bootstrap-5', ['ariaLabel' => 'Phân trang khóa học']) }}
            </div>
        @endif
    @else
        <div class="course-empty-state text-center">
            <i class="fa-solid fa-magnifying-glass mb-3" aria-hidden="true"></i>
            <h3 class="academy-heading fs-5 fw-bold">Chưa tìm thấy khóa học phù hợp</h3>
            <p class="text-secondary mb-0">Hãy thử từ khóa khác hoặc chọn lại danh mục.</p>
        </div>
    @endif
</div>
