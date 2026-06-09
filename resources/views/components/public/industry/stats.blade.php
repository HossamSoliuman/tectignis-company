@props(['industry'])

@php
    $section = $industry->content['stats'] ?? [];
    $items = array_values(array_filter($section['items'] ?? [], fn ($i) => filled($i['value'] ?? null)));
    $heading = $section['heading'] ?? null;
    $subtitle = $section['subtitle'] ?? null;
@endphp

@if (($section['enabled'] ?? true) && count($items))
    <section class="ind-stats">
        <div class="container">
            @if (filled($heading) || filled($subtitle))
                <div class="svc-section-head text-center">
                    @if (filled($subtitle))
                        <span class="svc-eyebrow">{{ $subtitle }}</span>
                    @endif
                    @if (filled($heading))
                        <h2 class="svc-section-title">{{ $heading }}</h2>
                    @endif
                </div>
            @endif

            <div class="ind-stats__grid">
                @foreach ($items as $item)
                    <div class="ind-stat wow move-up">
                        <span class="ind-stat__icon">
                            @if (filled($item['icon'] ?? null))
                                <img src="{{ asset('uploads/'.$item['icon']) }}" alt="" loading="lazy">
                            @else
                                <i class="fas fa-chart-line"></i>
                            @endif
                        </span>
                        <span class="ind-stat__value">{{ $item['value'] }}</span>
                        @if (filled($item['label'] ?? null))
                            <span class="ind-stat__label">{{ $item['label'] }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
