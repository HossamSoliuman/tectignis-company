@props(['industry'])

@php
    $section = $industry->content['solutions_grid'] ?? [];
    $items = array_values(array_filter($section['items'] ?? [], fn ($i) => filled($i['label'] ?? null)));
    $heading = $section['heading'] ?? $industry->name.' Solutions We Offer';
    $subtitle = $section['subtitle'] ?? null;
@endphp

@if (($section['enabled'] ?? true) && count($items))
    <section class="svc-section ind-grid-section">
        <div class="container">
            <div class="svc-section-head text-center">
                @if (filled($subtitle))
                    <span class="svc-eyebrow">{{ $subtitle }}</span>
                @endif
                <h2 class="svc-section-title">{{ $heading }}</h2>
            </div>

            <div class="ind-grid">
                @foreach ($items as $item)
                    <div class="ind-grid__item wow move-up">
                        <span class="ind-grid__icon">
                            @if (filled($item['icon'] ?? null))
                                <img src="{{ asset('uploads/'.$item['icon']) }}" alt="" loading="lazy">
                            @else
                                <i class="fas fa-cube"></i>
                            @endif
                        </span>
                        <span class="ind-grid__label">{{ $item['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
