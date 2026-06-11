<?php
    $siteSettings = \App\Models\Setting::values();
    $footerLogo = \App\Models\Setting::imageUrl($siteSettings['site_logo_dark'] ?? null, 'site_logo_dark')
        ?? asset('assets/images/logo/Tectignis-IT-solution-logo.webp');
    $footerPhone = $siteSettings['site_phone'] ?? '+91 9987705688';
    $footerEmail = $siteSettings['site_email'] ?? 'info@tectignis.in';
    $whatsappNumber = preg_replace('/\D/', '', $siteSettings['social_whatsapp'] ?? '');
    $socials = array_filter([
        ['url' => $siteSettings['social_linkedin'] ?? null, 'icon' => 'fab fa-linkedin-in', 'label' => 'Visit LinkedIn'],
        ['url' => $siteSettings['social_twitter'] ?? null, 'icon' => 'fab fa-twitter', 'label' => 'Visit X / Twitter'],
        ['url' => $siteSettings['social_instagram'] ?? null, 'icon' => 'fab fa-instagram', 'label' => 'Visit Instagram'],
        ['url' => $siteSettings['social_facebook'] ?? null, 'icon' => 'fab fa-facebook-f', 'label' => 'Visit Facebook'],
        ['url' => $whatsappNumber ? 'https://wa.me/'.$whatsappNumber : null, 'icon' => 'fab fa-whatsapp', 'label' => 'Chat on WhatsApp'],
    ], fn (array $social): bool => filled($social['url']));
?>

<footer class="ft-dark__main" aria-label="Site Footer">
    <div class="container">
        <div class="row ft-dark__row">

            
            <div class="col-xl-3 col-lg-4 col-md-12 ft-dark__col ft-dark__brand">
                <a href="<?php echo e(route('home')); ?>" class="ft-dark__logo">
                    <img src="<?php echo e($footerLogo); ?>"
                         width="160" height="48" class="img-fluid" alt="Tectignis IT Solutions">
                </a>
                <p class="ft-dark__tagline">
                    Navi Mumbai's trusted partner for software, AI, cloud, cybersecurity and smart
                    security solutions. Empowering businesses with innovative technology.
                </p>
                <ul class="ft-dark__socials">
                    <?php $__currentLoopData = $socials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e($social['url']); ?>" target="_blank" rel="noopener" aria-label="<?php echo e($social['label']); ?>">
                                <i class="<?php echo e($social['icon']); ?>"></i>
                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>

            
            <nav class="col-xl col-lg-2 col-md-4 col-sm-6 ft-dark__col" aria-label="Company">
                <h6 class="ft-dark__heading">Company</h6>
                <ul class="ft-dark__links">
                    <li><a href="<?php echo e(route('about')); ?>">About Us</a></li>
                    <li><a href="<?php echo e(route('about')); ?>">Why Tectignis</a></li>
                    <li><a href="<?php echo e(route('careers')); ?>">Careers</a></li>
                    <li><a href="<?php echo e(route('technology-insights')); ?>">News &amp; Insights</a></li>
                    <li><a href="<?php echo e(route('contact')); ?>">Contact Us</a></li>
                </ul>
            </nav>

            
            <nav class="col-xl col-lg-2 col-md-4 col-sm-6 ft-dark__col" aria-label="Capabilities">
                <h6 class="ft-dark__heading">Capabilities</h6>
                <ul class="ft-dark__links">
                    <li><a href="<?php echo e(route('capabilities.index')); ?>">Software Development</a></li>
                    <li><a href="<?php echo e(route('capabilities.index')); ?>">AI &amp; Automation</a></li>
                    <li><a href="<?php echo e(route('capabilities.index')); ?>">Cloud &amp; Infrastructure</a></li>
                    <li><a href="<?php echo e(route('capabilities.index')); ?>">Data &amp; Analytics</a></li>
                    <li><a href="<?php echo e(route('capabilities.index')); ?>">Cybersecurity</a></li>
                    <li><a href="<?php echo e(route('capabilities.index')); ?>">Smart Security</a></li>
                </ul>
            </nav>

            
            <nav class="col-xl col-lg-2 col-md-4 col-sm-6 ft-dark__col" aria-label="Industries">
                <h6 class="ft-dark__heading">Industries</h6>
                <ul class="ft-dark__links">
                    <li><a href="<?php echo e(route('industries.index')); ?>">Manufacturing</a></li>
                    <li><a href="<?php echo e(route('industries.index')); ?>">Healthcare</a></li>
                    <li><a href="<?php echo e(route('industries.index')); ?>">Retail &amp; eCommerce</a></li>
                    <li><a href="<?php echo e(route('industries.index')); ?>">Logistics</a></li>
                    <li><a href="<?php echo e(route('industries.index')); ?>">Finance &amp; Banking</a></li>
                    <li><a href="<?php echo e(route('industries.index')); ?>">Education</a></li>
                </ul>
            </nav>

            
            <nav class="col-xl col-lg-3 col-md-6 col-sm-6 ft-dark__col" aria-label="Resources">
                <h6 class="ft-dark__heading">Resources</h6>
                <ul class="ft-dark__links">
                    <li><a href="<?php echo e(route('case-studies.index')); ?>">Case Studies</a></li>
                    <li><a href="<?php echo e(route('blog.index')); ?>">Blog</a></li>
                    <li><a href="<?php echo e(route('technology-insights')); ?>">Technology Insights</a></li>
                    <li><a href="<?php echo e(route('downloads')); ?>">Downloads</a></li>
                    <li><a href="<?php echo e(route('faqs')); ?>">FAQs</a></li>
                    <li><a href="<?php echo e(route('sitemap')); ?>">Sitemap</a></li>
                </ul>
            </nav>

            
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 ft-dark__col">
                <h6 class="ft-dark__heading">Contact Us</h6>
                <ul class="ft-dark__contact">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Navi Mumbai,<br>Maharashtra, India</span>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <a href="tel:<?php echo e(preg_replace('/[^+\d]/', '', $footerPhone)); ?>"><?php echo e($footerPhone); ?></a>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:<?php echo e($footerEmail); ?>"><?php echo e($footerEmail); ?></a>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        <span>Mon - Sat: 9AM - 8PM</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</footer>

<div class="ft-dark__copy">
    <div class="container">
        <div class="ft-dark__copy-inner">
            <span class="ft-dark__copy-text">
                &copy; <?php echo e(date('Y')); ?>

                <a href="<?php echo e(route('home')); ?>">Tectignis IT Solutions Pvt. Ltd.</a>
                All Rights Reserved.
            </span>
            <span class="ft-dark__copy-reg">GSTIN: 27AAHFT31725F1Z3</span>
            <span class="ft-dark__copy-reg">CIN: U72900MH2023PTC401102</span>
            <span class="ft-dark__copy-legal">
                <a href="<?php echo e(route('legal.show', 'privacy-policy')); ?>">Privacy Policy</a>
                <a href="<?php echo e(route('legal.show', 'terms-and-conditions')); ?>">Terms of Service</a>
                <a href="<?php echo e(route('legal.show', 'cookie-policy')); ?>">Cookie Policy</a>
            </span>
        </div>
    </div>
</div>
<?php /**PATH D:\projects\tech-corporate\resources\views/components/public/footer.blade.php ENDPATH**/ ?>