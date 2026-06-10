<div class="res-widget res-news">
    <h4 class="res-widget__title">Stay <span>Updated</span></h4>
    <p class="res-news__text">Subscribe to our newsletter and get the latest insights delivered to your inbox.</p>
    @if (session('newsletter_status'))
        <p class="res-news__success">{{ session('newsletter_status') }}</p>
    @endif
    <form class="res-news__form" action="{{ route('newsletter.subscribe') }}" method="post">
        @csrf
        <input type="email" name="email" placeholder="Enter your email address" required>
        <button type="submit">Subscribe Now <i class="fas fa-arrow-right" aria-hidden="true"></i></button>
    </form>
    <p class="res-news__note">We respect your privacy.</p>
</div>
