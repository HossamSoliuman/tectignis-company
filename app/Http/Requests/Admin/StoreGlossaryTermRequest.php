<?php

namespace App\Http\Requests\Admin;

use App\Support\Locales;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGlossaryTermRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'locale' => ['required', 'string', Rule::in(Locales::prefixed())],
            'term' => ['required', 'string', 'max:255', Rule::unique('glossary_terms', 'term')->where('locale', $this->input('locale'))],
            'translation' => ['required', 'string', 'max:255'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'term.unique' => 'This term already has a translation in that language.',
        ];
    }
}
