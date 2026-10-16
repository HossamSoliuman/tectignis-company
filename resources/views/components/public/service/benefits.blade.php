@props(['service'])

@php
    $section = $service->content['benefits'] ?? [];
    $items = array_values(array_filter($section['items'] ?? [], fn ($i) => filled($i['title'] ?? null)));
    $heading = $section['heading'] ?? __('services.benefits_heading');
    $subtitle = $section['subtitle'] ?? __('services.benefits_subtitle');
    $fallbackIcons = ['fas fa-chart-line', 'fas fa-bolt', 'fas fa-expand-arrows-alt', 'fas fa-key', 'fas fa-smile', 'fas fa-shield-alt'];
@endphp

@if (($section['enabled'] ?? true) && count($items))
    <section class="svc-section svc-benefits">
        <div class="container">
            <div class="svc-section-head text-center">
                @if (filled($subtitle))
                    <span class="svc-eyebrow">{{ $subtitle }}</span>
                @endif
                <h2 class="svc-section-title">{{ $heading }}</h2>
            </div>

            <div class="row svc-benefits__grid">
                @foreach ($items as $index => $item)
                    <div class="col-lg-4 col-md-6 wow move-up">
                        <div class="svc-benefit">
                            <span class="svc-benefit__icon" aria-hidden="true">
                                @if (filled($item['icon'] ?? null))
                                    <img src="{{ asset('uploads/'.$item['icon']) }}" alt="" loading="lazy">
                                @else
                                    <i class="{{ $fallbackIcons[$index % count($fallbackIcons)] }}"></i>
                                @endif
                            </span>
                            <div>
                                <h3 class="svc-benefit__title">{{ $item['title'] }}</h3>
                                @if (filled($item['description'] ?? null))
                                    <p class="svc-benefit__text">{{ $item['description'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
