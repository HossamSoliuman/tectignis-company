@props(['caseStudy'])

{{-- A portfolio card linking to the full case study (spec §20 CaseStudyCard). --}}
<article {{ $attributes->merge(['class' => 'tx-card tx-case-card']) }}>
    <div class="tx-case-card__media">
        @if ($caseStudy->image)
            <img src="{{ asset('uploads/'.$caseStudy->image) }}" alt="{{ $caseStudy->title }}" width="480" height="270" loading="lazy" decoding="async">
        @else
            <span class="tx-case-card__placeholder" aria-hidden="true"><i class="fas fa-laptop-code"></i></span>
        @endif
    </div>

    <div class="tx-case-card__body">
        @if ($caseStudy->category)
            <span class="tx-case-card__tag">{{ $caseStudy->category->name }}</span>
        @endif

        <h3 class="tx-case-card__title">
            <a href="{{ route('case-studies.show', $caseStudy->slug) }}" class="tx-card__link">{{ $caseStudy->title }}</a>
        </h3>

        @if ($caseStudy->short_description)
            <p class="tx-case-card__text">{{ Str::limit($caseStudy->short_description, 150) }}</p>
        @endif

        @if (! empty($caseStudy->features))
            <ul class="tx-case-card__outcomes">
                @foreach (array_slice($caseStudy->features, 0, 3) as $feature)
                    <li><i class="fas fa-check" aria-hidden="true"></i> {{ $feature }}</li>
                @endforeach
            </ul>
        @endif

        <span class="tx-case-card__more" aria-hidden="true">Read case study <i class="fas fa-arrow-right"></i></span>
    </div>
</article>
