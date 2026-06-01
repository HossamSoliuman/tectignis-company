@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'canonical' => null,
    'ogImage' => null,
    'ogType' => 'website',
])

@php
    $siteTitle = 'Tectignis IT Solutions';
    $defaultDesc = \App\Models\Setting::get('meta_description', 'Tectignis IT Solutions — Software, AI, Cloud & Security services in Navi Mumbai, India.');
    $defaultImage = \App\Models\Setting::get('og_image', asset('assets/images/logo/logo-dark.webp'));

    $metaTitle = $title ? $title.' | '.$siteTitle : $siteTitle.' | Software, AI, Cloud & Security';
    $metaDesc = $description ?? $defaultDesc;
    $metaImage = $ogImage ?? $defaultImage;
    $metaCanonical = $canonical ?? url()->current();
@endphp

<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDesc }}">
@if ($keywords)
    <meta name="keywords" content="{{ $keywords }}">
@endif
<link rel="canonical" href="{{ $metaCanonical }}">

{{-- Open Graph --}}
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDesc }}">
<meta property="og:image" content="{{ $metaImage }}">
<meta property="og:url" content="{{ $metaCanonical }}">
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:site_name" content="{{ $siteTitle }}">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDesc }}">
<meta name="twitter:image" content="{{ $metaImage }}">
