@extends('layouts.public')

@section('title', 'Contact Us | Tectignis IT Solutions - Navi Mumbai & PAN India')

@section('seo')
    <meta name="description" content="Have a question or need assistance? Contact Tectignis IT Solutions Pvt Ltd, a leading IT solutions provider in Navi Mumbai offering services PAN India.">
    <link rel="canonical" href="{{ route('contact') }}">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb title="Get In Touch" :items="['Contact us' => null]" />
@endsection

@section('content')
    <div class="feature-icon-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="feature-list__one">
                        <div class="row align-items-stretch">
                            <div class="col-lg-4 col-md-6 d-flex wow move-up">
                                <div class="ht-box-icon style-01 single-svg-icon-box contact-info-card contact-info-card--phone w-100">
                                    <div class="icon-box-wrap h-100">
                                        <div class="contact-info-card__icon-wrap">
                                            <i class="fas fa-phone-alt"></i>
                                        </div>
                                        <div class="content">
                                            <h5 class="heading">Phone</h5>
                                            <div class="text">Call us today to explore software, AI, cloud and IT solutions.</div>
                                            <div class="feature-btn">
                                                <a href="tel:+919987705688"><span class="button-text">+91 9987705688</span>
                                                    <i class="fas fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 d-flex wow move-up">
                                <div class="ht-box-icon style-01 single-svg-icon-box contact-info-card contact-info-card--address w-100">
                                    <div class="icon-box-wrap h-100">
                                        <div class="contact-info-card__icon-wrap">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="content">
                                            <h5 class="heading">Registered Address</h5>
                                            <div class="text">Aashiyana CHS Shop no 05, Sector 11, Plot no 29, Kamothe, Navi Mumbai - 410206</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 d-flex wow move-up">
                                <div class="ht-box-icon style-01 single-svg-icon-box contact-info-card contact-info-card--email w-100">
                                    <div class="icon-box-wrap h-100">
                                        <div class="contact-info-card__icon-wrap">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="content">
                                            <h5 class="heading">Email</h5>
                                            <div class="text">Email us your query today and our team will provide tailored solutions.</div>
                                            <div class="feature-btn">
                                                <a href="mailto:info@tectignis.in"><span class="button-text">info@tectignis.in</span>
                                                    <i class="fas fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="contact-us-area appointment-contact-bg section-space--pb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="contact-title section-space--mb_50">
                        <h3 class="mb-20">Get in touch with us.</h3>
                        <p class="sub-title">We'd love to hear from you</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="contact-info-three text-left">
                        <h6 class="heading font-weight--reguler">Reach out now!</h6>
                        <h3 class="call-us"><a href="tel:9987705688">(+91) 9987705688</a></h3>
                        <div class="text">Have a project in mind or need expert IT solutions? Reach out and our team will
                            connect with you to bring your ideas to life.</div>
                        <div class="location-button mt-20">
                            <a href="mailto:info@tectignis.in" class="location-text-button">
                                <span class="button-icon"></span>
                                <span class="button-text">info@tectignis.in</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="contact-form-wrap">
                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form class="contact-form" id="contact-form" action="{{ route('contact.submit') }}" method="post">
                            @csrf
                            <div class="contact-form__two">
                                <div class="contact-input">
                                    <div class="contact-inner">
                                        <input name="con_name" type="text" placeholder="Full Name *"
                                            value="{{ old('con_name') }}" required>
                                    </div>
                                    <div class="contact-inner">
                                        <input name="con_email" type="email" placeholder="Email *"
                                            value="{{ old('con_email') }}" required>
                                    </div>
                                </div>
                                <div class="contact-input">
                                    <div class="contact-inner">
                                        <input name="con_phone" type="text" placeholder="Phone Number *"
                                            value="{{ old('con_phone') }}" maxlength="15" required>
                                    </div>
                                    <div class="contact-inner">
                                        <input name="con_subject" type="text" placeholder="Subject *"
                                            value="{{ old('con_subject') }}" required>
                                    </div>
                                </div>
                                <div class="contact-inner contact-message">
                                    <textarea name="con_message" placeholder="Message" required>{{ old('con_message') }}</textarea>
                                </div>
                                <div class="comment-submit-btn">
                                    <button class="ht-btn ht-btn-md" type="submit">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-public.cta />
@endsection
