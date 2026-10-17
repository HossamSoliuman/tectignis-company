<!DOCTYPE html>
<html class="no-js" lang="{{ \App\Support\Locales::current() }}" dir="{{ \App\Support\Locales::direction() }}">

<head>
    @include('layouts.partials.head')
</head>

{{-- Google Ads landing pages: the same head, analytics and assets as the main
     site, but no site navigation, consultation modal or full footer, so the
     only next step on the page is the enquiry form. --}}
<body class="lp-body">

    @include('layouts.partials.body-start')

    @stack('json-ld')
    <x-seo.json-ld />

    <a class="tx-skip-link" href="#main-content">{{ __('nav.skip_to_content') }}</a>

    <header class="lp-header">
        <div class="container lp-header__inner">
            <img class="lp-header__logo" src="{{ $company->logoUrl() }}" width="160" height="48" alt="{{ $company->name() }}">

            <div class="lp-header__actions">
                <a class="lp-header__phone" href="{{ $company->phoneHref() }}" data-track="phone_click">
                    <i class="fas fa-phone-alt" aria-hidden="true"></i>
                    <span class="lp-header__phone-number" dir="ltr">{{ $company->phone() }}</span>
                    <span class="sr-only">{{ __('common.call', ['phone' => $company->phone()]) }}</span>
                </a>
                <x-public.language-switcher />
                @hasSection('header-cta')
                    @yield('header-cta')
                @endif
            </div>
        </div>
    </header>

    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>

    <footer class="lp-footer">
        <div class="container">
            <ul class="lp-footer__contacts">
                <li>
                    <i class="fas fa-phone-alt" aria-hidden="true"></i>
                    <a href="{{ $company->phoneHref() }}" data-track="phone_click"><span dir="ltr">{{ $company->phone() }}</span></a>
                </li>
                <li>
                    <i class="fas fa-envelope" aria-hidden="true"></i>
                    <a href="mailto:{{ $company->email() }}" data-track="email_click">{{ $company->email() }}</a>
                </li>
                @if ($company->whatsappUrl())
                    <li>
                        <i class="fab fa-whatsapp" aria-hidden="true"></i>
                        <a href="{{ $company->whatsappUrl() }}" target="_blank" rel="noopener" data-track="whatsapp_click">{{ __('common.whatsapp') }}</a>
                    </li>
                @endif
                <li>
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    <address>{{ $company->address() }}</address>
                </li>
            </ul>

            <div class="lp-footer__bottom">
                <p class="lp-footer__copyright">&copy; {{ date('Y') }} {{ $company->legalName() }}. {{ __('footer.rights') }}</p>
                <nav class="lp-footer__links" aria-label="{{ __('footer.legal') }}">
                    <a href="{{ localized_route('legal.show', 'privacy-policy') }}" target="_blank" rel="noopener">{{ __('footer.privacy') }}</a>
                    <a href="{{ localized_route('legal.show', 'terms-and-conditions') }}" target="_blank" rel="noopener">{{ __('footer.terms') }}</a>
                    @if ($showIpGeolocationCredit)
                        <a href="https://db-ip.com" target="_blank" rel="noopener">{{ __('footer.ip_geolocation') }}</a>
                    @endif
                </nav>
            </div>
        </div>
    </footer>

    @stack('modals')

    @include('layouts.partials.scripts')

</body>

</html>
