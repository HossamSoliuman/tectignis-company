@extends('layouts.public')

@section('title', 'Solutions We Deliver | Tectignis IT Solutions')

@section('seo')
    <meta name="description" content="Business-focused solutions from Tectignis IT Solutions — ERP, CRM, HRMS, AI, Cloud, Cybersecurity, Automation and Smart Security tailored to your industry.">
    <link rel="canonical" href="{{ route('solutions.index') }}">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb title="Solutions" :items="['Solutions' => null]" />
@endsection

@section('content')
    <!--===========  Solutions We Deliver Start =============-->
    <div class="feature-images-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">Business-Focused</h6>
                        <h3 class="heading">Solutions We <span class="text-color-primary">Deliver</span></h3>
                    </div>
                </div>
            </div>
            <div class="row row--30">
                @foreach ($solutions as $solution)
                <div class="col-lg-3 col-md-4 col-sm-6 wow move-up section-space--mt_30">
                    <a href="{{ route('solutions.show', $solution->slug) }}" class="ht-box-images style-04 d-block text-center p-4 h-100">
                        <div class="image-box-wrap">
                            <div class="box-image mb-20">
                                <i class="{{ $solution->icon }} fa-3x text-color-primary"></i>
                            </div>
                            <div class="content">
                                <h6 class="heading">{{ $solution->title }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!--===========  Solutions We Deliver End =============-->
@endsection
