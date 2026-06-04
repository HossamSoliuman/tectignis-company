@props(['service'])

@php
    $section = $service->content['faq'] ?? [];
    $items = array_values(array_filter($section['items'] ?? [], fn ($i) => filled($i['question'] ?? null)));
    $heading = $section['heading'] ?? 'Frequently Asked Questions';
    $subtitle = $section['subtitle'] ?? null;
    $accordionId = 'faq-'.$service->id;
@endphp

@if (($section['enabled'] ?? true) && count($items))
    <div class="feature-images-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="section-title-wrap section-space--mb_40">
                        @if (filled($subtitle))
                            <h6 class="section-sub-title mb-20">{{ $subtitle }}</h6>
                        @endif
                        <h3 class="heading">{{ $heading }}</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="accordion" id="{{ $accordionId }}">
                        @foreach ($items as $index => $item)
                            <div class="accordion-item mb-10">
                                <h2 class="accordion-header" id="{{ $accordionId }}-heading-{{ $index }}">
                                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#{{ $accordionId }}-collapse-{{ $index }}">
                                        {{ $item['question'] }}
                                    </button>
                                </h2>
                                <div id="{{ $accordionId }}-collapse-{{ $index }}"
                                    class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                    data-bs-parent="#{{ $accordionId }}">
                                    <div class="accordion-body">
                                        {{ $item['answer'] ?? '' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
