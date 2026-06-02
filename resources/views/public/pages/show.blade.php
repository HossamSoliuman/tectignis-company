@extends('layouts.public')

@section('title', $page->seo_title ?: $page->title.' | Tectignis IT Solutions')

@section('seo')
    @if ($page->seo_description)
        <meta name="description" content="{{ $page->seo_description }}">
    @endif
    @if ($page->seo_keywords)
        <meta name="keywords" content="{{ $page->seo_keywords }}">
    @endif
    <link rel="canonical" href="{{ route('pages.show', $page->slug) }}">
    <meta property="og:title" content="{{ $page->seo_title ?: $page->title.' | Tectignis IT Solutions' }}">
    @if ($page->seo_description)
        <meta property="og:description" content="{{ $page->seo_description }}">
    @endif
    <meta property="og:url" content="{{ route('pages.show', $page->slug) }}">
    <meta property="og:type" content="website">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb :title="$page->title" :items="[$page->title => null]" />
@endsection

@section('content')
    @include('public._dynamic-body', ['body' => $page->body])
@endsection
