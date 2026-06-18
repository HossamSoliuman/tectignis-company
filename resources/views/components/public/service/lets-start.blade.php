@props(['service'])

@php
    $section = $service->content['lets_start'] ?? [];
    $heading = $section['heading'] ?? "Let's Start Something Great";
    $subtitle = $section['subtitle'] ?? 'Get In Touch';
    $text = $section['text'] ?? 'Tell us about your project and our team will get back to you within one business day with the next steps.';
    $phone = \App\Models\Setting::get('contact_phone') ?: '+91 9987705688';
    $email = \App\Models\Setting::get('contact_email') ?: 'info@tectignis.in';
    $address = \App\Models\Setting::get('contact_address') ?: 'Aashiyana CHS, Sector 11, Plot 29, Kamothe, Navi Mumbai - 410206';
@endphp

<section class="svc-section svc-start">
    <div class="container">
        <div class="svc-start__card">
            <div class="row g-0">
                <div class="col-lg-5 svc-start__info wow move-up">
                    <span class="svc-eyebrow">{{ $subtitle }}</span>
                    <h2 class="svc-start__title">{{ $heading }}</h2>
                    <p class="svc-start__text">{{ $text }}</p>

                    <ul class="svc-start__contacts">
                        <li>
                            <span class="svc-start__contact-icon"><i class="fas fa-phone-alt"></i></span>
                            <div>
                                <span class="svc-start__contact-label">Call us</span>
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">{{ $phone }}</a>
                            </div>
                        </li>
                        <li>
                            <span class="svc-start__contact-icon"><i class="fas fa-envelope"></i></span>
                            <div>
                                <span class="svc-start__contact-label">Email us</span>
                                <a href="mailto:{{ $email }}">{{ $email }}</a>
                            </div>
                        </li>
                        <li>
                            <span class="svc-start__contact-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <div>
                                <span class="svc-start__contact-label">Visit us</span>
                                <span>{{ $address }}</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-7 svc-start__form-col wow move-up">
                    <div class="svc-start__form">
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
                        <form action="{{ route('contact.submit') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <input class="svc-input" name="con_name" type="text" placeholder="Full Name *"
                                        value="{{ old('con_name') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <input class="svc-input" name="con_email" type="email" placeholder="Email *"
                                        value="{{ old('con_email') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <input class="svc-input" name="con_phone" type="text" placeholder="Phone Number *"
                                        value="{{ old('con_phone') }}" maxlength="20" required>
                                </div>
                                <div class="col-md-6">
                                    <input class="svc-input" name="con_subject" type="text" placeholder="Subject *"
                                        value="{{ old('con_subject', $service->title.' Enquiry') }}" required>
                                </div>
                                <div class="col-12">
                                    <textarea class="svc-input svc-input--area" name="con_message" rows="4"
                                        placeholder="Tell us about your project *" required>{{ old('con_message') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <button class="svc-btn svc-btn--primary svc-btn--block" type="submit">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
