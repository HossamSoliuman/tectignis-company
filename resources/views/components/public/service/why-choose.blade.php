@props(['service'])

@php
    $section = $service->content['why_choose'] ?? [];
    $heading = $section['heading'] ?? 'Why Choose Tectignis';
    $subtitle = $section['subtitle'] ?? 'The Tectignis Difference';
    $fallbackIcons = ['fas fa-medal', 'fas fa-code', 'fas fa-shield-alt', 'fas fa-bolt', 'fas fa-handshake', 'fas fa-headset'];

    // Preferred shape: rich cards [{icon, title, text}]. Falls back to the
    // simple `points` string list, rendering each point as a card heading.
    $cards = array_values(array_filter($section['cards'] ?? [], fn ($c) => filled($c['title'] ?? null)));
    if (! count($cards)) {
        $points = array_values(array_filter($section['points'] ?? [], fn ($p) => filled($p)));
        $cards = array_map(fn ($p) => ['icon' => null, 'title' => $p, 'text' => null], $points);
    }
@endphp

@if (($section['enabled'] ?? true) && count($cards))
    <section class="svc-section svc-why">
        <div class="container">
            <div class="svc-section-head text-center">
                @if (filled($subtitle))
                    <span class="svc-eyebrow">{{ $subtitle }}</span>
                @endif
                <h2 class="svc-section-title">{{ $heading }}</h2>
            </div>

            <div class="row svc-why__grid">
                @foreach ($cards as $index => $card)
                    <div class="col-lg-2 col-md-4 col-6 wow move-up">
                        <div class="svc-why-card">
                            <span class="svc-why-card__icon">
                                @if (filled($card['icon'] ?? null))
                                    <img src="{{ asset('uploads/'.$card['icon']) }}" alt="{{ $card['title'] }}" loading="lazy">
                                @else
                                    <i class="{{ $fallbackIcons[$index % count($fallbackIcons)] }}"></i>
                                @endif
                            </span>
                            <div class="svc-why-card__body">
                                <h3 class="svc-why-card__title">{{ $card['title'] }}</h3>
                                @if (filled($card['text'] ?? null))
                                    <p class="svc-why-card__text">{{ $card['text'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
