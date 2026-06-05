@props(['service'])

@php
    $section = $service->content['sub_services'] ?? [];
    $items = array_values(array_filter($section['items'] ?? [], fn ($i) => filled($i['title'] ?? null)));
    $heading = $section['heading'] ?? 'Our '.$service->title.' Services';
    $subtitle = $section['subtitle'] ?? 'What We Offer';
@endphp

@if (($section['enabled'] ?? true) && count($items))
    <section class="svc-section svc-sub-services">
        <div class="container">
            <div class="svc-section-head text-center">
                @if (filled($subtitle))
                    <span class="svc-eyebrow">{{ $subtitle }}</span>
                @endif
                <h2 class="svc-section-title">{{ $heading }}</h2>
            </div>

            <div class="row svc-sub-services__grid">
                @foreach ($items as $item)
                    <div class="col-lg-4 col-md-6 wow move-up">
                        <div class="svc-rect-card">
                            <span class="svc-rect-card__icon">
                                @if (filled($item['icon'] ?? null))
                                    <img src="{{ asset('uploads/'.$item['icon']) }}" alt="{{ $item['title'] }}" loading="lazy">
                                @else
                                    <i class="fas fa-check"></i>
                                @endif
                            </span>
                            <div class="svc-rect-card__body">
                                <h3 class="svc-rect-card__title">{{ $item['title'] }}</h3>
                                @if (filled($item['description'] ?? null))
                                    <p class="svc-rect-card__text">{{ $item['description'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
