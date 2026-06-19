@props(['solution'])

@php
    $hero = $solution->content['hero'] ?? [];
    $dark = ($hero['theme'] ?? 'light') === 'dark';
    $eyebrow = $hero['eyebrow'] ?? null;
    $heading = $hero['heading'] ?? $solution->title;
    $highlight = $hero['highlight'] ?? null;
    $intro = $hero['intro'] ?? $solution->short_description;
    $ctaPrimary = $hero['cta_primary_label'] ?? 'Request Free Consultation';
    $ctaSecondary = $hero['cta_secondary_label'] ?? 'Talk to Our Expert';

    $benefits = array_values(array_filter($hero['benefits'] ?? [], fn ($b) => filled($b)));
    $badges = array_values(array_filter($hero['badges'] ?? [], fn ($b) => filled($b['label'] ?? null)));

    // Highlight the chosen word(s) inside the heading in the pink accent colour.
    $headingHtml = e($heading);
    if (filled($highlight) && str_contains($heading, $highlight)) {
        $headingHtml = str_replace(e($highlight), '<span class="svc-accent">'.e($highlight).'</span>', e($heading));
    }
@endphp

<section class="ind-hero {{ $dark ? 'ind-hero--dark' : '' }}">
    <span class="ind-hero__blob" aria-hidden="true"></span>
    <div class="container">
        <nav class="ind-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('solutions.index') }}">Solutions</a>
            <span aria-hidden="true">/</span>
            <span class="ind-breadcrumb__current">{{ $solution->title }}</span>
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

                @if (count($benefits))
                    <ul class="ind-hero__features">
                        @foreach ($benefits as $benefit)
                            <li>
                                <span class="ind-hero__feature-icon"><i class="fas fa-check"></i></span>
                                <span>{{ $benefit }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="ind-hero__buttons">
                    <a href="{{ route('contact') }}" class="svc-btn svc-btn--primary">{{ $ctaPrimary }} <i class="fas fa-arrow-right"></i></a>
                    <a href="{{ route('contact') }}" class="svc-btn ind-btn--indigo">{{ $ctaSecondary }} <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 ind-hero__media wow move-up">
                <div class="ind-hero__frame">
                    @if ($solution->banner_image)
                        <img class="ind-hero__image" src="{{ asset('uploads/'.$solution->banner_image) }}" alt="{{ $solution->title }}" loading="eager">
                    @else
                        <div class="ind-hero__image ind-hero__image--placeholder">
                            <i class="{{ $solution->icon ?: 'fas fa-cubes' }}"></i>
                        </div>
                    @endif

                    @foreach ($badges as $loop_index => $badge)
                        <div class="ind-hero__badge ind-hero__badge--{{ $loop_index + 1 }}">
                            <span class="ind-hero__badge-icon">
                                @if (filled($badge['icon'] ?? null))
                                    <img src="{{ asset('uploads/'.$badge['icon']) }}" alt="" loading="lazy">
                                @else
                                    <i class="fas fa-cube"></i>
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
