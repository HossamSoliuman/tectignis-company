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
                    <span class="svc-eyebrow">{{ $subtitle }}</span><br>
                @endif
                <h2 class="svc-section-title">{{ $heading }}</h2>
            </div>

                        <div class="row tech-stack-row">
                @foreach ($techStacks as $tech)
                <div class="col-lg-2 col-md-3 col-4 wow move-up">
                    <div class="tech-stack-card">
                        <div class="tech-stack-card__logo">
                            @if ($tech->logo)
                                <img src="{{ asset('uploads/'.$tech->logo) }}" alt="{{ $tech->name }}" loading="lazy">
                            @endif
                        </div>
                        <p class="tech-stack-card__name">{{ $tech->name }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
