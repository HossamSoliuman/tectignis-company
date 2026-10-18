@extends('layouts.public')

@section('title', __('contact.title'))

@section('seo')
    <meta name="description" content="{{ __('contact.description') }}">
@endsection

@section('content')

    @php
        $contactPhone = $company->phone();
        $contactPhoneHref = preg_replace('/[^+\d]/', '', $contactPhone);
        $contactEmail = $company->email();
        $contactAddress = $company->address();
        $contactHours = $company->businessHours() ?? __('contact.default_hours');
        $contactWhatsappUrl = $company->whatsappUrl() ?? 'https://wa.me/'.preg_replace('/\D/', '', $contactPhone);
    @endphp

    <!--============ Contact Hero Start ============-->
    <section class="contact-hero">
        <span class="contact-hero__grid-bg" aria-hidden="true"></span>
        <span class="contact-hero__glow contact-hero__glow--one" aria-hidden="true"></span>
        <span class="contact-hero__glow contact-hero__glow--two" aria-hidden="true"></span>
        <div class="container">
            <div class="contact-hero__layout">
                <div class="contact-hero__content wow move-up">
                    <span class="contact-section-label contact-section-label--light">{{ __('contact.label') }}</span>
                    <h1 class="contact-hero__title">{{ __('contact.hero_title') }} <span class="text-color-primary">{{ __('contact.hero_title_highlight') }}</span></h1>
                    <p class="contact-hero__desc">{{ __('contact.hero_text') }}</p>
                    <div class="contact-hero__methods">
                        <a href="tel:{{ $contactPhoneHref }}" class="contact-method">
                            <span class="contact-method__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                            </span>
                            <span class="contact-method__body">
                                <span class="contact-method__label">{{ __('contact.call_us') }}</span>
                                <span class="contact-method__value" dir="ltr">{{ $contactPhone }}</span>
                            </span>
                        </a>
                        <a href="mailto:{{ $contactEmail }}" class="contact-method">
                            <span class="contact-method__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                            </span>
                            <span class="contact-method__body">
                                <span class="contact-method__label">{{ __('contact.email_us') }}</span>
                                <span class="contact-method__value">{{ $contactEmail }}</span>
                            </span>
                        </a>
                        <div class="contact-method">
                            <span class="contact-method__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            </span>
                            <span class="contact-method__body">
                                <span class="contact-method__label">{{ __('contact.response_time') }}</span>
                                <span class="contact-method__value">{{ __('contact.response_time_value') }}</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="contact-hero__media wow move-up" aria-hidden="true">
                    <div class="contact-hero__rings">
                        <span class="contact-hero__ring contact-hero__ring--lg"></span>
                        <span class="contact-hero__ring contact-hero__ring--md"></span>
                        <span class="contact-hero__core">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                        </span>
                        <span class="contact-hero__float contact-hero__float--phone">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                        </span>
                        <span class="contact-hero__float contact-hero__float--pin">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============ Contact Hero End ============-->

    <!--============ Why Choose Start ============-->
    <section class="contact-benefits section-space--ptb_80">
        <div class="container">
            <div class="section-title-wrap text-center section-space--mb_40">
                <span class="contact-section-label contact-section-label--center">{{ __('contact.benefits_label') }}</span>
                <h2 class="about-section-heading">{{ __('contact.benefits_title') }} <span class="text-color-primary">{{ __('contact.benefits_title_highlight') }}</span></h2>
            </div>
            @php
                $contactBenefits = [
                    ['icon' => '<path d="M13 10V3L4 14h7v7l9-11h-7z"/>', 'title' => __('contact.benefits.fast.title'), 'text' => __('contact.benefits.fast.text')],
                    ['icon' => '<path d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>', 'title' => __('contact.benefits.experts.title'), 'text' => __('contact.benefits.experts.text')],
                    ['icon' => '<path d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/>', 'title' => __('contact.benefits.tailored.title'), 'text' => __('contact.benefits.tailored.text')],
                    ['icon' => '<path d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>', 'title' => __('contact.benefits.communication.title'), 'text' => __('contact.benefits.communication.text')],
                    ['icon' => '<path d="M2.25 18 9 11.25l4.306 4.307a11.95 11.95 0 0 1 5.814-5.519l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/>', 'title' => __('contact.benefits.results.title'), 'text' => __('contact.benefits.results.text', ['count' => $company->statValue('projects', '150+')])],
                ];
            @endphp
            <div class="contact-benefits__grid">
                @foreach ($contactBenefits as $benefit)
                    <div class="contact-benefit-card wow move-up">
                        <span class="contact-benefit-card__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">{!! $benefit['icon'] !!}</svg>
                        </span>
                        <h3 class="contact-benefit-card__title">{{ $benefit['title'] }}</h3>
                        <p class="contact-benefit-card__text">{{ $benefit['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============ Why Choose End ============-->

    <!--============ Contact Section Start ============-->
    <section class="contact-main section-space--pb_80">
        <div class="container">
            <div class="contact-main__grid">
                <div class="contact-form-card wow move-up">
                    <span class="contact-section-label">{{ __('contact.form_label') }}</span>
                    <h2 class="about-section-heading">{{ __('contact.form_title') }} <span class="text-color-primary">{{ __('contact.form_title_highlight') }}</span></h2>
                    <p class="contact-form-card__desc">{{ __('contact.form_text') }}</p>

                    <x-public.enquiry-form form-id="contact-page" source="contact" variant="page" id="contact-lead-form" />
                </div>

                <div class="contact-info wow move-up">
                    <div class="contact-info-card">
                        <span class="contact-info-card__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                        </span>
                        <div class="contact-info-card__body">
                            <h3 class="contact-info-card__title">{{ __('contact.office') }}</h3>
                            <p class="contact-info-card__text">{{ $contactAddress }}</p>
                        </div>
                    </div>
                    <div class="contact-info-card">
                        <span class="contact-info-card__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                        </span>
                        <div class="contact-info-card__body">
                            <h3 class="contact-info-card__title">{{ __('contact.call') }}</h3>
                            <p class="contact-info-card__text"><a href="tel:{{ $contactPhoneHref }}"><span dir="ltr">{{ $contactPhone }}</span></a></p>
                        </div>
                    </div>
                    <div class="contact-info-card">
                        <span class="contact-info-card__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                        </span>
                        <div class="contact-info-card__body">
                            <h3 class="contact-info-card__title">{{ __('contact.email') }}</h3>
                            <p class="contact-info-card__text"><a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></p>
                        </div>
                    </div>
                    <div class="contact-info-card">
                        <span class="contact-info-card__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        </span>
                        <div class="contact-info-card__body">
                            <h3 class="contact-info-card__title">{{ __('contact.hours') }}</h3>
                            <p class="contact-info-card__text">{{ $contactHours }}</p>
                        </div>
                    </div>
                    <a href="{{ $contactWhatsappUrl }}" target="_blank" rel="noopener" class="contact-info-card contact-info-card--whatsapp">
                        <span class="contact-info-card__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163a11.867 11.867 0 0 1-1.587-5.945C.16 5.335 5.495 0 12.05 0a11.82 11.82 0 0 1 8.413 3.488 11.824 11.824 0 0 1 3.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 0 1-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 0 0 1.512 5.26l-.999 3.648 3.737-.981a9.875 9.875 0 0 0 .239.274zm5.392-.745c-2.831 0-5.13-2.299-5.13-5.13 0-.265.021-.526.062-.781h2.005c.038.255.06.516.06.781a3.005 3.005 0 0 0 3.003 3.003c.265 0 .526-.022.781-.06v2.005a5.16 5.16 0 0 1-.781.06z"/></svg>
                        </span>
                        <div class="contact-info-card__body">
                            <h3 class="contact-info-card__title">{{ __('contact.whatsapp') }}</h3>
                            <p class="contact-info-card__text">{{ __('contact.whatsapp_text') }}</p>
                        </div>
                        <span class="contact-info-card__arrow tx-flip-rtl" aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!--============ Contact Section End ============-->

    <!--============ Statistics Strip Start ============-->
    <section class="contact-statbar section-space--pb_80">
        <div class="container">
            <div class="contact-statbar__inner">
                <div class="contact-statbar__intro">
                    <span class="contact-section-label">{{ __('contact.stats_label') }}</span>
                    <h3 class="contact-statbar__title">{{ __('contact.stats_title') }}</h3>
                </div>
                @php
                    $contactStats = $company->stats()
                        ->take(4)
                        ->map(fn ($stat): array => ['num' => $stat->value, 'label' => $stat->label]);
                @endphp
                @foreach ($contactStats as $stat)
                    <div class="contact-stat">
                        <div class="contact-stat__num">{{ $stat['num'] }}</div>
                        <div class="contact-stat__label">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============ Statistics Strip End ============-->

    <!--============ FAQ Start ============-->
    <section class="contact-faq section-space--pb_80">
        <div class="container">
            <div class="section-title-wrap text-center section-space--mb_40">
                <span class="contact-section-label contact-section-label--center">{{ __('contact.faq_label') }}</span>
                <h2 class="about-section-heading">{{ __('contact.faq_title') }} <span class="text-color-primary">{{ __('contact.faq_title_highlight') }}</span></h2>
            </div>
            @php
                $contactFaqs = __('contact.faqs');
            @endphp
            <div class="contact-faq__grid">
                @foreach ($contactFaqs as $i => $faq)
                    <details class="contact-faq-item wow move-up" @if ($i === 0) open @endif>
                        <summary class="contact-faq-item__q">
                            <span class="contact-faq-item__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/></svg>
                            </span>
                            <span class="contact-faq-item__text">{{ $faq['q'] }}</span>
                            <span class="contact-faq-item__toggle" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                            </span>
                        </summary>
                        <div class="contact-faq-item__a">{{ $faq['a'] }}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
    <!--============ FAQ End ============-->

    <!--============ CTA Banner Start ============-->
    <section class="section-space--pb_80">
        <div class="container">
            <div class="contact-cta">
                <span class="contact-cta__icon" aria-hidden="true">
                    <svg class="tx-flip-rtl" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/></svg>
                </span>
                <div class="contact-cta__body">
                    <h3 class="contact-cta__title">{{ __('contact.cta_title') }}</h3>
                    <p class="contact-cta__text">{{ __('contact.cta_text') }}</p>
                </div>
                <div class="contact-cta__actions">
                    <button type="button" class="about-btn about-btn--primary js-consult-open">{{ __('contact.cta_button') }} <span class="tx-flip-rtl" aria-hidden="true">→</span></button>
                    <a href="tel:{{ $contactPhoneHref }}" class="contact-cta__call">{{ __('contact.cta_call') }}</a>
                </div>
            </div>
        </div>
    </section>
    <!--============ CTA Banner End ============-->

@endsection

@push('json-ld')
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($contactFaqs)->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['a']],
        ])->all(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
