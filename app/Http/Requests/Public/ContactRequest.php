<?php

namespace App\Http\Requests\Public;

use App\Models\LandingPage;
use App\Rules\PhoneNumber;
use App\Rules\Recaptcha;
use App\Services\RecaptchaService;
use App\Support\Countries;
use App\Support\EnquiryServices;
use App\Support\PhoneNumbers;
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
            'phone' => ['nullable', 'string', 'max:25', new PhoneNumber($this->phoneCountry())],
            'phone_country' => ['nullable', 'string', Rule::in(Countries::isoCodes())],
            'service' => ['required', 'string', Rule::in(EnquiryServices::all())],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
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
            'message.min' => __('forms.messages.message_min'),
            'consent.accepted' => __('forms.messages.consent_accepted'),
        ];
    }

    /**
     * The validated phone number in E.164 form, e.g. "+447400123456".
     */
    public function phoneNumber(): ?string
    {
        $phone = $this->validated('phone');

        return filled($phone) ? PhoneNumbers::toE164($phone, $this->phoneCountry()) : null;
    }

    /**
     * The visitor's country, detected from their IP address rather than asked
     * for. Null when it can't be detected.
     */
    public function country(): ?string
    {
        $isoCode = Countries::detect($this);

        return $isoCode === null ? null : Countries::name($isoCode);
    }

    /**
     * The calling code's country picked beside the number, when it's one we list.
     */
    private function phoneCountry(): ?string
    {
        $isoCode = strtoupper((string) $this->input('phone_country'));

        return in_array($isoCode, Countries::isoCodes(), true) ? $isoCode : null;
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
