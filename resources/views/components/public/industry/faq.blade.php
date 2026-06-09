@props(['industry'])

@php
    $section = $industry->content['faq'] ?? [];
    $items = array_values(array_filter($section['items'] ?? [], fn ($i) => filled($i['question'] ?? null)));
    $heading = $section['heading'] ?? 'Frequently Asked Questions';
    $subtitle = $section['subtitle'] ?? 'FAQs';
    $accordionId = 'ind-faq-'.$industry->id;

    $ctaHeading = $section['cta_heading'] ?? 'Ready to Transform Your '.$industry->name.'?';
    $ctaText = $section['cta_text'] ?? "Let's build a smart, secure and future-ready setup tailored to your goals.";
    $ctaLabel = $section['cta_label'] ?? 'Get Started Today';
@endphp

@if (($section['enabled'] ?? true) && count($items))
    <section class="svc-section ind-faq">
        <div class="container">
            <div class="row align-items-start">
                <div class="col-lg-7 col-md-12 ind-faq__main wow move-up">
                    <div class="svc-section-head text-left">
                        @if (filled($subtitle))
                            <span class="svc-eyebrow">{{ $subtitle }}</span>
                        @endif
                        <h2 class="svc-section-title">{{ $heading }}</h2>
                    </div>
                    <div class="ind-accordion accordion" id="{{ $accordionId }}">
                        @foreach ($items as $index => $item)
                            <div class="ind-accordion__item accordion-item">
                                <h3 class="accordion-header" id="{{ $accordionId }}-heading-{{ $index }}">
                                    <button class="ind-accordion__button accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#{{ $accordionId }}-collapse-{{ $index }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                        {{ $item['question'] }}
                                    </button>
                                </h3>
                                <div id="{{ $accordionId }}-collapse-{{ $index }}"
                                    class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                    data-bs-parent="#{{ $accordionId }}">
                                    <div class="accordion-body ind-accordion__body">
                                        {{ $item['answer'] ?? '' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-5 col-md-12 ind-faq__aside wow move-up">
                    <div class="ind-midcta">
                        <span class="ind-midcta__pattern" aria-hidden="true"></span>
                        <h3 class="ind-midcta__title">{{ $ctaHeading }}</h3>
                        <p class="ind-midcta__text">{{ $ctaText }}</p>
                        <a href="{{ route('contact') }}" class="svc-btn svc-btn--primary">{{ $ctaLabel }} <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
