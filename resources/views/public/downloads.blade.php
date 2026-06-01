@extends('layouts.public')

@section('title', 'Free Downloads | Brochures, Case Studies & Checklists | Tectignis')

@section('seo')
    <meta name="description" content="Download free resources from Tectignis — company brochures, case studies, implementation checklists, and project templates to support your digital transformation journey.">
    <link rel="canonical" href="{{ route('downloads') }}">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb title="Downloads" :items="['Downloads' => null]" />
@endsection

@section('content')

    <div class="feature-images-wrapper section-space--ptb_100">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="section-title-wrap section-space--mb_60">
                        <h6 class="section-sub-title mb-20">Free Resources</h6>
                        <h3 class="heading">Downloads &amp; <span class="text-color-primary">Resources</span></h3>
                        <p class="mt-20">Access our library of brochures, case studies, checklists, and templates. Download resources and get expert guidance tailored to your business needs.</p>
                    </div>
                </div>
            </div>

            <!--=========== Brochures ===========-->
            <div class="row section-space--mb_60">
                <div class="col-lg-12">
                    <h4 class="heading section-space--mb_30">Brochures</h4>
                </div>
                @php
                    $brochures = [
                        ['title' => 'Company Profile', 'desc' => 'Learn about our software, AI, cloud and infrastructure services.', 'icon' => 'fas fa-building'],
                        ['title' => 'Software Services Brochure', 'desc' => 'Custom software, web, mobile app development capabilities.', 'icon' => 'fas fa-laptop-code'],
                        ['title' => 'AI Solutions Brochure', 'desc' => 'AI chatbots, automation, ML and intelligent process solutions.', 'icon' => 'fas fa-robot'],
                        ['title' => 'Cybersecurity Brochure', 'desc' => 'VAPT, firewall, compliance and managed security services.', 'icon' => 'fas fa-shield-alt'],
                        ['title' => 'Infrastructure Services Brochure', 'desc' => 'Cloud, networking, servers, storage and AMC services.', 'icon' => 'fas fa-server'],
                    ];
                @endphp
                @foreach ($brochures as $brochure)
                <div class="col-lg-4 col-md-6 section-space--mt_30 wow move-up">
                    <div class="ht-box-images style-03 p-30 h-100 d-flex flex-column">
                        <div class="image-box-wrap flex-grow-1">
                            <i class="{{ $brochure['icon'] }} fa-2x text-color-primary mb-20 d-block"></i>
                            <h6 class="heading mb-10">{{ $brochure['title'] }}</h6>
                            <p class="text">{{ $brochure['desc'] }}</p>
                        </div>
                        <div class="mt-20">
                            <button class="ht-btn ht-btn-md ht-btn--outline w-100" data-bs-toggle="modal" data-bs-target="#downloadModal" data-resource="{{ $brochure['title'] }}">
                                <i class="fas fa-download me-2"></i> Download PDF
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!--=========== Case Studies ===========-->
            <div class="row section-space--mb_60">
                <div class="col-lg-12">
                    <h4 class="heading section-space--mb_30">Case Studies</h4>
                </div>
                @php
                    $caseStudyDownloads = [
                        ['title' => 'Manufacturing ERP Case Study', 'icon' => 'fas fa-industry'],
                        ['title' => 'Healthcare Software Case Study', 'icon' => 'fas fa-heartbeat'],
                        ['title' => 'Mobile App Development Case Study', 'icon' => 'fas fa-mobile-alt'],
                        ['title' => 'Cloud Migration Case Study', 'icon' => 'fas fa-cloud'],
                        ['title' => 'Access Control Deployment Case Study', 'icon' => 'fas fa-lock'],
                    ];
                @endphp
                @foreach ($caseStudyDownloads as $item)
                <div class="col-lg-4 col-md-6 section-space--mt_30 wow move-up">
                    <div class="ht-box-images style-03 p-30 h-100 d-flex flex-column">
                        <div class="image-box-wrap flex-grow-1">
                            <i class="{{ $item['icon'] }} fa-2x text-color-primary mb-20 d-block"></i>
                            <h6 class="heading mb-10">{{ $item['title'] }}</h6>
                        </div>
                        <div class="mt-20">
                            <button class="ht-btn ht-btn-md ht-btn--outline w-100" data-bs-toggle="modal" data-bs-target="#downloadModal" data-resource="{{ $item['title'] }}">
                                <i class="fas fa-download me-2"></i> Download PDF
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!--=========== Checklists ===========-->
            <div class="row section-space--mb_60">
                <div class="col-lg-12">
                    <h4 class="heading section-space--mb_30">Checklists &amp; Templates</h4>
                </div>
                @php
                    $checklists = [
                        ['title' => 'Website Development Checklist', 'icon' => 'fas fa-check-square'],
                        ['title' => 'Cloud Migration Checklist', 'icon' => 'fas fa-check-square'],
                        ['title' => 'Cybersecurity Assessment Checklist', 'icon' => 'fas fa-check-square'],
                        ['title' => 'Digital Transformation Checklist', 'icon' => 'fas fa-check-square'],
                        ['title' => 'Project Requirement Template', 'icon' => 'fas fa-file-alt'],
                        ['title' => 'Software Requirement Specification (SRS)', 'icon' => 'fas fa-file-alt'],
                        ['title' => 'RFP Template', 'icon' => 'fas fa-file-alt'],
                        ['title' => 'IT Infrastructure Audit Template', 'icon' => 'fas fa-file-alt'],
                    ];
                @endphp
                @foreach ($checklists as $item)
                <div class="col-lg-3 col-md-4 col-sm-6 section-space--mt_30 wow move-up">
                    <div class="ht-box-images style-03 p-25 h-100 d-flex flex-column">
                        <div class="image-box-wrap flex-grow-1">
                            <i class="{{ $item['icon'] }} fa-lg text-color-primary mb-15 d-block"></i>
                            <h6 class="heading mb-0" style="font-size: 14px;">{{ $item['title'] }}</h6>
                        </div>
                        <div class="mt-15">
                            <button class="btn btn-sm w-100" data-bs-toggle="modal" data-bs-target="#downloadModal" data-resource="{{ $item['title'] }}">
                                Download
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

    <!--=========== Download Lead Capture Modal ===========-->
    <div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="downloadModalLabel">Download Resource</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="downloadResourceName" class="text-color-primary font-weight--bold mb-20"></p>
                    <p class="mb-20">Please fill in your details to access this resource.</p>
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="download">
                        <input type="hidden" name="subject" id="downloadSubjectField" value="">
                        <div class="mb-15">
                            <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                        </div>
                        <div class="mb-15">
                            <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                        </div>
                        <div class="mb-15">
                            <input type="tel" name="phone" class="form-control" placeholder="Phone Number" required>
                        </div>
                        <button type="submit" class="ht-btn ht-btn-md w-100">Download Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('downloadModal').addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var resource = button.getAttribute('data-resource');
            document.getElementById('downloadResourceName').textContent = resource;
            document.getElementById('downloadSubjectField').value = 'Download Request: ' + resource;
        });
    </script>
    @endpush

    <!--====================  CTA ====================-->
    <x-public.cta />
    <!--====================  End CTA ====================-->

@endsection
