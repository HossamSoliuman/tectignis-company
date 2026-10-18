{{--
    Site header (spec §4.1): Home | Solutions | Industries | Our Work | Resources |
    Company | Contact, with the four business pillars in the Solutions mega-menu
    and the primary "Book a Technical Consultation" CTA.
    $navPillars / $navIndustries come from the composer in AppServiceProvider.
--}}
@php
    $resourceLinks = [
        ['label' => __('nav.blog'), 'url' => route('blog.index')],
        ['label' => __('nav.technology_insights'), 'url' => route('technology-insights')],
        ['label' => __('nav.case_studies'), 'url' => route('case-studies.index')],
        ['label' => __('nav.downloads'), 'url' => route('downloads')],
        ['label' => __('nav.faqs'), 'url' => route('faqs')],
    ];
    $companyLinks = [
        ['label' => __('nav.about'), 'url' => route('about')],
        ['label' => __('nav.careers'), 'url' => route('careers')],
        ['label' => __('nav.contact_us'), 'url' => localized_route('contact')],
    ];
@endphp

<a class="tx-skip-link" href="#main-content">{{ __('nav.skip_to_content') }}</a>

<div class="header-area header-area--default">

    <!-- Header Top Wrap Start -->
    <div class="header-top-wrap border-bottom">
        <div class="container-fluid">
            <div class="header-top-inner">
                <div class="header-top-left">
                    <ul class="header-top-info">
                        <li><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $company->shortLocation() }}</li>
                        <li class="d-sm-hide"><i class="fas fa-globe" aria-hidden="true"></i> {{ __('nav.delivering_to') }}</li>
                    </ul>
                </div>
                <div class="header-top-right">
                    <ul class="header-top-info">
                        <li><a href="{{ $company->phoneHref() }}" data-track="phone_click"><i class="fas fa-phone-alt" aria-hidden="true"></i> <span dir="ltr">{{ $company->phone() }}</span></a></li>
                        <li><a href="mailto:{{ $company->email() }}" data-track="email_click"><i class="fas fa-envelope" aria-hidden="true"></i> {{ $company->email() }}</a></li>
                        <x-public.language-switcher in-list-item />
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
                            <a href="{{ localized_route('home') }}" aria-label="{{ __('nav.home_link', ['name' => $company->name()]) }}">
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
                                            <nav class="navigation-menu primary--menu" aria-label="{{ __('nav.main') }}">
                                                <ul>
                                                    <li>
                                                        <a href="{{ localized_route('home') }}"><span>{{ __('nav.home') }}</span></a>
                                                    </li>
                                                    <li class="has-children">
                                                        <a href="{{ route('capabilities.index') }}"><span>{{ __('nav.solutions') }}</span></a>
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
                                                                            <li><a href="{{ localized_route('services.show', $service->slug) }}"><span>{{ $service->title }}</span></a></li>
                                                                        @endforeach
                                                                    </ul>
                                                                    <a href="{{ $pillar->url() }}" class="tx-mega__all">{{ __('nav.all_pillar_services', ['pillar' => $pillar->label()]) }} <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                                                                </li>
                                                            @endforeach

                                                            <li class="megamenu-cta-row">
                                                                <div class="megamenu-cta">
                                                                    <a href="#" class="megamenu-cta-box js-consult-open">
                                                                        <span class="cta-icon"><i class="fas fa-headset" aria-hidden="true"></i></span>
                                                                        <span>
                                                                            <h6>{{ __('nav.book_consultation') }}</h6>
                                                                            <span>{{ __('nav.book_consultation_text') }}</span>
                                                                        </span>
                                                                    </a>
                                                                    <a href="{{ route('case-studies.index') }}" class="megamenu-cta-box">
                                                                        <span class="cta-icon"><i class="fas fa-briefcase" aria-hidden="true"></i></span>
                                                                        <span>
                                                                            <h6>{{ __('nav.view_our_work') }}</h6>
                                                                            <span>{{ __('nav.view_our_work_text') }}</span>
                                                                        </span>
                                                                    </a>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li class="has-children">
                                                        <a href="{{ route('industries.index') }}"><span>{{ __('nav.industries') }}</span></a>
                                                        <ul class="megamenu megamenu--mega megamenu--industries">
                                                            <li>
                                                                <h2 class="page-list-title">{{ __('nav.industries_we_serve') }}</h2>
                                                                <ul>
                                                                    @foreach ($navIndustries as $industry)
                                                                        <li><a href="{{ route('industries.show', $industry->slug) }}"><span>{{ $industry->name }}</span></a></li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('case-studies.index') }}"><span>{{ __('nav.our_work') }}</span></a>
                                                    </li>
                                                    <li class="has-children">
                                                        <a href="{{ route('blog.index') }}"><span>{{ __('nav.resources') }}</span></a>
                                                        <ul class="megamenu megamenu--mega">
                                                            <li>
                                                                <h2 class="page-list-title">{{ __('nav.resources') }}</h2>
                                                                <ul>
                                                                    @foreach ($resourceLinks as $link)
                                                                        <li><a href="{{ $link['url'] }}"><span>{{ $link['label'] }}</span></a></li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li class="has-children">
                                                        <a href="{{ route('about') }}"><span>{{ __('nav.company') }}</span></a>
                                                        <ul class="megamenu megamenu--mega">
                                                            <li>
                                                                <h2 class="page-list-title">{{ __('nav.company') }}</h2>
                                                                <ul>
                                                                    @foreach ($companyLinks as $link)
                                                                        <li><a href="{{ $link['url'] }}"><span>{{ $link['label'] }}</span></a></li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <a href="{{ localized_route('contact') }}"><span>{{ __('nav.contact') }}</span></a>
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
                            <button type="button" class="tx-btn tx-btn--outline tx-btn--sm d-none d-lg-inline-flex js-consult-open">{{ __('nav.get_quote') }}</button>
                            <button type="button" class="tx-btn tx-btn--primary tx-btn--sm js-consult-open">
                                <span class="d-none d-md-inline">{{ __('nav.book_consultation') }}</span>
                                <span class="d-inline d-md-none">{{ __('nav.consultation') }}</span>
                            </button>

                            <!-- mobile menu -->
                            <button type="button" class="mobile-navigation-icon d-block d-xl-none" id="mobile-menu-trigger" aria-label="{{ __('nav.open_menu') }}" aria-controls="mobile-menu-overlay" aria-expanded="false">
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
            <a href="{{ localized_route('home') }}" class="mobile-menu-overlay__logo" aria-label="{{ __('nav.home_link', ['name' => $company->name()]) }}">
                <img src="{{ $company->logoUrl() }}" width="160" height="48" alt="{{ $company->name() }}">
            </a>
            <button type="button" class="mobile-navigation-close-icon" id="mobile-menu-close-trigger" aria-label="{{ __('nav.close_menu') }}"></button>
        </div>
        <div class="mobile-menu-overlay__body">
            <nav class="offcanvas-navigation" aria-label="{{ __('nav.mobile') }}">
                <ul>
                    <li>
                        <a href="{{ localized_route('home') }}">{{ __('nav.home') }}</a>
                    </li>
                    <li class="has-children">
                        <a href="{{ route('capabilities.index') }}">{{ __('nav.solutions') }}</a>
                        <ul class="sub-menu">
                            @foreach ($navPillars as $group)
                                <li class="mobile-sub-heading"><a href="{{ $group['pillar']->url() }}">{{ $group['pillar']->label() }}</a></li>
                                @foreach ($group['services']->take(6) as $service)
                                    <li><a href="{{ localized_route('services.show', $service->slug) }}"><span>{{ $service->title }}</span></a></li>
                                @endforeach
                            @endforeach
                        </ul>
                    </li>
                    <li class="has-children">
                        <a href="{{ route('industries.index') }}">{{ __('nav.industries') }}</a>
                        <ul class="sub-menu">
                            @foreach ($navIndustries as $industry)
                                <li><a href="{{ route('industries.show', $industry->slug) }}"><span>{{ $industry->name }}</span></a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('case-studies.index') }}">{{ __('nav.our_work') }}</a>
                    </li>
                    <li class="has-children">
                        <a href="{{ route('blog.index') }}">{{ __('nav.resources') }}</a>
                        <ul class="sub-menu">
                            @foreach ($resourceLinks as $link)
                                <li><a href="{{ $link['url'] }}"><span>{{ $link['label'] }}</span></a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="has-children">
                        <a href="{{ route('about') }}">{{ __('nav.company') }}</a>
                        <ul class="sub-menu">
                            @foreach ($companyLinks as $link)
                                <li><a href="{{ $link['url'] }}"><span>{{ $link['label'] }}</span></a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li>
                        <a href="{{ localized_route('contact') }}">{{ __('nav.contact') }}</a>
                    </li>
                </ul>
            </nav>

            <x-public.language-switcher variant="list" />

            <div class="tx-mobile-cta">
                <button type="button" class="tx-btn tx-btn--primary tx-btn--block js-consult-open">{{ __('nav.book_consultation') }}</button>
                <button type="button" class="tx-btn tx-btn--outline tx-btn--block js-consult-open">{{ __('nav.get_quote') }}</button>
                <a href="{{ $company->phoneHref() }}" class="tx-mobile-cta__contact" data-track="phone_click"><i class="fas fa-phone-alt" aria-hidden="true"></i> <span dir="ltr">{{ $company->phone() }}</span></a>
                <a href="mailto:{{ $company->email() }}" class="tx-mobile-cta__contact" data-track="email_click"><i class="fas fa-envelope" aria-hidden="true"></i> {{ $company->email() }}</a>
            </div>
        </div>
    </div>
</div>
<!--====================  End of mobile menu overlay ====================-->
