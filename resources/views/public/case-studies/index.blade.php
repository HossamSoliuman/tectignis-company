@extends('layouts.public')

@section('title', 'Case Studies | Successful IT Solutions - Tectignis')

@section('seo')
    <meta name="description" content="Discover our successful case studies. Tectignis delivers measurable results with software, AI, cloud and security solutions for businesses across India.">
    <link rel="canonical" href="{{ route('case-studies.index') }}">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb title="Case Studies" :items="['Case Studies' => null]" />
@endsection

@section('content')
    <div class="projects-wrapper section-space--ptb_100 projects-masonary-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h3 class="heading">Proud projects that <span class="text-color-primary">make us stand out</span></h3>
                    </div>
                </div>
            </div>

            <div class="projects-case-wrap">
                <div class="row mesonry-list">
                    @foreach ($caseStudies as $caseStudy)
                        <div class="col-lg-4 col-md-6 cat--2">
                            <a href="{{ route('case-studies.index') }}" class="projects-wrap style-01">
                                <div class="projects-image-box">
                                    <div class="projects-image">
                                        @if ($caseStudy->image)
                                            <img class="img-fluid" src="{{ asset('uploads/'.$caseStudy->image) }}"
                                                alt="{{ $caseStudy->title }}" loading="lazy">
                                        @endif
                                    </div>
                                    <div class="content">
                                        <h6 class="heading">{{ $caseStudy->title }}</h6>
                                        <div class="post-categories">{{ $caseStudy->category?->name }}</div>
                                        <div class="text">{{ $caseStudy->short_description }}</div>
                                        <div class="box-projects-arrow">
                                            <span class="button-text">View case study</span>
                                            <i class="fas fa-arrow-right ml-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <x-public.cta />
@endsection
