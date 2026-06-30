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

            <div class="svc-card-grid">
                @foreach ($capabilities as $capability)
                    <x-public.svc-card
                        :href="route('capabilities.show', $capability->slug)"
                        :title="$capability->title"
                        :text="$capability->short_description"
                        :icon-image="$capability->icon" />
                @endforeach
            </div>
        </div>
    </div>
    <!--===========  feature-images-wrapper  End =============-->
@endsection
