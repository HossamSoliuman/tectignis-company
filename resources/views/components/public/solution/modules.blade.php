@props(['solution'])

@php
    $section = $solution->content['modules'] ?? [];
    $cards = array_values(array_filter($section['cards'] ?? [], fn ($c) => filled($c['title'] ?? null)));
    $heading = $section['heading'] ?? 'Our Modules';
    $subtitle = $section['subtitle'] ?? null;
@endphp

@if (($section['enabled'] ?? true) && count($cards))
    <section class="svc-section">
        <div class="container">
            <div class="svc-section-head text-center">
                @if (filled($subtitle))
                    <span class="svc-eyebrow">{{ $subtitle }}</span>
                @endif
                <h2 class="svc-section-title">{{ $heading }}</h2>
            </div>

            <div class="row ind-solutions__grid">
                @foreach ($cards as $card)
                    <div class="col-lg-4 col-md-6 col-12 wow move-up">
                        <div class="ind-solution-card h-100 d-flex flex-column">
                            <span class="ind-solution-card__icon">
                                @if (filled($card['icon'] ?? null))
                                    <img src="{{ asset('uploads/'.$card['icon']) }}" alt="" loading="lazy">
                                @else
                                    <i class="fas fa-cube"></i>
                                @endif
                            </span>
                            <h3 class="ind-solution-card__title">{{ $card['title'] }}</h3>
                            @if (filled($card['description'] ?? null))
                                <p class="ind-solution-card__text">{{ $card['description'] }}</p>
                            @endif
                            <a href="{{ route('contact') }}" class="ind-case-card__link mt-auto pt-3">Learn More <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
