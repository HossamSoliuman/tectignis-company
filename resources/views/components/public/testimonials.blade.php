@props(['testimonials' => null])

@php
    $items = $testimonials ?? collect([
        ['name' => 'Amit Jadhav', 'rating' => 5, 'quote' => 'From the initial consultation to the final product delivery, Tectignis made the entire process seamless and enjoyable. I highly recommend them to anyone in need of professional IT solutions.'],
        ['name' => 'Nidhi Patil', 'rating' => 5, 'quote' => 'Tectignis is not just about aesthetics, they are results-driven. Our systems not only look great but perform exceptionally well, translating into real business value.'],
        ['name' => 'Amit Jana', 'rating' => 5, 'quote' => "Tectignis, the trailblazers of technology in Navi Mumbai! Their team's commitment to delivering quality and innovation is remarkable."],
        ['name' => 'Abhya Sharma', 'rating' => 5, 'quote' => 'Absolutely thrilled with the impeccable service provided by Tectignis! They exceeded all expectations with their expertise and attention to detail.'],
    ]);
@endphp

<div class="testimonial-slider-area section-space--ptb_80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-wrap text-center section-space--mb_40">
                    <h6 class="section-sub-title mb-20">Testimonials</h6>
                    <h3 class="heading">Why do people <span class="text-color-primary">love Tectignis?</span></h3>
                </div>
                <div class="testimonial-slider">
                    <div class="swiper-container testimonial-slider__container">
                        <div class="swiper-wrapper testimonial-slider__wrapper">
                            @foreach ($items as $t)
                                @php $t = (object) $t; @endphp
                                <div class="swiper-slide">
                                    <div class="testimonial-slider__one wow move-up">
                                        <div class="testimonial-slider--info">
                                            <div class="testimonial-slider__media">
                                                <img src="{{ asset('assets/images/testimonial/testimonial-.webp') }}"
                                                    class="img-fluid" alt="{{ $t->name }}" loading="lazy">
                                            </div>
                                            <div class="testimonial-slider__author">
                                                <div class="testimonial-rating">
                                                    @for ($i = 0; $i < (int) $t->rating; $i++)
                                                        <span class="fa fa-star"></span>
                                                    @endfor
                                                </div>
                                                <div class="author-info">
                                                    <h6 class="name">{{ $t->name }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="testimonial-slider__text">{{ $t->quote }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination swiper-pagination-t01 section-space--mt_30"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
