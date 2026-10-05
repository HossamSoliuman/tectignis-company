<?php

namespace App\Http\Requests\Public;

use App\Enums\LeadBudget;
use App\Enums\LeadTimeline;
use App\Rules\Recaptcha;
use App\Services\RecaptchaService;
use App\Support\Countries;
use App\Support\EnquiryServices;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * The project enquiry form (spec §11.2), shared by the contact page, the
 * consultation/quote modal and service pages.
 */
class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', Rule::in(Countries::all())],
            'phone' => ['nullable', 'string', 'max:25', 'regex:/^\+?[0-9][0-9\s().-]{5,23}$/'],
            'service' => ['required', 'string', Rule::in(EnquiryServices::all())],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
            'budget' => ['nullable', Rule::enum(LeadBudget::class)],
            'timeline' => ['nullable', Rule::enum(LeadTimeline::class)],
            'consent' => ['accepted'],
            'source' => ['nullable', 'in:contact,consultation'],
            'subject' => ['nullable', 'string', 'max:255'],
            'form_id' => ['nullable', 'string', 'max:50'],
            'page_url' => ['nullable', 'url', 'max:2048'],
            RecaptchaService::RESPONSE_FIELD => [new Recaptcha],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'email' => 'business email',
            'company' => 'company',
            'phone' => 'phone / WhatsApp number',
            'service' => 'service required',
            'message' => 'project description',
            'consent' => 'privacy consent',
            RecaptchaService::RESPONSE_FIELD => 'CAPTCHA',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.regex' => 'Please enter the phone number in international format, e.g. +91 98765 43210.',
            'message.min' => 'Please tell us a little more about your project (at least :min characters).',
            'consent.accepted' => 'Please agree to the privacy policy so we can respond to your enquiry.',
        ];
    }

    /**
     * Whether the submission came from the consultation/quote modal.
     */
    public function isConsultation(): bool
    {
        return $this->input('source') === 'consultation';
    }
}
