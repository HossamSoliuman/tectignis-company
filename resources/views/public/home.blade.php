@extends('layouts.public')

@section('title', $settings['home_meta_title'] ?? 'Software, AI & IT Solutions Company | Navi Mumbai, Mumbai, Pune, India | Tectignis')

@section('seo')
    @php
        $homeMetaDesc = $settings['home_meta_description'] ?? 'Tectignis IT Solutions – Custom Software Development, AI Automation, Cloud Infrastructure, Cybersecurity & Smart Security Systems.';
        $homeMetaKeywords = $settings['home_meta_keywords'] ?? null;
        $homeOgTitle = $settings['home_og_title'] ?? ($settings['home_meta_title'] ?? null);
        $homeOgDesc = $settings['home_og_description'] ?? $homeMetaDesc;
        $homeOgImage = ($settings['home_og_image'] ?? null)
            ? \App\Models\Setting::imageUrl($settings['home_og_image'], 'home_og_image')
            : null;
    @endphp
    <meta name="description" content="{{ $homeMetaDesc }}">
    @if ($homeMetaKeywords)
        <meta name="keywords" content="{{ $homeMetaKeywords }}">
    @endif
    <meta property="og:title" content="{{ $homeOgTitle }}">
    <meta property="og:description" content="{{ $homeOgDesc }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    @if ($homeOgImage)
        <meta property="og:image" content="{{ $homeOgImage }}">
    @endif
    <link rel="canonical" href="{{ url('/') }}">
@endsection

@section('content')

    <!--============ Hero Banner Start ============-->
    <div class="home-hero">
        <span class="home-hero__bg" aria-hidden="true"></span>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="home-hero__content wow move-up">
                        <span class="home-hero__overline">
                            <span class="home-hero__overline-line" aria-hidden="true"></span>
                            {{ $settings['hero_sub_heading'] ?? 'Serving Clients Across Navi Mumbai, Mumbai, Thane, Pune, India & Worldwide.' }}
                        </span>
                        <h1 class="home-hero__title">
                            {{ $settings['hero_heading_line1'] ?? 'Transforming Businesses Through' }}
                            <span class="home-hero__title-grad">{{ $settings['hero_heading_line2'] ?? 'Software, AI & Smart Technology Solutions' }}</span>
                        </h1>
                        <p class="home-hero__desc">{{ $settings['hero_info_heading'] ?? 'Custom Software Development, AI Automation, Cloud Infrastructure, Cybersecurity & Smart Security Systems.' }}</p>

                        <div class="home-hero__features">
                            <div class="home-hero__feature">
                                <span class="home-hero__feature-icon" aria-hidden="true"><i class="fas fa-lightbulb"></i></span>
                                <span class="home-hero__feature-text">
                                    <span class="home-hero__feature-title">Custom Software Development</span>
                                    <span class="home-hero__feature-sub">Tailored solutions for startups, SMEs & enterprises</span>
                                </span>
                            </div>
                            <div class="home-hero__feature">
                                <span class="home-hero__feature-icon" aria-hidden="true"><i class="fas fa-shield-alt"></i></span>
                                <span class="home-hero__feature-text">
                                    <span class="home-hero__feature-title">AI &amp; Business Automation</span>
                                    <span class="home-hero__feature-sub">Reduce manual work and improve productivity</span>
                                </span>
                            </div>
                            <div class="home-hero__feature">
                                <span class="home-hero__feature-icon" aria-hidden="true"><i class="fas fa-headset"></i></span>
                                <span class="home-hero__feature-text">
                                    <span class="home-hero__feature-title">Cloud &amp; Cybersecurity</span>
                                    <span class="home-hero__feature-sub">Secure, scalable and enterprise-ready infrastructure</span>
                                </span>
                            </div>
                            <div class="home-hero__feature">
                                <span class="home-hero__feature-icon" aria-hidden="true"><i class="fas fa-headset"></i></span>
                                <span class="home-hero__feature-text">
                                    <span class="home-hero__feature-title">ERP &amp; CRM</span>
                                    <span class="home-hero__feature-sub">Streamline operations and improve customer management</span>
                                </span>
                            </div>
                        </div>

                        <div class="home-hero__actions">
                            <button type="button" class="home-hero__btn home-hero__btn--primary js-consult-open">
                                {{ $settings['hero_btn_primary'] ?? 'Request Consultation' }} <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="home-hero__btn home-hero__btn--ghost js-consult-open">
                                {{ $settings['hero_btn_secondary'] ?? 'Get a Quote' }} <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="home-hero__media wow move-up">
                        <span class="home-hero__glow" aria-hidden="true"></span>
                        <img src="{{ \App\Models\Setting::imageUrl($settings['hero_image'] ?? 'uploads/site/Networking-Solutions-India.webp', 'hero_image') }}"
                            class="home-hero__img" alt="Software AI Smart Technology Solutions" fetchpriority="high">
                        <span class="home-hero__chip home-hero__chip--1"><i class="fas fa-code" aria-hidden="true"></i> Software Development</span>
                        <span class="home-hero__chip home-hero__chip--2"><i class="fas fa-robot" aria-hidden="true"></i> AI &amp; Automation</span>
                        <span class="home-hero__chip home-hero__chip--3"><i class="fas fa-cloud" aria-hidden="true"></i> Cloud</span>
                        <span class="home-hero__chip home-hero__chip--4"><i class="fas fa-shield-alt" aria-hidden="true"></i> Cybersecurity</span>
                        <span class="home-hero__chip home-hero__chip--5"><i class="fas fa-server" aria-hidden="true"></i> Infrastructure</span>
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
                    <h6 class="section-sub-title text-white">{{ $settings['stats_sub_heading'] ?? 'Trusted Technology Partner' }}</h6>
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
    
    
    
    

    <!--===========  Capabilities We Deliver Start =============-->
    <div class="solutions-deliver-wrapper bg-gray section-space--ptb_80">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <span class="solutions-deliver-overline">{{ $settings['cap_overline'] ?? '/// Capabilities We Deliver ///' }}</span>
                        <h3 class="heading mt-15">{{ $settings['cap_heading'] ?? 'Business-focused Capabilities Built for' }} <span class="text-color-primary">{{ $settings['cap_heading_highlight'] ?? 'Impact' }}</span></h3>
                        <p class="solutions-deliver-subtitle mt-15">{{ $settings['cap_subtitle'] ?? 'We deliver end-to-end IT capabilities tailored to your business needs, designed to drive efficiency, security, and growth.' }}</p>
                    </div>
                </div>
            </div>
            <div class="row solutions-deliver-row">
    @foreach ($capabilities as $capability)
    <div class="col-12 col-md-6 col-lg-3 wow move-up section-space--mt_30 d-flex">
        <a href="{{ route('capabilities.show', $capability->slug) }}"
           class="solution-deliver-card w-100"
           aria-label="Learn more about our {{ $capability->title }}">
            
            <span class="solution-deliver-card__icon" aria-hidden="true">
                <img src="{{ asset('uploads/'.$capability->icon) }}"
                     alt="{{ $capability->title }}"
                     loading="lazy">
            </span>

            <h6 class="solution-deliver-card__title">
                {{ $capability->title }}
            </h6>

            <span class="solution-deliver-card__divider" aria-hidden="true"></span>

            @if ($capability->short_description)
                <p class="solution-deliver-card__text">
                    {{ $capability->short_description }}
                </p>
            @endif
        </a>
    </div>
    @endforeach
</div>
        </div>
    </div>
    <!--===========  Capabilities We Deliver End =============-->




    <!--===========  Why Choose Tectignis Start =============-->
    <div class="why-choose-wrapper section-space--ptb_80">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <span class="why-choose-badge">{{ $settings['why_badge'] ?? 'Why Choose Us' }}</span>
                        <h3 class="heading mt-15">{{ $settings['why_heading'] ?? 'Why Choose' }} <span class="text-color-primary">{{ $settings['why_heading_highlight'] ?? 'Tectignis?' }}</span></h3>
                        <p class="why-choose-subtitle mt-15">{{ $settings['why_subtitle'] ?? 'We combine expertise, technology, and a customer-first approach to deliver solutions that drive real results for your business.' }}</p>
                    </div>
                </div>
            </div>

            <div class="row align-items-center why-choose-main">
                <div class="col-lg-5">
                    <div class="why-choose-visual">
                        <div class="why-choose-visual__image">
                            <img class="img-fluid" src="{{ \App\Models\Setting::imageUrl($settings['what_we_offer_image'] ?? 'IT-Services-in-Nav-Mumbai.webp', 'what_we_offer_image') }}" alt="Why Choose Tectignis IT Solutions" loading="lazy">
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 mt-5 mt-lg-0">
                    <div class="why-choose-features">
                        @foreach ($whyChooseFeatures as $feature)
                        <div class="why-choose-feature">
                            <div class="why-choose-feature__icon"><i class="{{ $feature->icon }}"></i></div>
                            <div class="why-choose-feature__content">
                                <h6 class="why-choose-feature__title">{{ $feature->title }}</h6>
                                <p class="why-choose-feature__text">{{ $feature->text }}</p>
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


    <!--===========  Industries We Serve Start =============-->
    <section class="industries-serve-section section-space--ptb_80">
        {{-- Hidden SVG gradient definition reused by all card icons --}}
        <svg aria-hidden="true" focusable="false" style="position:absolute;width:0;height:0;overflow:hidden">
            <defs>
                <linearGradient id="indGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#19587F"/>
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
                    <span>{{ $settings['ind_pretitle'] ?? 'INDUSTRIES WE SERVE' }}</span>
                    <span class="industries-serve-pretitle__dot"></span>
                    <span class="industries-serve-pretitle__line"></span>
                </div>
                <h2 class="industries-serve-heading">{{ $settings['ind_heading_line1'] ?? 'Empowering Every Industry' }}</h2>
                <h2 class="industries-serve-heading industries-serve-heading--gradient">{{ $settings['ind_heading_line2'] ?? 'with Intelligent Solutions' }}</h2>
                <p class="industries-serve-subtitle">{{ $settings['ind_subtitle'] ?? 'We deliver tailored IT solutions to help businesses across industries innovate, optimize operations, and achieve sustainable growth.' }}</p>
            </div>

            {{-- Industry card grid --}}
            <div class="industries-serve-grid">
                @foreach ($industries as $industry)
                <a href="{{ route('industries.show', $industry->slug) }}" class="industry-serve-card" aria-label="{{ $industry->name }}">
                    <div class="industry-serve-card__blob">
                        <i class="{{ $industry->icon }}" aria-hidden="true"></i>
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
                        <div class="industries-serve-banner__title">150+</div>
                        <div class="industries-serve-banner__subtitle">Projects Delivered</div>
                    </div>
                </div>
                <div class="industries-serve-banner__item">
                    <div class="industries-serve-banner__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.705-3.078Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <div class="industries-serve-banner__title">50+</div>
                        <div class="industries-serve-banner__subtitle">Happy Clients</div>
                    </div>
                </div>
                <div class="industries-serve-banner__item">
                    <div class="industries-serve-banner__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="industries-serve-banner__title">5+</div>
                        <div class="industries-serve-banner__subtitle">Countries Served</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--===========  Industries We Serve End =============-->

    <!--=========== Technology Stack Start ===========-->
    <div class="section-space--ptb_60">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">{{ $settings['tech_sub_heading'] ?? 'Tools & Platforms' }}</h6>
                        <h3 class="heading">{{ $settings['tech_heading'] ?? 'Technology' }} <span class="text-color-primary">{{ $settings['tech_heading_highlight'] ?? 'Stack' }}</span></h3>
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

    <!--=========== Our Proven Delivery Process Start ===========-->
    <section class="section-space--ptb_80">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h3 class="heading">Our Proven <span class="text-color-primary">Delivery Process</span></h3>
                    </div>
                </div>
            </div>
            <div class="gp-process">
                <div class="gp-process__track">
                    <span class="gp-process__line" aria-hidden="true"></span>
                    @foreach ($processSteps as $step)
                    <div class="gp-step">
                        <span class="gp-step__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                {!! $step->icon !!}
                            </svg>
                        </span>
                        <h4 class="gp-step__title">{{ $step->title }}</h4>
                        <p class="gp-step__desc">{{ $step->description }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!--=========== Our Proven Delivery Process End ===========-->

    <!--=========== Case Studies Start =============-->
    <section class="cs-section section-space--ptb_80">
        <div class="container">
            <div class="cs-header">
                <span class="cs-header__badge">{{ $settings['cs_badge'] ?? 'CASE STUDIES' }}</span>
                <h2 class="cs-header__title">{{ $settings['cs_heading'] ?? 'Real Stories. Real Impact.' }}</h2>
                <p class="cs-header__subtitle">{{ $settings['cs_subtitle'] ?? 'Explore how our solutions have helped businesses overcome challenges, improve operations, and achieve measurable results.' }}</p>
            </div>

            @php
                $csThemes = ['blue', 'red', 'purple'];
                $csDefaultIcon = 'M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0H3';
            @endphp

            <div class="cs-grid">
                @foreach ($caseStudies->take(3) as $caseStudy)
                @php
                    $csTheme = $caseStudy->theme ?: $csThemes[$loop->index % count($csThemes)];
                    $csIcon = $caseStudy->icon ?: $csDefaultIcon;
                @endphp
                <div class="cs-card cs-card--{{ $csTheme }}">
                    <div class="cs-card__illus">
                        <div class="cs-card__illus-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="{{ $csIcon }}"/>
                            </svg>
                        </div>
                        <div class="cs-card__illus-main">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="{{ $csIcon }}"/>
                            </svg>
                        </div>
                    </div>
                    <div class="cs-card__body">
                        @if ($caseStudy->category)
                        <span class="cs-card__badge">{{ $caseStudy->category->name }}</span>
                        @endif
                        <h3 class="cs-card__title">{{ $caseStudy->title }}</h3>
                        <p class="cs-card__desc">{{ $caseStudy->short_description }}</p>
                        @if (! empty($caseStudy->features))
                        <hr class="cs-card__divider">
                        <ul class="cs-card__features">
                            @foreach ($caseStudy->features as $feature)
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
                        @endif
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
    <section class="gp-section section-space--ptb_80">
        <div class="container">
            {{-- Header --}}
            <div class="gp-header">
                <span class="gp-pretitle">{{ $settings['gp_pretitle'] ?? 'Global Presence' }}</span>
                <h2 class="gp-title">{{ $settings['gp_heading'] ?? 'Serving Clients Worldwide, Delivering Excellence' }} <span class="gp-title__accent">{{ $settings['gp_heading_highlight'] ?? 'Everywhere.' }}</span></h2>
                <p class="gp-subtitle">{{ $settings['gp_subtitle'] ?? 'We combine local expertise with a global mindset to deliver innovative IT solutions that help your business grow, scale and succeed.' }}</p>
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
                        @foreach ($indiaLocations as $loc)
                        <li class="gp-list__item">
                            <span class="gp-list__pin" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <span class="gp-list__body">
                                <span class="gp-list__city">{{ $loc->city }}</span>
                                <span class="gp-list__type">{{ $loc->type }}</span>
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
                            <span class="gp-stat__num">{{ $settings['gp_india_projects'] ?? '100+' }}</span>
                            <span class="gp-stat__label">Projects Across India</span>
                        </span>
                    </div>
                </aside>

                {{-- Center: world map (uploadable from admin) with India hub + arcs --}}
                @php
                    $gpMapUrl = \App\Models\Setting::imageUrl($settings['gp_map_image'] ?? null, 'gp_map_image')
                        ?? 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b0/World_location_map_%28equirectangular_180%29.svg/1280px-World_location_map_%28equirectangular_180%29.svg.png';
                @endphp
                <div class="gp-map" role="img" aria-label="World map highlighting Tectignis delivery from India to clients across UAE, Saudi Arabia, UK, USA, Canada, Australia and Singapore.">
                    <img class="gp-map__img" src="{{ $gpMapUrl }}" alt="" loading="lazy" decoding="async">
                    {{-- Overlay: arcs + nodes positioned by lat/lon on the equirectangular map --}}
                    <svg class="gp-map__svg gp-map__svg--overlay" viewBox="0 0 1000 500" preserveAspectRatio="none">
                        {{-- Connection arcs from the Mumbai hub --}}
                        <g class="gp-arcs" fill="none">
                            <path class="gp-arc" d="M702,197 Q678,160 654,180"/>  {{-- Dubai --}}
                            <path class="gp-arc" d="M702,197 Q666,148 630,182"/>  {{-- Riyadh --}}
                            <path class="gp-arc" d="M702,197 Q600,60 500,107"/>   {{-- London --}}
                            <path class="gp-arc" d="M702,197 Q500,20 294,137"/>   {{-- New York --}}
                            <path class="gp-arc" d="M702,197 Q480,0 279,129"/>    {{-- Toronto --}}
                            <path class="gp-arc" d="M702,197 Q840,250 920,344"/>  {{-- Sydney --}}
                            <path class="gp-arc" d="M702,197 Q755,215 788,246"/>  {{-- Singapore --}}
                        </g>

                        {{-- Global destination nodes --}}
                        <g class="gp-nodes">
                            <g class="gp-node gp-node--global" transform="translate(654,180)"><circle class="gp-node__ring" r="9"/><circle class="gp-node__dot" r="3.5"/></g>
                            <g class="gp-node gp-node--global" transform="translate(630,182)"><circle class="gp-node__ring" r="9"/><circle class="gp-node__dot" r="3.5"/></g>
                            <g class="gp-node gp-node--global" transform="translate(500,107)"><circle class="gp-node__ring" r="9"/><circle class="gp-node__dot" r="3.5"/></g>
                            <g class="gp-node gp-node--global" transform="translate(294,137)"><circle class="gp-node__ring" r="9"/><circle class="gp-node__dot" r="3.5"/></g>
                            <g class="gp-node gp-node--global" transform="translate(279,129)"><circle class="gp-node__ring" r="9"/><circle class="gp-node__dot" r="3.5"/></g>
                            <g class="gp-node gp-node--global" transform="translate(920,344)"><circle class="gp-node__ring" r="9"/><circle class="gp-node__dot" r="3.5"/></g>
                            <g class="gp-node gp-node--global" transform="translate(788,246)"><circle class="gp-node__ring" r="9"/><circle class="gp-node__dot" r="3.5"/></g>
                        </g>

                        {{-- India hub (Mumbai) --}}
                        <g class="gp-node gp-node--hub" transform="translate(702,197)">
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
                        @foreach ($globalLocations as $loc)
                        <li class="gp-list__item">
                            <span class="gp-list__pin" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    <path d="M19.5 9.75c0 7.142-7.5 11.25-7.5 11.25S4.5 16.892 4.5 9.75a7.5 7.5 0 1 1 15 0Z"/>
                                </svg>
                            </span>
                            <span class="gp-list__body">
                                <span class="gp-list__city">{{ $loc->city }}</span>
                                <span class="gp-list__type">{{ $loc->type }}</span>
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
                            <span class="gp-stat__num">{{ $settings['gp_countries_served'] ?? '25+' }}</span>
                            <span class="gp-stat__label">Countries Served</span>
                        </span>
                    </div>
                </aside>
            </div>

            {{-- Our Global Advantage banner --}}
            <div class="gp-advantages">
                <h3 class="gp-block-title">Our Global Advantage</h3>
                <div class="gp-advantages__grid">
                    @foreach ($globalAdvantages as $adv)
                    <div class="gp-advantage">
                        <span class="gp-advantage__icon gp-advantage__icon--{{ $adv->tone }}" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                {!! $adv->icon !!}
                            </svg>
                        </span>
                        <div class="gp-advantage__body">
                            <h4 class="gp-advantage__title">{{ $adv->title }}</h4>
                            <p class="gp-advantage__desc">{{ $adv->description }}</p>
                        </div>
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
                        <h6 class="section-sub-title mb-20">{{ $settings['res_sub_heading'] ?? 'Stay Informed' }}</h6>
                        <h3 class="heading">{{ $settings['res_heading'] ?? 'Resources &' }} <span class="text-color-primary">{{ $settings['res_heading_highlight'] ?? 'Insights' }}</span></h3>
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

    <!--====================  Ready to Transform CTA Start ====================-->
    <section class="cta-transform section-space--ptb_80">
        <span class="cta-transform__bg" aria-hidden="true"></span>
        <div class="container">
            <div class="cta-transform__inner">
                {{-- Left column --}}
                <div class="cta-transform__content">
                    <span class="cta-transform__overline">
                        <span class="cta-transform__overline-line" aria-hidden="true"></span>
                        {{ $settings['cta_overline'] ?? "Let's Build the Future Together" }}
                    </span>
                    <h2 class="cta-transform__title">
                        {{ $settings['cta_heading'] ?? 'Ready to Transform' }}
                        <span class="cta-transform__title-grad">{{ $settings['cta_heading_highlight'] ?? 'Your Business?' }}</span>
                    </h2>
                    <p class="cta-transform__desc">{{ $settings['cta_subheading'] ?? "Let's discuss how our software, AI, cloud, cybersecurity, and infrastructure solutions can accelerate your growth and drive real business impact." }}</p>

                    <div class="cta-transform__features">
                        <div class="cta-feature">
                            <span class="cta-feature__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/></svg>
                            </span>
                            <span class="cta-feature__text">
                                <span class="cta-feature__title">Innovative Solutions</span>
                                <span class="cta-feature__sub">that drive growth</span>
                            </span>
                        </div>
                        <div class="cta-feature">
                            <span class="cta-feature__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                            </span>
                            <span class="cta-feature__text">
                                <span class="cta-feature__title">Secure &amp; Scalable</span>
                                <span class="cta-feature__sub">by design</span>
                            </span>
                        </div>
                        <div class="cta-feature">
                            <span class="cta-feature__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2.25 12a9.75 9.75 0 0 1 19.5 0M3.75 12v3.75A2.25 2.25 0 0 0 6 18h.75v-6.75H6a2.25 2.25 0 0 0-2.25 1.5Zm16.5 0A2.25 2.25 0 0 0 18 11.25h-.75V18H18a2.25 2.25 0 0 0 2.25-2.25V12Zm-1.5 6v.75A2.25 2.25 0 0 1 15 21h-1.5"/></svg>
                            </span>
                            <span class="cta-feature__text">
                                <span class="cta-feature__title">24/7 Expert Support</span>
                                <span class="cta-feature__sub">you can rely on</span>
                            </span>
                        </div>
                    </div>

                    <div class="cta-transform__actions">
                        <button type="button" class="cta-btn cta-btn--primary js-consult-open">{{ $settings['cta_btn_primary'] ?? 'Request Consultation' }} <span aria-hidden="true">→</span></button>
                        <button type="button" class="cta-btn cta-btn--ghost js-consult-open">{{ $settings['cta_btn_secondary'] ?? 'Get a Quote' }} <span aria-hidden="true">→</span></button>
                    </div>
                </div>

                {{-- Right column: rocket + orbiting badges --}}
                <div class="cta-transform__visual" aria-hidden="true">
                    <span class="cta-orbit cta-orbit--outer"></span>
                    <span class="cta-orbit cta-orbit--inner"></span>

                    <div class="cta-rocket">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/></svg>
                    </div>

                    @php
                        $ctaBadges = [
                            ['pos' => 'dev',   'label' => 'Software Development', 'tone' => 'purple', 'icon' => '<path d="m6.75 7.5-4.5 4.5 4.5 4.5m10.5-9 4.5 4.5-4.5 4.5M14.25 4.5l-4.5 15"/>'],
                            ['pos' => 'ai',    'label' => 'AI &amp; Automation',  'tone' => 'purple', 'icon' => '<rect x="6" y="6" width="12" height="12" rx="2"/><path d="M9 9.75h6v4.5H9zM9 3v3m3-3v3m3-3v3M9 18v3m3-3v3m3-3v3M3 9h3m-3 3h3m-3 3h3m12-6h3m-3 3h3m-3 3h3"/>'],
                            ['pos' => 'cloud', 'label' => 'Cloud',              'tone' => 'purple', 'icon' => '<path d="M2.25 15a4.5 4.5 0 0 0 4.5 4.5H18a3.75 3.75 0 0 0 1.332-7.257 3 3 0 0 0-3.758-3.848 5.25 5.25 0 0 0-10.233 2.33A4.502 4.502 0 0 0 2.25 15Z"/>'],
                            ['pos' => 'cyber', 'label' => 'Cybersecurity',      'tone' => 'red',    'icon' => '<path d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5a2.25 2.25 0 0 1 2.25 2.25v6A2.25 2.25 0 0 1 17.25 21H6.75a2.25 2.25 0 0 1-2.25-2.25v-6a2.25 2.25 0 0 1 2.25-2.25Z"/>'],
                            ['pos' => 'infra', 'label' => 'Infrastructure',     'tone' => 'purple', 'icon' => '<path d="M5.25 14.25h13.5m-13.5 0a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3v3.75a3 3 0 0 1-3 3m-16.5 0v2.25a3 3 0 0 0 3 3h13.5a3 3 0 0 0 3-3v-2.25M6.75 8.25h.008v.008H6.75V8.25Zm0 6h.008v.008H6.75v-.008Z"/>'],
                        ];
                    @endphp
                    @foreach ($ctaBadges as $badge)
                    <div class="cta-badge cta-badge--{{ $badge['pos'] }}">
                        <span class="cta-badge__circle cta-badge__circle--{{ $badge['tone'] }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">{!! $badge['icon'] !!}</svg>
                        </span>
                        <span class="cta-badge__label">{{ $badge['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!--====================  Ready to Transform CTA End ====================-->

    

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
