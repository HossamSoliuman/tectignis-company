@extends('layouts.public')

@section('title', 'About Us | Leading IT Solutions in Navi Mumbai & PAN India')

@section('seo')
    <meta name="description" content="Explore the expertise of Tectignis IT Solutions Pvt Ltd. World-class software development, AI, cloud, networking and security, serving Navi Mumbai & PAN India.">
    <link rel="canonical" href="{{ route('about') }}">
@endsection

@section('content')

    <!--============ About Hero Start ============-->
    <section class="about-hero section-space--ptb_80">
        <div class="container">
            <div class="about-hero__grid">
                <div class="about-hero__content wow move-up">
                    <span class="about-section-label">About Tectignis</span>
                    <h1 class="about-hero__title">Building the legacy of <span class="text-color-primary">information technology</span> across India &amp; beyond</h1>
                    <p class="about-hero__desc">Tectignis IT Solutions Pvt Ltd delivers enterprise-grade software, AI, cloud, networking and security solutions — combining world-class engineering with competitive pricing and the agility your business needs to stay ahead.</p>
                    <div class="about-hero__actions">
                        <a href="{{ route('contact') }}" class="about-btn about-btn--primary">Work With Us <span aria-hidden="true">→</span></a>
                        <a href="{{ route('services.index') }}" class="about-btn about-btn--ghost">Our Services</a>
                    </div>
                </div>
                <div class="about-hero__media wow move-up">
                    <span class="about-hero__shape" aria-hidden="true"></span>
                    <span class="about-hero__dots" aria-hidden="true"></span>
                    <div class="about-hero__image">
                        <img src="{{ asset('assets/images/bg/IT-Solutions-PAN-India.webp') }}" alt="Tectignis IT Solutions team delivering services across PAN India" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============ About Hero End ============-->

    <!--============ Statistics Bar Start ============-->
    <section class="about-stats-section section-space--pb_80">
        <div class="container">
            <div class="about-stats">
                @php
                    $aboutStats = [
                        ['icon' => '<path d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>', 'num' => '500+', 'label' => 'Happy Clients'],
                        ['icon' => '<path d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z"/>', 'num' => '350+', 'label' => 'Projects Delivered'],
                        ['icon' => '<path d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>', 'num' => '15+', 'label' => 'Industries Served'],
                        ['icon' => '<path d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247"/>', 'num' => '25+', 'label' => 'Countries Reached'],
                        ['icon' => '<path d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0"/>', 'num' => '10+', 'label' => 'Years of Expertise'],
                    ];
                @endphp
                @foreach ($aboutStats as $stat)
                    <div class="about-stat">
                        <span class="about-stat__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">{!! $stat['icon'] !!}</svg>
                        </span>
                        <div class="about-stat__num">{{ $stat['num'] }}</div>
                        <div class="about-stat__label">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============ Statistics Bar End ============-->

    <!--============ Company Overview Start ============-->
    <section class="about-overview section-space--pb_80">
        <div class="container">
            <div class="about-overview__grid">
                <div class="about-overview__media wow move-up">
                    <div class="about-overview__image">
                        <img src="{{ asset('assets/images/bg/IT-Services-in-Navi-Mumbai.webp') }}" alt="Tectignis IT Solutions office in Navi Mumbai" loading="lazy">
                    </div>
                </div>
                <div class="about-overview__content wow move-up">
                    <span class="about-section-label">Our Company</span>
                    <h2 class="about-section-heading">We run all kinds of IT services that vow your <span class="text-color-primary">success</span></h2>
                    <p class="about-overview__text">Tectignis IT Solutions Pvt Ltd builds customized plans for every client based on their unique requirements, delivering enterprise-grade technology while maintaining competitive pricing and agility. We collaborate with experienced technology professionals and implementation partners to assemble the right expertise for each project.</p>
                    <ul class="about-checklist">
                        @foreach (['Custom software, AI & cloud engineering', 'Cybersecurity & smart security systems', 'Networking, infrastructure & managed services', 'Dedicated support across Navi Mumbai & PAN India'] as $item)
                            <li class="about-checklist__item">
                                <span class="about-checklist__icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m4.5 12.75 6 6 9-13.5"/></svg>
                                </span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--============ Company Overview End ============-->

    <!--============ Vision & Mission Start ============-->
    <section class="about-vm section-space--pb_80">
        <div class="container">
            <div class="about-vm__grid">
                <div class="about-vm__card wow move-up">
                    <span class="about-vm__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                    </span>
                    <h3 class="about-vm__title">Our Vision</h3>
                    <p class="about-vm__text">To be the most trusted technology partner that empowers businesses of every size to innovate fearlessly, scale confidently, and lead their industries through intelligent, future-ready solutions.</p>
                </div>
                <div class="about-vm__card wow move-up">
                    <span class="about-vm__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/></svg>
                    </span>
                    <h3 class="about-vm__title">Our Mission</h3>
                    <p class="about-vm__text">To deliver secure, scalable and cost-effective IT solutions tailored to each client — combining deep technical expertise, a customer-first mindset, and a relentless commitment to measurable business impact.</p>
                </div>
            </div>
        </div>
    </section>
    <!--============ Vision & Mission End ============-->

    <!--============ Values Start ============-->
    <section class="about-values bg-gray section-space--ptb_80">
        <div class="container">
            <div class="section-title-wrap text-center section-space--mb_40">
                <span class="about-section-label about-section-label--center">What Drives Us</span>
                <h2 class="about-section-heading">Our Core <span class="text-color-primary">Values</span></h2>
                <p class="about-values__subtitle">The principles that shape every solution we build and every relationship we nurture.</p>
            </div>
            @php
                $aboutValues = [
                    ['icon' => '<path d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>', 'title' => 'Integrity', 'text' => 'We earn trust through transparency, honesty and accountability in everything we deliver.'],
                    ['icon' => '<path d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84"/>', 'title' => 'Innovation', 'text' => 'We embrace emerging technology to craft smarter, future-ready solutions.'],
                    ['icon' => '<path d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>', 'title' => 'Customer First', 'text' => 'Your goals lead the way — we build solutions around real business needs.'],
                    ['icon' => '<path d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z"/>', 'title' => 'Excellence', 'text' => 'We hold ourselves to the highest standards of quality and craftsmanship.'],
                    ['icon' => '<path d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>', 'title' => 'Agility', 'text' => 'We move fast and adapt, delivering value with speed and flexibility.'],
                ];
            @endphp
            <div class="about-values__grid">
                @foreach ($aboutValues as $value)
                    <div class="about-value-card wow move-up">
                        <span class="about-value-card__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">{!! $value['icon'] !!}</svg>
                        </span>
                        <h4 class="about-value-card__title">{{ $value['title'] }}</h4>
                        <p class="about-value-card__text">{{ $value['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============ Values End ============-->

    <!--============ Global Presence Start ============-->
    <section class="about-global section-space--ptb_80">
        <svg aria-hidden="true" focusable="false" style="position:absolute;width:0;height:0">
            <defs>
                <pattern id="aboutGpDots" x="0" y="0" width="14" height="14" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.6" fill="#D8DEE9"/>
                </pattern>
            </defs>
        </svg>
        <div class="container">
            <div class="about-global__grid">
                <div class="about-global__content wow move-up">
                    <span class="about-section-label">Global Presence</span>
                    <h2 class="about-section-heading">Local expertise with a <span class="text-color-primary">global mindset</span></h2>
                    <p class="about-global__text">Headquartered in Navi Mumbai and delivering across PAN India, we partner with clients worldwide — combining regional insight with a global delivery model to help businesses grow, scale and succeed wherever they operate.</p>
                    <ul class="about-checklist">
                        @foreach (['Delivery hubs across India', 'Clients in 25+ countries', 'Round-the-clock support model'] as $item)
                            <li class="about-checklist__item">
                                <span class="about-checklist__icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m4.5 12.75 6 6 9-13.5"/></svg>
                                </span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="about-global__media wow move-up">
                    <div class="about-global__map" role="img" aria-label="World map highlighting Tectignis global delivery from India.">
                        <svg viewBox="0 0 1000 460" preserveAspectRatio="xMidYMid meet">
                            <g fill="url(#aboutGpDots)">
                                <path d="M120,90 C100,110 90,150 110,180 C130,210 180,232 212,212 C244,196 252,158 235,128 C218,98 160,75 120,90 Z"/>
                                <path d="M250,255 C238,288 250,342 282,372 C302,392 322,382 322,350 C326,308 305,272 286,256 C271,246 258,246 250,255 Z"/>
                                <path d="M428,118 C413,128 413,156 435,166 C462,177 492,166 497,144 C502,126 480,114 460,114 C448,113 436,114 428,118 Z"/>
                                <path d="M452,198 C436,214 442,266 472,302 C492,322 512,316 517,286 C524,250 506,214 491,199 C476,187 462,188 452,198 Z"/>
                                <path d="M520,108 C562,92 662,98 742,128 C802,150 822,182 792,202 C742,228 650,222 590,212 C540,202 508,170 508,144 C508,128 514,116 520,108 Z"/>
                                <path d="M742,328 C726,344 732,376 766,386 C796,394 822,378 820,355 C817,334 792,321 766,321 C755,321 748,323 742,328 Z"/>
                            </g>
                            <path class="about-global__india" d="M548,212 C562,206 578,216 575,232 C572,250 560,266 552,252 C545,240 537,222 548,212 Z"/>
                            <g class="about-global__arcs" fill="none">
                                <path d="M560,236 Q495,150 430,158"/>
                                <path d="M560,236 Q360,118 182,198"/>
                                <path d="M560,236 Q360,78 182,150"/>
                                <path d="M560,236 Q702,298 782,358"/>
                                <path d="M560,236 Q622,250 662,300"/>
                            </g>
                            <g class="about-global__nodes">
                                <circle cx="430" cy="158" r="4"/>
                                <circle cx="182" cy="198" r="4"/>
                                <circle cx="182" cy="150" r="4"/>
                                <circle cx="782" cy="358" r="4"/>
                                <circle cx="662" cy="300" r="4"/>
                            </g>
                            <g class="about-global__hub" transform="translate(560,236)">
                                <circle class="about-global__hub-ring" r="13"/>
                                <circle class="about-global__hub-dot" r="5"/>
                            </g>
                        </svg>
                        <div class="about-global__card">
                            <span class="about-global__card-num">25+</span>
                            <span class="about-global__card-label">Countries Served</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============ Global Presence End ============-->

    <!--============ CTA Banner Start ============-->
    <section class="section-space--pb_80">
        <div class="container">
            <div class="about-cta">
                <span class="about-cta__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84"/></svg>
                </span>
                <div class="about-cta__body">
                    <h3 class="about-cta__title">Ready to build something exceptional together?</h3>
                    <p class="about-cta__text">Let's discuss how our software, AI, cloud and security solutions can accelerate your growth.</p>
                </div>
                <a href="{{ route('contact') }}" class="about-cta__btn">Get in Touch <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>
    <!--============ CTA Banner End ============-->

@endsection
