@props([
    'designations',
    'jobOpening' => null,
    'idPrefix' => 'apply',
])

@php
    // Errors live in their own bag so the enquiry forms on the page stay untouched.
    $applicationErrors = $errors->getBag('application');
    $selectedJob = old('job_opening_id', $jobOpening?->id);
    $field = fn (string $name): string => $idPrefix.'_'.$name;
@endphp

@if ($applicationErrors->any())
    <div class="consult-modal__alert consult-modal__alert--error" role="alert">
        <ul>
            @foreach ($applicationErrors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($designations->isEmpty())
    <div class="res-empty car-apply-empty">
        <i class="fas fa-user-clock" aria-hidden="true"></i>
        <p class="mb-0">No positions or designations are available for applications right now. Please check back soon.</p>
    </div>
@else
    <form {{ $attributes->merge(['class' => 'consult-form']) }} action="{{ route('careers.submit') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="job_opening_id" value="{{ $selectedJob }}" data-apply-job-field>

        <div class="car-form-grid">
            <div class="consult-field">
                <label for="{{ $field('name') }}">Full Name <span aria-hidden="true">*</span></label>
                <input id="{{ $field('name') }}" name="name" type="text" placeholder="Enter your full name" value="{{ old('name') }}" maxlength="255" autocomplete="name" required>
            </div>
            <div class="consult-field">
                <label for="{{ $field('email') }}">Email Address <span aria-hidden="true">*</span></label>
                <input id="{{ $field('email') }}" name="email" type="email" placeholder="Enter your email" value="{{ old('email') }}" maxlength="255" autocomplete="email" required>
            </div>
            <div class="consult-field">
                <label for="{{ $field('phone') }}">Phone Number <span aria-hidden="true">*</span></label>
                <input id="{{ $field('phone') }}" name="phone" type="tel" placeholder="Enter your phone number" value="{{ old('phone') }}" maxlength="25" autocomplete="tel" required>
            </div>
            <div class="consult-field">
                <label for="{{ $field('location') }}">Current Location <span aria-hidden="true">*</span></label>
                <input id="{{ $field('location') }}" name="location" type="text" placeholder="City, country" value="{{ old('location') }}" maxlength="255" required>
            </div>
            <div class="consult-field">
                <label for="{{ $field('designation') }}">Designation <span aria-hidden="true">*</span></label>
                <select id="{{ $field('designation') }}" name="designation_id" required>
                    <option value="" @selected(! old('designation_id'))>Select designation</option>
                    @foreach ($designations as $designation)
                        <option value="{{ $designation->id }}" @selected((string) old('designation_id') === (string) $designation->id)>{{ $designation->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="consult-field">
                <label for="{{ $field('experience') }}">Total Experience <span aria-hidden="true">*</span></label>
                <select id="{{ $field('experience') }}" name="experience" required>
                    <option value="" @selected(! old('experience'))>Select experience</option>
                    @foreach (\App\Enums\CandidateExperience::options() as $value => $label)
                        <option value="{{ $value }}" @selected(old('experience') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="consult-field">
                <label for="{{ $field('current_role') }}">Current Role <span class="consult-field__optional">(Optional)</span></label>
                <input id="{{ $field('current_role') }}" name="current_role" type="text" placeholder="e.g. Software Engineer at Acme" value="{{ old('current_role') }}" maxlength="255">
            </div>
            <div class="consult-field">
                <label for="{{ $field('notice_period') }}">Notice Period <span class="consult-field__optional">(Optional)</span></label>
                <select id="{{ $field('notice_period') }}" name="notice_period">
                    <option value="" @selected(! old('notice_period'))>Select notice period</option>
                    @foreach (\App\Models\JobApplication::NOTICE_PERIODS as $option)
                        <option value="{{ $option }}" @selected(old('notice_period') === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div class="consult-field">
                <label for="{{ $field('linkedin') }}">LinkedIn <span class="consult-field__optional">(Optional)</span></label>
                <input id="{{ $field('linkedin') }}" name="linkedin_url" type="url" placeholder="https://linkedin.com/in/…" value="{{ old('linkedin_url') }}" maxlength="500">
            </div>
            <div class="consult-field">
                <label for="{{ $field('portfolio') }}">Portfolio / GitHub <span class="consult-field__optional">(Optional)</span></label>
                <input id="{{ $field('portfolio') }}" name="portfolio_url" type="url" placeholder="https://github.com/…" value="{{ old('portfolio_url') }}" maxlength="500">
            </div>

            <div class="consult-field car-form-grid__full">
                <label for="{{ $field('resume') }}">Resume / CV <span aria-hidden="true">*</span></label>
                <div class="car-drop" data-apply-drop>
                    <input id="{{ $field('resume') }}" type="file" name="resume" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required data-apply-file>
                    <div class="car-drop__icon"><i class="fas fa-cloud-upload-alt" aria-hidden="true"></i></div>
                    <p class="car-drop__text">Drag &amp; drop your file here, or <span>browse files</span></p>
                    <p class="car-drop__hint">PDF, DOC, DOCX (Max. 5MB)</p>
                    <p class="car-drop__file" data-apply-filename hidden></p>
                </div>
            </div>

            <div class="consult-field car-form-grid__full">
                <label for="{{ $field('cover_letter') }}">Cover Letter <span class="consult-field__optional">(Optional)</span></label>
                <textarea id="{{ $field('cover_letter') }}" name="cover_letter" rows="3" maxlength="5000" placeholder="Tell us about yourself and why you'd like to join">{{ old('cover_letter') }}</textarea>
            </div>

            <label class="car-consent car-form-grid__full">
                <input type="checkbox" name="consent" value="1" @checked(old('consent')) required>
                <span>I agree to the <a href="{{ localized_route('legal.show', 'privacy-policy') }}" target="_blank">Privacy Policy</a> and consent to Tectignis storing my application and CV for recruitment purposes.</span>
            </label>
        </div>

        <x-public.recaptcha />

        <button type="submit" class="consult-form__submit">Submit Application</button>
    </form>

    @pushOnce('scripts')
        <script>
            // Resume drop zone: show the chosen file name and a drag highlight.
            document.querySelectorAll('[data-apply-drop]').forEach(function (dropZone) {
                var fileInput = dropZone.querySelector('[data-apply-file]');
                var fileName = dropZone.querySelector('[data-apply-filename]');

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
            });
        </script>
    @endPushOnce
@endif
