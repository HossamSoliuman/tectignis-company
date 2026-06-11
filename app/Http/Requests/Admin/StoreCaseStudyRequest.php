<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCaseStudyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize the newline-separated "features" textarea into an array.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->features)) {
            $this->merge([
                'features' => array_values(array_filter(
                    array_map('trim', preg_split('/\r\n|\r|\n/', $this->features)),
                    fn (string $line): bool => $line !== '',
                )),
            ]);
        }
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:case_studies,slug'],
            'case_study_category_id' => ['nullable', 'integer', 'exists:case_study_categories,id'],
            'theme' => ['nullable', 'string', 'in:blue,red,purple'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'file', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
