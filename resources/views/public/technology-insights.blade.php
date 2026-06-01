@extends('layouts.public')

@section('title', 'Technology Insights | Software, AI & Cloud Expertise | Tectignis')

@section('seo')
    <meta name="description" content="Deep technical expertise from the Tectignis team — covering Software Architecture, AI & ML, Cloud Modernization, Security, and DevOps best practices for modern businesses.">
    <link rel="canonical" href="{{ route('technology-insights') }}">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb title="Technology Insights" :items="['Technology Insights' => null]" />
@endsection

@section('content')

    <!--=========== Page Intro ===========-->
    <div class="feature-images-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="section-title-wrap section-space--mb_60">
                        <h6 class="section-sub-title mb-20">Company Expertise</h6>
                        <h3 class="heading">Technology <span class="text-color-primary">Insights</span></h3>
                        <p class="mt-20">In-depth technical perspectives from the Tectignis engineering team — helping businesses make smarter technology decisions.</p>
                    </div>
                </div>
            </div>

            <!--=========== Categories ===========-->
            <div class="row row--30">

                <!-- Software -->
                <div class="col-lg-6 col-md-6 wow move-up section-space--mt_30">
                    <div class="ht-box-images style-03 p-30 h-100">
                        <div class="image-box-wrap">
                            <div class="box-image mb-20">
                                <i class="fas fa-code fa-3x text-color-primary"></i>
                            </div>
                            <div class="content">
                                <h5 class="heading">Software Engineering</h5>
                                <ul class="list-unstyled mt-15">
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>Software Architecture &amp; Design Patterns</li>
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>Microservices &amp; API Integrations</li>
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>DevOps Practices &amp; CI/CD Pipelines</li>
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>Performance Optimization</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI -->
                <div class="col-lg-6 col-md-6 wow move-up section-space--mt_30">
                    <div class="ht-box-images style-03 p-30 h-100">
                        <div class="image-box-wrap">
                            <div class="box-image mb-20">
                                <i class="fas fa-robot fa-3x text-color-primary"></i>
                            </div>
                            <div class="content">
                                <h5 class="heading">Artificial Intelligence</h5>
                                <ul class="list-unstyled mt-15">
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>Generative AI &amp; Large Language Models</li>
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>Machine Learning Trends &amp; Applications</li>
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>AI in Manufacturing &amp; Healthcare</li>
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>Intelligent Process Automation</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cloud -->
                <div class="col-lg-6 col-md-6 wow move-up section-space--mt_30">
                    <div class="ht-box-images style-03 p-30 h-100">
                        <div class="image-box-wrap">
                            <div class="box-image mb-20">
                                <i class="fas fa-cloud fa-3x text-color-primary"></i>
                            </div>
                            <div class="content">
                                <h5 class="heading">Cloud &amp; Infrastructure</h5>
                                <ul class="list-unstyled mt-15">
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>Cloud Modernization Strategies</li>
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>Hybrid Cloud &amp; Multi-Cloud Architectures</li>
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>Serverless Computing</li>
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>Cost Optimisation on AWS &amp; Azure</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div class="col-lg-6 col-md-6 wow move-up section-space--mt_30">
                    <div class="ht-box-images style-03 p-30 h-100">
                        <div class="image-box-wrap">
                            <div class="box-image mb-20">
                                <i class="fas fa-shield-alt fa-3x text-color-primary"></i>
                            </div>
                            <div class="content">
                                <h5 class="heading">Cybersecurity</h5>
                                <ul class="list-unstyled mt-15">
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>Zero Trust Security Model</li>
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>Endpoint Security &amp; SOC</li>
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>Compliance Standards (ISO, GDPR)</li>
                                    <li class="mb-10"><i class="fas fa-chevron-right text-color-primary me-2"></i>VAPT &amp; Penetration Testing</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!--=========== Example Insight ===========-->
            <div class="row section-space--mt_60">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h6 class="section-sub-title mb-20">Featured</h6>
                        <h3 class="heading">Example Technology <span class="text-color-primary">Insight</span></h3>
                    </div>
                </div>
                <div class="col-lg-8 offset-lg-2">
                    <div class="ht-box-images style-03 p-40">
                        <h4 class="heading mb-20">How AI is Transforming Manufacturing</h4>
                        <p class="mb-10"><strong>Author:</strong> Tectignis Team</p>
                        <hr>
                        <h6 class="mt-20 mb-10">Industry Impact</h6>
                        <p>AI-powered manufacturing systems are reducing downtime by up to 30% through predictive maintenance, while intelligent quality control systems detect defects with greater accuracy than traditional methods.</p>
                        <h6 class="mt-20 mb-10">Key Benefits</h6>
                        <ul class="check-list">
                            <li class="list-item">Predictive maintenance reduces unplanned downtime</li>
                            <li class="list-item">AI-driven quality control improves product consistency</li>
                            <li class="list-item">Supply chain optimization through demand forecasting</li>
                            <li class="list-item">Real-time production monitoring and analytics</li>
                        </ul>
                        <div class="button-group-wrap mt-30">
                            <a href="{{ route('contact') }}" class="ht-btn ht-btn-md">Talk To Our Expert</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!--====================  CTA ====================-->
    <x-public.cta />
    <!--====================  End CTA ====================-->

@endsection
