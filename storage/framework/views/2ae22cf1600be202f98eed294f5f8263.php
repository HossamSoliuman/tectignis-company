<?php $__env->startSection('title', 'Top IT Services & Solutions in Navi Mumbai | Serving PAN India'); ?>

<?php $__env->startSection('seo'); ?>
    <meta name="description" content="Discover expert website design, digital marketing, and IT infrastructure solutions in Navi Mumbai. Tectignis IT Solutions offers cutting-edge services for startups, businesses, and entrepreneurs. Get a Free Quote!">
    <meta name="keywords" content="IT Services, Software Development, Networking Solutions, CCTV Systems, Mobile App Development, Web Design & Development, IT Services in Navi Mumbai, Software Development in Mumbai, CCTV Solutions Navi Mumbai, IT Services PAN India, Networking Solutions India, Custom Software Solutions PAN India">
    <meta property="og:title" content="Top IT Services & Solutions in Navi Mumbai | Serving PAN India">
    <meta property="og:description" content="Discover expert website design, digital marketing, and IT infrastructure solutions in Navi Mumbai. Tectignis IT Solutions offers cutting-edge services for startups, businesses, and entrepreneurs. Get a Free Quote!">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(url('/')); ?>">
    <link rel="canonical" href="<?php echo e(url('/')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <!--============ Infotechno Hero Start ============-->
    <div class="software-innovation-hero-wrapper section-space--pt_10">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="software-innovation-hero-wrap wow move-up">
                        <div class="software-innovation-hero-text">
                            <p class="sub-heading"><?php echo e($settings['hero_sub_heading'] ?? 'Innovation across Pan India.'); ?></p>
                            <h3><?php echo e($settings['hero_heading_line1'] ?? 'Secure future with our'); ?> </h3>
                            <h1 class="font-weight--reguler mb-30"><?php echo e($settings['hero_heading_line2'] ?? 'Software'); ?> </h1>
                            <h6 class="info-heading"><?php echo e($settings['hero_info_heading'] ?? 'And IT solutions engineered for precision, performance, & peace of mind.'); ?></h6>
                            <div class="hero-button  mt-30">
                                <a href="<?php echo e(route('contact')); ?>" class="ht-btn ht-btn-md">Connect with us</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="software-innovation-hero-image animation_images three mt-30">
                        <img src="<?php echo e(asset('assets/images/hero/'.($settings['hero_image'] ?? 'custom-software-solution-pan-india.webp'))); ?>" class="img-fluid" alt="Custom Software Solution Pan India">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--============ Infotechno Hero End ============-->

    <!--====================  brand logo slider area ====================-->
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
    <!--====================  End of brand logo slider area  ====================-->

    <!--===========  feature-images-wrapper  Start =============-->
    <div class="feature-images-wrapper bg-gray section-space--ptb_100">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <!-- section-title-wrap Start -->
                    <div class="section-title-wrap text-center">
                        <h6 class="section-sub-title mb-20">Our services</h6>
                        <h3 class="heading mb-40"><span class="text-color-primary"> Services </span>We Provide</h3>
                    </div>
                    <!-- section-title-wrap Start -->
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="projects-wrap">

                        <div class="row">
                            <?php $__currentLoopData = $services->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-lg-4 col-md-6">
                                <!-- Projects Wrap Start -->
                                <a href="<?php echo e(route('capabilities.show', $service->slug)); ?>" class="projects-wrap style-04 wow move-up">
                                    <div class="projects-image-box">

                                        <div class="box-image text-center">
                                            <img class="img-fulid" src="<?php echo e(asset('assets/images/icons/'.$service->icon)); ?>" alt="<?php echo e($service->title); ?>" loading="lazy">
                                        </div>

                                        <div class="content text-center">
                                            <h5 class="heading"><?php echo e($service->title); ?></h5>
                                            <div class="text"><?php echo e($service->short_description); ?></div>
                                            <div class="box-projects-arrow">
                                                <span class="button-text">Discover Now</span>
                                                <i class="fas fa-arrow-right ml-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <!-- Projects Wrap End -->
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="button-group-wrap text-center">
                        <a href="<?php echo e(route('capabilities.index')); ?>" class="btn">View More Services</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--===========  feature-images-wrapper  End =============-->

    <!--=========== fun fact Wrapper Start ==========-->
    <div class="fun-fact-wrapper bg-theme-default section-space--pb_30 section-space--pt_60">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 wow move-up">
                    <div class="fun-fact--two text-center">
                        <div class="fun-fact__count counter"><?php echo e($settings['stat_happy_clients'] ?? '500'); ?></div>
                        <h6 class="fun-fact__text">Happy clients</h6>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 wow move-up">
                    <div class="fun-fact--two text-center">
                        <div class="fun-fact__count counter"><?php echo e($settings['stat_projects'] ?? '350'); ?></div>
                        <h6 class="fun-fact__text">Projects Delivered</h6>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 wow move-up">
                    <div class="fun-fact--two text-center">
                        <div class="fun-fact__count"><?php echo e($settings['stat_growth'] ?? '1M'); ?></div>
                        <h6 class="fun-fact__text">Growth</h6>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 wow move-up">
                    <div class="fun-fact--two text-center">
                        <div class="fun-fact__count"><?php echo e($settings['stat_users'] ?? '1K'); ?></div>
                        <h6 class="fun-fact__text">Active Users</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--=========== fun fact Wrapper End ==========-->

    <div class="software-innovation-about-company-area software-innovation-about-bg section-space--ptb_120">
        <div class="container">

            <div class="row">

                <div class="col-lg-6">
                    <div class="image-inner-video-section">
                        <img class="img-fluid border-radus-5" src="<?php echo e(asset('assets/images/banners/'.($settings['what_we_offer_image'] ?? 'IT-Services-in-Nav-Mumbai.webp'))); ?>" alt="IT Services PAN India" loading="lazy">
                    </div>
                </div>
                <div class="col-lg-6 ms-auto mt-30">
                    <div class="machine-learning-about-content">
                        <div class="section-title mb-20">
                            <!-- section-title-wrap Start -->
                            <div class="section-title-wrap text-left section-space--mb_30">
                                <h6 class="section-sub-title mb-20"><?php echo e($settings['what_we_offer_sub'] ?? 'OVER 500+ CLIENT'); ?></h6>
                                <h3 class="heading">What We <span class="text-color-primary">Offer</span>
                                </h3>
                            </div>
                            <!-- section-title-wrap Start -->
                            <div class="list-group-wrap section-space--mb_60">
                                <p class="dec-text mt-20">Boost your business with cutting-edge website design, e-commerce, SEO, and security solutions. Tectignis IT Solutions serves startups, businesses, and enterprises in Navi Mumbai with expert IT services.</p>
                                <ul class="check-list">
                                    <li class="list-item"><strong>Proven expertise</strong> and years of experience delivering results-driven solutions.</li>
                                    <li class="list-item"><strong>Tailored Approach </strong> for customized services to meet your unique business needs.</li>
                                    <li class="list-item"><strong> End-to-End Support </strong> from installation to maintenance, we've got you covered.</li>
                                    <li class="list-item"><strong>Strategic Location </strong> based in Navi Mumbai, ensuring quick and reliable service.</li>
                                </ul>
                                <div class="button-group-wrap mt-3">
                                    <a href="<?php echo e(route('about')); ?>" class="btn">About Us</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--===========  feature-images-wrapper  Start =============-->
    <div class="feature-images-wrapper bg-gray section-space--ptb_100">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <!-- section-title-wrap Start -->
                    <div class="section-title-wrap text-center section-space--mb_10">
                        <h3 class="heading">For your very specific industry, <br> we have <span class="text-color-primary"> highly-tailored IT solutions.</span></h3>
                    </div>
                    <!-- section-title-wrap Start -->
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="feature-images__two small-mt__10">
                        <div class="modern-grid-image-box row row--30">

                            <div class="single-item wow move-up col-lg-4 col-md-6 section-space--mt_60  small-mt__40">
                                <!-- ht-box-icon Start -->
                                <div class="ht-box-images style-02">
                                    <div class="image-box-wrap">
                                        <div class="box-image">
                                            <img class="img-fulid" src="<?php echo e(asset('assets/images/icons/Custom-Software.webp')); ?>" alt="Custom Software" loading="lazy">
                                        </div>
                                        <div class="content">
                                            <h6 class="heading">Understanding Your Needs</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- ht-box-icon End -->
                            </div>

                            <div class="single-item wow move-up col-lg-4 col-md-6 section-space--mt_60">
                                <!-- ht-box-icon Start -->
                                <div class="ht-box-images style-02">
                                    <div class="image-box-wrap">
                                        <div class="box-image">
                                            <img class="img-fulid" src="<?php echo e(asset('assets/images/icons/Web-Design-and-Development.webp')); ?>" alt="Web Design and Development" loading="lazy">
                                        </div>
                                        <div class="content">
                                            <h6 class="heading">Customized Planning</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- ht-box-icon End -->
                            </div>

                            <div class="single-item wow move-up col-lg-4 col-md-6 section-space--mt_60">
                                <!-- ht-box-icon Start -->
                                <div class="ht-box-images style-02">
                                    <div class="image-box-wrap">
                                        <div class="box-image">
                                            <img class="img-fulid" src="<?php echo e(asset('assets/images/icons/Software-Development.webp')); ?>" alt="Software Development" loading="lazy">
                                        </div>
                                        <div class="content">
                                            <h6 class="heading">Seamless Implementation</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- ht-box-icon End -->
                            </div>

                            <div class="single-item wow move-up col-lg-4 col-md-6 section-space--mt_60">
                                <!-- ht-box-icon Start -->
                                <div class="ht-box-images style-02">
                                    <div class="image-box-wrap">
                                        <div class="box-image">
                                            <img class="img-fulid" src="<?php echo e(asset('assets/images/icons/CCTV-Systems.webp')); ?>" alt="CCTV Systems" loading="lazy">
                                        </div>
                                        <div class="content">
                                            <h6 class="heading">Cutting-Edge Technology</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- ht-box-icon End -->
                            </div>

                            <div class="single-item wow move-up col-lg-4 col-md-6 section-space--mt_60">
                                <!-- ht-box-icon Start -->
                                <div class="ht-box-images style-02">
                                    <div class="image-box-wrap">
                                        <div class="box-image">
                                            <img class="img-fulid" src="<?php echo e(asset('assets/images/icons/Networking-Solutions.webp')); ?>" alt="Networking Solutions" loading="lazy">
                                        </div>
                                        <div class="content">
                                            <h6 class="heading">Proactive Support and Maintenance</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- ht-box-icon End -->
                            </div>

                            <div class="single-item wow move-up col-lg-4 col-md-6 section-space--mt_60">
                                <!-- ht-box-icon Start -->
                                <div class="ht-box-images style-02">
                                    <div class="image-box-wrap">
                                        <div class="box-image">
                                            <img class="img-fulid" src="<?php echo e(asset('assets/images/icons/MobileApp-Development.webp')); ?>" alt="MobileApp Development" loading="lazy">
                                        </div>
                                        <div class="content">
                                            <h6 class="heading">Continuous Improvement</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- ht-box-icon End -->
                            </div>

                        </div>
                    </div>

                    <div class="section-under-heading text-center section-space--mt_60">Challenges are just opportunities in disguise. <a href="<?php echo e(route('contact')); ?>">Take the challenge!</a></div>

                </div>
            </div>
        </div>
    </div>
    <!--===========  feature-images-wrapper  End =============-->

    <div class="technology-service-area technology-service-bg">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="section-title section-space--pt_60">
                        <p class="text-white font-weight--bold">Our Expertise Digital Transformation</p>
                        <h3 class="text-white"><span class="text-color-secondary">Tectignis </span> – Best IT Services in navi mumbai</h3>
                        <p class="text-infos text-white mt-30"><strong>Tectignis IT Solutions in Navi Mumbai empowers businesses with cutting-edge digital solutions.</strong> We specialize in creating visually captivating and user-friendly websites, including e-commerce platforms, and implementing robust online marketing strategies to drive organic growth. Our expertise extends to advanced security systems, including CCTV, access control, and comprehensive IT infrastructure management, ensuring the safety and smooth operation of your business.</p>
                        <p class="text-infos text-white mt-30">We are committed to delivering results-driven solutions that help you achieve your business goals and thrive in the competitive digital landscape.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="banner-image section-space--pt_60">
                        <img src="<?php echo e(asset('assets/images/banners/Networking-Solutions-India.webp')); ?>" class="img-fluid" alt="Networking Solutions India" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--====================  gradation process area ====================-->
    <div class="gradation-process-area section-space--ptb_100">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <div class="gradation-title-wrapper section-space--mb_60">
                        <div class="gradation-title-wrap ">
                            <h6 class="section-sub-title text-black mb-20">How we works</h6>
                            <h4 class="heading">How it helps <span class="text-color-primary">your <br> business succeed</span></h4>
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
                        <!-- Single item gradation Start -->
                        <div class="item item-1 animate  wow fadeInRight" data-wow-delay="0.1s">
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

                                <h6 class="heading">01. Discussion</h6>

                                <div class="text">We conduct in-person consultations with clients at a designated location to thoroughly understand their specific needs and requirements before developing a customized proposal.</div>

                            </div>
                        </div>
                        <!-- Single item gradation End -->

                        <!-- Single item gradation Start -->
                        <div class="item item-1 animate  wow fadeInRight" data-wow-delay="0.15s">
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

                                <h6 class="heading">02. Concepts &amp; Initatives</h6>

                                <div class="text">Leveraging our expert knowledge, we develop a wide range of ideas and initiatives to ensure the delivery of top-tier IT service solutions.</div>

                            </div>
                        </div>
                        <!-- Single item gradation End -->

                        <!-- Single item gradation Start -->
                        <div class="item item-1 animate  wow fadeInRight" data-wow-delay="0.20s">
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

                                <h6 class="heading">03. Testing &amp; Trying</h6>

                                <div class="text">Following the confirmation of ideas and plans, we will proceed as scheduled and provide feedback on the outcomes and necessary adjustments.</div>

                            </div>
                        </div>
                        <!-- Single item gradation End -->

                        <!-- Single item gradation Start -->
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

                                <h6 class="heading">04. Execute &amp; install</h6>

                                <div class="text">Subsequent to final plan approval, all operations will be executed in accordance with the contractual agreement.</div>

                            </div>
                        </div>
                        <!-- Single item gradation End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====================  End of gradation process area  ====================-->

    <!--=========== Service Projects Wrapper Start =============-->
    <div class="service-projects-wrapper section-space--pt_100 mb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_60">
                        <h6 class="section-sub-title mb-20">Case studies</h6>
                        <h3 class="heading">Proud projects that <span class="text-color-primary">make us stand
                                out</span></h3>
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
                                    <div class="section-under-heading"><a href="<?php echo e(route('case-studies.index')); ?>">View More</a></div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-xl-7 order-1 order-lg-2">
                                <div class="slide-image">
                                    <div class="image-wrap">
                                        <div class="image">
                                            <?php if($caseStudy->image): ?>
                                                <img class="img-fluid" src="<?php echo e(asset('assets/images/features/'.$caseStudy->image)); ?>" alt="<?php echo e($caseStudy->title); ?>" loading="lazy">
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
        <!--=========== Service Projects Wrapper End =============-->

    </div>
    <!--=========== Service Projects Wrapper End =============-->

    <!--====================  testimonial section ====================-->
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
    <!--====================  End of testimonial section  ====================-->

    <!--====================  Conact us Section Start ====================-->
    <div class="contact-us-section-wrappaer infotechno-contact-us-bg section-space--ptb_120">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-lg-6">
                    <div class="conact-us-wrap-one">
                        <h3 class="heading">Obtaining further information by <span class="text-color-primary">make a contact</span> with our experienced IT staffs. </h3>

                        <div class="sub-heading">Customized IT Solutions for your Business.</div>

                    </div>
                </div>

                <div class="col-lg-6 col-lg-6">
                    <div class="contact-info-one text-center">
                        <div class="icon">
                            <span class="fas fa-phone"></span>
                        </div>
                        <h6 class="heading font-weight--reguler">let's grow your business together!</h6>
                        <h2 class="call-us"><a href="tel:+919987705688">+91 9987705688</a></h2>
                        <div class="contact-us-button mt-20">
                            <a href="<?php echo e(route('contact')); ?>" class="btn btn--secondary">Contact Us Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====================  Conact us Section End  ====================-->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\tech-corporate\resources\views/public/home.blade.php ENDPATH**/ ?>