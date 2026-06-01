<div class="header-area header-area--default">

    <!-- Header Top Wrap Start -->
    <div class="header-top-wrap border-bottom">
        <div class="container-fluid">
            <div class="header-top-inner">
                <div class="header-top-left">
                    <ul class="header-top-info">
                        <li><i class="fas fa-map-marker-alt"></i> Navi Mumbai, Maharashtra, India</li>
                        <li class="d-sm-hide"><i class="fas fa-globe"></i> Serving Clients Worldwide</li>
                    </ul>
                </div>
                <div class="header-top-right">
                    <ul class="header-top-info">
                        <li><a href="tel:+919987705688"><i class="fas fa-phone-alt"></i> +91 9987705688</a></li>
                        <li><a href="mailto:info@tectignis.in"><i class="fas fa-envelope"></i> info@tectignis.in</a></li>
                    </ul>
                    <a href="<?php echo e(route('contact')); ?>" class="header-top-btn">Request Consultation</a>
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
                                                        <a href="<?php echo e(route('about')); ?>"><span>Company</span></a>
                                                    </li>

                                                    <li class="has-children">
                                                        <a href="<?php echo e(route('capabilities.index')); ?>"><span>Capabilities</span></a>
                                                        <ul class="megamenu megamenu--mega megamenu--five">

                                                            <li>
                                                                <h2 class="page-list-title">Software Development</h2>
                                                                <ul>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'website-design-development')); ?>"><span>Website Design &amp; Development</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'web-application-development')); ?>"><span>Web Application Development</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'ecommerce-development')); ?>"><span>E-commerce Development</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'mobile-app-development')); ?>"><span>Mobile Application Development</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'custom-software-development')); ?>"><span>Custom Software Development</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>CRM Development</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>ERP Development</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>SaaS Product Development</span></a></li>
                                                                </ul>
                                                            </li>
                                                            <li>
                                                                <h2 class="page-list-title">Business Applications</h2>
                                                                <ul>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>HRMS Development</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>LMS Development</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Inventory Management Software</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Hospital Management Software</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>School / College Management</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Real Estate Management Software</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Visitor Management Software</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>POS (Point of Sale) Software</span></a></li>
                                                                </ul>
                                                            </li>
                                                            <li>
                                                                <h2 class="page-list-title">AI &amp; Automation</h2>
                                                                <ul>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>AI Integration</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>AI Chatbot Development</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>WhatsApp Business Automation</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>OCR &amp; Document Digitization</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Voice Bot Solutions</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Machine Learning Solutions</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Generative AI Solutions</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Business Process Automation</span></a></li>
                                                                </ul>
                                                            </li>
                                                            <li>
                                                                <h2 class="page-list-title">Cloud &amp; Security</h2>
                                                                <ul>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Cloud Migration Services</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>AWS Consulting</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Microsoft Azure Consulting</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Google Cloud Services</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Cybersecurity Consulting</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>VAPT Services</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Firewall Configuration &amp; Mgmt</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>SOC Support</span></a></li>
                                                                </ul>
                                                            </li>
                                                            <li>
                                                                <h2 class="page-list-title">Infrastructure &amp; Surveillance</h2>
                                                                <ul>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'networking-solutions')); ?>"><span>Networking Solutions</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Structured Cabling</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'workstation-solutions')); ?>"><span>Server Setup &amp; Management</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'network-storage')); ?>"><span>Storage Solutions</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'workstation-solutions')); ?>"><span>Workstation Solutions</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'cctv-security')); ?>"><span>CCTV &amp; Security Solutions</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'access-control')); ?>"><span>Access Control Systems</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.show', 'amc')); ?>"><span>AMC Services</span></a></li>
                                                                </ul>
                                                            </li>

                                                            <li class="megamenu-cta-row">
                                                                <div class="megamenu-cta">
                                                                    <a href="<?php echo e(route('contact')); ?>" class="megamenu-cta-box">
                                                                        <span class="cta-icon"><i class="fas fa-headset"></i></span>
                                                                        <span>
                                                                            <h6>Talk To Our Expert</h6>
                                                                            <span>Get expert guidance for your business needs.</span>
                                                                        </span>
                                                                    </a>
                                                                    <a href="<?php echo e(route('contact')); ?>" class="megamenu-cta-box">
                                                                        <span class="cta-icon"><i class="fas fa-file-invoice"></i></span>
                                                                        <span>
                                                                            <h6>Request A Quote</h6>
                                                                            <span>Get a tailored quote for your project.</span>
                                                                        </span>
                                                                    </a>
                                                                </div>
                                                            </li>

                                                        </ul>
                                                    </li>
                                                    <li class="has-children">
                                                        <a href="<?php echo e(route('capabilities.index')); ?>"><span>Solutions</span></a>
                                                        <ul class="megamenu megamenu--mega">
                                                            <li>
                                                                <h2 class="page-list-title">Business Solutions</h2>
                                                                <ul>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>ERP Solutions</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>CRM Solutions</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>HRMS Solutions</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>AI Solutions</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Cloud Solutions</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Security Solutions</span></a></li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li class="has-children">
                                                        <a href="#"><span>Industries</span></a>
                                                        <ul class="megamenu megamenu--mega">
                                                            <li>
                                                                <h2 class="page-list-title">Industries We Serve</h2>
                                                                <ul>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Manufacturing</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Healthcare</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Education</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Retail</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Real Estate</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Logistics</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Hospitality</span></a></li>
                                                                    <li><a href="<?php echo e(route('capabilities.index')); ?>"><span>Corporate Offices</span></a></li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo e(route('case-studies.index')); ?>"><span>Case Studies</span></a>
                                                    </li>
                                                    <li class="has-children">
                                                        <a href="#"><span>Resources</span></a>
                                                        <ul class="megamenu megamenu--mega">
                                                            <li>
                                                                <h2 class="page-list-title">Resources</h2>
                                                                <ul>
                                                                    <li><a href="<?php echo e(route('blog.index')); ?>"><span>Blog</span></a></li>
                                                                    <li><a href="<?php echo e(route('technology-insights')); ?>"><span>Technology Insights</span></a></li>
                                                                    <li><a href="<?php echo e(route('downloads')); ?>"><span>Downloads</span></a></li>
                                                                    <li><a href="<?php echo e(route('faqs')); ?>"><span>FAQs</span></a></li>
                                                                    <li><a href="<?php echo e(route('careers')); ?>"><span>Careers</span></a></li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo e(route('contact')); ?>"><span>Contact Us</span></a>
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
                        <a href="<?php echo e(route('capabilities.index')); ?>">Capabilities</a>
                        <ul class="sub-menu">
                            <li><a href="<?php echo e(route('capabilities.show', 'custom-software-development')); ?>"><span>Custom Software Development</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'website-design-development')); ?>"><span>Website Design &amp; Development</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'web-application-development')); ?>"><span>Web Application Development</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'mobile-app-development')); ?>"><span>Mobile App Development</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'ecommerce-development')); ?>"><span>E-commerce Development</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'cctv-security')); ?>"><span>CCTV &amp; Security Solutions</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'access-control')); ?>"><span>Access Control Systems</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'networking-solutions')); ?>"><span>Networking Solutions</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'amc')); ?>"><span>Annual Maintenance (AMC)</span></a></li>
                        </ul>
                    </li>
                    <li class="has-children">
                        <a href="<?php echo e(route('capabilities.index')); ?>">Solutions</a>
                        <ul class="sub-menu">
                            <li><a href="<?php echo e(route('capabilities.show', 'erp-solutions')); ?>"><span>ERP Solutions</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'crm-solutions')); ?>"><span>CRM Solutions</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'hrms-solutions')); ?>"><span>HRMS Solutions</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'ai-solutions')); ?>"><span>AI Solutions</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'cloud-solutions')); ?>"><span>Cloud Solutions</span></a></li>
                            <li><a href="<?php echo e(route('capabilities.show', 'cybersecurity-solutions')); ?>"><span>Security Solutions</span></a></li>
                        </ul>
                    </li>
                    <li class="has-children">
                        <a href="#">Resources</a>
                        <ul class="sub-menu">
                            <li><a href="<?php echo e(route('blog.index')); ?>"><span>Blog</span></a></li>
                            <li><a href="<?php echo e(route('technology-insights')); ?>"><span>Technology Insights</span></a></li>
                            <li><a href="<?php echo e(route('downloads')); ?>"><span>Downloads</span></a></li>
                            <li><a href="<?php echo e(route('faqs')); ?>"><span>FAQs</span></a></li>
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