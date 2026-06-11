<?php

namespace App\Http\Requests\Public;

use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
            'con_name' => ['required', 'string', 'max:255'],
            'con_company' => ['nullable', 'string', 'max:255'],
            'con_email' => [$this->isConsultation() ? 'nullable' : 'required', 'email', 'max:255'],
            'con_phone' => [$this->isConsultation() ? 'required' : 'nullable', 'string', 'max:20'],
            'con_subject' => ['nullable', 'string', 'max:255'],
            'con_message' => ['required', 'string', 'max:5000'],
            'con_source' => ['nullable', 'in:contact,consultation'],
        ];
    }

    /**
     * Verify the reCAPTCHA token after field validation. Runs even when the
     * token is missing so bots cannot bypass the check by omitting the field.
     *
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                (new Recaptcha)->validate(
                    'g-recaptcha-response',
                    $this->input('g-recaptcha-response', ''),
                    fn (string $message) => $validator->errors()->add('g-recaptcha-response', $message),
                );
            },
        ];
    }

    /**
     * Whether the submission originates from the consultation modal,
     * where the mobile number is required and email is optional.
     */
    public function isConsultation(): bool
    {
        return $this->input('con_source') === 'consultation';
    }
}
