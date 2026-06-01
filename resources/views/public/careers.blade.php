@extends('layouts.public')

@section('title', 'Careers at Tectignis IT Solutions | Join Our IT Team in Navi Mumbai & PAN India')

@section('seo')
    <meta name="keywords" content="Careers, IT Jobs Navi Mumbai, Software Developer Jobs, Networking Jobs India, Digital Marketing Careers, IT Careers PAN India">
    <meta name="description" content="Explore exciting career opportunities at Tectignis IT Solutions Pvt Ltd. Join our innovative team based in Navi Mumbai, serving clients PAN India with top-tier IT solutions.">
    <link rel="canonical" href="{{ route('careers') }}">
@endsection

@section('breadcrumb')
    <x-public.breadcrumb title="Careers" :items="['Careers' => null]" />
@endsection

@section('content')
    <!--======== careers-experts Start ==========-->
    <div class="contact-us-area service-contact-bg section-space--ptb_100">
        <div class="container pb-5">
            <div class="row align-items-center">
                <div class="row">
                    <div class="col-lg-7 m-auto">
                        <div class="section-title-wrap text-center section-space--mb_30">
                            <h3 class="heading text-light">Become a part of our big family to inspire and get inspired by
                                professional experts.</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 m-auto">
                    <div class="contact-form-service-wrap">
                        <div class="contact-title text-center section-space--mb_40">
                            <h3 class="mb-10">Need a hand?</h3>
                            <p class="text">Reach out to the world's most reliable IT services.</p>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success text-center mb-20">{{ session('status') }}</div>
                        @endif

                        <form class="contact-form" action="{{ route('careers.submit') }}" method="post">
                            @csrf
                            <div class="contact-form__two">
                                <div class="contact-input">
                                    <div class="contact-inner">
                                        <label>Enter Your Full Name *</label>
                                        <input name="con_name" type="text" value="{{ old('con_name') }}"
                                            placeholder="Full name" required>
                                        @error('con_name')
                                            <span style="color:red;font-size:13px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="contact-inner">
                                        <label>Enter Your Email *</label>
                                        <input name="con_email" type="email" value="{{ old('con_email') }}"
                                            placeholder="Email address" required>
                                        @error('con_email')
                                            <span style="color:red;font-size:13px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="contact-inner">
                                    <label>Enter Your Phone No. *</label>
                                    <input name="con_phone" type="text" maxlength="10"
                                        value="{{ old('con_phone') }}" placeholder="Phone number" required>
                                    @error('con_phone')
                                        <span style="color:red;font-size:13px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="contact-inner">
                                    <label>Tell us about yourself</label>
                                    <textarea name="con_message" rows="4"
                                        placeholder="Briefly describe your experience and the role you're interested in...">{{ old('con_message') }}</textarea>
                                </div>
                                <div class="comment-submit-btn">
                                    <button class="ht-btn ht-btn-md" type="submit">Submit Application</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--======== careers-experts End ==========-->

    <div class="contact-us-section-wrappaer infotechno-contact-us-bg section-space--ptb_120">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="conact-us-wrap-one">
                        <h3 class="heading">Obtaining further information by
                            <span class="text-color-primary">make a contact</span> with our experienced IT staffs.
                        </h3>
                        <div class="sub-heading">We're available for 8 hours a day!<br>Contact to require a detailed
                            analysis and assessment of your plan.</div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-info-one text-center">
                        <div class="icon">
                            <span class="fas fa-phone"></span>
                        </div>
                        <h6 class="heading font-weight--reguler">Reach out now!</h6>
                        <h2 class="call-us"><a href="tel:+919987705688">+91 9987705688</a></h2>
                        <div class="contact-us-button mt-20">
                            <a href="{{ route('contact') }}" class="btn btn--secondary">Contact us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
