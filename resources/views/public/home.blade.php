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
                    <h6 class="section-sub-title">Trusted Technology Partner</h6>
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
    <div class="feature-images-wrapper bg-gray section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">What We Do</h6>
                        <h3 class="heading">Our <span class="text-color-primary">Capabilities</span></h3>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($capabilities as $capability)
                <div class="col-lg-4 col-md-6 wow move-up">
                    <div class="projects-wrap style-04">
                        <div class="projects-image-box">
                            <div class="box-image text-center">
                                <img class="img-fulid" src="{{ asset('uploads/'.$capability->icon) }}" alt="{{ $capability->title }}" loading="lazy">
                            </div>
                            <div class="content text-center">
                                <h5 class="heading">{{ $capability->title }}</h5>
                                <div class="text">{{ $capability->short_description }}</div>
                                <div class="box-projects-arrow">
                                    <a href="{{ route('capabilities.show', $capability->slug) }}" class="button-text">Discover Now <i class="fas fa-arrow-right ml-1"></i></a>
                                </div>
                            </div>
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
    <div class="software-innovation-about-company-area software-innovation-about-bg section-space--ptb_120">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="image-inner-video-section">
                        <img class="img-fluid border-radus-5" src="{{ \App\Models\Setting::imageUrl($settings['what_we_offer_image'] ?? 'IT-Services-in-Nav-Mumbai.webp', 'what_we_offer_image') }}" alt="Why Choose Tectignis IT Solutions" loading="lazy">
                    </div>
                </div>
                <div class="col-lg-6 ms-auto mt-30">
                    <div class="machine-learning-about-content">
                        <div class="section-title mb-20">
                            <div class="section-title-wrap text-left section-space--mb_30">
                                <h6 class="section-sub-title mb-20">Strategic Delivery Network</h6>
                                <h3 class="heading">Why Choose <span class="text-color-primary">Tectignis</span>?</h3>
                            </div>
                            <p class="dec-text mt-20">We collaborate with experienced technology professionals and implementation partners to assemble the right expertise for each project — delivering enterprise-grade solutions while maintaining competitive pricing and agility.</p>
                            <ul class="check-list mt-20">
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>Global Delivery Model</strong></li>
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>Cost-Effective Solutions</strong></li>
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>Dedicated Project Management</strong></li>
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>Scalable Resources</strong></li>
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>Quality Assurance &amp; Testing</strong></li>
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>On-Time Delivery</strong></li>
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>Single Point of Contact</strong></li>
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>Multi-Domain Expertise</strong></li>
                            </ul>
                            <div class="button-group-wrap mt-30">
                                <a href="{{ route('about') }}" class="btn">About Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===========  Why Choose Tectignis End =============-->

    <!--===========  Solutions We Deliver Start =============-->
    <div class="feature-images-wrapper bg-gray section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">Business-Focused</h6>
                        <h3 class="heading">Solutions We <span class="text-color-primary">Deliver</span></h3>
                    </div>
                </div>
            </div>
            <div class="row row--30">
                @foreach ($solutions as $solution)
                <div class="col-lg-3 col-md-4 col-sm-6 wow move-up section-space--mt_30">
                    <a href="{{ route('solutions.show', $solution->slug) }}" class="ht-box-images style-04 d-block text-center p-4 h-100">
                        <div class="image-box-wrap">
                            <div class="box-image mb-20">
                                <i class="{{ $solution->icon }} fa-3x text-color-primary"></i>
                            </div>
                            <div class="content">
                                <h6 class="heading">{{ $solution->title }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!--===========  Solutions We Deliver End =============-->

    <!--===========  Industries We Serve Start =============-->
    <div class="feature-images-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">Sector Expertise</h6>
                        <h3 class="heading">Industries We <span class="text-color-primary">Serve</span></h3>
                    </div>
                </div>
            </div>
            <div class="row row--30">
                @foreach ($industries as $industry)
                <div class="col-lg-3 col-md-4 col-sm-6 wow move-up section-space--mt_30">
                    <a href="{{ route('industries.show', $industry->slug) }}" class="ht-box-images style-03 d-block text-center p-4">
                        <div class="image-box-wrap">
                            <div class="box-image mb-20">
                                <i class="{{ $industry->icon }} fa-2x text-color-primary"></i>
                            </div>
                            <div class="content">
                                <h6 class="heading">{{ $industry->name }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!--===========  Industries We Serve End =============-->

    <!--=========== Featured Services Start ===========-->
    <div class="feature-images-wrapper bg-gray section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">Premium Expertise</h6>
                        <h3 class="heading">Featured <span class="text-color-primary">Services</span></h3>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($services->take(8) as $service)
                <div class="col-lg-3 col-md-4 col-sm-6 wow move-up section-space--mt_30">
                    <a href="{{ route('services.show', $service->slug) }}" class="projects-wrap style-04 d-block">
                        <div class="projects-image-box">
                            <div class="box-image text-center">
                                <img class="img-fulid" src="{{ asset('uploads/'.$service->icon) }}" alt="{{ $service->title }}" loading="lazy">
                            </div>
                            <div class="content text-center">
                                <h5 class="heading">{{ $service->title }}</h5>
                                <div class="box-projects-arrow">
                                    <span class="button-text">Learn More</span>
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="button-group-wrap text-center mt-40">
                <a href="{{ route('services.index') }}" class="btn">View All Services</a>
            </div>
        </div>
    </div>
    <!--=========== Featured Services End ===========-->

    <!--=========== Technology Stack Start ===========-->
    <div class="section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">Tools &amp; Platforms</h6>
                        <h3 class="heading">Technology <span class="text-color-primary">Stack</span></h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center text-center">
                @foreach ($techStacks as $tech)
                <div class="col-lg-2 col-md-3 col-4 wow move-up section-space--mt_30">
                    <div class="ht-box-images style-03 text-center p-3">
                        @if ($tech->logo)
                            <img class="img-fluid mb-10" src="{{ asset('uploads/'.$tech->logo) }}" alt="{{ $tech->name }}" loading="lazy" style="max-height:40px;width:auto;display:inline-block;">
                        @endif
                        <p class="font-weight--bold text-color-primary mb-0">{{ $tech->name }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!--=========== Technology Stack End ===========-->

    <!--=========== Case Studies Start =============-->
    <div class="service-projects-wrapper bg-gray section-space--pt_100 mb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_60">
                        <h6 class="section-sub-title mb-20">Case Studies</h6>
                        <h3 class="heading">Proud projects that <span class="text-color-primary">make us stand out</span></h3>
                    </div>
                </div>
            </div>
            <div class="swiper-container service-slider__project-active">
                <div class="swiper-wrapper service-slider__project">
                    @foreach ($caseStudies as $caseStudy)
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="slide-content col-lg-6 col-xl-5 order-2 order-lg-1">
                                <div class="service-project-slide-info">
                                    <h4 class="heading font-weight--reguler mb-10">{{ $caseStudy->title }}</h4>
                                    <p class="sub-text text-color-primary">{{ $caseStudy->category }}</p>
                                    <div class="text">{{ $caseStudy->short_description }}</div>
                                    <div class="section-under-heading"><a href="{{ route('case-studies.index') }}">View All Case Studies</a></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-7 order-1 order-lg-2">
                                <div class="slide-image">
                                    <div class="image-wrap">
                                        <div class="image">
                                            @if ($caseStudy->image)
                                                <img class="img-fluid" src="{{ asset('uploads/'.$caseStudy->image) }}" alt="{{ $caseStudy->title }}" loading="lazy">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!--=========== Case Studies End =============-->

    <!--====================  Testimonials ====================-->
    <x-public.testimonials :testimonials="$testimonials" />
    <!--====================  End of Testimonials ====================-->

    <!--=========== Service Coverage Start ===========-->
    <div class="technology-service-area technology-service-bg section-space--ptb_80">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="section-title section-space--pt_60">
                        <h6 class="text-white section-sub-title mb-20">Local &amp; Global Reach</h6>
                        <h3 class="text-white">Serving Businesses <span class="text-color-secondary">Across India &amp; Worldwide</span></h3>
                        <div class="row mt-30">
                            <div class="col-6">
                                <h6 class="text-white font-weight--bold mb-15">India</h6>
                                <ul class="list-unstyled text-white">
                                    <li class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Navi Mumbai</li>
                                    <li class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Mumbai</li>
                                    <li class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Thane</li>
                                    <li class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Pune</li>
                                    <li class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Panvel</li>
                                    <li class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Maharashtra &amp; PAN India</li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <h6 class="text-white font-weight--bold mb-15">Global Delivery</h6>
                                <ul class="list-unstyled text-white">
                                    <li class="mb-1"><i class="fas fa-globe me-2"></i>UAE &amp; Saudi Arabia</li>
                                    <li class="mb-1"><i class="fas fa-globe me-2"></i>UK &amp; USA</li>
                                    <li class="mb-1"><i class="fas fa-globe me-2"></i>Canada</li>
                                    <li class="mb-1"><i class="fas fa-globe me-2"></i>Australia</li>
                                    <li class="mb-1"><i class="fas fa-globe me-2"></i>Singapore</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="banner-image section-space--pt_60">
                        <img src="{{ \App\Models\Setting::imageUrl($settings['tech_service_image'] ?? 'Networking-Solutions-India.webp', 'tech_service_image') }}" class="img-fluid" alt="Global IT Solutions Delivery" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--=========== Service Coverage End ===========-->

    <!--=========== Resources & Insights Start ===========-->
    @if ($recentPosts->isNotEmpty())
    <div class="feature-images-wrapper bg-gray section-space--ptb_100">
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
    <div class="contact-us-section-wrappaer infotechno-contact-us-bg section-space--ptb_120">
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
    <div class="contact-form-section section-space--ptb_100">
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
