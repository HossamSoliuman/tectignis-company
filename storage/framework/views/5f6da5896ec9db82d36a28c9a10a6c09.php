<?php $__env->startSection('title', 'About Us | Leading IT Solutions in Navi Mumbai & PAN India'); ?>

<?php $__env->startSection('seo'); ?>
    <meta name="description" content="Explore the expertise of Tectignis IT Solutions Pvt Ltd. World-class software development, AI, cloud, networking and security, serving Navi Mumbai & PAN India.">
    <link rel="canonical" href="<?php echo e(route('about')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.breadcrumb','data' => ['title' => 'About Tectignis','items' => ['About Us' => null]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'About Tectignis','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['About Us' => null])]); ?>
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
    <div class="feature-large-images-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_60">
                        <h6 class="section-sub-title mb-20">Our company</h6>
                        <h3 class="heading">We run all kinds of IT services that <br> vow your
                            <span class="text-color-primary">success</span>
                        </h3>
                    </div>
                </div>
            </div>

            <div class="cybersecurity-about-box section-space--pb_70">
                <div class="col-lg-12 ht-tab-wrap">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="tab-history-image mt-30">
                                <div class="single-popup-wrap">
                                    <img class="img-fluid" src="<?php echo e(asset('assets/images/bg/IT-Solutions-PAN-India.webp')); ?>"
                                        alt="IT Solutions PAN India" loading="lazy">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="tab-content-inner mt-30">
                                <div class="text mb-30">Tectignis IT Solutions Pvt Ltd builds customized plans for clients
                                    based on their requirements, delivering enterprise-grade software, AI, cloud and
                                    security solutions while maintaining competitive pricing and agility.</div>
                                <div class="text mb-30">We collaborate with experienced technology professionals and
                                    implementation partners to assemble the right expertise for each project, building the
                                    legacy of information technology in Navi Mumbai and beyond.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="feature-images__six">
                        <div class="row">
                            <?php $__currentLoopData = [['about-icon-001.webp', 'What we can do?', 'We focus on the needs of your business to find solutions that best fit your demand and nail it.'], ['about-icon-002.webp', 'Become our partner?', 'Our preventive and progressive approach helps you take the lead while addressing possible threats.'], ['about-icon-003.webp', 'Need a hand?', 'Our support team is here to proactively address and resolve any challenges you encounter.']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon, $title, $text]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-lg-4 col-md-6 wow move-up">
                                    <div class="ht-box-images style-06">
                                        <div class="image-box-wrap">
                                            <div class="box-image">
                                                <div class="default-image">
                                                    <img class="img-fulid" src="<?php echo e(asset('assets/images/icons/' . $icon)); ?>"
                                                        alt="<?php echo e($title); ?>" loading="lazy">
                                                </div>
                                            </div>
                                            <div class="content">
                                                <h5 class="heading"><?php echo e($title); ?></h5>
                                                <div class="text"><?php echo e($text); ?></div>
                                                <a href="<?php echo e(route('contact')); ?>" class="box-images-arrow">
                                                    <span class="button-text">Connect with us</span>
                                                    <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="fun-fact-wrapper bg-theme-default section-space--pb_30 section-space--pt_60">
        <div class="container">
            <div class="row">
                <?php $__currentLoopData = [['500', 'Happy clients'], ['350', 'Projects Delivered'], ['1M', 'Growth'], ['1K', 'Active Users']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$count, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-3 col-sm-6 wow move-up">
                        <div class="fun-fact--two text-center">
                            <div class="fun-fact__count"><?php echo e($count); ?></div>
                            <h6 class="fun-fact__text"><?php echo e($label); ?></h6>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <?php if (isset($component)) { $__componentOriginalea2809d7595df45ed6f356553cc47031 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalea2809d7595df45ed6f356553cc47031 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.testimonials','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.testimonials'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalea2809d7595df45ed6f356553cc47031)): ?>
<?php $attributes = $__attributesOriginalea2809d7595df45ed6f356553cc47031; ?>
<?php unset($__attributesOriginalea2809d7595df45ed6f356553cc47031); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalea2809d7595df45ed6f356553cc47031)): ?>
<?php $component = $__componentOriginalea2809d7595df45ed6f356553cc47031; ?>
<?php unset($__componentOriginalea2809d7595df45ed6f356553cc47031); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal2fb7d9fe440c4688b861c026e7f03cfa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2fb7d9fe440c4688b861c026e7f03cfa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.brands','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.brands'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2fb7d9fe440c4688b861c026e7f03cfa)): ?>
<?php $attributes = $__attributesOriginal2fb7d9fe440c4688b861c026e7f03cfa; ?>
<?php unset($__attributesOriginal2fb7d9fe440c4688b861c026e7f03cfa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2fb7d9fe440c4688b861c026e7f03cfa)): ?>
<?php $component = $__componentOriginal2fb7d9fe440c4688b861c026e7f03cfa; ?>
<?php unset($__componentOriginal2fb7d9fe440c4688b861c026e7f03cfa); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\tech-corporate\resources\views/public/about.blade.php ENDPATH**/ ?>