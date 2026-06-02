@extends('layouts.public')

@section('title', 'Industries We Serve | Tectignis IT Solutions')

@section('seo')
    <meta name="description" content="Tectignis IT Solutions delivers technology expertise across Manufacturing, Healthcare, Education, Retail, Real Estate, Logistics, Hospitality and Corporate sectors.">
    <link rel="canonical" href="{{ route('industries.index') }}">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb title="Industries" :items="['Industries' => null]" />
@endsection

@section('content')
    <!--===========  Industries We Serve Start =============-->
    <div class="feature-images-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">Sector Expertise</h6>
                        <h3 class="heading">Industries We <span class="text-color-primary">Serve</span></h3>
                    </div>
                </div>
            </div>
            <div class="row row--30">
                @foreach ($industries as $industry)
                <div class="col-lg-3 col-md-4 col-sm-6 wow move-up section-space--mt_30">
                    <a href="{{ route('industries.show', $industry->slug) }}" class="ht-box-images style-03 d-block text-center p-4 h-100">
                        <div class="image-box-wrap">
                            <div class="box-image mb-20">
                                <i class="{{ $industry->icon }} fa-2x text-color-primary"></i>
                            </div>
                            <div class="content">
                                <h6 class="heading">{{ $industry->name }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!--===========  Industries We Serve End =============-->
@endsection
