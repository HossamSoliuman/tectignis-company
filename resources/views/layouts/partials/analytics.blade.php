{{-- Analytics (docs/international-lead-generation/tracking.md). With a Tag
     Manager container set, GA4 is configured inside it; GA4's own tag loads
     only when there is no container, so GA4 never counts a page twice.
     window.txTrack() sends an event to whichever of the two is loaded and
     never carries personal data. --}}
@php
    $gtmId = $siteSettings['site_gtm_id'] ?? null;
    $ga4Id = $gtmId ? null : ($siteSettings['site_ga_id'] ?? null);
@endphp
<script>window.dataLayer = window.dataLayer || [];</script>
@if ($gtmId)
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer',@js($gtmId));</script>
@elseif ($ga4Id)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
    <script>
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', @js($ga4Id));
    </script>
@endif
<script>
    window.txTrack = function (event, params) {
        @if ($gtmId)
            window.dataLayer.push(Object.assign({ event: event }, params || {}));
        @elseif ($ga4Id)
            gtag('event', event, params || {});
        @endif
    };

    // Contact actions are tracked by what they are, so a call, mail or
    // WhatsApp link added later is counted without extra markup.
    document.addEventListener('click', function (e) {
        var target = e.target.closest && e.target.closest('[data-track], .js-consult-open, a[href^="tel:"], a[href^="mailto:"], a[href*="wa.me/"], a[href*="whatsapp.com/"]');
        if (!target) { return; }

        var href = target.getAttribute('href') || '';
        var event = target.getAttribute('data-track')
            || (target.classList.contains('js-consult-open') ? 'consultation_click'
                : href.indexOf('tel:') === 0 ? 'phone_click'
                : href.indexOf('mailto:') === 0 ? 'email_click'
                : 'whatsapp_click');

        window.txTrack(event, {
            page_language: document.documentElement.lang,
            link_location: target.closest('header, #mobile-menu-overlay') ? 'header' : (target.closest('footer') ? 'footer' : 'content'),
        });
    });
</script>
