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
        <meta property="og:image" content="{{ asset('assets/images/blog/'.$post->image) }}">
    @endif
    <meta property="og:url" content="{{ route('blog.show', $post->slug) }}">
    <meta property="og:type" content="article">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb :title="$post->title"
        :items="['Blog' => route('blog.index'), $post->title => null]" />
@endsection

@section('content')
    <!--====================  Blog Area Start ====================-->
    <div class="blog-pages-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">

                {{-- Sidebar --}}
                <div class="col-lg-4 order-lg-1 order-2">
                    <div class="page-sidebar-content-wrapper page-sidebar-left small-mt__40 tablet-mt__40">

                        <div class="sidebar-widget widget-blog-recent-post wow move-up">
                            <h4 class="sidebar-widget-title">Recent Posts</h4>
                            <ul>
                                @foreach ($recentPosts as $recent)
                                    <li>
                                        <a href="{{ route('blog.show', $recent->slug) }}"
                                            class="{{ $recent->slug === $post->slug ? 'active' : '' }}">
                                            {{ $recent->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="sidebar-widget widget-images wow move-up">
                            <img class="img-fluid" src="{{ asset('assets/images/blog/Startup-Services-India.webp') }}"
                                alt="Startup Marketing">
                            <p class="pt-4"><strong>Affordable Growth Solutions</strong></p>
                            <p><strong>Let <a href="{{ route('home') }}" style="color: #C10897;">Tectignis IT Solutions</a> build your brand online</strong></p>
                            <p><strong>Schedule Your Free Consultation!</strong></p>
                            <a href="{{ route('contact') }}" class="ht-btn ht-btn-xs mt-15">Contact Us</a>
                        </div>

                    </div>
                </div>

                {{-- Main content --}}
                <div class="col-lg-8 order-lg-2 order-1">
                    <div class="main-blog-wrap">
                        <div class="single-blog-item">
                            <div class="post-feature blog-thumbnail wow move-up">
                                <h3 class="post-title mb-5">{{ $post->title }}</h3>
                            </div>

                            <div class="post-info lg-blog-post-info wow move-up">
                                <div class="post-meta mt-20">
                                    <div class="post-date">
                                        <span class="far fa-calendar meta-icon"></span>
                                        {{ $post->published_at->format('M d, Y') }}
                                    </div>
                                </div>

                                <div class="post-excerpt mt-15">
                                    {!! $post->content !!}
                                </div>

                                <div class="entry-post-share-wrap border-bottom">
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="entry-post-share">
                                                <div class="share-label">Share this post</div>
                                                <div class="share-media">
                                                    <span class="share-icon fas fa-share-alt"></span>
                                                    <div class="share-list">
                                                        <a class="hint--bounce hint--top hint--primary twitter"
                                                            target="_blank" aria-label="Twitter"
                                                            href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}">
                                                            <i class="fab fa-twitter"></i>
                                                        </a>
                                                        <a class="hint--bounce hint--top hint--primary facebook"
                                                            target="_blank" aria-label="Facebook"
                                                            href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}">
                                                            <i class="fab fa-facebook-f"></i>
                                                        </a>
                                                        <a class="hint--bounce hint--top hint--primary linkedin"
                                                            target="_blank" aria-label="LinkedIn"
                                                            href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.show', $post->slug)) }}">
                                                            <i class="fab fa-linkedin"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--====================  Blog Area End  ====================-->

    <!--========== Call to Action Area Start ============-->
    <x-public.cta />
    <!--========== Call to Action Area End ============-->
@endsection
