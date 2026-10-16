@props(['service', 'relatedServices'])

@php
    $section = $service->content['related'] ?? [];
    $heading = $section['heading'] ?? __('services.related_heading');
    $subtitle = $section['subtitle'] ?? __('services.related_subtitle');
@endphp

@if (($section['enabled'] ?? true) && $relatedServices->isNotEmpty())
    <section class="svc-section svc-related" aria-labelledby="related-services-title">
        <div class="container">
            <div class="svc-section-head text-center">
                @if (filled($subtitle))
                    <span class="svc-eyebrow">{{ $subtitle }}</span>
                @endif
                <h2 class="svc-section-title" id="related-services-title">{{ $heading }}</h2>
            </div>

            <div class="row svc-related__grid">
                @foreach ($relatedServices as $related)
                    <div class="col-lg-4 col-md-6 wow move-up">
                        <a href="{{ localized_route('services.show', $related->slug) }}" class="svc-related-card">
                            <h3 class="svc-related-card__title">{{ $related->title }}</h3>
                            @if ($related->short_description)
                                <p class="svc-related-card__text">{{ \Illuminate\Support\Str::limit($related->short_description, 120) }}</p>
                            @endif
                            <span class="svc-related-card__link">{{ __('services.learn_more') }} <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
