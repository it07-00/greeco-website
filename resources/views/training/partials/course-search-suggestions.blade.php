<div class="course-search-suggestions-header">
    <span>{{ __('Gợi ý khóa học') }}</span>
    <small>{{ trans_choice('website.results', $programs->total()) }}</small>
</div>

@forelse($suggestionPrograms as $program)
    <a href="{{ website_route('slug.show', $program['slug']) }}"
        class="course-search-suggestion" role="option">
        <img src="{{ $program['image'] }}" width="56" height="56" alt="" loading="lazy" decoding="async">
        <span class="course-search-suggestion-content">
            <strong>{{ $program['title'] }}</strong>
            @if($program['category_name'] || $program['tag'])
                <small>{{ $program['category_name'] ?: $program['tag'] }}</small>
            @endif
        </span>
        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
    </a>
@empty
    <div class="course-search-suggestions-empty">
        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
        <span>{{ __('Chưa tìm thấy khóa học phù hợp.') }}</span>
    </div>
@endforelse

@if($programs->total() > $suggestionPrograms->count())
    <a href="{{ website_route('services.dao-tao', array_filter(['search' => $search, 'category' => $category])) }}#chuong-trinh-dao-tao"
        class="course-search-suggestions-all">
        {{ __('Xem tất cả') }} {{ trans_choice('website.results', $programs->total()) }}
    </a>
@endif
