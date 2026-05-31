@extends('layouts.public')

@section('title', 'Our Capabilities | Software, AI, Cloud & Security - Tectignis')

@section('seo')
    <meta name="description" content="Explore the full range of Tectignis capabilities: software development, mobile apps, AI & automation, cloud, cybersecurity and smart security systems.">
    <link rel="canonical" href="{{ route('capabilities.index') }}">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb title="Our Capabilities" :items="['Capabilities' => null]" />
@endsection

@section('content')
    <div class="service-grid-area section-space--ptb_100">
        <div class="container">
            <div class="feature-images__six">
                <div class="row">
                    @php
                        $capabilities = [
                            ['Software-Development.webp', 'Software Development', 'Custom software, web apps, ERP, CRM and SaaS platforms built for scale.'],
                            ['mobile-app-development.webp', 'Mobile App Development', 'Native and cross-platform Android & iOS apps with Flutter and React Native.'],
                            ['customize-software-development.webp', 'AI & Automation', 'AI chatbots, WhatsApp automation, OCR, machine learning and voice bots.'],
                            ['Network-Storage-Solutions.webp', 'Cloud & Infrastructure', 'AWS, Azure, Google Cloud, migration and managed hosting.'],
                            ['cctv-com.webp', 'Cybersecurity', 'VAPT, firewall security, SOC support and compliance.'],
                            ['access-control-system.webp', 'Smart Security', 'CCTV, access control, visitor management and biometrics.'],
                            ['Networking-Solutions.webp', 'Networking Solutions', 'Structured cabling, secure networks and server setup.'],
                            ['workstation-solutions.webp', 'Workstation Solutions', 'High-performance workstations and managed hardware.'],
                            ['annual-maintenance-com.webp', 'Annual Maintenance (AMC)', 'Hardware & software AMC to keep your systems running 24/7.'],
                        ];
                    @endphp
                    @foreach ($capabilities as [$icon, $title, $text])
                        <div class="col-lg-4 col-md-6 wow move-up">
                            <div class="ht-box-images style-06">
                                <div class="image-box-wrap">
                                    <div class="box-image">
                                        <div class="default-image">
                                            <img class="img-fulid" src="{{ asset('assets/images/icons/' . $icon) }}"
                                                alt="{{ $title }}" loading="lazy">
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h5 class="heading">{{ $title }}</h5>
                                        <div class="text">{{ $text }}</div>
                                        <a href="{{ route('contact') }}" class="box-images-arrow">
                                            <span class="button-text">Talk to an expert</span>
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <x-public.cta />
@endsection
