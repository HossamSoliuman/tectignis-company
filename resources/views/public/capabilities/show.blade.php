@extends('layouts.public')

@section('title', $capability->seo_title ?: $capability->title.' | Tectignis IT Solutions')

@section('seo')
    <meta name="description" content="{{ $capability->seo_description ?: $capability->short_description }}">
    @if ($capability->seo_keywords)
        <meta name="keywords" content="{{ $capability->seo_keywords }}">
    @endif
    <link rel="canonical" href="{{ route('capabilities.show', $capability->slug) }}">
    <meta property="og:title" content="{{ $capability->seo_title ?: $capability->title.' | Tectignis IT Solutions' }}">
    <meta property="og:description" content="{{ $capability->seo_description ?: $capability->short_description }}">
    <meta property="og:url" content="{{ route('capabilities.show', $capability->slug) }}">
    <meta property="og:type" content="website">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb :title="$capability->title"
        :items="['Capabilities' => route('capabilities.index'), $capability->title => null]" />
@endsection

@section('content')
    @if ($capability->body)
        @include('public._dynamic-body', ['body' => $capability->body])
    @else
    <!--===========  feature-large-images-wrapper  Start =============-->
    <div class="feature-large-images-wrapper section-space--ptb_100">
        <div class="container">
            <div class="cybersecurity-about-box">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="modern-number-01">
                            <h3 class="heading mt-30">Best <span class="text-color-primary">{{ $capability->title }}</span><br>in Navi Mumbai</h3>
                        </div>
                    </div>
                    <div class="col-lg-7 offset-lg-1">
                        <div class="conact-us-wrap-one managed-it">
                            <h5 class="heading"><span class="text-color-primary">Tectignis IT Solutions</span>
                                specializes in affordable &amp; effective
                                <span class="text-color-primary">{{ $capability->title }}</span> services.</h5>
                            <div class="sub-heading">Contact Tectignis IT Solutions today for a free consultation</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===========  feature-large-images-wrapper  End =============-->

    @if ($capability->description)
        <div class="section-space--ptb_100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        {!! $capability->description !!}
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="software-innovation-about-company-area software-innovation-about-bg section-space--ptb_120">
            <div class="container">
                <div class="row">
                    @if ($capability->banner_image)
                        <div class="col-lg-6">
                            <div class="image-inner-video-section">
                                <img class="img-fluid border-radus-5"
                                    src="{{ asset('uploads/'.$capability->banner_image) }}"
                                    alt="{{ $capability->title }}" loading="lazy">
                            </div>
                        </div>
                        <div class="col-lg-6 ms-auto mt-30">
                    @else
                        <div class="col-lg-10 offset-lg-1">
                    @endif
                        <div class="machine-learning-about-content">
                            <div class="section-title mb-20">
                                <div class="section-title-wrap text-left section-space--mb_30">
                                    <h3 class="heading">Expert <span class="text-color-primary">{{ $capability->title }}</span> Services</h3>
                                </div>
                                <p class="dec-text mt-20">{{ $capability->short_description }}</p>
                                <div class="tab-button mt-30">
                                    <a class="btn-text js-consult-open" href="#">
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

    <!--===========  feature-icon-wrapper  Start =============-->
    <div class="feature-icon-wrapper section-space--pt_100 section-space--pb_70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_20">
                        <h3 class="heading">Why Choose Tectignis IT Solutions?</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="feature-list__three">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="grid-item animate">
                                    <div class="ht-box-icon style-03">
                                        <div class="icon-box-wrap">
                                            <div class="content-header">
                                                <div class="icon">
                                                    <i class="fas fa-briefcase"></i>
                                                </div>
                                                <h5 class="heading">Experience and Expertise</h5>
                                            </div>
                                            <div class="content">
                                                <div class="text">We have a proven track record of delivering successful solutions to businesses across various industries.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="grid-item animate">
                                    <div class="ht-box-icon style-03">
                                        <div class="icon-box-wrap">
                                            <div class="content-header">
                                                <div class="icon">
                                                    <i class="fas fa-users"></i>
                                                </div>
                                                <h5 class="heading">Client-Centric Approach</h5>
                                            </div>
                                            <div class="content">
                                                <div class="text">We prioritize client satisfaction and work closely with you throughout the entire process.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="grid-item animate">
                                    <div class="ht-box-icon style-03">
                                        <div class="icon-box-wrap">
                                            <div class="content-header">
                                                <div class="icon">
                                                    <i class="fas fa-tags"></i>
                                                </div>
                                                <h5 class="heading">Affordable Pricing</h5>
                                            </div>
                                            <div class="content">
                                                <div class="text">We offer competitive pricing without compromising on quality. Every business deserves a high-quality solution.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="grid-item animate">
                                    <div class="ht-box-icon style-03">
                                        <div class="icon-box-wrap">
                                            <div class="content-header">
                                                <div class="icon">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                                <h5 class="heading">Timely Delivery</h5>
                                            </div>
                                            <div class="content">
                                                <div class="text">We understand the importance of deadlines and strive to deliver projects on time and within budget.</div>
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
    <!--===========  feature-icon-wrapper  End =============-->

    <!--====================  gradation process area ====================-->
    <div class="gradation-process-area section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="gradation-title-wrapper section-space--mb_60">
                        <div class="gradation-title-wrap">
                            <h6 class="section-sub-title text-black mb-20">How we work</h6>
                            <h4 class="heading">Our Simple <span class="text-color-primary"><br>4-Step Process</span></h4>
                        </div>
                        <div class="gradation-sub-heading">
                            <h3 class="heading"><mark>04</mark> Steps</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ht-gradation style-01">
                        <div class="item item-1 animate wow fadeInRight" data-wow-delay="0.1s">
                            <div class="line"></div>
                            <div class="circle-wrap">
                                <div class="mask">
                                    <div class="wave-pulse wave-pulse-1"></div>
                                    <div class="wave-pulse wave-pulse-2"></div>
                                    <div class="wave-pulse wave-pulse-3"></div>
                                </div>
                                <h6 class="circle">1</h6>
                            </div>
                            <div class="content-wrap">
                                <h6 class="heading">01. Discovery &amp; Consultation</h6>
                                <div class="text">We understand your business, goals, and target audience to tailor the perfect solution.</div>
                            </div>
                        </div>
                        <div class="item item-1 animate wow fadeInRight" data-wow-delay="0.15s">
                            <div class="line"></div>
                            <div class="circle-wrap">
                                <div class="mask">
                                    <div class="wave-pulse wave-pulse-1"></div>
                                    <div class="wave-pulse wave-pulse-2"></div>
                                    <div class="wave-pulse wave-pulse-3"></div>
                                </div>
                                <h6 class="circle">2</h6>
                            </div>
                            <div class="content-wrap">
                                <h6 class="heading">02. Design &amp; Development</h6>
                                <div class="text">Our expert team crafts high-quality solutions using the latest technologies.</div>
                            </div>
                        </div>
                        <div class="item item-1 animate wow fadeInRight" data-wow-delay="0.20s">
                            <div class="line"></div>
                            <div class="circle-wrap">
                                <div class="mask">
                                    <div class="wave-pulse wave-pulse-1"></div>
                                    <div class="wave-pulse wave-pulse-2"></div>
                                    <div class="wave-pulse wave-pulse-3"></div>
                                </div>
                                <h6 class="circle">3</h6>
                            </div>
                            <div class="content-wrap">
                                <h6 class="heading">03. Testing &amp; Quality Assurance</h6>
                                <div class="text">We rigorously test every deliverable to ensure optimal performance and seamless experience.</div>
                            </div>
                        </div>
                        <div class="item item-1 animate wow fadeInRight" data-wow-delay="0.25s">
                            <div class="line"></div>
                            <div class="circle-wrap">
                                <div class="mask">
                                    <div class="wave-pulse wave-pulse-1"></div>
                                    <div class="wave-pulse wave-pulse-2"></div>
                                    <div class="wave-pulse wave-pulse-3"></div>
                                </div>
                                <h6 class="circle">4</h6>
                            </div>
                            <div class="content-wrap">
                                <h6 class="heading">04. Launch &amp; Support</h6>
                                <div class="text">We launch smoothly and provide ongoing maintenance and support for continued success.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====================  End of gradation process area  ====================-->

    <!--========== Call to Action Area Start ============-->
    <x-public.cta />
    <!--========== Call to Action Area End ============-->
    @endif
@endsection
