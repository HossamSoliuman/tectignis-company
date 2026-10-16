@extends('layouts.public')

@section('title', $settings['home_meta_title'] ?? __('home.meta_title'))

@section('seo')
    @php
        $homeMetaDesc = $settings['home_meta_description'] ?? __('home.meta_description');
        $homeMetaKeywords = $settings['home_meta_keywords'] ?? null;
        $homeOgTitle = $settings['home_og_title'] ?? ($settings['home_meta_title'] ?? null);
        $homeOgDesc = $settings['home_og_description'] ?? $homeMetaDesc;
        $homeOgImage = ($settings['home_og_image'] ?? null)
            ? \App\Models\Setting::imageUrl($settings['home_og_image'], 'home_og_image')
            : null;
    @endphp
    <meta name="description" content="{{ $homeMetaDesc }}">
    @if ($homeMetaKeywords)
        <meta name="keywords" content="{{ $homeMetaKeywords }}">
    @endif
    <meta property="og:title" content="{{ $homeOgTitle }}">
    <meta property="og:description" content="{{ $homeOgDesc }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ localized_route('home') }}">
    @if ($homeOgImage)
        <meta property="og:image" content="{{ $homeOgImage }}">
    @endif
@endsection

@section('content')

    @php
        $heroTrust = collect(['projects', 'clients', 'industries', 'countries'])
            ->map(fn (string $key) => $company->stat($key))
            ->filter()
            ->map(fn ($stat): array => ['value' => $stat->value, 'label' => $stat->label])
            ->values()
            ->all();
        [$heroImageWidth, $heroImageHeight] = \App\Models\Setting::imageSize($settings['hero_image'] ?? null, 'hero_image') ?? [640, 520];
    @endphp

    {{-- 01 Hero --}}
    <x-public.hero
        class="tx-hero--single-line"
        :overline="$settings['hero_sub_heading'] ?? null"
        :title="$settings['hero_heading_line1'] ?? __('home.hero_title')"
        :highlight="$settings['hero_heading_line2'] ?? __('home.hero_highlight')"
        :description="$settings['hero_info_heading'] ?? __('home.hero_text')"
        :primary-label="$settings['hero_btn_primary'] ?? __('home.hero_primary')"
        :secondary-label="$settings['hero_btn_secondary'] ?? __('home.hero_secondary')"
        :secondary-url="localized_route('contact').'#contact-lead-form'"
        :image="\App\Models\Setting::imageUrl($settings['hero_image'] ?? null, 'hero_image')"
        :image-alt="__('home.hero_image_alt')"
        :image-width="$heroImageWidth"
        :image-height="$heroImageHeight"
        :trust="$heroTrust"
    />

    {{-- 02 Trust Bar --}}
    @if ($brands->isNotEmpty())
        <section class="tx-trust" aria-label="{{ __('home.trust_label') }}">
            <div class="container">
                <p class="tx-trust__title">{{ $settings['trust_heading'] ?? __('home.trust_heading') }}</p>
            </div>
            <x-public.brands :brands="$brands" />
        </section>
    @endif

    {{-- 03 Core Services: the two priority services for international buyers --}}
    @if ($coreServices->isNotEmpty())
        <section class="tx-section tx-section--muted" id="core-services">
            <div class="container">
                <x-public.section-heading
                    :pretitle="$settings['core_pretitle'] ?? __('home.core_pretitle')"
                    :title="$settings['core_heading'] ?? __('home.core_heading')"
                    :highlight="$settings['core_heading_highlight'] ?? __('home.core_highlight')"
                />
                <div class="tx-grid tx-grid--2">
                    @foreach ($coreServices as $coreService)
                        <x-public.core-service-card :service="$coreService" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Business Pillars --}}
    <section class="tx-section" id="solutions">
        <div class="container">
            <x-public.section-heading
                :pretitle="$settings['pillars_pretitle'] ?? __('home.pillars_pretitle')"
                :title="$settings['pillars_heading'] ?? __('home.pillars_heading')"
                :subtitle="$settings['pillars_subtitle'] ?? null"
            />
            <div class="tx-grid tx-grid--4">
                @foreach ($pillars as $pillar)
                    <x-public.service-card
                        :href="$pillar->url()"
                        :title="$pillar->label()"
                        :text="$pillar->message()"
                        :icon="$pillar->icon()"
                        :items="array_map(\App\Support\EnquiryServices::label(...), $pillar->primaryServices())"
                        :link-label="__('home.explore_pillar', ['pillar' => $pillar->label()])"
                    />
                @endforeach
            </div>
        </div>
    </section>

    {{-- 04 Why Tectignis --}}
    <section class="tx-section tx-section--muted">
        <div class="container">
            <div class="tx-why">
                <div class="tx-why__intro">
                    <x-public.section-heading
                        align="left"
                        :pretitle="$settings['why_badge'] ?? __('home.why_pretitle')"
                        :title="$settings['why_heading'] ?? __('home.why_heading')"
                        :highlight="$settings['why_heading_highlight'] ?? __('home.why_highlight')"
                        :subtitle="$settings['why_subtitle'] ?? null"
                    />
                    @if ($company->stats()->isNotEmpty())
                        <ul class="tx-stats">
                            @foreach ($company->stats() as $stat)
                                <li class="tx-stats__item">
                                    <span class="tx-stats__value">{{ $stat->value }}</span>
                                    <span class="tx-stats__label">{{ $stat->label }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <a href="{{ route('about') }}" class="tx-btn tx-btn--outline">{{ __('home.about_link') }}</a>
                </div>

                <ul class="tx-why__features">
                    @foreach ($whyChooseFeatures as $feature)
                        <li class="tx-feature">
                            <span class="tx-feature__icon" aria-hidden="true"><i class="{{ $feature->icon }}"></i></span>
                            <span>
                                <h3 class="tx-feature__title">{{ $feature->title }}</h3>
                                <p class="tx-feature__text">{{ $feature->text }}</p>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    {{-- 05 Recent Projects --}}
    @if ($caseStudies->isNotEmpty())
        <section class="tx-section" id="work">
            <div class="container">
                <x-public.section-heading
                    :pretitle="$settings['cs_badge'] ?? __('home.cs_pretitle')"
                    :title="$settings['cs_heading'] ?? __('home.cs_heading')"
                    :subtitle="$settings['cs_subtitle'] ?? null"
                />
                <div class="tx-grid tx-grid--3">
                    @foreach ($caseStudies as $caseStudy)
                        <x-public.case-study-card :case-study="$caseStudy" />
                    @endforeach
                </div>
                <div class="tx-section__footer">
                    <a href="{{ route('case-studies.index') }}" class="tx-btn tx-btn--primary">{{ __('nav.view_our_work') }} <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>
        </section>
    @endif

    {{-- 06 Industries --}}
    @if ($industries->isNotEmpty())
        <section class="tx-section tx-section--muted">
            <div class="container">
                <x-public.section-heading
                    :pretitle="$settings['ind_pretitle'] ?? __('home.ind_pretitle')"
                    :title="$settings['ind_heading_line1'] ?? __('home.ind_heading')"
                    :highlight="$settings['ind_heading_line2'] ?? __('home.ind_highlight')"
                    :subtitle="$settings['ind_subtitle'] ?? null"
                />
                <div class="tx-grid tx-grid--industries">
                    @foreach ($industries as $industry)
                        <x-public.industry-card :industry="$industry" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 07 How We Work --}}
    @if ($processSteps->isNotEmpty())
        <section class="tx-section">
            <div class="container">
                <x-public.section-heading
                    :pretitle="$settings['process_pretitle'] ?? __('home.process_pretitle')"
                    :title="$settings['process_heading'] ?? __('home.process_heading')"
                    :subtitle="$settings['process_subtitle'] ?? null"
                />
                <ol class="tx-process" style="--step-count: {{ $processSteps->count() }}">
                    @foreach ($processSteps as $step)
                        <li class="tx-process__step">
                            <span class="tx-process__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">{!! $step->icon !!}</svg>
                            </span>
                            <span class="tx-process__number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3 class="tx-process__title">{{ $step->title }}</h3>
                            <p class="tx-process__text">{{ $step->description }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    {{-- 08 Technology --}}
    @if ($techGroups->isNotEmpty())
        <section class="tx-section tx-section--muted">
            <div class="container">
                <x-public.section-heading
                    :pretitle="$settings['tech_sub_heading'] ?? __('home.tech_pretitle')"
                    :title="$settings['tech_heading'] ?? __('home.tech_heading')"
                    :highlight="$settings['tech_heading_highlight'] ?? __('home.tech_highlight')"
                />
                <div class="tx-tech">
                    @foreach (\App\Enums\TechStackCategory::cases() as $category)
                        @continue(! $techGroups->has($category->value))
                        <div class="tx-tech__group">
                            <h3 class="tx-tech__label">{{ $category->label() }}</h3>
                            <ul class="tx-tech__items">
                                @foreach ($techGroups[$category->value] as $tech)
                                    <li class="tx-tech__item">
                                        @if ($tech->logo)
                                            <img src="{{ asset('uploads/'.$tech->logo) }}" alt="" width="28" height="28" loading="lazy">
                                        @endif
                                        <span>{{ $tech->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 09 Testimonials --}}
    @if ($testimonials->isNotEmpty())
        <section class="tx-section">
            <div class="container">
                <x-public.section-heading :pretitle="__('home.testimonials_pretitle')" :title="__('home.testimonials_heading')" />
                <div class="tx-grid tx-grid--3">
                    @foreach ($testimonials->take(3) as $testimonial)
                        <x-public.testimonial-card
                            :quote="$testimonial->quote"
                            :name="$testimonial->name"
                            :rating="$testimonial->rating"
                        />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 10 Global Delivery --}}
    <section class="tx-section tx-section--dark" id="global">
        <div class="container">
            <x-public.section-heading
                tone="dark"
                :pretitle="$settings['gp_pretitle'] ?? __('home.global_pretitle')"
                :title="$settings['gp_heading'] ?? __('home.global_heading')"
                :highlight="$settings['gp_heading_highlight'] ?? __('home.global_highlight')"
                :subtitle="$settings['gp_subtitle'] ?? null"
            />
            <ul class="tx-markets">
                @foreach ($company->markets() as $market)
                    <li class="tx-market">
                        <img src="https://flagcdn.com/w40/{{ $market['flag'] }}.png" alt="" width="32" height="24" loading="lazy" class="tx-market__flag">
                        <span class="tx-market__body">
                            <span class="tx-market__name">
                                @if ($market['url'])
                                    <a href="{{ $market['url'] }}">{{ $market['name'] }}</a>
                                @else
                                    {{ $market['name'] }}
                                @endif
                            </span>
                            <span class="tx-market__focus">{{ $market['focus'] }}</span>
                        </span>
                    </li>
                @endforeach
            </ul>

            @if ($globalAdvantages->isNotEmpty())
                <div class="tx-grid tx-grid--4 tx-advantages">
                    @foreach ($globalAdvantages as $advantage)
                        <div class="tx-advantage">
                            <span class="tx-advantage__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">{!! $advantage->icon !!}</svg>
                            </span>
                            <h3 class="tx-advantage__title">{{ $advantage->title }}</h3>
                            <p class="tx-advantage__text">{{ $advantage->description }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- 11 Insights: the blog is English-only, so it's left out of the other languages --}}
    @if ($recentPosts->isNotEmpty() && \App\Support\Locales::isDefault())
        <section class="tx-section">
            <div class="container">
                <x-public.section-heading
                    :pretitle="$settings['res_sub_heading'] ?? __('home.res_pretitle')"
                    :title="$settings['res_heading'] ?? __('home.res_heading')"
                    :highlight="$settings['res_heading_highlight'] ?? __('home.res_highlight')"
                />
                <div class="tx-grid tx-grid--3">
                    @foreach ($recentPosts as $post)
                        <x-public.resource.card
                            :url="route('blog.show', $post->slug)"
                            :image="$post->image"
                            :topic="$post->category"
                            :title="$post->title"
                            :excerpt="Str::limit($post->excerpt ?? '', 120)"
                        />
                    @endforeach
                </div>
                <div class="tx-section__footer">
                    <a href="{{ route('blog.index') }}" class="tx-btn tx-btn--outline">{{ __('home.res_all') }}</a>
                </div>
            </div>
        </section>
    @endif

    {{-- 12 Lead CTA (13 Footer is rendered by the layout) --}}
    <x-public.lead-cta
        :overline="$settings['cta_overline'] ?? __('home.cta_overline')"
        :title="trim(($settings['cta_heading'] ?? __('home.cta_heading')).' '.($settings['cta_heading_highlight'] ?? __('home.cta_heading_highlight')))"
        :highlight="$settings['cta_heading_suffix'] ?? __('home.cta_heading_suffix')"
        :text="$settings['cta_subheading'] ?? null"
        :primary-label="$settings['cta_btn_primary'] ?? __('home.cta_primary')"
        :secondary-label="$settings['cta_btn_secondary'] ?? __('home.cta_secondary')"
    />

@endsection
