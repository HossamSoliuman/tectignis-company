<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * The reviewed translations from the import preview, `values[id][locale]`.
 * Each value is checked again against the export before it is saved.
 */
class ConfirmTranslationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'export_id' => ['required', 'string', 'exists:translation_exports,export_id'],
            'values' => ['required', 'array'],
            'values.*' => ['array'],
            'values.*.*' => ['nullable', 'string', 'max:65535'],
        ];
    }
}
