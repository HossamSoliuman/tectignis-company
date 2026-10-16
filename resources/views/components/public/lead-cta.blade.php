@props([
    'overline' => __('services.lead_cta_overline'),
    'title' => __('services.lead_cta_title'),
    'highlight' => __('services.lead_cta_highlight'),
    'text' => null,
    'primaryLabel' => __('services.lead_cta_primary'),
    'secondaryLabel' => __('services.lead_cta_secondary'),
    'secondaryUrl' => null,
])

{{-- The conversion band (spec §4.3 Lead CTA, §11.1 primary/secondary CTAs). --}}
<section {{ $attributes->merge(['class' => 'tx-lead-cta']) }} aria-labelledby="lead-cta-title">
    <div class="container">
        <div class="tx-lead-cta__inner">
            <div class="tx-lead-cta__content">
                @if ($overline)
                    <span class="tx-lead-cta__overline">{{ $overline }}</span>
                @endif
                <h2 class="tx-lead-cta__title" id="lead-cta-title">
                    {{ $title }}
                    @if ($highlight)
                        <span class="tx-lead-cta__highlight">{{ $highlight }}</span>
                    @endif
                </h2>
                @if ($text)
                    <p class="tx-lead-cta__text">{{ $text }}</p>
                @endif
                <ul class="tx-lead-cta__points">
                    @foreach (__('services.lead_cta_points') as $point)
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> {{ $point }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="tx-lead-cta__actions">
                <button type="button" class="tx-btn tx-btn--light tx-btn--lg js-consult-open">{{ $primaryLabel }} <i class="fas fa-arrow-right" aria-hidden="true"></i></button>
                @if ($secondaryUrl)
                    <a href="{{ $secondaryUrl }}" class="tx-btn tx-btn--ghost-light tx-btn--lg">{{ $secondaryLabel }}</a>
                @else
                    <button type="button" class="tx-btn tx-btn--ghost-light tx-btn--lg js-consult-open">{{ $secondaryLabel }}</button>
                @endif
                {{ $slot }}
            </div>
        </div>
    </div>
</section>
