@props(['solution'])

@php
    $section = $solution->content['benefits'] ?? [];
    $items = array_values(array_filter($section['items'] ?? [], fn ($i) => filled($i['title'] ?? null)));
    $heading = $section['heading'] ?? 'Key Benefits';
    $subtitle = $section['subtitle'] ?? null;
@endphp

@if (($section['enabled'] ?? true) && count($items))
    <section class="svc-section bg-gray">
        <div class="container">
            <div class="svc-section-head text-center">
                @if (filled($subtitle))
                    <span class="svc-eyebrow">{{ $subtitle }}</span>
                @endif
                <h2 class="svc-section-title">{{ $heading }}</h2>
            </div>

            <div class="row ind-solutions__grid">
                @foreach ($items as $item)
                    <div class="col-lg-4 col-md-6 col-12 wow move-up">
                        <div class="ind-solution-card h-100">
                            <span class="ind-solution-card__icon">
                                @if (filled($item['icon'] ?? null))
                                    <img src="{{ asset('uploads/'.$item['icon']) }}" alt="" loading="lazy">
                                @else
                                    <i class="fas fa-check"></i>
                                @endif
                            </span>
                            <h3 class="ind-solution-card__title">{{ $item['title'] }}</h3>
                            @if (filled($item['description'] ?? null))
                                <p class="ind-solution-card__text">{{ $item['description'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
