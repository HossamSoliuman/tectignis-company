@extends('layouts.public')

@section('title', $service->seo_title ?: $service->title.' | Tectignis IT Solutions')

@section('seo')
    <meta name="description" content="{{ $service->seo_description ?: $service->short_description }}">
    @if ($service->seo_keywords)
        <meta name="keywords" content="{{ $service->seo_keywords }}">
    @endif
    <link rel="canonical" href="{{ route('services.show', $service->slug) }}">
    <meta property="og:title" content="{{ $service->seo_title ?: $service->title.' | Tectignis IT Solutions' }}">
    <meta property="og:description" content="{{ $service->seo_description ?: $service->short_description }}">
    <meta property="og:url" content="{{ route('services.show', $service->slug) }}">
    <meta property="og:type" content="website">
@endsection

@section('content')
    @if ($service->body && empty($service->content))
        {{-- Escape hatch: legacy services still using free-form body and no rich content yet. --}}
        @include('public._dynamic-body', ['body' => $service->body])

        <!--========== Call to Action Area Start ============-->
        <x-public.cta />
        <!--========== Call to Action Area End ============-->
    @else
        {{-- Rich, admin-managed service template (redesigned, mockup-matching).
             Each component reads $service->content[...] / the pivots and renders
             nothing when off or empty. --}}
        <x-public.service.hero :service="$service" />              {{-- Hero + stats --}}
        <x-public.service.sub-services :service="$service" />      {{-- Our {Service} Services --}}
        <x-public.service.process :service="$service" />           {{-- Process stepper --}}
        <x-public.service.tech-stack :service="$service" />        {{-- Technologies We Use --}}
        <x-public.service.why-choose :service="$service" />        {{-- Why Choose Tectignis --}}
        <x-public.service.case-studies :service="$service" />      {{-- Recent Success Stories --}}
        <x-public.service.faq :service="$service" />               {{-- FAQ + selling points --}}
        <x-public.service.lets-start :service="$service" />        {{-- Let's Start contact --}}
    @endif
@endsection
