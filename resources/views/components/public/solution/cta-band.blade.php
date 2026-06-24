@props(['solution'])

@php
    $section = $solution->content['cta_band'] ?? [];
    $heading = $section['heading'] ?? 'Ready to Transform Your Business?';
    $subtitle = $section['subtitle'] ?? "Let's build an intelligent, integrated, and future-ready business together.";
    $primaryLabel = $section['button_primary_label'] ?? 'Request Free Consultation';
    $secondaryLabel = $section['button_secondary_label'] ?? 'Talk To Our Expert';
@endphp

@if ($section['enabled'] ?? true)
    <section class="ind-ctaband">
        <div class="container">
            <div class="ind-ctaband__inner">
                <span class="ind-ctaband__glow" aria-hidden="true"></span>
                <div class="ind-ctaband__text">
                    <span class="ind-ctaband__icon"><i class="fas fa-rocket"></i></span>
                    <div>
                        <h2 class="ind-ctaband__title">{{ $heading }}</h2>
                        @if (filled($subtitle))
                            <p class="ind-ctaband__subtitle">{{ $subtitle }}</p>
                        @endif
                    </div>
                </div>
                <div class="ind-ctaband__buttons">
                    <button type="button" class="svc-btn svc-btn--white js-consult-open">{{ $primaryLabel }} <i class="fas fa-arrow-right"></i></button>
                    <a href="{{ route('contact') }}" class="svc-btn ind-btn--outline-white">{{ $secondaryLabel }} <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>
@endif
