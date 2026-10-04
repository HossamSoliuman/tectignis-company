@extends('layouts.public')

@section('title', $caseStudy->title.' | Case Study - Tectignis')

@section('seo')
    <meta name="description" content="{{ $caseStudy->short_description ?: 'Case study: '.$caseStudy->title }}">
    <meta property="og:title" content="{{ $caseStudy->title }}">
    <meta property="og:description" content="{{ $caseStudy->short_description }}">
    @if ($caseStudy->image)
        <meta property="og:image" content="{{ asset('uploads/'.$caseStudy->image) }}">
    @endif
    <meta property="og:url" content="{{ route('case-studies.show', $caseStudy->slug) }}">
    <meta property="og:type" content="article">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb :title="$caseStudy->title" :items="['Case Studies' => route('case-studies.index'), $caseStudy->title => null]" />
@endsection

@section('content')

    <!--=========== Case Study Hero ===========-->
    <section class="res-hero res-hero--article">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="res-hero__eyebrow">Case Study</span>
                    <h1 class="res-hero__title">{{ $caseStudy->title }}</h1>
                    @if ($caseStudy->category)
                        <div class="res-hero__meta">
                            <span class="res-hero__topic">{{ $caseStudy->category->name }}</span>
                        </div>
                    @endif
                    @if ($caseStudy->short_description)
                        <p class="res-hero__intro">{{ $caseStudy->short_description }}</p>
                    @endif
                    <div class="res-hero__buttons mt-3">
                        <a href="{{ route('contact') }}" class="svc-btn svc-btn--primary">Start a Similar Project <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
                @if ($caseStudy->image)
                    <div class="col-lg-5">
                        <div class="res-hero__media">
                            <img src="{{ asset('uploads/'.$caseStudy->image) }}" alt="{{ $caseStudy->title }}" loading="eager">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!--=========== Case Study Body ===========-->
    <div class="res-body">
        <div class="container">
            <div class="row">

                <div class="col-lg-8">
                    <div class="res-article">
                        @if (filled($caseStudy->content))
                            {!! $caseStudy->content !!}
                        @else
                            <h2>Overview</h2>
                            <p>{{ $caseStudy->short_description }}</p>
                        @endif

                        <div class="res-article-cta">
                            <div>
                                <h5>Have a project like this in mind?</h5>
                                <p>Talk to our experts about your software, AI, cloud or security requirements.</p>
                            </div>
                            <a href="{{ route('contact') }}" class="svc-btn svc-btn--primary">Talk to Our Expert <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mt-5 mt-lg-0">
                    @if (! empty($caseStudy->features))
                        <div class="res-widget">
                            <h4 class="res-widget__title">Key Results</h4>
                            <ul class="svc-sell-panel__list">
                                @foreach ($caseStudy->features as $feature)
                                    <li><i class="fas fa-check-circle"></i><span>{{ $feature }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="res-widget">
                        <h4 class="res-widget__title">Project Details</h4>
                        <ul class="res-cats">
                            @if ($caseStudy->category)
                                <li><a href="{{ route('case-studies.index') }}"><i class="fas fa-tag" aria-hidden="true"></i> {{ $caseStudy->category->name }}</a></li>
                            @endif
                        </ul>
                        <a href="{{ route('case-studies.index') }}" class="svc-btn svc-btn--ghost mt-2">
                            <i class="fas fa-arrow-left" aria-hidden="true"></i> All Case Studies
                        </a>
                    </div>
                </div>

            </div>

            @if ($relatedCaseStudies->isNotEmpty())
                <div class="res-related mt-5">
                    <h3 class="res-section-title">More Success Stories</h3>
                    <div class="row mesonry-list mt-4">
                        @foreach ($relatedCaseStudies as $related)
                            <div class="col-lg-4 col-md-6 cat--2">
                                <a href="{{ route('case-studies.show', $related->slug) }}" class="projects-wrap style-01">
                                    <div class="projects-image-box">
                                        <div class="projects-image">
                                            @if ($related->image)
                                                <img class="img-fluid" src="{{ asset('uploads/'.$related->image) }}"
                                                    alt="{{ $related->title }}" loading="lazy">
                                            @endif
                                        </div>
                                        <div class="content">
                                            <h6 class="heading">{{ $related->title }}</h6>
                                            <div class="post-categories">{{ $related->category?->name }}</div>
                                            <div class="text">{{ $related->short_description }}</div>
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
            @endif
        </div>
    </div>

    <x-public.cta />
@endsection
