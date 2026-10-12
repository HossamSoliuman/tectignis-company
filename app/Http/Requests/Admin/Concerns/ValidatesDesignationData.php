<?php

namespace App\Http\Requests\Admin\Concerns;

use App\Models\Designation;
use Illuminate\Validation\Rule;

trait ValidatesDesignationData
{
    /**
     * Rules shared by the store and update designation requests (spec §28.7).
     *
     * @return array<string, array<int, mixed>>
     */
    protected function designationRules(?Designation $designation = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('designations', 'name')->ignore($designation)],
            'department' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Collapse stray whitespace so "Senior  Developer " cannot slip past the
     * duplicate check.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['name' => str((string) $this->input('name'))->squish()->toString()]);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'A designation with this name already exists.',
        ];
    }
}
