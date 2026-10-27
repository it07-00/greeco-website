{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
    @foreach (['vi', 'en'] as $sitemapLocale)
    @foreach ($staticRoutes as $staticRoute)
    <url>
        <loc>{{ website_url(route($staticRoute['route']), $sitemapLocale) }}</loc>
        <xhtml:link rel="alternate" hreflang="vi-VN" href="{{ website_url(route($staticRoute['route']), 'vi') }}" />
        <xhtml:link rel="alternate" hreflang="en" href="{{ website_url(route($staticRoute['route']), 'en') }}" />
        <changefreq>{{ $staticRoute['changefreq'] }}</changefreq>
        <priority>{{ $staticRoute['priority'] }}</priority>
    </url>
    @endforeach
    @foreach ($pages as $page)
    <url>
        <loc>{{ website_url(route('slug.show', $page->slug), $sitemapLocale) }}</loc>
        <xhtml:link rel="alternate" hreflang="vi-VN" href="{{ website_url(route('slug.show', $page->slug), 'vi') }}" />
        <xhtml:link rel="alternate" hreflang="en" href="{{ website_url(route('slug.show', $page->slug), 'en') }}" />
        <lastmod>{{ $page->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach ($services as $service)
    <url>
        <loc>{{ website_url(route('slug.show', $service->slug), $sitemapLocale) }}</loc>
        <xhtml:link rel="alternate" hreflang="vi-VN" href="{{ website_url(route('slug.show', $service->slug), 'vi') }}" />
        <xhtml:link rel="alternate" hreflang="en" href="{{ website_url(route('slug.show', $service->slug), 'en') }}" />
        <lastmod>{{ $service->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach ($courses as $course)
    <url>
        <loc>{{ website_url(route('slug.show', $course->slug), $sitemapLocale) }}</loc>
        <xhtml:link rel="alternate" hreflang="vi-VN" href="{{ website_url(route('slug.show', $course->slug), 'vi') }}" />
        <xhtml:link rel="alternate" hreflang="en" href="{{ website_url(route('slug.show', $course->slug), 'en') }}" />
        <lastmod>{{ $course->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
    @foreach ($posts as $post)
    <url>
        <loc>{{ website_url(route('slug.show', $post->slug), $sitemapLocale) }}</loc>
        <xhtml:link rel="alternate" hreflang="vi-VN" href="{{ website_url(route('slug.show', $post->slug), 'vi') }}" />
        <xhtml:link rel="alternate" hreflang="en" href="{{ website_url(route('slug.show', $post->slug), 'en') }}" />
        <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
    @foreach ($projects as $project)
    <url>
        <loc>{{ website_url(route('slug.show', $project->slug), $sitemapLocale) }}</loc>
        <xhtml:link rel="alternate" hreflang="vi-VN" href="{{ website_url(route('slug.show', $project->slug), 'vi') }}" />
        <xhtml:link rel="alternate" hreflang="en" href="{{ website_url(route('slug.show', $project->slug), 'en') }}" />
        <lastmod>{{ $project->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @endforeach
</urlset>
