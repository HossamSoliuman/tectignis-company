@props(['service'])

@php
    $section = $service->content['case_studies'] ?? [];
    // Section shows the three most recent active case studies globally.
    $caseStudies = \App\Models\CaseStudy::with('category')->active()->latest()->limit(3)->get();
    $heading = $section['heading'] ?? 'Our Recent Success Stories';
    $subtitle = $section['subtitle'] ?? 'Proof of Work';
@endphp

@if (($section['enabled'] ?? true) && $caseStudies->isNotEmpty())
    <section class="svc-section svc-cases">
        <div class="container">
            <div class="svc-cases__head wow move-up">
                @if (filled($subtitle))
                    <span class="svc-eyebrow">{{ $subtitle }}</span>
                @endif
                <h2 class="svc-section-title">{{ $heading }}</h2>
                <p class="svc-cases__lead">Real projects, real outcomes. See how we've helped businesses like yours turn ideas into measurable results.</p>
            </div>

            <div class="row svc-cases__grid">
                @foreach ($caseStudies as $caseStudy)
                    <div class="col-lg-4 col-md-6 wow move-up">
                        <a href="{{ route('case-studies.index') }}" class="svc-case-card"
                            @if ($caseStudy->image) style="background-image:linear-gradient(180deg, rgba(12,10,38,0.15) 0%, rgba(12,10,38,0.92) 100%), url('{{ asset('uploads/'.$caseStudy->image) }}');" @endif>
                            <div class="svc-case-card__content">
                                @if ($caseStudy->category)
                                    <span class="svc-case-card__tag">{{ $caseStudy->category->name }}</span>
                                @endif
                                <h3 class="svc-case-card__title">{{ $caseStudy->title }}</h3>
                                @if ($caseStudy->short_description)
                                    <p class="svc-case-card__text">{{ \Illuminate\Support\Str::limit($caseStudy->short_description, 90) }}</p>
                                @endif
                                <span class="svc-case-card__link">View case study <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
