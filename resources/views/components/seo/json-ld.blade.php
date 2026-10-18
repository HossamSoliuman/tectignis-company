@props([
    'type' => 'organization',
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
            'areaServed' => $company->areaServed(),
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
        'inLanguage' => \App\Support\Locales::enabled(),
    ];

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
        $items = [['@type' => 'ListItem', 'position' => 1, 'name' => __('common.home'), 'item' => localized_route('home')]];
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

<script type="application/ld+json">{!! json_encode($org, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_HEX_TAG) !!}</script>
<script type="application/ld+json">{!! json_encode($website, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_HEX_TAG) !!}</script>

@if ($articleSchema)
<script type="application/ld+json">{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endif

@if ($breadcrumbSchema)
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endif
