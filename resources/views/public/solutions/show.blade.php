@extends('layouts.public')

@section('title', $solution->seo_title ?: $solution->title.' | Tectignis IT Solutions')

@section('seo')
    <meta name="description" content="{{ $solution->seo_description ?: $solution->short_description }}">
    @if ($solution->seo_keywords)
        <meta name="keywords" content="{{ $solution->seo_keywords }}">
    @endif
    <link rel="canonical" href="{{ route('solutions.show', $solution->slug) }}">
    <meta property="og:title" content="{{ $solution->seo_title ?: $solution->title.' | Tectignis IT Solutions' }}">
    <meta property="og:description" content="{{ $solution->seo_description ?: $solution->short_description }}">
    <meta property="og:url" content="{{ route('solutions.show', $solution->slug) }}">
    <meta property="og:type" content="website">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb :title="$solution->title"
        :items="['Solutions' => route('solutions.index'), $solution->title => null]" />
@endsection

@section('content')
    @if ($solution->body)
        @include('public._dynamic-body', ['body' => $solution->body])
    @else
    <!--===========  feature-large-images-wrapper  Start =============-->
    <div class="feature-large-images-wrapper section-space--ptb_100">
        <div class="container">
            <div class="cybersecurity-about-box">
                <div class="row align-items-center">
                    <div class="col-lg-4 text-center">
                        @if ($solution->icon)
                            <i class="{{ $solution->icon }} fa-5x text-color-primary"></i>
                        @endif
                    </div>
                    <div class="col-lg-7 offset-lg-1">
                        <div class="conact-us-wrap-one managed-it">
                            <h3 class="heading"><span class="text-color-primary">{{ $solution->title }}</span></h3>
                            <div class="sub-heading">{{ $solution->short_description }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===========  feature-large-images-wrapper  End =============-->

    @if ($solution->description)
        <div class="section-space--ptb_100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        {!! $solution->description !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!--========== Call to Action Area Start ============-->
    <x-public.cta />
    <!--========== Call to Action Area End ============-->
    @endif
@endsection
