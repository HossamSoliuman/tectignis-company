<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'heading' => 'We run all kinds of IT services that vow your success',
    'buttonText' => "Let's talk",
    'buttonUrl' => null,
]));

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

foreach (array_filter(([
    'heading' => 'We run all kinds of IT services that vow your success',
    'buttonText' => "Let's talk",
    'buttonUrl' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="cta-image-area_one section-space--ptb_80 cta-bg-image_one">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-8 col-lg-7">
                <div class="cta-content md-text-center">
                    <h3 class="heading text-white"><?php echo $heading; ?></h3>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="cta-button-group--one text-center">
                    <a href="<?php echo e($buttonUrl ?? route('contact')); ?>" class="btn btn--white btn-one">
                        <span class="btn-icon me-2"><i class="far fa-comment-alt"></i></span> <?php echo e($buttonText); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\projects\tech-corporate\resources\views/components/public/cta.blade.php ENDPATH**/ ?>