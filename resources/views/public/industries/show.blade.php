@extends('layouts.public')

@section('title', $industry->seo_title ?: $industry->name.' Industry Solutions | Tectignis IT Solutions')

@section('seo')
    <meta name="description" content="{{ $industry->seo_description ?: $industry->short_description }}">
    @if ($industry->seo_keywords)
        <meta name="keywords" content="{{ $industry->seo_keywords }}">
    @endif
    <link rel="canonical" href="{{ route('industries.show', $industry->slug) }}">
    <meta property="og:title" content="{{ $industry->seo_title ?: $industry->name.' Industry Solutions | Tectignis IT Solutions' }}">
    <meta property="og:description" content="{{ $industry->seo_description ?: $industry->short_description }}">
    <meta property="og:url" content="{{ route('industries.show', $industry->slug) }}">
    <meta property="og:type" content="website">
@endsection

@section('content')
    @if ($industry->body && empty($industry->content))
        {{-- Escape hatch: legacy industries still using free-form body and no rich content yet. --}}
        @include('public._dynamic-body', ['body' => $industry->body])

        <!--========== Call to Action Area Start ============-->
        <x-public.cta />
        <!--========== Call to Action Area End ============-->
    @else
        {{-- Rich, admin-managed industry template (mockup-matching). Each component
             reads $industry->content[...] and renders nothing when off or empty. --}}
        <x-public.industry.hero :industry="$industry" />                        {{-- Hero + feature grid + badges --}}
        <x-public.industry.trust :industry="$industry" />                      {{-- Trust logos --}}
        <x-public.industry.challenges-solutions :industry="$industry" />       {{-- Split: challenges + solution cards --}}
        <x-public.industry.stats :industry="$industry" />                      {{-- Performance stats --}}
        <x-public.industry.case-studies :industry="$industry" />               {{-- Success stories --}}
        <x-public.industry.solutions-grid :industry="$industry" />             {{-- Solutions grid --}}
        <x-public.industry.faq :industry="$industry" />                        {{-- FAQ + mid CTA --}}
        <x-public.industry.cta-band :industry="$industry" />                   {{-- Bottom CTA band --}}
    @endif
@endsection
