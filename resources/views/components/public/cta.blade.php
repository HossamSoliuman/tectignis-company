@props([
    'heading' => 'We run all kinds of IT services that vow your success',
    'buttonText' => "Let's talk",
    'buttonUrl' => null,
])

<div class="cta-image-area_one section-space--ptb_80 cta-bg-image_one">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-8 col-lg-7">
                <div class="cta-content md-text-center">
                    <h3 class="heading text-white">{!! $heading !!}</h3>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="cta-button-group--one text-center">
                    <a href="{{ $buttonUrl ?? route('contact') }}" class="btn btn--white btn-one">
                        <span class="btn-icon me-2"><i class="far fa-comment-alt"></i></span> {{ $buttonText }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
