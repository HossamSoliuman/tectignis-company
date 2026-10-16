<?php

namespace App\Http\Requests\Public;

use App\Enums\LeadBudget;
use App\Enums\LeadTimeline;
use App\Models\LandingPage;
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
     * Prefix a national phone number with the dialling code picked beside it,
     * dropping the trunk "0" (07400… → +44 7400…) that only applies in-country.
     * Numbers already in international form are kept as typed.
     */
    protected function prepareForValidation(): void
    {
        $phone = trim((string) $this->input('phone'));
        $dialCode = Countries::dialCode((string) $this->input('phone_country'));

        if (str_starts_with($phone, '00')) {
            $phone = '+'.substr($phone, 2);
        }

        if ($phone !== '' && $dialCode !== null && ! str_starts_with($phone, '+')) {
            // Italy, the Vatican and San Marino keep the leading 0 after the country code.
            $national = in_array($dialCode, ['+39', '+378'], true) ? $phone : ltrim($phone, '0');
            $phone = $dialCode.' '.$national;
        }

        $this->merge(['phone' => $phone !== '' ? $phone : null]);
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
            'phone_country' => ['nullable', 'string', Rule::in(Countries::isoCodes())],
            'service' => ['required', 'string', Rule::in(EnquiryServices::all())],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
            'budget' => ['nullable', Rule::enum(LeadBudget::class)],
            'timeline' => ['nullable', Rule::enum(LeadTimeline::class)],
            'consent' => ['accepted'],
            'source' => ['nullable', 'in:contact,consultation'],
            'subject' => ['nullable', 'string', 'max:255'],
            'form_id' => ['nullable', 'string', 'max:50'],
            'page_url' => ['nullable', 'url', 'max:2048'],
            'landing_page' => ['nullable', 'string', 'max:255'],
            RecaptchaService::RESPONSE_FIELD => [new Recaptcha],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'email' => __('forms.attributes.email'),
            'company' => __('forms.attributes.company'),
            'phone' => __('forms.attributes.phone'),
            'service' => __('forms.attributes.service'),
            'message' => __('forms.attributes.message'),
            'consent' => __('forms.attributes.consent'),
            RecaptchaService::RESPONSE_FIELD => __('forms.attributes.captcha'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.regex' => __('forms.messages.phone_regex'),
            'message.min' => __('forms.messages.message_min'),
            'consent.accepted' => __('forms.messages.consent_accepted'),
        ];
    }

    /**
     * Whether the submission came from the consultation/quote modal.
     */
    public function isConsultation(): bool
    {
        return $this->input('source') === 'consultation';
    }

    /**
     * Slug of the active landing page the form was submitted from. An unknown
     * value is dropped rather than rejected, so it can never block a lead.
     */
    public function landingPageSlug(): ?string
    {
        $slug = $this->validated('landing_page');

        return filled($slug) ? LandingPage::where('slug', $slug)->value('slug') : null;
    }
}
