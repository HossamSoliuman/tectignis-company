@extends('layouts.public')

@section('title', 'Tectignis IT Solutions | Software, AI, Cloud, Cybersecurity & Smart Security')

@section('seo')
    <meta name="description" content="Tectignis delivers custom software development, AI automation, cloud infrastructure, cybersecurity and smart security systems. Serving Navi Mumbai, Mumbai, Thane, Pune, India & worldwide.">
    <meta name="keywords" content="Software Development, AI Automation, Cloud Infrastructure, Cybersecurity, CCTV, Access Control, IT Solutions Navi Mumbai, PAN India IT Services">
    <meta property="og:title" content="Tectignis IT Solutions | Software, AI, Cloud & Security">
    <meta property="og:description" content="Transforming businesses through software, AI & smart technology solutions.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <link rel="canonical" href="{{ url('/') }}">
@endsection

@section('content')

    {{-- 1. Hero --}}
    <div class="hero-area">
        <div class="hero-slider">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="hero-item"
                            style="background-image: url({{ asset('assets/images/hero/slider-cybersecurity-slide-01-image-01.webp') }});">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="hero-content">
                                            <h1 class="hero-title">Transforming Businesses Through Software, AI &amp; Smart Technology</h1>
                                            <p class="hero-text">Custom Software Development, AI Automation, Cloud Infrastructure, Cybersecurity &amp; Smart Security Systems. Serving Navi Mumbai, Mumbai, Thane, Pune, India &amp; Worldwide.</p>
                                            <a href="{{ route('contact') }}" class="ht-btn ht-btn-md">Request Consultation</a>
                                            <a href="{{ route('contact') }}" class="ht-btn ht-btn-md ht-btn--outline">Get a Quote</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="hero-item"
                            style="background-image: url({{ asset('assets/images/hero/slider-processing-slide-01-image-01.webp') }});">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="hero-content">
                                            <h1 class="hero-title">Custom Software &amp; AI Automation</h1>
                                            <p class="hero-text">We build enterprise-grade software, ERP, CRM and AI solutions tailored to your business.</p>
                                            <a href="{{ route('contact') }}" class="ht-btn ht-btn-md">Request Consultation</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    {{-- 2. Trusted Technology Partner (stats) --}}
    <div class="fun-fact-wrapper bg-theme-default section-space--pb_30 section-space--pt_60">
        <div class="container">
            <div class="row">
                @foreach ([['100+', 'Projects Delivered'], ['50+', 'Clients Served'], ['5+', 'Years Experience'], ['24/7', 'Support']] as [$count, $label])
                    <div class="col-md-3 col-sm-6 wow move-up">
                        <div class="fun-fact--two text-center">
                            <div class="fun-fact__count">{{ $count }}</div>
                            <h6 class="fun-fact__text">{{ $label }}</h6>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 3. Our Capabilities --}}
    <div class="service-grid-area section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_60">
                        <h6 class="section-sub-title mb-20">What we do</h6>
                        <h3 class="heading">Our <span class="text-color-primary">Capabilities</span></h3>
                    </div>
                </div>
            </div>
            <div class="feature-images__six">
                <div class="row">
                    @php
                        $capabilities = [
                            ['Software-Development.webp', 'Software Development', 'Custom software, web apps, ERP, CRM and SaaS platforms built for scale.'],
                            ['mobile-app-development.webp', 'Mobile Apps', 'Native and cross-platform Android & iOS apps with Flutter and React Native.'],
                            ['customize-software-development.webp', 'AI & Automation', 'AI chatbots, WhatsApp automation, OCR, machine learning and voice bots.'],
                            ['Network-Storage-Solutions.webp', 'Cloud & Infrastructure', 'AWS, Azure, Google Cloud, migration and managed hosting.'],
                            ['cctv-com.webp', 'Cybersecurity', 'VAPT, firewall security, SOC support and compliance.'],
                            ['access-control-system.webp', 'Smart Security', 'CCTV, access control, visitor management and biometrics.'],
                        ];
                    @endphp
                    @foreach ($capabilities as [$icon, $title, $text])
                        <div class="col-lg-4 col-md-6 wow move-up">
                            <div class="ht-box-images style-06">
                                <div class="image-box-wrap">
                                    <div class="box-image">
                                        <div class="default-image">
                                            <img class="img-fulid" src="{{ asset('assets/images/icons/' . $icon) }}"
                                                alt="{{ $title }}" loading="lazy">
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h5 class="heading">{{ $title }}</h5>
                                        <div class="text">{{ $text }}</div>
                                        <a href="{{ route('capabilities.index') }}" class="box-images-arrow">
                                            <span class="button-text">Learn more</span>
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- 5. Solutions We Deliver --}}
    <div class="service-area bg-theme-default section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_60">
                        <h6 class="section-sub-title mb-20">Business-focused</h6>
                        <h3 class="heading">Solutions We <span class="text-color-primary">Deliver</span></h3>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach (['ERP Solutions', 'CRM Solutions', 'HRMS Solutions', 'AI Solutions', 'Cloud Solutions', 'Cybersecurity Solutions', 'Automation Solutions', 'Smart Security Solutions'] as $solution)
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-30 wow move-up">
                        <div class="ht-box-icon style-01 single-svg-icon-box">
                            <div class="icon-box-wrap">
                                <div class="content">
                                    <h5 class="heading"><a href="{{ route('capabilities.index') }}">{{ $solution }}</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 6. Industries We Serve --}}
    <div class="feature-icon-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_60">
                        <h6 class="section-sub-title mb-20">Industries</h6>
                        <h3 class="heading">Industries We <span class="text-color-primary">Serve</span></h3>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach (['Manufacturing', 'Healthcare', 'Education', 'Retail', 'Real Estate', 'Logistics', 'Hospitality', 'Corporate Offices'] as $industry)
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-30 wow move-up text-center">
                        <div class="ht-box-icon style-01">
                            <div class="content">
                                <h5 class="heading">{{ $industry }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 8. Technology Stack --}}
    <div class="tech-stack-area bg-theme-default section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">Our Expertise</h6>
                        <h3 class="heading">Technology <span class="text-color-primary">Stack</span></h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                @foreach (['React', 'Flutter', 'Node.js', 'PHP', 'Python', 'Laravel', 'AWS', 'Azure', 'Google Cloud', 'MySQL', 'PostgreSQL', 'MongoDB'] as $tech)
                    <div class="col-auto mb-20">
                        <span class="ht-btn ht-btn--outline ht-btn-md">{{ $tech }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 11. Testimonials --}}
    <x-public.testimonials />

    {{-- Clients --}}
    <x-public.brands />

    {{-- 13. Call To Action --}}
    <x-public.cta heading="Ready to Transform Your Business?" buttonText="Request Consultation" />

@endsection
