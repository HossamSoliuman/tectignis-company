@props([
    'formId',
    'source' => 'contact',
    'variant' => 'page',
    'subject' => null,
    'submitLabel' => null,
    'service' => null,
    'landingPage' => null,
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
    // Landing pages ask for a phone number instead of the company and project
    // description (see ContactRequest::isFromLandingPage).
    $isLandingPage = filled($landingPage);
    $requiredMark = '<span aria-hidden="true">*</span>';
    $optionalMark = '<span class="consult-field__optional">'.e(__('forms.optional')).'</span>';
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

<form {{ $attributes->merge(['class' => $s['form']]) }} action="{{ localized_route('contact.submit') }}" method="post">
    @csrf
    <input type="hidden" name="form_id" value="{{ $formId }}">
    <input type="hidden" name="source" value="{{ $source }}">
    <input type="hidden" name="page_url" value="{{ url()->full() }}">
    @if ($subject)
        <input type="hidden" name="subject" value="{{ $subject }}">
    @endif
    @if ($landingPage)
        <input type="hidden" name="landing_page" value="{{ $landingPage }}">
    @endif

    <div class="{{ $s['grid'] }}">
        <div class="{{ $s['field'] }}">
            <label for="{{ $id('name') }}" class="{{ $s['label'] }}">{{ __('forms.name') }} <span aria-hidden="true">*</span></label>
            <input id="{{ $id('name') }}" class="{{ $s['control'] }}" name="name" type="text" autocomplete="name"
                placeholder="{{ $placeholder(__('forms.name_placeholder')) }}" value="{{ $value('name') }}" maxlength="255" required>
        </div>

        <div class="{{ $s['field'] }}">
            <label for="{{ $id('email') }}" class="{{ $s['label'] }}">{{ __('forms.email') }} <span aria-hidden="true">*</span></label>
            <input id="{{ $id('email') }}" class="{{ $s['control'] }}" name="email" type="email" autocomplete="email"
                placeholder="{{ $placeholder(__('forms.email_placeholder')) }}" value="{{ $value('email') }}" maxlength="255" required>
        </div>

        <div class="{{ $s['field'] }}">
            <label for="{{ $id('phone') }}" class="{{ $s['label'] }}">{{ __('forms.phone') }} {!! $isLandingPage ? $requiredMark : $optionalMark !!}</label>
            <x-public.phone-input :id="$id('phone')" :variant="$variant" :input-class="$s['control']"
                :value="$value('phone')" :country="$value('phone_country')" :required="$isLandingPage"
                :placeholder="$variant === 'service' ? __('forms.phone_placeholder') : null" />
        </div>

        <div class="{{ $s['field'] }} enquiry-form__pair">
            <label for="{{ $id('company') }}" class="{{ $s['label'] }}">{{ __('forms.company') }} {!! $isLandingPage ? $optionalMark : $requiredMark !!}</label>
            <input id="{{ $id('company') }}" class="{{ $s['control'] }}" name="company" type="text" autocomplete="organization"
                placeholder="{{ $placeholder(__('forms.company_placeholder')) }}" value="{{ $value('company') }}" maxlength="255" @required(! $isLandingPage)>
        </div>

        <div class="{{ $fullField }} enquiry-form__pair">
            <label for="{{ $id('service') }}" class="{{ $s['label'] }}">{{ __('forms.service') }} <span aria-hidden="true">*</span></label>
            <select id="{{ $id('service') }}" class="{{ $s['control'] }}" name="service" required>
                <option value="" @selected(! $value('service', $service))>{{ $variant === 'service' ? __('forms.service_placeholder') : __('forms.service_choose') }}</option>
                @foreach (\App\Support\EnquiryServices::grouped() as $group => $serviceOptions)
                    <optgroup label="{{ $group }}">
                        @foreach ($serviceOptions as $serviceOption)
                            <option value="{{ $serviceOption }}" @selected($value('service', $service) === $serviceOption)>{{ \App\Support\EnquiryServices::label($serviceOption) }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        <div class="{{ $fullField }}">
            <label for="{{ $id('message') }}" class="{{ $s['label'] }}">{{ __('forms.message') }} {!! $isLandingPage ? $optionalMark : $requiredMark !!}</label>
            <textarea id="{{ $id('message') }}" class="{{ $s['control'] }} {{ $variant === 'service' ? 'svc-input--area' : '' }}" name="message" rows="4"
                placeholder="{{ $variant === 'service' ? __('forms.message_placeholder') : __('forms.message_hint') }}"
                @unless ($isLandingPage) minlength="20" @endunless maxlength="5000" @required(! $isLandingPage)>{{ $value('message') }}</textarea>
        </div>

        <div class="{{ $fullField }}">
            <label class="enquiry-consent">
                <input type="checkbox" name="consent" value="1" @checked($value('consent')) required>
                <span>{!! __('forms.consent', ['link' => '<a href="'.e(localized_route('legal.show', 'privacy-policy')).'" target="_blank" rel="noopener">'.e(__('forms.privacy_policy')).'</a>']) !!} <span aria-hidden="true">*</span></span>
            </label>
        </div>

        <div class="{{ $fullField }} enquiry-form__captcha">
            <x-public.recaptcha />
        </div>

        <div class="{{ $s['full'] }} {{ $variant === 'service' ? '' : 'enquiry-form__actions' }}">
            <button type="submit" class="{{ $s['submit'] }}">{{ $submitLabel ?? __('forms.send_enquiry') }} <span class="tx-flip-rtl" aria-hidden="true">→</span></button>
        </div>
    </div>
</form>
