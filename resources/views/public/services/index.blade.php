@extends('layouts.public')

@section('title', 'Our Services | Tectignis IT Solutions')

@section('seo')
    <meta name="description" content="Explore the full range of IT services offered by Tectignis IT Solutions — software development, networking, security, cloud, and digital marketing across Navi Mumbai & PAN India.">
    <link rel="canonical" href="{{ route('services.index') }}">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb title="Services" :items="['Services' => null]" />
@endsection

@section('content')
    <!--===========  feature-images-wrapper  Start =============-->
    <div class="feature-images-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center">
                        <h3 class="heading">Comprehensive <span class="text-color-primary">IT services</span> tailored to your business.</h3>
                    </div>
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
                                                <a href="{{ route('services.show', $service->slug) }}">
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
