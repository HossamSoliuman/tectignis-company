@extends('layouts.public')

@section('title', $service->seo_title ?: $service->title.' | Tectignis IT Solutions')

@section('seo')
    <meta name="description" content="{{ $service->seo_description ?: $service->short_description }}">
    @if ($service->seo_keywords)
        <meta name="keywords" content="{{ $service->seo_keywords }}">
    @endif
    <link rel="canonical" href="{{ route('services.show', $service->slug) }}">
    <meta property="og:title" content="{{ $service->seo_title ?: $service->title.' | Tectignis IT Solutions' }}">
    <meta property="og:description" content="{{ $service->seo_description ?: $service->short_description }}">
    <meta property="og:url" content="{{ route('services.show', $service->slug) }}">
    <meta property="og:type" content="website">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb :title="$service->title"
        :items="['Services' => route('services.index'), $service->title => null]" />
@endsection

@section('content')
    @if ($service->body)
        @include('public._dynamic-body', ['body' => $service->body])
    @else
    <!--===========  feature-large-images-wrapper  Start =============-->
    <div class="feature-large-images-wrapper section-space--ptb_100">
        <div class="container">
            <div class="cybersecurity-about-box">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="modern-number-01">
                            <h3 class="heading mt-30">Best <span class="text-color-primary">{{ $service->title }}</span><br>in Navi Mumbai</h3>
                        </div>
                    </div>
                    <div class="col-lg-7 offset-lg-1">
                        <div class="conact-us-wrap-one managed-it">
                            <h5 class="heading"><span class="text-color-primary">Tectignis IT Solutions</span>
                                specializes in affordable &amp; effective
                                <span class="text-color-primary">{{ $service->title }}</span> services.</h5>
                            <div class="sub-heading">Contact Tectignis IT Solutions today for a free consultation</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===========  feature-large-images-wrapper  End =============-->

    @if ($service->description)
        <div class="section-space--ptb_100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        {!! $service->description !!}
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="software-innovation-about-company-area software-innovation-about-bg section-space--ptb_120">
            <div class="container">
                <div class="row">
                    @if ($service->banner_image)
                        <div class="col-lg-6">
                            <div class="image-inner-video-section">
                                <img class="img-fluid border-radus-5"
                                    src="{{ asset('uploads/'.$service->banner_image) }}"
                                    alt="{{ $service->title }}" loading="lazy">
                            </div>
                        </div>
                        <div class="col-lg-6 ms-auto mt-30">
                    @else
                        <div class="col-lg-10 offset-lg-1">
                    @endif
                        <div class="machine-learning-about-content">
                            <div class="section-title mb-20">
                                <div class="section-title-wrap text-left section-space--mb_30">
                                    <h3 class="heading">Expert <span class="text-color-primary">{{ $service->title }}</span> Services</h3>
                                </div>
                                <p class="dec-text mt-20">{{ $service->short_description }}</p>
                                <div class="tab-button mt-30">
                                    <a class="btn-text" href="{{ route('contact') }}">
                                        <span class="button-text">Get a free consultation <i class="fas fa-arrow-right"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
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
