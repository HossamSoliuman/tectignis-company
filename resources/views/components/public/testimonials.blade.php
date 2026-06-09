@props(['testimonials' => null])

@php
    $defaultItems = collect([
        ['name' => 'Manufacturing Client', 'role' => 'Operations Head', 'rating' => 5, 'quote' => 'Tectignis delivered our ERP solution on time and exceeded our expectations. Highly professional team and excellent support.'],
        ['name' => 'Healthcare Client',    'role' => 'IT Manager',       'rating' => 5, 'quote' => 'Their cloud migration and security expertise helped us achieve better performance and stronger security.'],
        ['name' => 'Retail Client',        'role' => 'Business Owner',   'rating' => 5, 'quote' => 'Great experience working with Tectignis. Their AI automation solution improved our business efficiency significantly.'],
    ]);

    $items = ($testimonials && $testimonials->isNotEmpty()) ? $testimonials : $defaultItems;

    $avatarThemes = ['pink', 'indigo', 'emerald', 'violet', 'blue', 'amber'];
    $avatarIcons  = [
        'pink'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18m2.25-18v18M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>',
        'indigo'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M3 6.75h16.5M3 12h16.5M12 3v18M7.5 3v18M16.5 3v18"/>',
        'emerald' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>',
        'violet'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006-3.75 3.75m-15.75 0a2.18 2.18 0 0 1-.75-1.661V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"/>',
        'blue'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0H3"/>',
        'amber'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>',
    ];

    $totalItems = count($items);
@endphp

<section class="testi-section" aria-label="Client Testimonials">
    <div class="container">

        {{-- Header --}}
        <div class="testi-header">
            <p class="testi-pretitle">CLIENT TESTIMONIALS</p>
            <h2 class="testi-title">What Our Clients Say</h2>
            <p class="testi-subtitle">Trusted by businesses across industries. Here's what our clients have to say about their experience.</p>
        </div>

        {{-- Carousel --}}
        <div class="testi-carousel-wrap">

            <button class="testi-nav testi-nav--prev" aria-label="Previous slide">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
            </button>

            <div class="testi-track-outer" aria-live="polite">
                <div class="testi-track">
                    @foreach ($items as $t)
                        @php
                            $t     = is_array($t) ? (object) $t : $t;
                            $theme = $avatarThemes[$loop->index % count($avatarThemes)];
                            $role  = $t->role ?? $t->designation ?? $t->position ?? '';
                        @endphp
                        <div class="testi-card" role="group" aria-label="Testimonial {{ $loop->iteration }}">
                            <span class="testi-card__quote" aria-hidden="true">&ldquo;</span>

                            <div class="testi-card__stars" aria-hidden="true">
                                @for ($i = 0; $i < min(5, (int) ($t->rating ?? 5)); $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"/>
                                    </svg>
                                @endfor
                            </div>

                            <p class="testi-card__text">{{ $t->quote }}</p>

                            <hr class="testi-card__divider">

                            <div class="testi-card__client">
                                <div class="testi-card__avatar testi-avatar--{{ $theme }}" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        {!! $avatarIcons[$theme] !!}
                                    </svg>
                                </div>
                                <div>
                                    <p class="testi-card__name">{{ $t->name }}</p>
                                    @if ($role)
                                        <p class="testi-card__role">{{ $role }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <button class="testi-nav testi-nav--next" aria-label="Next slide">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
            </button>

        </div>

        {{-- Pagination dots --}}
        <div class="testi-dots" role="tablist" aria-label="Testimonial slides">
            @for ($i = 0; $i < $totalItems; $i++)
                <button
                    class="testi-dot {{ $i === 0 ? 'testi-dot--active' : '' }}"
                    role="tab"
                    aria-label="Go to slide {{ $i + 1 }}"
                    aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                ></button>
            @endfor
        </div>

    </div>
</section>

<script>
(function () {
    const section    = document.querySelector('.testi-section');
    if (!section) return;

    const trackOuter = section.querySelector('.testi-track-outer');
    const track      = section.querySelector('.testi-track');
    const prevBtn    = section.querySelector('.testi-nav--prev');
    const nextBtn    = section.querySelector('.testi-nav--next');
    const dots       = Array.from(section.querySelectorAll('.testi-dot'));
    const cards      = Array.from(track.querySelectorAll('.testi-card'));

    let current = 0;

    function visibleCount() {
        const w = trackOuter.offsetWidth;
        if (w >= 840) return 3;
        if (w >= 540) return 2;
        return 1;
    }

    function maxIndex() {
        return Math.max(0, cards.length - visibleCount());
    }

    function goTo(idx) {
        idx = Math.max(0, Math.min(idx, maxIndex()));
        current = idx;

        const cardW = cards[0] ? cards[0].offsetWidth : 0;
        const gap   = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 24;

        track.style.transform = 'translateX(-' + (current * (cardW + gap)) + 'px)';

        dots.forEach(function (dot, i) {
            var active = i === current;
            dot.classList.toggle('testi-dot--active', active);
            dot.setAttribute('aria-selected', active ? 'true' : 'false');
        });

        if (prevBtn) prevBtn.disabled = current === 0;
        if (nextBtn) nextBtn.disabled = current >= maxIndex();
    }

    if (prevBtn) prevBtn.addEventListener('click', function () { goTo(current - 1); });
    if (nextBtn) nextBtn.addEventListener('click', function () { goTo(current + 1); });

    dots.forEach(function (dot, i) {
        dot.addEventListener('click', function () { goTo(i); });
    });

    var touchX = 0;
    track.addEventListener('touchstart', function (e) { touchX = e.changedTouches[0].clientX; }, { passive: true });
    track.addEventListener('touchend',   function (e) {
        var diff = touchX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) goTo(current + (diff > 0 ? 1 : -1));
    }, { passive: true });

    window.addEventListener('resize', function () { goTo(Math.min(current, maxIndex())); });

    goTo(0);
})();
</script>
