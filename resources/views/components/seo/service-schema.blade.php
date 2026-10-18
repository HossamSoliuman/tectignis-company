@props([
    'name',
    'url',
    'description' => null,
    'image' => null,
])

{{--
    Service JSON-LD for a service or landing page, provided by the
    Organization the layout describes and offered in the priority markets.
    Pushed to the layout's json-ld stack.
--}}
@php
    $company ??= app(\App\Support\CompanyProfile::class);
    $siteUrl = url('/');

    $serviceSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        '@id' => $url.'#service',
        'name' => $name,
        'serviceType' => $name,
        'description' => filled($description) ? $description : null,
        'url' => $url,
        'image' => $image,
        'provider' => ['@type' => 'Organization', '@id' => $siteUrl.'#organization', 'name' => $company->name(), 'url' => $siteUrl],
        'areaServed' => $company->areaServed(),
    ]);
@endphp

@push('json-ld')
    <script type="application/ld+json">{!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush
