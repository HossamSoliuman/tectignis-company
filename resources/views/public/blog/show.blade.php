@extends('layouts.public')

@section('title', $post->seo_title ?: $post->title.' | Tectignis IT Solutions')

@section('seo')
    <meta name="description" content="{{ $post->seo_description ?: $post->excerpt }}">
    @if ($post->seo_keywords)
        <meta name="keywords" content="{{ $post->seo_keywords }}">
    @endif
    <link rel="canonical" href="{{ route('blog.show', $post->slug) }}">
    <meta property="og:title" content="{{ $post->seo_title ?: $post->title }}">
    <meta property="og:description" content="{{ $post->seo_description ?: $post->excerpt }}">
    @if ($post->image)
        <meta property="og:image" content="{{ asset('uploads/'.$post->image) }}">
    @endif
    <meta property="og:url" content="{{ route('blog.show', $post->slug) }}">
    <meta property="og:type" content="article">
@endsection

@section('content')

    <!--=========== Article Hero ===========-->
    <section class="res-hero res-hero--article">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="res-hero__eyebrow">Blog</span>
                    <h1 class="res-hero__title">{{ $post->title }}</h1>
                    <div class="res-hero__meta">
                        <span><i class="far fa-user" aria-hidden="true"></i> By {{ $post->author ?: 'Tectignis Team' }}</span>
                        <span><i class="far fa-calendar" aria-hidden="true"></i> {{ $post->published_at->format('M d, Y') }}</span>
                        @if ($post->category)
                            <span class="res-hero__topic">{{ $post->category }}</span>
                        @endif
                    </div>
                    <div class="res-share">
                        <span>Share:</span>
                        <a target="_blank" rel="noopener" aria-label="Share on Facebook"
                            href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}"><i class="fab fa-facebook-f"></i></a>
                        <a target="_blank" rel="noopener" aria-label="Share on Twitter"
                            href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}"><i class="fab fa-twitter"></i></a>
                        <a target="_blank" rel="noopener" aria-label="Share on LinkedIn"
                            href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.show', $post->slug)) }}"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                @if ($post->image)
                    <div class="col-lg-5">
                        <div class="res-hero__media">
                            <img src="{{ asset('uploads/'.$post->image) }}" alt="{{ $post->title }}" loading="eager">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!--=========== Article Body ===========-->
    <div class="res-body">
        <div class="container">
            <div class="row">

                <div class="col-lg-9">
                    <div class="res-article">
                        {!! $post->content !!}

                        <div class="res-article-cta">
                            <div>
                                <h5>Ready to Get Started?</h5>
                                <p>Talk to our experts about your software, AI, cloud or security requirements.</p>
                            </div>
                            <a href="{{ route('contact') }}" class="svc-btn svc-btn--primary">Talk to Our Expert <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 mt-5 mt-lg-0">
                    @if ($categories->isNotEmpty())
                        <div class="res-widget">
                            <h4 class="res-widget__title">Categories</h4>
                            <ul class="res-cats">
                                @foreach ($categories as $category => $count)
                                    <li>
                                        <a href="{{ route('blog.index', ['category' => $category]) }}" class="{{ $post->category === $category ? 'is-active' : '' }}">
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
