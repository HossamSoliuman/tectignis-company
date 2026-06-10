<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
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
     * Whether the submission originates from the consultation modal,
     * where the mobile number is required and email is optional.
     */
    public function isConsultation(): bool
    {
        return $this->input('con_source') === 'consultation';
    }
}
