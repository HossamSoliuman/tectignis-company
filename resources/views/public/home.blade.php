@extends('layouts.public')

@section('title', 'Software, AI & IT Solutions Company | Navi Mumbai, Mumbai, Pune, India | Tectignis')

@section('seo')
    <meta name="description" content="Tectignis IT Solutions – Custom Software Development, AI Automation, Cloud Infrastructure, Cybersecurity & Smart Security Systems. Serving Navi Mumbai, Mumbai, Thane, Pune, Maharashtra & Worldwide.">
    <meta name="keywords" content="software development company Navi Mumbai, IT solutions Mumbai, AI development India, cloud services Pune, cybersecurity company Thane, ERP development, CRM solutions, CCTV installation Mumbai">
    <meta property="og:title" content="Software, AI & IT Solutions Company | Navi Mumbai | Tectignis">
    <meta property="og:description" content="Custom Software Development, AI Automation, Cloud Infrastructure, Cybersecurity & Smart Security Systems. Serving Navi Mumbai, Mumbai, Thane, Pune, India & Worldwide.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <link rel="canonical" href="{{ url('/') }}">
@endsection

@section('content')

    <!--============ Hero Banner Start ============-->
    <div class="software-innovation-hero-wrapper section-space--pt_10">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="software-innovation-hero-wrap wow move-up">
                        <div class="software-innovation-hero-text">
                            <p class="sub-heading">Serving Clients Across Navi Mumbai, Mumbai, Thane, Pune, India &amp; Worldwide.</p>
                            <h1 class="font-weight--reguler mb-20">Transforming Businesses Through <span class="text-color-primary">Software, AI &amp; Smart Technology</span> Solutions</h1>
                            <h6 class="info-heading">Custom Software Development, AI Automation, Cloud Infrastructure, Cybersecurity &amp; Smart Security Systems.</h6>
                            <div class="hero-button mt-30">
                                <a href="{{ route('contact') }}" class="ht-btn ht-btn-md">Request Consultation</a>
                                <a href="{{ route('contact') }}" class="ht-btn ht-btn-md ht-btn--outline ms-3">Get a Quote</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="software-innovation-hero-image animation_images three mt-30">
                        <img src="{{ \App\Models\Setting::imageUrl($settings['hero_image'] ?? 'uploads/site/Networking-Solutions-India.webp', 'hero_image') }}" class="img-fluid" alt="Software AI Smart Technology Solutions">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--============ Hero Banner End ============-->

    <!--====================  Brand Logo Slider ====================-->
    <x-public.brands :brands="$brands" />
    <!--====================  End of Brand Logo Slider ====================-->

    <!--=========== Trusted Technology Partner Stats Start ==========-->
    <div class="fun-fact-wrapper bg-theme-default section-space--pb_60 section-space--pt_60">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb-30">
                    <h6 class="section-sub-title text-white">Trusted Technology Partner</h6>
                </div>
            </div>
            <div class="row">
                @foreach ($stats as $stat)
                <div class="col-md-3 col-sm-6 wow move-up">
                    <div class="fun-fact--two text-center">
                        <div class="fun-fact__count @if (is_numeric($stat->value)) counter @endif">{{ $stat->value }}</div>
                        <h6 class="fun-fact__text">{{ $stat->label }}</h6>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!--=========== Trusted Technology Partner Stats End ==========-->

    <!--===========  Our Capabilities Start =============-->
    <div class="capabilities-wrapper bg-gray section-space--ptb_60">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-left section-space--mb_40">
                        <h6 class="section-sub-title text-color-primary mb-15">Our Expertise</h6>
                        <h3 class="heading">End-to-End Solutions to Drive Your <span class="text-color-primary">Business Forward</span></h3>
                        <p class="capabilities-subtitle mt-15">We deliver innovative, reliable, and scalable solutions across industries to help you stay ahead.</p>
                    </div>
                </div>
            </div>
            @php
                $capColors = ['#6D5BD0', '#3B82F6', '#0EA5E9', '#EB0B52', '#F43F5E', '#8B5CF6'];
            @endphp
            <div class="row capabilities-row">
                @foreach ($capabilities as $capability)
                <div class="col-lg-4 col-md-6 wow move-up d-flex">
                    <div class="capability-card" style="--cap-color: {{ $capColors[$loop->index % count($capColors)] }};">
                        <div class="capability-card__head">
                            <div class="capability-card__icon">
                                <img src="{{ asset('uploads/'.$capability->icon) }}" alt="{{ $capability->title }}" loading="lazy">
                            </div>
                            <div class="capability-card__heading-group">
                                <h5 class="capability-card__title">{{ $capability->title }}</h5>
                                <span class="capability-card__line"></span>
                            </div>
                            <button type="button" class="capability-card__toggle" aria-label="Toggle details">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div class="capability-card__body">
                            <p class="capability-card__text">{{ $capability->short_description }}</p>
                            <a href="{{ route('capabilities.show', $capability->slug) }}" class="capability-card__link">Explore Solutions <i class="fas fa-arrow-right ml-1"></i></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="button-group-wrap text-center mt-40">
                <a href="{{ route('capabilities.index') }}" class="btn">View All Capabilities</a>
            </div>
        </div>
    </div>
    <!--===========  Our Capabilities End =============-->

    <!--===========  Why Choose Tectignis Start =============-->
    <div class="why-choose-wrapper section-space--ptb_80">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <span class="why-choose-badge">Why Choose Us</span>
                        <h3 class="heading mt-15">Why Choose <span class="text-color-primary">Tectignis?</span></h3>
                        <p class="why-choose-subtitle mt-15">We combine expertise, technology, and a customer-first approach to deliver solutions that drive real results for your business.</p>
                    </div>
                </div>
            </div>

            <div class="row align-items-center why-choose-main">
                <div class="col-lg-5">
                    <div class="why-choose-visual">
                        <div class="why-choose-visual__image">
                            <img class="img-fluid" src="{{ \App\Models\Setting::imageUrl($settings['what_we_offer_image'] ?? 'IT-Services-in-Nav-Mumbai.webp', 'what_we_offer_image') }}" alt="Why Choose Tectignis IT Solutions" loading="lazy">
                        </div>
                        <div class="why-choose-card">
                            <span class="why-choose-card__dots" aria-hidden="true"></span>
                            <span class="why-choose-card__watermark" aria-hidden="true">G</span>
                            <h4 class="why-choose-card__title">Why Choose Tectignis</h4>
                            <p class="why-choose-card__text">Empowering businesses with innovative IT solutions, delivered with trust, transparency, and excellence.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 mt-5 mt-lg-0">
                    @php
                        $whyChooseFeatures = [
                            ['icon' => 'fas fa-globe', 'title' => 'Global Delivery Model', 'text' => 'We leverage a global talent pool to deliver round-the-clock progress on your projects.'],
                            ['icon' => 'fas fa-coins', 'title' => 'Cost-Effective Solutions', 'text' => 'Enterprise-grade quality at competitive pricing, without compromising on standards.'],
                            ['icon' => 'fas fa-tasks', 'title' => 'Dedicated Project Management', 'text' => 'A dedicated manager keeps every milestone on track from kickoff to delivery.'],
                            ['icon' => 'fas fa-expand-arrows-alt', 'title' => 'Scalable Resources', 'text' => 'Scale your team up or down on demand to match evolving project needs.'],
                            ['icon' => 'fas fa-vial', 'title' => 'Quality Assurance & Testing', 'text' => 'Rigorous QA and testing at every stage to ensure reliable, bug-free releases.'],
                            ['icon' => 'fas fa-clock', 'title' => 'On-Time Delivery', 'text' => 'Disciplined planning and execution that consistently meets agreed deadlines.'],
                            ['icon' => 'fas fa-headset', 'title' => 'Single Point of Contact', 'text' => 'One reliable point of contact for clear, streamlined communication.'],
                            ['icon' => 'fas fa-layer-group', 'title' => 'Multi-Domain Expertise', 'text' => 'Cross-industry experience that brings proven best practices to your domain.'],
                        ];
                    @endphp
                    <div class="why-choose-features">
                        @foreach ($whyChooseFeatures as $feature)
                        <div class="why-choose-feature">
                            <div class="why-choose-feature__icon"><i class="{{ $feature['icon'] }}"></i></div>
                            <div class="why-choose-feature__content">
                                <h6 class="why-choose-feature__title">{{ $feature['title'] }}</h6>
                                <p class="why-choose-feature__text">{{ $feature['text'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if ($stats->isNotEmpty())
            @php
                $statIcons = ['fas fa-briefcase', 'fas fa-smile', 'fas fa-award', 'fas fa-headset'];
            @endphp
            <div class="why-choose-stats">
                @foreach ($stats->take(4) as $stat)
                <div class="why-choose-stat">
                    <div class="why-choose-stat__icon"><i class="{{ $statIcons[$loop->index % count($statIcons)] }}"></i></div>
                    <div class="why-choose-stat__content">
                        <div class="why-choose-stat__number @if (is_numeric($stat->value)) counter @endif">{{ $stat->value }}</div>
                        <div class="why-choose-stat__label">{{ $stat->label }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <div class="button-group-wrap text-center mt-40">
                <a href="{{ route('about') }}" class="btn">About Us</a>
            </div>
        </div>
    </div>
    <!--===========  Why Choose Tectignis End =============-->

    <!--===========  Solutions We Deliver Start =============-->
    <div class="solutions-deliver-wrapper bg-gray section-space--ptb_80">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <span class="solutions-deliver-overline">/// Solutions We Deliver ///</span>
                        <h3 class="heading mt-15">Business-focused Solutions Built for <span class="text-color-primary">Impact</span></h3>
                        <p class="solutions-deliver-subtitle mt-15">We deliver end-to-end IT solutions tailored to your business needs, designed to drive efficiency, security, and growth.</p>
                    </div>
                </div>
            </div>
            <div class="row solutions-deliver-row">
                @foreach ($solutions as $solution)
                <div class="col-12 col-md-6 col-lg-3 wow move-up section-space--mt_30 d-flex">
                    <a href="{{ route('solutions.show', $solution->slug) }}" class="solution-deliver-card w-100" aria-label="Learn more about our {{ $solution->title }}">
                        <span class="solution-deliver-card__icon" aria-hidden="true">
                            <i class="{{ $solution->icon }}"></i>
                        </span>
                        <h6 class="solution-deliver-card__title">{{ $solution->title }}</h6>
                        <span class="solution-deliver-card__divider" aria-hidden="true"></span>
                        @if ($solution->short_description)
                        <p class="solution-deliver-card__text">{{ $solution->short_description }}</p>
                        @endif
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!--===========  Solutions We Deliver End =============-->

    <!--===========  Industries We Serve Start =============-->
    <section class="industries-serve-section section-space--ptb_80">
        {{-- Hidden SVG gradient definition reused by all card icons --}}
        <svg aria-hidden="true" focusable="false" style="position:absolute;width:0;height:0;overflow:hidden">
            <defs>
                <linearGradient id="indGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#E5185D"/>
                    <stop offset="100%" stop-color="#5B21B6"/>
                </linearGradient>
            </defs>
        </svg>

        <div class="container">
            {{-- Section header --}}
            <div class="industries-serve-header">
                <div class="industries-serve-pretitle">
                    <span class="industries-serve-pretitle__line"></span>
                    <span class="industries-serve-pretitle__dot"></span>
                    <span>INDUSTRIES WE SERVE</span>
                    <span class="industries-serve-pretitle__dot"></span>
                    <span class="industries-serve-pretitle__line"></span>
                </div>
                <h2 class="industries-serve-heading">Empowering Every Industry</h2>
                <h2 class="industries-serve-heading industries-serve-heading--gradient">with Intelligent Solutions</h2>
                <p class="industries-serve-subtitle">We deliver tailored IT solutions to help businesses across industries innovate, optimize operations, and achieve sustainable growth.</p>
            </div>

            {{-- Industry card grid --}}
            @php
                $industryIconPaths = [
                    'Manufacturing'              => '<path d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28ZM15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>',
                    'Healthcare'                 => '<path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>',
                    'Education'                  => '<path d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904a48.627 48.627 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>',
                    'Retail'                     => '<path d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>',
                    'Real Estate'                => '<path d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>',
                    'Logistics'                  => '<path d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>',
                    'Hospitality'                => '<path d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/>',
                    'Corporate Offices'          => '<path d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006-3.75 3.75m-15.75 0a2.18 2.18 0 0 1-.75-1.661V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"/>',
                    'Finance & Banking'          => '<path d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 10a48.36 48.36 0 0 0-9.5.332V21m13.5 0H18m-9 0H9m12 0a9 9 0 0 1-18 0"/>',
                    'E-commerce'                 => '<path d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>',
                    'Government'                 => '<path d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 10a48.36 48.36 0 0 0-9.5.332V21m13.5 0H18m-9 0H9m12 0a9 9 0 0 1-18 0"/><path d="M3.75 21h16.5M4.5 3h15"/>',
                    'Startups'                   => '<path d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>',
                    'Travel & Tourism'           => '<path d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>',
                    'Insurance'                  => '<path d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>',
                    'Energy & Utilities'         => '<path d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>',
                    'Telecom'                    => '<path d="M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0Z"/>',
                    'Agriculture'                => '<path d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>',
                    'Media & Entertainment'      => '<path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z"/>',
                    'IT & ITES'                  => '<path d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0H3"/>',
                    'EdTech'                     => '<path d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>',
                    'Non-Profit Organizations'   => '<path d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>',
                    'Pharmaceuticals'            => '<path d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L4.2 13.9m15.6 1.4-1.457 4.867A2.25 2.25 0 0 1 16.17 21.75H7.83a2.25 2.25 0 0 1-2.173-1.483L4.2 15.3M4.2 15.3l-.428-1.168A2.25 2.25 0 0 1 5.857 12h12.286a2.25 2.25 0 0 1 2.085 2.132L19.8 15.3"/>',
                    'Legal Services'             => '<path d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.97Zm-12.5 0L8.871 15.696c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L6.25 4.97Z"/>',
                    'Marine & Shipping'          => '<path d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/>',
                ];
                $defaultIconPath = '<path d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/>';
            @endphp

            <div class="industries-serve-grid">
                @foreach ($industries as $industry)
                <a href="{{ route('industries.show', $industry->slug) }}" class="industry-serve-card" aria-label="{{ $industry->name }}">
                    <div class="industry-serve-card__blob">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                             stroke="url(#indGrad)" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            {!! $industryIconPaths[$industry->name] ?? $defaultIconPath !!}
                        </svg>
                    </div>
                    <span class="industry-serve-card__label">{{ $industry->name }}</span>
                </a>
                @endforeach
            </div>

            {{-- Summary banner --}}
            <div class="industries-serve-banner">
                <div class="industries-serve-banner__item">
                    <div class="industries-serve-banner__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="industries-serve-banner__title">15+</div>
                        <div class="industries-serve-banner__subtitle">Industries Served</div>
                    </div>
                </div>
                <div class="industries-serve-banner__item">
                    <div class="industries-serve-banner__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M21.721 12.752a9.711 9.711 0 0 0-.945-5.003 12.754 12.754 0 0 1-4.339 2.708 18.991 18.991 0 0 1-.214 4.772 17.165 17.165 0 0 0 5.498-2.477ZM14.634 15.55a17.324 17.324 0 0 0 .332-4.647c-.952.227-1.945.347-2.966.347-1.021 0-2.014-.12-2.966-.347a17.515 17.515 0 0 0 .332 4.647 17.385 17.385 0 0 0 5.268 0ZM9.772 17.119a18.963 18.963 0 0 0 4.456 0A17.182 17.182 0 0 1 12 21.724a17.18 17.18 0 0 1-2.228-4.605ZM7.777 15.23a18.87 18.87 0 0 1-.214-4.774 12.753 12.753 0 0 1-4.34-2.708 9.711 9.711 0 0 0-.944 5.004 17.165 17.165 0 0 0 5.498 2.477ZM21.356 14.752a9.765 9.765 0 0 1-7.478 6.817 18.64 18.64 0 0 0 1.988-4.718 18.627 18.627 0 0 0 5.49-2.098ZM2.644 14.752c1.682.971 3.53 1.688 5.49 2.099a18.64 18.64 0 0 0 1.988 4.718 9.765 9.765 0 0 1-7.478-6.816ZM13.878 2.43a9.755 9.755 0 0 1 6.116 3.986 11.267 11.267 0 0 1-3.746 2.504 18.63 18.63 0 0 0-2.37-6.49ZM12 2.276a17.152 17.152 0 0 1 2.805 7.121c-.897.23-1.837.353-2.805.353-.968 0-1.908-.122-2.805-.353A17.151 17.151 0 0 1 12 2.276ZM10.122 2.43a18.629 18.629 0 0 0-2.37 6.49 11.266 11.266 0 0 1-3.746-2.504 9.754 9.754 0 0 1 6.116-3.985Z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="industries-serve-banner__title">Global Reach</div>
                        <div class="industries-serve-banner__subtitle">Solutions with Worldwide Impact</div>
                    </div>
                </div>
                <div class="industries-serve-banner__item">
                    <div class="industries-serve-banner__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.705-3.078Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <div class="industries-serve-banner__title">Domain Expertise</div>
                        <div class="industries-serve-banner__subtitle">Deep Industry Knowledge</div>
                    </div>
                </div>
                <div class="industries-serve-banner__item">
                    <div class="industries-serve-banner__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="industries-serve-banner__title">Business Impact</div>
                        <div class="industries-serve-banner__subtitle">Delivering Measurable Results</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--===========  Industries We Serve End =============-->

    <!--=========== Featured Services Start ===========-->
    <section class="fs-section section-space--ptb_80">
        <div class="container">
            <div class="fs-header">
                <p class="fs-pretitle">
                    <span aria-hidden="true">←</span>
                    FEATURED SERVICES
                    <span aria-hidden="true">→</span>
                </p>
                <h2 class="fs-title">Our Most In-Demand Services</h2>
                <p class="fs-subtitle">Powering businesses with innovative, secure, and scalable solutions tailored to meet today's digital challenges.</p>
            </div>

            @php
                $fsThemes = [
                    ['color' => '#8B5CF6', 'bg' => '#F5F3FF'],
                    ['color' => '#F43F5E', 'bg' => '#FFF1F2'],
                    ['color' => '#8B5CF6', 'bg' => '#F5F3FF'],
                    ['color' => '#F43F5E', 'bg' => '#FFF1F2'],
                    ['color' => '#8B5CF6', 'bg' => '#F5F3FF'],
                    ['color' => '#3B82F6', 'bg' => '#EFF6FF'],
                    ['color' => '#F43F5E', 'bg' => '#FFF1F2'],
                    ['color' => '#10B981', 'bg' => '#ECFDF5'],
                ];
            @endphp
            <div class="fs-grid">
                @foreach ($services->take(6) as $service)
                @php $fsTheme = $fsThemes[$loop->index % count($fsThemes)]; @endphp
                <a href="{{ route('services.show', $service->slug) }}"
                   class="fs-card"
                   style="--fs-color: {{ $fsTheme['color'] }}; --fs-color-bg: {{ $fsTheme['bg'] }};"
                   aria-label="{{ $service->title }}">
                    <div class="fs-card__icon" aria-hidden="true">
                        <img src="{{ asset('uploads/'.$service->icon) }}" alt="{{ $service->title }}" loading="lazy">
                    </div>
                    <div class="fs-card__body">
                        <h6 class="fs-card__title">{{ $service->title }}</h6>
                        @if ($service->short_description)
                        <p class="fs-card__desc">{{ Str::limit($service->short_description, 80) }}</p>
                        @endif
                    </div>
                    <span class="fs-card__arrow" aria-hidden="true">→</span>
                </a>
                @endforeach
            </div>

            <div class="button-group-wrap text-center mt-40">
                <a href="{{ route('services.index') }}" class="btn">View All Services</a>
            </div>
        </div>
    </section>
    <!--=========== Featured Services End ===========-->

    <!--=========== Technology Stack Start ===========-->
    <div class="section-space--ptb_60">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">Tools &amp; Platforms</h6>
                        <h3 class="heading">Technology <span class="text-color-primary">Stack</span></h3>
                    </div>
                </div>
            </div>
            <div class="row tech-stack-row">
                @foreach ($techStacks as $tech)
                <div class="col-lg-2 col-md-3 col-4 wow move-up">
                    <div class="tech-stack-card">
                        <div class="tech-stack-card__logo">
                            @if ($tech->logo)
                                <img src="{{ asset('uploads/'.$tech->logo) }}" alt="{{ $tech->name }}" loading="lazy">
                            @endif
                        </div>
                        <p class="tech-stack-card__name">{{ $tech->name }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!--=========== Technology Stack End ===========-->

    <!--=========== Case Studies Start =============-->
    <section class="cs-section section-space--ptb_80">
        <div class="container">
            <div class="cs-header">
                <span class="cs-header__badge">CASE STUDIES</span>
                <h2 class="cs-header__title">Real Stories. Real Impact.</h2>
                <p class="cs-header__subtitle">Explore how our solutions have helped businesses overcome challenges, improve operations, and achieve measurable results.</p>
            </div>

            @php
                $caseStudyCards = [
                    [
                        'theme'    => 'blue',
                        'badge'    => 'ERP SOLUTION',
                        'title'    => 'ERP for Manufacturing',
                        'desc'     => 'Streamlined operations and improved productivity for a leading manufacturing company.',
                        'features' => [
                            '40% increase in operational efficiency',
                            'Real-time data visibility across departments',
                            'Reduced manual processes by 60%',
                        ],
                        'icon'     => 'M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0H3',
                    ],
                    [
                        'theme'    => 'red',
                        'badge'    => 'MOBILE SOLUTION',
                        'title'    => 'Mobile Application',
                        'desc'     => 'Delivered a scalable and user-friendly app that enhanced customer engagement.',
                        'features' => [
                            '3x increase in user engagement',
                            'Seamless experience across platforms',
                            'Improved customer satisfaction by 45%',
                        ],
                        'icon'     => 'M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3',
                    ],
                    [
                        'theme'    => 'purple',
                        'badge'    => 'CLOUD SOLUTION',
                        'title'    => 'Cloud Migration',
                        'desc'     => 'Migrated infrastructure to the cloud for better performance, security, and scalability.',
                        'features' => [
                            '99.9% system uptime achieved',
                            'Enhanced security and data protection',
                            '30% reduction in IT infrastructure cost',
                        ],
                        'icon'     => 'M2.25 15a4.5 4.5 0 0 0 4.5 4.5H18a3.75 3.75 0 0 0 1.332-7.257 3 3 0 0 0-3.758-3.848 5.25 5.25 0 0 0-10.233 2.33A4.502 4.502 0 0 0 2.25 15Z',
                    ],
                ];
            @endphp

            <div class="cs-grid">
                @foreach ($caseStudyCards as $card)
                <div class="cs-card cs-card--{{ $card['theme'] }}">
                    <div class="cs-card__illus">
                        <div class="cs-card__illus-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="{{ $card['icon'] }}"/>
                            </svg>
                        </div>
                        <div class="cs-card__illus-main">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="{{ $card['icon'] }}"/>
                            </svg>
                        </div>
                    </div>
                    <div class="cs-card__body">
                        <span class="cs-card__badge">{{ $card['badge'] }}</span>
                        <h3 class="cs-card__title">{{ $card['title'] }}</h3>
                        <p class="cs-card__desc">{{ $card['desc'] }}</p>
                        <hr class="cs-card__divider">
                        <ul class="cs-card__features">
                            @foreach ($card['features'] as $feature)
                            <li class="cs-card__feature">
                                <span class="cs-card__check" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="m9 12 2 2 4-4"/>
                                    </svg>
                                </span>
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('case-studies.index') }}" class="cs-card__link">Read Case Study <span aria-hidden="true">→</span></a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="cs-footer">
                <a href="{{ route('case-studies.index') }}" class="cs-cta-btn">View All Case Studies →</a>
            </div>
        </div>
    </section>
    <!--=========== Case Studies End =============-->

    <!--====================  Testimonials ====================-->
    <x-public.testimonials :testimonials="$testimonials" />
    <!--====================  End of Testimonials ====================-->

    <!--=========== Global Presence Start ===========-->
    @php
        $gpIndiaLocations = [
            ['city' => 'Navi Mumbai', 'type' => 'Head Office'],
            ['city' => 'Mumbai', 'type' => 'Corporate Office'],
            ['city' => 'Thane', 'type' => 'Regional Office'],
            ['city' => 'Pune', 'type' => 'Development Center'],
            ['city' => 'Panvel', 'type' => 'Operations Center'],
            ['city' => 'Maharashtra', 'type' => 'Wide Service Network'],
            ['city' => 'India', 'type' => 'Nationwide Delivery'],
        ];
        $gpGlobalLocations = [
            ['city' => 'UAE', 'type' => 'Middle East Operations'],
            ['city' => 'Saudi Arabia', 'type' => 'Regional Delivery Partner'],
            ['city' => 'UK', 'type' => 'European Operations'],
            ['city' => 'USA', 'type' => 'North America Operations'],
            ['city' => 'Canada', 'type' => 'North America Partner'],
            ['city' => 'Australia', 'type' => 'Asia Pacific Operations'],
            ['city' => 'Singapore', 'type' => 'Southeast Asia Partner'],
        ];
        $gpAdvantages = [
            ['title' => 'Global Reach', 'desc' => 'Presence in 25+ countries', 'tone' => 'rose',
             'icon' => '<path d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/>'],
            ['title' => 'Round the Clock Support', 'desc' => '24/7 assistance across time zones', 'tone' => 'indigo',
             'icon' => '<path d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>'],
            ['title' => 'Local Expertise, Global Standards', 'desc' => 'Delivering excellence worldwide', 'tone' => 'rose',
             'icon' => '<path d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>'],
            ['title' => 'Secure &amp; Compliant', 'desc' => 'Following global security standards', 'tone' => 'indigo',
             'icon' => '<path d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>'],
            ['title' => 'Agile Delivery', 'desc' => 'Faster solutions, better results', 'tone' => 'rose',
             'icon' => '<path d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>'],
        ];
        $gpSteps = [
            ['title' => 'Understand', 'desc' => 'We understand your business challenges',
             'icon' => '<path d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>'],
            ['title' => 'Strategize', 'desc' => 'We design the right solution roadmap',
             'icon' => '<path d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>'],
            ['title' => 'Develop', 'desc' => 'We build with agility and innovation',
             'icon' => '<path d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/>'],
            ['title' => 'Deliver', 'desc' => 'We deliver quality, always on-time',
             'icon' => '<path d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>'],
            ['title' => 'Grow Together', 'desc' => 'We help you scale and achieve more',
             'icon' => '<path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>'],
        ];
    @endphp

    <section class="gp-section section-space--ptb_80">
        <svg aria-hidden="true" focusable="false" style="position:absolute;width:0;height:0">
            <defs>
                <pattern id="gpDots" x="0" y="0" width="14" height="14" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.6" fill="#D8DEE9"/>
                </pattern>
            </defs>
        </svg>

        <div class="container">
            {{-- Header --}}
            <div class="gp-header">
                <span class="gp-pretitle">Global Presence</span>
                <h2 class="gp-title">Serving Clients Worldwide, Delivering Excellence <span class="gp-title__accent">Everywhere.</span></h2>
                <p class="gp-subtitle">We combine local expertise with a global mindset to deliver innovative IT solutions that help your business grow, scale and succeed.</p>
            </div>

            {{-- Map + locations --}}
            <div class="gp-map-section">
                {{-- Left sidebar: India --}}
                <aside class="gp-sidebar gp-sidebar--india">
                    <span class="gp-badge gp-badge--india">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5"/>
                        </svg>
                        India Presence
                    </span>
                    <ul class="gp-list gp-list--india">
                        @foreach ($gpIndiaLocations as $loc)
                        <li class="gp-list__item">
                            <span class="gp-list__pin" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <span class="gp-list__body">
                                <span class="gp-list__city">{{ $loc['city'] }}</span>
                                <span class="gp-list__type">{{ $loc['type'] }}</span>
                            </span>
                        </li>
                        @endforeach
                    </ul>
                    <div class="gp-stat gp-stat--india">
                        <span class="gp-stat__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                            </svg>
                        </span>
                        <span class="gp-stat__body">
                            <span class="gp-stat__num">100+</span>
                            <span class="gp-stat__label">Projects Across India</span>
                        </span>
                    </div>
                </aside>

                {{-- Center: dotted world map with India hub + arcs --}}
                <div class="gp-map" role="img" aria-label="World map highlighting Tectignis delivery from India to clients across UAE, Saudi Arabia, UK, USA, Canada, Australia and Singapore.">
                    <svg class="gp-map__svg" viewBox="0 0 1000 460" preserveAspectRatio="xMidYMid meet">
                        {{-- Continents (dotted) --}}
                        <g fill="url(#gpDots)">
                            <path d="M120,90 C100,110 90,150 110,180 C130,210 180,232 212,212 C244,196 252,158 235,128 C218,98 160,75 120,90 Z"/>
                            <path d="M250,255 C238,288 250,342 282,372 C302,392 322,382 322,350 C326,308 305,272 286,256 C271,246 258,246 250,255 Z"/>
                            <path d="M428,118 C413,128 413,156 435,166 C462,177 492,166 497,144 C502,126 480,114 460,114 C448,113 436,114 428,118 Z"/>
                            <path d="M452,198 C436,214 442,266 472,302 C492,322 512,316 517,286 C524,250 506,214 491,199 C476,187 462,188 452,198 Z"/>
                            <path d="M520,108 C562,92 662,98 742,128 C802,150 822,182 792,202 C742,228 650,222 590,212 C540,202 508,170 508,144 C508,128 514,116 520,108 Z"/>
                            <path d="M742,328 C726,344 732,376 766,386 C796,394 822,378 820,355 C817,334 792,321 766,321 C755,321 748,323 742,328 Z"/>
                        </g>

                        {{-- India highlight --}}
                        <path class="gp-map__india" d="M548,212 C562,206 578,216 575,232 C572,250 560,266 552,252 C545,240 537,222 548,212 Z"/>

                        {{-- Connection arcs from India hub --}}
                        <g class="gp-arcs" fill="none">
                            <path class="gp-arc" d="M560,236 Q495,150 430,158"/>
                            <path class="gp-arc" d="M560,236 Q360,118 182,198"/>
                            <path class="gp-arc" d="M560,236 Q360,78 182,150"/>
                            <path class="gp-arc" d="M560,236 Q528,198 500,236"/>
                            <path class="gp-arc" d="M560,236 Q512,206 470,248"/>
                            <path class="gp-arc" d="M560,236 Q702,298 782,358"/>
                            <path class="gp-arc" d="M560,236 Q622,250 662,300"/>
                        </g>

                        {{-- Global destination nodes --}}
                        <g class="gp-nodes">
                            <g class="gp-node gp-node--global" transform="translate(430,158)"><circle class="gp-node__ring" r="9"/><circle class="gp-node__dot" r="3.5"/></g>
                            <g class="gp-node gp-node--global" transform="translate(182,198)"><circle class="gp-node__ring" r="9"/><circle class="gp-node__dot" r="3.5"/></g>
                            <g class="gp-node gp-node--global" transform="translate(182,150)"><circle class="gp-node__ring" r="9"/><circle class="gp-node__dot" r="3.5"/></g>
                            <g class="gp-node gp-node--global" transform="translate(500,236)"><circle class="gp-node__ring" r="9"/><circle class="gp-node__dot" r="3.5"/></g>
                            <g class="gp-node gp-node--global" transform="translate(470,248)"><circle class="gp-node__ring" r="9"/><circle class="gp-node__dot" r="3.5"/></g>
                            <g class="gp-node gp-node--global" transform="translate(782,358)"><circle class="gp-node__ring" r="9"/><circle class="gp-node__dot" r="3.5"/></g>
                            <g class="gp-node gp-node--global" transform="translate(662,300)"><circle class="gp-node__ring" r="9"/><circle class="gp-node__dot" r="3.5"/></g>
                        </g>

                        {{-- India hub --}}
                        <g class="gp-node gp-node--hub" transform="translate(560,236)">
                            <circle class="gp-node__ring" r="13"/>
                            <circle class="gp-node__dot" r="5"/>
                        </g>
                    </svg>
                </div>

                {{-- Right sidebar: Global --}}
                <aside class="gp-sidebar gp-sidebar--global">
                    <span class="gp-badge gp-badge--global">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/>
                        </svg>
                        Global Delivery
                    </span>
                    <ul class="gp-list gp-list--global">
                        @foreach ($gpGlobalLocations as $loc)
                        <li class="gp-list__item">
                            <span class="gp-list__pin" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    <path d="M19.5 9.75c0 7.142-7.5 11.25-7.5 11.25S4.5 16.892 4.5 9.75a7.5 7.5 0 1 1 15 0Z"/>
                                </svg>
                            </span>
                            <span class="gp-list__body">
                                <span class="gp-list__city">{{ $loc['city'] }}</span>
                                <span class="gp-list__type">{{ $loc['type'] }}</span>
                            </span>
                        </li>
                        @endforeach
                    </ul>
                    <div class="gp-stat gp-stat--global">
                        <span class="gp-stat__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/>
                            </svg>
                        </span>
                        <span class="gp-stat__body">
                            <span class="gp-stat__num">25+</span>
                            <span class="gp-stat__label">Countries Served</span>
                        </span>
                    </div>
                </aside>
            </div>

            {{-- Our Global Advantage banner --}}
            <div class="gp-advantages">
                <h3 class="gp-block-title">Our Global Advantage</h3>
                <div class="gp-advantages__grid">
                    @foreach ($gpAdvantages as $adv)
                    <div class="gp-advantage">
                        <span class="gp-advantage__icon gp-advantage__icon--{{ $adv['tone'] }}" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                {!! $adv['icon'] !!}
                            </svg>
                        </span>
                        <div class="gp-advantage__body">
                            <h4 class="gp-advantage__title">{!! $adv['title'] !!}</h4>
                            <p class="gp-advantage__desc">{{ $adv['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Connecting Opportunities process stepper --}}
            <div class="gp-process">
                <h3 class="gp-block-title">Connecting Opportunities. Delivering Impact.</h3>
                <div class="gp-process__track">
                    <span class="gp-process__line" aria-hidden="true"></span>
                    @foreach ($gpSteps as $step)
                    <div class="gp-step">
                        <span class="gp-step__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                {!! $step['icon'] !!}
                            </svg>
                        </span>
                        <h4 class="gp-step__title">{{ $step['title'] }}</h4>
                        <p class="gp-step__desc">{{ $step['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!--=========== Global Presence End ===========-->

    <!--=========== Resources & Insights Start ===========-->
    @if ($recentPosts->isNotEmpty())
    <div class="feature-images-wrapper bg-gray section-space--ptb_60">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">Stay Informed</h6>
                        <h3 class="heading">Resources &amp; <span class="text-color-primary">Insights</span></h3>
                    </div>
                </div>
            </div>
            <div class="row row--30">
                @foreach ($recentPosts->take(3) as $post)
                <div class="col-lg-4 col-md-6 wow move-up section-space--mt_30">
                    <div class="blog-wrap-layout3">
                        <div class="content">
                            @if ($post->category)
                            <div class="post-meta mb-10">
                                <span class="text-color-primary">{{ $post->category }}</span>
                            </div>
                            @endif
                            <h5 class="heading">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h5>
                            <p class="text mt-10">{{ Str::limit($post->excerpt ?? $post->short_description ?? '', 120) }}</p>
                            <div class="post-meta mt-20">
                                <a href="{{ route('blog.show', $post->slug) }}" class="button-text">Read More <i class="fas fa-arrow-right ml-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="button-group-wrap text-center mt-40">
                <a href="{{ route('blog.index') }}" class="btn">View All Posts</a>
            </div>
        </div>
    </div>
    @endif
    <!--=========== Resources & Insights End ===========-->

    <!--====================  Final CTA Start ====================-->
    <div class="contact-us-section-wrappaer infotechno-contact-us-bg section-space--ptb_80">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 col-md-6">
                    <div class="conact-us-wrap-one">
                        <h3 class="heading">Ready to <span class="text-color-primary">Transform</span> Your Business?</h3>
                        <div class="sub-heading mt-20">Let's discuss your software, AI, cloud, cybersecurity, or infrastructure requirements.</div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6 mt-30 mt-md-0">
                    <div class="contact-info-one text-center">
                        <div class="contact-us-button d-flex flex-wrap justify-content-center gap-3">
                            <a href="{{ route('contact') }}" class="ht-btn ht-btn-md">Request Consultation</a>
                            <a href="{{ route('contact') }}" class="ht-btn ht-btn-md ht-btn--outline">Get a Quote</a>
                        </div>
                        <div class="mt-20">
                            <h2 class="call-us"><a href="tel:+919987705688">+91 9987705688</a></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====================  Final CTA End ====================-->

    <!--====================  Contact Section Start ====================-->
    <div class="contact-form-section section-space--ptb_60">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 wow move-up">
                    <div class="section-title-wrap text-left section-space--mb_30">
                        <h6 class="section-sub-title mb-20">Get In Touch</h6>
                        <h3 class="heading">Tectignis IT Solutions <span class="text-color-primary">Pvt. Ltd.</span></h3>
                    </div>
                    <ul class="list-unstyled">
                        <li class="mb-15"><i class="fas fa-map-marker-alt text-color-primary me-3"></i>Navi Mumbai, Maharashtra, India</li>
                        <li class="mb-15"><i class="fas fa-phone text-color-primary me-3"></i><a href="tel:+919987705688">+91 9987705688</a></li>
                        <li class="mb-15"><i class="fas fa-envelope text-color-primary me-3"></i><a href="mailto:info@tectignis.in">info@tectignis.in</a></li>
                    </ul>
                </div>
                <div class="col-lg-6 wow move-up">
                    <div class="contact-form">
                        <form action="{{ route('contact.submit') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-20">
                                    <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <input type="tel" name="phone" class="form-control" placeholder="Phone Number">
                                </div>
                                <div class="col-md-6 mb-20">
                                    <input type="text" name="subject" class="form-control" placeholder="Service Required">
                                </div>
                                <div class="col-12 mb-20">
                                    <textarea name="message" class="form-control" rows="4" placeholder="Your Message" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="ht-btn ht-btn-md">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====================  Contact Section End ====================-->

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.capabilities-row .capability-card__head').forEach(function (head) {
        head.addEventListener('click', function (event) {
            if (event.target.closest('.capability-card__link')) {
                return;
            }
            head.closest('.capability-card').classList.toggle('is-open');
        });
    });
</script>
@endpush
