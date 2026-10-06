@props([
    'formId',
    'source' => 'contact',
    'variant' => 'page',
    'subject' => null,
    'submitLabel' => 'Send Enquiry',
])

@php
    // One form, three hosts (contact page, consultation modal, service pages),
    // each with its own existing styling.
    $styles = [
        'page' => ['form' => 'contact-form enquiry-form', 'grid' => 'contact-form__grid', 'field' => 'contact-field', 'full' => 'contact-field--full', 'control' => '', 'submit' => 'about-btn about-btn--primary contact-form__submit', 'label' => ''],
        'modal' => ['form' => 'consult-form enquiry-form', 'grid' => 'enquiry-form__grid', 'field' => 'consult-field', 'full' => 'enquiry-form__full', 'control' => '', 'submit' => 'consult-form__submit', 'label' => ''],
        'service' => ['form' => 'enquiry-form enquiry-form--service', 'grid' => 'row', 'field' => 'col-md-6 enquiry-form__cell', 'full' => 'col-12', 'control' => 'svc-input', 'submit' => 'svc-btn svc-btn--primary svc-btn--block', 'label' => 'sr-only'],
    ];
    $s = $styles[$variant] ?? $styles['page'];
    // Bootstrap columns (service variant) must not mix the half and full widths.
    $fullField = $variant === 'service' ? $s['full'] : $s['field'].' '.$s['full'];

    // Only refill fields (and show errors) for the form that was submitted —
    // the consultation modal shares every page with the inline forms.
    $isActive = old('form_id') === $formId;
    $value = fn (string $field, mixed $default = null): mixed => $isActive ? old($field, $default) : $default;
    $id = fn (string $field): string => $formId.'-'.$field;
    $placeholder = fn (string $text): string => $variant === 'service' ? $text : '';
@endphp

@if ($isActive && $errors->any())
    <div class="{{ $variant === 'modal' ? 'consult-modal__alert consult-modal__alert--error' : 'alert alert-danger' }}" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form {{ $attributes->merge(['class' => $s['form']]) }} action="{{ route('contact.submit') }}" method="post">
    @csrf
    <input type="hidden" name="form_id" value="{{ $formId }}">
    <input type="hidden" name="source" value="{{ $source }}">
    <input type="hidden" name="page_url" value="{{ url()->full() }}">
    @if ($subject)
        <input type="hidden" name="subject" value="{{ $subject }}">
    @endif

    <div class="{{ $s['grid'] }}">
        <div class="{{ $s['field'] }}">
            <label for="{{ $id('name') }}" class="{{ $s['label'] }}">Name <span aria-hidden="true">*</span></label>
            <input id="{{ $id('name') }}" class="{{ $s['control'] }}" name="name" type="text" autocomplete="name"
                placeholder="{{ $placeholder('Name *') }}" value="{{ $value('name') }}" maxlength="255" required>
        </div>

        <div class="{{ $s['field'] }}">
            <label for="{{ $id('email') }}" class="{{ $s['label'] }}">Business Email <span aria-hidden="true">*</span></label>
            <input id="{{ $id('email') }}" class="{{ $s['control'] }}" name="email" type="email" autocomplete="email"
                placeholder="{{ $placeholder('Business Email *') }}" value="{{ $value('email') }}" maxlength="255" required>
        </div>

        <div class="{{ $s['field'] }}">
            <label for="{{ $id('phone') }}" class="{{ $s['label'] }}">Phone / WhatsApp <span class="consult-field__optional">(Optional)</span></label>
            <x-public.phone-input :id="$id('phone')" :variant="$variant" :input-class="$s['control']"
                :value="$value('phone')" :country="$value('phone_country')"
                :placeholder="$variant === 'service' ? 'Phone / WhatsApp (optional)' : null" />
        </div>

        <div class="{{ $s['field'] }}">
            <label for="{{ $id('company') }}" class="{{ $s['label'] }}">Company <span aria-hidden="true">*</span></label>
            <input id="{{ $id('company') }}" class="{{ $s['control'] }}" name="company" type="text" autocomplete="organization"
                placeholder="{{ $placeholder('Company *') }}" value="{{ $value('company') }}" maxlength="255" required>
        </div>

        <div class="{{ $s['field'] }}">
            <label for="{{ $id('service') }}" class="{{ $s['label'] }}">Service Required <span aria-hidden="true">*</span></label>
            <select id="{{ $id('service') }}" class="{{ $s['control'] }}" name="service" required>
                <option value="" @selected(! $value('service'))>{{ $variant === 'service' ? 'Service Required *' : 'Choose a service' }}</option>
                @foreach (\App\Support\EnquiryServices::grouped() as $group => $services)
                    <optgroup label="{{ $group }}">
                        @foreach ($services as $service)
                            <option value="{{ $service }}" @selected($value('service') === $service)>{{ $service }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        <div class="{{ $s['field'] }}">
            <label for="{{ $id('country') }}" class="{{ $s['label'] }}">Country <span aria-hidden="true">*</span></label>
            <select id="{{ $id('country') }}" class="{{ $s['control'] }}" name="country" autocomplete="country-name" required>
                <option value="" @selected(! $value('country'))>{{ $variant === 'service' ? 'Country *' : 'Select your country' }}</option>
                <optgroup label="Priority markets">
                    @foreach (\App\Support\Countries::PRIORITY as $country)
                        <option value="{{ $country }}" @selected($value('country') === $country)>{{ $country }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="All countries">
                    @foreach (\App\Support\Countries::others() as $country)
                        <option value="{{ $country }}" @selected($value('country') === $country)>{{ $country }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>

        <div class="{{ $fullField }}">
            <label for="{{ $id('message') }}" class="{{ $s['label'] }}">Project Description <span aria-hidden="true">*</span></label>
            <textarea id="{{ $id('message') }}" class="{{ $s['control'] }} {{ $variant === 'service' ? 'svc-input--area' : '' }}" name="message" rows="4"
                placeholder="{{ $variant === 'service' ? 'Project Description *' : 'Goals, scope, current systems and anything else we should know…' }}"
                minlength="20" maxlength="5000" required>{{ $value('message') }}</textarea>
        </div>

        <div class="{{ $s['field'] }} enquiry-form__pair">
            <label for="{{ $id('budget') }}" class="{{ $s['label'] }}">Budget Range <span class="consult-field__optional">(Optional)</span></label>
            <select id="{{ $id('budget') }}" class="{{ $s['control'] }}" name="budget">
                <option value="" @selected(! $value('budget'))>{{ $variant === 'service' ? 'Budget Range (optional)' : 'Select a range' }}</option>
                @foreach (\App\Enums\LeadBudget::options() as $optionValue => $label)
                    <option value="{{ $optionValue }}" @selected($value('budget') === $optionValue)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="{{ $s['field'] }} enquiry-form__pair">
            <label for="{{ $id('timeline') }}" class="{{ $s['label'] }}">Timeline <span class="consult-field__optional">(Optional)</span></label>
            <select id="{{ $id('timeline') }}" class="{{ $s['control'] }}" name="timeline">
                <option value="" @selected(! $value('timeline'))>{{ $variant === 'service' ? 'Timeline (optional)' : 'When do you want to start?' }}</option>
                @foreach (\App\Enums\LeadTimeline::options() as $optionValue => $label)
                    <option value="{{ $optionValue }}" @selected($value('timeline') === $optionValue)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="{{ $fullField }}">
            <label class="enquiry-consent">
                <input type="checkbox" name="consent" value="1" @checked($value('consent')) required>
                <span>I agree to the <a href="{{ route('legal.show', 'privacy-policy') }}" target="_blank" rel="noopener">Privacy Policy</a> and to Tectignis contacting me about this enquiry. <span aria-hidden="true">*</span></span>
            </label>
        </div>

        <div class="{{ $fullField }} enquiry-form__captcha">
            <x-public.recaptcha />
        </div>

        <div class="{{ $s['full'] }} {{ $variant === 'service' ? '' : 'enquiry-form__actions' }}">
            <button type="submit" class="{{ $s['submit'] }}">{{ $submitLabel }} <span aria-hidden="true">→</span></button>
        </div>
    </div>
</form>
