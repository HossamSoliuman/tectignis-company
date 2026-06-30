@extends('layouts.public')

@section('title', $title.' | Tectignis IT Solutions')

@section('seo')
    <meta name="description" content="{{ $title }} of Tectignis IT Solutions Pvt Ltd.">
    <link rel="canonical" href="{{ route('legal.show', $slug) }}">
@endsection

@push('styles')
<style>
    .legal-content {
        all: revert;
        display: block;
        font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        font-size: 15px;
        line-height: 1.8;
        color: #374151;
    }
    .legal-content * {
        box-sizing: border-box;
    }
    .legal-content h1 {
        all: revert;
        font-family: 'Poppins', 'Inter', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: #111827;
        margin: 0 0 0.5rem;
        line-height: 1.25;
    }
    .legal-content h2 {
        all: revert;
        font-family: 'Poppins', 'Inter', sans-serif;
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin: 2rem 0 0.75rem;
        padding-bottom: 0.4rem;
        border-bottom: 1px solid #e5e7eb;
    }
    .legal-content h3 {
        all: revert;
        font-size: 1.05rem;
        font-weight: 600;
        color: #1f2937;
        margin: 1.5rem 0 0.5rem;
    }
    .legal-content p {
        all: revert;
        margin: 0 0 1rem;
        color: #374151;
        font-size: 15px;
        line-height: 1.8;
    }
    .legal-content ul,
    .legal-content ol {
        all: revert;
        margin: 0 0 1rem 1.5rem;
        padding: 0;
        color: #374151;
        font-size: 15px;
        line-height: 1.8;
    }
    .legal-content li {
        all: revert;
        margin-bottom: 0.35rem;
    }
    .legal-content a {
        all: revert;
        color: #19587F;
        text-decoration: underline;
    }
    .legal-content a:hover {
        color: #13405d;
    }
    .legal-content strong {
        all: revert;
        font-weight: 600;
        color: #111827;
    }
    .legal-content table {
        all: revert;
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
        font-size: 14px;
    }
    .legal-content th,
    .legal-content td {
        all: revert;
        padding: 0.6rem 0.75rem;
        border: 1px solid #e5e7eb;
        text-align: left;
        vertical-align: top;
    }
    .legal-content th {
        background: #f9fafb;
        font-weight: 600;
        color: #111827;
    }
    .legal-last-updated {
        font-size: 13px;
        color: #9ca3af;
        margin-top: 0.25rem;
    }
</style>
@endpush

@section('breadcrumb')
    <x-public.breadcrumb :title="$title" :items="[$title => null]" />
@endsection

@section('content')
    <section style="padding: 60px 0 80px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-xl-8">
                    @if ($content)
                        <div class="legal-content">
                            {!! $content !!}
                        </div>
                    @else
                        <div class="legal-content">
                            <h1>{{ $title }}</h1>
                            <p>This page is being updated. Please check back soon or
                                <a href="{{ route('contact') }}">contact us</a>
                                for more information.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
