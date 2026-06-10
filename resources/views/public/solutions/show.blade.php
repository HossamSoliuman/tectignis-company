@extends('layouts.public')

@section('title', $solution->seo_title ?: $solution->title.' | Tectignis IT Solutions')

@section('seo')
    <meta name="description" content="{{ $solution->seo_description ?: $solution->short_description }}">
    @if ($solution->seo_keywords)
        <meta name="keywords" content="{{ $solution->seo_keywords }}">
    @endif
    <link rel="canonical" href="{{ route('solutions.show', $solution->slug) }}">
    <meta property="og:title" content="{{ $solution->seo_title ?: $solution->title.' | Tectignis IT Solutions' }}">
    <meta property="og:description" content="{{ $solution->seo_description ?: $solution->short_description }}">
    <meta property="og:url" content="{{ route('solutions.show', $solution->slug) }}">
    <meta property="og:type" content="website">
@endsection

@section('content')
    @if ($solution->body && empty($solution->content))
        {{-- Escape hatch: legacy solutions still using free-form body and no rich content yet. --}}
        @include('public._dynamic-body', ['body' => $solution->body])

        <!--========== Call to Action Area Start ============-->
        <x-public.cta />
        <!--========== Call to Action Area End ============-->
    @else
        {{-- Rich, admin-managed solution template (mockup-matching). Each component
             reads $solution->content[...] and renders nothing when off or empty. --}}
        <x-public.solution.hero :solution="$solution" />              {{-- Hero + benefits list + badges --}}
        <x-public.solution.stats :solution="$solution" />             {{-- KPI cards --}}
        <x-public.solution.modules :solution="$solution" />           {{-- Modules grid --}}
        <x-public.solution.benefits :solution="$solution" />          {{-- Benefits grid --}}
        <x-public.solution.process :solution="$solution" />           {{-- Implementation process --}}
        <x-public.solution.industries :solution="$solution" />        {{-- Industry solutions --}}
        <x-public.solution.why-choose :solution="$solution" />        {{-- Why choose + testimonial --}}
        <x-public.solution.cta-band :solution="$solution" />          {{-- Final CTA band --}}
    @endif
@endsection
