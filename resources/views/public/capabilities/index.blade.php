@extends('layouts.public')

@section('title', 'Our Expertise | Leading IT Solutions Provider in Navi Mumbai & PAN India')

@section('seo')
    <meta name="keywords" content="Expertise in IT Services, Custom Software Development, Network Security, Digital Marketing Services, Web Development Navi Mumbai, IT Solutions PAN India, IT Expertise in Navi Mumbai, Software Development Mumbai, Digital Marketing India, IT Solutions PAN India, Networking Services India, Web App Development India">
    <meta name="description" content="Explore the expertise of Tectignis IT Solutions Pvt Ltd. We provide world-class IT solutions with specialized skills in software development, networking, and security, serving Navi Mumbai & PAN India.">
    <link rel="canonical" href="{{ route('capabilities.index') }}">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb title="IT Services" :items="['IT Services' => null]" />
@endsection

@section('content')
    <!--===========  feature-images-wrapper  Start =============-->
    <div class="feature-images-wrapper section-space--ptb_100">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <!-- section-title-wrap Start -->
                    <div class="section-title-wrap text-center">
                        <h3 class="heading">Preparing for your success, <br> we provide <span class="text-color-primary"> truly prominent IT solutions.</span></h3>
                    </div>
                    <!-- section-title-wrap Start -->
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="feature-images__one">
                        <div class="row">
                            @foreach ($services as $service)
                            <div class="col-lg-4 col-md-6 wow move-up">
                                <!-- ht-box-icon Start -->
                                <div class="ht-box-images style-01">
                                    <div class="image-box-wrap">
                                        <div class="box-image">
                                            <img class="img-fulid" src="{{ asset('uploads/'.$service->icon) }}" alt="{{ $service->title }}" loading="lazy">
                                        </div>
                                        <div class="content">
                                            <h5 class="heading">{{ $service->title }}</h5>
                                            <div class="text">{{ $service->short_description }}</div>
                                            <div class="circle-arrow">
                                                <div class="middle-dot"></div>
                                                <div class="middle-dot dot-2"></div>
                                                <a href="{{ route('capabilities.show', $service->slug) }}">
                                                    <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ht-box-icon End -->
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--===========  feature-images-wrapper  End =============-->
@endsection
