{{--
    Site footer (spec §19): Company, Solutions, Industries, Resources, Global,
    Legal and Contact columns. Contact details and registration numbers come
    from the single CompanyProfile source; $footerIndustries from the composer.
--}}
<footer class="tx-footer" aria-label="{{ __('footer.label') }}">
    <div class="container">

        <div class="tx-footer__cta">
            <div>
                <h2 class="tx-footer__cta-title">{{ __('footer.cta_title') }}</h2>
                <p class="tx-footer__cta-text">{{ __('footer.cta_text') }}</p>
            </div>
            <div class="tx-footer__cta-actions">
                <button type="button" class="tx-btn tx-btn--light js-consult-open">{{ __('nav.book_consultation') }}</button>
                <a href="{{ route('case-studies.index') }}" class="tx-btn tx-btn--ghost-light">{{ __('nav.view_our_work') }}</a>
            </div>
        </div>

        <div class="tx-footer__main">

            <div class="tx-footer__brand">
                <a href="{{ localized_route('home') }}" class="tx-footer__logo">
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

            <nav class="tx-footer__col" aria-label="{{ __('footer.company') }}">
                <h3 class="tx-footer__heading">{{ __('footer.company') }}</h3>
                <ul class="tx-footer__links">
                    <li><a href="{{ route('about') }}">{{ __('footer.about') }}</a></li>
                    <li><a href="{{ route('careers') }}">{{ __('footer.careers') }}</a></li>
                    <li><a href="{{ localized_route('contact') }}">{{ __('footer.contact') }}</a></li>
                </ul>
            </nav>

            <nav class="tx-footer__col" aria-label="{{ __('footer.solutions') }}">
                <h3 class="tx-footer__heading">{{ __('footer.solutions') }}</h3>
                <ul class="tx-footer__links">
                    @foreach (\App\Enums\Pillar::cases() as $pillar)
                        <li><a href="{{ $pillar->url() }}">{{ $pillar->label() }}</a></li>
                    @endforeach
                    <li><a href="{{ route('capabilities.show', 'saas-product-development') }}">{{ __('footer.saas') }}</a></li>
                </ul>
            </nav>

            <nav class="tx-footer__col" aria-label="{{ __('footer.industries') }}">
                <h3 class="tx-footer__heading">{{ __('footer.industries') }}</h3>
                <ul class="tx-footer__links">
                    @foreach ($footerIndustries as $industry)
                        <li><a href="{{ route('industries.show', $industry->slug) }}">{{ $industry->name }}</a></li>
                    @endforeach
                    <li><a href="{{ route('industries.index') }}">{{ __('footer.all_industries') }}</a></li>
                </ul>
            </nav>

            <nav class="tx-footer__col" aria-label="{{ __('footer.resources') }}">
                <h3 class="tx-footer__heading">{{ __('footer.resources') }}</h3>
                <ul class="tx-footer__links">
                    <li><a href="{{ route('blog.index') }}">{{ __('footer.blog') }}</a></li>
                    <li><a href="{{ route('case-studies.index') }}">{{ __('footer.case_studies') }}</a></li>
                    <li><a href="{{ route('technology-insights') }}">{{ __('footer.insights') }}</a></li>
                    <li><a href="{{ route('downloads') }}">{{ __('footer.downloads') }}</a></li>
                    <li><a href="{{ route('faqs') }}">{{ __('footer.faqs') }}</a></li>
                </ul>
            </nav>

            <div class="tx-footer__col">
                <h3 class="tx-footer__heading">{{ __('footer.global') }}</h3>
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
                <h3 class="tx-footer__heading">{{ __('footer.contact') }}</h3>
                <ul class="tx-footer__contact-list">
                    <li>
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <a href="mailto:{{ $company->email() }}" data-track="email_click">{{ $company->email() }}</a>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt" aria-hidden="true"></i>
                        <a href="{{ $company->phoneHref() }}" data-track="phone_click"><span dir="ltr">{{ $company->phone() }}</span></a>
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
            <p class="tx-footer__copyright">&copy; {{ date('Y') }} {{ $company->legalName() }}. {{ __('footer.rights') }}</p>

            <nav class="tx-footer__legal" aria-label="{{ __('footer.legal') }}">
                <a href="{{ localized_route('legal.show', 'privacy-policy') }}">{{ __('footer.privacy') }}</a>
                <a href="{{ localized_route('legal.show', 'terms-and-conditions') }}">{{ __('footer.terms') }}</a>
                <a href="{{ localized_route('legal.show', 'cookie-policy') }}">{{ __('footer.cookies') }}</a>
                <a href="{{ route('sitemap') }}">{{ __('footer.sitemap') }}</a>
                @if ($showIpGeolocationCredit)
                    <a href="https://db-ip.com" target="_blank" rel="noopener">{{ __('footer.ip_geolocation') }}</a>
                @endif
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
