<?php

namespace App\Http\Requests\Admin\Concerns;

trait ValidatesProductData
{
    /**
     * Turn the one-per-line features textarea into an array.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('features'))) {
            $this->merge([
                'features' => array_values(array_filter(
                    array_map('trim', preg_split('/\r\n|\r|\n/', $this->input('features'))),
                    fn (string $line): bool => $line !== '',
                )),
            ]);
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function productRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'features' => ['nullable', 'array', 'max:6'],
            'features.*' => ['string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'url' => ['nullable', 'url', 'max:255'],
            'case_study_id' => ['nullable', 'integer', 'exists:case_studies,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
