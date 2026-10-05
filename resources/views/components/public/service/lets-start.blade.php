@props(['service'])

@php
    $section = $service->content['lets_start'] ?? [];
    $heading = $section['heading'] ?? "Let's Start Something Great";
    $subtitle = $section['subtitle'] ?? 'Get In Touch';
    $text = $section['text'] ?? 'Tell us about your project and our team will get back to you within one business day with the next steps.';
    $company ??= app(\App\Support\CompanyProfile::class);
    $phone = $company->phone();
    $email = $company->email();
    $address = $company->address();
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
                        <x-public.enquiry-form form-id="service-enquiry" source="contact" variant="service"
                            :subject="$service->title.' Enquiry'" submit-label="Send Enquiry" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
