@extends('layouts.public')

@section('title', 'Blog | Latest Insights & Trends in IT Solutions - Tectignis')

@section('seo')
    <meta name="description" content="Stay updated with the latest IT industry trends, tips and insights from Tectignis IT Solutions on software, AI, cloud and security.">
    <link rel="canonical" href="{{ route('blog.index') }}">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb title="Blog" :items="['Blog' => null]" />
@endsection

@section('content')
    <div class="blog-pages-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">
                @foreach ($posts as $post)
                    <div class="col-lg-4 col-md-6 mb-30 wow move-up">
                        <div class="single-blog-item blog-grid">
                            <div class="post-feature blog-thumbnail">
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    @if ($post->image)
                                        <img class="img-fluid" src="{{ asset('uploads/'.$post->image) }}" alt="{{ $post->title }}">
                                    @endif
                                </a>
                            </div>
                            <div class="post-info lg-blog-post-info">
                                <div class="post-meta">
                                    <div class="post-date">
                                        <span class="far fa-calendar meta-icon"></span> {{ $post->published_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <h5 class="post-title font-weight--bold">
                                    <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                </h5>
                                <div class="post-excerpt mt-15">
                                    <p>{{ $post->excerpt }}</p>
                                </div>
                                <div class="btn-text">
                                    <a href="{{ route('blog.show', $post->slug) }}">Read more <i class="ml-1 button-icon fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <x-public.cta />
@endsection
