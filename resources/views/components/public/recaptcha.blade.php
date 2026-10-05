@props(['class' => '', 'theme' => 'light'])

@php $recaptcha = app(\App\Services\RecaptchaService::class); @endphp

{{-- Google reCAPTCHA v2 checkbox (spec §28.3). Renders nothing while an
     administrator has CAPTCHA switched off; the site key is the only key that
     ever reaches the page. --}}
@if ($recaptcha->isEnabled())
    <div {{ $attributes->merge(['class' => trim('form-recaptcha '.$class)]) }}>
        <div class="g-recaptcha" data-sitekey="{{ $recaptcha->siteKey() }}" data-theme="{{ $theme }}"></div>
    </div>

    @once
        @push('scripts')
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        @endpush
    @endonce
@endif
