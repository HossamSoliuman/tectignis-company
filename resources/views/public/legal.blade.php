@extends('layouts.public')

@section('title', $title.' | Tectignis IT Solutions')

@section('seo')
    <meta name="description" content="{{ $title }} of Tectignis IT Solutions Pvt Ltd.">
    <link rel="canonical" href="{{ route('legal.show', $slug) }}">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb :title="$title" :items="[$title => null]" />
@endsection

@section('content')
    <div class="blog-pages-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 order-lg-2 order-1">
                    <div class="main-blog-wrap">
                        <div class="single-blog-item">
                            <div class="post-info lg-blog-post-info wow move-up">
                                @if ($content)
                                    {!! $content !!}
                                @else
                                    <h3 class="post-title">{{ $title }}</h3>
                                    <div class="post-excerpt mt-15">
                                        <p>This page is being updated. Please check back soon or
                                            <a href="{{ route('contact') }}" style="color:#C10897;">contact us</a>
                                            for more information.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
