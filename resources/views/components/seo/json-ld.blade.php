@props([
    'type' => 'organization',
    'service' => null,
    'post' => null,
    'breadcrumbs' => [],
])

@php
    $siteName = 'Tectignis IT Solutions';
    $siteUrl = url('/');
    $logo = asset('assets/images/logo/logo-dark.webp');
    $phone = \App\Models\Setting::get('phone', '+91 9987705688');
    $email = \App\Models\Setting::get('email', 'info@tectignis.in');

    $org = [
        '@context' => 'https://schema.org',
        '@type' => ['Organization', 'LocalBusiness'],
        'name' => $siteName,
        'url' => $siteUrl,
        'logo' => $logo,
        'telephone' => $phone,
        'email' => $email,
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Aashiyana CHS Shop No 05, Sector 11, Plot No 29',
            'addressLocality' => 'Kamothe, Navi Mumbai',
            'addressRegion' => 'Maharashtra',
            'postalCode' => '410209',
            'addressCountry' => 'IN',
        ],
        'sameAs' => [
            'https://www.facebook.com/tectignis/',
            'https://www.linkedin.com/company/tectignis',
            'https://twitter.com/tectignis',
        ],
    ];

    $serviceSchema = null;
    if ($type === 'service' && $service) {
        $serviceSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $service->title,
            'description' => $service->seo_description ?? $service->short_description,
            'provider' => ['@type' => 'Organization', 'name' => $siteName, 'url' => $siteUrl],
            'url' => route('services.show', $service->slug),
        ];
    }

    $articleSchema = null;
    if ($type === 'article' && $post) {
        $articleSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->seo_description ?? $post->excerpt,
            'url' => route('blog.show', $post->slug),
            'datePublished' => $post->published_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'publisher' => [
                '@type' => 'Organization',
                'name' => $siteName,
                'logo' => ['@type' => 'ImageObject', 'url' => $logo],
            ],
        ];
    }

    $breadcrumbSchema = null;
    if (count($breadcrumbs) > 0) {
        $items = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')]];
        foreach ($breadcrumbs as $i => $crumb) {
            $item = ['@type' => 'ListItem', 'position' => $i + 2, 'name' => $crumb['name']];
            if (isset($crumb['url'])) {
                $item['item'] = $crumb['url'];
            }
            $items[] = $item;
        }
        $breadcrumbSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }
@endphp

<script type="application/ld+json">{!! json_encode($org, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>

@if ($serviceSchema)
<script type="application/ld+json">{!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endif

@if ($articleSchema)
<script type="application/ld+json">{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endif

@if ($breadcrumbSchema)
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endif
