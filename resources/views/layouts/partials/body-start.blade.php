{{-- Google Tag Manager (noscript) --}}
@php $siteSettings ??= \App\Models\Setting::values(); @endphp
@if ($siteSettings['site_gtm_id'] ?? false)
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $siteSettings['site_gtm_id'] }}"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif
