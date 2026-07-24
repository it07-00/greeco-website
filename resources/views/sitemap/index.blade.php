{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($staticRoutes as $staticRoute)
    <url>
        <loc>{{ route($staticRoute['route']) }}</loc>
        <changefreq>{{ $staticRoute['changefreq'] }}</changefreq>
        <priority>{{ $staticRoute['priority'] }}</priority>
    </url>
    @endforeach
    @foreach ($pages as $page)
    <url>
        <loc>{{ route('slug.show', $page->slug) }}</loc>
        <lastmod>{{ $page->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach ($services as $service)
    <url>
        <loc>{{ route('slug.show', $service->slug) }}</loc>
        <lastmod>{{ $service->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach ($courses as $course)
    <url>
        <loc>{{ route('slug.show', $course->slug) }}</loc>
        <lastmod>{{ $course->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
    @foreach ($posts as $post)
    <url>
        <loc>{{ route('slug.show', $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
    @foreach ($projects as $project)
    <url>
        <loc>{{ route('slug.show', $project->slug) }}</loc>
        <lastmod>{{ $project->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>
