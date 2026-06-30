@extends('layouts.public')

@section('title', 'Page Not Found (404) | Tectignis IT Solutions')

@section('seo')
    <meta name="robots" content="noindex, follow">
@endsection

@section('content')
    <section class="error-page section-space--ptb_100">
        <div class="container">
            <div class="error-page__inner">
                <span class="error-page__code">404</span>
                <h1 class="error-page__title">This page took an unexpected detour</h1>
                <p class="error-page__text">
                    The page you're looking for may have been moved, renamed, or no longer exists.
                    Let's get you back on track.
                </p>
                <div class="error-page__actions">
                    <a href="{{ route('home') }}" class="about-btn about-btn--primary">Back to Home <span aria-hidden="true">→</span></a>
                    <a href="{{ route('contact') }}" class="about-btn about-btn--ghost">Contact Us</a>
                </div>

                <div class="error-page__links">
                    <span class="error-page__links-label">Popular pages</span>
                    <ul>
                        <li><a href="{{ route('capabilities.index') }}">Capabilities</a></li>
                        <li><a href="{{ route('solutions.index') }}">Solutions</a></li>
                        <li><a href="{{ route('industries.index') }}">Industries</a></li>
                        <li><a href="{{ route('case-studies.index') }}">Case Studies</a></li>
                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .error-page__inner {
        max-width: 680px;
        margin: 0 auto;
        text-align: center;
    }
    .error-page__code {
        display: block;
        font-family: 'Poppins', 'Inter', sans-serif;
        font-size: clamp(80px, 18vw, 160px);
        font-weight: 800;
        line-height: 1;
        background: linear-gradient(135deg, #19587F 0%, #0F766E 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 8px;
    }
    .error-page__title {
        font-family: 'Poppins', 'Inter', sans-serif;
        font-size: clamp(24px, 4vw, 36px);
        font-weight: 700;
        color: #111426;
        margin: 0 0 16px;
    }
    .error-page__text {
        font-size: 16px;
        line-height: 1.7;
        color: #5a6072;
        margin: 0 auto 28px;
        max-width: 520px;
    }
    .error-page__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        justify-content: center;
        margin-bottom: 40px;
    }
    .error-page__links-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #9ca3af;
        margin-bottom: 14px;
    }
    .error-page__links ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 10px 22px;
        justify-content: center;
    }
    .error-page__links a {
        color: #19587F;
        font-weight: 500;
        text-decoration: none;
    }
    .error-page__links a:hover { text-decoration: underline; }
</style>
@endpush
