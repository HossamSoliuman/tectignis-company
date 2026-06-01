<div class="header-area header-area--default">

    <!-- Header Top Wrap Start -->
    <div class="header-top-wrap border-bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <p class="text-center top-message"> Leading Provider of IT Solutions in Navi Mumbai: <a href="<?php echo e(route('contact')); ?>">Expert Support</a></p>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Top Wrap End -->

    <!-- Header Bottom Wrap Start -->
    <div class="header-bottom-wrap header-sticky">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header default-menu-style position-relative">

                        <!-- brand logo -->
                        <div class="header__logo">
                            <a href="<?php echo e(route('home')); ?>">
                                <img src="<?php echo e(asset('assets/images/logo/Tectignis-IT-solution-logo.webp')); ?>" aria-label="Tectignis Logo" width="160" height="48" class="img-fluid" alt="Tectignis-IT-solution-logo">
                            </a>
                        </div>

                        <!-- header midle box  -->
                        <div class="header-midle-box">
                            <div class="header-bottom-wrap d-md-block d-none">
                                <div class="header-bottom-inner">
                                    <div class="header-bottom-left-wrap">
                                        <!-- navigation menu -->
                                        <div class="header__navigation d-none d-xl-block">
                                            <nav class="navigation-menu primary--menu">

                                                <ul>
                                                    <li>
                                                        <a href="<?php echo e(route('home')); ?>"><span>Home</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo e(route('about')); ?>"><span>About</span></a>
                                                    </li>

                                                    <li class="has-children">
                                                        <a href="<?php echo e(route('capabilities.index')); ?>"><span>Expertise</span></a>
                                                        <ul class="megamenu megamenu--mega">

                                                            <li>
                                                                <h2 class="page-list-title">IT Services</h2>
                                                                <ul>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'cctv-security')); ?>">CCTV &amp; Security Solutions</a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'access-control')); ?>">Access Control Systems</a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'amc')); ?>">Annual Maintenance Contracts (AMC)</a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'software-licensing')); ?>">Software Licensing</a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'network-storage')); ?>">Network Storage Solutions</a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'workstation-solutions')); ?>">Workstation Solutions</a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'networking-solutions')); ?>">Networking Solutions</a></li>
                                                                </ul>
                                                            </li>
                                                            <li>
                                                                <h2 class="page-list-title">Digital Services</h2>
                                                                <ul>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'website-design-development')); ?>">Website design &amp; development</a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'web-application-development')); ?>">Web Application Development</a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'ecommerce-development')); ?>">Ecommerce Development</a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'mobile-app-development')); ?>">Mobile App Development (Android &amp; iOS)</a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'hybrid-app-development')); ?>">Hybrid App Development</a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'custom-software-development')); ?>">Customised Software Development</a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'digital-marketing')); ?>">Digital Marketing Services</a></li>
                                                                </ul>
                                                            </li>

                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo e(route('careers')); ?>"><span>Careers</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo e(route('case-studies.index')); ?>"><span>Case Studies</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo e(route('contact')); ?>"><span>Contact</span></a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- header right box -->
                        <div class="header-box">

                            <!-- mobile menu -->
                            <div class="mobile-navigation-icon d-block d-xl-none" id="mobile-menu-trigger">
                                <i></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Bottom Wrap End -->

</div>

<!--====================  mobile menu overlay ====================-->
<div class="mobile-menu-overlay" id="mobile-menu-overlay">
    <div class="mobile-menu-overlay__inner">
        <div class="mobile-menu-overlay__header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6 col-8">
                        <!-- logo -->
                        <div class="logo">
                            <a href="<?php echo e(route('home')); ?>">
                                <img src="<?php echo e(asset('assets/images/logo/Tectignis-IT-solution-logo.webp')); ?>" class="img-fluid" alt="Tectignis-IT-solution-logo">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 col-4">
                        <!-- mobile menu content -->
                        <div class="mobile-menu-content text-end">
                            <span class="mobile-navigation-close-icon" id="mobile-menu-close-trigger"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mobile-menu-overlay__body">
            <nav class="offcanvas-navigation">
                <ul>
                    <li>
                        <a href="<?php echo e(route('home')); ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('about')); ?>">About</a>
                    </li>
                    <li class="has-children">
                        <a href="<?php echo e(route('capabilities.index')); ?>">Expertise</a>
                        <ul class="sub-menu">
                            <li><a href="<?php echo e(route('capabilities.show', 'website-design-development')); ?>"><span>Website Design &amp; Development</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'web-application-development')); ?>"><span>Web Application Development</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'ecommerce-development')); ?>"><span>E-commerce Development</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'mobile-app-development')); ?>"><span>Mobile App Development (Android &amp; iOS)</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'hybrid-app-development')); ?>"><span>Hybrid App Development</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'custom-software-development')); ?>"><span>Customize Software Development</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'cctv-security')); ?>"><span>CCTV &amp; Security Solutions</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'access-control')); ?>"><span>Access Control Systems</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'amc')); ?>"><span>Annual Maintenance Contracts (AMC)</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'software-licensing')); ?>"><span>Software Licensing</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'network-storage')); ?>"><span>Network Storage Solutions</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'workstation-solutions')); ?>"><span>Workstation Solutions</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'networking-solutions')); ?>"><span>Networking Solutions</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'digital-marketing')); ?>"><span>Digital Marketing Services</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?php echo e(route('contact')); ?>">Contact</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('careers')); ?>">Careers</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('case-studies.index')); ?>">Case Studies</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<!--====================  End of mobile menu overlay ====================-->
<?php /**PATH D:\projects\tech-corporate\resources\views/components/public/header.blade.php ENDPATH**/ ?>