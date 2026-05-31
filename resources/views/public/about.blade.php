@extends('layouts.public')

@section('title', 'About Us | Leading IT Solutions in Navi Mumbai & PAN India')

@section('seo')
    <meta name="description" content="Explore the expertise of Tectignis IT Solutions Pvt Ltd. World-class software development, AI, cloud, networking and security, serving Navi Mumbai & PAN India.">
    <link rel="canonical" href="{{ route('about') }}">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb title="About Tectignis" :items="['About Us' => null]" />
@endsection

@section('content')
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
                                    <img class="img-fluid" src="{{ asset('assets/images/bg/IT-Solutions-PAN-India.webp') }}"
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
                            @foreach ([['about-icon-001.webp', 'What we can do?', 'We focus on the needs of your business to find solutions that best fit your demand and nail it.'], ['about-icon-002.webp', 'Become our partner?', 'Our preventive and progressive approach helps you take the lead while addressing possible threats.'], ['about-icon-003.webp', 'Need a hand?', 'Our support team is here to proactively address and resolve any challenges you encounter.']] as [$icon, $title, $text])
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
                                                    <span class="button-text">Connect with us</span>
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
        </div>
    </div>

    <div class="fun-fact-wrapper bg-theme-default section-space--pb_30 section-space--pt_60">
        <div class="container">
            <div class="row">
                @foreach ([['500', 'Happy clients'], ['350', 'Projects Delivered'], ['1M', 'Growth'], ['1K', 'Active Users']] as [$count, $label])
                    <div class="col-md-3 col-sm-6 wow move-up">
                        <div class="fun-fact--two text-center">
                            <div class="fun-fact__count">{{ $count }}</div>
                            <h6 class="fun-fact__text">{{ $label }}</h6>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <x-public.testimonials />
    <x-public.brands />
@endsection
