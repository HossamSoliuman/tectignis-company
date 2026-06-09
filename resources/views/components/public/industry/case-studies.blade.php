@props(['industry'])

@php
    $section = $industry->content['case_studies'] ?? [];
    // Section shows the three most recent active case studies globally.
    $caseStudies = \App\Models\CaseStudy::active()->latest()->limit(3)->get();
    $heading = $section['heading'] ?? 'Real Results. Real Impact.';
    $subtitle = $section['subtitle'] ?? 'Success Stories';
@endphp

@if (($section['enabled'] ?? true) && $caseStudies->isNotEmpty())
    <section class="svc-section ind-cases">
        <div class="container">
            <div class="ind-cases__head wow move-up">
                <div>
                    @if (filled($subtitle))
                        <span class="svc-eyebrow">{{ $subtitle }}</span>
                    @endif
                    <h2 class="svc-section-title">{{ $heading }}</h2>
                </div>
                <a href="{{ route('case-studies.index') }}" class="ind-cases__view">View All Case Studies <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="row svc-cases__grid">
                @foreach ($caseStudies as $caseStudy)
                    <div class="col-lg-4 col-md-6 wow move-up">
                        <div class="ind-case-card">
                            <div class="ind-case-card__media">
                                @if ($caseStudy->image)
                                    <img src="{{ asset('uploads/'.$caseStudy->image) }}" alt="{{ $caseStudy->title }}" loading="lazy">
                                @else
                                    <span class="ind-case-card__placeholder"><i class="fas fa-briefcase"></i></span>
                                @endif
                            </div>
                            <div class="ind-case-card__body">
                                @if ($caseStudy->category)
                                    <span class="ind-case-card__tag">{{ $caseStudy->category }}</span>
                                @endif
                                <h3 class="ind-case-card__title">{{ $caseStudy->title }}</h3>
                                @if ($caseStudy->short_description)
                                    <p class="ind-case-card__text">{{ \Illuminate\Support\Str::limit($caseStudy->short_description, 100) }}</p>
                                @endif
                                <a href="{{ route('case-studies.index') }}" class="ind-case-card__link">Read More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
