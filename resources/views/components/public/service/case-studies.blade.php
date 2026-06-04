@props(['service'])

@php
    $section = $service->content['case_studies'] ?? [];
    // Section G shows the three most recent active case studies globally.
    $caseStudies = \App\Models\CaseStudy::active()->latest()->limit(3)->get();
    $heading = $section['heading'] ?? 'Our Recent Success Stories';
    $subtitle = $section['subtitle'] ?? null;
@endphp

@if (($section['enabled'] ?? true) && $caseStudies->isNotEmpty())
    <div class="projects-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        @if (filled($subtitle))
                            <h6 class="section-sub-title mb-20">{{ $subtitle }}</h6>
                        @endif
                        <h3 class="heading">{{ $heading }}</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($caseStudies as $caseStudy)
                    <div class="col-lg-4 col-md-6 wow move-up section-space--mt_30">
                        <a href="{{ route('case-studies.index') }}" class="projects-wrap style-01 d-block h-100">
                            <div class="projects-image-box">
                                @if ($caseStudy->image)
                                    <div class="projects-image">
                                        <img class="img-fluid" src="{{ asset('uploads/'.$caseStudy->image) }}"
                                            alt="{{ $caseStudy->title }}" loading="lazy">
                                    </div>
                                @endif
                                <div class="content">
                                    <h6 class="heading">{{ $caseStudy->title }}</h6>
                                    @if ($caseStudy->category)
                                        <div class="post-categories">{{ $caseStudy->category }}</div>
                                    @endif
                                    <div class="text">{{ $caseStudy->short_description }}</div>
                                    <div class="box-projects-arrow">
                                        <span class="button-text">View case study</span>
                                        <i class="fas fa-arrow-right ml-1"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
