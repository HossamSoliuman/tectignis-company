@props([
    'overline' => null,
    'title',
    'highlight' => null,
    'description' => null,
    'primaryLabel' => 'Book a Technical Consultation',
    'primaryUrl' => null,
    'secondaryLabel' => null,
    'secondaryUrl' => null,
    'image' => null,
    'imageAlt' => '',
    'imageWidth' => 640,
    'imageHeight' => 520,
    'trust' => [],
])

{{--
    Page hero (spec §4.2): one H1, short supporting copy, up to two CTAs, an
    optional visual and verified trust indicators. When no primaryUrl is given
    the primary CTA opens the consultation modal.
--}}
@php
    $hasMedia = isset($media) && $media->isNotEmpty();
@endphp

<section {{ $attributes->merge(['class' => 'tx-hero']) }}>
    <span class="tx-hero__grid" aria-hidden="true"></span>
    <div class="container">
        <div class="tx-hero__inner @if (! $image && ! $hasMedia) tx-hero__inner--solo @endif">
            <div class="tx-hero__content">
                @if ($overline)
                    <span class="tx-hero__overline">{{ $overline }}</span>
                @endif

                <h1 class="tx-hero__title">
                    {{ $title }}
                    @if ($highlight)
                        <span class="tx-hero__highlight">{{ $highlight }}</span>
                    @endif
                </h1>

                @if ($description)
                    <p class="tx-hero__desc">{{ $description }}</p>
                @endif

                <div class="tx-hero__actions">
                    @if ($primaryUrl)
                        <a href="{{ $primaryUrl }}" class="tx-btn tx-btn--primary tx-btn--lg">{{ $primaryLabel }} <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    @else
                        <button type="button" class="tx-btn tx-btn--primary tx-btn--lg js-consult-open">{{ $primaryLabel }} <i class="fas fa-arrow-right" aria-hidden="true"></i></button>
                    @endif

                    @if ($secondaryLabel && $secondaryUrl)
                        <a href="{{ $secondaryUrl }}" class="tx-btn tx-btn--outline tx-btn--lg">{{ $secondaryLabel }}</a>
                    @endif
                </div>

                @if (count($trust))
                    <ul class="tx-hero__trust">
                        @foreach ($trust as $item)
                            <li class="tx-hero__trust-item">
                                <span class="tx-hero__trust-value">{{ $item['value'] }}</span>
                                <span class="tx-hero__trust-label">{{ $item['label'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            @if ($hasMedia)
                <div class="tx-hero__media">{{ $media }}</div>
            @elseif ($image)
                <div class="tx-hero__media">
                    <img src="{{ $image }}" alt="{{ $imageAlt }}" width="{{ $imageWidth }}" height="{{ $imageHeight }}" class="tx-hero__img" fetchpriority="high" decoding="async">
                </div>
            @endif
        </div>
    </div>
</section>
