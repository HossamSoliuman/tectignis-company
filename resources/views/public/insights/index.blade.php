@extends('layouts.public')

@section('title', 'Technology Insights | Software, AI & Cloud Expertise | Tectignis')

@section('seo')
    <meta name="description" content="Explore expert perspectives, industry trends, and actionable insights from the Tectignis team — covering Cloud, AI, Security, Infrastructure and Data.">
    <link rel="canonical" href="{{ route('technology-insights') }}">
@endsection

@section('content')

    <!--=========== Insights Hero ===========-->
    <section class="res-hero">
        <div class="container">
            <span class="res-hero__eyebrow">Technology Insights</span>
            <h1 class="res-hero__title">Technology <span class="res-accent">Insights</span></h1>
            <p class="res-hero__intro">Explore expert perspectives, industry trends, and actionable insights to help you navigate the ever-evolving digital landscape.</p>
        </div>
    </section>

    <!--=========== Insights Listing ===========-->
    <div class="res-body">
        <div class="container">
            <div class="row">

                {{-- Main column --}}
                <div class="col-lg-9">
                    <div class="res-pills">
                        <a href="{{ route('technology-insights') }}" class="res-pill {{ $activeTopic === '' ? 'is-active' : '' }}">All Insights</a>
                        @foreach ($topics as $topic => $count)
                            <a href="{{ route('technology-insights', ['topic' => $topic]) }}" class="res-pill {{ $activeTopic === $topic ? 'is-active' : '' }}">{{ $topic }}</a>
                        @endforeach
                    </div>

                    @if ($insights->isEmpty())
                        <div class="res-empty">
                            <i class="far fa-lightbulb" aria-hidden="true"></i>
                            <p class="mb-0">No insights published yet. Check back soon.</p>
                        </div>
                    @else
                        <div class="row">
                            @foreach ($insights as $insight)
                                <div class="col-xl-4 col-md-6 mb-30 wow move-up">
                                    <x-public.resource.card
                                        :url="route('insights.show', $insight->slug)"
                                        :image="$insight->image"
                                        :topic="$insight->topic"
                                        :date="$insight->published_at"
                                        :title="$insight->title"
                                        :excerpt="$insight->excerpt" />
                                </div>
                            @endforeach
                        </div>

                        @if ($insights->hasPages())
                            <div class="res-pagination">
                                {{ $insights->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-3 mt-5 mt-lg-0">
                    @if ($topics->isNotEmpty())
                        <div class="res-widget">
                            <h4 class="res-widget__title">Popular Topics</h4>
                            <ul class="res-cats">
                                @foreach ($topics as $topic => $count)
                                    <li>
                                        <a href="{{ route('technology-insights', ['topic' => $topic]) }}" class="{{ $activeTopic === $topic ? 'is-active' : '' }}">
                                            <i class="fas fa-bolt" aria-hidden="true"></i> {{ $topic }}
                                            <span class="res-cats__count">{{ $count }}</span>
                                        </a>
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
