<!DOCTYPE html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="revisit-after" content="7 days">
    <meta property="og:locale" content="en_US" />
    <meta property="twitter:account_id" content="29759031" />
    <meta name="email" content="info@tectignis.in">
    <meta name="robots" content="index, follow">
    <meta name="copyright" content="Copyright Tectignis IT solutions - all rights reserved" />
    <meta name="document-type" content="Public">
    <meta name="document-distribution" content="Global">
    <meta property="fb:app_id" content="560418770098574" />
    <meta name="geo.region" content="IN-MH">
    <meta name="geo.placename" content="Navi Mumbai">
    <meta name="geo.position" content="19.02993219206727;73.06512509395156">
    <meta name="ICBM" content="19.02993219206727,73.06512509395156" />

    {{-- Page title + SEO meta (per-page) --}}
    <title>@yield('title', 'Tectignis IT Solutions | Software, AI, Cloud & Security')</title>
    @yield('seo')
    @stack('head')

    @php $siteSettings = \App\Models\Setting::values(); @endphp

    {{-- Google Search Console verification --}}
    @if ($siteSettings['google_search_console_verification'] ?? false)
        <meta name="google-site-verification" content="{{ $siteSettings['google_search_console_verification'] }}">
    @endif

    {{-- Google Tag Manager --}}
    @if ($siteSettings['site_gtm_id'] ?? false)
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{ $siteSettings['site_gtm_id'] }}');</script>
    @endif

    {{-- GA4 --}}
    @php $ga4Id = $siteSettings['site_ga_id'] ?? null; @endphp
    @if ($ga4Id)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $ga4Id }}');
        </script>
    @endif

    {{-- Meta Pixel --}}
    @if ($siteSettings['meta_pixel_id'] ?? false)
        <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $siteSettings['meta_pixel_id'] }}');
        fbq('track', 'PageView');</script>
        <noscript><img height="1" width="1" style="display:none" alt=""
            src="https://www.facebook.com/tr?id={{ $siteSettings['meta_pixel_id'] }}&ev=PageView&noscript=1"></noscript>
    @endif

    {{-- Favicon --}}
    <link rel="icon" href="{{ \App\Models\Setting::imageUrl($siteSettings['site_favicon'] ?? null, 'site_favicon') ?? asset('assets/images/favicon.webp') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;family=Poppins:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">

    {{-- Vendor, plugin & theme CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/vendor.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}?v={{ filemtime(public_path('assets/css/custom.css')) }}">
    @stack('styles')
</head>

<body>

    {{-- Google Tag Manager (noscript) --}}
    @if ($siteSettings['site_gtm_id'] ?? false)
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $siteSettings['site_gtm_id'] }}"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    {{-- JSON-LD (per-page override possible via stack) --}}
    @stack('json-ld')
    <x-seo.json-ld />

    {{-- Header --}}
    <x-public.header />

    @hasSection('breadcrumb')
        @yield('breadcrumb')
    @endif

    <div id="main-wrapper">
        <div class=" site-wrapper-reveal">
            @yield('content')
        </div>

        {{-- Footer --}}
        <x-public.footer />
    </div>

    {{-- Request Consultation modal --}}
    <x-public.consultation-modal />

    {{-- Page-level modals (rendered at body level so they escape the
         `.site-wrapper-reveal` stacking context and layer above the header) --}}
    @stack('modals')

    {{-- Scroll top --}}
    <a href="#" class="scroll-top" id="scroll-top">
        <i class="arrow-top fas fa-chevron-up"></i>
        <i class="arrow-bottom fas fa-chevron-up"></i>
    </a>

    {{-- JS --}}
    <script src="{{ asset('assets/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery-migrate-3.3.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/plugins.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}?v={{ filemtime(public_path('assets/js/main.js')) }}"></script>

    {{-- reCAPTCHA v3: attach a token to lead forms before submit --}}
    @if ($siteSettings['recaptcha_site_key'] ?? false)
        <script src="https://www.google.com/recaptcha/api.js?render={{ $siteSettings['recaptcha_site_key'] }}"></script>
        <script>
            document.querySelectorAll('form[action*="contact"], form[action*="careers"], form[action*="downloads"]').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.dataset.recaptchaDone) { return; }
                    event.preventDefault();
                    grecaptcha.ready(function () {
                        grecaptcha.execute('{{ $siteSettings['recaptcha_site_key'] }}', { action: 'lead' }).then(function (token) {
                            var input = form.querySelector('input[name="g-recaptcha-response"]');
                            if (!input) {
                                input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'g-recaptcha-response';
                                form.appendChild(input);
                            }
                            input.value = token;
                            form.dataset.recaptchaDone = '1';
                            form.submit();
                        });
                    });
                });
            });
        </script>
    @endif
    @stack('scripts')

</body>

</html>
