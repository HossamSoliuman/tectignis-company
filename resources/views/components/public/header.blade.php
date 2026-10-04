{{--
    Site header (spec §4.1): Solutions | Industries | Our Work | Resources |
    Company | Contact, with the four business pillars in the Solutions mega-menu
    and the primary "Book a Technical Consultation" CTA.
    $navPillars / $navIndustries come from the composer in AppServiceProvider.
--}}
@php
    $resourceLinks = [
        ['label' => 'Blog', 'url' => route('blog.index')],
        ['label' => 'Technology Insights', 'url' => route('technology-insights')],
        ['label' => 'Case Studies', 'url' => route('case-studies.index')],
        ['label' => 'Downloads', 'url' => route('downloads')],
        ['label' => 'FAQs', 'url' => route('faqs')],
    ];
    $companyLinks = [
        ['label' => 'About Tectignis', 'url' => route('about')],
        ['label' => 'Careers', 'url' => route('careers')],
        ['label' => 'Contact Us', 'url' => route('contact')],
    ];
@endphp

<a class="tx-skip-link" href="#main-content">Skip to main content</a>

<div class="header-area header-area--default">

    <!-- Header Top Wrap Start -->
    <div class="header-top-wrap border-bottom">
        <div class="container-fluid">
            <div class="header-top-inner">
                <div class="header-top-left">
                    <ul class="header-top-info">
                        <li><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $company->shortLocation() }}</li>
                        <li class="d-sm-hide"><i class="fas fa-globe" aria-hidden="true"></i> Delivering to India, USA, UAE, UK, Canada &amp; Europe</li>
                    </ul>
                </div>
                <div class="header-top-right">
                    <ul class="header-top-info">
                        <li><a href="{{ $company->phoneHref() }}" data-track="phone_click"><i class="fas fa-phone-alt" aria-hidden="true"></i> {{ $company->phone() }}</a></li>
                        <li><a href="mailto:{{ $company->email() }}" data-track="email_click"><i class="fas fa-envelope" aria-hidden="true"></i> {{ $company->email() }}</a></li>
                    </ul>
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
                            <a href="{{ route('home') }}" aria-label="{{ $company->name() }} home">
                                <img src="{{ $company->logoUrl() }}" width="160" height="48" class="img-fluid" alt="{{ $company->name() }}">
                            </a>
                        </div>

                        <!-- header midle box  -->
                        <div class="header-midle-box">
                            <div class="header-bottom-wrap d-md-block d-none">
                                <div class="header-bottom-inner">
                                    <div class="header-bottom-left-wrap">
                                        <!-- navigation menu -->
                                        <div class="header__navigation d-none d-xl-block">
                                            <nav class="navigation-menu primary--menu" aria-label="Main">
                                                <ul>
                                                    <li class="has-children">
                                                        <a href="{{ route('capabilities.index') }}"><span>Solutions</span></a>
                                                        <ul class="megamenu megamenu--mega megamenu--services tx-mega" style="--cap-count: {{ count($navPillars) }}">
                                                            @foreach ($navPillars as $group)
                                                                @php $pillar = $group['pillar']; @endphp
                                                                <li>
                                                                    <h2 class="page-list-title">
                                                                        <a href="{{ $pillar->url() }}">
                                                                            <span class="cap-title-icon tx-mega__icon"><i class="{{ $pillar->icon() }}" aria-hidden="true"></i></span>
                                                                            <span>{{ $pillar->label() }}</span>
                                                                        </a>
                                                                    </h2>
                                                                    <p class="tx-mega__message">{{ $pillar->message() }}</p>
                                                                    <ul>
                                                                        @foreach ($group['services']->take(6) as $service)
                                                                            <li><a href="{{ route('services.show', $service->slug) }}"><span>{{ $service->title }}</span></a></li>
                                                                        @endforeach
                                                                    </ul>
                                                                    <a href="{{ $pillar->url() }}" class="tx-mega__all">All {{ $pillar->label() }} services <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                                                                </li>
                                                            @endforeach

                                                            <li class="megamenu-cta-row">
                                                                <div class="megamenu-cta">
                                                                    <a href="#" class="megamenu-cta-box js-consult-open">
                                                                        <span class="cta-icon"><i class="fas fa-headset" aria-hidden="true"></i></span>
                                                                        <span>
                                                                            <h6>Book a Technical Consultation</h6>
                                                                            <span>Discuss your requirements with a solution engineer.</span>
                                                                        </span>
                                                                    </a>
                                                                    <a href="{{ route('case-studies.index') }}" class="megamenu-cta-box">
                                                                        <span class="cta-icon"><i class="fas fa-briefcase" aria-hidden="true"></i></span>
                                                                        <span>
                                                                            <h6>View Our Work</h6>
                                                                            <span>See projects we have designed and delivered.</span>
                                                                        </span>
                                                                    </a>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li class="has-children">
                                                        <a href="{{ route('industries.index') }}"><span>Industries</span></a>
                                                        <ul class="megamenu megamenu--mega">
                                                            <li>
                                                                <h2 class="page-list-title">Industries We Serve</h2>
                                                                <ul>
                                                                    @foreach ($navIndustries as $industry)
                                                                        <li><a href="{{ route('industries.show', $industry->slug) }}"><span>{{ $industry->name }}</span></a></li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('case-studies.index') }}"><span>Our Work</span></a>
                                                    </li>
                                                    <li class="has-children">
                                                        <a href="{{ route('blog.index') }}"><span>Resources</span></a>
                                                        <ul class="megamenu megamenu--mega">
                                                            <li>
                                                                <h2 class="page-list-title">Resources</h2>
                                                                <ul>
                                                                    @foreach ($resourceLinks as $link)
                                                                        <li><a href="{{ $link['url'] }}"><span>{{ $link['label'] }}</span></a></li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li class="has-children">
                                                        <a href="{{ route('about') }}"><span>Company</span></a>
                                                        <ul class="megamenu megamenu--mega">
                                                            <li>
                                                                <h2 class="page-list-title">Company</h2>
                                                                <ul>
                                                                    @foreach ($companyLinks as $link)
                                                                        <li><a href="{{ $link['url'] }}"><span>{{ $link['label'] }}</span></a></li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('contact') }}"><span>Contact</span></a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- header right box -->
                        <div class="header-box tx-header-actions">
                            <button type="button" class="tx-btn tx-btn--outline tx-btn--sm d-none d-lg-inline-flex js-consult-open" data-track="quote_request">Get a Quote</button>
                            <button type="button" class="tx-btn tx-btn--primary tx-btn--sm js-consult-open" data-track="consultation_booking">
                                <span class="d-none d-sm-inline">Book a Technical Consultation</span>
                                <span class="d-inline d-sm-none">Consultation</span>
                            </button>

                            <!-- mobile menu -->
                            <button type="button" class="mobile-navigation-icon d-block d-xl-none" id="mobile-menu-trigger" aria-label="Open menu" aria-controls="mobile-menu-overlay">
                                <i></i>
                            </button>
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
                                <img src="{{ $company->logoUrl() }}" width="160" height="48" class="img-fluid" alt="{{ $company->name() }}">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 col-4">
                        <!-- mobile menu content -->
                        <div class="mobile-menu-content text-end">
                            <span class="mobile-navigation-close-icon" id="mobile-menu-close-trigger" role="button" tabindex="0" aria-label="Close menu"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mobile-menu-overlay__body">
            <nav class="offcanvas-navigation" aria-label="Mobile">
                <ul>
                    <li class="has-children">
                        <a href="{{ route('capabilities.index') }}">Solutions</a>
                        <ul class="sub-menu">
                            @foreach ($navPillars as $group)
                                <li class="mobile-sub-heading"><a href="{{ $group['pillar']->url() }}">{{ $group['pillar']->label() }}</a></li>
                                @foreach ($group['services']->take(6) as $service)
                                    <li><a href="{{ route('services.show', $service->slug) }}"><span>{{ $service->title }}</span></a></li>
                                @endforeach
                            @endforeach
                        </ul>
                    </li>
                    <li class="has-children">
                        <a href="{{ route('industries.index') }}">Industries</a>
                        <ul class="sub-menu">
                            @foreach ($navIndustries as $industry)
                                <li><a href="{{ route('industries.show', $industry->slug) }}"><span>{{ $industry->name }}</span></a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('case-studies.index') }}">Our Work</a>
                    </li>
                    <li class="has-children">
                        <a href="{{ route('blog.index') }}">Resources</a>
                        <ul class="sub-menu">
                            @foreach ($resourceLinks as $link)
                                <li><a href="{{ $link['url'] }}"><span>{{ $link['label'] }}</span></a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="has-children">
                        <a href="{{ route('about') }}">Company</a>
                        <ul class="sub-menu">
                            @foreach ($companyLinks as $link)
                                <li><a href="{{ $link['url'] }}"><span>{{ $link['label'] }}</span></a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>
            </nav>

            <div class="tx-mobile-cta">
                <button type="button" class="tx-btn tx-btn--primary tx-btn--block js-consult-open">Book a Technical Consultation</button>
                <button type="button" class="tx-btn tx-btn--outline tx-btn--block js-consult-open">Get a Quote</button>
                <a href="{{ $company->phoneHref() }}" class="tx-mobile-cta__contact" data-track="phone_click"><i class="fas fa-phone-alt" aria-hidden="true"></i> {{ $company->phone() }}</a>
                <a href="mailto:{{ $company->email() }}" class="tx-mobile-cta__contact" data-track="email_click"><i class="fas fa-envelope" aria-hidden="true"></i> {{ $company->email() }}</a>
            </div>
        </div>
    </div>
</div>
<!--====================  End of mobile menu overlay ====================-->
