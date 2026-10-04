{{--
    Site footer (spec §19): Company, Solutions, Industries, Resources, Global,
    Legal and Contact columns. Contact details and registration numbers come
    from the single CompanyProfile source; $footerIndustries from the composer.
--}}
<footer class="tx-footer" aria-label="Site footer">
    <div class="container">

        <div class="tx-footer__cta">
            <div>
                <h2 class="tx-footer__cta-title">Have a project in mind?</h2>
                <p class="tx-footer__cta-text">Talk to a solution engineer about software, AI, cloud, cybersecurity or IT infrastructure.</p>
            </div>
            <div class="tx-footer__cta-actions">
                <button type="button" class="tx-btn tx-btn--light js-consult-open" data-track="consultation_booking">Book a Technical Consultation</button>
                <a href="{{ route('case-studies.index') }}" class="tx-btn tx-btn--ghost-light">View Our Work</a>
            </div>
        </div>

        <div class="tx-footer__main">

            <div class="tx-footer__brand">
                <a href="{{ route('home') }}" class="tx-footer__logo">
                    <img src="{{ $company->logoDarkUrl() }}" width="150" height="45" alt="{{ $company->name() }}" loading="lazy">
                </a>
                <p class="tx-footer__tagline">{{ $company->tagline() }}</p>
                @if (count($company->socials()))
                    <ul class="tx-footer__socials">
                        @foreach ($company->socials() as $social)
                            <li>
                                <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }}" @if (str_contains($social['icon'], 'whatsapp')) data-track="whatsapp_click" @endif>
                                    <i class="{{ $social['icon'] }}" aria-hidden="true"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <nav class="tx-footer__col" aria-label="Company">
                <h3 class="tx-footer__heading">Company</h3>
                <ul class="tx-footer__links">
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('careers') }}">Careers</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </nav>

            <nav class="tx-footer__col" aria-label="Solutions">
                <h3 class="tx-footer__heading">Solutions</h3>
                <ul class="tx-footer__links">
                    @foreach (\App\Enums\Pillar::cases() as $pillar)
                        <li><a href="{{ $pillar->url() }}">{{ $pillar->label() }}</a></li>
                    @endforeach
                    <li><a href="{{ route('capabilities.show', 'saas-product-development') }}">SaaS Product Development</a></li>
                </ul>
            </nav>

            <nav class="tx-footer__col" aria-label="Industries">
                <h3 class="tx-footer__heading">Industries</h3>
                <ul class="tx-footer__links">
                    @foreach ($footerIndustries as $industry)
                        <li><a href="{{ route('industries.show', $industry->slug) }}">{{ $industry->name }}</a></li>
                    @endforeach
                    <li><a href="{{ route('industries.index') }}">All Industries</a></li>
                </ul>
            </nav>

            <nav class="tx-footer__col" aria-label="Resources">
                <h3 class="tx-footer__heading">Resources</h3>
                <ul class="tx-footer__links">
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ route('case-studies.index') }}">Case Studies</a></li>
                    <li><a href="{{ route('technology-insights') }}">Guides &amp; Insights</a></li>
                    <li><a href="{{ route('downloads') }}">Downloads</a></li>
                    <li><a href="{{ route('faqs') }}">FAQs</a></li>
                </ul>
            </nav>

            <div class="tx-footer__col">
                <h3 class="tx-footer__heading">Global</h3>
                <ul class="tx-footer__links tx-footer__markets">
                    @foreach ($company->markets() as $market)
                        <li>
                            @if ($market['url'])
                                <a href="{{ $market['url'] }}">{{ $market['name'] }}</a>
                            @else
                                <span>{{ $market['name'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="tx-footer__col tx-footer__contact">
                <h3 class="tx-footer__heading">Contact</h3>
                <ul class="tx-footer__contact-list">
                    <li>
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <a href="mailto:{{ $company->email() }}" data-track="email_click">{{ $company->email() }}</a>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt" aria-hidden="true"></i>
                        <a href="{{ $company->phoneHref() }}" data-track="phone_click">{{ $company->phone() }}</a>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                        <address>{{ $company->address() }}</address>
                    </li>
                    @if ($company->businessHours())
                        <li>
                            <i class="far fa-clock" aria-hidden="true"></i>
                            <span>{{ $company->businessHours() }}</span>
                        </li>
                    @endif
                </ul>
            </div>

        </div>

        <div class="tx-footer__bottom">
            <p class="tx-footer__copyright">&copy; {{ date('Y') }} {{ $company->legalName() }}. All rights reserved.</p>

            <nav class="tx-footer__legal" aria-label="Legal">
                <a href="{{ route('legal.show', 'privacy-policy') }}">Privacy Policy</a>
                <a href="{{ route('legal.show', 'terms-and-conditions') }}">Terms &amp; Conditions</a>
                <a href="{{ route('legal.show', 'cookie-policy') }}">Cookie Policy</a>
                <a href="{{ route('sitemap') }}">Sitemap</a>
            </nav>

            @if ($company->gstin() || $company->cin())
                <p class="tx-footer__reg">
                    @if ($company->gstin()) <span>GSTIN: {{ $company->gstin() }}</span> @endif
                    @if ($company->cin()) <span>CIN: {{ $company->cin() }}</span> @endif
                </p>
            @endif
        </div>

    </div>
</footer>
