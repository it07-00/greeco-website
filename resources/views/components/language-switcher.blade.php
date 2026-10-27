<nav class="language-switcher d-inline-flex align-items-center gap-2 me-2" aria-label="{{ __('Ngôn ngữ') }}">
    @foreach (['vi' => 'VI', 'en' => 'EN'] as $locale => $label)
        <a href="{{ website_url(request()->fullUrl(), $locale) }}"
           lang="{{ $locale }}" hreflang="{{ $locale }}"
           aria-label="{{ $locale === 'vi' ? 'Tiếng Việt' : 'English' }}"
           onclick="const target = new URL(window.location.href); this.lang === 'en' ? target.searchParams.set('lang', 'en') : target.searchParams.delete('lang'); this.href = target.toString();"
           @if(app()->getLocale() === $locale) aria-current="true" @endif
           class="{{ app()->getLocale() === $locale ? 'fw-bold text-decoration-underline' : '' }}">{{ $label }}</a>
    @endforeach
</nav>
