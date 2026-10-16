@props(['service'])

@php
    $section = $service->content['problems'] ?? [];
    $items = array_values(array_filter($section['items'] ?? [], fn ($i) => filled($i['problem'] ?? null)));
    $heading = $section['heading'] ?? __('services.problems_heading');
    $subtitle = $section['subtitle'] ?? __('services.problems_subtitle');
@endphp

@if (($section['enabled'] ?? true) && count($items))
    <section class="svc-section svc-problems">
        <div class="container">
            <div class="svc-section-head text-center">
                @if (filled($subtitle))
                    <span class="svc-eyebrow">{{ $subtitle }}</span>
                @endif
                <h2 class="svc-section-title">{{ $heading }}</h2>
            </div>

            <div class="row svc-problems__grid">
                @foreach ($items as $item)
                    <div class="col-lg-6 wow move-up">
                        <div class="svc-problem">
                            <h3 class="svc-problem__issue">
                                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                <span>{{ $item['problem'] }}</span>
                            </h3>
                            @if (filled($item['solution'] ?? null))
                                <p class="svc-problem__fix">
                                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                                    <span>{{ $item['solution'] }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
