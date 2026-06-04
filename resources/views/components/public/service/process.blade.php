@props(['service'])

@php
    $section = $service->content['process'] ?? [];
    $steps = array_values(array_filter($section['steps'] ?? [], fn ($s) => filled($s['title'] ?? null)));
    $heading = $section['heading'] ?? 'Our Simple '.count($steps).'-Step Process';
    $subtitle = $section['subtitle'] ?? null;
@endphp

@if (($section['enabled'] ?? true) && count($steps))
    <div class="feature-images-wrapper bg-gray section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        @if (filled($subtitle))
                            <h6 class="section-sub-title mb-20">{{ $subtitle }}</h6>
                        @endif
                        <h3 class="heading">{{ $heading }}</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($steps as $index => $step)
                    <div class="col-lg col-md-4 col-sm-6 wow move-up section-space--mt_30">
                        <div class="cybersecurity-progress-step text-center h-100">
                            <div class="step-count font-weight--bold text-color-primary mb-15" style="font-size:34px;line-height:1;">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </div>
                            @if (filled($step['icon'] ?? null))
                                <div class="icon mb-15">
                                    <img class="img-fluid" src="{{ asset('uploads/'.$step['icon']) }}"
                                        alt="{{ $step['title'] }}" loading="lazy" style="max-height:48px;width:auto;display:inline-block;">
                                </div>
                            @endif
                            <h5 class="heading mb-10">{{ $step['title'] }}</h5>
                            @if (filled($step['description'] ?? null))
                                <p class="text">{{ $step['description'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
