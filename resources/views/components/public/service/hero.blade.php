@props(['service'])

@php
    $hero = $service->content['hero'] ?? [];
    $eyebrow = $hero['eyebrow'] ?? \Illuminate\Support\Str::headline($service->category ?? '');
    $heading = $hero['heading'] ?? trim($service->title.' Services in Navi Mumbai');
    $intro = $hero['intro'] ?? $service->short_description;
    $ctaPrimary = $hero['cta_primary_label'] ?? 'Get Free Consultation';
    $ctaSecondary = $hero['cta_secondary_label'] ?? 'View Our Work';
    $bullets = array_values(array_filter($hero['bullets'] ?? [], fn ($b) => filled($b)));

    // Stats row (icon + value + label). Falls back to the approved master
    // company stats so every service hero shows the same verified figures.
    $stats = array_values(array_filter($hero['stats'] ?? [], fn ($s) => filled($s['value'] ?? null)));
    if (! count($stats)) {
        $company ??= app(\App\Support\CompanyProfile::class);
        $statIcons = ['projects' => 'fas fa-rocket', 'clients' => 'fas fa-user-friends', 'industries' => 'fas fa-industry'];
        $stats = collect($statIcons)
            ->map(fn (string $icon, string $key): ?array => ($stat = $company->stat($key)) ? ['icon' => $icon, 'value' => $stat->value, 'label' => $stat->label] : null)
            ->filter()
            ->values()
            ->all();
    }

    // Highlight the service name inside the heading when it appears there.
    $headingHtml = e($heading);
    if (filled($service->title) && str_contains($heading, $service->title)) {
        $headingHtml = str_replace(e($service->title), '<span class="svc-accent">'.e($service->title).'</span>', e($heading));
    }
@endphp

<section class="svc-hero">
    <span class="svc-hero__blob svc-hero__blob--1" aria-hidden="true"></span>
    <span class="svc-hero__blob svc-hero__blob--2" aria-hidden="true"></span>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 svc-hero__content wow move-up">
                @if (filled($eyebrow))
                    <span class="svc-eyebrow">{{ $eyebrow }}</span>
                @endif
                <h1 class="svc-hero__title">{!! $headingHtml !!}</h1>
                @if (filled($intro))
                    <p class="svc-hero__intro">{{ $intro }}</p>
                @endif

                @if (count($bullets))
                    <ul class="svc-hero__bullets">
                        @foreach ($bullets as $bullet)
                            <li><i class="fas fa-check-circle" aria-hidden="true"></i><span>{{ $bullet }}</span></li>
                        @endforeach
                    </ul>
                @endif

                <div class="svc-hero__buttons">
                    <button type="button" class="svc-btn svc-btn--primary js-consult-open">{{ $ctaPrimary }}</button>
                    <a href="{{ route('case-studies.index') }}" class="svc-btn svc-btn--ghost">{{ $ctaSecondary }}</a>
                </div>

                @if (count($stats))
                    <div class="svc-hero__stats">
                        @foreach ($stats as $stat)
                            <div class="svc-stat">
                                <div class="svc-stat__top">
                                    <span class="svc-stat__icon"><i class="{{ $stat['icon'] ?? 'fas fa-star' }}"></i></span>
                                    <span class="svc-stat__value">{{ $stat['value'] }}</span>
                                </div>
                                @if (filled($stat['label'] ?? null))
                                    <span class="svc-stat__label">{{ $stat['label'] }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-6 col-md-12 svc-hero__media wow move-up">
                <div class="svc-hero__image-frame">
                    @if ($service->banner_image)
                        <img class="svc-hero__image" src="{{ asset('uploads/'.$service->banner_image) }}"
                            alt="{{ $service->title }}" loading="eager">
                    @else
                        <div class="svc-hero__image svc-hero__image--placeholder">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
