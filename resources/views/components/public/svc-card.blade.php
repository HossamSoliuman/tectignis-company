@props([
    'href' => null,
    'title',
    'text' => null,
    'iconImage' => null,
    'iconClass' => null,
])

<a @if ($href) href="{{ $href }}" @endif {{ $attributes->merge(['class' => 'svc-card wow move-up']) }}>
    <span class="svc-card__icon" aria-hidden="true">
        @if ($iconImage)
            <img src="{{ asset('uploads/'.$iconImage) }}" alt="" loading="lazy">
        @elseif ($iconClass)
            <i class="{{ $iconClass }}"></i>
        @endif
    </span>
    <h3 class="svc-card__title">{{ $title }}</h3>
    <span class="svc-card__accent" aria-hidden="true"></span>
    @if ($text)
        <p class="svc-card__text">{{ $text }}</p>
    @endif
    <span class="svc-card__arrow" aria-hidden="true"><i class="fas fa-arrow-right"></i></span>
</a>
