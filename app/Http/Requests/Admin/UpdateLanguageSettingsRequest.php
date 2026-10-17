<?php

namespace App\Http\Requests\Admin;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLanguageSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'translated_services' => ['nullable', 'array'],
            'translated_services.*' => ['string', Rule::in(Service::active()->pluck('slug'))],
            'detect_browser' => ['nullable', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'translated_services.*.in' => 'Only active services can be translated.',
        ];
    }
}
