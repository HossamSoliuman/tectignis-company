@props(['brands' => null])

@php
    $logos = $brands ?? collect([
        'canara-bank', 'go-best-dentist', 'gov-of-maharashtra', 'harmony-school', 'hp', 'jewelmyne',
        'lic', 'perfect-packing-solution', 'raul-engineering', 'reliance-petroleum', 'shree-aai-pratishtahan', 'syncat',
    ])->map(fn ($slug) => ['name' => $slug, 'logo' => asset("assets/images/brand/{$slug}.webp")]);
@endphp

<div class="brand-logo-slider-area section-space--ptb_80 bg-theme-default">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-wrap text-center section-space--mb_40">
                    <h6 class="section-sub-title mb-20">Our Clients</h6>
                    <h3 class="heading">Trusted by businesses across India</h3>
                </div>
                <div class="brand-logo-slider__container-area">
                    <div class="swiper-container brand-logo-slider__container">
                        <div class="swiper-wrapper brand-logo-slider__one">
                            @foreach ($logos as $brand)
                                @php $brand = (object) $brand; @endphp
                                <div class="swiper-slide brand-logo-slider__single">
                                    <div class="brand-logo--single">
                                        <div class="brand-image text-center">
                                            <img src="{{ $brand->logo }}" class="img-fluid"
                                                alt="{{ $brand->name }}" loading="lazy">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
