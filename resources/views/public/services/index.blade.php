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
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h3 class="heading">Comprehensive <span class="text-color-primary">IT services</span> tailored to your business.</h3>
                    </div>
                </div>
            </div>

            @foreach ($grouped as $key => $group)
                <div class="row {{ ! $loop->first ? 'section-space--mt_60' : '' }}">
                    <div class="col-lg-12">
                        <div class="section-title-wrap text-center section-space--mb_30">
                            <h4 class="heading">{{ $categoryLabels[$key] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="feature-images__one">
                            <div class="row">
                                @foreach ($group as $service)
                                    @include('public.services._card', ['service' => $service])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($ungrouped->isNotEmpty())
                <div class="row {{ $grouped->isNotEmpty() ? 'section-space--mt_60' : '' }}">
                    <div class="col-lg-12">
                        <div class="section-title-wrap text-center section-space--mb_30">
                            <h4 class="heading">Other Services</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="feature-images__one">
                            <div class="row">
                                @foreach ($ungrouped as $service)
                                    @include('public.services._card', ['service' => $service])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!--===========  feature-images-wrapper  End =============-->
@endsection
