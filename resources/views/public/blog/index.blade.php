@extends('layouts.public')

@section('title', 'Blog | Latest Insights & Trends in IT Solutions - Tectignis')

@section('seo')
    <meta name="description" content="Stay updated with the latest IT industry trends, tips and insights from Tectignis IT Solutions on software, AI, cloud and security.">
    <link rel="canonical" href="{{ route('blog.index') }}">
@endsection

@section('content')

    <!--=========== Blog Hero ===========-->
    <section class="res-hero">
        <div class="container">
            <span class="res-hero__eyebrow">Our Blog</span>
            <h1 class="res-hero__title">Insights. Ideas. <span class="res-accent">Innovation.</span></h1>
            <p class="res-hero__intro">Explore expert insights, industry trends, and practical guides to help your business grow with technology.</p>
        </div>
    </section>

    <!--=========== Blog Listing ===========-->
    <div class="res-body">
        <div class="container">
            <div class="row">

                {{-- Main column --}}
                <div class="col-lg-9">
                    <form class="res-toolbar" action="{{ route('blog.index') }}" method="get">
                        <div class="res-search">
                            <span class="res-search__icon"><i class="fas fa-search" aria-hidden="true"></i></span>
                            <input type="search" name="q" value="{{ $search }}" placeholder="Search blogs, topics, categories...">
                        </div>
                        @if ($activeCategory !== '')
                            <input type="hidden" name="category" value="{{ $activeCategory }}">
                        @endif
                        <button type="submit" class="svc-btn svc-btn--primary">Search</button>
                    </form>

                    @if ($posts->isEmpty())
                        <div class="res-empty">
                            <i class="far fa-newspaper" aria-hidden="true"></i>
                            <p class="mb-0">No articles found{{ $search !== '' ? ' for "'.$search.'"' : '' }}. Try a different search or category.</p>
                        </div>
                    @else
                        <div class="row">
                            @foreach ($posts as $post)
                                <div class="col-xl-4 col-md-6 mb-30 wow move-up">
                                    <x-public.resource.card
                                        :url="route('blog.show', $post->slug)"
                                        :image="$post->image"
                                        :topic="$post->category"
                                        :date="$post->published_at"
                                        :title="$post->title"
                                        :excerpt="$post->excerpt" />
                                </div>
                            @endforeach
                        </div>

                        @if ($posts->hasPages())
                            <div class="res-pagination">
                                {{ $posts->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-3 mt-5 mt-lg-0">
                    @if ($categories->isNotEmpty())
                        <div class="res-widget">
                            <h4 class="res-widget__title">Categories</h4>
                            <ul class="res-cats">
                                <li>
                                    <a href="{{ route('blog.index') }}" class="{{ $activeCategory === '' ? 'is-active' : '' }}">
                                        <i class="fas fa-layer-group" aria-hidden="true"></i> All Categories
                                        <span class="res-cats__count">{{ $categories->sum() }}</span>
                                    </a>
                                </li>
                                @foreach ($categories as $category => $count)
                                    <li>
                                        <a href="{{ route('blog.index', ['category' => $category]) }}" class="{{ $activeCategory === $category ? 'is-active' : '' }}">
                                            <i class="fas fa-tag" aria-hidden="true"></i> {{ $category }}
                                            <span class="res-cats__count">{{ $count }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($popularPosts->isNotEmpty())
                        <div class="res-widget">
                            <h4 class="res-widget__title">Popular Posts</h4>
                            <ul class="res-pop">
                                @foreach ($popularPosts as $popular)
                                    <li>
                                        <span class="res-pop__thumb">
                                            @if ($popular->image)
                                                <img src="{{ asset('uploads/'.$popular->image) }}" alt="{{ $popular->title }}" loading="lazy">
                                            @else
                                                <i class="far fa-image" aria-hidden="true"></i>
                                            @endif
                                        </span>
                                        <div>
                                            <a href="{{ route('blog.show', $popular->slug) }}" class="res-pop__title">{{ $popular->title }}</a>
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
