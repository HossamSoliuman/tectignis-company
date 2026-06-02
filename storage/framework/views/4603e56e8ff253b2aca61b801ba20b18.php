<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'organization',
    'service' => null,
    'post' => null,
    'breadcrumbs' => [],
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'type' => 'organization',
    'service' => null,
    'post' => null,
    'breadcrumbs' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
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
?>

<script type="application/ld+json"><?php echo json_encode($org, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?></script>

<?php if($serviceSchema): ?>
<script type="application/ld+json"><?php echo json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?></script>
<?php endif; ?>

<?php if($articleSchema): ?>
<script type="application/ld+json"><?php echo json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?></script>
<?php endif; ?>

<?php if($breadcrumbSchema): ?>
<script type="application/ld+json"><?php echo json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?></script>
<?php endif; ?>
<?php /**PATH D:\projects\tech-corporate\resources\views/components/seo/json-ld.blade.php ENDPATH**/ ?>