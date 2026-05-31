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
                    @php
                        $projects = [
                            ['Perfect Packaging Solution', 'Modern Website Redesign', 'perfect-packaging-solution.webp', 'A modern, visually appealing website redesign for Perfect Packers.'],
                            ['Shree Vakratunda Saree Collection', 'Seamless E-Commerce Solution', 'shree-vakratunda.webp', 'A robust online platform to showcase and sell products effectively.'],
                            ['Mangosteen Homestays', 'A Digital Success Story', 'mangosteen.webp', 'A strong online presence to attract travelers and streamline bookings.'],
                        ];
                    @endphp
                    @foreach ($projects as [$title, $category, $image, $text])
                        <div class="col-lg-4 col-md-6 cat--2">
                            <a href="{{ route('case-studies.index') }}" class="projects-wrap style-01">
                                <div class="projects-image-box">
                                    <div class="projects-image">
                                        <img class="img-fluid" src="{{ asset('assets/images/projects/' . $image) }}"
                                            alt="{{ $title }}" loading="lazy">
                                    </div>
                                    <div class="content">
                                        <h6 class="heading">{{ $title }}</h6>
                                        <div class="post-categories">{{ $category }}</div>
                                        <div class="text">{{ $text }}</div>
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
