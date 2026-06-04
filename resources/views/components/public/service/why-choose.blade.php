@props(['service'])

@php
    $section = $service->content['why_choose'] ?? [];
    $points = array_values(array_filter($section['points'] ?? [], fn ($p) => filled($p)));
    $heading = $section['heading'] ?? 'Why Choose Tectignis';
    $subtitle = $section['subtitle'] ?? null;
    $image = $section['image'] ?? null;
    $ctaLabel = $section['cta_label'] ?? 'Get a Quote';
@endphp

@if (($section['enabled'] ?? true) && count($points))
    <div class="software-innovation-about-company-area software-innovation-about-bg section-space--ptb_120">
        <div class="container">
            <div class="row align-items-center">
                @if ($image)
                    <div class="col-lg-6">
                        <div class="image-inner-video-section">
                            <img class="img-fluid border-radus-5" src="{{ asset('uploads/'.$image) }}"
                                alt="{{ $heading }}" loading="lazy">
                        </div>
                    </div>
                    <div class="col-lg-6 ms-auto mt-30">
                @else
                    <div class="col-lg-10 offset-lg-1">
                @endif
                    <div class="machine-learning-about-content">
                        <div class="section-title mb-20">
                            <div class="section-title-wrap text-left section-space--mb_30">
                                @if (filled($subtitle))
                                    <h6 class="section-sub-title mb-20">{{ $subtitle }}</h6>
                                @endif
                                <h3 class="heading">{{ $heading }}</h3>
                            </div>
                            <ul class="check-list mt-20">
                                @foreach ($points as $point)
                                    <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i>{{ $point }}</li>
                                @endforeach
                            </ul>
                            <div class="button-group-wrap mt-30">
                                <a href="{{ route('contact') }}" class="btn">{{ $ctaLabel }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
