@extends('layouts.public')

@section('title', 'Thank You | Tectignis IT Solutions')

@section('seo')
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('content')
    @php
        $firstName = \Illuminate\Support\Str::before(trim((string) ($enquiry['name'] ?? '')), ' ');
    @endphp

    <section class="thank-you">
        <div class="container">
            <div class="thank-you__card" role="status">
                <span class="thank-you__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12.75 11.25 15 15 9.75m6 2.25a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </span>
                <h1 class="thank-you__title">Thank you{{ $firstName !== '' ? ', '.$firstName : '' }}!</h1>
                <p class="thank-you__text">
                    We have received your enquiry{{ filled($enquiry['service'] ?? null) ? ' about '.$enquiry['service'] : '' }}.
                    Our team will review the details you shared and get in touch with you.
                </p>
                <div class="thank-you__actions">
                    <a href="{{ route('case-studies.index') }}" class="about-btn about-btn--primary">View Our Work <span aria-hidden="true">→</span></a>
                    <a href="{{ route('home') }}" class="about-btn about-btn--ghost">Back to Home</a>
                </div>
            </div>
        </div>
    </section>
@endsection
