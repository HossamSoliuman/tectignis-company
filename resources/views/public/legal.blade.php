@extends('layouts.public')

@section('title', $title.' | Tectignis IT Solutions')

@section('seo')
    <meta name="description" content="{{ __('common.legal_description', ['title' => $title]) }}">
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
    .legal-content h1,
    .legal-content [data-legal-title] {
        all: revert;
        font-family: 'Poppins', 'Inter', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: #111827;
        margin: 0 0 0.5rem;
        line-height: 1.25;
    }
    .legal-content [data-legal-title] {
        padding-bottom: 0;
        border-bottom: 0;
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
        margin: 0 0 1rem;
        margin-inline-start: 1.5rem;
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
        text-align: start;
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
    @php
        // The title band is the page's h1, so the document's own title is
        // its first h2, styled as before.
        $content = $content ? preg_replace(['/<h1\b/i', '/<\/h1>/i'], ['<h2 data-legal-title', '</h2>'], $content) : null;
    @endphp

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
                            <h2 data-legal-title>{{ $title }}</h2>
                            <p>{!! __('common.legal_updating', ['link' => '<a href="'.e(localized_route('contact')).'">'.e(__('common.contact_us')).'</a>']) !!}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
