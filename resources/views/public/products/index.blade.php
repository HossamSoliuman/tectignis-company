@extends('layouts.public')

@section('title', __('products.title'))

@section('seo')
    <meta name="description" content="{{ __('products.description') }}">
@endsection

@section('content')
    <section class="tx-products-hero">
        <span class="tx-products-hero__glow" aria-hidden="true"></span>
        <div class="container">
            <span class="tx-products-hero__eyebrow">{{ __('home.products_pretitle') }}</span>
            <h1 class="tx-products-hero__title">
                {{ __('home.products_heading') }}
                <span class="tx-products-hero__highlight">{{ __('home.products_highlight') }}</span>
            </h1>

            @if ($products->isNotEmpty())
                <nav class="tx-products-hero__nav" aria-label="{{ __('home.products_pretitle') }}">
                    @foreach ($products as $product)
                        @php
                            $cover = $product->galleryImages()[0] ?? null;
                        @endphp
                        <a href="#{{ $product->anchor() }}" class="tx-product-jump">
                            <span class="tx-product-jump__shot" aria-hidden="true">
                                @if ($cover)
                                    <img src="{{ asset('uploads/'.$cover['image']) }}" alt="" loading="lazy">
                                @else
                                    <i class="{{ $product->icon ?: 'fas fa-cube' }}"></i>
                                @endif
                            </span>
                            <span class="tx-product-jump__name">
                                <i class="{{ $product->icon ?: 'fas fa-cube' }}" aria-hidden="true"></i>
                                {{ $product->name }}
                            </span>
                            <i class="fas fa-arrow-down tx-product-jump__arrow" aria-hidden="true"></i>
                        </a>
                    @endforeach
                </nav>
            @endif
        </div>
    </section>

    @forelse ($products as $product)
        @php
            $screenshots = $product->galleryImages();
            $videos = $product->videoItems();
            $gallery = array_map(fn (array $item): array => ['src' => asset('uploads/'.$item['image']), 'alt' => $item['alt']], $screenshots);
        @endphp

        <section id="{{ $product->anchor() }}" class="tx-showcase {{ $loop->even ? 'tx-showcase--muted' : '' }}" data-showcase data-gallery='@json($gallery)'>
            <div class="container">
                <div class="tx-showcase__layout {{ $loop->even ? 'tx-showcase__layout--reverse' : '' }}">
                    <div class="tx-showcase__info">
                        <span class="tx-showcase__icon" aria-hidden="true"><i class="{{ $product->icon ?: 'fas fa-cube' }}"></i></span>
                        <h2 class="tx-showcase__name">{{ $product->name }}</h2>
                        <p class="tx-showcase__text">{{ $product->description }}</p>

                        @if (! empty($product->features))
                            <ul class="tx-showcase__features">
                                @foreach ($product->features as $feature)
                                    <li><i class="fas fa-check" aria-hidden="true"></i><span>{{ $feature }}</span></li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="tx-showcase__actions">
                            @if ($product->url)
                                <a href="{{ $product->url }}" target="_blank" rel="noopener" class="tx-btn tx-btn--primary">
                                    {{ __('home.product_visit', ['name' => $product->name]) }} <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                                </a>
                            @endif
                            @if ($videos !== [])
                                <a href="#{{ $product->anchor() }}-videos" class="tx-btn tx-btn--outline" data-play-first>
                                    <i class="fas fa-play-circle" aria-hidden="true"></i> {{ __('products.watch_video') }}
                                </a>
                            @endif
                            @if ($product->caseStudy)
                                <a href="{{ route('case-studies.show', $product->caseStudy->slug) }}" class="tx-showcase__more">
                                    {{ __('services.read_case_study') }} <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="tx-showcase__media">
                        <div class="tx-browser">
                            <div class="tx-browser__bar" aria-hidden="true">
                                <span class="tx-browser__dots"><i></i><i></i><i></i></span>
                                @if ($product->displayUrl())
                                    <span class="tx-browser__address"><i class="fas fa-lock"></i> {{ $product->displayUrl() }}</span>
                                @endif
                            </div>
                            @if ($screenshots !== [])
                                <button type="button" class="tx-browser__screen" data-lightbox-open aria-label="{{ __('products.enlarge') }}">
                                    <img src="{{ $gallery[0]['src'] }}" alt="{{ $gallery[0]['alt'] }}" data-showcase-main
                                        @if (! $loop->first) loading="lazy" @endif>
                                    <span class="tx-browser__zoom" aria-hidden="true"><i class="fas fa-expand"></i></span>
                                </button>
                            @else
                                <div class="tx-browser__screen tx-browser__screen--empty" aria-hidden="true">
                                    <i class="{{ $product->icon ?: 'fas fa-cube' }}"></i>
                                </div>
                            @endif
                        </div>

                        @if (count($screenshots) > 1)
                            <div class="tx-showcase__thumbs" role="group" aria-label="{{ __('products.screenshots', ['name' => $product->name]) }}">
                                @foreach ($gallery as $index => $shot)
                                    <button type="button" class="tx-showcase__thumb {{ $loop->first ? 'is-active' : '' }}" data-thumb="{{ $index }}"
                                        aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                                        aria-label="{{ __('products.view_screenshot', ['number' => $loop->iteration, 'total' => $loop->count]) }}">
                                        <img src="{{ $shot['src'] }}" alt="" loading="lazy">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                @if ($videos !== [])
                    <div class="tx-showcase__videos" id="{{ $product->anchor() }}-videos">
                        <h3 class="tx-showcase__subheading">{{ __('products.videos') }}</h3>
                        <div class="tx-video-grid {{ count($videos) === 1 ? 'tx-video-grid--single' : '' }}">
                            @foreach ($videos as $video)
                                <figure class="tx-video">
                                    <div class="tx-video__frame" data-video="{{ $video['type'] }}" data-src="{{ $video['src'] }}" data-title="{{ $video['title'] }}">
                                        <button type="button" class="tx-video__poster" aria-label="{{ __('products.play', ['title' => $video['title']]) }}">
                                            @if ($video['thumbnail'])
                                                <img src="{{ $video['thumbnail'] }}" alt="" loading="lazy">
                                            @elseif ($video['type'] === 'file')
                                                <video src="{{ $video['src'] }}#t=0.5" preload="metadata" muted playsinline tabindex="-1" aria-hidden="true"></video>
                                            @else
                                                <i class="{{ $product->icon ?: 'fas fa-cube' }} tx-video__icon" aria-hidden="true"></i>
                                            @endif
                                            <span class="tx-video__play" aria-hidden="true"><i class="fas fa-play"></i></span>
                                        </button>
                                    </div>
                                    <figcaption class="tx-video__title">{{ $video['title'] }}</figcaption>
                                </figure>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @empty
        <section class="tx-section">
            <div class="container">
                <div class="res-empty">
                    <i class="fas fa-cube" aria-hidden="true"></i>
                    <p class="mb-0">{{ __('products.empty') }}</p>
                </div>
            </div>
        </section>
    @endforelse

    <x-public.cta />
@endsection

@push('modals')
    <dialog class="tx-lightbox" data-lightbox aria-label="{{ __('products.viewer') }}">
        <button type="button" class="tx-lightbox__close" data-lightbox-close aria-label="{{ __('products.close') }}"><i class="fas fa-times" aria-hidden="true"></i></button>
        <button type="button" class="tx-lightbox__nav tx-lightbox__nav--prev" data-lightbox-prev aria-label="{{ __('products.previous') }}"><i class="fas fa-chevron-left" aria-hidden="true"></i></button>
        <figure class="tx-lightbox__figure">
            <img class="tx-lightbox__image" src="" alt="" data-lightbox-image>
            <figcaption class="tx-lightbox__caption">
                <span data-lightbox-caption></span>
                <span class="tx-lightbox__count" data-lightbox-count></span>
            </figcaption>
        </figure>
        <button type="button" class="tx-lightbox__nav tx-lightbox__nav--next" data-lightbox-next aria-label="{{ __('products.next') }}"><i class="fas fa-chevron-right" aria-hidden="true"></i></button>
    </dialog>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/product-showcase.js') }}?v={{ filemtime(public_path('assets/js/product-showcase.js')) }}" defer></script>
@endpush
