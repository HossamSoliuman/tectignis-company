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

@section('breadcrumb')
    <x-public.breadcrumb :title="$service->title"
        :items="['Services' => route('services.index'), $service->title => null]" />
@endsection

@section('content')
    @if ($service->body && empty($service->content))
        {{-- Escape hatch: legacy services still using free-form body and no rich content yet. --}}
        @include('public._dynamic-body', ['body' => $service->body])

        <!--========== Call to Action Area Start ============-->
        <x-public.cta />
        <!--========== Call to Action Area End ============-->
    @else
        {{-- Rich, admin-managed service template (Sections A → J). Each component
             reads $service->content[...] / the pivots and renders nothing when off or empty. --}}
        <x-public.service.hero :service="$service" />              {{-- A: Hero --}}
        <x-public.service.features-strip :service="$service" />    {{-- B: Feature/Trust strip --}}
        <x-public.service.sub-services :service="$service" />      {{-- C: Our {Service} Services --}}
        <x-public.service.process :service="$service" />           {{-- D: Process stepper --}}
        <x-public.service.tech-stack :service="$service" />        {{-- E: Technologies We Use --}}
        <x-public.service.industries :service="$service" />        {{-- F: Industries We Serve --}}
        <x-public.service.case-studies :service="$service" />      {{-- G: Recent Success Stories --}}
        <x-public.service.why-choose :service="$service" />        {{-- H: Why Choose Tectignis --}}
        <x-public.service.faq :service="$service" />               {{-- I: FAQ --}}
        <x-public.cta :service="$service" />                       {{-- J: CTA band --}}
    @endif
@endsection
