@php
    $siteSettings = \App\Models\Setting::values();
    $sitePhone = $siteSettings['site_phone'] ?? '+91 9987705688';
    $siteEmail = $siteSettings['site_email'] ?? 'info@tectignis.in';
    // Header now sits on a white background, so use the dark wordmark.
    $headerLogo = \App\Models\Setting::imageUrl($siteSettings['site_logo_dark'] ?? null, 'site_logo_dark')
        ?? asset('assets/images/logo/Tectignis-IT-solution-logo.webp');
    $mobileLogo = \App\Models\Setting::imageUrl($siteSettings['site_logo_dark'] ?? null, 'site_logo_dark')
        ?? asset('assets/images/logo/Tectignis-IT-solution-logo.webp');
@endphp

<div class="header-area header-area--default">

    <!-- Header Top Wrap Start -->
    <div class="header-top-wrap border-bottom">
        <div class="container-fluid">
            <div class="header-top-inner">
                <div class="header-top-left">
                    <ul class="header-top-info">
                        <li><i class="fas fa-map-marker-alt"></i> Navi Mumbai, Maharashtra, India</li>
                        <li class="d-sm-hide"><i class="fas fa-globe"></i> Serving Clients Worldwide</li>
                    </ul>
                </div>
                <div class="header-top-right">
                    <ul class="header-top-info">
                        <li><a href="tel:{{ preg_replace('/[^+\d]/', '', $sitePhone) }}"><i class="fas fa-phone-alt"></i> {{ $sitePhone }}</a></li>
                        <li><a href="mailto:{{ $siteEmail }}"><i class="fas fa-envelope"></i> {{ $siteEmail }}</a></li>
                    </ul>
                    <button type="button" class="header-top-btn js-consult-open">Request Consultation</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Top Wrap End -->

    <!-- Header Bottom Wrap Start -->
    <div class="header-bottom-wrap header-sticky">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header default-menu-style position-relative">

                        <!-- brand logo -->
                        <div class="header__logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ $headerLogo }}" aria-label="Tectignis Logo" width="160" height="48" class="img-fluid" alt="Tectignis-IT-solution-logo">
                            </a>
                        </div>

                        <!-- header midle box  -->
                        <div class="header-midle-box">
                            <div class="header-bottom-wrap d-md-block d-none">
                                <div class="header-bottom-inner">
                                    <div class="header-bottom-left-wrap">
                                        <!-- navigation menu -->
                                        <div class="header__navigation d-none d-xl-block">
                                            <nav class="navigation-menu primary--menu">

                                                <ul>
                                                    <li>
                                                        <a href="{{ route('home') }}"><span>Home</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('about') }}"><span>Company</span></a>
                                                    </li>

                                                    <li class="has-children">
                                                        <a href="{{ route('capabilities.index') }}"><span>Capabilities</span></a>
                                                        <ul class="megamenu megamenu--mega megamenu--services" style="--cap-count: {{ $navCapabilities->count() }}">

                                                            @foreach ($navCapabilities as $capability)
                                                                <li>
                                                                    <h2 class="page-list-title">
                                                                        <a href="{{ route('capabilities.show', $capability->slug) }}">
                                                                            @if ($capability->icon)
                                                                                <span class="cap-title-icon"><img src="{{ asset('uploads/'.$capability->icon) }}" alt="{{ $capability->title }}" loading="lazy"></span>
                                                                            @endif
                                                                            <span>{{ $capability->title }}</span>
                                                                        </a>
                                                                    </h2>
                                                                    <ul>
                                                                        @foreach ($capability->services as $service)
                                                                            <li><a href="{{ route('services.show', $service->slug) }}"><span>{{ $service->title }}</span></a></li>
                                                                        @endforeach
                                                                    </ul>
                                                                </li>
                                                            @endforeach

                                                            <li class="megamenu-cta-row">
                                                                <div class="megamenu-cta">
                                                                    <a href="{{ route('contact') }}" class="megamenu-cta-box">
                                                                        <span class="cta-icon"><i class="fas fa-headset"></i></span>
                                                                        <span>
                                                                            <h6>Talk To Our Expert</h6>
                                                                            <span>Get expert guidance for your business needs.</span>
                                                                        </span>
                                                                    </a>
                                                                    <a href="{{ route('contact') }}" class="megamenu-cta-box">
                                                                        <span class="cta-icon"><i class="fas fa-file-invoice"></i></span>
                                                                        <span>
                                                                            <h6>Request A Quote</h6>
                                                                            <span>Get a tailored quote for your project.</span>
                                                                        </span>
                                                                    </a>
                                                                </div>
                                                            </li>

                                                        </ul>
                                                    </li>
                                                    <li class="has-children">
                                                        <a href="{{ route('solutions.index') }}"><span>Solutions</span></a>
                                                        <ul class="megamenu megamenu--mega">
                                                            <li>
                                                                <h2 class="page-list-title">Business Solutions</h2>
                                                                <ul>
                                                                    <li><a href="{{ route('solutions.show', 'erp-solutions') }}"><span>ERP Solutions</span></a></li>
                                                                    <li><a href="{{ route('solutions.show', 'crm-solutions') }}"><span>CRM Solutions</span></a></li>
                                                                    <li><a href="{{ route('solutions.show', 'hrms-solutions') }}"><span>HRMS Solutions</span></a></li>
                                                                    <li><a href="{{ route('solutions.show', 'ai-solutions') }}"><span>AI Solutions</span></a></li>
                                                                    <li><a href="{{ route('solutions.show', 'cloud-solutions') }}"><span>Cloud Solutions</span></a></li>
                                                                    <li><a href="{{ route('solutions.show', 'cybersecurity-solutions') }}"><span>Security Solutions</span></a></li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li class="has-children">
                                                        <a href="{{ route('industries.index') }}"><span>Industries</span></a>
                                                        <ul class="megamenu megamenu--mega">
                                                            <li>
                                                                <h2 class="page-list-title">Industries We Serve</h2>
                                                                <ul>
                                                                    <li><a href="{{ route('industries.show', 'manufacturing') }}"><span>Manufacturing</span></a></li>
                                                                    <li><a href="{{ route('industries.show', 'healthcare') }}"><span>Healthcare</span></a></li>
                                                                    <li><a href="{{ route('industries.show', 'education') }}"><span>Education</span></a></li>
                                                                    <li><a href="{{ route('industries.show', 'retail') }}"><span>Retail</span></a></li>
                                                                    <li><a href="{{ route('industries.show', 'real-estate') }}"><span>Real Estate</span></a></li>
                                                                    <li><a href="{{ route('industries.show', 'logistics') }}"><span>Logistics</span></a></li>
                                                                    <li><a href="{{ route('industries.show', 'hospitality') }}"><span>Hospitality</span></a></li>
                                                                    <li><a href="{{ route('industries.show', 'corporate-offices') }}"><span>Corporate Offices</span></a></li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('case-studies.index') }}"><span>Case Studies</span></a>
                                                    </li>
                                                    <li class="has-children">
                                                        <a href="#"><span>Resources</span></a>
                                                        <ul class="megamenu megamenu--mega">
                                                            <li>
                                                                <h2 class="page-list-title">Resources</h2>
                                                                <ul>
                                                                    <li><a href="{{ route('blog.index') }}"><span>Blog</span></a></li>
                                                                    <li><a href="{{ route('technology-insights') }}"><span>Technology Insights</span></a></li>
                                                                    <li><a href="{{ route('downloads') }}"><span>Downloads</span></a></li>
                                                                    <li><a href="{{ route('faqs') }}"><span>FAQs</span></a></li>
                                                                    <li><a href="{{ route('careers') }}"><span>Careers</span></a></li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('contact') }}"><span>Contact Us</span></a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- header right box -->
                        <div class="header-box">

                            <!-- mobile menu -->
                            <div class="mobile-navigation-icon d-block d-xl-none" id="mobile-menu-trigger">
                                <i></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Bottom Wrap End -->

</div>

<!--====================  mobile menu overlay ====================-->
<div class="mobile-menu-overlay" id="mobile-menu-overlay">
    <div class="mobile-menu-overlay__inner">
        <div class="mobile-menu-overlay__header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6 col-8">
                        <!-- logo -->
                        <div class="logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ $mobileLogo }}" class="img-fluid" alt="Tectignis-IT-solution-logo">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 col-4">
                        <!-- mobile menu content -->
                        <div class="mobile-menu-content text-end">
                            <span class="mobile-navigation-close-icon" id="mobile-menu-close-trigger"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mobile-menu-overlay__body">
            <nav class="offcanvas-navigation">
                <ul>
                    <li>
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}">About</a>
                    </li>
                    <li class="has-children">
                        <a href="{{ route('capabilities.index') }}">Capabilities</a>
                        <ul class="sub-menu">
                            @foreach ($navCapabilities as $capability)
                                <li class="mobile-sub-heading"><a href="{{ route('capabilities.show', $capability->slug) }}">{{ $capability->title }}</a></li>
                                @foreach ($capability->services as $service)
                                    <li><a href="{{ route('services.show', $service->slug) }}"><span>{{ $service->title }}</span></a></li>
                                @endforeach
                            @endforeach
                        </ul>
                    </li>
                    <li class="has-children">
                        <a href="{{ route('solutions.index') }}">Solutions</a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('solutions.show', 'erp-solutions') }}"><span>ERP Solutions</span></a></li>
                            <li><a href="{{ route('solutions.show', 'crm-solutions') }}"><span>CRM Solutions</span></a></li>
                            <li><a href="{{ route('solutions.show', 'hrms-solutions') }}"><span>HRMS Solutions</span></a></li>
                            <li><a href="{{ route('solutions.show', 'ai-solutions') }}"><span>AI Solutions</span></a></li>
                            <li><a href="{{ route('solutions.show', 'cloud-solutions') }}"><span>Cloud Solutions</span></a></li>
                            <li><a href="{{ route('solutions.show', 'cybersecurity-solutions') }}"><span>Security Solutions</span></a></li>
                        </ul>
                    </li>
                    <li class="has-children">
                        <a href="{{ route('industries.index') }}">Industries</a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('industries.show', 'manufacturing') }}"><span>Manufacturing</span></a></li>
                            <li><a href="{{ route('industries.show', 'healthcare') }}"><span>Healthcare</span></a></li>
                            <li><a href="{{ route('industries.show', 'education') }}"><span>Education</span></a></li>
                            <li><a href="{{ route('industries.show', 'retail') }}"><span>Retail</span></a></li>
                            <li><a href="{{ route('industries.show', 'real-estate') }}"><span>Real Estate</span></a></li>
                            <li><a href="{{ route('industries.show', 'logistics') }}"><span>Logistics</span></a></li>
                            <li><a href="{{ route('industries.show', 'hospitality') }}"><span>Hospitality</span></a></li>
                            <li><a href="{{ route('industries.show', 'corporate-offices') }}"><span>Corporate Offices</span></a></li>
                        </ul>
                    </li>
                    <li class="has-children">
                        <a href="#">Resources</a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('blog.index') }}"><span>Blog</span></a></li>
                            <li><a href="{{ route('technology-insights') }}"><span>Technology Insights</span></a></li>
                            <li><a href="{{ route('downloads') }}"><span>Downloads</span></a></li>
                            <li><a href="{{ route('faqs') }}"><span>FAQs</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}">Contact</a>
                    </li>
                    <li>
                        <a href="{{ route('careers') }}">Careers</a>
                    </li>
                    <li>
                        <a href="{{ route('case-studies.index') }}">Case Studies</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<!--====================  End of mobile menu overlay ====================-->
