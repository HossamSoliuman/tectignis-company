@props(['industry'])

@php
    $section = $industry->content['cta_band'] ?? [];
    $heading = $section['heading'] ?? "Let's Build Smarter Workplaces, Together";
    $subtitle = $section['subtitle'] ?? 'Partner with our experts to design a setup that fits your goals.';
    $primaryLabel = $section['button_primary_label'] ?? 'Talk to an Expert';
    $secondaryLabel = $section['button_secondary_label'] ?? 'Request a Consultation';
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
                    <a href="{{ route('contact') }}" class="svc-btn svc-btn--white">{{ $primaryLabel }} <i class="fas fa-arrow-right"></i></a>
                    <a href="{{ route('contact') }}" class="svc-btn ind-btn--outline-white">{{ $secondaryLabel }} <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>
@endif
