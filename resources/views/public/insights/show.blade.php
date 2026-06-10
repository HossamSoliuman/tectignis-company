@extends('layouts.public')

@section('title', $insight->seo_title ?: $insight->title.' | Tectignis IT Solutions')

@section('seo')
    <meta name="description" content="{{ $insight->seo_description ?: $insight->excerpt }}">
    @if ($insight->seo_keywords)
        <meta name="keywords" content="{{ $insight->seo_keywords }}">
    @endif
    <link rel="canonical" href="{{ route('insights.show', $insight->slug) }}">
    <meta property="og:title" content="{{ $insight->seo_title ?: $insight->title }}">
    <meta property="og:description" content="{{ $insight->seo_description ?: $insight->excerpt }}">
    @if ($insight->image)
        <meta property="og:image" content="{{ asset('uploads/'.$insight->image) }}">
    @endif
    <meta property="og:url" content="{{ route('insights.show', $insight->slug) }}">
    <meta property="og:type" content="article">
@endsection

@section('content')

    <!--=========== Insight Hero ===========-->
    <section class="res-hero res-hero--article">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="res-hero__eyebrow">Technology Insights</span>
                    <h1 class="res-hero__title">{{ $insight->title }}</h1>
                    <div class="res-hero__meta">
                        <span><i class="far fa-user" aria-hidden="true"></i> By {{ $insight->author ?: 'Tectignis Team' }}</span>
                        <span><i class="far fa-calendar" aria-hidden="true"></i> {{ $insight->published_at->format('M d, Y') }}</span>
                        @if ($insight->topic)
                            <span class="res-hero__topic">{{ $insight->topic }}</span>
                        @endif
                    </div>
                    <div class="res-share">
                        <span>Share:</span>
                        <a target="_blank" rel="noopener" aria-label="Share on Facebook"
                            href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('insights.show', $insight->slug)) }}"><i class="fab fa-facebook-f"></i></a>
                        <a target="_blank" rel="noopener" aria-label="Share on Twitter"
                            href="https://twitter.com/intent/tweet?url={{ urlencode(route('insights.show', $insight->slug)) }}&text={{ urlencode($insight->title) }}"><i class="fab fa-twitter"></i></a>
                        <a target="_blank" rel="noopener" aria-label="Share on LinkedIn"
                            href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('insights.show', $insight->slug)) }}"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                @if ($insight->image)
                    <div class="col-lg-5">
                        <div class="res-hero__media">
                            <img src="{{ asset('uploads/'.$insight->image) }}" alt="{{ $insight->title }}" loading="eager">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!--=========== Insight Body ===========-->
    <div class="res-body">
        <div class="container">
            <div class="row">

                <div class="col-lg-9">
                    <div class="res-article">
                        {!! $insight->content !!}

                        <div class="res-article-cta">
                            <div>
                                <h5>Ready to Put This Into Practice?</h5>
                                <p>Talk to our experts about applying these insights to your business.</p>
                            </div>
                            <a href="{{ route('contact') }}" class="svc-btn svc-btn--primary">Talk to Our Expert <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 mt-5 mt-lg-0">
                    @if ($topics->isNotEmpty())
                        <div class="res-widget">
                            <h4 class="res-widget__title">Popular Topics</h4>
                            <ul class="res-cats">
                                @foreach ($topics as $topic => $count)
                                    <li>
                                        <a href="{{ route('technology-insights', ['topic' => $topic]) }}" class="{{ $insight->topic === $topic ? 'is-active' : '' }}">
                                            <i class="fas fa-bolt" aria-hidden="true"></i> {{ $topic }}
                                            <span class="res-cats__count">{{ $count }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($popularInsights->isNotEmpty())
                        <div class="res-widget">
                            <h4 class="res-widget__title">More Insights</h4>
                            <ul class="res-pop">
                                @foreach ($popularInsights as $popular)
                                    <li>
                                        <span class="res-pop__thumb">
                                            @if ($popular->image)
                                                <img src="{{ asset('uploads/'.$popular->image) }}" alt="{{ $popular->title }}" loading="lazy">
                                            @else
                                                <i class="far fa-lightbulb" aria-hidden="true"></i>
                                            @endif
                                        </span>
                                        <div>
                                            <a href="{{ route('insights.show', $popular->slug) }}" class="res-pop__title">{{ $popular->title }}</a>
                                            <span class="res-pop__date">{{ $popular->published_at->format('M d, Y') }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <x-public.resource.newsletter />
                </div>

            </div>
        </div>
    </div>

@endsection
