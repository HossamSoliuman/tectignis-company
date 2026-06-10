@props(['solution'])

@php
    $section = $solution->content['why_choose'] ?? [];
    $heading = $section['heading'] ?? 'Why Choose Tectignis';
    $subtitle = $section['subtitle'] ?? 'The Tectignis Difference';
    $points = array_values(array_filter($section['points'] ?? [], fn ($p) => filled($p)));
    $image = $section['image'] ?? null;

    $quote = $section['testimonial_quote'] ?? null;
    $author = $section['testimonial_author'] ?? null;
    $role = $section['testimonial_role'] ?? null;
    $ctaLabel = $section['testimonial_cta_label'] ?? null;

    $hasAside = filled($image) || filled($quote);
@endphp

@if (($section['enabled'] ?? true) && (count($points) || $hasAside))
    <section class="svc-section sol-why">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12 wow move-up">
                    @if (filled($subtitle))
                        <span class="svc-eyebrow">{{ $subtitle }}</span>
                    @endif
                    <h2 class="svc-section-title sol-why__title">{{ $heading }}</h2>

                    @if (count($points))
                        <ul class="ind-challenges sol-why__points">
                            @foreach ($points as $point)
                                <li>
                                    <span class="ind-challenges__icon"><i class="fas fa-check"></i></span>
                                    <span>{{ $point }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                @if ($hasAside)
                    <div class="col-lg-6 col-md-12 sol-why__aside wow move-up">
                        @if (filled($image))
                            <div class="sol-why__media">
                                <img src="{{ asset('uploads/'.$image) }}" alt="{{ $heading }}" loading="lazy">
                            </div>
                        @endif

                        @if (filled($quote))
                            <figure class="sol-testimonial">
                                <span class="sol-testimonial__mark"><i class="fas fa-quote-right"></i></span>
                                <blockquote class="sol-testimonial__quote">{{ $quote }}</blockquote>
                                @if (filled($author) || filled($role))
                                    <figcaption class="sol-testimonial__author">
                                        @if (filled($author))
                                            <span class="sol-testimonial__name">{{ $author }}</span>
                                        @endif
                                        @if (filled($role))
                                            <span class="sol-testimonial__role">{{ $role }}</span>
                                        @endif
                                    </figcaption>
                                @endif
                                @if (filled($ctaLabel))
                                    <a href="{{ route('case-studies.index') }}" class="ind-case-card__link sol-testimonial__link">{{ $ctaLabel }} <i class="fas fa-arrow-right"></i></a>
                                @endif
                            </figure>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
