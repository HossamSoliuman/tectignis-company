@extends('layouts.public')

@section('title', 'Careers at Tectignis IT Solutions | Join Our IT Team in Navi Mumbai & PAN India')

@section('seo')
    <meta name="keywords" content="Careers, IT Jobs Navi Mumbai, Software Developer Jobs, Networking Jobs India, Digital Marketing Careers, IT Careers PAN India">
    <meta name="description" content="Explore exciting career opportunities at Tectignis IT Solutions Pvt Ltd. Join our innovative team based in Navi Mumbai, serving clients PAN India with top-tier IT solutions.">
    <link rel="canonical" href="{{ route('careers') }}">
@endsection

@php
    $careersHeroImage = \App\Models\Setting::get('careers_hero_image');
    $careerStats = [
        ['icon' => 'fas fa-users', 'value' => '200+', 'label' => 'Team Members'],
        ['icon' => 'fas fa-user-tie', 'value' => '50+', 'label' => 'Hiring Experts'],
        ['icon' => 'fas fa-award', 'value' => '10+', 'label' => 'Years of Excellence'],
        ['icon' => 'fas fa-smile', 'value' => '95%', 'label' => 'Employee Satisfaction'],
        ['icon' => 'fas fa-book-open', 'value' => 'Continuous', 'label' => 'Learning'],
        ['icon' => 'fas fa-balance-scale', 'value' => 'Work-Life', 'label' => 'Balance'],
    ];
    $careerBenefits = [
        ['icon' => 'fas fa-chart-line', 'title' => 'Growth', 'text' => 'Continuous learning opportunities and career advancement.'],
        ['icon' => 'fas fa-lightbulb', 'title' => 'Innovation', 'text' => 'Work on cutting-edge technologies and solve real-world challenges.'],
        ['icon' => 'fas fa-handshake', 'title' => 'Culture', 'text' => 'Inclusive, collaborative, and transparent work environment.'],
        ['icon' => 'fas fa-clock', 'title' => 'Flexibility', 'text' => 'Flexible work arrangements and work-life balance.'],
        ['icon' => 'fas fa-heart', 'title' => 'Wellness', 'text' => 'Health & wellness programs for a happy you.'],
        ['icon' => 'fas fa-gift', 'title' => 'Rewards', 'text' => 'Competitive salary, performance bonuses, and recognition.'],
    ];
    $experienceOptions = ['Fresher', '1–2 years', '3–5 years', '5–8 years', '8+ years'];
    $noticePeriodOptions = ['Immediate', '15 days', '30 days', '60 days', '90 days'];
    $applyModalOpen = $errors->any() && old('con_form') === 'career';
@endphp

@section('content')

    <!--=========== Careers Hero ===========-->
    <section class="car-hero">
        <span class="car-hero__blob" aria-hidden="true"></span>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <span class="car-hero__eyebrow">Careers</span>
                    <h1 class="car-hero__title">Build Your Career.<br><span>Shape the Future.</span></h1>
                    <p class="car-hero__intro">Join our team of innovators and problem solvers who are building smart solutions and transforming businesses around the world.</p>
                    <div class="car-hero__buttons">
                        <a href="#open-positions" class="svc-btn svc-btn--primary">View Open Positions <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                        <a href="#why-join" class="svc-btn svc-btn--ghost">Why Join Us? <i class="fas fa-arrow-down" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="col-lg-6 car-hero__media">
                    <div class="car-hero__photo {{ $careersHeroImage ? '' : 'car-hero__photo--placeholder' }}">
                        @if ($careersHeroImage)
                            <img src="{{ \App\Models\Setting::imageUrl($careersHeroImage, 'careers_hero_image') }}" alt="The Tectignis team" loading="eager">
                        @else
                            <i class="fas fa-users" aria-hidden="true"></i>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--=========== Stats Strip ===========-->
    <div class="car-stats">
        <div class="container">
            <div class="car-stats__grid">
                @foreach ($careerStats as $stat)
                    <div class="car-stat">
                        <div class="car-stat__icon"><i class="{{ $stat['icon'] }}" aria-hidden="true"></i></div>
                        <div class="car-stat__value">{{ $stat['value'] }}</div>
                        <div class="car-stat__label">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!--=========== Why Join ===========-->
    <div class="car-benefits" id="why-join">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="res-section-title">Why Join <span class="svc-accent">Tectignis</span></h2>
                <p class="res-section-sub mx-auto">We empower our people to grow, innovate, and make a real impact.</p>
            </div>
            <div class="row">
                @foreach ($careerBenefits as $benefit)
                    <div class="col-xl-2 col-md-4 col-6 mb-30 wow move-up">
                        <div class="car-benefit">
                            <div class="car-benefit__icon"><i class="{{ $benefit['icon'] }}" aria-hidden="true"></i></div>
                            <h6>{{ $benefit['title'] }}</h6>
                            <p>{{ $benefit['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!--=========== Open Positions ===========-->
    <div class="car-jobs" id="open-positions">
        <div class="container">
            <h2 class="res-section-title">Open Positions</h2>
            <p class="res-section-sub">Find the opportunity that's right for you.</p>

            @if (session('career_status'))
                <div class="alert alert-success mb-4">{{ session('career_status') }}</div>
            @endif

            @forelse ($jobOpenings as $job)
                <div class="car-job">
                    <div class="car-job__icon"><i class="{{ $job->icon ?: 'fas fa-briefcase' }}" aria-hidden="true"></i></div>
                    <div class="car-job__info">
                        <h3 class="car-job__title">{{ $job->title }}</h3>
                        @if ($job->department)
                            <span class="car-job__dept">{{ $job->department }}</span>
                        @endif
                    </div>
                    <div class="car-job__meta">
                        @if ($job->location)
                            <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $job->location }}</span>
                        @endif
                        <span><i class="far fa-clock" aria-hidden="true"></i> {{ $job->employment_type }}</span>
                    </div>
                    <button type="button" class="car-job__apply js-apply-open" data-position="{{ $job->title }}">
                        Apply Now <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </div>
            @empty
                <div class="res-empty">
                    <i class="fas fa-briefcase" aria-hidden="true"></i>
                    <p class="mb-0">There are no open positions right now — but we're always happy to hear from great people. Send us your resume below.</p>
                </div>
            @endforelse

            <!--=========== Journey CTA ===========-->
            <div class="car-cta">
                <div class="car-cta__icon"><i class="far fa-paper-plane" aria-hidden="true"></i></div>
                <div class="car-cta__content">
                    <h4>Start Your Journey With Us</h4>
                    <p>Be a part of a team that builds innovative solutions and drives real impact.</p>
                </div>
                <div class="car-cta__actions">
                    <a href="#open-positions" class="svc-btn svc-btn--ghost">View Open Positions</a>
                    <button type="button" class="svc-btn svc-btn--primary js-apply-open" data-position="">Send Your Resume <i class="fas fa-arrow-right" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!--=========== Application Modal ===========-->
    <div class="consult-modal" id="apply-modal" aria-hidden="{{ $applyModalOpen ? 'false' : 'true' }}" @if ($applyModalOpen) data-open="true" @endif>
        <div class="consult-modal__overlay" data-apply-close aria-hidden="true"></div>

        <div class="consult-modal__dialog car-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="apply-modal-title">
            <div class="consult-modal__header">
                <span class="consult-modal__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                </span>
                <div class="consult-modal__heading">
                    <h3 class="consult-modal__title" id="apply-modal-title" data-apply-title>Apply Now</h3>
                    <p class="consult-modal__desc">Fill in your details and our HR team will get in touch with you.</p>
                </div>
                <button type="button" class="consult-modal__close" data-apply-close aria-label="Close">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="consult-modal__body">
                @if ($applyModalOpen)
                    <div class="consult-modal__alert consult-modal__alert--error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="consult-form" action="{{ route('careers.submit') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="con_form" value="career">
                    <input type="hidden" name="con_position" value="{{ old('con_position') }}" data-apply-position-field>

                    <div class="car-form-grid">
                        <div class="consult-field">
                            <label for="apply_name">Full Name <span aria-hidden="true">*</span></label>
                            <input id="apply_name" name="con_name" type="text" placeholder="Enter your full name" value="{{ old('con_name') }}" required>
                        </div>
                        <div class="consult-field">
                            <label for="apply_email">Email Address <span aria-hidden="true">*</span></label>
                            <input id="apply_email" name="con_email" type="email" placeholder="Enter your email" value="{{ old('con_email') }}" required>
                        </div>
                        <div class="consult-field">
                            <label for="apply_phone">Phone Number <span aria-hidden="true">*</span></label>
                            <input id="apply_phone" name="con_phone" type="text" placeholder="Enter your phone number" value="{{ old('con_phone') }}" maxlength="15" required>
                        </div>
                        <div class="consult-field">
                            <label for="apply_experience">Total Experience</label>
                            <select id="apply_experience" name="con_experience">
                                <option value="" {{ old('con_experience') ? '' : 'selected' }}>Select experience</option>
                                @foreach ($experienceOptions as $option)
                                    <option value="{{ $option }}" @selected(old('con_experience') === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="consult-field">
                            <label for="apply_location">Current Location</label>
                            <input id="apply_location" name="con_location" type="text" placeholder="Enter your current location" value="{{ old('con_location') }}">
                        </div>
                        <div class="consult-field">
                            <label for="apply_notice">Notice Period</label>
                            <select id="apply_notice" name="con_notice_period">
                                <option value="" {{ old('con_notice_period') ? '' : 'selected' }}>Select notice period</option>
                                @foreach ($noticePeriodOptions as $option)
                                    <option value="{{ $option }}" @selected(old('con_notice_period') === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="consult-field car-form-grid__full">
                            <label>Resume / CV</label>
                            <div class="car-drop" data-apply-drop>
                                <input type="file" name="con_resume" accept=".pdf,.doc,.docx" data-apply-file>
                                <div class="car-drop__icon"><i class="fas fa-cloud-upload-alt" aria-hidden="true"></i></div>
                                <p class="car-drop__text">Drag &amp; drop your file here, or <span>browse files</span></p>
                                <p class="car-drop__hint">PDF, DOC, DOCX (Max. 5MB)</p>
                                <p class="car-drop__file" data-apply-filename hidden></p>
                            </div>
                        </div>

                        <div class="consult-field car-form-grid__full">
                            <label for="apply_message">Additional Message <span class="consult-field__optional">(Optional)</span></label>
                            <textarea id="apply_message" name="con_message" rows="3" placeholder="Tell us something about yourself">{{ old('con_message') }}</textarea>
                        </div>

                        <label class="car-consent car-form-grid__full">
                            <input type="checkbox" required>
                            <span>I agree to the <a href="{{ route('legal.show', 'privacy-policy') }}" target="_blank">Privacy Policy</a> and <a href="{{ route('legal.show', 'terms-and-conditions') }}" target="_blank">Terms &amp; Conditions</a>.</span>
                        </label>
                    </div>

                    <button type="submit" class="consult-form__submit">Submit Application</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            var modal = document.getElementById('apply-modal');
            if (!modal) { return; }

            var titleEl = modal.querySelector('[data-apply-title]');
            var positionField = modal.querySelector('[data-apply-position-field]');
            var fileInput = modal.querySelector('[data-apply-file]');
            var fileName = modal.querySelector('[data-apply-filename]');
            var dropZone = modal.querySelector('[data-apply-drop]');

            function openModal(position) {
                positionField.value = position || '';
                titleEl.textContent = position ? 'Apply for ' + position : 'Send Your Resume';
                modal.setAttribute('data-open', 'true');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                var firstField = modal.querySelector('input[type="text"]');
                if (firstField) { firstField.focus(); }
            }

            function closeModal() {
                modal.removeAttribute('data-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            document.querySelectorAll('.js-apply-open').forEach(function (trigger) {
                trigger.addEventListener('click', function () {
                    openModal(trigger.getAttribute('data-position'));
                });
            });

            modal.querySelectorAll('[data-apply-close]').forEach(function (el) {
                el.addEventListener('click', closeModal);
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.hasAttribute('data-open')) { closeModal(); }
            });

            if (modal.hasAttribute('data-open')) {
                document.body.style.overflow = 'hidden';
                var oldPosition = positionField.value;
                titleEl.textContent = oldPosition ? 'Apply for ' + oldPosition : 'Send Your Resume';
            }

            // Resume drop zone feedback
            if (fileInput) {
                fileInput.addEventListener('change', function () {
                    var hasFile = fileInput.files.length > 0;
                    fileName.hidden = !hasFile;
                    fileName.textContent = hasFile ? fileInput.files[0].name : '';
                });
                ['dragenter', 'dragover'].forEach(function (evt) {
                    dropZone.addEventListener(evt, function () { dropZone.classList.add('is-dragover'); });
                });
                ['dragleave', 'drop'].forEach(function (evt) {
                    dropZone.addEventListener(evt, function () { dropZone.classList.remove('is-dragover'); });
                });
            }
        })();
    </script>
    @endpush

@endsection
