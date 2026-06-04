@props(['service'])

@php
    $hero = $service->content['hero'] ?? [];
    $eyebrow = $hero['eyebrow'] ?? \Illuminate\Support\Str::headline($service->category ?? '');
    $heading = $hero['heading'] ?? trim($service->title.' Services in Navi Mumbai');
    $intro = $hero['intro'] ?? $service->short_description;
    $bullets = array_values(array_filter($hero['bullets'] ?? [], fn ($b) => filled($b)));
    $ctaPrimary = $hero['cta_primary_label'] ?? 'Get Started';
    $ctaSecondary = $hero['cta_secondary_label'] ?? 'Talk to an Expert';

    // Highlight the service name inside the heading when it appears there.
    $headingHtml = e($heading);
    if (filled($service->title) && str_contains($heading, $service->title)) {
        $headingHtml = str_replace(e($service->title), '<span class="text-color-primary">'.e($service->title).'</span>', e($heading));
    }
@endphp

<div class="software-innovation-hero-wrapper section-space--ptb_90">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12">
                <div class="software-innovation-hero-wrap wow move-up">
                    <div class="software-innovation-hero-text">
                        @if (filled($eyebrow))
                            <p class="sub-heading text-uppercase font-weight--bold text-color-primary">{{ $eyebrow }}</p>
                        @endif
                        <h1 class="font-weight--reguler mb-20">{!! $headingHtml !!}</h1>
                        @if (filled($intro))
                            <p class="dec-text">{{ $intro }}</p>
                        @endif

                        <div class="hero-button mt-30">
                            <a href="{{ route('contact') }}" class="ht-btn ht-btn-md">{{ $ctaPrimary }}</a>
                            <a href="{{ route('contact') }}" class="ht-btn ht-btn-md ht-btn--outline ms-3">{{ $ctaSecondary }}</a>
                        </div>

                        @if (count($bullets))
                            <ul class="check-list mt-30">
                                @foreach ($bullets as $bullet)
                                    <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i>{{ $bullet }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
            @if ($service->banner_image)
                <div class="col-lg-6 col-md-12">
                    <div class="software-innovation-hero-image mt-30">
                        <img class="img-fluid border-radus-5" src="{{ asset('uploads/'.$service->banner_image) }}"
                            alt="{{ $service->title }}" loading="lazy">
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
