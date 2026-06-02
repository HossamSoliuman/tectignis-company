<?php $__env->startSection('title', 'Software, AI & IT Solutions Company | Navi Mumbai, Mumbai, Pune, India | Tectignis'); ?>

<?php $__env->startSection('seo'); ?>
    <meta name="description" content="Tectignis IT Solutions – Custom Software Development, AI Automation, Cloud Infrastructure, Cybersecurity & Smart Security Systems. Serving Navi Mumbai, Mumbai, Thane, Pune, Maharashtra & Worldwide.">
    <meta name="keywords" content="software development company Navi Mumbai, IT solutions Mumbai, AI development India, cloud services Pune, cybersecurity company Thane, ERP development, CRM solutions, CCTV installation Mumbai">
    <meta property="og:title" content="Software, AI & IT Solutions Company | Navi Mumbai | Tectignis">
    <meta property="og:description" content="Custom Software Development, AI Automation, Cloud Infrastructure, Cybersecurity & Smart Security Systems. Serving Navi Mumbai, Mumbai, Thane, Pune, India & Worldwide.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(url('/')); ?>">
    <link rel="canonical" href="<?php echo e(url('/')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <!--============ Hero Banner Start ============-->
    <div class="software-innovation-hero-wrapper section-space--pt_10">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="software-innovation-hero-wrap wow move-up">
                        <div class="software-innovation-hero-text">
                            <p class="sub-heading">Serving Clients Across Navi Mumbai, Mumbai, Thane, Pune, India &amp; Worldwide.</p>
                            <h1 class="font-weight--reguler mb-20">Transforming Businesses Through <span class="text-color-primary">Software, AI &amp; Smart Technology</span> Solutions</h1>
                            <h6 class="info-heading">Custom Software Development, AI Automation, Cloud Infrastructure, Cybersecurity &amp; Smart Security Systems.</h6>
                            <div class="hero-button mt-30">
                                <a href="<?php echo e(route('contact')); ?>" class="ht-btn ht-btn-md">Request Consultation</a>
                                <a href="<?php echo e(route('contact')); ?>" class="ht-btn ht-btn-md ht-btn--outline ms-3">Get a Quote</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="software-innovation-hero-image animation_images three mt-30">
                        <img src="<?php echo e(\App\Models\Setting::imageUrl($settings['hero_image'] ?? 'custom-software-solution-pan-india.webp', 'hero_image')); ?>" class="img-fluid" alt="Software AI Smart Technology Solutions">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--============ Hero Banner End ============-->

    <!--====================  Brand Logo Slider ====================-->
    <?php if (isset($component)) { $__componentOriginal2fb7d9fe440c4688b861c026e7f03cfa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2fb7d9fe440c4688b861c026e7f03cfa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.brands','data' => ['brands' => $brands]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.brands'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['brands' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($brands)]); ?>
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
    <!--====================  End of Brand Logo Slider ====================-->

    <!--=========== Trusted Technology Partner Stats Start ==========-->
    <div class="fun-fact-wrapper bg-theme-default section-space--pb_60 section-space--pt_60">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb-30">
                    <h6 class="section-sub-title">Trusted Technology Partner</h6>
                </div>
            </div>
            <div class="row">
                <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-3 col-sm-6 wow move-up">
                    <div class="fun-fact--two text-center">
                        <div class="fun-fact__count <?php if(is_numeric($stat->value)): ?> counter <?php endif; ?>"><?php echo e($stat->value); ?></div>
                        <h6 class="fun-fact__text"><?php echo e($stat->label); ?></h6>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <!--=========== Trusted Technology Partner Stats End ==========-->

    <!--===========  Our Capabilities Start =============-->
    <div class="feature-images-wrapper bg-gray section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">What We Do</h6>
                        <h3 class="heading">Our <span class="text-color-primary">Capabilities</span></h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php $__currentLoopData = $capabilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $capability): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-md-6 wow move-up">
                    <div class="projects-wrap style-04">
                        <div class="projects-image-box">
                            <div class="box-image text-center">
                                <img class="img-fulid" src="<?php echo e(asset('uploads/'.$capability->icon)); ?>" alt="<?php echo e($capability->title); ?>" loading="lazy">
                            </div>
                            <div class="content text-center">
                                <h5 class="heading"><?php echo e($capability->title); ?></h5>
                                <div class="text"><?php echo e($capability->short_description); ?></div>
                                <div class="box-projects-arrow">
                                    <a href="<?php echo e(route('capabilities.show', $capability->slug)); ?>" class="button-text">Discover Now <i class="fas fa-arrow-right ml-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="button-group-wrap text-center mt-40">
                <a href="<?php echo e(route('capabilities.index')); ?>" class="btn">View All Capabilities</a>
            </div>
        </div>
    </div>
    <!--===========  Our Capabilities End =============-->

    <!--===========  Why Choose Tectignis Start =============-->
    <div class="software-innovation-about-company-area software-innovation-about-bg section-space--ptb_120">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="image-inner-video-section">
                        <img class="img-fluid border-radus-5" src="<?php echo e(\App\Models\Setting::imageUrl($settings['what_we_offer_image'] ?? 'IT-Services-in-Nav-Mumbai.webp', 'what_we_offer_image')); ?>" alt="Why Choose Tectignis IT Solutions" loading="lazy">
                    </div>
                </div>
                <div class="col-lg-6 ms-auto mt-30">
                    <div class="machine-learning-about-content">
                        <div class="section-title mb-20">
                            <div class="section-title-wrap text-left section-space--mb_30">
                                <h6 class="section-sub-title mb-20">Strategic Delivery Network</h6>
                                <h3 class="heading">Why Choose <span class="text-color-primary">Tectignis</span>?</h3>
                            </div>
                            <p class="dec-text mt-20">We collaborate with experienced technology professionals and implementation partners to assemble the right expertise for each project — delivering enterprise-grade solutions while maintaining competitive pricing and agility.</p>
                            <ul class="check-list mt-20">
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>Global Delivery Model</strong></li>
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>Cost-Effective Solutions</strong></li>
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>Dedicated Project Management</strong></li>
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>Scalable Resources</strong></li>
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>Quality Assurance &amp; Testing</strong></li>
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>On-Time Delivery</strong></li>
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>Single Point of Contact</strong></li>
                                <li class="list-item"><i class="fas fa-check text-color-primary me-2"></i><strong>Multi-Domain Expertise</strong></li>
                            </ul>
                            <div class="button-group-wrap mt-30">
                                <a href="<?php echo e(route('about')); ?>" class="btn">About Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===========  Why Choose Tectignis End =============-->

    <!--===========  Solutions We Deliver Start =============-->
    <div class="feature-images-wrapper bg-gray section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">Business-Focused</h6>
                        <h3 class="heading">Solutions We <span class="text-color-primary">Deliver</span></h3>
                    </div>
                </div>
            </div>
            <div class="row row--30">
                <?php $__currentLoopData = $solutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-3 col-md-4 col-sm-6 wow move-up section-space--mt_30">
                    <a href="<?php echo e(route('solutions.show', $solution->slug)); ?>" class="ht-box-images style-04 d-block text-center p-4 h-100">
                        <div class="image-box-wrap">
                            <div class="box-image mb-20">
                                <i class="<?php echo e($solution->icon); ?> fa-3x text-color-primary"></i>
                            </div>
                            <div class="content">
                                <h6 class="heading"><?php echo e($solution->title); ?></h6>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <!--===========  Solutions We Deliver End =============-->

    <!--===========  Industries We Serve Start =============-->
    <div class="feature-images-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">Sector Expertise</h6>
                        <h3 class="heading">Industries We <span class="text-color-primary">Serve</span></h3>
                    </div>
                </div>
            </div>
            <div class="row row--30">
                <?php $__currentLoopData = $industries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $industry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-3 col-md-4 col-sm-6 wow move-up section-space--mt_30">
                    <a href="<?php echo e(route('industries.show', $industry->slug)); ?>" class="ht-box-images style-03 d-block text-center p-4">
                        <div class="image-box-wrap">
                            <div class="box-image mb-20">
                                <i class="<?php echo e($industry->icon); ?> fa-2x text-color-primary"></i>
                            </div>
                            <div class="content">
                                <h6 class="heading"><?php echo e($industry->name); ?></h6>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <!--===========  Industries We Serve End =============-->

    <!--=========== Featured Services Start ===========-->
    <div class="feature-images-wrapper bg-gray section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">Premium Expertise</h6>
                        <h3 class="heading">Featured <span class="text-color-primary">Services</span></h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php $__currentLoopData = $services->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-3 col-md-4 col-sm-6 wow move-up section-space--mt_30">
                    <a href="<?php echo e(route('services.show', $service->slug)); ?>" class="projects-wrap style-04 d-block">
                        <div class="projects-image-box">
                            <div class="box-image text-center">
                                <img class="img-fulid" src="<?php echo e(asset('uploads/'.$service->icon)); ?>" alt="<?php echo e($service->title); ?>" loading="lazy">
                            </div>
                            <div class="content text-center">
                                <h5 class="heading"><?php echo e($service->title); ?></h5>
                                <div class="box-projects-arrow">
                                    <span class="button-text">Learn More</span>
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="button-group-wrap text-center mt-40">
                <a href="<?php echo e(route('services.index')); ?>" class="btn">View All Services</a>
            </div>
        </div>
    </div>
    <!--=========== Featured Services End ===========-->

    <!--=========== Technology Stack Start ===========-->
    <div class="section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">Tools &amp; Platforms</h6>
                        <h3 class="heading">Technology <span class="text-color-primary">Stack</span></h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center text-center">
                <?php $__currentLoopData = $techStacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tech): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-2 col-md-3 col-4 wow move-up section-space--mt_30">
                    <div class="ht-box-images style-03 text-center p-3">
                        <?php if($tech->logo): ?>
                            <img class="img-fluid mb-10" src="<?php echo e(asset('uploads/'.$tech->logo)); ?>" alt="<?php echo e($tech->name); ?>" loading="lazy" style="max-height:40px;width:auto;display:inline-block;">
                        <?php endif; ?>
                        <p class="font-weight--bold text-color-primary mb-0"><?php echo e($tech->name); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <!--=========== Technology Stack End ===========-->

    <!--=========== Case Studies Start =============-->
    <div class="service-projects-wrapper bg-gray section-space--pt_100 mb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_60">
                        <h6 class="section-sub-title mb-20">Case Studies</h6>
                        <h3 class="heading">Proud projects that <span class="text-color-primary">make us stand out</span></h3>
                    </div>
                </div>
            </div>
            <div class="swiper-container service-slider__project-active">
                <div class="swiper-wrapper service-slider__project">
                    <?php $__currentLoopData = $caseStudies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $caseStudy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="slide-content col-lg-6 col-xl-5 order-2 order-lg-1">
                                <div class="service-project-slide-info">
                                    <h4 class="heading font-weight--reguler mb-10"><?php echo e($caseStudy->title); ?></h4>
                                    <p class="sub-text text-color-primary"><?php echo e($caseStudy->category); ?></p>
                                    <div class="text"><?php echo e($caseStudy->short_description); ?></div>
                                    <div class="section-under-heading"><a href="<?php echo e(route('case-studies.index')); ?>">View All Case Studies</a></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-7 order-1 order-lg-2">
                                <div class="slide-image">
                                    <div class="image-wrap">
                                        <div class="image">
                                            <?php if($caseStudy->image): ?>
                                                <img class="img-fluid" src="<?php echo e(asset('uploads/'.$caseStudy->image)); ?>" alt="<?php echo e($caseStudy->title); ?>" loading="lazy">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
    <!--=========== Case Studies End =============-->

    <!--====================  Testimonials ====================-->
    <?php if (isset($component)) { $__componentOriginalea2809d7595df45ed6f356553cc47031 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalea2809d7595df45ed6f356553cc47031 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.testimonials','data' => ['testimonials' => $testimonials]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.testimonials'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['testimonials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($testimonials)]); ?>
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
    <!--====================  End of Testimonials ====================-->

    <!--=========== Service Coverage Start ===========-->
    <div class="technology-service-area technology-service-bg section-space--ptb_80">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="section-title section-space--pt_60">
                        <h6 class="text-white section-sub-title mb-20">Local &amp; Global Reach</h6>
                        <h3 class="text-white">Serving Businesses <span class="text-color-secondary">Across India &amp; Worldwide</span></h3>
                        <div class="row mt-30">
                            <div class="col-6">
                                <h6 class="text-white font-weight--bold mb-15">India</h6>
                                <ul class="list-unstyled text-white">
                                    <li class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Navi Mumbai</li>
                                    <li class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Mumbai</li>
                                    <li class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Thane</li>
                                    <li class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Pune</li>
                                    <li class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Panvel</li>
                                    <li class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Maharashtra &amp; PAN India</li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <h6 class="text-white font-weight--bold mb-15">Global Delivery</h6>
                                <ul class="list-unstyled text-white">
                                    <li class="mb-1"><i class="fas fa-globe me-2"></i>UAE &amp; Saudi Arabia</li>
                                    <li class="mb-1"><i class="fas fa-globe me-2"></i>UK &amp; USA</li>
                                    <li class="mb-1"><i class="fas fa-globe me-2"></i>Canada</li>
                                    <li class="mb-1"><i class="fas fa-globe me-2"></i>Australia</li>
                                    <li class="mb-1"><i class="fas fa-globe me-2"></i>Singapore</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="banner-image section-space--pt_60">
                        <img src="<?php echo e(\App\Models\Setting::imageUrl($settings['tech_service_image'] ?? 'Networking-Solutions-India.webp', 'tech_service_image')); ?>" class="img-fluid" alt="Global IT Solutions Delivery" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--=========== Service Coverage End ===========-->

    <!--=========== Resources & Insights Start ===========-->
    <?php if($recentPosts->isNotEmpty()): ?>
    <div class="feature-images-wrapper bg-gray section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">Stay Informed</h6>
                        <h3 class="heading">Resources &amp; <span class="text-color-primary">Insights</span></h3>
                    </div>
                </div>
            </div>
            <div class="row row--30">
                <?php $__currentLoopData = $recentPosts->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-md-6 wow move-up section-space--mt_30">
                    <div class="blog-wrap-layout3">
                        <div class="content">
                            <?php if($post->category): ?>
                            <div class="post-meta mb-10">
                                <span class="text-color-primary"><?php echo e($post->category); ?></span>
                            </div>
                            <?php endif; ?>
                            <h5 class="heading">
                                <a href="<?php echo e(route('blog.show', $post->slug)); ?>"><?php echo e($post->title); ?></a>
                            </h5>
                            <p class="text mt-10"><?php echo e(Str::limit($post->excerpt ?? $post->short_description ?? '', 120)); ?></p>
                            <div class="post-meta mt-20">
                                <a href="<?php echo e(route('blog.show', $post->slug)); ?>" class="button-text">Read More <i class="fas fa-arrow-right ml-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="button-group-wrap text-center mt-40">
                <a href="<?php echo e(route('blog.index')); ?>" class="btn">View All Posts</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!--=========== Resources & Insights End ===========-->

    <!--====================  Final CTA Start ====================-->
    <div class="contact-us-section-wrappaer infotechno-contact-us-bg section-space--ptb_120">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 col-md-6">
                    <div class="conact-us-wrap-one">
                        <h3 class="heading">Ready to <span class="text-color-primary">Transform</span> Your Business?</h3>
                        <div class="sub-heading mt-20">Let's discuss your software, AI, cloud, cybersecurity, or infrastructure requirements.</div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6 mt-30 mt-md-0">
                    <div class="contact-info-one text-center">
                        <div class="contact-us-button d-flex flex-wrap justify-content-center gap-3">
                            <a href="<?php echo e(route('contact')); ?>" class="ht-btn ht-btn-md">Request Consultation</a>
                            <a href="<?php echo e(route('contact')); ?>" class="ht-btn ht-btn-md ht-btn--outline">Get a Quote</a>
                        </div>
                        <div class="mt-20">
                            <h2 class="call-us"><a href="tel:+919987705688">+91 9987705688</a></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====================  Final CTA End ====================-->

    <!--====================  Contact Section Start ====================-->
    <div class="contact-form-section section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 wow move-up">
                    <div class="section-title-wrap text-left section-space--mb_30">
                        <h6 class="section-sub-title mb-20">Get In Touch</h6>
                        <h3 class="heading">Tectignis IT Solutions <span class="text-color-primary">Pvt. Ltd.</span></h3>
                    </div>
                    <ul class="list-unstyled">
                        <li class="mb-15"><i class="fas fa-map-marker-alt text-color-primary me-3"></i>Navi Mumbai, Maharashtra, India</li>
                        <li class="mb-15"><i class="fas fa-phone text-color-primary me-3"></i><a href="tel:+919987705688">+91 9987705688</a></li>
                        <li class="mb-15"><i class="fas fa-envelope text-color-primary me-3"></i><a href="mailto:info@tectignis.in">info@tectignis.in</a></li>
                    </ul>
                </div>
                <div class="col-lg-6 wow move-up">
                    <div class="contact-form">
                        <form action="<?php echo e(route('contact.submit')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-6 mb-20">
                                    <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <input type="tel" name="phone" class="form-control" placeholder="Phone Number">
                                </div>
                                <div class="col-md-6 mb-20">
                                    <input type="text" name="subject" class="form-control" placeholder="Service Required">
                                </div>
                                <div class="col-12 mb-20">
                                    <textarea name="message" class="form-control" rows="4" placeholder="Your Message" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="ht-btn ht-btn-md">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====================  Contact Section End ====================-->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\tech-corporate\resources\views/public/home.blade.php ENDPATH**/ ?>