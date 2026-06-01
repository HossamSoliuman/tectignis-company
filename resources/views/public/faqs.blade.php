@extends('layouts.public')

@section('title', 'Frequently Asked Questions | Software, AI, Cloud & Security | Tectignis')

@section('seo')
    <meta name="description" content="Find answers to common questions about software development, mobile apps, AI solutions, cloud services, cybersecurity and CCTV from Tectignis IT Solutions.">
    <link rel="canonical" href="{{ route('faqs') }}">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb title="FAQs" :items="['FAQs' => null]" />
@endsection

@section('content')

    <div class="feature-images-wrapper section-space--ptb_100">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="section-title-wrap section-space--mb_60">
                        <h6 class="section-sub-title mb-20">Got Questions?</h6>
                        <h3 class="heading">Frequently Asked <span class="text-color-primary">Questions</span></h3>
                        <p class="mt-20">Everything you need to know about our services. Can't find an answer? <a href="{{ route('contact') }}">Contact us</a> directly.</p>
                    </div>
                </div>
            </div>

            @php
                $faqCategories = [
                    [
                        'title' => 'Software Development',
                        'icon' => 'fas fa-laptop-code',
                        'faqs' => [
                            ['q' => 'How much does software development cost?', 'a' => 'Software development costs vary based on project complexity, features, and technology stack. We offer competitive pricing with transparent quotes. Contact us for a free consultation and detailed estimate tailored to your requirements.'],
                            ['q' => 'How long does a software project take?', 'a' => 'Project timelines depend on scope and complexity. A simple web application may take 4–8 weeks, while a complex ERP or enterprise system can take 4–12 months. We provide detailed timelines during the planning phase.'],
                            ['q' => 'Do you provide source code?', 'a' => 'Yes, upon project completion and full payment, we transfer complete source code ownership to the client. All intellectual property rights are yours.'],
                        ],
                    ],
                    [
                        'title' => 'Mobile Apps',
                        'icon' => 'fas fa-mobile-alt',
                        'faqs' => [
                            ['q' => 'Do you build for Android or iOS?', 'a' => 'We develop for both Android and iOS, including hybrid/cross-platform apps using Flutter and React Native, giving you maximum reach with a single codebase.'],
                            ['q' => 'Can you publish the app on Play Store and App Store?', 'a' => 'Yes, we handle the complete submission process for Google Play Store and Apple App Store, including compliance, assets, and metadata preparation.'],
                            ['q' => 'Do you provide post-launch maintenance?', 'a' => 'Absolutely. We offer flexible AMC (Annual Maintenance Contract) plans covering bug fixes, updates, security patches, and feature enhancements.'],
                        ],
                    ],
                    [
                        'title' => 'AI & Automation',
                        'icon' => 'fas fa-robot',
                        'faqs' => [
                            ['q' => 'Can AI be integrated with our existing ERP?', 'a' => 'Yes. We specialize in integrating AI capabilities — such as predictive analytics, intelligent reporting, and process automation — into existing ERP and business systems.'],
                            ['q' => 'Do your AI chatbots support WhatsApp?', 'a' => 'Yes, we build WhatsApp-integrated AI chatbots using the WhatsApp Business API for automated customer support, lead generation, and business workflows.'],
                        ],
                    ],
                    [
                        'title' => 'Cloud Services',
                        'icon' => 'fas fa-cloud',
                        'faqs' => [
                            ['q' => 'Do you provide AWS support?', 'a' => 'Yes, we are experienced with AWS, Azure, and Google Cloud. We offer cloud architecture, deployment, migration, managed services, and cost optimization.'],
                            ['q' => 'Can you migrate our existing on-premise servers to cloud?', 'a' => 'Yes, we provide end-to-end cloud migration services — assessment, planning, migration, testing, and post-migration support — with minimal business disruption.'],
                        ],
                    ],
                    [
                        'title' => 'Cybersecurity',
                        'icon' => 'fas fa-shield-alt',
                        'faqs' => [
                            ['q' => 'What is VAPT?', 'a' => 'VAPT stands for Vulnerability Assessment and Penetration Testing. It is a security testing methodology that identifies weaknesses in your systems before attackers can exploit them. We provide comprehensive VAPT reports with remediation guidance.'],
                            ['q' => 'Do you provide cybersecurity audits?', 'a' => 'Yes, we conduct thorough security audits covering network infrastructure, applications, and policies, delivering detailed reports and actionable recommendations.'],
                        ],
                    ],
                    [
                        'title' => 'CCTV & Access Control',
                        'icon' => 'fas fa-video',
                        'faqs' => [
                            ['q' => 'Which CCTV system is best for my business?', 'a' => 'The best system depends on your premises size, coverage requirements, and budget. We conduct a site survey and recommend IP/HD CCTV systems from leading brands with the right resolution, storage, and remote access capabilities.'],
                            ['q' => 'Can CCTV be monitored remotely?', 'a' => 'Yes, all our CCTV solutions support remote monitoring via mobile apps and web browsers, allowing you to view live and recorded footage from anywhere in the world.'],
                            ['q' => 'Do you provide Annual Maintenance Contracts (AMC)?', 'a' => 'Yes, we offer comprehensive AMC plans for CCTV, access control, networking, and IT infrastructure — ensuring ongoing performance, support, and preventive maintenance.'],
                        ],
                    ],
                ];
            @endphp

            @foreach ($faqCategories as $category)
            <div class="row section-space--mb_60">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center section-space--mb_30">
                        <i class="{{ $category['icon'] }} fa-2x text-color-primary me-3"></i>
                        <h4 class="heading mb-0">{{ $category['title'] }}</h4>
                    </div>
                    <div class="accordion" id="faq-{{ Str::slug($category['title']) }}">
                        @foreach ($category['faqs'] as $index => $faq)
                        <div class="accordion-item mb-10">
                            <h2 class="accordion-header" id="heading-{{ Str::slug($category['title']) }}-{{ $index }}">
                                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ Str::slug($category['title']) }}-{{ $index }}">
                                    {{ $faq['q'] }}
                                </button>
                            </h2>
                            <div id="collapse-{{ Str::slug($category['title']) }}-{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#faq-{{ Str::slug($category['title']) }}">
                                <div class="accordion-body">
                                    {{ $faq['a'] }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

            <!--=========== Still have questions CTA ===========-->
            <div class="row section-space--mt_60">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="ht-box-images style-03 p-40">
                        <h4 class="heading mb-20">Still Have Questions?</h4>
                        <p class="mb-30">Our team is ready to answer any questions you have about our services, pricing, or project timelines.</p>
                        <a href="{{ route('contact') }}" class="ht-btn ht-btn-md">Contact Us</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
