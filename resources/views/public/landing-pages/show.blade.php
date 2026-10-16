@extends('layouts.landing')

@php
    $pageTitle = $landingPage->seo_title ?: $landingPage->headline.' | '.$company->name();
    $pageDescription = $landingPage->seo_description ?: $landingPage->supporting_text;
    $bullets = $landingPage->bullets();
    $benefits = $landingPage->benefits();
    $steps = $landingPage->processSteps();
    $faqs = $landingPage->faqItems();
    $formHeading = $landingPage->contentValue('form.heading', __('landing.form_heading'));
    // The lead's subject stays in English for the sales team; its language is stored with it.
    $enquirySubject = $landingPage->getRawOriginal('title').' Enquiry';
    $consultationSubject = $landingPage->getRawOriginal('secondary_cta_label').' - '.$enquirySubject;
    $formText = $landingPage->contentValue('form.text');
    $stats = collect(['projects', 'clients', 'industries'])
        ->map(fn (string $key) => $company->stat($key))
        ->filter()
        ->values();
    $benefitIcons = ['fas fa-chart-line', 'fas fa-bolt', 'fas fa-expand-arrows-alt', 'fas fa-key', 'fas fa-smile', 'fas fa-shield-alt'];
    $hasFormErrors = old('form_id') === 'landing-enquiry' && $errors->any();
    // Keep the consultation intent picked through the secondary CTA after a validation error.
    $isConsultation = $hasFormErrors && old('source') === 'consultation';
@endphp

@section('title', $pageTitle)

@section('seo')
    @if (filled($pageDescription))
        <meta name="description" content="{{ $pageDescription }}">
    @endif
    <meta property="og:title" content="{{ $pageTitle }}">
    @if (filled($pageDescription))
        <meta property="og:description" content="{{ $pageDescription }}">
    @endif
    <meta property="og:url" content="{{ localized_route('landing-pages.show', $landingPage->slug) }}">
    <meta property="og:type" content="website">
@endsection

@section('canonical', localized_route('landing-pages.show', $landingPage->slug))

@section('header-cta')
    <a href="#enquiry" class="svc-btn svc-btn--primary lp-header__cta" data-lp-cta="primary">{{ $landingPage->primary_cta_label }}</a>
@endsection

@section('content')
    {{-- Hero + enquiry form --}}
    <section class="lp-hero">
        <div class="container">
            <div class="lp-hero__grid">
                <div class="lp-hero__copy">
                    <h1 class="lp-hero__title">{{ $landingPage->headline }}</h1>
                    @if (filled($landingPage->supporting_text))
                        <p class="lp-hero__text">{{ $landingPage->supporting_text }}</p>
                    @endif

                    @if (count($bullets))
                        <ul class="lp-hero__bullets">
                            @foreach ($bullets as $bullet)
                                <li><i class="fas fa-check-circle" aria-hidden="true"></i><span>{{ $bullet }}</span></li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="lp-hero__buttons">
                        <a href="#enquiry" class="svc-btn svc-btn--primary" data-lp-cta="primary">{{ $landingPage->primary_cta_label }}</a>
                        <a href="#enquiry" class="svc-btn svc-btn--ghost" data-lp-cta="secondary">{{ $landingPage->secondary_cta_label }}</a>
                    </div>

                    @if ($stats->isNotEmpty())
                        <dl class="lp-hero__stats">
                            @foreach ($stats as $stat)
                                <div class="lp-stat">
                                    <dt class="lp-stat__label">{{ $stat->label }}</dt>
                                    <dd class="lp-stat__value">{{ $stat->value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @endif
                </div>

                <div class="lp-hero__form" id="enquiry" tabindex="-1" @if ($hasFormErrors) data-lp-errors @endif>
                    <div class="lp-form-card">
                        <h2 class="lp-form-card__title" data-lp-form-title data-primary-title="{{ $formHeading }}" data-secondary-title="{{ $landingPage->secondary_cta_label }}" data-consultation-subject="{{ $consultationSubject }}">{{ $isConsultation ? $landingPage->secondary_cta_label : $formHeading }}</h2>
                        @if (filled($formText))
                            <p class="lp-form-card__text">{{ $formText }}</p>
                        @endif

                        <x-public.enquiry-form form-id="landing-enquiry" :source="$isConsultation ? 'consultation' : 'contact'" variant="page"
                            :service="$landingPage->enquiry_service" :landing-page="$landingPage->slug"
                            :subject="$isConsultation ? $consultationSubject : $enquirySubject" :submit-label="$landingPage->primary_cta_label" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Benefits --}}
    @if (count($benefits))
        <section class="svc-section lp-section">
            <div class="container">
                <div class="svc-section-head text-center">
                    <h2 class="svc-section-title">{{ $landingPage->service?->content['benefits']['heading'] ?? __('landing.benefits_heading') }}</h2>
                </div>
                <div class="lp-grid lp-grid--3">
                    @foreach ($benefits as $index => $benefit)
                        <div class="svc-benefit">
                            <span class="svc-benefit__icon" aria-hidden="true">
                                @if (filled($benefit['icon'] ?? null))
                                    <img src="{{ asset('uploads/'.$benefit['icon']) }}" alt="" loading="lazy">
                                @else
                                    <i class="{{ $benefitIcons[$index % count($benefitIcons)] }}"></i>
                                @endif
                            </span>
                            <div>
                                <h3 class="svc-benefit__title">{{ $benefit['title'] }}</h3>
                                @if (filled($benefit['description'] ?? null))
                                    <p class="svc-benefit__text">{{ $benefit['description'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Project examples: cards stay on the page, so visitors are not sent away from the form. --}}
    @if ($caseStudies->isNotEmpty())
        <section class="svc-section lp-section lp-section--soft">
            <div class="container">
                <div class="svc-section-head text-center">
                    <h2 class="svc-section-title">{{ __('landing.projects_heading') }}</h2>
                </div>
                <div class="lp-grid lp-grid--3">
                    @foreach ($caseStudies as $caseStudy)
                        <article class="lp-project">
                            @if ($caseStudy->image)
                                <img class="lp-project__image" src="{{ asset('uploads/'.$caseStudy->image) }}" alt="{{ $caseStudy->title }}" loading="lazy">
                            @endif
                            <div class="lp-project__body">
                                @if ($caseStudy->category)
                                    <span class="lp-project__tag">{{ $caseStudy->category->name }}</span>
                                @endif
                                <h3 class="lp-project__title">{{ $caseStudy->title }}</h3>
                                @if ($caseStudy->short_description)
                                    <p class="lp-project__text">{{ $caseStudy->short_description }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Process --}}
    @if (count($steps))
        <section class="svc-section lp-section">
            <div class="container">
                <div class="svc-section-head text-center">
                    <h2 class="svc-section-title">{{ __('landing.process_heading') }}</h2>
                </div>
                <ol class="lp-steps">
                    @foreach ($steps as $index => $step)
                        <li class="lp-step">
                            <span class="lp-step__num" aria-hidden="true">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                <h3 class="lp-step__title">{{ $step['title'] }}</h3>
                                @if (filled($step['description'] ?? null))
                                    <p class="lp-step__text">{{ $step['description'] }}</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    {{-- FAQs --}}
    @if (count($faqs))
        <section class="svc-section lp-section lp-section--soft">
            <div class="container lp-container--narrow">
                <div class="svc-section-head text-center">
                    <h2 class="svc-section-title">{{ $landingPage->contentValue('faq.heading', __('landing.faq_heading')) }}</h2>
                </div>
                <div class="lp-faq">
                    @foreach ($faqs as $faq)
                        <details class="lp-faq__item" @if ($loop->first) open @endif>
                            <summary class="lp-faq__question">{{ $faq['question'] }}</summary>
                            <div class="lp-faq__answer">{{ $faq['answer'] ?? '' }}</div>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Closing CTA --}}
    <section class="lp-cta">
        <div class="container lp-cta__inner">
            <h2 class="lp-cta__title">{{ $landingPage->headline }}</h2>
            <div class="lp-cta__buttons">
                <a href="#enquiry" class="svc-btn svc-btn--white" data-lp-cta="primary">{{ $landingPage->primary_cta_label }}</a>
                <a href="{{ $company->phoneHref() }}" class="svc-btn svc-btn--outline-light" data-track="phone_click">
                    <i class="fas fa-phone-alt" aria-hidden="true"></i> <span dir="ltr">{{ $company->phone() }}</span>
                </a>
            </div>
        </div>
    </section>

    {{-- Mobile sticky action bar, hidden while the form is on screen. --}}
    <div class="lp-sticky" data-lp-sticky>
        <a href="{{ $company->phoneHref() }}" class="lp-sticky__call" data-track="phone_click">
            <i class="fas fa-phone-alt" aria-hidden="true"></i><span class="sr-only">{{ __('common.call', ['phone' => $company->phone()]) }}</span>
        </a>
        <a href="#enquiry" class="svc-btn svc-btn--primary lp-sticky__cta" data-lp-cta="primary">{{ $landingPage->primary_cta_label }}</a>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        var formWrap = document.getElementById('enquiry');
        if (!formWrap) { return; }

        var form = formWrap.querySelector('form');
        var title = formWrap.querySelector('[data-lp-form-title]');
        var source = form && form.querySelector('input[name="source"]');
        var subject = form && form.querySelector('input[name="subject"]');
        var baseSubject = {{ Illuminate\Support\Js::from($enquirySubject) }};

        // The secondary CTA ("Request a Consultation") files the lead as a
        // consultation request; the primary CTA keeps it a project enquiry.
        function setIntent(intent) {
            var consultation = intent === 'secondary';
            if (source) { source.value = consultation ? 'consultation' : 'contact'; }
            if (subject) { subject.value = consultation ? title.getAttribute('data-consultation-subject') : baseSubject; }
            if (title) { title.textContent = title.getAttribute(consultation ? 'data-secondary-title' : 'data-primary-title'); }
        }

        document.querySelectorAll('[data-lp-cta]').forEach(function (cta) {
            cta.addEventListener('click', function (e) {
                e.preventDefault();
                setIntent(cta.getAttribute('data-lp-cta'));
                formWrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
                var firstField = form && form.querySelector('input:not([type="hidden"]), select, textarea');
                if (firstField) { firstField.focus({ preventScroll: true }); }
            });
        });

        // Bring the form back into view when it returns with validation errors.
        if (formWrap.hasAttribute('data-lp-errors')) {
            formWrap.scrollIntoView({ block: 'start' });
        }

        var sticky = document.querySelector('[data-lp-sticky]');
        if (sticky && 'IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) {
                sticky.classList.toggle('is-hidden', entries[0].isIntersecting);
            }, { threshold: 0.15 }).observe(formWrap);
        }
    })();
</script>
@endpush
