@props(['industry'])

@php
    $hero = $industry->content['hero'] ?? [];
    $eyebrow = $hero['eyebrow'] ?? null;
    $heading = $hero['heading'] ?? $industry->name;
    $highlight = $hero['highlight'] ?? null;
    $intro = $hero['intro'] ?? $industry->short_description;
    $ctaPrimary = $hero['cta_primary_label'] ?? 'Talk to an Expert';
    $ctaSecondary = $hero['cta_secondary_label'] ?? 'Explore Solutions';

    $features = array_values(array_filter($hero['features'] ?? [], fn ($f) => filled($f['label'] ?? null)));
    $badges = array_values(array_filter($hero['badges'] ?? [], fn ($b) => filled($b['label'] ?? null)));

    // Highlight the chosen word(s) inside the heading in the pink accent colour.
    $headingHtml = e($heading);
    if (filled($highlight) && str_contains($heading, $highlight)) {
        $headingHtml = str_replace(e($highlight), '<span class="svc-accent">'.e($highlight).'</span>', e($heading));
    }
@endphp

<section class="ind-hero">
    <span class="ind-hero__blob" aria-hidden="true"></span>
    <div class="container">
        <nav class="ind-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('industries.index') }}">Industries</a>
            <span aria-hidden="true">/</span>
            <span class="ind-breadcrumb__current">{{ $industry->name }}</span>
        </nav>

        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 ind-hero__content wow move-up">
                @if (filled($eyebrow))
                    <span class="svc-eyebrow">{{ $eyebrow }}</span>
                @endif
                <h1 class="ind-hero__title">{!! $headingHtml !!}</h1>
                @if (filled($intro))
                    <p class="ind-hero__intro">{{ $intro }}</p>
                @endif

                @if (count($features))
                    <ul class="ind-hero__features">
                        @foreach ($features as $feature)
                            <li>
                                <span class="ind-hero__feature-icon">
                                    @if (filled($feature['icon'] ?? null))
                                        <img src="{{ asset('uploads/'.$feature['icon']) }}" alt="" loading="lazy">
                                    @else
                                        <i class="fas fa-check"></i>
                                    @endif
                                </span>
                                <span>{{ $feature['label'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="ind-hero__buttons">
                    <button type="button" class="svc-btn svc-btn--primary js-consult-open">{{ $ctaPrimary }} <i class="fas fa-arrow-right"></i></button>
                    <a href="{{ route('services.index') }}" class="svc-btn ind-btn--indigo">{{ $ctaSecondary }} <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 ind-hero__media wow move-up">
                <div class="ind-hero__frame">
                    @if ($industry->banner_image)
                        <img class="ind-hero__image" src="{{ asset('uploads/'.$industry->banner_image) }}" alt="{{ $industry->name }}" loading="eager">
                    @else
                        <div class="ind-hero__image ind-hero__image--placeholder">
                            <i class="{{ $industry->icon ?: 'fas fa-building' }}"></i>
                        </div>
                    @endif

                    @foreach ($badges as $loop_index => $badge)
                        <div class="ind-hero__badge ind-hero__badge--{{ $loop_index + 1 }}">
                            <span class="ind-hero__badge-icon">
                                @if (filled($badge['icon'] ?? null))
                                    <img src="{{ asset('uploads/'.$badge['icon']) }}" alt="" loading="lazy">
                                @else
                                    <i class="fas fa-shield-alt"></i>
                                @endif
                            </span>
                            <span class="ind-hero__badge-label">{{ $badge['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
