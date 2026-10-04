@props([
    'quote',
    'name',
    'designation' => null,
    'company' => null,
    'project' => null,
    'rating' => 5,
])

{{-- A single client testimonial with attribution (spec §4.3, §20 Testimonial). --}}
<figure {{ $attributes->merge(['class' => 'tx-testimonial']) }}>
    @if ($rating)
        <div class="tx-testimonial__stars" role="img" aria-label="Rated {{ min(5, (int) $rating) }} out of 5">
            @for ($i = 0; $i < min(5, (int) $rating); $i++)
                <i class="fas fa-star" aria-hidden="true"></i>
            @endfor
        </div>
    @endif

    <blockquote class="tx-testimonial__quote">
        <p>{{ $quote }}</p>
    </blockquote>

    <figcaption class="tx-testimonial__author">
        <span class="tx-testimonial__avatar" aria-hidden="true">{{ Str::upper(Str::substr($name, 0, 1)) }}</span>
        <span>
            <span class="tx-testimonial__name">{{ $name }}</span>
            @if ($designation || $company)
                <span class="tx-testimonial__role">{{ collect([$designation, $company])->filter()->join(', ') }}</span>
            @endif
            @if ($project)
                <span class="tx-testimonial__project">{{ $project }}</span>
            @endif
        </span>
    </figcaption>
</figure>
