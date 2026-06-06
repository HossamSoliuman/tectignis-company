<div class="ft-dark__main">
    <div class="container">
        <div class="row ft-dark__row">

            {{-- Column 1: logo + description + socials --}}
            <div class="col-xl-3 col-lg-3 col-md-6 ft-dark__col">
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
                        <a href="https://in.linkedin.com/company/tectignis" target="_blank" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://x.com/ItTectignis" target="_blank" aria-label="X / Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/tectignisofficial/" target="_blank" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/tectignisofficial/" target="_blank" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Column 2: Company --}}
            <div class="col-xl col-lg col-md-3 col-sm-6 ft-dark__col">
                <h6 class="ft-dark__heading">Company</h6>
                <ul class="ft-dark__links">
                    <li><a href="{{ route('about') }}">Why Tectignis</a></li>
                    <li><a href="{{ route('services.index') }}">Our Services</a></li>
                    <li><a href="{{ route('case-studies.index') }}">Case Studies</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ route('careers') }}">Careers</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                </ul>
            </div>

            {{-- Column 3: Capabilities --}}
            <div class="col-xl col-lg col-md-3 col-sm-6 ft-dark__col">
                <h6 class="ft-dark__heading">Capabilities</h6>
                <ul class="ft-dark__links">
                    <li><a href="{{ route('capabilities.index') }}">Software Development</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Mobile App Development</a></li>
                    <li><a href="{{ route('capabilities.index') }}">AI &amp; Automation</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Cloud &amp; Infrastructure</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Cybersecurity</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Smart Security</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Digital Marketing</a></li>
                </ul>
            </div>

            {{-- Column 4: Solutions --}}
            <div class="col-xl col-lg col-md-3 col-sm-6 ft-dark__col">
                <h6 class="ft-dark__heading">Solutions</h6>
                <ul class="ft-dark__links">
                    <li><a href="{{ route('services.index') }}">ERP Solutions</a></li>
                    <li><a href="{{ route('services.index') }}">CRM Solutions</a></li>
                    <li><a href="{{ route('services.index') }}">HRMS Solutions</a></li>
                    <li><a href="{{ route('services.index') }}">AI Solutions</a></li>
                    <li><a href="{{ route('services.index') }}">Automation Solutions</a></li>
                    <li><a href="{{ route('services.index') }}">Smart Security Solutions</a></li>
                    <li><a href="{{ route('services.index') }}">Digital Marketing</a></li>
                </ul>
            </div>

            {{-- Column 5: Industries --}}
            <div class="col-xl col-lg col-md-3 col-sm-6 ft-dark__col">
                <h6 class="ft-dark__heading">Industries</h6>
                <ul class="ft-dark__links">
                    <li><a href="{{ route('industries.index') }}">Manufacturing</a></li>
                    <li><a href="{{ route('industries.index') }}">Retail &amp; eCommerce</a></li>
                    <li><a href="{{ route('industries.index') }}">BFSI &amp; NBFCs</a></li>
                    <li><a href="{{ route('industries.index') }}">Healthcare</a></li>
                    <li><a href="{{ route('industries.index') }}">Finance &amp; Banking</a></li>
                    <li><a href="{{ route('industries.index') }}">Data &amp; Analytics</a></li>
                </ul>
            </div>

            {{-- Column 6: Quick Links --}}
            <div class="col-xl col-lg col-md-3 col-sm-6 ft-dark__col">
                <h6 class="ft-dark__heading">Quick Links</h6>
                <ul class="ft-dark__links">
                    <li><a href="{{ route('sitemap') }}">Sitemap</a></li>
                    <li><a href="{{ route('technology-insights') }}">Technology Insights</a></li>
                    <li><a href="{{ route('downloads') }}">Downloads</a></li>
                    <li><a href="{{ route('faqs') }}">FAQs</a></li>
                </ul>
            </div>

        </div>
    </div>
</div>

<div class="ft-dark__copy">
    <div class="container">
        <div class="ft-dark__copy-inner">
            <span class="ft-dark__copy-text">
                &copy; {{ date('Y') }}
                <a href="{{ route('home') }}">Tectignis IT Solutions Pvt. Ltd.</a>
                All Rights Reserved.
            </span>
            <span class="ft-dark__copy-reg">
                GST: 27AAHFT31725F1Z3
            </span>
            <span class="ft-dark__copy-reg">
                CIN: U72900MH2023PTC401102
            </span>
        </div>
    </div>
</div>
