<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['testimonials' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['testimonials' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $items = $testimonials ?? collect([
        ['name' => 'Amit Jadhav', 'rating' => 5, 'quote' => 'From the initial consultation to the final product delivery, Tectignis made the entire process seamless and enjoyable. I highly recommend them to anyone in need of professional IT solutions.'],
        ['name' => 'Nidhi Patil', 'rating' => 5, 'quote' => 'Tectignis is not just about aesthetics, they are results-driven. Our systems not only look great but perform exceptionally well, translating into real business value.'],
        ['name' => 'Amit Jana', 'rating' => 5, 'quote' => "Tectignis, the trailblazers of technology in Navi Mumbai! Their team's commitment to delivering quality and innovation is remarkable."],
        ['name' => 'Abhya Sharma', 'rating' => 5, 'quote' => 'Absolutely thrilled with the impeccable service provided by Tectignis! They exceeded all expectations with their expertise and attention to detail.'],
    ]);
?>

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
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $t = (object) $t; ?>
                                <div class="swiper-slide">
                                    <div class="testimonial-slider__one wow move-up">
                                        <div class="testimonial-slider--info">
                                            <div class="testimonial-slider__media">
                                                <img src="<?php echo e(asset('assets/images/testimonial/testimonial-.webp')); ?>"
                                                    class="img-fluid" alt="<?php echo e($t->name); ?>" loading="lazy">
                                            </div>
                                            <div class="testimonial-slider__author">
                                                <div class="testimonial-rating">
                                                    <?php for($i = 0; $i < (int) $t->rating; $i++): ?>
                                                        <span class="fa fa-star"></span>
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="author-info">
                                                    <h6 class="name"><?php echo e($t->name); ?></h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="testimonial-slider__text"><?php echo e($t->quote); ?></div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="swiper-pagination swiper-pagination-t01 section-space--mt_30"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\projects\tech-corporate\resources\views/components/public/testimonials.blade.php ENDPATH**/ ?>