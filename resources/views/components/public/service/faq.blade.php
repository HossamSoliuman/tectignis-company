@props(['service'])

@php
    $section = $service->content['faq'] ?? [];
    $items = array_values(array_filter($section['items'] ?? [], fn ($i) => filled($i['question'] ?? null)));
    $heading = $section['heading'] ?? 'Frequently Asked Questions';
    $subtitle = $section['subtitle'] ?? 'Got Questions?';
    $accordionId = 'faq-'.$service->id;

    // Selling-point panel: explicit `selling_points`, else reuse the why-choose
    // points, else a generic set so the panel is never empty.
    $panelHeading = $section['panel_heading'] ?? 'Still have questions?';
    $panelText = $section['panel_text'] ?? "Talk to our team and get clear, no-obligation answers tailored to your project.";
    $points = array_values(array_filter($section['selling_points'] ?? [], fn ($p) => filled($p)));
    if (! count($points)) {
        $points = array_slice(array_values(array_filter($service->content['why_choose']['points'] ?? [], fn ($p) => filled($p))), 0, 4);
    }
    if (! count($points)) {
        $points = ['Free, no-obligation consultation', 'Transparent, fixed-scope quotes', 'A dedicated project team', 'Long-term support & maintenance'];
    }
@endphp

@if (($section['enabled'] ?? true) && count($items))
    <section class="svc-section svc-faq">
        <div class="container">
            <div class="row align-items-start">
                <div class="col-lg-7 svc-faq__main wow move-up">
                    <div class="svc-section-head text-left">
                        @if (filled($subtitle))
                            <span class="svc-eyebrow">{{ $subtitle }}</span>
                        @endif
                        <h2 class="svc-section-title">{{ $heading }}</h2>
                    </div>
                    <div class="svc-accordion accordion" id="{{ $accordionId }}">
                        @foreach ($items as $index => $item)
                            <div class="svc-accordion__item accordion-item">
                                <h3 class="accordion-header" id="{{ $accordionId }}-heading-{{ $index }}">
                                    <button class="svc-accordion__button accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#{{ $accordionId }}-collapse-{{ $index }}">
                                        {{ $item['question'] }}
                                    </button>
                                </h3>
                                <div id="{{ $accordionId }}-collapse-{{ $index }}"
                                    class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                    data-bs-parent="#{{ $accordionId }}">
                                    <div class="accordion-body svc-accordion__body">
                                        {{ $item['answer'] ?? '' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-5 svc-faq__aside wow move-up">
                    <div class="svc-sell-panel">
                        <h3 class="svc-sell-panel__title">{{ $panelHeading }}</h3>
                        <p class="svc-sell-panel__text">{{ $panelText }}</p>
                        <ul class="svc-sell-panel__list">
                            @foreach ($points as $point)
                                <li><i class="fas fa-check-circle"></i><span>{{ $point }}</span></li>
                            @endforeach
                        </ul>
                        <a href="{{ route('contact') }}" class="svc-btn svc-btn--white">Let's Talk</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
