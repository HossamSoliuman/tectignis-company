@props([
    'href',
    'title',
    'text' => null,
    'icon' => null,
    'iconImage' => null,
    'items' => [],
    'linkLabel' => 'Explore',
])

{{-- A solution / service card linking to its detail page (spec §20 ServiceCard). --}}
<article {{ $attributes->merge(['class' => 'tx-card tx-service-card']) }}>
    <span class="tx-service-card__icon" aria-hidden="true">
        @if ($iconImage)
            <img src="{{ asset('uploads/'.$iconImage) }}" alt="" width="28" height="28" loading="lazy">
        @elseif ($icon)
            <i class="{{ $icon }}"></i>
        @endif
    </span>

    <h3 class="tx-service-card__title">
        <a href="{{ $href }}" class="tx-card__link">{{ $title }}</a>
    </h3>

    @if ($text)
        <p class="tx-service-card__text">{{ $text }}</p>
    @endif

    @if (count($items))
        <ul class="tx-service-card__items">
            @foreach ($items as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @endif

    <span class="tx-service-card__more" aria-hidden="true">{{ $linkLabel }} <i class="fas fa-arrow-right"></i></span>
</article>
