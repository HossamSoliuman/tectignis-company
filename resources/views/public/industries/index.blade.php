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
            <div class="svc-card-grid">
                @foreach ($industries as $industry)
                    <x-public.svc-card
                        :href="route('industries.show', $industry->slug)"
                        :title="$industry->name"
                        :text="$industry->short_description"
                        :icon-class="$industry->icon ?: 'fas fa-building'" />
                @endforeach
            </div>
        </div>
    </div>
    <!--===========  Industries We Serve End =============-->
@endsection
