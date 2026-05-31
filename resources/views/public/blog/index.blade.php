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
                @php
                    $posts = [
                        ['Top Technology Trends to Know in 2025', 'May 21, 2025', 'Artificial-Intelligence.webp', 'Keeping pace with technological advancements is not just a competitive advantage, it is a necessity as we step into 2025.'],
                        ['How Starlink is Changing the Future of Internet in India', 'May 08, 2025', 'Starlink-India.webp', 'Satellite internet is reshaping connectivity across India. Here is what it means for price, speed and access.'],
                        ['Digital Marketing Strategy for Indian Startups 2025', 'April 30, 2025', 'Digital-Marketing-Strategy.webp', 'A step-by-step guide for startups to build strong branding and clear communication from the beginning.'],
                    ];
                @endphp
                @foreach ($posts as [$title, $date, $image, $excerpt])
                    <div class="col-lg-4 col-md-6 mb-30 wow move-up">
                        <div class="single-blog-item blog-grid">
                            <div class="post-feature blog-thumbnail">
                                <a href="{{ route('blog.index') }}">
                                    <img class="img-fluid" src="{{ asset('assets/images/blog/' . $image) }}" alt="{{ $title }}">
                                </a>
                            </div>
                            <div class="post-info lg-blog-post-info">
                                <div class="post-meta">
                                    <div class="post-date">
                                        <span class="far fa-calendar meta-icon"></span> {{ $date }}
                                    </div>
                                </div>
                                <h5 class="post-title font-weight--bold">
                                    <a href="{{ route('blog.index') }}">{{ $title }}</a>
                                </h5>
                                <div class="post-excerpt mt-15">
                                    <p>{{ $excerpt }}</p>
                                </div>
                                <div class="btn-text">
                                    <a href="{{ route('blog.index') }}">Read more <i class="ml-1 button-icon fas fa-arrow-right"></i></a>
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
