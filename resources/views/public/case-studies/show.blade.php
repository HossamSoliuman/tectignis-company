@extends('layouts.public')

@php
    $metaTitle = $caseStudy->seo_title ?: $caseStudy->title.' | Case Study - Tectignis';
    $metaDescription = $caseStudy->seo_description ?: ($caseStudy->short_description ?: 'Case study: '.$caseStudy->title);
    $facts = array_filter([
        'Client' => $caseStudy->client_name,
        'Category' => $caseStudy->category?->name,
        'Industry' => $caseStudy->industry?->name,
        'Location' => $caseStudy->location,
        'Date' => $caseStudy->project_date?->format('F Y'),
        'Stage' => $caseStudy->project_status->label(),
    ]);
    $gallery = $caseStudy->galleryImages();
    $relatedService = $caseStudy->service?->is_active ? $caseStudy->service : null;
@endphp

@section('title', $metaTitle)

@if ($preview)
    @section('robots', 'noindex, nofollow')
@endif

@section('seo')
    <meta name="description" content="{{ Str::limit($metaDescription, 300) }}">
    <meta property="og:title" content="{{ $caseStudy->seo_title ?: $caseStudy->title }}">
    <meta property="og:description" content="{{ Str::limit($metaDescription, 300) }}">
    @if ($caseStudy->image)
        <meta property="og:image" content="{{ asset('uploads/'.$caseStudy->image) }}">
    @endif
    <meta property="og:url" content="{{ route('case-studies.show', $caseStudy->slug) }}">
    <meta property="og:type" content="article">
@endsection

@section('canonical', route('case-studies.show', $caseStudy->slug))

@section('breadcrumb')
    <x-public.breadcrumb :title="$caseStudy->title" :items="['Case Studies' => route('case-studies.index'), $caseStudy->title => null]" />
@endsection

@section('content')

    @if ($preview)
        <div class="cs-preview-bar" role="status">
            Preview · {{ $caseStudy->status->label() }}
            @unless ($caseStudy->isPublished())
                · not visible on the public site
            @endunless
        </div>
    @endif

    <!--=========== Case Study Hero ===========-->
    <section class="res-hero res-hero--article">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="res-hero__eyebrow">{{ $caseStudy->isOngoing() ? 'Ongoing Project' : 'Case Study' }}</span>
                    <h1 class="res-hero__title">{{ $caseStudy->title }}</h1>
                    @if ($caseStudy->category || $caseStudy->industry)
                        <div class="res-hero__meta">
                            @if ($caseStudy->category)
                                <span class="res-hero__topic">{{ $caseStudy->category->name }}</span>
                            @endif
                            @if ($caseStudy->industry)
                                <span class="res-hero__topic">{{ $caseStudy->industry->name }}</span>
                            @endif
                        </div>
                    @endif
                    @if ($caseStudy->short_description)
                        <p class="res-hero__intro">{{ $caseStudy->short_description }}</p>
                    @endif
                    <div class="res-hero__buttons mt-3">
                        <a href="{{ localized_route('contact') }}" class="svc-btn svc-btn--primary">Discuss a Similar Project <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
                @if ($caseStudy->image)
                    <div class="col-lg-5">
                        <div class="res-hero__media">
                            <img src="{{ asset('uploads/'.$caseStudy->image) }}" alt="{{ $caseStudy->coverAlt() }}" loading="eager">
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
                        @if (filled($caseStudy->challenge))
                            <h2>The Challenge</h2>
                            {!! nl2br(e($caseStudy->challenge)) !!}
                        @endif

                        @if (filled($caseStudy->solution))
                            <h2>Our Solution</h2>
                            {!! nl2br(e($caseStudy->solution)) !!}
                        @endif

                        @if (! empty($caseStudy->modules))
                            <h2>Modules and Features</h2>
                            <ul class="cs-checklist">
                                @foreach ($caseStudy->modules as $module)
                                    <li><i class="fas fa-check-circle" aria-hidden="true"></i><span>{{ $module }}</span></li>
                                @endforeach
                            </ul>
                        @endif

                        @if (filled($caseStudy->content))
                            {!! \App\Support\ArticleHeadings::outline($caseStudy->content) !!}
                        @elseif (blank($caseStudy->challenge) && blank($caseStudy->solution))
                            <h2>Overview</h2>
                            <p>{{ $caseStudy->short_description }}</p>
                        @endif

                        @if ($gallery !== [])
                            <h2>Screenshots</h2>
                            <div class="cs-gallery">
                                @foreach ($gallery as $item)
                                    <a href="{{ asset('uploads/'.$item['image']) }}" target="_blank" rel="noopener" class="cs-gallery__item">
                                        <img src="{{ asset('uploads/'.$item['image']) }}" alt="{{ $item['alt'] }}" loading="lazy" decoding="async">
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        @if (filled($caseStudy->outcome))
                            <h2>Outcome</h2>
                            {!! nl2br(e($caseStudy->outcome)) !!}
                        @endif

                        @if ($caseStudy->testimonial?->is_active)
                            <blockquote class="cs-quote">
                                <p>“{{ $caseStudy->testimonial->quote }}”</p>
                                <cite>{{ $caseStudy->testimonial->name }}</cite>
                            </blockquote>
                        @endif

                        <div class="res-article-cta">
                            <div>
                                <h2>Have a project like this in mind?</h2>
                                <p>Tell us what you need and we'll reply with an approach and next steps.</p>
                            </div>
                            <a href="{{ localized_route('contact') }}" class="svc-btn svc-btn--primary">Discuss a Similar Project <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mt-5 mt-lg-0">
                    @if ($facts !== [])
                        <div class="res-widget">
                            <h2 class="res-widget__title">Project Details</h2>
                            <dl class="cs-facts">
                                @foreach ($facts as $term => $detail)
                                    <div class="cs-facts__row">
                                        <dt>{{ $term }}</dt>
                                        <dd>{{ $detail }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    @endif

                    @if (! empty($caseStudy->technologies))
                        <div class="res-widget">
                            <h2 class="res-widget__title">Technology Stack</h2>
                            <ul class="cs-tags">
                                @foreach ($caseStudy->technologies as $technology)
                                    <li>{{ $technology }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (! empty($caseStudy->features))
                        <div class="res-widget">
                            <h2 class="res-widget__title">Project Highlights</h2>
                            <ul class="cs-checklist">
                                @foreach ($caseStudy->features as $feature)
                                    <li><i class="fas fa-check-circle" aria-hidden="true"></i><span>{{ $feature }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="res-widget">
                        <h2 class="res-widget__title">Explore More</h2>
                        <ul class="res-cats">
                            @if ($relatedService)
                                <li><a href="{{ localized_route('services.show', $relatedService->slug) }}"><i class="fas fa-layer-group" aria-hidden="true"></i> {{ $relatedService->title }}</a></li>
                            @endif
                            @if ($caseStudy->category)
                                <li><a href="{{ route('case-studies.index', ['category' => $caseStudy->category->id]) }}"><i class="fas fa-tag" aria-hidden="true"></i> More {{ $caseStudy->category->name }} projects</a></li>
                            @endif
                            <li><a href="{{ localized_route('contact') }}"><i class="fas fa-comments" aria-hidden="true"></i> Talk to our team</a></li>
                        </ul>
                        <a href="{{ route('case-studies.index') }}" class="svc-btn svc-btn--ghost mt-3">
                            <i class="fas fa-arrow-left" aria-hidden="true"></i> All Case Studies
                        </a>
                    </div>
                </div>

            </div>

            @if ($relatedCaseStudies->isNotEmpty())
                <div class="res-related mt-5">
                    <h2 class="res-section-title">Related Case Studies</h2>
                    <div class="row mesonry-list mt-4">
                        @foreach ($relatedCaseStudies as $related)
                            <div class="col-lg-4 col-md-6 cat--2">
                                <a href="{{ route('case-studies.show', $related->slug) }}" class="projects-wrap style-01">
                                    <div class="projects-image-box">
                                        <div class="projects-image">
                                            @if ($related->image)
                                                <img class="img-fluid" src="{{ asset('uploads/'.$related->image) }}"
                                                    alt="{{ $related->coverAlt() }}" loading="lazy">
                                            @endif
                                        </div>
                                        <div class="content">
                                            <h3 class="heading tx-size-h6">{{ $related->title }}</h3>
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
