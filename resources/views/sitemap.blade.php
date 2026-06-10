<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($staticPages as $page)
    <url>
        <loc>{{ $page['url'] }}</loc>
        <changefreq>{{ $page['changefreq'] }}</changefreq>
        <priority>{{ $page['priority'] }}</priority>
    </url>
    @endforeach
    @foreach ($capabilities as $capability)
    <url>
        <loc>{{ route('capabilities.show', $capability->slug) }}</loc>
        <lastmod>{{ $capability->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach ($services as $service)
    <url>
        <loc>{{ route('services.show', $service->slug) }}</loc>
        <lastmod>{{ $service->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach ($solutions as $solution)
    <url>
        <loc>{{ route('solutions.show', $solution->slug) }}</loc>
        <lastmod>{{ $solution->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
    @foreach ($industries as $industry)
    <url>
        <loc>{{ route('industries.show', $industry->slug) }}</loc>
        <lastmod>{{ $industry->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
    @foreach ($pages as $page)
    <url>
        <loc>{{ route('pages.show', $page->slug) }}</loc>
        <lastmod>{{ $page->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
    @foreach ($posts as $post)
    <url>
        <loc>{{ route('blog.show', $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
    @foreach ($insights as $insight)
    <url>
        <loc>{{ route('insights.show', $insight->slug) }}</loc>
        <lastmod>{{ $insight->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
    @foreach (['privacy-policy', 'terms-and-conditions', 'refund-policy'] as $legal)
    <url>
        <loc>{{ route('legal.show', $legal) }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    @endforeach
</urlset>
