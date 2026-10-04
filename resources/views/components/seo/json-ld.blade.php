@props([
    'type' => 'organization',
    'service' => null,
    'post' => null,
    'breadcrumbs' => [],
])

@php
    $company ??= app(\App\Support\CompanyProfile::class);
    $siteName = $company->name();
    $siteUrl = url('/');
    $logo = $company->logoUrl();
    preg_match('/\b(\d{6})\b/', $company->address(), $postalMatch);

    $org = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => $siteUrl.'#organization',
        'name' => $siteName,
        'legalName' => $company->legalName(),
        'url' => $siteUrl,
        'logo' => $logo,
        'description' => $company->tagline(),
        'telephone' => $company->phone(),
        'email' => $company->email(),
        'address' => array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $company->address(),
            'addressLocality' => 'Navi Mumbai',
            'addressRegion' => 'Maharashtra',
            'postalCode' => $postalMatch[1] ?? null,
            'addressCountry' => 'IN',
        ]),
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'contactType' => 'sales',
            'telephone' => $company->phone(),
            'email' => $company->email(),
            'areaServed' => ['IN', 'US', 'AE', 'GB', 'CA', 'EU'],
            'availableLanguage' => ['English', 'Hindi'],
        ],
        'sameAs' => $company->profileUrls(),
    ];

    $website = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => $siteUrl.'#website',
        'name' => $siteName,
        'url' => $siteUrl,
        'publisher' => ['@id' => $siteUrl.'#organization'],
        'inLanguage' => 'en',
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

<script type="application/ld+json">{!! json_encode($org, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_TAG) !!}</script>
<script type="application/ld+json">{!! json_encode($website, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_TAG) !!}</script>

@if ($serviceSchema)
<script type="application/ld+json">{!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endif

@if ($articleSchema)
<script type="application/ld+json">{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endif

@if ($breadcrumbSchema)
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endif
