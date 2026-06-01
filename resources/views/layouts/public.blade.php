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

    {{-- GA4 --}}
    @php $ga4Id = \App\Models\Setting::get('ga4_id'); @endphp
    @if ($ga4Id)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $ga4Id }}');
        </script>
    @endif

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('assets/images/favicon.webp') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    {{-- Vendor, plugin & theme CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/vendor.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')
</head>

<body>

    {{-- JSON-LD (per-page override possible via stack) --}}
    @stack('json-ld')
    <x-seo.json-ld />

    {{-- Header --}}
    <x-public.header />

    @hasSection('breadcrumb')
        @yield('breadcrumb')
    @endif

    <div id="main-wrapper">
        <div class="site-wrapper-reveal">
            @yield('content')
        </div>

        {{-- Footer --}}
        <x-public.footer />
    </div>

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
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/right-click.js') }}"></script>
    @stack('scripts')

</body>

</html>
