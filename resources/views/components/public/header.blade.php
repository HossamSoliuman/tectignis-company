<div class="header-area header-area--default">

    {{-- Header Top --}}
    <div class="header-top-wrap border-bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <p class="text-center top-message">Leading Provider of IT Solutions in Navi Mumbai:
                        <a href="{{ route('contact') }}">Expert Support</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Header Bottom --}}
    <div class="header-bottom-wrap header-sticky">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header default-menu-style position-relative">

                        {{-- Brand logo --}}
                        <div class="header__logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('assets/images/logo/Tectignis-IT-solution-logo.webp') }}"
                                    aria-label="Tectignis Logo" width="160" height="48" class="img-fluid"
                                    alt="Tectignis-IT-solution-logo">
                            </a>
                        </div>

                        {{-- Primary navigation --}}
                        <div class="header-midle-box">
                            <div class="header-bottom-wrap d-md-block d-none">
                                <div class="header-bottom-inner">
                                    <div class="header-bottom-left-wrap">
                                        <div class="header__navigation d-none d-xl-block">
                                            <nav class="navigation-menu primary--menu">
                                                <ul>
                                                    <li><a href="{{ route('home') }}"><span>Home</span></a></li>
                                                    <li><a href="{{ route('about') }}"><span>About</span></a></li>
                                                    <li class="has-children">
                                                        <a href="{{ route('capabilities.index') }}"><span>Capabilities</span></a>
                                                        <ul class="megamenu megamenu--mega">
                                                            <li>
                                                                <h2 class="page-list-title">IT Services</h2>
                                                                <ul>
                                                                    <li><a href="{{ route('capabilities.index') }}">CCTV &amp; Security Solutions</a></li>
                                                                    <li><a href="{{ route('capabilities.index') }}">Access Control Systems</a></li>
                                                                    <li><a href="{{ route('capabilities.index') }}">Annual Maintenance Contracts (AMC)</a></li>
                                                                    <li><a href="{{ route('capabilities.index') }}">Software Licensing</a></li>
                                                                    <li><a href="{{ route('capabilities.index') }}">Network Storage Solutions</a></li>
                                                                    <li><a href="{{ route('capabilities.index') }}">Workstation Solutions</a></li>
                                                                    <li><a href="{{ route('capabilities.index') }}">Networking Solutions</a></li>
                                                                </ul>
                                                            </li>
                                                            <li>
                                                                <h2 class="page-list-title">Digital Services</h2>
                                                                <ul>
                                                                    <li><a href="{{ route('capabilities.index') }}">Website Design &amp; Development</a></li>
                                                                    <li><a href="{{ route('capabilities.index') }}">Web Application Development</a></li>
                                                                    <li><a href="{{ route('capabilities.index') }}">Ecommerce Development</a></li>
                                                                    <li><a href="{{ route('capabilities.index') }}">Mobile App Development</a></li>
                                                                    <li><a href="{{ route('capabilities.index') }}">Hybrid App Development</a></li>
                                                                    <li><a href="{{ route('capabilities.index') }}">Custom Software Development</a></li>
                                                                    <li><a href="{{ route('capabilities.index') }}">Digital Marketing Services</a></li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li><a href="{{ route('case-studies.index') }}"><span>Case Studies</span></a></li>
                                                    <li><a href="{{ route('blog.index') }}"><span>Blog</span></a></li>
                                                    <li><a href="{{ route('contact') }}"><span>Contact</span></a></li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Mobile menu trigger --}}
                        <div class="header-box">
                            <div class="mobile-navigation-icon d-block d-xl-none" id="mobile-menu-trigger">
                                <i></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Mobile menu overlay --}}
<div class="mobile-menu-overlay" id="mobile-menu-overlay">
    <div class="mobile-menu-overlay__inner">
        <div class="mobile-menu-overlay__header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6 col-8">
                        <div class="logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('assets/images/logo/Tectignis-IT-solution-logo.webp') }}"
                                    class="img-fluid" alt="Tectignis-IT-solution-logo">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 col-4">
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
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li class="has-children">
                        <a href="{{ route('capabilities.index') }}">Capabilities</a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('capabilities.index') }}"><span>Website Design &amp; Development</span></a></li>
                            <li><a href="{{ route('capabilities.index') }}"><span>Web Application Development</span></a></li>
                            <li><a href="{{ route('capabilities.index') }}"><span>E-commerce Development</span></a></li>
                            <li><a href="{{ route('capabilities.index') }}"><span>Mobile App Development</span></a></li>
                            <li><a href="{{ route('capabilities.index') }}"><span>Custom Software Development</span></a></li>
                            <li><a href="{{ route('capabilities.index') }}"><span>CCTV &amp; Security Solutions</span></a></li>
                            <li><a href="{{ route('capabilities.index') }}"><span>Networking Solutions</span></a></li>
                            <li><a href="{{ route('capabilities.index') }}"><span>Digital Marketing Services</span></a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('case-studies.index') }}">Case Studies</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
