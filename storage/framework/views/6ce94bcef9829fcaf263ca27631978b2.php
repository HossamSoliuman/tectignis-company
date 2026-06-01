<?php $__env->startSection('title', $service->seo_title ?: $service->title.' | Tectignis IT Solutions'); ?>

<?php $__env->startSection('seo'); ?>
    <meta name="description" content="<?php echo e($service->seo_description ?: $service->short_description); ?>">
    <?php if($service->seo_keywords): ?>
        <meta name="keywords" content="<?php echo e($service->seo_keywords); ?>">
    <?php endif; ?>
    <link rel="canonical" href="<?php echo e(route('capabilities.show', $service->slug)); ?>">
    <meta property="og:title" content="<?php echo e($service->seo_title ?: $service->title.' | Tectignis IT Solutions'); ?>">
    <meta property="og:description" content="<?php echo e($service->seo_description ?: $service->short_description); ?>">
    <meta property="og:url" content="<?php echo e(route('capabilities.show', $service->slug)); ?>">
    <meta property="og:type" content="website">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.breadcrumb','data' => ['title' => $service->title,'items' => ['Capabilities' => route('capabilities.index'), $service->title => null]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service->title),'items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Capabilities' => route('capabilities.index'), $service->title => null])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal01c1b0e46c89eaeff9b0aabe5d063e2a)): ?>
<?php $attributes = $__attributesOriginal01c1b0e46c89eaeff9b0aabe5d063e2a; ?>
<?php unset($__attributesOriginal01c1b0e46c89eaeff9b0aabe5d063e2a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal01c1b0e46c89eaeff9b0aabe5d063e2a)): ?>
<?php $component = $__componentOriginal01c1b0e46c89eaeff9b0aabe5d063e2a; ?>
<?php unset($__componentOriginal01c1b0e46c89eaeff9b0aabe5d063e2a); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!--===========  feature-large-images-wrapper  Start =============-->
    <div class="feature-large-images-wrapper section-space--ptb_100">
        <div class="container">
            <div class="cybersecurity-about-box">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="modern-number-01">
                            <h3 class="heading mt-30">Best <span class="text-color-primary"><?php echo e($service->title); ?></span><br>in Navi Mumbai</h3>
                        </div>
                    </div>
                    <div class="col-lg-7 offset-lg-1">
                        <div class="conact-us-wrap-one managed-it">
                            <h5 class="heading"><span class="text-color-primary">Tectignis IT Solutions</span>
                                specializes in affordable &amp; effective
                                <span class="text-color-primary"><?php echo e($service->title); ?></span> services.</h5>
                            <div class="sub-heading">Contact Tectignis IT Solutions today for a free consultation</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===========  feature-large-images-wrapper  End =============-->

    <?php if($service->description): ?>
        <div class="section-space--ptb_100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <?php echo $service->description; ?>

                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="software-innovation-about-company-area software-innovation-about-bg section-space--ptb_120">
            <div class="container">
                <div class="row">
                    <?php if($service->banner_image): ?>
                        <div class="col-lg-6">
                            <div class="image-inner-video-section">
                                <img class="img-fluid border-radus-5"
                                    src="<?php echo e(asset('assets/images/banners/'.$service->banner_image)); ?>"
                                    alt="<?php echo e($service->title); ?>" loading="lazy">
                            </div>
                        </div>
                        <div class="col-lg-6 ms-auto mt-30">
                    <?php else: ?>
                        <div class="col-lg-10 offset-lg-1">
                    <?php endif; ?>
                        <div class="machine-learning-about-content">
                            <div class="section-title mb-20">
                                <div class="section-title-wrap text-left section-space--mb_30">
                                    <h3 class="heading">Expert <span class="text-color-primary"><?php echo e($service->title); ?></span> Services</h3>
                                </div>
                                <p class="dec-text mt-20"><?php echo e($service->short_description); ?></p>
                                <div class="tab-button mt-30">
                                    <a class="btn-text" href="<?php echo e(route('contact')); ?>">
                                        <span class="button-text">Get a free consultation <i class="fas fa-arrow-right"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!--===========  feature-icon-wrapper  Start =============-->
    <div class="feature-icon-wrapper section-space--pt_100 section-space--pb_70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_20">
                        <h3 class="heading">Why Choose Tectignis IT Solutions?</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="feature-list__three">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="grid-item animate">
                                    <div class="ht-box-icon style-03">
                                        <div class="icon-box-wrap">
                                            <div class="content-header">
                                                <div class="icon">
                                                    <i class="fas fa-briefcase"></i>
                                                </div>
                                                <h5 class="heading">Experience and Expertise</h5>
                                            </div>
                                            <div class="content">
                                                <div class="text">We have a proven track record of delivering successful solutions to businesses across various industries.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="grid-item animate">
                                    <div class="ht-box-icon style-03">
                                        <div class="icon-box-wrap">
                                            <div class="content-header">
                                                <div class="icon">
                                                    <i class="fas fa-users"></i>
                                                </div>
                                                <h5 class="heading">Client-Centric Approach</h5>
                                            </div>
                                            <div class="content">
                                                <div class="text">We prioritize client satisfaction and work closely with you throughout the entire process.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="grid-item animate">
                                    <div class="ht-box-icon style-03">
                                        <div class="icon-box-wrap">
                                            <div class="content-header">
                                                <div class="icon">
                                                    <i class="fas fa-tags"></i>
                                                </div>
                                                <h5 class="heading">Affordable Pricing</h5>
                                            </div>
                                            <div class="content">
                                                <div class="text">We offer competitive pricing without compromising on quality. Every business deserves a high-quality solution.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="grid-item animate">
                                    <div class="ht-box-icon style-03">
                                        <div class="icon-box-wrap">
                                            <div class="content-header">
                                                <div class="icon">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                                <h5 class="heading">Timely Delivery</h5>
                                            </div>
                                            <div class="content">
                                                <div class="text">We understand the importance of deadlines and strive to deliver projects on time and within budget.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===========  feature-icon-wrapper  End =============-->

    <!--====================  gradation process area ====================-->
    <div class="gradation-process-area section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="gradation-title-wrapper section-space--mb_60">
                        <div class="gradation-title-wrap">
                            <h6 class="section-sub-title text-black mb-20">How we work</h6>
                            <h4 class="heading">Our Simple <span class="text-color-primary"><br>4-Step Process</span></h4>
                        </div>
                        <div class="gradation-sub-heading">
                            <h3 class="heading"><mark>04</mark> Steps</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ht-gradation style-01">
                        <div class="item item-1 animate wow fadeInRight" data-wow-delay="0.1s">
                            <div class="line"></div>
                            <div class="circle-wrap">
                                <div class="mask">
                                    <div class="wave-pulse wave-pulse-1"></div>
                                    <div class="wave-pulse wave-pulse-2"></div>
                                    <div class="wave-pulse wave-pulse-3"></div>
                                </div>
                                <h6 class="circle">1</h6>
                            </div>
                            <div class="content-wrap">
                                <h6 class="heading">01. Discovery &amp; Consultation</h6>
                                <div class="text">We understand your business, goals, and target audience to tailor the perfect solution.</div>
                            </div>
                        </div>
                        <div class="item item-1 animate wow fadeInRight" data-wow-delay="0.15s">
                            <div class="line"></div>
                            <div class="circle-wrap">
                                <div class="mask">
                                    <div class="wave-pulse wave-pulse-1"></div>
                                    <div class="wave-pulse wave-pulse-2"></div>
                                    <div class="wave-pulse wave-pulse-3"></div>
                                </div>
                                <h6 class="circle">2</h6>
                            </div>
                            <div class="content-wrap">
                                <h6 class="heading">02. Design &amp; Development</h6>
                                <div class="text">Our expert team crafts high-quality solutions using the latest technologies.</div>
                            </div>
                        </div>
                        <div class="item item-1 animate wow fadeInRight" data-wow-delay="0.20s">
                            <div class="line"></div>
                            <div class="circle-wrap">
                                <div class="mask">
                                    <div class="wave-pulse wave-pulse-1"></div>
                                    <div class="wave-pulse wave-pulse-2"></div>
                                    <div class="wave-pulse wave-pulse-3"></div>
                                </div>
                                <h6 class="circle">3</h6>
                            </div>
                            <div class="content-wrap">
                                <h6 class="heading">03. Testing &amp; Quality Assurance</h6>
                                <div class="text">We rigorously test every deliverable to ensure optimal performance and seamless experience.</div>
                            </div>
                        </div>
                        <div class="item item-1 animate wow fadeInRight" data-wow-delay="0.25s">
                            <div class="line"></div>
                            <div class="circle-wrap">
                                <div class="mask">
                                    <div class="wave-pulse wave-pulse-1"></div>
                                    <div class="wave-pulse wave-pulse-2"></div>
                                    <div class="wave-pulse wave-pulse-3"></div>
                                </div>
                                <h6 class="circle">4</h6>
                            </div>
                            <div class="content-wrap">
                                <h6 class="heading">04. Launch &amp; Support</h6>
                                <div class="text">We launch smoothly and provide ongoing maintenance and support for continued success.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====================  End of gradation process area  ====================-->

    <!--========== Call to Action Area Start ============-->
    <?php if (isset($component)) { $__componentOriginaldf30a18d9440dd691ef9604b919389f4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf30a18d9440dd691ef9604b919389f4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.cta','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.cta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldf30a18d9440dd691ef9604b919389f4)): ?>
<?php $attributes = $__attributesOriginaldf30a18d9440dd691ef9604b919389f4; ?>
<?php unset($__attributesOriginaldf30a18d9440dd691ef9604b919389f4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldf30a18d9440dd691ef9604b919389f4)): ?>
<?php $component = $__componentOriginaldf30a18d9440dd691ef9604b919389f4; ?>
<?php unset($__componentOriginaldf30a18d9440dd691ef9604b919389f4); ?>
<?php endif; ?>
    <!--========== Call to Action Area End ============-->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\tech-corporate\resources\views/public/capabilities/show.blade.php ENDPATH**/ ?>