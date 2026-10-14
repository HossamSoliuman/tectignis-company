<!DOCTYPE html>
<html class="no-js" lang="en" dir="ltr">

<head>
    @include('layouts.partials.head')
</head>

<body>

    @include('layouts.partials.body-start')

    {{-- JSON-LD (per-page override possible via stack) --}}
    @stack('json-ld')
    <x-seo.json-ld />

    {{-- Header --}}
    <x-public.header />

    @hasSection('breadcrumb')
        @yield('breadcrumb')
    @endif

    <div id="main-wrapper">
        <main id="main-content" class="site-wrapper-reveal" tabindex="-1">
            @yield('content')
        </main>

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

    @include('layouts.partials.scripts')

</body>

</html>
