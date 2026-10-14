@props(['service'])

{{-- A priority service on the homepage: the problems it solves, what the client gains and its main capabilities. --}}
@php
    $content = $service->content ?? [];
    $problems = collect($content['problems']['items'] ?? [])->pluck('problem')->filter()->take(3);
    $benefits = collect($content['benefits']['items'] ?? [])->pluck('title')->filter()->take(3);
    $capabilities = collect($content['sub_services']['items'] ?? [])->pluck('title')->filter()->take(6);
@endphp

<article {{ $attributes->merge(['class' => 'tx-card tx-core-card']) }}>
    <div class="tx-core-card__head">
        <span class="tx-service-card__icon tx-core-card__icon" aria-hidden="true">
            @if ($service->icon)
                <img src="{{ asset('uploads/'.$service->icon) }}" alt="" width="28" height="28" loading="lazy">
            @else
                <i class="fas fa-code"></i>
            @endif
        </span>
        <h3 class="tx-core-card__title">
            <a href="{{ route('services.show', $service->slug) }}" class="tx-card__link">{{ $service->title }}</a>
        </h3>
    </div>

    <div class="tx-core-card__intro">
        @if ($service->short_description)
            <p class="tx-core-card__text">{{ $service->short_description }}</p>
        @endif
    </div>

    <div class="tx-core-card__body">
        @if ($problems->isNotEmpty() || $benefits->isNotEmpty())
            <div class="tx-core-card__cols">
                @if ($problems->isNotEmpty())
                    <div>
                        <h4 class="tx-core-card__label">Problems We Solve</h4>
                        <ul class="tx-core-card__list tx-core-card__list--problems">
                            @foreach ($problems as $problem)
                                <li><i class="fas fa-times-circle" aria-hidden="true"></i><span>{{ $problem }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if ($benefits->isNotEmpty())
                    <div>
                        <h4 class="tx-core-card__label">What You Gain</h4>
                        <ul class="tx-core-card__list">
                            @foreach ($benefits as $benefit)
                                <li><i class="fas fa-check-circle" aria-hidden="true"></i><span>{{ $benefit }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="tx-core-card__chips">
        @if ($capabilities->isNotEmpty())
            <ul class="tx-service-card__items" aria-label="{{ $service->title }} capabilities">
                @foreach ($capabilities as $capability)
                    <li>{{ $capability }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="tx-core-card__actions">
        <span class="tx-service-card__more" aria-hidden="true">Explore {{ $service->title }} <i class="fas fa-arrow-right"></i></span>
        <button type="button" class="tx-btn tx-btn--primary tx-core-card__cta js-consult-open">Get a Quote</button>
    </div>
</article>
