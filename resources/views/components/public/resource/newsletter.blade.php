<div class="res-widget res-news">
    <h4 class="res-widget__title">Stay <span>Updated</span></h4>
    <p class="res-news__text">Subscribe to our newsletter and get the latest insights delivered to your inbox.</p>
    @if (session('newsletter_status'))
        <p class="res-news__success">{{ session('newsletter_status') }}</p>
    @endif
    <form class="res-news__form" action="{{ route('newsletter.subscribe') }}" method="post">
        @csrf
        <input type="email" name="email" placeholder="Enter your email address" required>
        <x-public.recaptcha theme="dark" />
        <button type="submit">Subscribe Now <i class="fas fa-arrow-right" aria-hidden="true"></i></button>
        @if ($errors->hasAny(['email', \App\Services\RecaptchaService::RESPONSE_FIELD]) && ! old('form_id') && ! old('download_id') && ! old('con_name'))
            <p class="res-news__error" role="alert">{{ $errors->first('email') ?: $errors->first(\App\Services\RecaptchaService::RESPONSE_FIELD) }}</p>
        @endif
    </form>
    <p class="res-news__note">We respect your privacy.</p>
</div>
