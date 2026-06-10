<footer class="ft-dark__main" aria-label="Site Footer">
    <div class="container">
        <div class="row ft-dark__row">

            {{-- Column 1: Brand --}}
            <div class="col-xl-3 col-lg-4 col-md-12 ft-dark__col ft-dark__brand">
                <a href="{{ route('home') }}" class="ft-dark__logo">
                    <img src="{{ asset('assets/images/logo/Tectignis-IT-solution-logo.webp') }}"
                         width="160" height="48" class="img-fluid" alt="Tectignis IT Solutions">
                </a>
                <p class="ft-dark__tagline">
                    Navi Mumbai's trusted partner for software, AI, cloud, cybersecurity and smart
                    security solutions. Empowering businesses with innovative technology.
                </p>
                <ul class="ft-dark__socials">
                    <li>
                        <a href="https://in.linkedin.com/company/tectignis" target="_blank" rel="noopener" aria-label="Visit LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://x.com/ItTectignis" target="_blank" rel="noopener" aria-label="Visit X / Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/tectignisofficial/" target="_blank" rel="noopener" aria-label="Visit Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/tectignisofficial/" target="_blank" rel="noopener" aria-label="Visit Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Column 2: Company --}}
            <nav class="col-xl col-lg-2 col-md-4 col-sm-6 ft-dark__col" aria-label="Company">
                <h6 class="ft-dark__heading">Company</h6>
                <ul class="ft-dark__links">
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('about') }}">Why Tectignis</a></li>
                    <li><a href="{{ route('careers') }}">Careers</a></li>
                    <li><a href="{{ route('technology-insights') }}">News &amp; Insights</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                </ul>
            </nav>

            {{-- Column 3: Capabilities --}}
            <nav class="col-xl col-lg-2 col-md-4 col-sm-6 ft-dark__col" aria-label="Capabilities">
                <h6 class="ft-dark__heading">Capabilities</h6>
                <ul class="ft-dark__links">
                    <li><a href="{{ route('capabilities.index') }}">Software Development</a></li>
                    <li><a href="{{ route('capabilities.index') }}">AI &amp; Automation</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Cloud &amp; Infrastructure</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Data &amp; Analytics</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Cybersecurity</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Smart Security</a></li>
                </ul>
            </nav>

            {{-- Column 4: Industries --}}
            <nav class="col-xl col-lg-2 col-md-4 col-sm-6 ft-dark__col" aria-label="Industries">
                <h6 class="ft-dark__heading">Industries</h6>
                <ul class="ft-dark__links">
                    <li><a href="{{ route('industries.index') }}">Manufacturing</a></li>
                    <li><a href="{{ route('industries.index') }}">Healthcare</a></li>
                    <li><a href="{{ route('industries.index') }}">Retail &amp; eCommerce</a></li>
                    <li><a href="{{ route('industries.index') }}">Logistics</a></li>
                    <li><a href="{{ route('industries.index') }}">Finance &amp; Banking</a></li>
                    <li><a href="{{ route('industries.index') }}">Education</a></li>
                </ul>
            </nav>

            {{-- Column 5: Resources --}}
            <nav class="col-xl col-lg-3 col-md-6 col-sm-6 ft-dark__col" aria-label="Resources">
                <h6 class="ft-dark__heading">Resources</h6>
                <ul class="ft-dark__links">
                    <li><a href="{{ route('case-studies.index') }}">Case Studies</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ route('technology-insights') }}">Technology Insights</a></li>
                    <li><a href="{{ route('downloads') }}">Downloads</a></li>
                    <li><a href="{{ route('faqs') }}">FAQs</a></li>
                    <li><a href="{{ route('sitemap') }}">Sitemap</a></li>
                </ul>
            </nav>

            {{-- Column 6: Contact --}}
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 ft-dark__col">
                <h6 class="ft-dark__heading">Contact Us</h6>
                <ul class="ft-dark__contact">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Navi Mumbai,<br>Maharashtra, India</span>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <a href="tel:+919987705688">+91 9987705688</a>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:info@tectignis.in">info@tectignis.in</a>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        <span>Mon - Sat: 9AM - 8PM</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</footer>

<div class="ft-dark__copy">
    <div class="container">
        <div class="ft-dark__copy-inner">
            <span class="ft-dark__copy-text">
                &copy; {{ date('Y') }}
                <a href="{{ route('home') }}">Tectignis IT Solutions Pvt. Ltd.</a>
                All Rights Reserved.
            </span>
            <span class="ft-dark__copy-reg">GSTIN: 27AAHFT31725F1Z3</span>
            <span class="ft-dark__copy-reg">CIN: U72900MH2023PTC401102</span>
            <span class="ft-dark__copy-legal">
                <a href="{{ route('legal.show', 'privacy-policy') }}">Privacy Policy</a>
                <a href="{{ route('legal.show', 'terms-and-conditions') }}">Terms of Service</a>
            </span>
        </div>
    </div>
</div>
