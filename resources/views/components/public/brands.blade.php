@props(['brands' => null])

@php
    $items = $brands ?? \App\Models\Brand::active()->ordered()->get();
@endphp

<div class="brand-logo-slider-area section-space--ptb_60">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- brand logo slider -->
                <div class="brand-logo-slider__container-area">
                    <div class="swiper-container brand-logo-slider__container">
                        <div class="swiper-wrapper brand-logo-slider__one">
                            @foreach ($items as $brand)
                            <div class="swiper-slide brand-logo brand-logo--slider">
                                <div class="brand-logo__image">
                                    <img src="{{ asset('assets/images/brand/'.$brand->logo) }}" class="img-fluid" loading="lazy" alt="{{ $brand->name }}-tectignis">
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
