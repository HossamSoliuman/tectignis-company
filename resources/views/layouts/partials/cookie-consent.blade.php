{{-- Cookie consent banner. Shown on load only to visitors who must opt in
     (window.txConsentRequired, set in layouts.partials.analytics) and have
     not chosen yet; any [data-consent-open] control reopens it. The choice is
     stored in a first-party cookie and passed to Consent Mode and the Meta
     Pixel straight away. --}}
<div class="tx-consent" id="tx-consent" role="region" aria-label="{{ __('consent.label') }}" hidden>
    <p class="tx-consent__text">
        {{ __('consent.text') }}
        <a href="{{ localized_route('legal.show', 'cookie-policy') }}">{{ __('consent.policy') }}</a>
    </p>
    <div class="tx-consent__actions">
        <button type="button" class="tx-consent__button" data-consent-choice="denied">{{ __('consent.reject') }}</button>
        <button type="button" class="tx-consent__button tx-consent__button--primary" data-consent-choice="granted">{{ __('consent.accept') }}</button>
    </div>
</div>
<script>
    (function () {
        var banner = document.getElementById('tx-consent');
        if (!banner) { return; }

        var show = function () { banner.hidden = false; };
        var hide = function () { banner.hidden = true; };

        if (window.txConsentRequired && !window.txConsent) { show(); }

        document.addEventListener('click', function (e) {
            if (e.target.closest('[data-consent-open]')) {
                e.preventDefault();
                show();
                banner.querySelector('[data-consent-choice="granted"]').focus();

                return;
            }

            var button = e.target.closest('[data-consent-choice]');
            if (!button) { return; }

            var value = button.getAttribute('data-consent-choice');
            document.cookie = @js(\App\Support\Consent::COOKIE) + '=' + value + '; path=/; max-age=' + (@js(\App\Support\Consent::LIFETIME_DAYS) * 86400) + '; SameSite=Lax' + (location.protocol === 'https:' ? '; Secure' : '');
            window.txConsent = value;

            if (typeof gtag === 'function') {
                gtag('consent', 'update', { ad_storage: value, ad_user_data: value, ad_personalization: value, analytics_storage: value });
            }
            if (typeof fbq === 'function') {
                fbq('consent', value === 'granted' ? 'grant' : 'revoke');
            }

            hide();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !banner.hidden && window.txConsent) { hide(); }
        });
    })();
</script>
