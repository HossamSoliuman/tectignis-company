@extends('layouts.public')

@section('title', ($category ? $category->name.' Projects' : 'Case Studies').' | Portfolio - Tectignis')

@section('seo')
    <meta name="description" content="Recent Tectignis projects across software, websites, e-commerce, AI automation, cloud and IT infrastructure, with the challenge, solution and technology behind each one.">
@endsection

@if ($category || $caseStudies->currentPage() > 1)
    @section('robots', 'noindex, follow')
@endif

@section('breadcrumb')
    <x-public.breadcrumb title="Case Studies" :items="['Case Studies' => null]" />
@endsection

@section('content')
    <div class="projects-wrapper section-space--ptb_100 projects-masonary-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h2 class="heading tx-size-h3">Proud projects that <span class="text-color-primary">make us stand out</span></h2>
                    </div>
                </div>
            </div>

            @if ($categories->count() > 1)
                <nav class="res-pills justify-content-lg-center" aria-label="Filter projects by category">
                    <a href="{{ route('case-studies.index') }}" class="res-pill {{ $category ? '' : 'is-active' }}" @if (! $category) aria-current="page" @endif>All Projects</a>
                    @foreach ($categories as $option)
                        <a href="{{ route('case-studies.index', ['category' => $option->id]) }}" class="res-pill {{ $category?->is($option) ? 'is-active' : '' }}" @if ($category?->is($option)) aria-current="page" @endif>{{ $option->name }}</a>
                    @endforeach
                </nav>
            @endif

            @if ($caseStudies->isEmpty())
                <div class="res-empty">
                    <i class="fas fa-folder-open" aria-hidden="true"></i>
                    <p class="mb-0">No projects in this category yet.</p>
                </div>
            @else
                <div class="projects-case-wrap">
                    <div class="row mesonry-list">
                        @foreach ($caseStudies as $caseStudy)
                            <div class="col-lg-4 col-md-6 cat--2">
                                <a href="{{ route('case-studies.show', $caseStudy->slug) }}" class="projects-wrap style-01">
                                    <div class="projects-image-box">
                                        <div class="projects-image">
                                            @if ($caseStudy->image)
                                                <img class="img-fluid" src="{{ asset('uploads/'.$caseStudy->image) }}"
                                                    alt="{{ $caseStudy->coverAlt() }}" loading="lazy">
                                            @endif
                                        </div>
                                        <div class="content">
                                            <h3 class="heading tx-size-h6">{{ $caseStudy->title }}</h3>
                                            <div class="post-categories">
                                                {{ $caseStudy->category?->name }}@if ($caseStudy->isOngoing()) · Ongoing @endif
                                            </div>
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

                @if ($caseStudies->hasPages())
                    <div class="res-pagination">
                        {{ $caseStudies->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <x-public.cta />
@endsection
