@props(['solution'])

@php
    $section = $solution->content['process'] ?? [];
    $steps = array_values(array_filter($section['steps'] ?? [], fn ($s) => filled($s['title'] ?? null)));
    $heading = $section['heading'] ?? 'Our Implementation Process';
    $subtitle = $section['subtitle'] ?? 'How We Work';
    // Icons cycled per step when no custom upload is provided, so the
    // "line of icons" always reads as a coherent journey.
    $fallbackIcons = ['fas fa-search', 'fas fa-clipboard-list', 'fas fa-pencil-ruler', 'fas fa-cogs', 'fas fa-graduation-cap', 'fas fa-headset'];
@endphp

@if (($section['enabled'] ?? true) && count($steps))
    <section class="svc-section svc-process sol-process">
        <div class="container">
            <div class="svc-section-head text-center">
                @if (filled($subtitle))
                    <span class="svc-eyebrow">{{ $subtitle }}</span>
                @endif
                <h2 class="svc-section-title">{{ $heading }}</h2>
            </div>

            <div class="svc-process__steps">
                <div class="row flex-nowrap">
                    @foreach ($steps as $index => $step)
                        <div class="col wow move-up">
                            <div class="svc-step">
                                <span class="svc-step__icon">
                                    @if (filled($step['icon'] ?? null))
                                        <img src="{{ asset('uploads/'.$step['icon']) }}" alt="{{ $step['title'] }}" loading="lazy">
                                    @else
                                        <i class="{{ $fallbackIcons[$index % count($fallbackIcons)] }}"></i>
                                    @endif
                                </span>
                                <span class="svc-step__num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <h3 class="svc-step__title">{{ $step['title'] }}</h3>
                                @if (filled($step['description'] ?? null))
                                    <p class="svc-step__text">{{ $step['description'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
