@extends('layouts.public')

@section('title', __('forms.thank_you.title').' | Tectignis IT Solutions')

@section('robots', 'noindex, nofollow')

@section('content')
    @php
        $firstName = \Illuminate\Support\Str::before(trim((string) ($enquiry['name'] ?? '')), ' ');
        $enquiryService = filled($enquiry['service'] ?? null) ? \App\Support\EnquiryServices::label($enquiry['service']) : null;
    @endphp

    <section class="thank-you">
        <div class="container">
            <div class="thank-you__card" role="status">
                <span class="thank-you__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12.75 11.25 15 15 9.75m6 2.25a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </span>
                <h1 class="thank-you__title">{{ $firstName !== '' ? __('forms.thank_you.heading_name', ['name' => $firstName]) : __('forms.thank_you.heading') }}</h1>
                <p class="thank-you__text">
                    {{ $enquiryService ? __('forms.thank_you.received_service', ['service' => $enquiryService]) : __('forms.thank_you.received') }}
                    {{ __('forms.thank_you.next') }}
                </p>
                <div class="thank-you__actions">
                    <a href="{{ route('case-studies.index') }}" class="about-btn about-btn--primary">{{ __('nav.view_our_work') }} <span class="tx-flip-rtl" aria-hidden="true">→</span></a>
                    <a href="{{ localized_route('home') }}" class="about-btn about-btn--ghost">{{ __('forms.thank_you.back_home') }}</a>
                </div>
            </div>
        </div>
    </section>

    {{-- The site's only conversion: a stored enquiry. Sent once per submission,
         even if this page is restored from history. --}}
    <script>
        (function () {
            var lead = @js($conversion);
            try {
                var key = 'tx_lead_' + lead.transaction_id;
                if (sessionStorage.getItem(key)) { return; }
                sessionStorage.setItem(key, '1');
            } catch (e) {}
            window.txTrack('generate_lead', lead);
        })();
    </script>
@endsection
