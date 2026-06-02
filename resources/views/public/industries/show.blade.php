@extends('layouts.public')

@section('title', $industry->seo_title ?: $industry->name.' Industry Solutions | Tectignis IT Solutions')

@section('seo')
    <meta name="description" content="{{ $industry->seo_description ?: $industry->short_description }}">
    @if ($industry->seo_keywords)
        <meta name="keywords" content="{{ $industry->seo_keywords }}">
    @endif
    <link rel="canonical" href="{{ route('industries.show', $industry->slug) }}">
    <meta property="og:title" content="{{ $industry->seo_title ?: $industry->name.' Industry Solutions | Tectignis IT Solutions' }}">
    <meta property="og:description" content="{{ $industry->seo_description ?: $industry->short_description }}">
    <meta property="og:url" content="{{ route('industries.show', $industry->slug) }}">
    <meta property="og:type" content="website">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb :title="$industry->name"
        :items="['Industries' => route('industries.index'), $industry->name => null]" />
@endsection

@section('content')
    @if ($industry->body)
        @include('public._dynamic-body', ['body' => $industry->body])
    @else
    <!--===========  feature-large-images-wrapper  Start =============-->
    <div class="feature-large-images-wrapper section-space--ptb_100">
        <div class="container">
            <div class="cybersecurity-about-box">
                <div class="row align-items-center">
                    <div class="col-lg-4 text-center">
                        @if ($industry->icon)
                            <i class="{{ $industry->icon }} fa-5x text-color-primary"></i>
                        @endif
                    </div>
                    <div class="col-lg-7 offset-lg-1">
                        <div class="conact-us-wrap-one managed-it">
                            <h3 class="heading">Technology Solutions for <span class="text-color-primary">{{ $industry->name }}</span></h3>
                            <div class="sub-heading">{{ $industry->short_description }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===========  feature-large-images-wrapper  End =============-->

    @if ($industry->description)
        <div class="section-space--ptb_100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        {!! $industry->description !!}
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
