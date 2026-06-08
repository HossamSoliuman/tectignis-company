@props(['service'])

@php
    $section = $service->content['tech'] ?? [];
    // Per-service attached technologies, falling back to all active records.
    $techStacks = $service->techStacks->where('is_active', true)->sortBy('sort_order');
    if ($techStacks->isEmpty()) {
        $techStacks = \App\Models\TechStack::active()->ordered()->get();
    }
    $heading = $section['heading'] ?? 'Technologies We Use';
    $subtitle = $section['subtitle'] ?? 'Our Stack';
@endphp

@if (($section['enabled'] ?? true) && $techStacks->isNotEmpty())
    <section class="svc-section svc-tech">
        <div class="container">
            <div class="svc-section-head text-center">
                @if (filled($subtitle))
                    <span class="svc-eyebrow">{{ $subtitle }}</span>
                @endif
                <h2 class="svc-section-title">{{ $heading }}</h2>
            </div>

            <div class="svc-tech__grid">
                @foreach ($techStacks as $tech)
                    <div class="svc-tech-card wow move-up">
                        <div class="svc-tech-card__logo-wrap">
                            @if ($tech->logo)
                                <img class="svc-tech-card__logo" src="{{ asset('uploads/'.$tech->logo) }}"
                                    alt="{{ $tech->name }}" loading="lazy">
                            @else
                                <span class="svc-tech-card__fallback"><i class="fas fa-microchip"></i></span>
                            @endif
                        </div>
                        <span class="svc-tech-card__name">{{ $tech->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
